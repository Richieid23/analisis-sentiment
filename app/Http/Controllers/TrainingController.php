<?php

namespace App\Http\Controllers;

use App\Imports\TrainImport;
use App\Models\Training;
use App\Models\Bobot;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;
use App\Helper\Svm;


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

    public function train()
    {
        $bobot = Bobot::all();
        $trains = Training::all();
        $data = array();
        $labels = array();
        foreach ($trains as $data_train) {
            $id = $data_train->id;
            foreach ($bobot as $value) {
                if ($value->tweet_id == $id) {
                    $data[$id - 1][] = $value->tfidf;
                }
            }
            $labels[] = $data_train->label;
        }

        $ndata = count($data);
        $datamax = 0;
        for ($i = 0; $i < $ndata; $i++) {
            $nDataIns = count($data[$i]);
            if ($nDataIns > $datamax) {
                $datamax = $nDataIns;
            } else {
                $datamax = $datamax;
            }
        }

        for ($i = 0; $i < $ndata; $i++) {
            $nDataIns = count($data[$i]);
            if ($nDataIns < $datamax) {
                for ($j = $nDataIns; $j < $datamax; $j++) {
                    $data[$i][$j] = 0;
                }
            }
        }

        // $data = [[0, 0], [0.5, 0.5], [0.7, 0.7], [1, 1]];
        // $labels = [-1, -1, 1, 1];

        global $svm;
        $svm->train($data, $labels);

        // echo
        // '<pre>';
        // print_r($predict);
        // echo '</pre>';

        // echo
        // '<pre>';
        // print_r($labels);
        // echo '</pre>';
    }
}
