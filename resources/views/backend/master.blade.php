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
    <link href={{ asset("css/admin.css") }}  rel="stylesheet">

    <!-- Custom Fonts -->
    <link href='https://fonts.googleapis.com/css?family=Poiret+One' rel='stylesheet' type='text/css'> <!--fixed nav font-->
    <link href='https://fonts.googleapis.com/css?family=Lato:400,900,700' rel='stylesheet' type='text/css'> <!-- logo font-->
    <link href='https://fonts.googleapis.com/css?family=Play' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Pathway+Gothic+One' rel='stylesheet' type='text/css'>
    <link href={{ asset("css/font-awesome.min.css") }}  rel="stylesheet" type="text/css">

    @yield('custom-styles')


    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>
    <div id="wrapper">
        <div class="overlay"></div>
    
        <!-- Sidebar -->
         @include('backend.partials._sidebar')
        <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid">
                <div class="row" id="page-header">
                    <div class="col-xs-1 page-collapse-btn">
                        <button type="button" class="hamburger is-closed" data-toggle="offcanvas">
                            <span class="hamb-top"></span>
                            <span class="hamb-middle"></span>
                            <span class="hamb-bottom"></span>
                        </button>
                    </div>
                    <div class="col-xs-11 page-header-menu" id="page-navbar-header">
                        <nav class="navbar navbar-inverse" >
                          <div class="container-fluid">
                            <div class="navbar-header">
                              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-2">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                              </button>
                              <a class="navbar-brand" href="#">@yield('page-header')</a>
                            </div>

                            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-2">
                              <ul class="nav navbar-nav">
                                <li class="active"><a href={{ route('adminDashboard') }}>Home <span class="sr-only">(current)</span></a></li>
                                <!-- <li><a href="#">Link</a></li>
                                <li class="dropdown">
                                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Dropdown <span class="caret"></span></a>
                                  <ul class="dropdown-menu" role="menu">
                                    <li><a href="#">Action</a></li>
                                    <li><a href="#">Another action</a></li>
                                    <li><a href="#">Something else here</a></li>
                                    <li class="divider"></li>
                                    <li><a href="#">Separated link</a></li>
                                    <li class="divider"></li>
                                    <li><a href="#">One more separated link</a></li>
                                  </ul>
                                </li> -->
                              </ul>
                              <ul class="nav navbar-nav navbar-right">
                                <li><a href={{ route('logout') }}><i class="fa fa-sign-out fa-fw"></i>Logout</a></li>
                              </ul>
                            </div>
                          </div>
                        </nav>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        @section('main')
                        @show
                    </div>
                </div>                         

            </div>
        </div>
    </div>
    <!-- /#page-content-wrapper -->

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script type="text/javascript">window.jQuery || document.write("<script src={{ asset('js/jquery-2.1.4.js') }}>\x3C/script>");</script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
    <!-- <script src="js/verify.min.js"></script> -->
    <script src={{ asset("js/jquery-ui.js") }}></script>
    <script src={{ asset("js/bootstrap.min.js") }}></script>
    <script src={{ asset("js/admin.js") }}></script>

    @yield('footer-scripts')

</body>

</html>
