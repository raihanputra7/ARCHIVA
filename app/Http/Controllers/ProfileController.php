<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Services\AdminMonitoringService;
use App\Services\Notifications\NotificationDispatcher;
use App\Services\RegistrationService;
use App\Support\PhoneNormalizer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request, AdminMonitoringService $adminService): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
            'adminContacts' => $adminService->getAdminContacts(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(
        ProfileUpdateRequest $request,
        RegistrationService $registrationService,
        NotificationDispatcher $dispatcher,
    ): RedirectResponse {
        $validated = $request->validated();
        $user = $request->user();

        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone_wa' => ! empty($validated['phone_wa'])
                ? PhoneNormalizer::normalize($validated['phone_wa'])
                : null,
            'notify_email' => $request->boolean('notify_email'),
        ]);

        $emailChanged = $user->isDirty('email');

        if ($emailChanged) {
            $user->email_verified_at = null;
        }

        $user->save();

        if ($emailChanged) {
            $plainToken = $registrationService->issueTokenForUser($user);
            $dispatcher->sendVerificationEmail($user, $plainToken);

            return Redirect::route('profile.edit')->with('status', 'profile-email-verification-sent');
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
}
