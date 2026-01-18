<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use App\Models\Account;
use App\Models\Adherent;

class ProfileController extends Controller
{

    public function index()
    {
        $user = Auth::user()->load('adherent');

        return view('profile.index', compact('user'));
    }


    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user()->load('adherent');

        return view('profile.edit', compact('user'));
    }


    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse{
        $user = $request->user();

        $user->fill($request->validated());

        if ($user->isDirty('COM_MAIL')) {
            $user->email_verified_at = null;
        }

        $user->save();

        if ($request->filled('ADH_NUM_LICENCIE') || $request->filled('ADH_NUM_PUCE')) {
            Adherent::updateOrCreate(
                ['COM_ID' => $user->COM_ID],
                [
                    'ADH_NUM_LICENCIE' => $request->ADH_NUM_LICENCIE,
                    'ADH_NUM_PUCE'     => $request->ADH_NUM_PUCE,
                ]
            );
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }


    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $user = $request->user();
        $user->COM_MDP = Hash::make($request->password);
        $user->save();

        return back()->with('status', 'password-updated');
    }
}
