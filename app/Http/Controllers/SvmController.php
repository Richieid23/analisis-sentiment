<?php

namespace App\Http\Controllers;

use App\Models\Bobot;
use App\Models\Dataset;
use App\Models\Sentiment;
use App\Models\Training;
use App\Helper\Svm;
use App\Models\BobotTrain;
use PhpParser\Node\Expr\New_;

class SvmController extends Controller
{
    public function index()
    {
        $dataset = Sentiment::all();
        return view('testing', ['data' => $dataset]);
    }

    public function svm_fun()
    {
        Sentiment::truncate();
        $bobot = BobotTrain::all();
        $trains = Training::all();
        $train_data = array();
        $labels = array();
        foreach ($trains as $data_train) {
            $id = $data_train->id;
            foreach ($bobot as $value) {
                if ($value->tweet_id == $id) {
                    $train_data[$id - 1][] = $value->tfidf;
                }
            }
            $labels[] = $data_train->label;
        }

        $ndata = count($train_data);
        $datamax = 0;
        for ($i = 0; $i < $ndata; $i++) {
            $nDataIns = count($train_data[$i]);
            if ($nDataIns > $datamax) {
                $datamax = $nDataIns;
            } else {
                $datamax = $datamax;
            }
        }

        for ($i = 0; $i < $ndata; $i++) {
            $nDataIns = count($train_data[$i]);
            if ($nDataIns < $datamax) {
                for ($j = $nDataIns; $j < $datamax; $j++) {
                    $train_data[$i][$j] = 0;
                }
            }
        }

        $svm = new Svm();
        $svm->train($train_data, $labels);

        $bobot = Bobot::all();
        $dataset = Dataset::all();
        $test_data = array();
        $tweet = array();
        foreach ($dataset as $data_testing) {
            $id = $data_testing->id;
            foreach ($bobot as $value) {
                if ($value->tweet_id == $id) {
                    $test_data[$id - 1][] = $value->tfidf;
                }
            }
            $tweet[] = $data_testing->tweets;
        }

        $ndata = count($test_data);
        for ($i = 0; $i < $ndata; $i++) {
            $nDataIns = count($test_data[$i]);
            if ($nDataIns > $datamax) {
                $datamax = $nDataIns;
            } else {
                $datamax = $datamax;
            }
        }

        for ($i = 0; $i < $ndata; $i++) {
            $nDataIns = count($test_data[$i]);
            if ($nDataIns < $datamax) {
                for ($j = $nDataIns; $j < $datamax; $j++) {
                    $test_data[$i][$j] = 0;
                }
            }
        }

        $predict = $svm->predict($test_data);

        $nTweet = count($tweet);
        for ($i = 0; $i < $nTweet; $i++) {
            $sentiment = new Sentiment;

            $sentiment->tweets = $tweet[$i];
            $sentiment->sentiment = $predict[$i];

            $sentiment->save();
        }

        return redirect()->route('svm')->with('status', 'Data Successfully Processing');
        // echo $ndata;
        // echo
        // '<pre>';
        // print_r($train_data);
        // echo '</pre>';
    }
}
