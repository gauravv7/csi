@extends('frontend.master')

@section('page-header')
  <h4>Payements</h4>
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
          @include('frontend.partials._membershipPaymentSidebar')
        </div> <!-- profile left area -->

        <!-- profile area right -->
        <div class="col-md-9">
            @forelse($payments as $payment)
              @include('frontend.partials._membershipPaymentView')
            @empty
              <div class="alert alert-warning">
                  <h6 style="color: #fff">No payments are done for this service by the user, <a href={{ route('createSubmitToPayments', ['entity' => $entity] ) }}>click here</a> to do a payment</h6>
              </div>
            @endforelse
        </div>
      </div>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Reject for Rejection</h4>
      </div>
      <div class="modal-body">
        {!! Form::open([ 'route' => ['adminMemberPaymentReject', 'id' => -4, 'narration_id' => -2], 'id' => "rejection_form" ]) !!}
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
<div class="modal fade" id="exampleModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
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
<div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
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
@endsection


@section('footer-scripts')
<script type="text/javascript">
  $('#exampleModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); // Button that triggered the modal
    var nid = button.data('nid');
    var pid = button.data('pid'); 
    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.

    var rurl = $('#rejection_form').attr('action');
    rurl = rurl.replace('-4', pid);
    rurl = rurl.replace('-2', nid);
    console.log(rurl);
    $('#rejection_form').attr('action', rurl);
  });

  $('#exampleModal1').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); // Button that triggered the modal
    var filename = button.data('file');
    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
    url = window.location.origin+"/proofs/"+filename;
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

  $('#exampleModal2').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); // Button that triggered the modal
    var nid = button.data('nid');
    var pid = button.data('pid'); 

    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
    url = window.location.origin+"/payments/"+pid+"/rejection-reason/"+nid;
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