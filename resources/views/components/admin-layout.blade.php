<x-app-layout>
    @isset($header)
        <x-slot name="header">
            {{ $header }}
        </x-slot>
    @endisset

    {{ $slot }}
    
    @stack('scripts')
</x-app-layout>