  <div class="col-md-12 col-sm-12 col-xs-12"> <!-- div card -->
    <div class="panel panel-default" id="dd-list">
      <div class="panel-heading">
        <h6>
          Bulk Payment History
          {{-- <a class="edit-content" href={{ route('BulkPaymentsCreate') }} ><span class="glyphicon glyphicon-plus-sign"></span></a> --}}
        </h6>
      </div>
      <div class="panel-body">
        <ul class="list-unstyled">
        @forelse($bulkPayments as $payment)
            <li>
              {{ $payment->date_of_payment->toFormattedDateString() }}, {{ $payment->drafted_amount }}              
            </li>
        @empty
          <li>no bulk payments yet</li>
        @endforelse
        </ul>
      </div>
    </div>
  </div> <!-- div card-->

        