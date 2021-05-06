<?php

namespace App\Http\Controllers;

use App\Models\BobotTweet;
use Illuminate\Http\Request;

class BobotTweetController extends Controller
{
    public function index()
    {
        $data = BobotTweet::all();
        return view('bobot', ['data' => $data]);
    }

    public function pembobotan()
    {
        BobotTweet::truncate();
        hitung_bobotdoc();

        return redirect()->route('bobottweet')->with('status', 'Data Successfully Processing');
    }
}
