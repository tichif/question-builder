<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use VotableTrait;
    
    protected $fillable = ['body', 'user_id'];

    protected $appends = ['created_date','body_html'];

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
        return clean(\Parsedown::instance()->text($this->body));
    }

    public static function boot(){
        parent::boot();

        // le yon repons kreye,nonb repons la ogmante
        static::created(function($answer){
            $answer->question->increment('answers_count');
        });

        // le yon repons efase, nonb repons diminye
        static::deleted(function($answer){

            $answer->question->decrement('answers_count');
        });
    }

    // creating an accessor for getting the date in a specific format
    public function getCreatedDateAttribute(){
        return $this->created_at->diffForHumans();
    }

    // creating an accessor for getting the status for the answer
    public function getStatusAttribute(){
        return $this->isBest() ? "vote-accepted" : "";
    }

    // creating an accessor for getting the status for the answer
    public function getIsBestAttribute(){
       return $this->isBest();
    }

    public function isBest(){
        return $this->id == $this->question->best_answer_id;
    }    
}
