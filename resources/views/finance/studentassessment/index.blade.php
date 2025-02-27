@extends('finance.layouts.app')

@section('content')
<!-- DataTables -->
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
<!-- daterange picker -->
<link rel="stylesheet" href="{{asset('plugins/daterangepicker/daterangepicker.css')}}">
<style>
    table{
        font-size: 12px;
    }
</style>
<br>
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Student Assessment</h1>
                <!-- <h4 class="text-warning" style="text-shadow: 1px 1px 1px #000000">
                    <i class="fa fa-file-invoice nav-icon"></i> 
                    <b>STUDENT LEDGER</b></h4> -->
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item active">Student Assessment</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
<div class="row m-2">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <button type="button" class="btn btn-default btn-sm export" exporttype="excel"><i class="fa fa-download"></i> Excel</button>
                <button type="button" class="btn btn-default btn-sm export" exporttype="pdf"><i class="fa fa-download"></i> PDF</button>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="input-group input-group-sm ">
                            <div class="input-group-prepend">
                                <span class="input-group-text form-control form-control-sm">
                                    Schoolyear
                                </span>
                            </div>
                            <select class="form-control form-control-sm" id="selectedschoolyear">
                                @foreach($schoolyears as $schoolyear)
                                    <option value="{{$schoolyear->id}}" {{$schoolyear->isactive == 1 ? 'selected' : ''}}>{{$schoolyear->sydesc}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="input-group input-group-sm ">
                            <div class="input-group-prepend">
                                <span class="input-group-text form-control form-control-sm">
                                    Semester
                                </span>
                            </div>
                            <select class="form-control form-control-sm" id="selectedsemester">
                                <option value="" >All</option>
                                @foreach($semesters as $semester)
                                    <option value="{{$semester->id}}" >{{$semester->semester}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="input-group input-group-sm ">
                            <div class="input-group-prepend" >
                                <span class="input-group-text form-control form-control-sm">
                                    Month
                                </span>
                            </div>
                            <select id="selectedmonth" class="form-control form-control-sm">
                                <option value="">Select month</option>
                                @foreach($monthsetups as $monthsetup)
                                    <option value="{{strtolower($monthsetup->description)}}">{{$monthsetup->description}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="input-group input-group-sm ">
                            <div class="input-group-prepend">
                                <span class="input-group-text form-control form-control-sm">
                                    Level
                                </span>
                            </div>
                            <select class="form-control form-control-sm" id="selectedgradelevel">
                                <option value="" >All</option>
                                @foreach($gradelevels as $gradelevel)
                                    <option value="{{$gradelevel->id}}" >{{$gradelevel->levelname}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group input-group-sm ">
                            <div class="input-group-prepend" >
                                <span class="input-group-text form-control form-control-sm">
                                    Sections
                                </span>
                            </div>
                            <select class="form-control form-control-sm" id="selectedsection">
                                <option value="" >All</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-primary float-right btn-sm" id="generatebutton">Generate</button>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-12" id="resultscontainer">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- jQuery -->
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- DataTables -->
<script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
<!-- InputMask -->
<script src="{{asset('plugins/moment/moment.min.js')}}"></script>
<script src="{{asset('plugins/inputmask/min/jquery.inputmask.bundle.min.js')}}"></script>
<!-- date-range-picker -->
<script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
<script>
    $(document).ready(function(){
        $('body').addClass('sidebar-collapse')
        // Swal.fire("Loading","done","success");
        var selectedschoolyear = $('#selectedschoolyear').val();
        var selectedmonth = null;
        var selectedgradelevel = null;
        var selectedsemester = null;
        var selectedsection = null;
        var loadingtext = 'Getting ready...';
        function filterstudents()
        {
            Swal.fire({
                title: loadingtext,
                onBeforeOpen: () => {
                    Swal.showLoading()
                },
                allowOutsideClick: false
            })
            $.ajax({
                url: '{{ route('studentassessmentfilter')}}',
                type: 'GET',
                data: {
                    selectedschoolyear  : selectedschoolyear,
                    selectedmonth  : selectedmonth,
                    selectedgradelevel  : selectedgradelevel,
                    selectedsemester  : selectedsemester,
                    selectedsection  : selectedsection
                },
                success:function(data){
                    $('#resultscontainer').empty();
                    $('#resultscontainer').append(data)
                    
                    var tablecontainer = $("#example1").DataTable({
                        // pageLength : 10,
                        // lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Show All']]
                        aLengthMenu: [
                            [25, 50, 100, 200, -1],
                            [25, 50, 100, 200, "All"]
                        ],
                        iDisplayLength: -1,
                        "order": [[ 1, 'asc' ]],
                        paging: false
                    });
                    tablecontainer.on( 'order.dt search.dt', function () {
                        tablecontainer.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                            cell.innerHTML = i+1;
                        } );
                    } ).draw();
                    $(".swal2-container").remove();
                    $('body').removeClass('swal2-shown')
                    $('body').removeClass('swal2-height-auto')
                    $('#headerstudentstotalamount').text()
                    $('#headerstudentstotalamountpay').text($('#studentstotalamountpay').text())
                    $('#headerstudentstotalbalance').text($('#studentstotalbalance').text())


                }
            })
        }
        // filterstudents();
        $('#selectedschoolyear').on('change', function() {
            loadingtext = 'Loading...';
            selectedschoolyear = $(this).val();

        });
        $('#selectedmonth').on('change', function() {
            loadingtext = 'Loading...';
            selectedmonth = $(this).val()
        });
        $('#selectedgradelevel').on('change', function() {
            loadingtext = 'Loading...';
            selectedgradelevel = $(this).val()
            selectedsection = null;
            $.ajax({
                url: '{{ route('acctreceivablegetsections')}}',
                type: 'GET',
                datetype: 'json',
                data: {
                    selectedgradelevel  : selectedgradelevel
                },
                success:function(data){
                    $('#selectedsection').empty();
                    $('#selectedsection').append('<option value="">All</option>')
                    if(data.length > 0)
                    {
                        $.each(data, function(key, value){
                            $('#selectedsection').append('<option value="'+value.id+'">'+value.sectionname+'</option>')
                        })
                    }
                }
            })
        });
        $('#selectedsemester').on('change', function() {
            loadingtext = 'Loading...';
            selectedsemester = $(this).val()
        });
        $('#selectedsection').on('change', function() {
            loadingtext = 'Loading...';
            selectedsection = $(this).val()
        });
        $('#generatebutton').on('click', function(){
            filterstudents();
        })
        $('.export').on('click', function(){
                var exporttype = $(this).attr('exporttype')
                var paramet = {
                    selectedschoolyear  : selectedschoolyear,
                    selectedmonth       : selectedmonth,
                    selectedgradelevel  : selectedgradelevel,
                    selectedsemester    : selectedsemester,
                    selectedsection     : selectedsection
                }
				window.open("/studentassessment/export?exporttype="+exporttype+"&"+$.param(paramet));
        })
    })

</script>
@endsection