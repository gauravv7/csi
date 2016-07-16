 <div class="sidebar-nav">
   <div class="navbar navbar-default" role="navigation">
     <div class="navbar-header">
       <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-navbar-collapse">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
       </button>
       <span class="visible-xs navbar-brand">Services</span>
    </div>
    <div class="navbar-collapse collapse sidebar-navbar-collapse">
                <ul class="nav navbar-nav" id="sidenav01">
                  <li class="active">
                    <a href="#" data-toggle="collapse" data-target="#toggleDemo0" data-parent="#sidenav01" class="collapsed">
                       <h4>
                          {{ Auth::user()->user()->membership->type }}&nbsp;services
                       </h4>
                    </a>
                   </li>
               <li>
                 <a href="#" data-toggle="collapse" data-target="#toggleDemo" data-parent="#sidenav01" class="collapsed">
                    <span class="glyphicon glyphicon-user"></span> Profile <span class="caret pull-right"></span>
                 </a>
                 <div class="collapse" id="toggleDemo" style="height: 0px;">
                   <ul class="nav nav-list">
                      <li><a href={{ route('profile') }}>View Profile</a></li>
                      @if (Auth::user()->user()->membership->type == 'individual')
                        <li><a href={{ route('card') }}>Print CSI-Card</a></li>
                      @endif
                      <li><a href="#">Payment History</a></li>
                   </ul>
                </div>
             </li>
             {{-- <li class="active">
                <a href="#" data-toggle="collapse" data-target="#toggleDemo2" data-parent="#sidenav01" class="collapsed">
                 <span class="glyphicon glyphicon-inbox"></span> Submenu 2 <span class="caret pull-right"></span>
                </a>
                <div class="collapse" id="toggleDemo2" style="height: 0px;">
                   <ul class="nav nav-list">
                     <li><a href="#">Submenu2.1</a></li>
                     <li><a href="#">Submenu2.2</a></li>
                     <li><a href="#">Submenu2.3</a></li>
                  </ul>
                </div>
             </li> --}}
         @if (Auth::user()->user()->membership->type == 'individual')

         @endif

         @if (Auth::user()->user()->membership->type == 'institutional')

           @if(Auth::user()->user()->getMembership->membershipType->type == 'academic')
              @if(Auth::user()->user()->getMembership->subType->is_student_branch != 1)
                <li><a href={{ route('confirmStudentBranch') }}><span class="glyphicon glyphicon-certificate"></span> Request for Student branch</a></li>
              @endif
           @endif
           <li><a href="#"><span class="glyphicon glyphicon-plus"></span> Add Nominee</a></li>
           {{-- <li><a href="#"><span class="glyphicon glyphicon-calendar"></span> WithBadges <span class="badge pull-right">42</span></a></li> --}}
           <li><a href=""><span class="glyphicon glyphicon-duplicate"></span> Bulk Payments</a></li>
         @endif 

       </ul>
    </div><!--/.nav-collapse -->
 </div>
</div>