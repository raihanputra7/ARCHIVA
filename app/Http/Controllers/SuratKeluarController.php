<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSuratKeluarRequest;
use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use App\Services\SuratKeluarService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SuratKeluarController extends Controller
{
    public function __construct(
        protected SuratKeluarService $suratKeluarService
    ) {}

    public function index(Request $request): View
    {
        Gate::authorize('viewAny', SuratKeluar::class);

        $query = SuratKeluar::with(['creator', 'approver', 'parentSuratMasuk'])
            ->latest('tanggal_surat');

        if ($request->filled('q')) {
            $search = $request->string('q');
            $query->where(function ($q) use ($search) {
                $q->where('nomor_agenda', 'like', "%{$search}%")
                    ->orWhere('nomor_surat', 'like', "%{$search}%")
                    ->orWhere('tujuan', 'like', "%{$search}%")
                    ->orWhere('perihal', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        $suratKeluar = $query->paginate(10)->withQueryString();

        $parentSuratMasuk = null;
        if ($request->filled('parent_surat_masuk_id')) {
            $parentSuratMasuk = SuratMasuk::find($request->integer('parent_surat_masuk_id'));
        }

        $metrics = [
            'total' => SuratKeluar::count(),
            'draf' => SuratKeluar::where('status', 'Draf')->count(),
            'disetujui' => SuratKeluar::where('status', 'Disetujui')->count(),
            'terkirim' => SuratKeluar::where('status', 'Terkirim')->count(),
        ];

        return view('dashboard.surat-keluar.index', compact('suratKeluar', 'metrics', 'parentSuratMasuk'));
    }

    public function store(StoreSuratKeluarRequest $request): RedirectResponse
    {
        $suratKeluar = $this->suratKeluarService->register(
            $request->validated(),
            $request->file('berkas'),
            $request->user()
        );

        return redirect()->route('surat-keluar.show', $suratKeluar)
            ->with('success', 'Surat keluar berhasil didaftarkan dengan nomor agenda '.$suratKeluar->nomor_agenda);
    }

    public function show(SuratKeluar $suratKeluar): View
    {
        Gate::authorize('view', $suratKeluar);

        $suratKeluar->load(['creator', 'approver', 'parentSuratMasuk']);

        return view('dashboard.surat-keluar.show', compact('suratKeluar'));
    }

    public function approve(SuratKeluar $suratKeluar, Request $request): RedirectResponse
    {
        Gate::authorize('approve', $suratKeluar);

        $this->suratKeluarService->approve($suratKeluar, $request->user());

        return redirect()->route('surat-keluar.show', $suratKeluar)
            ->with('success', 'Surat keluar berhasil disetujui oleh Kepala Sekolah.');
    }

    public function download(SuratKeluar $suratKeluar): StreamedResponse
    {
        Gate::authorize('download', $suratKeluar);

        if (! $suratKeluar->file_path || ! Storage::disk('local')->exists($suratKeluar->file_path)) {
            abort(404, 'Berkas lampiran fisik surat tidak ditemukan.');
        }

        return Storage::disk('local')->download(
            $suratKeluar->file_path,
            $suratKeluar->file_name ?? 'surat-keluar-'.$suratKeluar->nomor_agenda.'.pdf'
        );
    }
}
