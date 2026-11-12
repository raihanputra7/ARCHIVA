<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Http\Requests\Admin\MonitorUsersRequest;
use App\Models\Disposisi;
use App\Models\RiwayatStatus;
use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use App\Models\User;
use App\Services\AdminMonitoringService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Redirect authenticated user to their role-specific dashboard.
     */
    public function index(Request $request): RedirectResponse
    {
        $role = $request->user()->role;
        $roleEnum = $role instanceof Role ? $role : Role::tryFrom((string) $role);

        if (! $roleEnum) {
            abort(403, 'Peran akun Anda tidak valid.');
        }

        return redirect()->route($roleEnum->dashboardRoute());
    }

    /**
     * Dashboard Administrator.
     */
    public function admin(MonitorUsersRequest $request, AdminMonitoringService $adminService): View
    {
        $validated = $request->validated();
        $search = $validated['q'] ?? null;
        $roleFilter = $validated['role'] ?? null;

        $monitoring = $adminService->getAccountMonitoringSummary($search, $roleFilter);
        $roles = Role::cases();

        $kpis = [
            [
                'id' => 'total_pengguna',
                'label' => 'Pengguna Terdaftar',
                'value' => (string) $monitoring['totalCount'],
                'unit' => 'Akun',
                'subtext' => 'Terdaftar di sistem ARCHIVA',
            ],
            [
                'id' => 'sesi_aktif',
                'label' => 'Sesi Aktif',
                'value' => (string) $monitoring['activeSessionsCount'],
                'unit' => 'Pengguna',
                'subtext' => 'Aktif dalam 15 menit terakhir',
            ],
            [
                'id' => 'akun_terverifikasi',
                'label' => 'Email Terverifikasi',
                'value' => (string) $monitoring['verifiedCount'],
                'unit' => 'Akun',
                'subtext' => 'Siap menerima notifikasi',
            ],
            [
                'id' => 'total_audit',
                'label' => 'Aktivitas & Audit',
                'value' => (string) (RiwayatStatus::count() + Disposisi::count()),
                'unit' => 'Catatan',
                'subtext' => 'Jejak disposisi & status',
            ],
        ];

        $activities = Disposisi::with(['fromUser', 'toUser', 'suratMasuk'])
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($disp) {
                $fromName = $disp->fromUser?->name ?? 'Sistem';
                $toName = $disp->toUser?->name ?? 'Penerima';
                $agenda = $disp->suratMasuk?->nomor_agenda ?? '-';

                return [
                    'action' => 'Disposisi '.$agenda,
                    'user' => $fromName.' ➔ '.$toName,
                    'detail' => $disp->instruksi,
                    'time' => $disp->created_at?->diffForHumans() ?? '-',
                    'icon' => 'rate_review',
                ];
            })->all();

        $repositories = $this->getRealRepositories();

        return view('dashboard.admin', compact(
            'kpis',
            'monitoring',
            'roles',
            'search',
            'roleFilter',
            'activities',
            'repositories'
        ));
    }

    /**
     * Dashboard Kepala Sekolah.
     */
    public function kepalaSekolah(): View
    {
        $kpis = [
            [
                'id' => 'perlu_disposisi',
                'label' => 'Menunggu Disposisi',
                'value' => (string) SuratMasuk::where('status', 'Diterima')->count(),
                'unit' => 'Naskah',
                'delta' => 'Prioritas',
                'trend' => 'up',
                'subtext' => 'Perlu arahan pimpinan',
            ],
            [
                'id' => 'dalam_proses',
                'label' => 'Disposisi Diproses',
                'value' => (string) Disposisi::where('status', 'Diproses')->count(),
                'unit' => 'Instruksi',
                'delta' => 'Berjalan',
                'trend' => 'up',
                'subtext' => 'Sedang ditindaklanjuti',
            ],
            [
                'id' => 'selesai',
                'label' => 'Disposisi Selesai',
                'value' => (string) Disposisi::where('status', 'Selesai')->count(),
                'unit' => 'Tuntas',
                'delta' => 'Tercapai',
                'trend' => 'up',
                'subtext' => 'Tugas telah selesai',
            ],
            [
                'id' => 'total_masuk',
                'label' => 'Total Surat Masuk',
                'value' => (string) SuratMasuk::count(),
                'unit' => 'Berkas',
                'delta' => 'Terdata',
                'trend' => 'up',
                'subtext' => 'SDN Jatipulo 07',
            ],
        ];

        $letters = SuratMasuk::with(['disposisi.toUser'])->latest()->take(6)->get();
        $chartData = $this->getRealChartData();
        $assignableUsers = User::whereIn('role', ['guru', 'tata_usaha', 'tenaga_pendidik'])->orderBy('name')->get();

        return view('dashboard.kepala-sekolah', compact('kpis', 'letters', 'chartData', 'assignableUsers'));
    }

    /**
     * Halaman Analitik Kepala Sekolah.
     */
    public function kepalaSekolahAnalytics(): View
    {
        $chartData = $this->getRealChartData();
        $metrics = [
            'total_masuk' => SuratMasuk::count(),
            'total_keluar' => SuratKeluar::count(),
            'total_disposisi' => Disposisi::count(),
            'selesai' => Disposisi::where('status', 'Selesai')->count(),
        ];

        return view('dashboard.kepala-sekolah.analytics', compact('chartData', 'metrics'));
    }

    /**
     * Dashboard Tata Usaha.
     */
    public function tataUsaha(): View
    {
        $kpis = [
            [
                'id' => 'surat_masuk',
                'label' => 'Total Surat Masuk',
                'value' => (string) SuratMasuk::count(),
                'unit' => 'Berkas',
                'delta' => 'Terarsip',
                'trend' => 'up',
                'subtext' => 'Tercatat di buku agenda',
            ],
            [
                'id' => 'bulan_ini',
                'label' => 'Surat Bulan Ini',
                'value' => (string) SuratMasuk::whereMonth('tanggal_diterima', now()->month)
                    ->whereYear('tanggal_diterima', now()->year)
                    ->count(),
                'unit' => 'Berkas',
                'delta' => Carbon::now()->isoFormat('MMMM Y'),
                'trend' => 'up',
                'subtext' => 'Periode berjalan',
            ],
            [
                'id' => 'top_kategori',
                'label' => 'Kategori Terbanyak',
                'value' => SuratMasuk::select('kategori')
                    ->groupBy('kategori')
                    ->orderByRaw('COUNT(*) DESC')
                    ->value('kategori') ?? 'Dinas',
                'unit' => 'Kategori',
                'delta' => 'Dominan',
                'trend' => 'up',
                'subtext' => 'Frekuensi tertinggi',
            ],
            [
                'id' => 'arsip_terakhir',
                'label' => 'Nomor Arsip Terakhir',
                'value' => SuratMasuk::latest('id')->value('nomor_agenda') ?? '-',
                'unit' => 'Agenda',
                'delta' => 'Terbaru',
                'trend' => 'up',
                'subtext' => 'Urutan otomatis',
            ],
        ];

        $letters = SuratMasuk::with(['disposisi.toUser'])->latest()->take(6)->get();
        $chartData = $this->getRealChartData();
        $repositories = $this->getRealRepositories();
        $assignableUsers = User::whereIn('role', ['guru', 'tata_usaha', 'tenaga_pendidik'])->orderBy('name')->get();

        return view('dashboard.tata-usaha', compact('kpis', 'letters', 'chartData', 'repositories', 'assignableUsers'));
    }

    /**
     * Dashboard Guru.
     */
    public function guru(Request $request): View
    {
        $user = $request->user();

        $myDisposisi = Disposisi::with(['suratMasuk', 'fromUser'])
            ->where('to_user_id', $user->id)
            ->latest()
            ->get();

        $kpis = [
            [
                'id' => 'tugas_masuk',
                'label' => 'Total Tugas Disposisi',
                'value' => (string) $myDisposisi->count(),
                'unit' => 'Tugas',
                'delta' => 'Kedinasan',
                'trend' => 'up',
                'subtext' => 'Instruksi pimpinan',
            ],
            [
                'id' => 'tugas_menunggu',
                'label' => 'Perlu Ditindaklanjuti',
                'value' => (string) $myDisposisi->where('status', 'Menunggu')->count(),
                'unit' => 'Instruksi',
                'delta' => 'Menunggu',
                'trend' => 'up',
                'subtext' => 'Segera diproses',
            ],
            [
                'id' => 'tugas_diproses',
                'label' => 'Sedang Dikerjakan',
                'value' => (string) $myDisposisi->where('status', 'Diproses')->count(),
                'unit' => 'Tugas',
                'delta' => 'Aktif',
                'trend' => 'up',
                'subtext' => 'Proses pelaksanaan',
            ],
            [
                'id' => 'tugas_selesai',
                'label' => 'Tugas Selesai',
                'value' => (string) $myDisposisi->where('status', 'Selesai')->count(),
                'unit' => 'Tuntas',
                'delta' => 'Lengkap',
                'trend' => 'up',
                'subtext' => 'Laporan terverifikasi',
            ],
        ];

        $letters = $myDisposisi->map(function ($disp) {
            return [
                'id' => $disp->suratMasuk->nomor_agenda,
                'number' => $disp->suratMasuk->nomor_surat_asli ?? '-',
                'subject' => $disp->suratMasuk->perihal,
                'sender' => $disp->fromUser->name.': '.$disp->instruksi,
                'date' => $disp->batas_waktu ? 'Batas: '.$disp->batas_waktu->isoFormat('D MMM Y') : $disp->created_at->isoFormat('D MMM Y'),
                'status' => $disp->status,
                'surat_id' => $disp->surat_masuk_id,
            ];
        })->all();

        $repositories = $this->getRealRepositories();

        return view('dashboard.guru', compact('kpis', 'letters', 'myDisposisi', 'repositories'));
    }

    /**
     * Dashboard Tenaga Pendidik.
     */
    public function tenagaPendidik(Request $request): View
    {
        $user = $request->user();

        $myDisposisi = Disposisi::with(['suratMasuk', 'fromUser'])
            ->where('to_user_id', $user->id)
            ->latest()
            ->get();

        $kpis = [
            [
                'id' => 'tugas_operasional',
                'label' => 'Instruksi Kerja',
                'value' => (string) $myDisposisi->count(),
                'unit' => 'Tugas',
                'delta' => 'Operasional',
                'trend' => 'up',
                'subtext' => 'Tugas & penugasan',
            ],
            [
                'id' => 'tugas_menunggu',
                'label' => 'Instruksi Baru',
                'value' => (string) $myDisposisi->where('status', 'Menunggu')->count(),
                'unit' => 'Arahan',
                'delta' => 'Perlu aksi',
                'trend' => 'up',
                'subtext' => 'Gedung 4 lantai',
            ],
            [
                'id' => 'tugas_diproses',
                'label' => 'Sedang Dikerjakan',
                'value' => (string) $myDisposisi->where('status', 'Diproses')->count(),
                'unit' => 'Pelaksanaan',
                'delta' => 'Berjalan',
                'trend' => 'up',
                'subtext' => 'Piket & sarpras',
            ],
            [
                'id' => 'tugas_selesai',
                'label' => 'Tugas Selesai',
                'value' => (string) $myDisposisi->where('status', 'Selesai')->count(),
                'unit' => 'Tuntas',
                'delta' => 'Laporan siap',
                'trend' => 'up',
                'subtext' => 'Selesai dikerjakan',
            ],
        ];

        $letters = $myDisposisi->map(function ($disp) {
            return [
                'id' => $disp->suratMasuk->nomor_agenda,
                'number' => $disp->suratMasuk->nomor_surat_asli ?? '-',
                'subject' => $disp->suratMasuk->perihal,
                'sender' => $disp->fromUser->name.': '.$disp->instruksi,
                'date' => $disp->batas_waktu ? 'Batas: '.$disp->batas_waktu->isoFormat('D MMM Y') : $disp->created_at->isoFormat('D MMM Y'),
                'status' => $disp->status,
                'surat_id' => $disp->surat_masuk_id,
            ];
        })->all();

        $repositories = $this->getRealRepositories();

        return view('dashboard.tenaga-pendidik', compact('kpis', 'letters', 'myDisposisi', 'repositories'));
    }

    /**
     * Menghitung data grafik nyata dari tabel surat_masuk.
     */
    private function getRealChartData(): array
    {
        // 7 hari terakhir
        $days = collect(range(6, 0))->map(fn ($i) => Carbon::today()->subDays($i));
        $labels = $days->map(fn ($d) => $d->isoFormat('D MMM'))->all();

        $counts = $days->map(function ($d) {
            return SuratMasuk::whereDate('tanggal_diterima', $d)->count();
        })->all();

        // Kategori
        $categoriesData = SuratMasuk::select('kategori')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('kategori')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        $catLabels = $categoriesData->pluck('kategori')->all();
        $catValues = $categoriesData->pluck('total')->all();

        if (empty($catLabels)) {
            $catLabels = ['Dinas', 'Undangan', 'Kurikulum', 'Keuangan', 'Kesehatan'];
            $catValues = [0, 0, 0, 0, 0];
        }

        return [
            'trend' => [
                'labels' => $labels,
                'data' => $counts,
            ],
            'categories' => [
                'labels' => $catLabels,
                'data' => $catValues,
            ],
        ];
    }

    /**
     * Repositori kategori persuratan nyata.
     */
    private function getRealRepositories(): array
    {
        return [
            [
                'id' => 'repo-1',
                'title' => 'Surat Masuk Dinas',
                'category' => 'Kedinasan',
                'description' => 'Arsip persuratan resmi dari Dinas Pendidikan, Sudin, dan Kementerian.',
                'files_count' => SuratMasuk::where('kategori', 'Dinas')->count(),
                'updated_at' => SuratMasuk::where('kategori', 'Dinas')->latest()->value('updated_at')?->locale('id')->diffForHumans() ?? 'Belum ada berkas',
            ],
            [
                'id' => 'repo-2',
                'title' => 'SK & Surat Tugas',
                'category' => 'Keputusan',
                'description' => 'Koleksi Surat Keputusan Kepala Sekolah, panitia kegiatan, dan pembagian tugas.',
                'files_count' => SuratMasuk::where('kategori', 'SK Tugas')->count() + SuratKeluar::where('kategori', 'SK Tugas')->count(),
                'updated_at' => 'Terkini',
            ],
            [
                'id' => 'repo-3',
                'title' => 'Kurikulum Merdeka 2026',
                'category' => 'Kurikulum',
                'description' => 'Perangkat ajar, Capaian Pembelajaran, Alur Tujuan Pembelajaran, dan modul ajar.',
                'files_count' => SuratMasuk::where('kategori', 'Kurikulum')->count(),
                'updated_at' => 'Terkini',
            ],
            [
                'id' => 'repo-4',
                'title' => 'SPJ & Laporan BOS',
                'category' => 'Keuangan',
                'description' => 'Surat Pertanggungjawaban (SPJ) dan verifikasi anggaran Bantuan Operasional Sekolah.',
                'files_count' => SuratMasuk::where('kategori', 'Keuangan')->count(),
                'updated_at' => 'Terkini',
            ],
        ];
    }
}
