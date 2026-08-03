<?php

namespace App\Livewire\Coordinator;

use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use App\Models\Application;
use App\Models\Attendance;
use Carbon\Carbon;

#[Layout('layouts.app')]
class AttendanceRegister extends Component
{
    // Status per participant
    public array $attendance = [];

    // Remarks per participant
    public array $remarks = [];

    public $centre;

    public string $selectedDate = '';



    public function mount()
    {
        $user = Auth::user();

        $this->centre = $user->coordinator_location;

        if (!$this->centre) {
            abort(403, 'No centre assigned to this coordinator.');
        }


        $this->selectedDate = Carbon::today()->format('Y-m-d');

        $this->loadAttendance();
    }



    #[Computed]
    public function centreName(): string
    {
        if (is_object($this->centre)) {
            return $this->centre->name ?? $this->centre->location ?? '';
        }

        return (string) $this->centre;
    }



    #[Computed]
    public function participants()
    {
        return Application::where('APL_Programme_Center', $this->centreName)
            ->where('APL_Status', 'Accepted')
            ->orderBy('APL_LName')
            ->orderBy('APL_FName')
            ->get();
    }



    #[Computed]
    public function summary(): array
    {
        $counts = array_count_values(array_filter($this->attendance));

        return [
            'present' => $counts['Present'] ?? 0,
            'late'    => $counts['Late'] ?? 0,
            'absent'  => $counts['Absent'] ?? 0,
            'excused' => $counts['Excused'] ?? 0,
        ];
    }



    public function updatedSelectedDate()
    {
        $this->loadAttendance();
    }



    public function loadAttendance()
    {
        $this->attendance = [];
        $this->remarks = [];


        $date = Carbon::parse($this->selectedDate)->toDateString();


        $participantIds = $this->participants
            ->pluck('APL_ID')
            ->filter()
            ->toArray();


        if (empty($participantIds)) {
            return;
        }



        $existingRecords = Attendance::whereIn('application_id', $participantIds)
            ->whereDate('attendance_date', $date)
            ->get()
            ->keyBy('application_id');



        foreach ($this->participants as $participant) {

            $record = $existingRecords[$participant->APL_ID] ?? null;


            $this->attendance[$participant->APL_ID] =
                $record?->status ?? '';


            $this->remarks[$participant->APL_ID] =
                $record?->remarks ?? '';

        }
    }





    public function saveAttendance()
    {
        $this->validate([

            'selectedDate' => 'required|date|before_or_equal:today',

            'attendance.*' => 'nullable|string|in:Present,Late,Absent,Excused',

            'remarks.*' => 'nullable|string|max:500',

        ]);



        $date = Carbon::parse($this->selectedDate)->toDateString();



        foreach ($this->attendance as $applicationId => $status) {


            if (empty($status)) {


                Attendance::where('application_id', $applicationId)
                    ->whereDate('attendance_date', $date)
                    ->delete();


                continue;

            }



            Attendance::updateOrCreate(

                [

                    'application_id'  => $applicationId,

                    'attendance_date' => $date,

                ],


                [

                    'status' => $status,

                    'remarks' => $this->remarks[$applicationId] ?? null,

                    'recorded_by' => Auth::id(),

                    'centre' => $this->centreName,

                ]

            );

        }



        session()->flash(
            'status',
            'Attendance recorded successfully for ' .
            Carbon::parse($date)->format('d M Y') .
            '.'
        );
    }



    public function render()
    {
        return view('livewire.coordinator.attendance-register');
    }
}