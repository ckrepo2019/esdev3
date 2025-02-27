
  <script>
      
    $(document).on('change','select[name=departmentid]', function(){
        $.ajax({
            url: '/hr/getdesignations',
            type:"GET",
            dataType:"json",
            data:{
                departmentid:$(this).val()
            },
            success:function(data) {
                $('select[name=designationid]').empty();
                if(data == 0){

                }else{
                $.each(data, function(key, value){
                    $('select[name=designationid]').append(
                        '<option value="'+value.id+'">'+value.utype+'</option>'
                    )
                });
                }
            }
        });
    })
  </script>
<script>
    // ------------------------------------------------------------------------------------ ACCOUNTS

    var clickedaccountrows = 1;

    $(document).on('click','.addrowaccountsbutton', function(){

        $('.addrowaccounts')
        .prepend(
            '<div class="row">'+
                '<div class="col-md-5">'+
                    '<label>Description <span class="text-danger">*</span></label>'+
                    '<input type="text" name="newaccountdescription[]" class="form-control form-control-sm" required/>'+
                '</div>'+
                '<div class="col-md-5">'+
                    '<label>Account # <span class="text-danger">*</span></label>'+
                    '<input type="text" name="newaccountnumber[]" class="form-control form-control-sm" required/>'+
                '</div>'+
                '<div class="col-md-2 text-left">'+
                    '<label>&nbsp;</label>'+
                    '<br>'+
                    '<button type="button" class="btn btn-danger removeaddaccountrow"><i class="fa fa-times"></i></button>'+
                '</div>'+
                '<hr class="col-md-12"/>'+
            '</div>'
        )

        clickedaccountrows+=1;

    });

    $(document).on('click', '.removeaddaccountrow', function(){
        clickedaccountrows-=1;
        if(clickedaccountrows == 0){
            $('#edit_accounts').modal('hide');
        }
        $(this).closest('.row').remove();
    })

    $('.deleteaccount').click(function() {
        var accountid       = $(this).closest('tr').attr('id');
        var accountdesc     = $(this).attr('accountdescription');
        var accountnum      = $(this).attr('accountnumber');
        
        Swal.fire({
            title: 'Are you sure you want to delete the selected account info?',
            // text: "You won't be able to revert this!",
            html:
                "Account Description: <strong>" + accountdesc + '</strong>'+
                '<br>'+ 
                "Account #: <strong>" + accountnum + '</strong>'+
                '<br>'+
                "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            allowOutsideClick: false
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: '/hr/deleteaccount',
                    type:"GET",
                    dataType:"json",
                    data:{
                        accountid: accountid,
                    },
                    // headers: { 'X-CSRF-TOKEN': token },,
                    complete: function(){
                        Swal.fire({
                            title: 'Deleted!',
                            text: "The selected account has been deleted.",
                            type: 'success',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK!',
                            allowOutsideClick: false
                        }).then((confirm) => {
                            if (confirm.value) {
                                window.location.reload();
                            }
                        })
                    }
                })
            }
        })
    });
    
        // ------------------------------------------------------------------------------------ FAMILY INFORMATION
        
        $('.addrow').on('click', function(){
            $('#familytbody').append(
                '<tr>'+
                    '<td class="p-0"><input class="form-control text-uppercase" type="text" name="familyname[]" required/></td>'+
                    '<td class="p-0"><input class="form-control text-uppercase" type="text" name="familyrelation[]"/></td>'+
                    // '<td class="p-0"><input class="form-control text-uppercase" type="date" name="familydob[]"/></td>'+
                    '<td class="p-0"><input class="form-control text-uppercase familycontactnum" type="text" minlength="13" maxlength="13" data-inputmask-clearmaskonlostfocus="true" name="familynum[]"/></td>'+
                    '<td class="p-0 bg-danger" style="vertical-align: middle;"><button type="button" class="btn btn-sm btn-danger btn-block deleterow"><i class="fa fa-times"></i></button></td>'+
                '</tr>'
            );
            $(".familycontactnum").inputmask({mask: "9999-999-9999"});
        });
        $(document).on('click','.deleterow', function(){
            $(this).closest('tr').remove();
        })
        

        $('.deletefamilymember').click(function() {
            var familymemberid      = $(this).attr('familyid');
            var familymembername    = $(this).attr('familymembername');
            var employeeid          = $(this).attr('id');
            
            Swal.fire({
                title: 'Are you sure you want to delete this family member?',
                // text: "You won't be able to revert this!",
                html:
                    "Family member: <strong>" + familymembername + '</strong>'+
                    '<br>'+
                    "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                allowOutsideClick: false
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: '/hr/deletefamilyinfo',
                        type:"GET",
                        dataType:"json",
                        data:{
                            familymemberid: familymemberid,
                            employeeid: employeeid
                        },
                        // headers: { 'X-CSRF-TOKEN': token },,
                        complete: function(){
                            Swal.fire({
                                title: 'Deleted!',
                                text: "The selected account has been deleted.",
                                type: 'success',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'OK!',
                                allowOutsideClick: false
                            }).then((confirm) => {
                                if (confirm.value) {
                                    window.location.reload();
                                }
                            })
                        }
                    })
                }
            })
        });
        
        // ------------------------------------------------------------------------------------ EDUCATIONAL BACKGROUND
        $(document).on('click','.addeducationcard', function(){
            $(".modal-content").scrollTop(0);
            $('#educationalbackgroundcontainer').prepend(
                '<div class="card p-4">'+
                    '<div class="row">'+
                        '<div class="col-lg-6 mb-2 pb-0">'+
                            '<div class="col-12" style="border:1px solid #ddd;border-radius: 10px;">'+
                                '<label class="mb-0">Institution</label>'+
                                '<input type="text" style="border:none" name="schoolname[]" class="form-control form-control-sm pb-0 pt-0 text-uppercase" required/>'+
                            '</div>'+
                        '</div>'+
                        '<div class="col-lg-6 mb-2 pb-0">'+
                            '<div class="col-12" style="border:1px solid #ddd;border-radius: 10px;">'+
                                '<label class="mb-0">Address</label>'+
                                '<input type="text" style="border:none" name="address[]" class="form-control form-control-sm pb-0 pt-0 text-uppercase"/>'+
                            '</div>'+
                        '</div>'+
                    '</div>'+
                    '<div class="row">'+
                        '<div class="col-lg-6 mb-2 pb-0">'+
                            '<div class="col-12" style="border:1px solid #ddd;border-radius: 10px;">'+
                                '<label class="mb-0">Course Taken</label>'+
                                '<input type="text" style="border:none" name="coursetaken[]" class="form-control form-control-sm pb-0 pt-0 text-uppercase"/>'+
                            '</div>'+
                        '</div>'+
                        '<div class="col-lg-6 mb-2 pb-0">'+
                            '<div class="col-12" style="border:1px solid #ddd;border-radius: 10px;">'+
                                '<label class="mb-0">Major</label>'+
                                '<input type="text" style="border:none" name="major[]" class="form-control form-control-sm pb-0 pt-0 text-uppercase"/>'+
                            '</div>'+
                        '</div>'+
                    '</div>'+
                    '<div class="row">'+
                        '<div class="col-lg-6 mb-2 pb-0">'+
                            '<div class="col-12" style="border:1px solid #ddd;border-radius: 10px;">'+
                                '<label class="mb-0">Date Completed</label>'+
                                '<input type="date" style="border:none" name="datecompleted[]" class="form-control form-control-sm pb-0 pt-0 text-uppercase"/>'+
                            '</div>'+
                        '</div>'+
                        '<div class="col-lg-6 mb-2 pb-0" >'+
                                '<div class="col-12"style="position:absolute;bottom:0;left:0; "><button type="button" class="btn btn-default btn-sm float-right deletecard">Delete &nbsp;<i class="fas fa-trash-alt text-danger"></i></button></div>'+
                        '</div>'+
                    '</div>'+
                '</div>'
            );
        });
        $(document).on('click','.deletecard', function(){
            $(this).closest('div.card').remove();
        }) 
</script>
<script>
    

        // ------------------------------------------------------------------------------------ WORK EXPERIENCE
        $(document).on('click','.addexperiencecard', function(){
            $(".modal-content").scrollTop(0);
            $('#experiencecontainer').prepend(
                '<div class="card p-4">'+
                    '<div class="row">'+
                        '<div class="col-lg-12 mb-2 pb-0">'+
                            '<div class="col-12" style="border:1px solid #ddd;border-radius: 10px;">'+
                                '<label class="mb-0">Company Name</label>'+
                                '<input type="text" style="border:none" name="companyname[]" class="form-control form-control-sm pb-0 pt-0 text-uppercase" required/>'+
                            '</div>'+
                        '</div>'+
                    '</div>'+
                    '<div class="row">'+
                        '<div class="col-lg-6 mb-2 pb-0">'+
                            '<div class="col-12" style="border:1px solid #ddd;border-radius: 10px;">'+
                                '<label class="mb-0">Location</label>'+
                                '<input type="text" style="border:none" name="location[]" class="form-control form-control-sm pb-0 pt-0 text-uppercase"/>'+
                            '</div>'+
                        '</div>'+
                        '<div class="col-lg-6 mb-2 pb-0">'+
                            '<div class="col-12" style="border:1px solid #ddd;border-radius: 10px;">'+
                                '<label class="mb-0">Job Position</label>'+
                                '<input type="text" style="border:none" name="jobposition[]" class="form-control form-control-sm pb-0 pt-0 text-uppercase"/>'+
                            '</div>'+
                        '</div>'+
                    '</div>'+
                    '<div class="row">'+
                        '<div class="col-lg-6 mb-2 pb-0">'+
                            '<div class="col-12" style="border:1px solid #ddd;border-radius: 10px;">'+
                                '<label class="mb-0">Period from</label>'+
                                '<input type="date" style="border:none" name="periodfrom[]" class="form-control form-control-sm pb-0 pt-0 text-uppercase"/>'+
                            '</div>'+
                        '</div>'+
                        '<div class="col-lg-6 mb-2 pb-0">'+
                            '<div class="col-12" style="border:1px solid #ddd;border-radius: 10px;">'+
                                '<label class="mb-0">Period to</label>'+
                                '<input type="date" style="border:none" name="periodto[]" class="form-control form-control-sm pb-0 pt-0 text-uppercase"/>'+
                            '</div>'+
                        '</div>'+
                    '</div>'+
                    '<div class="row">'+
                        '<div class="col-lg-12 mb-2 pb-0" >'+
                            '<div class="col-12"style="position:absolute;top:0;right:0;"><button type="button" class="btn btn-default btn-sm float-right deletecard">Delete &nbsp;<i class="fas fa-trash-alt text-danger"></i></button></div><br>&nbsp;'+
                        '</div>'+
                    '</div>'+
                '</div>'
            );
        });
        
        $('.deleteexperience').click(function() {
            var experienceid        = $(this).attr('experienceid');
            var experiencecompany   = $(this).attr('experiencecompany');
            var experienceposition  = $(this).attr('experienceposition');
            var employeeid          = '{{$profileinfo->id}}';


            Swal.fire({
                title: 'Are you sure you want to delete this work experience?',
                // text: "You won't be able to revert this!",
                html:
                    "Company: <strong>" + experiencecompany + '</strong>'+
                    '<br>'+
                    "Position: <strong>" + experienceposition + '</strong>'+
                    '<br>'+
                    "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                allowOutsideClick: false
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: '/hr/employeeexperience/delete',
                        type:"GET",
                        dataType:"json",
                        data:{
                            experienceid: experienceid,
                            employeeid: employeeid
                        },
                        // headers: { 'X-CSRF-TOKEN': token },,
                        complete: function(){
                            Swal.fire({
                                title: 'Deleted!',
                                text: "The selected experience information has been deleted.",
                                type: 'success',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'OK!',
                                allowOutsideClick: false
                            }).then((confirm) => {
                                if (confirm.value) {
                                    window.location.reload();
                                }
                            })
                        }
                    })
                }
            })
        });
</script>