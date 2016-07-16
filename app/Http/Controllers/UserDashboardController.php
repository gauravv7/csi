<?php

namespace App\Http\Controllers;

use App\CsiRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Payment;
use App\StudentMember;
use Auth;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class UserDashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {   
        $id = Auth::user()->user()->id;
        if($id!=null || intval($id)>0){
            $user = Auth::user()->user();
            $payments = Payment::filterByServiceAndMember(1, $user->id)->get();
            $unSettledMembershipPayment = 0;
            if(!$payments->isEmpty()){
                foreach ($payments as $payment) {
                    if( ($paidDiff = $payment->getPayableDiff()) != 0){
                        $unSettledMembershipPayment = 1;
                    }
                }
            } else{
                $unSettledMembershipPayment = 1;
            }
            $isProfileVerified = null;
            if($user->membership->type == "individual") {
                $isProfileVerified = $user->getMembership->subType->is_verified;
            }
            return view('frontend.dashboard.home', compact('user', 'paidDiff', 'unSettledMembershipPayment', 'isProfileVerified'));
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
}
