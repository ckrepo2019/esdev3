<script>
    
    $(document).on('click','.editrowfields[type=button]', function(event){
                    event.preventDefault();
                    $('.editrowfields').removeClass('btn-submit-edit');
                    $('.editrowfields').removeClass('btn-primary');
                    $('.editrowfields').addClass('btn-warning');
                    $('.editrowfields').prop('type','button');
                    $('.editrowfields').find('i').removeClass('fa-upload');
                    $('.editrowfields').find('i').addClass('fa-edit');
                    $('.existing').prop('disabled', true);
                    $(this).removeClass('btn-warning');
                    $(this).addClass('btn-primary');
                    $(this).find('i').removeClass('fa-edit');
                    $(this).find('i').addClass('fa-upload');
                    $(this).closest('tr').find('input').attr('disabled',false);
                    $(this).addClass('btn-submit-edit');
                    $(this).removeClass('editrowfields');
                    $(this).attr('id','btn-submit-edit-'+$(this).attr('data-key'));
                })
</script>

                
</div>
        @if($type == 1) {{-- pag-ibig --}}
            <div class="modal-body" id="modal-body">
                <div class="row">
                    <div class="col-md-12">
                    <table class=" display" id="table-pag-ibig">
                        <thead class="text-center">
                            <tr>
                                <th style="width: 40%;">Monthly Salary</th>
                                <th>Employee's Contribution Rate <br> (%)</th>
                                <th>Employer's Contribution Rate <br> (%)</th>
                                <th style="width: 15%;"><i class="fa fa-cogs"></i></th>
                            </tr>
                        </thead>
                        <tbody id="container-new">
                            @foreach(collect($brackets)->sortBy('id')->values() as $bracketkey=> $bracket)
                                    <tr>
                                        <td>
                                            <div class="row">
                                                <input type="hidden" name="id" step="any" class="form-control" value="{{$bracket->id}}"/>
                                                <input type="hidden" name="type" step="any" class="form-control" value="pag-ibig"/>
                                                <div class="col-5">
                                                    <input type="number" name="rangefrom" step="any" class="form-control form-control-sm existing" value="{{$bracket->rangefrom}}" step=".01" min="0"  onkeydown="return event.keyCode !== 69"/>
                                                </div>
                                                <div class="col-2 text-center">-</div>
                                                <div class="col-5">
                                                    <input type="number" name="rangeto" step="any" class="form-control form-control-sm existing" value="{{$bracket->rangeto}}" step=".01" min="0"  onkeydown="return event.keyCode !== 69"/>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="row">
                                                <input type="number" name="eescrate" step="any" class="form-control form-control-sm existing" value="{{$bracket->eescrate}}" step=".01" min="0"  onkeydown="return event.keyCode !== 69"/>
                                            </div>
                                        </td>
                                        <td> 
                                            <div class="row">
                                                <input type="number" name="erscrate" step="any" class="form-control form-control-sm existing" value="{{$bracket->erscrate}}" step=".01" min="0"  onkeydown="return event.keyCode !== 69"/>
                                            </div>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-warning edit-bracket-item"  data-id="{{$bracket->id}}">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-default btn-sm delete-bracket-item" data-id="{{$bracket->id}}" data-type="pag-ibig">
                                                <i class="fa fa-trash-alt"></i>
                                            </button>
                                        </td>
                                    </tr>
                            @endforeach
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btn-add-bracket-item">Add row</button>
            </div>
            <script>
                $(document).ready(function(){
                    function submitedit(_itemid, _bracketfrom, _bracketto, _bracketcontemployee, _bracketcontemployer)
                    {
                        $.ajax({
                            url: '/hr/setup/deductions/bracketing',
                            type: 'get',
                            dataType: 'json',
                            data: {
                                action  :  'bracketitem_edit',
                                type: 'pag-ibig',
                                itemid: _itemid,
                                bracketfrom: _bracketfrom,
                                bracketto: _bracketto,
                                bracketcontemployee: _bracketcontemployee,
                                bracketcontemployer: _bracketcontemployer
                            },
                            success: function(data){
                                toastr.success('Updated successfully!','Edit Bracket Item')
                            }
                        })
                    }
                    var t = $('#table-pag-ibig').DataTable({
                        "bFilter": false,
                        scrollY:        500,
                        scrollX:        false,
                        scrollCollapse: true,
                        paging:         false,
                        "info": false,
                        "ordering": false,
                        "aaSorting": []
                    });
                    var counter = '{{collect($brackets)->sortBy('id')->count()}}';
                    $('#btn-add-bracket-item').on('click', function(){
                        
                        t.row.add(['<div class="row">'+
                                    '<div class="col-md-5"><input type="number" class="form-control form-control-sm new-bracket-from" placeholder="0.00" name="rangefrom" step=".01" min="0"  onkeydown="return event.keyCode !== 69"/></div>'+
                                    '<div class="col-md-2 text-center">-</div>'+
                                    '<div class="col-md-5"><input type="number" class="form-control form-control-sm new-bracket-to" placeholder="0.00" name="rangeto" step=".01" min="0"  onkeydown="return event.keyCode !== 69"/></div>'+
                                '</div>','<input type="number" class="form-control form-control-sm new-bracket-contemployee" placeholder="0.00" name="eescrate" step=".01" min="0"  onkeydown="return event.keyCode !== 69"/>','<input type="number" class="form-control form-control-sm new-bracket-contemployer" placeholder="0.00" name="erscrate" step=".01" min="0"  onkeydown="return event.keyCode !== 69"/>','<button type="button" class="btn btn-sm btn-success btn-submit-new-'+counter+'"><i class="fa fa-share"></i></button>']).draw(false);
                
                        $('.btn-submit-new-'+counter).on('click',  function(){
                            var thisbutton = $(this);
                            var bracketfrom         = $(this).closest('tr').find('.new-bracket-from').val();
                            var bracketto           = $(this).closest('tr').find('.new-bracket-to').val();
                            var bracketcontemployee = $(this).closest('tr').find('.new-bracket-contemployee').val();
                            var bracketcontemployer = $(this).closest('tr').find('.new-bracket-contemployer').val();
                
                            var validation          = 0;
                            if(bracketfrom.replace(/^\s+|\s+$/g, "").length == 0)
                            { 
                                validation=1;
                                $(this).closest('tr').find('.new-bracket-from').css('border','2px solid red')
                            }else{
                                $(this).closest('tr').find('.new-bracket-from').removeAttr('style');
                            }
                            if(bracketto.replace(/^\s+|\s+$/g, "").length == 0)
                            { 
                                validation=1;
                                $(this).closest('tr').find('.new-bracket-to').css('border','2px solid red')
                            }else{
                                $(this).closest('tr').find('.new-bracket-to').removeAttr('style');
                            }
                            if(bracketcontemployee.replace(/^\s+|\s+$/g, "").length == 0)
                            { 
                                validation=1;
                                $(this).closest('tr').find('.new-bracket-contemployee').css('border','2px solid red')
                            }else{
                                $(this).closest('tr').find('.new-bracket-contemployee').removeAttr('style');
                            }
                            if(bracketcontemployer.replace(/^\s+|\s+$/g, "").length == 0)
                            { 
                                validation=1;
                                $(this).closest('tr').find('.new-bracket-contemployer').css('border','2px solid red')
                            }else{
                                $(this).closest('tr').find('.new-bracket-contemployer').removeAttr('style');
                            }
                            if(validation == 0)
                            {                
                                $.ajax({
                                    url: '/hr/setup/deductions/bracketing',
                                    type: 'get',
                                    dataType: 'json',
                                    data: {
                                        action  :  'bracketitem_add',
                                        type: 'pag-ibig',
                                        bracketfrom: bracketfrom,
                                        bracketto: bracketto,
                                        bracketcontemployee: bracketcontemployee,
                                        bracketcontemployer: bracketcontemployer
                                    },
                                    success: function(data){
                                        thisbutton.closest('tr').find('input').addClass('existing')
                                        // console.log(thisbutton.closest('tr'))
                                        // console.log(thisbutton.closest('tr').find('input'))
                                        toastr.success('Added successfully!','Bracket')
                                        var thistd = thisbutton.closest('td');
                                        thistd.empty();
                                        thistd.append(`<button type="button" class="btn btn-sm btn-warning edit-bracket-item-`+counter+`" data-id="`+data+`">
                                        <i class="fa fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-default btn-sm delete-bracket-item-`+counter+`" data-id="`+data+`">
                                            <i class="fa fa-trash-alt"></i>
                                        </button>`);
                                        $('.edit-bracket-item-'+counter).on('click', function(){
                                            var thisbutton          = $(this);
                                            var bracketfrom         = $(this).closest('tr').find('input[name="rangefrom"]').val();
                                            var bracketto           = $(this).closest('tr').find('input[name="rangeto"]').val();
                                            var bracketcontemployee = $(this).closest('tr').find('input[name="eescrate"]').val();
                                            var bracketcontemployer = $(this).closest('tr').find('input[name="erscrate"]').val();
                                            var itemid              = $(this).attr('data-id');
                                            var validation          = 0;
                                            if(bracketfrom.replace(/^\s+|\s+$/g, "").length == 0)
                                            { 
                                                validation=1;
                                                $(this).closest('tr').find('input[name="rangefrom"]').css('border','2px solid red')
                                            }else{
                                                $(this).closest('tr').find('input[name="rangefrom"]').removeAttr('style');
                                            }
                                            if(bracketto.replace(/^\s+|\s+$/g, "").length == 0)
                                            { 
                                                validation=1;
                                                $(this).closest('tr').find('input[name="rangeto"]').css('border','2px solid red')
                                            }else{
                                                $(this).closest('tr').find('input[name="rangeto"]').removeAttr('style');
                                            }
                                            if(bracketcontemployee.replace(/^\s+|\s+$/g, "").length == 0)
                                            { 
                                                validation=1;
                                                $(this).closest('tr').find('input[name="eescrate"]').css('border','2px solid red')
                                            }else{
                                                $(this).closest('tr').find('input[name="eescrate"]').removeAttr('style');
                                            }
                                            if(bracketcontemployer.replace(/^\s+|\s+$/g, "").length == 0)
                                            { 
                                                validation=1;
                                                $(this).closest('tr').find('input[name="erscrate"]').css('border','2px solid red')
                                            }else{
                                                $(this).closest('tr').find('input[name="erscrate"]').removeAttr('style');
                                            }
                                            if(validation == 0)
                                            {                
                                                submitedit(itemid, bracketfrom, bracketto, bracketcontemployee, bracketcontemployer)
                                            }else{
                                                
                                                toastr.warning('Please fill in important fields!','Edit Bracket Item')
                                            }
                                        })
                                        $('.delete-bracket-item-'+counter).on('click', function(){
                                            var thisbutton = $(this);
                                            var bracketid = $(this).attr('data-id');
                                            
                                            Swal.fire({
                                                title: 'Are you sure you want to delete this bracket?',
                                                type: 'warning',
                                                confirmButtonColor: '#3085d6',
                                                confirmButtonText: 'Delete',
                                                showCancelButton: true,
                                                allowOutsideClick: false,
                                            }).then((confirm) => {
                                                    if (confirm.value) {
                                                        
                                                        $.ajax({
                                                            url: '/hr/setup/deductions/bracketing',
                                                            type: 'get',
                                                            dataType: 'json',
                                                            data: {
                                                            action  :  'bracketitem_delete',
                                                                type: 'pag-ibig',
                                                                id: bracketid
                                                            },
                                                            complete: function(data){
                                                                thisbutton.closest('tr').remove()
                                                                toastr.success('Deleted successfully!','Bracket')
                                                            }
                                                        })
                                                    }
                                                })
                                        })
                                    }
                                })
                            }else{
                                
                                toastr.warning('Please fill in important fields!','New Bracket Item')
                            }
                        })
                        counter+=1;
                    })
                    $('.edit-bracket-item').on('click', function(){
                        var thisbutton          = $(this);
                        var bracketfrom         = $(this).closest('tr').find('input[name="rangefrom"]').val();
                        var bracketto           = $(this).closest('tr').find('input[name="rangeto"]').val();
                        var bracketcontemployee = $(this).closest('tr').find('input[name="eescrate"]').val();
                        var bracketcontemployer = $(this).closest('tr').find('input[name="erscrate"]').val();
                        var itemid              = $(this).attr('data-id');
                        var validation          = 0;
                        if(bracketfrom.replace(/^\s+|\s+$/g, "").length == 0)
                        { 
                            validation=1;
                            $(this).closest('tr').find('input[name="rangefrom"]').css('border','2px solid red')
                        }else{
                            $(this).closest('tr').find('input[name="rangefrom"]').removeAttr('style');
                        }
                        if(bracketto.replace(/^\s+|\s+$/g, "").length == 0)
                        { 
                            validation=1;
                            $(this).closest('tr').find('input[name="rangeto"]').css('border','2px solid red')
                        }else{
                            $(this).closest('tr').find('input[name="rangeto"]').removeAttr('style');
                        }
                        if(bracketcontemployee.replace(/^\s+|\s+$/g, "").length == 0)
                        { 
                            validation=1;
                            $(this).closest('tr').find('input[name="eescrate"]').css('border','2px solid red')
                        }else{
                            $(this).closest('tr').find('input[name="eescrate"]').removeAttr('style');
                        }
                        if(bracketcontemployer.replace(/^\s+|\s+$/g, "").length == 0)
                        { 
                            validation=1;
                            $(this).closest('tr').find('input[name="erscrate"]').css('border','2px solid red')
                        }else{
                            $(this).closest('tr').find('input[name="erscrate"]').removeAttr('style');
                        }
                        if(validation == 0)
                        {                
                            submitedit(itemid, bracketfrom, bracketto, bracketcontemployee, bracketcontemployer)
                        }else{
                            
                            toastr.warning('Please fill in important fields!','Edit Bracket Item')
                        }
                    })
                })
            </script>
        @elseif($type == 2) {{--philhealth--}}
            <div class="modal-body" id="modal-body">
                <div class="row">
                    <div class="col-md-4 col-sm-12">
                        
                            <div class="form-group">
                                <label>Select Year</label>
                                <div class="input-group">
                                    <select class="form-control select2" id="select-year">
                                        <option value="0">Add Year</option>
                                        @if(count($brackets) > 0)
                                            @foreach($brackets as $key=>$eachyear)
                                                <option value="{{$eachyear->id}}" {{--{{($key+1) == DB::table('hr_bracketph')->where('deleted','0')->count() ? 'selected' : ''}}--}}@if(collect($brackets)->where('isactive','1')->count()>0) {{$eachyear->isactive == 1 ? 'selected' : ''}} @else {{$eachyear->year == date('Y') ? 'selected' : ''}} @endif>{{$eachyear->year}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <div class="input-group-append p-0">
                                        <span class="input-group-text m-0 p-0"><button type="button" class="btn btn-success" id="btn-year-status" data-status="1">Active</button></span>
                                    </div>
                                </div>

                            </div>
        
                    </div>
                </div>
                <table class="display" id="table-philhealth">
                    <thead class="bg-info text-center">
                        <tr>
                            <th style="width: 50%">Monthly Salary</th>
                            <th>Premium rate(%)</th>
                            <th>Fixed Amount</th>
                            <th style="width: 15%;"><i class="fa fa-cogs"></i></th>
                        </tr>
                    </thead>
                    <tbody id="tbody-philhealth">
                    </tbody>
                </table>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btn-add-bracket-item">Add row</button>
            </div>
            <script>
                $(document).ready(function(){
                    $('.select2').select2({
                    theme: 'bootstrap4'
                    })
                    var t = $('#table-philhealth').DataTable({
                        "bFilter": false,
                        scrollY:        500,
                        scrollX:        false,
                        scrollCollapse: true,
                        paging:         false,
                        "info": false,
                        "ordering": false,
                        "aaSorting": []
                    });
                    function submitedit(_itemid, _bracketfrom, _bracketto, _bracketrate, _bracketfixedamount)
                    {
                        $.ajax({
                            url: '/hr/setup/deductions/bracketing',
                            type: 'get',
                            dataType: 'json',
                            data: {
                                action  :  'bracketitem_edit',
                                type: 'philhealth',
                                itemid: _itemid,
                                bracketfrom: _bracketfrom,
                                bracketto: _bracketto,
                                bracketrate: _bracketrate,
                                bracketfixedamount: _bracketfixedamount
                            },
                            success: function(data){
                                toastr.success('Updated successfully!','Edit Bracket Item')
                            }
                        })
                    }
                    var counter = 0;
                    $('#select-year').on('change', function(){
                        t.clear();
                        var selectval = $(this).val();
                        if(selectval == 0)
                        {
                            $('#modal-add-year').modal('show')
                        }else{    
                            $.ajax({
                                url: '/hr/setup/deductions/bracketing',
                                type: 'get',
                                dataType: 'json',
                                data: {
                                    action  :  'getbracketitems',
                                    yearid: selectval
                                },
                                success: function(data){
                                    t.clear();
                                    $('#tbody-philhealth').empty()
                                    counter = 0;
                                    if(data.length > 0)
                                    {
                                        $.each(data, function(key, val){
                                            t.row.add([`<div class="row">
                                                            <input type="hidden" name="id" step="any" class="form-control form-control-sm" value="`+val.id+`"/>
                                                            <input type="hidden" name="type" step="any" class="form-control form-control-sm" value="philhealth"/>
                                                            <div class="col-5">
                                                                <input type="number" name="rangefrom" step="any" class="form-control form-control-sm" value="`+val.rangefrom+`" placeholder="0.00" step=".01" min="0"  onkeydown="return event.keyCode !== 69"/>
                                                            </div>
                                                            <div class="col-2 text-center">-</div>
                                                            <div class="col-5">
                                                                <input type="number" name="rangeto" step="any" class="form-control form-control-sm" value="`+val.rangeto+`" placeholder="0.00" step=".01" min="0"  onkeydown="return event.keyCode !== 69"/>
                                                            </div>
                                                        </div>`,
                                                        `<div class="row">
                                                            <input type="number" name="premiumrate" step="any" class="form-control form-control-sm" value="`+val.premiumrate+`" placeholder="0.00" step=".01" min="0"  onkeydown="return event.keyCode !== 69"/>
                                                        </div>`,
                                                        `<div class="row">
                                                            <input type="number" name="fixedamount" step="any" class="form-control form-control-sm" value="`+val.fixedamount+`" placeholder="0.00" step=".01" min="0"  onkeydown="return event.keyCode !== 69"/>
                                                        </div>`,
                                                        `<button type="button" class="btn btn-sm btn-warning edit-bracket-item" data-id="`+val.id+`" id="edit-bracket-item-`+counter+`">
                                                            <i class="fa fa-edit"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-default btn-sm delete-bracket-item" data-id="`+val.id+`" data-type="philhealth">
                                                            <i class="fa fa-trash-alt"></i>
                                                        </button>`
                                            ]).draw(false);
                                            $('#edit-bracket-item-'+counter).on('click', function(){
                                                var thisbutton          = $(this);
                                                var bracketfrom         = $(this).closest('tr').find('input[name="rangefrom"]').val();
                                                var bracketto           = $(this).closest('tr').find('input[name="rangeto"]').val();
                                                var bracketrate = $(this).closest('tr').find('input[name="premiumrate"]').val();
                                                var bracketfixedamount = $(this).closest('tr').find('input[name="fixedamount"]').val();
                                                var itemid              = $(this).attr('data-id');
                                                var validation          = 0;
                                                if(bracketfrom.replace(/^\s+|\s+$/g, "").length == 0)
                                                { 
                                                    validation=1;
                                                    $(this).closest('tr').find('input[name="rangefrom"]').css('border','2px solid red')
                                                }else{
                                                    $(this).closest('tr').find('input[name="rangefrom"]').removeAttr('style');
                                                }
                                                if(bracketto.replace(/^\s+|\s+$/g, "").length == 0)
                                                { 
                                                    validation=1;
                                                    $(this).closest('tr').find('input[name="rangeto"]').css('border','2px solid red')
                                                }else{
                                                    $(this).closest('tr').find('input[name="rangeto"]').removeAttr('style');
                                                }
                                                if(bracketrate.replace(/^\s+|\s+$/g, "").length == 0)
                                                { 
                                                    validation=1;
                                                    $(this).closest('tr').find('input[name="premiumrate"]').css('border','2px solid red')
                                                }else{
                                                    $(this).closest('tr').find('input[name="premiumrate"]').removeAttr('style');
                                                }
                                                if(bracketfixedamount.replace(/^\s+|\s+$/g, "").length == 0)
                                                { 
                                                    validation=1;
                                                    $(this).closest('tr').find('input[name="fixedamount"]').css('border','2px solid red')
                                                }else{
                                                    $(this).closest('tr').find('input[name="fixedamount"]').removeAttr('style');
                                                }
                                                if(validation == 0)
                                                {                
                                                    submitedit(itemid, bracketfrom, bracketto, bracketrate, bracketfixedamount)
                                                }else{
                                                    
                                                    toastr.warning('Please fill in important fields!','Edit Bracket Item')
                                                }
                                            })
                                            counter+=1;
                                        })
                                    }else{
                                        $('#tbody-philhealth').append(`<tr><td colspan="4" class="text-center">No data available</td></tr>`)
                                    }
                                }
                            })
                            $.ajax({
                                url: '/hr/setup/deductions/bracketing',
                                type: 'get',
                                dataType: 'json',
                                data: {
                                    action  :  'getyearstatus',
                                    yearid: selectval
                                },
                                success: function(data){
                                    if(data == '0')
                                    {
                                        $('#btn-year-status').text('Set as Active')
                                        $('#btn-year-status').removeClass('btn-success')
                                        $('#btn-year-status').addClass('btn-secondary')
                                        $('#btn-year-status').attr('data-status','0')
                                    }else{
                                        $('#btn-year-status').text('Active')
                                        $('#btn-year-status').removeClass('btn-secondary')
                                        $('#btn-year-status').addClass('btn-success')
                                        $('#btn-year-status').attr('data-status','1')
                                    }
                                }
                            })
                        }
                    })
                    $('#select-year').trigger('change')
                    $('#btn-add-year').on('click', function(){
                        var addyear = $('#input-add-year').val()
                        if(addyear.replace(/^\s+|\s+$/g, "").length == 0)
                        {
                            $('#input-add-year').css('border','2px solid red')
                        }else{
                            $.ajax({
                                url: '/hr/setup/deductions/bracketing',
                                type: 'get',
                                dataType: 'json',
                                data: {
                                    action  :  'year_add',
                                    type: 'philhealth',
                                    addyear: addyear
                                },
                                success: function(data){
                                    if(data == 0)
                                    {
                                        toastr.warning('Year already exists!','Add Bracket Year')
                                    }else{
                                        toastr.success('Updated successfully!','Edit Bracket Item')
                                        $('#select-year').append('<option value="'+data+'" selected>'+addyear+'</option>')
                                        $('#select-year').trigger('change')
                                        $('#modal-add-year').modal('toggle');
                                    }
                                }
                            })
                        }
                    })
                    $('#btn-year-status').on('click', function(){
                        var selectval = $('#select-year').val();
                        var currentstatus = $(this).attr('data-status');
                        var thisbutton = $(this);
                        if(currentstatus == 0)
                        {
                            $.ajax({
                                url: '/hr/setup/deductions/bracketing',
                                type: 'get',
                                dataType: 'json',
                                data: {
                                    action  :  'year_updatestatus',
                                    type: 'philhealth',
                                    yearid: selectval,
                                    currentstatus: currentstatus
                                },
                                success: function(data){
                                    $('#btn-year-status').text('Active')
                                    $('#btn-year-status').removeClass('btn-secondary')
                                    $('#btn-year-status').addClass('btn-success')
                                    $('#btn-year-status').attr('data-status','1')
                                    toastr.success('Activated successfully!','Bracket Year Activation')
                                }
                            })
                        }
                    })

                    $('#btn-add-bracket-item').on('click', function(){
                        
                        t.row.add(['<div class="row">'+
                                    '<div class="col-md-5"><input type="number" class="form-control form-control-sm new-bracket-from" placeholder="0.00" name="rangefrom" step=".01" min="0"  onkeydown="return event.keyCode !== 69"/></div>'+
                                    '<div class="col-md-2 text-center">-</div>'+
                                    '<div class="col-md-5"><input type="number" class="form-control form-control-sm new-bracket-to" placeholder="0.00" name="rangeto" step=".01" min="0"  onkeydown="return event.keyCode !== 69"/></div>'+
                                '</div>','<input type="number" class="form-control form-control-sm new-bracket-rate" placeholder="0.00" name="premiumrate" step=".01" min="0"  onkeydown="return event.keyCode !== 69"/>','<input type="number" class="form-control form-control-sm new-bracket-fixedamount" placeholder="0.00" name="fixedamount" step=".01" min="0"  onkeydown="return event.keyCode !== 69"/>','<button type="button" class="btn btn-sm btn-success btn-submit-new-'+counter+'"><i class="fa fa-share"></i></button>']).draw(false);
                
                        $('.btn-submit-new-'+counter).on('click',  function(){
                            var thisbutton = $(this);
                            var bracketfrom         = $(this).closest('tr').find('input[name="rangefrom"]').val();
                            var bracketto           = $(this).closest('tr').find('input[name="rangeto"]').val();
                            var selectyear       = $('#select-year').val();
                            var bracketrate       = $(this).closest('tr').find('input[name="premiumrate"]').val();
                            var bracketfixedamount = $(this).closest('tr').find('input[name="fixedamount"]').val();
                
                            var validation          = 0;
                            if(bracketfrom.replace(/^\s+|\s+$/g, "").length == 0)
                            { 
                                validation=1;
                                $(this).closest('tr').find('input[name="rangefrom"]').css('border','2px solid red')
                            }else{
                                $(this).closest('tr').find('input[name="rangefrom"]').removeAttr('style');
                            }
                            if(bracketto.replace(/^\s+|\s+$/g, "").length == 0)
                            { 
                                validation=1;
                                $(this).closest('tr').find('input[name="rangeto"]').css('border','2px solid red')
                            }else{
                                $(this).closest('tr').find('input[name="rangeto"]').removeAttr('style');
                            }
                            if(bracketrate.replace(/^\s+|\s+$/g, "").length == 0)
                            { 
                                validation=1;
                                $(this).closest('tr').find('input[name="premiumrate"]').css('border','2px solid red')
                            }else{
                                $(this).closest('tr').find('input[name="premiumrate"]').removeAttr('style');
                            }
                            if(bracketfixedamount.replace(/^\s+|\s+$/g, "").length == 0)
                            { 
                                validation=1;
                                $(this).closest('tr').find('input[name="fixedamount"]').css('border','2px solid red')
                            }else{
                                $(this).closest('tr').find('input[name="fixedamount"]').removeAttr('style');
                            }
                            if(validation == 0)
                            {                
                                $.ajax({
                                    url: '/hr/setup/deductions/bracketing',
                                    type: 'get',
                                    dataType: 'json',
                                    data: {
                                        action  :  'bracketitem_add',
                                        type: 'philhealth',
                                        yearid: selectyear,
                                        bracketfrom: bracketfrom,
                                        bracketto: bracketto,
                                        bracketrate: bracketrate,
                                        bracketfixedamount: bracketfixedamount
                                    },
                                    success: function(data){
                                        // thisbutton.closest('tr').find('input').prop('disabled',true)
                                        thisbutton.closest('tr').find('input').addClass('existing')
                                        // console.log(thisbutton.closest('tr'))
                                        // console.log(thisbutton.closest('tr').find('input'))
                                        toastr.success('Added successfully!','Bracket')
                                        var thistd = thisbutton.closest('td');
                                        thistd.empty();
                                        thistd.append(`<button type="button" class="btn btn-sm btn-warning edit-bracket-item-`+counter+`" data-id="`+data+`">
                                        <i class="fa fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-default btn-sm delete-bracket-item-`+counter+`" data-id="`+data+`">
                                            <i class="fa fa-trash-alt"></i>
                                        </button>`);
                                        $('.edit-bracket-item-'+counter).on('click', function(){
                                            var thisbutton          = $(this);
                                            var bracketfrom         = $(this).closest('tr').find('input[name="rangefrom"]').val();
                                            var bracketto           = $(this).closest('tr').find('input[name="rangeto"]').val();
                                            var bracketrate         = $(this).closest('tr').find('input[name="premiumrate"]').val();
                                            var bracketfixedamount  = $(this).closest('tr').find('input[name="fixedamount"]').val();
                                            var itemid              = $(this).attr('data-id');
                                            var validation          = 0;
                                            if(bracketfrom.replace(/^\s+|\s+$/g, "").length == 0)
                                            { 
                                                validation=1;
                                                $(this).closest('tr').find('input[name="rangefrom"]').css('border','2px solid red')
                                            }else{
                                                $(this).closest('tr').find('input[name="rangefrom"]').removeAttr('style');
                                            }
                                            if(bracketto.replace(/^\s+|\s+$/g, "").length == 0)
                                            { 
                                                validation=1;
                                                $(this).closest('tr').find('input[name="rangeto"]').css('border','2px solid red')
                                            }else{
                                                $(this).closest('tr').find('input[name="rangeto"]').removeAttr('style');
                                            }
                                            if(bracketrate.replace(/^\s+|\s+$/g, "").length == 0)
                                            { 
                                                validation=1;
                                                $(this).closest('tr').find('input[name="premiumrate"]').css('border','2px solid red')
                                            }else{
                                                $(this).closest('tr').find('input[name="premiumrate"]').removeAttr('style');
                                            }
                                            if(bracketfixedamount.replace(/^\s+|\s+$/g, "").length == 0)
                                            { 
                                                validation=1;
                                                $(this).closest('tr').find('input[name="fixedamount"]').css('border','2px solid red')
                                            }else{
                                                $(this).closest('tr').find('input[name="fixedamount"]').removeAttr('style');
                                            }
                                            if(validation == 0)
                                            {                
                                                submitedit(itemid, bracketfrom, bracketto, bracketrate, bracketfixedamount)
                                            }else{
                                                
                                                toastr.warning('Please fill in important fields!','Edit Bracket Item')
                                            }
                                        })
                                        $('.delete-bracket-item-'+counter).on('click', function(){
                                            var thisbutton = $(this);
                                            var bracketid = $(this).attr('data-id');
                                            
                                            Swal.fire({
                                                title: 'Are you sure you want to delete this bracket?',
                                                type: 'warning',
                                                confirmButtonColor: '#3085d6',
                                                confirmButtonText: 'Delete',
                                                showCancelButton: true,
                                                allowOutsideClick: false,
                                            }).then((confirm) => {
                                                    if (confirm.value) {
                                                        
                                                        $.ajax({
                                                            url: '/hr/setup/deductions/bracketing',
                                                            type: 'get',
                                                            dataType: 'json',
                                                            data: {
                                                            action  :  'bracketitem_delete',
                                                                type: 'philhealth',
                                                                id: bracketid
                                                            },
                                                            complete: function(data){
                                                                thisbutton.closest('tr').remove()
                                                                toastr.success('Deleted successfully!','Bracket')
                                                            }
                                                        })
                                                    }
                                                })
                                        })
                                    }
                                })
                            }else{
                                
                                toastr.warning('Please fill in important fields!','New Bracket Item')
                            }
                        })
                        counter+=1;
                    })
                })
            </script>
        @elseif($type == 3)
            <div class="modal-body" id="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="display" id="table-sss" style="font-size: 13px;">
                            <thead class="bg-info text-center">
                                <tr>
                                    <th style="width: 40%;">Monthly Salary</th>
                                    <th>Monthly Salary Credit</th>
                                    <th>Employee's Contribution <br> (&#8369;)</th>
                                    <th>Employer's Contribution <br> (&#8369;)</th>
                                    <th style="width: 15%;"><i class="fa fa-cogs"></i></th>
                                </tr>
                            </thead>
                            <tbody id="container-new">
                                @foreach(collect($brackets)->sortBy('id') as $bracket)
                                        <tr>
                                            <td>
                                                <div class="row">
                                                    <input type="hidden" name="id" step="any" class="form-control form-control-sm" value="{{$bracket->id}}"/>
                                                    <input type="hidden" name="type" step="any" class="form-control form-control-sm" value="sss"/>
                                                    <div class="col-5">
                                                        <input type="number" name="rangefrom" step="any" class="form-control form-control-sm" value="{{$bracket->rangefrom}}" step=".01" min="0"  onkeydown="return event.keyCode !== 69"/>
                                                    </div>
                                                    <div class="col-2 text-center">-</div>
                                                    <div class="col-5">
                                                        <input type="number" name="rangeto" step="any" class="form-control form-control-sm" value="{{$bracket->rangeto}}" step=".01" min="0"  onkeydown="return event.keyCode !== 69"/>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="row">
                                                    <input type="number" name="monthlysalarycredit" step="any" class="form-control form-control-sm" value="{{$bracket->monthlysalarycredit}}" step=".01" min="0"  onkeydown="return event.keyCode !== 69"/>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="row">
                                                    <input type="number" name="eesamount" step="any" class="form-control form-control-sm" value="{{$bracket->eesamount}}" step=".01" min="0"  onkeydown="return event.keyCode !== 69"/>
                                                </div>
                                            </td>
                                            <td> 
                                                <div class="row">
                                                    <input type="number" name="ersamount" step="any" class="form-control form-control-sm" value="{{$bracket->ersamount}}" step=".01" min="0"  onkeydown="return event.keyCode !== 69"/>
                                                </div>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-warning btn-sm edit-bracket-item" data-id="{{$bracket->id}}">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-default btn-sm delete-bracket-item" data-id="{{$bracket->id}}" data-type="sss">
                                                    <i class="fa fa-trash-alt"></i>
                                                </button>
                                            </td>
                                        </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btn-add-bracket-item">Add row</button>
            </div>
            <script>
                $(document).ready(function(){
                    var t = $('#table-sss').DataTable({
                        "bFilter": false,
                        scrollY:        500,
                        scrollX:        false,
                        scrollCollapse: true,
                        paging:         false,
                        "info": false,
                        "ordering": false,
                        "aaSorting": []
                    });
                    function submitedit(_itemid, _bracketfrom, _bracketto, _monthlysalarycredit, _eesamount, _ersamount)
                    {
                        $.ajax({
                            url: '/hr/setup/deductions/bracketing',
                            type: 'get',
                            dataType: 'json',
                            data: {
                                action  :  'bracketitem_edit',
                                type: 'sss',
                                itemid: _itemid,
                                bracketfrom: _bracketfrom,
                                bracketto: _bracketto,
                                monthlysalarycredit: _monthlysalarycredit,
                                eesamount: _eesamount,
                                ersamount: _ersamount
                            },
                            success: function(data){
                                toastr.success('Updated successfully!','Edit Bracket Item')
                            }
                        })
                    }
                    var counter = 0;
                    $('#btn-add-bracket-item').on('click', function(){
                        
                        t.row.add(['<div class="row">'+
                                    '<div class="col-md-5"><input type="number" name="rangefrom" class="form-control form-control-sm new-bracket-from" placeholder="0.00" step=".01" min="0"  onkeydown="return event.keyCode !== 69"/></div>'+
                                    '<div class="col-md-2 text-center">-</div>'+
                                    '<div class="col-md-5"><input type="number" name="rangeto" class="form-control form-control-sm new-bracket-to" placeholder="0.00" step=".01" min="0"  onkeydown="return event.keyCode !== 69"/></div>'+
                                '</div>',
                                '<input type="number" name="monthlysalarycredit" class="form-control form-control-sm new-bracket-credit" placeholder="0.00" step=".01" min="0"  onkeydown="return event.keyCode !== 69"/>',
                                '<input type="number" name="eesamount" class="form-control form-control-sm new-bracket-contemployee" placeholder="0.00" step=".01" min="0"  onkeydown="return event.keyCode !== 69"/>',
                                '<input type="number" name="ersamount" class="form-control form-control-sm new-bracket-contemployer" placeholder="0.00" step=".01" min="0"  onkeydown="return event.keyCode !== 69"/>',
                                '<button type="button" class="btn btn-sm btn-success btn-submit-new-'+counter+'"><i class="fa fa-share"></i></button>']).draw(false);
                
                        $('.btn-submit-new-'+counter).on('click',  function(){
                            var thisbutton = $(this);
                            var bracketfrom         = $(this).closest('tr').find('input[name="rangefrom"]').val();
                            var bracketto           = $(this).closest('tr').find('input[name="rangeto"]').val();
                            var monthlysalarycredit           = $(this).closest('tr').find('input[name="monthlysalarycredit"]').val();
                            var eesamount       = $(this).closest('tr').find('input[name="eesamount"]').val();
                            var ersamount = $(this).closest('tr').find('input[name="ersamount"]').val();
                
                            var validation          = 0;
                            if(bracketfrom.replace(/^\s+|\s+$/g, "").length == 0)
                            { 
                                validation=1;
                                $(this).closest('tr').find('input[name="rangefrom"]').css('border','2px solid red')
                            }else{
                                $(this).closest('tr').find('input[name="rangefrom"]').removeAttr('style');
                            }
                            if(bracketto.replace(/^\s+|\s+$/g, "").length == 0)
                            { 
                                validation=1;
                                $(this).closest('tr').find('input[name="rangeto"]').css('border','2px solid red')
                            }else{
                                $(this).closest('tr').find('input[name="rangeto"]').removeAttr('style');
                            }
                            if(monthlysalarycredit.replace(/^\s+|\s+$/g, "").length == 0)
                            { 
                                validation=1;
                                $(this).closest('tr').find('input[name="monthlysalarycredit"]').css('border','2px solid red')
                            }else{
                                $(this).closest('tr').find('input[name="monthlysalarycredit"]').removeAttr('style');
                            }
                            if(eesamount.replace(/^\s+|\s+$/g, "").length == 0)
                            { 
                                validation=1;
                                $(this).closest('tr').find('input[name="eesamount"]').css('border','2px solid red')
                            }else{
                                $(this).closest('tr').find('input[name="eesamount"]').removeAttr('style');
                            }
                            if(ersamount.replace(/^\s+|\s+$/g, "").length == 0)
                            { 
                                validation=1;
                                $(this).closest('tr').find('input[name="ersamount"]').css('border','2px solid red')
                            }else{
                                $(this).closest('tr').find('input[name="ersamount"]').removeAttr('style');
                            }
                            if(validation == 0)
                            {                
                                $.ajax({
                                    url: '/hr/setup/deductions/bracketing',
                                    type: 'get',
                                    dataType: 'json',
                                    data: {
                                        action  :  'bracketitem_add',
                                        type: 'sss',
                                        bracketfrom: bracketfrom,
                                        bracketto: bracketto,
                                        monthlysalarycredit: monthlysalarycredit,
                                        eesamount: eesamount,
                                        ersamount: ersamount
                                    },
                                    success: function(data){
                                        // thisbutton.closest('tr').find('input').prop('disabled',true)
                                        thisbutton.closest('tr').find('input').addClass('existing')
                                        // console.log(thisbutton.closest('tr'))
                                        // console.log(thisbutton.closest('tr').find('input'))
                                        toastr.success('Added successfully!','Bracket')
                                        var thistd = thisbutton.closest('td');
                                        thistd.empty();
                                        thistd.append(`<button type="button" class="btn btn-sm btn-warning edit-bracket-item-`+counter+`" data-id="`+data+`">
                                        <i class="fa fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-default btn-sm delete-bracket-item-`+counter+`" data-id="`+data+`">
                                            <i class="fa fa-trash-alt"></i>
                                        </button>`);
                                        $('.edit-bracket-item-'+counter).on('click', function(){
                                            var thisbutton          = $(this);
                                            var bracketfrom         = $(this).closest('tr').find('input[name="bracketfrom"]').val();
                                            var bracketto           = $(this).closest('tr').find('input[name="bracketto"]').val();
                                            var monthlysalarycredit         = $(this).closest('tr').find('input[name="monthlysalarycredit"]').val();
                                            var eesamount  = $(this).closest('tr').find('input[name="eesamount"]').val();
                                            var ersamount  = $(this).closest('tr').find('input[name="ersamount"]').val();
                                            var itemid              = $(this).attr('data-id');
                                            var validation          = 0;
                                            if(bracketfrom.replace(/^\s+|\s+$/g, "").length == 0)
                                            { 
                                                validation=1;
                                                $(this).closest('tr').find('input[name="rangefrom"]').css('border','2px solid red')
                                            }else{
                                                $(this).closest('tr').find('input[name="rangefrom"]').removeAttr('style');
                                            }
                                            if(bracketto.replace(/^\s+|\s+$/g, "").length == 0)
                                            { 
                                                validation=1;
                                                $(this).closest('tr').find('input[name="rangeto"]').css('border','2px solid red')
                                            }else{
                                                $(this).closest('tr').find('input[name="rangeto"]').removeAttr('style');
                                            }
                                            if(monthlysalarycredit.replace(/^\s+|\s+$/g, "").length == 0)
                                            { 
                                                validation=1;
                                                $(this).closest('tr').find('input[name="monthlysalarycredit"]').css('border','2px solid red')
                                            }else{
                                                $(this).closest('tr').find('input[name="monthlysalarycredit"]').removeAttr('style');
                                            }
                                            if(eesamount.replace(/^\s+|\s+$/g, "").length == 0)
                                            { 
                                                validation=1;
                                                $(this).closest('tr').find('input[name="eesamount"]').css('border','2px solid red')
                                            }else{
                                                $(this).closest('tr').find('input[name="eesamount"]').removeAttr('style');
                                            }
                                            if(ersamount.replace(/^\s+|\s+$/g, "").length == 0)
                                            { 
                                                validation=1;
                                                $(this).closest('tr').find('input[name="ersamount"]').css('border','2px solid red')
                                            }else{
                                                $(this).closest('tr').find('input[name="ersamount"]').removeAttr('style');
                                            }
                                            if(validation == 0)
                                            {                
                                                submitedit(itemid, bracketfrom, bracketto, monthlysalarycredit, eesamount,  ersamount)
                                            }else{
                                                
                                                toastr.warning('Please fill in important fields!','Edit Bracket Item')
                                            }
                                        })
                                        $('.delete-bracket-item-'+counter).on('click', function(){
                                            var thisbutton = $(this);
                                            var bracketid = $(this).attr('data-id');
                                            
                                            Swal.fire({
                                                title: 'Are you sure you want to delete this bracket?',
                                                type: 'warning',
                                                confirmButtonColor: '#3085d6',
                                                confirmButtonText: 'Delete',
                                                showCancelButton: true,
                                                allowOutsideClick: false,
                                            }).then((confirm) => {
                                                    if (confirm.value) {
                                                        
                                                        $.ajax({
                                                            url: '/hr/setup/deductions/bracketing',
                                                            type: 'get',
                                                            dataType: 'json',
                                                            data: {
                                                            action  :  'bracketitem_delete',
                                                                type: 'sss',
                                                                id: bracketid
                                                            },
                                                            complete: function(data){
                                                                thisbutton.closest('tr').remove()
                                                                toastr.success('Deleted successfully!','Bracket')
                                                            }
                                                        })
                                                    }
                                                })
                                        })
                                    }
                                })
                            }else{
                                
                                toastr.warning('Please fill in important fields!','New Bracket Item')
                            }
                        })
                        counter+=1;
                    })
                    $('.edit-bracket-item').on('click', function(){
                        var thisbutton          = $(this);
                        var bracketfrom         = $(this).closest('tr').find('input[name="rangefrom"]').val();
                        var bracketto           = $(this).closest('tr').find('input[name="rangeto"]').val();
                        var monthlysalarycredit         = $(this).closest('tr').find('input[name="monthlysalarycredit"]').val();
                        var eesamount  = $(this).closest('tr').find('input[name="eesamount"]').val();
                        var ersamount  = $(this).closest('tr').find('input[name="ersamount"]').val();
                        var itemid              = $(this).attr('data-id');
                        var validation          = 0;
                        if(bracketfrom.replace(/^\s+|\s+$/g, "").length == 0)
                        { 
                            validation=1;
                            $(this).closest('tr').find('input[name="rangefrom"]').css('border','2px solid red')
                        }else{
                            $(this).closest('tr').find('input[name="rangefrom"]').removeAttr('style');
                        }
                        if(bracketto.replace(/^\s+|\s+$/g, "").length == 0)
                        { 
                            validation=1;
                            $(this).closest('tr').find('input[name="rangeto"]').css('border','2px solid red')
                        }else{
                            $(this).closest('tr').find('input[name="rangeto"]').removeAttr('style');
                        }
                        if(monthlysalarycredit.replace(/^\s+|\s+$/g, "").length == 0)
                        { 
                            validation=1;
                            $(this).closest('tr').find('input[name="monthlysalarycredit"]').css('border','2px solid red')
                        }else{
                            $(this).closest('tr').find('input[name="monthlysalarycredit"]').removeAttr('style');
                        }
                        if(eesamount.replace(/^\s+|\s+$/g, "").length == 0)
                        { 
                            validation=1;
                            $(this).closest('tr').find('input[name="eesamount"]').css('border','2px solid red')
                        }else{
                            $(this).closest('tr').find('input[name="eesamount"]').removeAttr('style');
                        }
                        if(ersamount.replace(/^\s+|\s+$/g, "").length == 0)
                        { 
                            validation=1;
                            $(this).closest('tr').find('input[name="ersamount"]').css('border','2px solid red')
                        }else{
                            $(this).closest('tr').find('input[name="ersamount"]').removeAttr('style');
                        }
                        if(validation == 0)
                        {                
                            submitedit(itemid, bracketfrom, bracketto, monthlysalarycredit, eesamount,  ersamount)
                        }else{
                            
                            toastr.warning('Please fill in important fields!','Edit Bracket Item')
                        }
                    })
                })
            </script>
        @elseif($type == 4)
            <div class="modal-body" id="modal-body">
                <div class="row mb-2">
                    <div class="col-md-3 col-sm-12">
                        <label>Select Tax Table</label>
                        <select class="form-control select2" id="select-bracket">
                            <option value="1" selected>Daily</option>
                            <option value="2">WEEKLY</option>
                            <option value="3">SEMI-MONTHLY</option>
                            <option value="4">MONTHLY</option>
                        </select>
                    </div>
                </div>
                <table style="width: 100%;" id="table-tax">
                    <thead class="bg-info text-center">
                        <tr>
                            {{-- <th style="width: 5%">Bracket</th> --}}
                            <th colspan="3" style="width: 30%; ">Compensation Range</th>
                            <th style="width: 5%; ">&nbsp;</th>
                            <th colspan="5">Prescribed Withholding Tax</th>
                            <th style="width: 10%; ">&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody class="display" id="tbody-tax" style="font-size: 13px;">
                        {{-- @foreach($brackets as $bracket)
                            @if($bracket->salarytypeid == 1)
                                <tr >
                                    <td class="text-center">{{$bracket->bracket}}</td>
                                    <td style="width: 15%;"><input type="number" class="form-control form-control-sm" value="{{$bracket->rangefrom}}" placeholder="0.00" step=".01" min="0"  onkeydown="return event.keyCode !== 69"/></td>
                                    <td style="width: 3%; text-align: center;">-</td>
                                    <td style="width: 15%;"><input type="number" class="form-control form-control-sm" value="{{$bracket->rangeto}}" placeholder="0.00" step=".01" min="0"  onkeydown="return event.keyCode !== 69"/></td>
                                    <td><input type="number" class="form-control form-control-sm" value="{{$bracket->prescribeamount}}" placeholder="0.00" step=".01" min="0"  onkeydown="return event.keyCode !== 69"/></td>
                                    <td style="width: 3%; text-align: center;">+</td>
                                    <td>
                                        <div class="form-group m-0">
                                            <div class="input-group input-group-sm">
                                            <input type="number" class="form-control datetimepicker-input" data-target="#reservationdate" value="{{$bracket->prescriberate}}" placeholder="0.00" step=".01" min="0"  onkeydown="return event.keyCode !== 69"/>
                                            <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                            <div class="input-group-text">%</div>
                                            </div>
                                            </div>
                                            </div>
                                    </td>
                                    <td style="width: 3%; text-align: center;">over</td>
                                    <td><input type="number" class="form-control form-control-sm" value="{{$bracket->prescribeover}}" placeholder="0.00" step=".01" min="0"  onkeydown="return event.keyCode !== 69"/></td>
                                    <td style="width: 5%; text-align: center;"><button tpe="button" class="btn btn-sm btn-outline-warning"><i class="fa fa-edit"></i></button></td>
                                    <td style="width: 5%; text-align: center;"><button tpe="button" class="btn btn-sm btn-outline-danger"><i class="fa fa-trash-alt"></i></button></td>
                                </tr>
                            @endif
                        @endforeach --}}
                    </tbody>
                </table>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btn-add-bracket-item">Add row</button>
            </div>
            <script>
                $(document).ready(function(){
                    function submitedit(_itemid, bracketrangefrom, bracketrangeto, bracketprescribeamount, bracketprescriberate, bracketprescribeover)
                    {
                        $.ajax({
                            url: '/hr/setup/deductions/bracketing',
                            type: 'get',
                            dataType: 'json',
                            data: {
                                action  :  'bracketitem_edit',
                                type: 'w_tax',
                                itemid: _itemid,
                                bracketrangefrom: bracketrangefrom,
                                bracketrangeto: bracketrangeto,
                                bracketprescribeamount: bracketprescribeamount,
                                bracketprescriberate: bracketprescriberate,
                                bracketprescribeover: bracketprescribeover
                            },
                            success: function(data){
                                toastr.success('Updated successfully!','Edit Bracket Item')
                            }
                        })
                    }
                    var counter = 0;
                    function selectbracket()
                    {
                        $.ajax({
                            url: '/hr/setup/deductions/bracketing',
                            type: 'get',
                            dataType: 'json',
                            data: {
                                    action  :  'gettaxtable',
                                type: 'w_tax',
                                tableid: $('#select-bracket').val()
                            },
                            success: function(data){
                                $('#tbody-tax').empty();
                                counter = data.length;
                                if(data.length > 0)
                                {
                                    $.each(data, function(key, value){
                                        
                                        $('#tbody-tax').append(
                                            `<tr>
                                                {{-- <td class="text-center">`+value.bracket+`</td> --}}
                                                <td style="width: 15%;"><input type="number" class="form-control form-control-sm" value="`+value.rangefrom+`" name="input-rangefrom" placeholder="0.00" step=".01" min="0"  onkeydown="return event.keyCode !== 69"/></td>
                                                <td style="width: 3%; text-align: center;">-</td>
                                                <td style="width: 15%;"><input type="number" class="form-control form-control-sm" value="`+value.rangeto+`" name="input-rangeto" placeholder="0.00" step=".01" min="0"  onkeydown="return event.keyCode !== 69"/></td>
                                                <td style="width: 5%; text-align: center;">&nbsp;</td>
                                                <td><input type="number" class="form-control form-control-sm" value="`+value.prescribeamount+`" name="input-prescribeamount" placeholder="0.00" step=".01" min="0"  onkeydown="return event.keyCode !== 69"/></td>
                                                <td style="width: 3%; text-align: center;">+</td>
                                                <td>
                                                    <div class="form-group m-0">
                                                        <div class="input-group input-group-sm">
                                                        <input type="number" class="form-control datetimepicker-input" data-target="#reservationdate" value="`+value.prescriberate+`" name="input-prescriberate" placeholder="0.00" step=".01" min="0"  onkeydown="return event.keyCode !== 69"/>
                                                        <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                                        <div class="input-group-text">%</div>
                                                        </div>
                                                        </div>
                                                        </div>
                                                </td>
                                                <td style="width: 3%; text-align: center;">over</td>
                                                <td><input type="number" class="form-control form-control-sm" value="`+value.prescribeover+`" name="input-prescribeover" placeholder="0.00" step=".01" min="0"  onkeydown="return event.keyCode !== 69"/></td>
                                                <td style="width: 10%; text-align: center;"><button tpe="button" class="btn btn-sm btn-outline-warning btn-bracketitem-edit" data-id="`+value.id+`"><i class="fa fa-edit"></i></button> <button tpe="button" class="btn btn-sm btn-outline-danger delete-bracket-item" data-id="`+value.id+`" data-type="w_tax"><i class="fa fa-trash-alt"></i></button></td>
                                            </tr>`
                                        );
                                        $('.btn-bracketitem-edit[data-id="'+value.id+'"]').on('click', function(){
                                            var thisbutton          = $(this);
                                            var thistr          = thisbutton.closest('tr');
                                            var bracketrangefrom         = thistr.find('input[name="input-rangefrom"]').val();
                                            var bracketrangeto           = thistr.find('input[name="input-rangeto"]').val();
                                            var bracketprescribeamount = thistr.find('input[name="input-prescribeamount"]').val();
                                            var bracketprescriberate = thistr.find('input[name="input-prescriberate"]').val();
                                            var bracketprescribeover = thistr.find('input[name="input-prescribeover"]').val();
                                            var itemid              = $(this).attr('data-id');
                                            var validation          = 0;
                                            if(bracketrangefrom.replace(/^\s+|\s+$/g, "").length == 0)
                                            { 
                                                validation=1;
                                                $(this).closest('tr').find('input[name="input-rangefrom"]').css('border','2px solid red')
                                            }else{
                                                $(this).closest('tr').find('input[name="input-rangefrom"]').removeAttr('style');
                                            }
                                            if(bracketrangeto.replace(/^\s+|\s+$/g, "").length == 0)
                                            { 
                                                validation=1;
                                                $(this).closest('tr').find('input[name="input-rangeto"]').css('border','2px solid red')
                                            }else{
                                                $(this).closest('tr').find('input[name="input-rangeto"]').removeAttr('style');
                                            }
                                            if(bracketprescribeamount.replace(/^\s+|\s+$/g, "").length == 0)
                                            { 
                                                validation=1;
                                                $(this).closest('tr').find('input[name="input-prescribeamount"]').css('border','2px solid red')
                                            }else{
                                                $(this).closest('tr').find('input[name="input-prescribeamount"]').removeAttr('style');
                                            }
                                            if(bracketprescriberate.replace(/^\s+|\s+$/g, "").length == 0)
                                            { 
                                                validation=1;
                                                $(this).closest('tr').find('input[name="input-prescriberate"]').css('border','2px solid red')
                                            }else{
                                                $(this).closest('tr').find('input[name="input-prescriberate"]').removeAttr('style');
                                            }
                                            if(bracketprescribeover.replace(/^\s+|\s+$/g, "").length == 0)
                                            { 
                                                validation=1;
                                                $(this).closest('tr').find('input[name="input-prescribeover"]').css('border','2px solid red')
                                            }else{
                                                $(this).closest('tr').find('input[name="input-prescribeover"]').removeAttr('style');
                                            }
                                            if(validation == 0)
                                            {                
                                                submitedit(itemid, bracketrangefrom, bracketrangeto, bracketprescribeamount, bracketprescriberate,bracketprescribeover)
                                            }else{
                                                
                                                toastr.warning('Please fill in important fields!','Edit Bracket Item')
                                            }
                                        })
                                    })
                                }else{

                                }
                            }
                        })
                        
                    }
                    selectbracket()
                    $('#select-bracket').on('change', function(){
                    selectbracket()
                    })
                    $('#btn-add-bracket-item').on('click', function(){
                        
                        $('#tbody-tax').append(
                            `<tr>
                                <td style="width: 15%;"><input type="number" class="form-control form-control-sm" value="" name="input-rangefrom" placeholder="0.00" step=".01" min="0"  onkeydown="return event.keyCode !== 69"/></td>
                                <td style="width: 3%; text-align: center;">-</td>
                                <td style="width: 15%;"><input type="number" class="form-control form-control-sm" value="" name="input-rangeto" placeholder="0.00" step=".01" min="0"  onkeydown="return event.keyCode !== 69"/></td>
                                <td style="width: 5%; text-align: center;">&nbsp;</td>
                                <td><input type="number" class="form-control form-control-sm" value="" name="input-prescribeamount" placeholder="0.00" step=".01" min="0"  onkeydown="return event.keyCode !== 69"/></td>
                                <td style="width: 3%; text-align: center;">+</td>
                                <td>
                                    <div class="form-group m-0">
                                        <div class="input-group input-group-sm">
                                        <input type="number" class="form-control datetimepicker-input" data-target="#reservationdate" value="" name="input-prescriberate" placeholder="0.00" step=".01" min="0"  onkeydown="return event.keyCode !== 69"/>
                                        <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                        <div class="input-group-text">%</div>
                                        </div>
                                        </div>
                                        </div>
                                </td>
                                <td style="width: 3%; text-align: center;">over</td>
                                <td><input type="number" class="form-control form-control-sm" value="" name="input-prescribeover" placeholder="0.00" step=".01" min="0"  onkeydown="return event.keyCode !== 69"/></td>
                                <td style="width: 10%; text-align: center;"><button tpe="button" class="btn btn-sm btn-outline-success btn-submit-new-`+counter+`"><i class="fa fa-share"></i></button> <button tpe="button" class="btn btn-sm btn-outline-danger btn-row-remove"><i class="fa fa-trash-alt"></i></button></td>
                            </tr>`
                        );
                        $('.btn-submit-new-'+counter).on('click',  function(){
                            var thisbutton = $(this);
                            var bracketrangefrom         = $(this).closest('tr').find('input[name="input-rangefrom"]').val();
                            var bracketrangeto           = $(this).closest('tr').find('input[name="input-rangeto"]').val();
                            var bracketprescribeamount = $(this).closest('tr').find('input[name="input-prescribeamount"]').val();
                            var bracketprescriberate = $(this).closest('tr').find('input[name="input-prescriberate"]').val();
                            var bracketprescribeover = $(this).closest('tr').find('input[name="input-prescribeover"]').val();
                
                            var validation          = 0;
                            if(bracketrangefrom.replace(/^\s+|\s+$/g, "").length == 0)
                            { 
                                validation=1;
                                $(this).closest('tr').find('input[name="input-rangefrom"]').css('border','2px solid red')
                            }else{
                                $(this).closest('tr').find('input[name="input-rangefrom"]').removeAttr('style');
                            }
                            if(bracketrangeto.replace(/^\s+|\s+$/g, "").length == 0)
                            { 
                                validation=1;
                                $(this).closest('tr').find('input[name="input-rangeto"]').css('border','2px solid red')
                            }else{
                                $(this).closest('tr').find('input[name="input-rangeto"]').removeAttr('style');
                            }
                            if(bracketprescribeamount.replace(/^\s+|\s+$/g, "").length == 0)
                            { 
                                validation=1;
                                $(this).closest('tr').find('input[name="input-prescribeamount"]').css('border','2px solid red')
                            }else{
                                $(this).closest('tr').find('input[name="input-prescribeamount"]').removeAttr('style');
                            }
                            if(bracketprescriberate.replace(/^\s+|\s+$/g, "").length == 0)
                            { 
                                validation=1;
                                $(this).closest('tr').find('input[name="input-prescriberate"]').css('border','2px solid red')
                            }else{
                                $(this).closest('tr').find('input[name="input-prescriberate"]').removeAttr('style');
                            }
                            if(bracketprescribeover.replace(/^\s+|\s+$/g, "").length == 0)
                            { 
                                validation=1;
                                $(this).closest('tr').find('input[name="input-prescribeover"]').css('border','2px solid red')
                            }else{
                                $(this).closest('tr').find('input[name="input-prescribeover"]').removeAttr('style');
                            }
                            if(validation == 0)
                            {                
                                $.ajax({
                                    url: '/hr/setup/deductions/bracketing',
                                    type: 'get',
                                    dataType: 'json',
                                    data: {
                                        action  :  'bracketitem_add',
                                        type: 'w_tax',
                                        tableid: $('#select-bracket').val(),
                                        bracketrangefrom: bracketrangefrom,
                                        bracketrangeto: bracketrangeto,
                                        bracketprescribeamount: bracketprescribeamount,
                                        bracketprescriberate: bracketprescriberate,
                                        bracketprescribeover: bracketprescribeover
                                    },
                                    success: function(data){
                                        thisbutton.closest('tr').find('input').addClass('existing')
                                        // console.log(thisbutton.closest('tr'))
                                        // console.log(thisbutton.closest('tr').find('input'))
                                        toastr.success('Added successfully!','Bracket')
                                        var thistd = thisbutton.closest('td');
                                        thistd.empty();
                                        thistd.append(`<button type="button" class="btn btn-sm btn-outline-warning edit-bracket-item-`+counter+`" data-id="`+data+`">
                                        <i class="fa fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-danger btn-sm delete-bracket-item-`+counter+`" data-id="`+data+`">
                                            <i class="fa fa-trash-alt"></i>
                                        </button>`);
                                        $('.edit-bracket-item-'+counter).on('click', function(){
                                            var thisbutton          = $(this);
                                            var bracketrangefrom         = $(this).closest('tr').find('input[name="input-rangefrom"]').val();
                                            var bracketrangeto           = $(this).closest('tr').find('input[name="input-rangeto"]').val();
                                            var bracketprescribeamount = $(this).closest('tr').find('input[name="input-prescribeamount"]').val();
                                            var bracketprescriberate = $(this).closest('tr').find('input[name="input-prescriberate"]').val();
                                            var bracketprescribeover = $(this).closest('tr').find('input[name="input-prescribeover"]').val();
                                            var itemid              = $(this).attr('data-id');
                                            var validation          = 0;
                                            if(bracketfrom.replace(/^\s+|\s+$/g, "").length == 0)
                                            { 
                                                validation=1;
                                                $(this).closest('tr').find('input[name="input-rangefrom"]').css('border','2px solid red')
                                            }else{
                                                $(this).closest('tr').find('input[name="input-rangefrom"]').removeAttr('style');
                                            }
                                            if(bracketto.replace(/^\s+|\s+$/g, "").length == 0)
                                            { 
                                                validation=1;
                                                $(this).closest('tr').find('input[name="input-rangeto"]').css('border','2px solid red')
                                            }else{
                                                $(this).closest('tr').find('input[name="input-rangeto"]').removeAttr('style');
                                            }
                                            if(bracketcontemployee.replace(/^\s+|\s+$/g, "").length == 0)
                                            { 
                                                validation=1;
                                                $(this).closest('tr').find('input[name="input-prescribeamount"]').css('border','2px solid red')
                                            }else{
                                                $(this).closest('tr').find('input[name="input-prescribeamount"]').removeAttr('style');
                                            }
                                            if(bracketcontemployer.replace(/^\s+|\s+$/g, "").length == 0)
                                            { 
                                                validation=1;
                                                $(this).closest('tr').find('input[name="input-prescriberate"]').css('border','2px solid red')
                                            }else{
                                                $(this).closest('tr').find('input[name="input-prescriberate"]').removeAttr('style');
                                            }
                                            if(bracketcontemployer.replace(/^\s+|\s+$/g, "").length == 0)
                                            { 
                                                validation=1;
                                                $(this).closest('tr').find('input[name="input-prescribeover"]').css('border','2px solid red')
                                            }else{
                                                $(this).closest('tr').find('input[name="input-prescribeover"]').removeAttr('style');
                                            }
                                            if(validation == 0)
                                            {                
                                                submitedit(itemid, bracketrangefrom, bracketrangeto, bracketprescribeamount, bracketprescriberate, bracketprescribeover)
                                            }else{
                                                
                                                toastr.warning('Please fill in important fields!','Edit Bracket Item')
                                            }
                                        })
                                        $('.delete-bracket-item-'+counter).on('click', function(){
                                            var thisbutton = $(this);
                                            var bracketid = $(this).attr('data-id');
                                            
                                            Swal.fire({
                                                title: 'Are you sure you want to delete this bracket?',
                                                type: 'warning',
                                                confirmButtonColor: '#3085d6',
                                                confirmButtonText: 'Delete',
                                                showCancelButton: true,
                                                allowOutsideClick: false,
                                            }).then((confirm) => {
                                                    if (confirm.value) {
                                                        
                                                        $.ajax({
                                                            url: '/hr/setup/deductions/bracketing',
                                                            type: 'get',
                                                            dataType: 'json',
                                                            data: {
                                                            action  :  'bracketitem_delete',
                                                                type: 'w_tax',
                                                                id: bracketid
                                                            },
                                                            complete: function(data){
                                                                thisbutton.closest('tr').remove()
                                                                toastr.success('Deleted successfully!','Bracket')
                                                            }
                                                        })
                                                    }
                                                })
                                        })
                                    }
                                })
                            }else{
                                
                                toastr.warning('Please fill in important fields!','New Bracket Item')
                            }
                        })
                        counter+=1;
                    })
                })
            </script>
        @endif
    </div>
</div>
<script>
    $(document).ready(function(){
        $(document).on('click','.delete-bracket-item', function(){
            var thisbutton = $(this);
            var bracketid = $(this).attr('data-id');
            var brackettype = $(this).attr('data-type')
            
            Swal.fire({
                title: 'Are you sure you want to delete this bracket item?',
                type: 'warning',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Delete',
                showCancelButton: true,
                allowOutsideClick: false,
            }).then((confirm) => {
                    if (confirm.value) {
                        
                        $.ajax({
                            url: '/hr/setup/deductions/bracketing',
                            type: 'get',
                            dataType: 'json',
                            data: {
                            action  :  'bracketitem_delete',
                                type: brackettype,
                                id: bracketid
                            },
                            complete: function(data){
                                thisbutton.closest('tr').remove()
                                toastr.success('Deleted successfully!','Bracket')
                            }
                        })
                    }
                })
        })
    })
</script>