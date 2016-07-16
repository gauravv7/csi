<?php

namespace App\Http\Controllers\Admin;

use App\AcademicMember;
use App\BulkPayment;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Individual;
use App\Institution;
use App\Member;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        //academic, now apply it_selected filter
        $academics = AcademicMember::lists('id');
        $academic_institutions = Institution::getInIds($academics)->lists('member_id');
        $academic_users = Member::getInIds($academic_institutions)->get();
        $not_verified_acadmic_members = array();
        $counter_academic = 0;
        // checking for membership validity
        foreach ($academic_users as $key => $user) {
            $isMembershipPaymentValid = $user->checkMembershipPaymentValidity();
            // see if the final amount is zero
            if(!$isMembershipPaymentValid){
                array_push($not_verified_acadmic_members, $key);
                $user->isPaymentBalanced = 0; // payments are not balanced
                $counter_academic++;
            }
        }

        //non-academic institutions
        $non_academic_institutions = Institution::getAllNonAcademicInstitutions()->lists('member_id');
        $non_academic_users = Member::getInIds($non_academic_institutions)->get();
        $not_verified_non_acadmic_members = array();
        $counter_non_academic = 0;
        // checking for membership validity
        foreach ($non_academic_users as $key => $user) {
            $isMembershipPaymentValid = $user->checkMembershipPaymentValidity();
            // see if the final amount is zero
            if(!$isMembershipPaymentValid){
                array_push($not_verified_non_acadmic_members, $key);
                $user->isPaymentBalanced = 0; // payments are not balanced
                $counter_non_academic++;
            }
        }
        //students
        $students = Individual::getAllStudents()->lists('member_id');
        $student_users = Member::getInIds($students)->get();
        $not_verified_student_members = array();
        $counter_student = 0;
        // checking for membership validity
        foreach ($student_users as $key => $user) {
            $isMembershipPaymentValid = $user->checkMembershipPaymentValidity();
            // see if the final amount is zero
            if(!$isMembershipPaymentValid){
                array_push($not_verified_student_members, $key);
                $user->isPaymentBalanced = 0; // payments are not balanced
                $counter_student++;
            }
        }
        //professionals
        $prof_individuals = Individual::getAllProfessionals()->lists('member_id');
        $prof_users = Member::getInIds($prof_individuals)->get();
        $not_verified_prof_members = array();
        $counter_prof = 0;
        // checking for membership validity
        foreach ($prof_users as $key => $user) {
            $isMembershipPaymentValid = $user->checkMembershipPaymentValidity();
            // see if the final amount is zero
            if(!$isMembershipPaymentValid){
                array_push($not_verified_prof_members, $key);
                $user->isPaymentBalanced = 0; // payments are not balanced
                $counter_prof++;
            }
        }

        
        // if($verified && !$not_verified){
        //     $users->forget($not_verified_members);
        // }
        // if(!$verified && $not_verified){
        //     $users->forget($verified_members);
        // }
        $counter_student_branch_req = AcademicMember::where('is_student_branch', -1)->count();
        $counter_bulk_payments_req  = BulkPayment::where('is_rejected', -1)->count();
        return view('backend.index', compact('counter_academic', 'counter_non_academic', 'counter_student', 'counter_prof', 'counter_student_branch_req', 'counter_bulk_payments_req'));
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
