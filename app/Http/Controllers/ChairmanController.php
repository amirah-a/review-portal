<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\ProgrammeCentre;
use Illuminate\Http\Request;

class ChairmanController extends Controller
{
    /**
     * Display all programme centres.
     */
    public function attendanceCentres()
    {
        $centres = ProgrammeCentre::orderBy('name')->get();

        return view('chairman.attendance.attendance-centres', compact('centres'));
    }

    /**
     * Display attendance days recorded for a centre.
     */
    public function attendanceDays(ProgrammeCentre $centre)
    {
        $days = Attendance::where('centre', $centre->name)
            ->selectRaw(
                "
            attendance_date,
            COUNT(*) as total,
            SUM(CASE WHEN status = 'Present' THEN 1 ELSE 0 END) as present,
            SUM(CASE WHEN status = 'Late' THEN 1 ELSE 0 END) as late,
            SUM(CASE WHEN status = 'Absent' THEN 1 ELSE 0 END) as absent,
            SUM(CASE WHEN status = 'Excused' THEN 1 ELSE 0 END) as excused
        ",
            )
            ->groupBy('attendance_date')
            ->orderByDesc('attendance_date')
            ->get();

        $totalRecords = Attendance::where('centre', $centre->name)->count();

        $present = Attendance::where('centre', $centre->name)->where('status', 'Present')->count();

        $late = Attendance::where('centre', $centre->name)->where('status', 'Late')->count();

        $absent = Attendance::where('centre', $centre->name)->where('status', 'Absent')->count();

        $excused = Attendance::where('centre', $centre->name)->where('status', 'Excused')->count();

        return view('chairman.attendance.attendance-days', compact('centre', 'days', 'totalRecords', 'present', 'late', 'absent', 'excused'));
    }

    /**
     * Display individual student attendance for a specific day.
     */
    public function attendanceDay($centre, $date)
    {
        $centre = ProgrammeCentre::findOrFail($centre);

        /*
         * Get all attendance records for this centre
         * and specific attendance date.
         */
        $attendance = Attendance::with(['participant', 'recorder'])
            ->where('centre', $centre->name)
            ->whereDate('attendance_date', $date)
            ->orderBy('status')
            ->get();

        /*
         * KPIs for the selected day.
         */
        $summary = [
            'total' => $attendance->count(),

            'present' => $attendance->where('status', 'Present')->count(),

            'late' => $attendance->where('status', 'Late')->count(),

            'absent' => $attendance->where('status', 'Absent')->count(),

            'excused' => $attendance->where('status', 'Excused')->count(),
        ];

        return view('chairman.attendance.attendance-day', compact('centre', 'date', 'attendance', 'summary'));
    }
}
