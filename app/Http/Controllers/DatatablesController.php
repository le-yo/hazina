<?php

namespace App\Http\Controllers;

use App\categories;
use App\DataTables\PaymentsDataTable;
use App\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use Yajra\Datatables\Datatables;

class DatatablesController extends Controller
{
    public  function __construct()
    {
        $this->middleware('auth');
    }

    public function getPayments()
    {

        return Datatables::of(Payment::whereStatus(0)->orderBy('transaction_time', 'desc')->limit(50)->get())
            ->editColumn('amount', function ($payment) {
                return number_format($payment->amount);
            })->editColumn('category', function ($payment) {

//                $category = categories::find($payment->category);
//                if($category){
//                    return '<a href="/paymentcategory/'.$category->id.'">'.$category->name.'</a>';
//                }else{
                    return "Payment not categorized";
//                }
//                return number_format($payment->amount);
            })->editColumn('action', function($payment) {
                    $action = '<a href="'.url('collectionSheet/'.$payment->id).'"> <button class="c-btn c-btn--info u-mr-xsmall" aria-haspopup="true" aria-expanded="false">Collection Sheet</button></a>
                 ';
                return $action;
            })->make(true);
    }

    public function getProcessedPayments()
    {
        return Datatables::of(Payment::whereStatus(1)->orderBy('transaction_time', 'desc')->limit(50)->get())
            ->editColumn('amount', function ($payment) {
                return number_format($payment->amount);
            })->editColumn('category', function ($payment) {

//                $category = categories::find($payment->category);
//                if($category){
//                    return '<a href="/paymentcategory/'.$category->id.'">'.$category->name.'</a>';
//                }else{
                return "Payment not categorized";
//                }
//                return number_format($payment->amount);
            })->editColumn('action', function($payment) {
                $action = ' <div class="c-dropdown dropdown">
                                                <button class="c-btn c-btn--info u-mr-xsmall has-dropdown dropdown-toggle" id="dropdownMenuButton'.$payment->id.'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Actions</button>
                                                
                                                <div class="c-dropdown__menu dropdown-menu" aria-labelledby="dropdownMenuButton'.$payment->id.'">
                                                
                                                    <a href="'.url('collectionSheet/'.$payment->id).'" class="c-dropdown__item dropdown-item">Collection Sheet</a>
                                                </div>
                                            </div>';
                return $action;
            })->make(true);
    }


    public function getUnrecognizedPayments()
    {
        return Datatables::of(Payment::whereStatus(2)->orderBy('transaction_time', 'desc')->limit(50)->get())
            ->editColumn('amount', function ($payment) {
                return number_format($payment->amount);
            })->editColumn('category', function ($payment) {

//                $category = categories::find($payment->category);
//                if($category){
//                    return '<a href="/paymentcategory/'.$category->id.'">'.$category->name.'</a>';
//                }else{
                return "Payment not categorized";
//                }
//                return number_format($payment->amount);
            })->editColumn('action', function($payment) {
                $action = ' <div class="c-dropdown dropdown">
                                                <button class="c-btn c-btn--info u-mr-xsmall has-dropdown dropdown-toggle" id="dropdownMenuButton'.$payment->id.'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Actions</button>
                                                
                                                <div class="c-dropdown__menu dropdown-menu" aria-labelledby="dropdownMenuButton'.$payment->id.'">
                                                
                                                    <a href="'.url('collectionSheet/'.$payment->id).'" class="c-dropdown__item dropdown-item">Collection Sheet</a>
                                                </div>
                                            </div>';
                return $action;
            })->make(true);
    }
}
