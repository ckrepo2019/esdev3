
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <button class="btn btn-sm btn-warning" ><strong>{{count($students)}}</strong> Students</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12" id="selectedoptionscontainer"></div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-12" id="resultscontainer">
                            <table id="example1" class="table">
                                <thead>
                                    <tr>
                                        <th style="width: 8%;">SID</th>
                                        <th style="width: 8%;">LRN</th>
                                        <th style="width: 44%;">Student</th>
                                        <th style="width: 20%;">Grade Level</th>
                                        <th style="width: 20%;"></th>
                                    </tr>
                                </thead>
                                <tbody style="font-size: 14px;">
                                    @if(count($students)>0)
                                        @foreach($students as $student)
                                            <tr>
                                                <td>{{$student->sid}}</td>
                                                <td>{{$student->lrn}}</td>
                                                <td>{{ucwords(strtolower($student->lastname))}}, {{ucwords(strtolower($student->firstname))}} {{ucwords(strtolower($student->middlename))}} {{ucwords(strtolower($student->suffix))}}
                                                </td>
                                                <td>{{$student->levelname}}</td>
                                                <td><button type="button" class="btn btn-default btn-sm btn-block btn-export" data-studid="{{$student->id}}" data-syid="{{$student->syid}}" data-levelname="{{$student->levelname}}" data-enrolleddate="{{$student->dateenrolled}}"><i class="fa fa-file-pdf"></i> Certificate</button></td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>