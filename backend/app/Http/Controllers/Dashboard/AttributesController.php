<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Attributes\AddAttributeRequest;
use App\Http\Requests\Dashboard\Attributes\UpdateAttributeRequest;
use App\Models\Attribute;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AttributesController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Attribute::get();
        return $this->sendResponse($data, 'Attributes fetched successfully');
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
     * @param  AddAttributeRequest $request
     * @return JsonResponse
     */
    public function store(AddAttributeRequest $request)
    {
        $data = $request->only('key', 'value', 'status');

        $data = Attribute::create($data);
        if ($data) {
            return $this->sendResponse($data, 'Attribute created success');
        } else {
            return $this->sendError('Attribute not created.. something went wrong', []);
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
        $data = Attribute::where('id', $id)->first();
        if ($data) {
            return $this->sendResponse($data,'Attribute fetched successfully');
        }
        return $this->sendError('failed', ['Attribute doesn\'t exist to show']);
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
     * @param  UpdateAttributeRequest $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function update(UpdateAttributeRequest $request, $id)
    {
        $data = Attribute::where('id' , $id)->first();
        if ($data) {
            $newData = $request->only('value', 'status');
            $data->update($newData);
            return $this->sendResponse($data,'Attribute updated successfully');
        } else {
            return $this->sendError('Attribute doesn\'t exist', []);
        }
    }

    public function status(Request $request) {
        $request->validate([
            'id' => 'required|exists:attributes,id',
            'status' => 'required'
        ]);

        $data = Attribute::where('id', $request->id)->first();
        $data->status = $request->status == 1 ? 1 : 0;
        $data->save();

        if($data) {
            return $this->sendResponse($data, $data->status ? 'Attribute is active now (ON)' : 'Attribute is inactive now (OFF)');
        } else {
            return $this->sendError('Something went wrong', []);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Attribute::find($id);
        if (!$data) {
            return $this->sendError('Attribute doesn\'t exist', []);
        }
        if ($data->exists()) {
            $data->delete();
            return $this->sendResponse([],'Attribute deleted successfully');
        } else {
            return $this->sendError('Attribute element can\'t be deleted', []);
        }
    }
}
