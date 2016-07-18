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
          <div class="col-md-8">
            <div class="row">
              <div class="col-md-12 col-sm-12">
                <ul class="list-unstyled list-inline">
                  <li>
                    <span class="header">Paid By</span>
                     {{ $j->narration->payer->getMembership->getName() }}
                  </li>

                  <li>
                    <span class="header">Bank</span>
                    {{ $j->narration->bank }}
                  </li>

                  
                </ul>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12 col-sm-12">
                <ul class="list-unstyled list-inline">

                  <li>
                    <span class="header">Drafted Amount</span>
                   {{ $j->narration->getFormattedDraftedAmount() }}
                  </li>
                  <li>
                    <span class="header">Branch</span>
                    {{ $j->narration->branch }}
                  </li>

                  <li>
                    <span class="header">Payment Mode</span>
                    {{ $j->narration->paymentMode->name }}
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <div class="col-md-4">
              <ul class="list-inline sub-heading-list action-list pull-right">
                <li>
                @if(ends_with($j->narration->proof, ".pdf"))
                  <a class="label label-primary" href={{ route('adminPaymentProof', ['filename' => $j->narration->proof]) }} target="_blank">Proof</a>
                @else
                  <a class="label label-primary" data-toggle="modal" data-target="#paymentProof" data-file={{ $j->narration->proof }}>Proofs</a>
                @endif
                </li>
                <li>
                  <a class="label label-info" data-toggle="modal" data-target="#paymentUpdate" data-pid={{ $j->payment_id }} data-nid={{ $j->narration_id }}>Update</a>
                </li>
            @if( ($j->narration->mode != "online") && ($j->is_rejected == -1) ) 
                <li><a href={{ route('adminMemberPaymentAccept', ['id'=> $j->payment_id, 'nid' => $j->narration_id]) }} class="label label-success accept">Accept</a></li>
                <li><a class="label label-danger" data-toggle="modal" data-target="#paymentReject" data-pid={{ $j->payment_id }} data-nid={{ $j->narration_id }}>Reject</a></li>
            @endif
            @if($j->is_rejected == 1)
                <li>
                  <a class="label label-info" data-toggle="modal" data-target="#paymentViewRejectionReason" data-pid={{ $j->payment_id }} data-nid={{ $j->narration_id }}>Reason</a>
                </li>
            @endif
              </ul>
          </div>
        </div> <!-- row -->
      </div> <!-- card -->