<div>
    <x-slot name="header">
        <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-700 pb-5">
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-gray-950 dark:text-gray-100">
                    Attendance Register
                </h2>

                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mt-1">
                    Record daily attendance for your assigned centre.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-10 bg-gray-50/50 dark:bg-gray-900/50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Success Message --}}
            @if (session()->has('status'))
                <div class="p-4 bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-800 rounded-xl text-emerald-700 dark:text-emerald-300 font-medium text-sm">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Main Card --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">

                {{-- Header --}}
                <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex flex-col md:flex-row md:items-center justify-between gap-5">
                    <div>
                        <div class="flex items-center gap-3">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">
                                Attendance Log
                            </h3>

                            <span class="bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800/60 px-3 py-1 rounded-lg text-xs font-semibold">
                                {{ $this->centreName }}
                            </span>
                        </div>

                        {{-- Date Selector --}}
                        <div class="flex items-center gap-3 mt-4">
                            <input type="date" 
                                wire:model.live="selectedDate" 
                                max="{{ now()->format('Y-m-d') }}"
                                class="rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-sm font-semibold text-gray-800 dark:text-gray-200 focus:border-amber-500 focus:ring-amber-500">

                            @if ($selectedDate === now()->format('Y-m-d'))
                                <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 dark:bg-emerald-950/40 px-3 py-1 rounded-md border border-emerald-200 dark:border-emerald-800">
                                    Today
                                </span>
                            @else
                                <button type="button"
                                    wire:click="$set('selectedDate','{{ now()->format('Y-m-d') }}')"
                                    class="text-xs font-semibold text-amber-600 hover:underline">
                                    Jump to Today
                                </button>
                            @endif
                        </div>
                    </div>

                    {{-- Save Button --}}
                    <button wire:click="saveAttendance" wire:loading.attr="disabled"
                        class="inline-flex items-center justify-center px-5 py-3 rounded-lg bg-amber-500 hover:bg-amber-600 text-white font-semibold text-sm transition disabled:opacity-50">

                        <span wire:loading.remove wire:target="saveAttendance">
                            Save Attendance
                        </span>

                        <span wire:loading wire:target="saveAttendance">
                            Saving...
                        </span>
                    </button>
                </div>

                {{-- Summary Bar --}}
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/40 border-b border-gray-100 dark:border-gray-700 flex flex-wrap justify-between gap-4">
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        Total Participants:
                        <span class="font-bold text-gray-900 dark:text-gray-100">
                            {{ $this->participants->count() }}
                        </span>
                    </div>

                    {{-- Real-Time Summary Output --}}
                    <div class="flex gap-5 text-sm font-semibold">
                        <span class="text-emerald-600">
                            {{ $this->summary['present'] }} Present
                        </span>

                        <span class="text-amber-600">
                            {{ $this->summary['late'] }} Late
                        </span>

                        <span class="text-red-600">
                            {{ $this->summary['absent'] }} Absent
                        </span>

                        <span class="text-blue-600">
                            {{ $this->summary['excused'] }} Excused
                        </span>
                    </div>
                </div>

                {{-- Table --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-900/50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs uppercase text-gray-500 font-semibold">
                                    Participant Name
                                </th>

                                <th class="px-6 py-3 text-right text-xs uppercase text-gray-500 font-semibold">
                                    Attendance Status
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse($this->participants as $participant)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition" wire:key="participant-{{ $participant->APL_ID }}">
                                    <td class="px-6 py-4 font-bold text-gray-900 dark:text-gray-100">
                                        {{ $participant->APL_FName }} {{ $participant->APL_LName }}
                                    </td>

                                    <td class="px-6 py-4 text-right">
                                        <select wire:model.live="attendance.{{ $participant->APL_ID }}"
                                            class="rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-sm font-medium focus:border-amber-500 focus:ring-amber-500">

                                            <option value="">
                                                Select Status
                                            </option>

                                            <option value="Present">
                                                Present
                                            </option>

                                            <option value="Late">
                                                Late
                                            </option>

                                            <option value="Absent">
                                                Absent
                                            </option>

                                            <option value="Excused">
                                                Excused
                                            </option>
                                        </select>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                        No accepted participants found for this centre.
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