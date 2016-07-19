@extends('frontend.master')
@section('title', 'Register')
@section('main')
    <section id="main">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div>
                        <h1 class="section-header-style">CSI Nominee Request form</h1>
                        <p style="font-size: 25px">Select the Associating Institution for Nominee.</p>
                    </div>


                    @if ( $errors->any() )
                        <ol class="csi-form-errors alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ol>
                    @endif
                    <div class="row page-header">
                        <div class="col-md-8">
                            <h3 id="stepText"> <small id="stepSubText"></small></h3>
                        </div>
                        <div class="col-md-4">
                            <p class="pull-right" style="    font-size: 14px;    margin: 35px 15px; color: RED;font-weight: bold;letter-spacing: 1px;">field with * are required</p>
                        </div>
                    </div>
                    {!! Form::open(['route' => ['NomineeRequest', 'id'=>Auth::user()->user()->id], 'files' => true]) !!}






                        <div class="form-group">
                            <label for="country" class="req">Associating Institute*</label>

                            {!! Form::select('associating_institution', $verified_institutions,null, array('class'=>"form-control", 'id'=>"institution", 'data-form'=>"0")) !!}

                        </div>
                    <div class="btn-group btn-group-justified">

                        <button class="btn btn-default" style="width: 100%" name="submit" id="submit">Submit</button>
                    </div>



                    {!! Form::Close() !!}
                </div>
            </div>
        </div>

    </section>


@endsection


@section('footer-scripts')
    <script src={{ asset("js/validateit.js") }}></script>
    <script src={{ asset('js/function12.js') }}></script>
@endsection

