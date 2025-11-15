<?php

namespace Modules\AdminBase\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\AdminBase\Models\SuperAdmin;

class AuthController extends Controller
{


    public function loginView(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
    {
        return view('adminbase::super-admin.auth.login');
    }

    public function login(Request $request): \Illuminate\Http\RedirectResponse
    {
        $superAdmin = SuperAdmin::first();

        if ($superAdmin && Hash::check($request->input('password'), $superAdmin->password)) {

            $guards = array_keys(config('auth.guards'));

            foreach ($guards as $guard) {
                Auth::guard($guard)->logout();
            }

            Auth::guard('super-admin')->login($superAdmin);

            return redirect()->route('super-admin.manageAdminsView');
        } else {
            return back()->with(['error' => 'Mot de passe incorrect'], 401);
        }
    }

    public function logout(): RedirectResponse
    {
        Auth::guard('super-admin')->logout();
        return redirect()
            ->route('super-admin.auth.disconnected.login')
            ->with('success','Déconnexion réussie');
    }

}
