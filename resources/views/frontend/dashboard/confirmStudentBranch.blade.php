@extends('frontend.master')
@section('title', 'Register')

@section('custom-styles')
   <link rel="stylesheet" type="text/css" href={{ asset('css/sidebar.css') }}>
@endsection

@section('main')
<section id="main">

   {{-- start --}}

<div class="container-fluid">
   <div class="row">
     <div class="col-md-12">
      @if (Session::has('flash_notification.message'))
         <div class="alert alert-{{ Session::get('flash_notification.level') }}">
             <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
             {{ Session::get('flash_notification.message') }}
         </div>
      @endif
     </div>
   </div>
   <div class="row affix-row">
     
      <div class="col-sm-9 col-md-10" style="padding: 0px 60px;" id="profile">
                 
         <div class="row">
            <div class="col-md-12">
               <h2 class="page-header"><span class="glyphicon glyphicon-user"></span>Request for being a Student Branch</h2>
               @if(Auth::user()->user()->getMembership->subType->is_student_branch == -2)
                <p>By clicking the link below you confirm to be a student branch</p>
                {!! Form::open(['route' => ['SendRequestStudentBranch'], 'method' => 'get' ]) !!}
                        <!-- Change this to a button or input when using this as a form -->
                         <div class="col-md-2">
                            <button class="btn btn-success btn-block" type="submit">Confirm</button>
                        </div>
                        <div class="col-md-2">
                            <a href={{ route('userDashboard') }} class="btn btn-primary">Cancel</a>
                        </div>
                {!! Form::Close() !!}
                @elseif(Auth::user()->user()->getMembership->subType->is_student_branch == -1)
                    <h4>
                      REQUEST RECEIVED.
                    </h4>
                @elseif(Auth::user()->user()->getMembership->subType->is_student_branch == 0)
                    <h4>
                      REQUEST CANCELLED.
                    </h4>
                    <h5>
                      Reason: {{ Auth::user()->user()->getMembership->subType->rejection_reason }}
                    </h5>
                @elseif(Auth::user()->user()->getMembership->subType->is_student_branch == 1)
                    <h4>
                      REQUEST ACCEPTED.
                    </h4>
                @endif

            </div>
         </div>

      </div>
   </div>

</div>

{{-- end --}}
<br/>
<br/>
</section>
@endsection