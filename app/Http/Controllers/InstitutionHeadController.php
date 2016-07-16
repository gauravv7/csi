<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\UpdateInstitutionHeadRequest;
use App\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laracasts\Flash\Flash;

class InstitutionHeadController extends Controller
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
        $member = Auth::user()->user()->getMembership;
        $salutation = $member->salutation_id;
        $isSingleStep = true;
        $entity   = (Auth::user()->user()->membership->type == 'institutional')? 'institution-': 'individual-';
        if($member->membershipType->type == 'academic'){
            $entity .= 'academic';
        } else if($member->membershipType->type == 'non-academic'){
            $entity .= 'non-academic';
        } else if($member->membershipType->type == 'student'){
            $entity .= 'student';
        } else if($member->membershipType->type == 'professional'){
            $entity .= 'professional';
        }
        return view('frontend.dashboard.profile.institution-head-edit', compact('member', 'entity', 'isSingleStep', 'salutation'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateInstitutionHeadRequest $request)
    {
        $institution_head = Auth::user()->user()->getMembership;

        $institution_head->head_name        =$request->input('headName');
        $institution_head->head_designation =$request->input('headDesignation');
        $institution_head->salutation_id    =$request->input('salutation');
        $institution_head->email            =$request->input('headEmail');
        $institution_head->country_code     =$request->input('country-code');
        $institution_head->mobile           =$request->input('mobile');

        if($institution_head->save() ){
            Flash::success('Updated Successfully');
        } else{
            // error 
            Flash::success('Error while Updating');
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
