<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Jobs\SendEmailUpdatePasswordJob;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as RulesPassword;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function showLogin($guard)
    {
        $validator = validator(['guard' => $guard], [
            'guard' => 'required|string|in:admin'
        ]);

        if (!$validator->fails()) {
            session()->put('guard', $guard);
            return view('cms.auth.login');
        }
        abort(Response::HTTP_NOT_FOUND);
    }

    public function login(Request $request)
    {
        $table = Str::plural(session('guard'));
        $validator = validator($request->all(), [
            'email' => 'required|email|exists:' . $table,
            'password' => 'required|string',
            'remember_me' => 'required|boolean',
        ]);

        if (!$validator->fails()) {
            if (Auth::guard(session('guard'))->attempt($request->only(['email', 'password']), $request->input('remember_me'))) {
                return response()->json([
                    'status' => true,
                    'message' => 'Login in Successfully'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Login Faild!'
                ], Response::HTTP_BAD_REQUEST);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => $validator->getMessageBag()->first()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function logout(Request $request)
    {
        $guard = session('guard');
        auth($guard)->logout();
        $request->session()->invalidate();
        return redirect(route('auth.showLogin', $guard));
    }

    public function showEmailVerification()
    {
        return view('cms.auth.email-verification');
    }

    public function emailVerification(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();
        return response()->json([
            'status' => true,
            'message' => 'Email verification request has been sent'
        ]);
    }

    public function verify(EmailVerificationRequest $emailVerificationRequest)
    {
        $emailVerificationRequest->fulfill();
        return redirect(route('home'));
    }

    public function forgotPassword()
    {
        return view('cms.auth.forgot-password');
    }

    public function requestResetPassword(Request $request)
    {
        $table = Str::plural(session('guard'));
        $validator = validator($request->all(), [
            'email' => 'required|email|exists:' . $table,
        ]);

        if (!$validator->fails()) {
            $status = Password::broker($table)->sendResetLink($request->all());
            return $status == Password::RESET_LINK_SENT ? response()->json([
                'status' => true,
                'message' => __($status)
            ]) : response()->json([
                'status' => false,
                'message' =>  __($status)
            ], Response::HTTP_BAD_REQUEST);
        } else {
            return response()->json([
                'status' => false,
                'message' => $validator->getMessageBag()->first()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function resetPassword(Request $request, $token)
    {
        return view('cms.auth.recover-password', ['token' => $token, 'email' => $request->input('email')]);
    }

    public function changePassword(Request $request)
    {
        $broker = Str::plural(session('guard'));
        $validator = validator($request->all(), [
            'token' => 'required',
            'email' => 'required|email|exists:password_reset_tokens',
            'password' => ['required', 'string', RulesPassword::min(8)->letters()->numbers()->mixedCase()->symbols()->uncompromised(), 'confirmed'],
        ]);

        if (!$validator->fails()) {
            $status = Password::broker($broker)->reset($request->all(), function ($user, $password) {
                $user->forceFill(['password' => Hash::make($password)])->save();
                event(new PasswordReset($user));
            });
            return $status == Password::PASSWORD_RESET ? response()->json([
                'status' => true,
                'message' => __($status)
            ]) : response()->json([
                'status' => false,
                'message' =>  __($status)
            ], Response::HTTP_BAD_REQUEST);
        } else {
            return response()->json([
                'status' => false,
                'message' => $validator->getMessageBag()->first()
            ], Response::HTTP_BAD_REQUEST);
        }
    }
    public function editProfile()
    {
        return view('cms.auth.edit-profile');
    }

    public function updateProfile(Request $request)
    {
        $table = Str::plural(session('guard'));
        $validator = validator($request->all(), [
            'full_name' => 'required|string|min:3|max:45',
            'email' => "required|email|unique:$table,email," . $request->user()->id,
            'phone_number' => "required|string|unique:$table,phone_number," . $request->user()->id,
            'address' => 'required|string|min:3|max:45',
        ]);

        if (!$validator->fails()) {
            if ($request->input('email') !== $request->user()->email) {
                $request->user()->email_verified_at = null;
            }
            $request->user()->update($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Update Profile'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => $validator->getMessageBag()->first()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function editpassword()
    {
        return view('cms.auth.edit-password');
    }
    public function updatePassword(Request $request)
    {
        $validator = validator($request->all(), [
            'oldPassword' => 'required|string|current_password:' . session('guard'),
            'newPassword' => ['required', 'string', RulesPassword::min(8)->letters()->numbers()->mixedCase()->symbols()->uncompromised(), 'confirmed'],
        ]);

        if (!$validator->fails()) {
            $request->user()->forceFill(['password' => Hash::make($request->input('newPassword'))])->save();
            SendEmailUpdatePasswordJob::dispatch($request->user(), $request->user()->full_name);
            return response()->json([
                'status' => true,
                'message' => 'Update Password'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => $validator->getMessageBag()->first()
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
