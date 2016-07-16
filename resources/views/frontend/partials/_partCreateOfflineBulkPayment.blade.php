<div class="tab-pane fade active in" id="offline">
	<div class="col-md-6">
		{!! Form::open([ 'route' => ['BulkPaymentsStore', 'id' => $bulkPayment->id, 'mode' => 'offline'], 'files' => true ]) !!}
		<div class="steps membership-payment">
          @include('frontend.partials._partMembershipPayment')
        </div>
        {!! Form::Close() !!}
	</div>
	<div class="col-md-6">
      <div class="panel payment_panel_amount panel-primary">
         <div class="panel-heading">
            <div class="row">
               <div class="col-md-12">
                  <h5><span class="glyphicon glyphicon-globe"></span>Bulk Payments-Students</h5>
               </div>
            </div> <!-- row -->
            <div class="row">
               <div class="col-md-12">
                <p id="payable-meta">
        					<span>Member Count:&nbsp;<span id="count">{{ $bulkPayment->member_count }}</span></span>
        					<span>Total Drafted Amount:&nbsp;<span id="payable">{{  $bulkPayment->getFormattedCalculatedAmount() }}</span></span>
        					<span>Total Paid Amount:&nbsp;<span id="payable">{{  $bulkPayment->getFormattedNarrationAmount() }}</span></span>
        					<span>Total Payable Amount:&nbsp;<span id="payable">{{  $bulkPayment->getFormattedPaidDiff() }}</span></span>
        				</p>
               </div>
            </div> <!-- row -->
            
         </div> <!-- panel-heading -->
      </div>   <!-- panel -->
				
	</div>
</div>

@section('footer-scripts')
	<script src={{ asset("js/validateit.js") }}></script>
	<script src={{ asset('js/make_bulk_payment.js') }}></script>
@endsection