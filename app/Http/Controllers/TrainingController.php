<?php

namespace App\Http\Controllers;

use App\Imports\TrainImport;
use App\Models\Training;
use App\Models\Bobot;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;
use App\Helper\Svm;
use App\Models\BobotTrain;
use App\Models\Preprocessing;
use App\Models\Sentiment;

class TrainingController extends Controller
{
    public function index()
    {
        $dataset = Training::all();
        return view('train', ['data' => $dataset]);
    }

    public function import_training(Request $request)
    {
        Training::truncate();
        Preprocessing::truncate();
        BobotTrain::truncate();
        Sentiment::truncate();

        // validasi
        $this->validate($request, [
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        // menangkap file excel
        $file = $request->file('file');

        // membuat nama file unik
        $nama_file = rand() . $file->getClientOriginalName();

        // upload ke folder file_dataset di dalam folder public
        $file->move('file_dataset', $nama_file);

        // import data
        Excel::import(new TrainImport, public_path('/file_dataset/' . $nama_file));

        // notifikasi dengan session
        Session::flash('sukses', 'Data Tweets Berhasil Diimport!');

        // alihkan halaman kembali
        return redirect()->route('train');
    }
}
