<div id="filter">
            <div class="row">
              <div class="col-md-12">
              
                {{-- <ul class="list-unstyled">
                  <li>
                    <a href="">Institutions</a>
                  </li>
                </ul> --}}  
                {!! Form::open(['route' => ['adminMembershipContent'], 'method' => 'get', 'class' => 'form-inline' ]) !!}
                  <div class="form-group">
                          {!! Form::select('membership-type', ['0' => 'asdasd'], null) !!}
                        
                  </div>
                  
                  <div class="form-group">
                    <div class="checkbox">
                      <label>verified:
                        <input type="checkbox" name="verified" value="0"> yes
                      </label>
                      <label>
                        <input type="checkbox" name="not-verified" value="1"> no
                      </label>
                    </div>
                  </div>
                  <div class="form-group">
                      <input type="number" class="form-control" name="rows" id="rows" value="15">
                  </div>
                  <div class="form-group">
                    <select name="cat"> <!-- search-cateria, self mapped, no db interaction -->
                      <option value="0">member id</option>
                      <option value="1">email</option>
                      <option value="2">request id</option>
                      <option value="3">region</option>
                      <option value="4">state</option>
                      <option value="5">chapter</option>
                      <option value="6">student-branch</option>
                    </select>
                
                      <input type="text" class="form-control" name="search_text" id="search_text" placeholder="Enter text">
                  </div>
                  <button type="submit" class="btn btn-default pull-right">Search</button>
                </form>
                {!! Form::close() !!}
              </div>
            </div>
          </div>