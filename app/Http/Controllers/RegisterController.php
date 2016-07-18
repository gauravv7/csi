<?php

namespace App\Http\Controllers;

use App\AcademicMember;
use App\Address;
use App\Country;
use App\CsiChapter;
use App\Enums\ActionStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\CreatePaymentRequest;
use App\Http\Requests\CreateRegisterRequest;
use App\Individual;
use App\Institution;
use App\InstitutionType;
use App\Jobs\SendMembershipRegisterFormSms;
use App\Jobs\SendRegisterSms;
use App\Journal;
use App\Member;
use App\Narration;
use App\Payment;
use App\PaymentHead;
use App\PaymentMode;
use App\Phone;
use App\ProfessionalMember;
use App\RequestAction;
use App\RequestService;
use App\Salutation;
use App\Service;
use App\ServicePeriod;
use App\ServiceTaxClass;
use App\State;
use App\StudentMember;
use DB;
use Hash;
use App;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Mail;
use Laracasts\Flash\Flash;
use Log;
use Response;

class RegisterController extends Controller
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
    public function create($entity) {

        if(!Auth::user()->user()) {
            $name = $entity;
            $membershipPeriods = [];
            $institutionTypes = [];
            $titles = [];
            $isSingleStep = false;
            $titles = Salutation::all();

            $country_code = (Input::exists('country'))? Input::get('country'): '';
            $countries = Country::lists('name', 'alpha3_code')->prepend('Please select a country', 'invalid');

            if( ( $entity == 'institution-academic') ) {
                $institutionTypes = InstitutionType::getInstitutionTypesById(1)->get();
                return view('frontend.register.institution-academic', compact('entity', 'institutionTypes', 'titles','countries', 'country_code', 'isSingleStep'));
            } else if( ( $entity == 'institution-non-academic' ) ){
                return view('frontend.register.institution-non-academic', compact('entity', 'titles','countries', 'country_code', 'isSingleStep'));
            } else if( ( $entity == 'individual-student' ) ){
                return view('frontend.register.individual-student', compact('entity', 'titles','countries', 'country_code', 'isSingleStep'));
            } else if( ( $entity == 'individual-professional' ) ){
                return view('frontend.register.individual-professional', compact('entity', 'titles', 'countries', 'country_code', 'isSingleStep'));
            } else if( ( $entity == 'nominee' ) ){
                $institutions = Institution::where('id', '>', 1)->get();
                $verified_institutions = collect([]);
                //checking for institution payment validity
                foreach ($institutions as $key => $inst){
                    // see if the final amount is zero
                    if($inst->member->checkMembershipPaymentValidity()) {

                        $verified_institutions->put($inst->id, $inst->getName());
                    }
                }

                $verified_institutions->prepend('Please select an associating institution', 'invalid');

                return view('frontend.register.nominee', compact('verified_institutions','entity', 'titles', 'countries', 'country_code', 'isSingleStep'));
            }
        } else{
            Flash::info('you are already registered');
            return redirect()->route('userDashboard');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createSubmitToPayments(Request $request, $entity)
    {
        $payModes = PaymentMode::lists('name', 'id');

        $user = Auth::user()->user();
        $membership_period = (Input::exists('membership-period'))? Input::get('membership-period'): '';
        $paymentMode = (Input::exists('paymentMode'))? Input::get('paymentMode'): '';
        $tno = (Input::exists('tno'))? Input::get('tno'): '';
        $drawn = (Input::exists('drawn'))? Input::get('drawn'): '';
        $bank = (Input::exists('bank'))? Input::get('bank'): '';
        $branch = (Input::exists('branch'))? Input::get('branch'): '';
        $amountPaid = (Input::exists('amountPaid'))? Input::get('amountPaid'): '';

        if ($user){
            if( ( $entity == 'institution-academic') ) {
                $membershipPeriods = ServicePeriod::getPeriodsByType(1)->get();
            } else if( ( $entity == 'institution-non-academic' ) ){
                $membershipPeriods = ServicePeriod::getPeriodsByType(2)->get();
            } else if( ( $entity == 'individual-student' ) ){
                $membershipPeriods = ServicePeriod::getPeriodsByType(3)->get();
            } else if( ( $entity == 'individual-professional' ) ){
                $membershipPeriods = ServicePeriod::getPeriodsByType(4)->get();
            }
            return view('frontend.register.payments.create-payment', compact('entity', 'payModes', 'membershipPeriods', 'membership_period', 'paymentMode', 'tno', 'drawn', 'bank', 'branch', 'amountPaid'));
        } else{
            Flash::error('Oops! There was a techncical error, please try again');
            return redirect()->back();
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function submitToPayments(CreateRegisterRequest $request, $entity)
    {

        $payModes = PaymentMode::lists('name', 'id');

        $user = null;

        $membership_period = (Input::exists('membership-period'))? Input::get('membership-period'): '';
        $paymentMode = (Input::exists('paymentMode'))? Input::get('paymentMode'): '';
        $tno = (Input::exists('tno'))? Input::get('tno'): '';
        $drawn = (Input::exists('drawn'))? Input::get('drawn'): '';
        $bank = (Input::exists('bank'))? Input::get('bank'): '';
        $branch = (Input::exists('branch'))? Input::get('branch'): '';
        $amountPaid = (Input::exists('amountPaid'))? Input::get('amountPaid'): '';
        $str_password = str_random(15);
        $password = (strcasecmp(env('APP_ENV', 'development'), 'production')==0)? Hash::make($str_password): Hash::make("1234");

        if ( ( $entity == 'institution-academic') ) {
            $user = $this->storeAcademicInstitutionMemberData($password);
        } else if ( ( $entity == 'institution-non-academic') ) {
            $user = $this->storeNonAcademicInstitutionMemberData($password);
        } else if ( ( $entity == 'individual-student') ) {
            $user = $this->storeStudentIndividualMemberData($password);
        } else if ( ( $entity == 'individual-professional') ) {
            $user = $this->storeProfessionalIndividualMemberData($password);
        } else if ( ( $entity == 'nominee') ) {
            $user1 = $this->storeNomineeMemberData($password);
        }

        //What to do for Nominee Now
        if ( ( $entity == 'nominee') ){
            $user=Member::find($user1[0]);
            $prof_member=ProfessionalMember::find($user1[1]);
        }






        if ($user){

            Auth::user()->login($user);
            if(App::environment('production')){
                $rid = RequestService::requestsByMemberIdAndServiceId($user->id, Service::getServiceIDByType('membership'))->first()->id;
                $this->dispatch(new SendMembershipRegisterFormSms($rid, $user->email, $user->getMembership->getMobile(), $entity, $str_password));
                Mail::queue('frontend.emails.membership-register-form', ['name' => $user->getMembership->getName(), 'email' => $user->email, 'rid' => $rid, 'category' => $user->getFormattedEntity(), 'password' => $str_password], function($message) use($user){
                    $message->to($user->email)->subject('CSI-Membership Registeration');
                    if($user->membership_id==1){
                        $message->cc($user->getMembership->email)->subject('CSI-Membership Registeration');
                    }
                });
            }
            $country_code = Address::getRegisteredAddress($user->id)->lists('country_code');

            if( ( $entity == 'institution-academic') ) {
                $membershipPeriods = ServicePeriod::getPeriodsByType(1)->get();
            } else if( ( $entity == 'institution-non-academic' ) ){
                $membershipPeriods = ServicePeriod::getPeriodsByType(2)->get();
            } else if( ( $entity == 'individual-student' ) ){
                $membershipPeriods = ServicePeriod::getPeriodsByType(3)->get();
            } else if( ( $entity == 'individual-professional' ) ){
                $membershipPeriods = ServicePeriod::getPeriodsByType(4)->get();
            } else if($entity=='nominee'){
                $name=$user->getMembership->getName();
                $email=$user->email;
                $aid=$user->getFullID();
                $isPayableBalanced=true;
                return View('frontend.register.register_success_csi_nominee', compact('name', 'email', 'aid', 'isPayableBalanced','prof_member'));
            }
            return view('frontend.register.payments.create-payment', compact('entity', 'payModes', 'membershipPeriods', 'membership_period', 'paymentMode', 'tno', 'drawn', 'bank', 'branch', 'amountPaid'));
        } else{
            Flash::error('Oops! There was a techncical error, please try again');
            return redirect()->back();
        }

    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePaymentRequest $request, $entity, $mode)
    {
        $user = Auth::user()->user();

        if ($user){
            if($mode == 'offline'){
                $payment_id = $this->storeOfflinePayment($user);
                $payment = Payment::find($payment_id);
                $paidDiff = $payment->getPayableDiff();

                if($paidDiff == 0){
                    // success
                    $isPayableBalanced = true;
                } else if($paidDiff < 0){
                    // wait for admin to settle the payments 
                    $isPayableBalanced = false;
                } else if($paidDiff > 0) {
                    // ask for split payment
                    $isPayableBalanced = false;
                }

                if ( ( $entity == 'institution-academic') || ( $entity == 'institution-non-academic')) {
                    $name = $user->getMembership->getName();
                    $email = $user->email;
                    $aid = $user->getFullID();
                    if(App::environment('production')){
                        $this->dispatch(new SendRegisterSms($aid, $email, $user->getMembership->mobile));
                        Mail::queue('frontend.emails.institution_register', ['name' => $name, 'email' => $email, 'aid' => $aid], function($message) use($user){
                            $message->to($user->email)->subject('CSI-Membership');
                            $message->cc($user->getMembership->email, $user->getMembership->head_name);
                        });
                    }
                    return View('frontend.register.register_success_institution', compact('name', 'email', 'aid', 'isPayableBalanced'));
                } else if ( ( $entity == 'individual-student') || ( $entity == 'individual-professional') ) {
                    $name = $user->getMembership->getName();
                    $email = $user->email;
                    $aid = $user->getFullID();
                    if(App::environment('production')){
                        $phone = $user->phone->first();
                        $mobile = $phone->mobile;
                        $this->dispatch(new SendRegisterSms($aid, $email, $mobile));
                        Mail::queue('frontend.emails.individual_register', ['name' => $name, 'email' => $email, 'aid' => $aid], function($message) use($user){
                            $message->to($user->email)->subject('CSI-Membership');
                        });
                    }
                    return View('frontend.register.register_success_individual', compact('name', 'email', 'aid', 'isPayableBalanced'));
                }
            } else if($mode == 'online'){
                // online methods
            }
        }
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

    public function getResource($resource){

        if(is_string($resource)){

            if('amount' == $resource){
                $country_code = Address::getRegisteredAddress(Auth::user()->user()->id)->lists('country_code')->first();
                $mem_period = Input::get('mem_period');

                // hard coding the currency ID to query payment-heads
                if('IND' == $country_code){
                    $currency_id = 1;
                } else{
                    $currency_id = 2;
                }
                Log::info('In getResource: '.$country_code.$mem_period);
                $head = PaymentHead::getHead($mem_period, $currency_id)->first();

                $amount = $head->amount;
                $service_tax = ServiceTaxClass::find($head->service_tax_class_id)->tax_rate;
                $total = ($amount + (($amount*$service_tax)/100));
                $data = [
                    'amount' => $head->getFormattedAmount(),
                    'service_tax' => $service_tax,
                    'sum' => $total,
                    'total' => $head->getFormattedCalculatedAmount($total)
                ];

            } else if('states' == $resource){
                $country_code = Input::get('code');
                Log::info('In getResource for states: '.$country_code);
                // querying with states of india and not regions > states;
                $states = State::where('country_code', 'like', $country_code)->orderBy('name', 'asc')->get(['state_code', 'name'])->toarray();
                Log::info('In getResource for states: typeof '.gettype($states));

                $data = $states;

            } else if('branches' == $resource){
                $state_code = Input::get('code');
                Log::info('In getResource for branches: '.$state_code);
                $chapters = CsiChapter::where('csi_state_code', $state_code)->get();
                $collection = new \Illuminate\Database\Eloquent\Collection;
                $result = new \Illuminate\Database\Eloquent\Collection;
                foreach ($chapters as $chapter) {
                    $members = Member::where('csi_chapter_id', $chapter->id)->where('membership_id', 1)->get();
                    if(!$members->isEmpty()){
                        $collection = $members->filter(function ($item) {
                            if($item->getMembership->membership_type_id == 1) {
                                if($item->getMembership->subType->is_student_branch==1) {
                                    return $item;
                                }
                            }
                        });
                    }
                }
                if($collection->isEmpty()){
                    $data = "null";
                } else{
                    foreach ($collection as $member) {
                        $arr = [];
                        $arr['member_id'] = $member->getMembership->subType->id;
                        $arr['name'] = $member->getMembership->getName();
                        $result->add($arr);
                    }
                    $data = $result->sortBy('name')->toarray();
                }
            } else if('chapters' == $resource){
                $state_code = Input::get('code');
                Log::info('In getResource for chapters: '.$state_code);
                $chapters = CsiChapter::where('csi_state_code', $state_code)->orderBy('name', 'asc')->get(['id', 'name'])->toarray();
                Log::info('In getResource for chapters: typeof '.gettype($chapters));

                $data = $chapters;
            } else if('institutions' == $resource){

            } else if('country_dial_code' == $resource){
                $country_code = Input::get('code');
                Log::info('In getResource for states: '.$country_code);
                // querying with states of india and not regions > states;
                $dial_code = Country::where('alpha3_code', 'like', $country_code)->get(['dial_code']);
                Log::info('In getResource for states: typeof '.gettype($dial_code));

                $data = $dial_code;
            }

            $response = Response::json($data, 200);
        } else{
            $response = Response::json(array('errors' => $e->getMessage()), 500);
        }
        return $response;
    }

    private function storeAcademicInstitutionMemberData($password) {

        $var = DB::transaction(function($connection) use($password){

            $institution_type       = Input::get('institution_type');
            $nameOfInstitution      = Input::get('nameOfInstitution');

            $country                = Input::get('country');
            $state                  = Input::get('state');
            $chapter                = Input::get('chapter');
            $address                = Input::get('address');
            $city                   = Input::get('city');
            $pincode                = Input::get('pincode');
            $email1                 = Input::get('email1');
            $email2                 = Input::get('email2');
            $std                    = Input::get('std');
            $phone                  = Input::get('phone');

            $salutation             = Input::get('salutation');
            $headName               = Input::get('headName');
            $headDesignation        = Input::get('headDesignation');
            $headEmail              = Input::get('headEmail');
            $country_code           = Input::get('country-code');
            $mobile                 = Input::get('mobile');

            $member = new Member;

            $num = $country_code.'-'.$mobile;

            $member->membership_id = 1;
            $membership_type = 1;
            $member->csi_chapter_id = $chapter;
            $member->email = $email1;
            $member->email_extra = $email2;
            $member->password = $password;

            $member->save();

            Address::create([
                'type_id' => 1,
                'member_id' => $member->id,
                'country_code' => $country,
                'state_code' => $state,
                'address_line_1' => $address,
                'city' => $city,
                'pincode' => $pincode
            ]);

            Phone::create([
                'member_id' => $member->id,
                'std_code' => $std,
                'landline' => $phone
            ]);

            $institution = Institution::create([
                'member_id' => $member->id,
                'membership_type_id' => $membership_type,
                'salutation_id' => $salutation,
                'name' => $nameOfInstitution,
                'head_name' => $headName,
                'head_designation' => $headDesignation,
                'email' => $headEmail,
                'country_code' => $country_code,
                'mobile' => $mobile,
            ]);


            $academic_member = AcademicMember::create([
                'id' => $institution->id,
                'institution_type_id' => $institution_type
            ]);

            $request = RequestService::create([
                'service_id' => Service::getServiceIDByType('membership'),
                'member_id'  => $member->id
            ]);

            RequestAction::create([
                'request_id' => $request->id,
                'status' => ActionStatus::pending
            ]);

            return $member;
        });

        return $var;

    }

    private function storeNonAcademicInstitutionMemberData($password) {

        $var = DB::transaction(function($connection) use($password){

            $nameOfInstitution      = Input::get('nameOfInstitution');

            $country                = Input::get('country');
            $state                  = Input::get('state');
            $chapter                = Input::get('chapter');
            $address                = Input::get('address');
            $city                   = Input::get('city');
            $pincode                = Input::get('pincode');
            $email1                 = Input::get('email1');
            $email2                 = Input::get('email2');
            $std                    = Input::get('std');
            $phone                  = Input::get('phone');

            $salutation             = Input::get('salutation');
            $headName               = Input::get('headName');
            $headDesignation        = Input::get('headDesignation');
            $headEmail              = Input::get('headEmail');
            $country_code           = Input::get('country-code');
            $mobile                 = Input::get('mobile');

            $member = new Member;

            $num = $country_code.'-'.$mobile;

            $member->membership_id = 1; //institution
            $membership_type = 2;   // non-academic
            $member->csi_chapter_id = $chapter;
            $member->email = $email1;
            $member->email_extra = $email2;
            $member->password = $password;

            $member->save();

            Address::create([
                'type_id' => 1,
                'member_id' => $member->id,
                'country_code' => $country,
                'state_code' => $state,
                'address_line_1' => $address,
                'city' => $city,
                'pincode' => $pincode
            ]);

            Phone::create([
                'member_id' => $member->id,
                'std_code' => $std,
                'landline' => $phone
            ]);

            $institution = Institution::create([
                'member_id' => $member->id,
                'membership_type_id' => $membership_type,
                'salutation_id' => $salutation,
                'name' => $nameOfInstitution,
                'head_name' => $headName,
                'head_designation' => $headDesignation,
                'email' => $headEmail,
                'country_code' => $country_code,
                'mobile' => $mobile,
            ]);

            $request = RequestService::create([
                'service_id' => Service::getServiceIDByType('membership'),
                'member_id'  => $member->id
            ]);

            RequestAction::create([
                'request_id' => $request->id,
                'status' => ActionStatus::pending
            ]);

            return $member;
        });

        return $var;
    }

    private function storeProfessionalIndividualMemberData($password){
        $var = DB::transaction(function($connection) use($password){

            $salutation = Input::get('salutation');
            $fname = Input::get('fname');
            $mname = Input::get('mname');
            $lname = Input::get('lname');
            $card_name = Input::get('card_name');
            $dob = Input::get('dob');
            $gender = Input::get('gender');

            $country = Input::get('country');
            $state = Input::get('state');
            $chapter = Input::get('chapter');
            $address = Input::get('address');
            $city = Input::get('city');
            $pincode = Input::get('pincode');

            $email1 = Input::get('email1');
            $email2 = Input::get('email2');
            $std = Input::get('std');
            $phone = Input::get('phone');
            $country_code = Input::get('country-code');
            $mobile = Input::get('mobile');

            $organisation = Input::get('organisation');
            $designation = Input::get('designation');
            $employee_id = Input::file('employee_id');

            $member = new Member;


            $member->membership_id = 2; // individual member
            $membership_type = 4; // professional member
            $member->csi_chapter_id = $chapter;
            $member->email = $email1;
            $member->email_extra = $email2;
            $member->password = $password;

            $member->save();

            Address::create([
                'type_id' => 1,
                'member_id' => $member->id,
                'country_code' => $country,
                'state_code' => $state,
                'address_line_1' => $address,
                'city' => $city,
                'pincode' => $pincode
            ]);

            Phone::create([
                'member_id' => $member->id,
                'std_code' => $std,
                'landline' => $phone,
                'country_code' => $country_code,
                'mobile' => $mobile,
            ]);

            $individual = Individual::create([
                'member_id' => $member->id,
                'membership_type_id' => $membership_type,
                'salutation_id' => $salutation,
                'first_name' => $fname,
                'middle_name' => $mname,
                'last_name' => $lname,
                'card_name' => $card_name,
                'gender' => $gender,
                'dob' => $dob
            ]);


            //move student id batch to a location savely and then store data in db
            $filename = $member->id.'-'.$member->membership_id.'.';
            $filename.=$employee_id->getClientOriginalExtension();
            $employee_id->move(storage_path('uploads/profile_proofs'), $filename);

            ProfessionalMember::create([
                'id' => $individual->id,
                'organisation' => $organisation,
                'is_nominee'=>ActionStatus::nothing,
                'designation' => $designation,
                'proof_id' => $filename,
            ]);

            $request = RequestService::create([
                'service_id' => Service::getServiceIDByType('membership'),
                'member_id'  => $member->id
            ]);

            RequestAction::create([
                'request_id' => $request->id,
                'status' => ActionStatus::pending
            ]);

            return $member;
        });
        return $var;
    }

    private function storeNomineeMemberData($password){
        $var = DB::transaction(function($connection) use($password){

            $salutation = Input::get('salutation');
            $fname = Input::get('fname');
            $mname = Input::get('mname');
            $lname = Input::get('lname');
            $card_name = Input::get('card_name');
            $dob = Input::get('dob');
            $gender = Input::get('gender');

            $country = Input::get('country');
            $state = Input::get('state');
            $chapter = Input::get('chapter');
            $address = Input::get('address');
            $city = Input::get('city');
            $pincode = Input::get('pincode');

            $email1 = Input::get('email1');
            $email2 = Input::get('email2');
            $std = Input::get('std');
            $phone = Input::get('phone');
            $country_code = Input::get('country-code');
            $mobile = Input::get('mobile');

            $associating_institution_id=Input::get('associating_institution');
            $organisation = Input::get('organisation');
            $designation = Input::get('designation');
            $employee_id = Input::file('employee_id');

            $member = new Member();


            $member->membership_id = 2; // individual member
            $membership_type = 4; // professional member
            $member->csi_chapter_id = $chapter;
            $member->email = $email1;
            $member->email_extra = $email2;
            $member->password = $password;

            $member->save();

            Address::create([
                'type_id' => 1,
                'member_id' => $member->id,
                'country_code' => $country,
                'state_code' => $state,
                'address_line_1' => $address,
                'city' => $city,
                'pincode' => $pincode
            ]);

            Phone::create([
                'member_id' => $member->id,
                'std_code' => $std,
                'landline' => $phone,
                'country_code' => $country_code,
                'mobile' => $mobile,
            ]);

            $individual = Individual::create([
                'member_id' => $member->id,
                'membership_type_id' => $membership_type,
                'salutation_id' => $salutation,
                'first_name' => $fname,
                'middle_name' => $mname,
                'last_name' => $lname,
                'card_name' => $card_name,
                'gender' => $gender,
                'dob' => $dob
            ]);


            //move student id batch to a location savely and then store data in db
            $filename = $member->id.'-'.$member->membership_id.'.';
            $filename.=$employee_id->getClientOriginalExtension();
            $employee_id->move(storage_path('uploads/profile_proofs'), $filename);


            ProfessionalMember::create([
                'id' => $individual->id,
                'associating_institution_id'=>$associating_institution_id,
                'organisation' => $organisation,
                'designation' => $designation,
                'is_nominee'=>ActionStatus::pending,
                'proof_id' => $filename,
            ]);

            $request = RequestService::create([
                'service_id' => Service::getServiceIDByType('membership'),
                'member_id'  => $member->id
            ]);

            RequestAction::create([
                'request_id' => $request->id,
                'status' => ActionStatus::pending
            ]);
            $member_profmember=[$member->id,$individual->id];

            return $member_profmember;
        });
        return $var;
    }

    private function storeStudentIndividualMemberData($password){
        $var = DB::transaction( function($connection) use($password){

            $salutation = Input::get('salutation');
            $fname = Input::get('fname');
            $mname = Input::get('mname');
            $lname = Input::get('lname');
            $card_name = Input::get('card_name');
            $dob = Input::get('dob');
            $gender = Input::get('gender');

            $country = Input::get('country');
            $state = Input::get('state');
            $stud_branch = Input::get('stud_branch');
            $address = Input::get('address');
            $city = Input::get('city');
            $pincode = Input::get('pincode');

            $college = Input::get('college');
            $course = Input::get('course');
            $cbranch = Input::get('cbranch');
            $cduration = Input::get('cduration');
            $student_id = Input::file('student_id');

            $email1 = Input::get('email1');
            $email2 = Input::get('email2');
            $std = Input::get('std');
            $phone = Input::get('phone');
            $country_code = Input::get('country-code');
            $mobile = Input::get('mobile');

            $student_branch = AcademicMember::find($stud_branch);
            $chapter = $student_branch->institution->member->csi_chapter_id;

            $member = new Member;

            $member->membership_id = 2; // individual member
            $membership_type = 3; // student member
            $member->csi_chapter_id = $chapter;
            $member->email = $email1;
            $member->email_extra = $email2;
            $member->password = $password;

            $member->save();

            Address::create([
                'type_id' => 1,
                'member_id' => $member->id,
                'country_code' => $country,
                'state_code' => $state,
                'address_line_1' => $address,
                'city' => $city,
                'pincode' => $pincode
            ]);

            Phone::create([
                'member_id' => $member->id,
                'std_code' => $std,
                'landline' => $phone,
                'country_code' => $country_code,
                'mobile' => $mobile,
            ]);

            $individual = Individual::create([
                'member_id' => $member->id,
                'membership_type_id' => $membership_type,
                'salutation_id' => $salutation,
                'first_name' => $fname,
                'middle_name' => $mname,
                'last_name' => $lname,
                'card_name' => $card_name,
                'gender' => $gender,
                'dob' => $dob
            ]);

            //move student id batch to a location savely and then store data in db
            $filename = $member->id.'-'.$member->membership_id.'.';
            $filename.=$student_id->getClientOriginalExtension();
            $student_id->move(storage_path('uploads/profile_proofs'), $filename);

            $student_details = StudentMember::create([
                'id'                => $individual->id,
                'student_branch_id' => $student_branch->id,
                'college_name'      => $college,
                'course_name'       => $course,
                'course_branch'     => $cbranch,
                'course_duration'   => $cduration,
                'proof_id'          => $filename,
            ]);

            $request = RequestService::create([
                'service_id' => Service::getServiceIDByType('membership'),
                'member_id'  => $member->id
            ]);

            RequestAction::create([
                'request_id' => $request->id,
                'status' => ActionStatus::pending
            ]);

            return $member;
        });

        return $var;
    }

    public function storeOfflinePayment($member){
        $var = DB::transaction(function($connection) use($member) {

            $membership_period      = Input::get('membership-period');
            $paymentMode            = Input::get('paymentMode');
            $tno                    = Input::get('tno');
            $drawn                  = Input::get('drawn');
            $bank                   = Input::get('bank');
            $branch                 = Input::get('branch');
            $paymentReciept         = Input::file('paymentReciept');
            $amountPaid             = Input::get('amountPaid');



            // 2nd arg is currency.. needs to be queried to put here
            $head = PaymentHead::getHead($membership_period, 1)->first();
            $finalAmount = ( $head->amount + (($head->amount*$head->serviceTaxClass->tax_rate)/100) );
            $payment = Payment::create([
                'paid_for' => $member->id,
                'payment_head_id' => $head->id,
                'service_id' => 1
            ]);

            $narration = Narration::create([
                'payer_id' => $member->id,
                'mode' => $paymentMode,
                'transaction_number' => $tno,
                'bank' => $bank,
                'branch' => $branch,
                'date_of_payment' => $drawn,
                'drafted_amount' => $finalAmount
            ]);

            $filename = $member->id.'-'.$narration->id.'.';
            $filename.=$paymentReciept->getClientOriginalExtension();
            $paymentReciept->move(storage_path('uploads/payment_proofs'), $filename);
            $narration->proof = $filename;
            $narration->save();

            $journal = Journal::create([
                'payment_id' => $payment->id,
                'narration_id' => $narration->id,
                'paid_amount' => $amountPaid,
            ]);

            $request = RequestService::requestsByMemberIdAndServiceId($member->id, Service::getServiceIDByType('membership'))->first();
            $request->payment_id = $payment->id;
            $request->save();

            return $payment->id;
        });
        return $var;
    }

}