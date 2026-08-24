<x-app-layout>

    {{-- HEADER --}}
    <x-slot name="header">

        <div class="flex items-center justify-between border-b border-gray-100 pb-5">

            <div>

                <h2 class="text-2xl font-bold tracking-tight text-gray-950">
                    Attendance Overview
                </h2>

                <p class="text-sm font-medium text-gray-500 mt-1">
                    Select a programme centre to view attendance records.
                </p>

            </div>

        </div>

    </x-slot>


    <div class="py-10 bg-gray-50/50 min-h-screen">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- SECTION HEADER --}}
            <div class="border-b border-gray-200 pb-4 mb-6">

                <h3 class="text-lg font-bold text-gray-900 tracking-tight">
                    Programme Centres
                </h3>

                <p class="text-sm text-gray-500 mt-1">
                    View attendance records by centre.
                </p>

            </div>


            {{-- CENTRE GRID --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                @forelse($centres as $centre)

                    <a
                        href="{{ route('chairman.attendance.days', $centre->id) }}"
                        class="group bg-white rounded-xl border border-gray-200/80
                               hover:border-amber-300 hover:shadow-lg
                               hover:-translate-y-0.5 transition duration-300
                               p-6 flex flex-col justify-between"
                    >

                        <div>

                            <div class="flex items-start justify-between gap-4">

                                <div>

                                    <span
                                        class="inline-flex items-center rounded-md
                                               bg-amber-50 px-2 py-0.5 text-xs
                                               font-medium text-amber-700
                                               ring-1 ring-inset ring-amber-700/10"
                                    >
                                        {{ $centre->location }}
                                    </span>

                                    <h4
                                        class="text-gray-900 font-bold text-base
                                               leading-snug group-hover:text-amber-600
                                               transition pt-2"
                                    >
                                        {{ $centre->name }}
                                    </h4>

                                </div>

                                <div
                                    class="text-gray-400 group-hover:text-amber-500
                                           transition"
                                >

                                    <svg
                                        class="w-5 h-5"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"
                                        />
                                    </svg>

                                </div>

                            </div>

                        </div>


                        <div
                            class="mt-6 pt-4 border-t border-gray-50"
                        >

                            <span
                                class="text-xs font-semibold text-amber-600"
                            >
                                View Attendance Records
                            </span>

                        </div>

                    </a>

                @empty

                    <div class="col-span-full bg-white rounded-xl border p-8 text-center">

                        <p class="text-gray-500">
                            No programme centres found.
                        </p>

                    </div>

                @endforelse

            </div>

        </div>

    </div>

</x-app-layout>
