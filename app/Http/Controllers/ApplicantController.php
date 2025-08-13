<?php

namespace App\Http\Controllers;

use App\Mail\ShortlistMail;
use App\Models\Listing;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ApplicantController extends Controller
{
        /**
     * Display jobs of an employer and the data of seekers that applied for them.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $listings = Listing::latest()->withCount('users')->where('user_id', auth()->user()->id)->get();

        return view('applicants.index', compact('listings'));
    }

    /**
     * Display users (job seekers) who applied for a job of the authenticated employer.
     *
     * Only the creator of the job can view applicants for that job.
     *
     * @param Listing $listing
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Listing $listing)
    {
        $this->authorize('view', $listing);

        $listing = Listing::with('users')->where('slug', $listing->slug)->first();

        return view('applicants.show', compact('listing'));
    }

    /**
     * Shortlist an applicant and send an email to notify them to prepare for interview.
     *
     * @param int $listingId
     * @param int $userId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function shortlist($listingId, $userId)
    {
        $listing = Listing::find($listingId);
        $user = User::find($userId);
        if ($listing) {
            $listing->users()->updateExistingPivot($userId, ['shortlisted' => true]);
            Mail::to($user->email)->queue(new ShortlistMail($user->name, $listing->title));

            return back()->with('success', 'User is shortlisted successfully');
        }

        return back();
    }

    /**
     * Apply for a job.
     *
     * @param int $listingId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function apply($listingId)
    {
        $user = auth()->user();
        $user->listings()->syncWithoutDetaching($listingId);

        return back()->with('success', 'Your application was successfully submitted');
    }
}
