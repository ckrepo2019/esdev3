
@extends('registrar.layouts.app')

@section('headerjavascript')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
@endsection
@section('content')
<!-- DataTables -->

    {{-- <section class="content-header">
        <div class="col-12">
            @if($academicprogram == 'elementary')
                <h4>Elementary</h4>
            @elseif($academicprogram == 'juniorhighschool')
                <h4>Junior High School</h4>
            @elseif($academicprogram == 'seniorhighschool')
                <h4>Senior High School</h4>
            @endif
        </div>
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                </div>
            </div>
        </div>
    </section> --}}
    <div class="row">
        <div class="col-12">
            <div class="card card-default color-palette-box">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fa fa-file"></i>
                        <strong>Learner's Permanent Academic Record</strong>
                    </h3>
                    <br>
                    <small><em>(Formerly Form 137)</em></small>
                    
                </div>
            </div>
        </div>
    </div>
    <div class="card shadow" style="border: none !important; box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;">
        <div class="card-body">
            <table id="example2" class="table table-hover" style="font-size: 12.5px;">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>ID Number</th>
                        <th>Student</th>
                        <th>Current Grade Level</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>   
        
        {{-- <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <strong>Students ({{count($students)}})</strong>
                </div>
            <div class="card-body">
                <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4" style="overflow: scroll">
                    <div class="row">
                        <div class="col-sm-12">
                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th style="width:5%">#</th>
                                        <th>Name</th>
                                        <th>Grade Level</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $count = 1;   
                                    @endphp
                                    @foreach($students as $student)
                                        <tr>
                                            <td class="text-center">{{$count}}</td>
                                            <td>{{$student->lastname}}, {{$student->firstname}} {{$student->middlename}}</td>
                                            <td>{{$student->levelname}}<br/><span class="right badge badge-default" style="border: 1px solid black;">{{$student->studentstatus}}</span></td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-info btn-block btn-view-record" data-id="{{$student->id}}" data-levelid="{{$student->levelid}}">View Record</button>
                                            </td>
                                        </tr>
                                        @php
                                            $count += 1;   
                                        @endphp
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
    <div class="modal fade" id="show-academicprogram" aria-hidden="true" style="display: none;">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Academic Program</h4>
            <button type="button" id="closeremarks" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body" id="acadprog-selection">
              
          </div>
          
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    {{-- </div> --}}
    <!-- jQuery -->
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
        
        $(function () {
            
            $('#example2').DataTable({
              "paging": true,
              "lengthChange": true,
              "searching": true,
              "ordering": false,
              "info": true,
              "autoWidth": false,
              "responsive": true,
            });
          });

          $(document).ready(function(){
            //   $('.paginate_button').addClass('btn btn-default')
            //   $('.paginate_button').on('click', function(){
            //   $('.paginate_button').addClass('btn btn-default')
            //   $(this).removeClass('btn-default')
            //   $(this).addClass('btn-primary')
            //   })

              $(document).on('click','.btn-view-record', function(){
                  var studentid = $(this).attr('data-id')
                  var levelid = $(this).attr('data-levelid')
                  
                  $('#show-academicprogram').modal('show')
                  $.ajax({
                    url: '/reports_schoolform10/selectacadprog',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        studentid   :studentid,
                        levelid        : levelid
                    }, success:function(data)
                    {
                        $('#acadprog-selection').empty();
                        if(data.length == 0)
                        {
                            $('#acadprog-selection').append(
                                '<div class="row"><div class="col-12"><h3>No Existing Data</h3></div></div>'
                            );
                        }else{
                            $.each(data, function(key,value){
                            $('#acadprog-selection').append(
                                '<div class="row mb-2"><div class="col-12"><a href="/reports_schoolform10/view?studentid='+studentid+'&acadprogid='+value.id+'&acadprogname='+value.description+'" type="button" class="btn btn-lg btn-default btn-block">'+value.description+'</a></div></div>'
                            );
                            })
                        }
                    }
                })
              })
    var onerror_url = @json(asset('dist/img/download.png'));
    function getemployees(){
        
        $('#example2').DataTable({
            // "paging": false,
            // "lengthChange": false,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "destroy": true,
            serverSide: true,
            processing: true,
            // ajax:'/student/preregistration/list',
            ajax:{
                url: '/reports_schoolform10/index',
                type: 'GET',
                data: {
                    action : 'getstudents'
                }
            },
            columns: [
                { "data": null },
                { "data": null },
                { "data": null },
                { "data": null },
                { "data": null }
            ],
            columnDefs: [
                {
                    'targets': 0,
                    'orderable': false, 
                    'createdCell':  function (td, cellData, rowData, row, col) {
                        $(td)[0].innerHTML = rowData.no;
                            // $(td).addClass('align-middle')
                    }
                },
                {
                    'targets': 1,
                    'orderable': false, 
                    'createdCell':  function (td, cellData, rowData, row, col) {
                        $(td)[0].innerHTML = rowData.sid
                            $(td).addClass('align-middle')
                    }
                },
                {
                    'targets': 2,
                    'orderable': false, 
                    'createdCell':  function (td, cellData, rowData, row, col) {
                        $(td)[0].innerHTML = rowData.lastname+', '+rowData.firstname;
                            $(td).addClass('align-middle')
                    }
                },
                {
                    'targets': 3,
                    'orderable': false, 
                    'createdCell':  function (td, cellData, rowData, row, col) {
                        $(td)[0].innerHTML = rowData.levelname
                            $(td).addClass('align-middle')
                    }
                },
                {
                    'targets': 4,
                    'orderable': false, 
                    'createdCell':  function (td, cellData, rowData, row, col) {
                        $(td)[0].innerHTML = '<button type="button" class="btn btn-sm btn-info btn-block btn-view-record" data-id="'+rowData.id+'" data-levelid="'+rowData.levelid+'">View Record</button>'
                            $(td).addClass('align-middle')
                    }
                }
            ]
        });
    }
    getemployees();
          })
   
    </script>
@endsection

                                        

                                        
                                        