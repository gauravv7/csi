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
              Service unavailable!
            </h3>
            <p>
                we'll be right back...
            </p>
        </div>  
            </div>
        </div>
    </section>
@endsection