<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    public function __invoke(Request $request)
    {
        $email = $request->validate([
            'email' => 'required|email',
        ]);

        $status = Password::sendResetLink($email);
        
        if($status === Password::RESET_LINK_SENT) {
            return back()->with([
                'status' => trans($status),
            ]);
        }

        return back()->withErrors([
            'email' => trans($status),
        ]);
    }
}
