<?php

namespace App\Http\Controllers;

use App\Enums\UserStatus;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail;


use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;


class AuthenticationController extends Controller
{
    public function signupPage()
    {

        return view('authentication.signup');

    }



    public function dashboardPage()
    {

        return view('site.dashboard');

    }



    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'phone' => 'required',
            'password' => [ 'required','confirmed','min:8','regex:/[a-z]/','regex:/[A-Z]/','regex:/[0-9]/',],
        ]);

        $data['user_status'] = UserStatus::APPROVED;

        User::create($data);

        return response()->json([
            'success' => true,
            'message' => 'User created successfully'
        ], 201);
    }




    public function loginPage()
    {
        return view('authentication.signin');
    }



    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        $remember = $request->boolean('remember');

        if (!Auth::attempt($credentials, $remember)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        $request->session()->regenerate();

        return response()->json([
            'success' => true,
            'message' => 'Login successful'
        ]);
    }



    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'success' => true,
            'message' => 'Logged out'
        ]);
    }



     public function forgetPage()
    {
        return view('authentication.forgetPassword');
    }



    // public function forgetPassword (Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required|email|exists:users,email',
    //     ]);
    //     $status = Password::sendResetLink(
    //         $request->only('email'),
    //     );
    //     if($status === Password::RESET_LINK_SENT){
    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Reset link send successfully'
    //         ]);
    //     }
    //     return response()->json([
    //         'success' => false ,
    //         'message' => 'Unable to send Reset link'
    //     ]);
    // }


    public function forgetPassword(Request $request)
{
    $request->validate([
        'email' => 'required|email|exists:users,email',
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'User not found',
        ]);
    }


    $token = Password::broker()->createToken($user);



$resetUrl = route('reset.page', ['token' => $token,'email' => $user->email]);


    Mail::to($user->email)->send(new ResetPasswordMail($resetUrl));

    return response()->json([
        'success' => true,
        'message' => 'Reset link sent successfully',
    ]);
}







    public function resetPage()
    {
        return view('authentication.resetPassword');
    }




    public function resetPassword (Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
                'password' => [
            'required',
            'confirmed',
            'min:8',
            'regex:/[a-z]/',
            'regex:/[A-Z]/',
            'regex:/[0-9]/',
        ],
        ]);
        $status = Password::reset(
            $request->only('email' , 'password' , 'password_confirmation' , 'token'),
            function($user, $password){
            $user->forceFill([
                'password' => Hash::make($password),
                'remember_token' => Str::random(60),
            ])->save();
            event(new PasswordReset($user));
            }
        );

        if($status === Password::PASSWORD_RESET){
            return response()->json([
                'success' => true,
                'message' => 'Password reset Successfully'
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Invalid or expired token'
        ], 400);


    }
}






