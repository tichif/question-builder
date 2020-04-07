<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AskQuestionRequest;
use App\Question;

class QuestionsController extends Controller
{
    public function __construct() {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $questions = Question::with('user')
                            ->latest()
                            ->paginate(5);

        return view('pages.questions.index', compact('questions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $question = new Question;
        return view('pages.questions.create', compact('question'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\AskQuestionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AskQuestionRequest $request)
    {
        $request->user()->questions()->create($request->only('title','body'));

        return redirect()->action('QuestionsController@index')->with('success','Your question has been submitted');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function show(Question $question)
    {
        $question->increment('views') ;
        /*
        *oubyen m ka ekri
        *$question->views = $question->views + 1;
        *$question->save();
        */
        return view('pages.questions.show', compact('question'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function edit(Question $question)
    {
        $this->authorize("update", $question);
        // si se se te id a m te gen kom parametre 
        // m tap use $question = Question::find(id);

        return view('pages.questions.edit', compact('question'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\AskQuestionRequest  $request
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function update(AskQuestionRequest $request, Question $question)
    {
        $this->authorize("update", $question);
        $question->update($request->only('title','body'));
        if(request()->expectsJson()){
            return response()->json([
                'message' => 'Your question has been updated',
                'body_html' => $question->body_html
            ]);
        }
        return redirect()->action('QuestionsController@index')->with('success','Your question has been updated');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function destroy(Question $question)
    {
        $this->authorize("delete", $question);
        $question->delete();
        
        if(request()->expectsJson()){
            return response()->json([
                'message' => 'Your question has been deleted'
            ]);
        }

        return redirect()->action('QuestionsController@index')->with('success','Your question has been deleted');
    }
}
