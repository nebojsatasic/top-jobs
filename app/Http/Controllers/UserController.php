<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegistrationFormRequest;
use App\Models\Listing;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    const JOB_SEEKER = 'seeker';
    const JOB_POSTER = 'employer';

    /**
     * Display the form for registering job seekers.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createSeeker()
    {
        return view('user.seeker-register');
    }

    /**
     * Display the form for registering employers.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createEmployer()
    {
        return view('user.employer-register');
    }

    /**
     * Store a job seeker.
     *
     * @param RegistrationFormRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function storeSeeker(RegistrationFormRequest $request)
    {
        $user = User::create([
            'name' => request('name'),
            'email' => request('email'),
            'password' => bcrypt(request('password')),
            'user_type' => self::JOB_SEEKER
        ]);

        Auth::login($user);

        $user->sendEmailVerificationNotification();

        return response()->json('success');

        //return redirect()->route('verification.notice')->with('successMessage','Your account was created');
    }

    /**
     * Store an employer.
     *
     * @param RegistrationFormRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function storeEmployer(RegistrationFormRequest $request)
    {
        $user = User::create([
            'name' => request('name'),
            'email' => request('email'),
            'password' => bcrypt(request('password')),
            'user_type' => self::JOB_POSTER,
            'user_trial' => now()->addWeek()
        ]);

        Auth::login($user);

        $user->sendEmailVerificationNotification();

return response()->json('success');

        //return redirect()->route('verification.notice')->with('successMessage','Your account was created');
    }

    /**
     * Display the login form.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function login()
    {
        return view('user.login');
    }

    /**
     * Login
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function postLogin(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            if (!auth()->user()->email_verified_at) {
                return redirect()->to('/verify');
            }
            if (auth()->user()->user_type == 'employer') {
                return redirect()->to('dashboard');
            } else {
                return redirect()->to('/');
            }
        }

        return 'Wrong email or password';
    }

    /**
     * Logout
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        auth()->logout();

        return redirect()->to('/');
    }

    /**
     * Display the page for updating an employer's profile.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function profile()
    {
        return view('profile.index');
    }

    /**
     * Update a user's profile.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function update(Request $request)
    {
        if ($request->hasFile('profile_pic')) {
            $imagePath = $request->file('profile_pic')->store('profile', 'public');

            User::find(auth()->user()->id)->update(['profile_pic' => $imagePath]);
        }

        User::find(auth()->user()->id)->update($request->except('profile_pic'));

        return back()->with('success', 'Your profile has been updated');
    }

    /**
     * Display the page on which a seeker can update their profile.
     *
     * A seeker can update profile, change password and upload resume on this page.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function seekerProfile()
    {
        return view('seeker.profile');
    }

    /**
     * Change password.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed'
        ]);

        $user = auth()->user();
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Current password is incorrect');
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Your password has been updated successfully');
    }

    /**
     * Upload resume by a job seeker.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function uploadResume(Request $request)
    {
        $this->validate($request, [
            'resume' => 'required|mimes:pdf,doc,docx'
        ]);

        if ($request->hasFile('resume')) {
            $resume = $request->file('resume')->store('resume', 'public');
            User::find(auth()->user()->id)->update(['resume' => $resume]);

            return back()->with('success', 'Your resume has been updated successfully');
        }
    }

    /**
     * Display the list of jobs the authenticated seeker applied for.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function jobApplied()
    {
        $users = User::with('listings')->where('id', auth()->user()->id)->get();

        return view('seeker.job-applied', compact('users'));
    }
}
