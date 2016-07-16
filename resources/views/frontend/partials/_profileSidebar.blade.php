<div class="col-md-12"> <!-- div card -->
    <div class="panel panel-default" id="dd-list">
      <div class="panel-thumbnail"><img id="profile_img" src={{ route('UserProfilePhotograph', [$user->getMembership->profile_photograph]) }} class="img-responsive">
        <a id="profileImageLink" data-toggle="modal" data-target="#uploadProfilePhoto"><span class="glyphicon glyphicon-pencil"></span></a>
      </div>
      <div class="panel-body">
        <p class="lead">{{ $user->getMembership->getName() }}</p>
        <p class="lead">{{ $user->getFullAllotedID() }}</p>
        <ul class="list-unstyled">
            <li>
              <span class="glyphicon glyphicon-file"></span><span class="header">Application ID</span>
              {{ $user->getFullID() }}
            </li>
            <!-- postal address info -->
            @forelse ($user->addresses as $address)
              <li>
              <span class="glyphicon glyphicon-map-marker"></span><span class="header">{{ $address->type->type }}<a class="edit-content" href={{ route('MemberAddressEditDetails', $address->id) }} ><span class="glyphicon glyphicon-pencil"></span></a></span>
              <address>
                {{ $address->address_line_1 }}<br>
                {{ $address->city }}, {{ $address->state->name }}, {{ $address->pincode }}, {{ $address->country->name }}
              </address>
              </li>
            @empty
              {{-- <li> --}}
              {{-- empty expr --}}
              {{-- </li> --}}
            @endforelse
        </ul>
      </div>
    </div>
  </div> <!-- div card-->

  <div class="col-md-12"> <!-- div card -->
    <div class="panel panel-default" id="dd-list">
      <div class="panel-heading">
        <h6>
          Contact Details
          <a class="edit-content" href={{ route('MemberContactEditDetails') }} ><span class="glyphicon glyphicon-pencil"></span></a>
        </h6>
      </div>
      <div class="panel-body">
        <ul class="list-unstyled">
            
          <!-- phone numbers -->
          <li>
            <span class="glyphicon glyphicon-earphone"></span>
            <address>
               @if ( !is_null($user->phone->landline) && $user->phone->landline!= '')
                  <abbr title="Landline">P:</abbr> ({{ $user->phone->std_code }}) {{ $user->phone->landline }}<br/>
               @endif
               @if ( !is_null($user->phone->mobile) && $user->phone->mobile!= '')
                  <abbr title="Mobile">P:</abbr> +({{ $user->phone->country_code }}) {{ $user->phone->mobile}}<br/>
               @endif
            </address>
          </li>
            
          <!-- emails --> 
          <li>
              <span class="glyphicon glyphicon-envelope"></span><span class="header">Primary Email [Login Email-ID]</span>
                {{ $user->email }}
              @if ( !is_null($user->email_extra) && $user->email_extra!='')
                 <span class="header">Secondary Email</span>
                 {{ $user->email_extra }}
              @endif
          </li>

        </ul>
      </div>
    </div>
  </div> <!-- div card-->

  
@if ($user->membership->id == 1)
  <div class="col-md-12"> <!-- div card -->
    <div class="panel panel-default" id="dd-list">
      <div class="panel-heading">
        <h6>
          Institution Details
        </h6>
      </div>
      <div class="panel-body">
        <ul class="list-unstyled">
            
          <li>
            <span class="glyphicon glyphicon-tag"></span><span class="header">Institution Category</span>
              @if ($user->getMembership->membershipType->id == 1)
                {{ $user->getMembership->membershipType->type }}  - {{ $user->getMembership->subType->InstitutionType->name  }}
              @else 
                {{ $user->getMembership->membershipType->type }}
                {{-- expr --}}
              @endif
            
          </li>

          @if ($user->getMembership->membershipType->type == 'academic')
          <li>
            <span class="glyphicon glyphicon-tag"></span><span class="header">Is a Student Branch</span>
            @if ($user->getMembership->subType->is_student_branch == 1)
                yes
              @else 
                No
                {{-- expr --}}
            @endif
          </li>
          @endif

          <li>
            <span class="glyphicon glyphicon-tag"></span><span class="header">Chapter</span>
              {{ $user->chapter->name }}
          </li>
          <li>
            <span class="glyphicon glyphicon-tag"></span><span class="header">Region</span>
               {{ $user->chapter->state->region->name }}
          </li>

        </ul>
      </div>
    </div>
  </div> <!-- div card-->

  <div class="col-md-12"> <!-- div card -->
    <div class="panel panel-default" id="dd-list">
      <div class="panel-heading">
        <h6>
          Details of Head of the Institution
          <a class="edit-content" href={{ route('MemberInstitutionHeadEditDetails') }} ><span class="glyphicon glyphicon-pencil"></span></a>
        </h6>
      </div>
      <div class="panel-body">
        <p class="lead">{{$user->getMembership->salutation->name}} {{ $user->getMembership->head_name }}</p>
        <ul class="list-unstyled">
            
          <li>
            <span class="glyphicon glyphicon-briefcase"></span><span class="header">Designation</span>
               {{ $user->getMembership->head_designation }}
         </li>
         <li>
           <span class="glyphicon glyphicon-envelope"></span><span class="header">Email</span>
            
               {{ $user->getMembership->email }}
         </li>


         <li>
           <span class="glyphicon glyphicon-envelope"></span>
                  <abbr title="Mobile">M: </abbr> +( {{ $user->getMembership->country_code }} ) {{ $user->getMembership->mobile }}
            </li>

        </ul>
      </div>
    </div>
  </div> <!-- div card-->
@elseif ($user->membership->id == 2)
  <div class="col-md-12"> <!-- div card -->
    <div class="panel panel-default" id="dd-list">
      <div class="panel-heading">
        <h6>
          Individual Details
        </h6>
      </div>
      <div class="panel-body">
        <ul class="list-unstyled">
            
          <li>
            <span class="glyphicon glyphicon-tag"></span><span class="header">Individual Category</span>
                {{ $user->getMembership->membershipType->type }}
          </li>

          <li>
            <span class="glyphicon glyphicon-tag"></span><span class="header">Email (Login ID)</span>
               {{ $user->email }}
          </li>

          <li>
            <span class="glyphicon glyphicon-tag"></span><span class="header">Chapter</span>
              {{ $user->chapter->name }}
          </li>
          <li>
            <span class="glyphicon glyphicon-tag"></span><span class="header">Region</span>
               {{ $user->chapter->state->region->name }}
          </li>

        </ul>
      </div>
    </div>
  </div> <!-- div card-->
  @if ($user->getMembership->membershipType->id == 3) <!-- student -->
    <div class="col-md-12"> <!-- div card -->
      <div class="panel panel-default" id="dd-list">
        <div class="panel-heading">
          <h6>
            Student Details
          </h6>
        </div>
        <div class="panel-body">
          
          <ul class="list-unstyled">
            <li>
              <span class="glyphicon glyphicon-envelope"></span><span class="header">College</span>
                  {{ $user->getMembership->subType->college_name }}
            </li>

            <li>
              <span class="glyphicon glyphicon-envelope"></span><span class="header">Course</span>
                  {{ $user->getMembership->subType->course_name }}
            </li>

            <li>
              <span class="glyphicon glyphicon-envelope"></span><span class="header">Course Branch</span>
                  {{ $user->getMembership->subType->course_branch }}
            </li>

            <li>
              <span class="glyphicon glyphicon-envelope"></span><span class="header">Course Duration</span>
                  {{ $user->getMembership->subType->course_duration }}
            </li>
            
            <li>
              <span class="glyphicon glyphicon-envelope"></span><span class="header">Student Branch</span>
                  {{ App\Institution::find($user->getMembership->subType->student_branch_id)->getName() }}
            </li>
            
          </ul>
        </div>
      </div>
    </div> <!-- div card-->
  @elseif ($user->getMembership->membershipType->id == 4) <!-- professional -->
    <div class="col-md-12"> <!-- div card -->
      <div class="panel panel-default" id="dd-list">
        <div class="panel-heading">
          <h6>
            Professional Details
          </h6>
        </div>
        <div class="panel-body">
          
          <ul class="list-unstyled">
            <li>
              <span class="glyphicon glyphicon-envelope"></span><span class="header">Is Nominee?</span>
                  @if ($user->getMembership->subType->is_nominee == 1)
                    yes
                  @else
                    no
                  @endif
            </li>
            @if ( !is_null($user->getMembership->subType->associating_institution_id) )
              <li>
                <span class="glyphicon glyphicon-envelope"></span><span class="header">Associating Institution</span>
                    {{ $user->getMembership->subType->institution->getname() }}
              </li>
            @endif
            <li>
              <span class="glyphicon glyphicon-briefcase"></span><span class="header">Designation</span>
                 {{ $user->getMembership->subType->designation }}
           </li>
           <li>
             <span class="glyphicon glyphicon-envelope"></span><span class="header">Organisation</span>
              
                 {{ $user->getMembership->subType->organisation }}
           </li>

          </ul>
        </div>
      </div>
    </div> <!-- div card-->

  @endif
@endif


<div class="modal fade" id="uploadProfilePhoto" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Upload Profile Photograph</h4>
      </div>
      <div class="modal-body">
        {!! Form::open([ 'route' => ['uploadImage', 1], 'id' => "uploadProfilePhotoForm" , 'files' => true]) !!}
          <div class="form-group">
            <label for="exampleInputFile" class="req">Profile Photograph</label>
            <input type="file" name="upload_image" id="upload_image">
            <span class="help-block">Please upload your photograph (file types allowed are jgp/png/bmp)</span>
          </div>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <input type="submit" name="submit" class="btn btn-primary" value="submit"/>
        {!! Form::close() !!}
      </div>
    </div>
  </div>
</div>

@section('footer-scripts-sidebar')
  <script type="text/javascript">
    var disp = 0;
    $(document).ready(function(e){
      $("#profile_img").mouseenter(function(){
          clearTimeout($(this).data('timeoutId'));
          if($('#profileImageLink').css('visibility') == 'hidden'){
            $('#profileImageLink').addClass('slideDown');
          }
      }).mouseleave(function(){
          var someElement = $(this),
              timeoutId = setTimeout(function(){
                  $('#profileImageLink').removeClass('slideDown');
              }, 650);
          //set the timeoutId, allowing us to clear this trigger if the mouse comes back over
          someElement.data('timeoutId', timeoutId); 
      });
      $('#profileImageLink').hover(function(e){
        $('#profileImageLink').css('visibility', 'visible');
      }, function(e){
        $('#profileImageLink').css('visibility', 'hidden');
      });
    });

  </script>
@endsection

