<div class="tab-pane fade active in" id="offline">
	<div class="col-md-6">
		{!! Form::open(['route' => ['DoMembershipPayments', 'id' => $payment->id, 'mode' => 'offline'], 'files' => true, 'id'=>'CreateMembershipSettlingPayment']) !!}
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
                  <h5><span class="glyphicon glyphicon-globe"></span>Applied membership category</h5>
               </div>
            </div> <!-- row -->
            <div class="row">
               <div class="col-md-12">
                <p id="payable-meta">
					<span>{{ $entity }} for <span id="membershipPeriod">{{$membership_period}}</span> (years)</span>
					<span>Membership Fee:&nbsp;<span id="fee">{{ $payment->paymentHead->amount }}</span></span>
					<span>Service Tax:&nbsp;<span id="tax">{{ $payment->paymentHead->serviceTaxClass->tax_rate }}</span>&#37;</span>
					<span>Total Payable Amount:&nbsp;Rs.&nbsp;<span id="payable">{{ $payment->calculatePayable() }}</span></span>
					<span>Total Outstanding Amount:&nbsp;Rs.&nbsp;<span id="outstanding">{{ abs($payment->getPayableDiff()) }}</span></span>
				</p>	
               </div>
            </div> <!-- row -->
            
         </div> <!-- panel-heading -->
      </div>   <!-- panel -->
				
	</div>
</div>

@section('footer-scripts')
	<script src={{ asset("js/validateit.js") }}></script>
	<script src={{ asset('js/make_membership_payment.js') }}></script>
@endsection