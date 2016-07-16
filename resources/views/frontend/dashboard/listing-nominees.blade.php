@extends('frontend.master')
@section('title', 'Dashboard home')


@section('main')
<section id="main">

   {{-- start --}}


<div class="container-fluid">

   <div class="row">
      <div class="col-md-12" style="padding-left: 30px;">
            <h2>
               Nominees
            </h2>
          @if (Session::has('flash_notification.message'))
            <div class="alert alert-{{ Session::get('flash_notification.level') }}">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                {{ Session::get('flash_notification.message') }}
            </div>
          @endif

        @if ( $errors->any() )
            <ol class="csi-form-errors alert alert-danger">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
            </ol>
        @endif
      </div>
   </div>

  {{-- can add filter div here --}}

  
<!-- Add anything from here -->
<div class="panel panel-default panel-list">
  <!-- start panel heading -->
  <div class="panel-heading compact-pagination ">
    <div class="row">
      <div class="col-md-3">
        <ul class="list-inline pull-left listing-quick-links">
          <li>
            <a data-toggle="modal" data-target="#addNominee" href="#"><span class="glyphicon glyphicon-plus-sign"></span></a>
          </li>
        </ul>
      </div>
      <div class="col-md-9">
          <!-- some data -->
{{--         <div class="btn-toolbar pull-right">
            <div class="btn-group">
              <a href="#" class="btn btn-default">5</a>
              <a href="#" class="btn btn-default">6</a>
              <a href="#" class="btn btn-default">7</a>
            </div>
          </div> --}}
      </div>
    </div>
 </div>
 <!-- ending panel-heading -->
 {{-- starting list items --}}
  <div class="panel-body">
    @unless(!$members)
      @forelse($members as $user)
      <div class="listing-items">
        <div class="row">
          <div class="col-md-8">
            <h6>{{ $user->individual->getName() }}</h6>
            <p>
              <span>
                Email ID: {{$user->individual->member->email}}
              </span>
            </p>
          </div>
          <div class="col-md-1">
            <h6>
              
            </h6>
          </div>
          <div class="col-md-3" style="padding-top: 15px;">
             <ul class="list-unstyled" style="font-size: 16px">
              <li>
                <a href={{ route('NomineeDelete', ['id'=>$user->id] ) }}><span class="glyphicon glyphicon-remove"></span></a>
                {{--@if( $bulkPayment->getPaidDiff() > 0 )
                  <a href={{ route('BulkPaymentsDoPayment', ['id' => $bulkPayment->id ]) }}  ><span class="glyphicon glyphicon-shopping-cart"></span></a>
                @endif
                @if(!$bulkPayment->is_rejected)
                  <span class="glyphicon glyphicon-ok"></span>
                @endif
                @if($bulkPayment->is_rejected)
                    <a class="label label-info" data-toggle="modal" data-target="#bulkPaymentViewRejectionReason" data-bid={{ $bulkPayment->id }} data-nid={{ $bulkPayment->narration_id }}>Reason</a>
                @endif
                @if(!$bulkPayment->narration)
                  <a data-toggle="modal" data-target="#uploadCSVEdit" data-bid={{ $bulkPayment->id }}> <span class="glyphicon glyphicon-edit"></span> </a>
                @endif
              </li>--}}
            </ul> 
          </div>
        </div> {{-- row --}}
      </div> {{-- listing-items --}}
      @empty 
        <div class="listing-item">
          <div class="row">
            <div class="col-md-12">
              <p>no nominees yet</p>
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
        <div class="col-md-9">
          {{-- other content --}}
        </div>
        <div class="col-md-3">
            {{-- some data --}}
        </div>
      </div>
    </div>
  </div>
<!-- panel -->
<!-- Add anything till here -->

</div>

{{-- end --}}
</section>

{{-- modals --}}

<div class="modal fade" id="addNominee" tabindex="-1" role="dialog" aria-labelledby="uploadCSVLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="uploadCSVLabel">Add Nominee</h4>
      </div>
      <div class="modal-body">
        {!! Form::open([ 'route' => ['NomineeCreate'], 'id' => "addNomineeForm"]) !!}
          <div class="form-group">
            <label for="exampleInputFile" class="req">Email ID</label>
            <input type="text" name="email" id="email">
          </div>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <input type="submit" name="submit" class="btn btn-primary" value="submit"/>
        {!! Form::close() !!}
      </div>
    </div>
  </div>
</div>

@endsection