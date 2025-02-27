<hr/>
<div class="row mt-2">
    <div class="col-md-4">
        <label>No of hours per day</label>
        <input type="number" step="any" class="form-control" name="hoursperday" id="hoursperday" />
    </div>
    <div class="col-md-4">
        <label>Salary amount</label>
        <input type="number" step="any" class="form-control" name="salaryamount" id="salaryamount" />
    </div>
    <div class="col-md-4 pt-2">
        <label>&nbsp;</label>
        <div class="form-group clearfix">
        <div class="icheck-primary d-inline">
          <input type="checkbox"  name="saturday" id="saturdaywork">
          <label for="saturdaywork">
              Saturday
          </label>
        </div>
        <div class="icheck-primary d-inline">
          <input type="checkbox"  name="sunday"  id="sundaywork">
          <label for="sundaywork"> 
              Sunday
          </label>
        </div>
      </div>
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
    $('#hoursperday').on('keyup', function(){
        if($(this).val().length>0)
        {
            $('#submitbuttoncontainer').show();
        }
    })
    $('#salaryamount').on('keyup', function(){
        if($(this).val()>0)
        {
            $('#submitbuttoncontainer').show();
        }
    })
    $('#submitbutton').on('click', function(){
        
        var basistypeid = $('#selectbasis').val();
        var hoursperday = $('#hoursperday').val();
        var salaryamount = $('#salaryamount').val();
        var paymenttype = $('#paymenttype').val();
        
        if(hoursperday.replace(/^\s+|\s+$/g, "").length == 0){
            $('#hoursperday').css("border","2px solid red")
        }else{
            $('#hoursperday').removeAttr('style')
        }
        if(salaryamount.replace(/^\s+|\s+$/g, "").length == 0){
            $('#salaryamount').css("border","2px solid red")
        }else{
            $('#salaryamount').removeAttr('style')
        }

        if($('#saturdaywork').prop('checked') == true)
        {
            var saturdaywork = 1;
        }else{
            var saturdaywork = 0;
        }
        if($('#sundaywork').prop('checked') == true)
        {
            var sundaywork = 1;
        }else{
            var sundaywork = 0;
        }

        
        if(hoursperday.replace(/^\s+|\s+$/g, "").length > 0 && salaryamount.replace(/^\s+|\s+$/g, "").length > 0){
            $.ajax({
                url: '/hr/employees/profile/tabbasicsalary/updateinfo',
                type: 'GET',
                dataType: 'json',
                data: {
                    basistypeid     : basistypeid,
                    hoursperday     : hoursperday,
                    salaryamount    : salaryamount,
                    saturdaywork    : saturdaywork,
                    sundaywork      : sundaywork,
                    paymenttype     : paymenttype,
                    employeeid      : '{{$employeeid}}'
                },
                success:function(data)
                {
                    if(data == 1)
                    {
                        toastr.success('Updated successfully!', 'Basic Salary Info')
                        $('#custom-content-above-salary-tab').click()
                    }else{
                        toastr.danger('Unable to make changes!', 'Basic Salary Info')
                    }
                }
            })
        }
        
    })
</script>
