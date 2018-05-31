<?php

namespace App\Http\Controllers;

use App\PreapprovedClients;
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
        $title = 'Clients';
        $clients = PreapprovedClients::orderBy('id','DESC')->paginate(6);
        return view('preapproved-clients.index',compact('clients','title'));
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
            $i = 0;
            $j = 0;
//            print_r($data);exit();
            if (!empty($data) && $data->count()){
                foreach($data[0] as $key => $value){

//                    print_r($value);exit();
                    if(!empty($value['mobilenumber'])){
                        $no = substr($value['mobilenumber'], -9);

                        $client = PreapprovedClients::where('mobile_number', "0" . $no)->orWhere('mobile_number', "254" . $no)->first();
                        if(!$client){
                            $data = [
                                'mobile_number' => $value['mobilenumber'],
                                'national_id_number' => $value['nationalidnumber'],
                                'names' => $value['names'],
                                'net_salary' => $value['netsalary'],
                                'loan_limits' => $value['loanlimits']
                            ];
                            PreapprovedClients::create($data);
                            $i++;
                        }else{
                            $j++;
                        }
                    }

                }
            }
            Session::flash('success',$i.' clients added successfully and '.$j.' duplicates rejected.');
            return redirect('preapproved-clients');
        }else{
            Session::flash('error','Your file is not valid');
            return back();
        }
    }

}
