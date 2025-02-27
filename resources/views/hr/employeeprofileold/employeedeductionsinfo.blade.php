{{-- 
@if(session()->has('linkid'))
    @if( session()->get('linkid') == 'custom-content-above-contributions') --}}
        <div class="tab-pane fade show active" id="custom-content-above-contributions" role="tabpanel" aria-labelledby="custom-content-above-contributions-tab">
    {{-- @else
        <div class="tab-pane fade" id="custom-content-above-contributions" role="tabpanel" aria-labelledby="custom-content-above-contributions-tab">
    @endif
@else
    <div class="tab-pane fade" id="custom-content-above-contributions" role="tabpanel" aria-labelledby="custom-content-above-contributions-tab">
@endif --}}
        <div class="card text-uppercase">
            <div class="card-body">
                @if(count($deductiontypes) > 0)
                
                <fieldset>
                    <legend>
                        <strong>Standard Deductions</strong>
                    </legend>
                    <label>Setup</label>
                    <div class="form-group clearfix">
                        <div class="icheck-primary d-inline">
                          <input type="radio" id="deductionsetupauto" name="deductionsetup" checked="">
                          <label for="deductionsetupauto">
                              Automatic
                          </label>
                        </div>
                        <div class="icheck-primary d-inline">
                          <input type="radio" id="deductionsetupmanual" name="deductionsetup">
                          <label for="deductionsetupmanual">
                              Manual
                          </label>
                        </div>
                      </div>
                    <br>
                    
                    @if(count($mycontributions) > 0)
                        <form action="/employeecontributions" method="get">
                            <input type="hidden" name="employeeid" value="{{$profileinfoid}}">
                            <input type="hidden" class="form-control" name="linkid" value="custom-content-above-contributions" />
                            <div style="width:100%;overflow: scroll">
                                <table class="table"  >
                                    <thead class="text-center">
                                        <tr>
                                            <th style="width:23%;">Particulars</th>
                                            <th>Employer's Share / month</th>
                                            <th>Employee's Share / month</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
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
                                                            <td class="ersamountscontainer">
                                                                <input type="hidden" step="any"class="form-control" name="ersamounts[]" value="{{$mycontribution->ersamount}}" placeholder="Employer Share / month"/>
                                                                <input type="number" step="any"class="form-control" name="ersamounts[]" value="{{$mycontribution->ersamount}}" placeholder="Employer Share / month" disabled required/>
                                                            </td>
                                                            <td class="eesamountscontainer">
                                                                <input type="hidden"  step="any"class="form-control" name="eesamounts[]" value="{{$mycontribution->eesamount}}"placeholder="Employee Share / month"/>
                                                                <input type="number"  step="any"class="form-control" name="eesamounts[]" value="{{$mycontribution->eesamount}}"placeholder="Employee Share / month" disabled required/>
                                                            </td>
                                                            <td class="contributionsradioboxcontainer">
                                                                @if($mycontribution->status == '1')
                                                                    <div class="icheck-success d-inline mr-3">
                                                                        <input type="radio" id="{{$deductiontype->id}}1" value="active" class="contributionsradiobox" name="contributionstatus{{$deductiontype->id}}" disabled="disabled" checked>
                                                                        <label for="{{$deductiontype->id}}1">
                                                                            Active
                                                                        </label>
                                                                    </div>
                                                                    <div class="icheck-secondary d-inline">
                                                                        <input type="radio" id="{{$deductiontype->id}}2" value="inactive" class="contributionsradiobox" name="contributionstatus{{$deductiontype->id}}"disabled="disabled" >
                                                                        <label for="{{$deductiontype->id}}2">
                                                                            Inactive
                                                                        </label>
                                                                    </div>
                                                                @else
                                                                    <div class="icheck-success d-inline mr-3">
                                                                        <input type="radio" id="{{$deductiontype->id}}1" value="active" class="contributionsradiobox" name="contributionstatus{{$deductiontype->id}}" disabled="disabled">
                                                                        <label for="{{$deductiontype->id}}1">
                                                                            Active
                                                                        </label>
                                                                    </div>
                                                                    <div class="icheck-secondary d-inline">
                                                                        <input type="radio" id="{{$deductiontype->id}}2" value="inactive" class="contributionsradiobox" name="contributionstatus{{$deductiontype->id}}" disabled="disabled" checked="">
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
                                    </tbody>
                                </table>
                            </div>
                            <div class="row  mt-3">
                                <div class="col-12 updatecontributionbutton">
                                    <button type="submit" class="btn btn-warning updatecontributionsbuttonstandard float-right">Update</button>
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
                            <form action="/employeeotherdeductionsinfo" method="get" name="otherdeductionform">
                                <input type="hidden" name="employeeid" value="{{$profileinfoid}}">
                                <input type="hidden" class="form-control" name="linkid" value="custom-content-above-contributions" />
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
                                                Date issued: {{$myotherdeduction->dateissued}}
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
                                                                <div class="modal-body">
                                                                    <form action="/employeeotherdeductionsinfoedit" method="get">
                                                                        <input type="hidden" name="employeeid" value="{{$profileinfoid}}">
                                                                        <input type="hidden" name="otherdeductionid" value="{{$myotherdeduction->id}}">
                                                                        <input type="hidden" class="form-control" name="linkid" value="custom-content-above-contributions" />
                                                                        <label>Description</label>
                                                                        <input type="text" class="form-control  mb-2" name="description" value="{{$myotherdeduction->description}}">
                                                                        <label>Amount</label>
                                                                        <input type="text" class="form-control mb-2" name="amount"  lang="en-150" value="{{$myotherdeduction->amount}}">
                                                                        <label>Term (No. of months)</label>
                                                                        <input type="number" step="0.01" class="form-control mb-2" name="term" value="{{$myotherdeduction->term}}">
                                                                        <br>
                                                                        <div class="submit-section">
                                                                            <button type="submit" class="btn btn-primary submit-btn float-right">Update</button>
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
                                                                    <form action="/employeeotherdeductionsinfodelete" method="get">
                                                                        <input type="hidden" name="employeeid" value="{{$profileinfoid}}">
                                                                        <input type="hidden" name="otherdeductionid" value="{{$myotherdeduction->id}}">
                                                                        <input type="hidden" class="form-control" name="linkid" value="custom-content-above-contributions" />
                                                                        <label>Description</label>
                                                                        <input type="text" class="form-control  mb-2" name="description" value="{{$myotherdeduction->description}}" disabled>
                                                                        <label>Amount</label>
                                                                        <input type="text" class="form-control mb-2" name="amount"  lang="en-150" value="{{$myotherdeduction->amount}}" disabled>
                                                                        <label>Term (No. of months)</label>
                                                                        <input type="number" class="form-control mb-2" name="term" value="{{$myotherdeduction->term}}" disabled>
                                                                        <br>
                                                                        <div class="submit-section">
                                                                            <button type="submit" class="btn btn-primary submit-btn float-right">Update</button>
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
@if(session()->has('linkid'))
    @if( session()->get('linkid') == 'custom-content-above-contributions')
        </div>
    @else
        </div>
    @endif
@else
    </div>
@endif