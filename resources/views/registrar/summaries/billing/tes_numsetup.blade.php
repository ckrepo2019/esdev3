
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
@extends('registrar.layouts.app')
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">

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
    /* th, td { white-space: nowrap; } */

    .alert-primary {
    color: #004085;
    background-color: #cce5ff;
    border-color: #b8daff;
}
.alert-danger {
    color: #721c24;
    background-color: #f8d7da;
    border-color: #f5c6cb;
}
th, td{
    padding: 2px;
}

</style>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Student Number Setting</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Student Number Setting</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div>
    </section>
    {{-- <div class="row mb-2">
      <div class="col-12">
        <!-- Custom Tabs --> --}}
        <div class="card">
          <div class="card-body" id="card-body-filter">
              <div class="row">
                <div class="col-md-3 mb-2">
                  <label>Select School Year</label>
                  <select class="form-control" id="select-sy">
                      @foreach(DB::table('sy')->get() as $sy)
                          <option value="{{$sy->id}}" @if($sy->isactive == 1) selected @endif>{{$sy->sydesc}}</option>
                      @endforeach
                  </select>
                </div>
                {{-- <div class="col-md-4 mb-2">
                  <label>Select Semester</label>
                  <select class="form-control" id="select-sem">
                      @foreach(DB::table('semester')->get() as $semester)
                          <option value="{{$semester->id}}" @if($semester->isactive == 1) selected @endif>{{$semester->semester}}</option>
                      @endforeach
                  </select>
                </div> --}}
                  <div class="col-md-3 mb-2">
                    <label>Select Year Level</label>
                    <select class="form-control" id="select-level">
                        <option value="0">All</option>
                        @foreach(DB::table('gradelevel')->where('acadprogid','6')->where('deleted','0')->get() as $level)
                            <option value="{{$level->id}}">{{$level->levelname}}</option>
                        @endforeach
                    </select>
                  </div>
                  <div class="col-md-3">
                    <label>Select College</label>
                    <select class="form-control" id="select-college">
                        <option value="0">All</option>
                        @foreach(DB::table('college_colleges')->where('deleted','0')->get() as $college)
                            <option value="{{$college->id}}">{{$college->collegeabrv}}</option>
                        @endforeach
                    </select>
                  </div>
                  <div class="col-md-3">
                    <label>Select Course</label>
                    <select class="form-control" id="select-course">
                        <option value="0">All</option>
                    </select>
                  </div>
                  <div class="col-md-12 text-right">
                    {{-- <label>&nbsp;</label><br/> --}}
                    <button type="button" class="btn btn-primary" id="btn-generate"><i class="fa fa-sync"></i> Generate</button>
                  </div>
              </div>
          </div><!-- /.card-body -->
        </div>
        
        <!-- ./card -->
      {{-- </div>
      <!-- /.col -->
    </div> --}}
    <div id="container-filter">
    </div>
    
    @endsection
    @section('footerjavascript')

    <script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-buttons/js/buttons.colVis.min.js')}}"></script>

    <script>
        $(document).ready(function(){

            $('#container-filter').hide();

            $('#select-sy').on('change', function(){
                $('#container-filter').hide();
            })
            $('#select-sem').on('change', function(){
                $('#container-filter').hide();
            })
            $('#select-level').on('change', function(){
                $('#container-filter').hide();
            })
            $('#select-college').on('change', function(){
                $('#container-filter').hide();
                $.ajax({
                    url: '/registrar/ctbd/getcourses',
                    type:'GET',
                    data: {
                        collegeid   :  $(this).val()
                    },
                    success:function(data) {
                        $('#select-course').empty()
                        $('#select-course').append(
                            '<option value="0">All</option>'
                        )
                        $.each(data, function(key, value){
                            $('#select-course').append(
                                '<option value="'+value.id+'">'+value.courseabrv+'</option>'
                            )
                        })
                    }
                })
            })
            $('#select-course').on('change', function(){
                $('#container-filter').hide();
            })


            $('#btn-generate').on('click', function(){
                Swal.fire({
                    title: 'Fetching data...',
                    onBeforeOpen: () => {
                        Swal.showLoading()
                    },
                    allowOutsideClick: false
                })
                $.ajax({
                    url: '/registrar/billingnumsetting/filter',
                    type:'GET',
                    data: {
                        syid        :  $('#select-sy').val(),
                        levelid     :  $('#select-level').val(),
                        collegeid   :  $('#select-college').val(),
                        courseid    :  $('#select-course').val()
                    },
                    success:function(data) {
                        $('#container-filter').show()
                        $('#container-filter').empty()
                        $('#container-filter').append(data)
                        $(".swal2-container").remove();
                        $('body').removeClass('swal2-shown')
                        $('body').removeClass('swal2-height-auto')
                        var table = $("#table-results").DataTable({
        // scrollY:        "300px",
        // scrollX:        true,
        // scrollCollapse: true,
                                    ordering:         false,
                            fixedColumns: {
                                leftColumns: 2
                            },
                            // scrollY:        400,
                            scrollX:        true,
                            fixedColumns:   true
                        });
                    }
                })
            })
            $(document).on('keypress','.input-awardnopernoa',function(e) {
                if(e.which == 13) {
                    var studid = $(this).attr('data-studid')
                    var awardno = $(this).val();
                    var validation = 0;
                    if($(this).val().replace(/^\s+|\s+$/g, "").length == 0)
                    {
                        validation = 1;
                        $(this).css('border','1px solid red');
                    }else{
                        $(this).removeAttr('style');
                    }
                    if(validation == 0)
                    {
                        $.ajax({
                            url: '/registrar/billingnumsetting/save',
                            type:'GET',
                            data: {
                                studid  :  studid,
                                notype  :  'pernoa',
                                awardno       :  awardno
                            },
                            success:function(data) {
                                if(data == 1)
                                {
                                    toastr.success('Updated successfully!')
                                }
                                // window.location.reload();
                            }
                        })
                    }else{
                        toastr.warning('Empty fields!')

                    }
                }
            });
            $(document).on('keypress','.input-awardnotes',function(e) {
                if(e.which == 13) {
                    var studid = $(this).attr('data-studid')
                    var awardno = $(this).val();
                    var validation = 0;
                    if($(this).val().replace(/^\s+|\s+$/g, "").length == 0)
                    {
                        validation = 1;
                        $(this).css('border','1px solid red');
                    }else{
                        $(this).removeAttr('style');
                    }
                    if(validation == 0)
                    {
                        $.ajax({
                            url: '/registrar/billingnumsetting/save',
                            type:'GET',
                            data: {
                                studid  :  studid,
                                notype  :  'tes',
                                awardno       :  awardno
                            },
                            success:function(data) {
                                if(data == 1)
                                {
                                    toastr.success('Updated successfully!')
                                }
                                // window.location.reload();
                            }
                        })
                    }else{
                        toastr.warning('Empty fields!')

                    }
                }
            });
            $(document).on('click','.btn-export-pdf', function(){
                var syid        =  $('#select-sy').val();
                var semid       =  $('#select-sem').val();
                var levelid     =  $('#select-level').val();
                var collegeid   =  $('#select-college').val();
                var courseid    =  $('#select-course').val();
                window.open('/registrar/ctbd/filter?action=export&exporttype=pdf&syid='+syid+'&semid='+semid+'&levelid='+levelid+'&collegeid='+collegeid+'&courseid='+courseid, '_blank');
            })
            $(document).on('click','.btn-export-excel', function(){
                var syid        =  $('#select-sy').val();
                var semid       =  $('#select-sem').val();
                var levelid     =  $('#select-level').val();
                var collegeid   =  $('#select-college').val();
                var courseid    =  $('#select-course').val();
                window.open('/registrar/ctbd/filter?action=export&exporttype=excel&syid='+syid+'&semid='+semid+'&levelid='+levelid+'&collegeid='+collegeid+'&courseid='+courseid, '_blank');
            })
        })
    </script>
@endsection
