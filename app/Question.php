<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{

    protected $fillable = ['title','body'];

    // Create a function witch allow relationship between questions table and users table
    public function user(){
        return $this->belongsTo(User::class); // or 'App\User'
    }

    // Creating a mutator for the title and the slug
    public function setTitleAttribute($value){
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = str_slug($value);
    }

    // creating an accessor for creating a route from a id
    public function getUrlAttribute(){
        return route("questions.show", $this->slug);
    }

    // creating an accessor for getting the date in a specific format
    public function getCreatedDateAttribute(){
        return $this->created_at->diffForHumans();
    }

    // creating an accessor for getting the status
    public function getStatusAttribute(){
        if($this->answers_count > 0){
            if($this->best_answer_id){
                return "answered-accepted";
            }
            return "answered";
        }else{
            return "unanswered";
        }
    }

    // creating an accessor for getting the body in a specific format
    public function getBodyHtmlAttribute(){
        return \Parsedown::instance()->text($this->body);
    }

    // Create a function witch allow relationship between questions table and answers table
    public function answers(){
        return $this->hasMany(Answer::class);
    }

    // Accept the answer as best answer for the question
    public function acceptBestAnswer(Answer $answer){
        $this->best_answer_id = $answer->id;
        $this->save();
    } 
}
