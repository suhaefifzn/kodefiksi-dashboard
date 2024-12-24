<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

// ? Services
use App\Services\AuthService;

class AuthController extends Controller
{
    private $authService;

    public function __construct() {
        $this->authService = new AuthService();
    }

    public function index() {
        return view('login', [
            'title' => 'Sign In'
        ]);
    }

    public function login(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
            'g-recaptcha-response' => 'required|captcha'
        ]);
        $response = $this->authService->generateAccessToken($request->email, $request->password);
        $decodedResponse = $this->decodeJsonResponse($response);

        if ($decodedResponse['status'] === 'success') {
            $token = $decodedResponse['data']['token']['access_token'];
            Session::put('access_token', $token);

            return response()->json([
                'url' => route('home')
            ]);
        }

        return $response;
    }

    public function logout() {
        $response = $this->authService->deleteAccessToken();
        $decodedResponse = $this->decodeJsonResponse($response);

        if ($decodedResponse['status'] === 'success') {
            Session::flush();
            return redirect()->route('auth.index');
        }

        return redirect()->back();
    }
}
