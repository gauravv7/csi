@extends('frontend.master')
@section('title', 'Register')

@section('custom-styles')
   <link rel="stylesheet" type="text/css" href={{ asset('css/sidebar.css') }}>
@endsection

@section('main')
<section id="main">

   {{-- start --}}

<div class="container-fluid">
   <div class="row affix-row">
      <div class="col-sm-3 col-md-2 affix-sidebar">
         @if (Auth::user()->user()->is_verified == 1)
            @include('frontend.partials.dashboardSidebar')
         @endif
      </div>

      <div class="col-sm-9 col-md-10" style="padding: 0px 60px;" id="profile">
                 
         <div class="row">
            <div class="col-md-12">
               <h2 class="page-header"><span class="glyphicon glyphicon-user"></span>Profile <small>{{ $user->subType->name }}</small></h2>
               
               <div class="col-md-12">
                  <h3>General Information</h3>
                  <ul class="list-unstyled">
                     <li>
                        <span class="title"><span class="glyphicon glyphicon-tag"></span>Name of the Institution</span><br/>
                        <span class="title-text">
                        {{ $user->subType->name }}
                        </span>
                     </li>                     
                     <li>
                        <span class="title"><span class="glyphicon glyphicon-tag"></span>Institution Category</span><br/>
                        <span class="title-text">
                           {{ $user->subType->membershipType->type }}
                           @if($user->subType->membershipType->type == 'academic')
                              - {{ $user->subType->subType->InstitutionType->name }}
                           @endif
                        </span>
                     </li>

                     <li>
                        <span class="title"><span class="glyphicon glyphicon-tag"></span>Chapter</span><br/>
                        <span class="title-text">
                           {{ $user->chapter->name }}
                        </span>
                     </li>
                     <li>
                        <span class="title"><span class="glyphicon glyphicon-tag"></span>Region</span><br/>
                        <span class="title-text">
                           {{ $user->chapter->state->region->name }}
                        </span>
                     </li>

                  </ul>
               </div>

               @if( (Auth::user()->user()->subType->membershipType->type == 'academic') || (Auth::user()->user()->subType->membershipType->type == 'non-academic'))
               <div class="col-md-12">
                  <h3>Details of Head of the Institution</h3>
                  <ul class="list-unstyled">
                     <li>
                        <span class="title"><span class="glyphicon glyphicon-tag"></span>Name</span><br/>
                        <span class="title-text">
                           {{$user->subType->salutation->name}} {{ $user->subType->head_name }}
                        </span>
                     </li>
                     <li>
                        <span class="title"><span class="glyphicon glyphicon-briefcase"></span>Designation</span><br/>
                        <span class="title-text">
                           {{ $user->subType->head_designation }}
                        </span>
                     </li>
                     <li>
                        <span class="title"><span class="glyphicon glyphicon-envelope"></span>Email</span><br/>
                        <span class="title-text">
                           <address>
                              {{ $user->subType->email }}
                           </address>
                        </span>
                     </li>

                     <li>
                        <span class="title-text">
                           <address>
                              <abbr title="Mobile">P:</abbr> +( {{ $user->subType->country_code }} ) {{ $user->subType->mobile }}
                           </address>
                        </span>
                        </li>
                     </li>
                  </ul>
               </div>
               @endif


               @if( (Auth::user()->user()->subType->membershipType->type == 'student') )
               <div class="col-md-12">
                  <h3>Student Details</h3>
                  <ul class="list-unstyled">
                     <li>
                        <span class="title"><span class="glyphicon glyphicon-tag"></span>Associating Institution</span><br/>
                        <span class="title-text">
                           {{$user->subType->subType->institution->name}}
                        </span>
                     </li>
                     <li>
                       <span class="title"><span class="glyphicon glyphicon-tag"></span>College</span><br/>
                       <span class="title-text">
                          {{$user->subType->subType->college_name}}
                       </span>
                    </li>
                    <li>
                       <span class="title"><span class="glyphicon glyphicon-briefcase"></span>Course</span><br/>
                       <span class="title-text">
                          {{ $user->subType->subType->course_name }}
                       </span>
                    </li>
                    <li>
                       <span class="title"><span class="glyphicon glyphicon-briefcase"></span>Course branch</span><br/>
                       <span class="title-text">                                          
                           @if ( !is_null($user->subType->course_branch) && $user->subType->course_branch!='' )
                              {{ $user->subType->subType->course_branch }}
                           @else
                               none specifed
                           @endif
                      </span>
                    </li>
                    <li>
                       <span class="title"><span class="glyphicon glyphicon-envelope"></span>Course Duration</span><br/>
                       <span class="title-text">
                          {{ $user->subType->subType->course_duration }}
                       </span>
                    </li>
                  </ul>
               </div>
               @endif

               @if( (Auth::user()->user()->subType->membershipType->type == 'professional') )
               <div class="col-md-12">
                  <h3>Details of Head of the Institution</h3>
                  <ul class="list-unstyled">
                     <li>
                         <span class="title"><span class="glyphicon glyphicon-tag"></span>    Associating Student Branch</span><br/>
                         <span class="title-text">
                             @if ( $user->subType->subType->hasAssociatingInstitution )
                                 {{ $user->subType->subType->institution->name }}
                             @else 
                                 no associating institution
                             @endif
                         </span>
                     </li>
                    <li>
                       <span class="title"><span class="glyphicon glyphicon-tag"></span>Organisation</span><br/>
                       <span class="title-text">
                          {{ $user->subType->subType->organisation }}
                       </span>
                    </li>
                    <li>
                       <span class="title"><span class="glyphicon glyphicon-briefcase"></span>Designation</span><br/>
                       <span class="title-text">
                          {{ $user->subType->subType->designation }}
                       </span>
                    </li>
                  </ul>
               </div>
               @endif

               <div class="col-md-12">
                  <h3>Contact Information</h3>
                  <ul class="list-unstyled">
                     <li>
                        <span class="title"><span class="glyphicon glyphicon-envelope"></span>Primary Email</span><br/>
                        <span class="title-text">
                        {{ $user->email }}
                        </span>
                     </li>
                     @if ( !is_null($user->email_extra) && $user->email_extra!='')
                        <li>
                           <span class="title"><span class="glyphicon glyphicon-envelope"></span>Secondary Email</span><br/>
                           <span class="title-text">
                           {{ $user->email_extra }}
                           </span>
                        </li>
                     @endif
                     
                        
                     @forelse ($user->addresses as $address)
                        <li>
                              <span class="title"><span class="glyphicon glyphicon-map-marker"></span>{{ $address->type->type }}</span><br/>
                              <span class="title-text">
                              <address>
                                <strong>{{ $address->address_line_1 }}</strong><br>
                                {{ $address->city }}<br>
                                {{ $address->state->name }}<br>
                                {{ $address->pincode }}<br>
                                {{ $address->country->name }}<br>
                              </address>
                              </span>
                        </li>
                     @empty
                        {{-- <li> --}}
                              {{-- empty expr --}}
                        {{-- </li> --}}
                     @endforelse

                     @if (!empty($user->phones))
                     <li>
                        <span class="title"><span class="glyphicon glyphicon-earphone"></span>Phone</span><br/>
                       

                        @foreach ($user->phones as $phone)
                           <span class="title-text">
                              <address>
                                 @if ( !is_null($phone->landline) && $phone->landline!= '')
                                    <abbr title="Landline">P:</abbr> ({{ $phone->std_code }}) {{ $phone->landline }}
                                 @endif
                                 @if ( !is_null($phone->mobile) && $phone->mobile!= '')
                                    <abbr title="Mobile">P:</abbr> +({{ $phone->country_code }}) {{ $phone->mobile}}
                                 @endif
                              </address>
                           </span>
                        @endforeach
                     </li>
                     @endif
                        
                     
                  </ul>
               </div>



            </div>
         </div>

      </div>
   </div>

</div>

{{-- end --}}
<br/>
<br/>
</section>
@endsection
