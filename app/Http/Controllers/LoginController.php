<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class LoginController extends Controller
{
    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'min:6'],
        ]);

        $user = User::query()->where('email', $request->get('email'))->first();
        if ($user) {
            if (Hash::check($request->get('password'), $user->password)) {
                return response()->json(new UserResource($user));
            }
        }

        return response()->json([
            'error' => 'The provided credentials do not match our records.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'email' => ['required', 'email', 'unique:users'],
                'name' => ['required'],
                'password' => ['required', 'min:6'],
            ]);

            $user = User::query()->create([
                'email' => $request->get('email'),
                'name' => $request->get('name'),
                'password' => Hash::make($request->get('password'))
            ]);

            if ($user) {
                return response()->json(new UserResource($user));
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ]);
        }

    }
}
