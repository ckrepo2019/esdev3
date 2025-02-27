
{{-- @if(session()->has('linkid'))
@if( session()->get('linkid') == 'custom-content-above-allowance') --}}
    <div class="tab-pane fade show active" id="custom-content-above-allowance" role="tabpanel" aria-labelledby="custom-content-above-allowance-tab">
{{-- @else
    <div class="tab-pane fade" id="custom-content-above-allowance" role="tabpanel" aria-labelledby="custom-content-above-allowance-tab">
@endif
@else
<div class="tab-pane fade" id="custom-content-above-allowance" role="tabpanel" aria-labelledby="custom-content-above-allowance-tab">
@endif --}}
    <div class="card text-uppercase" style="border: none;">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    @if(count($standardallowances) > 0)
                    <fieldset>
                        <legend>
                            <strong>Standard Allowances</strong>
                        </legend>
                        <form {{--action="/hr/employeestandardallowances" method="get"--}}>
                            <div style="width:100%;overflow: scroll">
                                <table class="table"  >
                                    <thead class="text-center">
                                        <tr>
                                            <th>Particulars</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($standardallowances as $standardallowance)
                                            <tr class="standardallowancedetails">
                                                <td>
                                                    <input type="hidden" name="allowanceid[]" value="{{$standardallowance->id}}" disabled>
                                                    @if(count($mystandardallowances) == 0)
                                                        <button type="button" class="btn btn-warning btn-sm allowancescheckbox" data-toggle="button" aria-pressed="false" autocomplete="off">
                                                            <i class="fa fa-edit"></i>
                                                        </button>
                                                        <label for="checkboxPrimary{{$standardallowance->id}}" style="display: inline;">
                                                            {{$standardallowance->description}}
                                                        </label>
                                                    @else
                                                        @foreach ($mystandardallowances as $mystandardallowance)
                                                            @if($standardallowance->id == $mystandardallowance->allowance_standardid)
                                                            <button type="button" class="btn btn-warning btn-sm allowancescheckbox" data-toggle="button" aria-pressed="false" autocomplete="off">
                                                                <i class="fa fa-edit"></i>
                                                            </button>
                                                                <label for="checkboxPrimary{{$standardallowance->id}}" style="display: inline;">
                                                                    {{$standardallowance->description}}
                                                                </label>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </td>
                                                <td class="standardallowanceamount">
                                                    @if(count($mystandardallowances) == 0)
                                                        <input type="number" class="form-control"  step="any" name="amounts[]" placeholder="Amount / month" disabled required/>
                                                    @else
                                                        @foreach ($mystandardallowances as $mystandardallowance)
                                                            @if($standardallowance->id == $mystandardallowance->allowance_standardid)
                                                                    <input type="number" step="any" class="form-control" name="amounts[]" value="{{$mystandardallowance->amount}}" placeholder="Amount / month" disabled required/>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </td>
                                                <td class="allowancesradioboxcontainer text-center">
                                                    @if(count($mystandardallowances) == 0)
                                                        <div class="icheck-success d-inline mr-3">
                                                            <input type="radio" id="allowance{{$standardallowance->id}}1" value="active" class="allowanceradiobox" name="allowancestatus{{$standardallowance->id}}" disabled="disabled">
                                                            <label for="allowance{{$standardallowance->id}}1">
                                                                Active
                                                            </label>
                                                        </div>
                                                        <div class="icheck-secondary d-inline">
                                                            <input type="radio" id="allowance{{$standardallowance->id}}2" value="inactive" class="allowanceradiobox" name="allowancestatus{{$standardallowance->id}}" disabled="disabled" checked="">
                                                            <label for="allowance{{$standardallowance->id}}2">
                                                                Inactive
                                                            </label>
                                                        </div>
                                                    @else
                                                        @foreach ($mystandardallowances as $mystandardallowance)
                                                            @if($standardallowance->id == $mystandardallowance->allowance_standardid)
                                                                @if($mystandardallowance->status == '1')
                                                                    <div class="icheck-success d-inline mr-3">
                                                                        <input type="radio" id="allowance{{$standardallowance->id}}1" value="active" class="allowanceradiobox" name="allowancestatus{{$standardallowance->id}}" disabled="disabled" checked="">
                                                                        <label for="allowance{{$standardallowance->id}}1">
                                                                            Active
                                                                        </label>
                                                                    </div>
                                                                    <div class="icheck-secondary d-inline">
                                                                        <input type="radio" id="allowance{{$standardallowance->id}}2" value="inactive" class="allowanceradiobox" name="allowancestatus{{$standardallowance->id}}" disabled="disabled" >
                                                                        <label for="allowance{{$standardallowance->id}}2">
                                                                            Inactive
                                                                        </label>
                                                                    </div>
                                                                @else
                                                                    <div class="icheck-success d-inline mr-3">
                                                                        <input type="radio" id="allowance{{$standardallowance->id}}1" value="active" class="allowanceradiobox" name="allowancestatus{{$standardallowance->id}}" disabled="disabled">
                                                                        <label for="allowance{{$standardallowance->id}}1">
                                                                            Active
                                                                        </label>
                                                                    </div>
                                                                    <div class="icheck-secondary d-inline">
                                                                        <input type="radio" id="allowance{{$standardallowance->id}}2" value="inactive" class="allowanceradiobox" name="allowancestatus{{$standardallowance->id}}" disabled="disabled" checked="">
                                                                        <label for="allowance{{$standardallowance->id}}2">
                                                                            Inactive
                                                                        </label>
                                                                    </div>
                                                                @endif
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="row  mt-3">
                                <div class="col-12 updatecontributionbutton">
                                    <button type="button" class="btn btn-warning updatecontributionsbutton float-right" id="updatestandardallowancebutton">Update</button>
                                </div>
                            </div>
                        </form>
                    </fieldset>
                    @endif
                </div>
            </div>
            <br>
            <br>
            <div class="row">
                <div class="col-md-12">
                    <fieldset>
                        <div class="row">
                            <legend>
                                <strong>Other Allowances</strong>
                            </legend>
                            <div class="col-md-4 col-12">
                                <button type="button" class="btn btn-sm text-success float-left" id="addallowance" clicked="0">
                                    <i class="fa fa-plus"></i>&nbsp; Add allowance/s
                                </button>
                                <br>
                                <br>
                                <div id="addallowancecontainer"></div>
                            </div>
                            <div class="col-md-8 col-12">
                                <table class="table table-bordered text-center" style="font-size: 12px;">
                                    <thead>
                                        <tr>
                                            <th>
                                                Description
                                            </th>
                                            <th>
                                                Amount
                                            </th>
                                            <th>
                                                Term
                                            </th>
                                            <th style="width:18%">
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="deductiondetails">
                                        @foreach ($myallowances as $myallowance)
                                            <tr>
                                                <td valign="middle">
                                                    {{$myallowance->description}}
                                                </td>
                                                <td valign="middle">
                                                    &#8369;{{$myallowance->amount}}
                                                </td>
                                                <td valign="middle">
                                                    {{$myallowance->term}} month/s
                                                </td>
                                                <td class="p-1" valign="middle" style="vertical-align: middle !important;text-align: center;">
                                                    @if($myallowance->paid == 0)
                                                        <button class="btn btn-sm btn-warning editallowancedetail p-1 m-0" data-toggle="modal" data-target="#editotherallowancedetail{{$myallowance->id}}">Edit</button>
                                                        <div id="editotherallowancedetail{{$myallowance->id}}" class="modal custom-modal fade" role="dialog" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                                                            <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title"><strong>Other Allowance</strong></h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">×</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <form>
                                                                            <input type="hidden" name="otherallowanceid" value="{{$myallowance->id}}">
                                                                            <input type="hidden" class="form-control" name="linkid" value="custom-content-above-allowance" />
                                                                            <label>Description</label>
                                                                            <input type="text" class="form-control  mb-2" name="description" value="{{$myallowance->description}}">
                                                                            <label>Amount</label>
                                                                            <input type="text" class="form-control mb-2" name="amount"  lang="en-150" value="{{$myallowance->amount}}">
                                                                            <label>Term (No. of months)</label>
                                                                            <input type="number" class="form-control mb-2" name="term" value="{{$myallowance->term}}">
                                                                            <br>
                                                                            <div class="submit-section">
                                                                                <button type="button" class="btn btn-primary submit-btn float-right editotherallowance" data-dismiss="modal">Update</button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <button class="btn btn-sm btn-danger deleteallowancedetail p-1 m-0" data-toggle="modal" data-target="#deleteotherallowancedetail{{$myallowance->id}}">Delete</button>
                                                        <div id="deleteotherallowancedetail{{$myallowance->id}}" class="modal custom-modal fade" role="dialog" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                                                            <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title"><strong>Other Allowance</strong></h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">×</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <h3 class="text-danger">Are you sure you want to delete this other allowance item ?</h3>
                                                                        <br>
                                                                        <form>
                                                                            <input type="hidden" name="otherallowanceid" value="{{$myallowance->id}}">
                                                                            <input type="hidden" class="form-control" name="linkid" value="custom-content-above-allowance" />
                                                                            <label>Description</label>
                                                                            <input type="text" class="form-control  mb-2" name="description" value="{{$myallowance->description}}" disabled>
                                                                            <label>Amount</label>
                                                                            <input type="text" class="form-control mb-2" name="amount"  lang="en-150" value="{{$myallowance->amount}}" disabled>
                                                                            <label>Term (No. of months)</label>
                                                                            <input type="number" class="form-control mb-2" name="term" value="{{$myallowance->term}}" disabled>
                                                                            <br>
                                                                            <div class="submit-section">
                                                                                <button type="button" class="btn btn-danger submit-btn float-right deleteotherdeduction"data-dismiss="modal">Delete</button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @elseif($myallowance->paid == 1)
                                                        <button class="btn btn-sm btn-secondary btn-block p-1 m-0">Paid</button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $('#addallowancecontainer').empty();
    $('.allowancescheckbox').click(function(){
        if($(this).hasClass('active') == true){
            $(this).removeClass('focus')
            $(this).removeClass('btn-secondary');
            $(this).addClass('btn-warning');
            $(this).closest('.standardallowancedetails').find('input[name="allowanceid[]"]').attr('disabled',true);
            $(this).closest('.standardallowancedetails').find('.allowancesradioboxcontainer')[0].children[0].children[0].readOnly = true;
            
            $(this).closest('.standardallowancedetails').find('.allowancesradioboxcontainer')[0].children[1].children[0].disabled = true;
            $(this).closest('.standardallowancedetails').find('.allowancesradioboxcontainer')[0].children[0].children[0].disabled = true;
            //ersinput
            $(this).closest('.standardallowancedetails').find('.standardallowanceamount')[0].children[0].disabled = true;
        }
        else
        {
            //activeradio
            $(this).addClass('focus');
            $(this).removeClass('btn-warning');
            $(this).addClass('btn-secondary');
            // $(this).closest('.standardallowancedetails').find('.allowancesradioboxcontainer')[0].children[0].children[0].readOnly = false;
            //inactiveradio
            $(this).closest('tr').find('input[name="allowanceid[]"]').attr('disabled',false)
            $(this).closest('tr').find('.allowancesradioboxcontainer')[0].children[1].children[0].disabled = false;
            $(this).closest('tr').find('.allowancesradioboxcontainer')[0].children[0].children[0].disabled = false;
            //eesinput
            $(this).closest('tr').find('.standardallowanceamount')[0].children[0].disabled = false;
        }
    })
    $('#addallowancecontainer').empty();
    var addallowancedetailrow = 0;
    $("#addallowance").unbind().click(function() {
    // $(document).on('click','#addallowance', function(e){
        if(addallowancedetailrow == 0){
            $('#addallowancecontainer').append(
                '<div class="card" style="border: none;">'+
                    '<button type="button" class="btn btn-block btn-success saveallowancebutton">Save</button>'+
                '</div>'
            );
            $('#addallowancecontainer').prepend(
                '<div class="card" style="border: none;">'+
                    '<div class="card-header">'+
                        '<div class="card-tools">'+
                            // '<button type="button" class="btn btn-tool" data-card-widget="collapse">'+
                            //     '<i class="fas fa-minus"></i>'+
                            // '</button>'+
                            '<button type="button" class="btn btn-tool removeallowancecard" data-card-widget="remove">'+
                                '<i class="fas fa-times"></i>'+
                            '</button>'+
                        '</div>'+
                    '</div>'+
                    '<div class="card-body">'+
                    
                        '<small><strong>Description</strong></small>'+
                        '<input type="text" name="description[]" class="form-control form-control-sm mb-2" placeholder="Description" required/>'+
                    
                        '<small><strong>Total Amount</strong></small>'+
                        '<input type="number" name="amount[]" class="form-control form-control-sm mb-2" placeholder="Total Amount" required/>'+

                        '<small><strong>Term</strong></small>'+
                        '<input type="number" name="term[]" class="form-control form-control-sm mb-2" placeholder="No. of months" required/>'+

                        // '<small><strong>Payable for (no. of months)</strong></small>'+
                        // '<input type="number" name="term[]" class="form-control form-control-sm" placeholder="No. of months" required/>'+
                        // '<small><strong>Select deduction type</strong></small>'+
                        // '<div class="deductiondetailcontainer"></div>'+
                        // '<small><strong>Enter Amount</strong></small>'+
                        // '<input type="number" name="amount[]" class="form-control form-control-sm" placeholder="Amount" required/>'+
                    '</div>'+
                '</div>'
            );
        }
        else if(addallowancedetailrow > 0){
            $('#addallowancecontainer').prepend(
                '<div class="card" style="border: none;">'+
                    '<div class="card-header">'+
                        '<div class="card-tools">'+
                            '<button type="button" class="btn btn-tool removeallowancecard" data-card-widget="remove">'+
                                '<i class="fas fa-times"></i>'+
                            '</button>'+
                        '</div>'+
                    '</div>'+
                    '<div class="card-body">'+
                    
                    '<small><strong>Description</strong></small>'+
                    '<input type="text" name="description[]" class="form-control form-control-sm mb-2" placeholder="Description" required/>'+
                
                    '<small><strong>Total Amount</strong></small>'+
                    '<input type="number" name="amount[]" class="form-control form-control-sm mb-2" placeholder="Total Amount" required/>'+

                    '<small><strong>Term</strong></small>'+
                    '<input type="number" name="term[]" class="form-control form-control-sm mb-2" placeholder="No. of months" required/>'+

                    // '<small><strong>Payable for (no. of months)</strong></small>'+
                    // '<input type="number" name="term[]" class="form-control form-control-sm" placeholder="No. of months" required/>'+
                        // '<small><strong>Select deduction type</strong></small>'+
                        // '<small><strong>Enter Amount</strong></small>'+
                        // '<input type="number" name="amount[]" class="form-control form-control-sm" placeholder="Amount" required/>'+
                    '</div>'+
                '</div>'
            );
        }
        addallowancedetailrow+=1;
    });
    $('.removeallowancecard').unbind().click(function(){
        addallowancedetailrow-=1;
        if(addallowancedetailrow == 0){
            $('#addallowancecontainer').empty();
        }
    })
    $('.updatecontributionsbutton').hide()
    $(document).on('input','input[name="amounts[]"]', function(){

        $('.updatecontributionsbutton').show();
    })
    $(document).on('change','input[class="allowanceradiobox"]', function(){

        $('.updatecontributionsbutton').show();
    })
    $(document).on('click','.saveallowancebutton', function(){
        var descriptions = []
        var amounts = []
        var terms = []

        var emptyelements = [];

        $('input[name="description[]"]').each(function(){
            $(this).css('border','1px solid #ddd')
            if($(this).val().replace(/^\s+|\s+$/g, "").length == 0)
            {
                emptyelements.push($(this))
            }else{

                descriptions.push($(this).val())
            }
        })
        $('input[name="amount[]"]').each(function(){
            $(this).css('border','1px solid #ddd')
            if($(this).val().replace(/^\s+|\s+$/g, "").length == 0)
            {
                emptyelements.push($(this))
            }else{

                amounts.push($(this).val())
            }
        })
        $('input[name="term[]"]').each(function(){
            $(this).css('border','1px solid #ddd')
            if($(this).val().replace(/^\s+|\s+$/g, "").length == 0)
            {
                emptyelements.push($(this))
            }else{

                terms.push($(this).val())
            }
        })

        if(emptyelements.length == 0)
        {
            $.ajax({
                url: "/hr/employees/profile/taballowances/addallowance",
                type: "GET",
                data: {
                    employeeid:   '{{$profileinfoid}}',
                    descriptions:descriptions,
                    amounts:amounts,
                    terms:terms
                    },
                complete: function (data) {
                    // $('#profilepic').attr('src',data)
                    toastr.success('Added successfully!','Standard Allowances')
                    $('#custom-content-above-allowance-tab').click()
                }
            });
        }else{
            $.each(emptyelements,function(){
                $(this).css('border','1px solid red')
            })
        }
        
    })
    $(document).on('click','.editotherallowance', function(){
        var otherallowanceid = $(this).closest('form').find('input[name="otherallowanceid"]').val();
        var description = $(this).closest('form').find('input[name="description"]').val();
        var amount      = $(this).closest('form').find('input[name="amount"]').val();
        var term        = $(this).closest('form').find('input[name="term"]').val();
        $.ajax({
            url: "/hr/employees/profile/taballowances/updateallowance",
            type: "GET",
            data: {
                employeeid:   '{{$profileinfoid}}',
                otherallowanceid:otherallowanceid,
                description:description,
                amount:amount,
                term:term
                },
                complete: function (data) {
                // $('#profilepic').attr('src',data)
                toastr.success('Updated successfully!','Other Allowances')
                $('#custom-content-above-allowance-tab').click()
            }
        });
    })
    $(document).on('click','.deleteotherdeduction', function(){
        var otherallowanceid = $(this).closest('form').find('input[name="otherallowanceid"]').val();
        $.ajax({
            url: "/hr/employees/profile/taballowances/deleteallowance",
            type: "GET",
            data: {
                employeeid:   '{{$profileinfoid}}',
                otherallowanceid:otherallowanceid
                },
                complete: function (data) {
                // $('#profilepic').attr('src',data)
                toastr.success('Deleted successfully!','Other Allowances')
                $('#custom-content-above-allowance-tab').click()
            }
        });
    })
        $(document).on('click','#updatestandardallowancebutton', function(){
            var allowanceid = [];
            var amounts     = [];
            var status      = [];
            var emptyelements = [];
            var thisform = $(this).closest('form')

            $('input[name="allowanceid[]"]').each(function(){
                    allowanceid.push($(this).val())
                    status.push($('input[name="allowancestatus'+$(this).val()+'"]:checked').val())
            })
            $('input[name="amounts[]"]').each(function(){
                $(this).css('border','1px solid #ddd')
                if($(this).val().replace(/^\s+|\s+$/g, "").length == 0)
                {
                    emptyelements.push($(this))
                }else{

                    amounts.push($(this).val())
                }
            })
            if(emptyelements.length == 0)
            {
                $.ajax({
                    url: "/hr/employees/profile/taballowances/updatestandardallowance",
                    type: "GET",
                    data: {
                        employeeid:   '{{$profileinfoid}}',
                        allowanceid:allowanceid,
                        amounts:amounts,
                        status:status
                        },
                    complete: function () {
                        // $('#profilepic').attr('src',data)
                        toastr.success('Updated successfully!', 'Standard Allowances')
                        $('#custom-content-above-allowance-tab').click()
                    }
                });
            }else{
                $.each(emptyelements,function(){
                    $(this).css('border','1px solid red')
                })
            }
        })
</script>