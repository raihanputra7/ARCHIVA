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
    x-show="mobileSidebarOpen"
    x-cloak
    @click="mobileSidebarOpen = false"
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
        sidebarExpanded ? 'w-64 min-w-[16rem]' : 'w-20 min-w-[5rem]',
        mobileSidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'
    ]"
    class="fixed inset-y-0 left-0 z-50 bg-surface-lowest border-r border-border-subtle flex flex-col justify-between transition-[width,transform] duration-300 ease-in-out select-none"
>
    <!-- Top: Logo & Nav -->
    <div class="flex-1 overflow-y-auto overflow-x-hidden">
        <!-- Logo & Header Area -->
        <div class="min-h-16 py-2 px-2.5 border-b border-border-subtle flex items-center justify-between overflow-hidden">
            <!-- Mode Expanded: Logo di panel putih di kiri, teks identitas di kanan, hover halus menyatu -->
            <div x-show="sidebarExpanded" class="flex items-center justify-between w-full min-w-0">
                <button
                    type="button"
                    @click="sidebarExpanded = !sidebarExpanded"
                    class="flex items-center gap-2.5 hover:bg-slate-100 dark:hover:bg-slate-800/60 rounded-xl p-2 transition-colors cursor-pointer focus:outline-none text-left w-full min-w-0 group"
                    title="Ciutkan Sidebar"
                    aria-label="Ciutkan Sidebar"
                >
                    <x-logo size="sm" />
                </button>

                <!-- Tombol Tutup Drawer Mobile (Hanya di layar ponsel < lg) -->
                <button
                    type="button"
                    @click="mobileSidebarOpen = false"
                    class="lg:hidden p-1.5 rounded-lg text-outline hover:text-on-surface hover:bg-surface-container focus:outline-none flex-shrink-0 ml-1"
                    aria-label="Tutup Menu"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Mode Collapsed: Hanya logo dalam panel putih kecil di tengah -->
            <div x-show="!sidebarExpanded" x-cloak class="hidden lg:flex items-center justify-center w-full">
                <button
                    type="button"
                    @click="sidebarExpanded = !sidebarExpanded"
                    class="hover:bg-slate-100 dark:hover:bg-slate-800/60 rounded-xl p-2 transition-colors cursor-pointer focus:outline-none"
                    title="Perluas Sidebar"
                    aria-label="Perluas Sidebar"
                >
                    <div class="flex-shrink-0 flex items-center justify-center bg-white rounded-lg p-1.5 shadow-xs border border-slate-200/60 dark:border-white/10">
                        <img
                            src="{{ asset('images/logo-sdn-jatipulo07.png') }}"
                            alt="Logo SDN Jatipulo 07"
                            class="h-7 w-auto object-contain flex-shrink-0"
                        >
                    </div>
                </button>
            </div>
        </div>

        <!-- Navigation Sections -->
        <nav class="p-3 space-y-4">
            @foreach($navSections as $section)
                <div>
                    <h4 x-show="sidebarExpanded" class="px-3 text-[11px] font-bold uppercase tracking-wider text-outline mb-2">
                        {{ $section['title'] }}
                    </h4>
                    <div x-show="!sidebarExpanded" x-cloak class="my-2 border-t border-border-subtle/60"></div>

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
                                    :class="sidebarExpanded ? 'justify-start px-3' : 'justify-center px-2'"
                                    :title="!sidebarExpanded ? '{{ $item['label'] }} (Segera hadir)' : ''"
                                    class="flex items-center gap-3 py-2.5 rounded-lg text-xs font-medium text-outline opacity-60 cursor-not-allowed select-none transition-colors"
                                >
                                    <svg class="w-5 h-5 flex-shrink-0 text-outline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        {!! $iconSvg !!}
                                    </svg>
                                    <span x-show="sidebarExpanded" class="truncate">{{ $item['label'] }}</span>
                                    <span x-show="sidebarExpanded" class="ml-auto text-[10px] bg-surface-container px-1.5 py-0.5 rounded text-on-surface-variant font-normal">Nanti</span>
                                </div>
                            @else
                                <a
                                    href="{{ $href }}"
                                    :title="!sidebarExpanded ? '{{ $item['label'] }}' : ''"
                                    :class="sidebarExpanded ? 'justify-start px-3' : 'justify-center px-2'"
                                    class="flex items-center gap-3 py-2.5 rounded-lg text-xs transition-colors duration-150 group {{ $isItemActive ? 'bg-primary-container text-primary border-l-[3px] border-secondary font-bold' : 'text-on-surface-variant hover:text-on-surface hover:bg-surface-container-low font-semibold' }}"
                                >
                                    <svg class="w-5 h-5 flex-shrink-0 {{ $isItemActive ? 'text-primary' : 'text-outline group-hover:text-on-surface' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        {!! $iconSvg !!}
                                    </svg>
                                    <span x-show="sidebarExpanded" class="truncate">{{ $item['label'] }}</span>
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
        <!-- Ketika sidebarExpanded: Avatar + Nama + Role + Tombol Logout sejajar horizontal -->
        <div x-show="sidebarExpanded" class="flex items-center justify-between gap-2.5">
            <div class="flex items-center gap-2.5 min-w-0">
                <div class="w-8 h-8 rounded-full bg-primary text-on-primary flex items-center justify-center font-bold text-xs flex-shrink-0 shadow-xs">
                    {{ strtoupper(substr($user?->name ?? 'U', 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-bold text-on-surface truncate leading-tight">{{ $user?->name }}</p>
                    <p class="text-[10px] text-on-surface-variant truncate capitalize">{{ str_replace('_', ' ', $role) }}</p>
                </div>
            </div>
            
            <form method="POST" action="{{ route('logout') }}" class="flex-shrink-0">
                @csrf
                <button
                    type="submit"
                    class="p-1.5 rounded-lg text-outline hover:text-error hover:bg-error-container/20 transition-colors"
                    title="Keluar ke Portal (Logout)"
                    aria-label="Keluar"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                </button>
            </form>
        </div>

        <!-- Ketika !sidebarExpanded: Tampilkan HANYA Avatar lingkaran di tengah yang bisa di-klik untuk logout/menu ringkas -->
        <div x-show="!sidebarExpanded" x-cloak class="hidden lg:flex items-center justify-center">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button
                    type="submit"
                    class="w-9 h-9 rounded-full bg-primary text-on-primary flex items-center justify-center font-bold text-xs shadow-xs hover:ring-2 hover:ring-error transition-all cursor-pointer"
                    title="Keluar ke Portal: {{ $user?->name }} ({{ str_replace('_', ' ', $role) }})"
                    aria-label="Keluar"
                >
                    {{ strtoupper(substr($user?->name ?? 'U', 0, 1)) }}
                </button>
            </form>
        </div>
    </div>
</aside>
