<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use VotableTrait;

    protected $fillable = ['title','body'];

    protected $appends = ['created_date', 'is_favorited','favorites_count'];

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
       return clean($this->bodyHtml());
    }

    // Create a function witch allow relationship between questions table and answers table
    public function answers(){
        return $this->hasMany(Answer::class)->orderBy('votes_counts','DESC');
    }

    // Accept the answer as best answer for the question
    public function acceptBestAnswer(Answer $answer){
        $this->best_answer_id = $answer->id;
        $this->save();
    } 

    // Create a function witch allow relationship between questions table and users table using favorites table
    public function favorites(){
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps(); // 'question_id', 'user_id');
    }

    
    // This function allows you know is this question is favorited by an specific user
    public function isFavorited()
    {
        return $this->favorites()->where('user_id', auth()->id())->count() > 0;
    }

    // creating an accessor for getting the favorited question
    public function getIsFavoritedAttribute(){
        return $this->isFavorited();
    }

    // creating an accessor for getting the count for a favorited question
    public function getFavoritesCountAttribute(){
        return $this->favorites->count();
    }

    // creating an accessor for getting the body in a specific format
    public function getExcerptAttribute(){
        return $this->excerpt(250);
    }

    public function excerpt($length){
        return str_limit(strip_tags($this->bodyHtml()), $length);
    }



    private function bodyHtml(){
        return \Parsedown::instance()->text($this->body);
    }

}
