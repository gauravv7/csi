@extends('frontend.master')

@section('title', 'Sample')

@section('main')
	<section>
		  <div class="container-fluid">
          @include('frontend.partials._filter_sample')
        
          <!-- Add anything from here -->

            <div class="panel panel-default panel-list">
                <!-- start panel heading -->
                <div class="panel-heading compact-pagination ">
                  <div class="row">
                    <div class="col-md-9">
                      {{-- other content --}}
                    </div>
                    <div class="col-md-3">
                        <!-- some data -->
                        <div class="btn-toolbar pull-right">
                          <div class="btn-group">
                            <a href="#" class="btn btn-default">5</a>
                            <a href="#" class="btn btn-default">6</a>
                            <a href="#" class="btn btn-default">7</a>
                          </div>
                        </div>
                    </div>
                  </div>
               </div>
                <!-- ending panel-heading -->

                {{-- starting list items --}}
                <div class="panel-body">
                    <div class="listing-items">
                      <div class="row">
                        <div class="col-md-8">
                          <h6>ABCD</h6>
                          <p>
                            <span>
                              sub-data
                            </span>
                          </p>
                      </div>
                      <div class="col-md-1">
                        <h6>
                          verified
                        </h6>
                      </div>
                      <div class="col-md-3" style="padding-top: 15px;">
                       <ul class="list-unstyled" style="font-size: 16px">
                          <li>
                            <a href="#">link1</a>
                            <a href=>link2</a>
                          </li>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
                {{-- ending list items --}}

                  <!-- panel-footer -->
                <div class="panel-footer compact-pagination">
                    <div class="row">
                      <div class="col-md-9">
                        {{-- other content --}}
                      </div>
                      <div class="col-md-3">
                          {{-- some data --}}
                      </div>
                    </div>
                  </div>
                </div>
              <!-- /.panel -->
				  <!-- Add anything till here -->
        </div>
	</section>
@endsection