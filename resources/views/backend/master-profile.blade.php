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
