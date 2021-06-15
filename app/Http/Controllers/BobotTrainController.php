<?php

namespace App\Http\Controllers;

use App\Helper\PembobotanTraining;
use App\Models\BobotTrain;
use App\Models\Preprocessing;

class BobotTrainController extends Controller
{
    public function index()
    {
        $data = BobotTrain::all();
        return view('bobot_train', ['data' => $data]);
    }

    public function pembobotan()
    {
        BobotTrain::truncate();
        $data = Preprocessing::all();

        foreach ($data as $tweets) {
            PembobotanTraining::hitung_tf($tweets);
        }

        return redirect()->route('pembobotan.training')->with('status', 'Data Successfully Processing');
    }

    public function tfidf()
    {
        PembobotanTraining::hitung_tfidf();

        return redirect()->route('pembobotan.training')->with('status', 'Data Successfully Processing');
    }
}
