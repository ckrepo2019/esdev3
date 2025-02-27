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

                }
    

                .select2-selection__choice {
                    font-size: 16px; /* Change the font size */
                    background-color: #5cb85c !important; /* Change the background color */
                    color:rgb(255, 255, 255) !important;
                    border-radius: 5px; /* Add rounded corners */
                    padding: 2px 8px; /* Add some padding */
                    margin-right: 5px; /* Add some space between items */
                }

                input{

                    height: auto;

                }

</style>




@endsection


@section('modalSection')




        <div class="modal fade" id="activateQuizModal" tabindex="-1" aria-labelledby="quizModal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="activateQuizModalLabel" style="color:white" >Activate Quiz</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <div class="modal-body">
                    <form class="was-validated">
                        <div class="form-group">
                            <label for="dateFrom">Date From</label>
                            <input type="date" class="form-control" id="date-from" name="dateFrom" required>
                        </div>
                        <div class="form-group">
                            <label for="timeFrom">Time From</label>
                            <input type="time" class="form-control" id="time-from" name="timeFrom" required>
                        </div>
                        <div class="form-group">
                            <label for="dateTo">Date To</label>
                            <input type="date" class="form-control" id="date-to" name="dateTo" required>
                        </div>
                        <div class="form-group">
                            <label for="timeTo">Time To</label>
                            <input type="time" class="form-control" id="time-to" name="timeTo" required>
                        </div>
                        <div class="form-group">

                            <label for="time">Time Limit:</label>
                                <div name="time" class="border  p-2 rounded">
                                    <label for="hours">Hours:</label>
                                    <input type="number" id="hours" class="form-control" name="hours" min="0" max="23" required>

                                    <label for="minutes">Minutes:</label>
                                    <input type="number" id="minutes" class="form-control" name="minutes" min="0" max="59" required>
                                </div>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success activate">Save</button>
                </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="program_set_up_form_modal" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                                <div class="modal-header pb-2 pt-2 border-0">
                                        <h4 class="modal-title">Program Set-Up Form</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span></button>
                                </div>
                                <div class="modal-body">
                                        <div class="message"></div>
                                        <div class="form-group programdiv">
                                                <label>Program</label>

                                                @php
                                                
                                                        $courses = DB::table('college_courses')
                                                                        ->where('deleted', 0)
                                                                        ->get()
                                                
                                                @endphp


                                            <select class="form-control form-control-alt select2" id="selectCollegeCourse"> 
                                                @foreach($courses as $item)
                                                    <option value="{{ $item->id }}" > {{ $item->courseDesc}} </option>
                                                @endforeach
                                            </select>

                                        </div>

                                        <div class="form-group testdiv">

                                            <label>Test Title</label>
                                            <select class="form-control form-control-alt select2" id="selectTest"> 
                                            </select>
                                        </div>
                                        <hr>
                                        <h4>Subjects</h4>
                                        <div class="form-group" id="subjectDiv">
                                            {{-- <label>Passing Percentage</label>
                                            <input id="passingPercentage" type="number" max="100" placeholder="Enter a value between 0 and 100" name="passingPercentage" class="form-control form-control-sm"> --}}
                                        </div>
                                        <hr>
                                        <div class="form-group">
                                                <label>Slot Available</label>
                                                <input id="slot" type="number" name="slot" class="form-control form-control" placeholder="Enter Program Available Slot">
                                        </div>
                                        <hr>
                                        <h4>General Setup <i class="fas fa-plus" id="addsetup" style="color: #00fa53;"></i> <i class="far fa-copy"  data-toggle="modal" data-target="#selectcourseModal"></i> </h4>
                                        <div id="setupDiv" style="max-height: 300px; overflow-y: auto;  overflow-x: hidden;"> 
                                            <div class="form-group">
                                                <div class="form-group">
                                                    <label class="setupLabel" data-fix="1">Exam Result</label>
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <label>Passing %</label>
                                                            <input id="" type="number" max="100" placeholder="Enter a value between 0 and 100" name="passingPercentage" class="form-control form-control passingPercentage"onkeyup="if (this.value < 0) this.value = ''; if (this.value > 100) this.value = '100'; if (this.value.length > 2) this.value = this.value.slice(0, 2);">
                                                        </div>
                                                        <div class="col-6">
                                                            <label>Overall Percentage</label>
                                                            <input type="number" max="100" placeholder="Enter a value between 0 and 100" name="passingPercentage" class="form-control form-control indiPercentage" id="examPercentage" value="100">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                        </div>
                                        <div class="form-group">
                                            <div class="form-group">
                                                <div class="row justify-content-end">
                                                    <div class="col-6 text-right">
                                                        <hr class="bg-dark">
                                                        <h4> <b>Total: </b> <span id="totalvaluepercentage"> 100 </span></h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        
                                        <div class="row">
                                                <div class="col-md-12">
                                                <button  type="button" class="btn btn-primary btn" id="saveSetup">Save <i class="fas fa-save"></i>  </button>
                                                </div>
                                        </div>
                                </div>
                        </div>
                </div>
        </div>



        <div class="modal fade" id="selectcourseModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Copy Setup</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <select class="form-control select2" id="selectOption">
                        @foreach($courses as $item)
                            <option value="{{ $item->id }}" > {{ $item->courseDesc}} </option>
                        @endforeach
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="saveOption">Save changes</button>
                    </div>
                </div>
            </div>
        </div>









@endsection





@section('content')


<!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Small boxes (Stat box) -->
                <div class="row">
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{$count}}</h3>

                            <p>Applicant</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{$passingrate}}<sup style="font-size: 20px">%</sup></h3>

                                <p>Passing Rate</p>
                            </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{$incoming}}</h3>

                            <p>Incoming Schedule</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{$admissiontest}}</h3>

                            <p>Test Created</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                </div>
                <!-- /.row -->
                <!-- Main row -->
                {{-- <div class="row">
                    <div class="col-md-12">
                        <div class="card shadow">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <h5><i class="fa fa-filter"></i> Filter</h5> 
                                    </div>
                                    <div class="col-md-8">
                                        <h5 class="float-right">Active S.Y.:</h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2  form-group  mb-0">
                                        <label for="" class="mb-1">School Year</label>

                                    </div>
                                    <div class="col-md-3  form-group  mb-0">
                                        <label for="" class="mb-1">User Type</label>
                    
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}

                <ul class="nav nav-pills mb-2" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Activate Test</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Program Set Up</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card shadow">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h5 class="my-2"> Activate test</h5>
                                                <table id="admissionDataTable" class="table table-striped" style="width : 100%;">
                                                    <thead class="thead-dark">
                                                        <tr>
                                                            <th width = "15%">#</th>
                                                            <th width = "15%">Test Title</th>
                                                            <th width = "20%">Test Created</th>
                                                            <th width = "20%" class="text-center">Test Link</th>
                                                            <th width = "20%" class="text-center">Activated on</th>
                                                            <th width = "10%" class="text-center">Activate</th>
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
                    </div>
                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card shadow">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h5 class="my-2"> Program Set-Up</h5>
                                                <table id="programDataTable" class="table table-striped" style="width : 100%;">
                                                    <thead class="thead-dark">
                                                        <tr>
                                                            <th width = "10%">#</th>
                                                            <th width = "30%">Program</th>
                                                            <th width = "20%" class="text-center">Slot Available</th>
                                                            <th width = "30%" class="text-center">Test</th>
                                                            <th width = "10%" class="text-center"> Actions </th>
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
                    </div>
                </div>

                
                <!-- /.row (main row) -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->




@endsection



@section('footerjavascript')

        <script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
        <script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script>
<script>

var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
})
$(document).ready(function(){

    var ADMISSION_DATA;
    var TEST_ID;
    var TOTAL;


    $('#selectCollegeCourse').select2({
        width: '100%',
    });

    $('#selectOption').select2({
        width: '100%',
    });

    $('#selectTest').select2({
            width: '100%',
            allowClear: true,
            placeholder: "Select Test",
            language: {
                noResults: function () {
                    return "No results found";
                }
            },
            escapeMarkup: function (markup) {
                return markup;
            },
            ajax: {
                url: "{{ route('testSelect') }}",
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




    function admissionDataTable() {

            $.ajax({
                type:'GET',
                url: '/admissionTable',
                success: function(data) {
                    ADMISSION_DATA = data;
                    renderAdmissionDataTable();
                }
            });

    }

    function setupDataTable(){

        $.ajax({
                type:'GET',
                url: '/admissiontest/setupTable',
                success: function(data) {
                    SETUP_DATA = data;
                    all_setupmain();
                }
            });

        
    }

    function getSubject(testid) {
                return $.ajax({
                    type: 'GET',
                    data:{

                        testid: testid,
                    },
                    url: '/admissiontest/getSubject',
                })
    }

    function renderAdmissionDataTable(){
            $("#admissionDataTable").DataTable({
                responsive: false,
                autowidth: false,
                destroy: true,
                data:ADMISSION_DATA,
                order: [[0, 'asc']],
                lengthChange: false,
                ordering: false,
                columns: [
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
                                var text = '<a class="mb-0">'+rowData.title +'</a>'
                                $(td)[0].innerHTML =  text;
                        }
                    },
                    {
                        'targets': 2,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            var text = '<a class="mb-0">'+rowData.formattedDate+'</a>'
                            $(td)[0].innerHTML =  text
                        }
                    },
                    {
                        'targets': 3,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                                if(rowData.admissiontestid == null){
                                    var text = '<a class="mb-0"> Test not Activated </a>'
                                }else{
                                    var text = '<div class="d-flex align-items-center justify-content-center">' +
                                    '<span class="mr-2">' + rowData.link + '</span>' +
                                    '<button class="btn btn-sm btn-outline-primary copyButton" data-link="' + rowData.link + '">' +
                                    '<i class="fas fa-copy"></i>' +
                                    '</button>' +
                                    '</div>';
                                }
                                $(td)[0].innerHTML =  text; 
                                $(td).addClass('text-center')
                                $(td).addClass('align-middle')
                                
                            }
                    
                    },
                    {
                        'targets': 4,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            var text = `<a class="mb-0"> 

                                <span class="badge badge-primary">${rowData.activatedon}</span>

                                </a>`
                            $(td)[0].innerHTML =  text
                            
                            $(td).addClass('text-center')
                            $(td).addClass('align-middle')
                        }
                    },
                    {
                        'targets': 5,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            
                                if(rowData.admissiontestid == null){
                                    var buttons = `<button type="button" class="btn btn-primary" style="width: 100px; height: 40px;" data-id="${rowData.id}" id="activate-test">
                                                        Activate &nbsp;
                                                    </button>`;
                                }else{
                                    var buttons = `<button type="button" class="btn ${rowData.badge}" data-id="${rowData.id}" style="width: 100px; height: 40px;" id="reactivate-test">
                                                        ${rowData.type}
                                                    </button>`;
                                }


                                
                                

                            $(td)[0].innerHTML =  buttons
                            $(td).addClass('text-center')
                            $(td).addClass('align-middle')


                        }
                    }
                ]
            });

    }

    function all_setupmain() {
        $("#programDataTable").DataTable({
                    responsive: false,
                    autowidth: false,
                    destroy: true,
                    data: SETUP_DATA,
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
                                    var text = '<a class="mb-0">'+rowData.program+'</a>'
                                    $(td)[0].innerHTML =  text;
                            }
                        },
                        {
                            'targets': 2,
                            'orderable': false, 
                            'createdCell':  function (td, cellData, rowData, row, col) {
                                    var text = '<a class="mb-0">'+rowData.slot+'</a>'
                                    $(td)[0].innerHTML =  text
                                    $(td).addClass('text-center')
                                    $(td).addClass('align-middle')
                                    
                            }
                        },
                        {
                            'targets': 3,
                            'orderable': false, 
                            'createdCell':  function (td, cellData, rowData, row, col) {

                                    var text = '<a class="mb-0">'+ rowData.test + '</i></a>'
                                    $(td)[0].innerHTML =  text; 
                                    $(td).addClass('text-center')
                                    $(td).addClass('align-middle')
                                    
                                }
                        
                        },
                        {
                            'targets': 4,
                            'orderable': false, 
                            'createdCell':  function (td, cellData, rowData, row, col) {
                                var text = `
                                        <button class="btn btn-primary edit-button" data-slot="${rowData.slot}" data-passing="${rowData.passing}" data-id="${rowData.programid}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-danger delete-button" data-id="${rowData.id}">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    `;

                                    $(td)[0].innerHTML = text;

                                    $(td).addClass('text-center');
                                    $(td).addClass('align-middle');

                            }
                        },
                    ]

        });

    
        var label_text = $($('#programDataTable_wrapper')[0].children[0])[0].children[0]
                        $(label_text)[0].innerHTML = '<button class="btn btn-sm btn-primary" title="setup" id="program_setup_button"> <i class="fas fa-plus"></i> Set Up</button>'
    

    }


    function saveSetup(){



        var slot     = $('#slot').val();
        var course   = $('#selectCollegeCourse').val();
        var test     = $('#selectTest').val();


        if(course == null || course == undefined){


            course = $('#programid').data('id');
        }

        if(test == null || test == undefined){


            test = $('#testid').data('id');
        }


        if(TOTAL != 100 && counter != 0){


            Swal.fire({
                type: 'error',
                title: 'Oops...',
                text: 'Total percentage must equal to 100.'
            })
            return;
        }




        if (!slot || !test) {
                Swal.fire({
                        type: 'error',
                        title: 'Hello...',
                        text: 'Please input all fields, thank you'
                        })
                return;
        }
        $('.inputSubject').each(function() {
            const value = $(this).val();
            const id = $(this).data('id');

            $.ajax({
                type:'GET',
                url: '/admissiontest/addSubjectSetup',
                data:{
                        subPassing  : value,
                        subId       : id,
                        course      : course,
                        test        : test,
                },
            })

        });

        var sortid = 1;
        $('.setupLabel').each(function() {
            const value = $(this).text().trim();
            const fix = $(this).data('fix');

            $.ajax({
                type:'GET',
                url: '/admissiontest/addGeneralSetup',
                data:{
                        fix         : fix,
                        sortid      : sortid,
                        setup       : value,
                        course      : course,
                },
            })

            sortid++;

        });

        var sortid = 1;
        $('.passingPercentage').each(function() {
            const value = $(this).val();
        

            $.ajax({
                type:'GET',
                url: '/admissiontest/addGeneralSetupPercentage',
                data:{
                        sortid           : sortid,
                        percentage       : value,
                        course           : course,
                },
            })

            sortid++;

        });

        var sortid = 1;
        $('.passingPercentage').each(function() {
            const value = $(this).val();
            

            $.ajax({
                type:'GET',
                url: '/admissiontest/addGeneralSetupPercentage',
                data:{
                        sortid           : sortid,
                        percentage       : value,
                        course           : course,
                },
            })

            sortid++;

        });

        var sortid = 1;
        $('.indiPercentage').each(function() {
            const value = parseFloat($(this).val()) || 0; // Get the value, convert to float (or default to 0)


            $.ajax({
                type:'GET',
                url: '/admissiontest/addGeneralSetupOveralPercentage',
                data:{
                        sortid           : sortid,
                        overalpercentage : value,
                        course           : course,
                },
            })

            sortid++;
        });



        $('.inputSubject').each(function() {
            const value = $(this).val();
            const id = $(this).data('id');

            $.ajax({
                type:'GET',
                url: '/admissiontest/addSubjectSetup',
                data:{
                        subPassing  : value,
                        subId       : id,
                        course      : course,
                        test        : test,
                },
            })

        });

        $.ajax({
                type:'GET',
                url: '/admissiontest/addSetup',
                data:{
                        test        : test,
                        slot        : slot,
                        course      : course,
                },
                success: function(data) {


                        setupDataTable();
                        $("#program_set_up_form_modal").modal('hide');
                        
                        if(data == 1){
                            Toast.fire({
                                    type: 'success',
                                    title: 'SetUp Added successfully',
                                    timer: 2000,
                            })
                        }else{

                            Toast.fire({
                                    type: 'success',
                                    title: 'SetUp Updated successfully',
                                    timer: 2000,
                            })

                        }

                }
        })

        
    }

    function deleteSetup(id){



                Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                if (result.value == true) {
                        $.ajax({
                        type:'GET',
                        url: '/admissiontest/setup/delete',
                        data:{
                                id    : id,
                        },
                        success: function(data) {

                                setupDataTable();
                                Swal.fire(
                                'Deleted!',
                                'The Setup has been deleted',
                                'success'
                                )

                        }
                        })
                }
                })






        }

    function getgeneralsetup(courseid) {
                return $.ajax({
                    type: 'GET',
                    data:{
                        courseid : courseid,
                    },
                    url: '/getgeneralsetup',
                })
    }

    function getsubjectsetup(courseid, testid) {
                return $.ajax({
                    type: 'GET',
                    data:{
                        courseid : courseid,
                        testid   : testid,
                    },
                    url: '/getsubgeneralsetup',
                })
    }

    function renderDefaultHtml(data) {
        return `<div class="form-group">
                    <div class="form-group">
                        <label class="setupLabel" data-fix="${data.fixed}">${data.setup}</label>
                        <div class="row">
                            <div class="col-6">
                                <label>Passing %</label>
                                <input id="passingPercentage" type="number" max="100" value="${data.percentage || ''}" placeholder="Enter a value between 0 and 100" name="passingPercentage" class="form-control form-control passingPercentage" onkeyup="if (this.value < 0) this.value = ''; if (this.value > 100) this.value = '100'; if (this.value.length > 2) this.value = this.value.slice(0, 2);">
                            </div>
                            <div class="col-6">
                                <label>Overall Percentage</label>
                                <input id="overallPercentage" type="number" max="100" value="${data.overalpercentage || ''}" placeholder="Enter a value between 0 and 100" name="overallPercentage" class="form-control form-control indiPercentage otherPercentage">
                            </div>
                        </div>
                    </div>
                </div>`;
        }

    let counter = 0;
    function calcTotal() {

        let total = 0;
        

        $('.indiPercentage').each(function() {
            const value = parseFloat($(this).val()) || 0; // Get the value, convert to float (or default to 0)
            total += value; // Add the value to the total
            TOTAL =  total; // Add the value to the total
            counter++; // Increment the counter
        });


        if(total > 100 && counter != 0){

            console.log(counter); //


            Swal.fire({
                type: 'error',
                title: 'Oops...',
                text: 'Total percentage cannot exceed 100.'
            })
        }


        $('#totalvaluepercentage').text(total);
                
    }

    function rendersubDefaultHtml(entry) {

            return `
            <label for="subject">${entry.category}</label>
            <input id="inputSubject" type="number" max="100" placeholder="Enter Passing Percentage.." name="subject" class="form-control form-control mb-1 inputSubject" value="${entry.input.passing}" data-id="${entry.id}">`;
    }


    

    function activateTest(){


        
        var dateFrom = $('#date-from').val();
        var timeFrom = $('#time-from').val();
        var dateTo = $('#date-to').val();
        var timeTo = $('#time-to').val();

        //time limit
        var hour = $('#hours').val();
        var minute = $('#minutes').val();

        
        if (!dateFrom || !timeFrom || !dateTo || !timeTo) {
            Swal.fire({
                type: 'error',
                title: 'Oops...',
                text: 'Please input all fields!'
            })
            return;
        }

        // check if the date and time inputs are valid
        if (new Date(dateFrom + 'T' + timeFrom + ':00') >= new Date(dateTo + 'T' + timeTo + ':00')) {
            Swal.fire({
                type: 'error',
                title: 'Oops...',
                text: 'Invalid date and time'
            })
            return;
        }


        // if the form inputs are valid, submit the form
        $(".activate").prop('disabled', true)
        $.ajax({
            type:'GET',
            url: '/admissiontest/activation',
            data:{
                dateFrom    : dateFrom,
                timeFrom    : timeFrom,
                dateTo      : dateTo,
                timeTo      : timeTo,
                hour        : hour,
                minute      : minute,
                testid      : TEST_ID,
            },
            success: function(data) {

                admissionDataTable();

                // hide modal
                $("#activateQuizModal").modal('hide');
            }
        })

        
    }

    function deleteSetup(id){



        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
            if (result.value == true) {
                $.ajax({
                    type:'GET',
                    url: '/admissiontest/setup/delete',
                    data:{
                        id    : id,
                    },
                    success: function(data) {

                        setupDataTable();
                            Swal.fire(
                            'Deleted!',
                            'Your file has been deleted.',
                            'success'
                            )

                    }
                })
            }
        })






    }



    //init
    admissionDataTable();
    setupDataTable();

    

    $(document).on('click','.copyButton',function(){



        var text = $(this).data("link");


        var $tempInput = $("<input>");
        $("body").append($tempInput);

        // Set the input's value to the text you want to copy
        $tempInput.val(text);

        // Select the input's value
        $tempInput.select();

        // Copy the selected text to the clipboard
        document.execCommand("copy");

        // Remove the temporary input element
        $tempInput.remove();

        // Alert the user that the text has been copied (you can also use a different feedback mechanism)
        // alert("Text has been copied to clipboard: " + text);
        Swal.fire({
            title: "Text has been copied to clipboard!",
            html: '<div class="container"><div class="row justify-content-center"><div class="col-8"><p class="text-center">' + text + '</p></div></div></div>',
            showConfirmButton: true,
            customClass: {
                popup: 'swal-bootstrap',
                content: 'swal-content'
            }
            });


    });



    $(document).on('click','#activate-test',function(){

                TEST_ID = $(this).data('id');
                $('#activateQuizModal').modal();
        

    });

    $(document).on('click','#reactivate-test',function(){

                TEST_ID = $(this).data('id');

                var data = ADMISSION_DATA.filter(x=>x.id==TEST_ID);

                $('#date-from').val(data[0].datefrom);
                $('#time-from').val(data[0].timefrom);
                $('#date-to').val(data[0].dateto);
                $('#time-to').val(data[0].timeto);
                $('#hours').val(data[0].hour);
                $('#minutes').val(data[0].minute);


                $('#activateQuizModal').modal();
        

    });


    $(document).on('click','#program_setup_button',function(){
                $('#course').prop('disabled', false);
                $('#slot').val("");
                $('#passingPercentage').val("");
                $('#course').val("");
                $('#program_set_up_form_modal').modal();

    })

    $(document).on('click','#saveSetup',function(){
                
                saveSetup();

    
    })

    $(document).on('click','.activate',function(){

                activateTest();


    });

    $(document).on('click','.delete-button',function(){
                        
                        var id = $(this).data('id');
                        deleteSetup(id);
                

    });

    $(document).on('click','.edit-button',function(){


                
                
                var course_id = $(this).data('id');

                var data = SETUP_DATA.filter(x=>x.programid==course_id);

    

                var slot = $(this).data('slot');
                $('#selectCollegeCourse').val(course_id);
                $('#slot').val(slot);
                $('#course').val(course_id);
                $('#course').prop('disabled', true);




                $('.testdiv').empty().append(`
                    <label>Test</label>
                    <input type="text" class="form-control form-control" id="programid" value="${data[0].test}" id="testid" data-id="${data[0].testid}" disabled>`
                );

                $('.programdiv').empty().append(`
                    <label>Program</label>
                    <input type="text" class="form-control form-control" value="${data[0].program}" id="programid" data-id="${data[0].programid}" disabled>`
                );
                
                getgeneralsetup(course_id).then(function(data) {
    
                    const renderHtml = data.map(entry => {

                            return renderDefaultHtml(entry);

                            
                    }).join('');
                    $('#setupDiv').empty().append(renderHtml);
                    calcTotal();
                });

                
                

                getsubjectsetup(course_id, data[0].testid).then(function(data) {

                    const renderHtml = data.map(entry => {

                            return rendersubDefaultHtml(entry);

                            
                    }).join('');
                    $('#subjectDiv').empty().append(renderHtml);
                });

                

                


            
                $('#program_set_up_form_modal').modal();
                



    });

    $(document).on('click','#addsetup',function(){
                
        var inputHTML = `    <div class="form-group">
                                <div class="form-group">
                                    <label class="setupLabel" data-fix="0" contenteditable="true"> <u>  Edit here </u> </label> <i class="fas fa-trash ml-1 trash" style="color:red" id="trash"></i>
                                    <div class="row">
                                        <div class="col-6">
                                            <label>Passing %</label>
                                            <input id="passingPercentage" type="number" max="100" placeholder="Enter a value between 0 and 100" name="passingPercentage" class="form-control form-control passingPercentage"onkeyup="if (this.value < 0) this.value = ''; if (this.value > 100) this.value = '100'; if (this.value.length > 2) this.value = this.value.slice(0, 2);">
                                        </div>
                                        <div class="col-6">
                                            <label>Overall Percentage</label>
                                            <input id="passingPercentage" type="number" max="100" placeholder="Enter a value between 0 and 100" name="passingPercentage" class="form-control form-control indiPercentage otherPercentage">
                                        </div>
                                    </div>
                                </div>
                            </div>`;


        $('#setupDiv').append(inputHTML);



    });

    $(document).on('change','#selectTest',function(){
                
        var test_id = $(this).val();

        $('#subjectDiv').empty();

        getSubject(test_id).then(function(data) {
            const renderHtml = data.map(entry => {
                return `
                    <label for="subject">${entry.category}</label>
                    <input id="inputSubject" type="number" max="100" placeholder="Enter Passing Percentage.." name="subject" class="form-control form-control mb-1 inputSubject" data-id="${entry.id}">`;
            }).join('');

            $('#subjectDiv').html(renderHtml);
        });



    });

    $(document).on('input','.indiPercentage',function(){
    
            
        
        

    });


    $(document).on('click','#saveOption',function(){

        var courseid = $('#selectOption').val();
        $("#selectcourseModal .close").click()
        getgeneralsetup(courseid).then(function(data) {
            const renderHtml = data.map(entry => {

                    return renderDefaultHtml(entry);

                    
            }).join('');
            $('#setupDiv').empty().append(renderHtml);
            calcTotal();
        });
            
        
        

    });

    $(document).on('click','.trash',function(){


        $(this).parent().parent().remove();
    
            
        
        

    });


    $(document).on('change','.otherPercentage',function(){
    
            
        var otherval =  $(this).val();
        var exampercentage = $('#examPercentage').val();
        var numleft = exampercentage - otherval;
        if(numleft > 0 ){

    
            $('#examPercentage').val(numleft);
        }else{

            Swal.fire({
                type: 'error',
                title: 'Oops...',
                text: 'Total percentage cannot subceed 0.'
            })




        }


        calcTotal();


        

    });










});




</script>
@endsection
