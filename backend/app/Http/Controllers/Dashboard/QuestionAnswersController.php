<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Resources\QuestionAnswersResource;
use Illuminate\Http\Request;
use App\Models\QuestionAnswer;

class QuestionAnswersController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = QuestionAnswersResource::collection(QuestionAnswer::get());
        return $this->sendResponse($data, 'Question Answers fetched successfully');
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
        $data = $request->only('title', 'body', 'status');
        $data = QuestionAnswer::create($data);
        if ($data) {
            $data = QuestionAnswersResource::make($data);
            return $this->sendResponse($data, 'Question created success');
        } else {
            return $this->sendError('Question not created.. something went wrong', []);
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
        $data = QuestionAnswer::where('id', $id)->first();
        if ($data) {
            $data = QuestionAnswersResource::make($data);
            return $this->sendResponse($data,'Question fetched successfully');
        }
        return $this->sendError('failed', ['Question doesn\'t exist to show']);
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
        $data = QuestionAnswer::where('id' , $id)->first();
        if ($data) {
            $newData = $request->only('title', 'body', 'status');
            $data->update($newData);
            $data = QuestionAnswersResource::make($data);
            return $this->sendResponse($data,'Question updated successfully');
        }else{
            return $this->sendError('Question doesn\'t exist', []);
        }
    }

    public function status(Request $request) {
        $request->validate([
            'id' => 'required|exists:question_answers,id',
            'status' => 'required'
        ]);

        $data = QuestionAnswer::where('id', $request->id)->first();
        $data->status = $request->status == 1 ? 1 : 0;
        $data->save();

        if($data) {
            $qaData = QuestionAnswersResource::make($data);
            return $this->sendResponse( $qaData, $data->status ? 'Question is active now (ON)' : 'Question is inactive now (OFF)');
        } else {
            return $this->sendError('something went wrong', []);
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
        $data = QuestionAnswer::find($id);
        if (!$data) {
            return $this->sendError('this question doesn\'t exist', []);
        }
        if ($data->exists()) {
            $data->delete();
            return $this->sendResponse([],'question deleted successfully');
        } else {
            return $this->sendError('question element can\'t be deleted', []);
        }
    }
}
