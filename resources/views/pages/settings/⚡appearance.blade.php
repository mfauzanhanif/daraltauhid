<?php

use Livewire\Component;

new class extends Component {
    //
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <h1 class="sr-only">{{ __('Pengaturan Tampilan') }}</h1>

    <x-pages::settings.layout :heading="__('Tampilan')" :subheading="__('Perbarui pengaturan tampilan untuk akun Anda')">
        <div class="flex flex-wrap gap-2" x-data="{ appearance: localStorage.getItem('theme') || 'system' }">
            <button 
                type="button" 
                @click="appearance = 'light'; localStorage.setItem('theme', 'light'); document.documentElement.classList.remove('dark')"
                :class="appearance === 'light' ? 'bg-emerald-600 text-white' : 'bg-gray-100 text-gray-900 dark:bg-gray-700 dark:text-white'"
                class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium rounded-lg hover:bg-emerald-700 hover:text-white focus:outline-none focus:ring-4 focus:ring-emerald-300 dark:focus:ring-emerald-800"
            >
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"></path>
                </svg>
                {{ __('Terang') }}
            </button>
            <button 
                type="button" 
                @click="appearance = 'dark'; localStorage.setItem('theme', 'dark'); document.documentElement.classList.add('dark')"
                :class="appearance === 'dark' ? 'bg-emerald-600 text-white' : 'bg-gray-100 text-gray-900 dark:bg-gray-700 dark:text-white'"
                class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium rounded-lg hover:bg-emerald-700 hover:text-white focus:outline-none focus:ring-4 focus:ring-emerald-300 dark:focus:ring-emerald-800"
            >
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                </svg>
                {{ __('Gelap') }}
            </button>
            <button 
                type="button" 
                @click="appearance = 'system'; localStorage.removeItem('theme'); if (window.matchMedia('(prefers-color-scheme: dark)').matches) { document.documentElement.classList.add('dark') } else { document.documentElement.classList.remove('dark') }"
                :class="appearance === 'system' ? 'bg-emerald-600 text-white' : 'bg-gray-100 text-gray-900 dark:bg-gray-700 dark:text-white'"
                class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium rounded-lg hover:bg-emerald-700 hover:text-white focus:outline-none focus:ring-4 focus:ring-emerald-300 dark:focus:ring-emerald-800"
            >
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3 5a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2h-2.22l.123.489.804.804A1 1 0 0113 18H7a1 1 0 01-.707-1.707l.804-.804L7.22 15H5a2 2 0 01-2-2V5zm5.771 7H5V5h10v7H8.771z" clip-rule="evenodd"></path>
                </svg>
                {{ __('Sistem') }}
            </button>
        </div>
    </x-pages::settings.layout>
</section>
