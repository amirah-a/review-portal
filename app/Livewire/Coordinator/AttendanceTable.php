<?php

namespace App\Livewire\Coordinator;

use Livewire\Component;
use App\Models\Application;
use App\Models\Attendance;

class AttendanceTable extends Component
{
    public $students = [];

    public $attendance = [];


    public function mount()
    {
        $centre = auth()->user()->centre;


        $this->students = Application::where(
                'APL_Programme_Center',
                $centre
            )
            ->where(
                'APL_Status',
                'Approved'
            )
            ->get();


        foreach ($this->students as $student) {

            $this->attendance[$student->id] = [
                'student_id' => $student->id,

                'name' => trim(
                    ($student->APL_First_Name ?? '') .
                    ' ' .
                    ($student->APL_Last_Name ?? '')
                ),

                'status' => '',

                'remarks' => '',
            ];
        }


        // Load existing attendance if already recorded

        $existing = Attendance::where(
                'centre',
                $centre
            )
            ->whereDate(
                'attendance_date',
                today()
            )
            ->first();


        if ($existing && $existing->attendance_data) {

            $this->attendance = $existing->attendance_data;

        }
    }



    public function save()
    {

        Attendance::updateOrCreate(

            [

                'centre' => auth()->user()->centre,

                'attendance_date' => today(),

            ],

            [

                'recorded_by' => auth()->id(),

                'attendance_data' => array_values(
                    $this->attendance
                ),

            ]

        );


        session()->flash(
            'success',
            'Attendance saved successfully.'
        );

    }



    public function render()
    {
        return view(
            'livewire.coordinator.attendance-table'
        );
    }
}
