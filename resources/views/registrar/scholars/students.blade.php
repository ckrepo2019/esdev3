
                
                {{-- <table id="studentstable" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th style="width: 20px;">#</th>    
                            <th>Students</th>       
                            <th>Grade Level</th>    
                            <th>Scholarships</th>    
                            <th></th>    
                        </tr>
                    </thead>
                    <tbody id="studentscontainer"  style="font-size: 12px;">
                        @if(count($students)>0)
                            @foreach($students as $student)
                                <tr>
                                    <td></td>
                                    <td>
                                        <div class="row">
                                            <div class="col-12">
                                                <label>{{$student->lastname}}, {{$student->firstname}} {{$student->middlename}} {{$student->suffix}}</label>
                                            </div>
                                            <div class="col-6">
                                                SID: {{$student->sid}}
                                            </div>
                                            <div class="col-6">
                                                LRN: {{$student->lrn}}
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{$student->levelname}}</td>
                                    <td id="stud{{$student->id}}">
                                        @if(count($student->scholarships)>0)
                                            <div class="row">
                                                @foreach ($student->scholarships as $scholarship)
                                                    <div class="col-12">{{$scholarship->program}}</div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-default btn-sm btn-addscholarship" data-id="{{$student->id}}" style="font-size: 10px;"><i class="fa fa-cogs"></i> Scholarship</button>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table> --}}
        <style>
            table th {
                padding: 1px !important;
            }
            table td {
                padding: 1px !important;
            }
        </style>
        @if(count($students)>0)
            @foreach($students as $student) 
                <div class="col-md-6 eachstudent" data-string="{{$student->lastname}}, {{$student->firstname}} {{$student->suffix}}<">
                    <div class="card" style="box-shadow: none !important; border: 2px solid #ddd !important;">
                        <div class="card-header">
                            <div class="row mb-2">
                                <div class="col-9">
                                    <h6><label>{{$student->lastname}}, {{$student->firstname}} {{$student->middlename}} {{$student->suffix}}</label></h6>
                                </div>
                                <div class="col-3 text-right">
                                    <button type="button" class="btn btn-default btn-sm btn-addscholarship" data-id="{{$student->id}}" style="font-size: 11px;"><i class="fa fa-cogs"></i> Scholarship</button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4">
                                    <small>Grade Level: {{$student->levelname}}</small>
                                </div>
                                <div class="col-4"><small>SID: {{$student->sid}}</small></div>
                                <div class="col-4"><small>LRN: {{$student->lrn}}</small></div>
                            </div>
                        </div>
                        <div class="card-body">
                            @if(count($student->scholarships)==0)
                                <div class="row">
                                    <div class="col-md-12">
                                        <small>No scholarships assigned</small>
                                    </div>
                                </div>
                            @else
                                <div class="row p-0">
                                    <div class="col-md-12 p-0 m-0">
                                        <table class="table table-bordered m-0">
                                            <thead style="font-size: 14px;">
                                                <tr>
                                                    <th style="width: 70%;">Scholarship</th>
                                                    <th style="width: 20%;">Amount</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody style="font-size: 16px;">
                                                @foreach ($student->scholarships as $scholarship)
                                                    <tr>
                                                        <td><small>{{$scholarship->abbreviation}}</small></td>
                                                        <td class="text-right"><small>{{number_format($scholarship->amount,2,'.',',')}}</small></td>
                                                        <td><button type="button" class="btn btn-sm m-0 p-0 text-warning btn-edit" data-id="{{$scholarship->progstudid}}"><i class="fa fa-edit"></i></button><button type="button" class="btn btn-sm m-0 p-0 float-right text-danger btn-delete" data-id="{{$scholarship->progstudid}}"><i class="fa fa-trash-alt"></i></button></td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
