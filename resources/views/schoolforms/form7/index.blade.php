
@extends($extends)

@section('headerjavascript')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
@endsection
@section('content')
<style>
    
    .select2-container--default .select2-selection--single .select2-selection__choice {
        background-color: #007bff;
        border-color: #006fe6;
        color: #fff;
        padding: 0 10px;
        margin-top: .31rem;
    }
    .select2-container--default .select2-selection--single .select2-selection__choice__remove {
        color: #fff;
    }
    .dtr-title, .dtr-data{
        color: black;
    }
    </style>
    <style>
    thead{
        background-color: #eee !important;
    }
    
    
    #thetable thead th:first-child  { 
        position: sticky; 
        left: 0; 
        background-color: #fff; 
        outline: 2px solid #dee2e6;
        outline-offset: -1px;
        z-index: 9999;
    }

    #thetable tbody td:first-child  {  
        position: sticky; 
        left: 0; 
        background-color: #fff; 
        background-color: #fff; 
        outline: 2px solid #dee2e6;
        outline-offset: -1px;
    }


</style>

    <section class="content">
        <div class="container-fluid">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">School Form 7</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                                <li class="breadcrumb-item active">School Form 7</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div>
            </section>
        </div>
    </section>
    <div class="card shadow" style="box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important; border: none;">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-2" hidden>
                    <label>Department(s)</label>
                    <select class="form-control form-control-sm select2" multiple="multiple" id="select-departmentid">
                        @foreach($departments as $eachdepartment)
                            <option value="{{$eachdepartment->id}}">{{$eachdepartment->department}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-2" >
                    <label>Grade Level(s)</label> <small>(This selection is for the schedule filtering only)</small>
                    <select class="form-control form-control-sm select2" multiple="multiple" id="select-levelid">
                        @foreach($gradelevels as $eachlevel)
                            <option value="{{$eachlevel->id}}">{{$eachlevel->levelname}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6" >
                    <label>School Year</label> <small>(This selection is for the schedule filtering only)</small>
                    <select class="form-control form-control-sm select2" id="select-syid">
                        @foreach(DB::table('sy')->get() as $eachsy)
                            <option value="{{$eachsy->id}}" @if($eachsy->isactive == 1) selected @endif>{{$eachsy->sydesc}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-12 align-self-end text-right mt-2">
                    {{-- <button type="button" class="btn btn-primary" id="btn-show-results"><i class="fa fa-sync"></i> Get results</button> --}}
                    <button type="button" class="btn btn-primary" id="btn-export-results"><i class="fa fa-download"></i> Export to Excel</button>
                </div>
            </div>
        </div>
    </div>  
    <div class="card shadow" style="box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important; border: none;">
        {{-- <div class="card-header text-right">
            <button type="button" class="btn btn-primary" id="btn-export-results"><i class="fa fa-download"></i> Export to Excel</button>
        </div> --}}
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <table id="thetable" class="table text-nowrap table-bordered" style="font-size: 12.5px;">
                      <thead>
                        <tr>
                          <th>Name of School Personnel<br/>
                              (Arrange by Position, Descending) </th>
                          <th>Employee
                              No.<br/>(or Tax
                              Identification<br/>
                              Number -
                              T.I.N.)</th>
                          <th>Sex</th>
                          <th>Fund
                              Source</th>
                          <th>Position/
                              Designation</th>
                          <th>Nature of
                              Appointment/<br/>
                              Employment
                              Status</th>
                          <th>Degree / Post
                              Graduate</th>
                          <th>Major/
                              Specialization</th>
                          <th>Minor</th>
                          <th>Subject Taught<br/>
                              (include Grade &
                              Section),<br/>Advisory Class
                              & Other<br/>Ancillary
                              Assignments</th>
                              <th>DAY
                                  (M/T/W/
                                  TH/F)</th>
                              <th>From
                                  (00:00)</th>
                              <th>To
                                  (00:00)</th>
                              <th>Total Actual
                                  Teaching<br/>
                                  Minutes per
                                  Week</th>
                          <th>Remarks<br/>(For
                              Detailed Items,<br/>
                              Indicate name of
                              school/office,<br/>For
                              IP's -Ethnicity)</th>
                        </tr>
                      </thead>
                      <tbody style="color: black;">
                      </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div> 
    {{-- <div id="container-results">
    </div>   --}}
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
    <script src="{{asset('js/rowsGroup.js')}}"></script>
    <script>
        $(document).ready(function(){
    function getemployees(){
        var syid = $('#select-syid').val()
        var month   = $('#select-month').val()
        $('#thetable').DataTable({
            // "paging": false,
            // "lengthChange": false,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": false,
    fixedColumns: true,
    scrollY:        500,
    scrollX:        true,
    scrollCollapse: true,
        fixedColumns:   {
            left: 1,
            right: 1
        },
            "responsive": false,
            "destroy": true,
            serverSide: true,
            processing: true,
            // ajax:'/student/preregistration/list',
            ajax:{
                url: '/schoolform/sf7',
                type: 'GET',
                data: {
                    action : 'filter',
                    departmentids     :  JSON.stringify($('#select-departmentid').val()),
                    levelids     :  JSON.stringify($('#select-levelid').val()),
                    syid     :  syid,
                    month       :  month
                },dataSrc: function ( json ) {
                                  
                        return json.data;
                }
            },
            columns: [
                {
                    name: 'first',
                    // title: 'ID',
                },
                {
                    name: 'second',
                    // title: 'Name',
                },
                {
                    name: 'third',
                }, 
                {
                    name: 'fourth',
                },
                {
                    name: 'fifth',
                },
                {
                    name: 'sixth',
                },
                {
                    name: 'seventh',
                },
                {
                    name: 'eighth',
                },
                {
                    name: 'ninth',
                },
                {
                    name: 'tenth',
                },
                {
                    name: 'eleventh',
                },
                {
                    name: 'twelfth',
                },
                {
                    name: 'thirteenth',
                },
                {
                    name: 'fourteenth',
                },
                {
                    name: 'fifteenth',
                },
            ],
            rowsGroup: [
            'first:name',
            'second:name',
            'third:name',
            'fourth:name',
            'fifth:name',
            'sixth:name',
            'seventh:name',
            'eighth:name',
            'ninth:name',
            'tenth:name',
            'eleventh:name',
            'twelfth:name',
            'thirteenth:name',
            'fourteenth:name',
            'fifteenth:name',
            ]
            // columns: [
            //     { "data": null },
            //     { "data": null },
            //     { "data": null },
            //     { "data": null },
            //     { "data": null },
            //     { "data": null },
            //     { "data": null },
            //     { "data": null },
            //     { "data": null },
            //     { "data": null },
            //     { "data": null },
            //     { "data": null },
            //     { "data": null },
            //     { "data": null },
            //     { "data": null }
            // ],
            // columnDefs: [
            //     {
            //         'width': "20px",
            //         'targets': 0,
            //         'orderable': false, 
            //         'createdCell':  function (td, cellData, rowData, row, col) {
            //             $(td)[0].innerHTML = rowData.lastname+', '+rowData.firstname;
            //         }
            //     },
            //     {
            //         'targets': 1,
            //         'orderable': false, 
            //         'createdCell':  function (td, cellData, rowData, row, col) {
            //             $(td)[0].innerHTML = rowData.tid
            //                 // $(td).addClass('align-middle')
            //         }
            //     },
            //     {
            //         'targets': 2,
            //         'orderable': false, 
            //         'createdCell':  function (td, cellData, rowData, row, col) {
            //             $(td)[0].innerHTML = rowData.gender != null ? rowData.gender[0].toUpperCase() : '';
            //         }
            //     },
            //     {
            //         'targets': 3,
            //         'orderable': false, 
            //         'createdCell':  function (td, cellData, rowData, row, col) {
            //             $(td)[0].innerHTML = '';
            //         }
            //     },
            //     {
            //         'targets': 4,
            //         'orderable': false, 
            //         'createdCell':  function (td, cellData, rowData, row, col) {
            //             $(td)[0].innerHTML = '';
            //         }
            //     },
            //     {
            //         'targets': 5,
            //         'orderable': false, 
            //         'createdCell':  function (td, cellData, rowData, row, col) {
            //             $(td)[0].innerHTML = '';
            //         }
            //     },
            //     {
            //         'targets': 6,
            //         'orderable': false, 
            //         'createdCell':  function (td, cellData, rowData, row, col) {
            //             $(td)[0].innerHTML = '';
            //         }
            //     },
            //     {
            //         'targets': 7,
            //         'orderable': false, 
            //         'createdCell':  function (td, cellData, rowData, row, col) {
            //             $(td)[0].innerHTML = '';
            //         }
            //     },
            //     {
            //         'targets': 8,
            //         'orderable': false, 
            //         'createdCell':  function (td, cellData, rowData, row, col) {
            //             $(td)[0].innerHTML = '';
            //         }
            //     },
            //     {
            //         'targets': 9,
            //         'orderable': false, 
            //         'createdCell':  function (td, cellData, rowData, row, col) {
            //             $(td)[0].innerHTML = '';
            //         }
            //     },
            //     {
            //         'targets': 10,
            //         'orderable': false, 
            //         'createdCell':  function (td, cellData, rowData, row, col) {
            //             $(td)[0].innerHTML = '';
            //         }
            //     },
            //     {
            //         'targets': 11,
            //         'orderable': false, 
            //         'createdCell':  function (td, cellData, rowData, row, col) {
            //             $(td)[0].innerHTML = '';
            //         }
            //     },
            //     {
            //         'targets': 12,
            //         'orderable': false, 
            //         'createdCell':  function (td, cellData, rowData, row, col) {
            //             $(td)[0].innerHTML = '';
            //         }
            //     },
            //     {
            //         'targets': 13,
            //         'orderable': false, 
            //         'createdCell':  function (td, cellData, rowData, row, col) {
            //             $(td)[0].innerHTML = '';
            //         }
            //     },
            //     {
            //         'targets': 14,
            //         'orderable': false, 
            //         'createdCell':  function (td, cellData, rowData, row, col) {
            //             $(td)[0].innerHTML = '';
            //         }
            //     }
            // ]
        });
    }
    getemployees();
            $('.select2').select2({
                theme: 'bootstrap4'
            })
            $('#btn-show-results').on('click', function(){
                var syid = $('#select-syid').val()
                var month   = $('#select-month').val()
                Swal.fire({
                    title: 'Fetching data...',
                    onBeforeOpen: () => {
                        Swal.showLoading()
                    },
                    allowOutsideClick: false
                })
                $.ajax({
                    url: '/schoolform/sf7',
                    type:'GET',
                    data: {
                        action      :  'filter',
                        syid     :  syid,
                        month       :  month
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
            $('#select-departmentid').on('change', function(){
                getemployees();
            })
            $('#select-levelid').on('change', function(){
                getemployees();
            })
            $(document).on('click','#btn-exporttopdf', function(){  
                var syid = $('#select-syid').val()
                var month   = $('#select-month').val()              
                window.open("/schoolform/sf7?action=export&month="+month+"&syid="+syid,'_blank');
            })
            $(document).on('click','#btn-export-results', function(){  
                var syid = $('#select-syid').val()
                var month   = $('#select-month').val()      
                
                var departmentids  =  JSON.stringify($('#select-departmentid').val());
                var levelids       =  JSON.stringify($('#select-levelid').val());  
                window.open("/schoolform/sf7?action=export&month="+month+"&syid="+syid+'&levelids='+levelids+'&departmentids='+departmentids,'_blank');
            })
        })
    </script>
@endsection

                                        

                                        
                                        