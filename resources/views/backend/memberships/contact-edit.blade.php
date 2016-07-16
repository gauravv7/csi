@extends('backend.master')

@section('page-header')
  <h4>Contact Details</h4>
@endsection

@section('main')

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
          <h3>Edit Contact details for <small>{{ $member->getMembership->getName() }}</small></h3>
          @if ( $errors->any() )
              <ul class="csi-form-errors alert alert-danger list-unstyled">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
              </ul>
          @endif
            {!! Form::open([ 'route' => ['adminMemberContactDetails', 'id'=>$member->id] ]) !!}
              @include('backend.partials._partContact')
            {!! Form::Close() !!}
         </div>
      </div>
@endsection

