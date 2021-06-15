<?php

namespace App\Http\Controllers;

use App\Models\Bobot;
use Atymic\Twitter\Facade\Twitter;
use Illuminate\Http\Request;
use App\Models\Dataset;
use App\Models\Preprocessing;
use App\Models\Sentiment;

class CrawlingController extends Controller
{
    public function index()
    {
        $data = Dataset::all();
        return view('crawling', ['data' => $data]);
    }

    public function crawling(Request $request)
    {
        Dataset::truncate();
        Preprocessing::truncate();
        Bobot::truncate();
        Sentiment::truncate();
        $query = $request->get('query');
        $result = Twitter::searchRecent($query, ['max_results' => 100]);
        $result = json_decode($result, true);
        foreach ($result['data'] as $i => $tweet) {
            $new_data = new Dataset;
            $new_data->tweets = $tweet['text'];
            $new_data->save();
        }

        return redirect()->route('crawling')->with('status', 'Data Successfully Processing');
    }
}
