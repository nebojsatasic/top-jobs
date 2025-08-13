<?php

namespace App\Http\Controllers;

use App\Http\Middleware\isEmployer;
use App\Http\Middleware\isPremiumUser;
use App\Http\Requests\JobEditFormRequest;
use App\Http\Requests\JobPostFormRequest;
use App\Models\Listing;
use App\Post\JobPost;

class PostJobController extends Controller
{
    /**
     * @var JobPost
     */
    protected $job;

    /**
     * PostJobController Constructor
     *
     * @param JobPost $job
     */
    public function __construct(JobPost $job)
    {
        $this->job = $job;
        $this->middleware('auth');
        $this->middleware(isPremiumUser::class)->only(['create', 'store']);
        $this->middleware(isEmployer::class);
    }

    /**
     * Display all jobs of the logged employer.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $jobs = Listing::where('user_id', auth()->user()->id)->get();

        return view('job.index', compact('jobs'));
    }

    /**
     * Display the form for creating a job.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('job.create');
    }

    /**
     * Store a job.
     *
     * @param JobPostFormRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(JobPostFormRequest $request)
    {
        $this->job->store($request);

        return redirect()->route('job.index')->with('success', 'Your job post has been posted');
    }

    /**
     * Display the form for editing a job.
     *
     * @param Listing $listing
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Listing $listing)
    {
        return view('job.edit', compact('listing'));
    }

    /**
     * Update a job.
     *
     * @param int $id
     * @param JobEditFormRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update($id, JobEditFormRequest $request)
    {
        $this->job->updatePost($id, $request);

        return back()->with('success', 'Your job post has been successfully updated');
    }

    /**
     * Delete a job.
     *
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function destroy($id)
    {
        Listing::find($id)->delete();

        return back()->with('success', 'Your job post has been successfully deleted');
    }
}
