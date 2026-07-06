<x-app-layout>
    <div class="py-10 bg-gray-50/50 dark:bg-gray-900/50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- HEADER SECTON --}}

            <x-slot name="header">
                <div class="flex items-center justify-between border-b border-gray-100 pb-5">
                    <div>
                        <h2 class="text-2xl font-bold tracking-tight text-gray-950">
                            {{ __('All Applications') }}
                        </h2>
                        <p class="text-sm font-medium text-gray-500 mt-1">
                            View all applications submitted for the LEAD Up Programme. Filter records instantly by status, programme, or assigned centre, or click any individual row to view deep-dive submission details.
                        </p>
                    </div>
                </div>
            </x-slot>
            
            {{-- CONTENT TABLE WRAPPER --}}
            <div
                class="mt-8 bg-white dark:bg-gray-800 rounded-xl border border-gray-200/80 dark:border-gray-700 shadow-sm overflow-hidden transition-all duration-200">
                <div
                    class="p-1 sm:p-2 bg-gray-50/50 dark:bg-gray-800/50 border-b border-gray-100 dark:border-gray-700/50">
                </div>

                <div class="overflow-x-auto p-4 sm:p-6">
                    <livewire:applications-table />
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
