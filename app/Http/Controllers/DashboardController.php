<?php

namespace App\Http\Controllers;

use App\Enums\Role;
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
        return view('dashboard.admin');
    }

    /**
     * Dashboard Kepala Sekolah.
     */
    public function kepalaSekolah(): View
    {
        return view('dashboard.kepala-sekolah');
    }

    /**
     * Dashboard Tata Usaha.
     */
    public function tataUsaha(): View
    {
        return view('dashboard.tata-usaha');
    }

    /**
     * Dashboard Guru.
     */
    public function guru(): View
    {
        return view('dashboard.guru');
    }

    /**
     * Dashboard Tenaga Pendidik.
     */
    public function tenagaPendidik(): View
    {
        return view('dashboard.tenaga-pendidik');
    }
}
