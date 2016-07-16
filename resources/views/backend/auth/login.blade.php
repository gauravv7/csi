<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>CSI Admin - Admin Portal</title>

    <!-- Bootstrap Core CSS -->
    <link href={{ asset("css/bootstrap.min.css") }}  rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href={{ asset("css/metisMenu.min.css") }}  rel="stylesheet">

    <!-- Custom CSS -->
    <link href={{ asset("css/sb-admin-2.css") }}  rel="stylesheet">

    <!-- Custom Fonts -->
    <link href={{ asset("css/font-awesome.min.css") }}  rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Please Sign In</h3>
                    </div>
                    <div class="panel-body">
                          @if ( $errors->any() )
                              <ul class="list-unstyled csi-form-errors alert alert-danger">
                              @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                              @endforeach
                              </ul>
                          @endif
                        {!! Form::open(['route' => ['adminLogin'] ]) !!}
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control" placeholder="E-mail" name="email" type="email" autofocus>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Password" id="password" name="password" type="password" value="">
                                </div>
                                <!-- Change this to a button or input when using this as a form -->
                                 <div>
                                    <button class="btn btn-success btn-block" type="submit">Login</button>
                                </div>
                            </fieldset>
                        {!! Form::Close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script type="text/javascript">window.jQuery || document.write("<script src={{ asset('js/jquery-2.1.4.js') }}>\x3C/script>");</script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
    <!-- <script src="js/verify.min.js"></script> -->
    <script src={{ asset("js/jquery-ui.js") }}></script>
    <script src={{ asset("js/bootstrap.min.js") }}></script>
    @yield('footer-scripts')


    <!-- Bootstrap Core JavaScript -->
    <script src={{ asset("js/bootstrap.min.js") }}></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src={{ asset("js/metisMenu.min.js") }}></script>

    <!-- Custom Theme JavaScript -->
    <script src={{ asset("js/sb-admin-2.js") }}></script>

</body>

</html>
