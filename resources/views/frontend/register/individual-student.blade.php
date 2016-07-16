@extends('frontend.master')

@section('title', 'Register')
@section('main')
	<section id="main">
   		<div class="container">
   			<div class="row">
   				<div class="col-md-12">
   					<div>
					  	<h1 class="section-header-style">student membership form</h1>
					</div>
   					<ul id="progressbar">
						<li class="active">General Details</li>
						<li>Address Details</li>
						<li>Contact Details</li>
						<li>Student Details</li>
					</ul>

					@if ( $errors->any() )
   						<ol class="csi-form-errors alert alert-danger">
   						@foreach ($errors->all() as $error)
   							<li>{{ $error }}</li>
   						@endforeach
   						</ol>
   					@endif
   					<div class="row page-header">
					  <div class="col-md-8">
					  	<h3 id="stepText"> <small id="stepSubText"></small></h3>
					  </div>
					  <div class="col-md-4">
					  	<p class="pull-right" style="    font-size: 14px;    margin: 35px 15px; color: RED;font-weight: bold;letter-spacing: 1px;">field with * are required</p>
					  </div>
					</div>
   					{!! Form::open(['route' => ['submitToPayments', 'entity'=>$entity], 'files' => true]) !!}
					  <div class="steps">
						<div class="form-group">
							<label for="exampleInputPassword1" class="req">Title of applicant*</label>
							<div class="radio">
							    <label class="radio-inline">
									{!! Form::radio('salutation', 1) !!}
									Mr
								</label>
								<label class="radio-inline">
									{!! Form::radio('salutation', 2) !!}
									Miss
								</label>
								<label class="radio-inline">
									{!! Form::radio('salutation', 3) !!}
									Mrs
								</label>
								<label class="radio-inline">
									{!! Form::radio('salutation', 4) !!}
									Dr
								</label>
								<label class="radio-inline">
									{!! Form::radio('salutation', 5) !!}
									Prof 
								</label>								
							</div>
						</div>
						<div class="form-group">
							<label for="exampleInputPassword1" class="req">First Name*</label>
							{!! Form::text('fname', null, ['class' => 'form-control', 'placeholder' => 'First Name ']) !!}
						</div>						
						<div class="form-group">
							<label for="exampleInputPassword1">Middle Name</label>
							{!! Form::text('mname', null, ['class' => 'form-control', 'placeholder' => 'Middle Name ']) !!}
						</div>
						<div class="form-group">
							<label for="exampleInputPassword1" class="">Last Name</label>
							{!! Form::text('lname', null, ['class' => 'form-control', 'placeholder' => 'Last Name ']) !!}
						</div>
						<div class="form-group">
							<label for="exampleInputPassword1" class="req">Name on CSI Card*</label>
							{!! Form::text('card_name', null, ['class' => 'form-control', 'placeholder' => 'Name on CSI Card ']) !!}
						</div>

						<div class="form-group">
							<label for="exampleInputPassword1" class="req">Date of Birth*</label>
							{!! Form::text('dob', null, ['class'=>'form-control', 'id'=>'dob_student'])!!}
							<span class="help-text"></span>
						</div>

						<div class="form-group">
					    <label class="req">Gender*</label>
	      				  <div class="radio">
						    <label class="radio-inline">
						    	{!! Form::radio('gender', 'm', null, ['id'=>'gender'])!!} Male
							</label>
							<label class="radio-inline">
							  {!! Form::radio('gender', 'f', null, ['id'=>'gender'])!!} Female
							</label>
						  	
						  </div>
					  </div>
						<div class="btn-group btn-group-justified">
							<a class="btn btn-default next">Next</a>
						</div>
					  </div>
					  
					  
					  @include('frontend.partials._partAddress')
					  @include('frontend.partials._partContact')


					  <div class="steps">
					  	
						<div class="form-group">
							<label class="control-label req">College Name*</label>
							{!! Form::text('college', null, ['class' => 'form-control', 'placeholder' => 'Enter your college name']) !!}
						</div>
					
						<div class="form-group">
						    <label class="control-label req">Course*</label>
						    {!! Form::text('course', null, ['class' => 'form-control', 'placeholder' => 'Enter your course name']) !!}
						 </div>

					  	<div class="form-group">
					    	<label class="control-label req">Course Branch*</label>
					    	{!! Form::text('cbranch', null, ['class' => 'form-control', 'placeholder' => 'Enter your course branch']) !!}
					  	</div>
					  
					  	<div class="form-group">
					    	<label class="control-label req">Course Duration(in years)*</label>
					    	{!! Form::text('cduration', null, ['class' => 'form-control', 'placeholder' => 'Enter your course duration']) !!}
					    </div>
					    <div class="form-group">
							<label for="exampleInputFile" class="req">Student ID*</label>
							<input type="file" name="student_id" id="student_id">
							<p class="help-block">Please upload a scanned copy of a valid student ID card/bonefied certificate(file types allowed are jpg/png/bmp/pdf)</p>
						</div>
						<div class="btn-group btn-group-justified">
							<a class="col-md-offset-4 btn btn-default previous">Previous</a>
							<a class="btn btn-default" name="submit" id="submit">Submit</a>
						</div>
					  </div>
					{!! Form::Close() !!}
   				</div>
   			</div>
   		</div>

   	</section>

   	<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" id="ForeignNationalNotAllowed" aria-labelledby="mySmallModalLabel">
	  <div class="modal-dialog">
      	<div class="modal-content">
		    <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
	    	<h5 style="text-align: center; padding: 20px;">Membership for Foreign National is not supported yet.</h5>
	    </div>
	  </div>
	</div>
@endsection


@section('footer-scripts')
	<script src={{ asset("js/validateit.js") }}></script>
	<script src={{ asset('js/function9.js') }}></script>
	<script src={{ asset('js/registeration.js') }}></script>
@endsection

