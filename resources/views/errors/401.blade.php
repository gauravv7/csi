@extends('frontend.master')

@section('title', 'Home')

@section('section-after-mainMenu')
@endsection

@section('main')
	<section>
		<div class="container">
			<div class="row">
				<div class="col-md-4">
				    <img src="img/404.gif" alt="not found">
				</div>
        <div class="col-md-8">
          <h3>
              403 - Forbidden!
            </h3>
            <p>
            	You are not authorized to perform this action!
            </p>
        </div>  
			</div>
		</div>
	</section>
@endsection