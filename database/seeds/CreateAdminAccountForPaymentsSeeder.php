<?php

use App\AcademicMember;
use App\Address;
use App\CsiChapter;
use App\Individual;
use App\Institution;
use App\Journal;
use App\Member;
use App\Narration;
use App\Payment;
use App\PaymentHead;
use App\Phone;
use App\ProfessionalMember;
use App\RequestService;
use App\Service;
use App\State;
use App\StudentMember;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Seeder;

class CreateAdminAccountForPaymentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

    	$faker = Faker\Factory::create();
    	
    	/**
    	 * academic institution
    	 */
    	$member = factory(Member::class, 'admin-institution')
    				->create();
		Address::create([
            'type_id' => 1,
            'member_id' => $member->id,
            'country_code' => 'IND', 
            'state_code' => CsiChapter::find($member->csi_chapter_id)->state->state_code,
            'address_line_1' => $faker->streetAddress,
            'city' => State::filterByStateCode(CsiChapter::find($member->csi_chapter_id)->state->state_code)->first()->name,
            'pincode' => 110052
        ]);
		$this->command->info('address done!');
        Phone::create([
            'member_id' => $member->id,
            'std_code' => 011,
            'landline' => 47028209
        ]);
		$this->command->info('phone done!');

		$institution = Institution::create([
			'member_id' => $member->id, 
			'membership_type_id' => 1, 
			'salutation_id' => 1, 
			'name' => 'csi', 
			'head_name' => $faker->name, 
			'head_designation' => $faker->word, 
			'email' => $faker->email, 
			'mobile' => 1234567890
		]);

		$this->command->info('institution done!');
		AcademicMember::create([
        	'id' => $institution->id,
        	'is_student_branch' => 1,
        	'institution_type_id' => 2
    	]);
		$this->command->info('academic done!');

        $head = PaymentHead::getHead(1, 1)->first();

        $payment = Payment::create([
            'paid_for' => $member->id, 
            'payment_head_id' => $head->id, 
            'service_id' => 1
        ]);
		$this->command->info('payment done!'.$member->id);

        $narration = Narration::create([ 
            'payer_id' => $member->id, 
            'mode' => 1, 
            'transaction_number' => str_random(12), 
            'bank' => 'self', 
            'branch' => 'self', 
            'date_of_payment' => Carbon::now()->format('d/m/Y'), 
            'drafted_amount' => $payment->calculatePayable(), 
            'proof' => '6.jpg'
        ]);
		$this->command->info('narration done!');

        $journal = Journal::create([
            'payment_id' => $payment->id,
            'narration_id' => $narration->id,
			'paid_amount' => $payment->calculatePayable(), 
        ]);
		$this->command->info('Journal done!');
	    $journal->is_rejected = 0;
	    $journal->save();
	    $payment->date_of_effect = Carbon::now()->format('d/m/Y');
	    $payment->save();

	    RequestService::create([
            'service_id' => Service::getServiceIDByType('membership'), 
            'payment_id' => $payment->id,
            'member_id'  => $member->id
        ]);
		$this->command->info('request done!');
		
		
        
    }
}
