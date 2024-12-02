<?php

namespace App\Http\Controllers\Profile;

use App\Events\SendToast;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfilePhotoController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'photo' => ['required', 'mimes:jpg,jpeg,png', 'max:1024'],
        ]);

        $request->user()->updateProfilePhoto($request->file('photo'));

        broadcast(new SendToast('Profile photo uploaded!', 'success', $request->user()->id));

        return back();
    }

    public function destroy(Request $request)
    {
        $request->user()->deleteProfilePhoto();

        broadcast(new SendToast('Profile photo deleted!', 'success', $request->user()->id));

        return back();
    }
}
