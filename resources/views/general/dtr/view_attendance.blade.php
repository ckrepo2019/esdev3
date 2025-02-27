
<div class="card shadow" style="border: none !important; box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;">
    <div class="card-header">
        <div class="row">
            <div class="col-md-12 text-right">
                <button type="button" class="btn btn-outline-info" id="btn-exporttopdf">Export to PDF</button>
            </div>
        </div>
    </div>
    <div class="card-body pt-0">
        <div class="row">
            <div class="col-md-12 p-0">
                {{-- @if(count($finalcalendar)>0)
                    @php
                    $schoolcalendar = collect($finalcalendar)->whereIn('date', collect($attendance)->pluck('date'))->groupBy('title');
                    @endphp
                <table style="width: 100%; font-size: 11px;" border="1">
                    <tr>
                        <th>
                            School Activities/Events for the Month
                        </th>
                    </tr>
                    @foreach($schoolcalendar as $eachtitlekey => $eachvalue)
                        <tr>
                            <td style="width: 70%;">
                                Title: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$eachtitlekey}}<br/>
                                Venue: &nbsp;{{$eachvalue[0]->venue}}
                            </td>
                            <td>
                                Start: &nbsp;&nbsp;&nbsp;&nbsp;{{date('m/d/Y h:i A , l ', strtotime($eachvalue[0]->startwithtime))}}<br/>
                                End: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{date('m/d/Y h:i A , l ', strtotime($eachvalue[0]->endwithtime))}}</td>
                        </tr>
                    @endforeach
                </table>
                @endif --}}
                <table class="table table-hover m-0" style="font-size: 13px;">
                    <thead class="text-center">
                        <tr>
                            <th rowspan="2" style="width: 20%;">Date</th>
                            <th colspan="2">AM</th>
                            <th colspan="2">PM</th>
                            <th rowspan="2" style="width: 30%;">Remarks</th>
                            {{-- <th rowspan="2" style="width: 15%;"></th> --}}
                        </tr>
                        <tr>
                            <th>IN</th>
                            <th>OUT</th>
                            <th>IN</th>
                            <th>OUT</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($attendance as $eachdayatt)
                            <tr>
                                <th style="vertical-align: middle;">{{date('m/d/Y - D', strtotime($eachdayatt->date))}}</th>
                                <td class="text-center" style="vertical-align: middle; font-size: 17px;">
                                    
                                    {{$eachdayatt->timeinam != null ? date('h:i', strtotime($eachdayatt->timeinam)) : ''}}
                                
                                </td>
                                <td class="text-center" style="vertical-align: middle; font-size: 17px;">
                                    
                                    {{$eachdayatt->timeoutam != null ? date('h:i', strtotime($eachdayatt->timeoutam)) : ''}}
                                
                                </td>
                                <td class="text-center" style="vertical-align: middle; font-size: 17px;">
                                    
                                    {{$eachdayatt->timeinpm != null ? date('h:i', strtotime($eachdayatt->timeinpm)) : ''}}
                                
                                </td>
                                <td class="text-center" style="vertical-align: middle; font-size: 17px;">                                    
                                    {{$eachdayatt->timeoutpm != null ? date('h:i', strtotime($eachdayatt->timeoutpm)) : ''}}                                
                                </td>
                                <td class="text-center" rowspan="2">
                                    <textarea class="form-control">{{$eachdayatt->remarks}}</textarea>
                                    <button class="btn btn-success btn-sm btn-submitremarks mt-1 btn-block" data-date="{{$eachdayatt->date}}"><i class="fa fa-share"></i> Submit Remarks</button>
                                </td>
                            </tr>
                            <tr>
                                <th colspan="5">
                                    School Activities/Events:<br/>
                                    @if(collect($finalcalendar)->where('date', $eachdayatt->date)->count() > 0)
                                        @foreach(collect($finalcalendar)->where('date', $eachdayatt->date)->values() as $eachdatecal)
                                        <span class="badge badge-success">{{$eachdatecal->title}}</span>
                                        @endforeach
                                    @endif
                                </th>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    $('#date-string').text("{{date('F d, Y', strtotime($datefrom))}} - {{date('F d, Y', strtotime($dateto))}}")
        $('.btn-submitremarks').on('click', function(){
            $.ajax({
                url: "/empdtr/updateremarks",
                type: "get",
                data: {
                    id: '{{$id}}',
                    selecteddate: $(this).attr('data-date'),
                    remarks  : $(this).closest('tr').find('textarea').val()
                },
                success: function (data) {
                    if(data == 1)
                    {
                        toastr.success('Updated successfully!')
                    }
                }
            });
        })
</script>