@props([
    'user' => null,
])

@php
    $user = $user ?? auth()->user();
    $roleName = match($user?->role?->value ?? $user?->role) {
        'admin' => 'Admin Sistem',
        'kepala_sekolah' => 'Kepala Sekolah',
        'tata_usaha' => 'Tata Usaha' . ($user?->unit ? ' (' . ucfirst($user->unit) . ')' : ''),
        'guru' => 'Guru',
        'tenaga_pendidik' => 'Tenaga Pendidik',
        default => 'Pengguna',
    };
@endphp

<header class="h-16 bg-surface-lowest border-b border-border-subtle sticky top-0 z-30 transition-colors">
    <div class="h-full px-4 sm:px-6 flex items-center justify-between gap-4">
        
        <!-- Left: Hamburger button for mobile drawer & Desktop Collapse Toggle -->
        <div class="flex items-center gap-2 sm:gap-3">
            <!-- Mobile Drawer Toggle -->
            <button
                type="button"
                @click="mobileSidebarOpen = !mobileSidebarOpen"
                class="lg:hidden p-2 rounded-lg text-on-surface hover:bg-surface-container focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary"
                aria-label="Buka Menu Navigasi"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>

            <!-- Desktop Collapse Toggle -->
            <button
                type="button"
                @click="sidebarExpanded = !sidebarExpanded"
                class="hidden lg:inline-flex items-center justify-center p-2 rounded-lg text-on-surface-variant hover:text-on-surface hover:bg-surface-container transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary"
                :title="sidebarExpanded ? 'Ciutkan Sidebar' : 'Perluas Sidebar'"
                :aria-label="sidebarExpanded ? 'Ciutkan Sidebar' : 'Perluas Sidebar'"
            >
                <svg class="w-5 h-5 transition-transform duration-200" :class="sidebarExpanded ? '' : 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
                </svg>
            </button>

            <!-- Search bar -->
            <div class="relative hidden sm:block w-64 md:w-80">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-outline">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8" stroke-width="2"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65" stroke-width="2"></line>
                    </svg>
                </div>
                <input
                    type="search"
                    placeholder="Cari surat, agenda, berkas..."
                    class="w-full h-9 pl-9 pr-3 rounded-lg text-xs bg-surface-container-low text-on-surface placeholder:text-outline border border-transparent focus:border-primary focus:bg-surface-lowest focus:ring-2 focus:ring-primary focus:outline-none transition-colors"
                >
            </div>
        </div>

        <!-- Right actions -->
        <div class="flex items-center gap-2 sm:gap-3">
            
            <!-- Dark mode toggle -->
            <button
                type="button"
                @click="$store.theme.toggle()"
                class="p-2 rounded-lg text-on-surface-variant hover:text-on-surface hover:bg-surface-container transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary"
                :title="$store.theme.dark ? 'Beralih ke Mode Terang' : 'Beralih ke Mode Gelap'"
                :aria-label="$store.theme.dark ? 'Beralih ke Mode Terang' : 'Beralih ke Mode Gelap'"
            >
                <!-- Sun icon (shown in dark mode) -->
                <svg x-show="$store.theme.dark" class="w-5 h-5 text-gold-light" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="5" stroke-width="2"></circle>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 1v2m0 18v2M4.22 4.22l1.42 1.42m12.72 12.72l1.42 1.42M1 12h2m18 0h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"></path>
                </svg>
                <!-- Moon icon (shown in light mode) -->
                <svg x-show="!$store.theme.dark" class="w-5 h-5 text-on-surface-variant" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"></path>
                </svg>
            </button>

            <!-- Role badge -->
            <div class="hidden sm:inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-primary-container text-on-primary-container border border-primary/20">
                <span class="w-2 h-2 rounded-full bg-secondary"></span>
                <span>{{ $roleName }}</span>
            </div>

            <!-- Profile Dropdown -->
            <div class="relative" x-data="{ open: false }">
                <button
                    type="button"
                    @click="open = !open"
                    @keydown.escape="open = false"
                    class="flex items-center gap-2 p-1.5 rounded-lg text-on-surface hover:bg-surface-container transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary"
                    aria-expanded="false"
                    :aria-expanded="open"
                >
                    <div class="w-8 h-8 rounded-full bg-primary text-on-primary flex items-center justify-center font-bold text-xs">
                        {{ strtoupper(substr($user?->name ?? 'A', 0, 1)) }}
                    </div>
                    <div class="hidden md:flex flex-col text-left">
                        <span class="text-xs font-bold text-on-surface leading-tight">{{ $user?->name ?? 'Pengguna' }}</span>
                        <span class="text-[10px] text-on-surface-variant leading-tight truncate max-w-[120px]">{{ $user?->email ?? '' }}</span>
                    </div>
                    <svg class="w-4 h-4 text-outline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <!-- Dropdown menu -->
                <div
                    x-show="open"
                    @click.away="open = false"
                    x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="absolute right-0 mt-2 w-52 bg-surface-lowest border border-border-subtle rounded-xl shadow-md py-1.5 z-50 text-xs"
                    style="display: none;"
                >
                    <div class="px-3.5 py-2 border-b border-border-subtle">
                        <p class="font-bold text-on-surface truncate">{{ $user?->name }}</p>
                        <p class="text-on-surface-variant truncate text-[11px]">{{ $user?->email }}</p>
                        <span class="mt-1 inline-block text-[10px] font-semibold text-gold-text bg-secondary-container px-1.5 py-0.5 rounded">
                            {{ $roleName }}
                        </span>
                    </div>

                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 px-3.5 py-2 text-on-surface hover:bg-surface-container-low transition-colors">
                        <svg class="w-4 h-4 text-outline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span>Pengaturan Akun</span>
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-2.5 px-3.5 py-2 text-error hover:bg-error-container/20 transition-colors text-left">
                            <svg class="w-4 h-4 text-error" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            <span>Keluar (Logout)</span>
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</header>

