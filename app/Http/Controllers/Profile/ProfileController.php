<?php

namespace App\Http\Controllers\Profile;

use App\Events\SendToast;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    public function update(ProfileUpdateRequest $request)
    {
        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->forceFill([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
        ])->save();

        broadcast(new SendToast('Profile updated!', 'success', $request->user()->id));

        return Redirect::route('profile.edit');
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
}
