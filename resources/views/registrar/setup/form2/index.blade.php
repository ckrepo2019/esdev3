
@extends('registrar.layouts.app')
@section('content')
<!-- DataTables -->
    <!-- DataTables -->
    <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
    <!-- jQuery -->
    <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
    <!-- DataTables  & Plugins -->
    <script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
    <script src="{{asset('plugins/jszip/jszip.min.js')}}"></script>
    <script src="{{asset('plugins/pdfmake/pdfmake.min.js')}}"></script>
    <script src="{{asset('plugins/pdfmake/vfs_fonts.js')}}"></script>
    <style>
        
        .select2-container .select2-selection--single {
            height: 40px !important;
        }
        
        #modal-adddata .modal-dialog{
            max-width: 800px
        }
        td, th {
            padding: 1px !important;
        }
    </style>
    <section class="content-header p-0">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Lock SF2</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Lock SF2</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div>
    </section>
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-3">
                    <label>Select S.Y.</label>
                    <select class="form-control" id="select-sy">
                        @foreach(DB::table('sy')->get() as $eachsy)
                            <option value="{{$eachsy->id}}" @if($eachsy->isactive == 1) selected @endif>{{$eachsy->sydesc}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Select Semester</label>
                    <select class="form-control" id="select-sem">
                        @foreach(DB::table('semester')->get() as $semester)
                            <option value="{{$semester->id}}" @if($semester->isactive == 1) selected @endif>{{$semester->semester}}</option>
                        @endforeach
                    </select>
                    <small style="font-size: 12px;"><em>Note: Need for SHS sections filtering</em></small>
                </div>
                <div class="col-md-3">
                    <label>Select Year</label>
                    <select class="form-control" id="select-year">
                        @if(DB::table('sf2_setup')->select('year')->where('deleted','0')->orderByDesc('year')->distinct()->count() == 0)
                        <option value="{{date('Y')}}">{{date('Y')}}</option>
                        <option value="{{date('Y') - 1}}">{{date('Y') - 1}}</option>
                        @else
                        @foreach(DB::table('sf2_setup')->select('year')->where('deleted','0')->orderByDesc('year')->distinct()->get() as $eachyear)
                            <option value="{{$eachyear->year}}">{{$eachyear->year}}</option>
                        @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Select Month</label>
                    <select class="form-control" id="select-month">
                        {{-- @foreach(DB::table('sf2_setup')->select('month')->where('deleted','0')->orderBy('month','asc')->distinct()->get() as $eachmonth)
                            <option value="{{$eachmonth->month}}" @if($eachmonth->month == ltrim(date('m').'0')) selected @endif>{{date("F", mktime(0, 0, 0, $eachmonth->month, 10))}} {{ltrim(date('m'),'0')}} {{$eachmonth->month}}</option>
                        @endforeach --}}
                        @for($m=1; $m<=12; $m++)
                            @php
                            $month = date('F', mktime(0,0,0,$m, 1, date('Y')));
                            @endphp
                            <option value="{{$m}}" @if($m == ltrim(date('m'),'0')) selected @endif>{{$month}}</option>
                        @endfor
                    </select>
                    <small style="font-size: 11px;"><em>&nbsp;</em></small>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 text-right">
                    <button type="button" class="btn btn-primary" id="btn-generate"><i class="fa fa-sync"></i> Generate</button>
                </div>
            </div>
        </div>
    </div>
    <div id="div-results">
    </div>
    <div id="div-newcards">
    </div>
    
    <script>
        $(document).ready(function(){
            $('#btn-generate').on('click', function()
            {
                var selectsy = $('#select-sy').val();
                var selectsem = $('#select-sem').val();
                var selectyear = $('#select-year').val();
                var selectmonth = $('#select-month').val();
                Swal.fire({
                        title: 'Generating...',
                        allowOutsideClick: false,
                        closeOnClickOutside: false,
                        onBeforeOpen: () => {
                            Swal.showLoading()
                        }
                })
                $.ajax({
                        url: '/setup/forms/sf2',
                        type: 'GET',
                        data: {
                            action: 'getsetups',
                            selectsy    : selectsy,
                            selectsem    : selectsem,
                            selectyear    : selectyear,
                            selectmonth    : selectmonth
                        },
                        success:function(data)
                        {
                            $('#div-results').empty()
                            $('#div-results').append(data)
                            $(".swal2-container").remove();
                            $('body').removeClass('swal2-shown')
                            $('body').removeClass('swal2-height-auto')
                        }
                }); 
            })
            $(document).on('click','.btn-lock', function(){
                var setupid = $(this).attr('data-setupid');
                var selectsy = $('#select-sy').val();
                var selectsem = $('#select-sem').val();
                var selectyear = $('#select-year').val();
                var selectmonth = $('#select-month').val();
                var thisbutton = $(this);
                $.ajax({
                        url: '/setup/forms/sf2',
                        type: 'GET',
                        data: {
                            action: 'updatelock',
                            setupid    : setupid,
                            selectsy    : selectsy,
                            selectsem    : selectsem,
                            selectyear    : selectyear,
                            selectmonth    : selectmonth
                        },
                        success:function(data)
                        {
                            if(thisbutton.hasClass('btn-outline-danger'))
                            {
                                thisbutton.empty()
                                thisbutton.append('<i class="fa fa-unlock"></i> &nbsp;&nbsp;Lock SF2')
                                thisbutton.addClass('btn-default')
                                thisbutton.removeClass('btn-outline-danger')
                                toastr.success('Setup unlocked!')
                            }else{
                                thisbutton.empty()
                                thisbutton.append('<i class="fa fa-lock"></i> Locked SF2')
                                thisbutton.addClass('btn-outline-danger')
                                thisbutton.removeClass('btn-default')
                                toastr.success('Setup locked!')
                            }
                        }
                }); 
            })
        })
   
    </script>
@endsection

                                        

                                        
                                        