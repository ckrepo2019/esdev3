
            @if(isset($torrecords))
            @php
                $uniqueId = 100;   
            @endphp
            @foreach ($torrecords as $torrecord)
                <div id="accordion">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h4 class="card-title col-md-12" >
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne{{$uniqueId}}">
                                    <div class="form-row">
                                        <div class="col-md-3">
                                            <div class="position-relative form-group ">
                                                <div class="input-group input-group-sm">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="inputGroupPrepend">CLASSIFIED AS:</span>
                                                    </div>
                                                    <input type="text" class="form-control text-uppercase" id="validationCustomUsername"  value="{{$torrecord->levelname->levelname}}" aria-describedby="inputGroupPrepend" placeholder="(Grade Level)" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group ">
                                                <div class="input-group input-group-sm">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="inputGroupPrepend">School</span>
                                                    </div>
                                                    <input type="text" class="form-control text-uppercase" id="validationCustomUsername"  value="{{$torrecord->schoolinfo->schoolname}}" aria-describedby="inputGroupPrepend" placeholder="(Municipal)" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="position-relative form-group ">
                                                <div class="input-group input-group-sm">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="inputGroupPrepend">School Year:</span>
                                                    </div>
                                                    <input type="text" class="form-control text-uppercase" id="validationCustomUsername"  value="{{$torrecord->schoolyear->schoolyear}}" aria-describedby="inputGroupPrepend" placeholder="" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </h4>
                        </div>
                        <div id="collapseOne{{$uniqueId}}" class="panel-collapse collapse in">
                            <div class="card-body">
                                <div class="container">
                                    <form action="/elem/editform10/edit" method="GET" class="m-0">
                                        <input type="hidden" name="student_id" value="{{$studentdata->id}}"/>
                                        <input type="hidden" name="academicprogram" value="{{$academicprogram}}"/>
                                        <input type="hidden" name="recordid" value="{{$torrecord->schoolyear->id}}"/>
                                        <button type="submit" class="btn btn-warning btn-xs mb-2 editButton"><i class="fa fa-edit"></i>&nbsp;Edit&nbsp;&nbsp;&nbsp;&nbsp;</button>
                                    </form>
                                    <button type="btn" class="btn btn-danger btn-xs mb-2 deleteButton" data-toggle="modal" data-target="#deleteButton{{$torrecord->schoolyear->id}}"><i class="fa fa-trash"></i>&nbsp;Delete</button>
                                    <br>
                                <!-- Modal -->
                                    <div class="modal fade" id="deleteButton{{$torrecord->schoolyear->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalCenterTitle">
                                                        <span class="mr-5">Student:</span>
                                                        <span class="">
                                                            <strong>{{$studentdata->lastname}}, {{$studentdata->firstname}} {{$studentdata->middlename}} {{$studentdata->suffix}}.</strong>
                                                        </span>
                                                    </h5>
                                                </div>
                                                <form name="deleteForm" action="/elem/deleteform10" method="GET" class="m-0">
                                                    <div class="modal-body">
                                                        <input type="hidden" name="studentid" value="{{$studentdata->id}}"/>
                                                        <input type="hidden" name="headerid" value="{{$torrecord->schoolyear->id}}"/>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-danger deleteConfirm">Confirm</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <small>Section:</small>
                                    <br>
                                    <small>Name of Adviser:</small>
                                    <table class="table table-bordered fontSize">
                                        <thead>
                                            <tr>
                                                <th rowspan="2" width="30%">SUBJECT</th>
                                                <th colspan="4">QUARTER</th>
                                                <th rowspan="2">FINAL RATING</th>
                                                <th rowspan="2">ACTION TAKEN</th>
                                                <th rowspan="2">CREDITS EARNED</th>
                                            </tr>
                                            <tr>
                                                <th>1</th>
                                                <th>2</th>
                                                <th>3</th>
                                                <th>4</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(count($torrecord->grades)>0)
                                                @foreach ($torrecord->grades as $subjects)
                                                    @if($subjects->finalrating < 75)
                                                        <tr style="background-color:#ffb2b9">
                                                    @else
                                                        <tr>
                                                    @endif
                                                            <td>{{$subjects->subj_desc}}</td>
                                                            <td><center>{{$subjects->quarter1}}</center></td>
                                                            <td><center>{{$subjects->quarter2}}</center></td>
                                                            <td><center>{{$subjects->quarter3}}</center></td>
                                                            <td><center>{{$subjects->quarter4}}</center></td>
                                                            <td><center>{{$subjects->finalrating}}</center></td>
                                                            <td><center>{{$subjects->action}}</center></td>
                                                            <td><center>{{$subjects->credits}}</center></td>
                                                        </tr>                                                    
                                                @endforeach
                                            @endif
                                            @if(count($torrecord->generalaverage)>0)
                                                <tr>
                                                    <td width="30%">&nbsp;</td>
                                                    <td colspan="4"><center>GENERAL AVERAGE</center></td>
                                                    <td>
                                                        {{$torrecord->generalaverage[0]->genave}}
                                                    </td>
                                                    <td>
                                                    </td>
                                                    <td>
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                    <br>
                                    @php
                                        $schooldays = 0;
                                        $dayspresent = 0;
                                        $daysabsent = 0;
                                    @endphp
                                    <table class="table table-bordered fontSize">
                                        <tr>
                                            <td style="width:20%;"></td>
                                            <th>Jun</th>
                                            <th>Jul</th>
                                            <th>Aug</th>
                                            <th>Sept</th>
                                            <th>Oct	</th>
                                            <th>Nov</th>
                                            <th>Dec</th>
                                            <th>Jan</th>
                                            <th>Feb</th>
                                            <th>Mar</th>
                                            <th>Apr</th>
                                            <th>Total</th>
                                        </tr>
                                        <tr>
                                            <td>No. of School Days</td>
                                            <td>
                                                @foreach ($torrecord->attendance as $monthlyAttendance)
                                                    @if($monthlyAttendance->month == 'Jun')
                                                    <center>{{$monthlyAttendance->numofschooldays}}</center>
                                                    @php
                                                        $schooldays+=$monthlyAttendance->numofschooldays;
                                                    @endphp
                                                    @else
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($torrecord->attendance as $monthlyAttendance)
                                                    @if($monthlyAttendance->month == 'Jul')
                                                    <center>{{$monthlyAttendance->numofschooldays}}</center>
                                                    @php
                                                        $schooldays+=$monthlyAttendance->numofschooldays;
                                                    @endphp
                                                    @else
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($torrecord->attendance as $monthlyAttendance)
                                                    @if($monthlyAttendance->month == 'Aug')
                                                    <center>{{$monthlyAttendance->numofschooldays}}</center>
                                                    @php
                                                        $schooldays+=$monthlyAttendance->numofschooldays;
                                                    @endphp
                                                    @else
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($torrecord->attendance as $monthlyAttendance)
                                                    @if($monthlyAttendance->month == 'Sep')
                                                    <center>{{$monthlyAttendance->numofschooldays}}</center>
                                                    @php
                                                        $schooldays+=$monthlyAttendance->numofschooldays;
                                                    @endphp
                                                    @else
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($torrecord->attendance as $monthlyAttendance)
                                                    @if($monthlyAttendance->month == 'Oct')
                                                    <center>{{$monthlyAttendance->numofschooldays}}</center>
                                                    @php
                                                        $schooldays+=$monthlyAttendance->numofschooldays;
                                                    @endphp
                                                    @else
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($torrecord->attendance as $monthlyAttendance)
                                                    @if($monthlyAttendance->month == 'Nov')
                                                    <center>{{$monthlyAttendance->numofschooldays}}</center>
                                                    @php
                                                        $schooldays+=$monthlyAttendance->numofschooldays;
                                                    @endphp
                                                    @else
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($torrecord->attendance as $monthlyAttendance)
                                                    @if($monthlyAttendance->month == 'Dec')
                                                    <center>{{$monthlyAttendance->numofschooldays}}</center>
                                                    @php
                                                        $schooldays+=$monthlyAttendance->numofschooldays;
                                                    @endphp
                                                    @else
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($torrecord->attendance as $monthlyAttendance)
                                                    @if($monthlyAttendance->month == 'Jan')
                                                    <center>{{$monthlyAttendance->numofschooldays}}</center>
                                                    @php
                                                        $schooldays+=$monthlyAttendance->numofschooldays;
                                                    @endphp
                                                    @else
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($torrecord->attendance as $monthlyAttendance)
                                                    @if($monthlyAttendance->month == 'Feb')
                                                    <center>{{$monthlyAttendance->numofschooldays}}</center>
                                                    @php
                                                        $schooldays+=$monthlyAttendance->numofschooldays;
                                                    @endphp
                                                    @else
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($torrecord->attendance as $monthlyAttendance)
                                                    @if($monthlyAttendance->month == 'Mar')
                                                    <center>{{$monthlyAttendance->numofschooldays}}</center>
                                                    @php
                                                        $schooldays+=$monthlyAttendance->numofschooldays;
                                                    @endphp
                                                    @else
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($torrecord->attendance as $monthlyAttendance)
                                                    @if($monthlyAttendance->month == 'Apr')
                                                    <center>{{$monthlyAttendance->numofschooldays}}</center>
                                                    @php
                                                        $schooldays+=$monthlyAttendance->numofschooldays;
                                                    @endphp
                                                    @else
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                <center>{{$schooldays}}</center>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>No. of Days present</td>
                                            <td>
                                                @foreach ($torrecord->attendance as $monthlyAttendance)
                                                    @if($monthlyAttendance->month == 'Jun')
                                                    <center>{{$monthlyAttendance->numofdayspresent}}</center>
                                                    @php
                                                        $dayspresent+=$monthlyAttendance->numofdayspresent;
                                                    @endphp
                                                    @else
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($torrecord->attendance as $monthlyAttendance)
                                                    @if($monthlyAttendance->month == 'Jul')
                                                    <center>{{$monthlyAttendance->numofdayspresent}}</center>
                                                    @php
                                                        $dayspresent+=$monthlyAttendance->numofdayspresent;
                                                    @endphp
                                                    @else
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($torrecord->attendance as $monthlyAttendance)
                                                    @if($monthlyAttendance->month == 'Aug')
                                                    <center>{{$monthlyAttendance->numofdayspresent}}</center>
                                                    @php
                                                        $dayspresent+=$monthlyAttendance->numofdayspresent;
                                                    @endphp
                                                    @else
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($torrecord->attendance as $monthlyAttendance)
                                                    @if($monthlyAttendance->month == 'Sep')
                                                    <center>{{$monthlyAttendance->numofdayspresent}}</center>
                                                    @php
                                                        $dayspresent+=$monthlyAttendance->numofdayspresent;
                                                    @endphp
                                                    @else
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($torrecord->attendance as $monthlyAttendance)
                                                    @if($monthlyAttendance->month == 'Oct')
                                                    <center>{{$monthlyAttendance->numofdayspresent}}</center>
                                                    @php
                                                        $dayspresent+=$monthlyAttendance->numofdayspresent;
                                                    @endphp
                                                    @else
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($torrecord->attendance as $monthlyAttendance)
                                                    @if($monthlyAttendance->month == 'Nov')
                                                    <center>{{$monthlyAttendance->numofdayspresent}}</center>
                                                    @php
                                                        $dayspresent+=$monthlyAttendance->numofdayspresent;
                                                    @endphp
                                                    @else
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($torrecord->attendance as $monthlyAttendance)
                                                    @if($monthlyAttendance->month == 'Dec')
                                                    <center>{{$monthlyAttendance->numofdayspresent}}</center>
                                                    @php
                                                        $dayspresent+=$monthlyAttendance->numofdayspresent;
                                                    @endphp
                                                    @else
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($torrecord->attendance as $monthlyAttendance)
                                                    @if($monthlyAttendance->month == 'Jan')
                                                    <center>{{$monthlyAttendance->numofdayspresent}}</center>
                                                    @php
                                                        $dayspresent+=$monthlyAttendance->numofdayspresent;
                                                    @endphp
                                                    @else
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($torrecord->attendance as $monthlyAttendance)
                                                    @if($monthlyAttendance->month == 'Feb')
                                                    <center>{{$monthlyAttendance->numofdayspresent}}</center>
                                                    @php
                                                        $dayspresent+=$monthlyAttendance->numofdayspresent;
                                                    @endphp
                                                    @else
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($torrecord->attendance as $monthlyAttendance)
                                                    @if($monthlyAttendance->month == 'Mar')
                                                    <center>{{$monthlyAttendance->numofdayspresent}}</center>
                                                    @php
                                                        $dayspresent+=$monthlyAttendance->numofdayspresent;
                                                    @endphp
                                                    @else
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($torrecord->attendance as $monthlyAttendance)
                                                    @if($monthlyAttendance->month == 'Apr')
                                                    <center>{{$monthlyAttendance->numofdayspresent}}</center>
                                                    @php
                                                        $dayspresent+=$monthlyAttendance->numofdayspresent;
                                                    @endphp
                                                    @else
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                <center>{{$dayspresent}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>No. of Days absent</td>
                                            <td>
                                                @foreach ($torrecord->attendance as $monthlyAttendance)
                                                    @if($monthlyAttendance->month == 'Jun')
                                                    <center>{{$monthlyAttendance->numofdaysabsent}}</center>
                                                    @php
                                                        $daysabsent+=$monthlyAttendance->numofdaysabsent;
                                                    @endphp
                                                    @else
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($torrecord->attendance as $monthlyAttendance)
                                                    @if($monthlyAttendance->month == 'Jul')
                                                    <center>{{$monthlyAttendance->numofdaysabsent}}</center>
                                                    @php
                                                        $daysabsent+=$monthlyAttendance->numofdaysabsent;
                                                    @endphp
                                                    @else
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($torrecord->attendance as $monthlyAttendance)
                                                    @if($monthlyAttendance->month == 'Aug')
                                                    <center>{{$monthlyAttendance->numofdaysabsent}}</center>
                                                    @php
                                                        $daysabsent+=$monthlyAttendance->numofdaysabsent;
                                                    @endphp
                                                    @else
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($torrecord->attendance as $monthlyAttendance)
                                                    @if($monthlyAttendance->month == 'Sep')
                                                    <center>{{$monthlyAttendance->numofdaysabsent}}</center>
                                                    @php
                                                        $daysabsent+=$monthlyAttendance->numofdaysabsent;
                                                    @endphp
                                                    @else
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($torrecord->attendance as $monthlyAttendance)
                                                    @if($monthlyAttendance->month == 'Oct')
                                                    <center>{{$monthlyAttendance->numofdaysabsent}}</center>
                                                    @php
                                                        $daysabsent+=$monthlyAttendance->numofdaysabsent;
                                                    @endphp
                                                    @else
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($torrecord->attendance as $monthlyAttendance)
                                                    @if($monthlyAttendance->month == 'Nov')
                                                    <center>{{$monthlyAttendance->numofdaysabsent}}</center>
                                                    @php
                                                        $daysabsent+=$monthlyAttendance->numofdaysabsent;
                                                    @endphp
                                                    @else
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($torrecord->attendance as $monthlyAttendance)
                                                    @if($monthlyAttendance->month == 'Dec')
                                                    <center>{{$monthlyAttendance->numofdaysabsent}}</center>
                                                    @php
                                                        $daysabsent+=$monthlyAttendance->numofdaysabsent;
                                                    @endphp
                                                    @else
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($torrecord->attendance as $monthlyAttendance)
                                                    @if($monthlyAttendance->month == 'Jan')
                                                    <center>{{$monthlyAttendance->numofdaysabsent}}</center>
                                                    @php
                                                        $daysabsent+=$monthlyAttendance->numofdaysabsent;
                                                    @endphp
                                                    @else
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($torrecord->attendance as $monthlyAttendance)
                                                    @if($monthlyAttendance->month == 'Feb')
                                                    <center>{{$monthlyAttendance->numofdaysabsent}}</center>
                                                    @php
                                                        $daysabsent+=$monthlyAttendance->numofdaysabsent;
                                                    @endphp
                                                    @else
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($torrecord->attendance as $monthlyAttendance)
                                                    @if($monthlyAttendance->month == 'Mar')
                                                    <center>{{$monthlyAttendance->numofdaysabsent}}</center>
                                                    @php
                                                        $daysabsent+=$monthlyAttendance->numofdaysabsent;
                                                    @endphp
                                                    @else
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($torrecord->attendance as $monthlyAttendance)
                                                    @if($monthlyAttendance->month == 'Apr')
                                                    <center>{{$monthlyAttendance->numofdaysabsent}}</center>
                                                    @php
                                                        $daysabsent+=$monthlyAttendance->numofdaysabsent;
                                                    @endphp
                                                    @else
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                <center>{{$daysabsent}}</center>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @php
                    $uniqueId+=1;   
                @endphp
            @endforeach
        @endif