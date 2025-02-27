<style>
    @page {
        margin: 30px;
    }
    * {
        font-family: Arial, Helvetica, sans-serif;
    }
    table {
        border-collapse: collapse;
    }
</style>
<table style="width: 100%;">
    <tr>
        <td style="width: 7%; vertical-align: top;" rowspan="3"> <img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" width="70px" style="display: inline;"></td>
        <td style="font-size: 12px; vertical-align: top;">
            {{DB::table('schoolinfo')->first()->schoolname}}<br/>
            {{DB::table('schoolinfo')->first()->address}}
        </td>
    </tr>
    <tr>
        <td style="font-size: 12px; font-weight: bold; vertical-align: top;">
            PAYROLL
        </td>
    </tr>
    <tr>
        <td style="font-size: 12px; vertical-align: top;">
            {{date('M d, Y', strtotime($payrolldates[0]->datefrom))}} - {{date('M d, Y', strtotime($payrolldates[0]->dateto))}}
        </td>
    </tr>
</table>
@php
$headers = collect($employees)->pluck('header')->toArray();
@endphp
<div style="width: 100%">
    <table style="width: 100%; font-size: 12px !important;" border="1">
        <tr>
            <th rowspan="2" style="width: 3%;">NO</th>
            <th rowspan="2" style="width: 20%;">Name</th>
            <th rowspan="2" style="width: 7%;">Basic Salary</th>
            <th rowspan="2" style="width: 7%;">Absences/<br/>Tardiness</th>
            <th rowspan="2" style="width: 7%;">Birthday/<br/>Holiday</th>
            <th rowspan="2" style="width: 7%;">Gross Salary<br/>Pay</th>
            <th rowspan="2" style="width: 7%;">Total Allowances</th>
            @if(count($standarddeductions)>0)
            <th colspan="{{count($standarddeductions)}}" style="width: 25%;">STANDARD DEDUCTIONS</th>
            @endif
            <th rowspan="2" style="width: 7%;">Total Deductions</th>
            <th rowspan="2" style="width: 7%;">Net Pay</th>
            <th rowspan="2" style="width: 7%;">Signature</th>
            <th rowspan="2" style="width: 2%;"></th>
        </tr>
        @if(count($standarddeductions)>0)
        <tr>
            @foreach($standarddeductions as $standarddeduction)
                <th>{{$standarddeduction->description}}</th>
            @endforeach
        </tr>
        @endif

        @if(count($employees)>0)
            @php
            
            @endphp
            @foreach($employees as $key => $employee)
                <tr>
                    <td style="text-align: center;">{{$key+1}}</td>
                    <td>{{$employee->lastname}}, {{$employee->firstname}} @if($employee->middlename != null){{$employee->middlename[0]}}.@endif {{$employee->suffix}}</td>
                    <td style="text-align: right;">{{number_format($employee->header->basicsalaryamount,2,'.',',')}}</td>
                    <td style="text-align: right;">{{number_format($employee->header->lateamount,2,'.',',')}}</td>
                    <td></td>
                    <td></td>
                    <td style="text-align: right;">{{number_format(collect($employee->particulars)->whereIn('particulartype',[3,4])->sum('amountpaid')+collect($employee->addedparticulars)->where('type',1)->sum('amount'),2,'.',',')}}</td>
                    @if(count($standarddeductions)>0)
                        @foreach($standarddeductions as $standarddeduction)
                            <td style="text-align: right;">
                                @if(collect($employee->particulars)->where('particulartype','1')->where('particularid', $standarddeduction->id)->count() >0)
                                    {{number_format(collect($employee->particulars)->where('particulartype','1')->where('particularid', $standarddeduction->id)->first()->amountpaid,2,'.',',')}}
                                @endif
                            </td>
                        @endforeach
                    @endif
                    <td style="text-align: right;">{{number_format(collect($employee->particulars)->whereIn('particulartype',[1,2])->sum('amountpaid')+collect($employee->addedparticulars)->where('type',2)->sum('amount'),2,'.',',')}}</td>
                    <td style="text-align: right;">{{number_format($employee->header->netsalary,2,'.',',')}}</td>
                    <td></td>
                    <td></td>
                </tr>
            @endforeach
            <tr style="font-weight: bold;">
                <td colspan="2" style="text-align: right;">TOTAL</td>
                <td style="text-align: right;">{{number_format(collect($headers)->sum('basicsalaryamount'),2,'.',',')}}</td>
                <td style="text-align: right;">{{number_format(collect($headers)->sum('lateamount'),2,'.',',')}}</td>
                <td style="text-align: center;"></td>
                <td style="text-align: center;"></td>
                <td style="text-align: right;">
                    {{number_format(collect($employees)->sum('totalstandardallowances')+collect($employees)->sum('totaladdedallowances'),2,'.',',')}}
                </td>
                @if(count($standarddeductions)>0)
                    @foreach($standarddeductions as $standarddeduction)
                        <td style="text-align: right;">
                            @php
                                $totaldeduction = 0;
                                foreach($employees as $employee)
                                {
                                    if(count($employee->particulars)>0)
                                    {
                                        foreach($employee->particulars as $eachpart)
                                        {
                                            if($eachpart->particulartype == 1 && $eachpart->particularid == $standarddeduction->id)
                                            {
                                                $totaldeduction+=$eachpart->amountpaid;
                                            }
                                        }
                                    }
                                }
                            @endphp
                            @if($totaldeduction>0)
                            {{number_format($totaldeduction,2,'.',',')}}
                            @endif
                        </td>
                    @endforeach
                @endif
                <td style="text-align: right;">
                    {{number_format(collect($employees)->sum('totalstandarddeductions')+collect($employees)->sum('totaladdeddeductions'),2,'.',',')}}
                </td>
                <td style="text-align: right;">{{number_format(collect($employees)->sum('netsalary'),2,'.',',')}}</td>
                <td style="text-align: center;"></td>
                <td style="text-align: center;"></td>
            </tr>
        @endif
    </table>
</div>