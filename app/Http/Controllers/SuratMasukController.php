<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Http\Requests\StoreSuratMasukRequest;
use App\Models\SuratMasuk;
use App\Models\User;
use App\Services\SuratMasukService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SuratMasukController extends Controller
{
    /**
     * Menampilkan daftar dan filter surat masuk sesuai hak akses pengguna.
     */
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', SuratMasuk::class);

        $user = $request->user();
        $query = SuratMasuk::with(['creator', 'penerima', 'disposisi.toUser', 'disposisi.keUser'])->latest('tanggal_diterima');

        // Batasi akses query untuk Guru & Tenaga Pendidik
        if ($user->hasRole(Role::GURU, Role::TENAGA_PENDIDIK)) {
            $query->where(function ($q) use ($user) {
                $q->whereHas('penerima', function ($p) use ($user) {
                    $p->where('users.id', $user->id);
                })->orWhereHas('disposisi', function ($d) use ($user) {
                    $d->where('ke_user_id', $user->id)->orWhere('to_user_id', $user->id);
                });
            });
        }

        // Filter status
        if ($request->filled('status') && $request->status !== 'semua') {
            $query->where('status', $request->status);
        }

        // Filter kategori
        if ($request->filled('kategori') && $request->kategori !== 'semua') {
            $query->where('kategori', $request->kategori);
        }

        // Filter asal surat (eksternal / internal)
        if ($request->filled('asal') && $request->asal !== 'semua') {
            $query->where('asal', $request->asal);
        }

        // Pencarian (nomor agenda, nomor surat, pengirim, perihal, ringkasan)
        if ($request->filled('q')) {
            $search = '%'.$request->q.'%';
            $query->where(function ($q) use ($search) {
                $q->where('nomor_agenda', 'like', $search)
                    ->orWhere('nomor_surat', 'like', $search)
                    ->orWhere('nomor_surat_asli', 'like', $search)
                    ->orWhere('pengirim', 'like', $search)
                    ->orWhere('perihal', 'like', $search)
                    ->orWhere('ringkasan', 'like', $search);
            });
        }

        $suratList = $query->paginate(15)->withQueryString();

        // Daftar target user untuk modal disposisi (Guru, TU, Tendik)
        $assignableUsers = User::whereIn('role', [Role::GURU, Role::TATA_USAHA, Role::TENAGA_PENDIDIK])
            ->orderBy('name')
            ->get();

        // Daftar calon penerima saat registrasi surat (default Kepala Sekolah)
        $recipientCandidates = User::whereIn('role', [Role::KEPALA_SEKOLAH, Role::GURU, Role::TATA_USAHA, Role::TENAGA_PENDIDIK])
            ->orderByRaw("CASE WHEN role = 'kepala_sekolah' THEN 1 ELSE 2 END, name ASC")
            ->get();

        // Metrik statistik (dihitung berbasis hak akses pengguna)
        $statsBaseQuery = clone $query;
        $stats = [
            'total' => (clone $query)->count(),
            'bulan_ini' => (clone $query)
                ->whereMonth('tanggal_diterima', now()->month)
                ->whereYear('tanggal_diterima', now()->year)
                ->count(),
            'perlu_disposisi' => (clone $query)->where('status', 'diterima')->orWhere('status', 'Diterima')->count(),
            'arsip_terakhir' => SuratMasuk::latest('id')->value('nomor_agenda') ?? '-',
        ];

        return view('dashboard.surat-masuk.index', compact('suratList', 'assignableUsers', 'recipientCandidates', 'stats'));
    }

    /**
     * Menyimpan registrasi surat masuk baru beserta berkas fisik privat dan baris audit trail.
     */
    public function store(StoreSuratMasukRequest $request, SuratMasukService $service): RedirectResponse
    {
        $validated = $request->validated();
        $penerimaIds = (array) ($request->input('penerima_ids') ?? []);

        $surat = $service->register(
            $validated,
            $request->file('berkas'),
            $request->user(),
            $penerimaIds
        );

        return redirect()->route('surat-masuk.index')
            ->with('status', 'Surat masuk berhasil dicatat dengan nomor agenda '.$surat->nomor_agenda);
    }

    /**
     * Menampilkan detail surat masuk, daftar penerima, dan riwayat status (audit trail).
     */
    public function show(SuratMasuk $suratMasuk): View
    {
        Gate::authorize('view', $suratMasuk);

        // Jika user adalah penerima yang belum tercatat dibaca_pada, catat timestamp pembacaan
        $user = auth()->user();
        if ($user) {
            $pivot = $suratMasuk->penerima()->where('user_id', $user->id)->first();
            if ($pivot && is_null($pivot->pivot->dibaca_pada)) {
                $suratMasuk->penerima()->updateExistingPivot($user->id, ['dibaca_pada' => Carbon::now()]);
            }
        }

        $suratMasuk->load([
            'creator',
            'pencatat',
            'penerima',
            'disposisi.dariUser',
            'disposisi.keUser',
            'disposisi.fromUser',
            'disposisi.toUser',
            'riwayatStatus.user',
        ]);

        $assignableUsers = User::whereIn('role', [Role::GURU, Role::TATA_USAHA, Role::TENAGA_PENDIDIK])
            ->orderBy('name')
            ->get();

        return view('dashboard.surat-masuk.show', compact('suratMasuk', 'assignableUsers'));
    }

    /**
     * Mengunduh berkas lampiran privat melalui proteksi Policy.
     */
    public function download(SuratMasuk $suratMasuk): StreamedResponse
    {
        Gate::authorize('download', $suratMasuk);

        if (! $suratMasuk->file_path || ! Storage::disk('local')->exists($suratMasuk->file_path)) {
            abort(404, 'Berkas fisik naskah dinas tidak ditemukan di sistem.');
        }

        $downloadName = $suratMasuk->file_name ?? 'berkas-surat-'.$suratMasuk->nomor_agenda.'.pdf';

        return Storage::disk('local')->download($suratMasuk->file_path, $downloadName);
    }

    /**
     * Menampilkan lembar disposisi resmi siap cetak format kedinasan SDN Jatipulo 07 Jakarta.
     */
    public function lembarDisposisi(SuratMasuk $suratMasuk): View
    {
        Gate::authorize('view', $suratMasuk);

        $suratMasuk->load([
            'creator',
            'penerima',
            'disposisi.dariUser',
            'disposisi.keUser',
            'disposisi.fromUser',
            'disposisi.toUser',
        ]);

        return view('dashboard.surat-masuk.lembar-disposisi', compact('suratMasuk'));
    }
}
