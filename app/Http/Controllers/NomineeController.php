<?php

namespace App\Http\Controllers;

use App;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\CreateNomineeRequest;
use App\Jobs\SendNomineeMembershipAcceptSms;
use App\Jobs\SendNomineeMembershipRejectSms;
use App\Journal;
use App\Member;
use App\Narration;
use App\Payment;
use App\ProfessionalMember;
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
        $members = ProfessionalMember::where('is_nominee', 1)->where('associating_institution_id', Auth::user()->user()->getMembership->id)->get();
        return view('frontend.dashboard.listing-nominees', compact('members'));
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
            if( $prof->hasAssociatingInstitution()->exists() && $prof->is_nominee ){
                Flash::error('The user has already been nominated. Please try again');
            } else{
                $prof->associating_institution_id = Auth::user()->user()->getMembership->id;
                $prof->is_nominee = 1;
                $prof->nominee_effective = Carbon::now()->format('d/m/Y');
                $count = ProfessionalMember::where('associating_institution_id', 2)->where('is_nominee', 1)->count();
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
        if(intval($id)){
            $user = ProfessionalMember::find($id);
            if(!$user){
                Flash::error('Nominee doesnot exists');
            } else{
                if($user->is_nominee && $user->associating_institution_id == Auth::user()->user()->getMembership->id){
                    $user->is_nominee = 0;
                    if($user->save()){
                        $nameOfInst = Auth::user()->user()->getMembership->getName();
                        $emailOfInst = Auth::user()->user()->email;
                        $emailOfHeadInst = Auth::user()->user()->getMembership->email;
                        $member = $user->individual->member;
                        if(App::environment('production')){
                            $this->dispatch(new SendNomineeMembershipRejectSms($member->email, $member->getMembership->getMobile(), $nameOfInst));
                            Mail::queue('frontend.emails.nominee-membership-reject', ['name' => $member->getMembership->getName(), 'email' => $member->email, 'inst' => $nameOfInst], function($message) use($member, $emailOfInst, $emailOfHeadInst){
                                $message->to($member->email)->subject('CSI-Nominee Membership Registeration');
                                $message->bcc($emailOfInst)->subject('CSI-Nominee Membership Registeration');
                                $message->bcc($emailOfHeadInst)->subject('CSI-Nominee Membership Registeration');
                            });
                        }
                        Flash::success('Nominee removed successfully');
                    }
                } else{
                    Flash::error('Not authorized');
                }
            }
        }

        return redirect()->back();
    }
}
