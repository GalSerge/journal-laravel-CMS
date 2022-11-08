<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;

class AuthController extends Controller
{
    public function index()
    {
        $user = session('user', null);
        if ($user)
            return redirect('/admin');

        return view('admin.login');
    }

    public function login(Request $request, UserService $userService)
    {
        try {
            $user = $userService->getUser($request->username, $request->password);
            $request->session()->put('user', $user);
        } catch (\Exception $exception) {
            $count = $request->session()->get('attempts', 0) + 1;
            $request->session()->put('attempts', $count);

            return redirect()->route('loginForm')->with('msg', $exception->getMessage());
        }

        return $this->index();
    }

    public function logout()
    {
        $user = session('user', null);
        if ($user)
            session()->flush();

        return redirect()->route('loginForm');
    }
}
