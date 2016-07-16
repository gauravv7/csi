<div class="form-group">
	<label for="exampleInputEmail1" class="req">Country*</label>
	{!! Form::select('country', $countries, $address->country_code, array('class'=>"form-control", 'id'=>"country", 'data-form'=>"0")) !!}
</div>
<div class="form-group">
	<label for="exampleInputEmail1" class="req">State*</label>
		{!! Form::select('state', $states, $address->state_code, array('class'=>"form-control", 'id'=>"state", 'data-form'=>( $entity == 'individual-student')? "1" : "0",)) !!}
</div>
@if($address->type->type == 'registered address')
	@if( $entity == 'individual-student')
		<div class="form-group">
		    <label class="control-label" class="req">Student Branch*</label>
			{!! Form::select('stud_branch', $stud_branch, $address->member->getMembership->subType->student_branch_id, array('class'=>"form-control", 'id'=>"stud_branch")) !!}
		</div>
	
	@elseif ( ( $entity == 'institution-academic') || ( $entity == 'institution-non-academic') || ( $entity == 'individual-professional') )
		<div class="form-group">
			<label for="exampleInputEmail1" class="req">Chapters*</label>
			{!! Form::select('chapter', $chapters, $address->member->chapter->name, array('class'=>"form-control", 'id'=>"chapter")) !!}
		</div>
	@endif
@endif
<div class="form-group">
	<label for="exampleInputPassword1" class="req">Address*</label>
	{!! Form::text('address', $address->address_line_1, ['class' => 'form-control', 'placeholder' => 'Parmanent Address']) !!}
</div>
<div class="form-group">
	<label for="exampleInputPassword1" class="req">City*</label>
	{!! Form::text('city', $address->city, ['class' => 'form-control', 'placeholder' => 'City']) !!}
</div>
<div class="form-group">
	<label for="exampleInputPassword1" class="req">Pincode*</label>
	{!! Form::text('pincode', $address->pincode, ['class' => 'form-control', 'placeholder' => 'Pincode']) !!}
</div>
<div class="form-group">
	<input type="submit" class="btn btn-lg btn-block btn-primary" name="submit" value="Submit" />
</div>
