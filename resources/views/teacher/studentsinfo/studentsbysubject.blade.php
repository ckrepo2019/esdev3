@extends('teacher.layouts.app')

@section('content')
<style>
    span{
        text-transform: uppercase;
    }
</style>
    <div class="row">
        <div class="col-md-12">
            <div class="main-card mb-3 card">
                <div class="card-header bg-info">
                    <h3 class="card-title">
                        <i class="fas fa-users"></i>
                        Students
                    </h3>
                </div>
                @if(isset($message))
                    <div class="card-body">
                        <div class="alert alert-warning alert-dismissible">
                            <h5><i class="icon fas fa-exclamation-triangle"></i>  {{$message}}</h5>
                            Possible reasons:
                           <ul>
                               <li>No assigned schedule.</li>
                           </ul>
                        </div>
                    </div>
                @endif
                @if(isset($gradeLevel))
                <div class="card-body">
                    @php
                        $countQuery= count($gradeLevel);
                    @endphp
                    <div class="alert alert-info alert-dismissible" id="noAssignedSched">
                        <h5><i class="icon fas fa-info"></i> Alert!</h5>
                        You are not yet assigned to any subjects.
                    </div>
                    
                    @if($countQuery==0)
                        <div class="alert alert-info alert-dismissible" id="noAssignedSched">
                            <h5><i class="icon fas fa-info"></i> Alert!</h5>
                            You are not yet assigned to a schedule.
                        </div>
                    @endif
                    <div id="filterPanel">
                        <div class="row mb-2">
                            <input type="hidden" id="countQuery" value="{{$countQuery}}" hidden >
                            <div class="col-md-3">
                                <select id="gradeLevel" class="form-control form-control-sm">
                                    <option value="0">Select Grade Level</option>
                                    
                                        @if($countQuery!=0)
                                            @foreach($gradeLevel as $level)
                                                <option value="{{$level->id}}" >{{$level->levelname}}</option>
                                            @endforeach
                                        @endif
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select id="section" class="form-control form-control-sm">
                                    <option value="0">Select Section</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select id="subject" class="form-control form-control-sm">
                                    <option value="0">Select Subject</option>
                                </select>
                            </div>
                            @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'gbbc' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'mhssi')
                                <div class="col-md-3">
                                    <select id="selectmonth" class="form-control form-control-sm">
                                    </select>
                                </div>
                            @elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hchs')
                                <div class="col-md-3" id="container-strands">
                                    <select id="selectstrand" class="form-control form-control-sm">
                                    </select>
                                </div>
                            @endif
                        </div>
                        @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'gbbc' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'mhssi')
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <button type="button" class="btn btn-primary" id="btn-filter"><i class="fa fa-sync"></i> Filter</button>
                            </div>
                        </div>
                        @elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hchs')
                            <div class="row" id="container-filter">
                                <div class="col-md-12 text-right">
                                    <button type="button" class="btn btn-primary" id="btn-filter"><i class="fa fa-sync"></i> Filter</button>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    <div id="students-container">
        {{-- <div class="card-body">
            <div class="alert alert-warning alert-dismissible" id="noAssignedStudents">
                <h5><i class="icon fas fa-info"></i> Alert!</h5>
                There are no available students under this subject.
            </div>
            <div class="row mb-2" id="totalbuttons" >
                <div class="col-md-12">
                    <button type="button" class="btn btn-sm btn-warning" id="totalmale">Male : </button>
                    <button type="button" class="btn btn-sm btn-warning" id="totalfemale">Female : </button>
                    <button type="button" class="btn btn-sm btn-warning" id="totalunspecified">Unspecified : </button>
                    <button type="button" class="btn btn-sm btn-warning" id="totalstudent">Total Number of Students : </button>
                </div>
            </div>
            <div class="row" id="studentView">
                <div class="col-md-6">
                    <label>MALE</label>
                    <div id="malecontainer"></div>
                </div>
                <div class="col-md-6">
                    <label>FEMALE</label>
                    <div id="femalecontainer"></div>
                </div>
            </div>
        </div> --}}
    </div>
{{-- </div> --}}

@endsection
@section('footerscripts')
<script>
$('#container-strands').hide()
$('#container-filter').hide()
$('#noAssignedSched').hide();
$('#noAssignedStudents').hide();
$('#totalbuttons').hide();
$(document).ready(function(){
    if($('#countQuery').val() == 0) {
        $('#noAssignedSched').show();
        $('#filterPanel').hide();
        $('#noAssignedStudents').hide();
    }
    else{
            $('#btn-filter').hide()
        $('#gradeLevel').on('change', function(){
            $('#malecontainer').empty();
            $('#femalecontainer').empty();
            var gradelevelid = $(this).val();
            $.ajax({
                url: '/students/bysubjectgetsections',
                type:"GET",
                dataType:"json",
                data:{
                    gradelevelid: gradelevelid
                },
                success:function(data) {
                    $('#subject').empty();
                    $('#subject').append('<option value="0">Select Subject</option>');
                    $('#section').empty();
                    $('#section').append('<option value="0">Select Section</option>');
                    $.each(data, function(key, value){
                        $('#section').append('<option value="'+ value.id +'">' + value.sectionname + '</option>');
                    });
                },
            });
            @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'gbbc' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'mhssi')
                $.ajax({
                    url: '/students/bysubjectgetsections',
                    type:"GET",
                    dataType:"json",
                    data:{
                        action: 'getquartersetup',
                        gradelevelid: gradelevelid
                    },
                    success:function(data) {
                        $('#selectmonth').empty();
                        $.each(data, function(key, value){
                            $('#selectmonth').append('<option value="'+ value.id +'">' + value.monthname + '</option>');
                        });
                    },
                });
            @endif
        });
        $('#section').on('change', function(){
            $('#malecontainer').empty();
            $('#femalecontainer').empty();
            var sectionid = $(this).val();
            $.ajax({
                url: '/students/bysubjectgetsubjects',
                type:"GET",
                dataType:"json",
                data:{
                    sectionid:sectionid
                },
                success:function(data) {
                    $('#subject').empty();
                    $('#subject').append('<option value="0">Select Subject</option>');
                    $.each(data, function(key, value){
                        $('#subject').append('<option value="'+ value.id +'">' + value.subjdesc + '</option>');
                    });
                    @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hchs')
                    $.ajax({
                        url: '/beadleAttendance/getstrands',
                        type:"GET",
                        // dataType:"json",
                        data:{
                            syid:'{{DB::table('sy')->where('isactive','1')->first()->id}}',
                            sectionid:sectionid,
                            levelid : $('#gradeLevel').val()
                        },
                        success:function(data) {
                            $('#selectstrand').empty();
                            $('#selectstrand').append('<option value="0">Select Strand</option>');
                            $.each(data, function(key, value){
                                $('#selectstrand').append('<option value="'+ value.id +'">' + value.strandcode + '</option>');
                            });
                        }
                    });
                    if($('#gradeLevel').val() == 14 || $('#gradeLevel').val() == 15)
                    {
                        $('#container-strands').show()
                        $('#container-filter').show()
                    }
                    @endif
                },
            });
        });
        $('#subject').on('change', function(){
            $('#btn-filter').show()
            @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'gbbc' && strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'hchs' && strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'mhssi')
            var subjectid = $(this).val();
            var sectionid = $('#section').val();
            $.ajax({
                url: '/students/bysubjectgetstudents',
                type:"GET",
                // dataType:"json",
                data:{
                    subjectid:subjectid,
                    sectionid:sectionid,
                    levelid : $('#gradeLevel').val()
                },
                success:function(data) {
                    $('#students-container').empty();
                    $('#students-container').append(data);
                    $(".swal2-container").remove();
                    $('body').removeClass('swal2-shown')
                    $('body').removeClass('swal2-height-auto')
                }
            });
            @endif
        }); 
        $('#btn-filter').on('click', function(){
            Swal.fire({
                title: 'Loading students...',
                allowOutsideClick: false,
                closeOnClickOutside: false,
                onBeforeOpen: () => {
                    Swal.showLoading()
                }
            }) 
            var subjectid = $('#subject').val();
            var sectionid = $('#section').val();
            $.ajax({
                url: '/students/bysubjectgetstudents',
                type:"GET",
                // dataType:"json",
                data:{
                    strandid: $('#selectstrand').val(),
                    subjectid:subjectid,
                    sectionid:sectionid,
                    setupid: $('#selectmonth').val(),
                    levelid : $('#gradeLevel').val()
                },
                success:function(data) {
                    $('#students-container').empty();
                    $('#students-container').append(data);
                    $(".swal2-container").remove();
                    $('body').removeClass('swal2-shown')
                    $('body').removeClass('swal2-height-auto')
                }
            });
        })
        $(document).on("keyup","#input-search", function() {
            var input = $(this).val().toUpperCase();
            var visibleCards = 0;
            var hiddenCards = 0;

            $(".container").append($("<div class='card-group card-group-filter'></div>"));


            $(".card-student").each(function() {
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
    }
});

</script>
@endsection
    