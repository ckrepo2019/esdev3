<!-- Font Awesome -->
{{-- <link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
<!-- DataTables -->
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
<!-- Theme style -->
<link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/fullcalendar/main.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/fullcalendar-daygrid/main.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/fullcalendar-timegrid/main.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/fullcalendar-bootstrap/main.min.css')}}"> --}}
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
@extends($extends)

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
        
    .alert {
        position: relative;
        padding: .75rem 1.25rem;
        margin-bottom: 1rem;
        border: 1px solid transparent;
        border-radius: .25rem;
    }
    .alert-primary {
        color: #004085;
        background-color: #cce5ff;
        border-color: #b8daff;
    }
    .alert-secondary {
        color: #383d41;
        background-color: #e2e3e5;
        border-color: #d6d8db;
    }
    .alert-success {
        color: #155724;
        background-color: #d4edda;
        border-color: #c3e6cb;
    }
    .alert-danger {
        color: #721c24;
        background-color: #f8d7da;
        border-color: #f5c6cb;
    }
    .alert-warning {
        color: #856404;
        background-color: #fff3cd;
        border-color: #ffeeba;
    }
    .alert-info {
        color: #0c5460;
        background-color: #d1ecf1;
        border-color: #bee5eb;
    }
    .alert-dark {
        color: #1b1e21;
        background-color: #d6d8d9;
        border-color: #c6c8ca;
    }
    .alert-pale-green{
        background-color: white;
        border-color: #c3e6cb;
        border-radius: 15px;
    }

    </style>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Student Masterlist</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Student Masterlist</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div>
    </section>
    <div class="card">
        <div class="card-header">
            @if($acadprogid == 6)
            <div class="row mb-2">
                <div class="col-md-3">
                    <label>Select S.Y.</label>
                    <select class="form-control" id="select-syid">
                        @foreach(DB::table('sy')->get() as $sy)
                            <option value="{{$sy->id}}" @if($sy->isactive == 1) selected @endif>{{$sy->sydesc}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Select Semester</label>
                    <select class="form-control" id="select-semid">
                        @foreach(DB::table('semester')->get() as $semester)
                            <option value="{{$semester->id}}" @if($semester->isactive == 1) selected @endif>{{$semester->semester}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Select College</label>
                    <select class="form-control" id="select-collegeid">
                        <option value="0">ALL</option>
                        @foreach(DB::table('college_colleges')->where('deleted',0)->get() as $college)
                            <option value="{{$college->id}}">{{$college->collegeabrv}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Select Grade Level</label>
                    <select class="form-control" id="select-levelid">
                        <option value="0">ALL</option>
                        @foreach(DB::table('gradelevel')->where('acadprogid',$acadprogid)->where('deleted','0')->orderBy('sortid','asc')->get() as $gradelevel)
                            <option value="{{$gradelevel->id}}">{{$gradelevel->levelname}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <label>Select Course</label>
                    <select class="form-control" id="select-courseid"></select>
                </div>
                {{-- <div class="col-md-3">
                    <label>Select Section</label>
                    <select class="form-control" id="select-section"></select>
                </div> --}}
                <div class="col-md-9 text-right">
                    <label>&nbsp;</label><br/>
                    <button type="button" class="btn btn-primary" id="btn-generate"><i class="fa fa-sync"></i> Generate</button>
                </div>
            </div>
            @elseif($acadprogid == 5)
            <div class="row mb-2">
                <div class="col-md-2">
                    <label>Select S.Y.</label>
                    <select class="form-control" id="select-syid">
                        @foreach(DB::table('sy')->get() as $sy)
                            <option value="{{$sy->id}}" @if($sy->isactive == 1) selected @endif>{{$sy->sydesc}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label>Select Semester</label>
                    <select class="form-control" id="select-semid">
                        @foreach(DB::table('semester')->get() as $semester)
                            <option value="{{$semester->id}}" @if($semester->isactive == 1) selected @endif>{{$semester->semester}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Select Grade Level</label>
                    <select class="form-control" id="select-levelid">
                        @foreach(DB::table('gradelevel')->where('acadprogid',$acadprogid)->where('deleted','0')->orderBy('sortid','asc')->get() as $gradelevel)
                            <option value="{{$gradelevel->id}}">{{$gradelevel->levelname}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-5">
                    <label>Select Section</label>
                    <select class="form-control" id="select-section"></select>
                </div>
                <div class="col-md-12 text-right mt-2">
                    <button type="button" class="btn btn-primary" id="btn-generate"><i class="fa fa-sync"></i> Generate</button>
                </div>
            </div>
            @else
            <div class="row mb-2">
                <div class="col-md-3">
                    <label>Select School Year</label>
                    <select class="form-control" id="select-syid">
                        @foreach(DB::table('sy')->get() as $sy)
                            <option value="{{$sy->id}}" @if($sy->isactive == 1) selected @endif>{{$sy->sydesc}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Select Grade Level</label>
                    <select class="form-control" id="select-levelid">
                        @foreach(DB::table('gradelevel')->where('acadprogid',$acadprogid)->where('deleted','0')->orderBy('sortid','asc')->get() as $gradelevel)
                            <option value="{{$gradelevel->id}}">{{$gradelevel->levelname}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Select Section</label>
                    <select class="form-control" id="select-section"></select>
                </div>
                <div class="col-md-1 text-right">
                    <label>&nbsp;</label><br/>
                    <div class="icheck-primary d-inline">
                        <input type="checkbox" id="checkboxesc" name="escCheck" value="0">
                        <label for="checkboxesc">
                            ESC
                        </label>
                    </div>
                </div>
                <div class="col-md-2 text-right">
                    <label>&nbsp;</label><br/>
                    <button type="button" class="btn btn-primary" id="btn-generate"><i class="fa fa-sync"></i> Generate</button>
                </div>
            </div>
            @endif
        </div>
    </div>
        <div class="alert alert-warning alert-dismissible" id="alert-no-results">
            <h5><i class="icon fas fa-exclamation-triangle"></i> Alert!</h5>
            No sections shown! <br/>
        </div>
    <div id="container-filter">
    </div>
    
    @endsection
    @section('footerjavascript')
    <script>
        @if($acadprogid == '6')
        $('#alert-no-results').hide()
        @else
        $('#alert-no-results').show()
        @endif
        $('.select2').select2({
          theme: 'bootstrap4'
        })
        function getsections(){
            var syid = $('#select-syid').val();
            var semid = $('#select-semid').val();
            var levelid = $('#select-levelid').val();
            var collegeid = $('#select-collegeid').val();
            var courseid = $('#select-courseid').val();
            Swal.fire({
                title: 'Fetching data...',
                onBeforeOpen: () => {
                    Swal.showLoading()
                },
                allowOutsideClick: false
            })
            $.ajax({
                url: '/registar/schoolforms/index',
                type:'GET',
                dataType: 'json',
                data: {
                    action        :  'getsections',
                    sf        :  0,
                    acadprogid               : '{{$acadprogid}}',
                    syid        :  syid,
                    semid       :  semid,
                    levelid       :  levelid,
                    collegeid       :  collegeid,
                    courseid       :  courseid
                },
                success:function(data) {
                    $('#select-section').empty()
                    if(data.length == 0)
                    {
                        $('#select-section').append(
                            '<option value="0">No sections shown</option>'
                        )
                        $('#btn-generate').hide()
                        $('#alert-no-results').show()
                        
                    }else{
                        $('#select-section').append(
                            '<option value="0">ALL</option>'
                        )
                        $.each(data, function(key, value){
                            $('#select-section').append(
                                '<option value="'+value.id+'">'+value.sectionname+'</option>'
                            )
                        })
                        $('#btn-generate').show()
                        $('#alert-no-results').hide()
                    }
                    $('#container-filter').empty()
                    $('#container-filter').append(data)
                    $(".swal2-container").remove();
                    $('body').removeClass('swal2-shown')
                    $('body').removeClass('swal2-height-auto')
                }
            })
        }
        @if($acadprogid == '6')
            function getcourses(){
                var syid = $('#select-syid').val();
                var semid = $('#select-semid').val();
                var collegeid = $('#select-collegeid').val();
                Swal.fire({
                    title: 'Fetching courses...',
                    onBeforeOpen: () => {
                        Swal.showLoading()
                    },
                    allowOutsideClick: false
                })
                $.ajax({
                    url: '/registar/schoolforms/index',
                    type:'GET',
                    dataType: 'json',
                    data: {
                        action        :  'getcourses',
                        sf        :  0,
                        acadprogid               : '{{$acadprogid}}',
                        syid        :  syid,
                        semid       :  semid,
                        collegeid       :  collegeid
                    },
                    success:function(data) {
                        $('#select-courseid').empty()
                        if(data.length == 0)
                        {
                                $('#select-courseid').append(
                                    '<option value="0">No courses shown</option>'
                                )
                            
                        }else{
                                $('#select-courseid').append(
                                    '<option value="0">ALL</option>'
                                )
                            $.each(data, function(key, value){
                                $('#select-courseid').append(
                                    '<option value="'+value.id+'">'+value.courseabrv+'</option>'
                                )
                            })
                            $('#btn-generate').show()
                            $('#alert-no-results').hide()
                        }
                        // getsections()
                        $('#container-filter').empty()
                        $('#container-filter').append(data)
                        $(".swal2-container").remove();
                        $('body').removeClass('swal2-shown')
                        $('body').removeClass('swal2-height-auto')
                    }
                })
            }
            getcourses()
            $('#select-collegeid').on('change', function(){
                getcourses()
            })
            $('#select-courseid').on('change', function(){
            // getcourses()
                // getsections()
            })
            $('#select-levelid').on('change', function(){    
            getcourses()        
                // getsections()
            })
        @else
            getsections()
            $('#select-syid').on('change', function(){            
                getsections()
            })
            $('#select-semid').on('change', function(){            
                getsections()
            })
            $('#select-levelid').on('change', function(){            
                getsections()
            })
        @endif
        // $('#select-section').on('change', function(){            
        //     getsections()
        // })
        $('#btn-generate').on('click', function(){
            var syid = $('#select-syid').val();
            var semid = $('#select-semid').val();
            var levelid = $('#select-levelid').val();
            var sectionid = $('#select-section').val();
            var strandid = $('#select-strand').val();
            var courseid = $('#select-courseid').val();
            var yearid = $('#select-year').val();
            var monthid = $('#select-setup').val();
            var escval = 0;
            var collegeid = $('#select-collegeid').val();
            if($('#checkboxesc').is(':checked'))
            {
                var escval = 1;
            }else{
                var escval = 0;
            }
            // Swal.fire({
            //     title: 'Fetching data...',
            //     onBeforeOpen: () => {
            //         Swal.showLoading()
            //     },
            //     allowOutsideClick: false
            // })

            $.ajax({
                url: '/registar/schoolforms/index',
                type: 'GET',
                data: {
                    // selectedlact            : $('#selectedlact').val(),
                    sf                    : 0,
                    esc                    : escval,
                    syid                    : syid,
                    semid                    : semid,
                    levelid                 : levelid,
                    sectionid               : sectionid,
                    courseid               : courseid,
                    collegeid               : collegeid,
                    strandid               : strandid,
                    acadprogid               : '{{$acadprogid}}',
                    action                  : 'getstudents'
                },
                success:function(data){
                    $('#container-filter').empty();
                    $('#container-filter').append(data)
                    // $(".swal2-container").remove();
                    // $('body').removeClass('swal2-shown')
                    // $('body').removeClass('swal2-height-auto')
                    
                }
            })
        })
        $(document).ready(function(){
            
            $(document).on('click','#checkboxesc', function (){
                $('#btn-generate').click()
            })
            $(document).on("keyup", ".filter",function() {
                var input = $(this).val().toUpperCase();
                var visibleCards = 0;
                var hiddenCards = 0;

                $(".container").append($("<div class='card-group card-group-filter'></div>"));


                $(".card-eachsection").each(function() {
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
            $(document).on('click','.btn-each-esccert', function(){
                var courseid = $('#select-courseid').val();
                var studentid = $(this).attr('data-id');
                var sectionid = $(this).attr('data-sectionid')
                var syid = $('#select-syid').val();
                var semid = $('#select-semid').val();
                var levelid = $('#select-levelid').val();
                window.open("/printable/masterlist?sf=0&acadprogid=4&action=export&schoolyear="+syid+"&sectionid="+sectionid+"&levelid="+levelid+"&exporttype=pdf&studentid="+studentid+"&report=studentesc&courseid="+courseid);
            })
            $(document).on('click','#exportpdf', function(){
                $('#exporttype').val('pdf')
                $('#exportform').submit();
            })
            $(document).on('click','#exportexcel', function(){
                $('#exporttype').val('excel')
                $('#exportform').submit();
            })
            $(document).on('click','#exportexcelinfo', function(){
                $('#exporttype').val('exportexcelinfo')
                $('#exportform').submit();
            })
            $(document).on('click','#exportexcellist', function(){
                $('#exporttype').val('excellist')
                $('#exportform').submit();
            })
            $(document).on('click','.btn-exportpdf', function(){
                var collegeid = $('#select-collegeid').val();
                var sectionid = $(this).attr('data-sectionid')
                var syid = $('#select-syid').val();
                var semid = $('#select-semid').val();
                var levelid = $('#select-levelid').val();
                var courseid = $('#select-courseid').val();
                
                window.open("/registrar/forms/schoolform1/export?schoolyear="+syid+"&sectionid="+sectionid+"&levelid="+levelid+"&exporttype=pdf&courseid="+courseid+"&collegeid="+collegeid);
            })
        })
    </script>
@endsection
