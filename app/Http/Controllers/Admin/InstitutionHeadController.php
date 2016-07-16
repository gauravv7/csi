<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\UpdateInstitutionHeadRequest;
use App\Member;
use Illuminate\Http\Request;
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
    public function edit($id)
    {
        $member = Member::find($id)->getMembership;
        $salutation = $member->salutation_id;
        $isSingleStep = true;
        $entity = $member->member->getEntity();
        return view('backend.memberships.institution-head-edit', compact('member', 'entity', 'isSingleStep', 'salutation'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateInstitutionHeadRequest $request, $id)
    {
        $institution_head = Member::find($id)->getMembership;

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
