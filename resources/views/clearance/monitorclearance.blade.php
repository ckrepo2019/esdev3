@php
    $check_refid = DB::table('usertype')->where('id',Session::get('currentPortal'))->select('refid')->first();

    if(Session::get('currentPortal') == 3){
        $extend = 'registrar.layouts.app';
    }else if(auth()->user()->type == 17){
        $extend = 'superadmin.layouts.app2';
    }else if(Session::get('currentPortal') == 2){
        $extend = 'principalsportal.layouts.app2';
    }else{
        if(isset($check_refid->refid)){
                if($check_refid->refid == 27){
                    $extend = 'academiccoor.layouts.app2';
                }
        }else{
                $extend = 'general.defaultportal.layouts.app';
        }
    }

@endphp

@extends($extend)

@section('pagespecificscripts')
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
<link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
<style>
    .is-valid + .select2-container .select2-selection {
        border-color: #4CAF50;
    }

    .is-invalid + .select2-container .select2-selection {
        border-color: #F44336;
    }

    /* For screens smaller than 768px (e.g., mobile devices) */
    @media (max-width: 767.98px) {
    /* Hide the text inside the button */
    .btn_count {
        display: flex;
        justify-content: center;
    }

    #table_students_filter {
        display: flex;
        justify-content: flex-end;
    }
    
    }
</style>
@endsection

@section('content')
{{-- Page --}}
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Clearance Monitoring</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/home">Home</a></li>
                    <li class="breadcrumb-item active">Clearance Monitoring</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content pt-0">
{{-- Modal --}}
<div class="container">
    <div class="modal fade view_clearance" id="view_clearance" tabindex="-1" role="dialog" aria-labelledby="viewClearance" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-8">
                                <lable id="student_name" role="display_name"></label>
                            </div>
                            <div class="col-4">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12" id="cleared_status"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-striped table-sm table-bordered" id="clearance_table" width="100%" style="font-size:.85rem !important" >
                                    <thead class="thead-primary">
                                        <tr>
                                            <th class="text-center" id="outof" width="100%"></th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div> --}}
            </div>
        </div>    
    </div>
</div>
{{-- Modal --}}

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="info-box shadow-lg">
                <div class="info-box-content">
                    <div class="row">
                        <i class="fa fa-filter"></i><label>Filter</label>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3">
                            <label>School Year</label>
                            <select class="form-control select2" id="filter_syid">
                                @foreach(collect(DB::table('sy')->get())->sortByDesc('sydesc')->values() as $eachsy)
                                    <option value="{{$eachsy->id}}" @if($eachsy->isactive == 1) selected @endif>{{$eachsy->sydesc}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3" style="vertical-align: bottom;">
                            <label>Grade Level</label>
                            <select class="form-control select2" id="filter_levelid"></select>
                        </div>
                        <div class="col-md-3" style="vertical-align: bottom;">
                            <label>Section</label>
                            <select class="form-control select2" id="filter_sectionid"></select>
                        </div>
                        <div class="col-md-3">
                            <label>Academic Term</label>
                            <select class="form-control select2" id="filter_acadterm"></select>
                        </div>        
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <label>Clearance Status</label>
                            <select class="form-control select2" id="filter_clearedstatus">
                                <option value="" selected></option>
                                <option value="0">Cleared</option>
                                <option value="1">Uncleared</option>
                                <option value="2">Pending</option>
                            </select>
                        </div>  
                        {{-- <div class="col-md-9 text-right align-self-end mt-2">
                            <button type="button" class="btn btn-primary btn-sm" id="btn-generate" disabled><i class="fa fa-sync"></i> Generate</button>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row" id="clearance_view" hidden>
        <div class="col-md-12">
            <div class="card shadow" style="">
                <div class="card-body" id="main_table">
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <table class="table table-striped table-sm table-bordered table-hover p-0" id="table_students" width="100%" style="font-size:.9rem !important">
                                <thead>
                                    <tr>
                                        <th width="40%">Name</th>
                                        <th width="40%">Grade Level</th>
                                        <th width="20%">Cleared Status</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</section>
@endsection
@section('footerscripts')
<script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{asset('plugins/datatables/jquery.dataTables.js') }}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
<script src="{{asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>
<script src="{{asset('plugins/moment/moment.min.js') }}"></script>
<script>
    var selectedid = null
    var status = null
    var action = null
    var name = null
    var countcleared = null
    var countuncleared = null
    var countpending = null
    var total_clearance = null
    var cleared_clearance = null
    var request = null

    $(document).ready(function(){
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000
        })

        var userType = "{{ auth()->user()->type }}";

        $('.select2').select2()

        $('#filter_clearedstatus').select2({
            placeholder: 'All',
            allowClear: true,
            minimumResultsForSearch: Infinity
        })

        // $(document).on('click','#btn-generate', function(){
        //     if($('#filter_sectionid').val() == null){
        //         $('#filter_sectionid').removeClass('is-valid').addClass('is-invalid')
        //         $("#btn-generate").attr('disabled', true)
        //     }else{
        //         action = 'Adviser'
        //         request = 'MONITORING'
        //         getstudents()
        //     }
        // })

        $(document).on('change','#filter_levelid', function(){
            getsection()
            $('#filter_sectionid').removeClass('is-invalid').removeClass('is-valid')
        })

        $(document).on('change','#filter_syid', function(){
            $('#clearance_view').attr('hidden', true)
        })

        $(document).on('click','#getclearance', function(){
            selectedid = $(this).attr('clearstud-id')
            getclearance()
        })

        $(document).on('change','#filter_acadterm', function(){
            getstudents()
        })

        $(document).on('change','#filter_sectionid', function(){
            getstudents()
        })

        $('#remarks_modal').on('hidden.bs.modal', function () {
            $('#stud_name').empty()
            $('#remarks_title').empty()
            $('#clearance_remarks').val("")
            name = ''
        })

        getgradelevel()

        function getgradelevel(){
            $.ajax({
                url: '/students/clearance/approval/getallgradelevel',
                type: 'GET',
                dataType: 'json',
                data: {},
                success:function(data){ 
                    level = data
                    $('#filter_levelid').empty();
                    if(level.length > 0 ){
                        $('#filter_gradelevel').empty()
                        $("#filter_levelid").select2({
                            data:level,
                            placeholder: "Select Grade Level",
                        })
                    }else{
                        $("#filter_levelid").select2({
                            placeholder: "No Grade Level!",
                        })
                        // $("#btn-generate").prop('disabled', true)
                    }
                    getsection()
                }
            })
        }

        function getsection(){
            var syid = $('#filter_syid').val();
            var levelid = $('#filter_levelid').val();

            $.ajax({
                url: '/students/clearance/approval/getsection',
                type: 'GET',
                dataType: 'json',
                data: {
                    syid:syid,
                    levelid:levelid,
                },
                success:function(data){
                    $('#filter_sectionid').empty();
                    section = data
                    if(section.length > 0){
                        $("#filter_sectionid").select2({
                            data:section,
                            placeholder: "Select Grade Level",
                        })
                    }else{
                        $("#filter_sectionid").select2({
                            placeholder: "No Section!",
                        })
                    }
                    getacadterm()
                }
            })
        }

        function getacadterm(){
            var syid = $('#filter_syid').val();
            var levelid = $('#filter_levelid').val();

            $.ajax({
                url: '/students/clearance/getacadterm',
                type: 'GET',
                dataType: 'json',
                data: {
                    syid:syid,
                    levelid:levelid,
                },
                success:function(data){
                    terms = data
                    $("#filter_acadterm").empty();
                    if(terms.length > 0 )
                    {
                        $("#filter_acadterm").select2({
                            minimumResultsForSearch: Infinity,
                            data:terms,
                            placeholder: "Select Term",
                        })
                        // $("#btn-generate").prop('disabled', false)
                    }else{
                        $("#filter_acadterm").select2({
                            minimumResultsForSearch: Infinity,
                            placeholder: "No Active Term!",
                        })
                        // $("#btn-generate").prop('disabled', true)
                    }
                    action = 'Adviser'
                    request = 'MONITORING'
                    getstudents()
                }
            })
        }

        function getstudents(){
            var table = $('#table_students').DataTable();
            table.clear().draw();
            var syid = $('#filter_syid').val();
            var acadterm = $('#filter_acadterm').val();
            var levelid = $('#filter_levelid').val();
            var sectionid = $('#filter_sectionid').val();
            var iscleared = $('#filter_clearedstatus').val();
            var loadingPopup = null

            if(action == 'Adviser'){
                loadingPopup = Swal.fire({
                    title: 'Fetching data...',
                    allowOutsideClick: false,
                    onBeforeOpen: () => {
                        Swal.showLoading();
                    }
                });
            }

            $.ajax({
                url: '/students/clearance/approval/getclearancestud',
                type: 'GET',
                dataType: 'json',
                data: {
                    action:action,
                    requested:request,
                    syid:syid,
                    acadterm:acadterm,
                    sectionid:sectionid,
                    levelid:levelid,
                    iscleared:iscleared,
                },
                success:function(data){
                    $('#clearance_view').attr('hidden', false)
                    students = data
                    studtable()
                    action = ""
                    loadingPopup.close()
                },
                error:function(){
                    action = ""
                    loadingPopup.close()
                    Toast.fire({
                        type: 'error',
                        title: 'Error/User signatory disabled'
                    })
                }
            })
        }

        function studtable(){
            var countstuds = students[0].length
            var countcleared = students[0].filter(x=>x.iscleared == 0).length
            var countuncleared = students[0].filter(x=>x.iscleared == 1).length
            var countpending = students[0].filter(x=>x.iscleared == 2).length

            $("#table_students").DataTable({
                destroy: true,
                data: students[0],
                lengthChange: false,
                stateSave: true,
                sort: false,
                columns: [
                    {"data": "lastname"},
                    {"data": "firstname"},
                    {"data": "middlename"},
                ],
                columnDefs:[
                    {
                        'targets': 0,
                        'orderable': true,
                        'createdCell': function (td, cellData, rowData, row, col){
                            var clearstud_id = rowData.clearance_studid != null ? rowData.clearance_studid : rowData.id;
                            var middlename = rowData.middlename != null ? rowData.middlename : '';

                            var text = `<a href="#" id="getclearance" clearstud-id="`+clearstud_id+`"> <i class="fas fa-info-circle" aria-hidden="true"> </i></a>&nbsp<b>` + rowData.lastname + `</b>, ` + rowData.firstname +" "+ middlename

                            $(td)[0].innerHTML = text
                            $(td).addClass('align-middle')
                        }
                    },
                    {
                        'targets': 1,
                        'orderable': true,
                        'createdCell': function (td, cellData, rowData, row, col){
                            var sectname = rowData.sectname
                            if(sectname == undefined){
                                sectname = ''
                            }

                            var text = rowData.levelname +" "+ sectname

                            $(td)[0].innerHTML = text
                            $(td).addClass('align-middle')
                        }
                    },
                    {
                        'targets': 2,
                        'orderable': true,
                        'createdCell': function (td, cellData, rowData, row, col){
                            var clearedstatus = rowData.iscleared
                            var date = ""
                            var formattedDatetime = ""

                            if(clearedstatus == 0){
                                date = rowData.cleareddatetime
                                status = '<span class="badge badge-success"><i class="fas fa-check" aria-hidden="true"> </i> Cleared</span>'
                            }else if(clearedstatus == 1){
                                date = rowData.updateddatetime
                                status = '<span class="badge badge-danger"><i class="fas fa-times" aria-hidden="true"> </i> Uncleared</span>'
                            }else if(clearedstatus == 2){
                                date = rowData.updateddatetime
                                status = '<span class="badge badge-warning"><i class="fas fa-hourglass-half" aria-hidden="true"> </i> Pending</span>'
                            }else{
                                date = null
                                status = '<span class="badge badge-warning"><i class="fas fa-ban" aria-hidden="true"></i> No Clearance Yet!</span>'
                            }

                            if(date != null){
                                let datetime = new Date(date);
                                let options = {
                                    year: 'numeric',
                                    month: '2-digit',
                                    day: '2-digit',
                                    hour: '2-digit',
                                    minute: '2-digit',
                                    second: '2-digit',
                                    hour12: true
                                };
                                formattedDatetime = datetime.toLocaleString('en-US', options);
                            }else{
                                formattedDatetime = ""
                            }

                            var text = '<span style="font-size:.9rem !important">' + status + '</span><br>'
                            text += '<span style="font-size:.7rem !important">' + formattedDatetime + '</span>'

                            $(td)[0].innerHTML = text
                            $(td).addClass('align-middle')
                            $(td).addClass('text-center')
                        }
                    },
                ],
                rowCallback: function(row, data) {
                    $(row).attr('data-id', data.id);
                }
            });

            var label_text = ''

            var label_text = $($("#table_students_wrapper")[0].children[0])[0].children[0]
            $(label_text)[0].innerHTML = `<div class="row">
                    <div class="col-md-12 btn_count" >
                        <div class="row">
                            <button type="button" class="btn btn-primary mr-1 mb-1 btn-sm">
                                <span class="badge badge-light">`+ countstuds +`</span> Students
                            </button>
                            <button type="button" class="btn btn-success mr-1 mb-1 btn-sm">
                                <span class="badge badge-light">`+ countcleared +`</span> Cleared 
                            </button>
                            <button type="button" class="btn btn-danger mr-1 mb-1 btn-sm">
                                <span class="badge badge-light">`+ countuncleared +`</span> Uncleared
                            </button>
                            <button type="button" class="btn btn-warning mr-1 mb-1 btn-sm">
                                <span class="badge badge-light">`+ countpending +`</span> Pending
                            </button>
                        </div>
                    </div>
                </div>`
        }

        function getclearance(){
            $("#student_name").empty()

            var clerstudid = selectedid
            console.log(clerstudid)
            var selected = students[0].filter(x=>x.clearance_studid == clerstudid)
            if(selected == ''){
                selected = students[0].filter(x=>x.id == clerstudid)
            }

            var termid = selected[0].termid
            var syid = selected[0].syid
            var str = selected[0].middlename
            if(str != null){
                var middlename = str.charAt(0) + '.' 
            }else{
                var middlename = ''
            }
            var name = '<h5><b>' + selected[0].lastname + ',</b> ' + selected[0].firstname + ' ' + middlename + '</h5>'

            $("#student_name").append(name)
            $.ajax({
                url: '/setup/student/clearance/getdata',
                type:'GET',
                dataType:'json',
                data:{
                    clerstudid:clerstudid,
                    syid:syid,
                    termid:termid
                },
                success:function(data){
                    clearance_data = data
                    console.log(clearance_data)
                    var total_clearance = clearance_data[0].length
                    var cleared_clearance = clearance_data[0].filter(x=>x.status === 0).length
                    var outof = '(' + cleared_clearance + ' cleared out of  ' + total_clearance + ')'

                    $("#outof").empty()
                    $("#outof").html(outof)
                    clearance_table()
                    $("#view_clearance").modal("show");
                },
                error:function(){
                    Toast.fire({
                        type: 'error',
                        title: 'Something went wrong!'
                    })
                }
            })   
        }

        function clearance_table(){
            $("#clearance_table").DataTable({
                destroy: true,
                data: clearance_data[0],
                lengthChange: true,
                stateSave: true,
                sort: false,
                searching: false, 
                paging: false, 
                info: false,
                responsive: true,
                columns: [
                    {"data": null},
                ],
                columnDefs:[
                    {
                        'targets': 0,
                        'orderable': true,
                        'createdCell': function (td, cellData, rowData, row, col){
                            var sub = rowData.subjdesc
                            var remarks = rowData.remarks
                            var middlename = rowData.middlename
                            var firstname = rowData.firstname
                            var lastname = rowData.lastname
                            var title = rowData.title
                            var status = rowData.status
                            var badge = ""
                            var date = ""

                            if(status == 0){
                                badge = '<span class="badge badge-pill badge-success"><i class="fa fa-check"></i></span>'
                                date = rowData.approveddatetime
                            }
                            if(status == 1){
                                badge = '<span class="badge badge-pill badge-danger"><i class="fa fa-times"></i></span>'
                                date = rowData.updateddatetime
                            }
                            if(status == 2){
                                badge = '<span class="badge badge-pill badge-warning"><i class="fas fa-hourglass-half"></i></span>'
                                date = rowData.updateddatetime
                            }
                            if(status == null){
                                badge = '<span class="badge badge-pill badge-warning"><i class="fas fa-ban"></i></span>'
                                date = null
                            }

                            if(date != null){
                                let datetime = new Date(date);
                                let options = {
                                    year: 'numeric',
                                    month: 'long',
                                    day: '2-digit',
                                };
                                formattedDatetime = '(' + datetime.toLocaleDateString('en-US', options) + ')';
                            }else{
                                formattedDatetime = ""
                            }

                            if(remarks == null){
                                remarks = "No Remarks"
                            }
                            if(title == null){
                                title = " "
                            }
                            if(lastname == null){
                                lastname = " "
                            }
                            if(firstname == null){
                                firstname = " "
                            }
                            if(middlename == null){
                                var firstLetter = ''
                            }else{
                                var firstLetter = middlename.charAt(0) + '.'
                            }
                            var fullname = ' - ' + title +' '+ firstname +' '+ firstLetter +' '+ lastname

                            if(sub == null){
                                subname = rowData.subject_id
                            }else{
                                subname = rowData.subjdesc
                            }
                            if(subname == null){
                                subname = rowData.departmentid
                            }
                            if(subname == null){
                                subname = 'Class Adviser'
                            }

                            var status = '<span>' + badge + ' <b>'  + subname +'</b>' + fullname + ' ' + formattedDatetime + '</span><br>'
                            // var subdesc = '<span class="text-right"><b>' + subname +'</b>' + fullname + '</span><br>'

                            var text = status
                            // text += subdesc
                            text += remarks

                            $(td)[0].innerHTML = text
                        }
                    },
                ],
                rowCallback: function(row, data) {
                    $(row).attr('data-id', data.id);
                }
            });


            $("#cleared_status").empty()
            var iscleared = clearance_data && clearance_data[1] && clearance_data[1][0] && clearance_data[1][0].iscleared;
            if(iscleared == 0){
                $("#cleared_status").append('<h5><span class="badge badge-success"><i class="fa fa-check"></i> Cleared</span></h5>');
            }else if(iscleared == 2){
                $("#cleared_status").append('<h5><span class="badge badge-warning"><i class="fas fa-hourglass-half"></i> Pending</span></h5>');
            }else{
                $("#cleared_status").append('<h5><span class="badge badge-danger"><i class="fa fa-times"></i> Uncleared</span></h5>');
            }
        }
    })
</script>
@endsection