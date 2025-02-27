
@php

$check_refid = DB::table('usertype')->where('id',Session::get('currentPortal'))->select('refid')->first();

if(Session::get('currentPortal') == 3){
      $extend = 'registrar.layouts.app';
}else if(auth()->user()->type == 17){
      $extend = 'superadmin.layouts.app2';
}else if(auth()->user()->type == 10){
      $extend = 'hr.layouts.app';
}else if(Session::get('currentPortal') == 7){
      $extend = 'studentPortal.layouts.app2';
}else if(Session::get('currentPortal') == 6){
      $extend = 'adminPortal.layouts.app2';
}else if(Session::get('currentPortal') == 9){
      $extend = 'parentsportal.layouts.app2';
}else if(Session::get('currentPortal') == 2){
      $extend = 'principalsportal.layouts.app2';
}else if(Session::get('currentPortal') == 1){
      $extend = 'teacher.layouts.app';
}else if ( Session::get('currentPortal') == 14){
      $extend = 'deanportal.layouts.app2';
}else if ( Session::get('currentPortal') == 16){
      $extend = 'chairpersonportal.layouts.app2';
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
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/fullcalendar-v5-11-3/main.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/fullcalendar-v5-11-3/main.min.css')}}">
    <style>
        /* select2 */
        .select2-container--default .select2-selection--single .select2-selection__rendered {
                margin-top: -9px;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice{

            background-color: #007bff;
            border: 1px solid #007bff;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove{

            color: white;
        }
        .shadow {
                box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
                border: 0;
        }
        input[type=search]{
                height: calc(1.7em + 2px) !important;
        }


        /* calendar */
        #calendar td {
            cursor: pointer;
        }

        #weekList td {
            cursor: pointer;
        }

        

    </style>
@endsection


@section('content')
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/eugz.css') }}"> 
    <link rel='stylesheet' href="{{asset('plugins/fullcalendar-v5-11-3/main.css')}}" />


    {{-- Color sa pag highlight sa gusto butangan sa event --}}
    <style>

        .fc-highlight{

            background: #007bff86!important;
            background-color: #007bff86!important;
        }

        .fc-timegrid-event-harness{
            
            width: 20%;
        }
    </style>


    <!-- Add Event Modal -->

        <div class="modal fade" id="addEvent" tabindex="-1" role="dialog" aria-labelledby="addEventLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content addEvent-modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addEventLabel">Add Event</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>×</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form id="form">

                        <input id="start" type="hidden">
                        <input id="end" type="hidden">

                        <div class="mb-3">
                            <label for="act_desc" class="form-label">Event Description</label>
                            <input type="text" class="form-control" id="act_desc" autocomplete="off">
                            
                        </div>

                        <div class="mb-3">
                            <label for="act_venue" class="form-label">Event Venue</label>
                            <input type="text" class="form-control" id="act_venue">
                            
                        </div>

                        <div class="eventtimedate">
                            <div class="d-flex justify-content-between">
                                <div class="mb-3 mr-3">
                                    <label for="datetimeDate" class="form-label">Event Date</label>
                                    <input type="text" class="form-control p-2" id="datetimeDate"  value="" disabled>
                                    
                                </div>
                                <div class="mb-3">
                                    <label for="datetimeTime" class="form-label">Time</label>
                                    <input type="text" class="form-control p-2" id="datetimeTime"  value="" disabled>
                                </div>
                    
                            </div>
                        </div>
                    
                        <div style="margin-bottom: 1rem!important" class="md-3">
                            <div class="select_container">
                                <label for="person_involved">Person Involved</label>
                                <select id="person_involved" name="person_involved" class=" form-control select2"></select>
                            </div>
                        </div>


                        <!-- hidden student -->
                        <div class="md-3 hidden_student">

                            <div class="md-3">
                                <div class="select_container">
                                    <label for="acad_prog">Academic Program</label>
                                    <select id="acad_prog" name="acad_prog" class=" form-control select2"></select>

                                </div>
                            </div>

                            <div class="md-3">
                                <div class="select_container">
                                    <label for="grade_level">Grade Level</label>
                                    <select id="grade_level" name="grade_level" class=" form-control select2"></select>

                                </div>
                            </div>

                            <div class="md-3">
                                <div class="select_container college hidden">
                                    <label for="courses">Course</label>
                                    <select id="courses" name="courses" class=" form-control select2"></select>
        
                                </div>
                            </div>

                            <div class="md-3">
                                <div class="select_container college hidden">
                                    <label for="colleges">College</label>
                                    <select id="colleges" name="colleges" class=" form-control select2"></select>

                                </div>
                            </div>

                            
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="isNoClass">
                                    <label class="form-check-label font-weight-bold" for="isNoClass">
                                        No Class Event
                                    </label>
                                    <p class="m-0 text-sm">Will appear on student calendar as no class event.</p>
                                </div>
                                
                            </div>

                        </div>


                        <!-- hidden faculty -->
                        <div class="hidden_faculty mt-3">

                            <div class="md-3 hidden_div">
                                <div class="select_container">
                                    <label for="faculty">Faculty & Staff 
                                        <a type="button" class="edit_faculty_modal pl-2 hidden"><i class="far fa-edit text-primary"></i></a>
                                        <a type="button" class="delete_faculty pl-1 hidden"><i class="far fa-trash-alt text-danger"></i></a>
                                    </label>
                                    <select id="faculty" name="faculty" class=" form-control select2"></select>

                                </div>
                            </div>

                        </div>

                        <div class="mt-3">
                            <label for="select_holiday">Holiday</label>
                            <select id="select_holiday" name="select_holiday" class="form-control form-control-sm">
                                <option value="0" selected>None</option>
                                <option value="1">VERY SPECIAL HOLIDAY</option>
                                <option value="2">REGULAR HOLIDAY</option>
                                <option value="3">SPECIAL NON WORKING HOLIDAY</option>
                                <option value="4">TEACHER ACTIVITY</option>
                                <option value="5">STUDENT ACTIVTY</option>
                                <option value="6">PARENT ACTIVITY</option>
                                <option value="7">SCHOOL ACTIVTY</option>
                                <option value="5">LOCAL HOLIDAY</option>
                            </select>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-sm btn-success add_event">Add</button>
                </div>
            </div>
        </div>
        </div>

    <!-- Add Item Modal END-->

    <!-- Add Faculty Involve Modal -->

        <div class="modal fade" id="addfaculty" tabindex="-1" role="dialog" aria-labelledby="addfacultyLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addfacultyLabel">Add Faculty</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>×</span>
                    </button>
                </div>
                <div class="modal-body">
                        <div class="mb-3">
                            <input type="hidden" id="faculty_id">
                            <label for="faculty_name" class="form-label">Faculty Name</label>
                            <input type="text" class="form-control" id="faculty_name">
                            
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-sm btn-success add_faculty" id="add_faculty">Add</button>
                </div>
            </div>
        </div>
        </div>

    <!-- Add Faculty Involve Modal -->


    <!-- Export Modal -->

        <div class="modal fade" id="exportModal" tabindex="-1" role="dialog" aria-labelledby="exportModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exportModalLabel">Export File</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>×</span>
                    </button>
                </div>
                <div class="modal-body">
                        <div class="mb-3">
                            <input type="hidden" id="faculty_id">
                            <label for="export_select" class="form-label">Select file type to export.</label>
                            <select id="export_select" name="export_select" class="form-control form-control-sm">
                                <option value="1">PDF</option>
                                <option value="2">Excel</option>
                            </select>
                            
                        </div>

                        <div class="mb-3">
                            <input type="hidden" id="faculty_id">
                            <label for="sy_export" class="form-label">School Year.</label>
                            <select id="sy_export" name="sy_export" class="form-control form-control-sm">
                                @foreach($sy as $schoolyear)
                                    @if($schoolyear->isactive == 1)
                                        <option value="{{$schoolyear->id}}" selected>{{$schoolyear->sydesc}}</option>
                                    @else

                                        <option value="{{$schoolyear->id}}">{{$schoolyear->sydesc}}</option>

                                    @endif
                                    
                                @endforeach
                            </select>
                            
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-sm btn-success exportbtn" id="exportbtn">Export</button>
                </div>
            </div>
        </div>
        </div>


    <!-- Export Modal -->

    <!-- Edit Item Modal -->

        <!-- <div class="modal fade" id="eventDetails" tabindex="-1" aria-labelledby="eventDetailsLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventDetailsLabel">Event Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>×</span>
                </button>
            </div>
            <div class="modal-body">
                    <input id="editid" type="hidden">
                    <input id="editstart" type="hidden">
                    <input id="editend" type="hidden">

                    <div class="mb-3">
                        <label for="edit_act_desc" class="form-label">Event Description</label>
                        <input type="text" class="form-control" id="edit_act_desc">
                        
                    </div>

                    <div class="mb-3">
                        <label for="edit_act_venue" class="form-label">Event Venue</label>
                        <input type="text" class="form-control" id="edit_act_venue">
                        
                    </div>

                    <div style="margin-bottom: 1rem!important" class="md-3">
                        <div class="select_container">
                            <label for="edit_person_involved">Person Involved</label>
                            <select id="edit_person_involved" name="edit_person_involved" class=" form-control select2"></select>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger delete_event" id="delete_event">Remove</button>
                <button type="button" class="btn btn-success update_event" id="update_event">Update</button>
            </div>
            </div>
        </div>
        </div> -->

    <!-- Edit Item Modal END-->

    <!-- BODY -->
    <div class="pt-3 px-2">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card shadow" style="height: 100vh">
                                        
                                    <div class="pb-2 pl-3 pr-3 pt-3 d-flex justify-content-between">
                                        <h5>
                                            <i class="fas fa-calendar-alt text-danger"></i>
                                            School Calendar
                                        </h5>
                                        <div class="col-md-6" style="flex-basis: 35%">
                                            <label for="filter_sy">School Year</label>
                                            <select id="filter_sy" name="filter_sy" class="form-control form-control-sm">
                                                @foreach($sy as $schoolyear)
                                                    @if($schoolyear->isactive == 1)
                                                        <option value="{{$schoolyear->id}}" selected>{{$schoolyear->sydesc}}</option>
                                                    @else

                                                        <option value="{{$schoolyear->id}}">{{$schoolyear->sydesc}}</option>

                                                    @endif
                                                    
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="card-body" style="padding-top: 0px">
                                        <div class="fc fc-ltr fc-bootstrap" style="font-size: 12px;" id="calendar"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                </div>
                <div class="col-md-4">
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card shadow" style="height: 47vh">
                                <div class="card-header">
                                    <h5 class="card-title">
                                    <i class="fas fa-exclamation-circle text-warning"></i>
                                        Event This Week
                                    </h5>
                                </div>
                                <div class="card-body">
                                    
                                    <div class="fc fc-ltr fc-bootstrap" id="weekList"></div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-md-12 event-details-holder">
                            <div class="card shadow" style="height: 50vh">
                                <div class="p-2 d-flex justify-content-between" style="border-bottom: 1px solid rgba(0,0,0,.125)">
                                    <h5 class="card-title" style="line-height: 25px;">
                                        <i class="fas fa-calendar-day text-info"></i>
                                        Event Details
                                    </h5>
                                    <div>
                                        
                                        <a type="button" data-id="" class="pl-0 text-danger btn-sm delete_event hidden" id="delete_event"><i class="far fa-trash-alt text-danger"></i></a>
                                        <a type="button" data-id="" class="pl-0 text-success btn-sm update_event hidden" id="update_event"><i class="far fa-edit text-primary"></i></a>
                                    </div>
                                </div>
                                <div class="card-body" style="overflow: auto">
                                    
                                    <div class="event-details hidden" id="event-details">

                                        <ul class="list-group datails_info">
                                            <li class="list-group-item p-1 active">
                                                <h6 class="m-0 text-center" id="year"></h6>
                                            </li>
                                            <li class="list-group-item p-1 d-flex justify-content-between align-items-center">
                                                <span class="badge badge-success badge-pill" style="width: 50px">What</span>
                                                <h6 style="width: 280px" class="pr-2 text-right" id="title"></h6>

                                                </li>
                                            <li class="list-group-item p-1 d-flex justify-content-between align-items-center">
                                                <span class="badge badge-info badge-pill"  style="width: 50px">Where</span>
                                                <h6 style="width: 280px" class="pr-2 text-right" id="venue"></h6>

                                            </li>
                                            <li class="list-group-item p-1 d-flex justify-content-between align-items-center"> 
                                                <span class="badge badge-danger badge-pill"  style="width: 50px">When</span>
                                                <h6 style="width: 280px" class="pr-2 text-right" id="time"></h6>

                                            </li>
                                            <li class="list-group-item p-1 d-flex justify-content-between align-items-center">
                                                <span class="badge badge-warning badge-pill"  style="width: 50px">Whom</span>
                                                <h6 style="width: 280px" class="pr-2 text-right" id="involve"></h6>

                                            </li>
                                            <li class="list-group-item p-1 d-flex justify-content-between align-items-center">
                                                <span class="badge badge-secondary badge-pill" style="width: 50px">Status</span>
                                                <h6 style="width: 280px" class="pr-2 text-right" id="isnoclass"></h6>

                                            </li>
                                            </ul>
                                        
                                        <!-- <div style="display: flex; justify-content: space-between"></div> -->
                    

                                    </div>
                                    <div id="nodata" class="text-center">
                                        <label class="m-0">No Event Found</label>
                                        <p>Click event to see details.</p>
                                    </div>

                                    <!-- <div class="fc fc-ltr fc-bootstrap" id="dragable">
                                        <p>
                                            <strong>Draggable Events</strong>
                                        </p>

                                        <div class='fc-event fc-h-event fc-daygrid-event fc-daygrid-block-event' data-event='{ "title": "Holiday", "start": "02:00" "end": "04:00"}'>
                                            <div class='fc-event-main'>Holiday</div>
                                        </div>
                                        <div class='fc-event fc-h-event fc-daygrid-event fc-daygrid-block-event' data-event='{ "title": "No Class", "start": "02:00" "end": "04:00"}'>
                                            <div class='fc-event-main'>No Class</div>
                                        </div>
                                        <p>
                                            <input type='checkbox' id='drop-remove' />
                                            <label for='drop-remove'>remove after drop</label>
                                        </p>
                                    </div> -->
                                    
                                </div>
            
                            </div>
                        </div>
                    </div>

                    
                </div>
            </div>
        </div>
    </div>

    <!-- BODY END-->


    <script src="{{asset('plugins/fullcalendar-v5-11-3/main.js') }}"></script>
    <script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{asset('plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>

    <script>

        var temp_gradelevelid;
        var calendar;

        var acadprog_g = @json($acad_prog);
        var gradelevel_g = @json($gradelevel);
        var courses_g = @json($courses);
        var colleges_g = @json($colleges);
        var faculty_g = @json($faculty);



        var selected_faculty = null;
        var set_college = null;

        var currentportal = @json(Session::get('currentPortal'));


        // Condition if ang current portal is pwede ba maka edit/setup or for viewing lang jod
        if(currentportal == 2 || currentportal == 3 || currentportal == 17 || currentportal == 24 || currentportal == 14 || currentportal == 16){
            
            // function para maka setup ang user
            setup(currentportal);

        }else{

            // function for viewing only (mostly for student)
            view(currentportal);

        }

        // event for cliking the export
        $(document).on('click', '.exportbtn', function(){

            var filetype = $('#export_select').val();
            var sy = $('#sy_export').val();

            if(filetype == 1){

                window.open('/school-calendar/pdf/'+sy, '_blank');

            }else if (filetype == 2){

                window.open('/school-calendar/excel/'+sy);
            }
        });


        //Loading the select2 data
        function load_person(){ 

            var person = [
                
                {"id":1, "text":"Student"},
                {"id":2, "text":"Faculty & Staff"},
                {"id":3, "text":"Everyone"}
            ]

            $('#person_involved').empty()
            $('#person_involved').append('<option value="">Select Person Involved</option>')
            $('#person_involved').select2({
                data: person,
                allowClear: true,
                placeholder: "Select Person Involved",
            })
        }

        function load_acad_prog(){ 

            var acad_prog = @json($acad_prog);

            $('#acad_prog').empty()
            $('#acad_prog').append('<option value="">Select Person Involved</option>')
            $('#acad_prog').select2({
                data: acad_prog,
                allowClear: true,
                placeholder: "Select Person Involved",
            })
        }

        function load_gradelevel(grade_level){ 

            $('#grade_level').empty()
            $('#grade_level').append('<option value="">Select Person Involved</option>')
            $('#grade_level').select2({
                data: grade_level,
                allowClear: true,
                placeholder: "Select Grade Level",
            })
            if(temp_gradelevelid != null){

                $('#grade_level').val(temp_gradelevelid).trigger('change');

            }
        } 

        function load_courses(){ 

            var courses = @json($courses);

            $('#courses').empty()
            $('#courses').append('<option value="">Select Person Involved</option>')
            $('#courses').select2({
                data: courses,
                multiple: true,
                allowClear: true,
                placeholder: "Select Person Involved",
            })
        }

        function load_colleges(){ 

            var colleges = @json($colleges);

            $('#colleges').empty()
            $('#colleges').append('<option value="">Select Person Involved</option>')
            $('#colleges').select2({
                data: colleges,
                multiple: true,
                allowClear: true,
                placeholder: "Select Person Involved",
            })
        }

        function load_faculty(){ 
        
            $.ajax({
                type:'GET',
                url: '{{ route("get.select2.faculty") }}',

                success:function(data) {

                    $('#faculty').empty()
                    $('#faculty').append('<option value="">Select Faculty</option>')
                    $('#faculty').append('<option value="add">Add Faculty</option>')
                    $("#faculty").select2({
                        data: data.faculty,
                        allowClear: true,
                        // multiple: true,
                        placeholder: "Select Faculty",
                    })

                    if(selected_faculty != null){
                        $('#faculty').val(selected_faculty).change()
                    }

                    faculty_g = data.faculty;
                }
            })
        }


        //function para ma get and data sa specific event daw i view sa UI 
        function view_event(id, start, end){

            $.ajax({

                url:'{{ route("get.event") }}',
                type:"GET",
                data:{
                    id: id,
                },
                success:function(data){

                    $('#nodata').addClass('hidden')
                    $('#event-details').removeClass('hidden');

                    $('#title').text(data[0]['title']);
                    $('#venue').text(data[0]['venue']);
                    $('#involve').text(data[0]['involve'])

                    if(data[0]['isnoclass'] == 0){

                        $('#isnoclass').text("No Class Event")
                    }else{

                        $('#isnoclass').text("With Class Event")
                    }
                    
                    

                    $('#editstart').val(data[0]['start']);
                    $('#editend').val(data[0]['end']);

                    var title;

                    // para ma format ang star date (ex. Feb 1)
                    var range1 = calendar.formatDate( start, {
                        month: 'short',
                        day: 'numeric',

                    });

                    // para ma format ang end date (ex. Feb 1)
                    var range2 = calendar.formatDate( end, {
                        month: 'short',
                        day: 'numeric',
                    });

                    // para ma format ang star time (ex. 12:30pm)
                    var hoursStart = calendar.formatDate( start, {
                        hour: '2-digit',
                        minute: '2-digit',

                    });

                    // para ma format ang end time (ex. 12:30pm)
                    var hoursEnd= calendar.formatDate( end, {
                        hour: '2-digit',
                        minute: '2-digit',

                    });

                    // para ma format ang year (ex. 2023)
                    var year = calendar.formatDate( start, {year: 'numeric'});

                    if(range1 == range2){

                        //Pag equal ang range1 and range2 pasabot whole day ang event
                        // Display ang date (ex. Febuary 1, 2023)
                        title = calendar.formatDate( end, {
                            month: 'short',
                            year: 'numeric',
                            day: 'numeric',
                        });

                    }else{
                        // Otherwise, display ang date sa ingani nga format (ex. Feb 1 - Feb2, 2023)
                        title = range1+" - "+range2+", "+year;
                    }

                    $('#time').text(hoursStart+" - "+hoursEnd);
                    $('#year').text(title);

                }
            });
        }

        //setup(mostly for staff)
        function setup(type){

            $(document).ready(function() {

                var sy_g = $('#filter_sy').val();

                load_person();
                load_acad_prog();
                load_gradelevel(@json($gradelevel));
                load_courses();
                load_colleges();
                load_faculty();

                renderCalendar(type, sy_g);


                // event clicks and event change

                $(document).on('change', '#filter_sy', function(){
                    var syid = $(this).val();
                    renderCalendar(type, syid);
                })

                // Hide ang unhide inputs depending sa Person Involved
                $(document).on('change', '#person_involved', function(){

                    var val = $(this).val();

                    if(val == 1){

                        $('.hidden_faculty').css("display", "none");
                        $('.hidden_student').css("display", "block");

                    }else if(val == 2){

                        $('.hidden_student').css("display", "none");
                        $('.hidden_faculty').css("display", "block");

                    }else{

                        $('.hidden_student').css("display", "none");
                        $('.hidden_faculty').css("display", "none");

                    }
                });

                $(document).on('click', '.add_event', function(){

                    var syid = @json($activeSY);
                    var acad_prog = $('#acad_prog').val();
                    var acadprogid = $('#acad_prog').val();
                    var grade_level = $('#grade_level').val();
                    var gradelevelid = $('#grade_level').val();
                    var courses = $('#courses').val();
                    var courseid = $('#courses').val();
                    var college = $('#colleges').val();
                    var collegeid = $('#colleges').val();

                    var start = $('#start').val();
                    var end = $('#end').val();

                    var event_desc = $('#act_desc').val();
                    var act_venue = $('#act_venue').val();
                    var isNoClass = $('#isNoClass').is(":checked");
                    var type = 0;
                    var college_text = "";
                    var course_text = "";

                    if(isNoClass){

                        isNoClass = 1;

                    }else{

                        isNoClass = 0;
                    }


                    var involve = $('#person_involved').val();

                    if(involve == 1){
                        
                        if(acad_prog == null || acad_prog == ""){

                            notify('error', "Academic Program is required!");
                            return false;

                        }else{

                            if(acad_prog == 6){

                                if(courses == null || courses == ""){

                                    notify('error', "Course is required!");
                                    return false;

                                }else if(college == null || college == ""){

                                    notify('error', "College is required!");
                                    return false;

                                }else{

                                    gradelevel_g.forEach(element => {

                                        if(grade_level == element.id){

                                            grade_level = element.text;
                                        }

                                    });


                                    
                                    courses_g.forEach(element => {

                                        courseid.forEach(elementid => {
                                            
                                            if(elementid == element.id){

                                                course_text += element.text+", ";
                                        }

                                        });
                                        
                                        
                                    });

                                    colleges_g.forEach(element => {

                                        collegeid.forEach(elementid => {
                                            
                                            if(elementid == element.id){

                                                college_text += element.text+", ";
                                        }

                                        });

                                    });

                                    involve = college_text+" "+course_text+" "+grade_level;

                                }
                                
                            }else{

                                gradelevel_g.forEach(element => {

                                    if(grade_level == element.id){

                                        grade_level = element.text;
                                    }

                                });

                                acadprog_g.forEach(element => {

                                    if(acad_prog == element.id){

                                        acad_prog = element.text;
                                    }

                                });

                                involve = acad_prog+" "+grade_level;
                            }

                            
                        }

                        type = 1;

                    }else if(involve == 2){

                        involve = $('#faculty').val();

                        if(involve == null || involve == ""){

                            notify('error', "Faculty and Staff is required!");

                            return false;

                        }else{

                            faculty_g.forEach(element => {

                                if(involve == element.id){

                                    involve = element.text;
                                }

                            });

                        }

                        type = 2;

                    }else if(involve == 3){

                        involve = "Everyone";
                        type = 0;

                    }else{

                        involve = null;
                    }


                    //validation
                    if(event_desc == null || event_desc == ""){

                        notify('error', 'Event Description is Required!')
                        $('#act_desc').css('box-shadow', 'red 0px 0px 7px');

                        
                    }
                    
                    else if(involve == null || involve == ""){

                        notify('error', 'Person Involved is Required!')
                        $('#person_involved').css('box-shadow', 'red 0px 0px 7px');
                    }
                    else{

                        if(act_venue == null || act_venue == ""){

                            act_venue = "N/A";

                        }

                        $.ajax({

                            url:'{{ route("add.event") }}',
                            type:"GET",
                            data:{

                                start: start,
                                end: end,
                                event_desc: event_desc,
                                act_venue: act_venue,
                                acadprogid: acadprogid,
                                gradelevelid: gradelevelid,
                                courseid:courseid,
                                collegeid:collegeid,
                                involve: involve,
                                isNoClass: isNoClass,
                                type: type,
                                syid: syid.id,
                            },
                            success:function(data){
                                
                                notify(data[0]['statusCode'], data[0]['message']);
                                // $('#form')[0].reset();   
                                
                                calendar.refetchEvents();
                                weeklist.refetchEvents();
                            }
                        });

                        $('#act_venue').css('box-shadow', 'red 0px 0px 0px');
                        $('#act_desc').css('box-shadow', 'red 0px 0px 0px');
                        $('#person_involved').css('box-shadow', 'red 0px 0px 0px');
                    }


                });

                $(document).on('click', '.edit', function(){

                    $.ajax({

                        url:'{{ route("get.event") }}',
                        type:"GET",
                        data:{

                            id: id,
                        },
                        success:function(data){

                            
                            $('#editstart').val(data[0]['start']);
                            $('#editend').val(data[0]['end']);
                            // $('#eventDetails').modal('toggle');

                        }
                    });
                })

                //Get event data daw i butang dayun sa mga inputs
                $(document).on('click', '#update_event', function(){
                    temp_gradelevelid = null;
                    var id = $(this).val();

                    $('#addEvent').modal();

                    $.ajax({

                        url:'{{ route("get.event") }}',
                        type:"GET",
                        data: {
                            id: id,
                        },
                    success:function(data){

                        $('#addEventLabel').text("Edit Event")
                        $('#act_desc').val(data[0]['title'])
                        $('#act_venue').val(data[0]['venue'])
                        $('.add_event').addClass('update')
                        $('.eventtimedate').addClass('hidden')
                        $('.update').removeClass('add_event')
                        $('.update').text("Save")
                        $('.update').val(data[0]['id'])

                        if(data[0]['type'] == 1){

                            
                            $('#person_involved').val(1).trigger('change');

                            if(data[0]['acadprogid'] == 6){
                                

                                var arrayCourseid = data[0]['courseid'].split(' ');
                                var arrayCollegeid = data[0]['collegeid'].split(' ');

                                $('#acad_prog').val(data[0]['acadprogid']).trigger('change');

                                temp_gradelevelid = data[0]['gradelevelid'];

                                $('#courses').val(arrayCourseid).trigger('change');

                                $('#colleges').val(arrayCollegeid).trigger('change');


                            }else{
                                    

                                    $('#acad_prog').val(data[0]['acadprogid']).trigger('change');

                                    temp_gradelevelid = data[0]['gradelevelid'];

                                    // $('#grade_level').val(data[0]['gradelevelid']).trigger('change');

                            }

                            if(data[0]['isnoclass'] == 1){

                                    $('#isNoClass').prop('checked', true);

                            }else{

                                    $('#isNoClass').prop('checked', false);

                            }


                        }else if(data[0]['type'] == 2){

                            $('#person_involved').val(2).trigger('change');

                            faculty_g.forEach(element => {

                                if(data[0]['involve'] == element.text){

                                    $('#faculty').val(element.id).trigger('change');
                                }
                                
                            });

                        }else{
                            
                            $('#person_involved').val(3).trigger('change');

                        }

                        

                    }
                    });
                })

                //The actual update function
                $(document).on('click', '.update', function(){

                    var id = $(this).val();

                    var acad_prog = $('#acad_prog').val();
                    var acadprogid = $('#acad_prog').val();
                    var grade_level = $('#grade_level').val();
                    var gradelevelid = $('#grade_level').val();
                    var courses = $('#courses').val();
                    var courseid = $('#courses').val();
                    var college = $('#colleges').val();
                    var collegeid = $('#colleges').val();

                    var event_desc = $('#act_desc').val();
                    var act_venue = $('#act_venue').val();
                    var isNoClass = $('#isNoClass').is(":checked");
                    var type = 0;
                    var college_text = "";
                    var course_text = "";

                    if(isNoClass){

                        isNoClass = 1;

                    }else{

                        isNoClass = 0;
                    }


                    var involve = $('#person_involved').val();

                    if(involve == 1){
                        
                        if(acad_prog == null || acad_prog == ""){

                            notify('error', "Academic Program is required!");
                            return false;

                        }else{

                            if(acad_prog == 6){

                                if(courses == null || courses == ""){

                                    notify('error', "Course is required!");
                                    return false;

                                }else if(college == null || college == ""){

                                    notify('error', "College is required!");
                                    return false;

                                }else{

                                    gradelevel_g.forEach(element => {

                                        if(grade_level == element.id){

                                            grade_level = element.text;
                                        }

                                    });

                                    courses_g.forEach(element => {

                                    courseid.forEach(elementid => {
                                        
                                        if(elementid == element.id){

                                            course_text += element.text+", ";
                                    }

                                    });
                                    
                                    
                                    });

                                    colleges_g.forEach(element => {

                                    collegeid.forEach(elementid => {
                                        
                                        if(elementid == element.id){

                                            college_text += element.text+", ";
                                    }

                                    });

                                    });

                                    involve = college_text+" "+course_text+" "+grade_level;
                                }
                                
                            }else{

                                gradelevel_g.forEach(element => {

                                    if(grade_level == element.id){

                                        grade_level = element.text;
                                    }

                                });

                                acadprog_g.forEach(element => {

                                    if(acad_prog == element.id){

                                        acad_prog = element.text;
                                    }

                                });

                                involve = acad_prog+" "+grade_level;
                            }

                            
                        }

                        type = 1;

                    }else if(involve == 2){

                        involve = $('#faculty').val();

                        if(involve == null || involve == ""){

                            notify('error', "Faculty and Staff is required!");

                            return false;

                        }else{

                            faculty_g.forEach(element => {

                                if(involve == element.id){

                                    involve = element.text;
                                }

                            });

                        }

                        type = 2;

                    }else if(involve == 3){

                        involve = "Everyone";
                        type = 0;

                    }else{

                        involve = null;
                    }


                    //validation
                    if(event_desc == null || event_desc == ""){

                        notify('error', 'Event Description is Required!')
                        $('#act_desc').css('box-shadow', 'red 0px 0px 7px');

                        
                    }
                    
                    else if(involve == null || involve == ""){

                        notify('error', 'Person Involved is Required!')
                        $('#person_involved').css('box-shadow', 'red 0px 0px 7px');
                    }
                    else{

                        if(act_venue == null || act_venue == ""){

                            act_venue = "N/A";

                        }

                        $.ajax({

                            url:'{{ route("update.event.details") }}',
                            type:"GET",
                            data:{
                                id:id,
                                event_desc: event_desc,
                                act_venue: act_venue,
                                acadprogid: acadprogid,
                                gradelevelid: gradelevelid,
                                courseid:courseid,
                                collegeid:collegeid,
                                involve: involve,
                                isNoClass: isNoClass,
                                type: type,
                            },
                            success:function(data){
                                
                                notify(data[0]['statusCode'], data[0]['message']);
                                view_event(data[0]['event'][0]['id'], data[0]['event'][0]['start'], data[0]['event'][0]['end'])  
                                
                                calendar.refetchEvents();
                                weeklist.refetchEvents();
                            }
                        });

                        $('#act_venue').css('box-shadow', 'red 0px 0px 0px');
                        $('#act_desc').css('box-shadow', 'red 0px 0px 0px');
                        $('#person_involved').css('box-shadow', 'red 0px 0px 0px');
                    }


                })

                $(document).on('click', '.delete_event', function(){

                    Swal.fire({
                    title: 'Are you sure?',
                    text: "You want to remove event?",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Remove!'
                    }).then((result) => {

                        var id = $('#delete_event').val();
                    
                        if (result.value) {

                            $.ajax({

                            url:'{{ route("delete.event") }}',
                            type:"GET",
                            data: {
                                id: id,
                            },
                            success:function(data){

                                notify(data[0]['statusCode'], data[0]['message']);
                                calendar.refetchEvents();
                                weeklist.refetchEvents();
                                $(".event-details-holder").load(location.href + " .event-details-holder");
                                

                            }
                            });

                        }
                            
                    })

                });

                $(document).on('click', '.add_faculty', function(){
                    let name = $("#faculty_name").val();

                    if(name == null || name == ""){

                        notify('error', "Faculty Name is Required!")
                        $('#faculty_name').css('box-shadow', 'red 0px 0px 7px');
                    }else{

                        $.ajax({

                            url:'{{ route("add.faculty") }}',
                            type:"GET",
                            data: {
                                name: name,
                            },
                            success:function(data){

                                notify(data[0]['statusCode'], data[0]['message']);                    
                                load_faculty();
                            }
                        });

                        $('#faculty_name').css('box-shadow', 'red 0px 0px 0px');
                    }

                });

                $(document).on('click', '.edit_faculty_modal', function(){
                    
                    $('#addfacultyLabel').text("Edit Faculty");
                    $('#add_faculty').addClass("edit_faculty");
                    $('#add_faculty').removeClass("add_faculty");
                    $('#add_faculty').text("Save");
                    $('#addfaculty').modal();

                    involve = $('#faculty').val();

                        faculty_g.forEach(element => {

                            if(involve == element.id){

                                involve = element.text;

                                $("#faculty_name").val(involve);
                            }

                        });

                });

                $(document).on('click', '.edit_faculty', function(){
                    let id = $('#faculty_id').val();
                    let name = $("#faculty_name").val();

                    if(selected_faculty != "add"){

                        selected_faculty = id;

                    }

                    $.ajax({

                        url:'{{ route("edit.faculty") }}',
                        type:"GET",
                        data: {
                            id: id,
                            name: name,
                        },
                        success:function(data){

                            notify(data[0]['statusCode'], data[0]['message']);
                            load_faculty();
                            $('#faculty_id').val(data[0]['id']).change();
                        }
                    });

                });

                $(document).on('click', '.delete_faculty', function(){
                    let id = $(this).attr('data-id');
                    $.ajax({

                        url:'{{ route("delete.faculty") }}',
                        type:"GET",
                        data: {
                            id: id,
                        },
                        success:function(data){

                            notify(data[0]['statusCode'], data[0]['message']);                    
                            load_faculty();
                        }
                    });

                });

                $(document).on('click', '.exportbtn', function(){

                    var filetype = $('#export_select').val();
                    var sy = $('#sy_export').val();

                    if(filetype == 1){

                        window.open('/school-calendar/pdf/'+sy, '_blank');

                    }else if (filetype == 2){

                        window.open('/school-calendar/excel/'+sy);
                    }
                });


                //acadprog condition
                $(document).on('change', '#acad_prog', function(){

                    var acad_prog = $(this).val();

                    if(acad_prog == 6){

                        $('.college').removeClass('hidden')


                    }else{

                        $('.college').removeClass('hidden')
                        $('.college').addClass('hidden')
                        $('#colleges').val(0)
                        $('#courses').val(0)
                    }

                    $.ajax({

                        url:'{{ route("get.select2.gradelevel") }}',
                        type:"GET",
                        data:{

                            acad_prog: acad_prog,

                        },
                        success:function(data){
                            
                            load_gradelevel(data.gradelevel);
                        }
                    });
                });

                //faculty condition
                $(document).on('change', '#faculty', function(){

                    var faculty = $(this).val();


                    if(faculty == 'add'){

                        $('#faculty_name').val("");
                        $(".edit_faculty_modal").addClass('hidden');
                        $(".delete_faculty").addClass('hidden');
                        $('#addfaculty').modal();


                    }else if(faculty == null || faculty == ""){

                        $(".edit_faculty_modal").addClass('hidden');
                        $(".delete_faculty").addClass('hidden');
                    }
                    else{

                        $('#faculty_id').val(faculty);
                        $(".delete_faculty").attr('data-id', faculty);

                        $(".edit_faculty_modal").removeClass('hidden');
                        $(".delete_faculty").removeClass('hidden');
                    }
                });

                $(document).on('hide.bs.modal', '#addfaculty', function (e) {
                    
                    load_faculty();

                });


            });
        }

        //viewing(mostly for student and parent)
        function view(type){

            $(document).ready(function() {

                var sy_g = $('#filter_sy').val();
                
                load_person();
                load_acad_prog();
                load_gradelevel(@json($gradelevel));
                load_courses();
                load_colleges();
                load_faculty();

                var calendarEl = document.getElementById('calendar');
                var weekListEl = document.getElementById('weekList');
                
                
                weeklist = new FullCalendar.Calendar(weekListEl, {

                    events: '/school-calendar/getall-event/'+type+'/'+sy_g,
                    height : '100%',
                    contentHeight : '100%',
                    timeZone: 'UTC',
                    themeSystem: 'bootstrap',
                    nowIndicator: true,
                    initialView: 'listWeek',
                    headerToolbar: false,
                    eventClick: function(info){ 

                        var id = info.event.id;
                        var start = info.event.startStr;
                        var endVar = info.event.endStr;

                        var d = new Date(endVar);
                        var mon =  d.getMonth()+1 <= 9? "0"+(d.getMonth()+1) : d.getMonth()+1;
                        var day =  d.getDate()-1 <= 9? "0"+(d.getDate()-1) : d.getDate()-1;

                        var end = d.getFullYear()+"-"+mon+"-"+day+"T23:59:00";

                        // $('#eventDetails').modal('toggle');


                        $.ajax({

                            url:'{{ route("get.event") }}',
                            type:"GET",
                            data:{

                                id: id,
                            },
                            success:function(data){

                                $('#nodata').addClass('hidden')
                                $('#event-details').removeClass('hidden');

                                $('#title').text(data[0]['title']);
                                $('#venue').text(data[0]['venue']);
                                $('#involve').text(data[0]['involve'])

                                if(data[0]['isnoclass'] == 0){

                                    $('#isnoclass').text("No Class Event")
                                }else{

                                    $('#isnoclass').text("With Class Event")
                                }
                                
                                

                                $('#editstart').val(data[0]['start']);
                                $('#editend').val(data[0]['end']);

                                var title;

                                var range1 = calendar.formatDate( start, {
                                    month: 'short',
                                    day: 'numeric',
                    
                                });

                                var range2 = calendar.formatDate( end, {
                                    month: 'short',
                                    day: 'numeric',
                                });

                                var hoursStart = calendar.formatDate( start, {
                                    hour: '2-digit',
                                    minute: '2-digit',
                    
                                });

                                var hoursEnd= calendar.formatDate( end, {
                                    hour: '2-digit',
                                    minute: '2-digit',
                    
                                });

                                var year = calendar.formatDate( start, {year: 'numeric'});

                                if(range1 == range2){

                                    title = calendar.formatDate( end, {
                                        month: 'short',
                                        year: 'numeric',
                                        day: 'numeric',
                                    });

                                }else{

                                    title = range1+" - "+range2+", "+year;
                                }

                                $('#time').text(hoursStart+" - "+hoursEnd);
                                $('#year').text(title);

                            }
                        });

                    },
                    

                });

                calendar = new FullCalendar.Calendar(calendarEl, {
                    
                    // initialView: 'listWeek',
                    events: '/school-calendar/getall-event/'+type+'/'+sy_g,
                    height : '100%',
                    contentHeight : '100%',
                    timeZone: 'UTC',
                    themeSystem: 'bootstrap',
                    selectable: true,
                    nowIndicator: true,
                    dayMaxEvents: true,
                    customButtons: {
                        export: {
                            text: 'Export',
                            click: function() {

                                $('#exportModal').modal();
                            }
                        },

                    },
                    headerToolbar: { 
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay listMonth',

                    },
                    footerToolbar: { 

                        right: 'export',

                    },
                    views: {
                        dayGridMonth: { // name of view
                            titleFormat: 
                                { year: 'numeric', month: 'long' } 
                        },

                        timeGridWeek: { // name of view
                            titleFormat: 
                                { year: 'numeric', month: 'short', day: 'numeric' }
                            
                        },

                        timeGridDay: { // name of view
                            titleFormat: 
                                { year: 'numeric', month: 'long', day: 'numeric' }
                            
                        },
                    },
                    businessHours: {
                        
                        startTime: '06:00', // a start time (6am in this example)
                        endTime: '19:00', // an end time (6pm in this example)
                    },
                    eventClick: function(info){ 

                        var id = info.event.id;
                        var start = info.event.startStr;
                        var endVar = info.event.endStr;

                        var d = new Date(endVar);
                        var mon =  d.getMonth()+1 <= 9? "0"+(d.getMonth()+1) : d.getMonth()+1;
                        var day =  d.getDate()-1 <= 9? "0"+(d.getDate()-1) : d.getDate()-1;

                        var end = d.getFullYear()+"-"+mon+"-"+day+"T23:59:00";

                        view_event(id, start, end)

                    },
                    
                });


                
                calendar.render();
                weeklist.render();

                $('.fc-today-button').addClass('btn-sm')
                $('.fc-prev-button').addClass('btn-sm')
                $('.fc-next-button').addClass('btn-sm')
                $('.fc-export-button').addClass('btn-sm mt-3 bg-danger border-danger export')

                $('.fc-toolbar').css('margin','0')
                $('.fc-toolbar').css('padding-top','0')
                $('.fc-toolbar').css('font-size','12px')
                $('.fc-list-event').css('cusor', 'pointer')
                $('#weekList').css('font-size', '10px')

            });
        }

        //function to render the actual callendar
        function renderCalendar(type, syid){

            var calendarEl = document.getElementById('calendar');
            var weekListEl = document.getElementById('weekList');
            
            //Para sa Weekly Calendar View
            weeklist = new FullCalendar.Calendar(weekListEl, {

                events: '/school-calendar/getall-event/'+type+'/'+syid,
                height : '100%',
                contentHeight : '100%',
                timeZone: 'UTC',
                themeSystem: 'bootstrap',
                nowIndicator: true,
                initialView: 'listWeek',
                headerToolbar: false,
                eventClick: function(info){ //pag i-click sa event

                    var id = info.event.id;
                    var start = info.event.startStr;
                    var endVar = info.event.endStr;

                    var d = new Date(endVar);
                    var mon =  d.getMonth()+1 <= 9? "0"+(d.getMonth()+1) : d.getMonth()+1;
                    var day =  d.getDate()-1 <= 9? "0"+(d.getDate()-1) : d.getDate()-1;

                    var end = d.getFullYear()+"-"+mon+"-"+day+"T23:59:00";


                    $('.delete_event').removeClass('hidden');
                    $('.update_event').removeClass('hidden');

                    $('#delete_event').val(id);
                    $('#update_event').val(id);
                    $('#editid').val(id);

                    $.ajax({

                        url:'{{ route("get.event") }}',
                        type:"GET",
                        data:{

                            id: id,
                        },
                        success:function(data){

                            $('#nodata').addClass('hidden')
                            $('#event-details').removeClass('hidden');

                            $('#title').text(data[0]['title']);
                            $('#venue').text(data[0]['venue']);
                            $('#involve').text(data[0]['involve'])

                            if(data[0]['isnoclass'] == 0){

                                $('#isnoclass').text("No Class Event")
                            }else{

                                $('#isnoclass').text("With Class Event")
                            }
                            
                            $('#editstart').val(data[0]['start']);
                            $('#editend').val(data[0]['end']);

                            var title;

                            var range1 = calendar.formatDate( start, {
                                month: 'short',
                                day: 'numeric',
                            });

                            var range2 = calendar.formatDate( end, {
                                month: 'short',
                                day: 'numeric',
                            });

                            var hoursStart = calendar.formatDate( start, {
                                hour: '2-digit',
                                minute: '2-digit',
                            });

                            var hoursEnd= calendar.formatDate( end, {
                                hour: '2-digit',
                                minute: '2-digit',
                            });

                            var year = calendar.formatDate( start, {year: 'numeric'});

                            if(range1 == range2){

                                title = calendar.formatDate( end, {
                                    month: 'short',
                                    year: 'numeric',
                                    day: 'numeric',
                                });

                            }else{

                                title = range1+" - "+range2+", "+year;
                            }

                            $('#time').text(hoursStart+" - "+hoursEnd);
                            $('#year').text(title);

                        }
                    });

                },
                

            });


            //Para sa Monthly Calendar View
            calendar = new FullCalendar.Calendar(calendarEl, {
                
                events: '/school-calendar/getall-event/'+type+'/'+syid,

                height : '100%',
                contentHeight : '100%',
                timeZone: 'UTC',
                themeSystem: 'bootstrap',
                selectable: true,
                editable: true,
                nowIndicator: true,
                dayMaxEvents: true,
                customButtons: {
                    export: {
                        text: 'Export',
                        click: function() {

                            $('#exportModal').modal();
                        }
                    },

                },
                headerToolbar: { 
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay listMonth',

                },
                footerToolbar: { 

                    right: 'export',

                },
                views: {
                    dayGridMonth: { // name of view
                        titleFormat: 
                            { year: 'numeric', month: 'long' } 
                    },

                    timeGridWeek: { // name of view
                        titleFormat: 
                            { year: 'numeric', month: 'short', day: 'numeric' }
                        
                    },

                    timeGridDay: { // name of view
                        titleFormat: 
                            { year: 'numeric', month: 'long', day: 'numeric' }
                        
                    },
                },
                businessHours: {
                    
                    startTime: '06:00', // a start time (6am in this example)
                    endTime: '19:00', // an end time (6pm in this example)
                },

                select: function (info){ //Pag mag add ug new event

                    console.log(info);
                    var start = info.startStr;

                    var end = info.endStr.split("-");
                    var endDay = new Date(info.endStr);
                    var startDay = new Date(start);

                    endDay = endDay.getDate() <= 9? "0"+endDay.getDate() : endDay.getDate();
                    
                    
                    if(info.allDay){
                        end = end[0]+"-"+end[1]+"-"+endDay+"T23:59:00";
                        console.log(end);
                    }else{
                        end = info.endStr;
                        console.log(end);
                    }
                    
                    var id = info.id;

                    var hoursStart = calendar.formatDate(  start, {
                        hour: '2-digit',
                        minute: '2-digit',
                    });

                    var hoursEnd= calendar.formatDate( end, {
                        hour: '2-digit',
                        minute: '2-digit',
                    });

                    dateStart = calendar.formatDate( start, {
                        month: 'short',
                        year: 'numeric',
                        day: 'numeric',
                    });

                    dateEnd = calendar.formatDate( end, {
                        month: 'short',
                        year: 'numeric',
                        day: 'numeric',
                    });

                    $('#addEventLabel').text("Add Event")
                    $('#act_desc').val("")
                    $('#act_venue').val("")
                    $('.update').addClass('add_event')
                    $('.add_event').removeClass('update')
                    $('.add_event').text("Add")
                    $('#person_involved').val(0).trigger('change');
                    $('#acad_prog').val(0).trigger('change');
                    $('#grade_level').val(0).trigger('change');
                    $('#courses').val(0).trigger('change');
                    $('#colleges').val(0).trigger('change');
                    $('#isNoClass').prop('checked', false);
                    $('#faculty').val(0).trigger('change');
                    $('.eventtimedate').removeClass('hidden');
                    $('.eventtimedate').removeClass('hidden');

                    if(dateStart == dateEnd){

                        $('#datetimeDate').val(dateStart);

                    }else{
                        $('#datetimeDate').val(dateStart+" - "+dateEnd);

                    }
                    
                    $('#datetimeTime').val(hoursStart+"-"+hoursEnd);
                    

                    // $(".addEvent-modal-content").load(location.href + " .addEvent-modal-content");
                    $('#addEvent').modal('toggle');

                    $('#start').val(start);
                    $('#end').val(end);
                    
                    
                },
                eventDrop: function (info){ //Pag mag click sa drag and drop

                    var id = info.event.id;
                    var start = info.event.startStr;
                    var end = info.event.endStr;
                    console.log(start);
                    $.ajax({

                        url:'{{ route("update.event") }}',
                        type:"GET",
                        data:{

                            id: id,
                            start: start,
                            end: end,
                        },
                        success:function(data){
                            
                            weeklist.refetchEvents();


                        }
                    });
                }, 
                eventClick: function(info){ //Pag mag click sa event

                    var id = info.event.id;
                    var start = info.event.startStr;
                    var endVar = info.event.endStr;

                    var d = new Date(endVar);
                    var mon =  d.getMonth()+1 <= 9? "0"+(d.getMonth()+1) : d.getMonth()+1;
                    var day =  d.getDate()-1 <= 9? "0"+(d.getDate()-1) : d.getDate()-1;

                    var end = d.getFullYear()+"-"+mon+"-"+day+"T23:59:00";

                    console.log(start);


                    $('.delete_event').removeClass('hidden');
                    $('.update_event').removeClass('hidden');

                    $('#delete_event').val(id);
                    $('#update_event').val(id);
                    $('#editid').val(id);
                    // $('#eventDetails').modal('toggle');


                    view_event(id, start, end);

                },
                eventResize: function( info ) { //Pag mag resize sa event time

                    var id = info.event.id;
                    var start = info.event.startStr;
                    var end = info.event.endStr;

                    console.log(start);
                    $.ajax({

                        url:'{{ route("update.event") }}',
                        type:"GET",
                        data:{

                            id: id,
                            start: start,
                            end: end,
                        },
                        success:function(data){
                            

                        }
                    });
                },

                
            });

            calendar.render();
            weeklist.render();


            //Manual change sa css/add class sa calendar
            $('.fc-today-button').addClass('btn-sm')
            $('.fc-prev-button').addClass('btn-sm')
            $('.fc-next-button').addClass('btn-sm')
            $('.fc-export-button').addClass('btn-sm mt-3 bg-danger border-danger export')

            $('.fc-toolbar').css('margin','0')
            $('.fc-toolbar').css('padding-top','0')
            $('.fc-toolbar').css('font-size','12px')
            $('.fc-list-event').css('cusor', 'pointer')
            $('#weekList').css('font-size', '10px')

            $('.fc-pdf-button').attr('formtarget', '_blank')
            $('.fc-excel-button').attr('formtarget', '_blank')



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
