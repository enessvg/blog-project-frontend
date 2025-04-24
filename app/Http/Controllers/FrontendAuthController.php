<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class FrontendAuthController extends Controller
{
    private $apiUrl;
    public function __construct() {
        $this->apiUrl = config('services.api_url');
    }


    public function loginf(Request $request)
    {
        $validateComment = Validator::make($request->all(),[
                    'email' => 'required|email',
                    'password' => 'required',
                ]);

                if ($validateComment->fails()) {
                    return back()
                    ->withErrors($validateComment)
                    ->withInput(); // mesela post atıyor name kısmını doldurmuş ama diğer yerleri boş bırakmış withInput sayesinde name kısmını koruyarak bidaha yazmamasını sağlıyorum. onuda {{ old('...') }} ile sağlıyorum form üzerinde
                }
        $response = Http::post($this->apiUrl.'api/auth/login', [
            'email' => $request->email,
            'password' => $request->password,
        ]);

        if ($response->successful()) {
            $name = $response->json('user')['name'];
            $email = $response->json('user')['email'];
          	$avatar = $response->json('user')['avatar_url'];
            $token = $response->json('token');
            session(['token' => $token, 'name' => $name, 'email' => $email, 'avatar' => $avatar]);

            return redirect('/auth/dashboard')->withErrors(['message' => 'The login is successful. Welcome...']);
        } else {
            return redirect('/auth/login')->withErrors(['error' => 'The email or password is incorrect.']);
        }
    }

    public function register(Request $request){
        $validateComment = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed',
        ]);

        if ($validateComment->fails()) {
            return back()
            ->withErrors($validateComment)
            ->withInput(); // mesela post atıyor name kısmını doldurmuş ama diğer yerleri boş bırakmış withInput sayesinde name kısmını koruyarak bidaha yazmamasını sağlıyorum. onuda {{ old('...') }} ile sağlıyorum form üzerinde
        }
        $response = Http::post($this->apiUrl.'api/auth/register', [
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'password_confirmation' => $request->password_confirmation,
            'ip_address' => $request->ip(),
        ]);

        if ($response->successful()) {
            return redirect('/auth/login')->withErrors(['message' => 'Your registration has been completed. You can now log in.']);
        } else {
            $apiErrors = $response->json('errors');
            if ($apiErrors) {
                return redirect('/auth/register')->withErrors($apiErrors)->withInput();
            }
            // Diğer genel hata durumları için
            return redirect('/auth/register')->withErrors(['error' => 'The email or password is incorrect.'])->withInput();
        }
    }

    public function logout(Request $request){
        $token = session()->get('token');
        if(!$token){
            return redirect('/auth/login')->withErrors(['error' => 'Please log in first.']);
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->post(env('API_URL').'api/auth/logout');

        if($response->successful()){
            session()->flush();
            return redirect('/auth/login')->withErrors(['message' => 'Logout successful.']);
        }else{
            return redirect('/auth/login')->withErrors(['error' => 'An error occurred during logout.']);
        }
    }

    public function user(Request $request){
        $token = session()->get('token');
        if(!$token){
            return redirect('/auth/login')->withErrors(['error' => 'Please log in first.']);
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->get(env('API_URL').'api/auth/user');

        if($response->successful()){
            $userData = $response->json();
            return view('user', compact('userData'));
        }else{
            return redirect('/auth/dashboard')->withErrors(['error' => 'An error occurred during logout.']);
        }
    }
}
