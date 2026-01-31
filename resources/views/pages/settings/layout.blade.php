<div class="flex items-start max-md:flex-col">
    <div class="me-10 w-full pb-4 md:w-[220px]">
        <nav class="flex flex-col gap-1" aria-label="{{ __('Pengaturan') }}">
            <a href="{{ route('profile.edit') }}" wire:navigate class="flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('profile.edit') ? 'bg-gray-100 text-gray-900 dark:bg-gray-700 dark:text-white' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700' }}">
                {{ __('Profil') }}
            </a>
            <a href="{{ route('user-password.edit') }}" wire:navigate class="flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('user-password.edit') ? 'bg-gray-100 text-gray-900 dark:bg-gray-700 dark:text-white' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700' }}">
                {{ __('Kata Sandi') }}
            </a>
            @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                <a href="{{ route('two-factor.show') }}" wire:navigate class="flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('two-factor.show') ? 'bg-gray-100 text-gray-900 dark:bg-gray-700 dark:text-white' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700' }}">
                    {{ __('Autentikasi Dua Faktor') }}
                </a>
            @endif
            <a href="{{ route('appearance.edit') }}" wire:navigate class="flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('appearance.edit') ? 'bg-gray-100 text-gray-900 dark:bg-gray-700 dark:text-white' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700' }}">
                {{ __('Tampilan') }}
            </a>
        </nav>
    </div>

    <hr class="md:hidden border-gray-200 dark:border-gray-700 w-full" />

    <div class="flex-1 self-stretch max-md:pt-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $heading ?? '' }}</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $subheading ?? '' }}</p>

        <div class="mt-5 w-full max-w-lg">
            {{ $slot }}
        </div>
    </div>
</div>
