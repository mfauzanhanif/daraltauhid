<div class="h-full px-3 pb-4 overflow-y-auto bg-white dark:bg-gray-800">
    <div class="mb-4 px-2">
        <span class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Menu Utama</span>
    </div>
    <ul class="space-y-2 font-medium">

        <!-- Dashboard -->
        <li>
            <a href="{{ route('dashboard') }}" wire:navigate
                class="flex items-center p-2 rounded-lg group {{ request()->routeIs('dashboard') ? 'text-white bg-emerald-600 hover:bg-emerald-700 shadow-md' : 'text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('dashboard') ? '' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white' }}"
                    fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                    </path>
                </svg>
                <span class="ms-3">Dashboard</span>
            </a>
        </li>

        <!-- Data Master Dropdown -->
        <li>
            <button type="button"
                class="flex items-center w-full p-2 text-base text-gray-900 dark:text-white transition duration-75 rounded-lg group hover:bg-gray-100 dark:hover:bg-gray-700"
                aria-controls="dropdown-master" data-collapse-toggle="dropdown-master">
                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 transition duration-75 group-hover:text-gray-900 dark:group-hover:text-white"
                    fill="currentColor" viewBox="0 0 20 20">
                    <path d="M3 12v3c0 1.657 3.134 3 7 3s7-1.343 7-3v-3c0 1.657-3.134 3-7 3s-7-1.343-7-3z"></path>
                    <path d="M3 7v3c0 1.657 3.134 3 7 3s7-1.343 7-3V7c0 1.657-3.134 3-7 3S3 8.657 3 7z"></path>
                    <path d="M17 5c0 1.657-3.134 3-7 3S3 6.657 3 5s3.134-3 7-3 7 1.343 7 3z"></path>
                </svg>
                <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">Data Master</span>
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 10 6">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m1 1 4 4 4-4" />
                </svg>
            </button>
            <ul id="dropdown-master" class="hidden py-2 space-y-2">
                <li>
                    <a href="#"
                        class="flex items-center w-full p-2 text-gray-900 dark:text-white transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:hover:bg-gray-700">Data
                        Santri</a>
                </li>
                <li>
                    <a href="#"
                        class="flex items-center w-full p-2 text-gray-900 dark:text-white transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:hover:bg-gray-700">Data
                        Guru / Asatidz</a>
                </li>
                <li>
                    <a href="#"
                        class="flex items-center w-full p-2 text-gray-900 dark:text-white transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:hover:bg-gray-700">Data
                        Kelas</a>
                </li>
            </ul>
        </li>

        <!-- Keuangan -->
        <li>
            <a href="#"
                class="flex items-center p-2 text-gray-900 dark:text-white rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 group">
                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 transition duration-75 group-hover:text-gray-900 dark:group-hover:text-white"
                    fill="currentColor" viewBox="0 0 20 20">
                    <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4z"></path>
                    <path fill-rule="evenodd"
                        d="M6 8a2 2 0 012-2h8a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2V8zm5 2a1 1 0 100 2 1 1 0 000-2zm-3 1a3 3 0 116 0 3 3 0 01-6 0z"
                        clip-rule="evenodd"></path>
                </svg>
                <span class="flex-1 ms-3 whitespace-nowrap">Keuangan</span>
            </a>
        </li>

        <!-- Akademik -->
        <li>
            <a href="#"
                class="flex items-center p-2 text-gray-900 dark:text-white rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 group">
                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 transition duration-75 group-hover:text-gray-900 dark:group-hover:text-white"
                    fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0z">
                    </path>
                </svg>
                <span class="flex-1 ms-3 whitespace-nowrap">Akademik</span>
            </a>
        </li>
    </ul>
</div>