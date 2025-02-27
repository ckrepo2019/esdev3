<div class="col-12">
    <table class="table table bordered"> 
        <thead>
            <tr>
                <th>Sections</th>
                <th></th>
                <th>Schedule</th>
                <th>Instructor</th>
            </tr>
        </thead>
        <tbody>
            @if(count($schedules)>0)
                @foreach($schedules as $schedule)
                <tr>
                    <td>
                        <div class="icheck-primary d-inline">
                        <input type="checkbox" class="checkbox-select-sched" id="checkbox-sched-{{$schedule->id}}" name="subjectsched[]" value="{{$schedule->id}}" 
                        @if($schedule->selected == 1) checked disabled @else @endif
                        >
                        <label for="checkbox-sched-{{$schedule->id}}">
                            {{$schedule->sectionname}}
                        </label>
                        </div>
                    </td>
                    <td>
                        @if($schedule->semesterid == 1)
                            1st Sem
                        @elseif($schedule->semesterid == 2)
                            2nd Sem
                        @endif<br/>
                        @if($schedule->added == 1)
                            <span class="right badge badge-warning mt-1">
                                Added: 1st Sem
                            </span>
                        @elseif($schedule->added == 2)
                            <span class="right badge badge-warning mt-1">
                                Added: 2nd Sem
                            </span>
                        @endif
                    </td>
                    <td>
                        @if(count($schedule->schedule)>0)
                            @foreach($schedule->schedule as $sched)
                                <div class="row">
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