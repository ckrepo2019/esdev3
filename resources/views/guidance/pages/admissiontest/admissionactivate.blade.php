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
        <link rel="stylesheet" href="{{asset('plugins/chart.js/Chart.css')}}"> 
        <link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">

        <style type="text/css">

                /* Custom CSS for table */
                #applicants_datatable {
                font-size: 14px; /* Adjust font size as needed */
                }

                #applicants_datatable thead th {
                font-weight: 600;
                }

                #applicants_datatable tbody tr:hover {
                background-color: #f5f5f5; /* Hover color */
                }

                #applicants_datatable tbody td {
                vertical-align: middle; /* Align content vertically in cells */
                }

                /* Custom styles for actions column */
                #applicants_datatable tbody .actions-column {
                text-align: center;
                }

                /* Example: Styling buttons inside actions column */
                #applicants_datatable tbody .actions-column button {
                padding: 5px 10px;
                border: none;
                border-radius: 3px;
                background-color: #3498db;
                color: white;
                cursor: pointer;
                transition: background-color 0.3s;
                }

                #applicants_datatable tbody .actions-column button:hover {
                background-color: #2980b9;
                }


                .headercard {
                        transition: transform 0.3s ease;
                }

                .headercard:hover {
                        transform: scale(1.05); /* Adjust the scale factor as desired */
                }

                .text-color {
                        color: red;  /* Adjust the scale factor as desired */
                }


        </style>

@endsection





@section('modalSection')


        
        <div class="modal fade" id="applicant_form_modal" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-m">
                        <div class="modal-content">
                                <div class="modal-header pb-2 pt-2 border-0">
                                        <h4 class="modal-title">Applicant Form</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span></button>
                                </div>
                                <div class="modal-body">
                                        <div class="message"></div>
                                        <div class="form-group">
                                                <label>Applicant Name</label>
                                                <input id="applicantName"  name="applicantName" class="form-control form-control-sm" placeholder="Applicant Name" onkeyup="this.value = this.value.toUpperCase();">
                                        </div>
                                        <div class="form-group">
                                                <label>Address</label>
                                                <input id="applicantaddress" placeholder="Applicant Address" name="aplicantAddress" class="form-control form-control-sm" onkeyup="this.value = this.value.toUpperCase();" >
                                        </div>
                                        <div class="form-group">
                                                <label>Desired Program</label>

                                                @php
                                                
                                                        $courses = DB::table('college_courses')
                                                                        ->where('deleted', 0)
                                                                        ->get()
                                                
                                                @endphp
                                                <select name="course" id="course" class="form-control select2">
                                                        @foreach($courses as $course)
                                                                <option selected value="{{$course->id}}">{{$course->courseabrv}} - {{$course->courseDesc}}</option>
                                                        @endforeach
                                                </select>
                                        </div>
                                        <div class="form-group">
                                                <label>SHS GWA</label>
                                                <input id="shsgrades" placeholder="Senior High General Weighted Average" name="shsgrades" class="form-control form-control-sm" >
                                        </div>
                                        <div class="form-group">
                                                <label>JHS GWA</label>
                                                <input id="jhsgrades" placeholder="Junior High General Weighted Average" name="jhsgrades" class="form-control form-control-sm" >
                                        </div>
                                        <div class="form-group">
                                                <label>Birthday</label>
                                                <input type="date" id="birthday" name="birthday" class="form-control form-control-sm" >
                                        </div>
                                </div>
                                <div class="modal-footer justify-content-end">
                                        <button  type="button" class="btn btn-primary btn-sm" id="add_applicant">Save <i class="fas fa-save"></i>  </button>
                                        
                                </div>
                        </div>
                </div>
        </div>

        <div class="modal fade" id="view_category_modal" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                                <div class="modal-header pb-2 pt-2 border-0">
                                        <h4 class="modal-title">Score by Category</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span></button>
                                </div>
                                <div class="modal-body">
                                        
                                        <div class="w-100">
                                                <canvas id="myChart" width="400" height="400"></canvas>        
                                        </div>
                                        <div class="w-100" id="categorymodaltable">
                                        </div>
                                </div>
                        </div>
                </div>
        </div>

        <div class="modal fade" id="view_result_modal" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                                <div class="modal-header border-0 bg-success">
                                        <h4 class="modal-title" id="resultText">Result</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                        </button>
                                </div>
                                <div class="modal-body">
                                        <div class="w-100" id="resultmodaltable"> </div>
                                </div>
                        </div>
                </div>
        </div>


@endsection





@section('content')
        <section class="content-header">
        <div class="container-fluid">

                <div class="row">
                        <div class="col-lg-3 col-md-6">
                                <div class="card bg-info text-white headercard">
                                <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                                <h3>{{$count}}</h3>
                                                <p class="mb-0">Applicant</p>
                                        </div>
                                        <i class="fas fa-users fa-3x"></i> <!-- Change the icon to your preferred icon -->
                                        </div>
                                </div>
                                </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                                <div class="card bg-success text-white headercard">
                                <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                                <h3>{{$passingrate}}<sup style="font-size: 20px">%</sup></h3>
                                                <p class="mb-0">Passing Rate</p>
                                        </div>
                                        <i class="fas fa-check-circle fa-3x"></i> <!-- Change the icon to your preferred icon -->
                                        </div>
                                </div>
                                </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                                <div class="card bg-warning text-dark headercard">
                                <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                                <h3>{{$incoming}}</h3>
                                                <p class="mb-0">Incoming Schedule</p>
                                        </div>
                                        <i class="fas fa-calendar-alt fa-3x"></i> <!-- Change the icon to your preferred icon -->
                                        </div>
                                </div>
                                </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                                <div class="card bg-danger text-white headercard">
                                        <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                <h3>{{$admissiontest}}</h3>
                                                <p class="mb-0">Test Created</p>
                                                </div>
                                                <i class="fas fa-clipboard-list fa-3x"></i> <!-- Change the icon to your preferred icon -->
                                        </div>
                                        </div>
                                </div>
                        </div>

                </div>



                <div class="row mb-2">
                <div class="col-sm-6">
                        <h1>Applicant</h1>
                </div>
                <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Applicant</li>
                </ol>
                </div>
                </div>
        </div>
        </section>
        <section class="content p-0">
        <div class="container-fluid">
                <div class="card shadow">
                <div class="card-body" style="font-size:.8rem !important">
                <table class="table table-hover" id="applicants_datatable">
                <thead class="thead-dark">
                        <tr>
                        <th width="5%" class="text-center">#</th>
                        <th width="15%">Applicant Name</th>
                        <th width="10%">Pooling Number</th>
                        <th width="15%">Address</th>
                        <th width="10%">Desired Program</th>
                        <th width="10%">Birthday</th>
                        <th width="10%">Result</th>
                        <th width="25%" class="text-center">Actions</th>
                        </tr>
                </thead>
                <tbody>
                        <!-- Your table rows go here -->
                </tbody>
                </table>
                </div>
        </div>
        </div>
        </section>


@endsection




@section('footerjavascript')
<script src="{{asset('plugins/datatables/jquery.dataTables.js') }}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
<script src="{{asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>
<script src="{{asset('plugins/chart.js/Chart.bundle.min.js') }}"></script>
<script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script>
<script>
                
                
        var Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
        })

        var all_applicants;
        var applicant_id;

        $(document).ready(function(){

                

                function all_applicantsmain(){


                        $.ajax({
                                type:'GET',
                                url: '/applicants/get',
                                success:function(data) {
                                        all_applicants = data
                                        applicants_datatable();

                                }
                        })


                }


                function applicants_datatable(){


                        var table = $("#applicants_datatable").DataTable({
                                destroy: true,
                                data:all_applicants,
                                lengthChange : false,
                                stateSave: true,
                                autoWidth: false,
                                sortable: true,
                                columns: [
                                        { "data": null },
                                        { "data": 'applicantname' },
                                        { "data": 'poolingnumber' },
                                        { "data": 'address' },
                                        { "data": null },
                                        { "data": 'bdate' },
                                        { "data": null },
                                        { "data": "search" },
                                ],
                                columnDefs: [
                                {
                                'targets': 0,
                                'orderable': false, 
                                'createdCell':  function (td, cellData, rowData, row, col) {
                                        var text = '<a class="mb-0">'+(row+1)+'</a>'
                                        $(td)[0].innerHTML =  text
                                        $(td).addClass('align-middle')
                                        $(td).addClass('text-center')
                                }
                                },
                                {
                                'targets': 1,
                                'orderable': true,
                                'createdCell': function (td, cellData, rowData, row, col) {
                                var text = '<a class="mb-0">' + rowData.applicantname + ' ';
                                
                                if (rowData.fileurl) {
                                text += '<a href="' + rowData.file + '" target="_blank"> View Documents </a>';
                                } else {
                                text += ' </a>';
                                }

                                $(td)[0].innerHTML = text;
                                $(td).addClass('align-middle');
                                $(td).addClass('text-center');
                                }
                                },
                                {
                                'targets': 4,
                                'orderable': false, 
                                'createdCell':  function (td, cellData, rowData, row, col) {
                                // Assuming rowData.program and rowData.status are defined properly
                                var textToInsert = rowData.program;
                                var statusClass = '';

                                if (rowData.status === 'Passed') {
                                statusClass = 'badge badge-success';
                                } else if (rowData.status === 'Failed') {
                                statusClass = 'badge badge-danger';
                                }

                                if (rowData.status) {
                                textToInsert += '&nbsp; <span class="' + statusClass + '"> ' + rowData.status + '</span>';
                                }

                                // Use jQuery to set the text inside the <td> element
                                $(td).empty().append($('<div>').html(textToInsert));


                                }
                                },
                                {
                                'targets': 6,
                                'orderable': false, 
                                'createdCell':  function (td, cellData, rowData, row, col) {

                                        if(rowData.teststatus != null && rowData.teststatus == 1){

                                                var buttons = '<a href="/admission/viewtestresponse/' + rowData.recordid + '/' + rowData.admissiontestid + '" class="view-response" data-recordid="'+rowData.recordid+'" data-testid="'+rowData.admissiontestid+'">View response</i></a>';
                                                $(td)[0].innerHTML =  buttons

                                        }else{
                                                // $(td).html(rowData.totalscore
                                                // ? rowData.totalscore + '/' + rowData.maxpoints + ' (' + rowData.percentage + '%) <button class="btn btn-link" data-recordid="'+rowData.recordid+'" data-testid="'+rowData.admissiontestid+'" id="view_category_button"><i class="fas fa-columns"></i></button>  '
                                                // : 'Result Not Available');
                                                $(td).html(rowData.totalscore !== null && rowData.totalscore !== undefined
                                                ? rowData.totalscore + '/' + rowData.maxpoints + ' (' + rowData.percentage + '%) <button class="btn btn-link" data-recordid="'+rowData.recordid+'" data-testid="'+rowData.admissiontestid+'" id="view_category_button"><i class="fas fa-columns"></i></button>'
                                                : 'Result Not Available'
                                                );



                                        }
                                }
                                },
                                {
                                'targets': 7,
                                'orderable': false, 
                                'createdCell':  function (td, cellData, rowData, row, col) {
                                        var buttons = '<button class="btn btn-primary edit-button" data-recordid="'+rowData.recordid+'" data-id="'+ rowData.id+'" data-testid="'+rowData.admissiontestid+'" ><i class="far fa-edit"></i> Edit</button>  ';
                                        buttons += '<button class="btn btn-danger delete-button" data-recordid="'+rowData.recordid+'" data-id="'+ rowData.id+'" data-testid="'+rowData.admissiontestid+'" ><i class="fas fa-trash"></i> Delete </button>  ';
                                        
                                        if(rowData.eligible != 0){

                                                buttons += '<button class="btn btn-success eligible-button" style="width: 115px; height: 40px;" data-recordid="'+rowData.recordid+'" data-id="'+ rowData.id+'" data-testid="'+rowData.admissiontestid+'"  > <i class="fas fa-toggle-on"></i> Eligible </button>  ';
                                        
                                        }else{

                                                buttons += '<button class="btn btn-danger eligible-button" style="width: 115px; height: 40px;" data-recordid="'+rowData.recordid+'" data-id="'+ rowData.id+'" data-testid="'+rowData.admissiontestid+'" > <i class="fas fa-toggle-off"></i> Ineligible </button>  ';

                                        }

                                        if(rowData.totalscore != null){
                                                buttons += '<button class="btn btn-warning" data-id="'+rowData.id+'" data-progid="'+rowData.programid+'" data-recordid="'+rowData.recordid+'" data-testid="'+rowData.admissiontestid+'" data-name="'+rowData.applicantname+'"  id="view_result_button"><i class="fas fa-flag"></i> Result </button>';
                                        }
                                        
                                        
                                        $(td)[0].innerHTML =  buttons


                                
                                }
                                },


                                ],
                        });

                        var label_text = $($('#applicants_datatable_wrapper')[0].children[0])[0].children[0]
                        $(label_text)[0].innerHTML = '<button class="btn btn-sm btn-primary" title="Applicant" id="add_applicant_button"> <i class="fas fa-plus"></i> Add Applicant</button>'


                        table.rows().every(function (rowIdx, tableLoop, rowLoop) {
                                var rowData = this.data();
                                if (rowData.eligible === 0) {
                                        $(this.node()).addClass('text-color'); // Apply 'bg-danger' class to the row
                                }
                        });

                }


                function getscorecategory(recordid, testid){

                        $.ajax({
                                type:'GET',
                                url: '/applicants/score/category',
                                data : {
                                        recordid : recordid,
                                        testid : testid
                                },
                                success:function(data) {
                                        rendertablecategory(data);

                                }
                        })
                }

                function modalcontent(id, progid, testid){

                        $.ajax({
                                type:'GET',
                                url: '/applicants/result',
                                data : {
                                        id : id,
                                        progid : progid,
                                        testid : testid
                                },
                                success:function(data) {
                        
                                        $('#resultmodaltable').empty().append(data);

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
                                url: '/applicants/delete',
                                data:{
                                        id    : id,
                                },
                                success: function(data) {

                                        all_applicantsmain()
                                        Swal.fire(
                                        'Deleted!',
                                        'The Applicant has been deleted',
                                        'success'
                                        )

                                }
                                })
                        }
                        })






                }


                function eligibleSetup(id){



                        Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Mark as Ineligible'
                        }).then((result) => {
                        if (result.value == true) {
                                $.ajax({
                                type:'GET',
                                url: '/applicants/eligibilty/',
                                data:{
                                        id    : id,
                                },
                                success: function(data) {

                                        all_applicantsmain()
                                        Swal.fire(
                                        'Ineligible',
                                        'The Applicant has been updated',
                                        'success'
                                        )

                                }
                                })
                        }
                        })






                }

                var ctx = document.getElementById('myChart').getContext('2d');
                var myChart = null; // Define the chart variable

                function rendertablecategory(response) {

                        var subjects = [];
                        var scores = [];

                        subjects.splice(0, subjects.length);
                        scores.splice(0, scores.length);

                        for (var i = 0; i < response.length; i++) {
                                subjects.push(response[i].category);
                                scores.push(response[i].score / response[i].sum * 100); // Pushing scores as percentages
                        }

                        function getRandomColor() {
                                var letters = '0123456789ABCDEF';
                                var color = '#';
                                for (var i = 0; i < 6; i++) {
                                        color += letters[Math.floor(Math.random() * 16)];
                                }
                                return color + '80'; // Adding alpha channel for transparency
                        }

                        var backgroundColors = [];
                        var borderColors = [];
                        for (var i = 0; i < subjects.length; i++) {
                                var randomColor = getRandomColor();
                                backgroundColors.push(randomColor);
                                borderColors.push(randomColor);
                        }

                        if (myChart) {
                                // Update the existing chart with new data
                                myChart.data.labels = subjects;
                                myChart.data.datasets[0].data = scores;
                                myChart.data.datasets[0].backgroundColor = backgroundColors.slice(); // Set background colors
                                myChart.data.datasets[0].borderColor = borderColors.slice(); // Set border colors
                                myChart.update(); // Update the chart
                        } else {
                                // Create the chart if it doesn't exist
                                myChart = new Chart(ctx, {
                                type: 'bar',
                                data: {
                                        labels: subjects,
                                        datasets: [{
                                        label: 'Scores',
                                        data: scores,
                                        backgroundColor: backgroundColors,
                                        borderColor: borderColors,
                                        borderWidth: 1
                                        }]
                                },
                                options: {
                                        scales: {
                                        y: {
                                                beginAtZero: true,
                                                max: 100
                                        }
                                        }
                                }
                                });
                        }
                }





                //init
                all_applicantsmain();
                

                $(document).on('click','#add_applicant_button',function(){
                        $('.modal-title').text('Applicant Form');
                        $('#add_applicant').text("Save");
                        $('#add_applicant').attr('id', 'add_applicant');
                        $('.form-control').val("");
                        $('#applicant_form_modal').modal()
                })

                $(document).on('click','#view_category_button',function(){

                        var recordid = $(this).data('recordid');
                        var testid = $(this).data('testid');

                        getscorecategory(recordid, testid);

                        $('#view_category_modal').modal()
                })

                $(document).on('click','#view_result_button',function(){

                        var id = $(this).data('id');

                        applicant_id = id;
                        var progid = $(this).data('progid');
                        var testid = $(this).data('testid');
                        var name = $(this).data('name');
                        applicant_id = id;
                        modalcontent(id, progid, testid);
                        $('#resultText').text(name);
                


                        $('#view_result_modal').modal()
                })

                $(document).on('click','.delete-button',function(){
                        
                        var applicant_id = $(this).data('id');
                        deleteSetup(applicant_id);
                

                });

                $(document).on('click','.eligible-button',function(){
                        
                        var applicant_id = $(this).data('id');
                        eligibleSetup(applicant_id);
                

                });

                $(document).on('click','#saveInput',function(){
                        
                        $('.inputfield').each(function() {
                                const value = $(this).val();
                                const id = $(this).data('id');
        
                                
                                $.ajax({
                                        type:'GET',
                                        url: '/applicants/setup/input',
                                        data:{
                                                result        : value,
                                                setupid       : id,
                                                applicantid   : applicant_id,
                                        },
                                        success: function(data) {

                                                Toast.fire({
                                                        type: 'success',
                                                        title: 'Success',
                                                        timer: 2000,
                                                })
                                                $("#view_result_modal .close").click()

                                        }
                                })
                        });

                


                
                });

                $(document).on('click','.edit-button',function(){
                        
                        applicant_id = $(this).data('id');

                        var data = all_applicants.filter(x=>x.id==applicant_id);

                        $('#applicantName').val(data[0].applicantname);
                        $('#applicantaddress').val(data[0].address);
                        $('#course').val(data[0].desiredprogramid);
                        $('#birthday').val(data[0].birthday);
                        $('#shsgrades').val(data[0].shsgrades);
                        $('#shsgrades').val(data[0].shsgrades);
                        $('#jhsgrades').val(data[0].jhsgrades);
                        
                        $('.modal-title').text(data[0].applicantname);
                        $('#add_applicant').text("Update");
                        $('#add_applicant').attr('id', 'update_applicant');
                        

                        $('#applicant_form_modal').modal()
                        

                });

                $(document).on('click','#add_applicant',function(){
                        // $('#applicant_form_modal').modal()
                        var name     = $('#applicantName').val();
                        var address  = $('#applicantaddress').val();
                        var course   = $('#course').val();
                        var birthday = $('#birthday').val();
                        var shsgrades = $('#shsgrades').val();
                        var jhsgrades = $('#jhsgrades').val();


                        $('#add_applicant').prop('disabled',true);


                        if (!name || !address || !course || !birthday || !shsgrades || !jhsgrades) {
                                Swal.fire({
                                        type: 'error',
                                        title: 'Hello...',
                                        text: 'Please input all fields, thank you'
                                        })
                                $('#add_applicant').prop('disabled',false);
                                return;
                        }

                        $.ajax({
                                type:'GET',
                                url: '/admissiontest/addApplicant',
                                data:{
                                        name        : name,
                                        address     : address,
                                        course      : course,
                                        birthday    : birthday,
                                        shsgrades   : shsgrades,
                                        jhsgrades   : jhsgrades,
                                },
                                success: function(data) {
                                        $("#applicant_form_modal .close").click()
                                        $('#add_applicant').prop('disabled',false);
                                        all_applicantsmain();
                                        Toast.fire({
                                                type: 'success',
                                                title: 'Applicant Added successfully',
                                                timer: 2000,
                                        })

                                }
                        })


                })

                $(document).on('click','#update_applicant',function(){
        
                        var name     = $('#applicantName').val();
                        var address  = $('#applicantaddress').val();
                        var course   = $('#course').val();
                        var birthday = $('#birthday').val();
                        var shsgrades = $('#shsgrades').val();



                        $('#add_applicant').prop('disabled',true);


                        if (!name || !address || !course || !birthday || !shsgrades) {
                                Swal.fire({
                                        type: 'error',
                                        title: 'Hello...',
                                        text: 'Please input all fields, thank you'
                                        })
                                return;
                        }

                        $.ajax({
                                type:'GET',
                                url: '/admissiontest/updateApplicant',
                                data:{
                                        id          : applicant_id,
                                        name        : name,
                                        address     : address,
                                        course      : course,
                                        birthday    : birthday,
                                        shsgrades   : shsgrades,
                                },
                                success: function(data) {
                                        $("#applicant_form_modal .close").click()
                                        all_applicantsmain();
                                        Toast.fire({
                                                type: 'success',
                                                title: 'Applicant Update successfully',
                                                timer: 2000,
                                        })

                                }
                        })


                })

                $(document).on('click','.card-header',function(){
                        var indicator = $(this).find('.collapse-indicator');
                        indicator.text() === '+' ? indicator.text('-') : indicator.text('+');
                });


                $(document).on('click','#saveChangesBtn',function(){

                        var programid = $('#selectCollegeCourse').val();
                        var remarks =   $('#remarks').val();
                        var status =   $('#selectStatus').val();


                        var formData = new FormData();
                        formData.append('applicantid', applicant_id);
                        formData.append('programid', programid );
                        formData.append('remarks', remarks);
                        formData.append('status', status);


                        var csrfToken = $('meta[name="csrf-token"]').attr('content');
                        

                                                        // Set the CSRF token in the request headers
                        $.ajaxSetup({
                                headers: {
                                'X-CSRF-TOKEN': csrfToken
                                }
                        });

                        $.ajax({
                                url: "/admissiontest/applicant/accept",
                                type: 'POST', // Use lowercase 'type' instead of 'TYPE'
                                data: formData,
                                processData: false,
                                contentType: false,
                                success: function(response) {
                                        if(response == 0){

                                                Toast.fire({
                                                        type: 'success',
                                                        title: 'All the changes have been saved'
                                                });

                                        }else{

                                                Toast.fire({
                                                        type: 'success',
                                                        title: 'All the changes have been updated'
                                                });


                                        }

                                        $("#view_result_modal .close").click()
                                },
                                error: function(xhr) {
                                // Handle error here
                                        Toast.fire({
                                                type: 'error',
                                                title: 'Something went wrong'
                                        });
                                }
                        });

                });


        });



</script>


@endsection


