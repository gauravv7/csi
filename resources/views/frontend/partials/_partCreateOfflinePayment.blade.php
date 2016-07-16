<div class="tab-pane fade active in" id="offline">
	<div class="col-md-6">
		{!! Form::open(['route' => ['CreatePayments', 'entity'=>$entity, 'mode' => 'offline'], 'files' => true, 'id' => 'CreateMembershipPayment']) !!}
			@include('frontend.partials._partPayment')
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
					<span>{{ $entity }} for <span id="membershipPeriod"></span></span>
					<span>Membership Fee:&nbsp;<span id="fee"></span></span>
					<span>Service Tax:&nbsp;<span id="tax"></span>&#37;</span>
					<span>Total Payable Amount:&nbsp;<span id="payable"></span></span>
				</p>	
               </div>
            </div> <!-- row -->
            
         </div> <!-- panel-heading -->
      </div>   <!-- panel -->
				
	</div>
</div>

@section('footer-scripts')
	<script src={{ asset("js/validateit.js") }}></script>
	<script src={{ asset('js/make_payment.js') }}></script>
@endsection