<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * DashboardController Constructor
     */
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * Display dashboard.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('dashboard');
    }

    /**
     * Display page for sending verification email.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function verify()
    {
        return view('user.verify');
    }

    /**
     * Send email with verification link if the user's email is not verified.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function resend(Request $request)
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('listing.index')->with('success', 'Your email was werified');
        }

        $user->sendEmailVerificationNotification();

        return back()->with('success', 'Verification link sent successfully');
    }
}
