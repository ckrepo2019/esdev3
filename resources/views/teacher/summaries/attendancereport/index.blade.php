@extends('teacher.layouts.app')
@section('content')

<!-- DataTables -->
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
<!-- Select2 -->
<link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
<style>
    .select2-container .select2-selection--single{
        height: 40px;
    }
</style>
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                {{-- <h1 class="m-0 text-dark">Summary</h1> --}}
                <h4>Attendance Per Subject</h4>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/home">Home</a></li>
                    <li class="breadcrumb-item">Summaries</li>
                    <li class="breadcrumb-item active">Attendance per Subject</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div>
</section>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-8">
                        <label>Grade Level & Section</label>
                        <select class="form-control select2" style="width: 100%;" id="levelandsection">
                            {{-- <option value="">ALL</option> --}}
                            @if(count($sections)>0)
                                @foreach($sections as $section)
                                    <option value="{{$section->levelid}}-{{$section->sectionid}}-{{$section->subjectid}}">{{$section->levelname}} - {{$section->sectionname}} ({{$section->subjectname}})</option>
                                @endforeach
                            @endif
                            
                        </select>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                          <label>Date range</label>
        
                          <div class="input-group">
                            <div class="input-group-prepend">
                              <span class="input-group-text">
                                <i class="far fa-calendar-alt"></i>
                              </span>
                            </div>
                            <input type="text" class="form-control float-right" id="daterange">
                          </div>
                          <!-- /.input group -->
                        </div>
                    </div>
                    <div class="col-12 text-right">
                        {{-- <button type="button" class="btn btn-default" id="btn-export-pdf"><i class="fa fa-file-pdf"></i> PDF</button> --}}
                        <button type="button" class="btn btn-default" id="btn-export-excel"><i class="fa fa-file-excel"></i> EXCEL</button>
                        <button type="button" class="btn btn-primary" id="btn-generate"><i class="fa fa-sync"></i> Generate</button>
                    </div>
                </div>
            </div>
            <div class="card-body table-responsive p-0" id="results-container" style="height: 500px; ">
                
            </div>
        </div>
    </div>
</div>

<!-- InputMask -->
<script src="{{asset('plugins/moment/moment.min.js')}}"></script>
{{-- <script type="text/javascript" src="{{asset('assets/scripts/main.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/scripts/jquery.min.js')}}"></script> --}}
<!-- DataTables -->
<script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>

<script>

    $(function () {
        //Initialize Select2 Elements
        $('.select2').select2()
        $('#daterange').daterangepicker()
    })
    $('#results-container').hide();
    $('#btn-export-pdf').hide();
    $('#btn-export-excel').hide();
    $(document).ready(function(){
        $('#btn-generate').on('click', function(){                
            Swal.fire({
                title: 'Fetching data...',
                onBeforeOpen: () => {
                    Swal.showLoading()
                },
                allowOutsideClick: false
            })

            $.ajax({
                url: '/summary/attendance/filter',
                type: 'GET',
                data: {
                    levelandsection         : $('#levelandsection').val(),
                    dateperiod              : $('#daterange').val(),
                    action                  : 'filter'
                },
                success:function(data){
                    $('#btn-export-pdf').show();
                    $('#btn-export-excel').show();
                    $('#results-container').show();
                    $('#results-container').empty();
                    $('#results-container').append(data)
                    $(".swal2-container").remove();
                    $('body').removeClass('swal2-shown')
                    $('body').removeClass('swal2-height-auto')
                }
            })
        })

        $('.select2').on('change', function(){
            $('#btn-export-pdf').hide();
            $('#btn-export-excel').hide();
        })
        $('#daterange').on('change', function(){
            $('#btn-export-pdf').hide();
            $('#btn-export-excel').hide();
        })
        $('#btn-export-excel').on('click', function(){
                var paramet = {
                    levelandsection         : $('#levelandsection').val(),
                    dateperiod              : $('#daterange').val(),
                    action                  : 'export'
                }
				window.open("/summary/attendance/filter?action=export&exporttype=excel&"+$.param(paramet));
        })
    })
</script>
@endsection