
@extends('registrar.layouts.app')
@section('headerjavascript')
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
@endsection
@section('content')

    <style>
        
        .donutTeachers{
            margin-top: 90px;
            margin: 0 auto;
            background: transparent url("{{asset('assets/images/corporate-grooming-20140726161024.jpg')}}") no-repeat  28% 60%;
            background-size: 30%;
        }
        .donutStudents{
            margin-top: 90px;
            margin: 0 auto;
            background: transparent url("{{asset('assets/images/student-cartoon-png-2.png')}}") no-repeat  28% 60%;
            background-size: 30%;
        }
        #studentstable{
            font-size: 13px;
        }
        @media (min-width: 768px) {
            .modal-xl {
                width: 90%;
                max-width:1200px;
            }
        }
        .icheck-primary[class*="icheck-"] > label {
            padding-left: 22px !important;
            line-height: 18px;
        }

        .icheck-primary[class*="icheck-"] > input:first-child + input[type="hidden"] + label::before, .icheck-primary[class*="icheck-"] > input:first-child + label::before {
            width: 18px;
            height: 18px;
            border-radius: 5px;
            margin-left: -22px;
        }

        .icheck-primary[class*="icheck-"] > input:first-child:checked + input[type="hidden"] + label::after,
        .icheck-primary[class*="icheck-"] > input:first-child:checked + label::after {
            top: 0px;
            width: 4px;
            height: 8px;
            left: 0px;
        }
    </style>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Number of Students</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Number of Students</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div>
    </section>
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-12">
                            <label>Select School Year</label>
                            <select class="form-control" id="select-syid">
                                @foreach($schoolyears as $sy)
                                    <option value="{{$sy->id}}" @if($sy->isactive == 1) selected @endif>{{$sy->sydesc}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <hr/>
                    <div class="row">
                        <div class="col-md-12">
                            <label>Select Semester</label>
                            <select class="form-control" id="select-semid">
                                @foreach($semesters as $semester)
                                    <option value="{{$semester->id}}" @if($semester->isactive == 1) selected @endif>{{$semester->semester}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <hr/>
                    <div class="row">
                        <div class="col-md-12">
                            <label>Select Gender</label>
                            <select class="form-control" id="select-gender">
                                <option value="0">All</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                        </div>
                    </div>
                    <hr/>
                    <div class="row">
                        <div class="col-md-12">
                            <label>Select Grade Level</label>
                            <div class="form-group clearfix">
                                @foreach($gradelevels as $gradelevel)
                                    <div class="icheck-primary" >
                                    <input type="checkbox" class="selected-gradelevels" id="checkboxgl{{$gradelevel->id}}" value="{{$gradelevel->id}}">
                                    <label for="checkboxgl{{$gradelevel->id}}">
                                        {{$gradelevel->levelname}}
                                    </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <label class="m-0">Select Admission Status</label>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($studentstatus as $studentstat)
                            @if($studentstat->id > 0)
                                <div class="col-md-4 mb-2">
                                    <div class="icheck-primary" >
                                        <input type="checkbox" class="selected-status" id="checkboxstatus{{$studentstat->id}}" value="{{$studentstat->id}}">
                                        <label for="checkboxstatus{{$studentstat->id}}">
                                            {{$studentstat->description}}
                                        </label>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-md-6">
                    <label>Select Student Type</label>
                    <select class="form-control" id="select-studtype">
                        <option value="all">ALL</option>
                        <option value="old">OLD</option>
                        <option value="returnee">RETURNEE</option>
                        <option value="new">NEW</option>
                    </select>
                </div>
                <div class="col-md-6 text-right">
                    <label>&nbsp;</label><br/>
                    <button type="button" id="btn-generate" class="btn btn-primary"><i class="fa fa-sync"></i> Generate</button>
                </div>
            </div>
            <!-- BAR CHART -->
            <div  id="container-results">
                <div class="card card-success">
                    <div class="card-header">
                           <button type="submit" id="btn-export" class="btn btn-default"><i class="fa fa-file-pdf"></i> PDF</button>
                    </div>
                    <div class="card-body">
                        <div class="chart" id="chart-container">
                        <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                  <!-- /.card-body -->
                </div>
                <div class="card card-success">
                    <div class="card-body p-0" style="overflow-x: scroll;">
                        <table class="table table-bordered" id="table-results" style="font-size: 12px;">
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    



    <!-- jQuery -->
    @endsection
    @section('footerjavascript')
    <script>
        $(document).ready(function(){
            $('#container-results').hide();
            $('#btn-generate').on('click', function(){
                var syid = $('#select-syid').val();
                var semid = $('#select-semid').val();
                var gender = $('#select-gender').val();
                var studtype = $('#select-studtype').val();
                var gradelevels = [];
                if($('.selected-gradelevels:checked').length>0)
                {
                    $('.selected-gradelevels:checked').each(function(){
                        gradelevels.push($(this).val())
                    })
                }
                var studentstatus = [];
                if($('.selected-status:checked').length == 0)
                {
                    toastr.warning('Please select an Admission status first!', 'Number of Students')

                }else{
                    $('.selected-status:checked').each(function(){
                        studentstatus.push($(this).val())
                    })
                    Swal.fire({
                        title: 'Fetching data...',
                        onBeforeOpen: () => {
                            Swal.showLoading()
                        },
                        allowOutsideClick: false
                    })
                    $.ajax({
                        url: '/printable/numofstudents/generate',
                        type:'GET',
                        dataType: 'json',
                        data: {
                            syid        :  syid,
                            semid       :  semid,
                            gender      :  gender,
                            studtype    :  studtype,
                            gradelevels : JSON.stringify(gradelevels),
                            studentstatus : JSON.stringify(studentstatus)
                        },
                        success:function(data) {
                            $('#container-results').show()
                            $('#chart-container').empty()
                            $('#chart-container').append('<canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>')

                            $(".swal2-container").remove();
                            $('body').removeClass('swal2-shown')
                            $('body').removeClass('swal2-height-auto')
                            $('#table-results').empty()
                            $('#table-results').append('<tr id="tr-count"></tr>');
                            var sumcount = 0;
                            var datacounts = [];
                            $.each(data, function(key, value){
                                sumcount+=value.studcount;
                                datacounts.push(value.studcount)
                                $('#tr-count').append(
                                    '<th>'+value.studcount+'</th>'
                                )
                            })
                            $('#tr-count').append(
                                '<th>'+sumcount+'</th>'
                            )
                            $('#table-results').append('<tr id="tr-levelname"></tr>');
                            var gradelevels = [];
                            $.each(data, function(key, value){
                                gradelevels.push(value.levelinfo.levelname)
                                var levelname = value.levelinfo.levelname;
                                if (value.levelinfo.levelname.split(/\W+/).length > 1) {
                                    if(levelname.indexOf('GRADE') != -1){
                                        levelname = levelname.replace('GRADE','G');
                                    }
                                    if(levelname.indexOf('KINDER') != -1){
                                        levelname = levelname.replace('KINDER','K');
                                    }
                                    if(levelname.indexOf('NURSERY') != -1){
                                        levelname = levelname.replace('NURSERY','N');
                                    }
                                    $('#tr-levelname').append(
                                        '<td>'+levelname+'</td>'
                                    )
                                }else{
                                    
                                    $('#tr-levelname').append(
                                        '<td>'+levelname+'</td>'
                                    )
                                }
                            })
                            $('#tr-levelname').append(
                                '<td>TOTAL</td>'
                            )
                            
                            var areaChartData = {
                            labels  : gradelevels,
                                datasets: [
                                    {
                                        label               : 'Results',
                                        backgroundColor     : 'rgba(60,141,188,0.9)',
                                        borderColor         : 'rgba(60,141,188,0.8)',
                                        pointRadius         : false,
                                        pointColor          : '#3b8bba',
                                        pointStrokeColor    : 'rgba(60,141,188,1)',
                                        pointHighlightFill  : '#fff',
                                        pointHighlightStroke: 'rgba(60,141,188,1)',
                                        data                : datacounts
                                    }
                                ]
                            }

                            var areaChartOptions = {
                                maintainAspectRatio : false,
                                responsive : true,
                                legend: {
                                    display: false
                                },
                                scales: {
                                    xAxes: [{
                                    gridLines : {
                                        display : false,
                                    }
                                    }],
                                    yAxes: [{
                                    gridLines : {
                                        display : false,
                                    }
                                    }]
                                }
                            }
                            //-------------
                            //- BAR CHART -
                            //-------------
                            var barChartCanvas = $('#barChart').get(0).getContext('2d')
                            var barChartData = $.extend(true, {}, areaChartData)
                            // var temp0 = areaChartData.datasets[0]
                            // var temp1 = areaChartData.datasets[1]
                            // barChartData.datasets[0] = temp0
                            // barChartData.datasets[1] = temp0

                            var barChartOptions = {
                            responsive              : true,
                            maintainAspectRatio     : false,
                            datasetFill             : false
                            }

                            new Chart(barChartCanvas, {
                            type: 'bar',
                            data: barChartData,
                            options: barChartOptions
                            })
                            let chartsData = $("#chart-container").html();
                            $("#chartInputData").val(chartsData);
                        }
                    })
                }
            })
            $('#btn-export').on('click', function(){
                var syid = $('#select-syid').val();
                var semid = $('#select-semid').val();
                var gender = $('#select-gender').val();
                var studtype = $('#select-studtype').val();
                var gradelevels = [];
                if($('.selected-gradelevels:checked').length>0)
                {
                    $('.selected-gradelevels:checked').each(function(){
                        gradelevels.push($(this).val())
                    })
                }
                var studentstatus = [];
                if($('.selected-status:checked').length == 0)
                {
                    toastr.warning('Please select an Admission status first!', 'Number of Students')

                }else{
                    $('.selected-status:checked').each(function(){
                        studentstatus.push($(this).val())
                    })
                    window.open("/printable/numofstudents/generate?action=export&syid="+syid+"&semid="+semid+"&gender="+gender+"&studtype="+studtype+"&gradelevels="+JSON.stringify(gradelevels)+"&studentstatus="+JSON.stringify(studentstatus),"_blank");
                }

            })
        })
    </script>
@endsection
