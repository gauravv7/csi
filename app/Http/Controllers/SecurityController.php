<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Message;
use App\Http\Requests;
use App\Http\Requests\CreateResetPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\UpdateMemberPasswordRequest;
use App\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Laracasts\Flash\Flash;
use Password;

class SecurityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user()->user();
        $username = $user->getMembership->getName();
        return view('frontend.dashboard.profile.password', compact('username'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('frontend.auth.forget-password');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateResetPasswordRequest $request)
    {
        $email = Input::get('email');
        Password::user()->sendResetLink(['email'=>$email], function($message){
            $message->subject('CSI - Reset Password');
        });
        Flash::success('Link sent to reset password, please check your mail');
        return redirect('login');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($type, $token)
    {
        if(DB::table('password_resets')->where('type', '=', $type)->where('token', '=', $token)->exists()){
            return view('frontend.auth.reset-password', compact('token', 'type'));
        } else{
            abort(404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(ResetPasswordRequest $request, $type, $token)
    {
        $password = Input::get('password');
        $user = DB::table('password_resets')->where('type', '=', $type)->where('token', '=', $token)->first();
        $member = Member::where('email', $user->email)->first();
        DB::table('password_resets')->where('type', '=', $type)->where('token', '=', $token)->delete();
        $member->password = Hash::make($password);
        $member->save();
        Flash::success('password reset Successfully');
        return redirect('/login');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMemberPasswordRequest $request)
    {
        $oldpswd    = Input::get('oldpswd');
        $pswd       = Input::get('pswd');
        $user       = Auth::user()->user();

        if( Hash::check($oldpswd, $user->password) ){
            $user->password = Hash::make($pswd);
            $user->save();
            Flash::success('Password Updated Successfully');
            return redirect()->route('userDashboard');
        } else {
            Flash::error('Invalid password, Please fill in the correct password');
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
