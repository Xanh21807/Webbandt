<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;    
use Illuminate\Support\Facades\Auth;
use Exception;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Hash;
class LoginGoogleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }
    public function handleGoogleCallback()
    {
        try {

            $googleUser = Socialite::driver('google')->user();

            $user = User::updateOrCreate(
                ['email' => $googleUser->email],
                [
                    'name' => $googleUser->name,
                    'google_id' => $googleUser->id,
                    'password' => Hash::make('123456dummy')
                ]
            );

            $token = $user->createToken('auth_token')->plainTextToken;

            $userData = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'status' => $user->status
            ];

            $userJson = json_encode($userData);

            return response("
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset='utf-8'>
                <title>Đang đăng nhập...</title>
            </head>
            <body>

            <script>

                localStorage.clear();

                localStorage.setItem('auth_token', '{$token}');
                localStorage.setItem('user', '{$userJson}');

                window.location.href = '/';

            </script>

            </body>
            </html>
            ");

        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}
