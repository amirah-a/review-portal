<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Mail\LeadUpAcceptedMail;
use Illuminate\Support\Facades\Mail;
use DB;

class IndexController extends Controller
{
    public function index()
    {
        $totalApplications = Application::count();
        $open = Application::where('APL_Status', 'open')->count();
        $underReview = Application::where('APL_Status', 'Pending Review')->count();
        $approved = Application::where('APL_Status', 'Accepted')->count();
        $declined = Application::where('APL_Status', 'Declined')->count();

        $centres = DB::table('programme_centres')
            ->select('id', 'name', 'location')
            ->selectSub(function ($query) {
                $query->from('applications')->selectRaw('COUNT(*)')->whereRaw('TRIM(LOWER(applications.APL_Programme_Center)) = TRIM(LOWER(programme_centres.name))');
            }, 'applications_count')
            ->orderBy('name', 'asc')
            ->get();

        return view('dashboard', compact('totalApplications', 'open', 'underReview', 'approved', 'declined', 'centres'));
    }

    public function viewAll()
    {
        return view('livewire.pages.applications.view-all');
    }

    public function show($id)
    {
        $application = \App\Models\Application::findOrFail($id);

        // Get metadata rows, keyed by the code identifier strings
        $fields = \DB::table('form_data')->where('Field_Name', '<>', '')->get()->keyBy('Field_Name');

        return view('livewire.pages.applications.show-record', compact('application', 'fields'));
    }

    public function testMail()
    {
        $application = Application::where('APL_ID', 468)->first();
        Mail::to($application->APL_Parent_Email)->send(new LeadUpAcceptedMail($application));

        // $name = "Amirah Ali";
        // Http::withHeaders([
        //     'appID' => env('SWIFT_APP_ID'),
        //     'Authorization' => 'Bearer ' . env('SWIFT_TOKEN'),
        // ])->post('https://swift.msya.gov.tt/api/general', [
        //     'email' => 'aliamirah07@gmail.com',
        //     'title' => 'Congratulations!',
        //     'subject' => 'Lead Up Application (2026)',
        //     'name' => $name,
        //     'body' => 'We are excited to welcome you and look forward to meeting you on Monday 13th July 2026 at your assigned RAPP LEAD-UP Centre. Get ready for four weeks of fun, learning, new friendships, exciting activities, and unforgettable experiences!. See you on Monday! We can\'t wait to begin this journey with you.',
        //     'app' => 'LEAD Up 2026',
        //     'header' => "You have been selected to participate in the RAPP LEAD-UP (Leadership, Empowerment, Advancement, Development, Upliftment and Progress) Vacation Programme!",
        //     'fromAddress' => 'noreply.mydns@gov.tt ',
        //     'fromName' => 'MSYA',
        // ]);
    }

    public function liveMail()
    {
        $applications = Application::query()->where('APL_Status', 'Accepted')->where('APL_INVALID', 1)->whereNull('acceptance_email_sent_at')->get();

        foreach ($applications as $application) {
            Mail::to($application->APL_Parent_Email)->send(new LeadUpAcceptedMail($application));

            $application->update([
                'acceptance_email_sent_at' => now(),
            ]);
        }
    }
}
