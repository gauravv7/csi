@extends('frontend.master')

@section('page-header')
  <h4>Password</h4>
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
        <div class="col-md-4 col-md-offset-4">
          <h3 style="margin-bottom: 40px; text-align: center">Update Password</h3>
          @if ( $errors->any() )
              <ol class="csi-form-errors alert alert-danger">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
              </ol>
          @endif
            {!! Form::open([ 'route' => ['MemberUpdatePassword'] ]) !!}
              <div class="form-group">
                <label for="exampleInputPassword1" class="req">Old Password</label>
                {!! Form::password('oldpswd', ['class' => 'form-control']) !!}
              </div>
              <div class="form-group">
                <label for="exampleInputPassword1" class="req">New Password</label>
                {!! Form::password('pswd', ['class' => 'form-control']) !!}
              </div>
              <div class="form-group">
                <label for="exampleInputPassword1" class="req">Retype Password </label>
                {!! Form::password('rpswd', ['class' => 'form-control']) !!}
              </div>
              <div class="form-group">
                <input type="submit" class="btn btn-lg btn-block btn-primary" name="submit" value="Submit" />
              </div>
            {!! Form::Close() !!}
         </div>
      </div>
    </div>
@endsection

