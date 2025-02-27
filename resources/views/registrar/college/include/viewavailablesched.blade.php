
  <div class="row">
    <div class="col-md-12">
        
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#" id="backsubjectselection"><i class="fa fa-arrow-left"></i> Back</a></li>
        </ol>
        <br/>
        <table class="table table-hover table-bordered" style="font-size: 12px;table-layout: fixed;">
            <thead>
                <tr>
                    <th>Section</th>
                    <th>Schedule</th>
                    <th>Instructor</th>
                </tr>
            </thead>
            <tbody>
                @if(count($scheds)==0)
                    <tr>
                        <td colspan="3" class="text-center">
                            No schedules found
                        </td>
                    </tr>
                @else
                    @foreach($scheds as $sched)
                        <tr @if($sched->status == 1) style="background-color: #0080004d;" @elseif($sched->status == 2) style="background-color: #e9967a6e;" @endif id="{{$sched->id}}">
                            <td>
                                @if($sched->status == 0)
                                    <button type="button" class="btn btn-sm btn-default p-1 mr-2 addsubject">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                @endif
                                <span class="sectionname">{{$sched->sectionname}}</span>
                            </td>
                            <td class="schedule">
                                @if(count($sched->schedules)>0)
                                    @foreach($sched->schedules as $schedule)
                                        {{$schedule->day}} - {{$schedule->stime}} - {{$schedule->etime}}
                                        <br/>
                                    @endforeach
                                @endif
                            </td>
                            <td>
                                {{$sched->teachername}}
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
  </div>