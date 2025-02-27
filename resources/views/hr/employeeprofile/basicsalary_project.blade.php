<hr/>
<div class="row mt-2">
    <div class="col-md-3 " style=" display: flex;align-items: center;">
        <div class="icheck-primary d-inline">
            <input type="radio" id="perday" name="projecttype">
            <label for="perday">
                Per day
            </label>
        </div>
    </div>
    <div class="col-md-3">
        <input type="number" step="any" class="form-control hoursperday" name="salaryamount" id="" placeholder="No. of hours per day" disabled/>
    </div>
    <div class="col-md-3">
        <input type="number" step="any" class="form-control salaryamount" name="salaryamount" id="" placeholder="Amount per day" disabled/>
    </div>
</div>
<div class="row mt-2">
    <div class="col-md-3 " style=" display: flex;align-items: center;">
        <div class="icheck-primary d-inline">
            <input type="radio" id="permonth" name="projecttype">
            <label for="permonth">
                Per month
            </label>
        </div>
    </div>
    <div class="col-md-3">
        <input type="number" step="any" class="form-control hoursperday" name="salaryamount" id="" placeholder="No. of hours per day" disabled/>
    </div>
    <div class="col-md-3">
        <input type="number" step="any" class="form-control salaryamount" name="salaryamount" id="" placeholder="Amount per month" disabled/>
    </div>
</div>
<div class="row mt-2">
    <div class="col-md-3 " style=" display: flex;align-items: center;">
        <div class="icheck-primary d-inline">
            <input type="radio" id="perperiod" name="projecttype">
            <label for="perperiod">
                Per payroll period
            </label>
        </div>
    </div>
    <div class="col-md-6">
        <input type="number" step="any" class="form-control salaryamount" name="salaryamount" id="" placeholder="Amount per payroll period" disabled/>
    </div>
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
    // $('#hoursperday').on('keyup', function(){
    //     if($(this).val().length>0)
    //     {
    //         $('#submitbuttoncontainer').show();
    //     }
    // })
    // $('#salaryamount').on('keyup', function(){
    //     if($(this).val()>0)
    //     {
    //         $('#submitbuttoncontainer').show();
    //     }
    // })

    var clickedprojecttype;

    $('input[name="projecttype"]').on('click', function(){
        $('input[type="number"]').prop('disabled',true)
        $('input[type="number"]').removeAttr('style')
        $('input[type="number"]').val('')
        if($(this).attr('id') == 'perday')
        {
            clickedprojecttype = 'perday';
            $(this).closest('.row').find('input[type="number"]').prop('disabled', false)
        }
        if($(this).attr('id') == 'perperiod')
        {
            clickedprojecttype = 'perperiod';
            $(this).closest('.row').find('input[type="number"]').prop('disabled', false)
        }
        if($(this).attr('id') == 'permonth')
        {
            clickedprojecttype = 'permonth';
            $(this).closest('.row').find('input[type="number"]').prop('disabled', false)
        }
        $('#submitbuttoncontainer').show();
    })

    $('#submitbutton').on('click', function(){
        
        var basistypeid = $('#selectbasis').val();
        var paymenttype = $('#paymenttype').val();
        var hoursperday = 0;
        var salaryamount = 0;

        var fillin = 0;
        var thisrow = $('input[name="projecttype"]:checked').closest('.row');

        if(clickedprojecttype == 'perday')
        {
            if(thisrow.find('.hoursperday').val().replace(/^\s+|\s+$/g, "").length == 0){
                thisrow.find('.hoursperday').css("border","2px solid red")
            }else{
                thisrow.find('.hoursperday').removeAttr('style')
            }
            if(thisrow.find('.salaryamount').val().replace(/^\s+|\s+$/g, "").length == 0){
                thisrow.find('.salaryamount').css("border","2px solid red")
            }else{
                thisrow.find('.salaryamount').removeAttr('style')
            }
            if(thisrow.find('.hoursperday').val().replace(/^\s+|\s+$/g, "").length > 0 && thisrow.find('.salaryamount').val().replace(/^\s+|\s+$/g, "").length > 0){
                hoursperday = thisrow.find('.hoursperday').val();
                salaryamount = thisrow.find('.salaryamount').val();
                fillin+=1;
            }
        }
        else if(clickedprojecttype == 'perperiod')
        {
            if(thisrow.find('.salaryamount').val().replace(/^\s+|\s+$/g, "").length == 0){
                thisrow.find('.salaryamount').css("border","2px solid red")
            }else{
                thisrow.find('.salaryamount').removeAttr('style')
                hoursperday = 0;
                salaryamount = thisrow.find('.salaryamount').val();
                fillin+=1;
            }
        }
        else if(clickedprojecttype == 'permonth')
        {
            if(thisrow.find('.hoursperday').val().replace(/^\s+|\s+$/g, "").length == 0){
                thisrow.find('.hoursperday').css("border","2px solid red")
            }else{
                thisrow.find('.hoursperday').removeAttr('style')
            }
            if(thisrow.find('.salaryamount').val().replace(/^\s+|\s+$/g, "").length == 0){
                thisrow.find('.salaryamount').css("border","2px solid red")
            }else{
                thisrow.find('.salaryamount').removeAttr('style')
            }
            if(thisrow.find('.hoursperday').val().replace(/^\s+|\s+$/g, "").length > 0 && thisrow.find('.salaryamount').val().replace(/^\s+|\s+$/g, "").length > 0){
                hoursperday = thisrow.find('.hoursperday').val();
                salaryamount = thisrow.find('.salaryamount').val();
                fillin+=1;
            }
        }

        if(fillin>0)
        {
            $.ajax({
                url: '/hr/employeebasicsalaryinfo',
                type: 'GET',
                dataType: 'json',
                data: {
                    basistypeid     : basistypeid,
                    hoursperday     : hoursperday,
                    salaryamount    : salaryamount,
                    paymenttype     : paymenttype,
                    projectbasedtype: clickedprojecttype,
                    employeeid      : '{{$employeeid}}'
                },
                success:function(data)
                {
                    if(data == 1)
                    {
                        toastr.success('Updated successfully!', 'Basic Salary Info')
                    }else{
                        toastr.danger('Unable to make changes!', 'Basic Salary Info')
                    }
                }
            })
        }
        
    })
</script>
