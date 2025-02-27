<style>
    .disabled {
        pointer-events: none; /* Disable mouse events */
        opacity: 0.5; /* Apply visual style for disabled state */
    }
</style>
@php
    $activedepts = collect($computations)->where('isactive','1')->unique('departmentid')->values();
@endphp
<div class="row mb-2">
    <div class="col-md-6">
        <div class="form-group clearfix">
            <div class="icheck-primary d-inline">
            <input type="checkbox" id="checkbox-activation" @if(collect($activedepts)->where('departmentid',$deptid)->count()>0) checked @endif>
            <label for="checkbox-activation">
                Active
            </label>
            </div>
            {{-- @php
                dd($computationsbaseonattendance);
            @endphp --}}
            {{-- <div class="icheck-primary d-inline" style="padding-left: 50px;">
                <input type="checkbox" id="checkbox-activation-boa" 
                @if($computationsbaseonattendance)
                    @if ($computationsbaseonattendance->isactive == 1)
                        checked
                    @endif
                @endif>
                <label for="checkbox-activation-boa">
                    Tardiness Base on Salary
                </label>
            </div> --}}
        </div>
        
        {{-- <div class="form-group m-0">
            <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                <input type="checkbox" class="custom-control-input" id="department-{{$deptid}}" data-id="{{$deptid}}" @if(collect($activedepts)->where('departmentid',$deptid)->count()>0) checked @endif/>
                <label class="custom-control-label" for="department-{{$deptid}}">&nbsp;</label>
            </div>
        </div> --}}
    </div>
    <div class="col-md-6 text-right">
        <button type="button" class="btn btn-sm btn-primary" id="btn-addbracket" data-deptid={{$deptid}}><i class="fa fa-plus"></i> Add Time Bracket</button>
    </div>
</div>
<div id="container-addbrackets"></div>
<div class="row">
    <div class="col-md-12 text-right">
        <button type="button" class="btn btn-sm btn-success" id="btn-submit">Submit New Time Brackets</button>
    </div>
</div>
{{-- <div class="row" style="font-size: 11px;">
    <div class="col-md-3">
        <label>From (mins.)</label>
    </div>
    <div class="col-md-3">
        <label>To (mins.)</label>
    </div>
    <div class="col-md-2" hidden>
        <label>Time Type</label>
    </div>
    <div class="col-md-2">
        <label>Deduct Type</label>
    </div>
    <div class="col-md-4 text-center">
        <label>% or Amount</label>
    </div>
</div> --}}
@if(count($computations)>0)
    @foreach(collect($computations)->sortByDesc('id')->values() as $computation)
    <hr data-id="{{$computation->id}}"/>
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label style="font-size: 11px;">From (mins.)</label>
                <div class="input-group input-group-sm date" id="reservationdate" data-target-input="nearest">
                    <input type="number" class="input-from form-control form-control-sm" value="{{number_format($computation->latefrom)}}" min="0"/>
                <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                <div class="input-group-text">mins</div>
                </div>
                </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group form-group-sm">
                    <label style="font-size: 11px;">To (mins.)</label>
                <div class="input-group input-group-sm date" id="reservationdate" data-target-input="nearest">
                    <input type="number" class="input-to form-control form-control-sm" value="{{number_format($computation->lateto)}}" min="0"/>
                <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                <div class="input-group-text">mins</div>
                </div>
                </div>
                </div>
            </div>
            <div class="col-md-2"hidden>
                <select class="form-control select-timetype form-control-sm">
                    <option value="1" @if($computation->latetimetype == 1) selected @endif>mins.</option>
                    <option value="2" @if($computation->latetimetype == 2) selected @endif>hrs.</option>
                </select>
            </div>
            <div class="col-md-3">
                <label style="font-size: 11px;">Deduct Type</label>
                <select class="form-control select-deducttype form-control-sm">
                    <option value="1" @if($computation->deducttype == 1) selected @endif>Fixed Amount</option>
                    <option value="2" @if($computation->deducttype == 2) selected @endif>Daily Rate %</option>
                </select>
            </div>
            <div class="col-md-3">
                <label style="font-size: 11px;">% or Amount</label>
                <input type="number" class="input-amount form-control form-control-sm" value="{{$computation->amount}}" min="0"/>
            </div>
            <div class="col-md-12 text-right">
                <button type="button" class="btn btn-sm btn-default btn-update" data-id="{{$computation->id}}"><i class="fa fa-check"></i></button>
                <button type="button" class="btn btn-sm btn-default btn-delete" data-id="{{$computation->id}}"><i class="fa fa-trash text-danger"></i></button>
            </div>
        </div>
    @endforeach
@endif
<script>
    $('#btn-submit').hide()
    $('input').on('input', function(){
        $(this).closest('.row').find('.btn-update').removeClass('btn-default')
        $(this).closest('.row').find('.btn-update').addClass('btn-warning')
    })
    $('.select-timetype').on('change', function(){
        $(this).closest('.row').find('.btn-update').removeClass('btn-default')
        $(this).closest('.row').find('.btn-update').addClass('btn-warning')
    })
    $('.select-deducttype').on('change', function(){
        $(this).closest('.row').find('.btn-update').removeClass('btn-default')
        $(this).closest('.row').find('.btn-update').addClass('btn-warning')
    })
    $('#btn-addbracket').on('click', function(){
        var deptid = '{{$deptid}}';
        var appendhtml = '<div class="row mb-2 each-newbracket" data-id="0" data-deptid="'+deptid+'">'+
                            '<div class="col-md-3"> <label style="font-size: 11px;">From (mins.)</label>'+
                                '<input type="number" class="input-from-new form-control form-control-sm" min="0"/>'+
                            '</div>'+
                                '<div class="col-md-3"><label style="font-size: 11px;">To (mins.)</label>'+
                                '<input type="number" class="input-to-new form-control form-control-sm" min="0"/>'+
                            '</div>'+
                            '<div class="col-md-2"hidden>'+
                                '<select class="select-timetype-new form-control form-control-sm">'+
                                    '<option value="1">mins.</option>'+
                                    '<option value="2">hrs.</option>'+
                                '</select>'+
                            '</div>'+
                            '<div class="col-md-3"><label style="font-size: 11px;">Deduct Type</label>'+
                                '<select class="select-deducttype-new  form-control form-control-sm">'+
                                    '<option value="1">Fixed Amount</option>'+
                                    '<option value="2">Daily Rate %</option>'+
                                '</select>'+
                            '</div>'+
                            '<div class="col-md-2"><label style="font-size: 11px;">% or Amount</label>'+
                                '<input type="number" class="input-amount-new form-control form-control-sm" min="0"/>'+
                            '</div>'+
                        '<div class="col-md-1 text-danger align-self-end text-right">'+
                            '<button type="button" class="btn btn-block btn-outline-danger bracket-remove"><i class="fa fa-times"></i></button>'
                        '</div>'+
                    '</div>';
        $('#container-addbrackets').prepend(appendhtml)
        
        $('#btn-submit').show()
    })
    $(document).on('click', '.bracket-remove', function(){
        $(this).closest('.row').remove()
        if($('.each-newbracket').length == 0)
        {
            $('#btn-submit').hide()
        }else{
            $('#btn-submit').show()
        }
    })
    $('#btn-submit').on('click', function(){
        var newbrackets = [];
        var validation = 0;
        $('.each-newbracket').each(function(){
            var eachvalidation = 0;

            if($(this).find('.input-from-new').val().replace(/^\s+|\s+$/g, "").length == 0)
            {
                eachvalidation+=1;
                $(this).find('.input-from-new').css('border','1px solid red')
            }
            if($(this).find('.input-to-new').val().replace(/^\s+|\s+$/g, "").length == 0)
            {
                eachvalidation+=1;
                $(this).find('.input-to-new').css('border','1px solid red')
            }
            if($(this).find('.input-amount-new').val().replace(/^\s+|\s+$/g, "").length == 0)
            {
                eachvalidation+=1;
                $(this).find('.input-amount-new').css('border','1px solid red')
            }
            if(eachvalidation == 0)
            {
                obj = {
                    deptid : '{{$deptid}}',
                    latefrom : $(this).find('.input-from-new').val(),
                    lateto : $(this).find('.input-to-new').val(),
                    timetype : $(this).find('.select-timetype-new').val(),
                    deducttype : $(this).find('.select-deducttype-new').val(),
                    amount : $(this).find('.input-amount-new').val()
                }
                newbrackets.push(obj);
            }else{
                validation+=1;
            }

        })
        if(validation == 0)
        {
            $.ajax({
                url: '/hr/tardinesscomp/addbrackets',
                type:"GET",
                data:{
                    brackets: JSON.stringify(newbrackets)
                },
                // headers: { 'X-CSRF-TOKEN': token },,
                success: function(data){    
                    if(data == 1)
                    {                            
                        toastr.success('Added successfully!', 'Time Braket')
                        $('#select-departmentid').change();
                    }     
                }
            })
        }else{
            toastr.warning('Please fill in the required fields!', 'New Time Brakets')
        }
    })
    $('.btn-update').on('click', function(){
        var thisrow = $(this).closest('.row');
        var dataid = $(this).attr('data-id');
        // alert(dataid)
        var eachvalidation = 0;

        if(thisrow.find('.input-from').val().replace(/^\s+|\s+$/g, "").length == 0)
        {
            eachvalidation+=1;
            thisrow.find('.input-from').css('border','1px solid red')
        }
        if(thisrow.find('.input-to').val().replace(/^\s+|\s+$/g, "").length == 0)
        {
            eachvalidation+=1;
            thisrow.find('.input-to').css('border','1px solid red')
        }
        if(thisrow.find('.input-amount').val().replace(/^\s+|\s+$/g, "").length == 0)
        {
            eachvalidation+=1;
            thisrow.find('.input-amount').css('border','1px solid red')
        }
        if(eachvalidation == 0)
        {
            $.ajax({
                url: '/hr/tardinesscomp/updatebracket',
                type:"GET",
                data:{
                    dataid      : dataid,
                    latefrom    : thisrow.find('.input-from').val(),
                    lateto      : thisrow.find('.input-to').val(),
                    timetype    : thisrow.find('.select-timetype').val(),
                    deducttype  : thisrow.find('.select-deducttype').val(),
                    amount      : thisrow.find('.input-amount').val()
                },
                // headers: { 'X-CSRF-TOKEN': token },,
                success: function(data){
                    if(data == 1)
                    {        
                        thisrow.find('.btn-update').addClass('btn-default')
                        thisrow.find('.btn-update').removeClass('btn-warning')                
                        toastr.success('Updated successfully!', 'Time Braket')
                    }
                }
            })
        }else{
            toastr.warning('Please fill in the required fields!', 'New Time Brakets')
        }
    })
    $('.btn-delete').on('click', function(){        
        var dataid = $(this).attr('data-id');
        var thisrow = $(this).closest('.row');
        Swal.fire({
            title: 'Are you sure you want to delete this bracket?',
            type: 'warning',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Delete',
            showCancelButton: true,
            allowOutsideClick: false
        }).then((confirm) => {
            if (confirm.value) {

                $.ajax({
                url: '/hr/tardinesscomp/deletebracket',
                    type: 'get',
                    dataType: 'json',
                    data: {
                        id          :   dataid
                    },
                    success: function(data){
                        $('hr[data-id="'+dataid+'"]').remove()
                        thisrow.remove()   
                        toastr.success('Deleted successfully!', 'Time Braket')
                    }
                })
            }
        })
    })
    $('#checkbox-activation').on('click', function(){
        
        var deptid = '{{$deptid}}';
        var isactive = 0;
        if ( $(this).is(':checked') ) {
            isactive = 1
        } 
        $.ajax({
            url: '/hr/tardinesscomp/activation',
            type:"GET",
            data:{
                deptid      : deptid,
                isactive    : isactive
            },
            // headers: { 'X-CSRF-TOKEN': token },,
            success: function(data){
                if(data == 1)
                {            
                    $('#checkbox-activation-bos').prop('checked', false);
                    toastr.success('Updated successfully!', 'Activation')
                }else if(data == 3){
                    toastr.warning('No brackets found!', 'Activation')
                }
            }
        })
    })
    $('#checkbox-activation-boa').on('click', function(){
        $('#checkbox-activation').prop('checked', false);
        var deptid = '{{$deptid}}';
        var applyall = 0;
        $.ajax({
            type: "GET",
            url: "/hr/tardinesscomp/baseonattendance",
            data: {
                deptid : deptid,
                applyall : applyall
            },

            success: function (data) {
                if(data == 1){            
                    toastr.success('Updated successfully!', 'Base On Attendance')
                }
            }
        });
    })
    
</script>