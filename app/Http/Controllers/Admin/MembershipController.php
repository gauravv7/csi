<?php

namespace App\Http\Controllers\Admin;

use App\AcademicMember;
use App\Admin;
use App\CsiChapter;
use App\Enums\ActionStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Individual;
use App\Institution;
use App\InstitutionType;
use App\Member;
use App\MembershipType;
use App\Payment;
use App\ProfessionalMember;
use App\RequestService;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Input;
use Laracasts\Flash\Flash;

class MembershipController extends Controller
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
    public function index(Request $request)
    {

        if(Gate::denies('view-all-memberships')){
            abort(403, 'action not allowed');
        }
        
        $rows = (Input::exists('row'))? (Input::get('row') < 5)?5:Input::get('row'): 15;          // how many rows for pagination
        $membership_types = MembershipType::lists('type','id')->put('5', 'nominee')->put('0', 'all');
        $institution_type = InstitutionType::lists('name', 'id');
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
        $it_selected = (Input::exists('it'))? $request->get('it'): array(); // institution type

        $verified_members = [];
        $not_verified_members = [];

        if($mt_selected){
            switch ($mt_selected) {
                case 1:
                    //academic, now apply it_selected filter
                    if(empty($it_selected) ){
                        $academics = AcademicMember::lists('id');
                    } else {
                        $academics = AcademicMember::getInInstitutionType($it_selected)->lists('id');
                    }
                    $institutions = Institution::getInIds($academics)->lists('member_id');
                    $users = Member::getInIds($institutions)->latest()->paginate($rows);
                    break;
                
                case 2: 
                    //non-academic institutions
                    $institutions = Institution::getAllNonAcademicInstitutions()->lists('member_id');
                    $users = Member::getInIds($institutions)->latest()->paginate($rows);
                    break;

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
                case 5:
                    //nominees
                    $individuals = Individual::getAllProfessionals()->lists('id');                    
                    $nominee=ProfessionalMember::whereIn('id',$individuals)->where('is_nominee','<>',ActionStatus::nothing)->lists('id');
                    
                    $individuals = Individual::whereIn('id',$nominee)->lists('member_id');
                    
                    $users = Member::getInIds($individuals)->latest()->paginate($rows);
                    break;
            }

        } else {
            if($verified || $not_verified){
                $users = Member::latest()->paginate($rows);
            } else {
                $users = Member::latest()->paginate($rows);
            }
        }

        // checking for membership validity
        foreach ($users as $key => $user) {
            $isMembershipPaymentValid = $user->checkMembershipPaymentValidity();
            $user->is_identity_verified = ($user->membership->type ==  "individual")? $user->getMembership->subType->is_verified: null;
            $user->profile = ($user->membership->type ==  "individual")? $user->getMembership->subType->proof_id: null;
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
                    if(CsiChapter::filterByStateName($search_text)->exists()){
                        $csi_chapter_id = CsiChapter::filterByStateName($search_text)->first()->id;
                        $users = Member::where('csi_chapter_id', $csi_chapter_id)->paginate();
                    } else{
                        Flash::info('No chapter found by such name');
                    }
                    break;
                case 7: //student-branch
                    break;

            }
        }       


    foreach ($users as $i => $user) {
         if( ($user->getMembership->membershipType->type == 'professional') && ($user->getMembership->subType->is_nominee==ActionStatus::approved)) {
            $payments = Payment::filterByServiceAndMember(1, $user->getMembership->subType->institution->member_id)->get();
        } else{
            $payments = Payment::filterByServiceAndMember(1, $user->id)->get();
        }
         if(!$payments->isEmpty()){
            foreach ($payments as $payment) { 
                $effective_date= $payment->date_of_effect;              
                $users[$i]->member_start_date = $payment->date_of_effect;
                if($payment->date_of_effect){
                    
                $users[$i]->member_end_date = $payment->date_of_effect->addYears($payment->paymentHead->servicePeriod->years);
                }
            } //foreach
        }
       
    }
    

        return view('backend.memberships.listing', compact('membership_types','users', 'institution_type', 'typeName', 'page', 'mt_selected', 'it_selected', 'verified', 'not_verified', 'rows', 'cat_selected', 'cat_select_options', 'search_text'));
    }

    public function institutionNominees($member_id)
    {
        $member=Member::find($member_id);
        $institution_id=$member->getMembership->id;       
        $institution_name=$member->getMembership->name;
        
        $members = ProfessionalMember::whereIn('is_nominee', [ActionStatus::pending, ActionStatus::approved,ActionStatus::cancelled])->where('associating_institution_id', $institution_id)->paginate();


        return view('backend.memberships.listing-admin-nominees', compact('institution_id','institution_name','statuses','checkbox_array','members','verified','not_verified','rows','page','fromDate','toDate'));
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
}
