<style>
    .table thead th:first-child  { 
        position: sticky; 
        left: 0; 
        background-color: #fff; 
        outline: 2px solid #dee2e6;
        outline-offset: -1px;
        /* z-index: 9999 !important */
    }
    .table thead th:last-child  { 
        position: sticky !important; 
        right: 0; 
        background-color: #fff; 
        outline: 2px solid #dee2e6;
        outline-offset: -1px;
        /* z-index: 9999 !important */
    }
    /* .table thead {
        
        z-index: 999
    } */
    
    .table tbody td:last-child  { 
        position: sticky; 
        right: 0; 
        background-color: #fff; 
        outline: 2px solid #dee2e6;
        outline-offset: -1px;
        /* z-index: 999 */
        }
    
    .table tbody td:first-child  {  
        position: sticky; 
        left: 0; 
        background-color: #fff; 
        width: 150px !important;
        background-color: #fff; 
        outline: 2px solid #dee2e6;
        outline-offset: -1px;
    }
    
    .table thead th:first-child  { 
            position: sticky; left: 0; 
            width: 150px !important;
            background-color: #fff; 
            outline: 2px solid #dee2e6;
            outline-offset: -1px;
    }
    </style>
<div class="card">
    @if(count($employees)>0)
    <div class="card-header">
        <div class="row">
            <div class="col-md-12 text-right">
                <button type="button" class="btn btn-primary" id="btn-exporttopdf"><i class="fa fa-file-pdf"></i> Export to PDF</button>
            </div>
        </div>
    </div>
    @endif
    <div class="card-body  table-responsive p-0" style="height: 700px;">
      <table class="table table-head-fixed text-nowrap table-bordered">
            <thead class="text-center">
                <tr>
                    <th rowspan="3" style="width: 40%;z-index: 999">Employee</th>
                    @foreach($dates as $date)
                        <th colspan="
                        @if(Session::get('currentPortal') == 10) 5 @else 4 @endif" style="font-size: 10px;">{{date('M d, Y', strtotime($date))}}</th>
                    @endforeach
                    @if(Session::get('currentPortal') == 10)
                    <th rowspan="3" style="z-index: 999; font-size: 10px;">Total<br/>Hours<br/>Worked</th>
                    @endif
                </tr>
                <tr style="font-size: 10px; font-size: 10px;">
                    @foreach($dates as $date)
                        <th colspan="2">AM</th>
                        <th colspan="2">PM</th>
                        @if(Session::get('currentPortal') == 10) 
                        <th rowspan="2">Total</th>
                        @endif
                    @endforeach
                </tr>
                <tr style="font-size: 10px; font-size: 10px;">
                    @foreach($dates as $date)
                        <th>IN</th>
                        <th>OUT</th>
                        <th>IN</th>
                        <th>OUT</th>
                    @endforeach
                </tr>
            </thead>
            @if(count($employees)==0)
                <tbody>
                    <tr>
                        @if(Session::get('currentPortal') == 10) 
                        <td colspan="{{count($dates)*5}}">No logs for the selected days</td>
                        @else 
                        <td colspan="{{count($dates)*4}}">No logs for the selected days</td>
                        @endif
                    </tr>
                </tbody>
            @else
                @foreach($employees as $employee)
                    <tr style="font-size: 11px;">
                        <td>{{$employee->lastname}}, {{$employee->firstname}}</td>
                        @foreach($employee->logs as $logvalue)
                            <td>
                                @if(collect($logvalue->logs)->where('tapstate','IN')->where('ttime','<','12:00:00')->count() >0)
                                    @foreach(collect($logvalue->logs)->where('tapstate','IN')->where('ttime','<','12:00:00') as $log)
                                        {{date('h:i', strtotime($log->ttime))}}
                                        <br/>
                                    @endforeach
                                @endif
                            </td>
                            <td>
                                @if(collect($logvalue->logs)->where('tapstate','OUT')->where('ttime','<=','12:00:00')->count() >0)
                                    @foreach(collect($logvalue->logs)->where('tapstate','OUT')->where('ttime','<=','12:00:00') as $log)
                                        {{date('h:i', strtotime($log->ttime))}}
                                        <br/>
                                    @endforeach
                                @endif
                            </td>
                            <td>
                                @if(collect($logvalue->logs)->where('tapstate','IN')->where('ttime','>','12:00:00')->count() >0)
                                    @foreach(collect($logvalue->logs)->where('tapstate','IN')->where('ttime','>','12:00:00') as $log)
                                        {{date('h:i', strtotime($log->ttime))}}
                                        <br/>
                                    @endforeach
                                @endif
                            </td>
                            <td>
                                @if(collect($logvalue->logs)->where('tapstate','OUT')->where('ttime','>','12:00:00')->count() >0)
                                    @foreach(collect($logvalue->logs)->where('tapstate','OUT')->where('ttime','>','12:00:00') as $log)
                                        {{date('h:i', strtotime($log->ttime))}}
                                        <br/>
                                    @endforeach
                                @endif
                            </td>
                            
                            @if(Session::get('currentPortal') == 10) 

                            <td>
                                @if(strtolower(date('l', strtotime($logvalue->date))) == 'sunday')
                                    {{-- S U N D A Y --}}
                                @else
                                    {{$logvalue->hours}}h {{$logvalue->minutes}}m
                                @endif
                            </td>
                            @endif
                        @endforeach
                        @if(Session::get('currentPortal') == 10) 
                        <td style="font-weight: bold;">
                            @php
                                $totalhours = collect($employee->logs)->sum('hours');
                                $totalminutes = collect($employee->logs)->sum('minutes');

                                while($totalminutes>=60)
                                {
                                    $totalhours+=1;
                                    $totalminutes-=60;
                                }
                            @endphp
                            {{$totalhours}}h {{$totalminutes}}m
                        </td>
                        @endif
                    </tr>
                @endforeach
            @endif
        </table>
    </div>
</div>