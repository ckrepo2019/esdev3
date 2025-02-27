@if(count($studenttor) > 0)

@foreach (DB::table('gradelevel')->where('acadprogid','6')->where('deleted','0')->get() as $year)
      @foreach (DB::table('college_semester')->where('deleted','0')->get() as $semester)
            @php
                  $subjects = collect($studenttor)->where('gid',$year->id)->where('description',$semester->description);
            @endphp
            <table class="table table-sm border-top border-bottom table-striped" data-id="0" id="notorid">
                  <thead>
                        <tr class="bg-secondary">
                              <th colspan="9" class="p-2">
                                    <button 
                                    class="btn btn-primary btn-sm multipleaddsubjects" 
                                    data-string="{{$year->levelname}} - {{$semester->description}}"
                                    data-year="{{$year->id}}"
                                    data-sem="{{$semester->id}}"
                                    >Add Subject</button>
                                    {{$year->levelname}} - {{$semester->description}}
                              </th>
                              
                        </tr>
                        <tr >
                              <td width="5%" class="align-middle">
                              <td width="20%" class="align-middle">Code</td>
                              <td width="45%" class="align-middle">Description</td>
                              <td width="5%" class="align-middle text-center">Lec.</td>
                              <td width="5%" class="align-middle text-center">Lab.</td>
                              <td width="5%" class="align-middle"> Total</td>
                              <td width="5%" class="align-middle">Midterm</td>
                              <td width="5%" class="text-center align-middle">Final</td>
                              <td width="5%" class="text-center align-middle">Remarks</td>
                              </td>
                            
                        </tr>
                  </thead>
                  @if(count($subjects) > 0 )
            
                        <tbody >
                              @foreach ($subjects as $item)
                              @if($item->remarks == 0)
                                    <tr >
                              @elseif($item->remarks == 1)
                                    <tr class="bg-success">
                              @else
                                    <tr class="bg-danger">
                              @endif
                                    <td class="align-middle">
                                          <div class="dropdown">
                                                <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                      <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <a 
                                                class="dropdown-item updateGrade" 
                                                href="#" 
                                                data-id="{{$item->studprosid}}"
                                                data-string="{{$item->subjDesc}}"
                                                >Edit Grades</a>
                                                </div>
                                          </div>
                                    </td>
                                          <td class="align-middle align-middle">{{$item->subjCode}}</td>
                                          <td class="align-middle align-middle">{{$item->subjDesc}}</td>
                                          <td class="align-middle text-center">{{$item->lecunits}}</td>
                                          <td class="align-middle text-center">{{$item->labunits}}</td>
                                          <td class="align-middle text-center">{{$item->lecunits + $item->labunits}}</td>
                                       
                                          <td class="text-center align-middle">{{$item->midtermgrade}}</td>
                                          <td class="text-center align-middle">{{$item->finalgrade}}</td>
                                          <td class="text-center align-middle">
                                                @if($item->remarks == 0)

                                                @elseif($item->remarks == 1)
                                                      PASSED
                                                @else
                                                      FAILED
                                                @endif
                                          </td>
                                    </tr>
                              @endforeach   
                        </tbody>
                  
                  @endif 
                        <tfoot>
                              <tr>
                                    <td></td>
                                    <td></td>
                                    <td class="text-right" colspan="3">Total Unit:</td>
                                    <td class="text-center">{{collect($subjects)->sum('lecunits') + collect($subjects)->sum('labunits')}}</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                               
                              </tr>
                        </tfoot>
                  </table>
      @endforeach
@endforeach

@else

      <table class="table mb-0" data-id="1" id="notorid">
            <thead>
                  <tr>
                        <th class="text-center">Transcript of record is not yet created.</th>
                  </tr>
            </thead>
      </table>

@endif