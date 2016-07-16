<?php

namespace App\Http\Controllers\Admin;

use App;
use App\Enums\ActionStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\UpdateNarrationRequest;
use App\Jobs\SendVerificationRejectSms;
use App\Jobs\SendVerificationSms;
use App\Journal;
use App\Member;
use App\Narration;
use App\Payment;
use App\RequestAction;
use App\RequestService;
use App\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Laracasts\Flash\Flash;
use Mail;
use Response;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        if($id!=null || intval($id)>0){
            $user = Member::find(intval($id));
            if(!$user){ 
                Flash::error('Specified user doesnot exist');
                return redirect()->back();
            }
            $payments = Payment::filterByServiceAndMember(1, $user->id)->get();
            $checkMembershipPaymentValidity = $user->checkMembershipPaymentValidity();
            $isPaymentBalanced = 1;
            // see if the final amount is zero
            if(!$checkMembershipPaymentValidity){
                $isPaymentBalanced = 0; // payment are not balanced
            }
                
            
            return view('backend.memberships.payment', compact('user', 'payments', 'isPaymentBalanced'));
        }
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
    public function store(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'amountPaid' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return redirect()
                        ->back()
                        ->withErrors($validator)
                        ->withInput();
        }
        

        $result = DB::transaction(function($connection) use($id) {
            $paymentMode            = 3; //cash
            $tno                    = 'self';
            $drawn                  = Carbon::now()->format('d/m/Y');
            $bank                   = 'self';
            $branch                 = 'self';
            $amountPaid             = Input::get('amountPaid');
            $finalAmount            = Payment::find($id)->calculatePayable();

            $narration = Narration::create([ 
                'payer_id' => 1, 
                'mode' => $paymentMode, 
                'transaction_number' => $tno, 
                'bank' => $bank, 
                'branch' => $branch, 
                'date_of_payment' => $drawn, 
                'drafted_amount' => $finalAmount, 
                'proof' => 'self.jpg'
            ]);

            $journal = Journal::create([
                'payment_id' => $id,
                'narration_id' => $narration->id,
                'paid_amount' => $amountPaid,
            ]);
            $journal->is_rejected = 0;
            $journal->save();

            return true;
        });
        if($result){
            Flash::success('Settled Successfully');
        } else{
            Flash::danger('Some Problem occured, try again');
        }
        return redirect()->back();
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
    public function update(UpdateNarrationRequest $request, $id, $nid)
    {
        
        $journal = Journal::filterByPaymentAndNarration($id, $nid)->first();
        $narration = $journal->narration;
        $narration->bank = Input::get('bank');
        $narration->branch = Input::get('branch');
        $narration->drafted_amount = Input::get('amountPaid');
        $journal->paid_amount = Input::get('amountPaid');
        $narration->save();
        $journal->save();
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
     * View Reject Reason of the journal.
     * id is payment-id
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     */
    public function viewRejectionReason($id, $narration_id)
    {
        $journal = Journal::filterByPaymentAndNarration($id, $narration_id)->first();
        return $journal->rejection_reason;
    }

    /**
     * Reject the journal.
     * id is payment-id
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     */
    public function reject($id, $narration_id)
    {
        
        if(empty(trim(Input::get('rejection-reason')))){
            Flash::error('Cannot leave Reason of Rejection empty');
            return redirect()->back();
        }

        $journal = Journal::filterByPaymentAndNarration($id, $narration_id)->first();
        $journal->is_rejected = 1;
        $journal->rejection_reason = Input::get('rejection-reason');   // no validations yet
        $journal->save();

        $request = RequestService::requestsByMemberIdAndServiceId($journal->payment->owner->id, Service::getServiceIDByType('membership'))->first();
        RequestAction::create([
            'request_id' => $request->id,
            'status' => ActionStatus::cancelled
        ]);

        $user = $journal->payment->owner;
        $entity = $user->getEntity();
        $mt = $user->getMembership->membershipType->type;
        $period = $journal->payment->paymentHead->servicePeriod->name;
        $aid = $user->getFullID();
        $email = $user->email;

        if(App::environment('production')){
            if ( ( $entity == 'institution-academic') || ( $entity == 'institution-non-academic') ) {
                $this->dispatch(new SendVerificationRejectSms($aid, $email, $user->getMembership->getMobile()));
                Mail::queue('frontend.emails.institution_reject_payment', ['name' => $user->getMembership->getName(), 'aid' => $aid, 'reason' => $journal->rejection_reason ], function($message) use($user){
                    $message->to($user->email)->subject('CSI-Membership Payment Rejected'); 
                    if($user->membership_id == 1){
                        $message->cc($user->getMembership->email)->subject('CSI-Membership Payment Rejected'); 
                    }
                });
            } else if ( ( $entity == 'individual-student') || ( $entity == 'individual-professional') ) {
                $this->dispatch(new SendVerificationRejectSms($aid, $email, $user->getMembership->getMobile()));
                Mail::queue('frontend.emails.individual_reject_payment', ['name' => $user->getMembership->getName(), 'aid' => $aid, 'reason' => $journal->rejection_reason ], function($message) use($user) {
                    $message->to($user->email)->subject('CSI-Membership Payment Rejected'); 
                });
            }
        }

        Flash::success('Rejected Successfully');
        return redirect()->back();
    }

    /**
     * Accept the journal.
     * id is payment-id
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     */
    public function accept($id, $narration_id)
    {
        DB::transaction(function($connection) use($id, $narration_id) {
            $journal = Journal::filterByPaymentAndNarration($id, $narration_id)->first();
            $journal->is_rejected = 0;
            $journal->save();

            if($journal->payment->AcceptMembershipPayment()){
                Flash::success('payment accepted Successfully');

                $request = RequestService::requestsByMemberIdAndServiceId($journal->payment->owner->id, Service::getServiceIDByType('membership'))->first();
                RequestAction::create([
                    'request_id' => $request->id,
                    'status' => ActionStatus::approved
                ]);
                
                $user = $journal->payment->owner;
                $entity = $user->getEntity();
                $mt = $user->getMembership->membershipType->type;
                $period = $journal->payment->paymentHead->servicePeriod->name;
                $aid = $user->getFullID();
                $cid = $user->getFullAllotedID();
                $email = $user->email;

                if(App::environment('production')){
                    if ( ( $entity == 'institution-academic') || ( $entity == 'institution-non-academic') ) {
                        $this->dispatch(new SendVerificationSms($cid, $email, $user->getMembership->getMobile()));
                        Mail::queue('frontend.emails.institution_verify', ['membership_type' => $mt, 'period' => $period, 'name' => $user->getMembership->getName(), 'email' => $email, 'aid' => $aid, 'cid' => $cid], function($message) use($user){
                            $message->to($user->email)->subject('CSI-Membership verified'); 
                            if($user->membership_id == 1){
                                $message->cc($user->getMembership->email)->subject('CSI-Membership verified'); 
                            }
                        });
                    } else if ( ( $entity == 'individual-student') || ( $entity == 'individual-professional') ) {
                        $this->dispatch(new SendVerificationSms($cid, $email, $user->getMembership->getMobile()));
                        Mail::queue('frontend.emails.individual_verify', ['membership_type' => $mt, 'period' => $period, 'name' => $user->getMembership->getName(), 'email' => $email, 'aid' => $aid, 'cid' => $cid], function($message) use($user) {
                            $message->to($user->email)->subject('CSI-Membership verified'); 
                        });
                    }
                }
            }
            Flash::success('Accepted Successfully');
        });
        return redirect()->back();
    }

    public function getResource($resource){
        $response = null;
        if(is_string($resource)){
            
            if('narration-update-info' == $resource){
                $pid = Input::get('pid');
                $nid = Input::get('nid');
                $journal = Journal::filterByPaymentAndNarration($pid, $nid)->first();
                $data = [
                    'amountPaid' => $journal->paid_amount,
                    'bank' => $journal->narration->bank,
                    'branch' => $journal->narration->branch,
                ];
            } 
            
            $response = Response::json($data, 200);
        } else{
            $response = Response::json(array('errors' => $e->getMessage()), 500);
        }
        return $response;
    }
}
