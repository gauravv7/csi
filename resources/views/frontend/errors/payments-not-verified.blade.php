@extends('frontend.master')
@section('title', 'Dashboard home')


@section('main')
<section id="main">

   {{-- start --}}


<div class="container-fluid">

  <div class="row">
     <div class="col-md-12" style="padding-left: 30px;">
           <h2>
              Not Yet!
           </h2>
         @if (Session::has('flash_notification.message'))
           <div class="alert alert-{{ Session::get('flash_notification.level') }}">
               <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>

               {{ Session::get('flash_notification.message') }}
           </div>
        @endif
     </div>
  </div>

  <div class="row">
      <div class="col-md-3">
         @include('frontend.partials._profileSidebar')
      </div>

      <div class="col-md-9">
            <div class="row">
               <div class="col-md-12">
                  <h2 class="page-header" style="margin-bottom: 20px; ">Not Verified Yet</h2>
                  <p class="title-text">Your Services are not verified. Please wait until to use your services.</p>
               </div>
            </div>
      </div>
  </div>

</div>

{{-- end --}}
<br/>
<br/>
<br/>
<br/>
<br/>
</section>
@endsection