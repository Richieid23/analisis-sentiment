<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use App\Models\Dataset;
use Illuminate\Http\Request;
use App\Imports\DatasetImport;
use App\Models\Bobot;
use App\Models\Preprocessing;
use App\Models\Sentiment;
use Maatwebsite\Excel\Facades\Excel;

class DatasetController extends Controller
{
    public function index()
    {
        $dataset = Dataset::all();
        return view('dataset', ['data' => $dataset]);
    }

    public function import_dataset(Request $request)
    {

        Dataset::truncate();
        Preprocessing::truncate();
        Bobot::truncate();
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
        Excel::import(new DatasetImport, public_path('/file_dataset/' . $nama_file));

        // notifikasi dengan session
        Session::flash('sukses', 'Data Tweets Berhasil Diimport!');

        // alihkan halaman kembali
        return redirect()->route('dataset');
    }
}
