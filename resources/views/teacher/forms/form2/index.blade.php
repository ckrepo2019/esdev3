

@extends('teacher.layouts.app')
@section('content')
<!-- DataTables -->
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<!-- daterange picker -->
<link rel="stylesheet" href="{{asset('plugins/daterangepicker/daterangepicker.css')}}">
<link rel="stylesheet" href="{{asset('plugins/toastr/toastr.min.css')}}">
<!-- SweetAlert2 -->
<link rel="stylesheet" href="{{asset('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css')}}">
@if(strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'hccsi' && strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'sjchssi')
  <style>

.tableFixHead       { overflow-y: auto; height: 500px; }

/* #studentstable { border-collapse: collapse; width: 100%; } */

#studentstable th,
#studentstable td    { /* padding: 8px 16px; */ }

#studentstable th    { position: sticky; top: 0; background-color: #eee; z-index: 100;}
#studentstable thead{
    background-color: #eee !important;
    z-index: 100;
}

#studentstable                    {width:100%; font-size:12px;; stext-transform: uppercase; }

/* .table thead th:first-child { position: sticky; left: 0; background-color: #fff;
z-index: 9999999 } */
#studentstable thead{

position: sticky;
top: 0;
}
#studentstable thead th:first-child  { 
position: sticky; 
left: 0; 
background-color: #fff; 
outline: 2px solid #dee2e6;
outline-offset: -1px;
z-index: 999 !important
}
#studentstable thead th:last-child  { 
position: sticky !important; 
right: 0; 
background-color: #fff; 
outline: 2px solid #dee2e6;
outline-offset: -1px;
z-index: 999 !important
}
/* .table thead {

z-index: 999
} */

#studentstable tbody td:last-child  { 
position: sticky; 
right: 0; 
background-color: #fff; 
outline: 2px solid #dee2e6;
outline-offset: -1px;
/* z-index: 999 */
}
#studentstable tfoot td:last-child  { 
position: sticky; 
right: 0; 
background-color: #fff; 
outline: 2px solid #dee2e6;
outline-offset: -1px;
/* z-index: 999 */
}

#studentstable td:first-child, #studentstable th:first-child {
position:sticky;
left:0;
z-index:1;
background-color:white;
}
#studentstable td:nth-child(2),
#studentstable th:nth-child(2)  { 
position:sticky;
left:24px;
z-index:1;
background-color:white;
}
#studentstable td:nth-last-child(2),
#studentstable th:nth-last-child(2)  { 
position:sticky;
right:72px;
z-index:1;
background-color:gold !important;
}
#studentstable td:nth-last-child(3),
#studentstable th:nth-last-child(3)  { 
position:sticky;
right:135px;
z-index:1;
background-color: darksalmon !important;
}

#studentstable th:nth-last-child(3),
#studentstable th:nth-last-child(2)  { 
z-index: 999 !important;
}



#studentstable tbody td:first-child  {  
position: sticky; 
left: 0; 
background-color: #fff; 
/* width: 150px !important; */
background-color: #fff; 
outline: 2px solid #dee2e6;
outline-offset: -1px;
}

#studentstable thead th:first-child  { 
    position: sticky; left: 0; 
    /* width: 150px !important; */
    background-color: #fff; 
    outline: 2px solid #dee2e6;
    outline-offset: -1px;
}

  </style>
@endif
<style>
    
    /* input[type=radio]                   { visibility: hidden; position: relative;width: 20px; height: 20px; }

    input[type=radio].present:before    { content: "";visibility: visible;position: absolute;border: 1px solid black;border-radius: 50%;top: 0;right: 0;bottom: 0;left: 0; }

    input[type=radio].late:before       { content: "";visibility: visible;position: absolute;border: 1px solid black;border-radius: 50%;top: 0;right: 0;bottom: 0;left: 0;padding: 0; }

    input[type=radio].halfday:before    { content: "";visibility: visible;position: absolute;border: 1px solid black;border-radius: 50%;top: 0;right: 0;bottom: 0;left: 0; }

    input[type=radio].absent:before     { content: "";visibility: visible;position: absolute;border: 1px solid black;border-radius: 50%;top: 0;right: 0;bottom: 0;left: 0; }

    input[type=radio].present:checked:before    { font-family: "Font Awesome 5 Free";content: "\f00c";color: green;font-size: 20px;border: 1px solid white; }

    input[type=radio].late:checked:before       { background-color: gold; }

    input[type=radio].halfday:checked:before    { background-color: #6c757d; }

    input[type=radio].absent:checked:before     { font-family: "Font Awesome 5 Pro", "Font Awesome 5 Free";content: "\f00d";color: red;font-size: 20px;border: 1px solid white; }

    td                  { text-transform: uppercase !important; } */
.crashedout{
        color: red;
        text-decoration: line-through;
    }

    
.btn-glow {
  box-shadow: 0 0 0 0 #28a745;
  animation: pulse-green 2s infinite;
}

@keyframes pulse-green {
  0% {
    transform: scale(0.95);
    box-shadow: 0 0 0 0 #28a745;
  }
  
  70% {
    transform: scale(1);
    box-shadow: 0 0 0 10px rgba(51, 217, 178, 0);
  }
  
  100% {
    transform: scale(0.95);
    box-shadow: 0 0 0 0 rgba(51, 217, 178, 0);
  }
}


#modal-view-advisoryatt .modal {
        position: fixed;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        overflow: hidden;
    }

    #modal-view-advisoryatt .modal-dialog {
        position: fixed;
        margin: 0;
        width: 100%;
        height: 100%;
        padding: 0;
    }
    @media (min-width: 576px)
    {
        #modal-view-advisoryatt .modal-dialog {
            max-width:  unset !important;
            margin: unset !important;
        }
    }
    #modal-view-advisoryatt .modal-content {
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        border: 2px solid #3c7dcf;
        border-radius: 0;
        box-shadow: none;
    }

    #modal-view-advisoryatt .modal-header {
        position: absolute;
        top: 0;
        right: 0;
        left: 0;
        height: 50px;
        padding: 10px;
        background: #6598d9;
        border: 0;
    }

    #modal-view-advisoryatt .modal-title {
        font-weight: 300;
        font-size: 2em;
        color: #fff;
        line-height: 30px;
    }

    #modal-view-advisoryatt .modal-body {
        position: absolute;
        top: 50px;
        bottom: 60px;
        width: 100%;
        font-weight: 300;
        overflow: auto;
        background-color: rgba(0,0,0,.0001) !important;
    }
    #modal-view-advisoryatt .modal-footer {
        position: absolute;
        right: 0;
        bottom: 0;
        left: 0;
        height: 60px;
        padding: 10px;
        background: #f1f3f5;
    }
    
.save-button  {
  -webkit-border-radius: 10px;
  border-radius: 10px;
  border: none;
  color: #FFFFFF;
  cursor: pointer;
  display: inline-block;
  /* font-family: Arial;
  font-size: 20px; */
  /* padding: 5px 10px; */
  text-align: center;
  text-decoration: none;
  -webkit-animation: saveglowing 1500ms infinite !important;
  -moz-animation: saveglowing 1500ms infinite !important;
  -o-animation: saveglowing 1500ms infinite !important;
  animation: saveglowing 1500ms infinite !important;
}
@-webkit-keyframes saveglowing {
  0% { background-color: #007bff; -webkit-box-shadow: 0 0 3px #007bff; }
  50% { background-color: #007bff; -webkit-box-shadow: 0 0 40px #007bff; }
  100% { background-color: #007bff; -webkit-box-shadow: 0 0 3px #007bff; }
}

@-moz-keyframes saveglowing {
  0% { background-color: #007bff; -moz-box-shadow: 0 0 3px #2e3133; }
  50% { background-color: #007bff; -moz-box-shadow: 0 0 40px #007bff; }
  100% { background-color: #007bff; -moz-box-shadow: 0 0 3px #007bff; }
}

@-o-keyframes saveglowing {
  0% { background-color: #007bff; box-shadow: 0 0 3px #007bff; }
  50% { background-color: #007bff; box-shadow: 0 0 40px #007bff; }
  100% { background-color: #007bff; box-shadow: 0 0 3px #007bff; }
}

@keyframes saveglowing {
  0% { background-color: #007bff; box-shadow: 0 0 3px #007bff; }
  50% { background-color: #007bff; box-shadow: 0 0 40px #007bff; }
  100% { background-color: #007bff; box-shadow: 0 0 3px #007bff; }
}
</style>
@php
  $year = ((explode('-',DB::table('sy')->where('isactive','1')->first()->sydesc)[1])+1) ?? date('Y')+1;
@endphp
<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <!-- <h1>Payroll</h1> -->
          <h4 class="text-warning" style="text-shadow: 1px 1px 1px #000000">
            <!-- <i class="fa fa-chart-line nav-icon"></i>  -->
            School Form 2</h4>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/home">Home</a></li>
            <li class="breadcrumb-item active">School Form 2</li>
            {{-- <li class="breadcrumb-item active">Employees</li> --}}
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
  <span id="sectionid" data-id="{{$sectionid}}"></span>
  <div class="row">
      <div class="col-md-12">
          <div class="card">
              <div class="card-header">
                <div class="row">
                  <div class="col-md-8">
                    <h5>{{DB::table('gradelevel')->where('id', $levelid)->first()->levelname}} - {{DB::table('sections')->where('id', $sectionid)->first()->sectionname}}</h5>                    
                  </div>
                  <div class="col-md-4 text-right">
                    @if($semid != 0)
                    <h5>{{DB::table('semester')->where('id', $semid)->first()->semester}}</h5> 
                    @endif                   
                  </div>
                </div>
              </div>
              <div class="card-body">
                  <div class="row mb-2">
                    <div class="col-md-3">
                      <label>LACT</label>
                      <select class="form-control" style="border: none; border-bottom: 1px solid #ddd" id="selectedlact">
                          <option value="1">1</option>
                          <option value="2">2</option>
                          <option value="3">3</option>
                      </select>
                    </div>
                    <div class="col-md-3">
                      <label>Year</label>
                      <select class="form-control" style="border: none; border-bottom: 1px solid #ddd" id="selectedyear">
                          @php
							$selectedSY = \Carbon\Carbon::now('Asia/Manila')->isoFormat('YYYY');
						  @endphp
						  @for($to = $year; 2000<$to; $to--)
                            <option {{$selectedSY == $to ? 'selected' : ''}} value="{{$to}}">{{$to}}</option>
                          @endfor
                      </select>
                    </div>
                      <div class="col-md-3">
                        <label>Month</label>
                        <select id="selectedmonth" class="form-control form-control" style="border: none; border-bottom: 1px solid #ddd">
                                <option value="01" {{'01' == $selectedmonth ? 'selected' : ''}}>January</option>
                                <option value="02" {{'02' == $selectedmonth ? 'selected' : ''}}>February</option>
                                <option value="03" {{'03' == $selectedmonth ? 'selected' : ''}}>March</option>
                                <option value="04" {{'04' == $selectedmonth ? 'selected' : ''}}>April</option>
                                <option value="05" {{'05' == $selectedmonth ? 'selected' : ''}}>May</option>
                                <option value="06" {{'06' == $selectedmonth ? 'selected' : ''}}>June</option>
                                <option value="07" {{'07' == $selectedmonth ? 'selected' : ''}}>July</option>
                                <option value="08" {{'08' == $selectedmonth ? 'selected' : ''}}>August</option>
                                <option value="09" {{'09' == $selectedmonth ? 'selected' : ''}}>September</option>
                                <option value="10" {{'10' == $selectedmonth ? 'selected' : ''}}>October</option>
                                <option value="11" {{'11' == $selectedmonth ? 'selected' : ''}}>November</option>
                                <option value="12" {{'12' == $selectedmonth ? 'selected' : ''}}>December</option>
                        </select>
                      </div>
                      <div class="col-md-3">
                          <label>&nbsp;</label>
                          <br/>
                            <button type="button" id="btn-generate" class="btn btn-sm btn-primary btn-block"><i class="fa fa-sync"></i> Generate</button>
                      </div>
                  </div>
                  <div class="row mb-2" id="div-lact12">
                        <div class="col-md-8">
						
                          @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hccsi' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sjchssi')
							<button type="button" id="btn-locksf2" class="btn btn-sm btn-outline-default mr-2" hidden disabled><i class="fa fa-lock"></i> School Form 2 locked</button>
                          @endif
                          {{-- <button type="button" id="btn-setup-delete" class="btn btn-sm btn-outline-danger mr-2 warning" hidden><i class="fa fa-trash-alt"></i> Delete Setup</button>
								<button type="button" id="btn-reselect-setup" class="btn btn-sm btn-outline-warning mr-2 warning" hidden><i class="fa fa-redo"></i> Reselect Dates</button> --}}
                          @if(count($setup) > 0)
                          @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'hccsi' && strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'sjchssi')
                          
                          {{-- <button type="button" id="btn-advisoryatt" class="btn btn-sm btn-outline-info mr-2" hidden><i class="fa fa-list"></i> Advisory Attendance</button> --}}
                          @endif
                          {{-- @else
                          <button type="button" id="btn-view-setup" class="btn btn-sm btn-danger mr-2"><i class="fa fa-trash"></i> Setup</button>  --}}
                          @endif
                        </div>
                        <div class="col-md-4 text-right">
                          @if($strandid == null)
                            @if(count($setup) == 0)
                              <button type="button" id="btn-printpdf" class="btn btn-sm btn-default" hidden><i class="fa fa-file-pdf"></i> PDF</button>
                                {{-- <button type="button" id="btn-printexcel" class="btn btn-sm btn-default" hidden><i class="fa fa-file-excel"></i> Excel</button> --}}
                            @else
                            <button type="button" id="btn-printpdf" class="btn btn-sm btn-default" ><i class="fa fa-file-pdf"></i> PDF</button>
                            {{-- <button type="button" id="btn-printexcel" class="btn btn-sm btn-default" ><i class="fa fa-file-excel"></i> Excel</button> --}}
                            @endif
                          @else
                            @if(count($setup) == 0)
                              <button type="button" id="btn-printpdf" class="btn btn-sm btn-default" hidden><i class="fa fa-file-pdf"></i> PDF</button>
                            <button type="button" id="btn-printpdf" class="btn btn-sm btn-default" ><i class="fa fa-file-pdf"></i> PDF</button>
                            @else
								<button type="button" id="btn-printpdf" class="btn btn-sm btn-default" hidden><i class="fa fa-file-pdf"></i> PDF</button>
                            @endif
                          @endif
                        </div>
                  </div>
                  @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'hccsi' && strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'sjchssi')
                  <div class="row mt-2" id="resultscontainer">
                  </div>
                  @endif
              </div>
          </div>
      </div>
  </div>

  @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hccsi' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sjchssi')
  <div id="resultscontainer" style="width:100%"></div>
@endif
  <div class="modal fade" id="show-calendar" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Create Setup</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <em class="text-success">Note: Please click dates to add to the setup!</em>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12" id="calendar-container"></div>
                {{-- <div class="col-md-12" >
                    <label>Course: (for TVL only)</label>
                    <input type="text" class="form-control" id="input-tvlcourse"/>
                </div> --}}
            </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default btn-close-modal" data-dismiss="modal" >Close</button>
          <button type="button" id="btn-submit-setup" class="btn btn-primary">Create</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  
  <div class="modal fade" id="show-setup" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Delete Setup</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <em class="text-success">Note: No important data will be affected by deleting this setup!</em>
                </div>
            </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="button" id="btn-delete-setup" class="btn btn-danger">Delete</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <div class="modal fade" id="show-reselectdays" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Reselect dates</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body" id="table-selecteddates">
          
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default btn-close" data-dismiss="modal">Close</button>
          <button type="button" id="btn-update-setup" class="btn btn-primary">Save Changes</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <div class="modal fade" id="show-remark" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Remarks</h4>
          <button type="button" id="closeremarks" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                  <textarea id="text-area-remark" class="form-control">

                  </textarea>
                </div>
            </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default btn-close" data-dismiss="modal">Close</button>
          <button type="button" id="btn-submit-remarks" class="btn btn-primary">Save Changes</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <div class="modal fade" id="modal-view-advisoryatt">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Advisory Attendance</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" id="advisoryatt-container">
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          {{-- <button type="button" class="btn btn-primary" id="btn-edit-submit" disabled>We're still working on this page!</button> --}}
          <button type="button" class="btn btn-primary save-button" id="btn-save-attendance">Save changes</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  @endsection
  @section('footerscripts')
  <!-- Bootstrap 4 -->
  {{-- <script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
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
  <script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script> --}}
  <script src="{{asset('plugins/toastr/toastr.min.js')}}"></script>
  <!-- Bootstrap Switch -->
  <script src="{{asset('plugins/bootstrap-switch/js/bootstrap-switch.min.js')}}"></script>
  <script>
      
      $('#div-lact12').hide();
        var selecteddates = [];
        $(document).ready(function(){
          $('#selectedlact').on('change', function(){
            $('#resultscontainer').empty()
            selecteddates = [];
            $('#div-lact12').hide();
          })
          $('#selectedyear').on('change', function(){
            $('#resultscontainer').empty()
            selecteddates = [];
            $('#div-lact12').hide();
          })
          $('#selectedmonth').on('change', function(){
            $('#resultscontainer').empty()
            selecteddates = [];
            $('#div-lact12').hide();
          })
          var sectionid = '{{$sectionid}}';
          
			load_generate()
            $('#btn-generate').on('click', function(){
                load_generate()
            })

			function load_generate(){
				
                Swal.fire({
                    title: 'Fetching data...',
                    onBeforeOpen: () => {
                        Swal.showLoading()
                    },
                    allowOutsideClick: false
                })

                $.ajax({
                    url: '/forms/form2',
                    type: 'GET',
                    data: {
                        selectedlact            : $('#selectedlact').val(),
                        syid                    : '{{$syid}}',
                        semid                    : '{{$semid}}',
                        selectedyear            : $('#selectedyear').val(),
                        selectedmonth           : $('#selectedmonth').val(),
                        levelid                 : '{{$levelid}}',
                        sectionid               : '{{$sectionid}}',
                        strandid               : '{{$strandid}}',
                        action                  : 'filter'
                    },
                    success:function(data){
                        $('#resultscontainer').empty();
                        $('#resultscontainer').append(data)
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
			
			}

			
			var all_attendance_setup = []
			get_attendance_setup()
			function get_attendance_setup(){
                  $.ajax({
                        type:'GET',
                        url: '/superadmin/attendance/list',
                        data:{
                              schoolyear:'{{$syid}}',
                              levelid:'{{$levelid}}',
                              attclass:0
                        },
                        success:function(data) {
                              all_attendance_setup = data
	
                        }
                  })
            }

            $('#btn-submit-setup').on('click', function(){

			
				
				var setup = all_attendance_setup.filter(x=>x.month == $('#selectedmonth').val())
				if(setup.length == 0){
					toastr.error('No Days Setup!')
					return false;
				}else{
					if(setup[0].days < selecteddates.length){
						toastr.error('Selected days should not exceed '+setup[0].days+'!')
						return false;
					}
					
				}

				

                $.ajax({
                    url: '/forms/form2',
                    type: 'GET',
                    data: {
                      tvlcourse   : $('#input-tvlcourse').val(),
                      selectedlact: $('#selectedlact').val(),
                        dates                   : selecteddates,
                        selectedyear            : $('#selectedyear').val(),
                        syid                    : '{{$syid}}',
                        selectedmonth           : $('#selectedmonth').val(),
                        strandid               : '{{$strandid}}',
                        sectionid               : '{{$sectionid}}',
                        action                  : 'createsetup'
                    },
                    complete:function(data){
                      $('#btn-generate').click()
                          $('.btn-close-modal').click()
                          $('body').removeClass('modal-open')
                        // window.location.hash = 'reload';
                        // window.location.reload();
                        // document.addEventListener("DOMContentLoaded", function(event) { 
                        //   if(window.location.hash == "#reload"){
                        //     alert("The Page has been reloaded!");
                        //   }else{
                        //     alert("The page has a new hit!");
                        //   }
                        // });
                    }
                })
            })
            $(document).on('click','#btn-setup-delete', function(){
                Swal.fire({
                    title: 'Are you sure you want to delete this setup?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.value) {
                      $.ajax({
                          url: '/forms/form2',
                          type: 'GET',
                          data: {
                              selectedmonth           : $('#selectedmonth').val(),
                              syid                    : '{{$syid}}',
                              selectedyear            : $('#selectedyear').val(),
                              strandid                : '{{$strandid}}',
                              sectionid               : '{{$sectionid}}',
                              action                  : 'deletesetup'
                          },
                          complete:function(data){
                              window.location.reload();
                          }
                      })
                    }
                })
            })
            $(document).on('click','#btn-view-setup', function(){
              $('#show-setup').modal('show')
            })
            $(document).on('click', '#btn-delete-setup', function(){

                $.ajax({
                    url: '/forms/form2',
                    type: 'GET',
                    data: {
                        selectedmonth           : $('#selectedmonth').val(),
                        syid                    : '{{$syid}}',
                        selectedyear            : $('#selectedyear').val(),
                        strandid                : '{{$strandid}}',
                        sectionid               : '{{$sectionid}}',
                        action                  : 'deletesetup'
                    },
                    complete:function(data){
                        window.location.reload();
                    }
                })
            })
            
          $(document).on('click','.student-remarks', function(){
              var studentid = $(this).attr('data-id')
              $('#btn-submit-remarks').attr('studentid',studentid);
              $('#show-remark').modal('show')
                  $.ajax({
                      url: '/forms/form2',
                      type: 'GET',
                      data: {
                          syid                    : '{{$syid}}',
                          levelid                 : '{{$levelid}}',
                          sectionid               : '{{$sectionid}}',
                      strandid               : '{{$strandid}}',
                          studentid               : studentid,
                          selectedyear            : $('#selectedyear').val(),
                          selectedmonth           : $('#selectedmonth').val(),
                          action                  : 'getremarks'
                      },
                      success:function(data){
                          $('#text-area-remark').val(data)
                          // window.location.reload();
                      }
                  })
            })
          $(document).on('click','#btn-submit-remarks', function(){
              var studentid = $(this).attr('studentid');
              var strandid = '{{$strandid}}';
              $.ajax({
                  url: '/forms/form2',
                  type: 'GET',
                  data: {
                      syid                    : '{{$syid}}',
                      remarks                 : $('#text-area-remark').val(),
                      levelid                 : '{{$levelid}}',
                      sectionid               : '{{$sectionid}}',
                      strandid               : '{{$strandid}}',
                      studentid               : studentid,
                      selectedyear            : $('#selectedyear').val(),
                      selectedmonth           : $('#selectedmonth').val(),
                      action                  : 'updateremarks'
                  },
                  success:function(data){
                        toastr.success('Updated successfully!','Remarks')
                      // $('body').removeClass('modal-open');
                      // $('#show-remark').removeClass('show');
                      // $('#show-remark').removeAttr('style');
                      // $('#show-remark').css('display','none');
                      // $('.modal-backdrop').removeClass('show')
                      // $('.modal-backdrop').remove()
                      $('.student-remarks[data-id="'+studentid+'"]').attr('title',data)
                      $('.student-remarks[data-id="'+studentid+'"]').attr('data-original-title',data)
                      
                      // console.log($('.student-remarks[data-id="'+studentid+'"]'))
                      $('[data-toggle="tooltip"]').tooltip();
                      $('#closeremarks').click()
                  }
              })
          })
            $(document).keypress('#input-equivalence', function (e) {
              if (e.which == 13) {
                  var equivalence = $('#input-equivalence').val();
                  $.ajax({
                      url: "/forms/form2?action=updateequivalence",
                      type: "get",
                      data: {
                        equivalence: equivalence,
                        syid                    : '{{$syid}}',
                        selectedlact            : $('#selectedlact').val(),
                        selectedyear            : $('#selectedyear').val(),
                        selectedmonth           : $('#selectedmonth').val(),
                        levelid                 : '{{$levelid}}',
                        sectionid               : '{{$sectionid}}',
                        strandid               : '{{$strandid}}',
                      },
                      complete: function (data) {
                        toastr.success('Updated successfully!')
                        $('#input-equivalence').attr('readonly', true);
                        $('#btn-generate').click();
                      }
                  });
                  return false;    //<---- Add this line
              }
            });
            $(document).on('input','.data-input', function(){
              $(this).closest('tr').find('.btn-lact2-save').addClass('btn-glow')
              $(this).closest('tr').find('.btn-lact3-save').addClass('btn-glow')
            })
            $(document).on('click','.btn-lact2-save', function(){
              var studid  = $(this).attr('data-studid');
              var firstd  = $(this).closest('tr').find('.input-first-d').val();
              var firstr  = $(this).closest('tr').find('.input-first-r').val();
              var secondd = $(this).closest('tr').find('.input-second-d').val();
              var secondr = $(this).closest('tr').find('.input-second-r').val();
              var thirdd  = $(this).closest('tr').find('.input-third-d').val();
              var thirdr  = $(this).closest('tr').find('.input-third-r').val();
              var fourthd = $(this).closest('tr').find('.input-fourth-d').val();
              var fourthr = $(this).closest('tr').find('.input-fourth-r').val();
              var headerid = $('#input-equivalence').attr('data-id');
                $.ajax({
                    url: "/forms/form2?action=updatestudlact2",
                    type: "get",
                    data: {
                      headerid                : headerid,
                      studid                  : studid,
                      firstd                  : firstd,
                      firstr                  : firstr,
                      secondd                 : secondd,
                      secondr                 : secondr,
                      thirdd                  : thirdd,
                      thirdr                  : thirdr,
                      fourthd                 : fourthd,
                      fourthr                 : fourthr,
                      syid                    : '{{$syid}}',
                      selectedyear            : $('#selectedyear').val(),
                      selectedmonth           : $('#selectedmonth').val(),
                      levelid                 : '{{$levelid}}',
                      sectionid               : '{{$sectionid}}',
                      strandid                : '{{$strandid}}',
                    },
                    complete: function (data) {
                      toastr.success('Updated successfully!')
                      $('#input-equivalence').attr('readonly', true);
                      $('#btn-generate').click();
                    }
                });
            })
            $(document).on('input','.input-submitted', function(){
              console.log($(this).val())
              if($(this).val() > $(this).closest('tr').find('.input-required').val())
              {
                $(this).val($(this).closest('tr').find('.input-required').val())
              }
            })
            $(document).on('click','.btn-lact3-save', function(){
              var studid  = $(this).attr('data-studid');
              var submitted  = $(this).closest('tr').find('.input-submitted').val();
              var required  = $(this).closest('tr').find('.input-required').val();
              var headerid = $('#input-equivalence').attr('data-id');
                $.ajax({
                    url: "/forms/form2?action=updatestudlact3",
                    type: "get",
                    data: {
                      syid                    : '{{$syid}}',
                      headerid                : headerid,
                      studid                  : studid,
                      submitted               : submitted,
                      required                : required,
                      selectedyear            : $('#selectedyear').val(),
                      selectedmonth           : $('#selectedmonth').val(),
                      levelid                 : '{{$levelid}}',
                      sectionid               : '{{$sectionid}}',
                      strandid                : '{{$strandid}}',
                    },
                    complete: function (data) {
                      toastr.success('Updated successfully!')
                      $('#input-equivalence').attr('readonly', true);
                      $('#btn-generate').click();
                    }
                });
            })
            $(document).on('click','#btn-reselect-setup', function(){
              $('#show-reselectdays').modal('show')
                $.ajax({
                    url: "/forms/form2?action=getselecteddates",
                    type: "get",
                    data: {
                      syid                    : '{{$syid}}',
                      selectedyear            : $('#selectedyear').val(),
                      selectedmonth           : $('#selectedmonth').val(),
                      levelid                 : '{{$levelid}}',
                      sectionid               : '{{$sectionid}}',
                      strandid                : '{{$strandid}}',
                    },
                    success: function (data) {
                      $('#table-selecteddates').empty()
                      $('#table-selecteddates').append(data)
                    }
                });
            })
            $(document).on('click','#btn-update-setup', function(){
              var datesselected = [];
              $('.each-date[data-select="1"]').each(function(){
                obj = {
                  tdate: $(this).attr('data-id')
                }
                datesselected.push(obj)
              })

				
				var setup = all_attendance_setup.filter(x=>x.month == $('#selectedmonth').val())
			

				if(setup.length == 0){
					toastr.error('No Days Setup!')
					return false;
				}else{
					if(setup[0].days < datesselected.length){
						toastr.error('Selected days should not exceed '+setup[0].days+'!')
						return false;
					}
					
				}

              $.ajax({
                  url: "/forms/form2?action=updatesetupdates",
                  type: "get",
                  data: {
                    syid                    : '{{$syid}}',
                    selectedyear            : $('#selectedyear').val(),
                    selectedmonth           : $('#selectedmonth').val(),
                    levelid                 : '{{$levelid}}',
                    sectionid               : '{{$sectionid}}',
                    strandid                : '{{$strandid}}',
                    dates                   : JSON.stringify(datesselected)
                  },
                  success: function (data) {
                    $('.btn-close').click();
                    $('#btn-generate').click()
                  }
              });
            })
            $(document).on('click', '.each-date', function(){
              if($(this).attr('data-select') == 0)
              {
                $(this).attr('data-select',1)
                $(this).addClass('btn-success');
              }else{
                $(this).attr('data-select',0)
                $(this).removeClass('btn-success');
              }
            })
          var attcounter = 0;
          function saveattendance(selectedschoolyear,selectedsemester,dataobj)
          {
              var firstobj = [dataobj[0]];
              if(dataobj.length != 0)
              {
                  $.ajax({
                          url: '/classattendance/submit',
                          type: 'GET',
                          data: {
                              version: '3',
                              acadprogcode   : '{{$acadprogcode}}',
                              selectedschoolyear   : selectedschoolyear,
                              selectedsemester   : selectedsemester,
                              datavalues   : firstobj
                          },
                          success:function(data){
                              attcounter+=1;
                              $('#attcounting').text(attcounter);
                              dataobj     = dataobj.filter(x=> x.tdate != firstobj[0].tdate || x.studid != firstobj[0].studid )
                              saveattendance(selectedschoolyear,selectedsemester,dataobj)
                          }, error:function()
                          {
                              saveattendance(selectedschoolyear,selectedsemester,dataobj)
                          }
                      })
              }else{
                  attcounter = 0;
                  $(".swal2-container").remove();
                  $('body').removeClass('swal2-shown')
                  $('body').removeClass('swal2-height-auto')
                  toastr.success('Updated successfully!', 'Class Attendance')
                  $('#btn-generate').click()
                  $('td[data-class="attstatus"]').attr('clicked',0)
              }
          }
          var totalchanges;

          $(document).on('click','#btn-save-attendance', function() { 
              attcounter = 0;
              var selectedsemester = $('#selectedsemester').val();
              var datavalues = [];
              console.log($('td[clicked="1"]').length)
              $('td[clicked="1"]').each(function(){
                  
                  obj = {
                      studid      : $(this).attr('data-studid'),
                      status      : $(this).attr('data-status'),
                      tdate       : $(this).attr('data-tdate'),
                      newstatus       : $(this).attr('data-newstatus')
                  };
                  // obj['studid'] = $(this).attr('data-studid');
                  // obj['status'] = $(this).attr('data-status');
                  // obj['tdate'] = $(this).attr('data-tdate');
                  // obj['newstatus'] = $(this).attr('data-newstatus');
                  datavalues.push(obj);
              })
              totalchaanges = datavalues.length;
              Swal.fire({
                  title: 'Saving changes...',
                  html:'<span id="attcounting"></span>/'+totalchaanges,
                  allowOutsideClick: false,
                  closeOnClickOutside: false,
                  onBeforeOpen: () => {
                      Swal.showLoading()
                  }
              })  
              saveattendance('{{$syid}}',selectedsemester,datavalues)
              attcounter = 0;
                    
          })
            $(document).on('click', '#btn-printpdf',function(){
              
                window.open("/forms/form2?action=export&exporttype=pdf&selectedmonth="+$('#selectedmonth').val()+"&selectedyear="+$('#selectedyear').val()+"&levelid={{$levelid}}&sectionid={{$sectionid}}&syid={{$syid}}&semid={{$semid}}&pam_male="+$('#pam_male').text()+"&pam_female="+$('#pam_female').text()+"&pam_total="+$('#pam_total').text()+'&strandid={{$strandid}}&semester='+$('#selectsemester').val()+'&selectedlact='+$('#selectedlact').val());
            })
            $(document).on('click', '#btn-printexcel', function(){
              
                window.open("/forms/form2?action=export&exporttype=excel&selectedmonth="+$('#selectedmonth').val()+"&selectedyear="+$('#selectedyear').val()+"&levelid={{$levelid}}&sectionid={{$sectionid}}&syid={{$syid}}&semid={{$semid}}&pam_male="+$('#pam_male').text()+"&pam_female="+$('#pam_female').text()+"&pam_total="+$('#pam_total').text()+'&strandid={{$strandid}}'+'&selectedlact='+$('#selectedlact').val());
            })
        })
  </script>
@endsection

