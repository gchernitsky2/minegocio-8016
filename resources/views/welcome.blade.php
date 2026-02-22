<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MiNegocio - Control financiero para tu negocio</title>
    <meta name="description" content="Controla ventas, gastos e inventario de tu negocio de forma simple y efectiva.">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet" />
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(2deg); }
        }
        @keyframes glow {
            0%, 100% { opacity: 0.4; }
            50% { opacity: 0.7; }
        }
        .float-1 { animation: float 6s ease-in-out infinite; }
        .float-2 { animation: float 8s ease-in-out infinite 1s; }
        .float-3 { animation: float 7s ease-in-out infinite 2s; }
        .glow { animation: glow 4s ease-in-out infinite; }
    </style>
</head>
<body class="antialiased bg-[#fafaff] text-slate-900" style="font-family: 'Inter', system-ui, sans-serif;">

    {{-- Navbar --}}
    <nav class="fixed top-0 z-50 w-full bg-white/80 backdrop-blur-xl border-b border-indigo-50">
        <div class="mx-auto flex max-w-6xl items-center justify-between px-6 py-3.5">
            <a href="/" class="flex items-center gap-2.5">
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-600 to-violet-600 shadow-lg shadow-indigo-500/25">
                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <span class="text-lg font-extrabold tracking-tight bg-gradient-to-r from-indigo-700 to-violet-600 bg-clip-text text-transparent">MiNegocio</span>
            </a>
            <div class="flex items-center gap-3">
                <a href="{{ url('/admin') }}" class="rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600 px-5 py-2.5 text-sm font-bold text-white shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40 hover:scale-[1.02] transition-all duration-200">
                    Ir al panel
                </a>
            </div>
        </div>
    </nav>

    {{-- Hero --}}
    <section class="relative pt-32 pb-24 overflow-hidden">
        {{-- Decorative blobs --}}
        <div class="absolute inset-0 -z-10 overflow-hidden">
            <div class="float-1 glow absolute top-10 left-[15%] w-80 h-80 bg-indigo-200 rounded-full blur-3xl"></div>
            <div class="float-2 glow absolute top-40 right-[10%] w-96 h-96 bg-violet-200 rounded-full blur-3xl"></div>
            <div class="float-3 glow absolute bottom-0 left-[40%] w-72 h-72 bg-emerald-100 rounded-full blur-3xl"></div>
        </div>

        <div class="mx-auto max-w-4xl px-6 text-center">
            <div class="inline-flex items-center gap-2 rounded-full bg-indigo-50 border border-indigo-100 px-4 py-1.5 text-xs font-bold text-indigo-700 mb-8 shadow-sm">
                <svg class="w-3.5 h-3.5 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                Simple, rapido y profesional
            </div>
            <h1 class="text-5xl sm:text-6xl lg:text-7xl font-extrabold leading-[1.1] tracking-tight">
                Controla las finanzas<br>
                <span class="bg-gradient-to-r from-indigo-600 via-violet-600 to-purple-600 bg-clip-text text-transparent">de tu negocio</span>
            </h1>
            <p class="mx-auto mt-6 max-w-2xl text-lg text-slate-500 leading-relaxed">
                Registra ventas y gastos, controla inventario y ve reportes claros. Todo lo que necesitas para saber como va tu negocio. Sin complicaciones.
            </p>
            <div class="mt-10 flex items-center justify-center gap-4">
                <a href="{{ url('/admin') }}" class="group rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600 px-7 py-3.5 text-sm font-bold text-white shadow-xl shadow-indigo-500/25 hover:shadow-indigo-500/40 hover:scale-[1.03] transition-all duration-200 flex items-center gap-2">
                    Empezar ahora
                    <svg class="w-4 h-4 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                </a>
                <a href="#features" class="rounded-xl bg-white px-7 py-3.5 text-sm font-bold text-slate-700 ring-1 ring-slate-200 hover:ring-indigo-200 hover:bg-indigo-50/50 transition-all duration-200 shadow-sm">
                    Ver funciones
                </a>
            </div>
        </div>
    </section>

    {{-- Features --}}
    <section class="py-24 bg-white" id="features">
        <div class="mx-auto max-w-6xl px-6">
            <div class="text-center mb-16">
                <p class="text-sm font-bold text-indigo-600 uppercase tracking-wider mb-3">Funcionalidades</p>
                <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-slate-900">Todo lo que necesitas</h2>
                <p class="mt-3 text-slate-500 max-w-xl mx-auto">Sin funciones innecesarias. Solo lo esencial para manejar las finanzas de tu negocio.</p>
            </div>
            <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-4">
                @foreach ([
                    ['Ventas', 'Registra ventas con monto, fecha y descripcion en segundos.', 'from-emerald-500 to-emerald-600', 'shadow-emerald-500/20', 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ['Gastos', 'Organiza gastos por categorias y controla en que se va tu dinero.', 'from-rose-500 to-rose-600', 'shadow-rose-500/20', 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z'],
                    ['Inventario', 'Controla stock de productos con entradas y salidas automaticas.', 'from-violet-500 to-violet-600', 'shadow-violet-500/20', 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
                    ['Reportes', '6 tipos de reportes con graficas interactivas para tomar decisiones.', 'from-indigo-500 to-indigo-600', 'shadow-indigo-500/20', 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
                ] as [$title, $desc, $gradient, $shadow, $icon])
                    <div class="group rounded-2xl bg-white p-6 hover:shadow-xl transition-all duration-300 border border-slate-100 hover:border-indigo-100">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br {{ $gradient }} shadow-lg {{ $shadow }}">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"></path>
                            </svg>
                        </div>
                        <h3 class="mt-5 text-base font-bold text-slate-900">{{ $title }}</h3>
                        <p class="mt-2 text-sm text-slate-500 leading-relaxed">{{ $desc }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Stats --}}
    <section class="py-20 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-indigo-950 via-[#1a1145] to-violet-950"></div>
        <div class="absolute inset-0 overflow-hidden">
            <div class="float-2 absolute top-10 left-[20%] w-40 h-40 bg-indigo-500 rounded-full blur-3xl opacity-10"></div>
            <div class="float-3 absolute bottom-10 right-[20%] w-60 h-60 bg-violet-500 rounded-full blur-3xl opacity-10"></div>
        </div>
        <div class="mx-auto max-w-6xl px-6 relative z-10">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                @foreach ([
                    ['6', 'Tipos de reportes'],
                    ['100%', 'Gratuito'],
                    ['0', 'Complicaciones'],
                    ['24/7', 'Accesible'],
                ] as [$number, $label])
                    <div class="group">
                        <p class="text-4xl font-extrabold bg-gradient-to-r from-indigo-300 to-violet-300 bg-clip-text text-transparent group-hover:from-indigo-200 group-hover:to-violet-200 transition-all">{{ $number }}</p>
                        <p class="mt-2 text-sm text-slate-400 font-medium">{{ $label }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="py-24">
        <div class="mx-auto max-w-3xl px-6 text-center">
            <div class="rounded-3xl bg-gradient-to-br from-indigo-50 via-violet-50 to-purple-50 border border-indigo-100 p-12 md:p-16">
                <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-slate-900">Empieza a controlar tu negocio hoy</h2>
                <p class="mt-4 text-slate-500 text-lg">Es gratis, sencillo, y funciona desde cualquier dispositivo.</p>
                <a href="{{ url('/admin') }}" class="group mt-8 inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600 px-7 py-3.5 text-sm font-bold text-white shadow-xl shadow-indigo-500/25 hover:shadow-indigo-500/40 hover:scale-[1.03] transition-all duration-200">
                    Ir al panel
                    <svg class="w-4 h-4 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                </a>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="border-t border-indigo-50 bg-white py-8">
        <div class="mx-auto max-w-6xl px-6 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="flex h-6 w-6 items-center justify-center rounded-md bg-gradient-to-br from-indigo-600 to-violet-600">
                    <svg class="h-3.5 w-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <p class="text-xs text-slate-400 font-medium">&copy; {{ date('Y') }} MiNegocio</p>
            </div>
            <p class="text-xs text-slate-400">Hecho con Laravel + Filament</p>
        </div>
    </footer>

</body>
</html>
