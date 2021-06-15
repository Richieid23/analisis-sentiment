<?php

namespace App\Helper;

use Illuminate\Support\Facades\DB;
use App\Models\BobotTrain;

class PembobotanTraining
{
    public static function hitung_tf($tweets)
    {
        $tweet = $tweets->results;
        $tweet = explode(' | ', trim($tweet));

        foreach ($tweet as $kata) {
            if ($kata != '') {

                $rescount = DB::table('bobot_trains')->select('tf')->where('term', $kata)->where('tweet_id', $tweets->id);
                $nTerm = $rescount->count();

                if ($nTerm > 0) {
                    $count = $rescount->value('tf');
                    $count++;

                    DB::table('bobot_trains')->where('term', $kata)->where('tweet_id', $tweets->id)->update(['tf' => $count]);
                } else {
                    $term = new BobotTrain();

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
        $data = BobotTrain::all();
        $resdoc = DB::table('bobot_trains')->distinct('tweet_id');
        $nDoc = $resdoc->count();

        foreach ($data as $kata) {
            $term = $kata->term;
            $tf = 1 + log($kata->tf);

            $resNTerm = DB::table('bobot_trains')->select(DB::raw('Count(*) as N'))->where('term', $term)->get();
            $nTerm = $resNTerm[0]->N;

            $idf = log($nDoc / $nTerm);

            $tfidf = $tf * $idf;

            $update_bobot = BobotTrain::findOrFail($kata->id);
            $update_bobot->df = $nTerm;
            $update_bobot->tfidf = $tfidf;

            $update_bobot->save();
        }
    }
}
