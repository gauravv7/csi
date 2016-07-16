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
    @include('frontend.partials._header')
    
    @section('main')
    @show

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script type="text/javascript">window.jQuery || document.write("<script src={{ asset('js/jquery-2.1.4.js') }}>\x3C/script>");</script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
    <!-- <script src="js/verify.min.js"></script> -->
    <script src={{ asset("js/jquery-ui.js") }}></script>
    <script src={{ asset("js/bootstrap.min.js") }}></script>
    @yield('footer-scripts-sidebar')
    @yield('footer-scripts')
  </body>
</html>