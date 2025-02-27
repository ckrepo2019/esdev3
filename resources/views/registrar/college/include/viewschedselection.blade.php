
            <table class="table table-bordered table-hover" style="font-size: 12px;table-layout: fixed;">
                <thead class="text-center">
                    <tr>
                        <th>Subj Code</th>
                        <th>Description</th>
                        <th>Units</th>
                        <th>Schedule</th>
                        <th>Instructor</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($scheds)>0)
                        @foreach($scheds as $sched)
                            <tr>
                                <td colspan="5" class="bg-info">Section : {{$sched->sectioninfo->sectionname}}</td>
                            </tr>
                            @if(count($sched->subjects)>0)
                                @foreach ($sched->subjects as $subjitem)
                                    @if($subjitem->status == 1)
                                        <tr style="background-color: #28a74585;">
                                    @else
                                        <tr>
                                    @endif
                                        <td>
                                            @if($subjitem->status == 0)
                                            <button type="button" class="btn btn-sm btn-default p-1 mr-2 addsubject">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                            @endif
                                            {{$subjitem->subjectinfo->subjectcode}}
                                        </td>
                                        <td>{{$subjitem->subjectinfo->subjectname}}</td>
                                        <td class="text-center">{{$subjitem->units}}</td>
                                        <td class="text-center">
                                            @if(count($subjitem->schedules)>0)
                                                @foreach ($subjitem->schedules as $scheditem)
                                                    {{$scheditem->day}} - {{$scheditem->stime}} {{$scheditem->etime}}
                                                    <br/>
                                                @endforeach
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($subjitem->teacherinfo != null)
                                                {{$subjitem->teacherinfo->lastname}}, {{$subjitem->teacherinfo->firstname}} {{$subjitem->teacherinfo->middlename}} {{$subjitem->teacherinfo->suffix}}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        @endforeach
                    @endif
                </tbody>
            </table>