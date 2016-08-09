@extends('backend.master')

@section('page-header')
    <h4>Memberships</h4>
@endsection


<style type="text/css">
    .ui-datepicker {
        background-color: #fff;
        border: 1px solid #66AFE9;
        border-radius: 4px;
        box-shadow: 0 0 8px rgba(102, 175, 233, .6);
        display: none;
        margin-top: 4px;
        padding: 10px;
        width: 240px;
    }

    .ui-datepicker a,
    .ui-datepicker a:hover {
        text-decoration: none;
    }

    .ui-datepicker a:hover,
    .ui-datepicker td:hover a {
        color: #2A6496;
        -webkit-transition: color 0.1s ease-in-out;
        -moz-transition: color 0.1s ease-in-out;
        -o-transition: color 0.1s ease-in-out;
        transition: color 0.1s ease-in-out;
    }

    .ui-datepicker .ui-datepicker-header {
        margin-bottom: 4px;
        text-align: center;
    }

    .ui-datepicker .ui-datepicker-title {
        font-weight: 700;
    }

    /*.ui-datepicker .ui-datepicker-prev,*/
    /*.ui-datepicker .ui-datepicker-next {*/
    /*cursor: default;*/
    /*font-family: 'Glyphicons Halflings';*/
    /*-webkit-font-smoothing: antialiased;*/
    /*font-style: normal;*/
    /*font-weight: normal;*/
    /*height: 20px;*/
    /*line-height: 1;*/
    /*margin-top: 2px;*/
    /*width: 30px;*/
    /*}*/
    /*.ui-datepicker .ui-datepicker-prev {*/
    /*float: left;*/
    /*text-align: left;*/
    /*}*/
    /*.ui-datepicker .ui-datepicker-next {*/
    /*float: right;*/
    /*text-align: right;*/
    /*}*/
    /*.ui-datepicker .ui-datepicker-prev:before {*/
    /*content: "\e079";*/
    /*}*/
    /*.ui-datepicker .ui-datepicker-next:before {*/
    /*content: "\e080";*/
    /*}*/
    .ui-datepicker .ui-icon {
        display: none;
    }

    .ui-datepicker .ui-datepicker-calendar {
        table-layout: fixed;
        width: 100%;
    }

    .ui-datepicker .ui-datepicker-calendar th,
    .ui-datepicker .ui-datepicker-calendar td {
        text-align: center;
        padding: 4px 0;
    }

    .ui-datepicker .ui-datepicker-calendar td {
        border-radius: 4px;
        -webkit-transition: background-color 0.1s ease-in-out, color 0.1s ease-in-out;
        -moz-transition: background-color 0.1s ease-in-out, color 0.1s ease-in-out;
        -o-transition: background-color 0.1s ease-in-out, color 0.1s ease-in-out;
        transition: background-color 0.1s ease-in-out, color 0.1s ease-in-out;
    }

    .ui-datepicker .ui-datepicker-calendar td:hover {
        background-color: #eee;
        cursor: pointer;
    }

    .ui-datepicker .ui-datepicker-calendar td a {
        text-decoration: none;
    }

    .ui-datepicker .ui-datepicker-current-day {
        background-color: #4289cc;
    }

    .ui-datepicker .ui-datepicker-current-day a {
        color: #fff
    }

    .ui-datepicker .ui-datepicker-calendar .ui-datepicker-unselectable:hover {
        background-color: #fff;
        cursor: default;
    }
</style>

@section('main')
 
        


                

            
            <h3>Nominees({{$institution_name}})</h3>
            <!-- Add anything from here -->
                <div class="panel panel-default panel-list">
                    <!-- start panel heading -->
                    <div class="panel-heading compact-pagination ">
                        <div class="row">
                            
                            <div class="col-md-12">
                                {!! $members->appends(array_except(Request::query(), ['page']) )->render() !!}
                            </div>
                        </div>
                    </div>
                    <!-- ending panel-heading -->
                    {{-- starting list items --}}
                    <div class="panel-body">
                        <div class="listing-items">
                            @unless(!$members)
                                @forelse($members as $user)
                                    <div class="listing-items">
                                        <div class="card">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6>{{ $user->individual->getName() }}</h6>
                                                <p><span>Email ID: {{$user->individual->member->email}}
              </span>
                                                </p>
                                            </div>
                                            <div class="col-md-1">
                                                <h6>
                                                    @if($user->is_nominee==\App\Enums\ActionStatus::approved)
                                                        <span class="label label-success">Accepted</span>
                                                    @elseif($user->is_nominee==\App\Enums\ActionStatus::pending)
                                                        <span class="label label-primary">Pending</span>
                                                    @elseif($user->is_nominee==\App\Enums\ActionStatus::cancelled && $user->associating_institution_id!=null)
                                                        <span class="label label-warning">Cancelled</span>
                                                    @elseif($user->is_nominee==\App\Enums\ActionStatus::cancelled && $user->associating_institution_id==null)
                                                        <span class="label label-danger">Rejected</span>
                                                    @endif
                                                </h6>
                                            </div>


                                            <div class="col-md-2">
                                                <ul class="list-unstyled list-inline pull-right" style="font-size: 16px">
                                                   
                                                    <li>
                                                        <a class="btn btn-info" data-toggle="modal"
                                                           data-target="#profile"
                                                           data-id={{ $user->individual->member->id }}><span
                                                                    class="glyphicon glyphicon-list-alt"></span>View
                                                            Profile</a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="col-md-2">
                                            </div>


                                        </div> {{-- row --}}
                                        
                                        </div>
                                    </div> {{-- listing-items --}}
                                @empty
                                    <div class="listing-item">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <p>no nominees yet</p>
                                            </div>
                                        </div>
                                    </div>
                        </div>
                        @endforelse
                        @endunless
                    </div>
                {{-- ending list items --}}

                <!-- panel-footer -->
                    <div class="panel-footer compact-pagination">
                        <div class="row">
                            <div class="col-md-3">
                                {{-- other content --}}
                            </div>
                            <div class="col-md-9">
                                {!! $members->appends(array_except(Request::query(), ['page']) )->render() !!}
                            </div>
                        </div>
                    </div>
                </div>
                <!-- panel -->
                <!-- Add anything till here -->

            </div>

            {{-- end --}}
        </div>
        </div>
    </section>

    {{-- modals --}}

   

    <div class="modal fade bs-example-modal-lg" id="profile" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="exampleModalLabel">Profile</h4>
                </div>
                <div class="modal-body">
                    <iframe src="" style="zoom:0.60" width="99.6%" height="885" frameborder="0"></iframe>
                </div>
            </div>
        </div>
    </div>













@endsection

@section('footer-scripts')
    <script>
        $(function () {
            $("#request_to_date").datepicker({
                inline: true,
                dateFormat: 'yy-mm-dd',
                changeMonth: true,
                changeYear: true,
            }).val();

            $("#request_from_date").datepicker({
                inline: true,
                dateFormat: 'yy-mm-dd',
                changeMonth: true,
                changeYear: true,
            }).val();
        });

        $('#profile').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var id = button.data('id');
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            url = window.location.origin + "/userprofile/" + id + "/view";
            var modal = $(this);
            modal.find('.modal-body iframe').attr('src', url);
        });
    </script>
    <script src={{ asset("js/validateit.js") }}></script>
@endsection

