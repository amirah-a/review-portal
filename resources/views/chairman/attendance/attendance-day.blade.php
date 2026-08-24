<x-app-layout>

    {{-- HEADER --}}
    <x-slot name="header">

        <div class="flex items-center justify-between border-b border-gray-100 pb-5">

            <div>

                <h2 class="text-2xl font-bold tracking-tight text-gray-950">
                    {{ $centre->name }}
                </h2>

                <p class="text-sm font-medium text-gray-500 mt-1">
                    Attendance for {{ \Carbon\Carbon::parse($date)->format('d F Y') }}
                </p>

            </div>


            <a href="{{ route('chairman.attendance.days', $centre->id) }}"
                class="text-sm font-semibold text-gray-500 hover:text-gray-900 transition">
                ← Back to Attendance History
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
                        Total Students
                    </p>

                    <p class="text-3xl font-black text-gray-900 mt-2">
                        {{ $summary['total'] }}
                    </p>

                </div>


                {{-- Present --}}
                <div class="relative overflow-hidden bg-white rounded-xl border border-gray-200/80 shadow-sm p-5">

                    <div class="absolute inset-y-0 left-0 w-1 bg-emerald-500"></div>

                    <p class="text-xs font-semibold text-emerald-600 uppercase tracking-wider">
                        Present
                    </p>

                    <p class="text-3xl font-black text-gray-900 mt-2">
                        {{ $summary['present'] }}
                    </p>

                </div>


                {{-- Late --}}
                <div class="relative overflow-hidden bg-white rounded-xl border border-gray-200/80 shadow-sm p-5">

                    <div class="absolute inset-y-0 left-0 w-1 bg-amber-500"></div>

                    <p class="text-xs font-semibold text-amber-600 uppercase tracking-wider">
                        Late
                    </p>

                    <p class="text-3xl font-black text-gray-900 mt-2">
                        {{ $summary['late'] }}
                    </p>

                </div>


                {{-- Absent --}}
                <div class="relative overflow-hidden bg-white rounded-xl border border-gray-200/80 shadow-sm p-5">

                    <div class="absolute inset-y-0 left-0 w-1 bg-red-500"></div>

                    <p class="text-xs font-semibold text-red-600 uppercase tracking-wider">
                        Absent
                    </p>

                    <p class="text-3xl font-black text-gray-900 mt-2">
                        {{ $summary['absent'] }}
                    </p>

                </div>


                {{-- Excused --}}
                <div class="relative overflow-hidden bg-white rounded-xl border border-gray-200/80 shadow-sm p-5">

                    <div class="absolute inset-y-0 left-0 w-1 bg-blue-500"></div>

                    <p class="text-xs font-semibold text-blue-600 uppercase tracking-wider">
                        Excused
                    </p>

                    <p class="text-3xl font-black text-gray-900 mt-2">
                        {{ $summary['excused'] }}
                    </p>

                </div>

            </div>


            {{-- STUDENT ATTENDANCE --}}
            <div>

                <div class="border-b border-gray-200 pb-4 mb-5">

                    <h3 class="text-lg font-bold text-gray-900 tracking-tight">
                        Student Attendance
                    </h3>

                    <p class="text-sm text-gray-500 mt-1">
                        Attendance recorded for
                        {{ \Carbon\Carbon::parse($date)->format('d F Y') }}
                    </p>

                </div>


                <div class="bg-white rounded-xl border border-gray-200/80 shadow-sm overflow-hidden">

                    <div class="overflow-x-auto">

                        <table class="min-w-full text-sm">

                            <thead class="bg-gray-50">

                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                                        Application ID
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                                        Student
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                                        Status
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                                        Remarks
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                                        Recorded By
                                    </th>

                                </tr>

                            </thead>


                            <tbody class="divide-y divide-gray-100">

                                @forelse($attendance as $record)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 text-gray-600">

                                            {{ $record->application_id }}

                                        </td>

                                        <td class="px-6 py-4">

                                            <div class="font-semibold text-gray-900">

                                                {{ $record->participant->APL_FName ?? 'Unknown' }}

                                                {{ $record->participant->APL_LName ?? '' }}

                                            </div>

                                        </td>


                                        <td class="px-6 py-4">

                                            @php

                                                $statusClasses = match (strtolower($record->status)) {
                                                    'present' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/10',

                                                    'late' => 'bg-amber-50 text-amber-700 ring-amber-600/10',

                                                    'absent' => 'bg-red-50 text-red-700 ring-red-600/10',

                                                    'excused' => 'bg-blue-50 text-blue-700 ring-blue-600/10',

                                                    default => 'bg-gray-50 text-gray-700 ring-gray-600/10',
                                                };

                                            @endphp


                                            <span
                                                class="inline-flex items-center rounded-md px-2.5 py-1 text-xs font-semibold ring-1 ring-inset {{ $statusClasses }}">
                                                {{ ucfirst($record->status) }}
                                            </span>

                                        </td>


                                        <td class="px-6 py-4 text-gray-600">

                                            {{ $record->remarks ?: '—' }}

                                        </td>


                                        <td class="px-6 py-4 text-gray-600">

                                            {{ $record->recorder->name ?? '—' }}

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                            No attendance records found for this date.
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
