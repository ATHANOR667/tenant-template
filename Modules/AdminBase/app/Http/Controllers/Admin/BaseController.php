<?php

namespace Modules\AdminBase\Http\Controllers\Admin;

use Illuminate\Routing\Controller ;
use Illuminate\Support\Facades\Auth;

class BaseController extends Controller
{
    public function __construct()
    {

        $this->middleware(['permission:customs-logs,admin'])->only(['logsDashboardView']);

    }

    public function profileView(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory
    {
        return view('adminbase::admin.pages.profile',
            [
                'admin' => Auth::guard('admin')->user(),
            ]);
    }

    public function logsDashboardView(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory
    {
        return view('adminbase::admin.pages.logs',
            [
                'admin' => Auth::guard('admin')->user(),
            ]);

    }
}
