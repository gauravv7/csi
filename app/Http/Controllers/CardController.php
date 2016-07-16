<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Payment;
use Validator;
use Request;
use Auth;
use Input;
use Gate;
use Flash;
use PDF;

class CardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $individual = Auth::user()->user()->getMembership;
        $username = $individual->getName();
        if(!Gate::denies('is-user-payment-verified')){

            $card_name = $individual->card_name;
            $signature = ($individual->signature=="sample.png")? "": $individual->signature;
            $photo = ($individual->profile_photograph=="sample.png")? "": $individual->profile_photograph;
            $cid = $individual->member->getFullID();
            $cat = $individual->member->getEntity();
            $payment = Payment::filterByServiceAndMember(1, $individual->member_id)->first();
            $dof = $payment->date_of_effect->toFormattedDateString();
            $period = $payment->paymentHead->servicePeriod->name;
            $is_identity_verified = $individual->subType->is_verified;
            return view('frontend.dashboard.profile.printcard', compact('username', 'card_name', 'photo', 'signature', 'cid', 'cat', 'dof', 'period', 'is_identity_verified'));
        } else{
            return view('frontend.dashboard.profile.printcard', compact('username'));
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Download csi card.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function download()
    {
        $individual = Auth::user()->user()->getMembership;
        $username = $individual->getName();
        if(!Gate::denies('is-user-payment-verified')){

            $card_name = $individual->card_name;
            $signature = ($individual->signature=="sample.png")? "": $individual->signature;
            $photo = ($individual->profile_photograph=="sample.png")? "": $individual->profile_photograph;
            $cid = $individual->member->getFullAllotedID();
            $cat = $individual->member->getEntity();
            $payment = Payment::filterByServiceAndMember(1, $individual->member_id)->first();
            $dof = $payment->date_of_effect->toFormattedDateString();
            $period = $payment->paymentHead->servicePeriod->name;
            $is_identity_verified = $individual->subType->is_verified;
            $pdf = PDF::loadView('frontend.dashboard.profile.card-pdf-view', compact('username', 'card_name', 'photo', 'signature', 'cid', 'cat', 'dof', 'period', 'is_identity_verified'));
            return $pdf->download('card.pdf');
        } else{
            return view('frontend.dashboard.profile.printcard', compact('username'));
        }
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
    public function update(Request $request)
    {
        $user = Auth::user()->user()->getMembership;
        $validator = Validator::make(Input::all(), [
            'card_name' => 'required|string',
        ]);
        if ($validator->fails()) {
            return redirect()
                        ->back()
                        ->withErrors($validator)
                        ->withInput();
        }
        $user->card_name = Input::get('card_name');
        $user->save();
        Flash::success('Card Name updated');
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
}
