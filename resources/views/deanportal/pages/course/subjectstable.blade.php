@if(count($subjects) > 0)

      <table class="table table-bordered mb-0" id="subjectTable">
            <thead>
                  <tr>
                        <th rowspan="2" width="10%" class="text-center align-middle">Code</th>
                        <th rowspan="2"  width="65%" class="text-center align-middle">Description</th>
                        <th colspan="3"  width="20%" class="text-center">UNITS</th>
                        <td rowspan="2" width="5%"></td>
                  </tr>
                  <tr>
                        <th class="text-center">Lec</th>
                        <th class="text-center">Lab</th>
                        <th class="text-center">Total</th>
                  </tr>
                  
                  
            </thead>
            <tbody>
                  @foreach($subjects as $item)
                        <tr data-id="{{$item->id}}">
                              <td class="text-center align-middle">{{$item->subjCode}}</td>
                              <td class="align-middle">{{$item->subjDesc}}</td>
                              <td class="text-center align-middle">{{$item->lecunits}}</td>
                              <td class="text-center align-middle">{{$item->labunits}}</td>
                              <td class="text-center align-middle">{{$item->labunits + $item->lecunits}}</td>
                              <td class="text-center align-middle">
                                    <div class="dropdown">
                                          <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                          </button>
                                          <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                          <a class="dropdown-item editSubject btn-block" href="#" data-id="{{$item->id}}">uPDATE Subject</a>
                                          <a class="dropdown-item removeSubject btn-block" href="#" 
                                          data-id="{{$item->id}}" 
                                          data-text="{{Str::slug($item->subjDesc)}}"
                                          data-class="{{Str::slug($item->subjclass)}}"
                                          
                                          >Delete Subject</a>
                                          </div>
                                    </div>
                              </td>
                        </tr>
                  @endforeach
            </tbody>
      </table>

@else

      <table class="table table-bordered">
            <thead>
                <tr>
                      <th>NO SUBJECTS ADDED</th>
                </tr>
            </thead>
           
      </table>


@endif