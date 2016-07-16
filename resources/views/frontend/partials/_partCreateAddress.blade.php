<div class="form-group">
	<label for="exampleInputEmail1" class="req">Address Type*</label>
	{!! Form::select('address-type', $address_types, null, array('class'=>"form-control", 'id'=>"country", 'data-form'=>"0")) !!}
</div>
<div class="form-group">
	<label for="exampleInputEmail1" class="req">Country*</label>
	{!! Form::select('country', $countries, null, array('class'=>"form-control", 'id'=>"country", 'data-form'=>"0")) !!}
</div>
<div class="form-group">
	<label for="exampleInputEmail1" class="req">State*</label>
		{!! Form::select('state', $states, null, array('class'=>"form-control", 'id'=>"state")) !!}
</div>
<div class="form-group">
	<label for="exampleInputPassword1" class="req">Address*</label>
	{!! Form::text('address', null, ['class' => 'form-control', 'placeholder' => 'Address']) !!}
</div>
<div class="form-group">
	<label for="exampleInputPassword1" class="req">City*</label>
	{!! Form::text('city', null, ['class' => 'form-control', 'placeholder' => 'City']) !!}
</div>
<div class="form-group">
	<label for="exampleInputPassword1" class="req">Pincode*</label>
	{!! Form::text('pincode', null, ['class' => 'form-control', 'placeholder' => 'Pincode']) !!}
</div>
<div class="form-group">
	<input type="submit" class="btn btn-lg btn-block btn-primary" name="submit" value="Submit" />
</div>
