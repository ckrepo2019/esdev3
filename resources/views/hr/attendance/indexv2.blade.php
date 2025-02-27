

@extends('hr.layouts.app')
@section('content')
<!-- DataTables -->
{{-- <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<!-- Toastr -->
<link rel="stylesheet" href="{{asset('plugins/toastr/toastr.min.css')}}"> --}}

<style>
    td{
        padding: 2px !important;
    }
</style>

<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
        <h1>ATTENDANCE</h1>
          <!-- <h1>Attendance</h1> -->
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/home">Home</a></li>
            <li class="breadcrumb-item active">Attendance</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
  
  <div class="card shadow" style="border: none !important; box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;">
        <div class="card-header">
            <div class="row">
                <div class="col-md-3">
                    <label>Select Date</label>
                    <input type="date" id="select-date" class="form-control" value="{{date('Y-m-d')}}"/>
                </div>
                @if(isset(DB::table('schoolinfo')->first()->servertype))
                  @if(DB::table('schoolinfo')->first()->servertype == 1)
                    <div class="col-md-4 align-self-end">
                        <label>Tapping Station:</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <span id="text-tap-count"></span>
                    </div>
                    <div class="col-md-3 align-self-end">
                        <label>Server:</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <span id="text-server-count"></span>
                    </div>
                    <div class="col-md-2 align-self-end">
                        <button type="button" class="btn btn-primary btn-sm btn-block" id="display-sync-info">Sync</button>
                    </div>
                  @endif
                @endif

            </div>
        </div>
  </div>
  <div class="card shadow" style="border: none !important; box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;">
    {{-- <div class="card-header">
          <h5 class="card-title">STUDENTS</h5>
    </div> --}}
    <div class="card-body">
        <table id="example2" class="table table-hover" style="font-size: 12.5px;">
            <thead>
                <tr>
                    {{-- <th></th> --}}
                    <th>Employee</th>
                    {{-- <th>Status</th> --}}
                    <th style="width: 10%;">AM In</th>
                    <th style="width: 10%;">AM Out</th>
                    <th style="width: 10%;">PM In</th>
                    <th style="width: 10%;">PM Out</th>
                    {{-- <th style="width: 5%;"></th> --}}
                    <th style="width: 20%;">REMARKS</th>
                    <th style="width: 5%;"></th>
                </tr>
            </thead>
            <tbody>
                {{-- @foreach($students as $student)
                    <tr>
                        <td>{{$student->sid}}</td>
                        <td>{{$student->lastname}}, {{$student->firstname}} {{$student->middlename}}
                        </td>
                        <td>{{$student->studentstatus}}</td>
                        <td> <input type="text" class="form-control" data-id="{{$student->id}}" value="{{$student->rfid}}"/></td>
                        <td><button type="button" class="btn btn-sm btn-warning btn-reset" data-id="{{$student->id}}"><i class="fa fa-undo"></i></button></td>
                    </tr>
                @endforeach --}}
            </tbody>
        </table>
    </div>
</div>   
  
<div class="modal fade" id="modal-timelogs" aria-hidden="true" style="display: none;"data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-md">
      <div class="modal-content" id="timelogsdetails">

      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
{{-- <div id="modal-timelogs" class="modal custom-modal fade" role="dialog" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content" id="timelogsdetails">
        </div>
    </div>
</div> --}}
@endsection
@section('footerscripts')

<script>

    
    var $ = jQuery;
    $(document).ready(function(){
        var approvedleaves = [];
    getapproveleaves()
        

        

        function getapproveleaves(){
            $.ajax({
            url: '/hr/attendance/getapproveleaves',
            type:"GET",
            success:function(data) {
                approvedleaves = data;
                getemployees()
            }
            });
        }

        $(".filter").on("keyup", function() {
            var input = $(this).val().toUpperCase();
            var visibleCards = 0;
            var hiddenCards = 0;

            $(".container").append($("<div class='card-group card-group-filter'></div>"));


            $(".card-each-emp").each(function() {
                if ($(this).data("string").toUpperCase().indexOf(input) < 0) {

                $(".card-group.card-group-filter:first-of-type").append($(this));
                $(this).hide();
                hiddenCards++;

                } else {

                $(".card-group.card-group-filter:last-of-type").prepend($(this));
                $(this).show();
                visibleCards++;

                if (((visibleCards % 4) == 0)) {
                    $(".container").append($("<div class='card-group card-group-filter'></div>"));
                }
                }
            });

        });
        
        var onerror_url = @json(asset('dist/img/download.png'));
        function getemployees(){
        var sdate = $('#select-date').val();
        console.log(sdate);
        $('#example2').DataTable({
            // "paging": false,
            // "lengthChange": false,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "destroy": true,
            serverSide: true,
            processing: true,
            // ajax:'/student/preregistration/list',
            ajax:{
                url: '/hr/attendance/indexv2',
                type: 'GET',
                data: {
                    action : 'getemployees',
                    changedate : $('#select-date').val()
                }
            },
            columns: [
                { "data": null },
                { "data": null },
                { "data": null },
                { "data": null },
                { "data": null },
                { "data": null },
                { "data": null }
            ],
            columnDefs: [
                {
                    'targets': 0,
                    'orderable': false, 
                    'createdCell':  function (td, cellData, rowData, row, col) {
                        $(td)[0].innerHTML = ' <div class="row">'+
                            '<div class="col-md-3">'+
                                '<img src="/'+rowData.picurl+'" class="" alt="User Image" onerror="this.src=\''+onerror_url+'\'" width="40px"/>'+

                                '</div>'+
                                '<div class="col-md-9">'+
                                    '<div class="row">'+
                                        '<div class="col-md-12">'+rowData.lastname+', '+rowData.firstname+'</div>   ' +
                                        '<div class="col-md-12">'+ '<small class="text-primary">'+rowData.tid+'</small></div>   ' +
                                    '</div>'+
                                    
                                
                                '</div>'+
                            '</div>'
                            // $(td).addClass('align-middle')
                    }
                },
                {
                    'targets': 1,
                    'orderable': false, 
                    'createdCell':  function (td, cellData, rowData, row, col) {
                        $(td)[0].innerHTML = rowData.amin
                            $(td).addClass('align-middle')
                    }
                },
                {
                    'targets': 2,
                    'orderable': false, 
                    'createdCell':  function (td, cellData, rowData, row, col) {
                        $(td)[0].innerHTML = rowData.amout
                            $(td).addClass('align-middle')
                    }
                },
                {
                    'targets': 3,
                    'orderable': false, 
                    'createdCell':  function (td, cellData, rowData, row, col) {
                        $(td)[0].innerHTML = rowData.pmin
                            $(td).addClass('align-middle')
                    }
                },
                {
                    'targets': 4,
                    'orderable': false, 
                    'createdCell':  function (td, cellData, rowData, row, col) {
                        $(td)[0].innerHTML = rowData.pmout
                            $(td).addClass('align-middle')
                    }
                },
                {
                    'targets': 5,
                    'orderable': false, 
                    'createdCell':  function (td, cellData, rowData, row, col) {
                        var apprempleaves = ''
                        var halfday = ''
                        var leavetype = ''
                        var daycoverd = ''
                        var remarks = ''
                        apprempleaves = approvedleaves.filter(x=>x.employeeid == rowData.id && x.ldate == sdate)[0]
                        console.log(apprempleaves);
                        if (apprempleaves) {
                            leavetype = apprempleaves.leave_type
                            daycoverd = apprempleaves.daycoverd
                            remarks = apprempleaves.remarks
                        } else {
                            apprempleaves = ''
                            halfday = ''
                        }

                        

                        var logremarks = ''
                        if (rowData.remarks == null || rowData.remarks == '') {
                            logremarks = ''
                        } else {
                            logremarks = rowData.remarks
                        }
                        
                        var text = '<span class="badge badge-info" style="font-size: 12px!important;">'+leavetype+'</span><br><span>'+daycoverd+' '+remarks+'</span>'
                        $(td)[0].innerHTML = text
                        $(td).addClass('align-middle', 'text-center')
                    }
                },
                {
                    'targets': 6,
                    'orderable': false, 
                    'createdCell':  function (td, cellData, rowData, row, col) {
                        $(td)[0].innerHTML = '<button type="button" class="btn btn-sm btn-default timelogs" data-id="'+rowData.id+'">'+
                                
                                'Logs</button>'
                            $(td).addClass('align-middle')
                    }
                }
            ]
        });
        $(document).on('click', '.timelogs', function(){
            var employeeid = $(this).attr('data-id')
            $('#modal-timelogs').modal('show')
            var selecteddate =  $('#select-date').val()
            $.ajax({
                url: '/hr/attendance/gettimelogs',
                type:"GET",
                data:{
                    employeeid: employeeid,
                    selecteddate: selecteddate
                },
                success:function(data) {
                    $('#timelogsdetails').empty()
                    $('#timelogsdetails').append(data)
                }
            });
        })


    

    }

    $('#select-date').on('change', function(){    
        getapproveleaves()
    })
    $(document).on('keypress', '.employee-remarks',function (e) {
        if (e.which == 13) {
    var selecteddate =  $('#select-date').val()
            
            $.ajax({
                url: "/hr/attendance/updateremarks",
                type: "get",
                data: {
                    id: $(this).attr('data-id'),
                    selecteddate: selecteddate,
                    remarks  : $(this).val()
                },
                success: function (data) {
                    if(data == 1)
                    {
                        toastr.success('Updated successfully!')
                    }
                }
            });
            return false;    //<---- Add this line
        }
    });
    var newlogscounter = 1;
    $(document).on('click','#buttonaddnewlog', function(){
        $('#newlogscontainer').append(
            '<div class="row mb-2">'+
                '<div class="col-1">'+
                    '<button type="button" class="btn btn-sm btn-default p-0 mt-1 btn-block savenewtimelog'+newlogscounter+'">&nbsp;<i class="fa fa-check"></i>&nbsp;</button>'+
                '</div>'+
                '<div class="col-6">'+
                    '<input type="time" class="form-control form-control-sm" name="newtimelog"/>'+
                    // '<input id="newtimepick'+newlogscounter+'" class="timepick timepickerinputs form-control form-control-sm" name="newtimelog" readonly/>'+
                '</div>'+
                '<div class="col-4  p-0 text-center">'+
                
                    '<input type="checkbox" id="newlogstate'+newlogscounter+'" name="logstate" checked data-bootstrap-switch data-off-color="warning" data-on-text="IN"  data-off-text="OUT" data-on-color="success" data-size="sm">'+
                '</div>'+
                // '<div class="col-2  p-0 text-center">'+
                
                //     '<input type="checkbox" id="newlog'+newlogscounter+'" name="dayshift" checked data-bootstrap-switch data-off-color="warning" data-on-text="AM"  data-off-text="PM" data-on-color="success" data-size="sm">'+
                // '</div>'+
                '<div class="col-1">'+
                    '<button type="button" class="btn btn-sm btn-default p-0 mt-1 btn-block removenewtimelog">&nbsp;<i class="fa fa-times"></i>&nbsp;</button>'+
                '</div>'+
            '</div>'
        )
        // $('#newtimepick'+newlogscounter).timepicker({ modal: false, header: false, footer: false, mode: 'ampm', format: 'hh:MM'});
        // $('#newtimepick'+newlogscounter).on('change', function(){
        //     var newlogscounter = $(this).val().split(':');
        //     if(newlogscounter[0] == '00'){
        //         $(this).val('12:'+newlogscounter[1])
        //     }
        // })
        // var timeshift = 'am';
        // $('#newlog'+newlogscounter).bootstrapSwitch('state', true);
        // $('#newlog'+newlogscounter).on('switchChange.bootstrapSwitch',function () {
        //     var check = $(this).closest('.bootstrap-switch-on')
        //     if (check.length > 0) {
        //         timeshift = 'pm';
        //     } else {
        //         timeshift = 'am';
        //     }
        // });
        var logstate = 'in';
        $('#newlogstate'+newlogscounter).bootstrapSwitch('state', true);
        $('#newlogstate'+newlogscounter).on('switchChange.bootstrapSwitch',function () {
            var check = $(this).closest('.bootstrap-switch-on')
            if (check.length > 0) {
                logstate = 'out';
            } else {
                logstate = 'in';
            }
        });
        $(document).on('click','.savenewtimelog'+newlogscounter, function(){
            var thisrow = $(this).closest('.row');
            var thissavebutton = $(this);
            var timelog = thisrow.find('input[name="newtimelog"]').val();
            var employeeid = $('#newlogscontainer').attr('employeeid');
            var usertypeid = $('#newlogscontainer').attr('usertypeid');
            var selecteddate =  $('#select-date').val()
            if(timelog.replace(/^\s+|\s+$/g, "").length == 0){

                toastr.warning('Please set a time first!','Time Logs')

            }else{
                $.ajax({
                    url: '/hr/attendance/addtimelog',
                    type:"GET",
                    data:{
                        employeeid  : employeeid,
                        usertypeid  : usertypeid,
                        timelog     : timelog,
                        // timeshift   : timeshift,
                        tapstate    : logstate,
                        selecteddate: selecteddate
                    },
                    success:function(data) {
                        if(data == '1')
                        {
                            toastr.success('Added successfully!','Time Logs')
                            thissavebutton.attr('disabled',true)
                            thisrow.find('.clock').remove()
                            thisrow.find('.removenewtimelog').remove()
                            thisrow.find("[name='dayshift']").bootstrapSwitch('disabled',true);
                        }else{
                            toastr.danger('Something went wrong!','Time Logs')
                        }
                    }
                });
            }
        })
        newlogscounter+=1;

        $('.removenewtimelog').on('click', function(){
            $(this).closest('.row').remove()
        })
    })
    $(document).on('click', '.deletelog', function(){
        var thisrow = $(this).closest('.row');
        var logid   = $(this).attr('id');
        Swal.fire({
            title: 'Are you sure you want to delete this log?',
            type: 'warning',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Delete',
            showCancelButton: true,
            allowOutsideClick: false
        }).then((confirm) => {
            if (confirm.value) {

                $.ajax({
                    url: '/hr/attendance/deletetimelog',
                    type: 'get',
                    dataType: 'json',
                    data: {
                        id          :   logid
                    },
                    complete: function(data){
                        thisrow.remove()
                        toastr.success('Time log deleted successfully!','Time Logs')
                    }
                })
            }
        })
    })
    $(document).on('click', '.deletelogtap', function(){
        var thisrow = $(this).closest('.row');
        var logid   = $(this).attr('id');

        console.log(thisrow);
        console.log(logid);
        Swal.fire({
            title: 'Are you sure you want to delete this log?',
            type: 'warning',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Delete',
            showCancelButton: true,
            allowOutsideClick: false
        }).then((confirm) => {
            if (confirm.value) {

                $.ajax({
                    url: '/hr/attendance/deletetimelogtapping',
                    type: 'get',
                    dataType: 'json',
                    data: {
                        id          :   logid
                    },
                    complete: function(data){
                        thisrow.remove()
                        toastr.success('Time log deleted successfully!','Time Logs')
                    }
                })
            }
        })
    })
    
    $('#modal-timelogs').on('hidden.bs.modal', function (e) {
        getemployees();
    })
    
    @if(isset(DB::table('schoolinfo')->first()->servertype))
        @if(DB::table('schoolinfo')->first()->servertype == 1)
            var school_setup = @json(DB::table('schoolinfo')->first());
            var tablename = 'taphistory';

            function get_last_index(tablename){ //table-to
                $.ajax({
                        type:'GET',
                        url: '/monitoring/tablecount',
                        data:{
                            tablename: tablename
                        },
                        success:function(data) {
                            lastindex = data[0].lastindex
                            update_local_table_display(tablename,lastindex)
                        },
                })
            }
            function get_last_index_fromtap(tablename){ //table-from
                $.ajax({
                        type:'GET',
                        // url: school_setup.es_cloudurl+'/monitoring/tablecount',
                        url: 'http://tapapp.ck/monitoring/tablecount',
                        data:{
                            tablename: tablename
                        },
                        success:function(data) {
                            var lastindextap = data[0].lastindex
                            $('#text-tap-count').text(lastindextap)
                        },
                        error:function(){
                            $('#display-sync-info')[0].innerHTML = 'Connection Problem'
                            $('#display-sync-info').prop('disabled', true)
                        }
                })
            }
            get_last_index_fromtap(tablename)
            function get_last_index_fromserver(tablename){ //table-to
                $.ajax({
                        type:'GET',
                        url: '/monitoring/tablecount',
                        data:{
                            tablename: tablename
                        },
                        success:function(data) {
                            var lastindexserver = data[0].lastindex
                            if(data[0].tablecount == 0)
                            {
                                $('#text-server-count').text(0)
                            }else{
                                $('#text-server-count').text(lastindexserver)
                            }
                        },
                        error:function(){
                            $('#display-sync-info')[0].innerHTML = 'Connection Problem'
                            $('#display-sync-info').prop('disabled', true)
                        }
                })
            }
            get_last_index_fromserver(tablename)
            function update_local_table_display(tablename,lastindex){ //table-from
                $.ajax({
                        type:'GET',
                        // url: school_setup.es_cloudurl+'/monitoring/table/data',
                        url: 'http://tapapp.ck/monitoring/table/data',
                        data:{
                            tablename:tablename,
                            tableindex:lastindex
                        },
                        success:function(syncdata) {
                            if(syncdata.length > 0){
                                    process_create(tablename,0,syncdata)
                            }
                        },
                        error:function(){
                            $('#display-sync-info')[0].innerHTML = 'Connection Problem'
                        }
                })
            }
            // get_last_index(tablename)
            function process_create(tablename,process_count,createdata){
                            console.log(createdata)
                var countcreatedtap = parseInt($('#text-tap-count').text())
                var countcreatedserver = parseInt($('#text-server-count').text())
                if(createdata.length == 0){
                                $('#display-sync-info').removeClass('btn-warning')
                                $('#display-sync-info').addClass('btn-primary')
                                $('#display-sync-info').text('Synced')
                        return false;
                }else{
                                $('#display-sync-info').addClass('btn-warning')
                                $('#display-sync-info').removeClass('btn-primary')
                                $('#display-sync-info').text('Syncing...')

                }
                var b = createdata[0]
                $.ajax({
                        type:'GET',
                        url: '/synchornization/insert',
                        data:{
                            tablename: tablename,
                            data:b
                        },
                        success:function(data) {
                            $('#text-server-count').text(countcreatedserver+1)
                            process_count += 1
                            createdata = createdata.filter(x=>x.id != b.id)
                            process_create(tablename,process_count,createdata)
                            // $('#text-tap-count').text(process_count)
                            
                        },
                        error:function(){
                            process_count += 1
                            createdata = createdata.filter(x=>x.id != b.id)
                            process_create(tablename,process_count,createdata)
                            $('#display-sync-info').prop('disabled', true)
                        }
                })
            }

            $('#display-sync-info').on('click', function(){
                get_last_index(tablename)
                $(this).prop('disabled', true)
                getemployees()
            })
        @endif
    @endif
})

  </script>
@endsection

