@extends('frontend.master')
@section('title', 'Register')
@section('main')
	<section id="main">
   		<div class="container">
   			<div class="row">
   				<div class="col-md-12">
   					<div>
					  	<h1 class="section-header-style">Make Payments</h1>
					</div>
   						
   					@if ( $errors->any() )
   					<div class="alert alert-danger">
   						<ul class="list-unstyle">
   						@foreach ($errors->all() as $error)
   							<li>{{ $error }}</li>
   						@endforeach
   						</ul>
   					</div>
   					@endif
   					
   					
   				</div>
   			</div>

   			<div class="row">
   				<div class="col-md-2">
   					<ul class="nav nav-tabs membership-payment-list">
					  <li class="active"><a href="#offline" data-toggle="tab" aria-expanded="true">Offline</a></li>
					  <li class=""><a href="#online" data-toggle="tab" aria-expanded="false">Online</a></li>
					</ul>
				</div>
				<div class="col-md-10">
					<div id="myTabContent" class="tab-content">
					  @include('frontend.partials._partCreateOfflinePayment')
					  @include('frontend.partials._partCreateOnlinePayment')
					</div>
				</div>
   				
   			</div>	
   		</div>

   	</section>
@endsection

