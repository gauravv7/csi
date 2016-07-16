<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>CSI-India: @yield('title')</title>
    
    <link href='https://fonts.googleapis.com/css?family=Poiret+One' rel='stylesheet' type='text/css'> <!--fixed nav font-->
    <link href='https://fonts.googleapis.com/css?family=Lato:400,900,700' rel='stylesheet' type='text/css'> <!-- logo font-->
    <link href='https://fonts.googleapis.com/css?family=Play' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Pathway+Gothic+One' rel='stylesheet' type='text/css'>
    <!-- Bootstrap -->
    <link href={{ asset("css/bootstrap.min.css") }} rel="stylesheet">
    <link href={{ asset("css/jquery-ui.css") }} rel="stylesheet">
    {{-- <link href={{ asset("css/style.css") }} rel="stylesheet"> --}}
    <link href={{ asset("css/style.materialize.css") }} rel="stylesheet">
    @yield('custom-styles')
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>

<div class="container">
  <div class="row">
    <div class="col-md-8 col-md-offset-2">
      <h3 style="margin-bottom: 40px;">CSI Card for <small>{{ $username }}</small></h3>
        @if($is_identity_verified)
        <div class="row">
          <div class="col-xs-12 csi-card">
              <div class="row">
                <div class="col-xs-3 no-padding">
                  <img src={{ route('UserProfilePhotograph', $photo) }} class="img-responsive" alt="">
                </div>
                <div class="col-xs-9 csi-content">
                  <h5>
                    <span style="">{{ $card_name }}</span>
                    <span class="pull-right">ID: {{ $cid }}</span>
                  </h5>
                   <p>
                    <span>{{ $cat }}</span> for <span>{{ $period }}</span>
                  </p>
                  <p>
                    from <span>{{ $dof }}</span>
                  </p>
                  <img src={{ route('UserProfileSignatures', $signature) }} class="img-responsive signatures" alt="">
                  <a href="#" data-toggle="modal" data-target="#uploadSignature" class="btn-signature-change">
                    <span class="glyphicon glyphicon-pencil"></span>
                  </a>
                </div>
              </div>
          </div> <!-- card -->
        </div>
        @endif
     </div>
  </div>
</div>
  </body>
</html>