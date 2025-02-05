<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;

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
     * @return \Illuminate\Http\JsonResponse
     */
    public function read()
    {
        return response()->json($this->currentUser);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request)
    {
        return response()->json($this->userService->update($request->all()));
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

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function favorite(Request $request)
    {
        return response()->json($this->userService->favorite($request->input('barber_id')));
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function favorites()
    {
        return response()->json($this->userService->favorites());
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAppointments()
    {
        return response()->json($this->userService->getAppointments());
    }
}
