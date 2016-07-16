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
              Sorry!
            </h3>
            <p>
            	We hung up! please try again later...
            </p>
        </div>  
			</div>
		</div>
	</section>
@endsection