<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
        $token = $this->userService->createAndLogin($data);

        return response()->json([
            'token' => $token,
            'user' => auth()->user()
        ], 201);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updateAvatar(Request $request)
    {
        $url = $this->userService->updateAvatar($request->file('avatar'), $this->currentUser);

        return response()->json(['url' => $url]);
    }
}
