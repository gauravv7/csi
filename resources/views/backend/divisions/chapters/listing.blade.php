@extends('backend.master')

@section('page-header')
    <h4>Divisions</h4>
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
				{!! Form::open(['route' => ['adminChapterContent', 'id' => $id, 'state_code' => $state_code], 'method' => 'get', 'class' => 'form-inline' ]) !!}

					<div class="form-group">
						{!! Form::input('number', 'row', $rows, ['class'=>"form-control", 'id'=>"rows", "min" => '0'] ) !!}
					</div>
					{!! Form::hidden('page', $page) !!}
					<button type="submit" class="btn btn-default pull-right">Search</button>
				{!! Form::close() !!}
			</div>
		</div>

	</div>
		
			
	<h3>Listing Chapters</h3>
	    <div class="panel panel-default panel-list">
	    	<div class="panel-heading compact-pagination ">
	    		<div class="row">
	    			<div class="col-md-3">
	    				<ul class="list-inline pull-left listing-quick-links">
				          <li>
							<a href="#" data-toggle="modal" data-target="#uploadChapters" ><span class="glyphicon glyphicon-cloud-upload"></a>
				          </li>
				        </ul>
	    			</div>
					<div class="col-md-9">
	    				{!! $chapters->appends(array_except(Request::query(), ['page']) )->render() !!}
	    			</div>
	    		</div>
	    	</div>
            <!-- /.panel-heading -->
            <div class="panel-body">
			@if(!($chapters->isEmpty()))
				@foreach ($chapters as $chapter)
					<div class="card">
                    	<div class="row">
							<div class="col-md-8">
								<h6>{{ $chapter->name }}</h6>
								<p>
									<span>
										{{ $chapter->state->state->name }}, {{ $chapter->state->region->country->name }}, {{ $chapter->state->region->name }}
									</span>
								</p>
		                    </div>
							<div class="col-md-1">
								<h6>
									{{-- some profile content --}}
								</h6>
							</div>
		                    <div class="col-md-3" style="padding-top: 15px;">
		                    	<ul class="list-unstyled" style="font-size: 16px">
		                    		<li>
		                    			<a href="#" data-toggle="modal" data-target="#EditChapter" data-cid={{ $chapter->id }}><span class="glyphicon glyphicon-edit"></a>
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
	    				{!! $chapters->appends(array_except(Request::query(), ['page']) )->render() !!}
	    			</div>
	    		</div>
	        </div>
	    </div>
	    <!-- /.panel -->
	</div>
	</div>
	        

{{-- modals --}}

<div class="modal fade" id="uploadChapters" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Upload Chapters</h4>
      </div>
      <div class="modal-body">
        {!! Form::open([ 'route' => ['adminChapterUpload', 'id' => $id, 'state_code' => $state_code], 'id' => "uploadChaptersForm" , 'files' => true]) !!}
          <div class="form-group">
            <label for="exampleInputFile" class="req">Upload Chapters</label>
            <input type="file" name="uploadChaptersField" id="uploadChaptersField">
            <span class="help-block">Please upload your payment document.(file types allowed are 'csv')</span>
          </div>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <input type="submit" name="submit" class="btn btn-primary" value="submit"/>
        {!! Form::close() !!}
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="EditChapter" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Edit Region</h4>
      </div>
      <div class="modal-body">
        {!! Form::open([ 'route' => ['adminChapterEdit', 'id' => $id, 'state_code' => $state_code, 'chapter_id' => -2], 'id' => "EditChapterForm"]) !!}
          <div class="form-group">
          	<label for="EditChapterName">Chapter Name</label>
            <input type="text" name="EditChapterName" id="EditChapterName">
          </div>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <input type="submit" name="submit" class="btn btn-primary" value="submit"/>
        {!! Form::close() !!}
      </div>
    </div>
  </div>
</div>
@endsection

@section('footer-scripts')
<script type="text/javascript">
  $('#EditChapter').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); // Button that triggered the modal
    var cid = button.data('cid');
    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.

    var rurl = $('#EditChapterForm').attr('action');
    rurl = rurl.replace('-2', cid);
    console.log(rurl);
    $('#EditChapterForm').attr('action', rurl);
  });
</script>
@endsection