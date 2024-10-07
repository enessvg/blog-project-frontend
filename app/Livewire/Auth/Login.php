<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Http;
use Livewire\Component;

class Login extends Component
{
    private $apiUrl;
    public function __construct() {
        $this->apiUrl = config('services.api_url');
    }

    public $email;
    public $password;

    protected $rules = [
        'email' => 'required|email|max:255',
        'password' => 'required|min:3',
    ];

    public function login()
    {
        $this->validate();

        $response = Http::post($this->apiUrl.'api/auth/login', [
            'email' => $this->email,
            'password' => $this->password,
        ]);

        if ($response->successful()) {
            $token = $response->json('token');
            session(['token' => $token]);

            session()->flash('message', 'Giriş başarılı.');
        } else {
            session()->flash('error', 'Giriş yapılamadı tekrar deneyin.');
        }
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
