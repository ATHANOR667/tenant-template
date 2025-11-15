<?php

namespace Modules\AdminBase\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Modules\AdminBase\Models\Admin;

class AuthController extends Controller
{


    public function signupView(): Factory|View|Application
    {
        return view('adminbase::admin.auth.signup');
    }



    public function loginView(): Factory|View|Application
    {
        return view('adminbase::admin.auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        try {
            $admin = Admin::where('email',$request->input('email'));

            if (!$admin->exists()) {
                return back()->with('error', 'Adresse inconue.');
            }

            $admin = $admin->first();

            if ($admin->isBanned($admin)) {
                return back('403')->with('error', 'Vous avez été bani .');
            }
        }catch (\Exception $exception){
            Log::error($exception->getMessage());
            return back()->with('error', 'Une erreur est survenue lors de l\'authentification');
        }

        if (Hash::check($request->input('password'), $admin->password)) {

            $guards = array_keys(config('auth.guards'));

            foreach ($guards as $guard) {
                Auth::guard($guard)->logout();
            }

            Auth::guard('admin')->login($admin);
            $request->session()->regenerate();
            return redirect()->route('admin.profileView');
        } else {
            return back()->with(['error' => 'Mot de passe incorrect'], 401);
        }
    }


    public function logout(): RedirectResponse
    {
        Auth::guard('admin')->logout();
        return redirect()
            ->route('admin.auth.disconnected.login')
            ->with('success','Déconnexion réussie');
    }

}
