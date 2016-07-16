  <header>
        <nav class="navbar navbar-default navbar-fixed-top">
          <div class="container-fluid">
            <div class="row">
                <div class="col-md-2 col-sm-2 col-xs-2">
                    <ul class="list-unstyled list-inlined pull-left">
                      @if ( Auth::user()->check() )
                          <li><a href={{ url("logout") }}><span class="glyphicon glyphicon-log-out"></span><span class="menu-text">logout</span></a></li>
                      @else
                          <li><a href={{ url("login") }}><span class="glyphicon glyphicon-log-in"></span><span class="menu-text">login</span></a></li>
                      @endif
                      {{-- <li class="active"><a href="./">Fixed top <span class="sr-only">(current)</span></a></li> --}}
                    </ul>
                </div>  
                <div class="col-md-8 col-sm-8 col-xs-8">
                    <p id="news"><span class="glyphicon glyphicon-bell"></span>This is some news!</p>
                </div>
                <div class="col-md-2 col-sm-2 col-xs-2">
                    <ul class="list-unstyled list-inlined pull-right">
                      @if ( Auth::user()->check() )
                          <li><a href={{ url("logout") }}><span class="glyphicon glyphicon-log-out"></span><span class="menu-text">logout</span></a></li>
                      @else
                          <li><a href={{ url("login") }}><span class="glyphicon glyphicon-log-in"></span><span class="menu-text">login</span></a></li>
                      @endif
                      {{-- <li class="active"><a href="./">Fixed top <span class="sr-only">(current)</span></a></li> --}}
                    </ul>
                </div>
            </div>
          </div>
        </nav>
        
        <div id="main-navigation">
            <nav class="navbar navbar-inverse nav-home fixed">
              <div class="container-fluid">
                <div class="navbar-header">
                  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-2">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                  </button>
                  <img src={{ asset('img/csi-logo-white.png') }} class="pull-left logo_image">
                  <a class="navbar-brand" href="#">Computer Society of India</a>
                </div>

                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-2">
                  <ul class="nav navbar-nav navbar-right">
                    <li class="active">
                      <a href={{ url("/home") }}><span class="glyphicon glyphicon-home"></span>Home</a>
                    </li>
                    
                    @if ( Auth::user()->check() )
                        <!-- DashBoard -->
                        <li>
                            <a href={{ url("/dashboard") }}><span class="glyphicon glyphicon-folder-close"></span>DashBoard</a>
                        </li>
                        <!-- My Account -->
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-user"></span>My Account <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="">{{ Auth::user()->user()->email }}</a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <a href={{ route('MemberChangePassword') }}>Change Password</a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <a href={{ route('viewAllMembershipPayments') }}>Payments</a>
                                </li>
                                @if (Auth::user()->user()->membership->type == 'individual')
                                    <li><a href={{ route('printcard') }}>Print CSI-Card</a></li>
                                @endif
                                <li class="divider"></li>
                                <li>
                                    <a href={{ url('/logout') }}>Logout</a>
                                </li>
                            </ul>
                        </li>
                    @else                                    
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Register <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href={{ route('register', [ 'entity' => 'institution-academic' ]) }}>Institution - Academic</a>
                                </li>
                                <li>
                                    <a href={{ route('register', [ 'entity' => 'institution-non-academic' ]) }}>Institution - Non Academic</a>
                                </li>
                                <li role="separator" class="divider"></li>
                                <li>
                                    <a href={{ route('register', [ 'entity' => 'individual-student' ]) }}>Individual - student membership</a>
                                </li>
                                <li>
                                    <a href={{ route('register', [ 'entity' => 'individual-professional' ]) }}>Individual - professional membership</a>
                                </li>
                            </ul>
                        </li>
                    @endif
                  </ul>
                </div>
              </div>
            </nav>
          @section('section-after-mainMenu')
          @show
        </div>

    </header>