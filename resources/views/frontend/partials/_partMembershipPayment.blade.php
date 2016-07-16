	<div class="form-group">
		<label for="exampleInputPassword1" class="req">Mode of transaction*</label>
			{!! Form::select('paymentMode', $payModes, $paymentMode, ['class'=>'form-control'])!!}
	</div>
	<div class="form-group">
		<label for="exampleInputPassword1" class="req">Transaction Number*</label>
		{!! Form::text('tno', $tno, ['class'=>'form-control', 'placeholder'=>'Transaction/ Cheque/ DD number'])!!}
		<span class="help-text">(in case of online payment)/Cheque/DD Number, not required in case of cash</span>
	</div>
	<div class="form-group">
		<label for="exampleInputPassword1" class="req">Drawn On*</label>
		{!! Form::text('drawn', $drawn, ['class'=>'form-control', 'id'=>'drawn_on'])!!}
		<span class="help-text"></span>
	</div>
	<div class="form-group">
		<label for="exampleInputPassword1" class="req">Bank Name*</label>
		{!! Form::text('bank', $bank, ['class'=>'form-control', 'placeholder'=>'Enter the Bank Name'])!!}
		<span class="help-text"></span>
	</div>
	<div class="form-group">
		<label for="exampleInputPassword1" class="req">Branch Name*</label>
		{!! Form::text('branch', $branch, ['class'=>'form-control', 'placeholder'=>'Enter the Branch of the bank'])!!}
		<span class="help-text"></span>
	</div>
	<div class="form-group">
		<label for="exampleInputFile" class="req">Payment Receipt*</label>
		<input type="file" name="paymentReciept" id="paymentReciept">
		<p class="help-block">Please upload your payment receipt.(file types allowed are jpg/png/bmp/pdf</p>
	</div>
	<div class="form-group">
		<label for="exampleInputPassword1" class="req">Total Amount Paid*</label>
		{!! Form::text('amountPaid', $amountPaid, ['class'=>'form-control', 'id'=>'amount_paid'])!!}
		<span class="help-text"></span>
	</div>
	<div class="btn-group btn-group-justified">
		<a class="btn btn-default" name="submit" id="submit">submit</a>
	</div>
