
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
@extends('registrar.layouts.app')

@section('headerjavascript')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
@endsection
@section('content')

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hccsi' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'pcc')
                    <h1 class="m-0 text-dark">Enrollment List</h1>
                    @else
                    <h1 class="m-0 text-dark">Enrollment Report</h1>
                    @endif
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hccsi' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'pcc')
                        <li class="breadcrumb-item active">Enrollment List</li>
                        @else
                        <li class="breadcrumb-item active">Enrollment Report</li>
                        @endif
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div>
    </section>
    <div class="card">
        <div class="card-header" style="border: none !important;">
            <div class="row">
                <div class="col-md-4">
                    <label>Select School Year</label>
                    <select class="form-control  select2" id="select-syid">
                        @foreach(DB::table('sy')->get() as $sy)
                            <option value="{{$sy->id}}" @if($sy->isactive == 1) selected @endif>{{$sy->sydesc}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label>Select Semester</label>
                    <select class="form-control  select2" id="select-semid">
                        @foreach(DB::table('semester')->get() as $semester)
                            <option value="{{$semester->id}}" @if($semester->isactive == 1) selected @endif>{{$semester->semester}}</option>
                        @endforeach
                    </select>
                </div>
                @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'sbc' && strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'ccsa')
                    @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'hccsi')
                    <div class="col-md-4">
                        <label>Select Grade Level</label>
                        <select class="form-control  select2" id="select-levelid">
                                {{-- <option value="0">All College Level</option> --}}
                            @foreach(DB::table('gradelevel')->where('deleted','0')->where('acadprogid','6')->orderBy('sortid')->get() as $gradelevel)
                                <option value="{{$gradelevel->id}}">{{$gradelevel->levelname}}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <div class="col-md-4">
                        <label>Select College</label>
                        <select class="form-control  select2" id="select-collegeid">
                                {{-- <option value="0">All Colleges</option> --}}
                            @foreach(DB::table('college_colleges')->where('deleted','0')->get() as $college)
                                <option value="{{$college->id}}">{{$college->collegeDesc}}</option>
                            @endforeach
                        </select>
                    </div>
                    @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'hccsi')
                    <div class="col-md-4">
                        <label>Select Course</label>
                        <select class="form-control  select2" id="select-courseid">
                                <option value="0">All Courses</option>
                        </select>
                    </div>
                    @endif
                @endif
                <div class="col-md-4 text-right">
                    <label>&nbsp;</label><br/>
                    <button type="button" class="btn btn-primary" id="btn-generate"><i class="fa fa-sync"></i> Generate</button>
                </div>
            </div>
        </div>
    </div>
    <div id="container-results"></div>
    
    @endsection
    @section('footerjavascript')
    <script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
    {{-- <script src="{{asset('plugins/jszip/jszip.min.js')}}"></script>
    <script src="{{asset('plugins/pdfmake/pdfmake.min.js')}}"></script>
    <script src="{{asset('plugins/pdfmake/vfs_fonts.js')}}"></script> --}}
    <script src="{{asset('plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-buttons/js/buttons.colVis.min.js')}}"></script>
    <script>
        
        $('.select2').select2({
          theme: 'bootstrap4'
        })
        function generate()
        {
            var levelid = $('#select-levelid').val()
            var semid   = $('#select-semid').val()
            Swal.fire({
                title: 'Fetching data...',
                onBeforeOpen: () => {
                    Swal.showLoading()
                },
                allowOutsideClick: false
            })
            $.ajax({
                url: '/registrar/reports/notenrolled',
                type:'GET',
                data: {
                    action      :  'filter',
                    levelid     :  levelid,
                    semid       :  semid
                },
                success:function(data) {
                    $('#container-results').empty()
                    $('#container-results').append(data)
                    $(".swal2-container").remove();
                    $('body').removeClass('swal2-shown')
                    $('body').removeClass('swal2-height-auto')
            
                }
            })
        }
        $(document).ready(function(){
            // $('#select-levelid').on('change', function(){
            //     if($(this).val() == 0 || $(this).val() == 14 || $(this).val() == 15 || $(this).val() == 17 || $(this).val() == 18 || $(this).val() == 19 || $(this).val() == 20 || $(this).val() == 21)
            //     {
            //         $('#container-semid').show();
            //     }else{
            //         $('#container-semid').hide();
            //     }
            // })
            $('#btn-generate').on('click', function(){
                var levelid = $('#select-levelid').val()
                var semid   = $('#select-semid').val()
                var syid   = $('#select-syid').val()
                var collegeid   = $('#select-collegeid').val()
                var courseid   = $('#select-courseid').val()
                Swal.fire({
                    title: 'Fetching data...',
                    onBeforeOpen: () => {
                        Swal.showLoading()
                    },
                    allowOutsideClick: false
                })
                $.ajax({
                    url: '/registrar/reports/enrollment',
                    type:'GET',
                    data: {
                        action      :  'filter',
                        levelid     :  levelid,
                        semid       :  semid,
                        syid       :  syid,
                        collegeid       :  collegeid,
                        courseid       :  courseid
                    },
                    success:function(data) {
                        $('#container-results').empty()
                        $('#container-results').append(data)
                        $(".swal2-container").remove();
                        $('body').removeClass('swal2-shown')
                        $('body').removeClass('swal2-height-auto')
                    }
                })
            })
            $('#select-collegeid').on('change', function(){
                $('#select-courseid').empty()
                $('#select-courseid').append(
                    '<option value="0">All Courses</option>'
                )
                if($(this).val() > 0)
                {
                    $.ajax({
                        url: '/registrar/ctbd/getcourses',
                        type:'GET',
                        data: {
                            collegeid     :  $(this).val()
                        },
                        success:function(data) {
                            
                            if(data.length > 0)
                            {
                                $.each(data, function(key,value){
                                    $('#select-courseid').append(
                                        '<option value="'+value.id+'">'+value.courseDesc+'</option>'
                                    )
                                })
                            }
                    
                        }
                    })
                }
            })
            $('#select-collegeid').trigger('change')
        })
        $(document).on('click','#btn-export-pdf', function(){
                var levelid = $('#select-levelid').val()
                var semid   = $('#select-semid').val()
                var syid   = $('#select-syid').val()
                var collegeid   = $('#select-collegeid').val()
                var courseid   = $('#select-courseid').val()
                var registrar   = $('#input-registrar').val()
                var president   = $('#input-president').val()
            window.open('/registrar/reports/enrollment?action=export&exporttype=pdf&levelid='+levelid+'&semid='+semid+'&syid='+syid+'&collegeid='+collegeid+'&courseid='+courseid+'&registrar='+registrar+'&president='+president,'_blank')
        })
        $(document).on('click','#btn-export-excel', function(){
                var levelid = $('#select-levelid').val()
                var semid   = $('#select-semid').val()
                var syid   = $('#select-syid').val()
                var collegeid   = $('#select-collegeid').val()
                var courseid   = $('#select-courseid').val()
                var registrar   = $('#input-registrar').val()
                var president   = $('#input-president').val()
            window.open('/registrar/reports/enrollment?action=export&exporttype=excel&levelid='+levelid+'&semid='+semid+'&syid='+syid+'&collegeid='+collegeid+'&courseid='+courseid+'&registrar='+registrar+'&president='+president,'_blank')
        })
    
        $(document).on('click','.each-button-export', function(){
                var levelid = $('#select-levelid').val()
                var semid   = $('#select-semid').val()
                var syid   = $('#select-syid').val()
                var tabno   = $(this).attr('data-id')
                var pagedesc   = $(this).attr('data-description')
                var firstpageno   = $(this).attr('data-firstpage')
                var registrar   = $('#input-registrar').val()
                var president   = $('#input-president').val()
            window.open('/registrar/reports/enrollment?action=export&exporttype=pdf&levelid='+levelid+'&semid='+semid+'&syid='+syid+'&tabno='+tabno+'&registrar='+registrar+'&president='+president+'&pagedesc='+pagedesc+'&firstpageno='+firstpageno,'_blank')
        })
    </script>
@endsection
