<?php

namespace App\Http\Controllers;

use App\Services\BarberService;
use Illuminate\Http\Request;

class BarberController extends Controller
{
    /**
     * @var \Illuminate\Contracts\Auth\Authenticatable|null
     */
    private $currentUser;

    /**
     * @var BarberService
     */
    private $barberService;

    /**
     * BarberController constructor.
     * @param BarberService $barberService
     */
    public function __construct(BarberService $barberService)
    {
        $this->middleware('auth:api');

        $this->currentUser = Auth()->user();
        $this->barberService = $barberService;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(Request $request)
    {
        return response()->json($this->barberService->list($request->all()));
    }
}
