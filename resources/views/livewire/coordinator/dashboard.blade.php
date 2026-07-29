<x-app-layout>


    <x-slot name="header">

        <div class="flex items-center justify-between border-b border-gray-100 pb-5">

            <div>

                <h2 class="text-2xl font-bold tracking-tight text-gray-950">
                    Coordinator Dashboard
                </h2>


                <p class="text-sm font-medium text-gray-500 mt-1">
                    Manage daily attendance for your assigned centre.
                </p>


            </div>

        </div>

    </x-slot>




    <div class="py-10 bg-gray-50/50 min-h-screen">


        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">



            {{-- Centre Information --}}

            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">


                <div class="flex items-center justify-between">


                    <div>

                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">
                            Assigned Centre
                        </p>


                        <h3 class="text-xl font-bold text-gray-900 mt-1">
                            {{ $centre->name ?? 'No Centre Assigned' }}
                        </h3>


                        <p class="text-sm text-gray-500">
                            {{ $centre->location ?? '' }}
                        </p>


                    </div>



                    <div class="bg-amber-50 text-amber-700 px-4 py-2 rounded-lg text-sm font-semibold">

                        Coordinator

                    </div>


                </div>


            </div>





            {{-- Attendance Summary --}}

            <div class="grid grid-cols-1 md:grid-cols-5 gap-5">



                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">


                    <p class="text-xs font-semibold text-gray-400 uppercase">
                        Participants
                    </p>


                    <p class="text-3xl font-black text-gray-900 mt-2">
                        {{ $participants }}
                    </p>


                </div>





                @foreach ([['Present', $summary['present'], 'emerald'], ['Late', $summary['late'], 'amber'], ['Absent', $summary['absent'], 'red'], ['Excused', $summary['excused'], 'blue']] as $card)
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">


                        <p class="text-xs font-semibold text-gray-400 uppercase">
                            {{ $card[0] }}
                        </p>


                        <p class="text-3xl font-black text-{{ $card[2] }}-600 mt-2">
                            {{ $card[1] }}
                        </p>


                    </div>
                @endforeach



            </div>






            {{-- Attendance Action --}}

            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">


                <div class="flex items-center justify-between">


                    <div>


                        <h3 class="text-lg font-bold text-gray-900">
                            Daily Attendance
                        </h3>


                        <p class="text-sm text-gray-500">
                            Record attendance for today's session.
                        </p>


                    </div>




                    <a href="{{ route('coordinator.attendance') }}"
                        class="inline-flex items-center px-5 py-3 rounded-lg bg-amber-500 text-white font-semibold text-sm hover:bg-amber-600 transition">


                        Record Attendance


                    </a>


                </div>


            </div>







            {{-- Recent Attendance --}}

            <div class="bg-white rounded-xl border border-gray-200 shadow-sm">


                <div class="px-6 py-4 border-b border-gray-100">


                    <h3 class="font-bold text-gray-900">
                        Recent Attendance Records
                    </h3>


                </div>




                <div class="overflow-x-auto">


                    <table class="min-w-full text-sm">


                        <thead class="bg-gray-50">


                            <tr>


                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                                    Date
                                </th>


                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                                    Present
                                </th>


                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                                    Absent
                                </th>


                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                                    Recorded By
                                </th>


                            </tr>


                        </thead>




                        <tbody class="divide-y divide-gray-100">


                            @forelse($recentAttendance as $attendance)
                                <tr>


                                    <td class="px-6 py-4">
                                        {{ $attendance->date->format('d M Y') }}
                                    </td>



                                    <td class="px-6 py-4 text-emerald-600 font-semibold">
                                        {{ $attendance->present }}
                                    </td>



                                    <td class="px-6 py-4 text-red-600 font-semibold">
                                        {{ $attendance->absent }}
                                    </td>



                                    <td class="px-6 py-4">
                                        {{ $attendance->user->name ?? 'System' }}
                                    </td>


                                </tr>



                            @empty


                                <tr>

                                    <td colspan="4" class="px-6 py-6 text-center text-gray-500">

                                        No attendance recorded yet.

                                    </td>

                                </tr>
                            @endforelse



                        </tbody>


                    </table>


                </div>


            </div>




        </div>

    </div>


</x-app-layout>
