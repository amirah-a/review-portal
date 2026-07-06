<x-app-layout>

    {{-- HEADER --}}
    <x-slot name="header">
        <div class="flex items-center justify-between border-b border-gray-100 pb-5">
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-gray-950">
                    Dashboard Overview
                </h2>
                <p class="text-sm font-medium text-gray-500 mt-1">
                    Real-time metrics for programme applications and centre distribution.
                </p>
            </div>
        </div>
    </x-slot>


    <div class="py-10 bg-gray-50/50 min-h-screen">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-12">

            {{-- ================= KPI STRIP ================= --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

                {{-- Total Card --}}
                <div
                    class="relative overflow-hidden bg-white rounded-xl border border-gray-200/80 shadow-sm p-5 group hover:shadow-md transition duration-300">
                    <div class="absolute inset-y-0 left-0 w-1 bg-gray-900"></div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Applications</p>
                    <p class="text-2xl font-extrabold text-gray-900 mt-1 tracking-tight">
                        {{ number_format($totalApplications) }}
                    </p>
                    <div class="mt-3 pt-2 border-t border-gray-50 flex justify-end">
                        <a href="#"
                            class="inline-flex items-center gap-1.5 text-sm font-semibold text-gray-500 hover:text-gray-900 transition duration-200">
                            <span>View all</span>
                            <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform duration-200"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                </path>
                            </svg>
                        </a>
                    </div>
                </div>

                {{-- Open/Pending Applications Card --}}
                <div
                    class="relative overflow-hidden bg-white rounded-xl border border-gray-200/80 shadow-sm p-5 group hover:shadow-md transition duration-300">
                    <div class="absolute inset-y-0 left-0 w-1 bg-amber-500"></div>
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-semibold text-amber-600 uppercase tracking-wider">Open Applications</p>
                        <span
                            class="inline-flex items-center rounded-md bg-amber-50 px-1.5 py-0.5 text-[10px] font-medium text-amber-700 ring-1 ring-inset ring-amber-600/10">
                            {{ $totalApplications > 0 ? round(($open / $totalApplications) * 100) : 0 }}%
                        </span>
                    </div>
                    <p class="text-2xl font-extrabold text-gray-900 mt-1 tracking-tight">
                        {{ number_format($open) }}
                    </p>
                    <div class="mt-3 pt-2 border-t border-gray-50 flex justify-end">
                        <a href="#"
                            class="inline-flex items-center gap-1.5 text-sm font-semibold text-amber-600 hover:text-amber-800 transition duration-200">
                            <span>View all</span>
                            <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform duration-200"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                </path>
                            </svg>
                        </a>
                    </div>
                </div>

                {{-- Under Review Card --}}
                <div
                    class="relative overflow-hidden bg-white rounded-xl border border-gray-200/80 shadow-sm p-5 group hover:shadow-md transition duration-300">
                    <div class="absolute inset-y-0 left-0 w-1 bg-indigo-500"></div>
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-semibold text-indigo-600 uppercase tracking-wider">Pending Review</p>
                        <span
                            class="inline-flex items-center rounded-md bg-indigo-50 px-1.5 py-0.5 text-[10px] font-medium text-indigo-700 ring-1 ring-inset ring-indigo-600/10">
                            {{ $totalApplications > 0 ? round(($underReview / $totalApplications) * 100) : 0 }}%
                        </span>
                    </div>
                    <p class="text-2xl font-extrabold text-gray-900 mt-1 tracking-tight">
                        {{ number_format($underReview) }}
                    </p>
                    <div class="mt-3 pt-2 border-t border-gray-50 flex justify-end">
                        <a href="#"
                            class="inline-flex items-center gap-1.5 text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition duration-200">
                            <span>View all</span>
                            <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform duration-200"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                </path>
                            </svg>
                        </a>
                    </div>
                </div>

                {{-- Approved Card --}}
                <div
                    class="relative overflow-hidden bg-white rounded-xl border border-gray-200/80 shadow-sm p-5 group hover:shadow-md transition duration-300">
                    <div class="absolute inset-y-0 left-0 w-1 bg-emerald-500"></div>
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-semibold text-emerald-600 uppercase tracking-wider">Approved</p>
                        <span
                            class="inline-flex items-center rounded-md bg-emerald-50 px-1.5 py-0.5 text-[10px] font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/10">
                            {{ $totalApplications > 0 ? round(($approved / $totalApplications) * 100) : 0 }}%
                        </span>
                    </div>
                    <p class="text-2xl font-extrabold text-gray-900 mt-1 tracking-tight">
                        {{ number_format($approved) }}
                    </p>
                    <div class="mt-3 pt-2 border-t border-gray-50 flex justify-end">
                        <a href="#"
                            class="inline-flex items-center gap-1.5 text-sm font-semibold text-emerald-600 hover:text-emerald-800 transition duration-200">
                            <span>View all</span>
                            <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform duration-200"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                </path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            {{-- ================= SECTION TITLE ================= --}}
            <div class="border-b border-gray-200 pb-4">
                <h3 class="text-lg font-bold text-gray-900 tracking-tight">
                    Programme Centers
                </h3>
                <p class="text-sm text-gray-500 mt-0.5">
                    Live application weights across regional centers
                </p>
            </div>


            {{-- ================= CENTER GRID ================= --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                @foreach ($centres as $centre)
                    @php
                        $percentage =
                            $totalApplications > 0 ? ($centre->applications_count / $totalApplications) * 100 : 0;
                    @endphp

                    <div
                        class="group bg-white rounded-xl border border-gray-200/80 hover:border-amber-300 hover:shadow-lg hover:-translate-y-0.5 transition duration-300 p-6 flex flex-col justify-between">

                        <div>
                            {{-- CARD HEADER (Indigo -> Amber) --}}
                            <div class="flex items-start justify-between gap-4">
                                <div class="space-y-1">
                                    <span
                                        class="inline-flex items-center gap-1.5 rounded-md bg-amber-50 px-2 py-0.5 text-xs font-medium text-amber-700 ring-1 ring-inset ring-amber-700/10">
                                        {{ $centre->location }}
                                    </span>
                                    <h4
                                        class="text-gray-900 font-bold text-base leading-snug group-hover:text-amber-600 transition pt-1">
                                        {{ $centre->name }}
                                    </h4>
                                </div>

                                <div
                                    class="text-3xl font-black text-gray-900 tracking-tight bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-100">
                                    {{ $centre->applications_count ?? 0 }}
                                </div>
                            </div>

                            {{-- BAR PROGRESS (Indigo -> Amber) --}}
                            <div class="mt-6">
                                <div class="flex justify-between text-xs font-medium text-gray-400 mb-1.5">
                                    <span>Volume Weight</span>
                                    <span class="text-gray-700 font-semibold">{{ round($percentage, 1) }}%</span>
                                </div>
                                <div class="h-2 w-full bg-gray-100 rounded-full overflow-hidden">
                                    <div class="h-2 bg-gradient-to-r from-amber-500 to-amber-600 rounded-full transition-all duration-500"
                                        style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                        </div>

                        {{-- FOOTER ACTION --}}
                        <div
                            class="mt-6 pt-4 border-t border-gray-50 flex items-center justify-between text-sm text-gray-500">
                            <span class="text-xs font-medium text-gray-400">Applications metrics breakdown</span>
                            <span
                                class="flex items-center gap-1 text-xs font-semibold text-amber-600 opacity-0 group-hover:opacity-100 group-hover:translate-x-1 transition duration-300 pointer-events-none">
                                View Details
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                </svg>
                            </span>
                        </div>

                    </div>
                @endforeach

            </div>

        </div>

    </div>

</x-app-layout>