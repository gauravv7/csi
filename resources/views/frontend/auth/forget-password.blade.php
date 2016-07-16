@extends('frontend.master')
@section('title', 'Register')
@section('main')
	<section id="main">
   		<div class="container">
   			<div class="row">
   				<div class="col-md-offset-4 col-md-4">
   					<div>
					     <h1 class="section-header-style text-center">Reset Password</h1>
					   </div>

					@if ( $errors->any() )
   						<ul class="list-unstyled csi-form-errors alert alert-danger">
   						@foreach ($errors->all() as $error)
   							<li>{{ $error }}</li>
   						@endforeach
   						</ul>
   				@endif

   					{!! Form::open(['route' => 'MemberForgetPasswordRequest']) !!}
  						<div class="form-group">
  							<label for="exampleInputPassword1">Email</label>
          					<input type="email" class="form-control"  name="email" value="">
  						</div>
  					  <div>
  					    <button type="submit" class="btn btn-primary btn-brand btn-lg btn-block">continue</button>
  					  </div>
					   {!! Form::Close() !!}
   				</div>
   			</div>
   		</div>
   	</section>
@endsection