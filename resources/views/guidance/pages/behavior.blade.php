@php

        if(!Auth::check()){ 
                header("Location: " . URL::to('/'), true, 302);
                exit();
        }
        $check_refid = DB::table('usertype')->where('id',Session::get('currentPortal'))->select('refid')->first();
        if(Session::get('currentPortal') == 17){
                $extend = 'superadmin.layouts.app2';
        }else if(Session::get('currentPortal') == 3){
                $extend = 'registrar.layouts.app';
        }else if(Session::get('currentPortal') == 6){
                $extend = 'adminPortal.layouts.app2';
        }else if(Session::get('currentPortal') == 2){
                $extend = 'principalsportal.layouts.app2';
        }
        else if(Session::get('currentPortal') == 8){
                $extend = 'admission.layouts.app2';
        }
        else{
                if(isset($check_refid->refid)){
                        if($check_refid->refid == 27){
                                $extend = 'academiccoor.layouts.app2';
                        }
                        if($check_refid->refid == 31){
                                $extend = 'guidance.layouts.app2';
                        }
                }else{
                header("Location: " . URL::to('/'), true, 302);
                exit();
                }
                
        }
@endphp


@extends($extend)


@section('pagespecificscripts')
        <link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">

        <style>
        .select2-container .select2-selection--single {
                    
                    height: auto;
                    margin-bottom: 20px;
                }

        .select2-selection__choice {
            font-size: 16px; /* Change the font size */
            background-color: #5cb85c !important; /* Change the background color */
            color:rgb(255, 255, 255) !important;
            border-radius: 5px; /* Add rounded corners */
            padding: 2px 8px; /* Add some padding */
            margin-right: 5px; /* Add some space between items */
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            top: 10px;
        }

        </style>
@endsection




@section('modalSection')

        <div class="modal" id="behavior_modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Action</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <label>Student </label><br/>
                                <input type="text" class="form-control" id="report-behavior-name" disabled/>
                            </div>
                            <div class="col-md-12 mb-2">
                                <label>Behavior </label><br/>
                                <textarea class="form-control" id="behavior" disabled></textarea>
                            </div>
                            
                            <div class="col-md-6">
                                <label>Date </label><br/>
                                <input type="date" class="form-control" id="behavior-date" value="{{date('Y-m-d')}}" disabled/>
                            </div>
                            <div class="col-md-6">
                                <label>Card </label><br/>
                                <input type="text" class="form-control text-light" id="behavior-card" disabled/>
                            </div>
                            <div class="col-md-12 mt-2">
                                <label>Action Recommended</label><br/>
                                <textarea class="form-control" id="behavior-action" disabled></textarea>
                                <hr class="hr">
                            </div>
                            <div class="col-md-6 mt-2">
                                
                                <label>Schedule Date</label><br/>
                                <input type="date" class="form-control" id="schedule-date" value="{{date('Y-m-d')}}"/>
                            </div>
                            <div class="col-md-12 mt-2">
                                <label>Message</label><br/>
                                <textarea class="form-control" id="behavior-message" ></textarea>

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-end">
                        <button type="button" class="btn btn-info" data-id="0" id="btn-summon">Notify Student</button>
                        <button type="button" class="btn btn-primary" data-id="1" id="btn-summon">Notify  Parent</button>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal" id="behavior_create_modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Report Behavior</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <label>Student <span class="text-danger">*</span></label><br/>
                                <select class="form-control select2" id="studentlist">
                                </select>
                            </div>
                            <div class="col-md-12 mb-2">
                                <label>Behavior <span class="text-danger">*</span></label><br/>
                                <textarea class="form-control" id="behavior2"></textarea>
                            </div>
                            
                            <div class="col-md-6">
                                <label>Date <span class="text-danger">*</span></label><br/>
                                <input type="date" class="form-control" id="behavior-date2" value="{{date('Y-m-d')}}"/>
                            </div>
                            <div class="col-md-6">
                                <label>Card <span class="text-danger">*</span></label><br/>
                                <select class="form-control" id="card2">
                                    <option value="Red">   Red </option>
                                    <option value="Green">Green </option>
                                </select>
                            </div>
                            <div class="col-md-12 mt-2">
                                <label>Action Recommended<span class="text-danger"></span></label><br/>
                                <textarea class="form-control" id="behavior-action2"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal" id="btn-close-addcomplaint">Close</button>
                        <button type="button" class="btn btn-primary" id="btn-submit-behavior">Submit</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal" id="pendingModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-secondary">
                        <h5 class="my-2"> Pending</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">

                            <div class="col-md-12">
                                
                                <table id="admissionDataTable" class="table table-striped" style="width : 100%;">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th width = "15%">#</th>
                                            <th width = "15%">Student</th>
                                            <th width = "20%">Behavior</th>
                                            <th width = "10%" >Card</th>
                                            <th width = "20%" class="text-center">Date</th>
                                            <th width = "10%" class="text-center">Status</th>
                                            <th width = "10%" class="text-center"></th>
                                        </tr>
                                    </thead>                            
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal" id="notifiedModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="my-2"> Notified</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <table id="notifiedDatable" class="table table-striped display" style="width:100%; overflow-x: auto;">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th width = "15%">#</th>
                                            <th width = "15%">Student</th>
                                            <th width = "20%">Behavior</th>
                                            <th width = "10%" >Card</th>
                                            <th width = "20%" class="text-center">Message</th>
                                            <th width = "10%" class="text-center">Date</th>
                                            <th width = "10%" class="text-center"></th>
                                        </tr>
                                    </thead>                            
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal" id="resolveModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-success">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">

                            <div class="col-md-12">
                                <h5 class="my-2"> Resolved</h5>
                                <table id="resolveDatable" class="table table-striped" style="width : 100%;">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th width = "15%">#</th>
                                            <th width = "15%">Student</th>
                                            <th width = "20%">Behavior</th>
                                            <th width = "10%" >Card</th>
                                            <th width = "20%" class="text-center">Remarks</th>
                                            <th width = "10%" class="text-center">Date</th>
                                            <th width = "10%" class="text-center"></th>
                                        </tr>
                                    </thead>                            
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal" id="absentModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="my-2"> Student Attedance</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <table id="absencesDatable" class="table table-striped" style="width : 100%;">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th  width = "10%">#</th>
                                            <th  width = "30%">Student</th>
                                            <th  width = "20%" class = "text-center">No. of Absent</th>
                                            <th  width = "25%">Status</th>
                                            <th  width = "10%" ></th>
                                        </tr>
                                    </thead>                            
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal" id="attendance_modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2>Absence Data</h2>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">

                            <table class="table" id="dateDataTable">
                                <thead>
                                    <tr>
                                        <th width = "5%">#</th>
                                        <th witdh = "95%">Absent Dates</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-end">
                        <button type="button" class="btn btn-info" data-id="0" id="btn-summon">Notify Student</button>
                        <button type="button" class="btn btn-primary" data-id="1" id="btn-summon">Notify  Parent</button>
                    </div>
                </div>
            </div>
        </div>







@endsection





@section('content')

        
<!-- Main content -->
        <section class="content h-100">
            <div class="container-fluid h-100">
                    </div>
                <!-- /.row -->
                <!-- Main row -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card shadow">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <h5><i class="fa fa-filter"></i> Filter</h5> 
                                    </div>
                                </div>
                                {{-- <div class="row">
                                    <div class="col-md-2  form-group  mb-0">
                                        <label for="" class="mb-1">Name</label>
                                        <input type="text" class="form-control float-right" placeholder="Student Name" id= "studname">

                                    </div>
                                    <div class="col-md-3  form-group  mb-0">
                                        <label for="" class="mb-1">Date Range</label>
                                        <input type="text" class="form-control float-right" id="Date">
                                    </div>
                                </div> --}}
                                <div class="row">
                                    <div class="col-md-2 form-group mb-0">
                                        <label for="studname" class="mb-1">Name</label>
                                        <input type="text" class="form-control float-right" placeholder="Student Name" id="studname">
                                    </div>
                                    <div class="col-md-3 form-group mb-0">
                                        <label for="Date" class="mb-1">Date Range</label>
                                        <div class="input-group">
                                        <input type="text" class="form-control float-right" id="Date" placeholder="Enter Date here">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" type="button" id="clearDate">Clear</button>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-12">
                        <div class="card shadow">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 text-right">
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#behavior_create_modal">
                                            Add New Report
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="container-fluid">
                    <div class="row">

                        <div class= "col-md-3">
                            <div  class="card card-row shadow rounded h-100" style="max-height: 100vh;">
                                <div class="card-header bg-warning">
                                    <h1 class="card-title ">
                                    Student Attendance
                                    </h1>

                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-type="absent" id="showtable">
                                            <i class="fas fa-th-large"></i>
                                        </button>
                    
                                    </div>
                                </div>
                                <div class="card-body"  id="contentlistattendance" style="overflow-y: scroll; max-height: 100vh;">
                                </div>
                            </div>
                        </div>

                        <div class= "col-md-3">
                            <div class="card card-row shadow rounded h-100" >
                                <div class="card-header bg-secondary">
                                    <h1 class="card-title">
                                    Pending
                                    </h1>
                                    <button class="btn btn-secondary position-absolute p-0 mr-2" style="top: -2; right: 0;" data-type="pending" id="showtable">
                                        <i class="fas fa-th-large"></i>
                                    </button>
                                </div>
                                <div class="card-body" id="contentlist" style="overflow-y: scroll; max-height: 100vh;"    >
                                    
                                </div>
                            </div>
                        </div>


                        <div class= "col-md-3">
                            <div  id="drop-area2"  class="card card-row shadow rounded  h-100"  >
                                <div class="card-header bg-primary">
                                    <h1 class="card-title ">
                                    Notified
                                    </h1>
                                    <button class="btn btn-primary position-absolute p-0 mr-2" style="top: -2; right: 0;" data-type="notified" id="showtable">
                                        <i class="fas fa-th-large"></i>
                                    </button>
                                </div>
                                <div class="card-body" id="contentlistnotified" style="overflow-y: scroll; max-height: 100vh;">
                                </div>
                            </div>
                        </div>

                        

                        <div class= "col-md-3">
                            <div  id="drop-area" class="card card-row shadow rounded  h-100" >
                                <div class="card-header bg-success">
                                    <h1 class="card-title ">
                                    Resolve
                                    </h1>
                                    <button class="btn btn-success position-absolute p-0 mr-2" style="top: -2; right: 0;" id="showtable" data-type="resolve" id="showtable">
                                        <i class="fas fa-th-large"></i>
                                    </button>
                                    
                                </div>
                                <div class="card-body" id="contentlistResolve" style="overflow-y: scroll; max-height: 100vh;" >
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- /.row (main row) -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->



        
@endsection



@section('footerjavascript')


        <script src="{{asset('templatefiles/jquery-3.3.1.min.js')}}"></script>
        <script src="{{asset('plugins/jquery-ui/jquery-ui.min.js')}}"></script>
        <script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
        <script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{asset('plugins/moment/moment.min.js')}}"></script>
        <script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
        <script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script>
        
<script>

var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
})



$(document).ready(function(){

    var REPORT_DATA;
    var REPORT_DATA2;
    var RESOLVE_REPORT;
    var ABSENT_TABLE;
    var DATE_TABLE;
    var STUDENT_ID;
    var ID;
    var SEARCH;
    var DATE;

    
    //init
    admissionDataTable();
    admissionDataTable2();
    absencesDatable();
    admissionDataTable3();

    $('#Date').daterangepicker();

    $('#studentlist').select2({
            width: '100%',
            allowClear: true,
            placeholder: "Select Student",
            language: {
                noResults: function () {
                    return "No results found";
                }
            },
            escapeMarkup: function (markup) {
                return markup;
            },
            ajax: {
                url: "{{ route('studentSelect') }}",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        search: params.term,
                        page: params.page || 0,
                    }
                    return query;
                },
                processResults: function (data, params) {
                    params.page = params.page || 0;
                    return {
                        results: data.results,
                        pagination: {
                            more: data.pagination.more
                        }
                    };
                },
                cache: true
            }
    });


    $("#drop-area").droppable({
            accept: ".draggable-card",
            drop: function(event, ui) {

                var element = $(ui.draggable);

                var id = element.data("id");
                var type= element.data("type");

                resolve(id,type);

                // Handle the drop event here
                // Log to console
                // Make the draggable element vanish
                ui.helper.hide();
            }
    });

    $("#drop-area2").droppable({
            accept: ".action",
            drop: function(event, ui) {

                var element = $(ui.draggable);

                var id = element.data("id");
                var type= element.data("type");

                button(id);

                // Handle the drop event here
                // Log to console
                // Make the draggable element vanish
                ui.helper.hide();
            }
    });
        

    

    function dragdrop(){
        $(".draggable-card").draggable({
            helper: "clone", // Use a clone of the element for dragging
            appendTo: "body", // Append the helper element to the body to avoid issues with overflow
            zIndex: 1000, // Set a high zIndex for the helper element to ensure it appears above other elements
            // Add any other draggable options you need
        });
        $(".action").draggable({
            helper: "clone", // Use a clone of the element for dragging
            appendTo: "body", // Append the helper element to the body to avoid issues with overflow
            zIndex: 1000, // Set a high zIndex for the helper element to ensure it appears above other elements
            // Add any other draggable options you need
        });

        

    }

    function resolve(id, type){



                if(type ==0){
                    Swal.fire({
                    title: 'Mark as Unresolved?',
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirm'
                    }).then((result) => {
                        if (result.value === true) {


                            $.ajax({
                            type:'GET',
                            url: '/guidance/report/resolve',
                            data:{
                                id         : id,
                                resolve    : type,
                            },
                            success: function(data) {

                                admissionDataTable();
                                admissionDataTable2();
                                Swal.fire(
                                'Resolved',
                                'The Report has been resolved.',
                                'success'
                                )

                            }
                            })
                        
                            


                        }
                    })

                }else{

                    Swal.fire({
                        title: 'Mark as Resolved?',
                        html: '<input type="text" id="remarks" class="swal2-input" placeholder="Enter remarks...">',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Resolve'
                    }).then((result) => {
                        if (result.value) {
                            var remarks = $('#remarks').val(); // Get the value of the input field

                            $.ajax({
                                type: 'GET',
                                url: '/guidance/report/resolve',
                                data: {
                                    id: id,
                                    resolve: type,
                                    remarks: remarks // Pass the remarks value to the server
                                },
                                success: function (data) {
                                    admissionDataTable2();
                                    admissionDataTable3();
                                    Swal.fire(
                                        'Resolved',
                                        'The Report has been resolved.',
                                        'success'
                                    )
                                }
                            })
                        }else{

                            admissionDataTable2();
                            admissionDataTable3();


                        }
                    });
                }

        

    }

    function button(id){

                    
                    
                    ID = id;
                    var data = REPORT_DATA.filter(x => x.id == ID);
                    STUDENT_ID = data[0].studentid;
                    $('#behavior_modal').modal({
                        backdrop: 'static',
                        keyboard: false  // optional: disable keyboard interactions as well
                    });
        

                    $('#report-behavior-name').val(data[0].studname);
                    $('#behavior').val(data[0].behavior);
                    $('#behavior-date').val(data[0].cdate);
                    $('#behavior-card').val(data[0].card);
                    $('#behavior-action').val(data[0].actionrecommended);

                    if (data[0].card == 'Green') {
                        $('#behavior-card').css('background-color', '#0ADD08');
                    } else {
                        $('#behavior-card').css('background-color', '#FF000D');
                    }
    

    }
    function buttonabsent(){

        $(document).on('click','#absenttable',function(){

                var studid = $(this).data('studid');
                $('#attendance_modal').modal();


                $.ajax({
                            type:'GET',
                            url: '/guidance/getAbsence/dates',
                            data:{
                                studid     : studid,
                            },
                            success: function(data) {


                                DATE_TABLE = data;
                                renderDateDataTable();
                            }
                            })
        

        });

    }
    

    function guidanceList() {

        var html ='';


        
        if(REPORT_DATA.length > 0) {

                for (var i = 0; i < REPORT_DATA.length; i++) {
                                
                    html +=`<div class="card shadow rounded action" data-id="${REPORT_DATA[i].id}" style="background-color: ${REPORT_DATA[i].color}">
                                <div class="card-body position-relative">
                                    <div class="row">
                                        <div class="col-md-4"> <!-- Adjusted column classes for responsiveness -->
                                            <img src="{{ asset('${REPORT_DATA[i].picurl}') }}" alt="profile" style="max-height: 100px; width: 50px; border-radius: 50%; overflow: hidden;" class="w-100"> <!-- Added 'img-fluid' class for responsive images -->
                                        </div>
                                        <div class="col-md-8">
                                            <h6>${REPORT_DATA[i].studname}</h6>
                                            <hr>
                                            <p class="text-muted">${REPORT_DATA[i].behavior}</p>
                                            <p class="text-muted"><b>Recommended: </b>${REPORT_DATA[i].actionrecommended}</p>
                                        </div>
                                    </div>
                                    <div class="date text-right">
                                        ${REPORT_DATA[i].cdate2}
                                    </div>
                                </div>
                            </div>
                            `;
                    }

    

                $("#contentlist").empty().append(html);
                dragdrop();

                

        }else{

                var html = '<div class="d-flex justify-content-center align-items-center h-100"><h2>No data available</h2></div>';
                $("#contentlist").empty().append(html);
        }



    }

    function guidanceListNotified() {

        var html ='';


        
        if(REPORT_DATA2.length > 0) {

                for (var i = 0; i < REPORT_DATA2.length; i++) {
                                
                    html +=`<div class="card shadow rounded draggable-card" data-id="${REPORT_DATA2[i].id}" data-type="1" style="background-color: ${REPORT_DATA2[i].color}">
                                <div class="card-body position-relative">
                                    <div class="row">
                                        <div class="col-md-4"> <!-- Adjusted column classes for responsiveness -->
                                            <img src="{{ asset('${REPORT_DATA2[i].picurl}') }}" alt="profile" style="max-height: 100px; width: 50px; border-radius: 50%; overflow: hidden;" class="w-100"> <!-- Added 'img-fluid' class for responsive images -->
                                        </div>
                                        <div class="col-md-8">
                                            <h6>${REPORT_DATA2[i].studname}</h6>
                                            <p class="text-muted">${REPORT_DATA2[i].behavior}</p>
                                            <p class="text-muted"><i>"${REPORT_DATA2[i].message}"</i></p>
                                        </div>
                                    </div>
                                    <div class="date text-right">
                                        ${REPORT_DATA2[i].cdate3}
                                    </div>
                                </div>
                            </div>
                            `;
                }

    

                $("#contentlistnotified").empty().append(html);
                dragdrop();

                

        }else{

                var html = '<div class="d-flex justify-content-center align-items-center h-100"><h2>No data available</h2></div>';
                $("#contentlistnotified").empty().append(html);
        }



    }


    function guidanceListResolve() {

        var html ='';


        
        if(RESOLVE_REPORT.length > 0) {

                for (var i = 0; i < RESOLVE_REPORT.length; i++) {
                                
                    html +=`<div class="card shadow rounded" data-id="${RESOLVE_REPORT[i].id}" id="resolve" data-type="0" style="background-color: ${RESOLVE_REPORT[i].color}">
                                <div class="card-body position-relative">
                                    <div class="row">
                                        <div class="col-md-4"> <!-- Adjusted column classes for responsiveness -->
                                            <img src="{{ asset('${RESOLVE_REPORT[i].picurl}') }}" alt="profile" style="max-height: 100px; width: 50px; border-radius: 50%; overflow: hidden;" class="w-100"> <!-- Added 'img-fluid' class for responsive images -->
                                        </div>
                                        <div class="col-md-8">
                                            <h6>${RESOLVE_REPORT[i].studname}</h6>
                                            <p class="text-muted">${RESOLVE_REPORT[i].behavior}</p>
                                            <p class="text-muted"><b> Remarks :</b> ${RESOLVE_REPORT[i].remark}</p>
                                        </div>
                                    </div>
                                    <div class="date text-right">
                                        ${RESOLVE_REPORT[i].cdate3}
                                    </div>
                                </div>
                            </div>
                            `;
                }

    

                $("#contentlistResolve").empty().append(html);

                

        }else{

                var html = '<div class="d-flex justify-content-center align-items-center h-100"><h2>No data available</h2></div>';
                $("#contentlistResolve").empty().append(html);
        }



    }


    function attendanceList() {

        var html ='';


        
        if(ABSENT_TABLE.length > 0) {

                for (var i = 0; i < ABSENT_TABLE.length; i++) {
                                
                    html +=`<div class="card shadow rounded" id="absenttable" data-studid="${ABSENT_TABLE[i].studid}">
                                <div class="card-body position-relative">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <img src="{{ asset('${ABSENT_TABLE[i].picurl}') }}" alt="profc style="height: 50px; width: 50px; border-radius: 50%; overflow: hidden;" class="h-100 w-100">
                                        </div>
                                        <div class="col-md-8">
                                            <h6> ${ABSENT_TABLE[i].studname}. </h6>
                                            <p class="text-muted">${ABSENT_TABLE[i].count} total absent</p>
                            
                                        </div>
                                    </div>
                                    <div class="date text-right">
                                    </div>
                                </div>
                            </div>`;
                }

    

                $("#contentlistattendance").empty().append(html);
                buttonabsent();

                

        }else{

                var html = '<div class="d-flex justify-content-center align-items-center h-100"><h2>No data available</h2></div>';
                $("#contentlistattendance").empty().append(html);
        }



    }

    

    


    function admissionDataTable() {

            $.ajax({
                type:'GET',
                data: {
                    search  : SEARCH,
                    date    : DATE,
                },
                url: '/guidance/reportTable',
                success: function(data) {
                    REPORT_DATA = data;
                    renderAdmissionDataTable();
                    guidanceList();
                }
            });

    }

    function admissionDataTable2() {

            $.ajax({
                type:'GET',
                data: {
                    search : SEARCH,
                    date    : DATE,
                },
                url: '/guidance/reportTable2',
                success: function(data) {
                    REPORT_DATA2 = data;

                    renderAdmissionDataTable2();
                    guidanceListNotified();
                }
            });

    }

    function admissionDataTable3() {

            $.ajax({
                type:'GET',
                data: {
                    search : SEARCH,
                    date    : DATE,
                },
                url: '/guidance/reportresolve',

                success: function(data) {
                    RESOLVE_REPORT = data;

                    renderAdmissionDataTable3();
                    guidanceListResolve();
                }
            });

    }

    function absencesDatable() {

            $.ajax({
                type:'GET',
                url: '/guidance/getAbsence',
                data: {search : SEARCH},
                success: function(data) {

                    ABSENT_TABLE = data;
                    renderAbsencesDatable();
                    attendanceList();
                    
                }
            });

    }


    function renderAdmissionDataTable(){
            $("#admissionDataTable").DataTable({
                responsive: false,
                autowidth: false,
                destroy: true,
                data:REPORT_DATA,
                order: [[0, 'asc']],
                lengthChange: false,
                ordering: false,
                columns: [
                    { "data": null},
                    { "data": null},
                    { "data": null},
                    { "data": null},
                    { "data": null},
                    { "data": null},
                    { "data": "search"}
                ],
                columnDefs: [
                    {
                        'targets': 0,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                                var text = '<a class="mb-0">'+(row+1)+'</a>'
                                $(td)[0].innerHTML =  text
                                $(td).addClass('align-middle')
                        }
                    },
                
                
                    {
                        'targets': 1,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                                var text = '<a class="mb-0">'+rowData.studname +'.</a>'
                                $(td)[0].innerHTML =  text;
                                $(td).addClass('align-middle')
                        }
                    },
                    {
                        'targets': 2,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            var text = '<a class="mb-0">'+rowData.behavior+'</a>'
                            $(td)[0].innerHTML =  text
                            $(td).addClass('align-middle')
                        }
                    },
                    {
                        'targets': 3,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {

                                if(rowData.card == 'Green') {

                                    var text = '<a class="m-1"> <i class="fas fa-square" style="color: #0ADD08;"></i> '+ rowData.card + '</a>'
                                

                                }else{

                                    var text = '<a class="m-1"> <i class="fas fa-square" style="color: #FF000D;"></i> '+ rowData.card + '</a>'


                                }
                                $(td)[0].innerHTML =  text; 
                                $(td).addClass('align-middle')
                                
                            }
                    
                    },
                    {
                        'targets': 4,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            var text = `<a class="mb-0"> ${rowData.cdate2}</a>`
                            $(td)[0].innerHTML =  text
                            
                            $(td).addClass('text-center')
                            $(td).addClass('align-middle')
                        }
                    },
                    {
                        'targets': 5,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            var text = '<a class="mb-0">'+(rowData.message ? '<span class="badge badge-info"> Requested presence </span> <br> <span class="badge badge-warning">' + rowData.scheddate + '</span>':  ' <span class="badge badge-light">No Presence Request Yet </span>')+'</a>'
                            $(td)[0].innerHTML =  text
                            
                            $(td).addClass('text-center')
                            $(td).addClass('align-middle')
                        }
                    },
                    {
                        'targets': 6,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            
                

                        
                            var buttons = `<span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Disabled tooltip">
                                    <button type="button" class="btn btn-success" data-studid="${rowData.studentid}" data-id="${rowData.id}"   data-toggle="tooltip" data-placement="bottom" title="Resolve Report" data-type="1" id="resolve">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </span>
                                `;

                            $(td)[0].innerHTML =  buttons
                            $(td).addClass('text-center')
                            $(td).addClass('align-middle')


                        }
                    }
                ]
            });

    }

    function renderAdmissionDataTable2(){
            $("#notifiedDatable").DataTable({
                responsive: true,
                autowidth: true,
                destroy: true,
                data:REPORT_DATA2,
                order: [[0, 'asc']],
                lengthChange: false,
                ordering: false,
                columns: [
                    { "data": null},
                    { "data": null},
                    { "data": null},
                    { "data": null},
                    { "data": null},
                    { "data": null},
                    { "data": "search"}
                ],
                columnDefs: [
                    {
                        'targets': 0,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                                var text = '<a class="mb-0">'+(row+1)+'</a>'
                                $(td)[0].innerHTML =  text
                                $(td).addClass('align-middle')
                        }
                    },
                
                
                    {
                        'targets': 1,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                                var text = '<a class="mb-0">'+rowData.studname +'.</a>'
                                $(td)[0].innerHTML =  text;
                                $(td).addClass('align-middle')
                        }
                    },
                    {
                        'targets': 2,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            var text = '<a class="mb-0">'+rowData.behavior+'</a>'
                            $(td)[0].innerHTML =  text
                            $(td).addClass('align-middle')
                        }
                    },
                    {
                        'targets': 3,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {

                                
                                if(rowData.card == 'Green') {

                                    var text = '<a class="m-1"> <i class="fas fa-square" style="color: #0ADD08;"></i> '+ rowData.card + '</a>'
                                

                                }else{

                                    var text = '<a class="m-1"> <i class="fas fa-square" style="color: #FF000D;"></i> '+ rowData.card + '</a>'


                                }
                                $(td)[0].innerHTML =  text; 
                                $(td).addClass('align-middle')
                                
                            }
                    
                    },
                    {
                        'targets': 4,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            var text = rowData.message ? rowData.message : 'No Message';
                            var truncatedText = text.length > 20 ? text.substring(0, 20) + '...' : text;

                            var link = $('<a>', {
                            class: 'mb-0',
                            text: truncatedText,
                            title: text, // Display the full text on hover
                            });

                            $(td).html(link);
                            $(td).addClass('text-center');
                            $(td).addClass('align-middle');
                            $(td).css('max-width', '100px'); // Set a maximum width for the cell
                            $(td).css('overflow', 'hidden');
                            $(td).css('text-overflow', 'ellipsis');
                            $(td).css('white-space', 'nowrap');

                        }
                    },
                    {
                        'targets': 5,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            var text = `<a class="mb-0"> <span class="badge badge-light"> ${rowData.cdate3} </span> <br> <span class="badge badge-info"> Date  Occured: ${rowData.cdate2} </span> <br> </a>`
                            $(td)[0].innerHTML =  text
                            
                            $(td).addClass('text-center')
                            $(td).addClass('align-middle')
                        }
                    },
                    {
                        'targets': 6,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            
                            var buttons = `<span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Disabled tooltip">
                                    <button type="button" class="btn btn-success" data-studid="${rowData.studentid}" data-id="${rowData.id}"   data-toggle="tooltip" data-placement="bottom" title="Resolve Report" data-type="1" id="resolve">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </span>
                                `;

                            $(td)[0].innerHTML =  buttons
                            $(td).addClass('text-center')
                            $(td).addClass('align-middle')


                        }
                    }
                ]
            });

    }

    function renderAdmissionDataTable3(){
            $("#resolveDatable").DataTable({
                responsive: false,
                autowidth: false,
                destroy: true,
                data:RESOLVE_REPORT,
                order: [[0, 'asc']],
                lengthChange: false,
                ordering: false,
                columns: [
                    { "data": null},
                    { "data": null},
                    { "data": null},
                    { "data": null},
                    { "data": null},
                    { "data": null},
                    { "data": "search"}
                ],
                columnDefs: [
                    {
                        'targets': 0,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                                var text = '<a class="mb-0">'+(row+1)+'</a>'
                                $(td)[0].innerHTML =  text
                                $(td).addClass('align-middle')
                        }
                    },
                
                
                    {
                        'targets': 1,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                                var text = '<a class="mb-0">'+rowData.studname +'.</a>'
                                $(td)[0].innerHTML =  text;
                                $(td).addClass('align-middle')
                        }
                    },
                    {
                        'targets': 2,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            var text = '<a class="mb-0">'+rowData.behavior+'</a>'
                            $(td)[0].innerHTML =  text
                            $(td).addClass('align-middle')
                        }
                    },
                    {
                        'targets': 3,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {

                                
                                if(rowData.card == 'Green') {

                                    var text = '<a class="m-1"> <i class="fas fa-square" style="color: #0ADD08;"></i> '+ rowData.card + '</a>'
                                

                                }else{

                                    var text = '<a class="m-1"> <i class="fas fa-square" style="color: #FF000D;"></i> '+ rowData.card + '</a>'


                                }
                                $(td)[0].innerHTML =  text; 
                                $(td).addClass('align-middle')
                                
                            }
                    
                    },
                    {
                        'targets': 4,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            var text = '<a class="mb-0">' + (rowData.remark ? rowData.remark : 'No Remarks')   + '</a>'
                            $(td)[0].innerHTML =  text

                            $(td).addClass('text-center')
                            $(td).addClass('align-middle')
                        }
                    },
                    {
                        'targets': 5,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            var text = `<a class="mb-0"> <span class="badge badge-light"> ${rowData.cdate3} </span> <br> <span class="badge badge-info"> Date  Occured: ${rowData.cdate2} </span> <br> </a>`
                            $(td)[0].innerHTML =  text
                            
                            $(td).addClass('text-center')
                            $(td).addClass('align-middle')
                        }
                    },
                    {
                        'targets': 6,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            
                            var buttons = `<span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Disabled tooltip">
                                                <button type="button" class="btn btn-info" data-studid="${rowData.studentid}" data-id="${rowData.id}"   data-toggle="tooltip" data-placement="bottom" title="Resolve Report" id="reportInfo">
                                                    <i class="fas fa-info-circle"></i>
                                                </button>
                                            </span>
                                            <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Disabled tooltip">
                                                <button type="button" class="btn btn-danger" data-studid="${rowData.studentid}" data-id="${rowData.id}"   data-toggle="tooltip" data-placement="bottom" title="Resolve Report" data-type="0" id="resolve">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </span>
                                            `;

                            $(td)[0].innerHTML =  buttons
                            $(td).addClass('text-center')
                            $(td).addClass('align-middle')


                        }
                    }
                ]
            });

    }

    function renderAbsencesDatable(){
            $("#absencesDatable").DataTable({
                responsive: false,
                autowidth: false,
                destroy: true,
                data:ABSENT_TABLE,
                order: [[0, 'asc']],
                lengthChange: false,
                ordering: false,
                columns: [
                    { "data": null},
                    { "data": null},
                    { "data": null},
                    { "data": null},
                    { "data": "search"}
                ],
                columnDefs: [
                    {
                        'targets': 0,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                                var text = '<a class="mb-0">'+(row+1)+'</a>'
                                $(td)[0].innerHTML =  text
                                $(td).addClass('align-middle')
                        }
                    },
                
                
                    {
                        'targets': 1,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                                var text = '<a class="mb-0">'+rowData.studname +'.</a>'
                                $(td)[0].innerHTML =  text;
                                $(td).addClass('align-middle')
                        }
                    },
                    {
                        'targets': 2,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            var text = '<a class="mb-0">'+rowData.count+'</a>'
                            $(td)[0].innerHTML =  text
                            $(td).addClass('align-middle')
                            $(td).addClass('text-center')
                        }
                    },
                    {
                        'targets': 3,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {

        
                                var text = '<a class="m-1">  Requested Presence</a>'
                                $(td)[0].innerHTML =  text; 
                                $(td).addClass('align-middle')
                                
                            }
                    
                    },
                    {
                        'targets': 4,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                    
                                    var buttons = `
                                            <button type="button" class="btn btn-primary" data-studid="${rowData.studid}"  data-id="${rowData.id}"  id="absenttable">
                                                <i class="fas fa-info-circle"></i>
                                            </button>
                                            <button type="button" class="btn btn-success" data-studid="${rowData.studid}"   data-toggle="tooltip" data-placement="bottom" title="Resolve Report" data-type="1" id="resolve">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            `;


                            $(td)[0].innerHTML =  buttons
                            $(td).addClass('text-center')
                            $(td).addClass('align-middle')


                        }
                    }
                ]
            });

    }

    function renderDateDataTable(){
            $("#dateDataTable").DataTable({
                responsive: false,
                autowidth: false,
                destroy: true,
                data:DATE_TABLE,
                order: [[0, 'asc']],
                lengthChange: true,
                searching: false,
                ordering: false,
                columns: [
                    { "data": null},
                    { "data": "search"}
                ],
                columnDefs: [
                    {
                        'targets': 0,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                                var text = '<a class="mb-0">'+(row+1)+'</a>'
                                $(td)[0].innerHTML =  text
                                $(td).addClass('align-middle')
                        }
                    },
                
                
                    {
                        'targets': 1,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                                var text = '<a class="mb-0">'+rowData.date +'</a>'
                                $(td)[0].innerHTML =  text;
                                $(td).addClass('align-middle')
                        }
                    },
                ]
            });

    }





    $(document).on('click','#btn-submit-behavior',function(){


                var date = $('#behavior-date2').val();
                var id =   $('#studentlist').val();
                var card = $('#card2').val();
                var action = $('#behavior-action2').val();
                var behavior = $('#behavior2').val();




                if (!date || !card || !action || !behavior ) {
                    Swal.fire({
                        type: 'error',
                        title: 'Oops...',
                        text: 'Please input all fields!'
                    })
                    return;
                }


                var formData = new FormData();
                formData.append('date', date);
                formData.append('card', card);
                formData.append('action', action);
                formData.append('behavior', behavior);
                formData.append('id', id);
                

                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                

                                                // Set the CSRF token in the request headers
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                $.ajax({
                    url: "/teacher/behavior/submitBehavior",
                    type: 'POST', // Use lowercase 'type' instead of 'TYPE'
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        // Handle the success response
                        Toast.fire({
                            type: 'success',
                            title: 'All the changes have been saved'
                        });
                        
                        admissionDataTable();

                    },
                    error: function(xhr) {
                        // Handle error here
                        Toast.fire({
                            type: 'error',
                            title: 'Something went wrong'
                        });

                        admissionDataTable();

                    }
                });



    })



    $(document).on('click','#activate-test',function(){

                TEST_ID = $(this).data('id');
                $('#activateQuizModal').modal();
        

    });

    $(document).on('click','#showtable',function(){

                var type = $(this).data('type');

                if(type == 'pending'){
                    $('#pendingModal').modal();
                }else if(type == 'notified'){
                    $('#notifiedModal').modal();

                }else if(type == 'resolve'){
                    $('#resolveModal').modal();
                }else if(type == 'absent'){
                    $('#absentModal').modal();

                }


    });




    $(document).on('input','#studname',function(){

                SEARCH = $(this).val();
                absencesDatable();
                admissionDataTable2();
                admissionDataTable();
                admissionDataTable3();
        

    });

    $(document).on('click','.applyBtn ',function(){

                DATE = $('#Date').val();
                admissionDataTable2();
                admissionDataTable();
                admissionDataTable3()
        

    });

    $(document).on('click','#clearDate ',function(){

                DATE = "";
                $('#Date').val('');
                admissionDataTable2();
                admissionDataTable();
                admissionDataTable3()
        

    });

    $(document).on('click','#behavior_modal .close',function(){

                admissionDataTable2();
                admissionDataTable();
        

    });

    

    $(document).on('click','#cardfilter',function(){

                var type = $(this).data('type');

                if(type == 1){

                    var  card = 'Green';

                }else{

                    var  card = 'Red';

                }
                admissionDataTable(card);
                
        

    });

    $(document).on('dblclick','.action',function(){

            ID = $(this).data('id');
            STUDENT_ID = $(this).data('studid');
            $('#behavior_modal').modal();
            
            var data = REPORT_DATA.filter(x=>x.id==ID);

            
            $('#report-behavior-name').val(data[0].studname);
            $('#behavior').val(data[0].behavior);
            $('#behavior-date').val(data[0].cdate);
            $('#behavior-card').val(data[0].card);
            $('#behavior-action').val(data[0].actionrecommended);

            if(data[0].card == 'Green') {

                $('#behavior-card').css('background-color', '#0ADD08');
                
            }else{

                $('#behavior-card').css('background-color', '#FF000D');

            }

        

    });

    $(document).on('click','#resolve',function(){
                
                var id = $(this).data('id');

                var type = $(this).data('type');



                if(type ==0){
                    Swal.fire({
                    title: 'Mark as Unresolved?',
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirm'
                    }).then((result) => {
                        if (result.value === true) {


                            $.ajax({
                            type:'GET',
                            url: '/guidance/report/resolve',
                            data:{
                                id         : id,
                                resolve    : type,
                            },
                            success: function(data) {

                                admissionDataTable2();
                                admissionDataTable3();
                                Swal.fire(
                                'Unresolved',
                                'The Report has been unresolved.',
                                'success'
                                )

                            }
                            })
                        
                            


                        }
                    })

                }else{

                    Swal.fire({
                        title: 'Mark as Resolved?',
                        html: '<input type="text" id="remarks" class="swal2-input" placeholder="Enter remarks...">',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Resolve'
                    }).then((result) => {
                        if (result.value) {
                            var remarks = $('#remarks').val(); // Get the value of the input field

                            $.ajax({
                                type: 'GET',
                                url: '/guidance/report/resolve',
                                data: {
                                    id: id,
                                    resolve: type,
                                    remarks: remarks // Pass the remarks value to the server
                                },
                                success: function (data) {
                                    admissionDataTable();
                                    admissionDataTable2();
                                    Swal.fire(
                                        'Resolved',
                                        'The Report has been resolved.',
                                        'success'
                                    )
                                }
                            })
                        }
                    });
                }




    })

    $(document).on('click','#btn-summon',function(){
                var type = $(this).data('id');

                
                var date = $('#schedule-date').val();
                var message = $('#behavior-message').val();


                if (!date || !message) {
                    Swal.fire({
                        type: 'error',
                        title: 'Oops...',
                        text: 'Please input all fields!'
                    })
                    return;
                }

                var formData = new FormData();
                formData.append('type', type);
                formData.append('studid', STUDENT_ID);
                formData.append('date', date);
                formData.append('message', message);
                formData.append('id', ID);
                


                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                

                // Set the CSRF token in the request headers
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                $.ajax({
                    url: "/guidance/behavior/summon",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        // Handle the success response
                        Toast.fire({
                            type: 'success',
                            title: 'All the changes have been saved'
                        });

                        admissionDataTable();
                        admissionDataTable2();

                        $("#behavior_modal .close").click()
                    },
                    error: function(xhr) {
                        // Handle error here
                        Toast.fire({
                            type: 'error',
                            title: 'Something went wrong'
                        });

                        $("#behavior_modal .close").click()
                    }
                });


    })











});




</script>
@endsection
