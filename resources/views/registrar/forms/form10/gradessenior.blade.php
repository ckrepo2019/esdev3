
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
                                            <div class="col-2">Grade Level
                                            </div>
                                            <div class="col-2">
                                                <select id="gradelevelid" class="form-control form-control-sm text-uppercase select" disabled>
                                                    {{-- <option value=""></option> --}}
                                                    @foreach($gradelevels as $level)
                                                        <option value="{{$level->id}}" {{$level->id == $recordval->levelid ? 'selected' : ''}}>{{$level->levelname}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-2">School Year:</div>
                                            <div class="col-2"><input type="text" class="form-control form-control-sm"  value="{{$recordval->sydesc}}" disabled/></div>
                                            <div class="col-2">Sem:</div>
                                            <div class="col-2">
                                                @if($recordval->semid == 1)
                                                    <input type="text" class="form-control form-control-sm"  value="1st Sem" disabled/>
                                                @elseif($recordval->semid == 2)
                                                    <input type="text" class="form-control form-control-sm"  value="2nd Sem" disabled/>
                                                @endif
                                            </div>
                                          </div>
                                          <div class="row mb-2">
                                              <div class="col-2">Track/Strand
                                              </div>
                                              <div class="col-4"><input type="text" class="form-control form-control-sm" value="{{$recordval->trackname}}/{{$recordval->strandname}}" disabled/>
                                              </div>
                                              <div class="col-2">Section</div>
                                              <div class="col-4"><input type="text" class="form-control form-control-sm" value="{{$recordval->sectionname}}" disabled/></div>
                                          </div>
                                          {{-- <div class="row mb-2">
                                              <div class="col-3">
                                                  Name of Adviser/Teacher
                                              </div>
                                              <div class="col-9">
                                                  <input type="text" class="form-control form-control-sm" value="{{$recordval->teachername}}" disabled/>
                                              </div>
                                          </div> --}}
                                      </a>
                                  </div>
                              </div>
                              <div id="collapse{{$recordkey}}" class="collapse" data-parent="#accordion">
                                  <div class="card-body p-0">
                                      @if($recordval->type == 2)
                                          <div class="row mb-2">
                                              <div class="col-12 text-right">
                                                  <button type="button" class="btn btn-sm btn-edit-syinfo btn-info" data-id="{{$recordval->id}}"><i class="fa fa-edit"></i> Info</button>
                                                  <button type="button" class="btn btn-sm btn-edit-reportcard btn-info" data-id="{{$recordval->id}}"><i class="fa fa-edit"></i> Report Card</button>
                                                  <button type="button" class="btn btn-sm btn-edit-remedialclasses btn-info" data-id="{{$recordval->id}}"><i class="fa fa-edit"></i> Remedial Classes</button>
                                                  <button type="button" class="btn btn-sm btn-delete-syinfo btn-danger" data-id="{{$recordval->id}}"><i class="fa fa-trash-alt"></i> Delete</button>
                                              </div>
                                          </div>
                                      @endif
                                      <table class="table table-bordered text-uppercase" style="font-size: 12px; table-layout: fixed;">
                                          <thead class="text-center">
                                              <tr>
                                                  <th style="width:10%;" rowspan="2">Indicate if Subject is CORE, APPLIED, or SPECIALIZED</th>
                                                  <th style="width:40%;" rowspan="2">SUBJECTS</th>
                                                  <th colspan="2">Quarterly Rating</th>
                                                  <th style="width:10%;" rowspan="2">SEM FINAL GRADE</th>
                                                  <th style="width:13%;" rowspan="2">ACTION TAKEN</th>
                                                  <th style="width:12%;" rowspan="2"></th>
                                              </tr>
                                              <tr>
                                                  <th style="width:10%;">1</th>
                                                  <th style="width:10%;">2</th>
                                              </tr>
                                          </thead>
                                          <tbody>
                                                @if(count($recordval->grades) == 0)
                                                  <tr>
                                                      <td colspan="7" class="text-center">No grades shown</td>
                                                  </tr>
                                                @else
                                                  @foreach(collect($recordval->grades)->where('semid', $recordval->semid) as $grade)
                                                      @if(strtolower($grade->subjdesc)!= 'general average')
                                                          <tr>
                                                              <td>{{$grade->subjcode}}</td>
                                                              <td>{{$grade->subjdesc}}</td>
                                                              
                                                              @if($grade->q1stat != 0)
                                                                    <td class="text-center p-0">
                                                                        <div class="row text-center p-0 m-0">
                                                                            <input type="number" class="form-control form-control-sm p-0 col-8 text-center" style="display: inline;" @if($grade->q1stat == 2) value="{{$grade->q1}}" @endif/><button type="button" class="btn btn-default col-4 p-0 @if($grade->q1stat == 1) btn-addinauto @else btn-editinauto @endif"  data-subjid="{{$grade->subjid}}" data-quarter="1"  data-syid="{{$recordval->syid}}" data-semid="{{$recordval->semid}}" data-levelid="{{$recordval->levelid}}">@if($grade->q1stat == 2)<i style="display: inline;" class="fa fa-edit fa-xs"></i>@else <i style="display: inline;" class="fa fa-plus fa-xs"></i>@endif</button>
                                                                        </div>
                                                                    </td>
                                                              @else
                                                                <td class="text-center">{{$grade->q1}}</td>
                                                              @endif
                                                              @if($grade->q2stat != 0)
                                                                    <td class="text-center p-0">
                                                                        <div class="row text-center p-0 m-0">
                                                                            <input type="number" class="form-control form-control-sm p-0 col-8 text-center" style="display: inline;" @if($grade->q2stat == 2) value="{{$grade->q2}}" @endif/><button type="button" class="btn btn-default col-4 p-0 @if($grade->q2stat == 1) btn-addinauto @else btn-editinauto @endif"  data-subjid="{{$grade->subjid}}" data-quarter="2"  data-syid="{{$recordval->syid}}" data-semid="{{$recordval->semid}}"  data-levelid="{{$recordval->levelid}}">@if($grade->q2stat == 2)<i style="display: inline;" class="fa fa-edit fa-xs"></i>@else <i style="display: inline;" class="fa fa-plus fa-xs"></i>@endif</button>
                                                                        </div>
                                                                    </td>
                                                              @else
                                                                <td class="text-center">{{$grade->q2}}</td>
                                                              @endif
                                                              <td class="text-center">{{$grade->finalrating}}</td>
                                                              <td class="text-center">{{$grade->remarks}}</td>
                                                              <td></td>
                                                          </tr>
                                                      @endif
                                                  @endforeach
                                                @endif
                                                @if($recordval->type == 1)
                                                    @if(count($recordval->subjaddedforauto)>0)
                                                        @foreach($recordval->subjaddedforauto as $customsubjgrade)
                                                            <tr>
                                                                <td class="p-0"><input type="text" class="form-control form-control-sm subjcode" value="{{$customsubjgrade->subjcode}}" disabled/></td>
                                                                <td class="p-0"><input type="text" class="form-control form-control-sm subjdesc" value="{{$customsubjgrade->subjdesc}}" disabled/></td>
                                                                <td class="text-center p-0"><input type="number" class="form-control form-control-sm subjq1" value="{{$customsubjgrade->q1}}" disabled/></td>
                                                                <td class="text-center p-0"><input type="number" class="form-control form-control-sm subjq2" value="{{$customsubjgrade->q2}}" disabled/></td>
                                                                <td class="text-center p-0"><input type="number" class="form-control form-control-sm subjfinalrating" value="{{$customsubjgrade->finalrating}}" disabled/></td>
                                                                <td class="text-center p-0"><input type="text" class="form-control form-control-sm subjremarks" value="{{$customsubjgrade->actiontaken}}" disabled/></td>
                                                                <td class="text-right p-0">
                                                                    <button type="button" class="btn btn-sm btn-default btn-subjauto-edit"><i class="fa fa-edit text-warning"></i></button><button type="button" class="btn btn-sm btn-default btn-subjauto-update" data-id="{{$customsubjgrade->id}}" disabled><i class="fa fa-share text-success"></i></button><button type="button" class="btn btn-sm btn-default btn-subjauto-delete" data-id="{{$customsubjgrade->id}}" disabled><i class="fa fa-trash text-danger"></i></button>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                @endif
                                          </tbody>
                                          <tfoot>
                                                @if($recordval->type == 1)
                                                    @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'bct')
                                                        <tr>
                                                            <td colspan="6" class="p-0 pl-2 pt-2"><em class="text-danger">Note: The added subjects are not included in General Average computation</em></td>
                                                            <td class="text-center p-0"><button type="button" class="btn btn-default btn-sm m-0 btn-block btn-addsubjinauto" data-syid="{{$recordval->syid}}" data-semid="{{$recordval->semid}}" data-levelid="{{$recordval->levelid}}"><i class="fa fa-plus"></i> Add subject</button></td>
                                                        </tr>
                                                    @endif
                                                    <tr>
                                                        <td colspan="4">General Ave. for the Semester</td>
                                                        <td class="text-center">{{number_format(collect($recordval->grades)->where('semid', $recordval->semid)->avg('finalrating'))}}</td>
                                                        <td class="text-center">{{$recordval->remarks}}</td>
                                                        <td></td>
                                                    </tr>
                                                @elseif($recordval->type == 2)
                                                    @if(count($recordval->grades) > 1)
                                                        @foreach(collect($recordval->grades)->where('semid', $recordval->semid) as $grade)
                                                            @if(strtolower($grade->subjdesc) == 'general average')
                                                                <tr style="font-weight: bold;">
                                                                    <td colspan="4">General Average</td>
                                                                    <td class="text-center">{{$grade->finalrating}}</td>
                                                                    <td class="text-center">{{$grade->remarks}}</td>
                                                                    <td></td>
                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                @endif
                                          </tfoot>
                                      </table>
                                      <div class="row mt-2 mb-2 p-2" style="font-size: 11px;">
                                          <div class="col-2">
                                              <label>REMARKS</label>
                                          </div>
                                          <div class="col-10">
                                              <input type="text" class="form-control form-control-sm" value="{{$recordval->remarks}}"/>
                                          </div>
                                      </div>
                                      <div class="row p-2" style="font-size: 11px;">
                                          <div class="col-4">
                                              <label>Prepared by:</label>
                                              <input type="text" class="form-control form-control-sm" value="{{$recordval->teachername}}"/>
                                              <span>Class Adviser</span>
                                          </div>
                                          <div class="col-4">
                                            <label>Certified True and Correct:</label>
                                            <input type="text" class="form-control form-control-sm" value="{{$recordval->recordincharge}}"/>
                                            <span>SHS-School Record's In-charge</span>
                                          </div>
                                          <div class="col-4">
                                            <label>Date Checked:</label>
                                            <input type="date" class="form-control form-control-sm" value="{{$recordval->datechecked}}"/>
                                          </div>
                                      </div>
                                      <br/>
                                      <br/>

                                      <table class="table table-bordered" style="font-size: 11px;">
                                          <thead>
                                              @if(collect($recordval->remedials)->contains('type','2'))                                              
                                                @foreach($recordval->remedials as $remedial)  
                                                    @if($remedial->type == 2)
                                                        <tr>
                                                            <th style="width: 10%;">REMEDIAL CLASSES</th>
                                                            <th colspan="2">CONDUCTED FROM: @if($remedial->datefrom!=null) <u>{{date('m/d/Y',strtotime($remedial->datefrom))}}</u> @endif &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;TO:  @if($remedial->dateto!=null) <u>{{date('m/d/Y',strtotime($remedial->dateto))}}</u> @endif </th>
                                                            <th>SCHOOL: {{$remedial->schoolname}}</th>
                                                            <th></th>
                                                            <th>SCHOOL ID: {{$remedial->schoolid}}</th>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                              @else
                                                <tr>
                                                    <th style="width: 10%;">REMEDIAL CLASSES</th>
                                                    <th colspan="2">CONDUCTED FROM TO</th>
                                                    <th>SCHOOL:</th>
                                                    <th></th>
                                                    <th>SCHOOL ID:</th>
                                                </tr>
                                              @endif
                                              <tr>
                                                  <th>INDICATE IF SUBJECT IS CORE, APPLIED, OR SPECIALIZED	</th>
                                                  <th>SUBJECTS</th>
                                                  <th>SEM FINAL GRADE</th>
                                                  <th>REMEDIAL CLASS MARK</th>
                                                  <th>RECOMPUTED FINAL GRADE</th>
                                                  <th>ACTION TAKE</th>
                                              </tr>
                                          </thead>
                                          <tbody>
                                              @if(count($recordval->remedials)>0)
                                                    @foreach($recordval->remedials as $remedial)
                                                        @if($remedial->type == 1)
                                                            <tr>
                                                                <td>{{$remedial->subjectcode}}</td>
                                                                <td>{{$remedial->subjectname}}</td>
                                                                <td>{{$remedial->finalrating}}</td>
                                                                <td>{{$remedial->remclassmark}}</td>
                                                                <td>{{$remedial->recomputedfinal}}</td>
                                                                <td>{{$remedial->remarks}}</td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                              @else
                                              @endif
                                          </tbody>
                                      </table>
                                  </div>
                              </div>
                          </div>
                      @endforeach
                    @endif
                </div>
                <div class="card">
                    <div class="card-header bg-info">
                        &nbsp;
                    </div>
                    <div class="card-body" style="font-size: 13px;">
                        <div class="row mb-4">
                            <div class="col-md-3">Track/Strand Accomplished: </div>
                            <div class="col-md-4"><input type="text" class="form-control" id="footerstrandaccomplished" placeholder="Enter text here" value="{{$footer->strandaccomplished}}"/></div>
                            <div class="col-md-3">SHS General Average: </div>
                            <div class="col-md-2"><input type="number" class="form-control" id="footergenave" value="{{$footer->shsgenave}}"/></div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-8">
                                <label>Awards/Honors Received:</label><br/>
                                <textarea id="footerhonorsreceived" class="form-control">{{$footer->honorsreceived}}</textarea>
                            </div>
                            <div class="col-md-4">
                                <label>Date of SHS Garduation:</label><br/>
                                <input type="date" class="form-control" id="footerdategrad" value="{{$footer->shsgraduationdateshow}}"/>
                            </div>
                        </div>
                        
                        @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'hcb')
                            <div class="row mb-5">
                                <div class="col-md-2">Date Certified: </div>
                                <div class="col-md-4"><input type="date" class="form-control" id="footerdatecertified" value="{{$footer->datecertifiedshow}}"/></div>
                            </div>
                        @endif
                        <div class="row mb-5">
                            @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hcb')
                                <div class="col-md-2">REMARKS: </div>
                                <div class="col-md-4">
                                    <table>
                                        <tr>
                                            <td style="border-bottom: 1px solid black;">
                                                <input type="text" class="form-control" id="footercopyforupper" placeholder="Enter text here" value="{{$footer->copyforupper}}"/>
                                            </td>
                                        </tr>
                                        
                                        @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'hcb')
                                            <tr>
                                                <td>
                                                    <input type="text" class="form-control" id="footercopyforlower" placeholder="Enter text here" value="{{$footer->copyforlower}}"/>
                                                </td>
                                            </tr>
                                        @endif
                                    </table>
                                </div>
                            @else
                                <div class="col-md-2">COPY FOR: </div>
                                <div class="col-md-4">
                                    <table>
                                        <tr>
                                            <td style="border-bottom: 1px solid black;">
                                                <input type="text" class="form-control" id="footercopyforupper" placeholder="Enter text here" value="{{$footer->copyforupper}}"/>
                                            </td>
                                        </tr>
                                        
                                        @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'hcb')
                                            <tr>
                                                <td>
                                                    <input type="text" class="form-control" id="footercopyforlower" placeholder="Enter text here" value="{{$footer->copyforlower}}"/>
                                                </td>
                                            </tr>
                                        @endif
                                    </table>
                                </div>
                            @endif
                            <div class="col-md-6 text-right d-block">
                                <label>&nbsp;</label><br/>
                                <button type="button" class="btn btn-primary" id="btn-savefooter"><i class="fa fa-share"></i> Save Changes</button>
                            </div>
                        </div>
                        {{-- <div class="row mb-5">
                        </div> --}}
                    </div>
                </div>