<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class HomeController extends Controller
{
    public function welcome(Request $request): View
    {
        $user = null;

        $userIdFromQuery = $request->query('userId');

        if ($userIdFromQuery)
        {
            try
            {
                $user = User::with('profileImage')->findOrFail($userIdFromQuery);
            }
            catch (ModelNotFoundException $exception)
            {
                Log::critical($exception->getMessage());
                $userIdFromQuery = null;
            }
        }

        return view('welcome', ['user' => $user]);
    }
}