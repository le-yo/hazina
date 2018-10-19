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


        return Datatables::of(Payment::whereStatus(1)->orderBy('transaction_time', 'desc')->get())
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
        return Datatables::of(Payment::whereStatus(1)->orderBy('transaction_time', 'desc')->get())
            ->editColumn('transaction_time', function ($payment) {
                return Carbon::parse($payment->transaction_time)->format('j F Y h:i A');
            })->editColumn('amount', function ($payment) {
                return number_format($payment->amount);
            })->make(true);
    }


    public function getUnrecognizedPayments()
    {

        return Datatables::of(Payment::whereStatus(2)->orderBy('transaction_time', 'desc')->get())
            ->editColumn('transaction_time', function ($payment) {
                return Carbon::parse($payment->transaction_time)->format('j F Y h:i A');
            })->editColumn('amount', function ($payment) {
                return number_format($payment->amount);
            })->editColumn('action', function($id) {
                return '<ul class="list-unstyled list-inline">
                         <li>
                                <a href="'.url('makePayment/manual/'.$id->id).'" class="btn btn-xs btn-info"><i class="icon-map"></i> Mark as Processed</a>
                            </li>
                             <li>
                                <a data-url="'.url('payments/comment/'.$id->id).'" class="btn btn-xs btn-info comment"><i class="icon-note"></i> Add Comment</a>
                            </li>
                            <li>
                        </ul>';
            })
            ->make(true);
    }
}
