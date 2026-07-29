<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\Attendance;

class CoordinatorController extends Controller
{
    public function index()
    {
        $centre = auth()->user()->coordinator_location;


        /*
        |--------------------------------------------------------------------------
        | Participants
        |--------------------------------------------------------------------------
        */

        $applications = Application::where('APL_Status', 'Accepted')
            ->where('APL_Programme_Center', $centre)
            ->get();



        /*
        |--------------------------------------------------------------------------
        | Today Attendance Summary
        |--------------------------------------------------------------------------
        */

        $todayRecords = Attendance::whereDate(
                'attendance_date',
                today()
            )
            ->where('centre', $centre)
            ->get();



        $summary = [
            'total_students' => $applications->count(),
            
            'present' => $todayRecords->where('status', 'Present')->count(),

            'late' => $todayRecords->where('status', 'Late')->count(),

            'absent' => $todayRecords->where('status', 'Absent')->count(),

            'excused' => $todayRecords->where('status', 'Excused')->count(),
        ];



        /*
        |--------------------------------------------------------------------------
        | Recent Attendance Records
        |--------------------------------------------------------------------------
        */

        $recentAttendance = Attendance::where(
                'centre',
                $centre
            )
            ->selectRaw('
                attendance_date,
                recorded_by,
                COUNT(CASE WHEN status = "Present" THEN 1 END) as present,
                COUNT(CASE WHEN status = "Absent" THEN 1 END) as absent,
                COUNT(CASE WHEN status = "Late" THEN 1 END) as late,
                COUNT(CASE WHEN status = "Excused" THEN 1 END) as excused
            ')
            ->groupBy(
                'attendance_date',
                'recorded_by'
            )
            ->orderBy(
                'attendance_date',
                'desc'
            )
            ->limit(10)
            ->get();



        return view(
            'livewire.coordinator.dashboard',
            compact(
                'centre',
                'applications',
                'summary',
                'recentAttendance'
            )
        );
    }
}