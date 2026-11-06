@props([
    'active' => 'dashboard',
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
                    ['id' => 'disposisi', 'label' => 'Disposisi', 'route' => 'dashboard.kepala-sekolah', 'icon' => 'rate_review', 'disabled' => true],
                    ['id' => 'arsip', 'label' => 'Arsip', 'route' => 'dashboard.kepala-sekolah', 'icon' => 'folder_open', 'disabled' => true],
                    ['id' => 'analytics', 'label' => 'Analitik Surat', 'route' => 'dashboard.kepala-sekolah.analytics', 'icon' => 'analytics'],
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
                    ['id' => 'disposisi', 'label' => 'Disposisi', 'route' => 'dashboard.tata-usaha', 'icon' => 'rate_review', 'disabled' => true],
                    ['id' => 'arsip', 'label' => 'Arsip', 'route' => 'dashboard.tata-usaha', 'icon' => 'folder_open', 'disabled' => true],
                    ['id' => 'surat_keluar', 'label' => 'Surat Keluar', 'route' => 'dashboard.tata-usaha', 'icon' => 'outgoing_mail', 'disabled' => true],
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
                    ['id' => 'disposisi', 'label' => 'Tugas Disposisi', 'route' => 'dashboard.guru', 'icon' => 'assignment', 'disabled' => true],
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
                    ['id' => 'instruksi', 'label' => 'Instruksi Kerja', 'route' => 'dashboard.tenaga-pendidik', 'icon' => 'task', 'disabled' => true],
                    ['id' => 'sarpras', 'label' => 'Sarpras', 'route' => 'dashboard.tenaga-pendidik', 'icon' => 'inventory_2', 'disabled' => true],
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
    @click="$store.sidebar.close()"
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
    :class="$store.sidebar.open ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
    class="fixed inset-y-0 left-0 z-50 w-[264px] bg-surface-lowest border-r border-border-subtle flex flex-col justify-between transition-transform duration-200 ease-in-out"
>
    <!-- Top: Logo & Nav -->
    <div class="flex-1 overflow-y-auto">
        <!-- Logo Area -->
        <div class="h-16 px-5 border-b border-border-subtle flex items-center justify-between">
            <x-logo size="md" />
            <button
                type="button"
                @click="$store.sidebar.close()"
                class="lg:hidden p-1.5 rounded-lg text-outline hover:text-on-surface hover:bg-surface-container"
                aria-label="Tutup Menu"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Navigation Sections -->
        <nav class="p-4 space-y-6">
            @foreach($navSections as $section)
                <div>
                    <h4 class="px-3 text-[11px] font-bold uppercase tracking-wider text-outline mb-2">
                        {{ $section['title'] }}
                    </h4>
                    <div class="space-y-1">
                        @foreach($section['items'] as $item)
                            @php
                                $isItemActive = ($active === $item['id']);
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
                                    class="flex items-center justify-between px-3 py-2.5 rounded-lg text-xs font-medium text-outline opacity-60 cursor-not-allowed select-none"
                                    title="Segera hadir"
                                >
                                    <div class="flex items-center gap-3">
                                        <svg class="w-4 h-4 text-outline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            {!! $iconSvg !!}
                                        </svg>
                                        <span>{{ $item['label'] }}</span>
                                    </div>
                                    <span class="text-[10px] bg-surface-container px-1.5 py-0.5 rounded text-on-surface-variant font-normal">Nanti</span>
                                </div>
                            @else
                                <a
                                    href="{{ $href }}"
                                    class="flex items-center justify-between px-3 py-2.5 rounded-lg text-xs font-semibold transition-colors duration-150 {{ $isItemActive 
                                        ? 'bg-primary-container text-primary border-l-[3px] border-secondary' 
                                        : 'text-on-surface-variant hover:text-on-surface hover:bg-surface-container-low' }}"
                                >
                                    <div class="flex items-center gap-3">
                                        <svg class="w-4 h-4 {{ $isItemActive ? 'text-primary' : 'text-outline' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            {!! $iconSvg !!}
                                        </svg>
                                        <span>{{ $item['label'] }}</span>
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
    <div class="p-4 border-t border-border-subtle bg-surface-container-low/50">
        <div class="flex items-center justify-between gap-3">
            <div class="flex items-center gap-2.5 min-w-0">
                <div class="w-8 h-8 rounded-full bg-primary text-on-primary flex items-center justify-center font-bold text-xs flex-shrink-0">
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
    </div>
</aside>
