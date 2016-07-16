@extends('frontend.master')

@section('page-header')
  <h4>CSI Card</h4>
@endsection

@section('main')
    <div class="container">
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

      <div class="row">
        <div class="col-md-8 col-md-offset-2">
          <h3 style="margin-bottom: 40px;">CSI Card for <small>{{ $username }}</small></h3>
          @can('is-user-payment-verified')


            @if ( $errors->any() )
                <ul class="list-unstyled csi-form-errors alert alert-danger">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
                </ul>
            @elseif(strlen($signature) && strlen($photo))
                @if($is_identity_verified==-1)
                  <div class="alert alert-dismissible alert-warning">
                    <h4>Identity Proof not verified!</h4>
                    <p>Your Identity proof has not been verified yet, please wait until to use your services</p>
                  </div>
                @elseif($is_identity_verified == 0)
                  <div class="alert alert-dismissible alert-warning">
                    <h4>Identity Proof not verified!</h4>
                    <p>Your Identity proof has been rejected, please <a href="#" data-toggle="modal" data-target="#uploadPhotograph" class="btn btn-primary btn-lg btn-block">click here</a> to upload again</p>
                  </div>
                @elseif($is_identity_verified == 1)
                  <a href={{ route('downloadCsiCard') }} class="btn btn-primary btn-lg btn-block">Click Here to download your CSI-Card</a>
                @endif
              @elseif(!strlen($signature) || !strlen($photo))
                <h4 class="text-center">Please provide the required content first</h6>
                @if(!strlen($signature))
                 <a href="#" data-toggle="modal" data-target="#uploadSignature" class="btn btn-primary btn-lg btn-block">please click here to upload your signatures</a>
                @endif
                 <a href="#" data-toggle="modal" data-target="#uploadPhotograph" class="btn btn-primary btn-lg btn-block">please click here to upload your photograph</a>
                @if(!strlen($photo))
                @endif
              @endif
        @else {{-- checkMembershipPaymentValidity --}}
              <div class="alert alert-dismissible alert-danger">
                <h4>Unauthorized Action!</h4>
                <p>Your payments are not verified. Please wait use print your csi card, until the same is verified</p>
              </div>
        @endcan {{-- checkMembershipPaymentValidity --}}
         </div>
      </div>
    </div>
<div class="modal fade" id="uploadSignature" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Upload Signature</h4>
      </div>
      <div class="modal-body">
        {!! Form::open([ 'route' => ['uploadImage', 2], 'files' => true ]) !!}
        <div class="form-group">
          <label for="exampleInputFile" class="req">Signatures</label>
          <input type="file" name="upload_image" id="upload_image">
          <span class="help-block">Please upload your signatures (file types allowed are jgp/png/bmp)</span>
        </div>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <input type="submit" name="submit" class="btn btn-primary" value="submit"/>
        {!! Form::Close() !!}
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="uploadPhotograph" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Upload Photograph</h4>
      </div>
      <div class="modal-body">
        {!! Form::open([ 'route' => ['uploadImage', 1], 'files' => true ]) !!}
        <div class="form-group">
          <label for="exampleInputFile" class="req">Photograph</label>
          <input type="file" name="upload_image" id="upload_image">
          <span class="help-block">Please upload your photograph (file types allowed are jgp/png/bmp)</span>
        </div>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <input type="submit" name="submit" class="btn btn-primary" value="submit"/>
        {!! Form::Close() !!}
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="changeCardName" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Card Name</h4>
      </div>
      <div class="modal-body">
        {!! Form::open([ 'route' => ['EditCardName'] ]) !!}
        <div class="form-group">
          <label for="exampleInputFile" class="req">Name on the Card</label>
          <input type="text" name="card_name" id="card_name">
        </div>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <input type="submit" name="submit" class="btn btn-primary" value="submit"/>
        {!! Form::Close() !!}
      </div>
    </div>
  </div>
</div>
@endsection



@section('footer-scripts')
@endsection

