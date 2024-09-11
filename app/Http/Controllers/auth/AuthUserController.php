<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Category;
use App\Models\FavoriteProduct;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password as FacadesPassword;
use Illuminate\Validation\Rules\Password;
use Symfony\Component\HttpFoundation\Response;

class AuthUserController extends Controller
{
    public function showLogin()
    {
        $categories = Category::with('products')->where('active', '=', true)->get();
        return view('login', ['categories' => $categories]);
    }

    public function login(Request $request)
    {
        $validator = Validator($request->all(), [
            'email' => 'required|email|exists:users',
            'password' => 'required|string',
            'remember_me' => 'required|boolean'
        ]);

        if (!$validator->fails()) {
            if (Auth::guard('user')->attempt($request->only(['email', 'password']), $request->input('remember_me'))) {
                return response()->json([
                    'status' => true,
                    'message' => 'Login Successfully'
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Login Failed!'
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
        auth('user')->logout();
        $request->session()->invalidate();
        return redirect(route('index'));
    }

    public function editProfile()
    {
        $countCarts = Cart::where('user_id', '=', auth('user')->user()->id ?? 0)->count();
        $countProductsFavorite = FavoriteProduct::where('user_id', '=', auth('user')->user()->id)->count();
        $categories = Category::with('products')->where('active', '=', true)->get();
        return view('editProfile', ['countCarts' => $countCarts, 'categories' => $categories, 'countProductsFavorite' => $countProductsFavorite]);
    }

    public function updateProfile(Request $request)
    {
        $validator = Validator($request->all(), [
            'first_name' => 'required|string|min:3|max:45',
            'last_name' => 'required|string|min:3|max:45',
            'email' => 'required|email|unique:users,email,' . $request->user('user')->id,
            'phone_number' => 'required|string|min:12|max:15|unique:users,phone_number,' . $request->user('user')->id,
        ]);

        if (!$validator->fails()) {
            if ($request->input('email') != $request->user('user')->email) {
                $request->user('user')->email_verified_at = null;
            }
            $request->user('user')
                ->forceFill($request->only(['first_name', 'last_name', 'email', 'phone_number']))
                ->save();
            return response()->json([
                'status' => true,
                'message' => 'Updated Profile'
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'status' => false,
                'message' => $validator->getMessageBag()->first()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function editPassword()
    {
        $countCarts = Cart::where('user_id', '=', auth('user')->user()->id ?? 0)->count();
        $countProductsFavorite = FavoriteProduct::where('user_id', '=', auth('user')->user()->id)->count();
        $categories = Category::with('products')->where('active', '=', true)->get();
        return view('editPassword', ['countCarts' => $countCarts, 'categories' => $categories, 'countProductsFavorite' => $countProductsFavorite]);
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator($request->all(), [
            'oldPassword' => 'required|string|current_password:user',
            'newPassword' => [
                'required',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(), 'string', 'confirmed'
            ]
        ]);

        if (!$validator->fails()) {
            $request->user('user')->forceFill([
                'password' => Hash::make($request->input('newPassword'))
            ])->save();
            return response()->json([
                'status' => true,
                'message' => 'Updated Password'
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'status' => false,
                'message' => $validator->getMessageBag()->first()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function showEmailVerification(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();
        return view('emailVerification');
    }

    public function verify(EmailVerificationRequest $emailVerificationRequest)
    {
        $emailVerificationRequest->fulfill();
        return redirect()->route('index');
    }

    public function forgotPassword()
    {
        $categories = Category::with('products')->where('active', '=', true)->get();
        return view('forgot', ['categories' => $categories]);
    }

    public function requestResetPassword(Request $request)
    {
        $validator = validator($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if (!$validator->fails()) {
            $status = FacadesPassword::broker('users')->sendResetLink($request->all());
            return $status === FacadesPassword::RESET_LINK_SENT ?
                response()->json([
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
        $categories = Category::with('products')->where('active', '=', true)->get();

        return view('recover-password', ['token' => $token, 'email' => $request->input('email'), 'categories' => $categories]);
    }

    public function changePassword(Request $request)
    {
        $validator = validator($request->all(), [
            'token' => 'required',
            'email' => 'required|email|exists:password_reset_tokens',
            'password' => ['required', 'string', Password::min(8)->letters()->numbers()->mixedCase()->symbols()->uncompromised(), 'confirmed'],
        ]);

        if (!$validator->fails()) {
            $status = FacadesPassword::broker('users')->reset($request->all(), function ($user, $password) {
                $user->forceFill(['password' => Hash::make($password)])->save();
                event(new PasswordReset($user));
            });
            return $status == FacadesPassword::PASSWORD_RESET ? response()->json([
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
}