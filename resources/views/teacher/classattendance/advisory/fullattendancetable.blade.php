
              <table class="table table-head-fixed text-nowrap">
                <thead>
                    <tr>
                        <th>Student Name</th>
                        @if(count($classsubjects)>0)
                            @foreach($classsubjects as $classsubject)
                                <th class="text-center">{{$classsubject->subjectcode}}</th>
                            @endforeach
                        @endif
                        <th>Advisory</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($students)>0)
                        @foreach($students as $student)
                            <tr>
                                <td>
                                    {{$student->lastname}}, {{$student->firstname}} {{$student->middlename}} {{$student->suffix}}
                                </td>
                                @if(count($student->subjectattendance)>0)
                                    @foreach($student->subjectattendance as $subjectattendance)
                                    <td class="text-center">
                                        @if(strtolower($subjectattendance->status) == 'present')
                                            <span class="badge bg-success">PRESENT</span>
                                        @elseif(strtolower($subjectattendance->status) == 'late')
                                            <span class="badge bg-warning" data-toggle="tooltip" data-placement="bottom" title="{{$subjectattendance->remarks}}">LATE</span>
                                        @elseif(strtolower($subjectattendance->status) == 'absent')
                                            <span class="badge bg-danger" data-toggle="tooltip" data-placement="bottom" title="{{$subjectattendance->remarks}}">ABSENT</span>
                                        @endif
                                    </td>
                                    @endforeach
                                @endif
                                <td>
                                    @if($student->classattendance == 1)
                                    <span class="badge bg-success">PRESENT</span>
                                    @elseif($student->classattendance == 2)
                                    <span class="badge bg-warning" data-toggle="tooltip" data-placement="bottom" title="{{$student->remarks}}">LATE</span>
                                    @elseif($student->classattendance == 3)
                                    <span class="badge bg-warning" data-toggle="tooltip" data-placement="bottom" title="{{$student->remarks}}">CUTTING CLASS</span>
                                    @elseif($student->classattendance == 4)
                                    <span class="badge bg-danger" data-toggle="tooltip" data-placement="bottom" title="{{$student->remarks}}">ABSENT</span>
                                    @else
                                    <span class="badge bg-secondary">UNCHECKED</span>
                                    @endif
                                    {{-- <select class="form-control form-control-sm changestatus" >
                                        <option value="" {{null == $student->classattendance ? 'selected' : ''}}>UNCHECKED</option>
                                        <option value="1" {{1 == $student->classattendance ? 'selected' : ''}}>PRESENT</option>
                                        <option value="2" {{2 == $student->classattendance ? 'selected' : ''}}>LATE</option>
                                        <option value="3" {{3 == $student->classattendance ? 'selected' : ''}}>CUTTING CLASS</option>
                                        <option value="4" {{4 == $student->classattendance ? 'selected' : ''}}>ABSENT</option>
                                    </select> --}}
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
              </table>