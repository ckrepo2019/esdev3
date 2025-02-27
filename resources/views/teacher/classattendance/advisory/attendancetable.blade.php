<style>
    
    input[type=radio]                   { visibility: hidden; position: relative;width: 20px; height: 20px; }

    input[type=radio] .present:before    { content: "";visibility: visible;position: absolute;border: 1px solid black;border-radius: 50%;top: 0;right: 0;bottom: 0;left: 0; }

    input[type=radio] .late:before       { content: "";visibility: visible;position: absolute;border: 1px solid black;border-radius: 50%;top: 0;right: 0;bottom: 0;left: 0;padding: 0; }

    input[type=radio] .halfday:before    { content: "";visibility: visible;position: absolute;border: 1px solid black;border-radius: 50%;top: 0;right: 0;bottom: 0;left: 0; }

    input[type=radio] .absent:before     { content: "";visibility: visible;position: absolute;border: 1px solid black;border-radius: 50%;top: 0;right: 0;bottom: 0;left: 0; }

    input[type=radio] .present:checked:before    { font-family: "Font Awesome 5 Free";content: "\f00c";color: green;font-size: 20px;border: 1px solid white; }

    input[type=radio] .late:checked:before       { background-color: gold; }

    input[type=radio] .halfday:checked:before    { background-color: #6c757d; }

    input[type=radio] .absent:checked:before     { font-family: "Font Awesome 5 Pro", "Font Awesome 5 Free";content: "\f00d";color: red;font-size: 20px;border: 1px solid white; }

    [data-id=shake]
    {
        animation: blinker 0.5s linear infinite;
        /* animation-iteration-count: infinite; */

    }

    [data-id=shake]:hover, [data-id=shake]:focus {
        animation-play-state: paused;
    }

    @keyframes blinker {
        50% {
            opacity: 0.5;
        }
    }
    .badge-pink{
        background-color: #e83e8c!important;
        color: white;
    }
</style>
    <div class="col-md-12">
        <div class="main-card mb-3 card ">
        <!-- gian -->
            <div class="card-header">
        <!-- end gian -->
                <h3 class="card-title">
                    <input type="date" value="{{$selecteddate}}" class="col-md-12 form-control" id="selecteddate"/>
                </h3>
                <div class="card-tools">
                    <a href="/teacher/classattendance/full/index?sectionid={{$section->id}}&levelid={{$gradelevel->id}}" type="button" class="btn btn-default"><i class="fa fa-eye"></i> Overview
                    </a>
                    @if($checkdate>0)
                        <button type="button" class="btn btn-warning" id="btn-submit"><i class="fa fa-edit"></i> Update</button>
                    @else
                        <button type="button" class="btn btn-success" id="btn-submit" data-id="shake"><i class="fa fa-share"></i> Save</button>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <button class="btn btn-sm btn-default">Male ({{$countmale}})</button>
                        <button class="btn btn-sm btn-default">Female ({{$countfemale}})</button>
                    </div>
                    <div class="col-6 text-right">
                        <span class="badge badge-success">Present ({{$countpresent}})</span>
                        <span class="badge badge-warning">Late ({{$countlate}})</span>
                        <span class="badge badge-danger">Half Day ({{$counthalfday}})</span>
                        <span class="badge badge-secondary">Absent ({{$countabsent}})</span>
                    </div>
                    <div class="col-12">
                        <table class="table table-bordered" style="table-layout: fixed;">
                            <thead class="text-center">
                                <tr>
                                    <th style="width: 30%;" rowspan="2">Student Name</th>
                                    <th style="width: 10%;" rowspan="2">Present</th>
                                    <th style="width: 10%;" rowspan="2">Late</th>
                                    <th style="" colspan="2">Half Day</th>
                                    <th style="width: 10%;" rowspan="2">Absent</th>
                                    <th style="width: 10%;" rowspan="2">Remarks</th>
                                </tr>
                                <tr>
                                    <th>AM</th>
                                    <th>PM</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($students)>0)
                                    <tr class="bg-info">
                                        <td colspan="7">MALE ({{$countmale}})</td>
                                    </tr>
                                    @foreach ($students as $student)
                                        @if(strtolower($student->gender) == 'male')
                                            <tr style="vertical-align: middle;">
                                                <td><span class="badge badge-info" style="font-size: 9px;">({{$student->description}})</span> {{$student->lastname}}, {{$student->firstname}} {{$student->middlename}}</td>
                                                <td style="vertical-align: middle;">
                                                    <div class="form-group clearfix text-center">
                                                        <div class="icheck-success d-inline" style="vertical-align: middle;">
                                                            <input type="radio" id="radioPrimary{{$student->id}}-1" class="present" value="present-{{$student->id}}" name="attendance-{{$student->id}}" @if($student->present == 1) checked="" @endif>
                                                            <label for="radioPrimary{{$student->id}}-1"  data-toggle="tooltip" data-placement="bottom" title="Present">
                                                                
                                                            </label>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td style="vertical-align: middle;">
                                                    <div class="form-group clearfix text-center">
                                                        <div class="icheck-warning d-inline" style="vertical-align: middle;">
                                                            <input type="radio" id="radioPrimary{{$student->id}}-2" class="late" value="tardy-{{$student->id}}" name="attendance-{{$student->id}}"  @if($student->tardy == 1) checked="" @endif>
                                                            <label for="radioPrimary{{$student->id}}-2" data-toggle="tooltip" data-placement="bottom" title="Late">
                                                                
                                                            </label>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td style="vertical-align: middle;">
                                                    <div class="form-group clearfix text-center">
                                                        <div class="icheck-danger d-inline" style="vertical-align: middle;">
                                                            <input type="radio" id="radioPrimary{{$student->id}}-3am" class="halfday" value="halfdayam-{{$student->id}}" name="attendance-{{$student->id}}"  @if($student->cc == 1 && $student->halfdayshift == 'am') checked="" @endif>
                                                            <label for="radioPrimary{{$student->id}}-3am"  data-toggle="tooltip" data-placement="bottom" title="Half Day / AM">
                                                                
                                                            </label>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td style="vertical-align: middle;">
                                                    <div class="form-group clearfix text-center">
                                                        <div class="icheck-danger d-inline" style="vertical-align: middle;">
                                                            <input type="radio" id="radioPrimary{{$student->id}}-3pm" class="halfday" value="halfdaypm-{{$student->id}}" name="attendance-{{$student->id}}"  @if($student->cc == 1 && $student->halfdayshift == 'pm') checked="" @endif>
                                                            <label for="radioPrimary{{$student->id}}-3pm"  data-toggle="tooltip" data-placement="bottom" title="Half Day / PM">
                                                                
                                                            </label>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td style="vertical-align: middle;">
                                                    <div class="form-group clearfix text-center">
                                                        <div class="icheck-secondary d-inline" style="vertical-align: middle;">
                                                            <input type="radio" id="radioPrimary{{$student->id}}-4" class="absent" value="absent-{{$student->id}}" name="attendance-{{$student->id}}"  @if($student->absent == 1) checked="" @endif>
                                                            <label for="radioPrimary{{$student->id}}-4" data-toggle="tooltip" data-placement="bottom" title="Absent">
                                                                
                                                            </label>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td style="vertical-align: middle;">
                                                    <button type="button" class="btn btn-default btn-block btn-remarks" data-id="{{$student->id}}" @if($checkdate == 0) disabled @endif><i class="fa fa-comment-alt fa-lg text-warning"></i></button>
                                                    {{-- <textarea class="form-control" name="attendance{{$student->id}}-remark">
                                                        {{$student->attendance[0]->remarks}}
                                                    </textarea> --}}
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                    <tr class="bg-pink">
                                        <td colspan="7">FEMALE ({{$countfemale}})</td>
                                    </tr>
                                    @foreach ($students as $student)
                                        @if(strtolower($student->gender) == 'female')
                                            <tr>
                                                <td><span class="badge badge-pink" style="font-size: 9px;">({{$student->description}})</span> {{$student->lastname}}, {{$student->firstname}} {{$student->middlename}}</td>
                                                <td>
                                                    <div class="form-group clearfix text-center">
                                                        <div class="icheck-success d-inline">
                                                            <input type="radio" id="radioPrimary{{$student->id}}-1" class="present" value="present-{{$student->id}}" name="attendance-{{$student->id}}" @if($student->present == 1) checked="" @endif>
                                                            <label for="radioPrimary{{$student->id}}-1" data-toggle="tooltip" data-placement="bottom" title="Present">
                                                                
                                                            </label>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group clearfix text-center">
                                                        <div class="icheck-warning d-inline">
                                                            <input type="radio" id="radioPrimary{{$student->id}}-2" class="late" value="tardy-{{$student->id}}" name="attendance-{{$student->id}}"  @if($student->tardy == 1) checked="" @endif>
                                                            <label for="radioPrimary{{$student->id}}-2" data-toggle="tooltip" data-placement="bottom" title="Late">
                                                                
                                                            </label>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group clearfix text-center">
                                                        <div class="icheck-danger d-inline">
                                                            <input type="radio" id="radioPrimary{{$student->id}}-3am" class="halfday" value="halfdayam-{{$student->id}}" name="attendance-{{$student->id}}"  @if($student->cc == 1 && $student->halfdayshift == 'am') checked="" @endif>
                                                            <label for="radioPrimary{{$student->id}}-3am"  data-toggle="tooltip" data-placement="bottom" title="Half Day / AM">
                                                                
                                                            </label>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group clearfix text-center">
                                                        <div class="icheck-danger d-inline">
                                                            <input type="radio" id="radioPrimary{{$student->id}}-3pm" class="halfday" value="halfdaypm-{{$student->id}}" name="attendance-{{$student->id}}"  @if($student->cc == 1 && $student->halfdayshift == 'pm') checked="" @endif>
                                                            <label for="radioPrimary{{$student->id}}-3pm"  data-toggle="tooltip" data-placement="bottom" title="Half Day / PM">
                                                                
                                                            </label>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group clearfix text-center">
                                                        <div class="icheck-secondary d-inline">
                                                            <input type="radio" id="radioPrimary{{$student->id}}-4" class="absent" value="absent-{{$student->id}}" name="attendance-{{$student->id}}"  @if($student->absent == 1) checked="" @endif>
                                                            <label for="radioPrimary{{$student->id}}-4" data-toggle="tooltip" data-placement="bottom" title="Absent">
                                                                
                                                            </label>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-default btn-block btn-remarks" data-id="{{$student->id}}" @if($checkdate == 0) disabled @endif><i class="fa fa-comment-alt fa-lg text-info"></i></button>
                                                    {{-- <textarea class="form-control" name="attendance{{$student->id}}-remark">
                                                        {{$student->attendance[0]->remarks}}
                                                    </textarea> --}}
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                    
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
        
<div class="modal fade" id="modal-remarks" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Remarks</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
            <label id="label-studentname"></label>
            <textarea class="form-control" id="textarea-remarks">

            </textarea>
        </div>
        <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal" id="btn-close-modal">Close</button>
            <button type="button" class="btn btn-primary" id="btn-submit-remarks">Save changes</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
    <script>
        $('[data-toggle="tooltip"]').tooltip()
        var $ = jQuery;
        // $(document).ready(function(){
            $('#btn-submit').unbind().click(function(){
                var attendance = [];
                $('input[type="radio"]:checked').each(function(){
                    attendance.push($(this).val())
                })
                var selecteddate = '{{$selecteddate}}';
                var selectedschoolyear = $('#selectedschoolyear').val();
                var selectedsemester = $('#selectedsemester').val();
                var selectedsection = '{{$section->id}}';
                var selectedgradelevel = '{{$gradelevel->id}}';

                Swal.fire({
                    title: 'Saving changes...',
                    onBeforeOpen: () => {
                        Swal.showLoading()
                    },
                    allowOutsideClick: false
                })
                $.ajax({
                    url: '/classattendance/submit',
                    type:"GET",
                    dataType:"json",
                    data:{
                        attendance:attendance,
                        selecteddate:selecteddate,
                        selectedschoolyear:selectedschoolyear,
                        selectedsemester:selectedsemester,
                        selectedsection:selectedsection,
                        selectedgradelevel:selectedgradelevel
                    },complete:function(data)
                    {
                        $(".swal2-container").remove();
                        $('body').removeClass('swal2-shown')
                        $('body').removeClass('swal2-height-auto')
                        $('#selecteddate').trigger('change')
                    }
                })
            })
            $('.btn-remarks').on('click', function(){
                $('#modal-remarks').modal('show')
                $('#btn-submit-remarks').attr('data-id', $(this).attr('data-id'))
                var studentid = $(this).attr('data-id');
                var selecteddate = '{{$selecteddate}}';
                var selectedschoolyear = $('#selectedschoolyear').val();
                var selectedsemester = $('#selectedsemester').val();
                var selectedsection = '{{$section->id}}';
                var selectedgradelevel = '{{$gradelevel->id}}';

                $.ajax({
                    url: '/classattendance/getremarks',
                    type:"GET",
                    dataType:"json",
                    data:{
                        studentid:studentid,
                        selecteddate:selecteddate,
                        selectedschoolyear:selectedschoolyear,
                        selectedsemester:selectedsemester,
                        selectedsection:selectedsection,
                        selectedgradelevel:selectedgradelevel
                    },success:function(data)
                    {
                        if(data)
                        {
                            $('#label-studentname').text(data.firstname+' '+data.middlename+' '+data.lastname)
                            $('#textarea-remarks').val(data.remarks)
                        }
                    }
                })
            })
            $('#btn-submit-remarks').on('click', function(){
                var studentid = $(this).attr('data-id');
                var selecteddate = '{{$selecteddate}}';
                var selectedschoolyear = $('#selectedschoolyear').val();
                var selectedsemester = $('#selectedsemester').val();
                var selectedsection = '{{$section->id}}';
                var selectedgradelevel = '{{$gradelevel->id}}';
                var remarks = $('#textarea-remarks').val()
                $.ajax({
                    url: '/classattendance/updateremarks',
                    type:"GET",
                    dataType:"json",
                    data:{
                        studentid:studentid,
                        selecteddate:selecteddate,
                        selectedschoolyear:selectedschoolyear,
                        selectedsemester:selectedsemester,
                        selectedsection:selectedsection,
                        selectedgradelevel:selectedgradelevel,
                        remarks:remarks
                    },complete:function(data)
                    {
                        $('#btn-close-modal').click();
                    }
                })
            })
            $('#selecteddate').unbind().change(function(){
                var selectedschoolyear = $('#selectedschoolyear').val();
                var selectedsemester = $('#selectedsemester').val();
                Swal.fire({
                    title: 'Fetching data...',
                    onBeforeOpen: () => {
                        Swal.showLoading()
                    },
                    allowOutsideClick: false
                })
                $.ajax({
                    url: '/classattendance/showtable',
                    type: 'GET',
                    data: {
                        levelid  : '{{$gradelevel->id}}',
                        sectionid: '{{$section->id}}',
                        selectedschoolyear  : selectedschoolyear,
                        selectedsemester  : selectedsemester,
                        selecteddate  : $(this).val()
                    },
                    success:function(data){
                        $('#attendancetablecontainer').empty();
                        $('#attendancetablecontainer').append(data)
                        
                        $(".swal2-container").remove();
                        $('body').removeClass('swal2-shown')
                        $('body').removeClass('swal2-height-auto')
                    }
                })
            })
    </script>