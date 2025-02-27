<table class=" font-sm table table-borderless" style="min-width: 1000px">
      <thead>
            <tr>
                  <th rowspan="2" class="border-bottom p-0 align-middle" width="2%"></th>
                  <th rowspan="2" class="border-bottom p-0 align-middle text-center" width="9%">SECTION</th>
                  <th rowspan="2" class="border-bottom p-0 align-middle text-center" width="8%">CODE</th>
                  <th rowspan="2" class="border-bottom align-middle" width="24%">DESCRIPTION</th>
                  <th colspan="3" class="text-center p-0 align-middle" width="12%">UNIT</th>
                  <th  rowspan="2" class="border-bottom p-0 align-middle text-center" width="30%">SCHEDULE / ROOM</th>
                  <th  rowspan="2" class="border-bottom p-0 align-middle text-center" width="22%">FACULTY</th>
            </tr>
            <tr>
                  <th  class="border-bottom p-0 align-middle text-center">Lec</th>
                  <th class="border-bottom p-0 align-middle text-center">Lab</th>
                  <th class="border-bottom p-0 align-middle text-center">Total</th>
            </tr>
      </thead>
      <tbody>
            @if(count($classSched) > 0)
                  @foreach($classSched as $itemclass)
                        <tr style="font-size:15px !important">
                              <td class="pl-2 pr-2">
                                    <div class="dropdown">
                                          <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                          </button>
                                          <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                          
                                                      <a data-sectionid="{{$itemclass[0]->sectionid}}" data-unit="{{number_format($itemclass[0]->lecunits + $itemclass[0]->labunits,1) }}" data-schedid="{{$itemclass[0]->id}}" class="dropdown-item addsched" href="#" data-id="1" data-value="{{$itemclass[0]->subjDesc}}" data-subj="{{$itemclass[0]->subjID}}"><i class="fas fa-plus-square"></i> Add Lecture Schedule</a>
                                                      <a data-sectionid="{{$itemclass[0]->sectionid}}" data-unit="{{number_format($itemclass[0]->lecunits + $itemclass[0]->labunits,1) }}" data-schedid="{{$itemclass[0]->id}}" class="dropdown-item addsched" href="#" data-id="2" data-value="{{$itemclass[0]->subjDesc}}" data-subj="{{$itemclass[0]->subjID}}"><i class="fas fa-plus-square"></i> Add Laboratory Schedule</a>
                                                      <a class="dropdown-item addIns" href="#" data-id="{{$itemclass[0]->id}}"><i class="fas fa-plus-square"></i> Add  Instructor</a>
                                          
                                          </div>
                                    </div>
                              </td>
                              <td >{{$itemclass[0]->sectionDesc}}</td>
                              <td class="text-center" >{{$itemclass[0]->subjCode}}</td>
                              <td>{{$itemclass[0]->subjDesc}}</td>
                              <td class="text-center ">{{number_format($itemclass[0]->lecunits,1)}}</td>
                              <td class="text-center ">{{number_format($itemclass[0]->labunits,1)}}</td>
                              <td class="text-center ">{{number_format($itemclass[0]->lecunits + $itemclass[0]->labunits,1) }}</td>
                              <td class="pl-4 text-sm" >
                                    @foreach($itemclass as $item)
                                                @if(isset($item->schedid))
                                                      <a data-unit="{{number_format($itemclass[0]->lecunits + $itemclass[0]->labunits,1) }}" data-schedid="{{$itemclass[0]->id}}" class="editsched" href="#" data-id="{{$itemclass[0]->schedid}}" data-value="{{$itemclass[0]->subjDesc}}" data-subj="{{$itemclass[0]->subjID}}" data-sectionid="{{$itemclass[0]->sectionid}}"><i class="fa fa-edit text-primary" ></i></a> | 
                                                      <a data-id="{{$item->schedid}}" href="#" class="removesched" data-sectionid="{{$itemclass[0]->sectionid}}"><i class="fa fa-trash-alt text-danger" ></i></a>
                                                @endif
                                                @if($item->scheddetialclass == 1)
                                                      Lec.
                                                @elseif($item->scheddetialclass == 2)
                                                      Lab.
                                                @endif

                                                {{$item->description}}   

                                                @if($item->stime!=null)
                                                      {{\Carbon\Carbon::create($item->stime)->isoFormat('hh:mm A')}} - {{\Carbon\Carbon::create($item->etime)->isoFormat('hh:mm A')}}  
                                                @endif

                                          
                                                {{$item->roomname}}   
                                          
                                    
                                                <br>
                                    
                                    @endforeach
                              </td>
                              <td>
                                    @if(isset($item->lastname))
                                    <a data-id="{{$itemclass[0]->id}}" href="#" data-sectionid="{{$itemclass[0]->sectionid}}" class="removeIns"><i class="fa fa-trash-alt text-danger"></i></a>  {{$item->lastname}}, {{substr($item->firstname,0,1)}}.
                                    @endif
                              </td>
                              
                        </tr>
                  
                  @endforeach
            @else
                  <tr>
                        <td colspan="8" class="text-center bg-danger text-large">
                              <h3 class="mb-0">NO SUBJECTS AVAILABLE!</h3>
                        </td>
                  </tr>
                  <tr class="bg-danger">
                        <td colspan="2"></td>
                        <td colspan="5" class="text-center">
                              <ul>
                                    <li>
                                          CHECK THE UNLOAD SUBJECTS BELOW TO ADD THE AVAILABLE SUBJECTS.
                                    </li>
                              </ul>
                        </td>
                        <td></td>
                  </tr>
            @endif
      </tbody>
</table>