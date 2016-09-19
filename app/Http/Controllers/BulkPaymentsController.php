<?php

namespace App\Http\Controllers;

use App;
use App\Address;
use App\BulkNarration;
use App\BulkPayment;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\CreateBulkPaymentRecordRequest;
use App\Http\Requests\CreateBulkPaymentRequest;
use App\Individual;
use App\Jobs\SendBulkRegistrationEditSms;
use App\Jobs\SendBulkRegistrationPaymentSms;
use App\Jobs\SendBulkRegistrationSms;
use App\Journal;
use App\Member;
use App\Narration;
use App\Payment;
use App\PaymentHead;
use App\PaymentMode;
use App\Phone;
use App\RequestService;
use App\Service;
use App\ServicePeriod;
use App\StudentMember;
use Auth;
use DB;
use Excel;
use GuzzleHttp\Client;
use Input;
use Laracasts\Flash\Flash;
use Log;
use Mail;
use Request;
use Validator;

class BulkPaymentsController extends Controller
{


    private $client = null;

    public function __construct(){
        $this->client = new Client();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user()->user()->getMembership;
        $username = $user->getName();
        $bulkPayments = BulkPayment::filterByInstitution($user->id)->get();
        
        return view('frontend.dashboard.view-bulk-payments', compact('username', 'bulkPayments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $username = Auth::user()->user()->getMembership->getName();
        $bulkPayment = BulkPayment::find($id);

        $payModes = PaymentMode::lists('name', 'id');
        $paymentMode = (Input::exists('paymentMode'))? Input::get('paymentMode'): '';
        $tno = (Input::exists('tno'))? Input::get('tno'): '';
        $drawn = (Input::exists('drawn'))? Input::get('drawn'): '';
        $bank = (Input::exists('bank'))? Input::get('bank'): '';
        $branch = (Input::exists('branch'))? Input::get('branch'): '';
        $amountPaid = (Input::exists('amountPaid'))? Input::get('amountPaid'): '';
        
        return view('frontend.dashboard.create-bulk-payments', compact('bulkPayment', 'username', 'payModes', 'membershipPeriods', 'membership_period', 'paymentMode', 'tno', 'drawn', 'bank', 'branch', 'amountPaid'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateBulkPaymentRequest $request, $id, $mode)
    {
        $user = Auth::user()->user();

        if ($user){
            if($mode == 'offline'){
                if($this->storeOfflinePayment($id)){
                    Flash::success("Your Payments are done successfully! Kindly wait for authentication");
                } else{
                    Flash::error('Oops! There occured some error while updating, please contact the technical team');
                }
            } else if($mode == 'online'){
                // online methods
            }
        }
        return redirect()->route('BulkPaymentsView');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * View Reject Reason of the bulkNarration.
     * id is payment-id
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     */
    public function viewRejectionReason($id, $narration_id)
    {
        $bulkPayment = BulkPayment::find($id);  // when shifting to split payments in bulk payments, do search by institution and narration id, or make a mapper btw bulk-payment & narration
        return $bulkPayment->rejection_reason;
    }

    public function uploadCSV(CreateBulkPaymentRecordRequest $request, $id = 0){
        if(intval($id) < 0){
            $bp = BulkPayment::find($id);
            // unauthorized
            if($bp->institution_id != Auth::user()->user()->getMembership->id) {
                Flash::error("You are not authorized for this action");
                return redirect()->back();
            }
        }
        $user = Auth::user()->user();
        $payer = Auth::user()->user()->getMembership;
        $listOfMembers = Input::file('listOfMembers');
        $count = 0;
        $calculated_amount = 0;
        $isAllDone = true;
        Excel::load($listOfMembers, function($reader) use(&$count, &$calculated_amount, $payer, &$isAllDone){
            if($reader){
                $rows = $reader->all(); 
                $count = $rows->count();
                foreach ($rows as $row) {

                    $membership_type_id = 3;
                    $service_id = ServicePeriod::getPeriodsByTypeAndDuration($membership_type_id, $row->membership_period)->first()->id;
                    $currency_id = (Address::getRegisteredAddress($payer->member_id)->first()->country_code == 'IND')? 1: 2;
                    $calculated_amount += PaymentHead::getHead($service_id, $currency_id)->first()->calculatePayable();
                    
                        $validator = validator::make(array_map('trim', $row->all()), [
                            'membership_period' => 'required',
                            'salutation' => 'required',
                            'fname' => 'required|string',
                            'mname' => 'string',
                            'lname' => 'required|string',
                            'card_name' => 'required|string',
                            'dob' => 'required|date_format:d/m/Y',
                            'gender' => 'required',
                            'address' => 'required|string',
                            'city' => 'required|string',
                            'pincode' => 'required|numeric',
                            'email1' => 'required|email|unique:members,email',
                            'email2' => 'email|unique:members,email_extra',
                            'std_code' => 'required|numeric',
                            'landline_phone' => 'required|numeric',
                            'country_code' => 'required|numeric',
                            'mobile' => 'required|numeric',
                            'amount' => 'required|numeric',
                            'college' => 'required|string',
                            'course' => 'required|string',
                            'branch_name' => 'required|string',
                            'cduration' => 'required|numeric',
                        ]);
                        if ($validator->fails()) {
                            $isAllDone = false;
                            Flash::error('Please check the integrity of uploaded data');
                            return redirect()
                                        ->back()
                                        ->withErrors($validator);
                        }
                        
                    
                    // check if not an academic branch then reject the csv, let him upload again.
                    // if( ((1 != $payer->membership_type_id ) && ( 1!=$payer->subType->is_student_branch ) ) ){
                    //     Flash::error("Sorry! Your member data consists of student members, which are not allowed to be entered if your not an Academic Student branch on CSI. Please upload rectified data.");
                    //     return redirect()->back();
                    // } 
                }// for
            }//if 
        });
        
        if($isAllDone){
            if($count<=0){
                Flash::error("Please Upload CSV with atleast 1 student data");
                return redirect()->back();
            }
            
            $bp = BulkPayment::create(['institution_id',
                'institution_id' => $payer->id,
                'member_count' => $count,
                'calculated_amount' => $calculated_amount,
            ]);

            if($bp){
                
                // uploading and saving the member's data(csv)
                $upload_members_doc_filename = $payer->id.'-'.$bp->id.'.';
                $upload_members_doc_filename.=$listOfMembers->getClientOriginalExtension();
                $listOfMembers->move(storage_path('uploads/bulk_payments'), $upload_members_doc_filename);
                $bp->uploads = $upload_members_doc_filename;
                $bp->save();

                if(App::environment('production')){
                    $this->dispatch(new SendBulkRegistrationSms($user->email, $payer->getMobile()));

                    $data = [
                        'data' => [
                            "template" => "bulk_membership/bulk-registration-request",
                            "subject" => "CSI-Bulk Registeration",
                            "to" => $user->email,
                            "payload" => ['name' => $payer->getName(), 'email' => $user->email, 'mid' => $user->getFullAllotedID()]
                        ]
                    ];
                    if ( $user->membership_id==1 ) { //institutions
                        $data['data']['cc'] = $user->getMembership->email;
                    }
                    $response = $this->client->requestAsync('POST', 'http://127.0.0.1:8000/email',[
                        'json' => $data,
                    ]);
                }
                Flash::success("Record added successfully! Please Check the record for further process!");
                return redirect()->back();
            } else{
                Flash::error("Oops! some technical error occured! please contact the technical staff");
                return redirect()->back();
            }
        } else{
            return redirect()->back();
        }

    } 

    public function uploadCSVEdit(CreateBulkPaymentRecordRequest $request, $id){
        if(intval($id) < 0){
            Flash::error('Oops! some error while downloading, please contact the techincal staff');
            return redirect()->back();
        }
        $user = Auth::user()->user();
        $payer = Auth::user()->user()->getMembership;
        $listOfMembers = Input::file('listOfMembers');
        $count = 0;
        $calculated_amount = 0;
        $isAllDone = true;
        Excel::load($listOfMembers, function($reader) use(&$count, &$calculated_amount, $payer, &$isAllDone){
            if($reader){
                $rows = $reader->all(); 
                $count = $rows->count();
                foreach ($rows as $row) {

                    $membership_type_id =3;
                    $service_id = ServicePeriod::getPeriodsByTypeAndDuration($membership_type_id, $row->membership_period)->first()->id;
                    $currency_id = (Address::getRegisteredAddress($payer->member_id)->first()->country_code == 'IND')? 1: 2;
                    $calculated_amount += PaymentHead::getHead($service_id, $currency_id)->first()->calculatePayable();
                    
                        $validator = validator::make(array_map('trim', $row->all()), [
                            'membership_period' => 'required',
                            'salutation' => 'required',
                            'fname' => 'required|string',
                            'mname' => 'string',
                            'lname' => 'required|string',
                            'card_name' => 'required|string',
                            'dob' => 'required|date_format:d/m/Y',
                            'gender' => 'required',
                            'address' => 'required|string',
                            'city' => 'required|string',
                            'pincode' => 'required|numeric',
                            'email1' => 'required|email|unique:members,email',
                            'email2' => 'email|unique:members,email_extra',
                            'std_code' => 'required|numeric',
                            'landline_phone' => 'required|numeric',
                            'country_code' => 'required|numeric',
                            'mobile' => 'required|numeric',
                            'amount' => 'required|numeric',
                            'college' => 'required|string',
                            'course' => 'required|string',
                            'branch_name' => 'required|string',
                            'cduration' => 'required|numeric',
                        ]);
                        if ($validator->fails()) {
                            $isAllDone = false;
                            Flash::error('Please check the integrity of uploaded data');
                            return redirect()
                                        ->back()
                                        ->withErrors($validator);
                        }
                        
                    
                    // check if not an academic branch then reject the csv, let him upload again.
                    if( ((1 != $payer->membership_type_id ) && ( 1!=$payer->subType->is_student_branch ) ) ){
                        Flash::error("Sorry! Your member data consists of student members, which are not allowed to be entered if your not an Academic Student branch on CSI. Please upload rectified data.");
                        return redirect()->back();
                    } 
                }// for
            }//if 
        });
        if($isAllDone){
            if($count<=0){
                Flash::error("Please Upload CSV with atleast 1 student data");
                return redirect()->back();
            }
            $bp = BulkPayment::find($id);
            $file = $bp->uploads;
            // uploading and saving the member's data(csv)
            $listOfMembers->move(storage_path('uploads/bulk_payments'), $file);
            $bp->calculated_amount = $calculated_amount;
            $bp->save();
            if(App::environment('production')){
                $this->dispatch(new SendBulkRegistrationEditSms($user->email, $payer->getMobile()));

                $data = [
                    'data' => [
                        "template" => "bulk_membership/bulk-registration-edit-request",
                        "subject" => "CSI-Bulk Registeration Edit",
                        "to" => $user->email,
                        "payload" => ['name' => $payer->getName(), 'email' => $user->email,  'mid' => $user->getFullAllotedID()]
                    ]
                ];
                if ( $user->membership_id==1 ) { //institutions
                    $data['data']['cc'] = $user->getMembership->email;
                }
                $response = $this->client->requestAsync('POST', 'http://127.0.0.1:8000/email',[
                    'json' => $data,
                ]);
            }
            Flash::success('Updated successfully');
        }
        return redirect()->back();           
    }

    public function downloadCSV($id){
        $file = BulkPayment::find($id)->uploads;
        return response()->download(storage_path() . '/uploads/bulk_payments/' .$file, 'temp.'.substr($file, -3, 3));
    }

    public function downloadSampleCSV($id){
        return response()->download(storage_path() . '/sample-user-bulk-payments.csv', 'sample-user-bulk-payments.csv');
    }

    public function storeOfflinePayment($id){
        $payer = Auth::user()->user();

        $paymentMode            = Input::get('paymentMode');
        $tno                    = Input::get('tno');
        $drawn                  = Input::get('drawn');
        $bank                   = Input::get('bank');
        $branch                 = Input::get('branch');
        $paymentReciept         = Input::file('paymentReciept');
        $amountPaid             = Input::get('amountPaid');

        $narration = Narration::create([ 
            'payer_id' => $payer->id, 
            'mode' => $paymentMode, 
            'transaction_number' => $tno, 
            'bank' => $bank, 
            'branch' => $branch, 
            'date_of_payment' => $drawn, 
            'drafted_amount' => $amountPaid
        ]);


        $filename = $payer->id.'-'.$narration->id.'.';
        $filename.=$paymentReciept->getClientOriginalExtension();
        $paymentReciept->move(storage_path('uploads/payment_proofs'), $filename);
        $narration->proof = $filename;
        // if(!$narration->save()){
        //     false;
        // }
        $bp = BulkPayment::find($id);
        $bp->narration_id = $narration->id;
        
        if(!$bp->save()){
            return false;
        }
        if(App::environment('production')){
            $this->dispatch(new SendBulkRegistrationPaymentSms($payer->email, $payer->getMembership->getMobile()));

            $data = [
                'data' => [
                    "template" => "bulk_membership/bulk-registration-payment",
                    "subject" => "CSI-Bulk Registeration Payments",
                    "to" => $payer->email,
                    "payload" => ['name' => $payer->getMembership->getName(), 'email' => $payer->email,  'mid' => $payer->getFullAllotedID()]
                ]
            ];
            if ( $payer->membership_id==1 ) { //institutions
                $data['data']['cc'] = $payer->getMembership->email;
            }
            $response = $this->client->requestAsync('POST', 'http://127.0.0.1:8000/email',[
                'json' => $data,
            ]);
        }
        return true;
    }


    public function storeBulkRecords($id){
        $payer = Auth::user()->user();

        $paymentMode            = Input::get('paymentMode');
        $tno                    = Input::get('tno');
        $drawn                  = Input::get('drawn');
        $bank                   = Input::get('bank');
        $branch                 = Input::get('branch');
        $paymentReciept         = Input::file('paymentReciept');
        $amountPaid             = Input::get('amountPaid');

        $narration = Narration::create([ 
            'payer_id' => $payer->id, 
            'mode' => $paymentMode, 
            'transaction_number' => $tno, 
            'bank' => $bank, 
            'branch' => $branch, 
            'date_of_payment' => $drawn, 
            'drafted_amount' => $amountPaid
        ]);


        $filename = $payer->id.'-'.$narration->id.'.';
        $filename.=$paymentReciept->getClientOriginalExtension();
        $paymentReciept->move(storage_path('uploads/payment_proofs'), $filename);
        $narration->proof = $filename;
        if(!$narration->save()){
            Flash::error('Oops! There occured some error, please contact the technical team');
            return redirect()->back();
        }
        $bp = BulkPayment::find($id);
        $bp->narration_id = $narration->id;
        if(!$bp->save()){
            Flash::error('Oops! There occured some error while updating, please contact the technical team');
            return redirect()->back();
        }

    }


}
