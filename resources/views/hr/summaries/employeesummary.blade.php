

@extends('hr.layouts.app')
@section('content')
<!-- DataTables -->
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<!-- daterange picker -->
<link rel="stylesheet" href="{{asset('plugins/daterangepicker/daterangepicker.css')}}">
<link rel="stylesheet" href="{{asset('plugins/toastr/toastr.min.css')}}">
<!-- SweetAlert2 -->
<link rel="stylesheet" href="{{asset('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css')}}">
<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <!-- <h1>Payroll</h1> -->
          <h4 class="text-warning" style="text-shadow: 1px 1px 1px #000000">
            <!-- <i class="fa fa-chart-line nav-icon"></i>  -->
            Employees</h4>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/home">Home</a></li>
            <li class="breadcrumb-item active">Reports</li>
            <li class="breadcrumb-item active">Employees</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
  <div class="card" style="border: none;">
      {{-- <div class="card-header">
        <label>Filter <i class="fa fa-filter"></i></label>
      </div> --}}
      <div class="card-body">
          <div class="row">
              <div class="col-md-3">
                <label>Department</label>
                <select id="selecteddepartment" class="form-control form-control" style="border: none; border-bottom: 1px solid #ddd">
                    <option value="">ALL</option>
                    @if(count($departments))
                        @foreach($departments as $department)
                            <option value="{{$department->id}}">{{strtoupper($department->department)}}</option>
                        @endforeach
                    @endif
                </select>
              </div>
              <div class="col-md-3">
                <label>Designation</label>
                <select id="selecteddesignation" class="form-control form-control" style="border: none; border-bottom: 1px solid #ddd">
                    <option value="">ALL</option>
                    @if(count($designations))
                        @foreach($designations as $designation)
                            <option value="{{$designation->id}}">{{strtoupper($designation->utype)}}</option>
                        @endforeach
                    @endif
                </select>
              </div>
              <div class="col-md-3">
                {{-- // 1 = casual; 2 = prov; 3 = regu;4 = parttime; 5 = substitute --}}
                <label>Employment Status</label>
                <select id="selectedstatus" class="form-control form-control" style="border: none; border-bottom: 1px solid #ddd">
                    <option value="">ALL</option>
                    <option value="1">CASUAL</option>
                    <option value="2">PROVISIONARY</option>
                    <option value="3">REGULAR</option>
                    <option value="4">PART-TIME</option>
                    <option value="5">SUBSTITUTE</option>
                </select>
              </div>
              <div class="col-md-3">
                <label>Gender</label>
                <select id="selectedgender" class="form-control form-control" style="border: none; border-bottom: 1px solid #ddd">
                    <option value="">ALL</option>
                    <option value="male">MALE</option>
                    <option value="female">FEMALE</option>
                </select>
              </div>
          </div>
          <div class="row mt-2">
              <div class="col-md-12">
                  <button type="button" id="btn-generate" class="btn btn-primary float-right"><i class="fa fa-sync"></i> Generate</button>
              </div>
          </div>
      </div>
  </div>
  <div id="resultscontainer">
  </div>
  <!-- Bootstrap 4 -->
  <script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
  <!-- SweetAlert2 -->
  <script src="{{asset('plugins/sweetalert2/sweetalert2.min.js')}}"></script>
  <!-- ChartJS -->
  <script src="{{asset('plugins/chart.js/Chart.min.js')}}"></script>
  <!-- DataTables -->
  <script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>
  <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
  <script src="{{asset('assets/scripts/gijgo.min.js')}}" ></script>
  <script src="{{asset('plugins/moment/moment.min.js')}}"></script>
  <!-- Toastr -->
  <script src="{{asset('plugins/toastr/toastr.min.js')}}"></script>
  <!-- date-range-picker -->
  <script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
  <!-- Bootstrap Switch -->
  <script src="{{asset('plugins/bootstrap-switch/js/bootstrap-switch.min.js')}}"></script>
  <script>
      $(document).ready(function(){
        // var selecteddepartment = null;
        // var selecteddesignation = null;
        // var selectedstatus = null;
        // var selectedgender = null;
        // $('#selecteddepartment').on('change', function(){
            
        //     $.ajax({
        //         url: '/hrreports/summaryofemployees/getdesignations',
        //         type: 'GET',
        //         data: {
        //             selecteddepartment      : $('#selecteddepartment').val()
        //         },
        //         success:function(data){
        //             $('#selecteddesignation').empty()
        //             $('#selecteddesignation').append(
        //                 '<option value="">ALL</option>'
        //             )
        //             if(data.length > 0)
        //             {
        //                 $.each(data, function(key, value){
        //                     $('#selecteddesignation').append(
        //                         '<option value="'+value.id+'">'+value.utype+'</option>'
        //                     )
        //                 })
        //             }
        //         }
        //     })
        //     })
        $('#btn-generate').on('click', function(){
            var selecteddepartment  = $('#selecteddepartment').val();
            var selecteddesignation = $('#selecteddesignation').val();
            var selectedstatus      = $('#selectedstatus').val();
            var selectedgender      = $('#selectedgender').val();
            
            Swal.fire({
                title: 'Fetching data...',
                onBeforeOpen: () => {
                    Swal.showLoading()
                },
                allowOutsideClick: false
            })

            $.ajax({
                url: '/hrreports/summaryofemployees/filter',
                type: 'GET',
                data: {
                    selecteddepartment      : selecteddepartment,
                    selecteddesignation     : selecteddesignation, 
                    selectedstatus          : selectedstatus, 
                    selectedgender          : selectedgender
                },
                success:function(data){
                    $('#resultscontainer').empty();
                    $('#resultscontainer').append(data)
                    var tablecontainer = $("#example1").DataTable({
                        // pageLength : 10,
                        // lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Show All']]
                        // aLengthMenu: [
                        //     [25, 50, 100, 200, -1],
                        //     [25, 50, 100, 200, "All"]
                        // ],
                        // iDisplayLength: -1,
                        "order": [[ 1, 'asc' ]],
                        "paging": true,
                        "lengthChange": true,
                        "searching": true,
                        "ordering": false,
                        "info": true,
                        "autoWidth": false,
                        "responsive": true,
                    });
                    tablecontainer.on( 'order.dt search.dt', function () {
                        tablecontainer.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                            cell.innerHTML = i+1;
                        } );
                    } ).draw();
                    tablecontainer.order([2, 'asc']).draw();
                    
                    $(".swal2-container").remove();
                    $('body').removeClass('swal2-shown')
                    $('body').removeClass('swal2-height-auto')
                }
            })
        })
      })
  </script>
@endsection

