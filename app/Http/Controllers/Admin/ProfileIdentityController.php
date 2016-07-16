<?php

namespace App\Http\Controllers\Admin;

use App;
use App\AcademicMember;
use App\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Individual;
use App\Institution;
use App\InstitutionType;
use App\Jobs\SendMembershipIdentityAcceptSms;
use App\Jobs\SendMembershipIdentityRejectSms;
use App\Member;
use App\MembershipType;
use App\Payment;
use App\RequestService;
use App\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Input;
use Laracasts\Flash\Flash;
use Mail;

class ProfileIdentityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if(Gate::denies('view-all-memberships')){
            abort(403, 'action not allowed');
        }
        
        $rows = (Input::exists('row'))? (Input::get('row') < 5)?5:Input::get('row'): 15;          // how many rows for pagination
        $membership_types = MembershipType::where('membership_id','>', 1)->lists('type','id')->put('0', 'all');
        $cat_select_options = [
            0=>'all',
            1=>'member id',
            2=>'email',
            3=>'request id',
            // 4=>'region',
            // 5=>'state',
            6=>'chapter',
            // 7=>'student-branch'
        ];

        // filters
        $cat_selected = (Input::exists('cat'))? intval(Input::get('cat')): 5;       // category
        $page = (Input::exists('page'))? abs(Input::get('page')): 1;        // current page
        $mt_selected = (Input::exists('mt'))? Input::get('mt'): 0;          // membership type
        $search_text = (Input::exists('st'))? Input::get('st'): "";         // search text with category selected
        $verified = (Input::exists('v'))? Input::get('v'): false;           // verified members
        $not_verified = (Input::exists('nv'))? Input::get('nv'): false;     // non verified members
        $p_not_verified = (Input::exists('pnv'))? Input::get('pnv'): null; // profile pending members
        $p_rejected = (Input::exists('prv'))? Input::get('prv'): null;     // profile non verified members
        $p_verified = (Input::exists('pv'))? Input::get('pv'): null;       // profile verified members

        $verified_members = [];
        $not_verified_members = [];
        $profile_pending_members = [];
        $profile_rejected_members = [];
        $profile_accepted_members = [];

        if($mt_selected){
            switch ($mt_selected) {
                case 3:
                    //students
                    $individuals = Individual::getAllStudents()->lists('member_id');
                    $users = Member::getInIds($individuals)->latest()->paginate($rows);
                    break;

                case 4:
                    //professionals
                    $individuals = Individual::getAllProfessionals()->lists('member_id');
                    $users = Member::getInIds($individuals)->latest()->paginate($rows);
                    break;
            }

        } else {
            if($verified || $not_verified){
                $users = Member::where('membership_id', '>', '1')->latest()->paginate($rows);
            } else {
                $users = Member::where('membership_id', '>', '1')->latest()->paginate($rows);
            }
        }

        // checking for membership validity
        foreach ($users as $key => $user) {
            $isMembershipPaymentValid = $user->checkMembershipPaymentValidity();
            // see if the final amount is zero
            if($isMembershipPaymentValid){
                array_push($verified_members, $key);
                $user->isPaymentBalanced = 1;
            } else{
                array_push($not_verified_members, $key);
                $user->isPaymentBalanced = 0; // payments are not balanced
            }
        }
        
        if($verified && !$not_verified){
            $users->forget($not_verified_members);
        }
        if(!$verified && $not_verified){
            $users->forget($verified_members);
        }
        // checking for profile id validity
        foreach ($users as $key => $user) {
            $is_verified = $user->getMembership->subType->is_verified;
            $user->profile_id = $user->getMembership->subType->is_verified;
            $user->profile = $user->getMembership->subType->proof_id;
            // see if the final amount is zero
            if($is_verified == -1){
                array_push($profile_pending_members, $key);
            } else if($is_verified == 0){
                array_push($profile_rejected_members, $key);
            } else if($is_verified == 1){
                array_push($profile_accepted_members, $key);
            }
        }

        if($p_not_verified && !$p_rejected && !$p_verified){
            $users->forget($profile_rejected_members);
            $users->forget($profile_accepted_members);
        } else if(!$p_not_verified && $p_rejected && !$p_verified){
            $users->forget($profile_pending_members);
            $users->forget($profile_accepted_members);
        } else if(!$p_not_verified && !$p_rejected && $p_verified){
            $users->forget($profile_rejected_members);
            $users->forget($profile_pending_members);
        } else if($p_not_verified && !$p_rejected && $p_verified){
            $users->forget($profile_rejected_members);
        } else if($p_not_verified && $p_rejected && !$p_verified){
            $users->forget($profile_accepted_members);
        } else if(!$p_not_verified && $p_rejected && $p_verified){
            $users->forget($profile_pending_members);
        }

        if($cat_selected){
            switch($cat_selected){
                case 1: // member id
                    $users = Member::where('id', $search_text)->paginate();
                    break;
                case 2: // email
                    $users = Member::where('email', $search_text)->paginate();
                    break;
                case 3: // request id
                    $mid = RequestService::find(intval($search_text))->member_id;
                    $users = Member::where('id', $mid)->paginate();
                    break;
                case 4: // region
                    break;
                case 5: // state
                    break;
                case 6: //chapter
                    $users = Member::where('csi_chapter_id', intval($search_text))->paginate();
                    break;
                case 7: //student-branch
                    break;

            }
        }

        return view('backend.memberships.profile-id-listing', compact('membership_types','users', 'page', 'mt_selected', 'verified', 'not_verified', 'p_verified', 'p_rejected', 'p_not_verified', 'rows', 'cat_selected', 'cat_select_options', 'search_text'));
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
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function accept($id)
    {
        if(intval($id)>1){
            $user = Member::find($id);
            $profile = $user->getMembership->subType;
            $profile->is_verified = 1;
            $profile->save();
            if(App::environment('production')){
                $rid = RequestService::requestsByMemberIdAndServiceId($user->id, Service::getServiceIDByType('membership'))->first()->id;
                $this->dispatch(new SendMembershipIdentityAcceptSms($rid, $user->email, $user->getMembership->getMobile(), $user->getFormattedEntity()));
                Mail::queue('frontend.emails.membership-identity-accept', ['name' => $user->getMembership->getName(), 'email' => $user->email, 'rid' => $rid, 'category' => $user->getFormattedEntity()], function($message) use($user){
                    $message->to($user->email)->subject('CSI-Membership Registeration Identity Proof');
                });
            }
            Flash::success('profile verified successfully');
        }
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function reject($id)
    {
        if(intval($id)>1){
            $user = Member::find($id);
            $profile = $user->getMembership->subType;
            $profile->is_verified = 0;
            $profile->save();
            if(App::environment('production')){
                $rid = RequestService::requestsByMemberIdAndServiceId($user->id, Service::getServiceIDByType('membership'))->first()->id;
                $this->dispatch(new SendMembershipIdentityRejectSms($rid, $user->email, $user->getMembership->getMobile(), $user->getFormattedEntity()));
                Mail::queue('frontend.emails.membership-identity-reject', ['name' => $user->getMembership->getName(), 'email' => $user->email, 'rid' => $rid, 'category' => $user->getFormattedEntity()], function($message) use($user){
                    $message->to($user->email)->subject('CSI-Membership Registeration Identity Proof');
                });
            }
            Flash::success('profile rejected successfully');
        }
        return redirect()->back();
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function profile($id)
    {
        if(intval($id)>1){
            $user = Member::find($id);
            $is_inline = true;
            return view('backend.memberships.profile', compact('user', 'is_inline'));
        } else{
            abort('404');
        }
    }
}
