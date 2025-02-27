{{-- <div class="card" style="border: none; box-shadow: unset !important;">
    <div class="card-body"> --}}
        {{-- <div class="row">
            <div class="col-md-12">
                <table class="table table-hover">
                    @foreach($employees as $employee)
                        <tr>
                            <td></td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div> --}}
        <div class="row mb-2">
            <div class="col-md-6 mb-2 text-left">                
                {{-- <button type="button" class="btn btn-sm btn-default"> <span class="right badge badge-info">{{count($employees)}}</span></button> --}}
            </div>
            <div class="col-md-6 mb-2 text-right">                
                <button type="button" class="btn btn-sm btn-default" id="btn-exporttopdf"><i class="fa fa-file-pdf"></i> Export to PDF</button>
            </div>
            <div class="col-md-12">                
              <input class="form-control" id="input-search" placeholder="Search employee" />
            </div>
        </div>
        <div style="height: 500px; overflow: scroll;" class="row">
            @foreach($employees as $employee)
                @if(count($employee->records) > 0)
                    <div class="col-md-12 card-each-employee" data-string="{{$employee->lastname}}, {{$employee->firstname}} {{$employee->suffix}}<">
                        <div class="card collapsed-card" style="border: none; box-shadow: unset !important;">
                        <div class="card-header p-1">
                                <h3 class="card-title"><span class="text-bold">{{$employee->lastname}}</span>, {{$employee->firstname}} {{$employee->middlename}} {{$employee->suffix}}</h3>
                    
                                <div class="card-tools">       
                                    {{-- <small><span class="right badge badge-danger">
                                        {{count($employee->daysabsent)}}
                                    </span> </small>                          --}}
                                    @if(count($offenses)>0)
                                    <div class="dropdown" style="display: inline;">
                                        <button class="btn btn-default dropdown-toggle btn-sm" type="button" data-toggle="dropdown"><span class="dropdown-text{{$employee->id}}"> @if(count($employee->offenses) == 0 )Mark Offense @elseif(count($employee->offenses) == 1) {{'('.count($employee->offenses).')'}} Offense @else  {{'('.count($employee->offenses).')'}} Offenses @endif</span>
                                        <span class="caret"></span></button>
                                        <ul class="dropdown-menu p-2">
                                        @foreach($offenses as $offense)
                                            <li>
                                                <a class="option-link" href="#">
                                                    <label>
                                                        <input name='options{{$employee->id}}[]' type="checkbox" class="option justone" @if(collect($employee->offenses)->where('offenseid', $offense->id)->count() == 0) value='0' @else  value="1" checked @endif data-empid="{{$employee->id}}" data-offenseid="{{$offense->id}}"/> {{$offense->title}}
                                                    </label>
                                                </a>
                                            </li>
                                        @endforeach
                                        </ul>
                                    </div>
                                    @endif
                                    <button type="button" class="btn btn-sm btn-outline-success btn-exporteachemp" data-id="{{$employee->id}}"><i class="fa fa-file-pdf"></i> PDF</button>
                                    <button type="button" class="btn btn-tool text-secondary" data-card-widget="collapse"><i class="fas fa-plus"></i></button>
                                </div>
                                <!-- /.card-tools -->
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body p-1" style="font-size: 14px;">
                                <label>Days</label>
                                <ol>
                                    @foreach($employee->records as $record)
                                        <li style="display: list-item !important; list-style: decimal !important;">                                            
                                            <div class="row mb-2">
                                                <div class="col-md-4">{{date('M d, Y', strtotime($record->date))}} <span class="badge badge-info">{{date('D', strtotime($record->date))}}</span></div>
                                                <div class="col-md-4">Late Hours: {{$record->latehours}}</div>
                                                <div class="col-md-4">Undertime Hours: {{$record->undertimehours}}</div>
                                            </div>                  
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <table class="table" style="font-size: 11px;">
                                                        <thead>
                                                            <tr>
                                                                <th colspan="2" class="text-center">AM</th>
                                                                <th colspan="2" class="text-center">PM</th>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-center">IN</th>
                                                                <th class="text-center">OUT</th>
                                                                <th class="text-center">IN</th>
                                                                <th class="text-center">OUT</th>
                                                            </tr>
                                                        </thead>
                                                        <tr>
                                                            <td class="text-center">@if($record->amtimein == null)<span class="right badge badge-warning" style="font-size: 11px;">Missed</span>@else {{$record->amtimein}} @endif</td>
                                                            <td class="text-center">@if($record->amtimeout == null)<span class="badge text-secondary" style="font-size: 11px; border: 1px solid black; ">Missed</span>@else {{date('h:i:s',strtotime($record->amtimeout))}} @endif</td>
                                                            <td class="text-center">@if($record->pmtimein == null)<span class="badge text-secondary" style="font-size: 11px; border: 1px solid black;">Missed</span>@else {{date('h:i:s',strtotime($record->pmtimein))}} @endif</td>
                                                            <td class="text-center">@if($record->pmtimeout == null)<span class="right badge badge-warning" style="font-size: 11px;">Missed</span>@else {{date('h:i:s',strtotime($record->pmtimeout))}} @endif</td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ol>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    {{-- </div>
</div> --}}
<script>
</script>