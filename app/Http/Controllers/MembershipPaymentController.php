<?php

namespace App\Http\Controllers;

use App\Address;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\CreateMembershipPaymentRequest;
use App\Jobs\SendRegisterSms;
use App\Journal;
use App\Narration;
use App\Payment;
use App\PaymentHead;
use App\PaymentMode;
use App\ServicePeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Laracasts\Flash\Flash;
use Mail;
use App;

class MembershipPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user()->user();
        $entity = $user->getEntity();
        $payments = Payment::filterByServiceAndMember(1, $user->id)->get();
        $checkMembershipPaymentValidity = $user->checkMembershipPaymentValidity();
        $isPaymentBalanced = 1;
        // see if the final amount is zero
        if(!$checkMembershipPaymentValidity){
            $isPaymentBalanced = 0; // payment are not balanced
        }
        return view('frontend.dashboard.membership.payments', compact('user', 'payments', 'journals', 'isPaymentBalanced', 'entity'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($pid)
    {
        $payModes = PaymentMode::lists('name', 'id');

        $user = Auth::user()->user();
        $entity = $user->getEntity();
        $payment = Payment::find($pid);
        $membership_period = $payment->paymentHead->servicePeriod->years;
        $paymentMode = (Input::exists('paymentMode'))? Input::get('paymentMode'): '';
        $tno = (Input::exists('tno'))? Input::get('tno'): '';
        $drawn = (Input::exists('drawn'))? Input::get('drawn'): '';
        $bank = (Input::exists('bank'))? Input::get('bank'): '';
        $branch = (Input::exists('branch'))? Input::get('branch'): '';
        $amountPaid = (Input::exists('amountPaid'))? Input::get('amountPaid'): abs($payment->getPayableDiff());

        return view('frontend.dashboard.membership.create-membership-settling-payment', compact('entity', 'payment', 'payModes', 'membershipPeriods', 'membership_period', 'paymentMode', 'tno', 'drawn', 'bank', 'branch', 'amountPaid'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateMembershipPaymentRequest $request, $pid, $mode)
    {
        $user = Auth::user()->user();

        if ($user){
            if($mode == 'offline'){
                if($this->storeOfflinePayment($pid)){
                    $paidDiff = Payment::find($pid)->getPayableDiff();

                    if($paidDiff == 0){
                        // success
                        $isPayableBalanced = true;
                    } else if($paidDiff < 0){
                        // wait for admin to settle the payments 
                        $isPayableBalanced = false;
                    } else if($paidDiff > 0) {
                        // ask for split payment
                        $isPayableBalanced = false;
                    }
                    $entity = $user->getEntity();
                    if ( ( $entity == 'institution-academic') || ( $entity == 'institution-non-academic')) {
                        $name = $user->getMembership->getName();
                        $email = $user->email;
                        $aid = $user->getMembership->membershipType->prefix."-".$user->id;
                        if(App::environment('production')){
                            $this->dispatch(new SendRegisterSms($aid, $email, $user->getMembership->mobile));
                            Mail::queue('frontend.emails.institution_register', ['name' => $name, 'email' => $email, 'aid' => $aid], function($message) use($user){
                                $message->to($user->email)->subject('CSI-Membership'); 
                                $message->cc($user->getMembership->email, $user->getMembership->head_name);
                            });
                        }
                        return View('frontend.register.register_success_institution', compact('name', 'email', 'aid', 'isPayableBalanced'));
                    } else if ( ( $entity == 'individual-student') || ( $entity == 'individual-professional') ) {
                        $name = $user->getMembership->getName();
                        $email = $user->email;
                        $aid = $user->getMembership->membershipType->prefix."-".$user->id;
                        if(App::environment('production')){
                            $phone = $user->phone->first();
                            $mobile = $phone->mobile;
                            $this->dispatch(new SendRegisterSms($aid, $email, $mobile));
                            Mail::queue('frontend.emails.individual_register', ['name' => $name, 'email' => $email, 'aid' => $aid], function($message) use($user){
                                $message->to($user->email)->subject('CSI-Membership'); 
                            });
                        }
                        return View('frontend.register.register_success_individual', compact('name', 'email', 'aid', 'isPayableBalanced'));
                    }
                } else{
                    Flash::error('Some Error Occured, Please try again');
                    return redirect()->back();
                }
            } else if($mode == 'online'){
                // online methods
            }
        }
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
    public function storeOfflinePayment($pid){
        $var = DB::transaction(function($connection) use($pid) {

                $paymentMode            = Input::get('paymentMode');
                $tno                    = Input::get('tno');
                $drawn                  = Input::get('drawn');
                $bank                   = Input::get('bank');
                $branch                 = Input::get('branch');
                $paymentReciept         = Input::file('paymentReciept');
                $amountPaid             = Input::get('amountPaid');




                // 2nd arg is currency.. needs to be queried to put here
                $head = PaymentHead::getHead(Payment::find($pid)->paymentHead->servicePeriod->id, 1)->first();
                $finalAmount = ( $head->amount + (($head->amount*$head->serviceTaxClass->tax_rate)/100) );
                $member = Auth::user()->user();
                $narration = Narration::create([ 
                    'payer_id' => $member->id, 
                    'mode' => $paymentMode, 
                    'transaction_number' => $tno, 
                    'bank' => $bank, 
                    'branch' => $branch, 
                    'date_of_payment' => $drawn, 
                    'drafted_amount' => $finalAmount
                ]);

                $filename = $member->id.'-'.$narration->id.'.';
                $filename.=$paymentReciept->getClientOriginalExtension();
                $paymentReciept->move(storage_path('uploads/payment_proofs'), $filename);
                $narration->proof = $filename;
                $narration->save();

                $journal = Journal::create([
                    'payment_id' => $pid,
                    'narration_id' => $narration->id,
                    'paid_amount' => $amountPaid, 
                ]);

                return true;
        });  
        return $var;
    }

    public function viewRejectionReason($id, $narration_id) {
        $journal = Journal::filterByPaymentAndNarration($id, $narration_id)->first();
        return $journal->rejection_reason;
    }

}
