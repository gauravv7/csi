<div class="list-card">
          <div class="row">
            <div class="col-md-12">
              <p class="lead">
                <span>Transaction #: {{ $narration->transaction_number }} </span>
                <span class="pull-right">Dated: {{ $narration->date_of_payment->toFormattedDateString() }} </span>
              </p>
              <ul class="list-inline sub-heading-list">
                <li>Amount Paid: {{ $bulkPayment->getFormattedNarrationAmount() }}</li>
                <li> 
                  @if( $bulkPayment->is_rejected == -1)
                    <span class="label label-warning">pending</span>
                  @elseif( $bulkPayment->is_rejected == 0)
                    <span class="label label-success">verified</span>
                  @elseif( $bulkPayment->is_rejected == 1)
                    <span class="label label-danger">rejected</span>
                  @endif
                </li>
                
              </ul>
            </div>
          </div>
        
        <div class="row">
          <div class="col-md-8">
            <div class="row">
              <div class="col-md-12">
                <ul class="list-unstyled list-inline">
                  <li>
                    <span class="header">Paid By</span>
                     {{ $narration->payer->getMembership->getName() }}
                  </li>
                  
                  <li>
                    <span class="header">Bank</span>
                    {{ $narration->bank }}
                  </li>
                </ul>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <ul class="list-unstyled list-inline">
                  <li>
                    <span class="header">Drafted Amount</span>
                   {{ $bulkPayment->getFormattedCalculatedAmount() }}
                  </li>
                  <li>
                    <span class="header">Branch</span>
                    {{ $narration->branch }}
                  </li>
                  <li>
                    <span class="header">Payment Mode</span>
                    {{ $narration->paymentMode->name }}
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <div class="col-md-4">
              <ul class="list-inline sub-heading-list action-list pull-right">
                <li>
                @if(ends_with($narration->proof, ".pdf"))
                  <a class="label label-primary" href={{ route('userPaymentProof', ['filename' => $narration->proof]) }} target="_blank">Proof</a>
                @else
                  <a class="label label-primary" data-toggle="modal" data-target="#bulkPaymentProof" data-file={{ $narration->proof }}>Proof</a>
                @endif
                </li>
                <li>
                  <a class="label label-info" data-toggle="modal" data-target="#bulkPaymentUpdate" data-nid={{ $narration->id }}>Update</a>
                </li>
            @if( ($narration->mode != "online") && ($bulkPayment->is_rejected == -1) ) 
                <li><a href={{ route('adminMemberBulkPaymentAccept', ['id'=> $bulkPayment->id, 'nid' => $narration->id]) }} class="label label-success accept">Accept</a></li>
                <li><a class="label label-danger" data-toggle="modal" data-target="#bulkPaymentReject" data-bid={{ $bulkPayment->id }} data-nid={{ $narration->id }}>Reject</a></li>
            @endif
            @if($bulkPayment->is_rejected == 1)
                <li>
                  <a class="label label-info" data-toggle="modal" data-target="#bulkPaymentViewRejectionReason" data-bid={{ $bulkPayment->id }} data-nid={{ $narration->id }}>Reason</a>
                </li>
            @endif
              </ul>
          </div>
        </div> <!-- row -->
      </div> <!-- card -->