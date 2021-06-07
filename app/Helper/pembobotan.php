<?php

namespace App\Helper;

use App\Models\Abusive;
use Illuminate\Support\Facades\DB;
use App\Models\Bobot;
use App\Models\BobotTweet;

class Pembobotan
{
    public static function hitung_tf($tweets)
    {
        $tweet = $tweets->results;
        $tweet = explode(' | ', trim($tweet));

        foreach ($tweet as $kata) {
            if ($kata != '') {

                $rescount = DB::table('bobots')->select('tf')->where('term', $kata)->where('tweet_id', $tweets->id);
                $nTerm = $rescount->count();

                if ($nTerm > 0) {
                    $count = $rescount->value('tf');
                    $count++;

                    DB::table('bobots')->where('term', $kata)->where('tweet_id', $tweets->id)->update(['tf' => $count]);
                } else {
                    $term = new Bobot;

                    $term->tweet_id = $tweets->id;
                    $term->term = $kata;
                    $term->tf = 1;
                    $term->save();
                }
            }
        }
    }

    public static function hitung_tfidf()
    {
        $data = Bobot::all();
        $resdoc = DB::table('bobots')->distinct('tweet_id');
        $nDoc = $resdoc->count();

        foreach ($data as $kata) {
            $term = $kata->term;
            $tf = 1 + log($kata->tf);

            $resNTerm = DB::table('bobots')->select(DB::raw('Count(*) as N'))->where('term', $term)->get();
            $nTerm = $resNTerm[0]->N;

            $idf = log($nDoc / $nTerm);

            $tfidf = $tf * $idf;

            $update_bobot = Bobot::findOrFail($kata->id);
            $update_bobot->df = $nTerm;
            $update_bobot->tfidf = $tfidf;

            $update_bobot->save();
        }
    }

    public static function hitung_bobotdoc()
    {
        $kasar = Abusive::all();
        for ($i = 1; $i <= 100; $i++) {
            $bobot = array();
            foreach ($kasar as $query) {
                $nQuery = Bobot::where('tweet_id', $i)->where('term', $query->kata)->first();
                if (isset($nQuery)) {
                    array_push($bobot, $nQuery->tfidf);
                }
            }
            $docBobot = array_sum($bobot);
            $bobot_baru = new BobotTweet();
            $bobot_baru->tweet_id = $i;
            $bobot_baru->bobot = $docBobot;

            $bobot_baru->save();
        }
    }
}
