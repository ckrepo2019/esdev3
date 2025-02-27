
                  <!-- we are adding the accordion ID so Bootstrap's collapse plugin detects it -->
                  <div id="accordion" >
                    @if(count($records)>0)
                      @foreach($records as $recordkey => $recordval)
                          <div class="card card-danger eachrecord" data-id="{{$recordval->id}}">
                              <div class="card-header" style="font-size: 12px !important;">
                                  <div class="col-md-12" >
                                      <a data-toggle="collapse" data-parent="#accordion" href="#collapse{{$recordkey}}">
                                          <div class="row mb-2">
                                              <div class="col-2">School</div>
                                              <div class="col-4"><input type="text" class="form-control form-control-sm" value="{{$recordval->schoolname}}" disabled/></div>
                                              <div class="col-2">School ID</div>
                                              <div class="col-4"><input type="text" class="form-control form-control-sm" value="{{$recordval->schoolid}}" disabled/></div>
                                          </div>
                                          <div class="row mb-2">
                                              <div class="col-2">District
                                              </div>
                                              <div class="col-2"><input type="text" class="form-control form-control-sm" value="{{$recordval->schooldistrict}}" disabled/></div>
                                              <div class="col-2">Division</div>
                                              <div class="col-2"><input type="text" class="form-control form-control-sm" value="{{$recordval->schooldivision}}" disabled/></div>
                                              <div class="col-2">Region</div>
                                              <div class="col-2"><input type="text" class="form-control form-control-sm" value="{{$recordval->schoolregion}}" disabled/></div>
                                          </div>
                                          <div class="row mb-2">
                                              <div class="col-2">Classified as Grade
                                              </div>
                                              <div class="col-2">
                                                  <select id="gradelevelid" class="form-control form-control-sm text-uppercase select" disabled>
                                                      {{-- <option value=""></option> --}}
                                                      @foreach($gradelevels as $level)
                                                          <option value="{{$level->id}}" {{$level->id == $recordval->levelid ? 'selected' : ''}}>{{$level->levelname}}</option>
                                                      @endforeach
                                                  </select>
                                              </div>
                                              <div class="col-2">Section</div>
                                              <div class="col-2"><input type="text" class="form-control form-control-sm" value="{{$recordval->sectionname}}" disabled/></div>
                                              <div class="col-2">School Year:</div>
                                              <div class="col-2"><input type="text" class="form-control form-control-sm"  value="{{$recordval->sydesc}}" disabled/></div>
                                          </div>
                                          <div class="row mb-2">
                                              <div class="col-3">
                                                  Name of Adviser/Teacher
                                              </div>
                                              <div class="col-9">
                                                  <input type="text" class="form-control form-control-sm" value="{{$recordval->teachername}}" disabled/>
                                              </div>
                                          </div>
                                      </a>
                                  </div>
                              </div>
                              <div id="collapse{{$recordkey}}" class="collapse" data-parent="#accordion">
                                  <div class="card-body">
                                      @if($recordval->type == 2)
                                          <div class="row mb-2">
                                              <div class="col-12 text-right">
                                                  <button type="button" class="btn btn-sm btn-edit-syinfo btn-info" data-id="{{$recordval->id}}"><i class="fa fa-edit"></i> Info</button>
                                                  <button type="button" class="btn btn-sm btn-edit-reportcard btn-info" data-id="{{$recordval->id}}"><i class="fa fa-edit"></i> Report Card</button>
                                                  {{-- <button type="button" class="btn btn-sm btn-edit-remedialclasses btn-info" data-id="{{$recordval->id}}"><i class="fa fa-edit"></i> Remedial Classes</button> --}}
                                                  <button type="button" class="btn btn-sm btn-delete-syinfo btn-danger" data-id="{{$recordval->id}}"><i class="fa fa-trash-alt"></i> Delete</button>
                                              </div>
                                          </div>
                                      @endif
                                      <table class="table table-bordered text-uppercase" style="font-size: 12px; table-layout: fixed;">
                                          <thead class="text-center">
                                              <tr>
                                                  <th style="width:35%;" rowspan="2" class="p-0">LEARNING AREAS</th>
                                                  <th colspan="4" style="width: 35%;" class="p-0">Quarterly Rating</th>
                                                  <th style="width:8%;" rowspan="2" class="p-0">Final<br/>Rating</th>
                                                  <th style="width:10%;" rowspan="2" class="p-0">Remarks</th>
                                                  <th rowspan="2"></th>
                                              </tr>
                                              <tr>
                                                  <th>1</th>
                                                  <th>2</th>
                                                  <th>3</th>
                                                  <th>4</th>
                                              </tr>
                                          </thead>
                                          <tbody>
                                              @if(count($recordval->grades) == 0)
                                                  <tr>
                                                      <td colspan="8" class="text-center">No grades shown</td>
                                                  </tr>
                                              @else
                                                  @foreach($recordval->grades as $grade)
                                                      @if(strtolower($grade->subjtitle)!= 'general average')
                                                          <tr style="font-size: 12px;">
                                                              <td class="p-1" style="vertical-align: middle;">{{$grade->subjtitle}}</td>
                                                              @if($grade->q1stat != 0)
                                                                <td class="text-center p-0">
                                                                    <div class="row text-center p-0 m-0">
                                                                        <input type="number" class="form-control form-control-sm p-0 col-8 text-center" style="display: inline;" @if($grade->q1stat == 2) value="{{$grade->quarter1}}" @endif/><button type="button" class="btn btn-default col-4 p-0 @if($grade->q1stat == 1) btn-addinauto @else btn-editinauto @endif"  data-subjid="{{$grade->subjid}}" data-quarter="1"  data-syid="{{$recordval->syid}}"  data-levelid="{{$recordval->levelid}}">@if($grade->q1stat == 2)<i style="display: inline;" class="fa fa-edit fa-xs"></i>@else <i style="display: inline;" class="fa fa-plus fa-xs"></i>@endif</button>
                                                                    </div>
                                                                </td>
                                                              @else
                                                                <td class="text-center p-1" style="vertical-align: middle;">{{$grade->quarter1}}</td>
                                                              @endif
                                                              @if($grade->q2stat != 0)
                                                                <td class="text-center p-0">
                                                                    <div class="row text-center p-0 m-0">
                                                                        <input type="number" class="form-control form-control-sm p-0 col-8 text-center" style="display: inline;" @if($grade->q2stat == 2) value="{{$grade->quarter2}}" @endif/><button type="button" class="btn btn-default col-4 p-0  @if($grade->q1stat == 1) btn-addinauto @else btn-editinauto @endif"  data-subjid="{{$grade->subjid}}" data-quarter="2" data-syid="{{$recordval->syid}}"  data-levelid="{{$recordval->levelid}}">@if($grade->q2stat == 2)<i style="display: inline;" class="fa fa-edit fa-xs"></i>@else <i style="display: inline;" class="fa fa-plus fa-xs"></i>@endif</button>
                                                                    </div>
                                                                </td>
                                                              @else
                                                                <td class="text-center p-1" style="vertical-align: middle;">{{$grade->quarter2}}</td>
                                                              @endif
                                                              @if($grade->q3stat != 0)
                                                                <td class="text-center p-0">
                                                                    <div class="row text-center p-0 m-0">
                                                                        <input type="number" class="form-control form-control-sm p-0 col-8 text-center" style="display: inline;" @if($grade->q3stat == 2) value="{{$grade->quarter3}}" @endif/><button type="button" class="btn btn-default col-4 p-0  @if($grade->q1stat == 1) btn-addinauto @else btn-editinauto @endif"  data-subjid="{{$grade->subjid}}" data-quarter="3" data-syid="{{$recordval->syid}}"  data-levelid="{{$recordval->levelid}}">@if($grade->q3stat == 2)<i style="display: inline;" class="fa fa-edit fa-xs"></i>@else <i style="display: inline;" class="fa fa-plus fa-xs"></i>@endif</button>
                                                                    </div>
                                                                </td>
                                                              @else
                                                                <td class="text-center p-1" style="vertical-align: middle;">{{$grade->quarter3}}</td>
                                                              @endif
                                                              @if($grade->q4stat != 0)
                                                                <td class="text-center p-0">
                                                                    <div class="row text-center p-0 m-0">
                                                                        <input type="number" class="form-control form-control-sm p-0 col-8 text-center" style="display: inline;" @if($grade->q4stat == 2) value="{{$grade->quarter4}}" @endif/><button type="button" class="btn btn-default col-4 p-0  @if($grade->q1stat == 1) btn-addinauto @else btn-editinauto @endif"  data-subjid="{{$grade->subjid}}" data-quarter="4" data-syid="{{$recordval->syid}}"  data-levelid="{{$recordval->levelid}}">@if($grade->q4stat == 2)<i style="display: inline;" class="fa fa-edit fa-xs"></i>@else <i style="display: inline;" class="fa fa-plus fa-xs"></i>@endif</button>
                                                                    </div>
                                                                </td>
                                                              @else
                                                                <td class="text-center p-1" style="vertical-align: middle;">{{$grade->quarter4}}</td>
                                                              @endif
                                                              <td class="text-center p-1" style="vertical-align: middle;">{{$grade->finalrating}}</td>
                                                              <td class="text-center p-1" style="vertical-align: middle;">{{$grade->remarks}}</td>
                                                              <td></td>
                                                          </tr>
                                                      @endif
                                                  @endforeach
                                              @endif
                                              {{-- @if($recordval->type == 1) --}}
                                                  @if(count($recordval->subjaddedforauto)>0)
                                                      @foreach($recordval->subjaddedforauto as $customsubjgrade)
                                                          <tr>
                                                              <td class="p-0"><input type="text" class="form-control form-control-sm subjdesc" value="{{$customsubjgrade->subjdesc}}" disabled/></td>
                                                              <td class="text-center p-0"><input type="number" class="form-control form-control-sm subjq1" value="{{$customsubjgrade->q1}}" disabled/></td>
                                                              <td class="text-center p-0"><input type="number" class="form-control form-control-sm subjq2" value="{{$customsubjgrade->q2}}" disabled/></td>
                                                              <td class="text-center p-0"><input type="number" class="form-control form-control-sm subjq3" value="{{$customsubjgrade->q3}}" disabled/></td>
                                                              <td class="text-center p-0"><input type="number" class="form-control form-control-sm subjq4" value="{{$customsubjgrade->q4}}" disabled/></td>
                                                              <td class="text-center p-0"><input type="number" class="form-control form-control-sm subjfinalrating" value="{{$customsubjgrade->finalrating}}" disabled/></td>
                                                              <td class="text-center p-0"><input type="text" class="form-control form-control-sm subjremarks" value="{{$customsubjgrade->actiontaken}}" disabled/></td>
                                                              <td class="text-right p-0">
                                                                  <button type="button" class="btn btn-sm btn-default btn-subjauto-edit"><i class="fa fa-edit text-warning"></i></button><button type="button" class="btn btn-sm btn-default btn-subjauto-update" data-id="{{$customsubjgrade->id}}" disabled><i class="fa fa-share text-success"></i></button><button type="button" class="btn btn-sm btn-default btn-subjauto-delete" data-id="{{$customsubjgrade->id}}" disabled><i class="fa fa-trash text-danger"></i></button>
                                                              </td>
                                                          </tr>
                                                      @endforeach
                                                  @endif
                                              {{-- @endif --}}
                                          </tbody>
                                          <tfoot>
                                                @if($recordval->type == 1)
                                                    @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'bct' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sihs')
                                                        <tr>
                                                            <td colspan="7" class="p-0 pl-2 pt-2"><em class="text-danger">Note: The added subjects are not included in General Average computation</em></td>
                                                            <td class="text-center p-0"><button type="button" class="btn btn-default btn-sm m-0 btn-block btn-addsubjinauto" data-syid="{{$recordval->syid}}" data-levelid="{{$recordval->levelid}}"><i class="fa fa-plus"></i> Subject</button></td>
                                                        </tr>
                                                    @endif
                                                    <tr>
                                                        <td></td>
                                                        <td colspan="4">General Average</td>
                                                        <td class="text-center">@if(collect($recordval->generalaverage)->count()>0){{collect($recordval->generalaverage)->first()->finalrating}}@endif</td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                @elseif($recordval->type == 2)
                                                    @if(count($recordval->generalaverage) == 0)
                                                        <!--@foreach($recordval->grades as $grade)-->
                                                            <!--@if(strtolower($grade->subjtitle) == 'general average')-->
                                                                <tr>
                                                                    <td></td>
                                                                    <td colspan="4">General Average</td>
                                                                    <td class="text-center">{{$recordval->generalaverage[0]->finalrating}}</td>
                                                                    <td class="text-center">{{$recordval->generalaverage[0]->remarks}}</td>
                                                                    <td></td>
                                                                </tr>
                                                            <!--@endif-->
                                                        <!--@endforeach-->
                                                    @else
                                                        <!--@foreach($recordval->grades as $grade)-->
                                                            <!--@if(strtolower($grade->subjtitle) == 'general average')-->
                                                                <tr>
                                                                    <td></td>
                                                                    <td colspan="4">General Average</td>
                                                                    <td class="text-center">{{$recordval->generalaverage[0]->finalrating}}</td>
                                                                    <td class="text-center">{{$recordval->generalaverage[0]->remarks}}</td>
                                                                    <td></td>
                                                                </tr>
                                                            <!--@endif-->
                                                        <!--@endforeach-->
                                                    @endif
                                                @endif
                                          </tfoot>
                                      </table>
                                  </div>
                              </div>
                          </div>
                      @endforeach
                    @endif
                </div>
                @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hcb' )
                    <div class="card">
                        <div class="card-header bg-info">
                            <h5>C E R T I F I C A T I O N</h5>
                        </div>
                        <div class="card-body" style="font-size: 13px;">
                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <label>Copy sent to:</label>
                                    <input type="text" class="form-control" id="certcopysentto" value="{{$footer->copysentto}}" placeholder=""/>
                                </div>
                                <div class="col-md-4">
                                    <label>Address:</label>
                                    <input type="text" class="form-control" id="certaddress" value="{{$footer->address}}" placeholder=""/>
                                </div>
                                <div class="col-md-4 text-right">
                                    <label>&nbsp;</label><br/>
                                    <button type="button" class="btn btn-primary" id="btn-savefooter"><i class="fa fa-share"></i> Save Changes</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sihs' )
            
                @elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'bct')
                    <div class="card">
                        <div class="card-header bg-info">
                            <h5>C E R T I F I C A T I O N</h5>
                        </div>
                        <div class="card-body" style="font-size: 13px;">
                            <div class="row mb-2">
                                <div class="col-md-12">
                                    <label>Purpose</label>
                                    <input type="text" class="form-control" id="purpose" value="{{$footer->purpose}}" placeholder="Type purposes of the copy here"/>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <label>Records In-charge</label>
                                    <input type="text" class="form-control" id="recordsincharge" value="{{$footer->recordsincharge}}" placeholder="Records In-charge"/>
                                </div>
                                <div class="col-md-6 text-right">
                                    <label>&nbsp;</label><br/>
                                    <button type="button" class="btn btn-primary" id="btn-savefooter"><i class="fa fa-share"></i> Save Changes</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="card">
                        <div class="card-header bg-info">
                            <h5>C E R T I F I C A T I O N</h5>
                        </div>
                        <div class="card-body" style="font-size: 13px;">
                            <div class="row mb-2">
                                <div class="col-md-12">
                                    <label>Purpose</label>
                                    <input type="text" class="form-control" id="purpose" value="{{$footer->purpose}}" placeholder="Type purposes of the copy here"/>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <label>Class Adviser</label>
                                    <input type="text" class="form-control" id="classadviser" value="{{$footer->classadviser}}" placeholder="Class Adviser"/>
                                </div>
                                <div class="col-md-6">
                                    <label>Records In-charge</label>
                                    <input type="text" class="form-control" id="recordsincharge" value="{{$footer->recordsincharge}}" placeholder="Records In-charge"/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <label>&nbsp;</label><br/>
                                    <button type="button" class="btn btn-primary" id="btn-savefooter"><i class="fa fa-share"></i> Save Changes</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                {{-- <div class="card">
                    <div class="card-header bg-info">
                        <h5>C E R T I F I C A T I O N</h5>
                    </div>
                    <div class="card-body" style="font-size: 13px;">
                        @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hcb' )
                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <label>Copy sent to:</label>
                                    <input type="text" class="form-control" id="certcopysentto" value="{{$footer->copysentto}}" placeholder=""/>
                                </div>
                                <div class="col-md-4">
                                    <label>Address:</label>
                                    <input type="text" class="form-control" id="certaddress" value="{{$footer->address}}" placeholder=""/>
                                </div>
                                <div class="col-md-4 text-right">
                                    <label>&nbsp;</label><br/>
                                    <button type="button" class="btn btn-primary" id="btn-savefooter"><i class="fa fa-share"></i> Save Changes</button>
                                </div>
                            </div>
                        @else
                            <div class="row mb-2">
                                <div class="col-md-12">
                                    <label>Purpose</label>
                                    <input type="text" class="form-control" id="purpose" value="{{$footer->copyforupper}}" placeholder="Type purposes of the copy here"/>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <label>Class Adviser</label>
                                    <input type="text" class="form-control" id="classadviser" value="{{$footer->classadviser}}" placeholder="Class Adviser"/>
                                </div>
                                <div class="col-md-6">
                                    <label>Records In-charge</label>
                                    <input type="text" class="form-control" id="recordsincharge" value="{{$footer->recordsincharge}}" placeholder="Records In-charge"/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <label>&nbsp;</label><br/>
                                    <button type="button" class="btn btn-primary" id="btn-savefooter"><i class="fa fa-share"></i> Save Changes</button>
                                </div>
                            </div>
                        @endif
                    </div>
                </div> --}}