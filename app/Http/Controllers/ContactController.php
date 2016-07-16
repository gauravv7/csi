<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\UpdateContactRequest;
use App\Member;
use App\Phone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;
use Laracasts\Flash\Flash;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function edit()
    {
        $member = Auth::user()->user();
        $isSingleStep = true;   // used in partAddress partial in backend
        $entity   = ($member->membership->type == 'institutional')? 'institution-': 'individual-';
        if($member->getMembership->membershipType->type == 'academic'){
            $entity .= 'academic';
        } else if($member->getMembership->membershipType->type == 'non-academic'){
            $entity .= 'non-academic';
        } else if($member->getMembership->membershipType->type == 'student'){
            $entity .= 'student';
        } else if($member->getMembership->membershipType->type == 'professional'){
            $entity .= 'professional';
        }

        return view('frontend.dashboard.profile.contact-edit', compact('member', 'entity', 'isSingleStep'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateContactRequest $request)
    {
        $member = Member::find($id);
        $phone = $member->phone;
        $errors = new MessageBag;
        $isMemberUpdated = true;
        $isPhoneUpdated = true;
        if($request->exists('email1') && $member->email != $request->input('email1') ){
            if (Member::where('email', '=', $request->input('email1'))->exists()) {
                // error, email exists
                $errors->add('email1', 'Primary Email Already Exists. Please choose other email');
            } else{
                $member->email = Input::get('email1');
                if(!$member->save()){
                    $isMemberUpdated = false;
                }
            }
        }
        if($request->exists('email2') && $member->email != $request->input('email2') ){
            if (Member::where('email_extra', '=', $request->input('email2'))->exists()) {
                // error, email exists
                $errors->add('email2', 'Secondary Email Already Exists. Please choose other email');
            } else{
                $member->email_extra = $request->input('email2');
                if(!$member->save()){
                    $isMemberUpdated = false;
                }
            }
        }
        $phone->std_code =  ($request->exists('std'))? $request->input('std'): $phone->std_code;
        $phone->landline = ($request->exists('phone'))? $request->input('phone'): $phone->landline;
        if($request->exists('country-code')){
            $phone->country_code = $request->input('country-code');
            if(!$phone->save()){
               $isPhoneUpdated = false;
            }
        }
        if($request->exists('mobile')){
            $phone->mobile = $request->input('mobile');
            if(!$phone->save()){
                $isPhoneUpdated = false;
            }
        }

        if( $isMemberUpdated && $isPhoneUpdated && $errors->isEmpty()){
            Flash::success('Updated Successfully');
        } else{
            //error
            Flash::error('Error while updating');
        }
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
