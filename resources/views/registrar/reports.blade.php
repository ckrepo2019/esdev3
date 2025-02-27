
@extends('registrar.layouts.app')
@section('content')
<style>
    
    .select2-search__field{
            margin: 0px;
        }
        .dataTables_filter label{
            float: right;
        }
.dataTables_paginate{
    float: right;
}
</style>
    <section class="content-header">
        <div class="col-12">
            @if($academicprogram == 'preschool')
                <h4>Pre-school</h4>
            @elseif($academicprogram == 'elementary')
                <h4>Elementary</h4>
            @elseif($academicprogram == 'juniorhighschool')
                <h4>Junior High School</h4>
            @elseif($academicprogram == 'seniorhighschool')
                <h4>Senior High School</h4>
            @endif
        </div>
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/reports/{{$academicprogram}}">{{$selectedform}}</a></li>
                    <li class="breadcrumb-item active">Select School Year</li>
                </ol>
                </div>
            </div>
        </div>
    </section>
    @php
        $buacsschoolids = array('405444','405042','405072','405075','404978','404989','404981','405032');
        $buacsschoolabb = array('sjhsti','phs','sait','sjhsli','lhs','sma','sihs','nsdphs');
        $schoolinfo = DB::table('schoolinfo')
                        ->first();

        if(in_array($schoolinfo->id, $buacsschoolids))
        {
            $buacs = 1;
        }else{
            if(in_array(strtolower($schoolinfo->abbreviation), $buacsschoolabb))
            {
                $buacs = 1;
            }else{
                $buacs = 0;
            }
        }
    @endphp
    @if(strtolower($academicprogram) == 'seniorhighschool' && strtolower($selectedform) == 'school form 9' && $buacs == 1)
        <div class="card">
            <div class="card-header">
                <input type="hidden" value="{{$selectedform}}" name="selectedform" id="selectedform"/>
                <input type="hidden" value="{{$academicprogram}}" name="academicprogram" id="academicprogram"/>
                <div class="row">
                    <div class="col-md-3">
                        <label>Select School Year</label>
                        <select class="form-control" id="select-schoolyear">
                            @foreach ($schoolyear as $sy)
                                <option value="{{$sy->id}}" @if($sy->isactive == 1) selected @endif>{{$sy->sydesc}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Select Semester</label>
                        <select class="form-control" id="select-semester">
                            @foreach ($semesters as $semester)
                                <option value="{{$semester->id}}" @if($semester->isactive == 1) selected @endif>{{$semester->semester}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Select Grade Level</label>
                        <select class="form-control" id="select-gradelevel">
                            @foreach ($gradelevels as $gradelevel)
                                <option value="{{$gradelevel->id}}">{{$gradelevel->levelname}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>&nbsp;</label>
                        <br/>
                        <button type="button" class="btn btn-primary btn-block" id="btn-generate"><i class="fa fa-sync"></i> Filter</button>
                    </div>
                </div>
            </div>
            <div class="card-body" id="card-studentscontainer">

            </div>
        </div>
    @else
        <div class="row">
            @foreach ($schoolyear as $sy)
                @if($sy->isactive == '1')
                    <div class="col-md-4 col-12">
                        <!-- small card -->
                        <form action="/reports/selectSection"  method="GET">
                                <div class="small-box bg-success">
                                    <div class="inner">
                                        <h3>{{$sy->sydesc}}</h3>
                                        <input type="hidden" value="{{$sy->id}}" name="syid"/>
                                        <input type="hidden" value="{{$selectedform}}" name="selectedform"/>
                                        <input type="hidden" value="{{$academicprogram}}" name="academicprogram"/>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <a class="small-box-footer">
                                    <button type="submit" class="btn btn-block">
                                        Select <i class="fas fa-arrow-circle-right"></i>
                                    </button>
                                    </a>
                                </div>
                        </form>
                    </div>
                @endif
            @endforeach
        </div>
        <div class="row">
            @foreach ($schoolyear as $sy)
                @if($sy->isactive != '1')
                    <div class="col-md-4 col-12">
                        <!-- small card -->
                        <form action="/reports/selectSection"  method="GET">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{$sy->sydesc}}</h3>
                                    <input type="hidden" value="{{$sy->id}}" name="syid"/>
                                    <input type="hidden" value="{{$selectedform}}" name="selectedform"/>
                                    <input type="hidden" value="{{$academicprogram}}" name="academicprogram"/>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <a class="small-box-footer">
                                <button type="submit" class="btn btn-block">
                                    Select <i class="fas fa-arrow-circle-right"></i>
                                </button>
                                </a>
                            </div>
                        </form>
                    </div>
                @endif
            @endforeach
        </div>
    @endif
    {{-- <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script> --}}
<!-- fullCalendar 2.2.5 -->
@endsection
@section('footerjavascript')
<script>
    $(document).ready(function(){
        $('#card-studentscontainer').hide();
        $('#btn-generate').on('click', function(){
            var selectedform = $('#selectedform').val();
            var academicprogram = $('#academicprogram').val();
            var selectedschoolyear = $('#select-schoolyear').val();
            var selectsemester = $('#select-semester').val();
            var selectgradelevel = $('#select-gradelevel').val();
            Swal.fire({
                title: 'Fetching data...',
                onBeforeOpen: () => {
                    Swal.showLoading()
                },
                allowOutsideClick: false
            })

            $.ajax({
                url: '/reports_schoolforms/filterstudents',
                type: 'GET',
                data: {
                    selectedform          : selectedform,
                    academicprogram       : academicprogram,
                    selectedschoolyear    : selectedschoolyear,
                    selectsemester        : selectsemester,
                    selectgradelevel      : selectgradelevel
                },
                success:function(data){
                    $('#card-studentscontainer').show();
                    $('#card-studentscontainer').empty();
                    $('#card-studentscontainer').append(data)
                    $(".swal2-container").remove();
                    $('body').removeClass('swal2-shown')
                    $('body').removeClass('swal2-height-auto')
                    
                    $('#example2').DataTable({
                        "bFilter": false,
                        searching: true
                    // "paging": false,
                    // "lengthChange": true,
                    // "searching": true,
                    // "ordering": false,
                    // "info": true,
                    // "autoWidth": false,
                    // "responsive": true,
                    });
                    $('.paginate_button').addClass('btn btn-default');
                }
            })
        })
        $(document).on('click','.paginate_button', function(){
            $('.paginate_button').addClass('btn btn-default');
            $(this).removeClass('btn-default');
            $(this).addClass('btn-primary');
        })
        
        $(document).on('click','.btn-view-record', function(){
            var studentid = $(this).attr('data-id')
            var levelid = $(this).attr('data-levelid')
            
            $('#show-academicprogram').modal('show')
            $.ajax({
                url: '/reports_schoolform10/selectacadprog',
                type: 'GET',
                dataType: 'json',
                data: {
                    studentid   :studentid,
                    levelid        : levelid
                }, success:function(data)
                {
                    $('#acadprog-selection').empty();
                    if(data.length == 0)
                    {
                        $('#acadprog-selection').append(
                            '<div class="row"><div class="col-12"><h3>No Existing Data</h3></div></div>'
                        );
                    }else{
                        $.each(data, function(key,value){
                        $('#acadprog-selection').append(
                            '<div class="row mb-2"><div class="col-12"><a href="/reports_schoolform10/view?studentid='+studentid+'&acadprogid='+value.id+'&acadprogname='+value.description+'" type="button" class="btn btn-lg btn-default btn-block">'+value.description+'</a></div></div>'
                        );
                        })
                    }
                }
            })
        })
        $(document).on('click','.btn-view-record', function(){
            var selectedform        = $('#selectedform').val();
            var academicprogram     = $('#academicprogram').val();
            var selectedschoolyear  = $('#select-schoolyear').val();
            var selectsemester      = $('#select-semester').val();
            var selectgradelevel    = $('#select-gradelevel').val();
            var sectionid           = $(this).attr('data-sectionid');
            var studentid           = $(this).attr('data-id');
            window.open('/reports/form9/view?selectedform='+selectedform+'&selectedschoolyear='+selectedschoolyear+'&selectsemester='+selectsemester+'&selectgradelevel='+selectgradelevel+'&studentid='+studentid+'&selectedsectionid='+sectionid,"_self");
        })
    })
</script>
@endsection
