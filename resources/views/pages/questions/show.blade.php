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
                    <vote name="question" :model="{{ $question }}"></vote>

                    <div class="media-body">
                      {!! $question->body_html !!}
                      <div class="row">
                        <div class="col-4"></div>
                        <div class="col-4"></div>
                        <div class="col-4">

                          <user-info :model="{{ $question }}" label="Asked"></user-info>

                        </div>
                      </div>
                    </div>
                  </div>
                </div>
            </div>
        </div>
    </div>
    <answers :answers="{{ $question->answers }}" :count="{{ $question->answers_count }}" ></answers>
    
    @include('pages.answers._create')
</div>
@endsection