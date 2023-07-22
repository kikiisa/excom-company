<?php

namespace App\Http\Controllers\account;

use Excel;
use App\Credit;
use App\Http\Controllers\Controller;
use App\Http\Controllers\CreditReportController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanCreditController extends Controller
{
    /**
     * LaporanCreditController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('account.laporan_credit.index');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Validation\ValidationException
     */
    public function check(Request $request)
    {
        // set validasi required

        $this->validate(
            $request,
            [
                'tanggal_awal'     => 'required',
                'tanggal_akhir'    => 'required',
            ],
            //set message validation
            [
                'tanggal_awal.required'  => 'Silahkan Pilih Tanggal Awal!',
                'tanggal_akhir.required' => 'Silahkan Pilih Tanggal Akhir!',
            ]
        );
        $tanggal_awal  = $request->input('tanggal_awal');
        $tanggal_akhir = $request->input('tanggal_akhir');
        $credit = Credit::select('credit.id', 'credit.category_id', 'credit.user_id', 'credit.nominal', 'credit.credit_date', 'credit.description', 'categories_credit.id as id_category', 'categories_credit.name')
            ->join('categories_credit', 'credit.category_id', '=', 'categories_credit.id', 'LEFT')
            ->whereDate('credit.credit_date', '>=', $tanggal_awal)
            ->whereDate('credit.credit_date', '<=', $tanggal_akhir)
            ->where('credit.user_id', Auth::user()->id)
            ->paginate(10)
            ->appends(request()->except('page'));
        if ($request->action == 'view') {

            return view('account.laporan_credit.index', compact('credit', 'tanggal_awal', 'tanggal_akhir'));
        } else {
            return Excel::download(new CreditReportController($tanggal_awal,$tanggal_akhir),"{$tanggal_awal}{$tanggal_akhir}.xlsx");
        }
    }
}
