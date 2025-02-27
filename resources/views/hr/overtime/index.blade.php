


@extends('hr.layouts.app')
@section('content')
<div class="card" style="border: none;">
    <div class="card-header">
        <div class="row">
            <div class="col-md-2 text-left">
                <button type="button" class="btn btn-info btn-block" data-toggle="modal" data-target="#fileovertime"><i class="fa fa-plus"></i> File</button>
            </div>
            <div class="col-md-6 text-left">
                <input type="text" class="form-control input-daterange" id="input-daterange"/>
            </div>
            <div class="col-md-4 text-right">
                <button type="button" class="btn btn-primary" id="btn-generate"><i class="fa fa-sync"></i> Generate</button>
            </div>
        </div>
    </div>
</div>
<div id="results-container">

</div>
<div id="fileovertime" class="modal custom-modal fade" role="dialog" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document" style="color: black;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Overtime Application</h4>
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
                    <div class="col-md-5">
                        <label>Date Range</label>
                    </div>
                    <div class="col-md-3">
                        <label>Time From</label>
                    </div>
                    <div class="col-md-3">
                        <label>Time To</label>
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
<script>
    $(document).ready(function(){
        $('.select2').select2();
        $('.input-daterange').daterangepicker();
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
                url: '/hr/overtime/filter',
                type: 'GET',
                data: {
                    daterange  : $('#input-daterange').val()
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
        $('#btn-adddates').on('click', function(){
            $('#div-adddates').append(
                '<div class="row mb-2">'+
                    '<div class="col-md-5">'+
                        '<input type="date" class="form-control input-adddaterange p-1"/>'+
                    '</div>'+
                    '<div class="col-md-3">'+
                        '<input type="time" class="form-control input-addtimefrom p-1"/>'+
                    '</div>'+
                    '<div class="col-md-3">'+
                        '<input type="time" class="form-control input-addtimeto p-1"/>'+
                    '</div>'+
                    '<div class="col-md-1">'+
                        '<button type="button" class="btn btn-default btn-removedate"><i class="fa fa-times"></i></button>'+
                    '</div>'+
                '</div>'
            )
            // $('.input-adddaterange').daterangepicker()
        })
        $(document).on('click','.btn-removedate', function(){
            $(this).closest('.row').remove();
        })
        $('#btn-submit').on('click', function(){
            var employeeids = $('#select-employees').val();
            var remarks = $('#textarea-remarks').val();
            var selecteddates = [];

            var checkvalidation = 0;

            $('.input-adddaterange').each(function(){
                
                if($(this).val().replace(/^\s+|\s+$/g, "").length > 0)
                {
                    obj = {
                        daterange : $(this).val(),
                        timefrom  : $(this).closest('.row').find('.input-addtimefrom').val(),
                        timeto    : $(this).closest('.row').find('.input-addtimeto').val()
                    };

                    if($(this).closest('.row').find('.input-addtimefrom').val().replace(/^\s+|\s+$/g, "").length > 0 && $(this).closest('.row').find('.input-addtimeto').val().replace(/^\s+|\s+$/g, "").length > 0)
                    {
                        selecteddates.push(obj)
                    }

                }
            })

            if(employeeids.length == 0)
            {
                checkvalidation = 1;
                toastr.warning('Please select employees!', 'Overtime Application')
            }
            if(selecteddates.length == 0)
            {
                checkvalidation = 1;
                toastr.warning('Please select dates!', 'Overtime Application')
            }

            if(checkvalidation == 0)
            {
                $.ajax({
                    url: '/hr/overtime/fileovertime',
                    type: 'GET',
                    data: {
                        employeeids     :   employeeids,
                        remarks         :   remarks,
                        selecteddates   :   JSON.stringify(selecteddates)
                    },
                    complete:function(){
                        $(".swal2-container").remove();
                        $('body').removeClass('swal2-shown')
                        $('body').removeClass('swal2-height-auto')
                        toastr.success('Filed successfully!', 'Overtime Application')
                    }
                })
            }
        })
        $(document).on('click','.btn-deleteovertime', function(){
            var id = $(this).attr('data-id');
            var thiscard = $(this).closest('.eachovertime');
            Swal.fire({
                title: 'Are you sure you want to delete this request?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                allowOutsideClick: false
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: '/hr/overtime/delete',
                        type:"GET",
                        dataType:"json",
                        data:{
                            id: id,
                        },
                        // headers: { 'X-CSRF-TOKEN': token },,
                        success: function(data){
                            if(data == 1)
                            {
                                thiscard.remove();
                                toastr.success('Deleted successfully!')
                                $('#btn-generate').click();
                            }else{
                                toastr.error('Something went wrong!')
                            }
                        }
                    })
                }
            })
        })
        $(document).on('click','.dropdown-approve', function(){
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
                        url: '/hr/overtime/approve',
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
        $(document).on('click','.dropdown-pending', function(){
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
                        url: '/hr/overtime/pending',
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
        $(document).on('click','.dropdown-disapprove', function(){
            var id = $(this).attr('data-id');
            
            Swal.fire({
                title: 'Disapproving request...',
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
                        url: '/hr/overtime/disapprove',
                        type:"GET",
                        dataType:"json",
                        data:{
                            id: id,
                        },
                        // headers: { 'X-CSRF-TOKEN': token },,
                        success: function(data){
                            if(data == 1)
                            {
                                toastr.success('Disapproved successfully!')
                                $('#btn-generate').click();
                            }else{
                                toastr.error('Something went wrong!')
                            }
                        }
                    })
                }
            })
        })
    })
</script>
@endsection

