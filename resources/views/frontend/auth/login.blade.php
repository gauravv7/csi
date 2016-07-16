@extends('frontend.master')
@section('title', 'Register')
@section('main')
	<section id="main">
   		<div class="container">
   			<div class="row">
          <div class="col-md-offset-4 col-md-4">
           @if (Session::has('flash_notification.message'))
             <div class="alert alert-{{ Session::get('flash_notification.level') }}">
                 <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                 {{ Session::get('flash_notification.message') }}
             </div>
          @endif
            <div>
               <h1 class="section-header-style text-center">Login</h1>
             </div>
					@if ( $errors->any() )
   						<ul class="csi-form-errors alert alert-danger">
   						@foreach ($errors->all() as $error)
   							<li>{{ $error }}</li>
   						@endforeach
   						</ul>
   				@endif

   					{!! Form::open(['url' => 'login']) !!}
  						<div class="form-group">
	   						<label for="exampleInputPassword1">Email</label>
       					<input type="email" class="form-control"  name="email" value="{{ old('email') }}">
				  		</div>
	     				<div class="form-group">
			   				<label for="exampleInputPassword1">Password</label>
        				<input type="password" class="form-control"  name="password" id="password">
   						</div>
					    <div>
					        <button type="submit" class="btn btn-primary btn-brand btn-lg btn-block">Login</button>
					    </div>
					{!! Form::Close() !!}
          <a href={{ route('MemberForgetPassword') }}>Forgot Password?</a>
   				</div>
   			</div>
   		</div>
   	</section>
@endsection