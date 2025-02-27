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


        </style>

@endsection




@section('modalSection')

        <div class="modal fade" id="acceptApplicantModal" tabindex="-1" role="dialog" aria-labelledby="acceptApplicantModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                        <div class="modal-content">
                        <div class="modal-header">
                                <h5 class="modal-title" id="acceptApplicantModalLabel">Accept Applicant</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                        </div>
                        <div class="modal-body">
                                <div class="card">
                                <div class="card-body">
                                        <div class="form-group">
                                        <label for="courseName">Course Name</label>
                                        @php
                                                
                                                        $courses = DB::table('college_courses')
                                                                        ->where('deleted', 0)
                                                                        ->get()
                                                
                                        @endphp
                                        <select name="course" id="selectCollegeCourse" class="form-control select2">
                                                @foreach($courses as $course)
                                                        <option selected value="{{$course->id}}">{{$course->courseabrv}} - {{$course->courseDesc}}</option>
                                                @endforeach
                                        </select>
                                        </div>
                                        <div class="form-group">
                                        <label for="courseName">Status</label>
                                        <select class="form-control form-control-alt select2" id="selectStatus"> 
                                                <option value="0" > Permanent </option>
                                                <option value="1" > Probationary </option>
                                        </select>
                                        </div>
                                        <div class="form-group">
                                        <label for="courseDescription">Remarks</label>
                                        <textarea class="form-control" id="remarks" placeholder="Enter course remarks" rows="3"></textarea>
                                        </div>
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer">
                                        <button type="button" class="btn btn-primary" data-id="0" id="saveChangesBtn">Save Changes</button>
                                </div>
                                </div>
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
                                                <h3>12</h3>
                                                <p class="mb-0">Accepted Applicant</p>
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
                                                <h3>12<sup style="font-size: 20px">%</sup></h3>
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
                                                <h3>12</h3>
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
                                                <h3>12</h3>
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


                <section class="content p-0">
                    <div class="container-fluid">
                        <div class="card shadow">
                            <div class="card-body" style="font-size:.8rem !important">
                                <table class="table table-hover" id="accepted_datatable">
                                <thead class="thead-dark">
                                        <tr>
                                        <th width="5%" class="text-center">#</th>
                                        <th width="20%">Applicant Name</th>
                                        <th width="20%">Program</th>
                                        <th width="15%">Pooling Number</th>
                                        <th width="15%">Address</th>
                                        <th width="15%">Date Accepted</th>
                                        <th width="15%">Remarks</th>
                                        <th width="15%">Status</th>
                                        <th width="10%" class="text-center">Actions</th>
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
    </section>



@endsection


@section('footerjavascript')
<script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('plugins/sweetalert2/sweetalert2.all.min.js')}}"></script>
<script src="{{asset('plugins/sweetalert2/sweetalert2.all.min.js')}}"></script>
<script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script>

<script>



    var Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
                })


    var accepted_applicants;


    $(document).ready(function(){


        accepted_applicant();


        function accepted_applicant(){


                $.ajax({
                        type:'GET',
                        url: '/admissiontest/applicant/getaccepted',
                        success:function(data) {
                                accepted_applicants = data
                                renderDatable(data);

                        }
                })


        }

        function renderDatable(data) {
                $("#accepted_datatable").DataTable({
                        destroy: true,
                        data: data,
                        lengthChange: false,
                        stateSave: true,
                        autoWidth: false,
                        columns: [
                        { data: null, render: function(data, type, row, meta) { return meta.row + 1; } }, // Index column
                        { data: 'applicantname' }, // Assuming 'applicantname' is a property in the object
                        { data: 'courseDesc' }, // Assuming 'courseDesc' is a property in the object
                        { data: 'poolingnumber' }, // Assuming 'poolingnumber' is a property in the object
                        { data: 'address' }, // Assuming 'address' is a property in the object
                        { data: 'date' }, // Assuming 'date' is a property in the object
                        { data: 'remarks' }, // Assuming 'date' is a property in the object
                        { data: 'status' }, // Assuming 'date' is a property in the object
                        { data: null } // Assuming 'date' is a property in the object
                        ],
                        columnDefs: [
                        {
                                targets: -1, // Last column index
                                orderable: false, // Disable sorting on this column
                                render: function(data, type, row) {
                                return `<button class="btn btn-link edit-button" data-recordid="${row.recordid}" data-id="${row.id}" data-testid="${row.admissiontestid}"><i class="far fa-edit"></i></button>
                                        <button class="btn btn-link delete-button" data-recordid="${row.recordid}" data-id="${row.id}" data-testid="${row.admissiontestid}"><i class="fas fa-trash" style="color: #f40606;"></i></button>`;
                                }
                        },
                        {
                                'targets': -2, // Last column index
                                'orderable': false, // Disable sorting on this column
                                'createdCell': function(td, data, type, row, rowData) {
                                        if(data == 'Permanent'){

                                                $(td).addClass('bg-primary')
                                                $(td).addClass('text-center')
                                                $(td).addClass('align-middle')

                                        }else{
                                                
                                                $(td).addClass('bg-warning')
                                                $(td).addClass('text-center')
                                                $(td).addClass('align-middle')

                                        }
                                
                                }
                        },
                        {
                        targets: '_all', // Apply to all columns
                        createdCell: function(td, data, row, col) {
                                $(td).addClass('align-middle');
                        }
                        },
                        ]
                });

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
                                url: '/acceptedapplicants/delete',
                                data:{
                                        id    : id,
                                },
                                success: function(data) {

                                        accepted_applicant();
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


                $(document).on('click','.edit-button',function(){
                        
                        applicant_id = $(this).data('id');

                        var data = accepted_applicants.filter(x=>x.id==applicant_id);

                        

                        $('#selectStatus').val(data[0].statusid).trigger('change');
                        $('#selectCollegeCourse').val(data[0].programid).trigger('change');
                        $('#remarks').val(data[0].remarks);


                        $('#acceptApplicantModal').modal()
                        

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
                                        accepted_applicant();
                                        $("#acceptApplicantModal .close").click()
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
                

                $(document).on('click','.delete-button',function(){
                        
                        var applicant_id = $(this).data('id');
                        deleteSetup(applicant_id);
                

                });




    });






</script>


@endsection


