

    <div class="card">
        <div class="card-header">
            @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hccsi')
            <div class="row mb-2">
               <div class="col-md-12 text-right">
                   @if(count($allschedules)>0)
                   <button type="button" class="btn btn-outline-success" id="btn-export-allpdf"> <i class="fa fa-file-pdf"></i> &nbsp;Export All Class List to&nbsp; PDF</button>
                   <button type="button" class="btn btn-default" id="btn-export-pdf"> <i class="fa fa-file-pdf"></i> &nbsp;Export Summary to&nbsp; PDF</button>
                   <button type="button" class="btn btn-default" id="btn-export-excel"> <i class="fa fa-file-excel"></i> &nbsp;Export Summary to&nbsp; Excel</button>
                   @endif
               </div>
           </div>
        </div>
           <div class="card-body pb-0">
            <div class="row mt-0 mb-2">
                <div class="col-md-4">
                    <label>Instructor</label>
                    <select class="form-control" id="select-instructorid">
                        {{-- @if(count($allschedules)>0) --}}
                            <option value="all">All</option>
                            @if(count($collegeinstructors)>0)
                                @foreach($collegeinstructors as $instructor)
                                    <option value="{{$instructor->id}}" {{$instructor->id == $selectedteacherid ? 'selected': ''}}>{{$instructor->teachername ?? $instructor->lastname.', '.$instructor->firstname}}</option>
                                @endforeach
                            @else
                            <option value="0">Unassigned Schedules</option>
                            @endif
                        {{-- @else
                        <option value="noschedules">No Schedules shown</option>
                        @endif --}}
                    </select>
               </div>
                <div class="col-md-8 align-self-end">
                    <input type="text" id="input-search" placeholder="Search..." class="form-control">
               </div>
            </div>
            @else
            <div class="card-body">
            <div class="row mb-2">
                <div class="col-md-4">
                    <input type="text" id="input-search" placeholder="Search" class="form-control">
               </div>
               <div class="col-md-8 text-right">
                   @if(count($allschedules)>0)
                   <button type="button" class="btn btn-outline-success" id="btn-export-allpdf"> <i class="fa fa-file-pdf"></i> &nbsp;Export All to&nbsp; PDF</button>
                   <button type="button" class="btn btn-default" id="btn-export-pdf"> <i class="fa fa-file-pdf"></i> &nbsp;Export list to&nbsp; PDF</button>
                   <button type="button" class="btn btn-default" id="btn-export-excel"> <i class="fa fa-file-excel"></i> &nbsp;Export list to&nbsp; Excel</button>
                   @endif
               </div>
           </div>
            @endif
           {{-- <div class="row mb-2">
                <div class="col-md-4">
                <select class="form-control form-control-sm select2" id="select-teacher">
                </select>
                </div>
           </div> --}}
           <div class="row">
               <div class="col-md-12">
                   <span class="badge badge-warning">Schedules ({{count($allschedules)}})</span>
               </div>
           </div>
           <div class="row">
               <div class="col-md-12"  style="overflow: scroll; max-height: 600px;">
                   @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'spct')
                   <table id="studentstable" class="table table-bordered tableFixHead table-hover">
                       <thead>
                           <tr>
                               <th style="width: 20px;">#</th>    
                               <th>Subject</th>     
                               <th>Description</th>      
                               <th>TimeBegin</th>    
                               <th>TimeEnd</th>    
                               <th>Days</th>    
                               <th>Enrolled</th>   
                               <th>Export</th>
                           </tr>
                       </thead>
                       <tbody class="studentscontainer"  style="font-size: 12px;">
                           @if(count($allschedules)>0)
                           @php
                           $allschedules =collect($allschedules)->groupBy('groupby');
                           @endphp
                               @foreach($allschedules as $schedule)
                                   @if(count($schedule)>0)
                                       <tr>
                                           <td></td>
                                           <td>{{collect($schedule)->first()->subjcode}}</td>
                                           <td>{{collect($schedule)->first()->subjectname}}</td>
                                           <td>{{date('h:i A',strtotime(collect($schedule)->first()->stime))}}</td>
                                           <td>{{date('h:i A',strtotime(collect($schedule)->first()->etime))}}</td>
                                           <td>{{collect($schedule)->first()->description}}</td>
                                           <td>
                                               @php
                                                   $students = collect();
                                                   foreach($schedule as $eachsched)
                                                   {
                                                       
                                                       $students = $students->merge($eachsched->students);
                                                   }
                                                   $students = $students->unique();
                                               @endphp
                                               {{count($students)}}
                                               {{-- {{$schedule->numstudents}} --}}
                                           </td>
                                           <td><button type="button" class="btn btn-sm btn-block btn-default each-btn-export" data-schedid="{{collect($schedule)->first()->subjcode}}" data-groupby="{{collect($schedule)->first()->groupby}}"><i class="fa fa-download"></i> PDF</button></td>
                                         
                                       </tr>
                                   @endif
                               @endforeach
                           @endif
                       </tbody>
                   </table>
                   @elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'dcc')
                   <table id="studentstable" class="table table-bordered tableFixHead table-hover">
                       <thead>
                           <tr>
                               <th style="width: 20px;">#</th>    
                               <th>Code</th>     
                               <th>Subject</th>      
                               <th>TimeBegin</th>    
                               <th>TimeEnd</th>    
                               <th>Days</th>    
                               <th>Enrolled</th>    
                               <th>Instructor</th>    
                           </tr>
                       </thead>
                       <tbody class="studentscontainer"  style="font-size: 12px;">
                           @if(count($allschedules)>0)
                               @foreach($allschedules as $keysched=>$schedule)
                                   {{-- @if(count($schedule)>0) --}}
                                       <tr>
                                           <td></td>
                                           <td><a href="#" class="each-btn-export" data-schedid="{{$schedule->code}}">{{$schedule->code}}</a></td>
                                           <td>{{$schedule->subjectname}}<br/>{{$schedule->subjcode}}</td>
                                           <td>
                                               {{date('h:i A',strtotime($schedule->stime))}}
                                           </td>
                                           <td>
                                               {{date('h:i A',strtotime($schedule->etime))}}
                                           </td>
                                           <td>
                                               {{$schedule->description}}
                                           </td>
                                           <td>{{$schedule->numstudents}}
                                               {{-- @php
                                                   $students = collect();
                                                   foreach($schedule as $eachsched)
                                                   {
                                                       $students = $students->merge($eachsched->students);
                                                   }
                                                   $students = $students->unique();
                                               @endphp
                                               {{count($students)}} --}}
                                           </td>
                                           <td>{{$schedule->lastname}}, {{$schedule->firstname}}</td>
                                        </tr>
                                   {{-- @endif --}}
                               @endforeach
                           @endif
                       </tbody>
                   </table>
                   @elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hccsi')
                   <table id="studentstable" class="table table-bordered tableFixHead table-hover">
                       <thead>
                           <tr>
                               <th style="width: 20px;">#</th>    
                               <th style="width: 50%;">Subject</th>     
                               <th>Course/Dept</th>    
                               <th>Section</th>    
                               <th>Schedule</th>    
                               <th></th>    
                               <th>Instructor</th>    
                           </tr>
                       </thead>
                       <tbody class="studentscontainer"  style="font-size: 12px;">
                           @if(count($allschedules)>0)
                               @foreach($allschedules as $schedule)
                                   <tr>
                                       <td></td>
                                       <td><a href="#" class="each-btn-export" data-schedid="{{$schedule->schedid}}">{{$schedule->subjectname}}<br/><span class="text-muted">{{$schedule->subjcode}}</span></a></td>
                                       <td>{{$schedule->courseabrv}}</td>
                                       <td>{{$schedule->sectionname}}</td>
                                       <td>{{$schedule->description}} - {{$schedule->starttime ?? $schedule->stime}} - {{$schedule->endtime ?? $schedule->etime}}<br/><span class="muted">Room: {{$schedule->roomname}}</span></td>
                                       <td>Units: {{$schedule->units}}<br/>Enrolled: {{$schedule->numstudents ?? count($schedule->students)}}</td>
                                       <td>
                                        <select class="form-control select2instructors" data-schedid="{{$schedule->schedid}}"  style="width: 100%;">
                                            @if(count($collegeinstructors)>0)
                                                @if($schedule->teacherid == null)
                                                <option value="0" selected>No instructor assigned</option>
                                                @else
                                                <option value="0">No instructor assigned</option>
                                                @endif

                                                @foreach($collegeinstructors as $collegeinstructor)
                                                    <option value="{{$collegeinstructor->id}}" {{$collegeinstructor->id == $schedule->teacherid ? 'selected': '0'}}>{{$collegeinstructor->lastname}}, {{$collegeinstructor->firstname}} </option>
                                                @endforeach
                                            @else
                                            <option value="0">No instructors available</option>
                                            @endif
                                        </select>
                                        {{-- {{$schedule->teachername ?? $schedule->lastname.', '.$schedule->firstname ?? ''}} --}}
                                    </td>
                                   </tr>
                               @endforeach
                           @endif
                       </tbody>
                   </table>
                   @else
                   <table id="studentstable" class="table table-bordered tableFixHead table-hover">
                       <thead>
                           <tr>
                               <th style="width: 20px;">#</th>    
                               <th style="width: 50%;">Subject</th>     
                               {{-- <th>Description</th>       --}}
                               <th>Course/Dept</th>    
                               <th>Section</th>    
                               <th>Schedule</th>    
                               {{-- <th>TimeBegin</th>    
                               <th>TimeEnd</th>     --}}
                               {{-- <th>Days</th>     --}}
                               {{-- <th>Room</th>     --}}
                               <th></th>    
                               {{-- <th>Enrolled</th>     --}}
                               <th>Instructor</th>    
                               @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sbc')
                               <th style="width: 80px;">Export</th>
                               @endif
                           </tr>
                       </thead>
                       <tbody class="studentscontainer" style="font-size: 12px;">
                        @if(count($allschedules) > 0)
                            @php
                                $schoolAbbreviation = strtolower(DB::table('schoolinfo')->first()->abbreviation);
                            @endphp
                            @foreach($allschedules as $schedule)
                                @php
                                    // Filter sched_group_detail by schedid
                                    $filtered_groups = $sched_group_detail->where('schedid', $schedule->schedid);
                                @endphp
                                @if ($filtered_groups->count() > 0)
                                    @foreach ($filtered_groups as $sched_group)
                                        <tr>
                                            <td></td>
                                            <td>
                                                <a href="#" class="each-btn-export" data-schedid="{{ $schedule->schedid }}">
                                                    {{ $schedule->subjectname }}<br/>
                                                    <span class="text-muted">{{ $schedule->subjcode }}</span>
                                                </a>
                                            </td>
                                            <td>{{ $schedule->courseabrv }}</td>
                                            <td>{{ $schedule->courseabrv }} - {{ $schedule->levelid - 16 }} {{ $sched_group->schedgroupdesc }}</td>
                                            <td>
                                                {{ $schedule->description }} - {{ $schedule->starttime ?? $schedule->stime }} - {{ $schedule->endtime ?? $schedule->etime }}<br/>
                                                <span class="muted">Room: {{ $schedule->roomname }}</span>
                                            </td>
                                            <td>
                                                Units: {{ $schedule->units }}<br/>
                                                Enrolled: {{ $schedule->numstudents ?? count($schedule->students) }}
                                            </td>
                                            <td>{{ $schedule->teachername ?? $schedule->lastname . ', ' . $schedule->firstname ?? '' }}</td>
                                            @if ($schoolAbbreviation == 'sbc')
                                                <td>
                                                    <a href="#" class="btn-block each-btn-export-gs" data-schedid="{{ $schedule->id ?? $schedule->schedid }}">
                                                        <u>Grade Sheet</u>
                                                    </a>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                @endif
                            @endforeach
                        @endif
                    </tbody>
                   </table>
                   @endif
               </div>
           </div>
    </div>
</div>
             
                <script>
                    $('#selectteacher').select2({
                        theme: 'bootstrap4'
                    })
                    $('.select2instructors').select2({
                        theme: 'bootstrap4'
                    })
                    $('#select-instructorid').select2({
                        theme: 'bootstrap4'
                    })
                    
                    
                    $('.teacher').show()
                    @if(count($teachers)>0)
                            $('#selectteacher').empty()
                            $('#selectteacher').append(
                                '<option value="0">ALL</option>'
                            )
                        @foreach($teachers as $teacher)
                            $('#selectteacher').append(
                                '<option value="{{$teacher->id}}">{{$teacher->lastname}}, {{$teacher->firstname}}</option>'
                            )
                        @endforeach
                    @endif
                    var selectedschoolyear = $('#selectedschoolyear').val();
                    var selectedsemester   = $('#selectedsemester').val();
                    function splitArrayIntoChunksOfLen(arr, len) {
                    var chunks = [], i = 0, n = arr.length;
                    while (i < n) {
                        chunks.push(arr.slice(i, i += len));
                    }
                    return chunks;
                    }
                    var noofschedules='{{count($allschedules)}}';
                    var batches = [];
                    obj = {
                                    selectedschoolyear    : selectedschoolyear,
                                    selectedsemester    : selectedsemester,
                        from    : 1,
                        to      : 50
                    }
                    batches.push(obj)
                    var start = 1;
                    var end = 50;

                    var numcount = 1;
                    var numfrom = 0;
                    var numto = 0;
                    for (let i = 0; i < noofschedules; i++) {
                        
                        if(numfrom<50)
                        {
                            numfrom+=1;
                            start+=1;
                            end+=1;
                        }else{
                                obj = {
                                    selectedschoolyear    : selectedschoolyear,
                                    selectedsemester    : selectedsemester,
                                    from    : start,
                                    to      : end
                                }
                                batches.push(obj)
                                numfrom = 0;
                                numto = 0;
                        }
                    }
                                // console.log(batches.length)
                    // schedules=schedules.replace(/&quot;/gi,"");
                    // schedules=schedules.replace(/\[/gi,"");
                    // schedules=schedules.replace(/\]/gi,"");
                        $('#btn-export-allpdf').on('click', function(){
                            var instructorid = $('#select-instructorid').val()
                            for(var i = 0; i < batches.length; i++) {
                                var acadprogid =  $('#selectedacadprog').val();
                                
                                var teacherid = $('#selectteacher').val();
                                    // window.open(playlist[i], "_blank");
                                var paramet = {
                                    instructorid  : instructorid,
                                    acadprogid  : acadprogid,
                                    selectedschoolyear  : selectedschoolyear,
                                    selectedsemester   : selectedsemester, 
                                    selectedgradelevel  : selectedgradelevel, 
                                    selectedcourse  : selectedcourse, 
                                    selectedsection    : selectedsection,
                                    teacherid    : teacherid,
                                    from    : batches[i].from,
                                    to    : batches[i].to
                                }
                                window.open("/registrar/summaries/alphaloading/filter?exporttype=pdf&export=1&"+$.param(paramet),'_blank');
                            }
                        })

                </script>