<div class="col-md-12 col-sm-12 col-xs-12"> <!-- div card -->
  <div class="panel panel-default payment-list">
    <div class="panel-heading">
      <h3>
        #Payment-ID: {{ $payment->id }}
        <a href="#" class="click-to-expand pull-right"><span class="glyphicon glyphicon-chevron-down"></span></a>
      </h3>
      <p class="lead">
        Service Applied - <span class="text-capitalized">{{ $payment->owner->getFormattedEntity()  }}</span> Membership <small>for</small> {{ $payment->paymentHead->servicePeriod->name  }}
        @if($isPaymentBalanced) 
          <span class="label label-success pull-right">verified</span>
        @else
          <span class="label label-danger pull-right">not verified</span>
        @endif
      </p>
      <div class="row">
          <div class="col-md-9">
            <p>
              <span>Total Paid Amount - {{ $payment->getFormattedTotalAmountForJournals() }} </span>
              @if( ($paidDiff = $payment->getPayableDiff()) > 0) 
                <span>Outstanding Amount - {{ $payment->getFormattedAmount($payment->paymentHead->currency->currency_code ,abs($paidDiff) ) }} </span>
              @elseif($paidDiff < 0)
                <span>Overpaid Amount - {{ $payment->getFormattedAmount($payment->paymentHead->currency->currency_code ,abs($paidDiff) ) }} </span>
              @endif
            </p>
          </div>
          <div class="col-md-3">
            <ul class="list-inline sub-heading-list pull-right">
            {{-- settle payments ONLY when we need to give back the overpaid amount --}}
            @if( ($paidDiff < 0) )
  <!--               <li>
                  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#paymentSettle" data-pid={{ $payment->id }}>Settle Payments</button>    
                </li> -->
            @endif
            </ul>
          </div>
      </div> {{-- row --}}
    </div>
    <div class="panel-body">
      @forelse($payment->journals as $j)
        @include('backend.partials._paymentCard')
      @empty
        <p>no narrations available</p>
      @endforelse
    </div>
  </div>
</div> <!-- div card-->