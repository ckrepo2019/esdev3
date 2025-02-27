<style>
    .table thead th:first-child  { 
        position: sticky; 
        left: 0; 
        background-color: #fff; 
        outline: 2px solid #dee2e6;
        outline-offset: -1px;
        /* z-index: 9999 !important */
    }
    /* .table thead th:last-child  { 
        position: sticky !important; 
        right: 0; 
        background-color: #fff; 
        outline: 2px solid #dee2e6;
        outline-offset: -1px;
    }
    .table tbody td:last-child  { 
        position: sticky; 
        right: 0; 
        background-color: #fff; 
        outline: 2px solid #dee2e6;
        outline-offset: -1px;
        }
     */
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
  <div class="card shadow" style="box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important; border: none !important;">
    @if(count($employees)>0)
    <div class="card-header">
        <div class="row">
            <div class="col-md-9">
              <div class="form-group m-0">
              <label>Select Employee</label>
              
              <select class="form-control select2"  multiple="multiple" id="select-id" style="width: 100%;">
                  @foreach($employees as $employee)
                      <option value="{{$employee->id}}">{{$employee->lastname}}, {{$employee->firstname}} {{$employee->middlename}}</option>
                  @endforeach
              </select>
              </div>
            </div>
            <div class="col-md-3 text-right align-self-end">
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
                        @if(Session::get('currentPortal') == 10) 4 @else 4 @endif" style="font-size: 10px;">{{date('M d, Y', strtotime($date))}}</th>
                    @endforeach
                    {{-- @if(Session::get('currentPortal') == 10)
                    <th rowspan="3" style="z-index: 999; font-size: 10px;">Total<br/>Hours<br/>Worked</th>
                    @endif --}}
                </tr>
                <tr style="font-size: 10px; font-size: 10px;">
                    @foreach($dates as $date)
                        <th colspan="2">AM</th>
                        <th colspan="2">PM</th>
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
                        {{-- @if(Session::get('currentPortal') == 10) 
                        <td colspan="{{count($dates)*5}}">No logs for the selected days</td>
                        @else  --}}
                        <td colspan="{{(count($dates)*4)+1}}">No logs for the selected days</td>
                        {{-- @endif --}}
                    </tr>
                </tbody>
            @else
                @foreach($employees as $employee)
                    <tr style="font-size: 11px;">
                        <td>{{$employee->lastname}}, {{$employee->firstname}}</td>
                        @foreach($employee->logs as $logvalue)
                        <td class="text-center" style="vertical-align: middle;">
                            
                            {{$logvalue->timeinam != null ? date('h:i', strtotime($logvalue->timeinam)) : ''}}
                        
                        </td>
                        <td class="text-center" style="vertical-align: middle;">
                            
                            {{$logvalue->timeoutam != null ? date('h:i', strtotime($logvalue->timeoutam)) : ''}}
                        
                        </td>
                        <td class="text-center" style="vertical-align: middle;">
                            
                            {{$logvalue->timeinpm != null ? date('h:i', strtotime($logvalue->timeinpm)) : ''}}
                        
                        </td>
                        <td class="text-center" style="vertical-align: middle;">                                    
                            {{$logvalue->timeoutpm != null ? date('h:i', strtotime($logvalue->timeoutpm)) : ''}}                                
                        </td>
                            
                            {{-- @if(Session::get('currentPortal') == 10) 

                            <td>
                                @if(strtolower(date('l', strtotime($logvalue->date))) == 'sunday')
                                
                                @else
                                    {{$logvalue->hours}}h {{$logvalue->minutes}}m
                                @endif
                            </td>
                            @endif --}}
                        @endforeach
                        {{-- @if(Session::get('currentPortal') == 10) 
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
                        @endif --}}
                    </tr>
                @endforeach
            @endif
        </table>
    </div>
</div>
<script>
    
    $('.select2').select2()
</script>