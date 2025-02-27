
                    <div class="card" style="font-size: 12px;">
                        <div class="ribbon-wrapper ribbon-sm">
                            <div class="ribbon bg-warning text-sm">NEW</div>
                        </div>
                        <button id="removeCard" class="btn btn-xs btn-outline-danger removeCard col-md-1"><i class="fa fa-times"></i></button>
                        <div class="card-header">
                            <div class="row mb-2">
                                <div class="col-md-2">School</div>
                                <div class="col-md-4"><input type="text" class="form-control form-control-sm" name="add-schoolname"/></div>
                                <div class="col-md-2">School ID</div>
                                <div class="col-md-4"><input type="text" class="form-control form-control-sm" name="add-schoolid"/></div>
                            </div>
                            @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'bct')
                                <div class="row mb-2">
                                    <div class="col-md-2">Grade Level
                                    </div>
                                    <div class="col-md-2">
                                        <select id="gradelevelid" name="add-gradelevelid" class="form-control form-control-sm text-uppercase select" readonly>
                                            {{-- <option value=""></option> --}}
                                            @foreach($gradelevels as $level)
                                                <option value="{{$level->id}}"@if($level->id == $levelid) selected @endif>{{$level->levelname}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">School Year:</div>
                                    <div class="col-md-2"><input type="text" class="form-control form-control-sm" name="add-schoolyear" value="{{$schoolyear}}" readonly/></div>
                                    <div class="col-md-2">Sem:</div>
                                    <div class="col-md-2">
                                        
                                        <select id="semester" name="add-semester" class="form-control form-control-sm text-uppercase select" readonly>
                                            <option value="1" @if($semid == 1) selected @endif>1st Semester</option>
                                            <option value="2" @if($semid == 2) selected @endif>2nd Semester</option>
                                        </select>
                                    </div>
                                </div>
                            @else
                                <div class="row mb-2">
                                    <div class="col-md-2">Grade Level
                                    </div>
                                    <div class="col-md-2">
                                        <select id="gradelevelid" name="add-gradelevelid" class="form-control form-control-sm text-uppercase select">
                                            {{-- <option value=""></option> --}}
                                            @foreach($gradelevels as $level)
                                                <option value="{{$level->id}}">{{$level->levelname}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">School Year:</div>
                                    <div class="col-md-2"><input type="text" class="form-control form-control-sm" name="add-schoolyear" value="" /></div>
                                    <div class="col-md-2">Sem:</div>
                                    <div class="col-md-2">
                                        
                                        <select id="semester" name="add-semester" class="form-control form-control-sm text-uppercase select" >
                                            <option value="1">1st Semester</option>
                                            <option value="2">2nd Semester</option>
                                        </select>
                                    </div>
                                </div>
                            @endif
                            <div class="row mb-2">
                                <div class="col-md-2">Track</div>
                                <div class="col-md-2"><input type="text" class="form-control form-control-sm" name="add-trackname"/></div>
                                <div class="col-md-2">Strand</div>
                                <div class="col-md-2"><input type="text" class="form-control form-control-sm" name="add-strandname"/></div>
                                <div class="col-md-2">Section</div>
                                <div class="col-md-2"><input type="text" class="form-control form-control-sm" name="add-sectionname"/></div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-3">
                                    Name of Adviser/Teacher
                                </div>
                                <div class="col-md-9">
                                    <input type="text" class="form-control form-control-sm" name="add-teachername"/>
                                </div>
                            </div>
                            {{-- <div class="form-row">
                                <div class="col-md-3">
                                    <div class="position-relative form-group ">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputGroupPrepend">CLASSIFIED AS:</span>
                                            </div>
                                            <select id="gradelevelid" name="gradelevelid" class="form-control form-control-sm text-uppercase select" required>
                                                <option value=""></option>
                                                @foreach($gradelevels as $level)
                                                    <option value="{{$level->id}}">{{$level->levelname}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group ">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputGroupPrepend">School</span>
                                            </div>
                                            <input id="schoolname" name="schoolname" type="text" class="form-control text-uppercase" id="validationCustomUsername" aria-describedby="inputGroupPrepend" placeholder="(Municipal)" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="position-relative form-group ">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputGroupPrepend">School Year:</span>
                                            </div>
                                            <input type="text" name="schoolyear_from" class="yearpicker form-control" value="" />
                                            <div class="input-group-append">
                                                <span class="input-group-text" id="inputGroupPrepend">to</span>
                                            </div>
                                            <input type="text" name="schoolyear_to" class="yearpicker form-control" value="" />
                                            <input id="schoolyear" name="schoolyear" type="text" class="form-control text-uppercase" id="validationCustomUsername" aria-describedby="inputGroupPrepend" placeholder="School Year" required>
                                        </div>
                                    </div>
                                </div>
                            </div> --}}
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                        {{-- <input type="hidden" name="studentid" value="{{$studentdata->id}}"/> --}}
                                        <table class="table table-bordered uppercase fontSize">
                                            <thead>
                                                <tr>
                                                    <th width="15%">Indicate if Subject is CORE, APPLIED, or SPECIALIZED</th>
                                                    <th width="40%">SUBJECTS</th>
                                                    <th>1</th>
                                                    <th>2</th>
                                                    <th>SEM FINAL GRADE</th>
                                                    <th>ACTION TAKEN</th>
                                                    {{-- <th>CREDITS EARNED</th> --}}
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody id="tbody">
                                                @if(count($subjects) == 0)
                                                    <tr class="tr-eachsubject">
                                                        <td class="tdInputClass">
                                                            <input type="text" class="form-control input7" value="1" hidden/>
                                                            <input type="text" class="form-control input0" value="0" hidden/>
                                                            <input type="text" class="form-control input1" name="add-indication[]" required/>
                                                        </td>
                                                        <td class="tdInputClass"><input type="text" class="form-control input2" name="add-subject[]" required/></td>
                                                        <td class="tdInputClass"><input type="number" class="form-control input3" max="100" name="add-q1[]" required/></td>
                                                        <td class="tdInputClass"><input type="number" class="form-control input4" name="add-q2[]" required/></td>
                                                        <td class="tdInputClass"><input type="number" class="form-control input5" name="add-final[]" required/></td>
                                                        <td class="tdInputClass"><input type="text" class="form-control input6" name="add-remarks[]" required/></td>
                                                        {{-- <td class="tdInputClass"><input type="number" class="form-control" name="entry[]" required/></td> --}}
                                                        <td class="removebutton"><center><i class="fa fa-trash text-gray"></i></center></td>
                                                    </tr>
                                                @else
                                                    @foreach ($subjects as $subject)
                                                    <tr class="tr-eachsubject">
                                                        <td class="tdInputClass">
                                                            <input type="text" class="form-control input7" value="{{$subject->editable}}" hidden/>
                                                            <input type="text" class="form-control input0" value="1" hidden/>
                                                            <input type="text" class="form-control input1" name="add-indication[]" required value="{{$subject->subjcode}}" readonly/>
                                                            <input type="text" class="form-control input000mapeh" value="{{$subject->inMAPEH}}" hidden>
                                                            <input type="text" class="form-control input000tle" value="{{$subject->inTLE}}" hidden>
                                                        </td>
                                                        <td class="tdInputClass">
                                                            <input type="text" class="form-control input2" name="add-subject[]" required value="{{$subject->subjdesc}}" readonly/>
                                                        </td>
                                                        <td class="tdInputClass">
                                                            <input type="number" class="form-control input3" max="100" name="add-q1[]" required value="{{$subject->q1}}" @if($subject->editable == 0) readonly @endif/>
                                                        </td>
                                                        <td class="tdInputClass">
                                                            <input type="number" class="form-control input4" name="add-q2[]" required value="{{$subject->q2}}" @if($subject->editable == 0) readonly @endif/>
                                                        </td>
                                                        <td class="tdInputClass">
                                                            <input type="number" class="form-control input5" name="add-final[]" required value="{{$subject->final}}"  @if($subject->editable == 0) readonly @endif/>
                                                        </td>
                                                        <td class="tdInputClass">
                                                            <input type="text" class="form-control input6" name="add-remarks[]" required value="{{$subject->remarks}}" />
                                                        </td>
                                                        {{-- <td class="tdInputClass"><input type="number" class="form-control" name="entry[]" required/></td> --}}
                                                        <td class="removebutton"><center><i class="fa fa-trash text-gray"></i></center></td>
                                                    </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td class="tdInputClass"><input type="text" class="form-control" name="add-generalaverage" value="General Average" disabled/></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td class="tdInputClass"><input type="number" class="form-control grades" name="add-generalaverageval" required/></td>
                                                    <td class="tdInputClass"><input type="text" class="form-control grades" name="add-generalaveragerem" required/></td>
                                                    <td></td>
                                                    {{-- <td></td> --}}
                                                </tr>
                                                <tr>
                                                    <td colspan="6" style="border-bottom: hidden; border-left: hidden;"></td>
                                                    <td id="addrow"><center><i class="fa fa-plus"></i></center></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                        {{-- <table class="table table-bordered fontSize">
                                            <thead>
                                                <tr>
                                                    <th width="20%"></th>
                                                    <th>Jun</th>
                                                    <th>Jul</th>
                                                    <th>Aug</th>
                                                    <th>Sept</th>
                                                    <th>Oct</th>
                                                    <th>Nov</th>
                                                    <th>Dec</th>
                                                    <th>Jan</th>
                                                    <th>Feb</th>
                                                    <th>Mar</th>
                                                    <th>Apr</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <th>No. of School</th>
                                                    <td class="tdInputClass"><input type="number" class="form-control" name="schooldays[]" required/></td>
                                                    <td class="tdInputClass"><input type="number" class="form-control" name="schooldays[]" required/></td>
                                                    <td class="tdInputClass"><input type="number" class="form-control" name="schooldays[]" required/></td>
                                                    <td class="tdInputClass"><input type="number" class="form-control" name="schooldays[]" required/></td>
                                                    <td class="tdInputClass"><input type="number" class="form-control" name="schooldays[]" required/></td>
                                                    <td class="tdInputClass"><input type="number" class="form-control" name="schooldays[]" required/></td>
                                                    <td class="tdInputClass"><input type="number" class="form-control" name="schooldays[]" required/></td>
                                                    <td class="tdInputClass"><input type="number" class="form-control" name="schooldays[]" required/></td>
                                                    <td class="tdInputClass"><input type="number" class="form-control" name="schooldays[]" required/></td>
                                                    <td class="tdInputClass"><input type="number" class="form-control" name="schooldays[]" required/></td>
                                                    <td class="tdInputClass"><input type="number" class="form-control" name="schooldays[]" required/></td>
                                                    <td class="tdInputClass"><input type="number" class="form-control" name="schooldays[]" required/></td>
                                                </tr>
                                                <tr>
                                                    <th>No. of Days present</th>
                                                    <td class="tdInputClass"><input type="number" class="form-control" name="dayspresent[]" required/></td>
                                                    <td class="tdInputClass"><input type="number" class="form-control" name="dayspresent[]" required/></td>
                                                    <td class="tdInputClass"><input type="number" class="form-control" name="dayspresent[]" required/></td>
                                                    <td class="tdInputClass"><input type="number" class="form-control" name="dayspresent[]" required/></td>
                                                    <td class="tdInputClass"><input type="number" class="form-control" name="dayspresent[]" required/></td>
                                                    <td class="tdInputClass"><input type="number" class="form-control" name="dayspresent[]" required/></td>
                                                    <td class="tdInputClass"><input type="number" class="form-control" name="dayspresent[]" required/></td>
                                                    <td class="tdInputClass"><input type="number" class="form-control" name="dayspresent[]" required/></td>
                                                    <td class="tdInputClass"><input type="number" class="form-control" name="dayspresent[]" required/></td>
                                                    <td class="tdInputClass"><input type="number" class="form-control" name="dayspresent[]" required/></td>
                                                    <td class="tdInputClass"><input type="number" class="form-control" name="dayspresent[]" required/></td>
                                                    <td class="tdInputClass"><input type="number" class="form-control" name="dayspresent[]" required/></td>
                                                </tr>
                                                <tr>
                                                    <th>No. of Days absent</th>
                                                    <td class="tdInputClass"><input type="number" class="form-control" name="daysabsent[]" required/></td>
                                                    <td class="tdInputClass"><input type="number" class="form-control" name="daysabsent[]" required/></td>
                                                    <td class="tdInputClass"><input type="number" class="form-control" name="daysabsent[]" required/></td>
                                                    <td class="tdInputClass"><input type="number" class="form-control" name="daysabsent[]" required/></td>
                                                    <td class="tdInputClass"><input type="number" class="form-control" name="daysabsent[]" required/></td>
                                                    <td class="tdInputClass"><input type="number" class="form-control" name="daysabsent[]" required/></td>
                                                    <td class="tdInputClass"><input type="number" class="form-control" name="daysabsent[]" required/></td>
                                                    <td class="tdInputClass"><input type="number" class="form-control" name="daysabsent[]" required/></td>
                                                    <td class="tdInputClass"><input type="number" class="form-control" name="daysabsent[]" required/></td>
                                                    <td class="tdInputClass"><input type="number" class="form-control" name="daysabsent[]" required/></td>
                                                    <td class="tdInputClass"><input type="number" class="form-control" name="daysabsent[]" required/></td>
                                                    <td class="tdInputClass"><input type="number" class="form-control" name="daysabsent[]" required/></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        '&nbsp;
                                        <div class="form-row">
                                            <div class="col-md-4">
                                                <div class="position-relative form-group ">
                                                    <div class="input-group input-group-sm">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="inputGroupPrepend">TOTAL NUMBER OF UNITS EARNED:</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="position-relative form-group ">
                                                    <div class="input-group input-group-sm">
                                                        <input type="number" name="numUnits" class="form-control " id="validationCustomUsername" aria-describedby="inputGroupPrepend" placeholder="">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col-md-4">
                                                <div class="position-relative form-group ">
                                                    <div class="input-group input-group-sm">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="inputGroupPrepend">TOTAL NUMBER OF YEARS IN SCHOOL TO DATE:</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="position-relative form-group ">
                                                    <div class="input-group input-group-sm">
                                                        <input type="number" class="form-control" id="validationCustomUsername"  name="numYears" value="" aria-describedby="inputGroupPrepend" placeholder="">
                                                    </div>
                                                </div>
                                            </div>
                                        </div> --}}
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-3">
                                        Remarks
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control form-control-sm" name="add-semesterremarks"/>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-3">
                                        SHS-School Record's In-charge
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" class="form-control form-control-sm" name="add-recordsincharge"/>
                                    </div>
                                    <div class="col-md-3">
                                        Date Checked
                                    </div>
                                    <div class="col-md-3">
                                        <input type="date" class="form-control form-control-sm" name="add-datechecked"/>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 text-right">
                                        <button type="button" class="btn  btn-warning" id="btn-submitnewform"><i class="fa fa-share"></i> Submit</button>
                                    </div>
                                </div>
                            </div>
                        </div>