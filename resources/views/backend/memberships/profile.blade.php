@extends('backend.master-profile')

@section('page-header')
  <h4>Profile</h4>
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

        <div class="col-md-12 col-sm-12"> <!-- profile right area -->
          @include('backend.partials._profileSidebar')
        </div> <!-- profile left area -->

      </div>

@endsection