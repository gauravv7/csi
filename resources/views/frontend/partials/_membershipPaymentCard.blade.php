<div class="list-card">
    <div class="row">
      <div class="col-md-12">
        <p class="lead">
          <span>Transaction #: {{ $j->narration->transaction_number }} </span>
          <span class="pull-right">Dated: {{ $j->narration->date_of_payment->toFormattedDateString() }} </span>
        </p>
        <ul class="list-inline sub-heading-list">
          <li>Amount Paid: {{ $j->getFormattedPaidAmount() }}</li>
          <li> 
            @if( $j->is_rejected == -1)
              <span class="label label-warning">pending</span>
            @elseif( $j->is_rejected == 0)
              <span class="label label-success">verified</span>
            @elseif( $j->is_rejected == 1)
              <span class="label label-danger">rejected</span>
            @endif
          </li>
          
        </ul>
      </div>
    </div>
  
  <div class="row">
    <div class="col-md-10">
      <div class="col-md-3">
        <ul class="list-unstyled">
          <li>
            <span class="header">Paid By</span>
             {{ $j->narration->payer->getMembership->getName() }}
          </li>
          <li>
            <span class="header">Drafted Amount</span>
           {{ $j->narration->getFormattedDraftedAmount() }}
          </li>
          
        </ul>
      </div>
      
      <div class="col-md-3">
        <ul class="list-unstyled">
          <li>
            <span class="header">Bank</span>
            {{ $j->narration->bank }}
          </li>
          <li>
            <span class="header">Branch</span>
            {{ $j->narration->branch }}
          </li>
        </ul>
      </div>
      <div class="col-md-3">
        <ul class="list-unstyled">
          <li>
            <span class="header">Payment Mode</span>
            {{ $j->narration->paymentMode->name }}
          </li>
        </ul>
      </div>
    </div>
    <div class="col-md-2">
        <ul class="list-inline sub-heading-list">
          <li>
            @if( ends_with($j->narration->proof, '.pdf') )
              <a role="button" class="btn btn-primary" target="_blank" href={{ route("userPaymentProof",$j->narration->proof) }}>Proofs</a>
            @else
              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal1" data-file={{ $j->narration->proof }}>Proofs</button>
            @endif
          </li>
          @if($j->is_rejected == 1)
            <li>
              <button type="button" class="btn btn-info" data-toggle="modal" data-target="#exampleModal2" data-pid={{ $j->payment_id }} data-nid={{ $j->narration_id }}>Reason</button>
            </li>
           @endif
        </ul>
    </div>
  </div> <!-- row -->
</div> <!-- card -->