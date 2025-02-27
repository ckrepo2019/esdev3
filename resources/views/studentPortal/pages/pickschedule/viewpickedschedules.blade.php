<div class="row">
    <div class="col-12">
        <table class="table table-striped table-hover" style="font-size: 11px;width: 700px !important;"> 
            <thead>
                <tr>
                    <th>Subjects</th>
                    <th>Sections</th>
                    <th>Schedule</th>
                    <th>Instructor</th>
                </tr>
            </thead>
            <tbody>
                @if(count($pickedschedules)>0)
                    @foreach($pickedschedules as $schedule)
                    <tr>
                        <td>
                            @if($schedule->isapprove != 1)
                            <button type="button" class="btn btn-sm btn-danger btn-delete-sched" data-id="{{$schedule->id}}"><i class="fa fa-trash m-0"></i></button>
                            @endif
                            {{$schedule->subjectcode}} - {{$schedule->subjectname}}<br/>
                            
                            <span class="badge badge-primary mt-1" style="font-size: 11px;">
                                {{$schedule->levelname}}
                            </span>
                            <span class="badge badge-info mt-1" style="font-size: 11px;">
                                @if($schedule->semesterid == 1)
                                    1st Sem
                                @elseif($schedule->semesterid == 2)
                                    2nd Sem
                                @endif
                            </span>
                            @if($schedule->isapprove == 1)
                                <span class="badge badge-success mt-1" style="font-size: 11px;">
                                    Approved
                                </span>
                            @elseif($schedule->isapprove == 0)
                                <span class="badge badge-warning mt-1" style="font-size: 11px;">
                                    Pending
                                </span>
                            @endif
                        </td>
                        <td>
                            {{$schedule->sectionname}}
                        </td>
                        <td>
                            @if(count($schedule->schedule)>0)
                                @foreach($schedule->schedule as $sched)
                                    <div class="row mb-1">
                                        <div class="col-6">
                                            {{$sched->stime}} - {{$sched->etime}}
                                        </div>
                                        <div class="col-3">
                                            {{substr($sched->day, 0, 3)}}
                                        </div>
                                        <div class="col-3">
                                            {{$sched->roomname}}
                                        </div>
                                    </div>
                                @endforeach
                            @else
                            
                            @endif
                        </td>
                        <td>{{$schedule->teachername}}</td>
                    </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>