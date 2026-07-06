<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use DB;

class IndexController extends Controller
{
    public function index()
    {
        $totalApplications = Application::count();
        $open = Application::where('APL_Status', 'open')->count();
        $underReview = Application::where('APL_Status', 'under review')->count();
        $approved = Application::where('APL_Status', 'approved')->count();
        $declined = Application::where('APL_Status', 'declined')->count();

        $centres = DB::table('programme_centres')
            ->select('name', 'location')
            ->selectSub(function ($query) {
                $query->from('applications')
                    ->selectRaw('COUNT(*)')
                    // TRIM eliminates hidden spaces, LOWER ignores case discrepancies
                    ->whereRaw('TRIM(LOWER(applications.APL_Programme_Center)) = TRIM(LOWER(programme_centres.name))');
            }, 'applications_count')
            ->orderBy('name', 'asc')
            ->get();

        return view('dashboard', compact(
            'totalApplications',
            'open',
            'underReview',
            'approved',
            'declined',
            'centres'
        ));
    }

    public function viewAll()
    {
        return view('livewire.pages.applications.view-all');
    }

    public function show($id)
    {
        $application = \App\Models\Application::findOrFail($id);

        // Get metadata rows, keyed by the code identifier strings
        $fields = \DB::table('form_data')
            ->where('Field_Name', '<>', '')
            ->get()
            ->keyBy('Field_Name');

        return view('livewire.pages.applications.show-record', compact('application', 'fields'));
    }
}
