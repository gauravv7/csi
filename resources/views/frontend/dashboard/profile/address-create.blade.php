@extends('frontend.master')

@section('page-header')
  <h4>Address Details</h4>
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
          <h3 style="margin-bottom: 40px;">Create Address details for <small>{{ $username }}</small></h3>
            <a href={{ route('MemberAddressCreate') }} class="help-text"><span class="glyphicon glyphicon-plus"></span> click here, to add more addresses without chapters/student branch details</a>
          @if ( $errors->any() )
              <ol class="csi-form-errors alert alert-danger">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
              </ol>
          @endif
            {!! Form::open([ 'route' => ['MemberAddressStore'] ]) !!}
              @include('frontend.partials._partCreateAddress')
            {!! Form::Close() !!}
         </div>
      </div>
    </div>
@endsection

