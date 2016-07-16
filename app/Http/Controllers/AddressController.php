<?php

namespace App\Http\Controllers;

use App\AcademicMember;
use App\Address;
use App\AddressType;
use App\Country;
use App\CsiChapter;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\CreateAddressRequest;
use App\Http\Requests\UpdateAddressRequest;
use App\Member;
use App\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Laracasts\Flash\Flash;

class AddressController extends Controller
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
        $user = Auth::user()->user();
        $address_types = AddressType::whereNotIn('id', $user->addresses->lists('type_id'))->lists('type', 'id');
        if($address_types->isEmpty()){
            Flash::error("Sorry you don't have more address types to add");
            return redirect()->back();
        } else{
            $username = $user->getMembership->getName();
            $countries = Country::lists('name', 'alpha3_code');
            $states = State::where('country_code', 'IND')->lists('name', 'state_code');
            return view('frontend.dashboard.profile.address-create', compact('address_types', 'username', 'countries', 'states'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateAddressRequest $request)
    {
        Address::Create([ 
            'type_id' => Input::get('address-type'),
            'member_id' => Auth::user()->user()->id,
            'country_code' => Input::get('country'),
            'state_code' => Input::get('state'),
            'address_line_1' => Input::get('address'),
            'city' => Input::get('city'),
            'pincode' => Input::get('pincode'),
        ]);

        Flash::success('Address added successfully');
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
        $address  = Address::find($id);
        $username = $address->member->getMembership->getName();
        $entity   = ($address->member->membership->type == 'institutional')? 'institution-': 'individual-';
        if($address->member->getMembership->membershipType->type == 'academic'){
            $entity .= 'academic';
            $chapters = CsiChapter::where('csi_state_code', $address->state_code)->lists('name', 'id');
        } else if($address->member->getMembership->membershipType->type == 'non-academic'){
            $entity .= 'non-academic';
            $chapters = CsiChapter::where('csi_state_code', $address->state_code)->lists('name', 'id');
        } else if($address->member->getMembership->membershipType->type == 'student'){
            $entity .= 'student';
            $temp = AcademicMember::isStudentBranch()->get()->reverse();
            $stud_branch = array();
            foreach ($temp as $key) {
                $stud_branch[$key->id] = $key->institution->getName();
            }
        } else if($address->member->getMembership->membershipType->type == 'professional'){
            $entity .= 'professional';
            $chapters = CsiChapter::where('csi_state_code', $address->state_code)->lists('name', 'id');
        }
        $countries = Country::lists('name', 'alpha3_code');
        $states = State::where('country_code', 'IND')->lists('name', 'state_code');
        $isSingleStep = true;   // used in partAddress partial in backend
        
        return view('frontend.dashboard.profile.address-edit', compact('address', 'username', 'entity', 'isSingleStep', 'countries', 'states', 'chapters', 'stud_branch'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAddressRequest $request, $id)
    {   
        $address = Address::find($id);
        $member = $address->member;

        $address->city = $request->input('city');
        $address->pincode = $request->input('pincode');
        $address->address_line_1 = $request->input('address');
        $address->state_code = $request->input('state');
        $address->country_code = $request->input('country');
        
        $type = $address->member->getMembership->membershipType->type;
        
        $isSuccessful = DB::transaction( function($connection) use($type, $request, $member, $address) {
            if( ($type == 'academic') || ($type == 'non-academic') || ($type == 'professional') ){
               $member->csi_chapter_id = ($request->exists('chapter'))? $request->input('chapter'): $member->csi_chapter_id;
               $member->save();
            } else if( ($type == 'student') && ($request->exists('stud_branch')) ){
                $student_member = $member->getMembership->subType;
                $student_member->student_branch_id = $request->input('stud_branch');
                $student_member->save();
            }

            $address->save();
            return true;
        });
        
        if($isSuccessful){
            Flash::success('Address Updated Successfully');
        } else{
            Flash::error('Error while updating address... try again');
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
