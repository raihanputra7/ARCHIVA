@props([
    'active' => null,
])

@php
    $user = auth()->user();
    $role = $user?->role?->value ?? $user?->role ?? 'guru';

    // Menu definition per role
    $navSections = match($role) {
        'admin' => [
            [
                'title' => 'Menu Utama',
                'items' => [
                    ['id' => 'dashboard', 'label' => 'Dashboard', 'route' => 'dashboard.admin', 'icon' => 'dashboard'],
                    ['id' => 'surat_masuk', 'label' => 'Surat Masuk', 'route' => 'surat-masuk.index', 'icon' => 'mark_email_unread'],
                    ['id' => 'surat_keluar', 'label' => 'Surat Keluar', 'route' => 'surat-keluar.index', 'icon' => 'outgoing_mail'],
                    ['id' => 'audit', 'label' => 'Audit', 'route' => 'dashboard.admin', 'icon' => 'fact_check', 'disabled' => true],
                    ['id' => 'basis_data', 'label' => 'Basis Data', 'route' => 'dashboard.admin', 'icon' => 'database', 'disabled' => true],
                ]
            ],
            [
                'title' => 'Lainnya',
                'items' => [
                    ['id' => 'profile', 'label' => 'Pengaturan', 'route' => 'profile.edit', 'icon' => 'settings'],
                ]
            ]
        ],
        'kepala_sekolah' => [
            [
                'title' => 'Menu Utama',
                'items' => [
                    ['id' => 'dashboard', 'label' => 'Dashboard', 'route' => 'dashboard.kepala-sekolah', 'icon' => 'dashboard'],
                    ['id' => 'surat_masuk', 'label' => 'Disposisi Surat', 'route' => 'surat-masuk.index', 'icon' => 'rate_review'],
                    ['id' => 'surat_keluar', 'label' => 'Surat Keluar', 'route' => 'surat-keluar.index', 'icon' => 'outgoing_mail'],
                    ['id' => 'analytics', 'label' => 'Analitik Eksekutif', 'route' => 'dashboard.kepala-sekolah.analytics', 'icon' => 'analytics'],
                    ['id' => 'arsip', 'label' => 'Arsip Naskah', 'route' => 'dashboard.kepala-sekolah', 'icon' => 'folder_open', 'disabled' => true],
                ]
            ],
            [
                'title' => 'Lainnya',
                'items' => [
                    ['id' => 'profile', 'label' => 'Pengaturan', 'route' => 'profile.edit', 'icon' => 'settings'],
                ]
            ]
        ],
        'tata_usaha' => [
            [
                'title' => 'Menu Utama',
                'items' => [
                    ['id' => 'dashboard', 'label' => 'Dashboard', 'route' => 'dashboard.tata-usaha', 'icon' => 'dashboard'],
                    ['id' => 'surat_masuk', 'label' => 'Surat Masuk', 'route' => 'surat-masuk.index', 'icon' => 'mark_email_unread'],
                    ['id' => 'surat_keluar', 'label' => 'Surat Keluar', 'route' => 'surat-keluar.index', 'icon' => 'outgoing_mail'],
                    ['id' => 'arsip', 'label' => 'Buku Agenda', 'route' => 'dashboard.tata-usaha', 'icon' => 'folder_open', 'disabled' => true],
                ]
            ],
            [
                'title' => 'Lainnya',
                'items' => [
                    ['id' => 'profile', 'label' => 'Pengaturan', 'route' => 'profile.edit', 'icon' => 'settings'],
                ]
            ]
        ],
        'guru' => [
            [
                'title' => 'Menu Utama',
                'items' => [
                    ['id' => 'dashboard', 'label' => 'Dashboard', 'route' => 'dashboard.guru', 'icon' => 'dashboard'],
                    ['id' => 'disposisi', 'label' => 'Tugas Disposisi', 'route' => 'surat-masuk.index', 'icon' => 'assignment'],
                    ['id' => 'arsip', 'label' => 'Arsip Kurikulum', 'route' => 'dashboard.guru', 'icon' => 'folder_open', 'disabled' => true],
                ]
            ],
            [
                'title' => 'Lainnya',
                'items' => [
                    ['id' => 'profile', 'label' => 'Pengaturan', 'route' => 'profile.edit', 'icon' => 'settings'],
                ]
            ]
        ],
        'tenaga_pendidik' => [
            [
                'title' => 'Menu Utama',
                'items' => [
                    ['id' => 'dashboard', 'label' => 'Dashboard', 'route' => 'dashboard.tenaga-pendidik', 'icon' => 'dashboard'],
                    ['id' => 'instruksi', 'label' => 'Instruksi Disposisi', 'route' => 'surat-masuk.index', 'icon' => 'task'],
                    ['id' => 'sarpras', 'label' => 'Sarpras & Fasilitas', 'route' => 'dashboard.tenaga-pendidik', 'icon' => 'inventory_2', 'disabled' => true],
                ]
            ],
            [
                'title' => 'Lainnya',
                'items' => [
                    ['id' => 'profile', 'label' => 'Pengaturan', 'route' => 'profile.edit', 'icon' => 'settings'],
                ]
            ]
        ],
        default => []
    };
@endphp

<!-- Mobile Backdrop -->
<div
    x-show="$store.sidebar.open"
    @click="$store.sidebar.closeMobile()"
    x-transition:enter="transition-opacity ease-linear duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition-opacity ease-linear duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 bg-navy-dark/60 z-40 lg:hidden"
    style="display: none;"
    aria-hidden="true"
></div>

<!-- Sidebar / Drawer -->
<aside
    :class="[
        $store.sidebar.open ? 'translate-x-0' : '-translate-x-full lg:translate-x-0',
        $store.sidebar.collapsed ? 'lg:w-[76px]' : 'lg:w-[264px]'
    ]"
    class="fixed inset-y-0 left-0 z-50 w-[264px] bg-surface-lowest border-r border-border-subtle flex flex-col justify-between transition-all duration-300 ease-in-out select-none"
>
    <!-- Top: Logo & Nav -->
    <div class="flex-1 overflow-y-auto overflow-x-hidden">
        <!-- Logo Area -->
        <div class="h-16 px-4 border-b border-border-subtle flex items-center justify-between overflow-hidden">
            <!-- Mode Normal (Expanded) -->
            <div x-show="!$store.sidebar.collapsed" class="flex items-center gap-2">
                <x-logo size="md" />
            </div>

            <!-- Mode Collapsed (Desktop Mini) -->
            <div x-show="$store.sidebar.collapsed" class="hidden lg:flex items-center justify-center w-full" title="ARCHIVA — SDN Jatipulo 07">
                <img
                    src="{{ asset('images/logo-sdn-jatipulo07.png') }}"
                    alt="Logo SDN Jatipulo 07"
                    class="h-9 w-auto object-contain flex-shrink-0"
                    loading="eager"
                >
            </div>

            <!-- Tombol Collapse Desktop di Sidebar Header -->
            <button
                type="button"
                @click="$store.sidebar.toggleCollapse()"
                class="hidden lg:inline-flex p-1.5 rounded-lg text-outline hover:text-on-surface hover:bg-surface-container transition-colors"
                :title="$store.sidebar.collapsed ? 'Perluas Sidebar' : 'Ciutkan Sidebar'"
                aria-label="Toggle Sidebar"
            >
                <svg class="w-4 h-4 transition-transform duration-200" :class="$store.sidebar.collapsed ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
                </svg>
            </button>

            <!-- Tombol Close Drawer Mobile -->
            <button
                type="button"
                @click="$store.sidebar.closeMobile()"
                class="lg:hidden p-1.5 rounded-lg text-outline hover:text-on-surface hover:bg-surface-container"
                aria-label="Tutup Menu"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Navigation Sections -->
        <nav class="p-3 space-y-5">
            @foreach($navSections as $section)
                <div>
                    <h4 x-show="!$store.sidebar.collapsed" class="px-3 text-[11px] font-bold uppercase tracking-wider text-outline mb-2 transition-opacity duration-200">
                        {{ $section['title'] }}
                    </h4>
                    <div x-show="$store.sidebar.collapsed" class="my-2 border-t border-border-subtle/60"></div>

                    <div class="space-y-1">
                        @foreach($section['items'] as $item)
                            @php
                                $isItemActive = false;

                                if ($item['id'] === 'dashboard') {
                                    $isItemActive = request()->routeIs('dashboard*') && !request()->routeIs('dashboard.kepala-sekolah.analytics*');
                                } elseif (in_array($item['id'], ['surat_masuk', 'disposisi', 'instruksi'], true)) {
                                    $isItemActive = request()->routeIs('surat-masuk*');
                                } elseif (in_array($item['id'], ['surat_keluar', 'surat-keluar'], true)) {
                                    $isItemActive = request()->routeIs('surat-keluar*');
                                } elseif ($item['id'] === 'analytics') {
                                    $isItemActive = request()->routeIs('dashboard.kepala-sekolah.analytics*');
                                } elseif (in_array($item['id'], ['profile', 'pengaturan'], true)) {
                                    $isItemActive = request()->routeIs('profile*') || request()->routeIs('pengaturan*');
                                } else {
                                    $isItemActive = ($active === $item['id']) || (isset($item['route']) && \Illuminate\Support\Facades\Route::has($item['route']) && request()->routeIs($item['route'].'*'));
                                }

                                $isDisabled = !empty($item['disabled']);
                                $hasRoute = \Illuminate\Support\Facades\Route::has($item['route']);
                                $href = $hasRoute ? route($item['route']) : '#';
                            @endphp

                            @php
                                $iconSvg = match($item['icon']) {
                                    'dashboard' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>',
                                    'mark_email_unread', 'outgoing_mail' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>',
                                    'rate_review', 'assignment', 'task' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>',
                                    'folder_open', 'inventory_2' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z"/>',
                                    'analytics' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>',
                                    'settings' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>',
                                    default => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>',
                                };
                            @endphp

                            @if($isDisabled)
                                <div
                                    class="flex items-center rounded-lg text-xs font-medium text-outline opacity-60 cursor-not-allowed select-none transition-all duration-150"
                                    :class="$store.sidebar.collapsed ? 'justify-center px-2 py-2.5' : 'justify-between px-3 py-2.5'"
                                    :title="$store.sidebar.collapsed ? '{{ $item['label'] }} (Segera hadir)' : 'Segera hadir'"
                                >
                                    <div class="flex items-center gap-3">
                                        <svg class="w-4 h-4 text-outline flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            {!! $iconSvg !!}
                                        </svg>
                                        <span x-show="!$store.sidebar.collapsed" class="truncate">{{ $item['label'] }}</span>
                                    </div>
                                    <span x-show="!$store.sidebar.collapsed" class="text-[10px] bg-surface-container px-1.5 py-0.5 rounded text-on-surface-variant font-normal">Nanti</span>
                                </div>
                            @else
                                <a
                                    href="{{ $href }}"
                                    :title="$store.sidebar.collapsed ? '{{ $item['label'] }}' : ''"
                                    :class="[
                                        $store.sidebar.collapsed ? 'justify-center px-2 py-2.5' : 'justify-between px-3 py-2.5',
                                        {{ $isItemActive ? 'true' : 'false' }} 
                                            ? 'bg-primary-container text-primary border-l-[3px] border-secondary font-bold' 
                                            : 'text-on-surface-variant hover:text-on-surface hover:bg-surface-container-low font-semibold'
                                    ]"
                                    class="flex items-center rounded-lg text-xs transition-colors duration-150 group"
                                >
                                    <div class="flex items-center gap-3">
                                        <svg class="w-4 h-4 flex-shrink-0 {{ $isItemActive ? 'text-primary' : 'text-outline group-hover:text-on-surface' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            {!! $iconSvg !!}
                                        </svg>
                                        <span x-show="!$store.sidebar.collapsed" class="truncate">{{ $item['label'] }}</span>
                                    </div>
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endforeach
        </nav>
    </div>

    <!-- Bottom: User Meta & Quick Logout -->
    <div class="p-3 border-t border-border-subtle bg-surface-container-low/50 overflow-hidden">
        <!-- Expanded User Meta -->
        <div x-show="!$store.sidebar.collapsed" class="flex items-center justify-between gap-2.5">
            <div class="flex items-center gap-2.5 min-w-0">
                <div class="w-8 h-8 rounded-full bg-primary text-on-primary flex items-center justify-center font-bold text-xs flex-shrink-0 shadow-xs">
                    {{ strtoupper(substr($user?->name ?? 'U', 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-bold text-on-surface truncate leading-tight">{{ $user?->name }}</p>
                    <p class="text-[10px] text-on-surface-variant truncate capitalize">{{ str_replace('_', ' ', $role) }}</p>
                </div>
            </div>
            
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button
                    type="submit"
                    class="p-1.5 rounded-lg text-outline hover:text-error hover:bg-error-container/20 transition-colors"
                    title="Keluar ke Portal (Logout)"
                    aria-label="Keluar"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                </button>
            </form>
        </div>

        <!-- Collapsed Mini User Meta -->
        <div x-show="$store.sidebar.collapsed" class="hidden lg:flex flex-col items-center justify-center gap-2 py-1">
            <div
                class="w-8 h-8 rounded-full bg-primary text-on-primary flex items-center justify-center font-bold text-xs shadow-xs"
                title="{{ $user?->name }} ({{ str_replace('_', ' ', $role) }})"
            >
                {{ strtoupper(substr($user?->name ?? 'U', 0, 1)) }}
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button
                    type="submit"
                    class="p-1.5 rounded-lg text-outline hover:text-error hover:bg-error-container/20 transition-colors"
                    title="Keluar ke Portal (Logout)"
                    aria-label="Keluar"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</aside>
