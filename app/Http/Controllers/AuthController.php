<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AuthController extends Controller
{
    /**
     * AuthController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'unauthorized']]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $data = $request->only(['email', 'password']);

        Validator::make($data, [
            'email' => ['string', 'email', 'required'],
            'password' => ['string', 'min:6']
        ])->validate();

        $token = Auth()->attempt($data);

        if (!$token) {
            throw new AccessDeniedHttpException(null, null, 0, ['errors' => 'Bad credentials.']);
        }

        return response()->json(['token' => $token]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return response()->json(['token' => auth()->refresh()]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['success' => true]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function unauthorized()
    {
        return response()->json(['errors' => 'Unauthorized'], 401);
    }
}
