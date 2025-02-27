
                    <div class="card" style="font-size: 12px;">
                        <div class="ribbon-wrapper ribbon-sm">
                            <div class="ribbon bg-warning text-sm">NEW</div>
                        </div>
                        <button id="removeCard" class="btn btn-xs btn-outline-danger removeCard col-md-1"><i class="fa fa-times"></i></button>
                        <div class="card-header">
                            <div class="row mb-2">
                                <div class="col-2">School</div>
                                <div class="col-4"><input type="text" class="form-control form-control-sm" name="add-schoolname"/></div>
                                <div class="col-2">School ID</div>
                                <div class="col-4"><input type="text" class="form-control form-control-sm" name="add-schoolid"/></div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-2">District
                                </div>
                                <div class="col-2"><input type="text" class="form-control form-control-sm" name="add-schooldistrict"/></div>
                                <div class="col-2">Division</div>
                                <div class="col-2"><input type="text" class="form-control form-control-sm" name="add-schooldivision"/></div>
                                <div class="col-2">Region</div>
                                <div class="col-2"><input type="text" class="form-control form-control-sm" name="add-schoolregion"/></div>
                            </div>
                            @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'bct')
                            <div class="row mb-2">
                                <div class="col-2">Classified as Grade
                                </div>
                                <div class="col-2">
                                    <select id="gradelevelid" name="add-gradelevelid" class="form-control form-control-sm text-uppercase select" required readonly>
                                        {{-- <option value=""></option> --}}
                                        @foreach($gradelevels as $level)
                                            <option value="{{$level->id}}" @if($level->id == $levelid) selected @endif>{{$level->levelname}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-2">Section</div>
                                <div class="col-2"><input type="text" class="form-control form-control-sm" name="add-sectionname"/></div>
                                <div class="col-2">School Year:</div>
                                <div class="col-2"><input type="text" class="form-control form-control-sm" name="add-schoolyear" value="{{$schoolyear}}" readonly/></div>
                            </div>
                            @else
                            <div class="row mb-2">
                                <div class="col-2">Classified as Grade
                                </div>
                                <div class="col-2">
                                    <select id="gradelevelid" name="add-gradelevelid" class="form-control form-control-sm text-uppercase select" required>
                                        {{-- <option value=""></option> --}}
                                        @foreach($gradelevels as $level)
                                            <option value="{{$level->id}}" @if($level->id == $levelid) selected @endif>{{$level->levelname}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-2">Section</div>
                                <div class="col-2"><input type="text" class="form-control form-control-sm" name="add-sectionname"/></div>
                                <div class="col-2">School Year:</div>
                                <div class="col-2"><input type="text" class="form-control form-control-sm" name="add-schoolyear" value="" /></div>
                            </div>
                            @endif
                            <div class="row">
                                <div class="col-3">
                                    Name of Adviser/Teacher
                                </div>
                                <div class="col-9">
                                    <input type="text" class="form-control form-control-sm" name="add-teachername"/>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                        {{-- <input type="hidden" name="studentid" value="{{$studentdata->id}}"/> --}}
                                        <table class="table table-bordered uppercase fontSize">
                                            <thead>
                                                <tr>
                                                    <th width="30%">LEARNING AREAS</th>
                                                    <th>1</th>
                                                    <th>2</th>
                                                    <th>3</th>
                                                    <th>4</th>
                                                    <th>FINAL RATING</th>
                                                    <th>REMARKS</th>
                                                    {{-- <th>CREDITS EARNED</th> --}}
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody id="tbody">
                                                @if(count($subjects) == 0)
                                                    <tr class="tr-eachsubject">
                                                        <td class="tdInputClass"><input type="text" class="form-control input0"value="1" hidden/><input type="text" class="form-control input00"value="0" hidden/><input type="text" class="form-control input1" name="add-subject[]" required/></td>
                                                        <td class="tdInputClass"><input type="number"class="form-control input2" max="100" name="add-q1[]" required/></td>
                                                        <td class="tdInputClass"><input type="number"class="form-control input3" name="add-q2[]" required/></td>
                                                        <td class="tdInputClass"><input type="number"class="form-control input4" name="add-q3[]" required/></td>
                                                        <td class="tdInputClass"><input type="number"class="form-control input5" name="add-q4[]" required/></td>
                                                        <td class="tdInputClass"><input type="number"class="form-control input6" name="add-final[]" required/></td>
                                                        <td class="tdInputClass"><input type="text" class="form-control input7" name="add-remarks[]" required/></td>
                                                        {{-- <td class="tdInputClass"><input type="number" class="form-control" name="entry[]" required/></td> --}}
                                                        <td class="removebutton"><center><i class="fa fa-trash text-gray"></i></center></td>
                                                    </tr>
                                                @else
                                                    @foreach ($subjects as $subject)
                                                        <tr class="tr-eachsubject">
                                                            <td class="tdInputClass">
                                                                <input type="text" class="form-control input00"value="1" hidden/>
                                                                <input type="text" class="form-control input0"value="{{$subject->editable}}" hidden/>
                                                                <input type="text" class="form-control input1" name="add-subject[]" required value="{{$subject->subjdesc}}" readonly/>
                                                                <input type="text" class="form-control input000mapeh" value="{{$subject->inMAPEH}}" hidden>
                                                                <input type="text" class="form-control input000tle" value="{{$subject->inTLE}}" hidden>
                                                            </td>
                                                            <td class="tdInputClass"><input type="number"class="form-control input2" max="100" name="add-q1[]" required/></td>
                                                            <td class="tdInputClass"><input type="number"class="form-control input3" name="add-q2[]" required/></td>
                                                            <td class="tdInputClass"><input type="number"class="form-control input4" name="add-q3[]" required/></td>
                                                            <td class="tdInputClass"><input type="number"class="form-control input5" name="add-q4[]" required/></td>
                                                            <td class="tdInputClass"><input type="number"class="form-control input6" name="add-final[]" required/></td>
                                                            <td class="tdInputClass"><input type="text" class="form-control input7" name="add-remarks[]" required/></td>
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
                                                    <td></td>
                                                    <td class="tdInputClass"><input type="number" class="form-control grades" name="add-generalaverageval" required/></td>
                                                    <td></td>
                                                    {{-- <td></td> --}}
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="7" style="border-bottom: hidden; border-left: hidden;"></td>
                                                    <td id="addrow"><center><i class="fa fa-plus"></i></center></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 text-right">
                                        <button type="button" class="btn  btn-warning" id="btn-submitnewform"><i class="fa fa-share"></i> Submit</button>
                                    </div>
                                </div>
                            </div>
                        </div>