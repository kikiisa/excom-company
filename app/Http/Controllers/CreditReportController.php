<?php

namespace App\Http\Controllers;

use App\Credit;
use Illuminate\Http\Request;
use illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromView;

class CreditReportController implements FromView
{
    public $tgl_awal;
    public $tgl_akhir;

    public function __construct($tgl_awal,$tgl_akhir)
    {
        $this->tgl_awal = $tgl_awal;
        $this->tgl_akhir = $tgl_akhir;
    }

    public function view(): View
    {
        $tanggal_awal = $this->tgl_awal;
        $tanggal_akhir = $this->tgl_akhir;
        $credit = Credit::select('credit.id', 'credit.category_id', 'credit.user_id', 'credit.nominal', 'credit.credit_date', 'credit.description', 'categories_credit.id as id_category', 'categories_credit.name')
            ->join('categories_credit', 'credit.category_id', '=', 'categories_credit.id', 'LEFT')
            ->whereDate('credit.credit_date', '>=', $this->tgl_awal)
            ->whereDate('credit.credit_date', '<=', $this->tgl_akhir)
            ->where('credit.user_id', Auth::user()->id)->get();
        return view('report.keluar',compact('credit', 'tanggal_awal', 'tanggal_akhir'));
    }
}
