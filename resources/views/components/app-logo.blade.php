@props([
'sidebar' => false,
])

<a {{ $attributes->merge(['class' => 'flex items-center gap-2']) }}>
    <span class="flex aspect-square size-8 items-center justify-center rounded-md bg-emerald-600 text-white">
        <x-app-logo-icon class="size-5 fill-current" />
    </span>
    <span class="font-semibold text-gray-900 dark:text-white">Dar Al-Tauhid</span>
</a>