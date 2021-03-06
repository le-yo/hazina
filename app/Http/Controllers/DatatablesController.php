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


        return Datatables::of(Payment::whereStatus(1)->orderBy('updated_at', 'desc')->take(50)->get())
            ->editColumn('transaction_time', function ($payment) {
                return Carbon::parse($payment->transaction_time)->format('j F Y h:i A');
            })->editColumn('amount', function ($payment) {
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

                    $action = '
                                <a href="'.url('collectionSheet/'.$payment->id).'" class="btn btn-xs btn-info"><i class="icon-map"></i>Collection Sheet</a>
                            ';

                return $action;
            })->make(true);
    }

    public function getProcessedPayments()
    {
        return Datatables::of(Payment::whereStatus(1)->orderBy('updated_at', 'desc')->take(500)->get())
            ->editColumn('transaction_time', function ($payment) {
                return Carbon::parse($payment->transaction_time)->format('j F Y h:i A');
            })->editColumn('amount', function ($payment) {
                return number_format($payment->amount);
            })->make(true);
    }


    public function getUnProcessedPayments()
    {

        return Datatables::of(Payment::whereStatus(0)->orderBy('updated_at', 'desc')->take(500)->get())
            ->editColumn('transaction_time', function ($payment) {
                return Carbon::parse($payment->transaction_time)->format('j F Y h:i A');
            })->editColumn('amount', function ($payment) {
                return number_format($payment->amount);
            })->editColumn('action', function($id) {
                return '<ul class="list-unstyled list-inline">
                             <li>
                                <a data-toggle="modal" data-url="'.url('payments/editAccount/'.$id->id).'" data-target="#modal-comment" class="btn btn-info comment btn-sm"><i class="icon-note"></i>Enter Correct Account</a>
                            </li>
                            <li>
                                <a href="'.url('makePayment/manual/'.$id->id).'" class="btn btn-sm btn-info"><i class="icon-map"></i> Mark as processed</a>
                            </li>
                        </ul>';
            })
            ->make(true);
    }

    public function getUnrecognizedPayments()
    {

        return Datatables::of(Payment::whereStatus(2)->orderBy('updated_at', 'desc')->take(500)->get())
            ->editColumn('transaction_time', function ($payment) {
                return Carbon::parse($payment->transaction_time)->format('j F Y h:i A');
            })->editColumn('amount', function ($payment) {
                return number_format($payment->amount);
            })->editColumn('action', function($id) {
                return '<ul class="list-unstyled list-inline">
                             <li>
                                <a data-toggle="modal" data-url="'.url('payments/editAccount/'.$id->id).'" data-target="#modal-comment" class="btn btn-info comment btn-sm"><i class="icon-note"></i>Enter Correct Account</a>
                            </li>
                            <li>
                                <a href="'.url('makePayment/manual/'.$id->id).'" class="btn btn-sm btn-info"><i class="icon-map"></i> Mark as processed</a>
                            </li>
                        </ul>';
            })
            ->make(true);
    }
}
