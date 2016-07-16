@extends('backend.master')

@section('page-header')
    <div class="col-md-2">
        <h4>Dashboard</h4>
    </div>
    <div class="col-md-10">
        
    </div>
@endsection

@section('main')
  <div class="row">
    <div class="col-md-12">
      <h2>Membership Payments</h2>    
      <!-- /.row -->
      <div class="row">
         <div class="col-md-3">
            <div class="panel dashboard-divs panel-primary">
               <div class="panel-heading fixed-height">
                  <div class="row">
                     <div class="col-md-12">
                        <h5 style="color: #fff">Academic Institutions Memberships</h5>
                        <p>There are <span class="badge">{{ $counter_academic }}</span> request pending</p>
                     </div>
                  </div> <!-- row -->
                  <div class="row">
                     <div class="col-md-12">
                        <a href={{ route('adminMembershipContent', ['mt'=>1, 'nv'=>1, 'row'=>15, 'cat'=>0,'page'=>1]) }} style="color:#fff">
                           <span class="pull-left">Click here to process pending payments</span>
                           <span class="pull-right glyphicon glyphicon-chevron-right"></span>
                           <div class="clearfix"></div>
                        </a>
                        <a href={{ route('adminMembershipContent', ['mt'=>1, 'nv'=>0, 'v'=>1, 'row'=>15, 'cat'=>0,'page'=>1]) }} style="color:#fff">
                           <span class="pull-left">Click here to process accepted payments</span>
                           <span class="pull-right glyphicon glyphicon-chevron-right"></span>
                           <div class="clearfix"></div>
                        </a>
                        <a href={{ route('adminMembershipContent', ['mt'=>1, 'nv'=>1, 'v'=>1, 'row'=>15, 'cat'=>0,'page'=>1]) }} style="color:#fff">
                           <span class="pull-left">Click here to process available payments</span>
                           <span class="pull-right glyphicon glyphicon-chevron-right"></span>
                           <div class="clearfix"></div>
                        </a>
                     </div>
                  </div> <!-- row -->
                  
               </div> <!-- panel-heading -->
            </div>   <!-- panel -->
         </div>   <!-- div.md-4 -->         

         <div class="col-md-3">
            <div class="panel dashboard-divs panel-primary">
               <div class="panel-heading">
                  <div class="row">
                     <div class="col-md-12">
                        <h5 style="color: #fff">Non Academic Institutions Memberships</h5>
                        <p>There are <span class="badge">{{ $counter_non_academic }}</span> request pending</p>
                     </div>
                  </div> <!-- row -->
                  <div class="row">
                     <div class="col-md-12">
                        <a href={{ route('adminMembershipContent', ['mt'=>2, 'nv'=>1, 'row'=>15, 'cat'=>0,'page'=>1]) }} style="color:#fff">
                           <span class="pull-left">Click here to process pending payments</span>
                           <span class="pull-right glyphicon glyphicon-chevron-right"></span>
                           <div class="clearfix"></div>
                        </a>
                        <a href={{ route('adminMembershipContent', ['mt'=>2, 'nv'=>0, 'v' => 1, 'row'=>15, 'cat'=>0,'page'=>1]) }} style="color:#fff">
                           <span class="pull-left">Click here to process accepted payments</span>
                           <span class="pull-right glyphicon glyphicon-chevron-right"></span>
                           <div class="clearfix"></div>
                        </a>
                        <a href={{ route('adminMembershipContent', ['mt'=>2, 'nv'=>1, 'v' => 1, 'row'=>15, 'cat'=>0,'page'=>1]) }} style="color:#fff">
                           <span class="pull-left">Click here to process available payments</span>
                           <span class="pull-right glyphicon glyphicon-chevron-right"></span>
                           <div class="clearfix"></div>
                        </a>
                     </div>
                  </div> <!-- row -->
                  
               </div> <!-- panel-heading -->
            </div>   <!-- panel -->
         </div>   <!-- div.md-4 -->         
      
         <div class="col-md-3">
            <div class="panel dashboard-divs panel-primary">
               <div class="panel-heading">
                  <div class="row">
                     <div class="col-md-12">
                        <h5 style="color: #fff">Student Individuals Memberships</h5>
                        <p>There are <span class="badge">{{ $counter_student }}</span> request pending</p>
                     </div>
                  </div> <!-- row -->
                  <div class="row">
                     <div class="col-md-12">
                        <a href={{ route('adminMembershipContent', ['mt'=>3, 'nv'=>1, 'row'=>15, 'cat'=>0,'page'=>1]) }} style="color:#fff">
                           <span class="pull-left">Click here to process pending payments</span>
                           <span class="pull-right glyphicon glyphicon-chevron-right"></span>
                           <div class="clearfix"></div>
                        </a>
                        <a href={{ route('adminMembershipContent', ['mt'=>3, 'nv'=>0, 'v'=>1, 'row'=>15, 'cat'=>0,'page'=>1]) }} style="color:#fff">
                           <span class="pull-left">Click here to process accepted payments</span>
                           <span class="pull-right glyphicon glyphicon-chevron-right"></span>
                           <div class="clearfix"></div>
                        </a>
                        <a href={{ route('adminMembershipContent', ['mt'=>3, 'nv'=>1, 'v'=>1, 'row'=>15, 'cat'=>0,'page'=>1]) }} style="color:#fff">
                           <span class="pull-left">Click here to process available payments</span>
                           <span class="pull-right glyphicon glyphicon-chevron-right"></span>
                           <div class="clearfix"></div>
                        </a>
                     </div>
                  </div> <!-- row -->
                  
               </div> <!-- panel-heading -->
            </div>   <!-- panel -->
         </div>   <!-- div.md-4 -->         

         <div class="col-md-3">
            <div class="panel dashboard-divs panel-primary">
               <div class="panel-heading">
                  <div class="row">
                     <div class="col-md-12">
                        <h5 style="color: #fff">Professional Individuals Memberships</h5>
                        <p>There are <span class="badge">{{ $counter_prof }}</span> request pending</p>
                     </div>
                  </div> <!-- row -->
                  <div class="row">
                     <div class="col-md-12">
                        <a href={{ route('adminMembershipContent', ['mt'=>4, 'nv'=>1, 'row'=>15, 'cat'=>0,'page'=>1]) }} style="color:#fff">
                           <span class="pull-left">Click here to process pending payments</span>
                           <span class="pull-right glyphicon glyphicon-chevron-right"></span>
                           <div class="clearfix"></div>
                        </a>
                        <a href={{ route('adminMembershipContent', ['mt'=>4, 'nv'=>0, 'v'=>1, 'row'=>15, 'cat'=>0,'page'=>1]) }} style="color:#fff">
                           <span class="pull-left">Click here to process accepted payments</span>
                           <span class="pull-right glyphicon glyphicon-chevron-right"></span>
                           <div class="clearfix"></div>
                        </a>
                        <a href={{ route('adminMembershipContent', ['mt'=>4, 'nv'=>1, 'v'=>1, 'row'=>15, 'cat'=>0,'page'=>1]) }} style="color:#fff">
                           <span class="pull-left">Click here to process available payments</span>
                           <span class="pull-right glyphicon glyphicon-chevron-right"></span>
                           <div class="clearfix"></div>
                        </a>
                     </div>
                  </div> <!-- row -->
                  
               </div> <!-- panel-heading -->
            </div>   <!-- panel -->
         </div>   <!-- div.md-4 -->         
      </div>
      <!-- /.row -->
    </div><!--col-->
  </div><!--row-->



<div class="row">
    <div class="col-md-6">
      <h2>Student Branch Requests</h2>    
      <!-- /.row -->
      <div class="row">
         <div class="col-md-12">
            <div class="panel dashboard-divs panel-primary">
               <div class="panel-heading">
                  <div class="row">
                     <div class="col-md-12">
                        <h5 style="color: #fff">Student Branch</h5>
                        <p>There are <span class="badge">{{ $counter_student_branch_req }}</span> request pending</p>
                     </div>
                  </div> <!-- row -->
                  <div class="row">
                     <div class="col-md-12">
                        <a href={{ route('adminStudentBranchContent', ['nv'=>1, 'v'=>0, 'row'=>15, 'page'=>1]) }} style="color:#fff">
                           <span class="pull-left">Click here to process pending payments</span>
                           <span class="pull-right glyphicon glyphicon-chevron-right"></span>
                           <div class="clearfix"></div>
                        </a>
                        <a href={{ route('adminStudentBranchContent', ['nv'=>0, 'v'=>1, 'row'=>15, 'page'=>1]) }} style="color:#fff">
                           <span class="pull-left">Click here to process accepted payments</span>
                           <span class="pull-right glyphicon glyphicon-chevron-right"></span>
                           <div class="clearfix"></div>
                        </a>
                        <a href={{ route('adminStudentBranchContent', ['row'=>15, 'page'=>1]) }} style="color:#fff">
                           <span class="pull-left">Click here to process available payments</span>
                           <span class="pull-right glyphicon glyphicon-chevron-right"></span>
                           <div class="clearfix"></div>
                        </a>
                     </div>
                  </div> <!-- row -->
                  
               </div> <!-- panel-heading -->
            </div>   <!-- panel -->
         </div>   <!-- div.md-4 -->         
      </div> <!-- /.row -->
    </div><!--col-->


   <div class="col-md-6">
      <h2>Bulk Payments Requests</h2>    
      <!-- /.row -->
      <div class="row">
         <div class="col-md-12">
            <div class="panel dashboard-divs panel-primary">
               <div class="panel-heading">
                  <div class="row">
                     <div class="col-md-12">
                        <h5 style="color: #fff">Bulk Payments</h5>
                        <p>There are <span class="badge">{{ $counter_bulk_payments_req }}</span> request pending</p>
                     </div>
                  </div> <!-- row -->
                  <div class="row">
                     <div class="col-md-12">
                        <a href={{ route('adminMemberBulkPayments', ['nv'=>1, 'v'=>0, 'row'=>15, 'page'=>1]) }} style="color:#fff">
                           <span class="pull-left">Click here to process pending payments</span>
                           <span class="pull-right glyphicon glyphicon-chevron-right"></span>
                           <div class="clearfix"></div>
                        </a>
                        <a href={{ route('adminMemberBulkPayments', ['nv'=>0, 'v'=>1, 'row'=>15, 'page'=>1]) }} style="color:#fff">
                           <span class="pull-left">Click here to process accepted payments</span>
                           <span class="pull-right glyphicon glyphicon-chevron-right"></span>
                           <div class="clearfix"></div>
                        </a>
                        <a href={{ route('adminMemberBulkPayments', ['row'=>15, 'page'=>1]) }} style="color:#fff">
                           <span class="pull-left">Click here to process available payments</span>
                           <span class="pull-right glyphicon glyphicon-chevron-right"></span>
                           <div class="clearfix"></div>
                        </a>
                     </div>
                  </div> <!-- row -->
                  
               </div> <!-- panel-heading -->
            </div>   <!-- panel -->
         </div>   <!-- div.md-4 -->         
      </div> <!-- /.row -->
    </div><!--col-->

  </div><!--row-->



@endsection
