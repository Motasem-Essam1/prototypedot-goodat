<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\ContactUs;
use Illuminate\Http\Request;

class ContactUsController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = ContactUs::get();
        return $this->sendResponse($data, 'Data fetched successfully');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'first_name'    => 'required|string|min:2',
            'last_name'     => 'required|string|min:2',
            'email'         => 'required|email',
            'phone_number'  => 'required|min:6',
            'message'       => 'required',
        ]);

        $data = $request->only('first_name', 'last_name', 'email', 'phone_number', 'message');
        $data = ContactUs::create($data);
        if ($data) {
            return $this->sendResponse($data, 'Thank you for your message, our team will quickly contact you.');
        } else {
            return $this->sendError('Something went wrong', []);
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
        $data = ContactUs::where('id', $id)->first();
        if ($data) {
            return $this->sendResponse($data, 'Data fetched successfully');
        }
        return $this->sendError('failed', ['Data doesn\'t exist to show']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = ContactUs::find($id);
        if (!$data) {
            return $this->sendError('Data doesn\'t exist', []);
        }
        if ($data->exists()) {
            $data->delete();
            return $this->sendResponse([], 'Data deleted successfully');
        } else {
            return $this->sendError('Data element can\'t be deleted', []);
        }
    }
}
