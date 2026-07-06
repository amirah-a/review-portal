@php
    // Custom wrapper to format strings safely and transform complex nested arrays into clean elements
    $safeStr = function ($val, $fallback = '—') use (&$safeStr) {
        if (is_array($val) || $val instanceof \Illuminate\Support\Collection) {
            // Recursively flatten arrays if multi-dimensional values exist
            $flatItems = [];
            array_walk_recursive($val, function ($item) use (&$flatItems) {
                if ($item !== null && $item !== '') {
                    $flatItems[] = trim((string) $item);
                }
            });

            $items = array_filter($flatItems);
            if (empty($items)) {
                return $fallback;
            }

            // Returns a clean, flexible grid layout of tiny pill badges for array entries
            $html = '<div class="flex flex-wrap gap-1.5 mt-0.5">';
            foreach ($items as $item) {
                $html .=
                    '<span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-bold bg-slate-100 dark:bg-slate-900 text-slate-800 dark:text-slate-200 border border-slate-200/40 dark:border-slate-700/30 shadow-2xs">' .
                    e($item) .
                    '</span>';
            }
            $html .= '</div>';

            return $html;
        }

        return $val !== null && $val !== '' ? e((string) $val) : $fallback;
    };

    // Safely extract string parts from name attributes for layout initials
    $fNameRaw = is_array($application->APL_FName)
        ? current($application->APL_FName) ?? 'P'
        : $application->APL_FName ?? 'P';
    $lNameRaw = is_array($application->APL_LName)
        ? current($application->APL_LName) ?? 'I'
        : $application->APL_LName ?? 'I';

    // String cast before substr protects against nested anomalies
    $fNameString = is_array($fNameRaw) ? 'P' : (string) $fNameRaw;
    $lNameString = is_array($lNameRaw) ? 'I' : (string) $lNameRaw;

    $initials = strtoupper(substr(trim($fNameString), 0, 1) . substr(trim($lNameString), 0, 1));
@endphp

<x-app-layout>
    <div class="py-10 bg-slate-50 dark:bg-slate-900 min-h-screen antialiased">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- TOP BREADCRUMB / ACTION BAR --}}
            <div class="flex items-center justify-between">
                <a href="{{ route('all-applications') }}"
                    class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-100 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to all applications
                </a>

                {{-- Quick Status Indicator --}}
                <div class="flex items-center gap-3">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Current Status:</span>
                    <span
                        class="inline-flex items-center px-3.5 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wider shadow-sm border
                        @if (($application->APL_Status ?? '') === 'approved') bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-400 dark:border-emerald-900/60
                        @elseif(($application->APL_Status ?? '') === 'rejected') bg-rose-50 text-rose-700 border-rose-200 dark:bg-rose-950/40 dark:text-rose-400 dark:border-rose-900/60
                        @else bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-950/40 dark:text-amber-400 dark:border-amber-900/60 @endif">
                        {{ str_replace('_', ' ', $application->APL_Status ?? 'pending') }}
                    </span>
                </div>
            </div>

            {{-- MAIN TWO-COLUMN LAYOUT --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

                {{-- LEFT COLUMN: DEEP-DIVE HARDCODED SECTIONS --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- HERO PROFILE CARD --}}
                    <div
                        class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/60 dark:border-slate-700/60 shadow-sm p-6 relative overflow-hidden">
                        <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-indigo-500 to-violet-600">
                        </div>

                        <div class="flex items-start gap-4">
                            <div
                                class="w-14 h-14 bg-indigo-50 dark:bg-indigo-950/60 rounded-xl flex items-center justify-center text-indigo-600 dark:text-indigo-400 shrink-0 font-extrabold text-xl tracking-tight border border-indigo-100 dark:border-indigo-900/50">
                                {{ $initials }}
                            </div>
                            <div class="space-y-1">
                                <h1
                                    class="text-2xl font-black text-slate-900 dark:text-slate-50 tracking-tight flex flex-wrap items-center gap-1.5">
                                    {!! $safeStr($application->APL_FName) !!} {!! $safeStr($application->APL_MName, '') !!} {!! $safeStr($application->APL_LName) !!}
                                </h1>
                                <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">
                                    Selected Center: <span
                                        class="text-slate-800 dark:text-slate-200 font-bold">{!! $safeStr($application->APL_Programme_Center) !!}</span>
                                </p>
                            </div>
                        </div>

                        <div
                            class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-6 pt-6 border-t border-slate-100 dark:border-slate-700/50 text-xs">
                            <div>
                                <span class="block text-slate-400 font-semibold uppercase tracking-wider mb-0.5">Date of
                                    Birth</span>
                                <span
                                    class="text-sm font-bold text-slate-800 dark:text-slate-200">{!! $safeStr($application->APL_DOB) !!}</span>
                            </div>
                            <div>
                                <span class="block text-slate-400 font-semibold uppercase tracking-wider mb-0.5">Age /
                                    Sex</span>
                                <span
                                    class="text-sm font-bold text-slate-800 dark:text-slate-200">{!! $safeStr($application->APL_Age) !!}
                                    Yrs / {!! $safeStr($application->APL_Sex) !!}</span>
                            </div>
                            <div>
                                <span class="block text-slate-400 font-semibold uppercase tracking-wider mb-0.5">Jersey
                                    Size</span>
                                <span
                                    class="text-sm font-bold text-slate-800 dark:text-slate-200 uppercase">{!! $safeStr($application->APL_Participant_Jersey_Size) !!}</span>
                            </div>
                            <div>
                                <span class="block text-slate-400 font-semibold uppercase tracking-wider mb-0.5">Meal
                                    Pref</span>
                                <span
                                    class="text-sm font-bold text-slate-800 dark:text-slate-200">{!! $safeStr($application->APL_Meal_Preference) !!}</span>
                            </div>
                        </div>
                    </div>

                    {{-- MANUAL SECTION 1: PARTICIPANT INFORMATION --}}
                    <div
                        class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/60 dark:border-slate-700/60 shadow-sm overflow-hidden">
                        <div
                            class="px-6 py-4 border-b border-slate-100 dark:border-slate-700/50 bg-slate-50/50 dark:bg-slate-800/40">
                            <h3
                                class="text-xs font-extrabold text-slate-400 dark:text-slate-400 tracking-wider uppercase">
                                Participant Information
                            </h3>
                        </div>
                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-5">
                            {{-- Address Lines --}}
                            <div class="md:col-span-2 space-y-1">
                                <span
                                    class="block text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wide">{{ $fields['APL_Address_1']->Question ?? 'Address Line 1' }}</span>
                                <div
                                    class="text-sm font-semibold text-slate-800 dark:text-slate-200 p-3 bg-slate-50 dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800 rounded-xl leading-relaxed">
                                    {!! $safeStr($application->APL_Address_1) !!}{{ !empty($application->APL_Address_2) ? ', ' . $safeStr($application->APL_Address_2, '') : '' }}
                                </div>
                            </div>

                            {{-- Region / Area --}}
                            <div class="space-y-1">
                                <span
                                    class="block text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wide">{{ $fields['APL_Area']->Question ?? 'Region/Area' }}</span>
                                <div
                                    class="text-sm font-semibold text-slate-800 dark:text-slate-200 p-3 bg-slate-50 dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800 rounded-xl leading-relaxed">
                                    {!! $safeStr($application->APL_Area) !!}
                                </div>
                            </div>

                            {{-- Citizen Check --}}
                            <div class="space-y-1">
                                <span
                                    class="block text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wide">{{ $fields['APL_Is_Citizen']->Question ?? 'Trinidad & Tobago Citizen?' }}</span>
                                <div
                                    class="text-sm font-semibold text-slate-800 dark:text-slate-200 p-3 bg-slate-50 dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800 rounded-xl leading-relaxed">
                                    {!! $safeStr($application->APL_Is_Citizen) !!}
                                </div>
                            </div>

                            {{-- Identification Document Details --}}
                            <div class="space-y-1">
                                <span
                                    class="block text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wide">{{ $fields['APL_ID_Type']->Question ?? 'Identification Document' }}</span>
                                <div
                                    class="text-sm font-semibold text-slate-800 dark:text-slate-200 p-3 bg-slate-50 dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800 rounded-xl leading-relaxed">
                                    {!! $safeStr($application->APL_ID_Type) !!}
                                </div>
                            </div>
                            <div class="space-y-1">
                                <span
                                    class="block text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wide">{{ $fields['APL_ID_Number']->Question ?? 'Identification Number' }}</span>
                                <div
                                    class="text-sm font-semibold text-slate-800 dark:text-slate-200 p-3 bg-slate-50 dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800 rounded-xl leading-relaxed font-mono">
                                    {!! $safeStr($application->APL_ID_Number) !!}
                                </div>
                            </div>

                            {{-- Education Baseline --}}

                            {{-- 1. Enrolled Status Toggled Banner --}}
                            <div class="space-y-1">
                                <span
                                    class="block text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wide">
                                    {{ $fields['APL_Enrolled']->Question ?? 'Enrolled in training?' }}
                                </span>
                                <div
                                    class="text-sm font-semibold text-slate-800 dark:text-slate-200 p-3 bg-slate-50 dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800 rounded-xl leading-relaxed">
                                    {!! $safeStr($application->APL_Enrolled) !!}
                                </div>
                            </div>

                            @if ($application->APL_Enrolled === 'Yes')
                                {{-- Path A: Enrolled --}}
                                <div class="space-y-1">
                                    <span
                                        class="block text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wide">
                                        {{ $fields['APL_School_Enrolled']->Question ?? 'Name of School Currently Enrolled' }}
                                    </span>
                                    <div
                                        class="text-sm font-semibold text-slate-800 dark:text-slate-200 p-3 bg-slate-50 dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800 rounded-xl leading-relaxed">
                                        {!! $safeStr($application->APL_School_Enrolled) !!}
                                    </div>
                                </div>
                            @else
                                {{-- Path B: Not Enrolled --}}
                                <div class="space-y-1">
                                    <span
                                        class="block text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wide">
                                        {{ $fields['APL_Education_Level']->Question ?? 'Level of education attained' }}
                                    </span>
                                    <div
                                        class="text-sm font-semibold text-slate-800 dark:text-slate-200 p-3 bg-slate-50 dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800 rounded-xl leading-relaxed">
                                        {!! $safeStr($application->APL_Education_Level) !!}
                                    </div>
                                </div>

                                <div class="space-y-1">
                                    <span
                                        class="block text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wide">
                                        {{ $fields['APL_Last_School_Name']->Question ?? 'Name of Last School Enrolled' }}
                                    </span>
                                    <div
                                        class="text-sm font-semibold text-slate-800 dark:text-slate-200 p-3 bg-slate-50 dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800 rounded-xl leading-relaxed">
                                        {!! $safeStr($application->APL_Last_School_Name) !!}
                                    </div>
                                </div>
                            @endif


                            {{-- Medical Declarations Block --}}
                            <div class="md:col-span-2 space-y-1">
                                <span
                                    class="block text-[11px] font-bold text-rose-500 uppercase tracking-wide">{{ $fields['APL_Allergy_Signs']->Question ?? 'Allergy Signs / Reactions' }}</span>
                                <div
                                    class="text-sm font-semibold text-slate-800 dark:text-slate-200 p-3 bg-rose-50/50 dark:bg-rose-950/10 border border-rose-100 dark:border-rose-900/30 rounded-xl leading-relaxed">
                                    {!! $safeStr($application->APL_Allergy_Signs, 'None Reported') !!}
                                </div>
                            </div>

                            <div class="md:col-span-2 space-y-1">
                                <span
                                    class="block text-[11px] font-bold text-rose-500 uppercase tracking-wide">{{ $fields['APL_Has_Medical_Condition']->Question ?? 'Medical or Health Conditions' }}</span>
                                <div
                                    class="text-sm font-semibold text-slate-800 dark:text-slate-200 p-3 bg-rose-50/50 dark:bg-rose-950/10 border border-rose-100 dark:border-rose-900/30 rounded-xl leading-relaxed">
                                    <div class="font-bold text-slate-900 dark:text-slate-100 mb-1">
                                        {!! $safeStr($application->APL_Has_Medical_Condition, 'No') !!}</div>
                                    @if (!empty($application->APL_Medical_Details))
                                        <div
                                            class="text-xs font-medium text-slate-600 dark:text-slate-400 pt-2 border-t border-rose-200/40">
                                            {!! $safeStr($application->APL_Medical_Details) !!}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- MANUAL SECTION 2: LEARNING PROFILE --}}
                    <div
                        class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/60 dark:border-slate-700/60 shadow-sm overflow-hidden">
                        <div
                            class="px-6 py-4 border-b border-slate-100 dark:border-slate-700/50 bg-slate-50/50 dark:bg-slate-800/40">
                            <h3
                                class="text-xs font-extrabold text-slate-400 dark:text-slate-400 tracking-wider uppercase">
                                Learning Profile
                            </h3>
                        </div>
                        <div class="p-6 space-y-6">

                            {{-- Soft Behavioral Attributes Profile Stack --}}
                            <div
                                class="border border-slate-100 dark:border-slate-800 rounded-xl overflow-hidden bg-slate-50/30 dark:bg-slate-900/20 p-4 space-y-3">
                                <span
                                    class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Social
                                    & Soft Skills Evaluation</span>
                                <div
                                    class="grid grid-cols-1 gap-2.5 text-xs font-semibold text-slate-700 dark:text-slate-300">
                                    @foreach (['APL_Attitude_Learning', 'APL_Confident_Abilities', 'APL_Listening_Skills', 'APL_Social_Skills', 'APL_Perseverance', 'APL_Mood', 'APL_Communication'] as $itemKey)
                                        <div
                                            class="flex justify-between items-center p-2.5 bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700/40 rounded-lg shadow-2xs">
                                            <span
                                                class="pr-4">{{ $fields[$itemKey]->Question ?? str_replace('APL_', '', $itemKey) }}</span>
                                            <span
                                                class="bg-slate-100 dark:bg-slate-900 text-slate-900 dark:text-slate-100 px-2.5 py-1 rounded text-right shrink-0">
                                                {!! $safeStr($application->$itemKey) !!}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Narratives --}}
                            <div class="grid grid-cols-1 gap-5">
                                <div class="space-y-1">
                                    <span
                                        class="block text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wide">{{ $fields['APL_Hobbies']->Question ?? 'Hobbies' }}</span>
                                    <div
                                        class="text-sm font-semibold text-slate-800 dark:text-slate-200 p-3 bg-slate-50 dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800 rounded-xl leading-relaxed">
                                        {!! $safeStr($application->APL_Hobbies) !!}</div>
                                </div>
                                <div class="space-y-1">
                                    <span
                                        class="block text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wide">{{ $fields['APL_New_Skills']->Question ?? 'New Skills Expected' }}</span>
                                    <div
                                        class="text-sm font-semibold text-slate-800 dark:text-slate-200 p-3 bg-slate-50 dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800 rounded-xl leading-relaxed">
                                        {!! $safeStr($application->APL_New_Skills) !!}</div>
                                </div>
                                <div class="space-y-1">
                                    <span
                                        class="block text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wide">{{ $fields['APL_Education_Goals']->Question ?? 'Educational Goals' }}</span>
                                    <div
                                        class="text-sm font-semibold text-slate-800 dark:text-slate-200 p-3 bg-slate-50 dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800 rounded-xl leading-relaxed">
                                        {!! $safeStr($application->APL_Education_Goals) !!}</div>
                                </div>
                                <div class="space-y-1">
                                    <span
                                        class="block text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wide">{{ $fields['APL_Career_Goals']->Question ?? 'Career Goals' }}</span>
                                    <div
                                        class="text-sm font-semibold text-slate-800 dark:text-slate-200 p-3 bg-slate-50 dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800 rounded-xl leading-relaxed">
                                        {!! $safeStr($application->APL_Career_Goals) !!}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- MANUAL SECTION 3: CONTACT INFORMATION --}}
                    <div
                        class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/60 dark:border-slate-700/60 shadow-sm overflow-hidden">
                        <div
                            class="px-6 py-4 border-b border-slate-100 dark:border-slate-700/50 bg-slate-50/50 dark:bg-slate-800/40">
                            <h3
                                class="text-xs font-extrabold text-slate-400 dark:text-slate-400 tracking-wider uppercase">
                                Contact Information
                            </h3>
                        </div>
                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-5">
                            <div class="md:col-span-2 space-y-1">
                                <span
                                    class="block text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wide">{{ $fields['APL_Parent_Name']->Question ?? 'Parent/Guardian Name' }}</span>
                                <div
                                    class="text-sm font-bold text-slate-900 dark:text-slate-50 p-3 bg-slate-50 dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800 rounded-xl">
                                    {!! $safeStr($application->APL_Parent_Name) !!}</div>
                            </div>

                            <div class="space-y-1">
                                <span
                                    class="block text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wide">{{ $fields['APL_Parent_Cellphone']->Question ?? 'Parent Cellphone' }}</span>
                                <div
                                    class="text-sm font-semibold text-slate-800 dark:text-slate-200 p-3 bg-slate-50 dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800 rounded-xl font-mono">
                                    {!! $safeStr($application->APL_Parent_Cellphone) !!}</div>
                            </div>
                            <div class="space-y-1">
                                <span
                                    class="block text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wide">{{ $fields['APL_Parent_Work_Home_Phone']->Question ?? 'Parent Work/Home Phone' }}</span>
                                <div
                                    class="text-sm font-semibold text-slate-800 dark:text-slate-200 p-3 bg-slate-50 dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800 rounded-xl font-mono">
                                    {!! $safeStr($application->APL_Parent_Work_Home_Phone) !!}</div>
                            </div>

                            <div class="md:col-span-2 space-y-1">
                                <span
                                    class="block text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wide">{{ $fields['APL_Parent_Email']->Question ?? 'Parent Email Address' }}</span>
                                <div
                                    class="text-sm font-semibold text-slate-800 dark:text-slate-200 p-3 bg-slate-50 dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800 rounded-xl">
                                    {!! $safeStr($application->APL_Parent_Email) !!}</div>
                            </div>

                            {{-- Parent Identification Attributes --}}
                            <div class="space-y-1">
                                <span
                                    class="block text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wide">{{ $fields['APL_Parent_ID_Type']->Question ?? 'Parent ID Type' }}</span>
                                <div
                                    class="text-sm font-semibold text-slate-800 dark:text-slate-200 p-3 bg-slate-50 dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800 rounded-xl">
                                    {!! $safeStr($application->APL_Parent_ID_Type) !!}</div>
                            </div>
                            <div class="space-y-1">
                                <span
                                    class="block text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wide">{{ $fields['APL_Parent_ID_Number']->Question ?? 'Parent ID Number' }}</span>
                                <div
                                    class="text-sm font-semibold text-slate-800 dark:text-slate-200 p-3 bg-slate-50 dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800 rounded-xl font-mono">
                                    {!! $safeStr($application->APL_Parent_ID_Number) !!}</div>
                            </div>

                            {{-- Secondary Operational / Pickup Logistics --}}
                            <div class="space-y-1">
                                <span
                                    class="block text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wide">{{ $fields['APL_Authorized_Pickup_Name']->Question ?? 'Authorized Pickup Person' }}</span>
                                <div
                                    class="text-sm font-semibold text-slate-800 dark:text-slate-200 p-3 bg-slate-50 dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800 rounded-xl">
                                    {!! $safeStr($application->APL_Authorized_Pickup_Name) !!}</div>
                            </div>
                            <div class="space-y-1">
                                <span
                                    class="block text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wide">{{ $fields['APL_Authorized_Pickup_Phone']->Question ?? 'Pickup Person Contact Number' }}</span>
                                <div
                                    class="text-sm font-semibold text-slate-800 dark:text-slate-200 p-3 bg-slate-50 dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800 rounded-xl font-mono">
                                    {!! $safeStr($application->APL_Authorized_Pickup_Phone) !!}</div>
                            </div>

                            <div class="md:col-span-2 space-y-1">
                                <span
                                    class="block text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wide">{{ $fields['APL_How_Heard']->Question ?? 'How did you hear about us?' }}</span>
                                <div
                                    class="text-sm font-semibold text-slate-800 dark:text-slate-200 p-3 bg-slate-50 dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800 rounded-xl">
                                    {!! $safeStr($application->APL_How_Heard) !!}
                                    {{ !empty($application->APL_How_Heard_Other) ? '(' . $safeStr($application->APL_How_Heard_Other) . ')' : '' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- COMPLIANCE & CONSENT CARD --}}
                    <div
                        class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/60 dark:border-slate-700/60 shadow-sm overflow-hidden">
                        <div
                            class="px-6 py-4 border-b border-slate-100 dark:border-slate-700/50 bg-slate-50/50 dark:bg-slate-800/40">
                            <h3
                                class="text-xs font-extrabold text-slate-400 dark:text-slate-400 tracking-wider uppercase">
                                Legal Declarations & Consents
                            </h3>
                        </div>
                        <div class="p-6 space-y-4 divide-y divide-slate-100 dark:divide-slate-700/50">
                            @foreach (['APL_Consent_Participation', 'APL_Consent_Medical', 'APL_Consent_Photo', 'APL_Consent_Liability', 'APL_Consent_Declaration'] as $consentKey)
                                @if (isset($fields[$consentKey]))
                                    <div class="flex items-start gap-4 pt-4 first:pt-0">
                                        <div
                                            class="mt-0.5 text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/40 p-1 rounded-full border border-emerald-100 dark:border-emerald-900/40 shrink-0">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                    d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                        <div class="space-y-1">
                                            <p
                                                class="text-xs font-medium text-slate-500 dark:text-slate-400 leading-relaxed">
                                                {{ $fields[$consentKey]->Question }}
                                            </p>
                                            <span
                                                class="inline-block text-[10px] font-bold uppercase tracking-wider text-emerald-700 dark:text-emerald-400 bg-emerald-50/60 dark:bg-emerald-950/20 px-2 py-0.5 rounded">
                                                Agreed / Granted: {!! $safeStr($application->$consentKey, 'Yes') !!}
                                            </span>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>


                </div>

                {{-- RIGHT COLUMN: STICKY METADATA & FILES SIDEBAR --}}
                <div class="space-y-6 lg:sticky lg:top-6">

                    {{-- REGISTRATION METADATA CARD --}}
                    <div
                        class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/60 dark:border-slate-700/60 shadow-sm p-6 space-y-4">
                        <h4 class="text-xs font-extrabold text-slate-400 uppercase tracking-wider">Submission Metadata
                        </h4>

                        <div class="space-y-3 text-xs border-b border-slate-100 dark:border-slate-700/50 pb-4">
                            <div class="flex justify-between">
                                <span class="font-medium text-slate-400">Application Key</span>
                                <span
                                    class="font-mono font-bold text-slate-800 dark:text-slate-200">#{!! $safeStr($application->id ?? $application->APL_ID) !!}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium text-slate-400">Received Date</span>
                                <span
                                    class="font-semibold text-slate-800 dark:text-slate-200">{{ \Carbon\Carbon::parse($application->created_at)->format('d M Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium text-slate-400">Received Time</span>
                                <span
                                    class="font-semibold text-slate-800 dark:text-slate-200">{{ \Carbon\Carbon::parse($application->created_at)->format('h:i A') }}</span>
                            </div>
                        </div>

                        {{-- Emergency Contact Sidebar Anchor --}}
                        <div class="space-y-1 pt-1">
                            <span
                                class="text-[10px] font-bold text-rose-500 dark:text-rose-400 uppercase tracking-wide block">{{ $fields['APL_Emergency_Contact_Name']->Question ?? 'Primary Emergency Contact' }}</span>
                            <p class="text-sm font-bold text-slate-800 dark:text-slate-100">{!! $safeStr($application->APL_Emergency_Contact_Name) !!}
                            </p>
                            <p class="text-xs font-mono font-bold text-slate-500 dark:text-slate-400">
                                {!! $safeStr($application->APL_Emergency_Contact_Phone) !!}</p>
                        </div>
                    </div>

                    {{-- FILE DOWNLOAD ATTACHMENTS CARD --}}
                    <div
                        class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/60 dark:border-slate-700/60 shadow-sm p-6 space-y-4">
                        <h4 class="text-xs font-extrabold text-slate-400 uppercase tracking-wider">Verification
                            Documents</h4>

                        <div class="space-y-2">
                            @php $hasFiles = false; @endphp

                            @foreach (['APL_ID_File', 'Birth_Certificate_File', 'APL_Passport_Photo_File', 'APL_Parent_ID_File', 'APL_Authorized_Pickup_ID_File', 'APL_Emergency_Contact_ID_File', 'APL_Consent_Form_File'] as $fileKey)
                                @if (!empty($application->$fileKey) && isset($fields[$fileKey]))
                                    @php $hasFiles = true; @endphp
                                    <a href="{{ Storage::url($application->$fileKey) }}" target="_blank"
                                        class="flex items-center justify-between p-3 bg-slate-50 dark:bg-slate-900/60 hover:bg-indigo-50/60 dark:hover:bg-indigo-950/40 border border-slate-100 dark:border-slate-800 rounded-xl transition group">
                                        <div class="flex items-center gap-2.5 min-w-0">
                                            <div
                                                class="p-2 bg-white dark:bg-slate-800 text-slate-400 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 rounded-lg shadow-2xs border border-slate-100 dark:border-slate-700/60 shrink-0">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                    </path>
                                                </svg>
                                            </div>
                                            <span
                                                class="text-xs font-bold text-slate-700 dark:text-slate-300 truncate tracking-tight pr-2">
                                                {{ $fields[$fileKey]->Question }}
                                            </span>
                                        </div>
                                        <svg class="w-4 h-4 text-slate-400 group-hover:translate-x-0.5 transition-transform shrink-0"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                @endif
                            @endforeach

                            @if (!$hasFiles)
                                <div
                                    class="text-center py-6 border-2 border-dashed border-slate-100 dark:border-slate-800 rounded-xl">
                                    <p class="text-xs font-medium text-slate-400 italic">No document files uploaded</p>
                                </div>
                            @endif
                        </div>
                    </div>
                    {{-- SIMPLIFIED APPLICATION STATUS FORM --}}
                    <div class="md:col-span-2 mt-6 pt-6 border-t border-slate-100 dark:border-slate-800/80">
                        <form wire:submit.prevent="saveReview" class="space-y-4">
                            <div class="flex items-center gap-2 mb-2">
                                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                    </path>
                                </svg>
                                <h4
                                    class="text-xs font-extrabold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                                    Review Action</h4>
                            </div>

                            {{-- Status Dropdown Field --}}
                            <div class="space-y-1">
                                <label for="APL_Status"
                                    class="block text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wide">
                                    Application Status <span class="text-red-500">*</span>
                                </label>
                                <select id="APL_Status" wire:model="APL_Status"
                                    class="w-full text-sm font-semibold rounded-xl p-3 border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all duration-150">
                                    <option value="">Select a status...</option>
                                    <option value="open">Open</option>
                                    <option value="pending review">Pending Review</option>
                                    <option value="accepted">Accepted</option>
                                    <option value="declined">Declined</option>
                                </select>
                                @error('APL_Status')
                                    <span class="text-xs text-red-500 mt-1 block font-medium">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Remarks Text Area --}}
                            <div class="space-y-1">
                                <label for="reviewer_notes"
                                    class="block text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wide">
                                    Internal Remarks <span class="text-red-500">*</span>
                                </label>
                                <textarea id="reviewer_notes" wire:model="reviewer_notes" rows="3"
                                    placeholder="Provide context or justification for this status change..."
                                    class="w-full text-sm font-medium rounded-xl p-3 border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-200 placeholder-slate-400 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all duration-150 leading-relaxed"></textarea>
                                @error('reviewer_notes')
                                    <span class="text-xs text-red-500 mt-1 block font-medium">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Form Action Control --}}
                            <div class="flex justify-end pt-1">
                                <button type="submit"
                                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider text-white bg-indigo-600 hover:bg-indigo-700 active:scale-[0.98] shadow-sm shadow-indigo-500/10 transition-all duration-150 cursor-pointer">
                                    <span>Update Application Status</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </button>
                            </div>
                        </form>
                    </div>


                </div>

            </div>

        </div>
    </div>
</x-app-layout>
