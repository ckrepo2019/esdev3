
            <div class="row">
                
                @if($firstemployee[0]->employee_info[0]->released == 0)
                    <div class="col-md-12">
                        <form action="/employeesalaryupdate" method="get" class="m-0 p-0">
                            @csrf
                            <input type="hidden" name="employeeid" value="{{$firstemployee[0]->employee_info[0]->id}}">
                            <input type="hidden" name="payrollid" value="{{$payrolldate[0]->id}}">
                            <input type="hidden" name="netpay" value="{{$firstemployee[0]->netsalary}}" />
                            <input type="hidden" name="basicpay" value="{{$firstemployee[0]->basicpay}}"/>
                            <input type="hidden" name="ratetype" value="{{$firstemployee[0]->ratetype}}"/>
                            <input type="hidden" name="projectbasedtype" />

                            <input type="hidden" name="attendancesalary" value="{{$firstemployee[0]->attendancesalary}}"/>

                            <input type="hidden" name="numofabsentdays" value="{{$firstemployee[0]->numberofabsent}}"/>
                            <input type="hidden" name="absentdeduction" value="{{$firstemployee[0]->absentdeduction}}"/>

                            <input type="hidden" name="totalearnings" value="{{$firstemployee[0]->totalearnings}}"/>
                            <input type="hidden" name="totaldeductions" value="{{$firstemployee[0]->totaldeductions}}"/>
                            <input type="hidden" name="overtimepay" value="{{$firstemployee[0]->overtimepay}}"/>
                            <input type="hidden" name="holidaypay" value="{{$firstemployee[0]->holidaypay}}"/>
                            <input type="hidden" name="holidayovertimepay" value="{{$firstemployee[0]->holidayovertimepay}}"/>
                            
                            <input type="hidden" name="leavesnumdays" value="{{$firstemployee[0]->leavesnumdays}}"/>
                            <input type="hidden" name="leaveid" value="{{$firstemployee[0]->leaveid}}"/>
                            <input type="hidden" name="earnedleaves" value="{{$firstemployee[0]->leavesearn}}"/>
                            <input type="hidden" name="deductedleaves" value="{{$firstemployee[0]->leavesdeduct}}"/>
                            <input type="hidden" name="tardiness" value="{{$firstemployee[0]->latedeductions}}"/>
                            <input type="hidden" name="exists" value="{{$firstemployee[0]->employee_info[0]->payrollhistoryrecord}}"/>
                            <div id="formcontainer"></div>       
                            @if($firstemployee[0]->employee_info[0]->payrollhistoryrecord == 0)                 
                                <button type="submit" class="btn btn-block btn-primary" id="payemployeesalary"><i class="fa fa-save"></i> Save</button>
                            @else
                                <button type="submit" class="btn btn-block btn-primary" id="payemployeesalary"><i class="fa fa-edit"></i> Update</button>
                            @endif
                        </form>
                    </div>
                    {{-- <div class="col-md-6">
                    
                        @if($firstemployee[0]->employee_info[0]->payrollhistoryrecord == 0)
                            <button type="button" class="btn btn-block btn-warning" disabled>Pay</button>
                        @else
                            <form action="/payrollgenerateslip" method="get" class="m-0 p-0" target="_blank" id="employeesalarypaysubmit">
                                <input name="employeeid" type="hidden" value="{{$firstemployee[0]->employee_info[0]->id}}">
                                <input name="payrolldateid" type="hidden" value="{{$payrolldate[0]->id}}">
                                <input name="action" type="hidden" value="generatepayslip">
                            </form>
                            <button type="button" class="btn btn-block btn-warning"  id="employeesalarypaybutton" employeename="{{$firstemployee[0]->employee_info[0]->firstname}} {{$firstemployee[0]->employee_info[0]->middlename[0]}}. {{$firstemployee[0]->employee_info[0]->lastname}} {{$firstemployee[0]->employee_info[0]->suffix}}">Pay</button>
                        @endif
                    </div> --}}
                @else
                <div class="col-12 bg-info">
                    <small>
                        Released. Please proceed to the payroll summary to view details.
                    </small>
                </div>
                    {{-- <div class="col-md-12">
                        <div class="col-12 bg-info">
                            <small>
                                To view the summary of the salary, click the button below.
                            </small>
                        </div>
                        <br>
                        <form action="/payrollgenerateslip" method="get" target="_blank">
                            <input name="employeeid" type="hidden" value="{{$firstemployee[0]->employee_info[0]->id}}">
                            <input name="payrolldateid" type="hidden" value="{{$payrolldate[0]->id}}">
                            <input name="action" type="hidden" value="generatepayslip">
                            <button type="submit" class="btn btn-block btn-secondary"><i class="fa fa-print"></i> Print</button>
                        </form>
                    </div> --}}
                @endif
            </div>