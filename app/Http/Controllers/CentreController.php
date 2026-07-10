<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\ProgrammeCentre;

class CentreController extends Controller
{
    public function show(ProgrammeCentre $centre)
    {
        $applications = Application::query()->where('APL_Programme_Center', $centre->name)->latest()->get();

        $approvedCount = $applications->where('APL_Status', 'Approved')->count();

        $declinedCount = $applications->where('APL_Status', 'Declined')->count();

        $openCount = $applications->where('APL_Status', 'Open')->count();

        return view('livewire.centre-show', [
            'centre' => $centre,
            'applications' => $applications,
            'approvedCount' => $approvedCount,
            'declinedCount' => $declinedCount,
            'openCount' => $openCount,
        ]);
    }
}
