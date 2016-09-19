<?php

namespace App\Http\Controllers\Admin;

use App;
use App\AcademicMember;
use App\Enums\ActionStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\StudentBranchDeclineRequest;
use App\Institution;
use App\Jobs\SendStudentBranchRequestApproveSms;
use App\Jobs\SendStudentBranchRequestDeclineSms;
use App\RequestAction;
use App\RequestService;
use App\Service;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Laracasts\Flash\Flash;
use Mail;

class StudentBranchController extends Controller
{
 
    private $client = null;

    public function __construct(){
        $this->client = new Client();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rows = (Input::exists('row'))? (Input::get('row') < 5)?5:Input::get('row'): 15;          // how many rows for pagination
        $page = (Input::exists('page'))? abs(Input::get('page')): 1;        // current page
        $verified = (Input::exists('v'))? Input::get('v'): false;           // verified members
        $not_verified = (Input::exists('nv'))? Input::get('nv'): false;     // non verified members


        if($verified && !$not_verified){
            $branches = AcademicMember::where('is_student_branch', 1)->orderBy('created_at', 'desc')->paginate($rows);
        }
        if(!$verified && $not_verified) {
            $branches = AcademicMember::where('is_student_branch', -1)->orderBy('created_at', 'desc')->paginate($rows);
        } else{
            $branches = AcademicMember::latest()->paginate($rows);
        }

        return view('backend.student-branches.listing', compact('branches', 'page', 'rows'));
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
    public function store(Request $request, $id)
    {
        if(intval($id)){
            $academic_inst = AcademicMember::find($id);
            if($academic_inst){
                $request = RequestService::filterStudentBranchesByMemberId($academic_inst->institution->member_id)->first();
                if($request){
                    $user=$academic_inst->institution->member;
                    $academic_inst->is_student_branch = 1;
                    $academic_inst->save();

                    RequestAction::create([
                        'request_id' => $request->id,
                        'status' => ActionStatus::approved
                    ]);

                    //mail and sms
                    if(App::environment('production')){
                        $this->dispatch(new SendStudentBranchRequestApproveSms($request->id, $user->getFullAllotedID(), $user->email, $user->getMembership->mobile));
                        $data = [
                            'data' => [
                                "template" => "student_branch/student-branch-approve",
                                "subject" => 'CSI-Request Student Branch Approved',
                                "to" => $user->email,
                                "payload" => ['name' => $user->getMembership->getName(), 'email' => $user->email, 'rid' => $request->id, 'mid' => $user->getFullAllotedID()]
                            ]
                        ];
                        if($user->membership_id==1){
                            $data['data']['cc']= $user->getMembership->email;
                        }
                        $response = $this->client->requestAsync('POST', env('CUSTOM_MAIL_URL'),[
                            'json' => $data,
                        ]);
                    }

                    Flash::success('Request approved');
                } else{
                    Flash::error('unauthorized request');
                }
            } else{
                Flash::error('Requested user is invalid');
            }
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
    public function destroy(StudentBranchDeclineRequest $request, $id)
    {
        if(intval($id)){
            $academic_inst = AcademicMember::find($id);
            if($academic_inst){
                $request = RequestService::filterStudentBranchesByMemberId($academic_inst->institution->member_id)->first();
                if($request){
                    $user=$academic_inst->institution->member;
                    $academic_inst->is_student_branch = 0;
                    $academic_inst->rejection_reason = Input::get('rejection-reason');
                    $academic_inst->save();

                    RequestAction::create([
                        'request_id' => $request->id,
                        'status' => ActionStatus::cancelled
                    ]);

                    //mail and sms
                    if(App::environment('production')){
                        $this->dispatch(new SendStudentBranchRequestDeclineSms($request->id, $user->email, $user->getMembership->mobile));
                        $data = [
                            'data' => [
                                "template" => "student_branch/student-branch-decline",
                                "subject" => 'CSI-Request Student Branch declined',
                                "to" => $user->email,
                                "payload" => ['name' => $user->getMembership->getName(), 'email' => $user->email, 'rid' => $request->id, 'reason' => $academic_inst->rejection_reason]
                            ]
                        ];
                        if($user->membership_id==1){
                            $data['data']['cc']= $user->getMembership->email;
                        }
                        $response = $this->client->requestAsync('POST', env('CUSTOM_MAIL_URL'),[
                            'json' => $data,
                        ]);
                    }

                    Flash::success('Request declined');
                } else{
                    Flash::error('unauthorized request');
                }
            } else{
                Flash::error('Requested user is invalid');
            }
        }
        return redirect()->back();
    }

        /**
     * View Reject Reason of the Student Branch.
     * id is payment-id
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     */
    public function viewRejectionReason($id)
    {
        $inst = AcademicMember::find($id);
        return $inst->rejection_reason;
    }
}
