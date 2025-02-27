@extends('finance.layouts.app')

@section('content')
<!-- DataTables -->
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
<!-- daterange picker -->
<link rel="stylesheet" href="{{asset('plugins/daterangepicker/daterangepicker.css')}}">
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
<style>
    table{
        font-size: 12px;
    }
</style>
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Statement of Account</h1>
                <!-- <h4 class="text-warning" style="text-shadow: 1px 1px 1px #000000">
                    <i class="fa fa-file-invoice nav-icon"></i> 
                    <b>STUDENT LEDGER</b></h4> -->
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item active">Statement of Account</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

{{-- MODALS --}}
<div class="modal" tabindex="-1" role="dialog" id="month_range_modal">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Month Range Setup</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12" id="container_month_range">
                    <table width="100%" class="table table-striped">
                        <thead>
                            <tr>
                                <th width="5%"></th>
                                <th width="35%" class="text-center">START MONTH</th>
                                <th width="35%" class="text-center">END</th>
                                <th width="25%" class="text-center">ACTION</th>
                            </tr>
                        </thead>
                        <tbody id="table_month_range" style="overflow: scroll;">
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary btn-sm" id="btn_add_range">Add Range</button>
        </div>
      </div>
    </div>
</div>

<div class="modal" tabindex="-1" role="dialog" id="month_range_modal_add">
    <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Add Range</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-6">
                    <label>Start Month</label>
                    <select class="form-control form-control-sm" id="select_start_range">
                        <option value="1">January</option>
                        <option value="2">February</option>
                        <option value="3">March</option>
                        <option value="4">April</option>
                        <option value="5">May</option>
                        <option value="6">June</option>
                        <option value="7">July</option>
                        <option value="8">August</option>
                        <option value="9">September</option>
                        <option value="10">October</option>
                        <option value="11">November</option>
                        <option value="12">December</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label>End Month</label>
                    <select class="form-control form-control-sm" id="select_end_range">
                        <option value="1">January</option>
                        <option value="2">February</option>
                        <option value="3">March</option>
                        <option value="4">April</option>
                        <option value="5">May</option>
                        <option value="6">June</option>
                        <option value="7">July</option>
                        <option value="8">August</option>
                        <option value="9">September</option>
                        <option value="10">October</option>
                        <option value="11">November</option>
                        <option value="12">December</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-success btn-sm" id="btn_save_range">Save Range</button>
        </div>
      </div>
    </div>
</div>
{{-- END MODALS --}}
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12 text-right">
                <button id="btn_range" class="btn btn-default btn-lg" data-toggle="tooltip" title="Month Range Setup">
                    <i class="fas fa-cogs"></i>
                </button>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2">
                <label>School Year</label>
                <select class="form-control form-control-sm" id="selectedschoolyear">
                    @foreach($schoolyears as $schoolyear)
                        <option value="{{$schoolyear->id}}" {{$schoolyear->isactive == 1 ? 'selected' : ''}}>{{$schoolyear->sydesc}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label>Semester</label>
                <select class="form-control form-control-sm" id="selectedsemester">
                    @foreach($semesters as $semester)
                        <option value="{{$semester->id}}" {{$semester->isactive == 1 ? 'selected' : ''}}>{{$semester->semester}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label>Grade Level</label>
                <select class="form-control form-control-sm" id="selectedgradelevel">
                    @foreach($gradelevels as $gradelevel)
                        <option value="{{$gradelevel->id}}">{{$gradelevel->levelname}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 div_section">
                <label>Section</label>
                <select class="form-control form-control-sm" id="selectedsection">
                    {{-- <option value="0" >All</option> --}}
                </select>
            </div>
            <div class="col-md-2 div_course" style="display: none">
                <label>Course</label>
                <select class="form-control form-control-sm" id="selectedcourse">
                    {{-- <option value="0" >All</option> --}}
                </select>
            </div>
            {{-- <div class="col-md-2">
                <label>Month Range &nbsp;&nbsp; <a href="javascript:void(0)" id="btn_range"><i class="fas fa-sliders-h"></i></a></label> 
                <select class="form-control form-control-sm" id="selectedmonthrange" style="text-transform: uppercase"></select>
            </div> --}}
            <div class="col-md-2">
                <label>Month Setup</label>
                <select class="form-control form-control-sm" id="selectedmonth">
                    {{-- @foreach($monthsetups as $monthsetup)
                        <option value="{{$monthsetup->id}}">{{$monthsetup->description}}</option>
                    @endforeach --}}
                </select>
            </div>
            {{-- @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'hccsi')
                <div class="col-md-2">
                    @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'xai')
                        @if($status == 0)
                            <button type="button" id="viewnote" class="btn btn-sm btn-default float-right btn-block" data-toggle="tooltip" data-placement="left" title="No notes available"> <span style="height: 10px;width: 10px;background-color: red;border-radius: 50%;display: inline-block;"></span> Note (Inactive)</button>
                        @else
                            <button type="button" id="viewnote" class="btn btn-sm btn-default float-right btn-block" data-toggle="tooltip" data-placement="left" title="Active"> <span style="height: 10px;width: 10px;background-color: green;border-radius: 50%;display: inline-block;"></span> Note (Active)</button>
                        @endif
                    @endif
                </div>
            @endif --}}
        </div>
        <div class="row mt-2">
            <div class="col-md-12 text-right">
            <button type="button" class="btn btn-warning" id="btn-notify">Notify SMS</button>
                <button type="button" class="btn btn-primary" id="btn-generate">Generate</button>
                {{-- <button type="button" class="btn btn-default" id="btn-export-all">
                    <i class="fa fa-file-pdf"></i> Export to PDF
                </button> --}}
            </div>
        </div>
    </div>
</div>
<div id="results-container"></div>
<div class="modal fade" id="viewnotemodal" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
            {{-- <div class="col-sm-12">
                <div class="alert alert-warning alert-dismissible">
                    <h5><i class="icon fas fa-exclamation-triangle"></i> Alert!</h5>
                    Currently working on this feature.
                  </div>
            </div> --}}
          <p><em>This note will be added at the bottom of the report to be followed by the signatories.</em></p>
          <div class="row" id="notecontainer"></div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="submitnotes">Save changes</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
@endsection

@section('modal')
    <div class="modal fade show" id="modal-notify" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-lg" style="margin-top: 4em">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Notify SMS</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <form class="form-horizontal">
            <div class="card-body">
              {{-- <div class="form-group row">
                <div class="col-sm-10 input-group">
                  <input id="txtsearch" type="text" class="form-control validation" id="item-code" placeholder="SEARCH STUDENT" onkeyup="this.value = this.value.toUpperCase();">
                  <span class="input-group-append">
                    <span type="button" class="btn btn-info btn-flat"><i class="fas fa-search"></i></span>
                  </span>
                </div>
              </div> --}}
              <div class="row">
                <div class="table-responsive" style="height: 28em; overflow: scroll;">
                  <table class="table table-striped">
                    <thead>
                      <tr>
                        <th>ID NO.</th>
                        <th>NAME</th>
                        <th>DUE FOR THE MONTH</th>
                        <th>CONTACT NO.</th>
                      </tr>
                    </thead>
                    <tbody id="sms_soalist" style="height: 27em; overflow: scroll;">
                      
                    </tbody>
                  </table>
                </div>
              </div>
              
            </div>
            <!-- /.card-body -->
            <!-- /.card-footer -->
          </form>
        </div>
        <div class="modal-footer ">
          <button id="savestud" type="button" class="btn btn-default" data-dismiss="modal">CLOSE</button> 
          <button id="sendsms" type="button" class="btn btn-primary">SEND SMS</button> 
        </div>
      </div>
    </div> {{-- dialog --}}
  </div>
@endsection

@section('footerscripts')
<script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
<script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
<script src="{{asset('plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
<script src="{{asset('plugins/jszip/jszip.min.js')}}"></script>
<script src="{{asset('plugins/pdfmake/pdfmake.min.js')}}"></script>
<script src="{{asset('plugins/pdfmake/vfs_fonts.js')}}"></script>
<script src="{{asset('plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
<script src="{{asset('plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
<script src="{{asset('plugins/datatables-buttons/js/buttons.colVis.min.js')}}"></script>
<script>
    $(document).ready(function(){
        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });
        select_month_range()

        $('[data-toggle="tooltip"]').tooltip()
        $('#selecteddaterange').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear',
                format: 'YYYY-MM-DD'
            }
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
                url: '{{ route('statementofacctgenerate')}}',
                type: 'GET',
                data: {
                    selectedschoolyear   : $('#selectedschoolyear').val(),
                    selectedsemester     : $('#selectedsemester').val(),
                    selectedgradelevel   : $('#selectedgradelevel').val(),
                    selectedmonth        : $('#selectedmonth').val(),
                    selectedcourse       : $('#selectedcourse').val(),
                    selectedsection      : $('#selectedsection').val()
                },
                success:function(data){
                    $('#export-tools').show();
                    $('#results-container').empty();
                    $('#results-container').append(data)
                    $('.paginate_button').addClass('btn btn-sm btn-default')
                    $(".swal2-container").remove();
                    $('body').removeClass('swal2-shown')
                    $('body').removeClass('swal2-height-auto')
                    var table = $("#example1").DataTable({
                        // retreive: true,
                        pageLength : 10,
                        lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Show All']],
                        "ordering": false
                        // "bPaginate": false,
                        // "bInfo" : false,
                        // "bFilter" : false,
                        // "order": [[ 1, 'asc' ]]
                    });
                }
            })
        })
        $(document).on('click','.viewdetails', function(){
            if($(this).closest('.card').hasClass('collapsed-card'))
            {
                var selectedschoolyear = $('#selectedschoolyear').val();
                var selectedsemester = $('#selectedsemester').val();
                var selectedmonth = $('#selectedmonth').val();
                var studid = $(this).attr('id');
                $.ajax({
                    url: '{{ route('statementofacctgetaccount')}}',
                    type: 'GET',
                    data: {
                        studid: studid,
                        selectedschoolyear: selectedschoolyear,
                        selectedsemester: selectedsemester,
                        selectedmonth: selectedmonth,
                        action: 'generate'
                    },
                    success:function(data){
                        $('#stud'+studid).empty();
                        $('#stud'+studid).append(data);
                    }
                })
            }else{
                $('#stud'+$(this).attr('id')).empty()
            }
        })
        $(document).on('click','.printstatementofacct', function(){
                var selectedschoolyear = $('#selectedschoolyear').val();
                var selectedsemester = $('#selectedsemester').val();
                var selectedmonth = $('#selectedmonth').val();
                var studid = $(this).attr('studid');
                var exporttype = $(this).attr('exporttype')
                var paramet = {
                    selectedschoolyear  : selectedschoolyear,
                    selectedsemester    : selectedsemester, 
                    selectedmonth       : selectedmonth, 
                    studid              : studid
                }
				window.open("/statementofacctgetaccount?exporttype="+exporttype+"&"+$.param(paramet));
        })
        $(document).on('click','#viewnote', function(){
            $.ajax({
                url: '/statementofacctgetnote',
                type: 'GET',
                data: {
                    type: 1
                },
                success:function(data)
                {
                    $('#notecontainer').empty();
                    $('#notecontainer').append(data)
                }
            })
            $('#viewnotemodal').addClass('show')
            $('#viewnotemodal').css({'padding-right':'10px','display':'block'})
            $('#viewnotemodal').modal('show');
            $('body').addClass('modal-open')
            $('.modal-backdrop').addClass('show')
            $('.modal-backdrop').show()
        })
        $(document).on('click','#submitnotes', function()
        {
            
            var submit = 4;
            var notes = [];
            $('textarea').each(function(){
                if($(this).val().replace(/^\s+|\s+$/g, "").length == 0)
                {
                    submit -= 1;
                }else{
                    notes.push(
                        {
                            'id':$(this).attr('id'),
                            'content':$(this).val()
                        }
                    )
                }
            })
            if(submit>0)
            {
                if($('input[name="notestatus"]').length == 0)
                {
                    var notestatus = 1;
                }else{
                    var notestatus = $('input[name="notestatus"]:checked').val();
                }
                $.ajax({
                    url: '/statementofacctsubmitnotes',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        notes: JSON.stringify(notes),
                        notestatus : notestatus
                    },
                    success:function(data)
                    {
                        if(data == '1')
                        {
                            
                            $('#viewnotemodal').removeClass('show')
                            $('#viewnotemodal').removeAttr('style')
                            $('#viewnotemodal').css('display','none;')
                            $('body').removeClass('modal-open')
                            $('.modal-backdrop').removeClass('show')
                            $('.modal-backdrop').hide()
                        }
                    }
                })
            }

        })
        $(document).on('click','.btn-export-all', function(){
            var selectedschoolyear = $('#selectedschoolyear').val();
            var selectedsemester = $('#selectedsemester').val();
            var selectedgradelevel   = $('#selectedgradelevel').val();
            var selectedmonth = $('#selectedmonth').val();
            var selectedcourse = $('#selectedcourse').val();
            var selectedsection = $('#selectedsection').val();

            var paramet = {
                selectedschoolyear  : selectedschoolyear,
                selectedsemester    : selectedsemester, 
                exporttype    : $(this).attr('exporttype'), 
                selectedgradelevel   : $('#selectedgradelevel').val(),
                selectedmonth       : selectedmonth,
                selectedcourse : selectedcourse,
                selectedsection : selectedsection
            }
            window.open("/statementofacctexportall?"+$.param(paramet));
        });

        $(document).on('change', '#selectedgradelevel', function(){
            loadsection();
        });

        loadsection();

        function loadsection()
        {
            var levelid = $('#selectedgradelevel').val();
            var syid = $('#selectedschoolyear').val();
            var semid = $('#selectedsemester').val();

            $.ajax({
                type: "GET",
                url: "{{route('statementofacctloadsection')}}",
                data: {
                    levelid:levelid,
                    syid:syid,
                    semid:semid
                },
                // dataType: "dataType",
                success: function (data) {
                    if(levelid == 14 || levelid == 15)
                    {
                        $('.div_section').show();
                        $('.div_course').hide();
                        $('#selectedsection').html(data);
                    }
                    else if(levelid >= 17 && levelid <= 20)
                    {
                        $('.div_section').hide();
                        $('.div_course').show();
                        $('#selectedcourse').html(data);
                    }
                    else
                    {
                        $('.div_section').show();
                        $('.div_course').hide();
                        $('#selectedsection').html(data);
                    }
                }
            });
        }

        var sms_list = ''

        function sms_getstudents()
        {
            var syid = $('#selectedschoolyear').val()
            var semid = $('#selectedsemester').val()
            var levelid = $('#selectedgradelevel').val()
            var sectionid = $('#selectedsection').val()
            var courseid = $('#selectedcourse').val()
            var monthid = $('#selectedmonth').val()
            
            $.ajax({
                type: "GET",
                url: "{{ route('statementofaccsmsnotify') }}",
                data: {
                    syid:syid,
                    semid:semid,
                    levelid:levelid,
                    sectionid:sectionid,
                    courseid:courseid,
                    monthid:monthid
                },
                // dataType: "dataType",
                success: function (data) {

                    sms_list = data

                    $('#sms_soalist').empty()
                    
                    $.each(data, function (indexInArray, val) { 
                        $('#sms_soalist').append(`
                        <tr>
                            <td>`+val.sid+`</td>
                            <td>`+val.name+`</td>
                            <td class="text-right">`+val.balance+`</td>
                            <td>`+val.contactno+`</td>
                        </tr>
                    `)
                    });

                    $('#modal-notify').modal('show')

                    
                }
            });

        }

        $(document).on('click', '#btn-notify', function(){
            if($('#soa_body tr').length > 0)
            {
                sms_getstudents()
            }
            else{
                const Toast = Swal.mixin({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                    }
                });
                Toast.fire({
                    type: "error",
                    title: "Please generate SOA first."
                });
            }            
        })

        $(document).on('click', '#sendsms', function(){
            var syid = $('#selectedschoolyear').val()
            var semid = $('#selectedsemester').val()
            var levelid = $('#selectedgradelevel').val()
            var sectionid = $('#selectedsection').val()
            var courseid = $('#selectedcourse').val()
            var monthid = $('#selectedmonth').val()

            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('statementofaccsmssend') }}",
                data: {
                    sms_list:sms_list,
                    syid:syid,
                    semid:semid,
                    levelid:levelid,
                    sectionid:sectionid,
                    courseid:courseid,
                    monthid:monthid
                },
                // dataType: "dataType",
                success: function (data) {
                    if(data == 0)
                    {
                        const Toast = Swal.mixin({
                            toast: true,
                            position: "top-end",
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast.onmouseenter = Swal.stopTimer;
                                toast.onmouseleave = Swal.resumeTimer;
                            }
                        });
                        Toast.fire({
                            type: "error",
                            title: "SOA Notification already sent."
                        });
                    }
                    else{
                        const Toast = Swal.mixin({
                            toast: true,
                            position: "top-end",
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast.onmouseenter = Swal.stopTimer;
                                toast.onmouseleave = Swal.resumeTimer;
                            }
                        });
                        Toast.fire({
                            type: "success",
                            title: "Messages are sending."
                        });

                        $('#modal-notify').modal('hide')
                        insertnotifyflag()
                    }
                }
            });
        })

        function insertnotifyflag()
        {
            var syid = $('#selectedschoolyear').val()
            var semid = $('#selectedsemester').val()
            var levelid = $('#selectedgradelevel').val()
            var sectionid = $('#selectedsection').val()
            var courseid = $('#selectedcourse').val()
            var monthid = $('#selectedmonth').val()

            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('statementofaccaddflag') }}",
                data: {
                    syid:syid,
                    semid:semid,
                    levelid:levelid,
                    sectionid:sectionid,
                    courseid:courseid,
                    monthid:monthid
                },
                success: function (response) {

                }
            });
        }

        function getMonthOptions(selectedMonth) {
            var months = [
                "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"
            ];

            // Create an HTML select element with options for each month
            var select = '<select class="form-control form-control-sm" disabled>';
            $.each(months, function(index, month) {
                var selected = (index + 1 === selectedMonth) ? 'selected' : '';
                select += '<option value="' + (index + 1) + '" ' + selected + '>' + month + '</option>';
            });
            select += '</select>';
            
            return select;
        }

        function select_month_range() {
            $.ajax({
                type: "GET",
                url: "{{ route('statementofacctgetmonthrange') }}", // Ensure this route is defined
                success: function (data) {
                    table_range(data)
                    $('#selectedmonthrange').empty(); 
                    
                    $.each(data, function (index, val) {
                        const startMonth = moment().month(val.sdate - 1).format('MMMM'); // val.sdate as month number
                        const endMonth = moment().month(val.edate - 1).format('MMMM');   // val.edate as month number
                        
                        $('#selectedmonthrange').append(`
                            <option value="${val.id}">
                                ${startMonth} - ${endMonth}
                            </option>
                        `);
                    });
                    
                    getselectedrange()  
                }
            });
        }

        function  table_range(data){
            
            $('#table_month_range').empty()
                        
            $.each(data, function (indexInArray, val) { 
                indexInArray++
                $('#table_month_range').append(`
                    <tr>
                        <td style="vertical-align: middle">`+indexInArray+`</td>
                        <td  class="text-center">`+getMonthOptions(val.sdate)+`</td>
                        <td class="text-center">`+getMonthOptions(val.edate)+`</td>
                        <td class="text-center" style="vertical-align: middle">
                            <a href="javascript:void(0)" id="btn_delete_range" data-id="`+val.id+`"><i class="fas fa-trash text-danger"></i></a>
                        </td>
                    </tr>
                `)
            });

            if (data.length > 0) {
                $('#btn_add_range').attr('hidden', true)
            } else {
                $('#btn_add_range').attr('hidden', false)
            }
        }

        function getselectedrange(rangeid){

            $.ajax({
                type: "GET",
                url: "{{ route('statementofacctselectedmonthrange') }}",
                data: {
                    rangeid : rangeid
                },
                success: function (response) {
                    $('#selectedmonth').empty();
            
                    $.each(response, function (index, month) {
                        $('#selectedmonth').append(
                            `<option value="${month.id}">${month.description}</option>`
                        );
                    });
                }
            });
        }

        $(document).on('click', '#btn_add_range', function(){
            $('#month_range_modal_add').modal('show')
        })

        $(document).on('click', '#btn_range', function(){
            $('#month_range_modal').modal('show')
        })

        $(document).on('click', '#btn_save_range', function(){
            var start_month = $('#select_start_range').val();
            var end_month = $('#select_end_range').val();
            
            $.ajax({
                type: "GET",
                url: "{{ route('statementofacctaddmonthrange') }}",
                data: {
                    start_month : start_month,
                    end_month : end_month
                },
                success: function (data) {
                    if (data == 1) {
                        Toast.fire({
                            type: "success",
                            title: "Added Successfully."
                        });
                        $('#month_range_modal_add').modal('hide')
                        select_month_range()
                    } else {
                        Toast.fire({
                            type: "error",
                            title: "Already Exist."
                        });
                    }
                }
            });
        })

        $(document).on('click', '#btn_delete_range', function(){
            var id = $(this).attr('data-id')

            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: "GET",
                        url: "{{ route('statementofacctremovemonthrange') }}",
                        data: {
                            id : id
                        },
                        success: function (response) {
                            select_month_range()
                            Toast.fire({
                                type: "success",
                                title: "Deleted Successfully."
                            });
                        }
                    });
                }
            })
        })
    })

</script>
@endsection