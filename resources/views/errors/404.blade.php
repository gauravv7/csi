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
              404 - Page Not Found
            </h3>
            <p>
            The page you are trying to access does not exist.<br/>This page was possibly moved or never created...
            </p>
        </div>  
			</div>
		</div>
	</section>
@endsection