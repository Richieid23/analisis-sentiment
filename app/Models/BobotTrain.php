<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BobotTrain extends Model
{
    use HasFactory;

    protected $fillable = ['term', 'tweet_id', 'tf', 'df', 'tfidf'];
}
