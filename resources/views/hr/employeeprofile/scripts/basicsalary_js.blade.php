
<script>
    
      
    @if(count($employee_basicsalaryinfo) == 0)
        var salaryamount = 0;
    @else
        var salaryamount = '{{$employee_basicsalaryinfo[0]->amount}}';
    @endif
    parseFloat(salaryamount);
    console.log(salaryamount)
    $('.basicsalarybutton').hide();
    // var salarybasistype             = 0;
    // var salaryamount                = 0;
    // var hoursperweek                = 0;
    // var hoursperday                 = 0;
    // var projectradiosettingtype     = 0;
    // var perdayamount                = 0;
    // var perdayhours                 = 0;
    // var persalaryperiodamount       = 0;
    // var permonthamount              = 0;
    // var permonthhours               = 0;
    $('select[name=salarybasistype]').on('change', function(){
            $('.basicsalarybutton').show();
    });
    $('input[name=hoursperweek]').on('input', function(){
            $('.basicsalarybutton').show();
    });
    $('input[name=hoursperday]').on('input', function(){
            $('.basicsalarybutton').show();
    });
    $('input[name=salaryamount]').on('input', function(){
            $('.basicsalarybutton').show();
    });
    $('input[name=projectradiosettingtype').on('click', function(){
            $('.basicsalarybutton').show();
    });
    $('input[name=perdayamount]').on('input', function(){
            $('.basicsalarybutton').show();
    });
    $('input[name=perdayhours]').on('input', function(){
            $('.basicsalarybutton').show();
    });
    $('input[name=persalaryperiodamount]').on('input', function(){
            $('.basicsalarybutton').show();
    });
    $('input[name=permonthamount]').on('input', function(){
            $('.basicsalarybutton').show();
    });
    $('input[name=permonthhours]').on('input', function(){
            $('.basicsalarybutton').show();
    });
    $('.timepick').on('change', function(){
            $('.basicsalarybutton').show();
    })
    $('#workonsaturdays').on('click', function(){
            $('.basicsalarybutton').show();
    })
    $('#workonsundays').on('click', function(){
            $('.basicsalarybutton').show();
    })
    
    var clickeddays = 0;
    $('input[name="daysrender[]"]').each(function(){
        if($(this).prop('checked') == true){
        clickeddays+=1;
        }
    });
    
    $('.additionalworkondays').hide();
    if($('select[name="salarybasistype"]').val() == '4'){
        $('.additionalworkondays').show();
    }
    $(document).on('change','select[name="salarybasistype"]', function(){
            $('#generalsalaryamouncontainer').empty();
            $('#noofhours').empty();
            $('#othersalarysettingcontainer').empty();
            $('.additionalworkondays').hide();
        if($(this).val() == '7'){
            $('#othersalarysettingcontainer').append(
                '<div class="col-md-4">'+
                '<label class="col-form-label">No. of months</label>'+
                '<input type="number" name="noofmonthscontractual" class="form-control" placeholder="No. of months" required/>'+
                '</div>'
            );
        }
        else if($(this).val() == '4'){
            $('.additionalworkondays').show()
            $('#generalsalaryamouncontainer').append(
                '<div class="form-group">'+
                    '<label class="col-form-label">Salary amount</label>'+
                    '<br>'+
                    '<div class="input-group">'+
                        '<div class="input-group-prepend">'+
                            '<span class="input-group-text">&#8369;</span>'+
                        '</div>'+
                        '<input type="number" class="form-control" name="salaryamount" placeholder="Type your salary amount" value="0.00" required>'+
                    '</div>'+
                '</div>'
            );
            $('#noofhours').append(
                '<label class="col-form-label">No. working hours per day</label>'+
                '<input type="number" name="hoursperday" class="form-control mb-2" value="0"placeholder="No. working hours per day" required/>'
            );                    
        }
        else if($(this).val() == '5'){
            $('#generalsalaryamouncontainer').append(
                '<div class="form-group">'+
                    '<label class="col-form-label">Salary amount</label>'+
                    '<br>'+
                    '<div class="input-group">'+
                        '<div class="input-group-prepend">'+
                            '<span class="input-group-text">&#8369;</span>'+
                        '</div>'+
                        '<input type="text" class="form-control groupOfTexbox" name="salaryamount" placeholder="Type your salary amount" value="0.00" required>'+
                    '</div>'+
                '</div>'
            );
            $('#noofhours').append(
                '<label class="col-form-label">No. working hours per day</label>'+
                '<input type="number" name="hoursperday" class="form-control mb-2" value="0"placeholder="No. working hours per day" required/>'
            );                    
        }
        else if($(this).val() == '6'){
            $('#generalsalaryamouncontainer').append(
                '<div class="form-group">'+
                    '<label class="col-form-label">Salary amount</label>'+
                    '<br>'+
                    '<div class="input-group">'+
                        '<div class="input-group-prepend">'+
                            '<span class="input-group-text">&#8369;</span>'+
                        '</div>'+
                        '<input type="text" class="form-control groupOfTexbox" name="salaryamount" placeholder="Type your salary amount" value="0.00" required>'+
                    '</div>'+
                '</div>'
            );
            $('#othersalarysettingcontainer').prepend(
                
                // '<label class="col-form-label">Days to render</label><br>'+
                '<div class="row">'+
                    '<div class="col-md-4">'+
                        '<label class="col-form-label">No. working hours per week</label>'+
                        '<input type="number" name="hoursperweek" class="form-control" value="0"placeholder="No. working hours per week" readonly required/>'+
                    '</div>'+
                    '<div class="col-md-8">'+
                        '<label>Select Working days by filling in the number of hours</label>'+
                        '<div class="row">'+
                            '<div class="col-md-2" style=" display: flex;align-items: center;">'+
                                    '<div class="icheck-primary d-inline col-md-5">'+
                                        '<input type="checkbox" name="daysrender[]" value="monday" id="daymon" class="hourlycheckbox"  disabled>'+
                                        '<label class="mr-5" for="daymon">'+
                                        'M'+
                                        '</label>'+
                                    '</div>'+
                            '</div>'+
                            '<div class="col-md-8">'+
                                '<input type="number" class="form-control form-control-sm monday hourlydaysrender" name="nodaysrender[]" value="0">'+
                            '</div>'+
                        '</div>'+
                        '<div class="row">'+
                            '<div class="col-md-2" style=" display: flex;align-items: center;">'+
                                    '<div class="icheck-primary d-inline col-md-5">'+
                                        '<input type="checkbox" name="daysrender[]" value="tuesday" id="daytue" class="hourlycheckbox" disabled>'+
                                        '<label class="mr-5" for="daytue">'+
                                        'T'+
                                        '</label>'+
                                    '</div>'+
                            '</div>'+
                            '<div class="col-md-8">'+
                                '<input type="number" class="form-control form-control-sm tuesday hourlydaysrender" name="nodaysrender[]" value="0">'+
                            '</div>'+
                        '</div>'+
                        '<div class="row">'+
                            '<div class="col-md-2" style=" display: flex;align-items: center;">'+
                                    '<div class="icheck-primary d-inline col-md-5">'+
                                        '<input type="checkbox" name="daysrender[]" value="wednesday" id="daywed" class="hourlycheckbox" disabled>'+
                                        '<label class="mr-5" for="daywed">'+
                                        'W'+
                                        '</label>'+
                                    '</div>'+
                            '</div>'+
                            '<div class="col-md-8">'+
                                '<input type="number" class="form-control form-control-sm wednesday hourlydaysrender" name="nodaysrender[]" value="0">'+
                            '</div>'+
                        '</div>'+
                        '<div class="row">'+
                            '<div class="col-md-2" style=" display: flex;align-items: center;">'+
                                    '<div class="icheck-primary d-inline col-md-5">'+
                                        '<input type="checkbox" name="daysrender[]" value="thursday" id="daythu" class="hourlycheckbox" disabled>'+
                                        '<label class="mr-5" for="daythu">'+
                                        'Th'+
                                        '</label>'+
                                    '</div>'+
                            '</div>'+
                            '<div class="col-md-8">'+
                                '<input type="number" class="form-control form-control-sm thursday hourlydaysrender" name="nodaysrender[]" value="0">'+
                            '</div>'+
                        '</div>'+
                        '<div class="row">'+
                            '<div class="col-md-2" style=" display: flex;align-items: center;">'+
                                    '<div class="icheck-primary d-inline col-md-5">'+
                                        '<input type="checkbox" name="daysrender[]" value="friday" id="dayfri" class="hourlycheckbox" disabled>'+
                                        '<label class="mr-5" for="dayfri">'+
                                        'F'+
                                        '</label>'+
                                    '</div>'+
                            '</div>'+
                            '<div class="col-md-8">'+
                                '<input type="number" class="form-control form-control-sm friday hourlydaysrender" name="nodaysrender[]" value="0">'+
                            '</div>'+
                        '</div>'+
                        '<div class="row">'+
                            '<div class="col-md-2" style=" display: flex;align-items: center;">'+
                                    '<div class="icheck-primary d-inline col-md-5">'+
                                        '<input type="checkbox" name="daysrender[]" value="saturday" id="daysat" class="hourlycheckbox" disabled>'+
                                        '<label class="mr-5" for="daysat">'+
                                        'Sat'+
                                        '</label>'+
                                    '</div>'+
                            '</div>'+
                            '<div class="col-md-8">'+
                                '<input type="number" class="form-control form-control-sm saturday hourlydaysrender" name="nodaysrender[]" value="0">'+
                            '</div>'+
                        '</div>'+
                        '<div class="row">'+
                            '<div class="col-md-2" style=" display: flex;align-items: center;">'+
                                    '<div class="icheck-primary d-inline col-md-5">'+
                                        '<input type="checkbox" name="daysrender[]" value="sunday" id="daysun" class="hourlycheckbox" disabled>'+
                                        '<label class="mr-5" for="daysun">'+
                                        'Sun'+
                                        '</label>'+
                                    '</div>'+
                            '</div>'+
                            '<div class="col-md-8">'+
                                '<input type="number" class="form-control form-control-sm sunday hourlydaysrender" name="nodaysrender[]" value="0">'+
                            '</div>'+
                        '</div>'+
                    '</div>'+
                '</div>'
            );
            var workingdays;
            var workinghours;
            $(document).unbind().click('.hourlycheckbox', function(){
                var workingdaysarray= [];
                var workinghoursarray= [];
                $('.hourlycheckbox:checked').each(function(){
                    workingdaysarray.push($(this).val())
                    workinghoursarray.push($(this).closest('.row').find('.hourlydaysrender').val())
                })
                workingdays = workingdaysarray;
                workinghours = workinghoursarray;
            })
            $(document).on('keyup','.hourlydaysrender', function(){
                if($(this).val() > 0)
                {
                    $(this).closest('.row').find('.hourlycheckbox').prop('checked', true)
                }else{
                    $(this).closest('.row').find('.hourlycheckbox').prop('checked', false)
                }
                $('.hourlycheckbox').click();
                console.log(workingdays)
                console.log(workinghours)
                var total = 0;
                for (var i = 0; i < workinghours.length; i++) {
                    total += workinghours[i] << 0;
                }
                $('input[name="hoursperweek"]').val(total)
            })

        }
        else if($(this).val() == '8'){
            $('#othersalarysettingcontainer').append(
                '<div class="row">'+
                    '<div class="col-md-3">'+
                        '<div class="form-group clearfix">'+
                            '<div class="icheck-primary d-inline">'+
                                '<input type="radio" id="projectradiosettingtype1" name="projectradiosettingtype" value="perday" checked>'+
                                '<label for="projectradiosettingtype1">Per day</label>'+
                            '</div>'+
                        '</div>'+
                    '</div>'+
                    '<div class="col-md-3">'+
                        '<input type="number" class="form-control form-control-sm projectamount" name="perdayamount" placeholder="Amount per day" required>'+
                    '</div>'+
                    '<div class="col-md-3">'+
                        '<input type="number" class="form-control form-control-sm projecthours" name="perdayhours" placeholder="No. of hours per day" required>'+
                    '</div>'+
                '</div>'+
                '<div class="row">'+
                    '<div class="col-md-3">'+
                        '<div class="form-group clearfix">'+
                            '<div class="icheck-primary d-inline">'+
                                '<input type="radio" id="projectradiosettingtype2" name="projectradiosettingtype" value="persalaryperiod">'+
                                '<label for="projectradiosettingtype2">Per salary period</label>'+
                            '</div>'+
                        '</div>'+
                    '</div>'+
                    '<div class="col-md-3">'+
                        '<input type="number" class="form-control form-control-sm projectamount" name="persalaryperiodamount" placeholder="Amount per salary period" required disabled>'+
                    '</div>'+
                '</div>'+
                '<div class="row">'+
                    '<div class="col-md-3">'+
                        '<div class="form-group clearfix">'+
                            '<div class="icheck-primary d-inline">'+
                                '<input type="radio" id="projectradiosettingtype3" name="projectradiosettingtype" value="permonth">'+
                                '<label for="projectradiosettingtype3">Per month</label>'+
                            '</div>'+
                        '</div>'+
                    '</div>'+
                    '<div class="col-md-3">'+
                        '<input type="number" class="form-control form-control-sm projectamount" name="permonthamount" placeholder="Amount per month" required disabled>'+
                    '</div>'+
                    '<div class="col-md-3">'+
                        '<input type="number" class="form-control form-control-sm projecthours" name="permonthhours" placeholder="No. of hours per day" required disabled>'+
                    '</div>'+
                '</div>'
            );
            // $('#othersalarysettingcontainer').append(
            //     '<div class="row">'+
            //         '<div class="col-md-4">'+
            //             ''
            //         '</div>'+
            //     '</div>'
            // )
        }
    });
    $(document).ready(function() {
    $('.groupOfTexbox').keypress(function (event) {
        return isNumber(event, this)
    });
});
// THE SCRIPT THAT CHECKS IF THE KEY PRESSED IS A NUMERIC OR DECIMAL VALUE.
function isNumber(evt, element) {

    var charCode = (evt.which) ? evt.which : event.keyCode

    if (
        (charCode != 45 || $(element).val().indexOf('-') != -1) &&      // “-” CHECK MINUS, AND ONLY ONE.
        (charCode != 46 || $(element).val().indexOf('.') != -1) &&      // “.” CHECK DOT, AND ONLY ONE.
        (charCode < 48 || charCode > 57))
        return false;

    return true;
}    
$(document).on('click','input[name=projectradiosettingtype]', function(){
    $('input.projectamount').attr('disabled',true);
    $('input.projecthours').attr('disabled',true);
    $(this).closest('.row').find('input.projectamount').attr('disabled',false);
    $(this).closest('.row').find('input.projecthours').attr('disabled',false);
})

// $(document).on('input','input[name="hoursperweek"]',function(){
//     // console.log($(this).val())
//     var valueeach = ($(this).val() / clickeddays).toFixed(1);
//     $('input.daysrender').val(valueeach)
// })
// $(document).on('click','input[name="daysrender[]"]',function(){
//     if($(this).prop('checked') == true){
//         clickeddays+=1;
//         $(this).closest('.row').find('input[name="nodaysrender[]"').addClass('daysrender');
//         $(this).closest('.row').find('input[name="nodaysrender[]"').attr('disabled',false);
//     }else{
//         clickeddays-=1;
//         $(this).closest('.row').find('.daysrender').val(0);
//         $(this).closest('.row').find('.daysrender').removeClass('daysrender');
//         $(this).closest('.row').find('input[name="nodaysrender[]"').attr('disabled',true);
//     }
//     // console.log(clickeddays)
//     $('input.daysrender').val($('input[name="hoursperweek"]').val()/clickeddays)

// })
// $(document).ready(function(){
    
//         // ------------------------------------------------------------------------------------ CUSTOM TIMESCHED
//         $('#timepickeramin').timepicker({ modal: false, header: false, footer: false, format: 'HH:MM'});

//         $('#timepickeramin').on('change', function(){
//             $.ajax({
//                 url: '/employeecustomtimesched/{{Crypt::encrypt('am_in')}}',
//                 type:"GET",
//                 dataType:"json",
//                 data:{
//                     employeeid:$(this).attr('employeeid'),
//                     am_in:$(this).val()
//                 },
//                 success:function(data) {
//                 }
//             });
//         })

//         $('#timepickeramout').timepicker({ modal: false, header: false, footer: false, mode: 'ampm', format: 'HH:MM'});

//         $('#timepickeramout').on('change', function(){
//             $.ajax({
//                 url: '/employeecustomtimesched/{{Crypt::encrypt('am_out')}}',
//                 type:"GET",
//                 dataType:"json",
//                 data:{
//                     employeeid:$(this).attr('employeeid'),
//                     am_out:$(this).val()
//                 },
//                 success:function(data) {
//                 }
//             });
//         })

//         $('#timepickerpmin').timepicker({ modal: false, header: false, footer: false, mode: 'ampm', format: 'HH:MM'});

//         $('#timepickerpmin').on('change', function(){
//             $.ajax({
//                 url: '/employeecustomtimesched/{{Crypt::encrypt('pm_in')}}',
//                 type:"GET",
//                 dataType:"json",
//                 data:{
//                     employeeid:$(this).attr('employeeid'),
//                     pm_in:$(this).val()
//                 },
//                 success:function(data) {
//                 }
//             });
//         })

//         $('#timepickerpmout').timepicker({ modal: false, header: false, footer: false, mode: 'ampm', format: 'HH:MM'});

//         $('#timepickerpmout').on('change', function(){
//             $.ajax({
//                 url: '/employeecustomtimesched/{{Crypt::encrypt('pm_out')}}',
//                 type:"GET",
//                 dataType:"json",
//                 data:{
//                     employeeid:$(this).attr('employeeid'),
//                     pm_out:$(this).val()
//                 },
//                 success:function(data) {
//                 }
//             });
//         })
//         $('input[name="workshift"]').on('click', function(){
//             if($(this).prop('checked') == true)
//             {
//                 if($(this).val() == 0)
//                 {
//                     $('#timepickeramin').attr('disabled',false)
//                     $('#timepickeramout').attr('disabled',false)
//                     $('#timepickerpmin').attr('disabled',false)
//                     $('#timepickerpmout').attr('disabled',false)
//                 }
//                 else if($(this).val() == 1)
//                 {
//                     $('#timepickeramin').attr('disabled',false)
//                     $('#timepickeramout').attr('disabled',false)
//                     $('#timepickerpmin').attr('disabled',true)
//                     $('#timepickerpmout').attr('disabled',true)
//                 }
//                 else if($(this).val() == 2)
//                 {
//                     $('#timepickeramin').attr('disabled',true)
//                     $('#timepickeramout').attr('disabled',true)
//                     $('#timepickerpmin').attr('disabled',false)
//                     $('#timepickerpmout').attr('disabled',false)
//                 }
//                 $.ajax({
//                     url: '/hr/employeeworkshift',
//                     type:"GET",
//                     dataType:"json",
//                     data:{
//                         employeeid:'{{$profileinfoid}}',
//                         shiftid:$(this).val()
//                     },
//                     complete:function() {
//                         toastr.success('Updated successfully!','WORK SHIFT',)
//                     }
//                 });
//             }
//         })
// })

</script>