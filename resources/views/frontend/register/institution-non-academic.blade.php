@extends('frontend.master')
@section('title', 'Register')
@section('main')
	<section id="main">
   		<div class="container">
   			<div class="row">
   				<div class="col-md-12">
   					<div>
					  <h1 class="section-header-style">non-academic institutional membership form</h1>
					</div>
   					<ul id="progressbar">
						<li class="active">Institution &amp; Category Details</li>
						<li>Address Details</li>
						<li>Institute's Contact Details</li>
						<li>Details of Head of the Institution</li>
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
							<label for="exampleInputPassword1" class="req">Name of the Institution*</label>
							{!! Form::text('nameOfInstitution', null, ['class' => 'form-control', 'placeholder' => 'Name of the Institution']) !!}
						</div>
						<div class="btn-group btn-group-justified">
							<a class="btn btn-default next">Next</a>
						</div>
					  </div>
					  
					  
					  @include('frontend.partials._partAddress')
					  @include('frontend.partials._partContact')


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
							<label for="exampleInputPassword1" class="req">Name*</label>
							{!! Form::text('headName', null, ['class' => 'form-control', 'placeholder' => 'Name of the Head of the institution']) !!}
						</div>
						<div class="form-group">
							<label for="exampleInputPassword1" class="req">Designation*</label>
							{!! Form::text('headDesignation', null, ['class' => 'form-control', 'placeholder' => 'Designation of the Head of the institution']) !!}
						</div>
						<div class="form-group">
							<label for="exampleInputPassword1" class="req">Email-ID*</label>
							{!! Form::text('headEmail', null, ['class' => 'form-control', 'placeholder' => 'Email ID of the Head of the institution']) !!}
						</div>
						<div class="form-group">
							<label for="exampleInputPassword1" class="req">Mobile*</label>
							<div class="input-group">
						    	<span class="input-group-addon">+</span>
		      				 	{!! Form::text('country-code', null, ['class' => 'form-control', 'placeholder' => 'Country Code', 'id'=>'country-code', 'style'=> 'width: 30%; float:left; border-bottom: 2px solid #ff9800']) !!}
						   		{!! Form::text('mobile', null, ['class' => 'form-control', 'placeholder' => 'Mobile Number', 'id'=>'mobile', 'style'=> 'width: 70%; float:left; border-bottom: 2px solid #9c27b0']) !!}
						    </div>
						    <div class="row">
					    		<div class="col-md-4" id="errorCountry">
					    			<span id="helpBlock" class="help-block ">Country Code</span>
							    		
					    		</div>
					    		<div class="col-md-6" id="errorMobile">
					    			<span id="helpBlock" class="help-block ">Mobile number</span>
						    				
					    		</div>
					    	</div>
						</div>
						<div class="btn-group btn-group-justified">
							<a class="col-md-offset-4 btn btn-default previous">Previous</a>
							<a class="btn btn-default" id="submit">Next</a>
						</div>
					  </div>

					{!! Form::Close() !!}
   				</div>
   			</div>
   		</div>
   		<br/>
   		<br/>
   		<br/>

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
	<script src={{ asset('js/function8.js') }}></script>
	<script src={{ asset('js/registeration.js') }}></script>
@endsection