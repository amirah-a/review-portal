<x-app-layout>

    {{-- HEADER --}}
    <x-slot name="header">

        <div class="flex items-center justify-between border-b border-gray-100 pb-5">

            <div>

                <h2 class="text-2xl font-bold tracking-tight text-gray-950">
                    {{ $centre->name }}
                </h2>

                <p class="text-sm font-medium text-gray-500 mt-1">
                    Attendance History · {{ $centre->location }}
                </p>

            </div>


            <a
                href="{{ route('chairman.attendance.centres') }}"
                class="text-sm font-semibold text-gray-500 hover:text-gray-900 transition"
            >
                ← Back to Centres
            </a>

        </div>

    </x-slot>


    <div class="py-10 bg-gray-50/50 min-h-screen">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-10">


            {{-- KPI STRIP --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">


                {{-- Total --}}
                <div class="relative overflow-hidden bg-white rounded-xl border border-gray-200/80 shadow-sm p-5">

                    <div class="absolute inset-y-0 left-0 w-1 bg-gray-900"></div>

                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">
                        Total Records
                    </p>

                    <p class="text-3xl font-black text-gray-900 mt-2">
                        {{ number_format($totalRecords) }}
                    </p>

                </div>


                {{-- Present --}}
                <div class="relative overflow-hidden bg-white rounded-xl border border-gray-200/80 shadow-sm p-5">

                    <div class="absolute inset-y-0 left-0 w-1 bg-emerald-500"></div>

                    <p class="text-xs font-semibold text-emerald-600 uppercase tracking-wider">
                        Present
                    </p>

                    <p class="text-3xl font-black text-gray-900 mt-2">
                        {{ number_format($present) }}
                    </p>

                </div>


                {{-- Late --}}
                <div class="relative overflow-hidden bg-white rounded-xl border border-gray-200/80 shadow-sm p-5">

                    <div class="absolute inset-y-0 left-0 w-1 bg-amber-500"></div>

                    <p class="text-xs font-semibold text-amber-600 uppercase tracking-wider">
                        Late
                    </p>

                    <p class="text-3xl font-black text-gray-900 mt-2">
                        {{ number_format($late) }}
                    </p>

                </div>


                {{-- Absent --}}
                <div class="relative overflow-hidden bg-white rounded-xl border border-gray-200/80 shadow-sm p-5">

                    <div class="absolute inset-y-0 left-0 w-1 bg-red-500"></div>

                    <p class="text-xs font-semibold text-red-600 uppercase tracking-wider">
                        Absent
                    </p>

                    <p class="text-3xl font-black text-gray-900 mt-2">
                        {{ number_format($absent) }}
                    </p>

                </div>


                {{-- Excused --}}
                <div class="relative overflow-hidden bg-white rounded-xl border border-gray-200/80 shadow-sm p-5">

                    <div class="absolute inset-y-0 left-0 w-1 bg-blue-500"></div>

                    <p class="text-xs font-semibold text-blue-600 uppercase tracking-wider">
                        Excused
                    </p>

                    <p class="text-3xl font-black text-gray-900 mt-2">
                        {{ number_format($excused) }}
                    </p>

                </div>

            </div>


            {{-- ATTENDANCE DAYS --}}
            <div>

                <div class="border-b border-gray-200 pb-4 mb-5">

                    <h3 class="text-lg font-bold text-gray-900 tracking-tight">
                        Recorded Attendance
                    </h3>

                    <p class="text-sm text-gray-500 mt-1">
                        Select a date to view all students and their attendance.
                    </p>

                </div>


                <div class="bg-white rounded-xl border border-gray-200/80 shadow-sm overflow-hidden">

                    <div class="overflow-x-auto">

                        <table class="min-w-full text-sm">

                            <thead class="bg-gray-50">

                                <tr>

                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                                        Date
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                                        Total
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-semibold text-emerald-600 uppercase">
                                        Present
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-semibold text-amber-600 uppercase">
                                        Late
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-semibold text-red-600 uppercase">
                                        Absent
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-semibold text-blue-600 uppercase">
                                        Excused
                                    </th>

                                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">
                                    </th>

                                </tr>

                            </thead>


                            <tbody class="divide-y divide-gray-100">

                                @forelse($days as $day)

                                    <tr
                                        onclick="window.location='{{ route('chairman.attendance.day', [$centre->id, $day->attendance_date->format('Y-m-d')]) }}'"
                                        class="hover:bg-gray-50 cursor-pointer transition"
                                    >

                                        <td class="px-6 py-4 font-semibold text-gray-900">

                                            {{ $day->attendance_date->format('d M Y') }}

                                        </td>


                                        <td class="px-6 py-4 font-semibold">

                                            {{ $day->total }}

                                        </td>


                                        <td class="px-6 py-4 text-emerald-600 font-semibold">

                                            {{ $day->present }}

                                        </td>


                                        <td class="px-6 py-4 text-amber-600 font-semibold">

                                            {{ $day->late }}

                                        </td>


                                        <td class="px-6 py-4 text-red-600 font-semibold">

                                            {{ $day->absent }}

                                        </td>


                                        <td class="px-6 py-4 text-blue-600 font-semibold">

                                            {{ $day->excused }}

                                        </td>


                                        <td class="px-6 py-4 text-right">

                                            <span class="text-xs font-semibold text-amber-600">
                                                View Students →
                                            </span>

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td
                                            colspan="7"
                                            class="px-6 py-10 text-center text-gray-500"
                                        >
                                            No attendance has been recorded for this centre.
                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>
