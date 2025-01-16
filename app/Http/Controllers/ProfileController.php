<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{

    protected array $breadcrumb = array();

    public function __construct()
    {
        parent::__construct();
        $this->menu = 'Users';
        $this->route = $this->routes['web'] . 'admin.user';
        $this->slug = $this->slugs['web'] . 'user';
        $this->view = $this->views['web'] . 'user';
        $this->breadcrumb = $this->breadcrumbs;

        \Inertia\Inertia::share([
            'menu' => $this->menu,
        ]);
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        $breadcrumb = $this->breadcrumb;
        $breadcrumb[] = ['label' => 'User', 'slug' => 'user'];
        $breadcrumb[] = ['label' => 'Profile', 'slug' => 'profile'];
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
            'breadcrumbs' => $breadcrumb
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
