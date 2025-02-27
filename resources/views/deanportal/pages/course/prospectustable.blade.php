{{-- @if(count($prospectus) > 0) --}}

      @foreach (DB::table('gradelevel')->where('acadprogid','6')->where('deleted','0')->get() as $year)
            @foreach (DB::table('college_semester')->where('deleted','0')->get() as $semester)
                  @php
                        $subjects = collect($prospectus)->where('gid',$year->id)->where('description',$semester->description);
                  @endphp
                  <table class="table table-sm border-top border-bottom table-striped">
                        <thead>
                              <tr class="bg-secondary">
                                    <th colspan="7" class="p-2">
                                          <button 
                                          class="btn btn-primary btn-sm multipleaddsubjects" 
                                          data-string="{{$year->levelname}} - {{$semester->description}}"
                                          data-year="{{$year->id}}"
                                          data-sem="{{$semester->id}}"
                                          >Add Subject</button>
                                          {{$year->levelname}} - {{$semester->description}}
                                    </th>
                                    
                              </tr>
                              <tr class="bg-primary">
                                    <td width="20%">Code</td>
                                    <td width="45%">Description</td>
                                    <td width="10%">Prereq</td>
                                    <td width="5%" class="align-middle text-center">Lec.</td>
                                    <td width="5%" class="align-middle text-center">Lab.</td>
                                    <td width="5%"> Total</td>
                                    <td width="5%" class="text-center"></td>
                              </tr>
                        </thead>
                        @if(count($subjects) > 0 )
                       
                              <tbody >
                                    @foreach ($subjects as $item)
                                          <tr >
                                                <td class="align-middle">{{$item->subjCode}}</td>
                                                <td class="align-middle">{{$item->subjDesc}}</td>
                                                <td class="align-middle" style="font-size:10px">
                                                      @if($item->perID != null)
                                                            @php
                                                                  $prereq = DB::table('college_subjprereq')
                                                                              ->join('college_subjects',function($join){
                                                                                    $join->on('college_subjects.id','=','college_subjprereq.prereqsubjID');
                                                                                    $join->where('college_subjects.deleted',0);
                                                                              })
                                                                              ->where('college_subjprereq.subjid',$item->id)
                                                                              ->where('college_subjprereq.deleted',0)
                                                                              ->select('college_subjects.subjCode')
                                                                              ->get();
                                                            @endphp

                                                            @foreach ($prereq as $prereqitem)
                                                                  {{$prereqitem->subjCode}}<br>
                                                            @endforeach
                                                      @endif
                                                </td>
                                                <td class="align-middle text-center">{{$item->lecunits}}</td>
                                                <td class="align-middle text-center">{{$item->labunits}}</td>
                                                <td class="align-middle text-center">{{$item->lecunits + $item->labunits}}</td>
                                                <td>
                                                      {{-- <a href="/dean/remove/prospectussubject/{{$item->id.'--'.Str::slug($item->subjDesc)}}" class="btn"><i class="far fa-trash-alt text-danger"></i></a> --}}
                                                      <div class="dropdown subjectoptions" >
                                                            <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                  <i class="fas fa-ellipsis-v"></i>
                                                            </button>
                                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                            {{-- <a 
                                                                  class="dropdown-item editSubject" 
                                                                  href="#" 
                                                                  data-id="{{$item->id}}"
                                                                  data-class="{{Str::slug($item->subjDesc)}}"
                                                            >Edit Subject</a> --}}
                                                            <a class="dropdown-item removeSubject" href="#" 
                                                                  data-id="{{$item->id}}" 
                                                                  {{-- data-text="{{Str::slug($item->subjDesc)}}"--}}
                                                                  data-class="{{Str::slug($item->subjDesc)}}" 
                                                            >Remove Subject</a>
                                                            <a class="dropdown-item addprereq" href="#" 
                                                                  data-id="{{$item->id}}"
                                                                  data-string="{{$item->subjDesc}}" 
                                                            >Edit Prerequisite</a>
                                                            </div>
                                                      </div>
                                                </td>
                                          </tr>
                                    @endforeach   
                              </tbody>
                             
                        @endif 
                              <tfoot>
                                    <tr>
                                          <td></td>
                                          <td class="text-right" colspan="3">Total Unit:</td>
                                          <td class="text-center">{{collect($subjects)->sum('lecunits') + collect($subjects)->sum('labunits')}}</td>
                                          <td></td>
                                          <td></td>
                                    </tr>
                              </tfoot>
                        </table>
            @endforeach
      @endforeach

{{-- @else

      <table class="table table-bordered">
            <thead>
                  <tr class="text-center">
                        <th>NO SUBJECTS AVAILABLE</th>
                  </tr>
            </thead>
      
      </table>

@endif --}}
