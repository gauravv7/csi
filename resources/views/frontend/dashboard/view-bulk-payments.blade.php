@extends('frontend.master')
@section('title', 'Dashboard home')


@section('main')
<section id="main">

   {{-- start --}}


<div class="container-fluid">

   <div class="row">
      <div class="col-md-12" style="padding-left: 30px;">
            <h2>
               Bulk Payments
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
      <div class="col-md-8">
        <ul class="list-inline pull-left listing-quick-links">
          <li>
            <a class="btn btn-success btn-xs pull-left" data-toggle="modal" data-target="#uploadCSV" href="#" title="add a bulk-payment" style="text-transform: capitalize; font-size: 14px;"><span class="glyphicon glyphicon-plus"></span>Upload Bulk Payments Data File</a>
          </li>
          <li>
            <a  class="btn btn-success btn-xs pull-left"  href={{ route('BulkPaymentsGetFileSample') }} title="download sample file for bulk-payments" style="text-transform: capitalize; font-size: 14px;"><span class="glyphicon glyphicon-file"></span>Download Sample Bulk Payment Data File</a>
          </li>
          <li>
            <a  class="btn btn-warning btn-xs pull-left"  href="#" title="view Instructions" style="text-transform: capitalize; font-size: 14px;" data-toggle="modal" data-target="#InstructionBulkPayments"><span class="glyphicon glyphicon-question-sign"></span>Click here for help</a>
          </li>
        </ul>
      </div>
      <div class="col-md-9">
          <!-- some data 
          <div class="btn-toolbar pull-right">
            <div class="btn-group">
              <a href="#" class="btn btn-default">5</a>
              <a href="#" class="btn btn-default">6</a>
              <a href="#" class="btn btn-default">7</a>
            </div>
          </div>
          -->
      </div>
    </div>
 </div>
 <!-- ending panel-heading -->
 {{-- starting list items --}}
  <div class="panel-body">
    @unless(!$bulkPayments)
      @forelse($bulkPayments as $bulkPayment)
      <div class="listing-items">
        <div class="row">
          <div class="col-md-6">
            <h6>{{ $bulkPayment->created_at->format('l, jS \\of F Y ') }}</h6>
              <p>
              <span>
                Member Count: {{$bulkPayment->member_count}}, Total Calculated: {{ $bulkPayment->getFormattedCalculatedAmount() }}, Total Paid: {{ $bulkPayment->getFormattedNarrationAmount() }}, Total Remaining: {{ $bulkPayment->getFormattedPaidDiff() }}
              </span>
            </p>
          </div>
          <div class="col-md-1">
            <h6>
              
            </h6>
          </div>
          <div class="col-md-5" style="padding-top: 15px;">
            <ul class="list-unstyled bulk-payments" style="font-size: 16px">
              <li>
                <a class="btn btn-primary btn-xs pull-left" href={{ route('BulkPaymentsGetFile', ['id'=>$bulkPayment->id] ) }} title="download current file"><span class="glyphicon glyphicon-new-window"></span>Download uploaded Records</a>
              </li>
              @if( $bulkPayment->getPaidDiff() > 0 )
              <li>
                  <a class="btn btn-success btn-xs pull-left" title="click here to pay for this bulk-payment" href={{ route('BulkPaymentsDoPayment', ['id' => $bulkPayment->id ]) }}  ><span class="glyphicon glyphicon-shopping-cart"></span>Make Payment</a>
              </li>
              @endif
              @if(!$bulkPayment->is_rejected)
              <li>
                  <span class="glyphicon glyphicon-ok"></span>
              </li>
              @endif
              @if($bulkPayment->is_rejected==1)
              <li>
                    <a class="label label-info" title="show rejection reason" data-toggle="modal" data-target="#bulkPaymentViewRejectionReason" data-bid={{ $bulkPayment->id }} data-nid={{ $bulkPayment->narration_id }}>Reason</a>
              </li>
              @endif
              @if(!$bulkPayment->narration)
              <li>
                  <a class="btn btn-warning btn-xs pull-left" data-toggle="modal" title="upload a revised document" data-target="#uploadCSVEdit" data-bid={{ $bulkPayment->id }}> <span class="glyphicon glyphicon-edit"></span> Upload Revised Records </a>
              </li>
              @endif
            </ul>
          </div>
        </div> {{-- row --}}
      </div> {{-- listing-items --}}
      @empty 
        <div class="listing-item">
          <div class="row">
            <div class="col-md-12">
              <p>no bulk payments yet</p>
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

<div class="modal fade" id="uploadCSV" tabindex="-1" role="dialog" aria-labelledby="uploadCSVLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="uploadCSVLabel">Bulk Payment</h4>
      </div>
      <div class="modal-body">
        {!! Form::open([ 'route' => ['BulkPaymentsCreate'], 'id' => "uploadCSVForm" , 'files' => true]) !!}
          <div class="form-group">
            <label for="exampleInputFile" class="req">List of Members</label>
            <input type="file" name="listOfMembers" id="listOfMembers">
            <span class="help-block">Please upload your payment document.(file types allowed are 'csv')</span>
          </div>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <input type="submit" name="submit" class="btn btn-primary" value="submit"/>
        {!! Form::close() !!}
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="bulkPaymentViewRejectionReason" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Reason of Rejection</h4>
      </div>
      <div class="modal-body">
        <p id="rejectionReason">
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="uploadCSVEdit" tabindex="-1" role="dialog" aria-labelledby="uploadCSVEditLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="uploadCSVEditLabel">Bulk Payment</h4>
      </div>
      <div class="modal-body">
        {!! Form::open([ 'route' => ['BulkPaymentsEdit', 'id' => -2], 'id' => "uploadCSVFormEdit" , 'files' => true]) !!}
          <div class="form-group">
            <label for="exampleInputFile" class="req">List of Members</label>
            <input type="file" name="listOfMembers" id="listOfMembers">
            <span class="help-block">Please upload your payment document.(file types allowed are 'csv')</span>
          </div>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <input type="submit" name="submit" class="btn btn-primary" value="submit"/>
        {!! Form::close() !!}
      </div>
    </div>
  </div>
</div>

<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" id="InstructionBulkPayments" aria-labelledby="mySmallModalLabel">
<div class="modal-dialog">
    <div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Instruction for Bulk payments</h4>
  </div>
  <div class="modal-body">
    <ul class="list-unstyled instructions">
      <li><strong>Step 1:</strong> Download Sample CSV<li>
      <li><strong>Step 2:</strong> Upload CSV containing data of new members</li>
      <li><strong>Step 3:</strong> Make Payment</li>
    </ul>
  </div>
  </div>
</div>
</div>

@endsection

@section('footer-scripts')
<script type="text/javascript">
  jQuery(document).ready(function($) {
    $('#InstructionBulkPayments').modal('show');  
  });
  $('#uploadCSVEdit').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); // Button that triggered the modal
    var bid = button.data('bid');
    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.

    var rurl = $('#uploadCSVFormEdit').attr('action');
    rurl = rurl.replace('-2', bid);
    console.log(rurl);
    $('#uploadCSVFormEdit').attr('action', rurl);
  });
  $('#bulkPaymentViewRejectionReason').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); // Button that triggered the modal
    var nid = button.data('nid');
    var bid = button.data('bid'); 

    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
    url = window.location.origin+"/bulk-payments/"+bid+"/rejection-reason/"+nid;
    var modal = $(this);
    $.ajax({
      url: url,
      processData : false
    })
      .done(function( data ) {
        console.log(data);
        modal.find('#rejectionReason').text(data);
      });
  });
</script>
@endsection