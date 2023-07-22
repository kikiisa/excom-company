<?php

namespace App\Http\Controllers;

use App\Debit;
use Illuminate\Http\Request;
use illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromView;

class LaporanController implements FromView
{
    public $tgl_awal;
    public $tgl_akhir;

    public function __construct($tgl_awal,$tgl_akhir)
    {
        $this->tgl_awal = $tgl_awal;
        $this->tgl_akhir = $tgl_akhir;    
    }
    public function view():View
    {

        $tanggal_awal = $this->tgl_awal;
        $tanggal_akhir = $this->tgl_akhir;
        $debit = Debit::select('debit.id', 'debit.category_id', 'debit.user_id', 'debit.nominal', 'debit.debit_date', 'debit.description', 'categories_debit.id as id_category', 'categories_debit.name')
        ->join('categories_debit', 'debit.category_id', '=', 'categories_debit.id', 'LEFT')
        ->whereDate('debit.debit_date', '>=', $tanggal_awal)
        ->whereDate('debit.debit_date', '<=', $tanggal_akhir)
        ->where('debit.user_id',Auth::user()->id)->get();
        return view('report.masuk',compact('debit','tanggal_awal','tanggal_akhir'));
        // return view('report.masuk', [
        //     'debit' => $this->debit, 
        //     'tanggal_awal' => $this->tgl_awal, 
        //     'tanggal_akhir' => $this->tgl_akhir
        // ]);
    }
}
