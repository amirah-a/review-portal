<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LEAD Up Evaluation Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="antialiased bg-gray-50/50 text-gray-900 min-h-screen flex flex-col justify-between relative overflow-hidden">

    <!-- Faded structural geometric watermark background layer -->
    <div class="absolute right-0 bottom-0 top-0 w-1/2 hidden lg:flex items-center justify-center pointer-events-none select-none opacity-[0.03]">
        <svg class="w-full h-full p-20 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
        </svg>
    </div>

    <!-- Minimal Header -->
    <header class="bg-white border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-amber-500 flex items-center justify-center shadow-md shadow-amber-500/20">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
                <span class="text-sm font-bold text-gray-950 uppercase tracking-wider">LEAD Up <span class="text-amber-500 font-extrabold">Evaluation</span></span>
            </div>
            <div>
                <a href="{{ route('login') }}" class="text-xs font-bold uppercase tracking-wider text-gray-500 hover:text-gray-900 transition duration-150">
                    Login
                </a>
            </div>
        </div>
    </header>

    <!-- Structural Layout Content Area -->
    <main class="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-20 lg:py-32 flex-1 flex items-center">
        <div class="max-w-2xl space-y-8 z-10">
            
            <!-- Context Capsule -->
            <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md text-[11px] font-bold bg-amber-50 text-amber-700 ring-1 ring-inset ring-amber-600/10 shadow-3xs">
                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                Internal Review Network
            </div>

            <!-- Left-Heavy Bold Headlines -->
            <div class="space-y-4">
                <h1 class="text-4xl sm:text-6xl font-black tracking-tight text-gray-950 leading-none">
                    Track Applications.<br>
                    Manage Cohorts.
                </h1>
                <p class="text-sm sm:text-base text-gray-500 font-medium max-w-xl leading-relaxed">
                    Welcome to the secure administrative interface built for processing programmatic intakes, linking real-time regional weighting indices, and managing cohort validation records cleanly.
                </p>
            </div>

            <!-- Focus Action Elements Group -->
            <div class="pt-4 flex flex-col sm:flex-row items-start sm:items-center gap-4">
                <a href="{{ route('login') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl text-xs font-extrabold uppercase tracking-wider text-white bg-amber-500 hover:bg-amber-600 shadow-sm shadow-amber-500/10 active:scale-[0.99] transition duration-150">
                    <span>Enter Ecosystem</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"></path>
                    </svg>
                </a>
            </div>

        </div>
    </main>

    <!-- Minimalistic Base Footer line -->
    <footer class="bg-white border-t border-gray-100 py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row justify-between items-center gap-2 text-xs font-semibold text-gray-400">
            <p>&copy; {{ date('Y') }} LEAD Up Platform. All rights reserved.</p>
            <div class="flex items-center gap-2 text-[10px] font-mono text-gray-400">
                <span>GATEWAY_SECURE</span>
            </div>
        </div>
    </footer>

</body>
</html>