<div class="row">
        <div class="col-md-12">
            <div class="mb-3 card card-body">
                <h5 class="card-title">Class Schedule</h5>
                    {{-- <div class=" scroll-area-md" >
                        <div class="scrollbar-container ps ps--active-y"> --}}
                            <div class="table-responsive">
                                <table width="500" class="table">
                                    <tr>
                                        <td width="10%" class="border">Time</td>
                                        <td width="15%" class="border">Monday</td>
                                        <td width="15%" class="border">Tuesday</td>
                                        <td width="15%" class="border">Wednesday</td>
                                        <td width="15%" class="border">Thursday</td>
                                        <td width="15%" class="border">Friday</td>
                                        <td width="15%" class="border">Saturday</td>
                                    </tr>
                                @php
                                    $totalTime = 7;
                                    $timeCount = 7;
                                    $timeMin = 30;
                                    $datetableindex = 7;
                                    $arrayRowCount = array(7,7,7,7,7,7,7);
                                @endphp
                                
                                @while($totalTime < 29)
                                    <tr>
                                        @if($totalTime%2==1)
                                            <td height="30" class="border">{{$timeCount}} : 00</td>
                                            @foreach($days as $key=>$day)
                                                @if($arrayRowCount[$key]==$totalTime)
                                                    @php
                                                        $valueCount = 0;
                                                        $span = 0;
                                                        $starttime = 0;
                                                        $subject ="";
                                                        $time = "";
                                                    @endphp
                                                    @foreach($sampleScheds as $sampleSched)
                                                        @if($totalTime>10)
                                                            @if($day == $sampleSched['day'] && ($timeCount.":00" == $sampleSched['startime']))
                                                                @php
                                                                    $time = $sampleSched['startime'].' - '.$sampleSched['endtime'];
                                                                    $subject = $sampleSched['subject'];
                                                                    $valueCount=1;
                                                                    $span = $sampleSched['length'];
                                                                @endphp
                                                            @endif
                                                        @else
                                                            @if($day == $sampleSched['day'] && ("0".$timeCount.":00" == $sampleSched['startime']))
                                                                @php
                                                                    $time = $sampleSched['startime'].' - '.$sampleSched['endtime'];
                                                                    $subject = $sampleSched['subject'];
                                                                    $valueCount=1;
                                                                    $span = $sampleSched['length'];
                                                                @endphp
                                                            @endif
                                                        @endif
    
                                                    @endforeach
    
                                                    @if($valueCount>0)
                                                        @php
                                                            $arrayRowCount[$key] += $span;
                                                            echo '<td rowspan="'.$span.'"class="border bg-info text-white" align="center" valign="middle">'.$subject.'<br>
                                                                '.$time.'<br>Teacher</td>';
                                                        @endphp
                                                    @else
                                                        <td class="border"></td>
                                                        @php
                                                            $arrayRowCount[$key] += 1;
                                                        @endphp
                                                    @endif
                                                @endif
                                            @endforeach
                                        @else
                                            <td height="30" class="border"></td>
                                            @foreach($days as $key=>$day)
                                                @if($arrayRowCount[$key]==$totalTime)
                                                    @php
                                                        $valueCount = 0;
                                                        $span = 0;
                                                    @endphp
                                                    @foreach($sampleScheds as $sampleSched)
                                                        @if($totalTime>10)
                                                            @if($day == $sampleSched['day'] && ($timeCount.":30" == $sampleSched['startime']))
                                                                @php
                                                                    $time = $sampleSched['startime'].' - '.$sampleSched['endtime'];
                                                                    $subject = $sampleSched['subject'];
                                                                    $valueCount=1;
                                                                    $span = $sampleSched['length'];
                                                                @endphp
                                                            @endif
                                                        @else
                                                            @if($day == $sampleSched['day'] && ("0".$timeCount.":30" == $sampleSched['startime']))
                                                                @php
                                                                    $time = $sampleSched['startime'].' - '.$sampleSched['endtime'];
                                                                    $subject = $sampleSched['subject'];
                                                                    $valueCount=1;
                                                                    $span = $sampleSched['length'];
                                                                @endphp
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                    @if($valueCount>0)
                                                        @php
                                                            $arrayRowCount[$key] += $span;
                                                            echo '<td rowspan="'.$span.'"class="border bg-info text-white" align="center" valign="middle">'.$subject.'<br>
                                                                '.$time.'<br>Teacher</td>';
                                                        @endphp
                                                    @else
                                                        <td class="border"></td>
                                                        @php
                                                            $arrayRowCount[$key] += 1;
                                                        @endphp
                                                    @endif
                                                @endif
                                            @endforeach
                                            @php
                                                $timeCount+=1;  
                                            @endphp
                                        @endif
                                    </tr>
                                    @php
                                        
                                        $totalTime +=1;
                                    @endphp
    
                                @endwhile
                            </table>
                        {{-- </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>