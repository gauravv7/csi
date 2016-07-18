@extends('frontend.master-profile')
@section('title', 'Register')

@section('custom-styles')
   <link rel="stylesheet" type="text/css" href={{ asset('css/sidebar.css') }}>
@endsection

@section('main')
<section id="main">

    <div class="row">

        <div class="col-md-12 col-sm-12"> <!-- profile right area -->
            @include('frontend.partials._profileSidebar')
        </div> <!-- profile left area -->

    </div>
</section>
@endsection
