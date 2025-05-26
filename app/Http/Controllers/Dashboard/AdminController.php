<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Dashboard\Admins\AddAdminRequest;
use App\Http\Requests\Dashboard\Admins\UpdateAdminRequest;
use App\Http\Resources\AdminResource;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Laravel\Sanctum\PersonalAccessToken;

class AdminController extends BaseController
{
    private $adminModel;
    public function __construct(Admin $adminModel){
        $this->adminModel = $adminModel;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $admins = AdminResource::collection(Admin::all());
        return $this->sendResponse($admins,'Admin data fetched successfully');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddAdminRequest $request){
        $data = $request->only('name', 'email', 'password');
        $data['password'] = bcrypt($request->password);
        $admin = $this->adminModel->create($data);
        if($admin){
            $admin = AdminResource::make($admin);
            return $this->sendResponse( $admin, 'Admin create success');
        }else{
            return $this->sendError('something went wrong', []);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $admin = Admin::where('id', $id)->first();
        if($admin){
            $admin = AdminResource::make($admin);
            return $this->sendResponse($admin,'Admin data fetched successfully to show');
        }
        return $this->sendError('faild',['Admin element dosn\'t exist to show']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAdminRequest $request){
        $newAdmin = $request->only('id', 'name', 'email');
        $admin = Admin::where('id' , $newAdmin['id'])->first();
        $admin->update($newAdmin);
        $admin = AdminResource::make($admin);
        return $this->sendResponse($admin,'Admin element updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $admin = Admin::find($id);
        if($admin){
            $admin->delete();
            return $this->sendResponse([],'Admin element deleted successfully');
        }else{
            return $this->sendError('Admin element dosn\'t exist to delete', []);
        }
    }

    public function updatePassword(Request $request){
        $request->validate([
            'id'            => 'required|exists:admins,id,deleted_at,NULL',
            'password'      => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        $data = $request->only('id', 'password');
        $admin = Admin::where('id' , $data['id'])->first();
        $admin['password'] = Hash::make($data['password']);
        if ($admin->save()){
            $admin =  AdminResource::make($admin);
            return $this->sendResponse($admin, 'Admin password updated successfully');
        }else{
            return $this->sendError('failed.', 'something went wrong');
        }
    }

    public function getAdminByToken(Request $request)
    {
        $token = PersonalAccessToken::findToken($request->token);
        if($token){
            $admin = $token->tokenable;
            $admin = AdminResource::make($admin);
            return $this->sendResponse($admin,'getAdminByToken data fetched successfully');
        }
        return $this->sendError('faild',['element dosn\'t exist']);
    }

    public function status(Request $request) {
        $request->validate([
            'id' => 'required|exists:admins,id,deleted_at,NULL',
            'status' => 'required'
        ]);

        $data = Admin::where('id', $request->id)->first();
        $data->status = $request->status == 1 ? 1 : 0;
        $data->save();

        if($data) {
            $adminData = AdminResource::make($data);
            return $this->sendResponse($adminData, $data->status ? 'Admin is active now (ON)' : 'Admin is inactive now (OFF)');
        } else {
            return $this->sendError('Admin active fail something went wrong', []);
        }
    }
}
