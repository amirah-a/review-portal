<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\Attendance;

class CoordinatorController extends Controller
{
    public function index()
    {
        $centre = auth()->user()->centre;

        $applications = Application::where('APL_Status', 'Approved')->where('APL_Programme_Center', $centre)->get();

        $todayAttendance = Attendance::whereDate('attendance_date', today())->where('centre', $centre)->first();

        $summary = [
            'total_students' => $applications->count(),

            'present' => 0,

            'absent' => 0,

            'pending' => $applications->count(),
        ];

        if ($todayAttendance) {
            $records = $todayAttendance->attendance_data ?? [];

            foreach ($records as $record) {
                if (($record['status'] ?? '') === 'Present') {
                    $summary['present']++;
                } elseif (($record['status'] ?? '') === 'Absent') {
                    $summary['absent']++;
                }
            }

            $summary['pending'] = $summary['total_students'] - ($summary['present'] + $summary['absent']);
        }

        $history = Attendance::where('centre', $centre)->orderBy('attendance_date', 'desc')->limit(10)->get();

        return view('livewire.coordinator.dashboard', compact('applications', 'todayAttendance', 'summary', 'history'));
    }
}
