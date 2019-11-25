<?php

namespace App\Http\Controllers;

use App\Poll;
use App\Http\Resources\Poll as PollResource;

use Illuminate\Http\Request;
use Validator;

class PollsController extends Controller
{
    //
    public function index() {
        return response()->json(Poll::paginate(1), 200);
    }

    public function show($id) {

        /* Overriding Laravel's 404 response to get a custom response */

       /*  $poll = Poll::find($id);

        if(is_null($poll))
            return response()->json(null, 404); */

        $poll = Poll::with('questions')->findOrFail($id);
        $response['poll'] = $poll;
        $response['questions'] = $poll->questions;

        $response = new PollResource($response); // get the poll, transform the poll using the resource class and store it in the response variable // with('questions') return nested data

        return response()->json($response, 200); // find changed to findOrFail to get the standard 404 error page from Laravel if the resource does not exist
    }

    public function store(Request $request) {
        
        // Adding validations

        $rules = [
            'title' => 'required|max:255'
        ];

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails())
            return response()->json($validator->errors(), 400);
        
        $poll = Poll::create($request->all());

        return response()->json($poll, 201);
    }

    public function update(Request $request, Poll $poll) {
        $poll->update($request->all());

        return response()->json($poll, 200); // 200 since we are just editing and not creating a new resource
    }

    public function delete(Request $request, Poll $poll) {
        $poll->delete();

        return response()->json(null, 204);
    }

    public function errors() {
        return response()->json(['msg' => 'Payment is required!'], 501);
    }

    public function questions(Request $request, Poll $poll) {
        $questions = $poll->questions;

        return response()->json($questions, 200); // if you have no relationships defined you will get an empty response
    }
}
