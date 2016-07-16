@extends('frontend.master')
@section('title', 'Register')

@section('custom-styles')
   <link rel="stylesheet" type="text/css" href={{ asset('css/sidebar.css') }}>
@endsection

@section('main')
<section id="main">

   {{-- start --}}

<div class="container-fluid">
   <div class="row affix-row">
      <div class="col-sm-3 col-md-2 affix-sidebar">
         @include('frontend.partials.dashboardSidebar')
      </div>

      <div class="col-sm-9 col-md-10" style="padding: 0px 60px;" >
                 
         <div class="row">
            <div class="col-md-12">
               <h2 class="page-header"><span class="glyphicon glyphicon-print"></span>CSI Card <small>{{ $user->subType->name }}</small></h2>
               
               <div class="col-md-offset-2 col-md-8">
                     <div id="card">
                        <p>
                           <span class="title"  style="display:block; text-align: center">CSI Membership-Card</span>
                           <span class="title-text">
                              Membership-ID: {{ $user->subType->membershipType->prefix }}-{{ $user->id }}
                           </span>
                           <span class="title-text pull-right">
                              Institution-Type: {{ $user->subType->subType->institutionType->name }}
                           </span>

                           <span class="title-text" style="display:block; margin-top: 20px;">
                              Name of the Institution: {{ $user->subType->name }}
                           </span>
                           <span class="title-text" style="display:block; margin-top: 20px;">
                              Name of the Institution: {{ $user->subType->name }}
                           </span>
                        </p>
                     </div>
               </div>
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
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
</section>
@endsection


@section('footer-scripts')
<script src={{ asset("js/validateit.js") }}></script>
<script src={{ asset('js/function7.js') }}></script>
@endsection