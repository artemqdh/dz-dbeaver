<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class VerifyController
{
    public function verify(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);

        if (! $request->hasValidSignature()) {
            return "Invalid or expired verification link.";
        }

        if ($hash !== sha1($user->email)) {
            return "Invalid verification hash.";
        }

        if ($user->email_verified_at) {
            return "Email already verified.";
        }

        $user->email_verified_at = now();
        $user->save();

        return "Your email has been verified. You may now log in.";
    }
}
