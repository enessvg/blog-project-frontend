<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FrontendAuthController extends Controller
{
    private $apiUrl;
    public function __construct() {
        $this->apiUrl = config('services.api_url');
    }


    public function loginf(Request $request)
    {

        $response = Http::post($this->apiUrl.'api/auth/login', [
            'email' => $request->email,
            'password' => $request->password,
        ]);

        if ($response->successful()) {
            $token = $response->json('token');
            session(['token' => $token]);

            return redirect('/')->withErrors(['message' => 'Giriş başarılı. Hoşgeldiniz...']);
        } else {
            return redirect('/auth/login')->withErrors(['error' => 'E-Posta veya şifre hatalı']);
        }
    }

    public function logout(){
        session()->forget('token');
    return redirect('/')->withErrors(['message' => 'Çıkış başarılı']);
    }
}
