@extends('hr.layouts.app')
@section('content')
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
<link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
<style>
    .select2-container .select2-selection--single {
        height: 40px;
    }
</style>
<section class="content-header p-0" style="padding-top: 15px!important;">
    <div class="container-fluid">
        <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>APPLY LEAVE</h1>
                </div>
                <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/home">Home</a></li>
                    <li class="breadcrumb-item active">APPLY LEAVE</li>
                </ol>
                </div>
        </div>
    </div>
</section>

@php
$refid = DB::table('usertype')
    ->where('id', Session::get('currentPortal'))
    ->first()->refid;
@endphp


<section class="content">
    {{-- MODAL  --}}
    <div class="modal fade" id="modal-showapplyleave">
        <div class="modal-dialog @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'gbbc')modal-lg @endif">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Apply Leave</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form method="POST" action="/leaves/apply/submit" id="multiple-files-upload" enctype="multipart/form-data">
                @csrf
                <div class="modal-body" style="padding-top: 0px;">
                    {{-- <div class="row">
                        <div class="col-md-12">
                            <span id="yearofservice"></span>
                            <input type="hidden" id="input-yos">
                        </div>
                    </div> --}}
                    <input type="hidden" id="input-yos">

                    <div class="row availableleave"></div>
                    {{-- <div class="row mb-3">
                        <div class="col-md-12">
                            <label>Leave Type</label>
                            <select class="form-control form-control-sm select2" id="leavetype"></select>
                        </div>
                    </div> --}}
                    <div id="container-visibility">
                        <div class="row mb-2">
                            <input type="hidden" id="leavetypeid">
                            <div class="col-md-12">
                                <label>Reasons/Purpose</label>
                                <textarea class="form-control" id="textarea-remarks" name="remarks"></textarea>
                            </div>
                        </div>
                        {{-- <div id="container-alloweddates"></div> --}}
                        <div id="container-dates"></div>
                        {{-- <div class="row mb-2">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-sm btn-info" id="btn-adddates"><i class="fa fa-plus"></i> Add dates</button>
                            </div>
                        </div>
                        <div id="div-adddates">
                            
                        </div> --}}
                        <div class="container p-0 m-0">  
                            <label>Attachments</label>
                            <input type="file" id="file-input" multiple accept="application/pdf, image/*" name="files[]" class="form-control"/>
                            <span class="text-danger">{{ $errors->first('image') }}</span>
                            <div id="thumb-output" class="row mt-2"></div>
                        </div>
                        <input type="hidden" id="employeeids" name="employeeids"/>
                        <div class="leavedates"></div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default btn-close-modal" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="submitleave">Submit</button>
                </div>
            </form>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <div class="modal fade" id="modal-showappliedleave">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                <h4 class="modal-title">Applied Leaves</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body" style="padding-top: 0px;">
                    <table width="100%" class="table table-sm table-bordered table-head-fixed " id="employee_appliedleaves"  style="font-size: 16px">
                        <input type="hidden" id="employeeidforleave">
                        <thead>
                              <tr>
                                    <th width="50%">Leave Type</th>
                                    {{-- <th class="text-center" width="15%">Date From</th> --}}
                                    {{-- <th class="text-center" width="15%">Date To</th> --}}
                                    {{-- <th class="text-center" width="10%">Files</th> --}}
                                    <th class="text-center" width="10%">Delete</th>
                              </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default btn-close-modal" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" hidden>Submit</button>
                </div>
            </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    {{--  --}}
    
    <div class="card border-0">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <table width="100%" class="table table-sm table-bordered table-head-fixed " id="employee_datatables"  style="font-size: 16px">
                        <thead>
                              <tr>
                                    <th width="2%">&nbsp;&nbsp;No.</th>
                                    <th width="43%">Employee</th>
                                    <th class="text-center" width="15%">Year of Service</th>
                                    <th class="text-center" width="10%">Leaves</th>
                                    <th class="text-center" width="10%">Action</th>
                              </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
      </div>
</section>
  
@endsection
@section('footerjavascript')
<script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{asset('plugins/datatables/jquery.dataTables.js') }}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
<script src="{{asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>
<script src="{{asset('plugins/moment/moment.min.js') }}"></script>

<script>
    $(document).ready(function(){
        $('#container-visibility').hide();
        $('#btn-applyleave-submit').hide();

        // variable calls
        var employee_list = [];
        var leaves = [];
        var allleaves = [];
        var employeeappliedleaves = [];
        // ==============================================================================================================================================

        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
        });
        
        $(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });

        // ============================================================= function calls =================================================================
        load_employees()
        loadleaves()
        
        // ==============================================================================================================================================
        $('#modal-showapplyleave').on('hide.bs.modal', function (e) {
            loadavailableleave(0,0)
            $('#container-visibility').hide();
        })
        // ============================================================= Modal Close ====================================================================
    
        // ========================================================= click event sections ===============================================================
        $(document).on('click', '.applyleave', function(){
            var teacherid = $(this).attr('teacherid')
            var yos = $(this).attr('yos')
            $('#employeeids').val(teacherid)
            $('#input-yos').val(yos)
            $('#yearofservice').text(yos + ' ' +'Years of Service')

            loadavailableleave(teacherid,yos)

            $('#modal-showapplyleave').modal('show')
        })

        $(document).on('change', '.leavetype', function(){
            $('#div-adddates').empty();
            if ($(this).is(':checked')) {
                var employeeid = $('#employeeids').val();
                var selecttext = $(this).attr('leavedesc');
                var leaveid = $(this).attr('leaveid');
                // $('#countdays'+leaveid).css('color', '#343a40')

                $('#leavetypeid').val(leaveid)
                $('#container-dates').empty();
                $('#container-alloweddates').empty();
                

                loaddates(employeeid,leaveid)
                // Uncheck other checkboxes
                $('.leavetype').not(this).prop('checked', false);

                $.ajax({
                    url: '/leaves/datesallowed/getinfo',
                    type: 'GET',
                    data: {
                        selecttext     :   selecttext,
                        employeeid     :   employeeid,
                        leaveid         :   leaveid
                    },
                    success:function(data){
                        $('#container-dates').append(data)
                    }
                })

                $('#container-visibility').show();
                $('#btn-applyleave-submit').show();
            } else {
                $('#leavetypeid').val('')
                $('#container-visibility').hide();
                $('#btn-applyleave-submit').hide();
            }
        });
        
        var input_file = document.getElementById('file-input');
        var remove_products_ids = [];
        var product_dynamic_id = 0;

        $("#file-input").change(function (event) {
            var file = this.files[0];
            var  fileType = file['type'];
            var validImageTypes = ['image/gif', 'image/jpeg', 'image/png','application/pdf'];
            if (!validImageTypes.includes(fileType)) {
                toastr.warning('Invalid File Type! JPEG/PNG/PDF files only!', 'Leave Application')
                $(this).val('')
                $('#thumb-output').empty()
            }else{
                var len = input_file.files.length;
                $('#thumb-output').empty()
                
                for(var j=0; j<len; j++) {
                    var src = "";
                    var name = event.target.files[j].name;
                    var mime_type = event.target.files[j].type.split("/");
                    if(mime_type[0] == "image") {
                    src = URL.createObjectURL(event.target.files[j]);
                    } else if(mime_type[0] == "video") {
                    src = 'icons/video.png';
                    } else {
                    src = 'icons/file.png';
                    }
                    $('#thumb-output').append("<div class='col-md-3'><img id='" + product_dynamic_id + "' src='"+src+"' title='"+name+"' width='100%'></div>");
                    product_dynamic_id++;
                }
            }
        });    

        // $('#btn-applyleave-submit').on('click',function(){
        //     var leaveid = $('#leavetypeid').val();
        //     var remarks = $('#textarea-remarks').val();
        //     var selecteddates = [];

        //     $('.input-adddates').each(function(){
        //         if($(this).val().replace(/^\s+|\s+$/g, "").length > 0)
        //         {
        //             selecteddates.push($(this).val())
        //         }
        //     })

        //     var checkvalidation = 0;
        //     if(remarks.replace(/^\s+|\s+$/g, "").length == 0)
        //     {
        //         checkvalidation = 1;
                
        //         $('#textarea-remarks').css('border','1px solid red');
        //         toastr.warning('Please write a purpose/reason!', 'Leave Application')
        //     }
        //     if(selecteddates.length == 0)
        //     {
        //         checkvalidation = 1;
        //         toastr.warning('Please select dates!', 'Leave Application')
        //     }

        //     if(checkvalidation == 0)
        //     {
        //         $(this).closest('form').submit()
        //     }
        // })
        $(document).on('click', '#submitleave', function(){
            var valid_data = true;
            var checkedHalfdayLeave = $('.halfdayleave:checked');
            var halfdayleavestatus = 0; // Array to store values
    
            checkedHalfdayLeave.each(function() {
                
                halfdayleaves = $(this).attr('halfdaystatus');
                if (halfdayleaves == 'amleave') {
                    halfdayleavestatus = 1;
                } else if(halfdayleaves == 'pmleave'){
                    halfdayleavestatus = 2;
                }
            });

            var leaveid = $('#leavetypeid').val();
            console.log('LEAVEID', leaveid);
            if(!leaveid){
                valid_data = false;
                Toast.fire({
                    type: 'error',
                    title: 'No Leavetype selected!'
                })
                return false
            }
            
            var countday = $('#countdays'+leaveid).text()
            var parts = countday.split('/');
            var totalcountstart = parseFloat(parts[0])
            var totalcountend = parseFloat(parts[1])

            if (totalcountstart == totalcountend) {
                Toast.fire({
                    type: 'error',
                    title: 'No Days Left'
                })
            }


            var remarks = $('#textarea-remarks').val().trim();

            if (!remarks) {
                $('#textarea-remarks').addClass('is-invalid');
                valid_data = false;
                Toast.fire({
                    type: 'error', // Use 'icon' instead of 'type' as 'type' is deprecated in SweetAlert2
                    title: 'Reason is required'
                });
            } else {
                $('#textarea-remarks').removeClass('is-invalid');
            }


            var employeeid = $('#employeeids').val();
            var teacherid = $('#employeeids').val();
            var yos = $('#input-yos').val();

            var files = $('#file-input')[0].files;
            var formData = new FormData();

            var dateRange = $('#reservation').val();
            var dateRangeParts = dateRange.split(' - ');
            
        

            // Construct Date objects with the correct format (MM/DD/YYYY)
            var startDate = new Date(dateRangeParts[0]);
            var endDate = new Date(dateRangeParts[1]);
            var selecteddates = [];

            // Add the start date to the selecteddates array
            var formattedSdate = startDate.getFullYear() + '-' + 
                ('0' + (startDate.getMonth() + 1)).slice(-2) + '-' + 
                ('0' + startDate.getDate()).slice(-2);

            var formattedEdate = endDate.getFullYear() + '-' + 
                ('0' + (startDate.getMonth() + 1)).slice(-2) + '-' + 
                ('0' + startDate.getDate()).slice(-2);

            selecteddates.push(formattedSdate);

            // Check if there is only one date in the range
            if (startDate.getTime() !== endDate.getTime()) {
                // Clone the start date to avoid modifying the original date object
                var currentDate = new Date(startDate);
                // Loop through the dates and add them to the selecteddates array
                while (currentDate <= endDate) {
                    currentDate.setDate(currentDate.getDate() + 1);
                    selecteddates.push(currentDate.toISOString().slice(0, 10));
                }
            }

            // Convert dates to a consistent format (YYYY-MM-DD) and remove duplicates
            var uniqueDates = Array.from(new Set(selecteddates));
            // Function to format date from MM/DD/YYYY to YYYY-MM-DD
            function formatDate(dateString) {
                var parts = dateString.split("-");
                return parts[0] + "-" + parts[1].padStart(2, '0') + "-" + parts[2].padStart(2, '0');
            }

            // Function to parse date strings into Date objects
            function parseDate(dateString) {
                return new Date(dateString);
            }

            // Append the CSRF token directly to the formData object
            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
            formData.append('leaveid', leaveid);
            formData.append('remarks', remarks);
            
            // Add selecteddates array to the formData object
            for (var i = 0; i < uniqueDates.length; i++) {
                formData.append('selecteddates[' + i + ']', uniqueDates[i]);
                formData.append('r' + (i + 1), 0);
            }
            // Append the file input data to the FormData object
            for (var i = 0; i < files.length; i++) {
                formData.append('files[]', files[i]);
            }
            formData.append('employeeid', employeeid);
            formData.append('halfdayleavestatus', halfdayleavestatus);
            
            if (valid_data) {
                $.ajax({
                    type: "POST",
                    url: "/leaves/apply/submitleave",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (data, textStatus, xhr) {
                        if (xhr.status === 200) {
                            // Successful response
                            if (data == 1) {
                                $('#leavetypeid').val('')
                                $('#thumb-output').empty();
                                $('#textarea-remarks').val('');
                                $('#file-input').val('');
                                $('#container-visibility').hide();
                                loadavailableleave(employeeid,yos)
                                loaddates(employeeid,leaveid)
                                load_employees()
                                toastr.success('Added successfully!')
                            } else {
                                toastr.error('Something went wrong!')
                            }
                        } else if (xhr.status === 302) {
                            // Redirect response, handle it as needed
                            window.location.href = xhr.getResponseHeader('Location');
                        }
                    },
                    error: function (xhr, textStatus, errorThrown) {
                        // Handle other errors
                        console.error(xhr.responseText);
                    }
                });
            }
        });
        
        $(document).on('click', '.deleteempleave', function(){
            var teacherid = $('#employeeidforleave').val()
            var leaveempid = $(this).attr('leaveempid')
            var leaveid = $('#leavetypeid').val()

            Swal.fire({
                title: 'Are you sure you want to delete this Leave?',
                html: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                allowOutsideClick: false
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: '/leaves/apply/deletedates',
                        type:"GET",
                        data:{
                            leaveempid   :  leaveempid,
                            teacherid : teacherid
                        },
                        success: function(data){
                            if(data == 1)
                            {
                                load_employees()
                                loadappliedleaves(teacherid)
                                toastr.success('Deleted successfully!')
                            }else{
                                toastr.error('Something went wrong!')
                            }
                        }
                    })
                }
            })
        })

        $(document).on('click', '.deletedate', function(){
            var employeeid = $('#employeeids').val();
            var daystatus = $(this).attr('daystatus');
            var id = $(this).attr('leaveempid')
            var leaveid = $('#leavetypeid').val()
            var yos = $('#input-yos').val()
            var countdays = $('#countdays'+leaveid).text()
            var parts = countdays.split('/');
            var totalcountstart = parseFloat(parts[0])
            var totalcountend = parseFloat(parts[1])
            if (daystatus == 1 || daystatus == 2) {
                var minusthis = 0.5;
            } else if(daystatus == 0){
                var minusthis = 1;
            }
          

            Swal.fire({
                title: 'Are you sure you want to delete this Date?',
                html: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                allowOutsideClick: false
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: '/leaves/apply/deletedays',
                        type:"GET",
                        dataType:"json",
                        data:{
                            id   :  id,
                            leaveid : leaveid,
                            employeeid : employeeid
                        },
                        // headers: { 'X-CSRF-TOKEN': token },,
                        success: function(data){
                            if(data == 1)
                            {
                                // loadavailableleave(employeeid,yos)
                                loaddates(employeeid,leaveid)

                              
                                $('#countdays' + leaveid).text((totalcountstart - minusthis) + '/' + totalcountend);
                                toastr.success('Deleted successfully!')
                            }else{
                                toastr.error('Something went wrong!')
                            }
                        }
                    })
                }
            })
        })


        $(document).on('click', '.appliedleaves', function(){
            var teacherid = $(this).attr('teacherid')

            $('#employeeidforleave').val(teacherid)
            $('#modal-showappliedleave').modal('show')
            
            loadappliedleaves(teacherid)
        })


        $(document).on('click', '#amleave', function(){
            $('#pmleave').prop('checked', false)
        })
        $(document).on('click', '#pmleave', function(){
            $('#amleave').prop('checked', false)
        })
        // ========================================================== Functions =========================================================================
      
        // function getDateArrayFromRange(dateRange) {
        //     var startDate = new Date(dateRange.split(' - ')[0]);
        //     var endDate = new Date(dateRange.split(' - ')[1]);
        //     var datesArray = [];

        //     while (startDate <= endDate) {
        //         var formattedDate = startDate.toISOString().split('T')[0];
        //         datesArray.push(formattedDate);
        //         startDate.setDate(startDate.getDate() + 1);
        //     }

        //     return datesArray;
        // }



        function load_employees(){
            $.ajax({
                type: "GET",
                url: "/leaves/apply/loademployees",
                success: function (data) {
                    employee_list = data
                    console.log(employee_list);
                    load_employeedatatable()
                }
            });
        }

        // this function is for the employee list datatable
        function load_employeedatatable(){
            $('#employee_datatables').DataTable({
                destroy: true,
                lengthChange: false,
                scrollX: true,
                autoWidth: true,
                order: false,
                data: employee_list,
                columns : [
                    {"data" : 'full_name'},
                    {"data" : null},
                    {"data" : null},
                    {"data" : null},
                    {"data" : null}
                ], 
                columnDefs: [
                    {
                        'targets': 0,
                        'orderable': false, 
                        'createdCell': function (td, cellData, rowData, row, col) {
                            var index = row + 1; 
                            var text = '<span>&nbsp;&nbsp;' + index + '</span>';
                            $(td)[0].innerHTML = text;
                            $(td).addClass('align-middle text-left');
                        }
                    },
                    {
                        'targets': 1,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            var text = '<a class="mb-0" style="text-transform: uppercase;">'+rowData.full_name+'</a>';
                            $(td)[0].innerHTML =  text
                            $(td).addClass('align-middle  text-left')
                        }
                    },
                    {
                        'targets': 2,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            if (rowData.yos == null) {
                                var yos = 0
                            } else {
                                var yos = rowData.yos
                            }
                            var text = '<a class="mb-0" style="text-transform: uppercase;">'+yos+'</a>';
                            $(td)[0].innerHTML =  text
                            $(td).addClass('align-middle  text-center')
                        }
                    },
                    {
                        'targets': 3,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            var text = '<a href="javascript:void(0)" class="text-primary appliedleaves" id="appliedleaves'+rowData.id+'" teacherid="'+rowData.id+'">'+rowData.countleaves+'</a>';
                            $(td)[0].innerHTML = text;
                            $(td).addClass('align-middle text-center');
                        }

                    },
                    {
                        'targets': 4,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            if (rowData.yos == null) {
                                var yos = 0
                            } else {
                                var yos = rowData.yos
                            }
                            var buttons = '<div class="text-center" style="display: flex; justify-content: center; align-items: center;">' +
                                '<button type="button" class="btn btn-sm btn-primary applyleave" id="applyleave'+rowData.id+'" teacherid="'+rowData.id+'" data-id="'+rowData.id+'" yos="'+yos+'"><span>Apply Leave</span></button>' +
                                '</div>';
                            $(td)[0].innerHTML =  buttons
                            $(td).addClass('text-center')
                            $(td).addClass('align-middle')
                        }
                    }
                ]
            })
        }

        function loadleaves(){
            $.ajax({
                type: "GET",
                url: "/leaves/apply/loadleaves",
                success: function (data) {
                    leaves = data;
                    
                    $('#leavetype').empty();
                    $('#leavetype').append('<option value="">Select Leaves</option>')
                    $('#leavetype').select2({
                        data: leaves,
                        allowClear : true,
                        placeholder: 'Select Leaves'
                    });
                }
            });
        }

        function loadavailableleave(teacherid,yos){
            
            $.ajax({
                type: "GET",
                url: "/leaves/apply/loadavailableleaves",
                data: {
                    teacherid : teacherid,
                    yos : yos
                },
                success: function (data) {
                    allleaves = data

                    console.log('allleaves');
                    console.log(allleaves);
                    console.log('allleaves');
                    var table = $('<table width="100%" class="table-sm">');
                    table.append(`<thead>
                                    <tr>
                                        <th width="80%">LEAVE TYPE</th>
                                        <th class="text-center" width="20%">ACTION</th>
                                    </tr>
                                </thead>`);
                    var tbody = $('<tbody>');
                    $.each(data, function (index, item) {
                        var row = $('<tr>');
                            if (item.applicable == 1) {
                                row.append('<td class="text-left" style="vertical-align: middle;"><span>(<span id="countdays'+item.id+'">' + item.countapplied + '/' + item.dayss + '</span>) ' + item.leave_type + '</span></td>');
                                row.append('<td class="text-center vertical-middle"><input type="checkbox" class="leavetype"  name="leaveid" id="leavetype'+item.id+'" leaveid="'+item.id+'" value="'+item.id+'"  leavedesc="(' + item.countapplied + '/' + item.dayss + ') ' + item.leave_type + '" style="width: 18px; height: 18px; margin-top: 6px!important;" /></td>');
                            } else {
                                row.append('<td class="text-left" style="vertical-align: middle;"><s class="text-danger">'+item.leave_type+'</s></td>');
                                row.append('<td class="text-center vertical-middle"><i class="fas fa-stop-circle text-danger"></i></td>');
                            }
                        
                        tbody.append(row);
                    })

                    table.append(tbody);
                    $('.availableleave').empty().append(table);
                }
            });
        }

        function loaddates(employeeid,leaveid){
            $.ajax({
                type: "GET",
                url: "/leaves/apply/loaddates",
                data: {
                    employeeid : employeeid,
                    leaveid : leaveid
                },
                success: function (data) {

                    console.log(data);
                    var table = $('<table width="100%" class="table-sm">');
                    table.append(`<thead>
                                    <tr>
                                        <th width="30%">Dates</th>
                                        <th width="50%">Dates</th>
                                        <th class="text-center" width="20%">ACTION</th>
                                    </tr>
                                </thead>`);
                    var tbody = $('<tbody>');
                    $.each(data, function (index, item) {
                        if (item.halfday == 0) {
                            var status = 'Whole Day'
                        } else if(item.halfday == 1){
                            var status = 'Half Day Morning'
                        } else {
                            var status = 'Half Day Afternoon'
                        }
                        var row = $('<tr>');
                        row.append('<td class="text-left text-primary" style="vertical-align: middle;">'+item.ldate+'</td>');
                        row.append('<td class="text-left text-primary" style="vertical-align: middle;">'+status+'</td>');
                        row.append('<td class="text-center vertical-middle"><a href="javascript:void(0)" class="deletedate" id="deletedate'+item.id+'" leaveempid="'+item.id+'" daystatus="'+item.halfday+'"><i class="fas fa-trash text-danger"></i></a></td>');
                        
                        tbody.append(row);
                    })

                    table.append(tbody);
                    $('.leavedates').empty().append(table);
                }
            });
        }

        function loadappliedleaves(teacherid){
            $.ajax({
                type: "GET",
                url: "/leaves/apply/loadleavesapplied",
                data: {
                    teacherid : teacherid
                },
                success: function (data) {
                    console.log('MYLEAVE', data);
                    employeeappliedleaves = data;
                    load_employeeleavesdatatable()
                }
            });
        }

        function load_employeeleavesdatatable(){
            $('#employee_appliedleaves').DataTable({
                destroy: true,
                lengthChange: false,
                scrollX: false,
                autoWidth: false,
                searching: false,
                order: false,
                data: employeeappliedleaves,
                columns : [
                    {"data" : null},
                    // {"data" : null},
                    // {"data" : null},
                    // {"data" : null},
                    {"data" : null}
                ], 
                columnDefs: [
                    {
                        'targets': 0,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            var text = '<a class="mb-0" style="text-transform: uppercase;">'+rowData.leave_type+'</a> <br> <span class="text-muted m-0" style="font-size:12px;">' + rowData.createddate+ '</span> ';
                            $(td)[0].innerHTML =  text
                            $(td).addClass('align-middle  text-left')
                        }
                    },
                    // {
                    //     'targets': 1,
                    //     'orderable': false, 
                    //     'createdCell':  function (td, cellData, rowData, row, col) {
                    //         var text = '<a class="mb-0" style="text-transform: uppercase;">'+rowData.datefrom+'</a>';
                    //         $(td)[0].innerHTML =  text
                    //         $(td).addClass('align-middle  text-left')
                    //     }
                    // },
                    // {
                    //     'targets': 2,
                    //     'orderable': false, 
                    //     'createdCell':  function (td, cellData, rowData, row, col) {
                    //         var text = '<a class="mb-0" style="text-transform: uppercase;">'+rowData.dateto+'</a>';
                    //         $(td)[0].innerHTML =  text
                    //         $(td).addClass('align-middle  text-left')
                    //     }
                    // },
                    // {
                    //     'targets': 1,
                    //     'orderable': false, 
                    //     'createdCell':  function (td, cellData, rowData, row, col) {
                    //         var text = '<a href="javascript:void(0)" class="text-primary appliedleavesfiles" id="appliedleavesfiles'+rowData.id+'" teacherid="'+rowData.id+'"><i class="fas fa-file"></i></a>';
                    //         $(td)[0].innerHTML = text;
                    //         $(td).addClass('align-middle text-center');
                    //     }

                    // },
                    {
                        'targets': 1,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            var text = '<a href="javascript:void(0)" class="deleteempleave" id="deleteempleave'+rowData.id+'" leaveempid="'+rowData.id+'"><i class="fas fa-trash text-danger"></i></a>';
                            $(td)[0].innerHTML =  text
                            $(td).addClass('text-center')
                            $(td).addClass('align-middle')
                        }
                    }
                ]
            })
        }

        
        // ==============================================================================================================================================

    })
</script>
@endsection


