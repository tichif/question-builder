<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $appends = ['url', 'avatar'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Create a function witch allow relationship between questions table and users table
    public function questions(){
        return $this->hasMany(Question::class);
    }
 
    // creating an accessor for creating a route from a id
    public function getUrlAttribute(){
        // return route("questions.show", $this->id);
        return '#';
    }

    // Create a function witch allow relationship between users table and answers table
    public function answers(){
        return $this->hasMany(Answer::class);
    }

    // Create an accessor for avatar
    public function getAvatarAttribute(){
        $email = $this->email;
        $size = 32;
        return  "https://www.gravatar.com/avatar/" . md5( strtolower( trim( $email ) ) ) . "?s=" . $size;
    }

    // Create a function witch allow relationship between questions table and users table using favorites table
    public function favorites(){
        return $this->belongsToMany(Question::class, 'favorites')->withTimestamps(); // 'user_id', 'question_id');
    }

    // Relationship Function between Questions table and Users table using a Pivot Table 'votables'
    public function voteQuestions(){
        return $this->morphedByMany(Question::class, 'votable'); // Eloquent will assume that votable is for the votables table;
    }

    // Relationship Function between Answers table and Users table using a Pivot Table 'votables'
    public function voteAnswers(){
        return $this->morphedByMany(Answer::class, 'votable'); // Eloquent will assume that votable is for the votables table;
    }

    // This function allows the user to vote for a question once 
    public function voteQuestion(Question $question, $vote){
        $voteQuestions = $this->voteQuestions();
        
        $this->_vote($voteQuestions, $question, $vote);
    }

    // This function allows the user to vote for a answer once 
    public function voteAnswer(Answer $answer, $vote){
        $voteAnswers = $this->voteAnswers();
        
        $this->_vote($voteAnswers, $answer, $vote);
    }

    private function _vote($relationship, $model, $vote){
        if($relationship->where('votable_id', $model->id)->exists()){
            $relationship->updateExistingPivot($model, ['vote' => $vote]);
        }else{
            $relationship->attach($model, ['vote' => $vote]);
        }

        // recount the total number of votes (sum)
        $model->load('votes');
        $downVotes = (int) $model->downVotes()->sum('vote'); // you must cast the response because it will return a string
        $upVotes = (int) $model->upVotes()->sum('vote'); // you must cast the response because it will return a string

        $model->votes_counts = $upVotes + $downVotes;
        $model->save();
    }
}
