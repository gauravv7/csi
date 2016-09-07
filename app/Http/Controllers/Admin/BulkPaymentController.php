<?php

namespace App\Http\Controllers\Admin;

use App;
use App\AcademicMember;
use App\Address;
use App\BulkPayment;
use App\Enums\ActionStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\UpdateNarrationRequest;
use App\Individual;
use App\Institution;
use App\InstitutionType;
use App\Jobs\SendBulkMembershipRegisterSms;
use App\Jobs\SendBulkRegistrationAcceptSms;
use App\Jobs\SendBulkRegistrationRejectSms;
use App\Journal;
use App\Member;
use App\MembershipType;
use App\Narration;
use App\Payment;
use App\PaymentHead;
use App\Phone;
use App\ProfessionalMember;
use App\RequestAction;
use App\RequestService;
use App\Service;
use App\ServicePeriod;
use App\StudentMember;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Laracasts\Flash\Flash;
use Maatwebsite\Excel\Facades\Excel;
use Mail;
use Response;

class BulkPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rows = (Input::exists('row'))? (Input::get('row') < 5)?5:Input::get('row'): 15;          // how many rows for pagination
        $page = (Input::exists('page'))? abs(Input::get('page')): 1;        // current page

        $verified = (Input::exists('v'))? Input::get('v'): false;           // verified members
        $not_verified = (Input::exists('nv'))? Input::get('nv'): false;     // non verified members


        if($verified && !$not_verified){
            $bulkPayments = BulkPayment::where('is_rejected', 1)->orderBy('created_at', 'desc')->paginate($rows);
        }
        if(!$verified && $not_verified) {
            $bulkPayments = BulkPayment::where('is_rejected', -1)->orderBy('created_at', 'desc')->paginate($rows);
        } else{
            $bulkPayments = BulkPayment::paginate($rows);
        }
        
        return view('backend.bulk-payments.listing', compact('bulkPayments', 'page', 'rows'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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

    public function details($id)
    {
        if($id!=null || intval($id)>0){
            $bulkPayment = BulkPayment::find(intval($id));
            $user = $bulkPayment->institution->member;
            $narration = $bulkPayment->narration;
                        
            return view('backend.bulk-payments.payment', compact('user', 'bulkPayment', 'narration'));
        }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateNarrationRequest $request, $narration_id)
    {
        $narration = Narration::find($narration_id);
        $narration->bank = Input::get('bank');
        $narration->branch = Input::get('branch');
        $narration->drafted_amount = Input::get('amountPaid');
        $narration->save();
        Flash::success('Updated the Record');
        return redirect()->back();
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

    /**
     * Reject the bulkNarration.
     * id is payment-id
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     */
    public function reject($id, $narration_id)
    {
        $bulkPayment = BulkPayment::find($id);  // when shifting to split payments in bulk payments, do search by institution and narration id, or make a mapper btw bulk-payment & narration
        $bulkPayment->is_rejected = 1;
        $bulkPayment->rejection_reason = Input::get('rejection-reason');   // no validations yet
        $bulkPayment->save();
        $user = $bulkPayment->institution->member;
        if(App::environment('production')){
            $this->dispatch(new SendBulkRegistrationRejectSms($user->email, $user->getMembership->getMobile(), $bulkPayment->rejection_reason));
            Mail::queue('frontend.emails.bulk-registration-reject', ['name' => $user->getMembership->getName(), 'email' => $user->email,  'mid' => $user->getFullAllotedID(), 'reason' => $bulkPayment->rejection_reason], function($message) use($user){
                $message->to($user->email)->subject('CSI-Bulk Registeration Rejected');
                if($user->membership_id==1){
                    $message->cc($user->email)->subject('CSI-Bulk Registeration Rejected');
                }
            });
        }
        Flash::success('Rejected Successfully');
        return redirect()->back();
    }

    /**
     * Accept the bulkNarration.
     * id is payment-id
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     */
    public function accept($id, $narration_id)
    {
        $bulkPayment = BulkPayment::find($id);  // when shifting to split payments in bulk payments, do search by institution and narration id, or make a mapper btw bulk-payment & narration
        if(0==$bulkPayment->getPaidDiff()){
            if($this->storeBulkRecords($id)){
                Flash::success('payment accepted Successfully');
                $bulkPayment->is_rejected = 0;
                $bulkPayment->save();
                $user = $bulkPayment->institution->member;
                if(App::environment('production')){
                    $this->dispatch(new SendBulkRegistrationAcceptSms($user->email, $user->getMembership->getMobile()));
                    Mail::queue('frontend.emails.bulk-registration-accept', ['name' => $user->getMembership->getName(), 'email' => $user->email,  'mid' => $user->getFullAllotedID()], function($message) use($user){
                        $message->to($user->email)->subject('CSI-Bulk Registeration Accepted');
                        if($user->membership_id==1){
                            $message->cc($user->email)->subject('CSI-Bulk Registeration Accepted');
                        }
                    });
                }
            } else{
                Flash::error('Oops! There occured some error while processing the member data, please contact the technical staff');
            }
        } else{
            Flash::error('The Payments are not balanced yet');
        }
        return redirect()->back();
    }

    public function getResource($resource){
        $response = null;
        if(is_string($resource)){
            
            if('narration-update-info' == $resource){
                $nid = Input::get('nid');
                $narration = Narration::find($nid);
                $data = [
                    'amountPaid' => $narration->drafted_amount,
                    'bank' => $narration->bank,
                    'branch' => $narration->branch,
                ];
            } 
            
            $response = Response::json($data, 200);
        } else{
            $response = Response::json(array('errors' => $e->getMessage()), 500);
        }
        return $response;
    }

    public function storeBulkRecords($id){
        $bp = BulkPayment::find($id);
        $payer = $bp->institution->member;
        $isAllDone = true;
        Excel::load(storage_path() . '/uploads/bulk_payments/' . $bp->uploads, function($reader) use($payer, $bp, &$isAllDone)
        {   
                if($reader){
                    $results = $reader->all();
                foreach($results as $row)
                {
                    $str_password = str_random(15);
                    $password = (strcasecmp(env('APP_ENV', 'development'), 'production')==0)? Hash::make($str_password): Hash::make("1234");
                    // store every user
                    if( ( (1 == $payer->getMembership->membership_type_id) ){
                        $validator = Validator::make(array_map('trim', $row->all()), [
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
                        $user = $this->storeStudent($row, $payer, $bp, $password);
                    }
                    if(App::environment('production')){
                        $rid = RequestService::requestsByMemberIdAndServiceId($user->id, Service::getServiceIDByType('membership'))->first()->id;
                        $this->dispatch(new SendBulkMembershipRegisterSms($rid, $user->email, $user->getMembership->getMobile(), $user->getFormattedEntity(), $str_password));
                        Mail::queue('frontend.emails.bulk-membership-register', ['name' => $user->getMembership->getName(), 'email' => $user->email, 'rid' => $rid, 'category' => $user->getFormattedEntity(), 'password' => $str_password], function($message) use($user){
                            $message->to($user->email)->subject('CSI-Membership Registeration');
                        });
                    }
                    if(!$user){
                        return false;
                    }  
                }
                }
        });
        if(!$isAllDone){
            return false;
        }
        return true;

    }

    public function storeStudent($row, $payer, $bp, $password){
        return DB::transaction(function($connection) use($row, $payer, $bp, $password) {
            $salutation = $row->salutation;
            $fname = $row->fname;
            $mname = $row->mname;
            $lname = $row->lname;
            $card_name = $row->card_name;
            $dob = $row->dob;
            $gender = $row->gender;
    
            $country = Address::getRegisteredAddress($payer->id)->first()->country_code;
            $state = Address::getRegisteredAddress($payer->id)->first()->state_code;
            
            $address = $row->address;
            $city = $row->city;
            $pincode = $row->pincode;
            
            $college = $row->college;
            $course = $row->course;
            $cbranch = $row->branch_name;
            $cduration = $row->cduration;  
            
            $email1 = trim($row->email1);
            $email2 = trim($row->email2);
            $std = $row->std_code;
            $phone = $row->landline_phone;
            $country_code = $row->country_code;
            $mobile = $row->mobile;
            
            $student_branch = $payer->getMembership->id;
            $chapter = $payer->csi_chapter_id;
            $member = Member::create([
                'membership_id' => 2, // individual member
                'csi_chapter_id' => $payer->csi_chapter_id,
                'email' => $email1,
                'email_extra' => $email2,
                'password' => $password,
            ]);
            
            Address::create([
                'type_id' => 1,
                'member_id' => $member->id,
                'country_code' => $country, 
                'state_code' => $state,
                'address_line_1' => $address,
                'city' => $city,
                'pincode' => $pincode
            ]);

            Phone::create([
                'member_id' => $member->id,
                'std_code' => $std,
                'landline' => $phone,
                'country_code' => $country_code,
                'mobile' => $mobile,
            ]);

            $individual = Individual::create([
                'member_id' => $member->id, 
                'membership_type_id' => 3,  // student member 
                'salutation_id' => $salutation, 
                'first_name' => $fname,
                'middle_name' => $mname,
                'last_name' => $lname,
                'card_name' => $card_name,
                'gender' => $gender,
                'dob' => $dob
            ]);

            $student_details = StudentMember::create([
                'id'                => $individual->id,
                'student_branch_id' => $student_branch,
                'college_name'      => $college,
                'course_name'       => $course,
                'course_branch'     => $cbranch,
                'course_duration'   => $cduration,
                'is_verified'       => 1,
            ]);

            $membership_period      = $row->membership_period;
            $paymentMode            = 3; //cash
            $amountPaid             = $row->amount;

            // 2nd arg is currency.. needs to be queried to put here
            $head = PaymentHead::getHead(ServicePeriod::getPeriodsByTypeAndDuration(3, $membership_period)->first()->id, ($country=='IND')? 1: 2 )->first();
            $payment = Payment::create([
                'paid_for' => $member->id, 
                'payment_head_id' => $head->id, 
                'service_id' => 1
            ]);
            $payment->date_of_effect = Carbon::now()->format('d/m/Y');
            $payment->save();

            // in case of split payment, for every narration that the institution has for this bulkPayment, make a corresponding journal for the member
            $journal = Journal::create([
                'payment_id' => $payment->id,
                'narration_id' => $bp->narration_id,
                'paid_amount' => $amountPaid, 
            ]);
            $journal->is_rejected = 0;  // since we had fired this function when we knew, this bulkPayment has balanced amount, so accepting the same journals for the member now.
            $journal->save();
            
            $request = RequestService::create([
                'service_id' => Service::getServiceIDByType('membership'),
                'payment_id' => $payment->id, 
                'member_id'  => $member->id
            ]);

            RequestAction::create([
                'request_id' => $request->id,
                'status' => ActionStatus::pending
            ]);

            RequestAction::create([
                'request_id' => $request->id,
                'status' => ActionStatus::approved
            ]);

            return $member;
        });
    }

    public function storeProfessional($row, $payer, $bp, $password){
        return DB::transaction(function($connection) use($row, $payer, $bp, $password) {
            $salutation = $row->salutation;
            $fname = $row->fname;
            $mname = $row->mname;
            $lname = $row->lname;
            $card_name = $row->card_name;
            $dob = $row->dob;
            $gender = $row->gender;
            
            $country = Address::getRegisteredAddress($payer->id)->first()->country_code;
            $state = Address::getRegisteredAddress($payer->id)->first()->state_code;

            $address = $row->address;
            $city = $row->city;
            $pincode = $row->pincode;
            
            $organisation = $row->organisation;
            $designation = $row->designation;
            
            $email1 = trim($row->email1);
            $email2 = trim($row->email2);
            $std = $row->std;
            $phone = $row->phone;
            $country_code = $row->country_code;
            $mobile = $row->mobile;
            
            $member = Member::create([
                'membership_id' => 2, // individual member
                'csi_chapter_id' => $payer->csi_chapter_id,
                'email' => $email1,
                'email_extra' => $email2,
                'password' => $password,
            ]);
            
            Address::create([
                'type_id' => 1,
                'member_id' => $member->id,
                'country_code' => $country, 
                'state_code' => $state,
                'address_line_1' => $address,
                'city' => $city,
                'pincode' => $pincode
            ]);

            Phone::create([
                'member_id' => $member->id,
                'std_code' => $std,
                'landline' => $phone,
                'country_code' => $country_code,
                'mobile' => $mobile,
            ]);

            $individual = Individual::create([
                'member_id' => $member->id, 
                'membership_type_id' => 4,  // professional member 
                'salutation_id' => $salutation, 
                'first_name' => $fname,
                'middle_name' => $mname,
                'last_name' => $lname,
                'card_name' => $card_name,
                'gender' => $gender,
                'dob' => $dob
            ]);

            $professional = ProfessionalMember::create([
                'id' => $individual->id,
                'organisation' => $organisation,
                'designation' => $designation,
                'is_verified' => 1,
            ]);
            $professional->associating_institution_id = $individual->id; 
            $professional->save();

            $membership_period      = $row->membership_period;
            $paymentMode            = 3; //cash
            $amountPaid             = $row->amount;

            // 2nd arg is currency.. needs to be queried to put here
            $head = PaymentHead::getHead(ServicePeriod::getPeriodsByTypeAndDuration(4, $membership_period)->first()->id, ($country=='IND')? 1: 2 )->first();
            $payment = Payment::create([
                'paid_for' => $member->id, 
                'payment_head_id' => $head->id, 
                'service_id' => 1
            ]);
            $payment->date_of_effect = Carbon::now()->format('d/m/Y');
            $payment->save();

            // in case of split payments, for every narration that the institution has for this bulkPayment, make a corresponding journal for the member
            $journal = Journal::create([
                'payment_id' => $payment->id,
                'narration_id' => $bp->narration_id,
                'paid_amount' => $amountPaid, 
            ]);
            $journal->is_rejected = 0;  // since we had fired this function when we knew, this bulkPayment has balanced amount, so accepting the same journals for the member now.
            $journal->save();
            
            $request = RequestService::create([
                'service_id' => Service::getServiceIDByType('membership'),
                'payment_id' => $payment->id, 
                'member_id'  => $member->id
            ]);

            RequestAction::create([
                'request_id' => $request->id,
                'status' => ActionStatus::pending
            ]);

            RequestAction::create([
                'request_id' => $request->id,
                'status' => ActionStatus::approved
            ]);

            return $member;
        });
    }


}
