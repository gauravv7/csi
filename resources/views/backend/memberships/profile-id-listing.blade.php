@extends('backend.master')

@section('page-header')
    <h4>Membership - Profile ID</h4>
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
			
				{{-- <ul class="list-unstyled">
					<li>
						<a href="">Institutions</a>
					</li>
				</ul> --}}	
				{!! Form::open(['route' => ['adminProfileIDContent'], 'method' => 'get', 'class' => 'form-inline' ]) !!}
					<div class="form-group">
						{{-- mt = membership type --}}
						{!! Form::select('mt', $membership_types, $mt_selected) !!}
					</div>

					<div class="form-group">
						<div class="checkbox">
							<label>Payments:
								{{-- v = verified --}}
							 	{!! Form::checkbox('v', 1 , ($verified)? true: false, null ) !!}
							 	yes
							</label>
							<label>
								{{-- nv = not verified --}}
							 	{!! Form::checkbox('nv', 1 , ($not_verified)? true: false, null ) !!}
							  	no
							</label>
						</div>
					</div>
					<div class="form-group">
						<div class="checkbox">
							<label>ID:
							<label>
								{{-- pnv = profile not verified/ profile pending --}}
							 	{!! Form::checkbox('pnv', 1 , ($p_not_verified)? true: false, null ) !!}
							  	pending
							</label>
							<label>
								{{-- prv = profile rejected --}}
							 	{!! Form::checkbox('prv', 1 , ($p_rejected)? true: false, null ) !!}
							  	rejected
							</label>
							</label>
								{{-- pv = profile verified --}}
							 	{!! Form::checkbox('pv', 1 , ($p_verified)? true: false, null ) !!}
							 	accepted
						</div>
					</div>
					<div class="form-group">
						{!! Form::input('number', 'row', $rows, ['class'=>"form-control", 'id'=>"rows", "min" => '0'] ) !!}
					</div>
					<div class="form-group">
						{!! Form::select('cat', $cat_select_options, $cat_selected) !!}
						{{-- st = search text --}}
						{!! Form::input('text', 'st', $search_text, ['class' => 'form-control', 'id' => 'search_text']) !!}
					</div>
					{!! Form::hidden('page', $page) !!}
					<button type="submit" class="btn btn-default pull-right">Search</button>
				{!! Form::close() !!}
			</div>
		</div>

	</div>

	
	<h3>Listing All </h3>
            <div class="panel panel-default panel-list">
            	<div class="panel-heading compact-pagination ">
            		<div class="row">
            			<div class="col-md-3">
            				{{-- other content --}}
            			</div>
						<div class="col-md-9">
            				{!! $users->appends(array_except(Request::query(), ['page']) )->render() !!}
            			</div>
            		</div>
            	</div>
                <!-- /.panel-heading -->
                <div class="panel-body">
				@if(!($users->isEmpty()))
					@foreach ($users as $user)
						<div class="card">
                        	<div class="row">
								<div class="col-md-8">
									<h6>{{ $user->getMembership->getName() }}</h6>
									<p>
										<span>
											{{ $user->membership->type }}-{{ $user->getMembership->membershipType->type }}
											{{ $user->chapter->name }}
										</span>
									</p>
			                    </div>
								<div class="col-md-1">
									<h6>
										@if($user->isPaymentBalanced)
											<span class="glyphicon glyphicon-ok"></span>
										@else
											<span class="glyphicon glyphicon-remove"></span>
										@endif
									</h6>
								</div>
			                    <div class="col-md-3" style="padding-top: 15px;">
			                    	<ul class="list-unstyled" style="font-size: 16px">
			                    		<li>
			                    			@if($user->profile_id == -1)
			                    			<a class="text-primary" data-toggle="modal" data-target="#profileProof" data-file={{ $user->id }}><span class="glyphicon glyphicon-user"></span></a>
			                    			@elseif($user->profile_id == 0)
			                    			<a class="text-danger" data-toggle="modal" data-target="#profileProof" data-file={{ $user->id }}><span class="glyphicon glyphicon-user"></span></a>
			                    			@elseif($user->profile_id == 1)
			                    			<a class="text-success" data-toggle="modal" data-target="#profileProof" data-file={{ $user->id }}><span class="glyphicon glyphicon-user"></span></a>
			                    			@endif
			                    			<a class="text-info" data-toggle="modal" data-target="#profile" data-id={{ $user->id }}><span class="glyphicon glyphicon-list-alt"></span></a>
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
            				{!! $users->appends(array_except(Request::query(), ['page']) )->render() !!}
            			</div>
            		</div>
                </div>
            </div>
            <!-- /.panel -->
        </div>
	</div>

<div class="modal fade" id="profileProof" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Profile ID</h4>
      </div>
      <div class="modal-body">
      	<div class="form-group">
        	<a id="accept" class="btn btn-success" href={{ route('adminProfileIDAccept', -4) }}>Accept</a>
        	<a id="reject" class="btn btn-danger" href={{ route('adminProfileIDReject', -4) }}>Reject</a>
        </div>
        <img src="" alt="" id="imgProof" class="img-responsive">
      </div>
    </div>
  </div>
</div>  

<div class="modal fade bs-example-modal-lg" id="profile" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Profile</h4>
      </div>
      <div class="modal-body">
      	<iframe src="" style="zoom:0.60" width="99.6%" height="885" frameborder="0"></iframe>
      </div>
    </div>
  </div>
</div>     
@endsection

@section('footer-scripts')
<script type="text/javascript">
  $('#profileProof').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); // Button that triggered the modal
    var filename = button.data('file');
    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
    url = window.location.origin+"/admin/profile-proofs/"+filename;
    var modal = $(this);
    $.ajax({
      url: url,
      processData : false
    })
      .done(function( data ) {
        console.log(data);
        modal.find('#imgProof').attr('src',data);
      });
    var hrefAccept = modal.find('.modal-body .form-group #accept').attr('href');
    var hrefReject = modal.find('.modal-body .form-group #reject').attr('href');
    hrefAccept = hrefAccept.replace(-4, filename);
    hrefReject = hrefReject.replace(-4, filename);
    modal.find('.modal-body .form-group #accept').attr('href', hrefAccept);
    modal.find('.modal-body .form-group #reject').attr('href', hrefReject);
  }); 
  $('#profile').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); // Button that triggered the modal
    var id = button.data('id');
    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
    url = window.location.origin+"/admin/memberships/profile/"+id+"/view";
    var modal = $(this);
	modal.find('.modal-body iframe').attr('src', url);
  }); 
</script>
@endsection

