<table class="table table-bordered">
      <thead>
           <tr>
                  <th rowspan="2" ></th>
                  <th rowspan="2" ></th>
                  <th rowspan="2" class="text-center align-middle p-1">SUBJECT</th>
                  <th colspan="2" class="text-center p-1">GRADE STATUS</th>
            </tr>
            <tr> 
                  <th width="15%" class="text-center p-1">Midterm</th>
                  <th width="15%" class="text-center p-1">Finalterm</th>
            </tr>
      </thead>
      <tbody>
            @foreach ($subjects as $bysetion)
                  @php
                        $first = true;
                  @endphp
                  @foreach ($bysetion as $item)
                        <tr>
                              @if($first)
                                    <td rowspan="{{count($bysetion)}}" class="align-middle">
                                          {{$item->sectionDesc}}
                                    </td>
                                    @php
                                          $first = false;
                                    @endphp
                                    
                              @endif
                                   
                          
                              <td class="align-middle">
                                    <div class="dropdown">
                                          <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                          </button>
                                          <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                          <a class="dropdown-item viewmidtermgrades" 
                                          href="#" 
                                                data-id="{{$item->subjectID}}"
                                                data-section="{{$item->sectionID}}"
                                          ><i class="fas fa-eye pr-2"></i> View Midterm Grades</a>
                                          <a class="dropdown-item viewfinalgrades" 
                                          href="#" 
                                                data-id="{{$item->subjectID}}"
                                                data-section="{{$item->sectionID}}"
                                          ><i class="fas fa-eye pr-2"></i> View Finalterm Grades</a>
                                          </div>
                                    </div>
                              </td>
                              <td class="align-middle" style="font-size:12\5px">
                                    {{$item->subjDesc}}
                                    
                              </td>

                              @if($item->midtermsubmit == 1)
                                    <td class="text-center"><span class="badge badge-success">SUBMITTED</span></td>
                              @elseif($item->midtermsubmit == 2)
                                    <td class="text-center"><span class="badge badge-warning">APPROVED</span></td>
                              @elseif($item->midtermsubmit == 3)
                                    <td class="text-center"><span class="badge badge-primary">POSTED</span></td>
                              @else
                                    <td class="text-center"><span class="badge badge-danger">UNSUBMITTED</span></td>
                              @endif

                              @if($item->finalsubmit == 1)
                                    <td class="text-center"><span class="badge badge-success">SUBMITTED</span></td>
                              @elseif($item->midtermsubmit == 2)
                                    <td class="text-center"><span class="badge badge-warning">APPROVED</span></td>
                              @elseif($item->midtermsubmit == 3)
                                    <td class="text-center"><span class="badge badge-primary">POSTED</span></td>
                              @else
                                    <td class="text-center"><span class="badge badge-danger">UNSUBMITTED</span></td>
                              @endif
                        
                        </tr>
                  @endforeach
            @endforeach
            
      </tbody>
</table>