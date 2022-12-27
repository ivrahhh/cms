<?php

namespace App\Http\Livewire;

use Illuminate\Http\Request;
use Livewire\Component;

class ResendVerificationLink extends Component
{
    public function resend(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();

        return redirect()->with([
            'status' => 'Verification Link sent!',
        ]);
    }

    public function render()
    {
        return view('livewire.resend-verification-link');
    }
}
