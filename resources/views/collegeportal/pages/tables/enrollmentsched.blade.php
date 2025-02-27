<table class=" table table-sm table-borderless font-sm"  id="schedtable">
      <thead>
            <tr>
                  <th  rowspan="2" width="5%" class="border-bottom align-middle"></th>
                  <th rowspan="2" class="border-bottom align-middle" width="10%">CODE</th>
                  <th rowspan="2" class="border-bottom p-0 align-middle" width="26%">DESCRIPTION</th>
                  <th colspan="3" class="text-center p-0 align-middle" width="6%">UNIT</th>
                  <th  rowspan="2" class="border-bottom p-0 align-middle text-center" width="35%">SCHEDULE / ROOM</th>
                  <th  rowspan="2" class="border-bottom p-0 align-middle text-center" width="18%">FACULTY</th>

                  @if(isset($sectionName))
                        <th  rowspan="2" class="border-bottom p-0 align-middle text-center">SECTION</th>
                  @endif
                  
            </tr>
            <tr>
                  <th  class="border-bottom p-0 align-middle text-center pr-2 pl-2" width="2%">Lec</th>
                  <th class="border-bottom p-0 align-middle text-center pr-2 pl-2" width="2%">Lab</th>
                  <th class="border-bottom p-0 align-middle text-center pr-2 pl-2" width="2%">Credit</th>
            </tr>
      </thead>
      <tbody>

            @php
                  $totalLec = 0;
                  $totalLab = 0;
                  $totalCred = 0;
            @endphp
          
            @foreach($schedules as $itemclass)
                  <tr style="font-size:12px !important" data-value="{{$itemclass[0]->id}}">

                        @php
                              $totalLec += $itemclass[0]->lecunits;
                              $totalLab += $itemclass[0]->labunits;
                              $totalCred += $itemclass[0]->labunits + $itemclass[0]->lecunits;
                        @endphp
                        <td class="p-2 pl-0">
                              <div class="icheck-success d-inline">
                                    <input type="checkbox" id="sched{{$itemclass[0]->id}}" 
                                    data-value="{{$itemclass[0]->id}}"
                                    data-subj="{{$itemclass[0]->subjID}}"
                                    data-units = "{{$itemclass[0]->lecunits + $itemclass[0]->labunits}}"
                                    data-subjectID="{{$itemclass[0]->subjectID}}"
                                    data-sectionDesc="{{$itemclass[0]->sectionDesc}}"
                                    class="section_sched"
                                    @if(isset($sectionName))
                                          data-subjectID="{{$itemclass[0]->subjectID}}"
                                          othercollege="othercollege"
                                          data-section="{{$itemclass[0]->sectionDesc}}"
                                    @endif    
                                    >
                                    <label  for="sched{{$itemclass[0]->id}}">
                                    </label>
                              </div>
                        </td>
                        <td>{{$itemclass[0]->subjCode}}</td>
                        <td>{{$itemclass[0]->subjDesc}}</td>
                        <td class="text-center pl-0 pr-0 text-center">{{number_format($itemclass[0]->lecunits,1)}}</td>
                        <td class="text-center pl-0 pr-0 text-center"">{{number_format($itemclass[0]->labunits,1)}}</td>
                        <td class="text-center pl-0 pr-0 text-center"">{{number_format($itemclass[0]->lecunits + $itemclass[0]->labunits,1) }}</td>
                        <td class="pl-4" >
                              @foreach($itemclass as $item)
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
                        <td class="text-center">
                              @if($itemclass[0]->lastname != null && $itemclass[0]->firstname != null)
                                    {{$itemclass[0]->lastname}}, {{$itemclass[0]->firstname}}
                              @endif
                        </td>
                        
                        @if(isset($sectionName))
                              <td class="text-center">{{$item->sectionDesc}}</td>
                        @endif
                  </tr>
            @endforeach
            <tr>
                  <td></td>
                  <td></td>
                  <td class="text-right">Total Unit(s)</td>
                  <td class="text-center">{{number_format($totalLec,2)}}</td>
                  <td  class="text-center">{{number_format($totalLab,2)}}</td>
                  <td  class="text-center">{{number_format($totalCred,2)}}</td>
                  <td></td>
                  <td></td>
                  
                  @if(isset($sectionName))
                       <th></th>
                  @endif
            </tr>
      </tbody>
</table>
