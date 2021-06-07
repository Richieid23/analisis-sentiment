<?php

namespace App\Http\Controllers;

use App\Models\Alay;
use App\Models\Dataset;
use App\Models\Preprocessing;
use App\Models\Stopword;
use Sastrawi\Stemmer\StemmerFactory;

class PreprocessingController extends Controller
{
    public function index()
    {
        $data = Preprocessing::all();
        return view('preprocessing', ['data' => $data]);
    }

    public function preprocessing()
    {
        Preprocessing::truncate();
        $stemmerFactory = new StemmerFactory();
        $stemmer = $stemmerFactory->createStemmer();

        foreach (Dataset::all() as $tweets) {
            $tweet = $tweets->tweets;
            $tweet = casefolding($tweet);
            $tweet = cleansing($tweet);

            foreach (Alay::all() as $kata) {
                $tweet = preg_replace('/\b(' . $kata->kata_alay . ')\b/m', $kata->kata_dasar, $tweet);
            }

            $stoplist = array();
            foreach (Stopword::all() as $word) {
                $stoplist[] = $word->kata;
            }
            $tweet = preg_replace('/\b(' . implode('|', $stoplist) . ')\b/', '', $tweet);

            $tweet = convert_negation($tweet);

            $tweet = $stemmer->stem($tweet);

            $tweet = cleansing($tweet);

            foreach (Alay::all() as $kata) {
                $tweet = preg_replace('/\b(' . $kata->kata_alay . ')\b/m', $kata->kata_dasar, $tweet);
            }

            $stoplist = array();
            foreach (Stopword::all() as $word) {
                $stoplist[] = $word->kata;
            }
            $tweet = preg_replace('/\b(' . implode('|', $stoplist) . ')\b/', '', $tweet);

            $tweet = convert_negation($tweet);

            $tweet = $stemmer->stem($tweet);

            $tweet = stripcslashes($tweet);
            $tweet = trim($tweet);
            $tweet = explode(' ', $tweet);

            $preprocessing = new Preprocessing;
            $preprocessing->tweets = $tweets->tweets;
            $preprocessing->results = implode(' | ', $tweet);
            $preprocessing->save();
        }
        return redirect()->route('preprocessing')->with('status', 'Data Successfully Processing');
    }
}
