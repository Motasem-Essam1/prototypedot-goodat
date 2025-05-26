<?php

namespace App\Http\Controllers\Dashboard\Auth;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Dashboard\Auth\LoginRequestDashboard;
use App\Http\Resources\AdminLoginResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use App\Models\Admin;


class AuthController extends BaseController
{
    public function login(LoginRequestDashboard $request): JsonResponse
    {
        if(Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password])){
              $admin = AdminLoginResource::make(Admin::where('id' , Auth::guard('admin')->id())->first());
            return $this->sendResponse($admin, 'Admin login successfully.');
        }else {
            return $this->sendError('Unauthorised.', ['Unauthorised']);
        }
    }
}
