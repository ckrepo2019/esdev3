
<script src="{{asset('plugins/bootstrap-switch/js/bootstrap-switch.min.js')}}"></script>
<div class="tab-pane fade show active" id="custom-content-above-contributions" role="tabpanel" aria-labelledby="custom-content-above-contributions-tab">
    <div class="card text-uppercase" style="border: none;">
        <div class="card-body">
            @if(count($deductiontypes) > 0)
            
            <fieldset>
                <legend>
                    <strong>Standard Deductions</strong>
                </legend>
                <div class="row">
                    <div class="col-md-12">
                        <label>Setup</label>
                        <div class="form-group clearfix m-0">
                            <div class="icheck-primary d-inline">
                                @if($setuptype == '1')
                                <input type="radio" id="deductionsetupauto" class="setuptypeselection" name="deductionsetup" value="0">
                                @else
                                <input type="radio" id="deductionsetupauto" class="setuptypeselection" name="deductionsetup" checked="" value="0">
                                @endif
                              <label for="deductionsetupauto">
                                  Automatic
                              </label>
                            </div>
                            <div class="icheck-primary d-inline">
                                @if($setuptype == '1')
                                <input type="radio" id="deductionsetupmanual" class="setuptypeselection" name="deductionsetup" checked="" value="1">
                                @else
                                <input type="radio" id="deductionsetupmanual" class="setuptypeselection" name="deductionsetup" value="1">
                                @endif
                              <label for="deductionsetupmanual">
                                  Manual
                              </label>
                            </div>
                          </div>
                    </div>
                </div>
                @if($setuptype == '0')
                <div class="row">
                    <div class="col-md-12">
                        <label><small>For PhilHealth Only</small></label>
                        <div class="form-group clearfix m-0">
                            <div class="icheck-primary d-inline">
                                @if($deductionbased == '1')
                                <input type="radio" id="setupdeductiontyperate" class="setupdeductiontype" name="setupdeductiontype" value="0">
                                @else
                                <input type="radio" id="setupdeductiontyperate" class="setupdeductiontype" name="setupdeductiontype" checked="" value="0">
                                @endif
                              <label for="setupdeductiontyperate">
                                  Rate Based
                              </label>
                            </div>
                            <div class="icheck-primary d-inline">
                                @if($deductionbased == '1')
                                <input type="radio" id="setupdeductiontypefixed" class="setupdeductiontype" name="setupdeductiontype" checked="" value="1">
                                @else
                                <input type="radio" id="setupdeductiontypefixed" class="setupdeductiontype" name="setupdeductiontype" value="1">
                                @endif
                              <label for="setupdeductiontypefixed">
                                  Fixed Amount
                              </label>
                            </div>
                          </div>
                    </div>
                </div>
                @endif
                
                @if(count($mycontributions) > 0)
                    <form {{--action="/employeecontributions" method="get"--}}>
                        <div style="width:100%;overflow: scroll">
                            <table class="table"  >
                                <thead class="text-center">
                                    <tr>
                                        <th style="width:23%;">Particulars</th>
                                        <th hidden>Employer's Share / month</th>
                                        <th>Employee's Share / month</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(count($mycontributions)>0)
                                    @foreach ($mycontributions as $mycontribution)
                                        @foreach ($deductiontypes as $deductiontype)
                                            @if($mycontribution->contributionid == $deductiontype->id)
                                                @if($deductiontype->constant == 1)
                                                    <tr class="standarddeductiondetails">
                                                        <td>
                                                            <input type="hidden" name="deductiontypes[]" value="{{$deductiontype->id}}" readonly>
                                                            <button type="button" class="btn btn-warning btn-sm contributionscheckbox" data-toggle="button" aria-pressed="false" autocomplete="off">
                                                                <i class="fa fa-edit"></i>
                                                            </button>
                                                            <label for="checkboxPrimary{{$deductiontype->id}}" style="display: inline">
                                                                {{$deductiontype->description}}
                                                            </label>
                                                        </td>
                                                        <td class="ersamountscontainer" hidden>
                                                            <input type="number" step="any"class="form-control" name="ersamounts[]" value="{{$mycontribution->ersamount}}" placeholder="Employer Share / month" @if($setuptype != '1' ) disabled @endif required/>
                                                        </td>
                                                        <td class="eesamountscontainer">
                                                            <input type="number"  step="any"class="form-control" name="eesamounts[]" value="{{$mycontribution->eesamount}}"placeholder="Employee Share / month" @if($setuptype != '1' ) disabled @endif required/>
                                                        </td>
                                                        <td class="contributionsradioboxcontainer">
                                                            @if($mycontribution->status == '1')
                                                                <div class="icheck-success d-inline mr-3">
                                                                    <input type="radio" id="{{$deductiontype->id}}1" value="1" class="contributionsradiobox" name="contributionstatus{{$deductiontype->id}}[]" disabled="disabled" checked>
                                                                    <label for="{{$deductiontype->id}}1">
                                                                        Active
                                                                    </label>
                                                                </div>
                                                                <div class="icheck-secondary d-inline">
                                                                    <input type="radio" id="{{$deductiontype->id}}2" value="0" class="contributionsradiobox" name="contributionstatus{{$deductiontype->id}}[]"disabled="disabled" >
                                                                    <label for="{{$deductiontype->id}}2">
                                                                        Inactive
                                                                    </label>
                                                                </div>
                                                            @else
                                                                <div class="icheck-success d-inline mr-3">
                                                                    <input type="radio" id="{{$deductiontype->id}}1" value="1" class="contributionsradiobox" name="contributionstatus{{$deductiontype->id}}[]" disabled="disabled">
                                                                    <label for="{{$deductiontype->id}}1">
                                                                        Active
                                                                    </label>
                                                                </div>
                                                                <div class="icheck-secondary d-inline">
                                                                    <input type="radio" id="{{$deductiontype->id}}2" value="0" class="contributionsradiobox" name="contributionstatus{{$deductiontype->id}}[]" disabled="disabled" checked="">
                                                                    <label for="{{$deductiontype->id}}2">
                                                                        Inactive
                                                                    </label>
                                                                </div>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @else
                                                <tr class="standarddeductiondetails">
                                                    <td>
                                                        <input type="hidden" name="deductiontypes[]" value="{{$deductiontype->id}}" readonly>
                                                        <button type="button" class="btn btn-warning btn-sm contributionscheckbox" data-toggle="button" aria-pressed="false" autocomplete="off">
                                                            <i class="fa fa-edit"></i>
                                                        </button>
                                                        <label for="checkboxPrimary{{$deductiontype->id}}" style="display: inline">
                                                            {{$deductiontype->description}}
                                                        </label>
                                                    </td>
                                                    <td class="ersamountscontainer">
                                                        <input type="number" step="any"class="form-control" name="ersamounts[]" value="{{$mycontribution->ersamount}}" placeholder="Employer Share / month" @if($setuptype != '1' ) disabled @endif required/>
                                                    </td>
                                                    <td class="eesamountscontainer">
                                                        <input type="number"  step="any"class="form-control" name="eesamounts[]" value="{{$mycontribution->eesamount}}"placeholder="Employee Share / month" @if($setuptype != '1' ) disabled @endif required/>
                                                    </td>
                                                    <td class="contributionsradioboxcontainer">
                                                        @if($mycontribution->status == '1')
                                                            <div class="icheck-success d-inline mr-3">
                                                                <input type="radio" id="{{$deductiontype->id}}1" value="1" class="contributionsradiobox" name="contributionstatus{{$deductiontype->id}}[]" disabled="disabled" checked>
                                                                <label for="{{$deductiontype->id}}1">
                                                                    Active
                                                                </label>
                                                            </div>
                                                            <div class="icheck-secondary d-inline">
                                                                <input type="radio" id="{{$deductiontype->id}}2" value="0" class="contributionsradiobox" name="contributionstatus{{$deductiontype->id}}[]"disabled="disabled" >
                                                                <label for="{{$deductiontype->id}}2">
                                                                    Inactive
                                                                </label>
                                                            </div>
                                                        @else
                                                            <div class="icheck-success d-inline mr-3">
                                                                <input type="radio" id="{{$deductiontype->id}}1" value="1" class="contributionsradiobox" name="contributionstatus{{$deductiontype->id}}[]" disabled="disabled">
                                                                <label for="{{$deductiontype->id}}1">
                                                                    Active
                                                                </label>
                                                            </div>
                                                            <div class="icheck-secondary d-inline">
                                                                <input type="radio" id="{{$deductiontype->id}}2" value="0" class="contributionsradiobox" name="contributionstatus{{$deductiontype->id}}[]" disabled="disabled" checked="">
                                                                <label for="{{$deductiontype->id}}2">
                                                                    Inactive
                                                                </label>
                                                            </div>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endif
                                            @endif
                                        @endforeach
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="row  mt-3">
                            <div class="col-12 updatecontributionbutton">
                                <button type="button" class="btn btn-warning updatecontributionsbuttonstandard float-right">Update</button>
                            </div>
                        </div>
                    </form>
                @endif
            </fieldset>
            <br>
            <br>
            @endif
            <fieldset>
                <legend>
                    <strong>Other Deductions</strong>
                </legend>
                <div class="row">
                    <div class="col-md-4 col-12">
                        <button type="button" class="btn btn-sm text-success float-left" id="adddeduction" clicked="0">
                            <i class="fa fa-plus"></i>&nbsp; Add
                        </button>
                        <br>
                        <br>
                        <form {{--action="/employeeotherdeductionsinfo" method="get" name="otherdeductionform"--}}>
                            {{-- <input type="hidden" name="employeeid" value="{{$profileinfoid}}"> --}}
                            <div class="adddeductioncontainer">
                            </div>
                        </form>
                    </div>
                    <div class="col-md-8 col-12">
                        <table class="table table-bordered" style="font-size: 14px;">
                            <thead>
                                <tr>
                                    <th>
                                        Details
                                    </th>
                                    <th style="width:18%">
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="deductiondetails">
                                @foreach ($myotherdeductions as $myotherdeduction)
                                    <tr>
                                        <td>
                                            Description: {{$myotherdeduction->description}}
                                            <br>
                                            Amount:  &#8369; {{$myotherdeduction->amount}}
                                            <br>
                                            Payable for: {{$myotherdeduction->term}} month/s
                                            <br>
                                            Start date: {{$myotherdeduction->dateissued}}
                                            <br>
                                            Status: 
                                            <span class="updateotherdeductionstatus" currentstatus="{{$myotherdeduction->status}}" otherdeductionid="{{$myotherdeduction->id}}" otherdeductiondescription="{{$myotherdeduction->description}}">
                                                @if($myotherdeduction->status == 1)
                                                    <span class="right badge badge-success">Active</span>
                                                @else
                                                    <span class="right badge badge-secondary">Inactive</span>
                                                @endif
                                                
                                            </span>
                                        </td>
                                        <td class="p-1" valign="middle deductiondetailbuttonscontainer" style="vertical-align: middle !important;text-align: center;">
                                            @if($myotherdeduction->paid == 0)
                                                <button type="button" class="btn btn-sm btn-warning editdeductiondetail p-1 m-0" data-toggle="modal" data-target="#editotherdeductiondetail{{$myotherdeduction->id}}">Edit</button>
                                                <div id="editotherdeductiondetail{{$myotherdeduction->id}}" class="modal custom-modal fade" role="dialog" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                                                    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title"><strong>Other Deduction</strong></h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">×</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body text-left">
                                                                <form {{--action="/employeeotherdeductionsinfoedit" method="get"--}}>
                                                                    <input type="hidden" name="otherdeductionid" value="{{$myotherdeduction->id}}">
                                                                    <label>Description</label>
                                                                    <input type="text" class="form-control  mb-2" name="description" value="{{$myotherdeduction->description}}">
                                                                    <label>Amount</label>
                                                                    <input type="text" class="form-control mb-2" name="amount"  lang="en-150" value="{{$myotherdeduction->amount}}">
                                                                    <label>Term (No. of months)</label>
                                                                    <input type="number" step="0.01" class="form-control mb-2" name="term" value="{{$myotherdeduction->term}}">
                                                                    <br>
                                                                    
                                                                    Status:
                                                                    <div class="form-group clearfix">
                                                                        <div class="icheck-primary d-inline">
                                                                          <input type="radio" id="otherdeduction{{$myotherdeduction->id}}1" name="status" value="1" @if($myotherdeduction->status == 1) checked @endif>
                                                                          <label for="otherdeduction{{$myotherdeduction->id}}1">
                                                                            ACTIVE
                                                                          </label>
                                                                        </div>
                                                                        <div class="icheck-primary d-inline">
                                                                          <input type="radio" id="otherdeduction{{$myotherdeduction->id}}2" name="status" value="0" @if($myotherdeduction->status < 1) checked @endif>
                                                                          <label for="otherdeduction{{$myotherdeduction->id}}2">
                                                                            INACTIVE
                                                                          </label>
                                                                        </div>
                                                                      </div>
                                                                    <div class="submit-section">
                                                                        <button type="button" class="btn btn-primary submit-btn float-right editotherdeductionbutton" data-dismiss="modal">Update</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button  type="button"class="btn btn-sm btn-danger deletedeductiondetail p-1 m-0" data-toggle="modal" data-target="#deletedotherdeductiondetail{{$myotherdeduction->id}}">Delete</button>
                                                <div id="deletedotherdeductiondetail{{$myotherdeduction->id}}" class="modal custom-modal fade" role="dialog" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                                                    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title"><strong>Other Deduction</strong></h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">×</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <h3 class="text-danger">Are you sure you want to delete this other deduction item ?</h3>
                                                                <br>
                                                                <form {{--action="/employeeotherdeductionsinfodelete" method="get"--}}>
                                                                    <label>Description</label>
                                                                    <input type="text" class="form-control  mb-2" name="description" value="{{$myotherdeduction->description}}" disabled>
                                                                    <label>Amount</label>
                                                                    <input type="text" class="form-control mb-2" name="amount"   value="{{$myotherdeduction->amount}}" disabled>
                                                                    <label>Term (No. of months)</label>
                                                                    <input type="number" class="form-control mb-2" name="term" value="{{$myotherdeduction->term}}" disabled>
                                                                    <br>
                                                                    <div class="submit-section">
                                                                        <button type="button" class="btn btn-primary submit-btn float-right deletedotherdeduction"data-dismiss="modal" id="{{$myotherdeduction->id}}">Delete</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @elseif($myotherdeduction->paid == 1)
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
<script>
    
        // ------------------------------------------------------------------------------------ DEDUCTIONS
        $('.contributionscheckbox').on('click', function(){
            if($(this).hasClass('active') == false){
                //activeradio
                $(this).addClass('active');
                $(this).removeClass('bg-warning');
                $(this).addClass('bg-secondary');
                // console.log( $(this).closest('.standarddeductiondetails').find('.ersamountscontainer'))
                $(this).closest('.standarddeductiondetails').find('input[name="deductiontypes[]"]').attr('disabled',false)
                $(this).closest('.standarddeductiondetails').find('.contributionsradioboxcontainer')[0].children[0].children[0].readOnly = false;
                //inactiveradio
                $(this).closest('.standarddeductiondetails').find('.contributionsradioboxcontainer')[0].children[1].children[0].disabled = false;
                $(this).closest('.standarddeductiondetails').find('.contributionsradioboxcontainer')[0].children[0].children[0].disabled = false;
                if('{{$setuptype}}' == 1)
                {
                //ersinput
                $(this).closest('tr').find('input[name="ersamounts[]"]').prop('disabled', false)
                //eesinput
                $(this).closest('tr').find('input[name="eesamounts[]"]').prop('disabled', false)
                }
                else{
                //ersinput
                $(this).closest('tr').find('input[name="ersamounts[]"]').prop('disabled', true)
                //eesinput
                $(this).closest('tr').find('input[name="eesamounts[]"]').prop('disabled', true)
                
                $(this).closest('.standarddeductiondetails').find('.contributionsradioboxcontainer input').prop('disabled', false);
                }
                
            }
            else if($(this).hasClass('active') == true){
                $(this).removeClass('active')
                $(this).addClass('bg-warning');
                $(this).removeClass('bg-secondary');
                // $('input').attr('readonly',true)
                $(this).closest('.standarddeductiondetails').find('input[name="deductiontypes[]"]').attr('disabled',true)
                $(this).closest('.standarddeductiondetails').find('.contributionsradioboxcontainer')[0].children[0].children[0].readOnly = true;
                
                $(this).closest('.standarddeductiondetails').find('.contributionsradioboxcontainer')[0].children[1].children[0].disabled = true;
                $(this).closest('.standarddeductiondetails').find('.contributionsradioboxcontainer')[0].children[0].children[0].disabled = true;
                //ersinput
                $(this).closest('.standarddeductiondetails').find('.ersamountscontainer')[0].children[0].readOnly = true;
                //eesinput
                $(this).closest('.standarddeductiondetails').find('.eesamountscontainer')[0].children[0].readOnly = true;
            }
        })
        // ===========================================================================
            // Deduction Details 
        // ===========================================================================
        $('.adddeductioncontainer').empty();
        var adddeductiondetailrow = 0;
        
        $("#adddeduction").unbind().click(function() {
            if(adddeductiondetailrow == 0){
                $('.adddeductioncontainer').append(
                    '<div class="card" style="border:none;">'+
                        '<button type="button" class="btn btn-block btn-success saveotherdeductionbutton">Save</button>'+
                    '</div>'
                );
                $('.adddeductioncontainer').prepend(
                    '<div class="card" style="border:none;">'+
                        '<div class="card-header">'+
                            '<div class="card-tools">'+
                                '<button type="button" class="btn btn-tool removedeductioncard" data-card-widget="remove">'+
                                    '<i class="fas fa-times"></i>'+
                                '</button>'+
                            '</div>'+
                        '</div>'+
                        '<div class="card-body">'+
                        
                            '<small><strong>Description</strong></small>'+
                            '<input type="text" name="description[]" class="form-control form-control-sm mb-2" placeholder="Description" required/>'+
                        
                            '<small><strong>Total Amount</strong></small>'+
                            '<input type="number" name="totalamount[]" class="form-control form-control-sm mb-2" placeholder="Total Amount" required/>'+

                            '<small><strong>Payable for (no. of months)</strong></small>'+
                            '<input type="number" name="term[]" class="form-control form-control-sm mb-2" placeholder="No. of months" required/>'+
                        '<small><strong>Start date</strong></small>'+
                        '<input type="date" name="startdates[]" class="form-control form-control-sm" required/>'+
                        '</div>'+
                    '</div>'
                );
                adddeductiondetailrow+=1;
            }
            else if(adddeductiondetailrow > 0){
                $('.adddeductioncontainer').prepend(
                    '<div class="card" style="border:none;">'+
                        '<div class="card-header">'+
                            '<div class="card-tools">'+
                                '<button type="button" class="btn btn-tool removedeductioncard" data-card-widget="remove">'+
                                    '<i class="fas fa-times"></i>'+
                                '</button>'+
                            '</div>'+
                        '</div>'+
                        '<div class="card-body">'+
                        
                        '<small><strong>Description</strong></small>'+
                        '<input type="text" name="description[]" class="form-control form-control-sm mb-2" placeholder="Description" required/>'+
                    
                        '<small><strong>Total Amount</strong></small>'+
                        '<input type="number" name="totalamount[]" class="form-control form-control-sm mb-2" placeholder="Total Amount" required/>'+

                        '<small><strong>Payable for (no. of months)</strong></small>'+
                        '<input type="number" name="term[]" class="form-control form-control-sm" placeholder="No. of moupdatecontributionsbuttonstandardnths" required/>'+

                        '<small><strong>Start date</strong></small>'+
                        '<input type="date" name="startdates[]" class="form-control form-control-sm" required/>'+
                            // '<small><strong>Select deduction type</strong></small>'+
                            // '<small><strong>Enter Amount</strong></small>'+
                            // '<input type="number" name="amount[]" class="form-control form-control-sm" placeholder="Amount" required/>'+
                        '</div>'+
                    '</div>'
                );
                adddeductiondetailrow+=1;
            }
        });
        $(document).on('click','.removedeductioncard', function(){
            adddeductiondetailrow-=1;
            if(adddeductiondetailrow == 0){
                $('.adddeductioncontainer').empty();
            }
        })
        
        $('.updatecontributionsbuttonstandard').hide()
        $(document).on('input','input[name="ersamounts[]"]', function(){

            $('.updatecontributionsbuttonstandard').show();
        })
        $(document).on('input','input[name="eesamounts[]"]', function(){

            $('.updatecontributionsbuttonstandard').show();
        })
        $(document).on('change','input[class="contributionsradiobox"]', function(){

            $('.updatecontributionsbuttonstandard').show();
        })
        $('.updatecontributionsbuttonstandard').on('click', function(){
            var deductiontypes = [];
            var ersamounts = [];
            var eesamounts = [];
            var contributionstatus = [];
            var emptyelements = [];
            $('input[name="deductiontypes[]"]').each(function(){
                $(this).css('border','1px solid #ddd')
                if($(this).val().replace(/^\s+|\s+$/g, "").length == 0)
                {
                    emptyelements.push($(this))
                }else{

                    deductiontypes.push($(this).val())
                }
            })
            $('input[name="ersamounts[]"]').each(function(){
                $(this).css('border','1px solid #ddd')
                if($(this).val().replace(/^\s+|\s+$/g, "").length == 0)
                {
                    emptyelements.push($(this))
                }else{

                    ersamounts.push($(this).val())
                }
            })
            $('input[name="eesamounts[]"]').each(function(){
                $(this).css('border','1px solid #ddd')
                if($(this).val().replace(/^\s+|\s+$/g, "").length == 0)
                {
                    emptyelements.push($(this))
                }else{

                    eesamounts.push($(this).val())
                }
            })
            $('input.contributionsradiobox[type="radio"]:checked').each(function(){
                $(this).css('border','1px solid #ddd')
                contributionstatus.push($(this).val())
            })
            if(emptyelements.length == 0)
            {
                $.ajax({
                    url: "/hr/employees/profile/tabdeductions/updatedeductions",
                    type: "GET",
                    data: {
                        employeeid:   '{{$profileinfoid}}',
                        deductiontypes  :   deductiontypes,
                        ersamounts :   ersamounts,
                        eesamounts :   eesamounts,
                        contributionstatus   :   contributionstatus
                        },
                    success: function (data) {
                        // $('#profilepic').attr('src',data)
                        toastr.success('Standard deductions updated successfully!')
                        $('#custom-content-above-tabContent').empty()
                        $('#custom-content-above-contributions-tab').click()
                    }
                });
            }else{
                $.each(emptyelements,function(){
                    $(this).css('border','1px solid red')
                })
            }

        })
        // $(document).on('click','input[name="deductionsetup"]', function(){
        $("input[name='deductionsetup']").unbind().click(function() {
            var setuptype = $(this).val()
            $.ajax({
                url: "/hr/employees/profile/tabdeductions/updatesetuptype",
                type: "GET",
                data: {
                    employeeid:   '{{$profileinfoid}}',
                    setuptype  :   setuptype,
                    },
                success: function (data) {
                    // $('#profilepic').attr('src',data)
                    toastr.success('Setup updated successfully!', 'Deduction Setup Type')
                    $('#custom-content-above-tabContent').empty()
                    $('#custom-content-above-contributions-tab').click()
                }
            });
        })
        $("input[name='setupdeductiontype']").unbind().click(function() {
            var setupdeductiontype = $(this).val()
            $.ajax({
                url: "/hr/employees/profile/tabdeductions/updatesetuptype",
                type: "GET",
                data: {
                    employeeid:   '{{$profileinfoid}}',
                    setupdeductiontype  :   setupdeductiontype,
                    },
                success: function (data) {
                    // $('#profilepic').attr('src',data)
                    toastr.success('Setup updated successfully!', 'Deduction Setup Type')
                    $('#custom-content-above-tabContent').empty()
                    $('#custom-content-above-contributions-tab').click()
                }
            });
        })
        
        $(document).on('click','.saveotherdeductionbutton', function(){
            var description = [];
            var totalamount = [];
            var term = [];
            var startdates = [];
            var emptyelements = [];
            $('input[name="description[]"]').each(function(){
                $(this).css('border','1px solid #ddd')
                if($(this).val().replace(/^\s+|\s+$/g, "").length == 0)
                {
                    emptyelements.push($(this))
                }else{

                    description.push($(this).val())
                }
            })
            $('input[name="totalamount[]"]').each(function(){
                $(this).css('border','1px solid #ddd')
                if($(this).val().replace(/^\s+|\s+$/g, "").length == 0)
                {
                    emptyelements.push($(this))
                }else{

                    totalamount.push($(this).val())
                }
            })
            $('input[name="term[]"]').each(function(){
                $(this).css('border','1px solid #ddd')
                if($(this).val().replace(/^\s+|\s+$/g, "").length == 0)
                {
                    emptyelements.push($(this))
                }else{

                    term.push($(this).val())
                }
            })
            $('input[name="startdates[]"]').each(function(){
                $(this).css('border','1px solid #ddd')
                if($(this).val().replace(/^\s+|\s+$/g, "").length == 0)
                {
                    emptyelements.push($(this))
                }else{

                    startdates.push($(this).val())
                }
            })
            
            if(emptyelements.length == 0)
            {
                $.ajax({
                    url: "/hr/employees/profile/tabdeductions/adddeduction",
                    type: "GET",
                    data: {
                        employeeid:   '{{$profileinfoid}}',
                        description:description,
                        totalamount:totalamount,
                        term:term,
                        startdates:startdates
                        },
                    success: function (data) {
                        // $('#profilepic').attr('src',data)
                        toastr.success('Added successfully!', 'Other deductions')
                        $('#custom-content-above-tabContent').empty()
                        $('#custom-content-above-contributions-tab').click()
                    }
                });
            }else{
                $.each(emptyelements,function(){
                    $(this).css('border','1px solid red')
                })
            }
        })
        $('.editotherdeductionbutton').unbind().click(function(){
            var thismodal = $(this).closest('.modal')
            var otherdeductionid = $(this).closest('form').find('input[name="otherdeductionid"]').val() ;
            var description = $(this).closest('form').find('input[name="description"]').val() ;
            var amount = $(this).closest('form').find('input[name="amount"]').val() ;
            var term = $(this).closest('form').find('input[name="term"]').val() ;
            var status = $(this).closest('form').find('input[name="status"]:checked').val() ;
            
            var emptyelements =0;

            if(description.replace(/^\s+|\s+$/g, "").length == 0)
            {
                $(this).css('border','1px solid red')
                emptyelements+=1;
            }else{
                $(this).css('border','1px solid #ddd')
            }
            if(amount.replace(/^\s+|\s+$/g, "").length == 0)
            {
                $(this).css('border','1px solid red')
                emptyelements+=1;
            }else{
                $(this).css('border','1px solid #ddd')
            }
            if(term.replace(/^\s+|\s+$/g, "").length == 0)
            {
                $(this).css('border','1px solid red')
                emptyelements+=1;
            }else{
                $(this).css('border','1px solid #ddd')
            }
            if(emptyelements == 0)
            {
                // console.log(amount)
                $.ajax({
                    url: "/hr/employees/profile/tabdeductions/editdeduction",
                    type: "GET",
                    data: {
                        employeeid:   '{{$profileinfoid}}',
                        otherdeductionid:otherdeductionid,
                        description:description,
                        amount:amount,
                        term:term,
                        status:status
                        },
                    success: function (data) {
                        // $('#profilepic').attr('src',data)
                        toastr.success('Added successfully!', 'Other deductions')
                        $('#custom-content-above-contributions-tab').unbind().click()
                        $('body').removeClass('modal-open');
                        $('.thismodal').removeClass('show');
                        $('.thismodal').removeAttr('style');
                        $('.thismodal').css('display','none');
                        $('.modal-backdrop').removeClass('show')
                        $('.modal-backdrop').remove()
                    }
                });
            }

        })
        $(document).on('click','.deletedotherdeduction', function(){
            var otherdeductionid = $(this).attr('id')
            console.log(otherdeductionid)
            $.ajax({
                url: "/hr/employees/profile/tabdeductions/deletededuction",
                type: "GET",
                data: {
                    employeeid:   '{{$profileinfoid}}',
                    otherdeductionid:otherdeductionid
                    },
                success: function (data) {
                    // $('#profilepic').attr('src',data)
                    toastr.success('Deleted successfully!', 'Other deductions')
                    $('#custom-content-above-contributions-tab').click()
                }
            });
        })
</script>