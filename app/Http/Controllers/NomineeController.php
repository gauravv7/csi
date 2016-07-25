<?php

namespace App\Http\Controllers;

use App;
use App\Enums\ActionStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\CreateNomineeRequest;
use App\Jobs\SendNomineeMembershipAcceptSms;
//use App\Jobs\SendNomineeMembershipRejectSms;
use App\Jobs\SendCSINomineeMembershipAcceptSms;
use App\Jobs\SendCSINomineeMembershipRejectSms;
use App\Jobs\SendNomineeMembershipRemoveSms;
use App\Jobs\SendNomineeMembershipRenewSms;
use App\Journal;
use App\Member;
use App\Narration;
use App\Payment;
use App\ProfessionalMember;
use App\Individual;
use App\RequestService;
use App\Service;
use Carbon\Carbon;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Input;
use Mail;

class NomineeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $verified='';
        $not_verified='';
        $statuses = [
            1 => 'Accepted',
            -1 => 'Pending',
            0 => 'Cancelled'
        ];

        $rows = (Input::exists('rows'))?abs(Input::get('rows')): 15;         // how many rows for pagination
        $page = (Input::exists('page'))? abs(Input::get('page')): 1;
        $status=Input::get('status');// current page
        $fromDate=(Input::exists('request_from_date'))?(Input::get('request_from_date')):'';
        $toDate=(Input::exists('request_to_date'))?(Input::get('request_to_date')):'';

        if(count($status)){
            $checkbox_array=Input::get('status');
            $members = ProfessionalMember::whereIn('is_nominee', $status)->where('associating_institution_id', Auth::user()->user()->getMembership->id)->paginate();


        }else{
            $checkbox_array=array();
            $members = ProfessionalMember::whereIn('is_nominee', [ActionStatus::pending, ActionStatus::approved])->where('associating_institution_id', Auth::user()->user()->getMembership->id)->paginate();


        }


        //$members = ProfessionalMember::whereIn('is_nominee', [ActionStatus::pending, ActionStatus::approved])->where('associating_institution_id', Auth::user()->user()->getMembership->id)->paginate();

        $from_date_records = array();
        $to_date_records = array();
        foreach ($members as $key => $member) {
            if($fromDate){
                if($member->created_at <= $fromDate ){ //lower bound
                    array_push($from_date_records, $key);
                }
            }
            if($toDate){
                if($member->created_at >= $toDate ){ //upper bound
                    array_push($to_date_records, $key);
                }
            }
            if(!empty($fromDate)){
                //we need intersection of both the filters
                $members->forget($from_date_records);
            }
            if(!empty($toDate)){
                $members->forget($to_date_records);
            }
        }
        return view('frontend.dashboard.listing-nominees', compact('statuses','checkbox_array','members','verified','not_verified','rows','page','fromDate','toDate'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateNomineeRequest $request)
    {
        $email = Input::get('email');
        $member = Member::where('email', $email)->first();
        // Payment::filterByServiceAndMember(1, Auth::user()->user()->id)->get();
        //nominees can be a professional individual only
        if($member->getMembership->membershipType->type == 'professional'){
            $prof = $member->getMembership->subType;
            if( $prof->hasAssociatingInstitution()->exists() && $prof->is_nominee== ActionStatus::approved ){
                Flash::error('The user has already been nominated. Please try again');
            } else{
                $prof->associating_institution_id = Auth::user()->user()->getMembership->id;
                $prof->is_nominee = ActionStatus::approved;
                $prof->nominee_effective = Carbon::now()->format('d/m/Y');
                $count = ProfessionalMember::where('associating_institution_id',$prof->associating_institution_id)->where('is_nominee', 1)->count();
                if(!$member->alloted_id){
                    $member->alloted_id = Payment::getNextAllotedID();
                    $member->save();
                }
                if($count<3){
                    if( Auth::user()->user()->checkMembershipPaymentValidity() ){
                        if( $prof->save() ){
                            $nameOfInst = Auth::user()->user()->getMembership->getName();
                            $emailOfInst = Auth::user()->user()->email;
                            $emailOfHeadInst = Auth::user()->user()->email;
                            $effective_date = $prof->nominee_effective;
                            if(App::environment('production')){
                                $this->dispatch(new SendNomineeMembershipAcceptSms($member->email, $member->getMembership->getMobile(), $effective_date, $nameOfInst));
                                Mail::queue('frontend.emails.nominee-membership-accept', ['name' => $member->getMembership->getName(), 'email' => $member->email, 'inst' => $nameOfInst, 'date' => $effective_date], function($message) use($member, $emailOfInst, $emailOfHeadInst){
                                    $message->to($member->email)->subject('CSI-Nominee Membership Registeration');
                                    $message->bcc($emailOfInst)->subject('CSI-Nominee Membership Registeration');
                                    $message->bcc($emailOfHeadInst)->subject('CSI-Nominee Membership Registeration');
                                });
                            }
                            Flash::success('Nominee created successfully');
                        }
                    } else{
                        Flash::error('you are not authorized for this action');
                    }
                } else{
                    Flash::error('No more than 3 nominees can be added');
                }
            }
        } else{
            Flash::error('Nominated member of category "'.$member->getFormattedEntity().'" is not authorized for this action');
        }
        return redirect()->back();
    }

    public function accept($id)
    {
        if(intval($id)>0) {
            $prof_member = ProfessionalMember::find($id);
            $member = $prof_member->individual->member;
            // Payment::filterByServiceAndMember(1, Auth::user()->user()->id)->get();
            //nominees can be a professional individual only
            if(Auth::user()->user()->getMembership->id== $prof_member->associating_institution_id) {
                if ($member->getMembership->membershipType->type == 'professional') {

                    if ($prof_member->hasAssociatingInstitution()->exists() && $prof_member->is_nominee == ActionStatus::approved) {
                        Flash::error('The user has already been nominated. Please try again');
                    } else {
                        //$prof_member->associating_institution_id = $prof_member->associating_institution_id;
                        $prof_member->is_nominee = ActionStatus::approved;
                        $prof_member->nominee_effective = Carbon::now()->format('d/m/Y');
                        $count = ProfessionalMember::where('associating_institution_id', $prof_member->associating_institution_id)->where('is_nominee', ActionStatus::approved)->count();
                        if (!$member->alloted_id) {
                            $member->alloted_id = Payment::getNextAllotedID();
                            $member->save();
                        }
                        if ($count < 3) {
                            if (Auth::user()->user()->checkMembershipPaymentValidity()) {
                                if ($prof_member->save()) {
                                    $name=$prof_member->individual->getName();
                                    $email=$member->email;
                                    $mobile=$member->phone();
                                    $nameOfInst = $prof_member->institution->getName();
                                    $emailOfInst = $prof_member->institution->member->email;
                                    $emailOfHeadInst = $prof_member->institution->email;
                                    $effective_date = $prof_member->nominee_effective;
                                    if (App::environment('production')) {
                                        $this->dispatch(new SendCSINomineeMembershipAcceptSms($email, $mobile, $effective_date, $nameOfInst));
                                        Mail::queue('frontend.emails.nominee-requests.nominee-accept', ['name' => $name, 'email' => $email, 'associating_institution' => $nameOfInst, 'date' => $effective_date], function ($message) use ($email, $emailOfInst, $emailOfHeadInst) {
                                            $message->to($email)->subject('CSI-Nominee Membership Registeration');
                                            $message->bcc($emailOfInst)->subject('CSI-Nominee Membership Registeration');
                                            $message->bcc($emailOfHeadInst)->subject('CSI-Nominee Membership Registeration');
                                        });
                                    }
                                    Flash::success('Nominee accepted successfully');
                                }
                            } else {
                                Flash::error('you are not authorized for this action');
                            }
                        } else {
                            Flash::error('No more than 3 nominees can be added');
                        }
                    }
                } else {
                    Flash::error('Nominated member of category "' . $member->getFormattedEntity() . '" is not authorized for this action');
                }
            }
        }
        return redirect()->back();
    }

    public function renew($id)
    {
        if(intval($id)>0) {
            $prof_member = ProfessionalMember::find($id);
            $member = $prof_member->individual->member;
            // Payment::filterByServiceAndMember(1, Auth::user()->user()->id)->get();
            //nominees can be a professional individual only
            if(Auth::user()->user()->getMembership->id== $prof_member->associating_institution_id) {
                if ($member->getMembership->membershipType->type == 'professional') {

                    if ($prof_member->hasAssociatingInstitution()->exists() &&  $prof_member->is_nominee == ActionStatus::approved) {
                        Flash::error('The user has already been nominated. Please try again');
                    } else {
                        //$prof_member->associating_institution_id = $prof_member->associating_institution_id;
                        $prof_member->is_nominee = ActionStatus::approved;
                        $prof_member->nominee_effective = Carbon::now()->format('d/m/Y');
                        $count = ProfessionalMember::where('associating_institution_id', $prof_member->associating_institution_id)->where('is_nominee', ActionStatus::approved)->count();
                        if (!$member->alloted_id) {
                            $member->alloted_id = Payment::getNextAllotedID();
                            $member->save();
                        }
                        if ($count < 3) {
                            if (Auth::user()->user()->checkMembershipPaymentValidity()) {
                                if ($prof_member->save()) {
                                    $name=$prof_member->individual->getName();
                                    $email=$member->email;
                                    $mobile=$member->phone();
                                    $nameOfInst = $prof_member->institution->getName();
                                    $emailOfInst = $prof_member->institution->member->email;
                                    $emailOfHeadInst = $prof_member->institution->email;
                                    $effective_date = $prof_member->nominee_effective;
                                    if (App::environment('production')) {
                                        $this->dispatch(new SendNomineeMembershipRenewSms($email, $mobile, $effective_date, $nameOfInst));
                                        Mail::queue('frontend.emails.nominee-requests.nominee-renew', ['name' => $name(), 'email' => $email, 'associating_institution' => $nameOfInst, 'date' => $effective_date], function ($message) use ($email, $emailOfInst, $emailOfHeadInst) {
                                            $message->to($email)->subject('CSI-Nominee Membership Registeration');
                                            $message->bcc($emailOfInst)->subject('CSI-Nominee Membership Registeration');
                                            $message->bcc($emailOfHeadInst)->subject('CSI-Nominee Membership Registeration');
                                        });
                                    }
                                    Flash::success('Nominee renewed successfully');
                                }
                            } else {
                                Flash::error('you are not authorized for this action');
                            }
                        } else {
                            Flash::error('No more than 3 nominees can be added');
                        }
                    }
                } else {
                    Flash::error('Nominated member of category "' . $member->getFormattedEntity() . '" is not authorized for this action');
                }
            }
        }
        return redirect()->back();
    }
    public function reject($id)
    {
        if(intval($id)>0){

            $prof_member = ProfessionalMember::find($id);
            $member=$prof_member->individual->member;
            if (!$prof_member) {
                Flash::error('Nominee doesnot exists');
            } else {
                if(Auth::user()->user()->getMembership->id== $prof_member->associating_institution_id) {
                    if ($prof_member->is_nominee == ActionStatus::pending && $prof_member->associating_institution_id == Auth::user()->user()->getMembership->id) {
                        $prof_member->is_nominee = ActionStatus::nothing;
                        if ($prof_member->save()) {
                            $nameOfInst = $prof_member->institution->getName();
                            $emailOfInst = $prof_member->institution->member->email;
                            $emailOfHeadInst = $prof_member->institution->email;
                            $effective_date = $prof_member->nominee_effective;
                            if (App::environment('production')) {
                                $this->dispatch(new SendCSINomineeMembershipRejectSms($member->email, $member->getMembership->getMobile(), $effective_date, $nameOfInst));
                                Mail::queue('frontend.emails.nominee-requests.nominee-reject', ['name' => $member->getMembership->getName(), 'email' => $member->email, 'associating_institution' => $nameOfInst, 'date' => $effective_date], function ($message) use ($member, $emailOfInst, $emailOfHeadInst) {
                                    $message->to($member->email)->subject('CSI-Nominee Membership Registeration');
                                    $message->bcc($emailOfInst)->subject('CSI-Nominee Membership Registeration');
                                    $message->bcc($emailOfHeadInst)->subject('CSI-Nominee Membership Registeration');
                                });
                            }
                            Flash::success('Nominee removed successfully');
                        }
                    } else {
                        Flash::error('Not authorized');
                    }
                }
                else {
                    Flash::error('Not authorized');
                }
            }
        }

        return redirect()->back();
    }
    public function remove($id)
    {
        if(intval($id)>0){

            $user = ProfessionalMember::find($id);
            $member=$user->individual->member;
            if (!$user) {
                Flash::error('Nominee doesnot exists');
            } else {
                if(Auth::user()->user()->getMembership->id== $user->associating_institution_id) {
                    if ($user->is_nominee == ActionStatus::approved && $user->associating_institution_id == Auth::user()->user()->getMembership->id) {
                        $user->is_nominee = ActionStatus::cancelled;
                        if ($user->save()) {
                            $nameOfInst = $user->institution->getName();
                            $emailOfInst = $user->institution->member->email;
                            $emailOfHeadInst = $user->institution->email;
                            $effective_date = $user->nominee_effective;
                            if (App::environment('production')) {
                                $this->dispatch(new SendNomineeMembershipRemoveSms($member->email, $member->getMembership->getMobile(), $effective_date, $nameOfInst));
                                Mail::queue('frontend.emails.nominee-requests.nominee-remove', ['name' => $member->getMembership->getName(), 'email' => $member->email, 'associating_institution' => $nameOfInst, 'date' => $effective_date], function ($message) use ($member, $emailOfInst, $emailOfHeadInst) {
                                    $message->to($member->email)->subject('CSI-Nominee Membership Registeration');
                                    $message->bcc($emailOfInst)->subject('CSI-Nominee Membership Registeration');
                                    $message->bcc($emailOfHeadInst)->subject('CSI-Nominee Membership Registeration');
                                });
                            }
                            Flash::success('Nominee removed successfully');
                        }
                    } else {
                        Flash::error('Not authorized');
                    }
                }
                else{
                    Flash::error('Not authorized');

                }
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
    public function destroy($id)
    {
        if(intval($id)>0){

            $user = ProfessionalMember::find($id);
            if (!$user) {
                Flash::error('Nominee doesnot exists');
            } else {
                if(Auth::user()->user()->getMembership->id== $user->associating_institution_id) {
                    if ($user->is_nominee == ActionStatus::approved && $user->associating_institution_id == Auth::user()->user()->getMembership->id) {
                        $user->is_nominee = ActionStatus::nothing;
                        $user->associating_institution_id = null;
                        if ($user->save()) {
//                            $nameOfInst = Auth::user()->user()->getMembership->getName();
//                            $emailOfInst = Auth::user()->user()->email;
//                            $emailOfHeadInst = Auth::user()->user()->getMembership->email;
//                            $member = $user->individual->member;
//                            if (App::environment('production')) {
//                                $this->dispatch(new SendNomineeMembershipRejectSms($member->email, $member->getMembership->getMobile(), $nameOfInst));
//                                Mail::queue('frontend.emails.nominee-membership-reject', ['name' => $member->getMembership->getName(), 'email' => $member->email, 'inst' => $nameOfInst], function ($message) use ($member, $emailOfInst, $emailOfHeadInst) {
//                                    $message->to($member->email)->subject('CSI-Nominee Membership Registeration');
//                                    $message->bcc($emailOfInst)->subject('CSI-Nominee Membership Registeration');
//                                    $message->bcc($emailOfHeadInst)->subject('CSI-Nominee Membership Registeration');
//                                });
//                            }
                            Flash::success('Nominee removed successfully');
                        }
                    } else {
                        Flash::error('Not authorized');
                    }
                }
                else{
                    Flash::error('Not authorized');

                }
            }
        }

        return redirect()->back();
    }
}
