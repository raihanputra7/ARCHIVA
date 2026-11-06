<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Support\DummyDashboardData;
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
    public function admin(): View
    {
        return view('dashboard.admin', [
            'kpis' => DummyDashboardData::getKpis(),
            'activities' => DummyDashboardData::getAuditActivities(),
            'repositories' => DummyDashboardData::getRepositories(),
        ]);
    }

    /**
     * Dashboard Kepala Sekolah.
     */
    public function kepalaSekolah(): View
    {
        return view('dashboard.kepala-sekolah', [
            'kpis' => DummyDashboardData::getKpis(),
            'letters' => DummyDashboardData::getRecentLetters(),
            'chartData' => DummyDashboardData::getChartData(),
            'activities' => DummyDashboardData::getAuditActivities(),
        ]);
    }

    /**
     * Dashboard Tata Usaha.
     */
    public function tataUsaha(): View
    {
        return view('dashboard.tata-usaha', [
            'kpis' => DummyDashboardData::getKpis(),
            'letters' => DummyDashboardData::getRecentLetters(),
            'chartData' => DummyDashboardData::getChartData(),
            'repositories' => DummyDashboardData::getRepositories(),
        ]);
    }

    /**
     * Dashboard Guru.
     */
    public function guru(): View
    {
        return view('dashboard.guru', [
            'kpis' => DummyDashboardData::getKpis(),
            'letters' => array_filter(DummyDashboardData::getRecentLetters(), fn ($item) => in_array($item['category'], ['Dinas', 'Undangan', 'Kurikulum', 'SK Tugas'])),
            'repositories' => DummyDashboardData::getRepositories(),
        ]);
    }

    /**
     * Dashboard Tenaga Pendidik.
     */
    public function tenagaPendidik(): View
    {
        return view('dashboard.tenaga-pendidik', [
            'kpis' => DummyDashboardData::getKpis(),
            'letters' => array_filter(DummyDashboardData::getRecentLetters(), fn ($item) => in_array($item['category'], ['SK Tugas', 'Kesehatan', 'Dinas'])),
            'repositories' => DummyDashboardData::getRepositories(),
        ]);
    }
}
