@extends('backend.master')

@section('page-header')
    <h4>Student Branches</h4>
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
				{!! Form::open(['route' => ['adminStudentBranchContent'], 'method' => 'get', 'class' => 'form-inline' ]) !!}
					<div class="form-group">
						{!! Form::input('number', 'row', $rows, ['class'=>"form-control", 'id'=>"rows", "min" => '0'] ) !!}
					</div>

					{!! Form::hidden('page', $page) !!}
					<button type="submit" class="btn btn-default pull-right">Search</button>
				{!! Form::close() !!}
			</div>
		</div>
	</div>
		
			
	<h3>Listing All Student Branches</h3>
    <div class="panel panel-default panel-list">
    	<div class="panel-heading compact-pagination ">
    		<div class="row">
    			<div class="col-md-3">
    				{{-- other content --}}
    			</div>
				<div class="col-md-9">
    				{!! $branches->appends(array_except(Request::query(), ['page']) )->render() !!}
    			</div>
    		</div>
    	</div>
        <!-- /.panel-heading -->
        <div class="panel-body">
		@if(!($branches->isEmpty()))
			@foreach ($branches as $branch)
				<div class="card">
                	<div class="row">
						<div class="col-md-8">
							<h6>At {{ $branch->created_at->format('l, jS \\of F Y ') }} by {{ $branch->institution->member->getMembership->getName() }} </h6>
							<p>
								<span>
									{{ $branch->institution->member->getEntity() }}
									{{ $branch->institution->member->chapter->name }}
								</span>
							</p>
	                    </div>
						<div class="col-md-1" style="padding-top: 15px;">
	                		@if($branch->institution->member->getMembership->subType->is_student_branch==-1)
								<h5 class="label label-primary">pending</h5>
							@elseif($branch->institution->member->getMembership->subType->is_student_branch==0)
								<h5 class="label label-danger">cancelled</h5>
							@elseif($branch->institution->member->getMembership->subType->is_student_branch==1)
								<h5 class="label label-success">verified</h5>
							@endif
						</div>
	                    <div class="col-md-3" style="padding-top: 15px;">
	                    	<ul class="list-unstyled" style="font-size: 16px">
	                    		<li>
	                    		@if( $branch->institution->member->getMembership->subType->is_student_branch == 0 ) 
	                    			<a class="btn btn-danger" data-toggle="modal" data-target="#StudentBranchViewRejectionReason" data-sid={{$branch->id}}>View Reason</a>
	                    		@elseif( $branch->institution->member->getMembership->subType->is_student_branch == -1)
	                    			<a class="btn btn-danger" data-toggle="modal" data-target="#StudentBranchReject" data-sid={{$branch->id}}>Reject</a>
	                    			<a class="btn btn-success" href={{ route('adminStudentBranchConfirm', ['id' => $branch->id]) }}>Accept</a>
	                    		@endif
	                    			<a class="btn btn-info" data-toggle="modal" data-target="#profile" data-id={{ $branch->institution->member->id }}>View Profile</a>
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
    				{!! $branches->appends(array_except(Request::query(), ['page']) )->render() !!}
    			</div>
    		</div>
        </div>
    </div>
    <!-- /.panel -->

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
<div class="modal fade" id="StudentBranchReject" tabindex="-1" role="dialog" aria-labelledby="StudentBranchRejectLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="StudentBranchRejectLabel">Reason for Rejection</h4>
      </div>
      <div class="modal-body">
        {!! Form::open([ 'route' => ['adminStudentBranchDecline', 'id' => -4], 'id' => "student_branch_rejection_form" ]) !!}
          <div class="form-group">
            <label for="rejection-reason" class="control-label">Reason:</label>
            <textarea class="form-control" name="rejection-reason" id="rejection-reason"></textarea>
          </div>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <input type="submit" name="submit" class="btn btn-primary" value="submit"/>
        {!! Form::close() !!}
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="StudentBranchViewRejectionReason" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Reason of Rejection</h4>
      </div>
      <div class="modal-body">
        <p id="rejectionReason">
      </div>
    </div>
  </div>
</div>
@endsection

@section('footer-scripts')
<script type="text/javascript">
  $('#StudentBranchReject').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); // Button that triggered the modal
    var sid = button.data('sid');
    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.

    var rurl = $('#student_branch_rejection_form').attr('action');
    rurl = rurl.replace('-4', sid);
    console.log(rurl);
    $('#student_branch_rejection_form').attr('action', rurl);
  });
  $('#StudentBranchViewRejectionReason').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); // Button that triggered the modal
    var sid = button.data('sid');

    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
    url = window.location.origin+"/admin/student-branch/"+sid+"/decline";
    var modal = $(this);
    $.ajax({
      url: url,
      processData : false
    })
      .done(function( data ) {
        console.log(data);
        modal.find('#rejectionReason').text(data);
      });
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