<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * @var \Illuminate\Contracts\Auth\Authenticatable|null
     */
    private $currentUser;

    /**
     * @var UserService
     */
    private $userService;

    /**
     * UserController constructor.
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->middleware('auth:api', ['except' => ['create']]);

        $this->currentUser = Auth()->user();
        $this->userService = $userService;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create(Request $request)
    {
        $data = $request->only(['email', 'password', 'password_confirmation', 'name']);

        Validator::make($data, [
            'email' => ['string', 'email', 'required', 'unique:users'],
            'password' => ['string', 'required', 'min:6', 'confirmed'],
            'name' => ['string', 'required'],
        ])->validate();

        $token = $this->userService->createAndLogin($data);

        return response()->json([
            'token' => $token,
            'user' => auth()->user()
        ], 201);
    }
}
