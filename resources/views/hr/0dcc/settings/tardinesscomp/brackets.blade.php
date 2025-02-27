<div class="row mb-2">
    <div class="col-md-12 text-right">
        <button type="button" class="btn btn-sm btn-primary" id="btn-addbracket" data-deptid={{$deptid}}><i class="fa fa-plus"></i> Add Time Bracket</button>
    </div>
</div>
<div class="row" style="font-size: 11px;">
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
</div>
@if(count($computations)>0)
    @foreach($computations as $computation)
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
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
                <select class="form-control select-deducttype form-control-sm">
                    <option value="1" @if($computation->deducttype == 1) selected @endif>Fixed Amount</option>
                    <option value="2" @if($computation->deducttype == 2) selected @endif>Daily Rate %</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="number" class="input-amount form-control form-control-sm" value="{{$computation->amount}}"/>
            </div>
            <div class="col-md-12 text-right">
                <button type="button" class="btn btn-sm btn-default btn-update" data-id="{{$computation->id}}"><i class="fa fa-check"></i></button>
                <button type="button" class="btn btn-sm btn-default btn-delete" data-id="{{$computation->id}}"><i class="fa fa-trash text-danger"></i></button>
            </div>
        </div>
        <hr/>
    @endforeach
@endif
<div id="container-addbrackets"></div>
<script>
    
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
                        thisrow.remove()   
                        toastr.success('Deleted successfully!', 'Time Braket')
                    }
                })
            }
        })
    })
</script>