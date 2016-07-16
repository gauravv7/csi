<div class="steps">
	<div class="form-group">
	<label for="exampleInputPassword1" class="req">Title of applicant*</label>
	<div class="radio">
	    <label class="radio-inline">
			{!! Form::radio('salutation', 1, ($salutation==1)? true: false ) !!}
			Mr
		</label>
		<label class="radio-inline">
			{!! Form::radio('salutation', 2, ($salutation==2)? true: false) !!}
			Miss
		</label>
		<label class="radio-inline">
			{!! Form::radio('salutation', 3, ($salutation==3)? true: false) !!}
			Mrs
		</label>
		<label class="radio-inline">
			{!! Form::radio('salutation', 4, ($salutation==4)? true: false) !!}
			Dr
		</label>
		<label class="radio-inline">
			{!! Form::radio('salutation', 5, ($salutation==5)? true: false) !!}
			Prof 
		</label>								
	</div>
	</div>
	<div class="form-group">
		<label for="exampleInputPassword1" class="req">Name*</label>
		{!! Form::text('headName', $member->head_name, ['class' => 'form-control', 'placeholder' => 'Name of the Head of the institution']) !!}
	</div>
	<div class="form-group">
		<label for="exampleInputPassword1" class="req">Designation*</label>
		{!! Form::text('headDesignation', $member->head_designation, ['class' => 'form-control', 'placeholder' => 'Designation of the Head of the institution']) !!}
	</div>
	<div class="form-group">
		<label for="exampleInputPassword1" class="req">Email-ID*</label>
		{!! Form::text('headEmail', $member->email, ['class' => 'form-control', 'placeholder' => 'Email ID of the Head of the institution']) !!}
	</div>
	<div class="form-group">
		<label for="exampleInputPassword1" class="req">Mobile*</label>
		<div class="input-group">
	    	<span class="input-group-addon">+</span>
			 	{!! Form::text('country-code', $member->country_code, ['class' => 'form-control', 'placeholder' => 'Country Code', 'id'=>'country-code', 'style'=> 'width: 30%; float:left']) !!}
	   		{!! Form::text('mobile', $member->mobile, ['class' => 'form-control', 'placeholder' => 'Mobile Number', 'id'=>'mobile', 'style'=> 'width: 70%; float:left']) !!}
	    </div>
	    <div class="row">
			<div class="col-md-4" id="errorCountry">
				<span id="helpBlock" class="help-block ">STD Code</span>
		    		
			</div>
			<div class="col-md-6" id="errorMobile">
				<span id="helpBlock" class="help-block ">Landline number</span>
	    				
			</div>
		</div>
	</div>
	@if($isSingleStep)
		<div>
			<input type="submit" class="btn btn-lg btn-block btn-primary" name="submit" value="Submit" />
		</div>
	@else
		<div class="btn-group btn-group-justified">
			<a class="col-md-offset-4 btn btn-default previous">Previous</a>
			<a class="btn btn-default next">Next</a>
		</div>
	@endif
</div>