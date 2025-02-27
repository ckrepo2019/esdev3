
                {{-- <div class="row mb-2">
                    <div class="col-md-9">
                        <input type="text" id="input-search" placeholder="Search" class="form-control">
                    </div> --}}
                    {{-- <div class="col-md-3 text-right">
                        @if(count($allschedules)>0)
                        <button type="button" class="btn btn-success" id="btn-export-excel"> <i class="fa fa-download"></i> &nbsp;Export&nbsp; Excel</button>
                        @endif
                    </div> --}}
                {{-- </div> --}}
                <div class="row">
                    <div class="col-md-12"  style="overflow: scroll; max-height: 600px;">
                        <table id="studentstable" class="table table-bordered tableFixHead table-hover">
                            <thead>
                                <tr>
                                    <th width="10px">#</th>    
                                    <th>Student Name</th>   
                                    <th>Section</th>     
                                    <th>Year Level - Course</th>          
                                </tr>
                            </thead>
                            <tbody class="studentscontainer"  style="font-size: 12px;">
                                @if(count($students)>0)
                                    @foreach($students as $student)
                                        <tr>
                                            <td></td>
                                            <td>{{$student->lastname}}, {{$student->firstname}} {{$student->middlename}} {{$student->suffix}} <button type="button" class="btn btn-sm btn-default float-right btn-view" data-id="{{$student->id}}" data-courseid="{{$student->courseid}}" data-levelid="{{$student->levelid}}" data-sectionid="{{$student->sectionid}}">View</button></td>
                                            <td>{{$student->sectionname}}</td>
                                            <td>{{$student->levelname}} - {{$student->coursecode}}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                
                