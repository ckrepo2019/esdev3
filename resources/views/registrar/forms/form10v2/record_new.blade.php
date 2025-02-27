
                    <div class="card" style="font-size: 12px;">
                        <div class="ribbon-wrapper ribbon-sm">
                            <div class="ribbon bg-warning text-sm">NEW</div>
                        </div>
                        <button id="removeCard" class="btn btn-xs btn-outline-danger removeCard col-md-1"><i class="fa fa-times"></i></button>
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="m-0" style="font-size: 13.5px; width: 100%;">
                                        <tr>
                                            <td style="width: 15%;">School</td>
                                            <td colspan="5" style="border-bottom: 1px solid #ddd;"><input type="text" class="form-control form-control-sm p-0 input-norecord input-header input-schoolname"/> </td>
                                        </tr>
                                        <tr>
                                            <td>School ID</td>
                                            <td colspan="5" style="border-bottom: 1px solid #ddd;"><input type="text" class="form-control form-control-sm p-0 input-norecord input-header input-schoolid"/></td>
                                        </tr>
                                        <tr>
                                            <td>District</td>
                                            <td style="width: 20%; border-bottom: 1px solid #ddd;"><input type="text" class="form-control form-control-sm p-0 input-norecord input-header input-district"/></td>
                                            <td class="text-right">Division&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                            <td style="border-bottom: 1px solid #ddd;"><input type="text" class="form-control form-control-sm p-0 input-norecord input-header input-division"/></td>
                                            <td class="text-right">Region&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                            <td style="border-bottom: 1px solid #ddd;"><input type="text" class="form-control form-control-sm p-0 input-norecord input-header  input-region"/></td>
                                        </tr>
                                        <tr>
                                            <td>Grade Level</td>
                                            <td style="border-bottom: 1px solid #ddd;" colspan="3"><input type="text" class="form-control form-control-sm p-0 input-norecord input-header input-levelid" data-id="{{$levelinfo->id}}" value="{{$levelinfo->levelname}}" readonly/></td>
                                            <td class="text-right">School Year&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                            <td style="border-bottom: 1px solid #ddd;"><input type="text" class="form-control form-control-sm p-0 input-norecord input-header input-sydesc text-center" value="{{$schoolyear}}" readonly/></td>
                                        </tr>
                                    </table>
                                    <table class="m-0" style="font-size: 13.5px; width: 100%;">
                                        <tr>
                                            <td style="width: 15%;">Section</td>
                                            <td style="border-bottom: 1px solid #ddd;"><input type="text" class="form-control form-control-sm p-0 input-norecord input-header input-sectionname"/></td>
                                            <td class="text-right">Adviser&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                            <td style="border-bottom: 1px solid #ddd;"><input type="text" class="form-control form-control-sm p-0 input-norecord input-header input-adviser"/></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mt-0">
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-sm btn-outline-success btn-submit-header" data-recordid="0"><i class="fa fa-share"></i> Save changes</button>
                                </div>
                            </div>
                            <div class="row mt-2" hidden>
                                <div class="col-md-12">
                                    <table class="table" style="font-size: 12px; table-layout: fixed;">
                                        <thead class="text-center">
                                            <tr>
                                                <th>Indent</th>
                                                <th style="width: 30%;">Subjects</th>
                                                <th>1st</th>
                                                <th>2nd</th>
                                                <th>3rd</th>
                                                <th>4th</th>
                                                <th style="width: 8%;">Final</th>
                                                <th style="width: 13%;">Remarks</th>
                                                <th style="width: 8%;">Credit Earned</th>
                                                <th style="width: 15%;">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="gradescontainer">  
                                        @if(count($subjects)>0)
                                            @foreach($subjects as $subject)
                                                <tr>
                                                    <td><input type="checkbox" class="form-control" id="{{$subject->id}}" style="width: 20px;height: 20px;" @if($subject->inMAPEH == 1) checked @endif></td>
                                                    <td><input type="hidden" class="form-control form-control-sm text-center p-0 input-subjid" value="{{$subject->id}}"/><input type="text" class="form-control form-control-sm p-0 input-norecord input-subjdesc" value="{{$subject->subjdesc}}"/></td>
                                                    <td class="text-center"><input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-q1"/></td>
                                                    <td class="text-center"><input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-q2"/></td>
                                                    <td class="text-center"><input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-q3"/></td>
                                                    <td class="text-center"><input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-q4"/></td>
                                                    <td class="text-center"><input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-finalgrade"/></td>
                                                    <td class="text-center"><input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-remarks"/></td>
                                                    <td class="text-center"><input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-credits" /></td>
                                                    <th>
                                                        {{-- <button type="button" class="btn btn-sm btn-outline-warning btn-updatesubject text-sm" data-id="{{$grade->id}}">Update</button> <button type="button" class="btn btn-sm btn-outline-danger btn-deletesubject text-sm" data-id="{{$grade->id}}"><i class="fa fa-trash-alt"></i></button> --}}
                                                    </th>
                                                </tr>
                                            @endforeach 
                                        @endif
                                        </tbody>
                                        <tr>
                                            <th colspan="10" class="text-right"><button type="button" class="btn btn-sm btn-outline-success btn-addrow mt-2"><i class="fa fa-plus"></i> Add Subject</button></th>
                                        </tr>
                                        <tfoot hidden>
                                            <tr>
                                                <th colspan="10" class="text-right">
                                                    <button type="button" class="btn btn-sm btn-success mt-2"><i class="fa fa-share"></i> Save changes</button>
                                                </th>
                                            </tr>    
                                        </tfoot>
                                        <script>
                                            if($('.gradescontainer').find('.eachsubject').length >0)
                                            {
                                                $('.gradescontainer').closest('.table').find('tfoot').removeAttr('hidden')
                                            }
                                        </script>
                                    </table>
                                </div>
                            </div>
                            {{-- <div class="row">
                                <div class="col-md-12">
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
                                </div> --}}
                            </div>
                        </div>