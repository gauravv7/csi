@extends('frontend.master')
@section('title', 'Dashboard home')


@section('main')
<section id="main">

   {{-- start --}}


<div class="container-fluid">

<div class="row">
   <div class="col-md-12" style="padding-left: 30px;">
         <h2>
            Dashboard
         </h2>
       @if (Session::has('flash_notification.message'))
         <div class="alert alert-{{ Session::get('flash_notification.level') }}">
             <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
             {{ Session::get('flash_notification.message') }}
         </div>
      @endif
      @if ( $errors->any() )
        <ul class="csi-form-errors alert alert-danger">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
        </ul>
      @endif
   </div>
</div>

   <div class="row">
      <div class="col-md-3 col-sm-3">

         @include('frontend.partials._profileSidebar')

      </div>

      <div class="col-md-9 col-sm-9">
         @if(Auth::user()->user()->membership->type == "individual")
            @if($isProfileVerified == -1)
              <div class="alert alert-dismissible alert-warning">
                <h4>Identity Proof not verified!</h4>
                <p>Your Identity proof has not been verified yet, please wait until to use your services</p>
              </div>
           @endif
           @if($isProfileVerified == 0)
              <div class="alert alert-dismissible alert-warning">
                <h4>Identity Proof not verified!</h4>
                <p>Your Identity proof has been rejected, please <a class="text-info" data-toggle="modal" data-target="#uploadProfileProof" data-id={{ Auth::user()->user()->id }} style="cursor: pointer">click here</a> to upload again</p>
              </div>
           @endif
         @endif

          @can('is-user-payment-verified')
              @if(Auth::user()->user()->membership->type == "individual" && $isProfileVerified)
                @include('frontend.partials._partUserDashboard')
              @elseif(Auth::user()->user()->membership->type == "institutional")
                @include('frontend.partials._partUserDashboard')
              @endif
           @else {{-- checkMembershipPaymentValidity --}}
             @if($unSettledMembershipPayment)
                <div class="alert alert-dismissible alert-warning">
                  @if($reject)
                    <h4>The payments made by you have been rejected by admin</h4>
                  @else
                    <h4>Payments details submitted by you have not been verified by admin yet</h4>
                  @endif
                  <p>you have unsettled payments for your membership, <a href={{ route('viewAllMembershipPayments') }} class="alert-link">click here</a> to see your payments</p>
                </div>
              @else

                @if(!$reject)
                     <div class="alert alert-dismissible alert-warning">
                         <h4>Your membership service have expired</h4>
                         <p>Your payment for membership service have expired. Please make payment for this service to renew your account.</p>
                     </div>
                     @can('is-individual')
                      <div class="row">
                        <div class="col-md-4">
                           <div class="panel dashboard-divs panel-primary">
                              <div class="panel-heading">
                                 <div class="row">
                                    <div class="col-md-12">
                                       <p><span class="glyphicon glyphicon-user"></span>Be a Nominees</p>
                                    </div>
                                 </div> <!-- row -->
                                 <div class="row">
                                    <div class="col-md-12">
                                       <a href={{ route('NomineeRequestForm',Auth::user()->user()->id) }} style="color:#fff">
                                          <span class="pull-left">View Details</span>
                                          <span class="pull-right glyphicon glyphicon-chevron-right"></span>
                                          <div class="clearfix"></div>
                                       </a>
                                    </div>
                                 </div> <!-- row -->

                              </div> <!-- panel-heading -->
                           </div>   <!-- panel -->
                        </div>   <!-- div.md-4 -->
                      </div>
                   @endcan
                @else
                     <div class="alert alert-dismissible alert-warning">
                         <h4>Payments details submitted by you have not been verified by admin yet</h4>
                     </div>
                @endif
             @endif
           @endcan {{-- checkMembershipPaymentValidity --}}
      </div>
   </div>

</div>

{{-- end --}}
<br/>
<br/>
<br/>
<br/>
<br/>

<div class="modal fade" id="uploadProfileProof" tabindex="-1" role="dialog" aria-labelledby="uploadProfileProofLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="uploadProfileProofLabel">Upload Profile ID</h4>
      </div>
      <div class="modal-body">
        {!! Form::open([ 'route' => ['adminEditProfile'], 'id' => "uploadProfileIDForm" , 'files' => true]) !!}
          <div class="form-group">
            <label for="exampleInputFile" class="req">Profile proof</label>
            <input type="file" name="profile_id" id="profile_id">
            <span class="help-block">Please upload your Identity card issued by your respective institution.(file types allowed are 'jpg', 'jpeg', 'png', 'bmp')</span>
          </div>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <input type="submit" name="submit" class="btn btn-primary" value="submit"/>
        {!! Form::close() !!}
      </div>
    </div>
  </div>
</div>

</section>
@endsection