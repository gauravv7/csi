<div class="row">
   <div class="col-md-4">
      <div class="panel dashboard-divs panel-primary">
         <div class="panel-heading">
            <div class="row">
               <div class="col-md-12">
                  <p><span class="glyphicon glyphicon-globe"></span>New Notification!</p>
               </div>
            </div> <!-- row -->
            <div class="row">
               <div class="col-md-12">
                  <a href="#" style="color:#fff">
                     <span class="pull-left">View Details</span>
                     <span class="pull-right glyphicon glyphicon-chevron-right"></span>
                     <div class="clearfix"></div>
                  </a>
               </div>
            </div> <!-- row -->
            
         </div> <!-- panel-heading -->
      </div>   <!-- panel -->
   </div>   <!-- div.md-4 -->             
   
   <div class="col-md-4">
      <div class="panel dashboard-divs panel-success">
         <div class="panel-heading">
            <div class="row">
               <div class="col-md-12">
                  <p><span class="glyphicon glyphicon-exclamation-sign"></span>Latest News!</p>
               </div>
            </div> <!-- row -->
            <div class="row">
               <div class="col-md-12">
                  <a href="#" style="color:#fff">
                     <span class="pull-left">View Details</span>
                     <span class="pull-right glyphicon glyphicon-chevron-right"></span>
                     <div class="clearfix"></div>
                  </a>
               </div>
            </div> <!-- row -->
            
         </div> <!-- panel-heading -->
      </div>   <!-- panel -->
   </div>   <!-- div.md-4 -->  
   @can('is-institution')
  @if($user->getMembership->membership_type_id==1)
   
   <div class="col-md-4">
      <div class="panel dashboard-divs panel-danger">
         <div class="panel-heading">
            <div class="row">
               <div class="col-md-12">
                  
                  <p><span class="glyphicon glyphicon-exclamation-sign"></span>Bulk Registrations</p>
               </div>
            </div> <!-- row -->
            <div class="row">
               <div class="col-md-12">
                  <a href={{ route('BulkPaymentsView') }} style="color:#fff">
                     <span class="pull-left">view details</span>
                     <span class="pull-right glyphicon glyphicon-chevron-right"></span>
                     <div class="clearfix"></div>
                  </a>
               </div>
            </div> <!-- row -->
            
         </div> <!-- panel-heading -->
      </div>   <!-- panel -->
   </div>   <!-- div.md-4 --> 

   @endif
        
   @endcan
   <!-- can copy one more row from above -->
  
</div>  {{-- end of panel row --}}

<!-- row -->
<div class="row">
   @can('is-institution')
      <div class="col-md-4">
         <div class="panel dashboard-divs panel-info">
            <div class="panel-heading">
               <div class="row">
                  <div class="col-md-12">
                     <p><span class="glyphicon glyphicon-user"></span>Nominees</p>
                  </div>
               </div> <!-- row -->
               <div class="row">
                  <div class="col-md-12">
                     <a href={{ route('NomineeView') }} style="color:#fff">
                        <span class="pull-left">View Details</span>
                        <span class="pull-right glyphicon glyphicon-chevron-right"></span>
                        <div class="clearfix"></div>
                     </a>
                  </div>
               </div> <!-- row -->
               
            </div> <!-- panel-heading -->
         </div>   <!-- panel -->
      </div>   <!-- div.md-4 -->    
      @can('is-academic-institution')
      <div class="col-md-4">
         <div class="panel dashboard-divs panel-primary">
            <div class="panel-heading">
               <div class="row">
                  <div class="col-md-12">
                     <p><span class="glyphicon glyphicon-envelope"></span>Student Branch</p>
                  </div>
               </div> <!-- row -->
               <div class="row">
                  <div class="col-md-12">
                     <a href={{ route('RequestStudentBranch') }} style="color:#fff">
                        <span class="pull-left">Send Request/View Requests</span>
                        <span class="pull-right glyphicon glyphicon-chevron-right"></span>
                        <div class="clearfix"></div>
                     </a>
                  </div>
               </div> <!-- row -->
               
            </div> <!-- panel-heading -->
         </div>   <!-- panel -->
      </div>   <!-- div.md-4 -->    
      @endcan
   @endcan
</div> <!-- row -->