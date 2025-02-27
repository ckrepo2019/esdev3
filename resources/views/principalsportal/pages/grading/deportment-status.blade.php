
@php

      $check_refid = DB::table('usertype')->where('id',Session::get('currentPortal'))->select('refid')->first();

      if(Session::get('currentPortal') == 3){
            $extend = 'registrar.layouts.app';
      }else if(auth()->user()->type == 17){
            $extend = 'superadmin.layouts.app2';
      }else if(Session::get('currentPortal') == 2){
            $extend = 'principalsportal.layouts.app2';
      }else if(Session::get('currentPortal') == 1){
            $extend = 'teacher.layouts.app';
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
      <link rel="stylesheet" href="{{asset('plugins/jquery-image-viewer-magnify/css/jquery.magnify.min.css')}}">
      <link rel="stylesheet" href="{{asset('plugins/jquery-image-viewer-magnify/css/magnify-bezelless-theme.css')}}">
      <style>
            .select2-container--default .select2-selection--single .select2-selection__rendered {
                  margin-top: -9px;
            }
            .shadow {
                  box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
                  border: 0 !important;
            }
            img{
                  border-radius: 0 !important
            }
            .myFont{
                  font-size:.8rem !important;
            }
            .tableFixHead {
                  overflow: auto;
                  height: 100px;
            }

            .tableFixHead thead th {
                  position: sticky;
                  top: 0;
                  background-color: #fff;
                  outline: 2px solid #dee2e6;
                  outline-offset: -1px;
            
            }

            .ribbon-wrapper.ribbon-lg .ribbon {
                  right: -16px;
                  top: 4px;
                  width: 160px;
            }

            .enroll , .view_enrollment {
                  cursor: pointer;
            }

            .form-control-sm-form {
                  height: calc(1.4rem + 1px);
                  padding: 0.75rem 0.3rem;
                  font-size: .875rem;
                  line-height: 1.5;
                  border-radius: 0.2rem;
            }
            input[type=search]{
                  height: calc(1.7em + 2px) !important;
            }

      </style>
@endsection


@section('content')
    

<!-- Font Awesome -->
   
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/eugz.css') }}"> 

<!-- Student Count Details Modal -->
    <div class="modal fade" id="studlist_detials" tabindex="-1" aria-labelledby="studlist_detialsLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
            <div class="modal-header bg-primary p-1"></div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-2">
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <label id="modal-status-title">Submitted Students</label>
                                </div>
                            </div>

                            <div class="row" style=" font-size:11px !important">
                                <div class="col-md-12 form-group">
                                    <label for="">Status</label>
                                    <select name="" id="filter_status_4" class="form-control form-control-sm">
                                    </select>
                                </div>
                            </div>

                            <!-- <hr> -->

                            <div class="row" style=" font-size:11px !important">
                                <div class="col-md-12 form-group">
                                    <label for="">Sections</label>
                                    <select name="" id="filter_section" class="form-control form-control-sm">
                                    </select>
                                </div>
                            </div>
                            
                            <!-- <div class="row" style=" font-size:11px !important">
                                <div class="col-md-12 form-group">
                                    <label for="">Teacher</label>
                                    <select name="" id="filter_teacher" class="form-control form-control-sm">
                                    </select>
                                </div>
                            </div> -->

                            <br>
                            <hr>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <button class="btn btn-primary btn-sm" id="filter_button_4" style=" font-size:11px !important"><i class="fas fa-filter"></i> Filter</button>
                                </div>
                                <div class="col-md-6">
                                    <button class="btn btn-danger btn-sm float-right" data-dismiss="modal" style=" font-size:11px !important"><i class="fas fa-times"></i> Close</button>
                                </div>
                            </div>
                            </div>
                            <div class="col-md-10">
                                <div class="row">
                                    <div class="col-md-12" style="font-size:11px !important">
                                            
                                            <table class="table-hover table-bordered table table-striped table-sm " id="student_status" width="100%">
                                                <thead>
                                                        <tr>
                                                            <th width="25%">Student</th>
                                                            <th width="20%">Section</th>
                                                            <th width="20%">Teacher</th>
                                                            <th class="text-center" width="5%">Initial</th>
                                                            <th class="text-center" width="5%">Final</th>
                                                            <th class="text-center" width="25%">Action</th>
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
        </div>

<!-- Student Count Details Modal END-->

<!-- Deportment Status Details Modal -->
    <div class="modal fade" id="deportmentstatus_detials" tabindex="-1" aria-labelledby="deportmentstatus_detialsLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
            <div class="modal-header bg-primary p-1"></div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="row" style=" font-size:11px !important">
                            <div class="col-md-5">
                                    <strong><i class="fas fa-book mr-1"></i> Grade Level</strong>
                                    <p class="text-muted" id="label_gradelevel">GRADE 10</p>
                            </div>
                            <div class="col-md-7">
                                    <strong><i class="fas fa-book mr-1"></i> Section</strong>
                                    <p class="text-muted" id="label_section">GRADE 10</p>
                            </div>
                        </div>
                       
                        <div class="row" style=" font-size:11px !important">
                            <div class="col-md-12">
                                    <strong><i class="fas fa-book mr-1"></i> Teacher</strong>
                                    <p class="text-muted mb-0" id="label_teacher">MS. FINANCE  ADMIN </p>
                                    <p class="text-danger mb-0">
                                        <i id="label_tid">20210001</i>
                                    </p>
                            </div>
                        </div>
                        <hr>

                        <div class="row mt-3" style=" font-size:11px !important">

                            <div class="col-md-5">
                                    <strong><i class="fas fa-book mr-1"></i> Grade Status</strong>
                                    <p class="text-muted" id="label_status">Posted</p>
                            </div>

                        </div>

                        <!-- <div class="row" style=" font-size:11px !important">
                            <div class="col-md-12">
                                    <strong><i class="fas fa-book mr-1"></i> Last date Uploaded</strong>
                                    <p class="text-muted" id="label_dateuploaded">No file uploaded</p>
                            </div>
                        </div> -->

                        <!-- <div class="row" style=" font-size:11px !important">

                            <div class="col-md-7">
                                <strong><i class="fas fa-book mr-1"></i> Grade Submitted</strong>
                                <p class="text-muted" id="label_datesubmitted">August 20, 2022 03:26 pm</p>
                            </div>

                        </div> -->

                        <hr>

                        <div class="row mt-3" style=" font-size:11px !important">
                            <div class="col-md-12">
                                    <button class="btn btn-danger btn-sm" data-dismiss="modal" style=" font-size:11px !important"><i class="fas fa-times"></i> Close</button>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-4">
                                    <h5>Class Deportment</h5>
                            </div>
                            <div class="col-md-2">
                                    <button class="btn btn-primary btn-sm btn-block status_btn" style=" font-size:11px !important" id="approve_grade">Approve</button>
                            </div>
                            <div class="col-md-2 for_p">
                                    <button class="btn btn-info btn-sm btn-block status_btn" style=" font-size:11px !important" id="post_grade">Post</button>
                            </div>
                            <div class="col-md-2 ">
                                    <button class="btn btn-warning btn-sm btn-block status_btn" style=" font-size:11px !important" id="pending_grade">Pending</button>
                            </div>
                            <div class="col-md-2 for_p">
                                    <button class="btn btn-danger btn-sm btn-block status_btn" style=" font-size:11px !important" id="unpost_grade">Unpost</button>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-md-12" id="class-deportment-holder" style="font-size:11px !important;">
                            </div>
                        </div>

                        <style>
                            .deportment-table-header1 {
                                position: sticky;
                                top: 0px;
                            }

                            .deportment-table-header2 {
                                position: sticky;
                                top: 27.23px;
                            }

                            .deportment-table-header3 {
                                position: sticky;
                                top: 54.46px
                            }

                            .deportment-table td:nth-child(2) {
                                position: sticky;
                                left: 31px;
                            }

                            .deportment-table th:nth-child(2) {
                                position: sticky;
                                left: 31px;
                            }
                        
                            .deportment-table th:nth-last-child(2), .deportment-table td:nth-last-child(2) {
                                position: sticky;
                                right: 58px;
                                z-index: 1;
                            }

                            .deportment-table th:nth-last-child(3), .deportment-table td:nth-last-child(3) {
                                position: sticky;
                                right: 98px;
                            }

                        </style>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>

<!-- Deportment Status Details Modal END-->

<!-- BODY -->

    <div class="content-header">
        <h1 class="page-title" >Deportment Status</h1>
    </div>

    <section class="content pt-0">
      <div class="container-fluid">     
            <div class="row">
                    
                    <div class="col-md-5">

                        <div class="col-md-12">
                            
                            <div class="info-box shadow-lg">
                                <span class="info-box-icon bg-success"><i class="fas fa-filter"></i></span>
                                <div class="info-box-content">
                                    <div class="row">
                                            <div class="col-md-6  form-group">
                                                <label for="filter_sy">School Year</label>
                                                <select id="filter_sy" name="filter_sy" class=" form-control select2"></select>
                                            </div>

                                            <!-- <div class="col-md-3  form-group">
                                                <label for="filter_section">Section</label>
                                                <select id="filter_section" name="filter_section" class=" form-control select2"></select>
                                            </div> -->
                                            
                                            <div class="col-md-6  form-group">
                                                <label for="filter_quarter">Quarter</label>
                                                <select id="filter_quarter" name="filter_quarter" class=" form-control select2"></select>
                                            </div>

                                    </div>
                                    <div class="row">
                                            <div class="col-md-12 text-left">
                                                <button class="btn btn-success btn-sm" id="button_filter"><i class="fas fa-filter"></i> Filter</button>
                                            </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="card shadow">
                                <div class="card-header border-0 pb-0">
                                    <h3 class="card-title">Student Statistics</h3>
                                </div>
                                <div class="card-body pt-2">
                                    <div class="row">
                                        <div class="col-md-12" style="font-size:11px !important">
                                            <table class="table-hover table-bordered table table-striped table-sm " id="student_list" width="100%">
                                                <thead>
                                                        <tr>
                                                            <th width="80%">Status</th>
                                                            <th width="20%" class="text-center">Count</th>
                                                        </tr>
                                                </thead>
                                            </table>
                                                
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                <div class="col-md-7">
                    <div class="card shadow">
                            <div class="card-header border-0 pb-0">
                                <h3 class="card-title">Deportment Status Details</h3>
                            </div>
                            <div class="card-body  pt-2">
                                <div class="row">
                                    <div class="col-md-12" style="font-size:11px !important">

                                        <table class="table-hover table-bordered table table-striped table-sm " id="deportment_details" width="100%">
                                            <thead>
                                                    <tr>
                                                        <th width="33%">Section</th>
                                                        <th width="32%">Teacher</th>
                                                        <th width="35%">Status</th>
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

 
<!-- BODY END-->


@endsection


@section('footerjavascript')

<script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{asset('plugins/datatables/jquery.dataTables.js') }}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
<script src="{{asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>

<script>

    var deportment_list = [];
    var student_list = [];
    var student_status = [];
    var status_list;

    $(document).ready(function(){

        get_sy();
        get_section();
        // get_teacher();
        get_quarter();
        load_deportment_details();
        load_studentlist();


        $(document).on('click', '#button_filter', function(){

            get_deportment_details();
            get_student_list();

        });

        $(document).on('click', '.view_stud_list', function(){

            $('#studlist_detials').modal('toggle');

            var status = $(this).attr('value');

            var headertitle = "";

            if(status == 1){

                headertitle = "Not Submitted Students";

            }
            else if(status == 2){

                headertitle = "Submitted Students";
            }
            else if(status == 3){

                headertitle = "Pending Students";
            }
            else if(status == 4){

                headertitle = "Aproved Students";
            }
            else if(status == 5){

                headertitle = "Posted Students";
            }

            $('#modal-status-title').text(headertitle);
            
            get_student_status(status);

            

        });

        $(document).on('click', '#filter_button_4', function(){

            var status = $('#filter_status_4').val();
            var section = $('#filter_section').val();
            var teacher = $('#filter_teacher').val();
            var headertitle ="";

            if(status == 1){

                headertitle = "Not Submitted Students";

            }
            else if(status == 2){

                headertitle = "Submitted Students";
            }
            else if(status == 3){

                headertitle = "Pending Students";
            }
            else if(status == 4){

                headertitle = "Aproved Students";
            }
            else if(status == 5){

                headertitle = "Posted Students";
            }

            $('#modal-status-title').text(headertitle);


            filter_student_status(status, section);

        });

        $(document).on('click', '.view_deportment_section', function(){

            $('#deportmentstatus_detials').modal('toggle');

            var sectionid = $(this).attr('data-id');
            var grade_status = $(this).attr('grade_status');
            var gradelevel = $(this).attr('gradelevel');
            var section = $(this).attr('section');
            var teacher = $(this).attr('teacher');
            var tid = $(this).attr('tid');
            var statuslist = $(this).attr('statuslist');

            load_class_deportment(sectionid, grade_status);

            $('#label_gradelevel').text(gradelevel);
            $('#label_section').text(section);
            $('#label_teacher').text(teacher);
            $('#label_tid').text(section);
            $('#label_status').text(statuslist);

        });

        $(document).on('click', '#checkAll', function(){

            $('input:checkbox[name=status_checkbox]').not(this).prop('checked', this.checked);
            
        });

        $(document).on('click', '.status_btn', function(event){

            var statusCode = $(this).html();
            var status;

            
            if(statusCode == "Post"){

                status = 5;
            }
            else if(statusCode == "Approve"){

                status = 4;
            }

            else if(statusCode == "Pending"){

                status = 3;
            }

            else{

                status = 2;
            }

            console.log(status);

            array = [];
                
            $("input:checkbox[name=status_checkbox]:checked").each(function(){
                array.push($(this).val());
            });

            if(array.length == 0){

                notify('error', 'Please select student you want to submit.');

            }else{

                Swal.fire({
                title: 'Are you sure?',
                text: "You want to approve?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
                }).then((result) => {

                    if (result.value) {
                        var syid = $('#filter_sy').val();
                        var sectionid = $('#section_id').val();
                        var quarter_ID = $('#filter_quarter').val();
                        var hpsid = $('.hps-holder').attr('row_id');

                        $.ajax({
                            
                            url:'{{ route("update.grade.status") }}',
                            type:"GET",
                            data:{

                                array:array,
                                syid:syid,
                                sectionid:sectionid,
                                quarter_ID:quarter_ID,
                                hpsid: hpsid,
                                status:status

                            },success:function(response){

                                notify(response[0]['statusCode'], response[0]['message']);
                                  
                                load_class_deportment(sectionid, status);
                                get_student_list();
                                get_deportment_details();

                                
                            }

                        });
                    }
                        
                })

            }

        });

        $(document).on('click', '.pending', function(event){

            var studid = $(this).val();
            var currentstatusCode = $(this).attr('status_code');

            update_specificstud_gradestat(3, studid, currentstatusCode);

        });


        $(document).on('click', '.approved', function(event){

            var studid = $(this).val();
            var currentstatusCode = $(this).attr('status_code');

            update_specificstud_gradestat(4, studid, currentstatusCode);

        });


        $(document).on('click', '.posted', function(event){

            var studid = $(this).val();
            var currentstatusCode = $(this).attr('status_code');

            update_specificstud_gradestat(5, studid, currentstatusCode);

        });

        $(document).on('click', '.unpost', function(event){

            var studid = $(this).val();
            var currentstatusCode = $(this).attr('status_code');

            update_specificstud_gradestat(4, studid, currentstatusCode);
        });



    });













    function load_deportment_details(){

        $("#deportment_details").DataTable({
                destroy: true,
                data:deportment_list,
                lengthChange : false,
                columns: [
                        { "data": null, 

                            render: function ( data, type, row ) {

                                var data = row.sectioname+"/n"+row.levelname;

                                return data;
                                
                            },
                        },
                        

                        { "data": null, 
                            render: function ( data, type, row ) {
								
                                var data = row.title+" "+ row.lastname +" "+row.firstname+"/n"+row.tid;
                                
                                return data;
                            
                            },
                        },
                        { "data": "gradestatus" },
                    ],
                columnDefs: [
                    {
                        'targets': 0,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {

                                var status = '';
                                var statusText = '';


                                for (let index = 0; index < rowData.gradestatus.length; index++) {

                                    
                                    if(rowData.gradestatus[index] != 0){


                                        if(index == 0){

                                            status = "Submitted";

                                        }
                                        else if(index == 1){
                                            
                                            status = "Pending";

                                        }
                                        else if(index == 2){
                                            
                                            status = "Aproved";

                                        }
                                        else if(index == 3){
                                            
                                            status = "Posted";

                                        }

                                        statusText += status+', ';
                                        

                                    }
                                    
                                }

                            var buttons = '<a style="cursor: pointer" class="text-primary view_deportment_section" statuslist="'+statusText+'" gradelevel="'+rowData.levelname+'"  section="'+rowData.sectionname+'" tid="'+rowData.tid+'" teacher="'+rowData.title+' '+ rowData.lastname +' '+rowData.firstname+'"grade_status="'+rowData.gradestatus+'" data-id="'+rowData.id+'">'+rowData.sectionname+'</a>'
                                +'<p class="m-0">'+rowData.levelname+'</p>';
                            $(td)[0].innerHTML =  buttons;
                        }
                    },
                    
                    {
                        'targets': 1,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
							var title = "";
								
							if(row.title != null){
								title = row.title;
							}
                            var buttons = '<p class="m-0">'+title+" "+ rowData.lastname +" "+rowData.firstname+'</p>'
                                +'<p class="m-0">'+rowData.tid+'</p>';
                            $(td)[0].innerHTML =  buttons;
                        }
                    },

                    {
                            'targets': 2,
                            'orderable': false, 
                            'createdCell':  function (td, cellData, rowData, row, col) {

                                var status = '';
                                var color = '';
                                var buttons = '';


                                for (let index = 0; index < rowData.gradestatus.length; index++) {

                                    
                                    if(rowData.gradestatus[index] != 0){


                                        if(index == 0){

                                            status = "Submitted";
                                            color = "success";

                                        }
                                        else if(index == 1){
                                            
                                            status = "Pending";
                                            color = "warning";

                                        }
                                        else if(index == 2){
                                            
                                            status = "Aproved";
                                            color = "primary";

                                        }
                                        else if(index == 3){
                                            
                                            status = "Posted";
                                            color = "info";

                                        }

                                        buttons += '<a style="cursor: pointer; border-radius: 2px; padding: 2px 5px;" class="bg-'+color+' ml-1">'+status+'</a>';
                                        

                                    }
                                    
                                }

                                $(td)[0].innerHTML =  buttons;

                                $(td).addClass('text-center');


                            }
                    },
                ]
                
        });
    }

    function load_studentlist(){

        $("#student_list").DataTable({
                destroy: true,
                data: student_list,
                lengthChange : false,
				searching: false,
                paging: false,
                info: false,
                bSort: false,
                columns: [
                            { "data": "status" },
                            { "data": "count" },
                    ],
                columnDefs: [
                    {
                            'targets': 0,
                            'orderable': false, 
                      
                    },

                    {
                            'targets': 1,
                            'orderable': false, 
                            'createdCell':  function (td, cellData, rowData, row, col) {
                                var disabled = '';
                                var buttons = '<a style="cursor: pointer"  class="view_stud_list text-primary" value="'+rowData.statuscode+'">'+rowData.count+'</a>';
                                $(td)[0].innerHTML =  buttons;
                                $(td).addClass('text-center')
                            }
                    },
                ]
                
        });
    }

    function load_student_status(){

        $("#student_status").DataTable({
                destroy: true,
                data:student_status,
                lengthChange : false,
                columns: [
                        { "data": null,

                            render: function ( data, type, row ) {

                                var middlename = row.middlename; 
                                if(middlename == null){
                                    middlename = " ";

                                }else{
                                    middlename = row.middlename.charAt(0)+".";

                                }
                                return row.lastname+", "+ row.firstname;
                            }  
                        },
                        { "data": "sectionname",
                            render: function ( data, type, row ) {

                                return row.sectionname+"<br>"+row.levelname;
                            
                            },
                        },
                        { "data": null, 
                            render: function ( data, type, row ) {

                                return row.title+" "+ row.teacherfirtname +" "+row.teacherlastname+"<br>"+row.tid;
                            
                            },
                        },
                        { "data": "initial"},
                        { "data": "final"},
                        { "data": null},
                    ],
                columnDefs: [
                    {
                        'targets': 0,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            var buttons = '<a style="cursor: pointer"  class="text-primary" data-id="'+rowData.id+'">'+rowData.lastname+', '+rowData.firstname+'</a><p class="m-0">'+rowData.sid+'</p>';
                            $(td)[0].innerHTML =  buttons;
                        }
                    },

                    {
                        'targets': 3,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {

                            $(td).addClass('text-center');
                        }
                    },

                    {
                        'targets': 4,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {

                            $(td).addClass('text-center');
                        }
                    },

                    {
                        'targets': 5,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            var buttons = '';
                            
                            if(rowData.gradestatus == 2){

                                buttons = '<button class="btn btn-warning btn-sm mr-1 pending" status_code="'+rowData.gradestatus+'" value="'+rowData.id+'" style="font-size:.6rem; width: 50px;">Pending</button>'
                                        +'<button class="btn btn-primary btn-sm mr-1 approved" status_code="'+rowData.gradestatus+'" value="'+rowData.id+'" style="font-size:.6rem; width: 50px;">Approve</button>'
                                        +'<button class="btn btn-info btn-sm posted" status_code="'+rowData.gradestatus+'" value="'+rowData.id+'" style="font-size:.6rem; width: 50px;">Post</button>';

                            }
                            else if(rowData.gradestatus == 3){

                                buttons = '';
                            }
                            else if(rowData.gradestatus == 4){

                                buttons = '<button class="btn btn-info btn-sm mr-1 posted" status_code="'+rowData.gradestatus+'" value="'+rowData.id+'" style="font-size:.6rem; width: 50px;">Post</button>'
                                    +'<button class="btn btn-warning btn-sm pending" status_code="'+rowData.gradestatus+'" value="'+rowData.id+'" style="font-size:.6rem; width: 50px;">Pending</button>';
                                                            
                            }

                            else if(rowData.gradestatus == 5){

                                buttons = '<button class="btn btn-warning btn-sm mr-1 pending" status_code="'+rowData.gradestatus+'" value="'+rowData.id+'" style="font-size:.6rem; width: 50px;">Pending</button>'
                                    +'<button class="btn btn-danger btn-sm mr-1 unpost" status_code="'+rowData.gradestatus+'" value="'+rowData.id+'" style="font-size:.6rem; width: 50px;">Unpost</button>';

                                                            
                            }
                            else{

                                false;
                            }

                            $(td)[0].innerHTML =  buttons;
                            $(td).addClass("text-center");
                        }
                    },
                ]
                
        });
    }

    function load_class_deportment(sectionid, grade_status){

        var syid = $('#filter_sy').val();
        var quarterid = $('#filter_quarter').val();

        
        $.ajax({
            type:'GET',
            url:'{{ route("load.class.table") }}',
            data: {
                
                syid:syid,
                quarterid:quarterid,
                sectionid:sectionid,
                grade_status:grade_status
            },
            success:function(data) {

                $("#class-deportment-holder").html(data);
            }
        })
    }


    function get_deportment_details(){

        var sy = $('#filter_sy').val();
        var quarter = $('#filter_quarter').val();

        $.ajax({
                type:'GET',
                url:'{{ route("get.deportment.details") }}',
                data: {
                    sy:sy,
                    quarter:quarter
                },
                success:function(data) {
                    
                    if(data.length == 0){

                        notify('warning', 'No deportment found.');
                     
                    }else{
                        deportment_list = data;
                        load_deportment_details();
                    }
                }
        })
    }

    function get_student_list(){

        var sy = $('#filter_sy').val();
        var quarter = $('#filter_quarter').val();

        $.ajax({
                type:'GET',
                url:'{{ route("get.student.list") }}',
                data: {
                    sy:sy,
                    quarter:quarter
                },
                success:function(data) {

                    if(data.length == 0){

                        notify('warning', 'No deportment found.');
                    
                    }else{

                        var array = [
                            {
                                "status": "NOT SUBMITTED",
                                "count":  "0",
                                "statuscode": 1,
                               
                            },
                            {
                                "status": "SUBMITTED",
                                "count": "0",
                                "statuscode": 2,
                               
                            },
                            {
                                "status": "PENDING",
                                "count":  "0",
                                "statuscode": 3,
                               
                            },
                            {
                                "status": "APROVED",
                                "count": "0",
                                "statuscode": 4,
                                
                            },
                            {
                                "status": "POSTED",
                                "count": "0",
                                "statuscode": 5,
                               
                            }
                        ]

                        for (let i = 0; i < array.length; i++) {
                            
                            if(i == 0){

                                array[i]['count'] = data[0]['notsubmitted'];
                            }

                            else if(i == 1){

                                array[i]['count'] = data[0]['submitted'];
                            }

                            else if(i == 2){

                                array[i]['count'] = data[0]['pending'];
                            }

                            else if(i == 3){

                                array[i]['count'] = data[0]['aproved'];
                            }

                            else if(i == 4){

                                array[i]['count'] = data[0]['posted'];
                            }
                        }

                        student_list = array;
                        load_studentlist();
                    }
                }
        })
    }

    function get_student_status(status){

        var sy = $('#filter_sy').val();
        var quarter = $('#filter_quarter').val();

        $.ajax({
                type:'GET',
                url:'{{ route("get.student.status") }}',
                data: {
                    sy:sy,
                    quarter:quarter,
                    status:status
                },
                success:function(data) {
                    
                    student_status = data[0]['student'];
                    status_list = data[0]['status']
                    get_status();
                    load_student_status();
                    
                }
        })
    }

    function filter_student_status(status, section){

        var sy = $('#filter_sy').val();
        var quarter = $('#filter_quarter').val();

        $.ajax({
                type:'GET',
                url:'{{ route("filter.student.status") }}',
                data: {
                    sy:sy,
                    quarter:quarter,
                    status:status,
                    section:section,
                    // teacher:teacher,
                },
                success:function(data) {
                    
                    // console.log(data);
                    student_status = data;
                    load_student_status();
                    
                }
        })
    }

    function get_sy(){ // Para ma display ang mga available school year sa select2
        var sy = @json($sy);

        for (let i = 0; i < sy.length; i++) {
            
            if(sy[i]['isactive'] == 1){

                selected_sy = sy[i]['id'];
            }
            
        }

        $('#filter_sy').empty()
        $('#filter_sy').append('<option value="">Select School Year</option>')
        $('#filter_sy').select2({
            data: sy,
            allowClear: true,
            placeholder: "Select School Year",
        });

        if(selected_sy != null){

            $('#filter_sy').val(selected_sy).change()
        }

    }

    function get_quarter(){ // Para ma display ang mga quarter sa select2

        var quarter = [
            {"id":1,"text":"1st Quarter"},
            {"id":2,"text":"2nd Quarter"},
            {"id":3,"text":"3rd Quarter"},
            {"id":4,"text":"4th Quarter"},

        ]


        $('#filter_quarter').empty()
        $('#filter_quarter').append('<option value="">Select Quarter</option>')
        $("#filter_quarter").select2({
            data: quarter,
            allowClear: true,
            placeholder: "Select Quarter",
        })
    }

    function get_status(){ // Para ma display ang mga status sa select2
        var selected_status;

        for (let i = 0; i < status_list.length; i++) {
            
            if(status_list[i]['isactive'] == 1){

                selected_status = status_list[i]['id'];
            }
            
        }


        $('#filter_status_4').empty()
        $('#filter_status_4').append('<option value="">Select Status</option>')
        $("#filter_status_4").select2({

            data: status_list,
            allowClear: true,
            placeholder: "Select Status",
             
        })

        if(selected_status != null){

            $('#filter_status_4').val(selected_status).change()
        }
    }

    function get_section(){ // Para ma display ang mga sections sa select2

        var sections = @json($sections);

        
        $('#filter_section').empty()
        $('#filter_section').append('<option value="">Select Sections</option>')
        $("#filter_section").select2({
            data: sections,
            allowClear: true,
            placeholder: "Select Sections",
        })
    }

    // function get_teacher(){ // Para ma display ang mga sections sa select2

    //     var teachers = @json($teachers);

    //     teachers.forEach(element => {

    //         element.text = element.lastname+', '+element.firstname;
            
    //     });

        
    //     $('#filter_teacher').empty()
    //     $('#filter_teacher').append('<option value="">Select Teacher</option>')
    //     $("#filter_teacher").select2({
    //         data: teachers,
    //         allowClear: true,
    //         placeholder: "Select Teacher",
    //     })
    // }

    function update_specificstud_gradestat(status, studid, currentStatus){

        var sy = $('#filter_sy').val();
        var quarter = $('#filter_quarter').val();

        $.ajax({
            type:'GET',
            url:'{{ route("update.stud.gradstatus") }}',
            data: {
                sy:sy,
                quarter:quarter,
                studid:studid,
                status: status,
                currentStatus: currentStatus,
            },
            success:function(data) {
                
                notify(data[0]['statusCode'], data[0]['message']);
                get_student_list();
                get_deportment_details();
                get_student_status(data[0]['currentStudStatus'])
            }
        })
    }
                    
    /////////////SWEET ALERT///////////////
        function notify(code, message){
            Swal.fire({
                type: code,
                title: message,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
            });

        }

</script>
@endsection