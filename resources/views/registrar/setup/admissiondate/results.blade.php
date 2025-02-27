<table class="table table-bordered table-striped" id="table-admission-dates">
    <thead style="text-align: center;">
        <tr>
            <th>School Year</th>
            <th>Grade Level</th>
            <th>Date</th>
            <th>&nbsp;</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @if(count($admissiondates)>0)
            @foreach($admissiondates as $eachadmissiondate)
                <tr>
                    <td style="vertical-align: middle; text-align: center;">{{$eachadmissiondate->sydesc ?? ''}}</td>
                    <td style="vertical-align: middle; text-align: center;">{{$eachadmissiondate->levelname ?? ''}}</td>
                    <td style="vertical-align: middle; text-align: center;"><input type="date" data-id="{{$eachadmissiondate->id}}" class="form-control input-admissiondate-edit" value="{{$eachadmissiondate->admissiondate ?? ''}}" onkeydown="return false"/></td>
                    <td style="vertical-align: middle; text-align: center;" id="date-string-{{$eachadmissiondate->id}}">{{date('F d, Y',strtotime($eachadmissiondate->admissiondate)) ?? ''}}</td>
                    <td style="vertical-align: middle;" class="text-right">
                        <button type="button" class="btn btn-sm btn-outline-danger btn-block btn-admissiondate-delete" data-id="{{$eachadmissiondate->id}}"><i class="fa fa-trash-alt"></i> Delete</button>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table> 
<script>
    
    $('#table-admission-dates').DataTable({
            //paging:         false,
            //"info": false,
            "ordering": false,
            //"aaSorting": []
        }) 

    $('.input-admissiondate-edit').on('change', function(){
        var dataid = $(this).attr('data-id')
        var admissiondate = $(this).val()
        var convertdate = new Date($(this).val())
        var admissiondatestr = convertdate.toLocaleString('en-us', { year: 'numeric', month: 'long', day: 'numeric' });
        Swal.fire({
            title: 'Saving changes...',
            allowOutsideClick: false,
            closeOnClickOutside: false,
            onBeforeOpen: () => {
                Swal.showLoading()
            }
        })  
        $.ajax({
            url: '/setup/admissiondate',
            type: 'GET',
            data: {
                action: 'editadmissiondate',
                id    : dataid,
                admissiondate    : admissiondate,
            },
            success:function(data) {
                $('#date-string-'+dataid).text(admissiondatestr)
                toastr.success('Updated succesfully!', 'Admission date')
                $(".swal2-container").remove();
                $('body').removeClass('swal2-shown')
                $('body').removeClass('swal2-height-auto')
            }
        });
    })
    $('.btn-admissiondate-delete').on('click', function(){
        var dataid = $(this).attr('data-id')
        var thisrow = $(this).closest('tr');
        Swal.fire({
            title: 'Are you sure you want to delete this admission date?',
            type: 'warning',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Delete',
            showCancelButton: true,
            allowOutsideClick: false,
        }).then((confirm) => {
            if (confirm.value) {                    
                $.ajax({
                    url: '/setup/admissiondate',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        action: 'deleteadmissiondate',
                        id    : dataid
                    },
                    success: function(data){
                        if(data == 1)
                        {
                            toastr.success('Deleted successfully!', 'Admission date')
                            thisrow.remove();
                        }else{
                            toastr.error('Something went wrong!', 'Admission date')
                        }
                    }
                })
            }
        })
    })
</script>