<?php

use App\Models\Abusive;
use App\Models\Kamus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Bobot;
use App\Models\BobotTweet;
use Illuminate\Support\Facades\Http;

function scraping()
{
    $response = Http::withHeaders([
        'x-rapidapi-key' => '96f7afa770msh321de7b73a2fe29p1a72a3jsn725ee221fb1e',
        'x-rapidapi-host' => 'twitter32.p.rapidapi.com'
    ])->get('https://twitter32.p.rapidapi.com/getSearch', [
        'username' => 'cnn',
        'hashtag' => 'trump',
        'start_date' => '2018-01-01',
        'end_date' => '2020-10-10',
        'lang' => 'en'
    ]);

    echo $response->body();
}

function casefolding($tweet)
{
    return Str::lower($tweet);
}

function cleansing($tweet)
{
    $tweet = preg_replace('/(\xF0\x9F[\x00-\xFF][\x00-\xFF])/', ' ', $tweet);
    $tweet = preg_replace('/[0-9,\(\)\-\=\.\,\;\!\?]+/', ' ', $tweet);
    $tweet = preg_replace('/@[-A-Z0-9+&@#""\/%?=~_|$!:,.;]*[A-Z0-9+&@#\/%=~_\$]/i', '', $tweet);
    $tweet = preg_replace('/#[-A-Z0-9+&@#\/%?=~_|$!:,.;]*[A-Z0-9+&@#\/%=~_|$]/i', '', $tweet);
    $tweet = preg_replace('/\b(https?|ftp|file|http):\/\/[-A-Z0-9+&@#\/%?=~_|$!:,.;]*[A-Z0-9+&@#\/%=~_|$]/i', '', $tweet);
    $tweet = preg_replace('/rt | a`;/i', '', $tweet);

    $tweet = explode(' ', trim($tweet));
    foreach ($tweet as $kata) {
        $lenkata = strlen($kata);
        if ($lenkata <= 2) {
            $key = array_search($kata, $tweet);
            unset($tweet[$key]);
        }
    }

    return implode(' ', $tweet);
}

function convert_negation($tweet)
{
    $List = array(
        'gak' => 'gak', 'ga', 'ngga' => 'ngga', 'tidak' => 'tidak', 'bkn' => 'bkn',
        'tida' => 'tida', 'tak' => 'tak', 'jangan' => 'jangan', 'enggak' => 'enggak', 'gak' => 'gak',
        'ga' => 'ga', 'ngga' => 'ngga', 'tidak' => 'tidak', 'bkn' => 'bkn', 'tida' => 'tida',
        'tak' => 'tak', 'jangan' => 'jangan', 'enggak' => 'enggak'
    );
    $patterns = array();
    $replacement = array();

    foreach ($List as $from => $to) {
        $from = '/\b' . $from . '\b/';
        $patterns[] = $from;
        $replacement[] = $to;
    }
    return preg_replace($patterns, $replacement, $tweet);
}
