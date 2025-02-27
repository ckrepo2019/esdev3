<div class="card">
    @if(count($logs)>0)
    <div class="card-header">
        <div class="row">
            <div class="col-md-12 text-right">
                <button type="button" class="btn btn-primary" id="btn-exporttopdf"><i class="fa fa-file-pdf"></i> Export to PDF</button>
            </div>
        </div>
    </div>
    @endif
    <div class="card-body">
        <table class="table table-bordered" style="table-layout: fixed;">
            <thead class="text-center">
                <tr>
                    <th rowspan="3" style="width: 30%;">Date</th>
                    <th colspan="5">Logs</th>
                </tr>
                <tr>
                    <th colspan="2">AM</th>
                    <th colspan="2">PM</th>
                    <th rowspan="2">Hours Worked</th>
                </tr>
                <tr>
                    <th>IN</th>
                    <th>OUT</th>
                    <th>IN</th>
                    <th>OUT</th>
                </tr>
            </thead>
            @if(count($logs)==0)
                <tbody>
                    <tr>
                        <td colspan="6">No logs for the selected days</td>
                    </tr>
                </tbody>
            @else
                <tbody>
                    @foreach($logs as $logvalue)
                        <tr>
                            <td>{{date('M d, Y', strtotime($logvalue->date))}} <span class="right badge badge-info">{{date('l', strtotime($logvalue->date))}}</span></td>
                            <td>
                                @if(collect($logvalue->logs)->where('tapstate','IN')->where('ttime','<','12:00:00')->count() >0)
                                    @foreach(collect($logvalue->logs)->where('tapstate','IN')->where('ttime','<','12:00:00') as $log)
                                        <button type="button" class="btn btn-sm btn-default p-0">{{date('h:i A', strtotime($log->ttime))}}</button>
                                        <br/>
                                    @endforeach
                                @endif
                            </td>
                            <td>
                                @if(collect($logvalue->logs)->where('tapstate','OUT')->where('ttime','<=','12:00:00')->count() >0)
                                    @foreach(collect($logvalue->logs)->where('tapstate','OUT')->where('ttime','<=','12:00:00') as $log)
                                        <button type="button" class="btn btn-sm btn-default p-0">{{date('h:i A', strtotime($log->ttime))}}</button>
                                        <br/>
                                    @endforeach
                                @endif
                            </td>
                            <td>
                                @if(collect($logvalue->logs)->where('tapstate','IN')->where('ttime','>','12:00:00')->count() >0)
                                    @foreach(collect($logvalue->logs)->where('tapstate','IN')->where('ttime','>','12:00:00') as $log)
                                        <button type="button" class="btn btn-sm btn-default p-0">{{date('h:i A', strtotime($log->ttime))}}</button>
                                        <br/>
                                    @endforeach
                                @endif
                            </td>
                            <td>
                                @if(collect($logvalue->logs)->where('tapstate','OUT')->where('ttime','>','12:00:00')->count() >0)
                                    @foreach(collect($logvalue->logs)->where('tapstate','OUT')->where('ttime','>','12:00:00') as $log)
                                        <button type="button" class="btn btn-sm btn-default p-0">{{date('h:i A', strtotime($log->ttime))}}</button>
                                        <br/>
                                    @endforeach
                                @endif
                            </td>
                            <td>
                                @if(strtolower(date('l', strtotime($logvalue->date))) == 'sunday')
                                    S U N D A Y
                                @else
                                    {{$logvalue->hours}}h {{$logvalue->minutes}}m
                                @endif
                            </td>
                        </tr>
                        {{-- <tr>
                            <td>{{date('M d, Y', strtotime($logvalue->date))}} <span class="right badge badge-info">{{date('l', strtotime($logvalue->date))}}</span></td>
                            <td>
                                @if(count($logvalue->logs)>0)
                                    @foreach($logvalue->logs as $log)
                                        @if($log->tapstate == 'IN')
                                            <button type="button" class="btn btn-sm btn-default p-0">{{date('h:i A', strtotime($log->ttime))}}</button>
                                            <br/>
                                        @endif
                                    @endforeach
                                @endif
                            </td>
                            <td></td>
                            <td>
                                @if(count($logvalue->logs)>0)
                                    @foreach($logvalue->logs as $log)
                                        @if($log->tapstate == 'OUT')
                                            <button type="button" class="btn btn-sm btn-default p-0">{{date('h:i A', strtotime($log->ttime))}}</button>
                                            <br/>
                                        @endif
                                    @endforeach
                                @endif
                            </td>
                        </tr> --}}
                    @endforeach
                    
                    <tr>
                        <td>&nbsp;</td>
                        <td colspan="4" style="text-align: right;">Total Working Hours: </td>
                        <td style="text-align: center;">
                            @php
                                $totalhours = collect($logs)->sum('hours');
                                $totalminutes = collect($logs)->sum('minutes');

                                while($totalminutes>=60)
                                {
                                    $totalhours+=1;
                                    $totalminutes-=60;
                                }
                            @endphp
                            {{$totalhours}}h {{$totalminutes}}m
                        </td>
                    </tr>
                </tbody>
            @endif
        </table>
    </div>
</div>