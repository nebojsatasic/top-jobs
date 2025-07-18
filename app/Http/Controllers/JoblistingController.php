<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Listing;
use Illuminate\Http\Request;

class JoblistingController extends Controller
{
    /**
     * Filter and display jobs.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $salary = $request->query('sort');
        $date = $request->query('date');
        $jobType = $request->query('job_type');

        $listings = Listing::query();

        if ($salary === 'high_to_low') {
            $listings->orderByRaw('CAST(salary AS UNSIGNED) DESC');
        } elseif ($salary === 'low_to_high') {
            $listing->orderByRaw('CAST(salary AS UNSIGNED) ASC');
        }

        if ($date === 'latest') {
            $listing->orderBy('created_at', 'desc');
        } elseif ($date === 'oldest') {
            $listing->orderBy('created_at', 'asc');
        }

        if ($jobType === 'Fulltime') {
            $listings->where('job_type', 'Fulltime');
        } elseif ($jobType === 'Parttime') {
            $listings->where('job_type', 'Parttime');
        } elseif ($jobType === 'Casual') {
            $listings->where('job_type', 'Casual');
        } elseif ($jobType === 'Contract') {
            $listings->where('job_type', 'Contract');
        }

        $jobs = $listings->with('profile')->get();

        return view('home', compact('jobs'));
    }

    /**
     * Display a job.
     *
     * @param Listing $listing
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Listing $listing)
    {
        return view('show', compact('listing'));
    }

    /**
     * Display an employer and it's jobs.
     *
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function company($id)
    {
        $company = User::with('jobs')->where('id', $id)->where('user_type', 'employer')->first();

        return view('company', compact('company'));
    }
}
