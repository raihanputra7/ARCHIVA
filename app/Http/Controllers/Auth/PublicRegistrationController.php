<?php

namespace App\Http\Controllers\Auth;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\PublicRegisterRequest;
use App\Services\RegistrationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicRegistrationController extends Controller
{
    public function __construct(
        private readonly RegistrationService $registrationService
    ) {}

    public function create(): View
    {
        $publicRoles = collect(RegistrationService::allowedPublicRoles())
            ->map(fn (string $val) => Role::from($val));

        return view('auth.register', compact('publicRoles'));
    }

    public function store(PublicRegisterRequest $request): RedirectResponse
    {
        $user = $this->registrationService->register($request->validated());

        return redirect()
            ->route('register.accepted')
            ->with('registered_email', $user->email);
    }

    public function accepted(): View
    {
        return view('auth.register-accepted');
    }

    public function verifyEmail(string $token): View
    {
        $result = $this->registrationService->verifyToken($token);

        return view('auth.verify-email-result', [
            'success' => $result['success'],
            'message' => $result['message'],
        ]);
    }

    public function resendVerification(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'string', 'email', 'max:191'],
        ], [
            'email.required' => 'Masukkan alamat email Anda terlebih dahulu.',
            'email.email' => 'Format alamat email tidak valid.',
        ]);

        $this->registrationService->resendVerification($validated['email']);

        return back()->with(
            'status',
            'Jika alamat email terdaftar dan belum terverifikasi, tautan verifikasi baru telah dikirim ke kotak masuk Anda.'
        );
    }
}
