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
                <div class="modal-dialog modal-m">
                        <div class="modal-content">
                                <div class="modal-header pb-2 pt-2 border-0">
                                        <h4 class="modal-title">Program Set-Up Form</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span></button>
                                </div>
                                <div class="modal-body">
                                        <div class="message"></div>
                                        <div class="form-group">
                                                <label>Program</label>

                                                @php
                                                
                                                        $courses = DB::table('college_courses')
                                                                        ->where('deleted', 0)
                                                                        ->get()
                                                
                                                @endphp

                                        </div>
                                        <div class="form-group">
                                                <label>Slot Available</label>
                                                <input id="slot" type="number" name="slot" class="form-control form-control-sm" placeholder="Enter Program Available Slot">
                                        </div>
                                        <div class="form-group">
                                                <label>Passing Percentage</label>
                                                <input id="passingPercentage" type="number" max="100" placeholder="80%" name="passingPercentage" class="form-control form-control-sm">
                                        </div>
                                        
                                        <div class="row">
                                                <div class="col-md-12">
                                                <button  type="button" class="btn btn-primary btn-sm" id="saveSetup">Save <i class="fas fa-save"></i>  </button>
                                                </div>
                                        </div>
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
                                                            <th width = "30%" class="text-center">Passing Score</th>
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


    




    function admissionDataTable() {

            $.ajax({
                type:'GET',
                url: '/admissionTable',
                success: function(data) {
                    ADMISSION_DATA = data;

                    console.log(ADMISSION_DATA);
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
                    console.log(data);
                    all_setupmain();
                }
            });

        
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
                                    var buttons = `<button type="button" class="btn ${rowData.badge}" data-id="${rowData.id}" style="width: 100px; height: 40px;" id="activate-test">
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

                                    var text = '<a class="mb-0">'+ rowData.passing + '%</i></a>'
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
                                        <button class="btn btn-danger delete-button" data-id="${rowData.programid}">
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
        var passing  = $('#passingPercentage').val();
        var course   = $('#course').val();


        console.log(slot);
        console.log(passing);
        console.log(course);




        if (!slot || !passing) {
                Swal.fire({
                        type: 'error',
                        title: 'Hello...',
                        text: 'Please input all fields, thank you'
                        })
                return;
        }

        if (passing > 100 || passing < 0) {
                Swal.fire({
                        type: 'error',
                        title: 'Passing score',
                        text: 'Please input a valid passing score, thank you'
                        })
                return;
        }

        $.ajax({
                type:'GET',
                url: '/admissiontest/addSetup',
                data:{
                        passing     : passing,
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

    function activateTest(){


        
        var dateFrom = $('#date-from').val();
        var timeFrom = $('#time-from').val();
        var dateTo = $('#date-to').val();
        var timeTo = $('#time-to').val();

        //time limit
        var hour = $('#hours').val();
        var minute = $('#minutes').val();


        console.log(dateFrom);
        console.log(timeFrom);
        console.log(dateTo);
        console.log(timeTo);
        console.log(hour);
        console.log(minute);
        console.log(TEST_ID);
        
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

    $(document).on('click','.edit-button',function(){
                
                var course_id = $(this).data('id');
                var slot = $(this).data('slot');
                var passing = $(this).data('passing');
                console.log(course_id);
                $('#slot').val(slot);
                $('#passingPercentage').val(passing);
                $('#course').val(course_id);
                $('#course').prop('disabled', true);
                $('#program_set_up_form_modal').modal();
                



    });

    $(document).on('click','.delete-button',function(){
                
                var course_id = $(this).data('id');
                console.log(course_id);
                deleteSetup(course_id);
                



    });










});




</script>
@endsection
