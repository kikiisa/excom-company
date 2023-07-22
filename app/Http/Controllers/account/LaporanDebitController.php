<?php

namespace App\Http\Controllers\account;

use App\Debit;
use Excel;
use App\Http\Controllers\Controller;
use App\Http\Controllers\LaporanController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class LaporanDebitController extends Controller
{
    
    /**
     * LaporanDebitController constructor.
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
        return view('account.laporan_debit.index');
    }


    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Validation\ValidationException
     */
    public function check(Request $request)
    {
        // set validasi required
        $this->validate($request, [
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
        $debit = Debit::select('debit.id', 'debit.category_id', 'debit.user_id', 'debit.nominal', 'debit.debit_date', 'debit.description', 'categories_debit.id as id_category', 'categories_debit.name')
            ->join('categories_debit', 'debit.category_id', '=', 'categories_debit.id', 'LEFT')
            ->whereDate('debit.debit_date', '>=', $tanggal_awal)
            ->whereDate('debit.debit_date', '<=', $tanggal_akhir)
            ->where('debit.user_id',Auth::user()->id)
            ->paginate(10)
            ->appends(request()->except('page'));
        if($request->action == 'view')
        {
            return view('account.laporan_debit.index', compact('debit', 'tanggal_awal', 'tanggal_akhir'));
        }else{
            return Excel::download(new LaporanController($tanggal_awal,$tanggal_akhir),"{$tanggal_awal}{$tanggal_akhir}.xlsx");
        }

    }

}
