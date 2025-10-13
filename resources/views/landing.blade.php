<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Survei Akademika FISIP UI') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-white text-[#1b1b18]">
        <div class="min-h-screen flex flex-col">
            <header class="w-full">
                <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('images/fisip-ui.png') }}" alt="Logo FISIP UI" class="h-10 w-auto">
                        <h1 class="text-lg font-semibold tracking-wide">FISIP UI</h1>
                    </div>
                    @if (Route::has('login'))
                        <nav class="hidden md:flex items-center gap-6 text-sm">
                            <a href="#tentang" class="hover:text-indigo-700">Tentang</a>
                            <a href="#akademik" class="hover:text-indigo-700">Akademik</a>
                            <a href="#kontak" class="hover:text-indigo-700">Kontak</a>
                            @auth
                                <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded-md border border-gray-300 hover:bg-gray-50">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="px-4 py-2 rounded-md bg-indigo-600 text-white hover:bg-indigo-700">Masuk</a>
                            @endauth
                        </nav>
                    @endif
                </div>
            </header>

            <main class="flex-1">
                <!-- Hero ala FISIP UI -->
                <section class="relative">
                    <div class="absolute inset-0">
                        <img src="{{ asset('images/fisip-ui.png') }}" alt="FISIP UI" class="h-[60vh] md:h-[70vh] w-full object-cover">
                        <div class="absolute inset-0 bg-black/30"></div>
                    </div>
                    <div class="relative max-w-7xl mx-auto px-6 h-[60vh] md:h-[70vh] flex items-center">
                        <div class="text-white drop-shadow-lg">
                            <p class="text-base md:text-lg tracking-wide">SELAMAT DATANG DI</p>
                            <h2 class="mt-2 text-3xl md:text-5xl lg:text-6xl font-extrabold leading-tight">
                                SURVEI KEPUASAN AKADEMIKA LAYANAN OPF FISIP<br class="hidden md:block"> UNIVERSITAS INDONESIA
                            </h2>
                            <div class="mt-6 flex gap-3">
                                @auth
                                    <a href="{{ route('dashboard') }}" class="px-5 py-2 rounded-md bg-indigo-600 text-white hover:bg-indigo-700">Mulai Isi Survei</a>
                                @else
                                    <a href="{{ route('login') }}" class="px-5 py-2 rounded-md bg-indigo-600 text-white hover:bg-indigo-700">Masuk</a>
                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}" class="px-5 py-2 rounded-md border border-white/70 text-white hover:bg-white/10">Daftar</a>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Seksi informasi ringkas -->
                <section id="tentang" class="max-w-7xl mx-auto px-6 py-12">
                    <div class="grid md:grid-cols-3 gap-6">
                        <div class="p-6 rounded-xl border border-gray-200">
                            <h3 class="font-semibold text-gray-800">Survei Akademika</h3>
                            <p class="mt-2 text-sm text-gray-600">Sampaikan masukan Anda untuk peningkatan layanan akademik FISIP UI.</p>
                        </div>
                        <div class="p-6 rounded-xl border border-gray-200">
                            <h3 class="font-semibold text-gray-800">Mudah & Cepat</h3>
                            <p class="mt-2 text-sm text-gray-600">Antarmuka sederhana, responsif, dan dapat diakses dari perangkat apapun.</p>
                        </div>
                        <div class="p-6 rounded-xl border border-gray-200">
                            <h3 class="font-semibold text-gray-800">Data Terjaga</h3>
                            <p class="mt-2 text-sm text-gray-600">Jawaban digunakan untuk evaluasi internal dan tetap rahasia.</p>
                        </div>
                    </div>
                </section>
            </main>

            <footer id="kontak" class="py-6 text-center text-sm text-gray-500">
                © {{ date('Y') }} FISIP UI • Survei Akademika
            </footer>
        </div>
    </body>
    </html>


