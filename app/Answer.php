<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    // Create a function witch allow relationship between answers table and users table
    public function user(){
        return $this->belongsTo(User::class);
    }

    // Create a function witch allow relationship between answers table and questions table
    public function question(){
        return $this->belongsTo(Question::class);
    }

    // creating an accessor for getting the body in a specific format
    public function getBodyHtmlAttribute(){
        return \Parsedown::instance()->text($this->body);
    }
}
