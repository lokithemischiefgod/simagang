<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center">
            <!-- Logo + Links -->
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ auth()->check()
                        ? (auth()->user()->role === 'peserta'
                            ? route('peserta.dashboard')
                            : (auth()->user()->role === 'superadmin'
                                ? route('superadmin.admins.index')
                                : route('admin.pengajuan.index')))
                        : route('pengajuan.create') }}"
                    class="flex items-center gap-3">

                        <!-- Logo Lintasarta -->
                        <img src="{{ asset('images/lintasarta.png') }}"
                            alt="Lintasarta"
                            class="h-8 w-auto object-contain">

                        <!-- Nama Sistem -->
                        <span class="text-sm font-semibold text-gray-700 tracking-wide">
                            SIMAGANG
                        </span>

                    </a>
                </div>  

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    @auth
                        {{-- ADMIN & SUPERADMIN: menu operasional --}}
                        @if (in_array(auth()->user()->role, ['admin','superadmin']))
                            <x-nav-link :href="route('admin.pengajuan.index')" :active="request()->routeIs('admin.pengajuan.*')">
                                Pengajuan
                            </x-nav-link>

                            <x-nav-link :href="route('admin.absensi.index')" :active="request()->routeIs('admin.absensi.*')">
                                Absensi
                            </x-nav-link>

                            <x-nav-link :href="route('admin.peserta.index')" :active="request()->routeIs('admin.peserta.*')">
                                Peserta
                            </x-nav-link>
                        @endif

                        {{-- SUPERADMIN: menu tambahan --}}
                        @if (auth()->user()->role === 'superadmin')
                            <x-nav-link :href="route('superadmin.admins.index')" :active="request()->routeIs('superadmin.*')">
                                Manajemen Admin
                            </x-nav-link>
                        @endif

                        @if (auth()->user()->role === 'peserta')
                            <x-nav-link :href="route('peserta.dashboard')" :active="request()->routeIs('peserta.*')">
                                Dashboard
                            </x-nav-link>
                        @endif
                        
                    @endauth
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center ml-auto">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        @if (auth()->user()->must_change_password)
                            <x-dropdown-link :href="route('profile.edit')">
                                Ganti Password
                            </x-dropdown-link>
                        @else
                            <x-dropdown-link :href="route('profile.edit')">
                                Akun
                            </x-dropdown-link>
                        @endif

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger (mobile) -->
            <div class="-me-2 flex items-center sm:hidden ml-auto">
                <button @click="open = ! open"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }"
                              class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }"
                              class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @auth
                @if (auth()->user()->must_change_password)
                    <x-responsive-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.edit')">
                        Ganti Password
                    </x-responsive-nav-link>
                @else
                    @if (auth()->user()->role === 'peserta')
                        <x-responsive-nav-link :href="route('peserta.dashboard')" :active="request()->routeIs('peserta.*')">
                            Dashboard
                        </x-responsive-nav-link>
                    @endif

                    @if (in_array(auth()->user()->role, ['admin','superadmin']))
                        <x-responsive-nav-link :href="route('admin.pengajuan.index')" :active="request()->routeIs('admin.pengajuan.*')">
                            Pengajuan
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('admin.absensi.index')" :active="request()->routeIs('admin.absensi.*')">
                            Absensi
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('admin.peserta.index')" :active="request()->routeIs('admin.peserta.*')">
                            Peserta
                        </x-responsive-nav-link>
                    @endif

                    @if (auth()->user()->role === 'superadmin')
                        <x-responsive-nav-link :href="route('superadmin.admins.index')" :active="request()->routeIs('superadmin.*')">
                            Manajemen Admin
                        </x-responsive-nav-link>
                    @endif
                @endif
            @endauth
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
