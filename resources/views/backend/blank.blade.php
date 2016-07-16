@extends('backend.master')

@section('custom-styles')
    <!-- Timeline CSS -->
    <link href="../dist/css/timeline.css" rel="stylesheet">
    <!-- Morris Charts CSS -->
    <link href="../bower_components/morrisjs/morris.css" rel="stylesheet">
@endsection

@section('main')
	    <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Title</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-md-12">
                    
                </div>
            <!-- /.row -->
            </div>
        </div>
        <!-- /#page-wrapper -->
@endsection


@section('footer-scripts')
    <!-- Morris Charts JavaScript -->
    <script src={{ asset("raphael-min.js") }}></script>
    <script src={{ asset("morris.min.js") }}></script>
    <script src={{ asset("js/morris-data.js") }}></script>
@endsection