@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                  <div class="card-title">
                    <div class="d-flex align-items-center">
                      <h1>{{ $question->title }}</h1>
                      <div class="ml-auto">
                        <a href="{{ route('questions.index') }}" class="btn btn-outline-secondary">Go Back </a>
                      </div>
                    </div> 
                  </div> 
                  <hr>
                  <div class="media">
                    @include('pages.shared._vote',[
                      'model' => $question
                    ])

                    <div class="media-body">
                      {!! $question->body_html !!}
                      <div class="row">
                        <div class="col-4"></div>
                        <div class="col-4"></div>
                        <div class="col-4">
                          @include('pages.shared._author',[
                          'model' => $question,
                          'label' => 'Asked'
                          ])
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
            </div>
        </div>
    </div>
    @include('pages.answers._index',[
      'answersCount' => $question->answers_count,
      'answers' => $question->answers
       ])
    @include('pages.answers._create')
</div>
@endsection