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

    // Creating a mutator 
    public function setTitleAttribute($value){
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = str_slug($value);
    }

    // creating an accessor for creating a route from a id
    public function getUrlAttribute(){
        return route("questions.show", $this->id);
    }

    // creating an accessor 
    public function getCreatedDateAttribute(){
        return $this->created_at->diffForHumans();
    }
}
