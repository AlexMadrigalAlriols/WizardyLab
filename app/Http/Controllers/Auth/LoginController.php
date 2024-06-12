<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\SubdomainHelper;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        $portal = SubdomainHelper::getPortal(request());

        return view('auth.login', compact('portal'));
    }

    protected function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $portal = SubdomainHelper::getPortal($request);

            if ($user->portal_id != $portal->id) {
                Auth::logout();
                return redirect()->route('login')->withErrors(['portal_id' => 'No tienes acceso a este portal.']);
            }

            return redirect()->intended($this->redirectPath());
        }

        // Si las credenciales son incorrectas
        return $this->sendFailedLoginResponse($request);
    }

    public function logout(Request $request) {
        Auth::logout();
        return redirect('/login');
    }
}
