<x-app-layout>

    {{-- HEADER --}}
    <x-slot name="header">
        <div class="flex items-center justify-between border-b border-gray-100 pb-5">
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-gray-950">
                    {{ $centre->name }}
                </h2>

                <p class="text-sm font-medium text-gray-500 mt-1">
                    {{ $centre->location }} Centre Application Overview
                </p>
            </div>

            <a href="{{ route('dashboard') }}" class="text-sm font-semibold text-gray-500 hover:text-gray-900 transition">
                ← Back to Dashboard
            </a>
        </div>
    </x-slot>


    <div class="py-10 bg-gray-50/50 min-h-screen">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-10">


            {{-- ================= KPI STRIP ================= --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">


                {{-- Total --}}
                <div class="relative overflow-hidden bg-white rounded-xl border border-gray-200/80 shadow-sm p-5">

                    <div class="absolute inset-y-0 left-0 w-1 bg-gray-900"></div>

                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">
                        Total Applications
                    </p>

                    <p class="text-3xl font-black text-gray-900 mt-2">
                        {{ $applications->count() }}
                    </p>

                </div>



                {{-- Open --}}
                <div class="relative overflow-hidden bg-white rounded-xl border border-gray-200/80 shadow-sm p-5">

                    <div class="absolute inset-y-0 left-0 w-1 bg-amber-500"></div>

                    <p class="text-xs font-semibold text-amber-600 uppercase tracking-wider">
                        Open
                    </p>

                    <p class="text-3xl font-black text-gray-900 mt-2">
                        {{ $openCount }}
                    </p>

                </div>



                {{-- Approved --}}
                <div class="relative overflow-hidden bg-white rounded-xl border border-gray-200/80 shadow-sm p-5">

                    <div class="absolute inset-y-0 left-0 w-1 bg-emerald-500"></div>

                    <p class="text-xs font-semibold text-emerald-600 uppercase tracking-wider">
                        Approved
                    </p>

                    <p class="text-3xl font-black text-gray-900 mt-2">
                        {{ $approvedCount }}
                    </p>

                </div>



                {{-- Declined --}}
                <div class="relative overflow-hidden bg-white rounded-xl border border-gray-200/80 shadow-sm p-5">

                    <div class="absolute inset-y-0 left-0 w-1 bg-red-500"></div>

                    <p class="text-xs font-semibold text-red-600 uppercase tracking-wider">
                        Declined
                    </p>

                    <p class="text-3xl font-black text-gray-900 mt-2">
                        {{ $declinedCount }}
                    </p>

                </div>


            </div>




            {{-- ================= CENTRE INFO ================= --}}
            <div class="bg-white rounded-xl border border-gray-200/80 shadow-sm p-6">

                <div class="flex items-center justify-between">

                    <div>
                        <h3 class="text-lg font-bold text-gray-900">
                            Centre Information
                        </h3>

                        <p class="text-sm text-gray-500 mt-1">
                            Programme location details
                        </p>
                    </div>


                    <span
                        class="inline-flex items-center rounded-md bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700 ring-1 ring-inset ring-amber-700/10">
                        {{ $centre->location }}
                    </span>

                </div>


                <div class="mt-5 text-sm text-gray-600">

                    <p>
                        <span class="font-semibold text-gray-900">
                            Address:
                        </span>
                        {{ $centre->address }}
                    </p>

                </div>

            </div>





            {{-- ================= APPLICATION TABLE ================= --}}
            <div>

                <div class="border-b border-gray-200 pb-4 mb-5">

                    <h3 class="text-lg font-bold text-gray-900 tracking-tight">
                        Applications
                    </h3>

                    <p class="text-sm text-gray-500 mt-1">
                        Complete list of applicants assigned to this centre
                    </p>

                </div>


                <div class="bg-white rounded-xl border border-gray-200/80 shadow-sm p-6">

                    <livewire:centre-applications-table :centre="$centre" />

                </div>


            </div>

        </div>


</x-app-layout>
