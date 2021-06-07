<?php

namespace App\Http\Controllers;

use App\Helper\Pembobotan;
use App\Models\BobotTweet;

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
        Pembobotan::hitung_bobotdoc();

        return redirect()->route('bobottweet')->with('status', 'Data Successfully Processing');
    }
}
