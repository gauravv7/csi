<div class="col-md-12 col-sm-12 col-xs-12"> <!-- div card -->
  <div class="panel panel-default payment-list">
    <div class="panel-heading">
      <h3>
        #Bulk Payment-ID: {{ $bulkPayment->id }}
        <a href="#" class="click-to-expand pull-right"><span class="glyphicon glyphicon-chevron-down"></span></a>
      </h3>
      <p class="lead">
        Filed At {{ $bulkPayment->created_at->format('l, jS \of F Y ') }}
         {{-- same route as of user --}}
        <a title="download the uploaded bulk-payment listing file" href={{ route('BulkPaymentsGetFile', ['id'=>$bulkPayment->id] ) }} class="pull-right" style="margin: 0 4px"><span class="glyphicon glyphicon-new-window"></span></a>
        @if($bulkPayment->is_rejected == 0) 
          <span class="label label-success pull-right">verified</span>
        @else
          <span class="label label-danger pull-right">not verified</span>
        @endif
      </p>
      <div class="row">
          <div class="col-md-9">
            <p>
              {{-- <span>Request ID - {{$payment->requestService->first()->id}} </span> --}}
              <span>Total Member Count - {{ $bulkPayment->member_count }} </span>
              <span>Total Paid Amount - {{ $bulkPayment->getFormattedNarrationAmount() }} </span>
              @if( $bulkPayment->getPaidDiff() > 0) 
                <span>Outstanding Amount - {{ $bulkPayment->getFormattedPaidDiff() }} </span>
              @elseif( $bulkPayment->getPaidDiff() > 0 )
                <span>Overpaid Amount - {{ $bulkPayment->getFormattedPaidDiff() }} </span>
              @endif
              
            </p>
          </div>
          <div class="col-md-3">
            <ul class="list-inline sub-heading-list pull-right">
            {{-- can settle payments here, ONLY when we need to give back the overpaid amount --}}
            </ul>
          </div>
      </div> {{-- row --}}
    </div>
    <div class="panel-body">
      @if($narration)
        @include('backend.partials._bulkPaymentCard')
      @else
        <p>no narrations available, the user has to pay yet for the bulk-payments registered</p>
      @endif
    </div>
  </div>
</div> <!-- div card-->