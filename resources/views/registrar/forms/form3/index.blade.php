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
@extends('registrar.layouts.app')

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
                    <h1 class="m-0 text-dark">School Form 3</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">School Form 3</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div>
    </section>
    <div class="card">
        <div class="card-header">
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
                        @foreach(DB::table('gradelevel')->where('acadprogid',$acadprogid)->where('deleted',0)->get() as $gradelevel)
                            <option value="{{$gradelevel->id}}">{{$gradelevel->levelname}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Select Section</label>
                    <select class="form-control" id="select-section"></select>
                </div>
                <div class="col-md-3 text-right">
                    <label>&nbsp;</label><br/>
                    <button type="button" class="btn btn-primary" id="btn-generate"><i class="fa fa-sync"></i> Genrate</button>
                </div>
            </div>
        </div>
    </div>
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-warning alert-dismissible" id="alert-no-results">
                    <h5><i class="icon fas fa-exclamation-triangle"></i> Alert!</h5>
                    Warning alert preview. No setup found! <br/>
                </div>
            </div>
        </div>
    <div id="container-filter"  class="container-fluid">
    </div>
    
    @endsection
    @section('footerjavascript')
    <script>
        $('#alert-no-results').hide();
        $('#setup-container').hide();
        $('.select2').select2({
          theme: 'bootstrap4'
        })
        function getsections(){
            var syid = $('#select-syid').val();
            var semid = $('#select-semid').val();
            var levelid = $('#select-levelid').val();
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
                    sf        :  3,
                    syid        :  syid,
                    levelid       :  levelid
                },
                success:function(data) {
                    $('#select-section').empty()
                    $.each(data, function(key, value){
                        $('#select-section').append(
                            '<option value="'+value.id+'">'+value.sectionname+'</option>'
                        )
                    })
                    $('#btn-getsetups').show()
                    $('#container-filter').empty()
                    $('#container-filter').append(data)
                    $(".swal2-container").remove();
                    $('body').removeClass('swal2-shown')
                    $('body').removeClass('swal2-height-auto')
                }
            })
        }
        getsections()
        $('#select-levelid').on('change', function(){            
            getsections()
        })
        $(document).ready(function(){
            $('#btn-getsetups').on('click', function(){
                var syid = $('#select-syid').val();
                var semid = $('#select-semid').val();
                var levelid = $('#select-levelid').val();
                var sectionid = $('#select-section').val();
                var yearid = $('#select-year').val();
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
                        action        :  'getsetup',
                        sf        :  2,
                        syid        :  syid,
                        semid       :  semid,
                        levelid       :  levelid,
                        sectionid       :  sectionid,
                        yearid       :  yearid,
                        acadprogid       :  '{{$acadprogid}}'
                    },
                    success:function(data) {
                        $('#select-setup').empty()
                        if(data.length == 0)
                        {
                            $('#setup-container').hide();
                            $('#alert-no-results').show();
                        }else{
                            $('#setup-container').show();
                            $.each(data, function(key, value){
                                $('#select-setup').append(
                                    '<option value="'+value.month+'">'+value.monthname+'</option>'
                                )
                            })
                            $('#alert-no-results').hide();
                        }
                        $('#container-filter').empty()
                        $('#container-filter').append(data)
                        $(".swal2-container").remove();
                        $('body').removeClass('swal2-shown')
                        $('body').removeClass('swal2-height-auto')
                    }
                })
            })
            $('#btn-generate').on('click', function(){
                
                var syid = $('#select-syid').val();
                var levelid = $('#select-levelid').val();
                var sectionid = $('#select-section').val();
                
                Swal.fire({
                    title: 'Fetching data...',
                    onBeforeOpen: () => {
                        Swal.showLoading()
                    },
                    allowOutsideClick: false
                })

                $.ajax({
                    url: '/forms/form3',
                    type: 'GET',
                    data: {
                        // selectedlact            : $('#selectedlact').val(),
                        syid                    : syid,
                        levelid                 : levelid,
                        sectionid               : sectionid,
                        acadprogid               : '{{$acadprogid}}',
                        action                  : 'filter'
                    },
                    success:function(data){
                        $('#container-filter').empty();
                        $('#container-filter').append(data)
                        $(".swal2-container").remove();
                        $('body').removeClass('swal2-shown')
                        $('body').removeClass('swal2-height-auto')
                        if($('#selectedlact').val() == 1 || $('#selectedlact').val() == 2)
                        { 
                          $('#div-lact12').show();
                        }else{
                          $('#div-lact12').hide();
                        }
                        
                    }
                })
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
            $(document).on('click','#btn-printpdf', function(){
                var syid = $('#select-syid').val();
                var semid = $('#select-semid').val();
                var levelid = $('#select-levelid').val();
                var sectionid = $('#select-section').val();
                var strandid = $('#select-strand').val();
                var yearid = $('#select-year').val();
                var monthid = $('#select-setup').val();
                
                window.open("/forms/form2?action=export&exporttype=pdf&selectedmonth="+monthid+"&selectedyear="+yearid+"&levelid="+levelid+"&sectionid="+sectionid+"&syid="+syid+"&pam_male="+$('#pam_male').attr('data-id')+"&pam_female="+$('#pam_female').attr('data-id')+"&pam_total="+$('#pam_total').attr('data-id')+"&strandid="+strandid+"&semester="+semid+"&selectedlact="+1);
            })
            $(document).on('click', '#btn-printpdf',function(){
              
            })
        })
    </script>
@endsection
