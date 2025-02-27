
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
@extends('registrar.layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h4 class="m-0 text-dark">Certificate Of Eligibility To Transfer</h4>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Certificate Of Eligibility To Transfer</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div>
    </section>
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-3">
                    <label>Select School Year</label>
                    <select class="form-control  select2" id="select-syid">
                        @foreach(DB::table('sy')->get() as $eachsy)
                            <option value="{{$eachsy->id}}" @if($eachsy->isactive == 1) selected @endif>{{$eachsy->sydesc}}</option>
                        @endforeach
                    </select>
                </div>
                {{-- <div class="col-md-3">
                    <label>Select Semester</label>
                    <select class="form-control  select2" id="select-semid">
                        @foreach(DB::table('semester')->get() as $eachsemester)
                            <option value="{{$eachsemester->id}}">{{$eachsemester->semester}}</option>
                        @endforeach
                    </select>
                </div> --}}
                <div class="col-md-6">
                    <label>Select Student</label>
                    <select class="form-control  select2" id="select-student">
                        @foreach($students as $student)
                            <option value="{{$student->id}}">{{$student->lastname}}, {{$student->firstname}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 align-self-end text-right">
                    <button type="button" class="btn btn-primary" id="btn-generate"><i class="fa fa-sync"></i> Generate</button>
                </div>
                {{-- <div class="col-md-12 text-right mt-2">
                    <button type="button" class="btn btn-primary" id="btn-generate"><i class="fa fa-sync"></i> Generate</button>
                </div> --}}
            </div>
        </div>
    </div>
    <div id="container-filter">
    </div>
    

    <!-- jQuery -->
    @endsection
    @section('footerjavascript')
    <script>
        $('.select2').select2({
          theme: 'bootstrap4'
        })
        function getstudents()
        {
            var syid = $('#select-syid').val();
            var semid = $('#select-semid').val();
            $.ajax({
                url: '/printable/certification/eligtotransfer',
                type:'GET',
                dataType: 'json',
                data: {
                    action      :  'getstudents',
                    syid        :  syid
                    // semid       :  semid
                    // levelid     :  levelid
                },
                success:function(data) {
                    // $('#container-filter').empty()
                    // $('#container-filter').append(data)
                    $('#select-student').empty()
                    if(data.length == 0)
                    {
                        $('#select-student').append(
                            '<option value="0">No students shown</option>'
                        )
                    }else{
                        $.each(data, function(key, value){
                            if(value.suffix == null)
                            {
                                value.suffix = '';
                            }
                            if(value.middlename == null)
                            {
                                value.middlename = '';
                            }
                            $('#select-student').append(
                                '<option value="'+value.id+'">'+value.lastname+', '+value.firstname+' '+value.middlename+' '+value.suffix+'</option>'
                            )
                        })

                    }
                    // $(".swal2-container").remove();
                    // $('body').removeClass('swal2-shown')
                    // $('body').removeClass('swal2-height-auto')
                }
            })
        }
        getstudents()
        $('#select-syid').on('change', function(){
        getstudents()
        })
        $('#select-semid').on('change', function(){
        getstudents()
        })
        // $('#select-student').on('change', function(){
        //     $('#container-filter').empty()
        // })
        $('#btn-generate').on('click', function(){
            var studentid = $('#select-student').val();
            var syid = $('#select-syid').val();
            var semid = $('#select-semid').val();
            Swal.fire({
                title: 'Fetching data...',
                onBeforeOpen: () => {
                    Swal.showLoading()
                },
                allowOutsideClick: false
            })
            $.ajax({
                url: '/printable/certification/eligtotransfer',
                type:'GET',
                // dataType: 'json',
                data: {
                    action      :  'gettemplate',
                    studentid        :  studentid,
                    syid        :  syid
                    // semid       :  semid
                },
                success:function(data) {
                    $('#container-filter').empty()
                    $('#container-filter').append(data)
                    $(".swal2-container").remove();
                    $('body').removeClass('swal2-shown')
                    $('body').removeClass('swal2-height-auto')
                }
            })
        })
        $(document).on('click','#btn-export', function(){
            var studentid = $('#select-student').val();
            var syid = $('#select-syid').val();
            var registrar   = $('.registrar-name').val();
            var transferno = $('#input-transferno').val();
            var transferdate = $('#input-transferdate').val();
            window.open("/printable/certification/eligtotransfer?studentid="+studentid+"&action=export&registrar="+registrar+"&syid="+syid+"&transferno="+transferno+"&transferdate="+transferdate,'_blank');
        }) 
    </script>
@endsection
