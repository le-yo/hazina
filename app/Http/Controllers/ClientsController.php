<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Files\File;
use Maatwebsite\Excel\Facades\Excel;

class ClientsController extends Controller
{
    //
    public function index(){

        return view('preapproved-clients.index');

    }

    public function upload(){
        return view('preapproved-clients.upload');
    }

    public function storeUpload(Request $request){
//      validate the file
        $validator = Validator::make($request->all(),[
            'file'=>'required'
        ]);

        if ($validator->fails()){
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();

        }

//        upload logic process
        if ($request->hasFile('file')){
            $path = $request->file->getRealPath();
            $data = Excel::load($path, function ($reader){

            })->get();

//            print_r($data);exit();
            if (!empty($data) && $data->count()){
                foreach($data[0] as $key => $value){
//                    print_r($value);exit();
                    $insert[] =
                    [
                        'mobile_number' => $value['mobilenumber'],
                        'national_id_number' => $value['nationalidnumber'],
                        'names' => $value['names'],
                        'net_salary' => $value['netsalary'],
                        'loan_limits' => $value['loanlimits']
                    ];
                }
//                print_r($insert);exit();
                if (!empty($insert)){
                    $insertData = DB::table('preapproved_clients')->insert($insert);

                    if ($insertData){
                        Session::flash('success','Your data has successfully imported');
                    }else{
                        Session::flash('error','Error while importing your data');
                    }
                }
            }
            return back();
        }else{
            Session::flash('error','Your file is not valid');
            return back();
        }
    }

}
