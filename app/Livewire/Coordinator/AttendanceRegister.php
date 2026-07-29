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
    // Array holding selected status per participant: [application_id => status]
    public array $attendance = [];

    public $centre;

    public string $selectedDate = '';

    public function mount()
    {
        $user = Auth::user();

        $this->centre = $user->coordinator_location;

        if (!$this->centre) {
            abort(403, 'No centre assigned to this coordinator.');
        }

        // Set initial date to today
        $this->selectedDate = Carbon::today()->format('Y-m-d');

        $this->loadAttendance();
    }

    /**
     * Get the centre name string regardless of whether $this->centre is object or string.
     */
    #[Computed]
    public function centreName(): string
    {
        if (is_object($this->centre)) {
            return $this->centre->name ?? $this->centre->location ?? '';
        }

        return (string) $this->centre;
    }

    /**
     * Computed Property: Fetches accepted participants for assigned centre.
     */
    #[Computed]
    public function participants()
    {
        return Application::where('APL_Programme_Center', $this->centreName)
            ->where('APL_Status', 'Accepted')
            ->orderBy('APL_LName')
            ->orderBy('APL_FName')
            ->get();
    }

    /**
     * Real-time computed summary counts based on active select state.
     */
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

    /**
     * Triggered automatically by Livewire when the selected date updates.
     */
    public function updatedSelectedDate()
    {
        $this->loadAttendance();
    }

    /**
     * Load existing attendance for the selected date.
     */
    public function loadAttendance()
    {
        $this->attendance = [];

        $date = Carbon::parse($this->selectedDate)->toDateString();
        $participantIds = $this->participants->pluck('APL_ID')->filter()->toArray();

        if (empty($participantIds)) {
            return;
        }

        // Fetch existing records indexed by application_id
        $existingRecords = Attendance::whereIn('application_id', $participantIds)
            ->whereDate('attendance_date', $date)
            ->pluck('status', 'application_id')
            ->toArray();

        // Map status or default to empty string for every participant
        foreach ($this->participants as $participant) {
            $this->attendance[$participant->APL_ID] = $existingRecords[$participant->APL_ID] ?? '';
        }
    }

    public function saveAttendance()
    {
        $this->validate([
            'selectedDate' => 'required|date|before_or_equal:today',
            'attendance.*' => 'nullable|string|in:Present,Late,Absent,Excused',
        ]);

        $date = Carbon::parse($this->selectedDate)->toDateString();

        foreach ($this->attendance as $applicationId => $status) {
            if (empty($status)) {
                // Delete record if status was reset to empty
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
                    'status'      => $status,
                    'recorded_by' => Auth::id(),
                    'centre'      => $this->centreName,
                ]
            );
        }

        session()->flash('status', 'Attendance recorded successfully for ' . Carbon::parse($date)->format('d M Y') . '.');
    }

    public function render()
    {
        return view('livewire.coordinator.attendance-register');
    }
}