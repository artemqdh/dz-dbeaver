<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class SearchController extends Controller
{
    public function searchAjax(Request $request)
    {
        if ($request->has('search') && $request->get('search') != '') {
            $users = User::query()
                ->with('profileImage')
                ->where('name', 'like', '%' . $request->get('search') . '%')
                ->orWhere('email', 'like', '%' . $request->get('search') . '%')
                ->get();

            return response()->json($users);
        } elseif ($request->has('search') && is_null($request->get('search'))) {
            $users = User::query()
                ->with('profileImage')
                ->get();

            foreach ($users as $user) {
                $user->image->socset;
            }
            return response()->json($users);
        }
        throw new BadRequestHttpException();
    }

    public function search()
    {
        return view('search');
    }
}