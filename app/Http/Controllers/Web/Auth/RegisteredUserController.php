<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Providers\RouteServiceProvider;
use App\Services\UserService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;


class RegisteredUserController extends Controller
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.register');
    }


    /**
     * Handle an incoming registration request.
     * @param RegisterRequest $request
     * @return Application|RedirectResponse|Redirector
     */
    public function store(RegisterRequest $request)
    {
        $data = $request->only(['name', 'email', 'phone_number', 'phone_code', 'password', 'invitation_code', 'is_provider']);
        $data['phone_number'] = str_replace(' ', '', $request->phone_number);
        $data['email'] = str_replace(' ', '', $request->email);
        $data['is_provider'] = isset($data['is_provider']);
        $data['phoned_Signed'] = true;
        $data['provider'] = 'web';
        $user = $this->userService->createUser($data);

        event(new Registered($user['data']));
        Auth::login($user['data']);
        if ($user['sms_status']){
            return redirect()->route('verification.phone');
        }else{
            return redirect(RouteServiceProvider::HOME);
        }
    }

}
