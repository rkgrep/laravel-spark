<?php

namespace App\Http\Controllers\Settings;

use App\Spark;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Repositories\UserRepository;

class DashboardController extends Controller
{
    /**
     * The user repository instance.
     *
     * @var \App\Repositories\UserRepository
     */
    protected $users;

    /**
     * Create a new controller instance.
     *
     * @param  \App\Repositories\UserRepository  $users
     * @return void
     */
    public function __construct(UserRepository $users)
    {
        $this->users = $users;

        $this->middleware('auth');
    }

    /**
     * Show the settings dashboard.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $data = [
            'activeTab' => $request->get('tab', Spark::firstSettingsTabKey()),
            'invoices' => [],
            'user' => $this->users->getCurrentUser(),
        ];

        if (Auth::user()->stripe_id) {
            $data['invoices'] = Cache::remember('spark:invoices:'.Auth::id(), 30, function () {
                return Auth::user()->invoices();
            });
        }

        return view('settings.dashboard', $data);
    }
}
