
@extends($extends)
@section('content')
<!-- DataTables -->
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">

<!-- Tempusdominus Bbootstrap 4 -->
<link rel="stylesheet" href="{{asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
<!-- Select2 -->
<link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
<!-- Bootstrap4 Duallistbox -->
<link rel="stylesheet" href="{{asset('plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css')}}">
<style>
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #007bff;
        border-color: #006fe6;
        color: #fff;
        padding: 0 10px;
        margin-top: .31rem;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: #fff;
    }
    .tableFixHead          { overflow-y: auto !important; height: 100px !important; }
    .tableFixHead thead th { position: sticky !important; top: 0 !important; background-color: white !important; z-index: 999}
</style>
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'dcc')
                
                <h1 class="m-0 text-dark">Class List</h1>
                @elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sbc')
                
                <h1 class="m-0 text-dark">Grade Sheet</h1>
                @else
                <h1 class="m-0 text-dark">ALPHA LOADING</h1>
                @endif
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/home">Home</a></li>
                    @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'dcc')
                    
                    <li class="breadcrumb-item active">Class List</li>
                    @elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sbc')                    
                    <li class="breadcrumb-item active">Grade Sheet</li>
                    @else
                    <li class="breadcrumb-item active">ALPHA LOADING</li>
                    @endif
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div>
</section>

@php
$studentcount = 0;
@endphp
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">             
                <div class="row">
                    <div class="col-md-3">
                        <label>Select S.Y</label>
                        <select class="form-control" id="selectedschoolyear">
                            @foreach ($schoolyears as $schoolyear)
                                @if($schoolyear->isactive == 1)
                                    <option value="{{$schoolyear->id}}" selected>{{$schoolyear->sydesc}}</option>
                                @else
                                    <option value="{{$schoolyear->id}}">{{$schoolyear->sydesc}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Select Semester</label>
                        <select class="form-control" id="selectedsemester">
                            {{-- <option value="">ALL</option> --}}
                            @foreach ($semesters as $semester)
                                @if($semester->isactive == 1)
                                    <option value="{{$semester->id}}" selected>{{$semester->semester}}</option>
                                @else
                                    <option value="{{$semester->id}}">{{$semester->semester}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Select Grade Level</label>
                        <select class="form-control" id="selectedgradelevel">
                            <option value="">All college level</option>
                            @foreach (collect($gradelevels)->where('acadprogid','6')->where('deleted','0')->values() as $gradelevel)
                                <option value="{{$gradelevel->id}}">{{$gradelevel->levelname}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Select Course/Dept</label>
                        <select class="form-control" id="selectedcourse">
                            <option value="">NONE</option>
                            @foreach ($courses as $course)
                                <option value="{{$course->id}}">{{$course->courseabrv}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-12 text-right mt-2">
                        <button class="btn btn-primary" id="btn-generate"><i class="fa fa-sync"></i>&nbsp; Generate</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="resultscontainer"></div>
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- Select2 -->
<script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script>
<!-- DataTables -->
<script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
<!-- InputMask -->
<script src="{{asset('plugins/moment/moment.min.js')}}"></script>
<!-- date-range-picker -->
<script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
<script>

    var selectedschoolyear = $('#selectedschoolyear').val();
    var selectedsemester   = $('#selectedsemester').val();
    var selectedgradelevel = null;
    var selectedcourse     = null;
    var selectedsection    = null;
    
    $(document).ready(function(){
        $('#selectedgradelevel').on('change', function(){
            selectedgradelevel = $(this).val()
            $.ajax({
                url: '/registrar/summaries/alphaloading/getsection',
                type: 'GET',
                dataType: 'json',
                data: {
                    selectedgradelevel  : selectedgradelevel
                },
                success:function(data){
                    $('#selectedsection').empty();
                    if(data.length == 0)
                    {
                        $('#selectedsection').append(
                            '<option value="">NONE</option>'
                        )
                    }else{
                        $('#selectedsection').append(
                            '<option value="">ALL</option>'
                        )
                        $.each(data, function(key, value){
                            $('#selectedsection').append(
                                '<option value="'+value.id+'">'+value.sectionname+'</option>'
                            )
                        })
                    }
                    // $('#selectedsection').append(data)
                    
                    // $(".swal2-container").remove();
                    // $('body').removeClass('swal2-shown')
                    // $('body').removeClass('swal2-height-auto')
                }
            })
        })

        $('#selectedcourse').on('change', function(){
            selectedcourse = $(this).val();
        })
        $('#selectedsection').on('change', function(){
            selectedsection = $(this).val();
        })
        $('#selectedgradelevel').on('change', function(){
            selectedgradelevel = $(this).val();
        })
        $('#btn-generate').on('click', function(){
            Swal.fire({
                title: 'Fetching data...',
                onBeforeOpen: () => {
                    Swal.showLoading()
                },
                allowOutsideClick: false
            })
            selectedcollege = $('#selectedcollege').val();
            selectedsemester   = $('#selectedsemester').val();
            selectedschoolyear = $('#selectedschoolyear').val();
            $.ajax({
                url: '/registrar/summaries/alphaloading/filter',
                type: 'GET',
                data: {
                selectedterm        : $('#selectedterm').val(),
                    selectedcollege  : selectedcollege,
                    selectedschoolyear  : selectedschoolyear,
                    selectedsemester    : selectedsemester,
                    selectedgradelevel  : selectedgradelevel,
                    selectedcourse      : selectedcourse,
                    selectedsection     : selectedsection
                },
                success:function(data){
                    $('#resultscontainer').empty();
                    $('#resultscontainer').append(data)
                    
                    $(".swal2-container").remove();
                    $('body').removeClass('swal2-shown')
                    $('body').removeClass('swal2-height-auto')
                    var $rows = $('.studentscontainer tr');
                    $('#input-search').on('keyup', function(){
                        var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();
                        
                        $rows.show().filter(function() {
                            var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
                            return !~text.indexOf(val);
                        }).hide();
                    })
                    var table = $("#studentstable").DataTable({
                        // pageLength : 10,
                        // lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Show All']]
                        "bPaginate": false,
                        "bInfo" : false,
                                "bFilter" : false,
                            "columnDefs": [
                                { "width": "20%", "targets": 1 },
                                { "width": "15%", "targets": 2 },
                                { "width": "10%", "targets": 3 },
                                { "width": "20%", "targets": 4 }
                            ]
                    });
                    table.on( 'order.dt search.dt', function () {
                        table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                            cell.innerHTML = i+1;
                        } );
                    } ).draw();
                }
            })
        })
        $(document).on('click','.each-btn-export',  function(){
            var paramet = {
                selectedterm        : $('#selectedterm').val(),
                selectedschoolyear  : selectedschoolyear,
                selectedsemester   : selectedsemester, 
                selectedgradelevel  : selectedgradelevel, 
                selectedcourse  : selectedcourse, 
                selectedsection    : selectedsection
            }
            window.open("/registrar/summaries/alphaloading/filter?exporttype=pdfstudents&export=1&schedid="+$(this).attr('data-schedid')+"&"+$.param(paramet),'_blank');
        })
        $(document).on('click','.each-btn-export-gs',  function(){
            var paramet = {
                selectedterm        : $('#selectedterm').val(),
                selectedschoolyear  : selectedschoolyear,
                selectedsemester   : selectedsemester, 
                selectedgradelevel  : selectedgradelevel, 
                selectedcourse  : selectedcourse, 
                selectedsection    : selectedsection
            }
            window.open("/registrar/summaries/alphaloading/filter?exporttype=pdfstudentsgs&export=1&schedid="+$(this).attr('data-schedid')+"&"+$.param(paramet),'_blank');
        })
        $(document).on('click','#btn-export-excel', function(){
                            var instructorid = $('#select-instructorid').val()
            var selectedcollege = $('#selectedcollege').val();
            var paramet = {
                instructorid  : instructorid,
                selectedcollege  : selectedcollege,
                selectedschoolyear  : selectedschoolyear,
                selectedsemester   : selectedsemester, 
                selectedgradelevel  : selectedgradelevel, 
                selectedcourse  : selectedcourse, 
                selectedsection    : selectedsection
            }
            window.open("/registrar/summaries/alphaloading/filter?exporttype=excel&export=1&"+$.param(paramet));
        })
        $(document).on('click','#btn-export-pdf', function(){
                            var instructorid = $('#select-instructorid').val()
            var selectedschoolyear = $('#selectedschoolyear').val();
            var selectedsemester   = $('#selectedsemester').val();
            var selectedcollege = $('#selectedcollege').val();
            var acadprogid =  $('#selectedacadprog').val();
            var paramet = {
                instructorid  : instructorid,
                acadprogid  : acadprogid,
                selectedcollege  : selectedcollege,
                selectedschoolyear  : selectedschoolyear,
                selectedsemester   : selectedsemester, 
                selectedgradelevel  : selectedgradelevel, 
                selectedcourse  : selectedcourse, 
                selectedsection    : selectedsection
            }
            window.open("/registrar/summaries/alphaloading/filter?exporttype=pdflist&export=1&"+$.param(paramet));
        })
        
        // $(document).on('click','#btn-export-pdf', function(){
        //     var paramet = {
        //         selectedschoolyear  : selectedschoolyear,
        //         selectedsemester   : selectedsemester, 
        //         selectedgradelevel  : selectedgradelevel, 
        //         selectedcourse  : selectedcourse, 
        //         selectedsection    : selectedsection
        //     }
        //     window.open("/registrar/summaries/alphaloading/filter?exporttype=pdf&export=1&"+$.param(paramet));
        // })

        $(document).on('change','#select-instructorid', function(){
                        
                        Swal.fire({
                            title: 'Filtering results...',
                            onBeforeOpen: () => {
                                Swal.showLoading()
                            },
                            allowOutsideClick: false
                        })
                        selectedgradelevel = $('#selectedgradelevel').val();
                        selectedsemester   = $('#selectedsemester').val();
                        selectedschoolyear = $('#selectedschoolyear').val();
                        selectedcourse = $('#selectedcourse').val();
                        instructorid = $(this).val();
                        $.ajax({
                            url: '/registrar/summaries/alphaloading/filter',
                            type: 'GET',
                            data: {
                                selectedschoolyear  : selectedschoolyear,
                                selectedsemester    : selectedsemester,
                                selectedgradelevel  : selectedgradelevel,
                                selectedcourse      : selectedcourse,
                                instructorid      : instructorid
                            },
                            success:function(data){
                                $('#resultscontainer').empty();
                                $('#resultscontainer').append(data)
                                
                                $(".swal2-container").remove();
                                $('body').removeClass('swal2-shown')
                                $('body').removeClass('swal2-height-auto')
                                var $rows = $('.studentscontainer tr');
                                $('#input-search').on('keyup', function(){
                                    var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();
                                    
                                    $rows.show().filter(function() {
                                        var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
                                        return !~text.indexOf(val);
                                    }).hide();
                                })
                                var table = $("#studentstable").DataTable({
                                    // pageLength : 10,
                                    // lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Show All']]
                                    "bPaginate": false,
                                    "bInfo" : false,
                                "bFilter" : false,
                            "columnDefs": [
                                { "width": "20%", "targets": 1 },
                                { "width": "15%", "targets": 2 },
                                { "width": "10%", "targets": 3 },
                                { "width": "20%", "targets": 4 }
                            ]
                                });
                                table.on( 'order.dt search.dt', function () {
                                    table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                                        cell.innerHTML = i+1;
                                    } );
                                } ).draw();
                            }
                        })
                    })
            $(document).on('change','.select2instructors', function(){
                var schedid = $(this).attr('data-schedid')
                var teacherid = $(this).val()
                $.ajax({
                    url: '/registrar/summaries/alphaloading/assigninstructor',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        schedid  : schedid,
                        teacherid  : teacherid
                    },
                    success:function(data){
                        if(data == 1)
                        {
                            toastr.success('Updated successfully!')
                        }else{
                            toastr.error('Something went wrong!')
                        }
                        // $('#selectedsection').append(data)
                        
                        // $(".swal2-container").remove();
                        // $('body').removeClass('swal2-shown')
                        // $('body').removeClass('swal2-height-auto')
                    }
                })
            })
    })
</script>

@endsection
