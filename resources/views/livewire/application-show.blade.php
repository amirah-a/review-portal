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

<div>
    <x-app-layout>
        {{-- Main Container - Expanded to screen-2xl for extra wide-screen breathing room --}}
        <div class="py-12 bg-slate-50 dark:bg-slate-900 min-h-screen antialiased">
            <div class="max-w-[1440px] mx-auto px-6 sm:px-8 lg:px-10 space-y-8">

                {{-- TOP BREADCRUMB / ACTION BAR --}}
                <div
                    class="flex items-center justify-between pb-2 border-b border-slate-200/40 dark:border-slate-800/40">
                    <a href="{{ route('all-applications') }}"
                        class="inline-flex items-center gap-2.5 text-sm font-semibold text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-100 transition">
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
                            class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider shadow-2xs border
                        @if (($application->APL_Status ?? '') === 'approved' || ($application->APL_Status ?? '') === 'Accepted') bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-400 dark:border-emerald-900/60
                        @elseif(($application->APL_Status ?? '') === 'declined' || ($application->APL_Status ?? '') === 'Declined') bg-rose-50 text-rose-700 border-rose-200 dark:bg-rose-950/40 dark:text-rose-400 dark:border-rose-900/60
                        @else  bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-950/40 dark:text-blue-400 dark:border-blue-900/60 @endif">
                            {{ str_replace('_', ' ', $application->APL_Status ?? 'Pending Review') }}
                        </span>
                    </div>
                </div>

                {{-- OUTER 12-COLUMN SYSTEM - Spacing increased to gap-10 --}}
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">

                    {{-- LEFT SIDEBAR: METADATA & VERIFICATION DOCUMENTS --}}
                    <div class="lg:col-span-3 space-y-8">

                        {{-- REGISTRATION METADATA CARD --}}
                        <div
                            class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/60 dark:border-slate-700/60 shadow-xs p-6 space-y-5">
                            <h4 class="text-xs font-extrabold text-slate-400 uppercase tracking-wider tracking-widest">
                                Submission Metadata</h4>

                            <div class="space-y-3.5 text-xs border-b border-slate-100 dark:border-slate-700/50 pb-5">
                                <div class="flex justify-between items-center">
                                    <span class="font-medium text-slate-400">Application Key</span>
                                    <span
                                        class="font-mono font-bold text-slate-800 dark:text-slate-200 bg-slate-50 dark:bg-slate-900 px-2 py-0.5 rounded-md border border-slate-100 dark:border-slate-800">#{!! $safeStr($application->id ?? $application->APL_ID) !!}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="font-medium text-slate-400">Received Date</span>
                                    <span
                                        class="font-semibold text-slate-800 dark:text-slate-200">{{ \Carbon\Carbon::parse($application->created_at)->format('d M Y') }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="font-medium text-slate-400">Received Time</span>
                                    <span
                                        class="font-semibold text-slate-800 dark:text-slate-200">{{ \Carbon\Carbon::parse($application->created_at)->format('h:i A') }}</span>
                                </div>
                            </div>

                            {{-- Emergency Contact Anchor --}}
                            <div class="space-y-2 pt-1">
                                <span
                                    class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wide block">{{ $fields['APL_Emergency_Contact_Name']->Question ?? 'Primary Emergency Contact' }}</span>
                                <p class="text-sm font-bold text-slate-800 dark:text-slate-100">{!! $safeStr($application->APL_Emergency_Contact_Name) !!}
                                </p>
                                <p
                                    class="text-xs font-mono font-bold text-slate-500 dark:text-slate-400 bg-slate-50 dark:bg-slate-900 p-2 rounded-lg border border-slate-100 dark:border-slate-800/60 inline-block">
                                    {!! $safeStr($application->APL_Emergency_Contact_Phone) !!}</p>
                            </div>
                        </div>

                        {{-- FILE DOWNLOAD ATTACHMENTS CARD --}}
                        <div
                            class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/60 dark:border-slate-700/60 shadow-xs p-6 space-y-5">
                            <h4 class="text-xs font-extrabold text-slate-400 uppercase tracking-wider tracking-widest">
                                Verification Documents</h4>

                            <div class="space-y-3">
                                @php
                                    $hasFiles = false;

                                    $fileLabels = [
                                        'APL_ID_File' => 'Participant Identification',
                                        'APL_Birth_Certificate_File' => 'Participant Birth Certificate',
                                        'APL_Passport_Photo_File' => 'Passport Photograph',
                                        'APL_Parent_ID_File' => 'Parent/Guardian Identification',
                                        'APL_Authorized_Pickup_ID_File' => 'Authorized Pickup Identification',
                                        'APL_Emergency_Contact_ID_File' => 'Emergency Contact Identification',
                                        'APL_Consent_Form_File' => 'Signed Consent Form',
                                    ];
                                @endphp

                                @foreach (array_keys($fileLabels) as $fileKey)
                                    @if (!empty($application->$fileKey))
                                        @php $hasFiles = true; @endphp

                                        <a href="{{ route('documents.show', ['path' => $application->$fileKey]) }}"
                                            target="_blank"
                                            class="flex items-center justify-between p-3.5 bg-slate-50 dark:bg-slate-900/60 hover:bg-amber-50/60 dark:hover:bg-amber-950/40 border border-slate-100 dark:border-slate-800 rounded-xl transition group">
                                            <div class="flex items-center gap-3 min-w-0">
                                                <div
                                                    class="p-2 bg-white dark:bg-slate-800 text-slate-400 group-hover:text-amber-600 dark:group-hover:text-amber-400 rounded-lg shadow-2xs border border-slate-100 dark:border-slate-700/60 shrink-0">
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
                                                    {{ $fileLabels[$fileKey] }}
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
                                        class="text-center py-8 border-2 border-dashed border-slate-100 dark:border-slate-800 rounded-xl">
                                        <p class="text-xs font-medium text-slate-400 italic">No document files uploaded
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>

                    </div>

                    {{-- CENTER COLUMN: MAIN APPLICANT PROFILE CORE DATA --}}
                    <div class="lg:col-span-6 space-y-8">

                        {{-- HERO PROFILE CARD --}}
                        <div
                            class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/60 dark:border-slate-700/60 shadow-xs p-8 relative overflow-hidden">
                            <div
                                class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-amber-500 to-orange-600">
                            </div>

                            <div class="flex items-center gap-5">
                                <div
                                    class="w-16 h-16 bg-amber-50 dark:bg-amber-950/60 rounded-xl flex items-center justify-center text-amber-600 dark:text-amber-400 shrink-0 font-black text-2xl tracking-tight border border-amber-100 dark:border-amber-900/50">
                                    {{ $initials }}
                                </div>
                                <div class="space-y-1">
                                    <h1
                                        class="text-3xl font-black text-slate-900 dark:text-slate-50 tracking-tight flex flex-wrap items-center gap-1.5">
                                        {!! $safeStr($application->APL_FName) !!} {!! $safeStr($application->APL_MName, '') !!} {!! $safeStr($application->APL_LName) !!}
                                    </h1>
                                    <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">
                                        Selected Center: <span
                                            class="text-slate-800 dark:text-slate-200 font-bold">{!! $safeStr($application->APL_Programme_Center) !!}</span>
                                    </p>
                                </div>
                            </div>

                            <div
                                class="grid grid-cols-2 sm:grid-cols-4 gap-6 mt-8 pt-8 border-t border-slate-100 dark:border-slate-700/50 text-xs">
                                <div class="space-y-1">
                                    <span class="block text-slate-400 font-semibold uppercase tracking-wider">Date of
                                        Birth</span>
                                    <span
                                        class="text-base font-bold text-slate-800 dark:text-slate-200">{!! $safeStr($application->APL_DOB) !!}</span>
                                </div>
                                <div class="space-y-1">
                                    <span class="block text-slate-400 font-semibold uppercase tracking-wider">Age /
                                        Sex</span>
                                    <span
                                        class="text-base font-bold text-slate-800 dark:text-slate-200">{!! $safeStr($application->APL_Age) !!}
                                        Yrs / {!! $safeStr($application->APL_Sex) !!}</span>
                                </div>
                                <div class="space-y-1">
                                    <span class="block text-slate-400 font-semibold uppercase tracking-wider">Jersey
                                        Size</span>
                                    <span
                                        class="text-base font-bold text-slate-800 dark:text-slate-200 uppercase">{!! $safeStr($application->APL_Participant_Jersey_Size) !!}</span>
                                </div>
                                <div class="space-y-1">
                                    <span class="block text-slate-400 font-semibold uppercase tracking-wider">Meal
                                        Pref</span>
                                    <span
                                        class="text-base font-bold text-slate-800 dark:text-slate-200">{!! $safeStr($application->APL_Meal_Preference) !!}</span>
                                </div>
                            </div>
                        </div>

                        {{-- PARTICIPANT INFORMATION --}}
                        <div
                            class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/60 dark:border-slate-700/60 shadow-xs overflow-hidden">
                            <div
                                class="px-8 py-5 border-b border-slate-100 dark:border-slate-700/50 bg-slate-50/50 dark:bg-slate-800/40">
                                <h3
                                    class="text-xs font-extrabold text-slate-400 dark:text-slate-400 tracking-wider uppercase">
                                    Participant Information</h3>
                            </div>
                            <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                                <div class="md:col-span-2 space-y-2">
                                    <span
                                        class="block text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wide">{{ $fields['APL_Address_1']->Question ?? 'Address Line 1' }}</span>
                                    <div
                                        class="text-sm font-semibold text-slate-800 dark:text-slate-200 p-3.5 bg-slate-50 dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800 rounded-xl leading-relaxed">
                                        {!! $safeStr($application->APL_Address_1) !!}{{ !empty($application->APL_Address_2) ? ', ' . $safeStr($application->APL_Address_2, '') : '' }}
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <span
                                        class="block text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wide">{{ $fields['APL_Area']->Question ?? 'Region/Area' }}</span>
                                    <div
                                        class="text-sm font-semibold text-slate-800 dark:text-slate-200 p-3.5 bg-slate-50 dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800 rounded-xl leading-relaxed">
                                        {!! $safeStr($application->APL_Area) !!}
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <span
                                        class="block text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wide">{{ $fields['APL_Is_Citizen']->Question ?? 'Trinidad & Tobago Citizen?' }}</span>
                                    <div
                                        class="text-sm font-semibold text-slate-800 dark:text-slate-200 p-3.5 bg-slate-50 dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800 rounded-xl leading-relaxed">
                                        {!! $safeStr($application->APL_Is_Citizen) !!}
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <span
                                        class="block text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wide">{{ $fields['APL_ID_Type']->Question ?? 'Identification Document' }}</span>
                                    <div
                                        class="text-sm font-semibold text-slate-800 dark:text-slate-200 p-3.5 bg-slate-50 dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800 rounded-xl leading-relaxed">
                                        {!! $safeStr($application->ID_Type ?? $application->APL_ID_Type) !!}
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <span
                                        class="block text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wide">{{ $fields['APL_ID_Number']->Question ?? 'Identification Number' }}</span>
                                    <div
                                        class="text-sm font-semibold text-slate-800 dark:text-slate-200 p-3.5 bg-slate-50 dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800 rounded-xl leading-relaxed font-mono tracking-wide">
                                        {!! $safeStr($application->ID_Number ?? $application->APL_ID_Number) !!}
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <span
                                        class="block text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wide">{{ $fields['APL_Enrolled']->Question ?? 'Enrolled in training?' }}</span>
                                    <div
                                        class="text-sm font-semibold text-slate-800 dark:text-slate-200 p-3.5 bg-slate-50 dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800 rounded-xl leading-relaxed">
                                        {!! $safeStr($application->APL_Enrolled) !!}
                                    </div>
                                </div>

                                @if ($application->APL_Enrolled === 'Yes')
                                    <div class="space-y-2">
                                        <span
                                            class="block text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wide">{{ $fields['APL_School_Enrolled']->Question ?? 'Name of School Currently Enrolled' }}</span>
                                        <div
                                            class="text-sm font-semibold text-slate-800 dark:text-slate-200 p-3.5 bg-slate-50 dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800 rounded-xl leading-relaxed">
                                            {!! $safeStr($application->APL_School_Enrolled) !!}
                                        </div>
                                    </div>
                                @else
                                    <div class="space-y-2">
                                        <span
                                            class="block text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wide">{{ $fields['APL_Education_Level']->Question ?? 'Level of education attained' }}</span>
                                        <div
                                            class="text-sm font-semibold text-slate-800 dark:text-slate-200 p-3.5 bg-slate-50 dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800 rounded-xl leading-relaxed">
                                            {!! $safeStr($application->APL_Education_Level) !!}
                                        </div>
                                    </div>

                                    <div class="space-y-2">
                                        <span
                                            class="block text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wide">{{ $fields['APL_Last_School_Name']->Question ?? 'Name of Last School Enrolled' }}</span>
                                        <div
                                            class="text-sm font-semibold text-slate-800 dark:text-slate-200 p-3.5 bg-slate-50 dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800 rounded-xl leading-relaxed">
                                            {!! $safeStr($application->APL_Last_School_Name) !!}
                                        </div>
                                    </div>
                                @endif

                                <div class="md:col-span-2 space-y-2">
                                    <span
                                        class="block text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wide">{{ $fields['APL_Allergy_Signs']->Question ?? 'Allergy Signs / Reactions' }}</span>
                                    <div
                                        class="text-sm font-semibold text-slate-800 dark:text-slate-200 p-3.5 bg-slate-50 dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800 rounded-xl leading-relaxed">
                                        {!! $safeStr($application->APL_Allergy_Signs, 'None Reported') !!}
                                    </div>
                                </div>

                                <div class="md:col-span-2 space-y-2">
                                    <span
                                        class="block text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wide">{{ $fields['APL_Has_Medical_Condition']->Question ?? 'Medical or Health Conditions' }}</span>
                                    <div
                                        class="text-sm font-semibold text-slate-800 dark:text-slate-200 p-4 bg-slate-50 dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800 rounded-xl leading-relaxed">
                                        <div class="font-bold text-slate-900 dark:text-slate-100 mb-2">
                                            {!! $safeStr($application->APL_Has_Medical_Condition, 'No') !!}</div>
                                        @if (!empty($application->APL_Medical_Details))
                                            <div
                                                class="text-xs font-medium text-slate-600 dark:text-slate-400 pt-3 border-t border-slate-200/60 dark:border-slate-700/40">
                                                {!! $safeStr($application->APL_Medical_Details) !!}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- LEARNING PROFILE --}}
                        <div
                            class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/60 dark:border-slate-700/60 shadow-xs overflow-hidden">
                            <div
                                class="px-8 py-5 border-b border-slate-100 dark:border-slate-700/50 bg-slate-50/50 dark:bg-slate-800/40">
                                <h3
                                    class="text-xs font-extrabold text-slate-400 dark:text-slate-400 tracking-wider uppercase">
                                    Learning Profile</h3>
                            </div>
                            <div class="p-8 space-y-8">
                                <div
                                    class="border border-slate-100 dark:border-slate-800 rounded-xl overflow-hidden bg-slate-50/30 dark:bg-slate-900/20 p-5 space-y-4">
                                    <span
                                        class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider tracking-widest">Social
                                        & Soft Skills Evaluation</span>
                                    <div
                                        class="grid grid-cols-1 gap-3 text-xs font-semibold text-slate-700 dark:text-slate-300">
                                        @foreach (['APL_Attitude_Learning', 'APL_Confident_Abilities', 'APL_Listening_Skills', 'APL_Social_Skills', 'APL_Perseverance', 'APL_Mood', 'APL_Communication'] as $itemKey)
                                            <div
                                                class="flex justify-between items-center p-3 bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700/40 rounded-lg shadow-2xs">
                                                <span
                                                    class="pr-6 leading-relaxed">{{ $fields[$itemKey]->Question ?? str_replace('APL_', '', $itemKey) }}</span>
                                                <span
                                                    class="bg-slate-100 dark:bg-slate-900 text-slate-900 dark:text-slate-100 px-3 py-1 rounded-md text-right shrink-0 font-bold">
                                                    {!! $safeStr($application->$itemKey) !!}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 gap-6">
                                    <div class="space-y-2">
                                        <span
                                            class="block text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wide">{{ $fields['APL_Hobbies']->Question ?? 'Hobbies' }}</span>
                                        <div
                                            class="text-sm font-semibold text-slate-800 dark:text-slate-200 p-3.5 bg-slate-50 dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800 rounded-xl leading-relaxed">
                                            {!! $safeStr($application->APL_Hobbies) !!}</div>
                                    </div>
                                    <div class="space-y-2">
                                        <span
                                            class="block text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wide">{{ $fields['APL_New_Skills']->Question ?? 'New Skills Expected' }}</span>
                                        <div
                                            class="text-sm font-semibold text-slate-800 dark:text-slate-200 p-3.5 bg-slate-50 dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800 rounded-xl leading-relaxed">
                                            {!! $safeStr($application->APL_New_Skills) !!}</div>
                                    </div>
                                    <div class="space-y-2">
                                        <span
                                            class="block text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wide">{{ $fields['APL_Education_Goals']->Question ?? 'Educational Goals' }}</span>
                                        <div
                                            class="text-sm font-semibold text-slate-800 dark:text-slate-200 p-3.5 bg-slate-50 dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800 rounded-xl leading-relaxed">
                                            {!! $safeStr($application->APL_Education_Goals) !!}</div>
                                    </div>
                                    <div class="space-y-2">
                                        <span
                                            class="block text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wide">{{ $fields['APL_Career_Goals']->Question ?? 'Career Goals' }}</span>
                                        <div
                                            class="text-sm font-semibold text-slate-800 dark:text-slate-200 p-3.5 bg-slate-50 dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800 rounded-xl leading-relaxed">
                                            {!! $safeStr($application->APL_Career_Goals) !!}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- CONTACT INFORMATION --}}
                        <div
                            class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/60 dark:border-slate-700/60 shadow-xs overflow-hidden">
                            <div
                                class="px-8 py-5 border-b border-slate-100 dark:border-slate-700/50 bg-slate-50/50 dark:bg-slate-800/40">
                                <h3
                                    class="text-xs font-extrabold text-slate-400 dark:text-slate-400 tracking-wider uppercase">
                                    Contact Information</h3>
                            </div>
                            <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                                <div class="md:col-span-2 space-y-2">
                                    <span
                                        class="block text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wide">{{ $fields['APL_Parent_Name']->Question ?? 'Parent/Guardian Name' }}</span>
                                    <div
                                        class="text-sm font-bold text-slate-900 dark:text-slate-50 p-3.5 bg-slate-50 dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800 rounded-xl">
                                        {!! $safeStr($application->APL_Parent_Name) !!}</div>
                                </div>

                                <div class="space-y-2">
                                    <span
                                        class="block text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wide">{{ $fields['APL_Parent_Cellphone']->Question ?? 'Parent Cellphone' }}</span>
                                    <div
                                        class="text-sm font-semibold text-slate-800 dark:text-slate-200 p-3.5 bg-slate-50 dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800 rounded-xl font-mono tracking-wide">
                                        {!! $safeStr($application->APL_Parent_Cellphone) !!}</div>
                                </div>
                                <div class="space-y-2">
                                    <span
                                        class="block text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wide">{{ $fields['APL_Parent_Work_Home_Phone']->Question ?? 'Parent Work/Home Phone' }}</span>
                                    <div
                                        class="text-sm font-semibold text-slate-800 dark:text-slate-200 p-3.5 bg-slate-50 dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800 rounded-xl font-mono tracking-wide">
                                        {!! $safeStr($application->APL_Parent_Work_Home_Phone) !!}</div>
                                </div>

                                <div class="md:col-span-2 space-y-2">
                                    <span
                                        class="block text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wide">{{ $fields['APL_Parent_Email']->Question ?? 'Parent Email Address' }}</span>
                                    <div
                                        class="text-sm font-semibold text-slate-800 dark:text-slate-200 p-3.5 bg-slate-50 dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800 rounded-xl">
                                        {!! $safeStr($application->APL_Parent_Email) !!}</div>
                                </div>

                                <div class="space-y-2">
                                    <span
                                        class="block text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wide">{{ $fields['APL_Parent_ID_Type']->Question ?? 'Parent ID Type' }}</span>
                                    <div
                                        class="text-sm font-semibold text-slate-800 dark:text-slate-200 p-3.5 bg-slate-50 dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800 rounded-xl">
                                        {!! $safeStr($application->APL_Parent_ID_Type) !!}</div>
                                </div>
                                <div class="space-y-2">
                                    <span
                                        class="block text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wide">{{ $fields['APL_Parent_ID_Number']->Question ?? 'Parent ID Number' }}</span>
                                    <div
                                        class="text-sm font-semibold text-slate-800 dark:text-slate-200 p-3.5 bg-slate-50 dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800 rounded-xl font-mono tracking-wide">
                                        {!! $safeStr($application->APL_Parent_ID_Number) !!}</div>
                                </div>

                                <div class="space-y-2">
                                    <span
                                        class="block text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wide">{{ $fields['APL_Authorized_Pickup_Name']->Question ?? 'Authorized Pickup Person' }}</span>
                                    <div
                                        class="text-sm font-semibold text-slate-800 dark:text-slate-200 p-3.5 bg-slate-50 dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800 rounded-xl">
                                        {!! $safeStr($application->APL_Authorized_Pickup_Name) !!}</div>
                                </div>
                                <div class="space-y-2">
                                    <span
                                        class="block text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wide">{{ $fields['APL_Authorized_Pickup_Phone']->Question ?? 'Pickup Person Contact Number' }}</span>
                                    <div
                                        class="text-sm font-semibold text-slate-800 dark:text-slate-200 p-3.5 bg-slate-50 dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800 rounded-xl font-mono tracking-wide">
                                        {!! $safeStr($application->APL_Authorized_Pickup_Phone) !!}</div>
                                </div>

                                <div class="md:col-span-2 space-y-2">
                                    <span
                                        class="block text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wide">{{ $fields['APL_How_Heard']->Question ?? 'How did you hear about us?' }}</span>
                                    <div
                                        class="text-sm font-semibold text-slate-800 dark:text-slate-200 p-3.5 bg-slate-50 dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800 rounded-xl leading-relaxed">
                                        {!! $safeStr($application->APL_How_Heard) !!}
                                        {{ !empty($application->APL_How_Heard_Other) ? '(' . $safeStr($application->APL_How_Heard_Other) . ')' : '' }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- COMPLIANCE & CONSENT CARD --}}
                        <div
                            class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/60 dark:border-slate-700/60 shadow-xs overflow-hidden">
                            <div
                                class="px-8 py-5 border-b border-slate-100 dark:border-slate-700/50 bg-slate-50/50 dark:bg-slate-800/40">
                                <h3
                                    class="text-xs font-extrabold text-slate-400 dark:text-slate-400 tracking-wider uppercase">
                                    Legal Declarations & Consents</h3>
                            </div>
                            <div class="p-8 space-y-5 divide-y divide-slate-100 dark:divide-slate-700/50">
                                @foreach (['APL_Consent_Participation', 'APL_Consent_Medical', 'APL_Consent_Photo', 'APL_Consent_Liability', 'APL_Consent_Declaration'] as $consentKey)
                                    @if (isset($fields[$consentKey]))
                                        @php $isAgreed = trim(strtolower($application->$consentKey ?? 'yes')) === 'yes'; @endphp
                                        <div class="flex items-start gap-4 pt-5 first:pt-0">
                                            <div
                                                class="mt-0.5 p-1.5 rounded-full border shrink-0 {{ $isAgreed ? 'text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/40 border-emerald-100 dark:border-emerald-900/40' : 'text-rose-600 dark:text-rose-400 bg-rose-50 dark:bg-rose-950/40 border-rose-100 dark:border-rose-900/40' }}">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="3"
                                                        d="{{ $isAgreed ? 'M5 13l4 4L19 7' : 'M6 18L18 6M6 6l12 12' }}">
                                                    </path>
                                                </svg>
                                            </div>
                                            <div class="space-y-2">
                                                <p
                                                    class="text-xs font-medium text-slate-500 dark:text-slate-400 leading-relaxed">
                                                    {{ $fields[$consentKey]->Question }}</p>
                                                <span
                                                    class="inline-block text-[10px] font-bold uppercase tracking-wider {{ $isAgreed ? 'text-emerald-700 dark:text-emerald-400 bg-emerald-50/60 dark:bg-emerald-950/20' : 'text-rose-700 dark:text-rose-400 bg-rose-50/60 dark:bg-rose-950/20' }} px-2.5 py-1 rounded-md">
                                                    {{ $isAgreed ? 'Agreed / Granted: ' : 'Declined / Withheld: ' }}
                                                    {!! $safeStr($application->$consentKey, $isAgreed ? 'Yes' : 'No') !!}
                                                </span>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                    </div>

                    {{-- RIGHT SIDEBAR: STICKY FILAMENT REVIEW PANEL --}}
                    <div class="lg:col-span-3 space-y-8 lg:sticky lg:top-8">
                        <div
                            class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/60 dark:border-slate-800 shadow-xs p-6 space-y-6">

                            {{-- Header --}}
                            <div class="space-y-1">
                                <h3 class="text-sm font-semibold text-slate-900 dark:text-white">
                                    Review Application
                                </h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400">
                                    Process and update the application decision.
                                </p>
                            </div>

                            <form wire:submit.prevent="saveReview" class="space-y-6">

                                {{-- STATUS --}}
                                <div class="space-y-2.5">
                                    <label class="text-sm font-medium text-slate-700 dark:text-slate-200 block">
                                        Status <span class="text-amber-500 font-bold">*</span>
                                    </label>

                                    <div class="space-y-2">
                                        <label
                                            class="flex items-center gap-3 px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/50 hover:bg-slate-50 dark:hover:bg-slate-800/40 transition cursor-pointer">
                                            <input type="radio" wire:model="APL_Status" value="Open"
                                                class="h-4 w-4 text-amber-600 border-slate-300 dark:border-slate-700"
                                                disabled>
                                            <span class="text-sm text-slate-700 dark:text-slate-300 font-medium">
                                                Open
                                            </span>
                                        </label>

                                        <label
                                            class="flex items-center gap-3 px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/50 hover:bg-slate-50 dark:hover:bg-slate-800/40 transition cursor-pointer">
                                            <input type="radio" wire:model="APL_Status" value="Pending Review"
                                                class="h-4 w-4 text-amber-600 border-slate-300 dark:border-slate-700">
                                            <span class="text-sm text-slate-700 dark:text-slate-300 font-medium">
                                                Pending Review
                                            </span>
                                        </label>

                                        <label
                                            class="flex items-center gap-3 px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/50 hover:bg-slate-50 dark:hover:bg-slate-800/40 transition cursor-pointer">
                                            <input type="radio" wire:model="APL_Status" value="Accepted"
                                                class="h-4 w-4 text-emerald-600 border-slate-300 dark:border-slate-700">
                                            <span class="text-sm text-slate-700 dark:text-slate-300 font-medium">
                                                Accepted
                                            </span>
                                        </label>

                                        <label
                                            class="flex items-center gap-3 px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/50 hover:bg-slate-50 dark:hover:bg-slate-800/40 transition cursor-pointer">
                                            <input type="radio" wire:model="APL_Status" value="Declined"
                                                class="h-4 w-4 text-rose-600 border-slate-300 dark:border-slate-700">
                                            <span class="text-sm text-slate-700 dark:text-slate-300 font-medium">
                                                Declined
                                            </span>
                                        </label>

                                    </div>

                                    @error('APL_Status')
                                        <p class="text-xs text-rose-500 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- NOTES --}}
                                <div class="space-y-2.5">
                                    <label class="text-sm font-medium text-slate-700 dark:text-slate-200 block">
                                        Remarks <span class="text-amber-500 font-bold">*</span>
                                    </label>

                                    <textarea wire:model="reviewer_notes" rows="5" placeholder="Enter reviewer comments..."
                                        class="w-full text-sm rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 px-4 py-3"></textarea>

                                    @error('reviewer_notes')
                                        <p class="text-xs text-rose-500 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- BUTTON --}}
                                <div>
                                    <button type="submit"
                                        class="w-full inline-flex items-center justify-center px-4 py-3 text-sm font-semibold text-white bg-amber-600 hover:bg-amber-500 rounded-lg transition">
                                        Save Review
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>

                </div>
                {{-- END OUTER 12-COLUMN SYSTEM --}}

            </div>
        </div>
    </x-app-layout>
</div>
