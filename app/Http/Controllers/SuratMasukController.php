<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSuratMasukRequest;
use App\Models\SuratMasuk;
use App\Models\User;
use App\Services\SuratMasukService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SuratMasukController extends Controller
{
    /**
     * Menampilkan daftar dan filter surat masuk.
     */
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', SuratMasuk::class);

        $query = SuratMasuk::with(['creator', 'disposisi.toUser'])->latest();

        // Filter status
        if ($request->filled('status') && $request->status !== 'semua') {
            $query->where('status', $request->status);
        }

        // Filter kategori
        if ($request->filled('kategori') && $request->kategori !== 'semua') {
            $query->where('kategori', $request->kategori);
        }

        // Pencarian (nomor surat, pengirim, perihal)
        if ($request->filled('q')) {
            $search = '%'.$request->q.'%';
            $query->where(function ($q) use ($search) {
                $q->where('nomor_agenda', 'like', $search)
                    ->orWhere('nomor_surat_asli', 'like', $search)
                    ->orWhere('pengirim', 'like', $search)
                    ->orWhere('perihal', 'like', $search);
            });
        }

        $suratList = $query->paginate(15)->withQueryString();

        // Daftar target user untuk modal disposisi (Guru, TU, Tendik)
        $assignableUsers = User::whereIn('role', ['guru', 'tata_usaha', 'tenaga_pendidik'])
            ->orderBy('name')
            ->get();

        // Metrik statistik
        $stats = [
            'total' => SuratMasuk::count(),
            'bulan_ini' => SuratMasuk::whereMonth('tanggal_diterima', now()->month)
                ->whereYear('tanggal_diterima', now()->year)
                ->count(),
            'perlu_disposisi' => SuratMasuk::where('status', 'Diterima')->count(),
            'arsip_terakhir' => SuratMasuk::latest('id')->value('nomor_agenda') ?? '-',
        ];

        return view('dashboard.surat-masuk.index', compact('suratList', 'assignableUsers', 'stats'));
    }

    /**
     * Menyimpan registrasi surat masuk baru.
     */
    public function store(StoreSuratMasukRequest $request, SuratMasukService $service): RedirectResponse
    {
        $surat = $service->register(
            $request->validated(),
            $request->file('berkas'),
            $request->user()
        );

        return redirect()->route('surat-masuk.index')
            ->with('status', 'Surat masuk berhasil dicatat dengan nomor agenda '.$surat->nomor_agenda);
    }

    /**
     * Menampilkan detail surat masuk dan riwayat disposisinya.
     */
    public function show(SuratMasuk $suratMasuk): View
    {
        Gate::authorize('view', $suratMasuk);

        $suratMasuk->load(['creator', 'disposisi.fromUser', 'disposisi.toUser']);

        $assignableUsers = User::whereIn('role', ['guru', 'tata_usaha', 'tenaga_pendidik'])
            ->orderBy('name')
            ->get();

        return view('dashboard.surat-masuk.show', compact('suratMasuk', 'assignableUsers'));
    }

    /**
     * Mengunduh berkas lampiran privat melalui Policy.
     */
    public function download(SuratMasuk $suratMasuk): StreamedResponse
    {
        Gate::authorize('download', $suratMasuk);

        if (! $suratMasuk->file_path || ! Storage::disk('local')->exists($suratMasuk->file_path)) {
            abort(404, 'Berkas fisik naskah dinas tidak ditemukan.');
        }

        return Storage::disk('local')->download(
            $suratMasuk->file_path,
            $suratMasuk->file_name ?? 'naskah-surat.pdf'
        );
    }

    /**
     * Menampilkan lembar disposisi standar kedinasan sekolah (siap cetak/PDF).
     */
    public function lembarDisposisi(SuratMasuk $suratMasuk): View
    {
        Gate::authorize('view', $suratMasuk);

        $suratMasuk->load(['creator', 'disposisi.fromUser', 'disposisi.toUser']);

        return view('dashboard.surat-masuk.lembar-disposisi', compact('suratMasuk'));
    }
}
