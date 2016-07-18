<div class="steps" data-stp=2>
	<div class="form-group">
		<label for="exampleInputEmail1" class="req">Country*</label>
		{!! Form::select('country', $countries, $country_code, array('class'=>"form-control", 'id'=>"country", 'data-form'=>"0")) !!}
	</div>
	<div class="form-group">
		<label for="exampleInputEmail1" class="req">State*</label>
		{!! Form::select('state', ['invalid' => 'Please select a state'], null, array('class'=>"form-control", 'id'=>"state", 'data-state'=>( $entity == 'individual-student')? "1" : "0")) !!}

	</div>
	@if( $entity == 'individual-student')
		<div class="form-group">
			<label for="exampleInputEmail1" class="req">Student Branch*</label>
			{!! Form::select('stud_branch', ['invalid' => 'Please select a student branch'], null, array('class'=>"form-control", 'id'=>"stud_branch")) !!}
		</div>
	
	@elseif ( ( $entity == 'institution-academic') || ( $entity == 'institution-non-academic') || ( $entity == 'individual-professional')|| ( $entity == 'nominee') )
		<div class="form-group">
			<label for="exampleInputEmail1" class="req">Chapters*</label>
		  	{!! Form::select('chapter', ['invalid' => 'Please select a chapter'], null, array('class'=>"form-control", 'id'=>"chapter")) !!}
		</div>
	@endif
	<div class="form-group">
		<label for="exampleInputPassword1" class="req">Address*</label>
		{!! Form::text('address', null, ['class' => 'form-control', 'placeholder' => 'Permanent Address']) !!}
	</div>
	<div class="form-group">
		<label for="exampleInputPassword1" class="req">City*</label>
		{!! Form::text('city', null, ['class' => 'form-control', 'placeholder' => 'City']) !!}
	</div>
	<div class="form-group">
		<label for="exampleInputPassword1" class="req">Pincode*</label>
		{!! Form::text('pincode', null, ['class' => 'form-control', 'placeholder' => 'Pincode']) !!}
	</div>
	<div class="btn-group btn-group-justified">
		<a class="col-md-offset-4 btn btn-default previous">Previous</a>
		<a class="btn btn-default next">Next</a>
	</div>

</div>