{{-- @foreach ($testingSched as $item)
      <tr>
            @if($sectionInfo->acadprogid == 5)
                  @if($item->subjinfo->type==1)
                        <td class="p-0 text-center text-white align-middle bg-red-50"><b>C</b></td>
                  @elseif($item->subjinfo->type==2)
                        <td class="p-0 text-center text-white align-middle bg-blue-50"><b>SP</b></td>
                  @else
                        <td class=" p-0 text-center text-white align-middle bg-green-50"><b>AS</b></td>
                  @endif
            @else
                  <td></td>
            @endif
            <td class="text-center align-middle tablesub appadd">{{$item->subjinfo->subjcode}}</td>
            <td class="text-center align-middle tablesub">{{$item->daysum}}</td>

            @if($item->subjinfo->stime!='00:00')
                  <td class="text-center align-middle">
                        {{\Carbon\Carbon::create($item->subjinfo->stime)->isoFormat('hh : mm a')}}
                        <br>
                        {{\Carbon\Carbon::create($item->subjinfo->etime)->isoFormat('hh : mm a')}}
                  </td>
            @else
                  <td class="text-red align-middle">Not Set</td>
            @endif
            
            <td class="text-center align-middle tablesub appadd">{{$item->subjinfo->roomname}}</td>

            @if($item->subjinfo->teacherid!=0)
                  <td class="text-center align-middle appadd">
                        {{$item->subjinfo->lastname}}, 
                        {{explode(' ',trim($item->subjinfo->firstname))[0]}} asdsdasdasdsd
                  </td>
            @else
                  <td class="text-red align-middle text-center">
                        No Assigned Teacher
                  </td>
            @endif
            <td class="p-0 align-middle align-middle">

                  <div class="dropdown">
                        <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                              <a class="dropdown-item" href="#" id="editcourse" data-id="{{$course->id}}" data-value="{{$course->courseDesc}}" ><i class="fas fa-edit"></i> Edit Course</a>
                              <a class="dropdown-item" href="#" id="removecourse" data-id="{{$course->courseDesc}}" ><i class="fas fa-trash-alt pr-2"></i>Remove Course</a>
                        </div>
                  </div>
                  @if(App\Models\Principal\SPP_SchoolYear::getActiveSchoolYear()->id == Session::get('schoolYear')->id)
                        @if($sectionInfo->acadprogid == 5)

                        @if($item->subjinfo->type != 2)
                              <a type="button" href="#" id="removeSHSched" data-id="{{$item->subjinfo->detailid}}" class="text-danger btn p-0 del"><i class="fa fa-trash-alt"></i></a>
                        @endif
                        @else
                              <a type="button" href="#" class="text-danger btn p-0 del" id="removeSched" data-id="{{$item->subjinfo->detailid}}"><i class="fa fa-trash-alt"></i></a>
                        @endif
                  @endif
            </td>
      </tr> 
@endforeach --}}


@foreach($subjects as $item)

            @php
                  $countSched = 0;
                  $countSched = collect($schedulesummary)->where('subjid',$item->id)->count();
                  $first = true;

                  $sortid = 1;

                  if($item->acadprogid == 5){

                        if($item->type==1){

                              $sortid  = 1;

                        }
                        elseif($item->type==2){

                              $sortid  = 3;

                        }
                        else{

                              $sortid  = 2;

                        }

                  }
                  else{
                        $sortid = 1;
                  }
            @endphp

   
            @if($countSched > 0)
         
                  @foreach (collect($schedulesummary)->where('subjid',$item->id) as $scheditem)
                        <tr style="font-size:11px" sort-id="{{$sortid}}">
                              @if($first)
                                    @if($item->acadprogid == 5)
                                          @if($item->type==1)
                                                <td rowspan="{{$countSched}}" class="pl-2 text-center text-white align-middle bg-red-50">C</td>
                                          @elseif($item->type==2)
                                                <td class="pl-2 text-center text-white align-middle bg-blue-50"  rowspan="{{$countSched}}">SP1</td >
                                          @else
                                                <td rowspan="{{$countSched}}" class="pl-2 text-center text-white align-middle bg-green-50" >AS1</td>
                                          @endif
                                    @else
                                          <td rowspan="{{$countSched}}" "></td>
                                    @endif

                                   
                                    <td rowspan="{{$countSched}}" class="align-middle">
                                          {{$item->subjcode}} - {{$item->subjdesc}} <br>
                                          {{-- <button class="btn btn-primary btn-xs" id="add_sched" data-id="{{$item->id}}"><i class="fas fa-plus"></i> Add Schedule</button> --}}
                                          @if($item->acadprogid == 5)
                                                @if($item->type==3 || $item->type==1)
                                                      <button class="btn btn-primary btn-xs" id="add_sched"  data-id="{{$item->id}}"><i class="fas fa-plus"></i> Add Schedule</button>
                                                @endif
                                          @else
                                                <button class="btn btn-primary btn-xs" id="add_sched"  data-id="{{$item->id}}"><i class="fas fa-plus"></i> Add Schedule</button>
                                          @endif
                                    </td>
                                    @php
                                          $first = false;
                                    @endphp
                              @endif
                              <td class="text-center align-middle" style="font-size:11px">
                                    <span class="text-primary text-bold">{{$scheditem->subjinfo->schedclass}}</span>
                                    <br>{{$scheditem->daysum}}
                               
                                   
                              </td>
                              <td class="text-center align-middle">{{\Carbon\Carbon::create($scheditem->subjinfo->stime)->isoFormat('hh:mm a')}}<br>{{\Carbon\Carbon::create($scheditem->subjinfo->etime)->isoFormat('hh:mm a')}}</td>
                              <td class="text-center align-middle">{{$scheditem->subjinfo->roomname}}</td>
                              <td class="text-center align-middle">{{$scheditem->subjinfo->firstname}}<br>{{$scheditem->subjinfo->lastname}}</td>


                              @if($item->acadprogid == 5)
                                    @if($item->type!=2)
                                          <td class="text-center align-middle">
                                                <div class="dropdown">
                                                      <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <i class="fas fa-ellipsis-v"></i>
                                                      </button>
                                                      <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                            
                                                            <a class="dropdown-item update_sched" href="#" data-id="{{$scheditem->subjinfo->teacherid}}" data-subjid="{{$scheditem->subjinfo->subjid}}" data-type="seniorhigh" data-headerid="{{$scheditem->subjinfo->detailid}}"><i class="fas fa-edit text-primary pr-1"></i> Update Schedule</a>
                                                            <a class="dropdown-item remove_sched" href="#" data-id="{{$scheditem->subjinfo->detailid}}"><i class="fas fa-trash-alt text-danger pr-1"></i> Remove Schedule</a>
                                                      </div>
                                                </div>
                                          </td>
                                    @else
                                          <td></td>
                                    @endif
                              @else
                                    <td class="text-center align-middle">
                                          <div class="dropdown">
                                                <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                      <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                      <a    class="dropdown-item update_sched" 
                                                            href="#" 
                                                            data-id="{{$scheditem->subjinfo->teacherid}}" 
                                                            data-subjid="{{$scheditem->subjinfo->subjid}}" data-type="juniorhigh"
                                                            data-headerid="{{$scheditem->subjinfo->detailid}}">
                                                            <i class="fas fa-ddit text-primary pr-1" ></i> 
                                                            Update Schedule
                                                      </a>
                                                      <a class="dropdown-item remove_sched" 
                                                      href="#" 
                                                      data-id="{{$scheditem->subjinfo->detailid}}">
                                                      <i class="fas fa-trash-alt text-danger pr-1"></i> 
                                                            Remove Schedule
                                                </a>
                                                </div>
                                          </div>
                                    </td>
                              @endif
                             
                              
                        </tr>
                  @endforeach
           
            @else
                  <tr style="font-size:11px" sort-id="{{$sortid}}">
                        @if($item->acadprogid == 5)
                              @if($item->type==1)
                                    <td class="pl-2 text-center text-white align-middle bg-red-50" >C</td>
                              @elseif($item->type==2)
                                    <td class="pl-2 text-center text-white align-middle bg-blue-50">SP</td>
                              @else
                                    <td class="pl-2 text-center text-white align-middle bg-green-50">AS2</td>
                              @endif
                        @else
                              <td  ></td>
                        @endif
                        <td class="align-middle">
                              {{$item->subjcode}} - {{$item->subjdesc}} <br>
                              @if($item->acadprogid == 5)
                                    @if($item->type==3 || $item->type==1)
                                          <button class="btn btn-primary btn-xs" id="add_sched"  data-id="{{$item->id}}"><i class="fas fa-plus"></i> Add Schedule</button>
                                    @endif
                              @else
                                    <button class="btn btn-primary btn-xs" id="add_sched"  data-id="{{$item->id}}"><i class="fas fa-plus"></i> Add Schedule</button>
                              @endif

                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>
                              {{-- <div class="dropdown">
                                    <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                          <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                          <a class="dropdown-item" href="#" id="add_sched" data-id="{{$item->id}}"><i class="fas fa-edit"></i> Add Schedule</a>
                                    </div>
                              </div> --}}
                        </td>

                  </tr>
            @endif
          
  


@endforeach