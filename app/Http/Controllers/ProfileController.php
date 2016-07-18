<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\UpdateProfileIDRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Laracasts\Flash\Flash;
use App\Member;

class ProfileController extends Controller
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProfileIDRequest $request)
    {
        
        $profile_details = Auth::user()->user()->getMembership->subType;
        if($profile_details){
            //move student id batch to a location savely and then store data in db
            $profile_id = Input::file('profile_id');
            $profile_id->move(storage_path('uploads/student_profile_proofs'), $profile_details->proof_id);
            $profile_details->is_verified = -1;
            $profile_details->save();
            Flash::success('Identity proof uploaded successfully');
        } else{
            abort(501);
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

    public function profile($id)
    {
        if(intval($id)>1){
            $user = Member::find($id);
            $is_inline = true;
            return view('frontend.dashboard.profile', compact('user', 'is_inline'));
        } else{
            abort('404');
        }
    }
}
