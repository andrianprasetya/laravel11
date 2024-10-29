<?php

namespace App\Http\Controllers;

use App\Models\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LogController extends Controller
{
    public function index(Request $request): \Inertia\Response|\Inertia\ResponseFactory
    {
        $sessions = Session::query()->select("*")->paginate();

        return inertia('User/Log', [
            'sessions' => $sessions,
            'breadcrumbs' => [
                ['label' => 'Dashboard', 'url' => '/dashboard'],
                ['label' => 'User'],
                ['label' => 'Log'],
            ]
        ]);
    }
}
