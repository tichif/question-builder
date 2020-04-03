<?php

namespace App\Http\Controllers;

use App\Answer;
use Illuminate\Http\Request;
use App\Question;

class AnswersController extends Controller
{
 
    public function __construct(){

        $this->middleware('auth')->except('index');
    }

    public function index(Question $question){

        return $question->answers()->with('user')->simplePaginate(3);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Question $question
     * @return \Illuminate\Http\Response
     */
    public function store(Question $question, Request $request)
    {
        $this->validate($request, [
            'body' => 'required'
        ]);

        $question->answers()->create(['body' => $request->body, 'user_id' => \Auth::id()]);

        return back()->with('success', 'Your answer has been submitted successfully !!!') ;
    }

    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Question  $question
     * @param  \App\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function edit(Question $question,Answer $answer)
    {
        $this->authorize('update', $answer);

        return view('pages.answers.edit', compact('question', 'answer'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Question  $question
     * @param  \App\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Question $question, Answer $answer)
    {
        $this->authorize('update', $answer);

        $answer->update($request->validate([
            'body' => 'required'
        ]));

        if( $request->expectsJson()){
            return response()->json([
                'message' => "Your answer has been updated successfully !!!",
                'body_html' => $answer->body_html
            ]);
        }
        return redirect()->route('questions.show', $question->slug)->with('success','Your answer has been updated successfully !!!') ;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Question  $question
     * @param  \App\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Question $question, Answer $answer)
    {
        $this->authorize('delete', $answer);

        $answer->delete();

        if(request()->expectsJson()){
            return response()->json([
                'message' => 'Your answer has been deleted successfully',
            ]);
        }
        return back()->with('success','Your answer has been deleted successfully');
    }
}
