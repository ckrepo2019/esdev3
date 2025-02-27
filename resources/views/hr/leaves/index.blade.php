


@extends($extends)
@section('content')
<link rel="stylesheet" href="{{asset('plugins/toastr/toastr.min.css')}}">
@php
    $leaves = DB::table('hr_leaves')
                ->where('isactive','1')
                ->where('deleted','0')
                ->get();
@endphp
<section class="content-header p-0">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
           <h1>Filed Leaves</h1> 
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/home">Home</a></li>
            <li class="breadcrumb-item active">Filed Leaves</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
<div class="card" style="border: none;" hidden>
    <div class="card-header">
        <div class="row">
            {{-- <div class="col-md-4 text-left">
                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#fileleave"><i class="fa fa-plus"></i> File</button>
            </div> --}}
            <div class="col-md-4">
                <select class="form-control" id="select-leavetype">
                    <option value="0">All</option>
                    @if(count($leaves)>0)
                        @foreach($leaves as $leave)
                            <option value="{{$leave->id}}">{{$leave->leave_type}}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="col-md-4"></div>
            <div class="col-md-4 text-right">
                <button type="button" class="btn btn-primary btn-block" id="btn-generate"><i class="fa fa-sync"></i> Generate</button>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-3">
        <div class="info-box shadow">
            <span class="info-box-icon text-success"><i class="fa fa-share"></i></span>

            <div class="info-box-content">
            <span class="info-box-text">Leave Applications</span>
            <span class="info-box-number" id="spanbox-submitted"></span>
            </div>
            <!-- /.info-box-content -->
        </div>
    </div>
    <div class="col-3">
        <div class="info-box shadow">
            <span class="info-box-icon text-warning"><i class="fa fa-clock"></i></span>

            <div class="info-box-content">
            <span class="info-box-text">Pending</span>
            <span class="info-box-number" id="spanbox-pending"></span>
            </div>
            <!-- /.info-box-content -->
        </div>
    </div>
    <div class="col-3">
        <div class="info-box shadow">
            <span class="info-box-icon text-success"><i class="fa fa-check"></i></span>

            <div class="info-box-content">
            <span class="info-box-text">Approved</span>
            <span class="info-box-number" id="spanbox-approved"></span>
            </div>
            <!-- /.info-box-content -->
        </div>
    </div>
    <div class="col-3">
        <div class="info-box shadow">
            <span class="info-box-icon text-danger"><i class="fa fa-times"></i></span>

            <div class="info-box-content">
            <span class="info-box-text">Disapproved</span>
            <span class="info-box-number" id="spanbox-disapproved"></span>
            </div>
            <!-- /.info-box-content -->
        </div>
    </div>
</div>
<div class="card" style="border: 1px solid #ddd;">
    <div class="card-body table-responsive"  id="results-container">
    </div>
</div>
<div id="fileleave" class="modal custom-modal fade" role="dialog" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document" style="color: black;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" >Leave Application</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" style="text-align: none !important">
                <div class="row mb-3">
                    <div class="col-md-12 mb-2">
                        <label>Employees</label>
                        <select id="select-employees" class="form-control select2 m-0 text-uppercase" multiple="multiple" data-placeholder="Select employee/s:" name="leaveapplicants[]" required>
                            @foreach($employees as $employee)
                                <option value="{{$employee->id}}">
                                    {{strtoupper($employee->lastname)}}, {{strtoupper($employee->firstname)}} {{strtoupper($employee->suffix)}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-12">
                        <label>Leave Type</label>
                        <select class="form-control" name="leavetype" id="leavetype" required>
                            @foreach ($leavetypes as $leavetype)
                                <option value="{{$leavetype->id}}">{{$leavetype->leave_type}}</option>                        
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-12">
                        <label>Remarks</label>
                        <textarea class="form-control" id="textarea-remarks"></textarea>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-sm btn-info" id="btn-adddates"><i class="fa fa-plus"></i> Add dates</button>
                    </div>
                </div>
                <div id="div-adddates">
                    
                </div>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" id="btn-submit">Submit</button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('footerscripts')
<script src="{{asset('plugins/toastr/toastr.min.js')}}"></script>
<script>
    $(document).ready(function(){
        // $('body').addClass('sidebar-collapse');
        $('.select2').select2();
        $('[data-toggle="tooltip"]').tooltip();
        $('#select-leavetype').on('change', function(){
            $('#results-container').empty();
        })
        $('#btn-generate').on('click', function(){
            Swal.fire({
                title: 'Fetching data...',
                allowOutsideClick: false,
                closeOnClickOutside: false,
                onBeforeOpen: () => {
                    Swal.showLoading()
                }
            })  
            $.ajax({
                url: '/hr/leaves/index',
                type: 'GET',
                data: {
                    leavetypeid   : $('#select-leavetype').val()
                },
                success:function(data){
                    $(".swal2-container").remove();
                    $('body').removeClass('swal2-shown')
                    $('body').removeClass('swal2-height-auto')
                    $('#results-container').empty()
                    $('#results-container').append(data)
                    
                }
            })
        })
        $('#btn-generate').click();
        $(document).on('click', '.btn-approve',function(){
            var id = $(this).attr('data-id');            
            Swal.fire({
                title: 'Approving request...',
                text: 'Would you like to continue?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Continue',
                allowOutsideClick: false
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: '/hr/leaves/approve',
                        type:"GET",
                        dataType:"json",
                        data:{
                            id: id,
                        },
                        // headers: { 'X-CSRF-TOKEN': token },,
                        success: function(data){
                            if(data == 1)
                            {
                                toastr.success('Approved successfully!')
                                $('#btn-generate').click();
                            }else{
                                toastr.error('Something went wrong!')
                            }
                        }
                    })
                }
            })
        })
        $(document).on('click','.btn-pending', function(){
            var id = $(this).attr('data-id');
            
            Swal.fire({
                title: 'Pending request...',
                text: 'Would you like to continue?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Continue',
                allowOutsideClick: false
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: '/hr/leaves/pending',
                        type:"GET",
                        dataType:"json",
                        data:{
                            id: id,
                        },
                        // headers: { 'X-CSRF-TOKEN': token },,
                        success: function(data){
                            if(data == 1)
                            {
                                toastr.success('Pending successfully!')
                                $('#btn-generate').click();
                            }else{
                                toastr.error('Something went wrong!')
                            }
                        }
                    })
                }
            })
        })
        // $(document).on('click','.dropdown-approve', function(){
        //     var id = $(this).attr('data-id');
            
        //     Swal.fire({
        //         title: 'Approving request...',
        //         text: 'Would you like to continue?',
        //         type: 'warning',
        //         showCancelButton: true,
        //         confirmButtonColor: '#3085d6',
        //         cancelButtonColor: '#d33',
        //         confirmButtonText: 'Continue',
        //         allowOutsideClick: false
        //     }).then((result) => {
        //         if (result.value) {
        //             $.ajax({
        //                 url: '/hr/leaves/approve',
        //                 type:"GET",
        //                 dataType:"json",
        //                 data:{
        //                     id: id,
        //                 },
        //                 // headers: { 'X-CSRF-TOKEN': token },,
        //                 success: function(data){
        //                     if(data == 1)
        //                     {
        //                         toastr.success('Request has been approved!')
        //                     }else{
        //                         toastr.error('Something went wrong!')
        //                     }
        //                 }
        //             })
        //         }
        //     })
        // })
        $(document).on('click','.btn-disapprove', function(){
            var id = $(this).attr('data-id');
            
            Swal.fire({
                title: 'Disapproving request...',
                html: '<div class="row"><div class="col-md-12"><label>Remarks:</label><input type="text" class="form-control" id="input-disapproveremarks"/></div></div>',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Continue',
                allowOutsideClick: false
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: '/hr/leaves/disapprove',
                        type:"GET",
                        dataType:"json",
                        data:{
                            id: id,
                            remarks: $('#input-disapproveremarks').val(),
                        },
                        success: function(data){
                            if(data == 1)
                            {
                                toastr.success('Request has been disapproved!')
                                $('#btn-generate').click();
                            }else{
                                toastr.error('Something went wrong!')
                            }
                        }
                    })
                }
            })
        })
        
        $(document).on("keyup",'.filter', function() {
            var input = $(this).val().toUpperCase();
            var visibleCards = 0;
            var hiddenCards = 0;

            $(".container").append($("<div class='card-group card-group-filter'></div>"));


            $(".eachfiledleave").each(function() {
                if ($(this).data("string").toUpperCase().indexOf(input) < 0) {

                $(".card-group.card-group-filter:first-of-type").append($(this));
                $(this).hide();
                hiddenCards++;

                } else {

                $(".card-group.card-group-filter:last-of-type").prepend($(this));
                $(this).show();
                visibleCards++;

                if (((visibleCards % 4) == 0)) {
                    $(".container").append($("<div class='card-group card-group-filter'></div>"));
                }
                }
            });

        });
    })
</script>
@endsection

