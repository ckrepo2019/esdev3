
<style>
    
 
    table td, table th {
        padding: 0px !important;
    }
    thead{
        background-color: #eee !important;
    }
    
    .table                      {font-size:90%; text-transform: uppercase; }
    
.thetable thead th:first-child  { 
    position: sticky; 
    left: 0; 
    background-color: #fff; 
    outline: 2px solid #dee2e6;
    outline-offset: -1px;
}

.thetable tbody td:first-child  {  
    position: sticky; 
    left: 0; 
    background-color: #fff; 
    background-color: #fff; 
    outline: 2px solid #dee2e6;
    outline-offset: -1px;
}

.thetable thead th:first-child  { 
        position: sticky; left: 0; 
        background-color: #fff; 
        outline: 2px solid #dee2e6;
        outline-offset: -1px;
}

.dataTables_filter, .dataTables_info { display: none; }
</style>
{{-- <div class="card shadow" style="border: none !important; box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;">
    <div class="card-header d-flex p-0">
        <h3 class="card-title p-3"></h3>
            <ul class="nav nav-pills ml-auto p-2">
        
                <li class="nav-item"><a class="nav-link active" href="#tab_con" data-toggle="tab">MAPEH</a></li>
                @foreach($subjects as $subject)
                <li class="nav-item"><a class="nav-link" href="#tab_{{$subject->subjid}}" data-toggle="tab">{{$subject->subjdesc}}</a></li>
                @endforeach
            </ul>
    </div>
</div> --}}
<div class="tab-content">
    <div class="row">
        <div class="col-md-8 align-self-center pl-2">
            <em>Note: Click "Save Changes" when checking specific cells before leaving the page.</em>
        </div>
        <div class="col-md-4 text-right">
            <button type="button" class="btn btn-sm btn-outline-success m-2 " id="btn-save-con"><i class="fa fa-share"></i> Save Changes</button>
        </div>
    </div>
    {{-- <div class="card-body p-0" >
        <div class="tab-content">
            <div class="tab-pane active" id="tab_con">
            A wonderful serenity has taken possession of my entire soul,
            like these sweet mornings of spring which I enjoy with my whole heart.
            I am alone, and feel the charm of existence in this spot,
            which was created for the bliss of souls like mine. I am so happy,
            my dear friend, so absorbed in the exquisite sense of mere tranquil existence,
            that I neglect my talents. I should be incapable of drawing a single stroke
            at the present moment; and yet I feel that I never was a greater artist than now.
            </div> --}}
            
            {{-- @foreach($subjects as $key=>$subject) --}}
                        <table class="table-head-fixed text-nowrap table-bordered thetable" style="font-size: 12px;">
                            <thead>
                                <tr>
                                    <th style="width: 100px !important; vertical-align: middle; text-align: center;">Student Name</th>
                                    @if(count($dates)>0)
                                        @foreach($dates as $date)
                                        <th class="text-center eachdate" data-date="{{$date->date}}" style="width: 100px !important;">
                                            {{$date->datestr}}<br/>{{$date->day}}<br/>
                                            {{-- <button type="button" class="btn btn-sm btn-default btn-hide">&nbsp;<i class="fa fa-eye"></i>&nbsp;</button> --}}<br/>
                                                <select class="form-control form-control-sm select-column-att-con" data-date="{{$date->date}}">
                                                    <option value="delete"></option>
                                                    <option value="delete">Reset</option>
                                                    <option value="present">Present</option>
                                                    <option value="absent">Absent</option>
                                                    <option value="late">Late</option>
                                                </select>
                                        </th>
                                        @endforeach
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($students)>0)
                                <tr class="bg-info">
                                    <td class="bg-info">MALE</td>
                                    @if(count($dates)>0)
                                        @foreach($dates as $date)
                                            <td></td>
                                        @endforeach
                                    @endif
                                </tr>
                                    @foreach($students as $student)
                                    @if(strtolower($student->gender) == 'male')
                                    <tr class="eachstud" data-id="{{$student->id}}">
                                            <td>
                                            <div class="row">
                                                <div class="col-md-4 align-self-start">
                                                    <select class="form-control form-control-sm select-row-att-con" data-id="{{$student->id}}">
                                                        <option value="delete"></option>
                                                        <option value="delete">Reset</option>
                                                        <option value="present">Present</option>
                                                        <option value="absent">Absent</option>
                                                        <option value="late">Late</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-8">
                                                    <span class="badge badge-info">{{$student->sid}}</span>
                                                    <span class="badge badge-info">{{$student->description}}</span><br/>
                                                    <strong>{{$student->lastname}}</strong>, {{ucwords(strtolower($student->firstname))}}
                                                </div>
                                            </div>
                                            </td>
                                                @foreach($dates as $eachdate)
                                                    @if(collect($student->attendance)->where('subjid', $subjectid)->where('tdate', $eachdate->date)->count() == 0)
                                                        <td data-class="attstatus" class="eachstuddate" data-studid="{{$student->id}}"
                                                            data-tdate="{{$eachdate->date}}"
                                                            clicked="0" >&nbsp;</td>
                                                    @else
                                                        @php
                                                            $eachdatestatus = collect($student->attendance)->where('subjid', $subjectid)->where('tdate', $eachdate->date)->first();
                                                        @endphp
                                                    <td data-class="attstatus" class="@if(strtolower($eachdatestatus->status) == 'present') bg-success @elseif(strtolower($eachdatestatus->status) == 'absent') bg-danger @elseif(strtolower($eachdatestatus->status) == 'late') bg-warning @elseif(strtolower($eachdatestatus->status) == 'cc') bg-secondary @endif  eachstuddate" data-studid="{{$student->id}}"
                                                        data-status="{{$eachdatestatus->status}}"
                                                        data-tdate="{{$eachdatestatus->tdate}}"
                                                        data-newstatus="{{$eachdatestatus->status}}"
                                                        clicked="0"  >{{strtoupper($eachdatestatus->status)}} </td>
                                                    @endif
                                                @endforeach
                                            {{-- @if(count($student->attendance)>0)
                                                @foreach(collect($student->attendance)->where('subject_id', $subjectid) as $att)
                                                <td data-class="attstatus" class="@if(strtolower($att->status) == 'present') bg-success @elseif(strtolower($att->status) == 'absent') bg-danger @elseif(strtolower($att->status) == 'late') bg-warning @elseif(strtolower($att->status) == 'cc') bg-secondary @endif eachstuddate"
                                                    data-studid="{{$student->id}}"
                                                    data-status="{{$att->status}}"
                                                    data-tdate="{{$att->tdate}}"
                                                    data-newstatus="{{$att->status}}"
                                                    clicked="0" >
                                                {{strtoupper($att->status)}} 
                                                </td>
                                                @endforeach
                                            @endif --}}
                                        </tr>
                                    @endif
                                    @endforeach
                                    <tr class="bg-pink">
                                        <td class="bg-pink">FEMALE</td>
                                        @if(count($dates)>0)
                                            @foreach($dates as $date)
                                            <td></td>
                                            @endforeach
                                        @endif
                                    </tr>
                                    @foreach($students as $student)
                                        @if(strtolower($student->gender) == 'female')
                                        <tr class="eachstud" data-id="{{$student->id}}">
                                            <td>
                                                <div class="row">
                                                    <div class="col-md-4 align-self-start">
                                                        <select class="form-control form-control-sm select-row-att-con" data-id="{{$student->id}}">
                                                            <option value="reset"></option>
                                                            <option value="reset">Reset</option>
                                                            <option value="present">Present</option>
                                                            <option value="absent">Absent</option>
                                                            <option value="late">Late</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <span class="badge badge-info">{{$student->sid}}</span>
                                                        <span class="badge badge-info">{{$student->description}}</span><br/>
                                                        <strong>{{$student->lastname}}</strong>, {{ucwords(strtolower($student->firstname))}}
                                                    </div>
                                                </div>
                                            </td>
                                            @foreach($dates as $eachdate)
                                                @if(collect($student->attendance)->where('subjid', $subjectid)->where('tdate', $eachdate->date)->count() == 0)
                                                    <td data-class="attstatus" class="eachstuddate" data-studid="{{$student->id}}"
                                                        data-tdate="{{$eachdate->date}}"
                                                        clicked="0" >&nbsp;</td>
                                                @else
                                                    @php
                                                        $eachdatestatus = collect($student->attendance)->where('subjid', $subjectid)->where('tdate', $eachdate->date)->first();
                                                    @endphp
                                                <td data-class="attstatus" class="@if(strtolower($eachdatestatus->status) == 'present') bg-success @elseif(strtolower($eachdatestatus->status) == 'absent') bg-danger @elseif(strtolower($eachdatestatus->status) == 'late') bg-warning @elseif(strtolower($eachdatestatus->status) == 'cc') bg-secondary @endif  eachstuddate" data-studid="{{$student->id}}"
                                                    data-status="{{$eachdatestatus->status}}"
                                                    data-tdate="{{$eachdatestatus->tdate}}"
                                                    data-newstatus="{{$eachdatestatus->status}}"
                                                    clicked="0"  >{{strtoupper($eachdatestatus->status)}} </td>
                                                @endif
                                            @endforeach
                                            </tr>
                                        @endif
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    {{-- </div>
                </div>
            </section> --}}
            {{-- @endforeach --}}
        
        {{-- </div>
    </div>
</div> --}}
</div>
<script>
    $('.thetable').DataTable({
  "columnDefs": [
    { "width": "300px", "targets": 0 }
  ],
fixedColumns: true,
    scrollY:        500,
    scrollX:        true,
    scrollCollapse: true,
    paging:         false,
    fixedColumns:   true,
    "aaSorting": []
    })   
    $('th').on('click', function(){
        return false;
    })
    $('th').unbind('click.DT');

    
    $(document).ready(function(){
        
        $('.select-column-att-con').on('change', function(){
            columnid = $(this).closest('th').index();
            var selecteddate = $(this).attr('data-date');
            var valstatus = $(this).val();
            var studids = []
            $('.eachstud').each(function(){
                studids.push($(this).attr('data-id'));
            })
            Swal.fire({
                title: 'Are you sure you want to change the attendance status?',
                // text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Mark',
                allowOutsideClick: false
            }).then((result) => {
                if (result.value) {
                    Swal.fire({
                        title: 'Saving changes...',
                        allowOutsideClick: false,
                        closeOnClickOutside: false,
                        onBeforeOpen: () => {
                            Swal.showLoading()
                        }
                    }) 
                    $.ajax({
                        url: '/beadleAttendance/updatecolumn',
                        type:"GET",
                        dataType:"json",
                        data:{
                            action    :  valstatus,
                            tdate    :  selecteddate,
                            studids    : JSON.stringify(studids),
                            levelid  : $('#selectedgradelevel').val(),
                            sectionid: $('#selectedsection').val(),
                            subjectid: '{{$subjectid}}'
                        },
                        // headers: { 'X-CSRF-TOKEN': token },,
                        complete: function(){
                            toastr.success('Updated successfully!')
                            $("tr.eachstud").each(function() {
                                $(this).children("td:eq("+columnid+")").removeAttr('class');
                                if(valstatus == 'present')
                                {
                                    $(this).children("td:eq("+columnid+")").addClass('bg-success');
                                    $(this).children("td:eq("+columnid+")").text('PRESENT');
                                    $(this).children("td:eq("+columnid+")").attr('data-newstatus','PRESENT');
                                    $(this).children("td:eq("+columnid+")").attr('data-status','PRESENT');
                                }else if(valstatus == 'late')
                                {
                                    $(this).children("td:eq("+columnid+")").addClass('bg-warning');
                                    $(this).children("td:eq("+columnid+")").text('LATE');
                                    $(this).children("td:eq("+columnid+")").attr('data-newstatus','LATE');
                                    $(this).children("td:eq("+columnid+")").attr('data-status','LATE');
                                }else if(valstatus == 'absent')
                                {
                                    $(this).children("td:eq("+columnid+")").addClass('bg-danger');
                                    $(this).children("td:eq("+columnid+")").text('ABSENT');
                                    $(this).children("td:eq("+columnid+")").attr('data-newstatus','ABSENT');
                                    $(this).children("td:eq("+columnid+")").attr('data-status','ABSENT');
                                }else{
                                    
                                    $(this).children("td:eq("+columnid+")").removeAttr('style');
                                    $(this).children("td:eq("+columnid+")").text('');
                                    $(this).children("td:eq("+columnid+")").attr('data-newstatus','none');
                                }
                                $(this).children("td:eq("+columnid+")").attr('clicked','0');
                            });
                            $(".swal2-container").remove();
                            $('body').removeClass('swal2-shown')
                            $('body').removeClass('swal2-height-auto')
                        }
                    })
                }
            })
        })
        $('.select-row-att-con').on('change', function(){
            var studid = $(this).attr('data-id');
            var thistr = $(this).closest('tr');
            var valstatus = $(this).val();
            var dates = []
            $('.eachdate').each(function(){
                dates.push($(this).attr('data-date'));
            })
            Swal.fire({
                title: 'Are you sure you want to change the attendance status?',
                // text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Mark',
                allowOutsideClick: false
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: '/beadleAttendance/updaterow',
                        type:"GET",
                        dataType:"json",
                        data:{
                            action    :  valstatus,
                            studid   :  studid,
                            dates    : JSON.stringify(dates),
                            levelid  : $('#selectedgradelevel').val(),
                            sectionid: $('#selectedsection').val(),
                            subjectid: '{{$subjectid}}'
                        },
                        // headers: { 'X-CSRF-TOKEN': token },,
                        complete: function(){
                            toastr.success('Marked successfully!')
                            thistr.find('.eachstuddate').each(function(){
                                    $(this).removeAttr('class');
                                    $(this).addClass('eachstuddate');
                                if(valstatus == 'present')
                                {
                                    $(this).addClass('bg-success');
                                    $(this).removeAttr('style');
                                    $(this).text('PRESENT');
                                    $(this).attr('data-newstatus','PRESENT');
                                    $(this).attr('data-status','PRESENT');
                                }else if(valstatus == 'late')
                                {
                                    $(this).addClass('bg-warning');
                                    $(this).removeAttr('style');
                                    $(this).text('LATE');
                                    $(this).attr('data-newstatus','LATE');
                                    $(this).attr('data-status','LATE');
                                }else if(valstatus == 'absent')
                                {
                                    $(this).addClass('bg-danger');
                                    $(this).removeAttr('style');
                                    $(this).text('ABSENT');
                                    $(this).attr('data-newstatus','ABSENT');
                                    $(this).attr('data-status','ABSENT');
                                }else{
                                        
                                    $(this).addClass('eachstuddate');
                                    $(this).removeAttr('style');
                                    $(this).text('');
                                    $(this).attr('data-newstatus','none');
                                }
                                $(this).attr('clicked','0');
                            })
                            $('#btn-reload').click()
                        }
                    })
                }
            })
        })
        $('#btn-save-con').on('click',  function() {
            Swal.fire({
                title: 'Saving changes...',
                onBeforeOpen: () => {
                    Swal.showLoading()
                },
                allowOutsideClick: false
            })   
            var selectedyear = $('#selectedyear').val();
            var selectedmonth = $('#selectedmonth').val();
            var selectedschoolyear = $('#selectedschoolyear').val();
            var selectedsemester = $('#selectedsemester').val();
            var selectedgradelevel = $('#selectedgradelevel').val();
            var selectedsection = $('#selectedsection').val();
            var selectedsubject = '{{$subjectid}}'
            var datavalues = [];

            $('td[clicked="1"]').each(function(){
                
                obj = {
                    studid      : $(this).attr('data-studid'),
                    status      : $(this).attr('data-status'),
                    tdate       : $(this).attr('data-tdate'),
                    newstatus       : $(this).attr('data-newstatus')
                };
                datavalues.push(obj);
            })
                   
            $.ajax({
                url: '/beadleAttendanceUpdate',
                type: 'GET',
                data: {
                    version: '3',
                    selectedschoolyear      : selectedschoolyear,
                    selectedsemester      : selectedsemester,
                    selectedyear    : selectedyear,
                    selectedmonth   : selectedmonth,
                    selectedgradelevel   : selectedgradelevel,
                    selectedsection   : selectedsection,
                    selectedsubject   : selectedsubject,
                    datavalues   : datavalues
                },
                complete:function(data){
                    
                    toastr.success('Updated successfully!')
                    $(".swal2-container").remove();
                    $('body').removeClass('swal2-shown')
                    $('body').removeClass('swal2-height-auto')
                    $('#btn-reload').click()
                }
            })
        })
    })
</script>
{{-- <div class="d-flex p-0">
<h3 class="card-title p-3">Tabs</h3>
    <ul class="nav nav-pills ml-auto p-2">

        <li class="nav-item"><a class="nav-link active" href="#tab_con" data-toggle="tabCon">MAPEH</a></li>
        @foreach($subjects as $subject)
        <li class="nav-item"><a class="nav-link" href="#tab_{{$subject->subjid}}" data-toggle="tab">{{$subject->subjdesc}}</a></li>
        @endforeach
    </ul>
</div>
    <div class="tab-content">
        <div class="tab-pane active" id="tab_con">
        A wonderful serenity has taken possession of my entire soul,
        like these sweet mornings of spring which I enjoy with my whole heart.
        I am alone, and feel the charm of existence in this spot,
        which was created for the bliss of souls like mine. I am so happy,
        my dear friend, so absorbed in the exquisite sense of mere tranquil existence,
        that I neglect my talents. I should be incapable of drawing a single stroke
        at the present moment; and yet I feel that I never was a greater artist than now.
        </div>
        
        @foreach($subjects as $subject)
        <div class="tab-pane" id="tab_{{$subject->subjid}}">
        The European languages are members of the same family. Their separate existence is a myth.
        For science, music, sport, etc, Europe uses the same vocabulary. The languages only differ
        in their grammar, their pronunciation and their most common words. Everyone realizes why a
        new common language would be desirable: one could refuse to pay expensive translators. To
        achieve this, it would be necessary to have uniform grammar, pronunciation and more common
        words. If several languages coalesce, the grammar of the resulting language is more simple
        and regular than that of the individual languages.
        </div>
        @endforeach
    
    </div> --}}
        {{-- <table class="table table-head-fixed text-nowrap table-bordered" id="thetable">
            <thead>
                <tr>
                    <th>Student Name</th>
                    @if(count($dates)>0)
                        @foreach($dates as $date)
                        <th class="text-center eachdate" data-date="{{$date->date}}" colspan="{{count($subjects)}}">
                            {{$date->datestr}}<br/>{{$date->day}}<br/>
                        </th>
                        @endforeach
                    @endif
                </tr>
                <tr>
                    <th style="height: 60px;">&nbsp;</th>
                    @if(count($dates)>0)
                        @foreach($dates as $date)
                            @foreach($subjects as $subject)
                            <th style="width:300px !important;">
                                {{$subject->subjectcode[0]}}
                            </th>
                            @endforeach
                        @endforeach
                    @endif
                </tr>
            </thead>
            <tbody>
                @if(count($students)>0)
                  <tr class="bg-info">
                      <td class="bg-info">MALE</td>
                      @if(count($dates)>0)
                          @foreach($dates as $date)
                              @foreach($subjects as $subject)
                              <th>
                                <select class="form-control form-control-sm">
                                    <option value="0"></option>
                                    <option value="present">P</option>
                                    <option value="late">L</option>
                                    <option value="absent">A</option>
                                </select>
                              </th>
                              @endforeach
                          @endforeach
                      @endif
                  </tr>
                    @foreach($students as $student)
                      @if(strtolower($student->gender) == 'male')
                      <tr class="eachstud" data-id="{{$student->id}}">
                            <td>
                                <div class="row mb-1">
                                    <div class="col-md-12">
                                        <span class="badge badge-info">{{$student->sid}}</span>
                                        <span class="badge badge-info">{{$student->description}}</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        {{$student->lastname}}, {{$student->firstname}} {{$student->middlename}} {{$student->suffix}}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="button" class="btn btn-sm btn-default btn-row-null m-0 pt-1 pb-1 pr-1 pl-1" data-id="{{$student->id}}"><i class="fa fa-trash"></i></button>
                                        <button type="button" class="btn btn-sm btn-default btn-row-present m-0 pt-0 pb-0 pr-1 pl-1" data-id="{{$student->id}}">P</button>
                                        <button type="button" class="btn btn-sm btn-default btn-row-late m-0 pt-0 pb-0 pr-1 pl-1" data-id="{{$student->id}}">L</button>
                                        <button type="button" class="btn btn-sm btn-default btn-row-absent m-0 pt-0 pb-0 pr-1 pl-1" data-id="{{$student->id}}">A</button>
                                    </div>
                                </div>
                            </td>
                              @if(count($student->attendance)>0)
                                  @foreach($student->attendance as $att)
                                  <td data-class="attstatus" class="@if(strtolower($att->status) == 'present') bg-success @elseif(strtolower($att->status) == 'absent') bg-danger @elseif(strtolower($att->status) == 'late') bg-warning @elseif(strtolower($att->status) == 'cc') bg-secondary @endif eachstuddate"
                                      data-studid="{{$student->id}}"
                                      data-status="{{$att->status}}"
                                      data-tdate="{{$att->tdate}}"
                                      data-newstatus="{{$att->status}}"
                                      clicked="0" >
                                  {{$att->status}} 
                                  </td>
                                  @endforeach
                              @endif
                          </tr>
                      @endif
                    @endforeach
                    <tr class="bg-pink">
                        <td class="bg-pink">FEMALE</td>
                        <td colspan="{{count($dates)*count($subjects)}}"></td>
                    </tr>
                      @foreach($students as $student)
                        @if(strtolower($student->gender) == 'female')
                        <tr class="eachstud" data-id="{{$student->id}}">
                              <td>
                                  <div class="row mb-1">
                                      <div class="col-md-12">
                                          <span class="badge  bg-pink">{{$student->sid}}</span>
                                          <span class="badge  bg-pink">{{$student->description}}</span>
                                      </div>
                                  </div>
                                  <div class="row">
                                      <div class="col-md-12">
                                          {{$student->lastname}}, {{$student->firstname}} {{$student->middlename}} {{$student->suffix}}
                                      </div>
                                  </div>
                                  <div class="row">
                                      <div class="col-md-12">
                                          <button type="button" class="btn btn-sm btn-default btn-row-null m-0 pt-1 pb-1 pr-1 pl-1" data-id="{{$student->id}}"><i class="fa fa-trash"></i></button>
                                          <button type="button" class="btn btn-sm btn-default btn-row-present m-0 pt-0 pb-0 pr-1 pl-1" data-id="{{$student->id}}">P</button>
                                          <button type="button" class="btn btn-sm btn-default btn-row-late m-0 pt-0 pb-0 pr-1 pl-1" data-id="{{$student->id}}">L</button>
                                          <button type="button" class="btn btn-sm btn-default btn-row-absent m-0 pt-0 pb-0 pr-1 pl-1" data-id="{{$student->id}}">A</button>
                                      </div>
                                  </div>
                              </td>
                                @if(count($student->attendance)>0)
                                    @foreach($student->attendance as $att)
                                    <td data-class="attstatus"
                                    class="@if(strtolower($att->status) == 'present') bg-success @elseif(strtolower($att->status) == 'absent') bg-danger @elseif(strtolower($att->status) == 'late') bg-warning @elseif(strtolower($att->status) == 'cc') bg-secondary @endif eachstuddate"
                                    data-studid="{{$student->id}}"
                                    data-status="{{$att->status}}"
                                    data-tdate="{{$att->tdate}}"
                                    data-newstatus="{{$att->status}}"
                                    clicked="0">
                                    {{$att->status}} 
                                    </td>
                                    @endforeach
                                @endif
                            </tr>
                        @endif
                      @endforeach
                @endif
            </tbody>
          </table> --}}