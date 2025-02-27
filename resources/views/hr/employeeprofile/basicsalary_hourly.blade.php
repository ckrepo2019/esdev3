<hr/>
<div class="row mt-2">
    <div class="col-md-4">
        <label>No of hours per week</label>
        <input type="number" step="any" class="form-control" name="hoursperweek" id="hoursperweek" value="0" readonly/>
    </div>
    <div class="col-md-4">
        <label>Salary amount (per hour)</label>
        <input type="number" step="any" class="form-control" name="salaryamount" id="salaryamount" />
    </div>
</div>
<br/>
<div class="row">
    <div class="col-md-2" style=" display: flex;align-items: center;">
            <div class="icheck-primary d-inline col-md-5">
                <input type="checkbox" name="daysrender[]" value="monday" id="daymon" class="hourlycheckbox"  disabled>
                <label class="mr-5" for="daymon">
                Mon
                </label>
            </div>
    </div>
    <div class="col-md-6">
        <input type="number" class="form-control form-control-sm monday hourlydaysrender" name="nodaysrender[]" value="0">
    </div>
    {{-- <div class="col-md-1">
        <button type="button" class="btn btn-sm btn-block btn-default" disabled><i class="fa fa-plus"></i> Time sched</button>
    </div> --}}
</div>
<div class="row">
    <div class="col-md-2" style=" display: flex;align-items: center;">
            <div class="icheck-primary d-inline col-md-5">
                <input type="checkbox" name="daysrender[]" value="tuesday" id="daytue" class="hourlycheckbox" disabled>
                <label class="mr-5" for="daytue">
                Tue
                </label>
            </div>
    </div>
    <div class="col-md-6">
        <input type="number" class="form-control form-control-sm tuesday hourlydaysrender" name="nodaysrender[]" value="0">
    </div>
    {{-- <div class="col-md-1">
        <button type="button" class="btn btn-sm btn-block btn-default" disabled><i class="fa fa-plus"></i> Time sched</button>
    </div> --}}
</div>
<div class="row">
    <div class="col-md-2" style=" display: flex;align-items: center;">
            <div class="icheck-primary d-inline col-md-5">
                <input type="checkbox" name="daysrender[]" value="wednesday" id="daywed" class="hourlycheckbox" disabled>
                <label class="mr-5" for="daywed">
                Wed
                </label>
            </div>
    </div>
    <div class="col-md-6">
        <input type="number" class="form-control form-control-sm wednesday hourlydaysrender" name="nodaysrender[]" value="0">
    </div>
    {{-- <div class="col-md-1">
        <button type="button" class="btn btn-sm btn-block btn-default" disabled><i class="fa fa-plus"></i> Time sched</button>
    </div> --}}
</div>
<div class="row">
    <div class="col-md-2" style=" display: flex;align-items: center;">
            <div class="icheck-primary d-inline col-md-5">
                <input type="checkbox" name="daysrender[]" value="thursday" id="daythu" class="hourlycheckbox" disabled>
                <label class="mr-5" for="daythu">
                Thu
                </label>
            </div>
    </div>
    <div class="col-md-6">
        <input type="number" class="form-control form-control-sm thursday hourlydaysrender" name="nodaysrender[]" value="0">
    </div>
    {{-- <div class="col-md-1">
        <button type="button" class="btn btn-sm btn-block btn-default" disabled><i class="fa fa-plus"></i> Time sched</button>
    </div> --}}
</div>
<div class="row">
    <div class="col-md-2" style=" display: flex;align-items: center;">
            <div class="icheck-primary d-inline col-md-5">
                <input type="checkbox" name="daysrender[]" value="friday" id="dayfri" class="hourlycheckbox" disabled>
                <label class="mr-5" for="dayfri">
                Fri
                </label>
            </div>
    </div>
    <div class="col-md-6">
        <input type="number" class="form-control form-control-sm friday hourlydaysrender" name="nodaysrender[]" value="0">
    </div>
    {{-- <div class="col-md-1">
        <button type="button" class="btn btn-sm btn-block btn-default" disabled><i class="fa fa-plus"></i> Time sched</button>
    </div> --}}
</div>
<div class="row">
    <div class="col-md-2" style=" display: flex;align-items: center;">
            <div class="icheck-primary d-inline col-md-5">
                <input type="checkbox" name="daysrender[]" value="saturday" id="daysat" class="hourlycheckbox" disabled>
                <label class="mr-5" for="daysat">
                Sat
                </label>
            </div>
    </div>
    <div class="col-md-6">
        <input type="number" class="form-control form-control-sm saturday hourlydaysrender" name="nodaysrender[]" value="0">
    </div>
    {{-- <div class="col-md-1">
        <button type="button" class="btn btn-sm btn-block btn-default" disabled><i class="fa fa-plus"></i> Time sched</button>
    </div> --}}
</div>
<div class="row">
    <div class="col-md-2" style=" display: flex;align-items: center;">
            <div class="icheck-primary d-inline col-md-5">
                <input type="checkbox" name="daysrender[]" value="sunday" id="daysun" class="hourlycheckbox" disabled>
                <label class="mr-5" for="daysun">
                Sun
                </label>
            </div>
    </div>
    <div class="col-md-6">
        <input type="number" class="form-control form-control-sm sunday hourlydaysrender" name="nodaysrender[]" value="0">
    </div>
    {{-- <div class="col-md-1">
        <button type="button" class="btn btn-sm btn-block btn-default" disabled><i class="fa fa-plus"></i> Time sched</button>
    </div> --}}
</div>
<div class="row mt-2">
    <div class="col-md-4">
        <label>Payment Type</label>
        <select class="form-control" name="paymenttype" id="paymenttype" required>
            <option value="cash">Cash</option>
            <option value="check">Check</option>
            <option value="banktransfer">Bank deposit</option>
        </select>
    </div>
    <div class="col-md-2"  style=" display: grid;" id="submitbuttoncontainer">
        <label>&nbsp;</label>
        <button type="button" class="btn btn-primary" id="submitbutton">Save Changes</button>
    </div>
</div>
<script>
    
    $('#submitbuttoncontainer').hide();
    
    var workingdays;
    var workinghours;
    $('.hourlycheckbox').unbind().click(function(){
        var workingdaysarray= [];
        var workinghoursarray= [];
        $('.hourlycheckbox:checked').each(function(){
            workingdaysarray.push($(this).val())
            workinghoursarray.push($(this).closest('.row').find('.hourlydaysrender').val())
        })
        workingdays = workingdaysarray;
        workinghours = workinghoursarray;
    })
    $('.hourlydaysrender').on('keyup', function(){
        if($(this).val() > 0)
        {
            $(this).closest('.row').find('.hourlycheckbox').prop('checked', true)
            // $(this).closest('.row').find('button').prop('disabled', false)
            // $(this).closest('.row').find('button').removeClass('btn-default')
            // $(this).closest('.row').find('button').addClass('btn-info')
        }else{
            $(this).closest('.row').find('.hourlycheckbox').prop('checked', false)
            // $(this).closest('.row').find('button').prop('disabled', true)
            // $(this).closest('.row').find('button').removeClass('btn-info')
            // $(this).closest('.row').find('button').addClass('btn-default')
        }
        $('.hourlycheckbox').click();
        console.log(workingdays)
        console.log(workinghours)
        var total = 0;
        for (var i = 0; i < workinghours.length; i++) {
            total += workinghours[i] << 0;
        }
        $('input[name="hoursperweek"]').val(total)

        if(total > 0)
        {
            $('#submitbuttoncontainer').show();
        }else{
            $('#submitbuttoncontainer').hide();
        }
    })
    $('#submitbutton').on('click', function(){
        var basistypeid = $('#selectbasis').val();
        var hoursperweek = $('#hoursperweek').val();
        var salaryamount = $('#salaryamount').val();
        var paymenttype = $('#paymenttype').val();

        if($('#salaryamount').val().replace(/^\s+|\s+$/g, "").length > 0 && salaryamount.replace(/^\s+|\s+$/g, "").length > 0){
            $('#salaryamount').removeAttr('style');
            $.ajax({
                url: '/hr/employeebasicsalaryinfo',
                type: 'GET',
                dataType: 'json',
                data: {
                    basistypeid     : basistypeid,
                    hoursperweek    : hoursperweek,
                    salaryamount    : salaryamount,
                    workingdays     : workingdays,
                    workinghours    : workinghours,
                    paymenttype     : paymenttype,
                    employeeid      : '{{$employeeid}}'
                },
                success:function(data)
                {
                    if(data == 1)
                    {
                        toastr.success('Updated successfully!', 'Basic Salary Info')
                        $('#custom-content-above-salary-tab').click();
                    }else{
                        toastr.error('Unable to make changes!', 'Basic Salary Info')
                    }
                }
            })
        }else{
            $('#salaryamount').css("border","2px solid red")
        }
    })
</script>
