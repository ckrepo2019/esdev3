
            @if(isset($records))
            @php
                $uniqueId = 1;   
            @endphp
            @foreach ($records as $studentRecord)
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
                                                    <input type="text" class="form-control text-uppercase" id="validationCustomUsername"  value="{{$studentRecord->gradedetails->levelname}}" aria-describedby="inputGroupPrepend" placeholder="(Grade Level)" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group ">
                                                <div class="input-group input-group-sm">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="inputGroupPrepend">School</span>
                                                    </div>
                                                    <input type="text" class="form-control text-uppercase" id="validationCustomUsername"  value="{{$studentRecord->schoolinformation->schoolname}}" aria-describedby="inputGroupPrepend" placeholder="(Municipal)" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="position-relative form-group ">
                                                <div class="input-group input-group-sm">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="inputGroupPrepend">School Year:</span>
                                                    </div>
                                                    <input type="text" class="form-control text-uppercase" id="validationCustomUsername"  value="{{$studentRecord->gradedetails->sydesc}}" aria-describedby="inputGroupPrepend" placeholder="" readonly>
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
                                    <small>Section:</small>
                                    <br>
                                    <small>Name of Adviser:</small>
                                    <table class="table table-bordered fontSize">
                                        <thead>
                                            <tr>
                                                <th rowspan="2" width="30%">SUBJECT</th>
                                                <th colspan="4">QUARTER</th>
                                                <th rowspan="2">FINAL RATING</th>
                                                <th rowspan="2">REMARKS</th>
                                            </tr>
                                            <tr>
                                                <th>1</th>
                                                <th>2</th>
                                                <th>3</th>
                                                <th>4</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $avggrade = 0;   
                                            @endphp
                                            @foreach ($studentRecord->grades as $subjects)
                                                @if($subjects->remarks == 'FAILED')
                                                    <tr style="background-color:#ffb2b9">
                                                @else
                                                    <tr>
                                                @endif
                                                        <td>{{$subjects->subjtitle}}</td>
                                                        <td><center>{{$subjects->quarter1}}</center></td>
                                                        <td><center>{{$subjects->quarter2}}</center></td>
                                                        <td><center>{{$subjects->quarter3}}</center></td>
                                                        <td><center>{{$subjects->quarter4}}</center></td>
                                                        <td><center>{{$subjects->finalrating}}</center></td>
                                                        <td><center>{{$subjects->remarks}}</center></td>
                                                        {{-- <td><center>{{$subjects->credits}}</center></td> --}}
                                                    </tr>
                                                    @php
                                                        $avggrade+=$subjects->finalrating;
                                                    @endphp
                                            @endforeach
                                            <tr>
                                                <td width="30%">&nbsp;</td>
                                                <td colspan="4"><center>GENERAL AVERAGE</center></td>
                                                <td>
                                                    @if($avggrade == 0)
                                                    @else
                                                    <center>{{round($avggrade/count($studentRecord->grades))}}</center>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($avggrade == 0)
                                                    @else
                                                    @if(($avggrade/count($studentRecord->grades))>75)
                                                    <center>PASSED</center>
                                                    @else
                                                    <center>FAILED</center>
                                                    @endif
                                                    @endif
                                                </td>
                                            </tr>
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
                                                @foreach ($studentRecord->attendance as $monthlyAttendance)
                                                    @if($monthlyAttendance->month == 'Jun')
                                                    <center>{{$monthlyAttendance->numDays}}</center>
                                                    @php
                                                        $schooldays+=$monthlyAttendance->numDays;
                                                    @endphp
                                                    @else
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($studentRecord->attendance as $monthlyAttendance)
                                                    @if($monthlyAttendance->month == 'Jul')
                                                    <center>{{$monthlyAttendance->numDays}}</center>
                                                    @php
                                                        $schooldays+=$monthlyAttendance->numDays;
                                                    @endphp
                                                    @else
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($studentRecord->attendance as $monthlyAttendance)
                                                    @if($monthlyAttendance->month == 'Aug')
                                                    <center>{{$monthlyAttendance->numDays}}</center>
                                                    @php
                                                        $schooldays+=$monthlyAttendance->numDays;
                                                    @endphp
                                                    @else
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($studentRecord->attendance as $monthlyAttendance)
                                                    @if($monthlyAttendance->month == 'Sep')
                                                    <center>{{$monthlyAttendance->numDays}}</center>
                                                    @php
                                                        $schooldays+=$monthlyAttendance->numDays;
                                                    @endphp
                                                    @else
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($studentRecord->attendance as $monthlyAttendance)
                                                    @if($monthlyAttendance->month == 'Oct')
                                                    <center>{{$monthlyAttendance->numDays}}</center>
                                                    @php
                                                        $schooldays+=$monthlyAttendance->numDays;
                                                    @endphp
                                                    @else
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($studentRecord->attendance as $monthlyAttendance)
                                                    @if($monthlyAttendance->month == 'Nov')
                                                    <center>{{$monthlyAttendance->numDays}}</center>
                                                    @php
                                                        $schooldays+=$monthlyAttendance->numDays;
                                                    @endphp
                                                    @else
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($studentRecord->attendance as $monthlyAttendance)
                                                    @if($monthlyAttendance->month == 'Dec')
                                                    <center>{{$monthlyAttendance->numDays}}</center>
                                                    @php
                                                        $schooldays+=$monthlyAttendance->numDays;
                                                    @endphp
                                                    @else
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($studentRecord->attendance as $monthlyAttendance)
                                                    @if($monthlyAttendance->month == 'Jan')
                                                    <center>{{$monthlyAttendance->numDays}}</center>
                                                    @php
                                                        $schooldays+=$monthlyAttendance->numDays;
                                                    @endphp
                                                    @else
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($studentRecord->attendance as $monthlyAttendance)
                                                    @if($monthlyAttendance->month == 'Feb')
                                                    <center>{{$monthlyAttendance->numDays}}</center>
                                                    @php
                                                        $schooldays+=$monthlyAttendance->numDays;
                                                    @endphp
                                                    @else
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($studentRecord->attendance as $monthlyAttendance)
                                                    @if($monthlyAttendance->month == 'Mar')
                                                    <center>{{$monthlyAttendance->numDays}}</center>
                                                    @php
                                                        $schooldays+=$monthlyAttendance->numDays;
                                                    @endphp
                                                    @else
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($studentRecord->attendance as $monthlyAttendance)
                                                    @if($monthlyAttendance->month == 'Apr')
                                                    <center>{{$monthlyAttendance->numDays}}</center>
                                                    @php
                                                        $schooldays+=$monthlyAttendance->numDays;
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
                                                @foreach ($studentRecord->attendance as $monthlyAttendance)
                                                    @if($monthlyAttendance->month == 'Jun')
                                                    <center>{{$monthlyAttendance->present}}</center>
                                                    @php
                                                        $dayspresent+=$monthlyAttendance->present;
                                                    @endphp
                                                    @else
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($studentRecord->attendance as $monthlyAttendance)
                                                    @if($monthlyAttendance->month == 'Jul')
                                                    <center>{{$monthlyAttendance->present}}</center>
                                                    @php
                                                        $dayspresent+=$monthlyAttendance->present;
                                                    @endphp
                                                    @else
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($studentRecord->attendance as $monthlyAttendance)
                                                    @if($monthlyAttendance->month == 'Aug')
                                                    <center>{{$monthlyAttendance->present}}</center>
                                                    @php
                                                        $dayspresent+=$monthlyAttendance->present;
                                                    @endphp
                                                    @else
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($studentRecord->attendance as $monthlyAttendance)
                                                    @if($monthlyAttendance->month == 'Sep')
                                                    <center>{{$monthlyAttendance->present}}</center>
                                                    @php
                                                        $dayspresent+=$monthlyAttendance->present;
                                                    @endphp
                                                    @else
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($studentRecord->attendance as $monthlyAttendance)
                                                    @if($monthlyAttendance->month == 'Oct')
                                                    <center>{{$monthlyAttendance->present}}</center>
                                                    @php
                                                        $dayspresent+=$monthlyAttendance->present;
                                                    @endphp
                                                    @else
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($studentRecord->attendance as $monthlyAttendance)
                                                    @if($monthlyAttendance->month == 'Nov')
                                                    <center>{{$monthlyAttendance->present}}</center>
                                                    @php
                                                        $dayspresent+=$monthlyAttendance->present;
                                                    @endphp
                                                    @else
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($studentRecord->attendance as $monthlyAttendance)
                                                    @if($monthlyAttendance->month == 'Dec')
                                                    <center>{{$monthlyAttendance->present}}</center>
                                                    @php
                                                        $dayspresent+=$monthlyAttendance->present;
                                                    @endphp
                                                    @else
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($studentRecord->attendance as $monthlyAttendance)
                                                    @if($monthlyAttendance->month == 'Jan')
                                                    <center>{{$monthlyAttendance->present}}</center>
                                                    @php
                                                        $dayspresent+=$monthlyAttendance->present;
                                                    @endphp
                                                    @else
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($studentRecord->attendance as $monthlyAttendance)
                                                    @if($monthlyAttendance->month == 'Feb')
                                                    <center>{{$monthlyAttendance->present}}</center>
                                                    @php
                                                        $dayspresent+=$monthlyAttendance->present;
                                                    @endphp
                                                    @else
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($studentRecord->attendance as $monthlyAttendance)
                                                    @if($monthlyAttendance->month == 'Mar')
                                                    <center>{{$monthlyAttendance->present}}</center>
                                                    @php
                                                        $dayspresent+=$monthlyAttendance->present;
                                                    @endphp
                                                    @else
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($studentRecord->attendance as $monthlyAttendance)
                                                    @if($monthlyAttendance->month == 'Apr')
                                                    <center>{{$monthlyAttendance->present}}</center>
                                                    @php
                                                        $dayspresent+=$monthlyAttendance->present;
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
                                                @foreach ($studentRecord->attendance as $monthlyAttendance)
                                                    @if($monthlyAttendance->month == 'Jun')
                                                    <center>{{$monthlyAttendance->absent}}</center>
                                                    @php
                                                        $daysabsent+=$monthlyAttendance->absent;
                                                    @endphp
                                                    @else
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($studentRecord->attendance as $monthlyAttendance)
                                                    @if($monthlyAttendance->month == 'Jul')
                                                    <center>{{$monthlyAttendance->absent}}</center>
                                                    @php
                                                        $daysabsent+=$monthlyAttendance->absent;
                                                    @endphp
                                                    @else
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($studentRecord->attendance as $monthlyAttendance)
                                                    @if($monthlyAttendance->month == 'Aug')
                                                    <center>{{$monthlyAttendance->absent}}</center>
                                                    @php
                                                        $daysabsent+=$monthlyAttendance->absent;
                                                    @endphp
                                                    @else
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($studentRecord->attendance as $monthlyAttendance)
                                                    @if($monthlyAttendance->month == 'Sep')
                                                    <center>{{$monthlyAttendance->absent}}</center>
                                                    @php
                                                        $daysabsent+=$monthlyAttendance->absent;
                                                    @endphp
                                                    @else
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($studentRecord->attendance as $monthlyAttendance)
                                                    @if($monthlyAttendance->month == 'Oct')
                                                    <center>{{$monthlyAttendance->absent}}</center>
                                                    @php
                                                        $daysabsent+=$monthlyAttendance->absent;
                                                    @endphp
                                                    @else
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($studentRecord->attendance as $monthlyAttendance)
                                                    @if($monthlyAttendance->month == 'Nov')
                                                    <center>{{$monthlyAttendance->absent}}</center>
                                                    @php
                                                        $daysabsent+=$monthlyAttendance->absent;
                                                    @endphp
                                                    @else
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($studentRecord->attendance as $monthlyAttendance)
                                                    @if($monthlyAttendance->month == 'Dec')
                                                    <center>{{$monthlyAttendance->absent}}</center>
                                                    @php
                                                        $daysabsent+=$monthlyAttendance->absent;
                                                    @endphp
                                                    @else
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($studentRecord->attendance as $monthlyAttendance)
                                                    @if($monthlyAttendance->month == 'Jan')
                                                    <center>{{$monthlyAttendance->absent}}</center>
                                                    @php
                                                        $daysabsent+=$monthlyAttendance->absent;
                                                    @endphp
                                                    @else
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($studentRecord->attendance as $monthlyAttendance)
                                                    @if($monthlyAttendance->month == 'Feb')
                                                    <center>{{$monthlyAttendance->absent}}</center>
                                                    @php
                                                        $daysabsent+=$monthlyAttendance->absent;
                                                    @endphp
                                                    @else
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($studentRecord->attendance as $monthlyAttendance)
                                                    @if($monthlyAttendance->month == 'Mar')
                                                    <center>{{$monthlyAttendance->absent}}</center>
                                                    @php
                                                        $daysabsent+=$monthlyAttendance->absent;
                                                    @endphp
                                                    @else
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($studentRecord->attendance as $monthlyAttendance)
                                                    @if($monthlyAttendance->month == 'Apr')
                                                    <center>{{$monthlyAttendance->absent}}</center>
                                                    @php
                                                        $daysabsent+=$monthlyAttendance->absent;
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