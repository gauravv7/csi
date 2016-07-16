<div @if(!$isSingleStep) class="steps" @endif>						
	<div class="form-group">
		<label for="exampleInputPassword1" class="req">Primary Email-ID*</label>
		{!! Form::email('email1', isset($member)? $member->email: '', ['class' => 'form-control', 'placeholder' => 'Primary Email ID ']) !!}
	</div>
	<div class="form-group">
		<label for="exampleInputPassword1">Secondary Email-ID</label>
		{!! Form::text('email2', isset($member)? $member->email_extra: '', ['class' => 'form-control', 'placeholder' => 'Secondary Email ID']) !!}
	</div>
	<div class="row">
			<div class="col-md-4 form-group">
				@if ( ($entity == 'individual-student') || ($entity == 'individual-professional'))
					<label for="exampleInputPassword1">Landline</label>
				@else
					<label for="exampleInputPassword1" class="req">Landline*</label>
				@endif
				<div  class="input-group">
		      		<span class="input-group-addon" id="country-code-for-std"></span>

					  	{!! Form::text('std', isset($member)? $member->phone->std_code: '', ['class' => 'form-control', 'placeholder' => 'STD Code', 'id' => 'std-code', 'style'=>"border-top-right-radius:0px; border-bottom-right-radius: 0px;width: 100%; float: left;"]) !!}
			    </div>
			    <div class="row">
					<div class="col-md-12" id="errorSTD">
						<span class="help-block ">STD Code</span>
				    		
					</div>
				</div>
			</div>
			<div class="col-md-8 form-group">
				<div  class="input-group" style="width: 100%; margin-top:28px;">
		    		  	{!! Form::text('phone', isset($member)? $member->phone->landline: '', ['class' => 'form-control', 'placeholder' => '  Landline Number', 'id' => 'phone', 'style'=>"border-top-left-radius:0px; border-bottom-left-radius: 0px;width: 100%; float: left;"]) !!}
			    </div>
			    <div class="row">
					<div class="col-md-12" id="errorPhone">
						<span class="help-block ">Landline</span>
			    				
					</div>
				</div>
			</div>
	</div>
		@if ( ($entity == 'individual-student') || ($entity == 'individual-professional'))

			<div class="row">
				<div class="col-md-4 form-group">
					<label for="exampleInputPassword1" class="req">Mobile*</label>
					<div class="input-group">
				    	<span class="input-group-addon">+</span>
	  				 	{!! Form::text('country-code', isset($member)? $member->phone->country_code: '', ['class' => 'form-control', 'placeholder' => 'Country Code', 'id'=>'country-code', 'style'=> 'width: 100%; float:left']) !!}
				    </div>
				    <div class="row">
			    		<div class="col-md-12" id="errorCountry">
			    			<span id="helpBlock" class="help-block ">Country Code</span>
			    		</div>
			    	</div>
				</div>
				<div class="col-md-8 form-group">
					<label for="exampleInputPassword1" class="req"></label>
					<div class="input-group" style="margin-top:5px; width: 100%">
				   		{!! Form::text('mobile', isset($member)? $member->phone->mobile: '', ['class' => 'form-control', 'placeholder' => 'Mobile Number', 'id'=>'mobile', 'style'=> 'width: 100%; float:left']) !!}
				    </div>
				    <div class="row">
			    		<div class="col-md-12" id="errorMobile">
			    			<span id="helpBlock" class="help-block ">Mobile</span>
			    		</div>
			    	</div>
				</div>
			</div>
		@endif

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