<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CoordinatorController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $centreName = $user->coordinator_location;


        /*
        |--------------------------------------------------------------------------
        | Centre Information
        |--------------------------------------------------------------------------
        */

        $centre = (object) [
            'name' => $centreName,
            'location' => $centreName,
        ];



        /*
        |--------------------------------------------------------------------------
        | Participants
        |--------------------------------------------------------------------------
        */

        $participants = Application::where('APL_Status', 'Approved')
            ->where('APL_Programme_Center', $centreName)
            ->count();



        /*
        |--------------------------------------------------------------------------
        | Today's Attendance
        |--------------------------------------------------------------------------
        */

        $todayAttendance = Attendance::whereDate(
                'attendance_date',
                Carbon::today()
            )
            ->where('centre', $centreName)
            ->first();



        $presentToday = 0;
        $lateToday = 0;
        $absentToday = 0;
        $excusedToday = 0;



        if ($todayAttendance) {

            $attendanceData = collect(
                $todayAttendance->attendance_data ?? []
            );


            $presentToday = $attendanceData
                ->where('status', 'Present')
                ->count();


            $lateToday = $attendanceData
                ->where('status', 'Late')
                ->count();


            $absentToday = $attendanceData
                ->where('status', 'Absent')
                ->count();


            $excusedToday = $attendanceData
                ->where('status', 'Excused')
                ->count();

        }



        /*
        |--------------------------------------------------------------------------
        | Attendance History
        |--------------------------------------------------------------------------
        */

        $recentAttendance = Attendance::where('centre', $centreName)
            ->latest('attendance_date')
            ->limit(10)
            ->get()
            ->map(function ($attendance) {


                $data = collect(
                    $attendance->attendance_data ?? []
                );


                $attendance->date = Carbon::parse(
                    $attendance->attendance_date
                );


                $attendance->present = $data
                    ->where('status', 'Present')
                    ->count();


                $attendance->absent = $data
                    ->where('status', 'Absent')
                    ->count();


                return $attendance;

            });



        return view('livewire.coordinator.dashboard', [

            'centre' => $centre,

            'participants' => $participants,

            'summary' => [
                'present' => $presentToday,
                'late' => $lateToday,
                'absent' => $absentToday,
                'excused' => $excusedToday,
            ],

            'recentAttendance' => $recentAttendance,

        ]);
    }
}
