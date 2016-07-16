@extends('backend.master')

@section('page-header')
    <h4>Bulk Payments</h4>
@endsection

@section('main')

	
	@if (Session::has('flash_notification.message'))
	    <div class="alert alert-{{ Session::get('flash_notification.level') }}">
	        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	        {{ Session::get('flash_notification.message') }}
	    </div>
	@endif
	<div id="filter">
		<div class="row">
			<div class="col-md-12">
				{!! Form::open(['route' => ['adminMembershipContent'], 'method' => 'get', 'class' => 'form-inline' ]) !!}
					<div class="form-group">
						{!! Form::input('number', 'row', $rows, ['class'=>"form-control", 'id'=>"rows", "min" => '0'] ) !!}
					</div>

					{!! Form::hidden('page', $page) !!}
					<button type="submit" class="btn btn-default pull-right">Search</button>
				{!! Form::close() !!}
			</div>
		</div>
	</div>
		
			
	<h3>Listing All Bulk Payments</h3>
    <div class="panel panel-default panel-list">
    	<div class="panel-heading compact-pagination ">
    		<div class="row">
    			<div class="col-md-3">
    				{{-- other content --}}
    			</div>
				<div class="col-md-9">
    				{!! $bulkPayments->appends(array_except(Request::query(), ['page']) )->render() !!}
    			</div>
    		</div>
    	</div>
        <!-- /.panel-heading -->
        <div class="panel-body">
		@if(!($bulkPayments->isEmpty()))
			@foreach ($bulkPayments as $payment)
				<div class="card">
                	<div class="row">
						<div class="col-md-8">
							<h6>At {{ $payment->created_at->format('l, jS \\of F Y ') }} by {{ $payment->institution->getName() }} </h6>
							<p>
								<span>
									{{ $payment->institution->member->membership->type }}-{{ $payment->institution->membershipType->type }}
									{{ $payment->institution->member->chapter->name }} | {{ $payment->member_count }} | {{ $payment->getFormattedCalculatedAmount() }}
								</span>
							</p>
	                    </div>
						<div class="col-md-1">
							{{-- <h6>
								other content
							</h6> --}}
						</div>
	                    <div class="col-md-3" style="padding-top: 15px;">
	                    	<ul class="list-unstyled" style="font-size: 16px">
	                    		<li>
		                    		@if($payment->is_verified)
										<span class="glyphicon glyphicon-ok"></span>
									@else
										<span class="glyphicon glyphicon-remove"></span>
									@endif
	                    			<a href={{ route('adminMemberBulkPaymentDetails', ['id' => $payment->id]) }}><span class="glyphicon glyphicon-user"></span></a>
	                    		</li>
	                    	</ul>
	                    </div>
	                 </div>
                </div>
			@endforeach
	    @else
	    	<p>No records</p>
	    @endif
            
        </div>
        <!-- panel-footer -->
        <div class="panel-footer compact-pagination">
        	<div class="row">
    			<div class="col-md-3">
    				{{-- other content --}}
    			</div>
				<div class="col-md-9">
    				{!! $bulkPayments->appends(array_except(Request::query(), ['page']) )->render() !!}
    			</div>
    		</div>
        </div>
    </div>
    <!-- /.panel -->
@endsection