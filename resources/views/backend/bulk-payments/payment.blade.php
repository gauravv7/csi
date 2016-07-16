@extends('backend.master')

@section('page-header')
  <h4>Bulk Payments</h4>
@endsection

@section('main')

      <div class="row">
        <div class="col-md-12">
          @if (Session::has('flash_notification.message'))
              <div class="alert alert-{{ Session::get('flash_notification.level') }}">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  {{ Session::get('flash_notification.message') }}
              </div>
          @endif
          @if ( $errors->any() )
              <ul class="csi-form-errors alert alert-danger">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
              </ul>
          @endif
          </div>
      </div>


      <div class="row">

        <div class="col-md-3"> <!-- profile right area -->
          @include('backend.partials._profileSidebar')
        </div> <!-- profile left area -->

        <!-- profile area right -->
        <div class="col-md-9">
            @if($bulkPayment)
              @include('backend.partials._bulkPaymentView')
            @else
              <div class="alert alert-warning">
                  <h6 style="color: #fff">No payments are done for this service by the user</h6>
              </div>
            @endif
        </div>
      </div>

<div class="modal fade" id="bulkPaymentReject" tabindex="-1" role="dialog" aria-labelledby="bulkPaymentRejectLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="bulkPaymentRejectLabel">Reason for Rejection</h4>
      </div>
      <div class="modal-body">
        {!! Form::open([ 'route' => ['adminMemberBulkPaymentReject', 'id' => -4, 'narration_id' => -2], 'id' => "rejection_form" ]) !!}
          <div class="form-group">
            <label for="rejection-reason" class="control-label">Reason:</label>
            <textarea class="form-control" name="rejection-reason" id="rejection-reason"></textarea>
          </div>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <input type="submit" name="submit" class="btn btn-primary" value="submit"/>
        {!! Form::close() !!}
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="bulkPaymentProof" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Payment Receipt</h4>
      </div>
      <div class="modal-body">
        <img src="" alt="" id="imgProof" class="img-responsive">
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
<div class="modal fade" id="bulkPaymentUpdate" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Update Record</h4>
      </div>
      <div class="modal-body">
        {!! Form::open([ 'route' => ['adminMemberBulkPaymentUpdate', 'narration_id' => -2 ] , 'id' => "update_form" ]) !!}
          <div class="form-group">
            <label for="rejection-reason" class="control-label">Bank</label>
            <input type="text" class="form-control" name="bank" id="bank">
          </div>
          <div class="form-group">
            <label for="rejection-reason" class="control-label">Branch</label>
            <input type="text" class="form-control" name="branch" id="branch">
          </div>
          <div class="form-group">
            <label for="rejection-reason" class="control-label">Amount Paid</label>
            <input type="text" class="form-control" name="amountPaid" id="amountPaid">
          </div>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <input type="submit" name="submit" class="btn btn-primary" value="submit"/>
        {!! Form::close() !!}
      </div>
    </div>
  </div>
</div>
{{-- settle payments here --}}
@endsection


@section('footer-scripts')
<script type="text/javascript">
  $('#bulkPaymentProof').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); // Button that triggered the modal
    var filename = button.data('file');
    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
    url = window.location.origin+"/admin/proofs/"+filename;
    var modal = $(this);
    $.ajax({
      url: url,
      processData : false
    })
      .done(function( data ) {
        console.log(data);
        modal.find('#imgProof').attr('src',data);
      });
    
  });  
  $('#bulkPaymentReject').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); // Button that triggered the modal
    var nid = button.data('nid');
    var bid = button.data('bid'); 
    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.

    var rurl = $('#rejection_form').attr('action');
    rurl = rurl.replace('-4', bid);
    rurl = rurl.replace('-2', nid);
    console.log(rurl);
    $('#rejection_form').attr('action', rurl);
  });
  $('#bulkPaymentUpdate').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); // Button that triggered the modal
    var nid = button.data('nid');
    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.

    var rurl = $('#update_form').attr('action');
    rurl = rurl.replace('-2', nid);
    console.log(rurl);
    $('#update_form').attr('action', rurl);

    var sendInfo = {
      nid : nid
    };

    var modal = $(this)

    var url = window.location.origin+'/';
    $.ajax({
      url : url+"admin/bulk-payments/getresource/narration-update-info",
      method : "POST",
      async : true,
      headers : {
        'X-CSRF-Token': modal.find('input[name="_token"]').val()
      },
      dataType: "json",
      data : sendInfo
    }).success(function(data) {
      if (console && console.log) {
        console.log("Sample of data:", (data!="[]")? data: "false");

          // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
          modal.find('#bank').val(data.bank)
          modal.find('#branch').val(data.branch)
          modal.find('#amountPaid').val(data.amountPaid)
      }
      //$('#amount').text(data);
    }).fail(function(data) {
      alert('some technical error occured. please try again later');
    });

  });
  
  $('#bulkPaymentViewRejectionReason').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); // Button that triggered the modal
    var nid = button.data('nid');
    var bid = button.data('bid'); 

    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
    url = window.location.origin+"/admin/bulk-payments/"+bid+"/rejection-reason/"+nid;
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
  $('document').ready(function(e){
    var $allPanels = $('.payment-list > .panel-body').hide();
    var $allExpandIndicators = $('.panel-heading .click-to-expand span');
    $('.payment-list .panel-heading .click-to-expand').click(function() {
      $allExpandIndicators.removeClass('glyphicon-chevron-up').addClass('glyphicon-chevron-down');
      $(this).children('span').removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-up');
      $allPanels.slideUp();
      $(this).parentsUntil('.panel-heading').parent().next('.panel-body').slideDown();
      return false;
    });
  });
</script>
@endsection