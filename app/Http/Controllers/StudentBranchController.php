<?php

namespace App\Http\Controllers;

use App;
use App\Enums\ActionStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Jobs\SendStudentBranchRequestSms;
use App\RequestAction;
use App\RequestService;
use App\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mail;
use Laracasts\Flash\Flash;

class StudentBranchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('frontend.dashboard.confirmStudentBranch');
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
        
        $sid = Service::getServiceIDByType('student branch');
        $user = Auth::user()->user();
        $mid = Auth::user()->user()->id;
        if(!RequestService::requestsByMemberIdAndServiceId($mid, $sid)->exists()){
            
            $request = DB::transaction(function($connection) use($user, $sid, $mid){
                $inst = $user->getMembership->subType;
                $inst->is_student_branch = -1;
                $inst->save();

                $request = RequestService::create([
                    'service_id' => $sid,
                    'member_id'  => $mid
                ]);

                RequestAction::create([
                    'request_id' => $request->id,
                    'status' => ActionStatus::pending
                ]);

                return $request;
            });

            //mail and sms
            if(App::environment('production')){
                $this->dispatch(new SendStudentBranchRequestSms($request->id, $user->getFullAllotedID(), $request->member->email, $request->member->getMembership->mobile));
                Mail::queue('frontend.emails.student-branch-request', ['name' => $request->member->getMembership->getName(), 'email' => $request->member->email, 'rid' => $request->id, 'mid' => $user->getFullAllotedID()], function($message) use($user){
                    $message->to($user->email)->subject('CSI-Request Student Branch');
                    if($user->membership_id==1){
                        $message->cc($user->getMembership->email)->subject('CSI-Request Student Branch');
                    }
                });
            }
            Flash::success('Your Request for being a student branch has been sent');
        } else{
            Flash::error('Your Request for being a student branch has already been registered once before, cannot apply for student branch again');
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
