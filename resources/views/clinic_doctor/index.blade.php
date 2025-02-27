@extends('clinic_doctor.layouts.app')

<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
{{-- <style>
    .dataTable                  { font-size:80%; }
    .tschoolschedule .card-body { height:250px; }
    .tschoolcalendar            { font-size: 12px; }
    .tschoolcalendar .card-body { height: 250px; overflow-x: scroll; }
    .teacherd ul li a           { color: #fff; -webkit-transition: .3s; }
    .teacherd ul li             { -webkit-transition: .3s; border-radius: 5px; background: rgba(173, 177, 173, 0.3); margin-left: 2px; }
    .sf5                        { background: rgba(173, 177, 173, 0.3)!important; border: none!important; }
    .sf5menu a:hover            { background-color: rgba(173, 177, 173, 0.3)!important; }
    .teacherd ul li:hover       { transition: .3s; border-radius: 5px; padding: none; margin: none; }

    .small-box                  { box-shadow: 1px 2px 2px #001831c9; overflow-y: auto scroll; }

    .small-box h5               { text-shadow: 1px 1px 2px gray; }
    .time                       { font-size: 12px; text-align: center; justify-content: center}
        input[type="checkbox"]::before {
        /* ...existing styles */
        content: "";
        width: 0.65em;
        height: 0.65em;
        transform: scale(0);
        transition: 120ms transform ease-in-out;
        box-shadow: inset 1em 1em var(--form-control-color);
        }
            

    img{
        border-radius: unset !important;
    }

    


    #hero {
        width: 105vw;
        height: 105vh;
        position: relative;
        padding: 200px 0 200px 0;
    }

    #hero .hero-img {
    margin-top: -10%;
    width: 70%;
    float: right;
    }

    .img-fluid {
    max-width: 70%;
    height: auto;
    }


    #hero .hero-info {
        width: 50%;
        float: left;
    }

    #hero .hero-info h2 {
    color: #fff;
    margin-bottom: 40px;
    font-size: 28px;
    font-weight: 700;
    }

    #hero .hero-info h2 span {
    color: #74b5fc;
    }


    #hero .hero-info .btn-get-started,
    #hero .hero-info .btn-services {
    font-family: "Montserrat", sans-serif;
    font-size: 14px;
    font-weight: 600;
    letter-spacing: 1px;
    display: inline-block;
    padding: 10px 32px;
    border-radius: 50px;
    transition: 0.5s;
    margin: 0 20px 20px 0;
    color: #fff;
    }

    #hero .hero-info .btn-get-started {
    background: #007bff;
    border: 2px solid #007bff;
    color: #fff;
    }

    #hero .hero-info .btn-get-started:hover {
    background: none;
    border-color: #fff;
    color: #fff;
    }

    #hero .hero-info .btn-services {
    border: 2px solid #fff;
    }

    #hero .hero-info .btn-services:hover {
    background: #007bff;
    border-color: #007bff;
    color: #fff;
    }

    #appointment{
        border: 0px;
        border-color: white;
    }

    #appointment .card-title{
        font-family: "Montserrat", sans-serif;
        font-size: 14px;
        font-weight: 600;
        letter-spacing: 1px;
        color: #fff;;
    }

    #appointment .todo-list{
        font-family: "Montserrat", sans-serif;
        font-size: 14px;
        font-weight: 300;
        border: 2px solid #fff;

    }

</style> --}}


<style>

    .content{
        background: url("dist/img/hero-bg.png") center bottom no-repeat;
        background-size: cover;
    }

    #hero .hero-info h2 {
        color: #fff;
        margin-bottom: 40px;
        font-size: 28px;
        font-weight: 700;
    }

    #hero .hero-info h2 span {
        color: #74b5fc;
    }

    #appointment .todo-list{
        box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15);
        font-family: "Montserrat", sans-serif;
        font-size: 14px;
        font-weight: 300;
        border: 2px solid #fff;

    }



</style>
@section('content')
@php

    use \Carbon\Carbon;
    $now = Carbon::now();
    $comparedDate = $now->toDateString();

    $appointments = DB::table('clinic_appointments')
            ->select('clinic_appointments.*','users.type','usertype.utype')
            ->join('users','clinic_appointments.userid','=','users.id')
            ->join('usertype','users.type','=','usertype.id')
            ->where('clinic_appointments.adate', date('Y-m-d'))
            ->where('clinic_appointments.admitted','1')
            //->where('clinic_appointments.docid',DB::table('teacher')->where('userid', auth()->user()->id)->where('deleted','0')->first()->id)
            ->get();
        $name_showlast = "";
        $name = $name_showlast;

        if(count($appointments)>0)
        {
            foreach($appointments as $appointment)
            {
            
                $info = DB::table('users')
                    ->where('ID', $appointment->userid)
                    ->first();

                $name_showlast = "";

                $name_showlast.=$info->name.' ';

    

            $appointment->name_showlast = $name_showlast;
            $appointedname = '';
            $name = $name_showlast;
            }
        }
        

@endphp


<div class="content-header align-items-center">
        <div class="container-fluid"  id="hero">
            <div class="row justify-content-center">
                <div class="col-md-7 col-sm-12">
                    <div class="row">
                        <div class="col-md-6 col-sm-12 mt-5" >
                            <div class="row">
                                <div class="col-md-12 col-sm-12 hero-info">
                                    <h2>Good day, <span>Dr. {{auth()->user()->name}}</span><br></h2>
                                </div>
                                <div class="col-md-6 col-sm-12 hero-info text-right">
                                    <a href="/clinic/complaints/index" class="btn btn-primary scrollto text-white w-100 mt-2" style="border-radius: 20px;">View Complaints</a>
                                </div>
                                <div class="col-md-6 col-sm-12 hero-info text-left">
                                    <a href="/clinic/doctor/availablity/index" class="btn btn-link scrollto text-white border-white w-100 mt-2" style="border-radius: 20px;">Manage Schedule</a>
                                </div>
                                <div class="col-md-12 mt-2">
                                    <div class="container" id= "appointment">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="card shadow">
                                                    <div class="card-header bg-primary">
                                                        <h3 class="card-title">
                                                            Appointments for Today
                                                        </h3>
                                                    </div>
                                                    @if(count($appointments) > 0)
                                                    <div class="card-body">
                                                        <ul class="todo-list" data-widget="todo-list">
                                                            @foreach($appointments as $appointment)
                                                            <li class="list-group-item align-items-center">
                                                                <div class="icheck-primary d-inline">
                                                                    <input type="checkbox" value="{{$appointment->id}}" name="appointment" class="check-appointment form-check-input" id="todoCheckAppointment{{$appointment->id}}" @if($appointment->label == 1) checked @endif>
                                                                    <label for="todoCheckAppointment{{$appointment->id}}"></label>
                                                                </div>
                                                                <span class="text-dark name">{{$appointment->name_showlast}}</span>
                                                                @if($appointment->atime != null)
                                                                    <span class="text-dark time"><i style = "color: #FF6961;" class=" far fa-clock mr-1"></i>{{date('M d, Y h:i A', strtotime($appointment->atime))}}</span>
                                                                @endif
                                                                <div style="float:right"> 
                                                                    <button type="button" class="btn btn-sm btn-primary edit " data-id="{{$appointment->id}}">
                                                                            <i class="fa fa-info-circle"></i>
                                                                    </button>
                                                                </div>
                                                            </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                    @else
                                                    <div class="row">
                                                        <div class="col-md-12 text-center p-5 h6">No Appointments for Today</div>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 d-flex align-items-center">
                            <div class="w-100">
                                <img src='dist/img/features-section.svg' class="img-fluid">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    

    


    

        <div class="modal" id="modal-viewAppointment">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <div class="modal-header" style = "background-color: #0275d8; color:#fff">
                <h4 class="modal-title">Appointment Details</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="viewAppointment">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <section class="modal-content">
                    <div class="container-fluid">
                        <div class="card shadow">
                        <div class="card-body" style="font-size:.8rem !important">
                            <div class="d-flex align-items-center">
                                <h5 id = "name1"><strong></strong></h5>
                            </div>
                            
                            <table class="table table-striped table-hovered table-hover " style = "font-size:18px" id="description_datatable">
                            <thead>
                                <tr>
                                <th width="33%">Date Admitted</th>
                                <th width="33%" >Time Slot</th>
                                <th width="33%" >Description</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            </table>
                        </div>
                    </div>
                    
                    </div>
                </section>
                <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal" id="btn-close-viewAppointment">Close</button>
                <button type="button" class="btn btn-primary" id="btn-view-records">Medical History</button>
            </div>
                </div>
     

            </div>

            
            </div>
            </div>

            
        <div class="modal" id="modal-viewMedicalrecords">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <div class="modal-header" style = "background-color: #0275d8; color:#fff">
                <h4 class="modal-title">Medical History</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="viewAppointment">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <section class="modal-content">
                    <div class="container-fluid">
							
								<div class="card-body" style="font-size:.8rem !important">
									<h5><strong>Appointment history<strong></h6>
									<table class="table table-sm table-striped table-bordered table-hovered table-hover " style = "font-size: 16px" id="medicals_datatable">
										<thead>
											<tr>
											<th width="10%">#</th>
											<th width="30%">Patient</th>
											<th width="25%" class="p-0 text-center align-middle">Details</th>
											<th width="20%">Status</th>
											<th width="15%">Physician</th>
											</tr>
										</thead>
										<tbody></tbody>
									</table>
									<br>
									<br>
									<h5><strong>Complaint history<strong></h6>
									<table class="table table-sm table-striped table-bordered table-hovered table-hover " style = "font-size: 16px" id="complaints_datatable">
									<thead>
										<tr>
										<th width="10%">#</th>
										<th width="30%">Date</th>
										<th width="25%" class="p-0 text-center align-middle">Complaint</th>
										<th width="20%">Action Taken</th>
										<th width="15%">Medicine given</th>
										</tr>
									</thead>
									<tbody>
									</tbody>
									</table>
									<br>
									<br>
									<h5><strong>Health History</strong></h5>
										
										
											<div class="card-body" style="font-size:.8rem !important">
											<div class="d-flex align-items-center">
												<h4 id = "names"><strong></strong></h4>
											</div>
											<h6>Hospitalizations / Surgeries: </h6>
											<p id="HS"></p>
											<h6>Family History of Illness:</h6>
											<p id="FH"></p>
											<h6>Smoking:&nbsp;<strong id="Smoking"></strong></h6>
											<h6>Age starting smoking:&nbsp;<strong id="age1"></strong></h6>
											<h6>Age Quit smoking:&nbsp;<strong id="age2"></strong></h6>
											<h6>Packs per day:&nbsp;<strong id="pack"></strong></h6>
											<h6>Alcohol:&nbsp;<strong id="alcohol"></strong></h6>
											<h6>Average Drink per day:&nbsp;<strong id="ave"></strong></h6>
											<h6>Current Medications:</h6>
											<p id="CM"></p>
											<h6>Allergies:</h6>
											<p id="allergies"></p>
											


										
										</div>
									
						
						</div>
					</section>
                </div>

            </div>
            </div>
            </div>

@endsection
@section('footerjavascript')


<script>
        function medicals_datatable(){
            key = 0;
            $("#medicals_datatable").DataTable({
               // destroy: true,
                    destroy: true,
                    lengthChange : false,
                    stateSave: true,
                    searching: false,
                    responsive: true,
                    data:all_history,
        
                columns: [
                    { "data": null },
                    {  "data": null},
                    { "data": null },
                    { "data": null },
                    { "data": null },

            ],
            columnDefs: [
                    
					{
						'targets': 0,
						'orderable': false, 
						'createdCell':  function (td, cellData, rowData, row, col) {
								$(td)[0].innerHTML = key +=1
								$(td).addClass('align-middle')   
								$(td).addClass('text-center')   
						}
                    },
                    {
						'targets': 1,
						'orderable': false, 
						'createdCell':  function (td, cellData, rowData, row, col) {
								console.log(name)
								var date2 =  new Date(Date.parse(rowData.createddatetime));
								markdate = date2.toLocaleDateString("en-US", {month: "long", year: "numeric", day: "numeric", hour: "numeric", minute: "numeric"});
								$(td)[0].innerHTML = name +'<br/>' + '<b style="font-size: .7rem !important">' + "Submitted: " + markdate + '</b>'
								$(td).addClass('align-middle')   
						}
                    },
                    {
						'targets': 2,
						'orderable': false, 
						'createdCell':  function (td, cellData, rowData, row, col) {
								$(td)[0].innerHTML = rowData.description
							$(td).addClass('align-middle')
						}
                    },
                    {
						'targets': 3,
						'orderable': false, 
						'createdCell':  function (td, cellData, rowData, row, col) {
								if (rowData.label == 1){
								var date3 =  new Date(Date.parse(rowData.labeldatetime))
								markdate2 = date3.toLocaleDateString("en-US", {month: "long", year: "numeric", day: "numeric", hour: "numeric", minute: "numeric"});
								$(td)[0].innerHTML = "Marked as Done" + '<br/>' + '<b>' + markdate2 + '</b>' 
								}else{
								$(td)[0].innerHTML = "Incomplete"
								$(td).addClass('align-middle')    
								} 
						}
                    },
						{
						
						'targets': 4,
						'orderable': false, 
						'createdCell':  function (td, cellData, rowData, row, col) {
								$(td)[0].innerHTML = 'Dr.'+ rowData.firstname  + " "+  rowData.lastname
								$(td).addClass('align-middle')   
						}
                    },
                ],
            });
        }

        function complaints(){
        key = 0;
                $("#complaints_datatable").DataTable({
                // destroy: true,
                        destroy: true,
                        lengthChange : false,
                        stateSave: true,
                        searching: false,
                        responsive: true,
                        data:all_complaints,
            
                    columns: [
                        { "data": null },
                        {  "data": null},
                        { "data": null },
                        { "data": null },
                        { "data": null },

                ],
                columnDefs: [
                        
                        {
                        'targets': 0,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                                $(td)[0].innerHTML = key +=1
                        }
                        },
                        {
                        'targets': 1,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                                var date2 =  new Date(Date.parse(rowData.cdate));
                                markdate = date2.toLocaleDateString("en-US", {month: "long", day: "numeric",  year: "numeric"});
                                $(td)[0].innerHTML = markdate
                        }
                        },
                        {

                        'targets': 2,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                                $(td)[0].innerHTML = rowData.description
                        }
                        },
                        {
                        'targets': 3,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                                $(td)[0].innerHTML = rowData.actiontaken
                        }
                        },
                        {
                        
                        'targets': 4,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                                $(td)[0].innerHTML = rowData.med 



                        }
                        },
                    ],
                });
            }

	var name;
    $(document).ready(function(){
    
        $('#btn-createapp').on('click', function(){
            window.open("/clinic/appointment/viewdetail");
        })

        var description =[];
        var date2 = "";
       
        $('.edit').on('click', function(){
            var appointmentid   = $(this).attr('data-id');
            
            $('#modal-viewAppointment').modal('show');
            var modalBody = $('#myModal .modal-body');
            var data =<?php echo json_encode($appointments); ?>;


            
            var appointmentdata = data.filter(x=>x.id==appointmentid);
            var date1 = new Date(Date.parse(appointmentdata[0].admitteddatetime));

            $('#btn-view-records').attr('data-id', appointmentdata[0].userid);

            name = appointmentdata[0].name_showlast;

            var NameElement = document.getElementById("name1");
            NameElement.innerHTML = appointmentdata[0].name_showlast;
            
            date2 = date1.toLocaleDateString("en-US", {month: "long", year: "numeric", day: "numeric"});
            description = appointmentdata;
            description_datatable()
        });

        $('#btn-view-records').on('click', function(){
            var id   = $(this).attr('data-id');
            $('#btn-close-viewAppointment').click();
            $('#modal-viewMedicalrecords').modal('show');


            $.ajax({
                    url:'/clinic/medicalhistory/gethistory2',
                            type:'GET',
                            dataType: 'json',
                            data: {
                                id      :  id
                            },
                            success:function(data1) {
                                all_history = data1;
                                medicals_datatable();
        
                            }
            })

            $.ajax({
                    url:'/clinic/medicalhistory/getcomplaint2',
                            type:'GET',
                            dataType: 'json',
                            data: {
                                id      :  id
                            },
                            success:function(data1) {
                                all_complaints = data1;
                                complaints()
        
                            }
            })



            $.ajax({
                    url:'/clinic/medicalhistory/get',
                            type:'GET',
                            dataType: 'json',
                            data: {
                                id      :  id
                            },
                            success:function(data) {
								  
									var hsElement = document.getElementById("HS");
									var fhElement = document.getElementById("FH");
									var smk = document.getElementById("Smoking");
									var age1Element = document.getElementById("age1");
									var age2Element = document.getElementById("age2");
									var packElement = document.getElementById("pack");
									var alcoholElement = document.getElementById("alcohol");
									var aveElement = document.getElementById("ave");
									var cmElement = document.getElementById("CM");
									var allergyElement = document.getElementById("allergies");
									var nameElement = document.getElementById("names");

									nameElement.innerHTML = name;
								
									if(data.length > 0){
										
										hsElement.innerHTML = data[0].hospitalization
										fhElement.innerHTML = data[0].familyhistory
										if(data[0].smoke == 0){
											smk.innerHTML = "Yes"
										}else{
											smk.innerHTML = "No"
										}
										if(data[0].alcohol == 0){
											alcoholElement.innerHTML = "Yes"
										}else{
											alcoholElement.innerHTML = "No"
										}
										age1Element.innerHTML = data[0].agestarted
										age2Element.innerHTML = data[0].agequit
										packElement.innerHTML = data[0].packs
										aveElement.innerHTML = data[0].averagedrink
										cmElement.innerHTML = data[0].currentMedications
										allergyElement.innerHTML = data[0].allergies
									}else{
										hsElement.innerHTML = "Not Specified"
										fhElement.innerHTML = "Not Specified"
										smk.innerHTML = "Not Specified"
										alcoholElement.innerHTML = "Not Specified"
										age1Element.innerHTML = "Not Specified"
										age2Element.innerHTML = "Not Specified"
										packElement.innerHTML = "Not Specified"
										aveElement.innerHTML = "Not Specified"
										cmElement.innerHTML = "Not Specified"
										allergyElement.innerHTML = "Not Specified"


								
									}
									

                
                               }
                            })




        });



        
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
        function description_datatable(){
            $("#description_datatable").DataTable({
               // destroy: true,
                    destroy: true,
                    lengthChange : false,
                    stateSave: true,
                    searching: false,
                    responsive: true,
                    data:description,
        
                columns: [
                    { "data": null},
                    { "data": null},
                    { "data": null}

            ],
            columnDefs: [
                    
                    {
                    'targets': 0,
                    'orderable': false, 
                    'createdCell':  function (td, cellData, rowData, row, col) {
                            $(td)[0].innerHTML = date2
                    }
                    },
                    {
                    'targets': 1,
                    'orderable': false, 
                    'createdCell':  function (td, cellData, rowData, row, col) {
                            $(td)[0].innerHTML = rowData.atime
                    }
                    },
                    {
                    'targets': 2,
                    'orderable': false, 
                    'createdCell':  function (td, cellData, rowData, row, col) {
                            $(td)[0].innerHTML = rowData.description
                    }
                    },
                    
                    ],
                        });
            }




        $('.check-appointment').on('click', function(){
                var appointmentid   = $(this).val();
                var applabel           = 0;
                var labelstring     = 'undone';
                if($(this).prop('checked'))
                {
                    applabel         = 1;
                    labelstring       = 'done'
                }
                Swal.fire({
                    title: 'You are going to mark this appointment '+labelstring+'.',
                    text: 'Would you like to continue?',
                    type: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Continue'
                })
                .then((result) => {
                    if (result.value) {
                        $.ajax({
                            url:'/clinic/appointment/markdone',
                            type:'GET',
                            dataType: 'json',
                            data: {
                                id        :  appointmentid,
                                applabel  :  applabel
                            },
                            success:function(data) {
                                if(data == 1)
                                {
                                    Toast.fire({
                                        type: 'success',
                                        title: 'Appointment marked '+labelstring+'!'
                                    })
                                }else{
                                    Toast.fire({
                                        type: 'error',
                                        title: 'Something went wrong!'
                                    })
                                }
                            }
                        })
                    }
                })
            })
        })
    </script>
 @endsection
