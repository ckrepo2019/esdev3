
@if(session()->has('linkid'))
    @if( session()->get('linkid') == 'custom-content-above-dtr')
        <div class="tab-pane fade show active" id="custom-content-above-dtr" role="tabpanel" aria-labelledby="custom-content-above-dtr-tab">
    @else
        <div class="tab-pane fade" id="custom-content-above-dtr" role="tabpanel" aria-labelledby="custom-content-above-dtr-tab">
    @endif
@else
    <div class="tab-pane fade" id="custom-content-above-dtr" role="tabpanel" aria-labelledby="custom-content-above-dtr-tab">
@endif
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <strong>Daily Time Record</strong>
                </div>
            </div>
        </div>
        <div class="card-body">
            <label>DTR Period</label>
            <input type="text" name="dtrchangeperiod"  class="form-control form-control-sm col-md-3" id="dtrdaterange" value="{{$currentmonthfirstday}} - {{$currentmonthlastday}}">
            <br>
            <span class="div-only-mobile bg-info row">Swipe left to view more informations</span>
            <br>
            <div class="row" style="overflow: scroll;">

                <table class="table table-bordered" >
                    <thead class="text-center">
                        <tr>
                            <th rowspan="2" style="width: 25%;">Date</th>
                            <th colspan="2">AM</th>
                            <th colspan="2">PM</th>
                            <th rowspan="2">Tardiness<br>(Minutes)</th>
                            <th rowspan="2">Hours<br>Rendered</th>
                        </tr>
                        <tr>
                            <th>IN</th>
                            <th>OUT</th>
                            <th>IN</th>
                            <th>OUT</th>
                        </tr>
                    </thead>
                    <tbody id="timerecord">
                        @foreach($employeeattendance as $empattendance)
                            <tr>
                                <td>
                                    {{$empattendance->date}}
                                    @if(strtolower($empattendance->day) == 'saturday' || strtolower($empattendance->day) == 'sunday')
                                        <span class="right badge badge-secondary">{{$empattendance->day}}</span>
                                    @else
                                        <span class="right badge badge-default">{{$empattendance->day}}</span>
                                    @endif
                                </td>
                                <td class="text-center">{{$empattendance->timerecord->amin}}</td>
                                <td class="text-center">{{$empattendance->timerecord->amout}}</td>
                                <td class="text-center">{{$empattendance->timerecord->pmin}}</td>
                                <td class="text-center">{{$empattendance->timerecord->pmout}}</td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@if(session()->has('linkid'))
    @if( session()->get('linkid') == 'custom-content-above-dtr')
        </div>
    @else
        </div>
    @endif
@else
</div>
@endif