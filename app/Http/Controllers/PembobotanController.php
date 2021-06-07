<?php

namespace App\Http\Controllers;

use App\Helper\Pembobotan;
use App\Models\Bobot;
use App\Models\Preprocessing;

class PembobotanController extends Controller
{
    public function index()
    {
        $data = Bobot::all();
        return view('pembobotan', ['data' => $data]);
    }

    public function pembobotan()
    {
        Bobot::truncate();
        $data = Preprocessing::all();

        foreach ($data as $tweets) {
            Pembobotan::hitung_tf($tweets);
        }

        return redirect()->route('pembobotan')->with('status', 'Data Successfully Processing');
    }

    public function tfidf()
    {
        Pembobotan::hitung_tfidf();

        return redirect()->route('pembobotan')->with('status', 'Data Successfully Processing');
    }
}
