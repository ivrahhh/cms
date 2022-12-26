<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    public function __invoke(EmailVerificationRequest $request) : RedirectResponse
    {
        if(!$request->user()->hasVerifiedEmail()) {
            $request->fulfill();
        }   

        return redirect()->intended('/');
    }
}
