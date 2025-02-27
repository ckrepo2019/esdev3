<style>
    .alert-primary {
        color: #004085;
        background-color: #cce5ff;
        border-color: #b8daff;
    }
    .alert-secondary {
        color: #383d41;
        background-color: #e2e3e5;
        border-color: #d6d8db;
    }
    .alert-success {
        color: #155724;
        background-color: #d4edda;
        border-color: #c3e6cb;
    }
    .alert-danger {
        color: #721c24;
        background-color: #f8d7da;
        border-color: #f5c6cb;
    }
    .alert-warning {
        color: #856404;
        background-color: #fff3cd;
        border-color: #ffeeba;
    }
    .alert-info {
        color: #0c5460;
        background-color: #d1ecf1;
        border-color: #bee5eb;
    }
    .alert-light {
        color: #818182;
        background-color: #fefefe;
        border-color: #fdfdfe;
    }
    .alert-dark {
        color: #1b1e21;
        background-color: #d6d8d9;
        border-color: #c6c8ca;
    }
    </style>
    @if($acadprogid == 6)
    @php
        $strands = collect($students)->groupBy('strandcode');
    @endphp
            <div class="card card-success card-eachsection">
                <div class="card-header">
                    <div class="row">
                        <div class="col-12">
                            <div class="icheck-primary d-inline">
                                <input type="checkbox" id="checkboxesc" name="escCheck" @if($esc > 0) value="1" checked @else value="0" @endif>
                                <label for="checkboxesc">
                                    ESC Grantee
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
        @if(count($students) == 0)
            <div class="alert alert-primary" role="alert">
                No students enrolled!
                </div>
        @else
            <div class="card-body">
                <div class="row">
                    @if(count($students) == 0)
                        <div class="col-md-12">
                            <div class="alert alert-primary" role="alert">
                                No students enrolled!
                                </div>
                        </div>
                    @else
                        <div class="col-md-12 mb-2 text-right">
                            @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'gbbc' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sbc')
                                <form action="/reports_studentmasterlist/print/{{$syid}}/{{$sectionid}}" target="_blank" id="exportform" class="m-0 p-0">
                                    <input type="hidden" name="exporttype" id="exporttype"/>
                                    <input type="hidden" name="levelid" value="{{$levelid}}"/>
                                    <input type="hidden" name="semid" value="{{$semid}}"/>
                                    <input type="hidden" name="syid" value="{{$syid}}"/>
                                    <input type="hidden" name="collegeid" value="{{$collegeid}}"/>
                                    <input type="hidden" name="courseid" value="{{$courseid}}"/>
                                    <input type="hidden" name="sectionid" value="0"/>
                                    <input type="hidden" name="acadprogid" value="{{$acadprogid}}"/>
                                    <div class="row">
                                        <div class="col-md-3 text-left">
                                            <select class="form-control form-control-sm" name="format">
                                                <option value="lastname_first">Template - Last Name First</option>
                                                <option value="firstname_first">Template - First Name First</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4 text-left">
                                            <button type="button" class="btn btn-default btn-sm btn-export" id="exportpdf">Export to PDF</button>
                                            <button type="button" class="btn btn-default btn-sm btn-export" id="exportexcel">Export to EXCEL</button>
                                        </div>
                                        <div class="col-md-5">
                                            <button type="button" class="btn btn-default btn-sm btn-export" id="exportexcelinfo">Export to EXCEL (INFO)</button>
                                            <button type="button" class="btn btn-default btn-sm btn-export" id="exportexcellist">Export to EXCEL (LIST)</button>
                                        </div>
                                    </div>
                                    <input type="hidden" value="{{$esc}}" name="esc"/>
                                </form>
                            @else
                                <form action="/reports_studentmasterlist/print/{{$syid}}/0" target="_blank" id="exportform" class="m-0 p-0">
                                    <input type="hidden" name="exporttype" id="exporttype"/>
                                    <input type="hidden" name="levelid" value="{{$levelid}}"/>
                                    <input type="hidden" name="semid" value="{{$semid}}"/>
                                    <input type="hidden" name="syid" value="{{$syid}}"/>
                                    <input type="hidden" name="collegeid" value="{{$collegeid}}"/>
                                    <input type="hidden" name="courseid" value="{{$courseid}}"/>
                                    <input type="hidden" name="sectionid" value="0"/>
                                    <input type="hidden" name="acadprogid" value="{{$acadprogid}}"/>
                                    <div class="row">
                                        <div class="col-md-3 text-left">
                                            <select class="form-control form-control-sm" name="format">
                                                <option value="lastname_first">Template - Last Name First</option>
                                                <option value="firstname_first">Template - First Name First</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4 text-left">
                                            <button type="button" class="btn btn-default btn-sm btn-export" id="exportpdf">Export to PDF</button>
                                            <button type="button" class="btn btn-default btn-sm btn-export" id="exportexcel">Export to EXCEL</button>
                                        </div>
                                        <div class="col-md-5">
                                            @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hccsi')
                                            <button type="button" class="btn btn-default btn-sm btn-export" id="exportexcelinfo">Export to EXCEL (LIST)</button>
                                            <button type="button" class="btn btn-default btn-sm btn-export" id="exportexcellist">Export to EXCEL (INFO)</button>
                                            @else
                                            <button type="button" class="btn btn-default btn-sm btn-export" id="exportexcelinfo">Export to EXCEL (INFO)</button>
                                            <button type="button" class="btn btn-default btn-sm btn-export" id="exportexcellist">Export to EXCEL (LIST)</button>
                                            @endif
                                        </div>
                                    </div>
                                <input type="hidden" value="{{$esc}}" name="esc"/>
                            </form>
                            @endif
                        
                        </div>
                        
                        <div class="col-md-12">
                        @foreach($strands as $key=>$eachstrand)
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5>{{$key}}</h5>
                                    </div>
                                    <div class="col-md-6">
                                        <label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;MALE</label>
                                        <ol>
                                            @foreach($eachstrand as $student)
                                                @if(strtolower($student->gender) == 'male')
                                                    <li  style="display: list-item;list-style: decimal; list-style-position: inside; @if($student->studstatus == 3 || $student->studstatus == 5)text-decoration: line-through @endif">{{$student->lastname}}, {{$student->firstname}} {{$student->middlename}} @if($student->studstatus == 3 || $student->studstatus == 5){{DB::table('studentstatus')->where('id', $student->studstatus)->first()->description}}@endif @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hc babak') @if($esc == 1) @if(strtolower($student->granteedesc) == 'esc') - <button type="button" class="btn btn-sm btn-default btn-each-esccert" data-id="{{$student->id}}"><i class="fa fa-file-pdf text-secondary"></i></button>@endif @endif @endif</li>
                                                @endif
                                            @endforeach
                                        </ol>
                                    </div>
                                    <div class="col-md-6">
                                        <label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;FEMALE</label>
                                        <ol>
                                            @foreach($eachstrand as $student)
                                                @if(strtolower($student->gender) == 'female')
                                                <li  style="display: list-item;list-style: decimal; list-style-position: inside; @if($student->studstatus == 3 || $student->studstatus == 5)text-decoration: line-through @endif">{{$student->lastname}}, {{$student->firstname}} {{$student->middlename}} @if($student->studstatus == 3 || $student->studstatus == 5){{DB::table('studentstatus')->where('id', $student->studstatus)->first()->description}}@endif @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hc babak') @if($esc == 1) @if(strtolower($student->granteedesc) == 'esc') - <button type="button" class="btn btn-sm btn-default btn-each-esccert" data-id="{{$student->id}}"><i class="fa fa-file-pdf text-secondary"></i></button>@endif @endif @endif</li>
                                                @endif
                                            @endforeach
                                        </ol>
                                    </div>
                                </div>
                                <hr/>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        @endif
        <!-- /.card-body -->
        </div>
    @elseif($acadprogid == 5)
    @php
        $strands = collect($students)->groupBy('strandcode');
    @endphp
            <div class="card card-success card-eachsection">
                <div class="card-header">
                    <div class="row">
                        <div class="col-12">
                            <div class="icheck-primary d-inline">
                                <input type="checkbox" id="checkboxesc" name="escCheck" @if($esc > 0) value="1" checked @else value="0" @endif>
                                <label for="checkboxesc">
                                    ESC Grantee
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
        @if(count($students) == 0)
            <div class="alert alert-primary" role="alert">
                No students enrolled!
                </div>
        @else
            <div class="card-body">
                <div class="row">
                    @if(count($students) == 0)
                        <div class="col-md-12">
                            <div class="alert alert-primary" role="alert">
                                No students enrolled!
                                </div>
                        </div>
                    @else
                        <div class="col-md-12 mb-2 text-right">
                            @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'gbbc' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sbc')
                                <form action="/reports_studentmasterlist/print/{{$syid}}/{{$sectionid}}" target="_blank" id="exportform" class="m-0 p-0">
                                    <input type="hidden" name="exporttype" id="exporttype"/>
                                    <input type="hidden" name="levelid" value="{{$levelid}}"/>
                                    <input type="hidden" name="semid" value="{{$semid}}"/>
                                    <input type="hidden" name="syid" value="{{$syid}}"/>
                                    <input type="hidden" name="collegeid" value="{{$collegeid}}"/>
                                    <input type="hidden" name="courseid" value="{{$courseid}}"/>
                                    <input type="hidden" name="sectionid" value="{{$sectionid}}"/>
                                    <input type="hidden" name="acadprogid" value="{{$acadprogid}}"/>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <select class="form-control form-control-sm" name="format">
                                                <option value="lastname_first">Template - Last Name First</option>
                                                <option value="firstname_first">Template - First Name First</option>
                                            </select>
                                        </div>
                                        <div class="col-md-8">
                                            <button type="button" class="btn btn-default btn-sm btn-export" id="exportpdf">Export to PDF</button>
                                            <button type="button" class="btn btn-default btn-sm btn-export" id="exportexcelinfo">Export to EXCEL (INFO)</button>
                                            <button type="button" class="btn btn-default btn-sm btn-export" id="exportexcellist">Export to EXCEL (LIST)</button>
                                        </div>
                                    </div>
                                    <input type="hidden" value="{{$esc}}" name="esc"/>
                                </form>
                            @else
                                <form action="/reports_studentmasterlist/print/{{$syid}}/{{$sectionid}}" target="_blank" id="exportform" class="m-0 p-0">
                                    <input type="hidden" name="exporttype" id="exporttype"/>
                                    <input type="hidden" name="levelid" value="{{$levelid}}"/>
                                    <input type="hidden" name="semid" value="{{$semid}}"/>
                                    <input type="hidden" name="syid" value="{{$syid}}"/>
                                    <input type="hidden" name="collegeid" value="{{$collegeid}}"/>
                                    <input type="hidden" name="courseid" value="{{$courseid}}"/>
                                    <input type="hidden" name="sectionid" value="{{$sectionid}}"/>
                                    <input type="hidden" name="acadprogid" value="{{$acadprogid}}"/>
                                    <div class="row">
                                        <div class="col-md-3 text-left">
                                            <select class="form-control form-control-sm" name="format">
                                                <option value="lastname_first">Template - Last Name First</option>
                                                <option value="firstname_first">Template - First Name First</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4 text-left">
                                            <button type="button" class="btn btn-default btn-sm btn-export" id="exportpdf">Export to PDF</button>
                                            <button type="button" class="btn btn-default btn-sm btn-export" id="exportexcel">Export to EXCEL</button>
                                        </div>
                                        <div class="col-md-5">
                                            @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hccsi')
                                            <button type="button" class="btn btn-default btn-sm btn-export" id="exportexcelinfo">Export to EXCEL (LIST)</button>
                                            <button type="button" class="btn btn-default btn-sm btn-export" id="exportexcellist">Export to EXCEL (INFO)</button>
                                            @else
                                            <button type="button" class="btn btn-default btn-sm btn-export" id="exportexcelinfo">Export to EXCEL (INFO)</button>
                                            <button type="button" class="btn btn-default btn-sm btn-export" id="exportexcellist">Export to EXCEL (LIST)</button>
                                            @endif
                                        </div>
                                    </div>
                                    <input type="hidden" value="{{$esc}}" name="esc"/>
                                </form>
                            @endif
                            {{-- <button type="button" class="btn btn-default btn-sm btn-exportpdf">Export to PDF</button> --}}
                        </div>
                        
                        <div class="col-md-12">
                        @foreach($strands as $key=>$eachstrand)
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5>{{$key}}</h5>
                                    </div>
                                    <div class="col-md-6">
                                        <label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;MALE</label>
                                        <ol>
                                            @foreach($eachstrand as $student)
                                                @if(strtolower($student->gender) == 'male')
                                                    <li  style="display: list-item;list-style: decimal; list-style-position: inside; @if($student->studstatus == 3 || $student->studstatus == 5)text-decoration: line-through @endif">{{$student->lastname}}, {{$student->firstname}} {{$student->middlename}} @if($student->studstatus == 3 || $student->studstatus == 5){{DB::table('studentstatus')->where('id', $student->studstatus)->first()->description}}@endif @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hc babak') @if($esc == 1) @if(strtolower($student->granteedesc) == 'esc') - <button type="button" class="btn btn-sm btn-default btn-each-esccert" data-id="{{$student->id}}"><i class="fa fa-file-pdf text-secondary"></i></button>@endif @endif @endif</li>
                                                @endif
                                            @endforeach
                                        </ol>
                                    </div>
                                    <div class="col-md-6">
                                        <label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;FEMALE</label>
                                        <ol>
                                            @foreach($eachstrand as $student)
                                                @if(strtolower($student->gender) == 'female')
                                                <li  style="display: list-item;list-style: decimal; list-style-position: inside; @if($student->studstatus == 3 || $student->studstatus == 5)text-decoration: line-through @endif">{{$student->lastname}}, {{$student->firstname}} {{$student->middlename}} @if($student->studstatus == 3 || $student->studstatus == 5){{DB::table('studentstatus')->where('id', $student->studstatus)->first()->description}}@endif @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hc babak') @if($esc == 1) @if(strtolower($student->granteedesc) == 'esc') - <button type="button" class="btn btn-sm btn-default btn-each-esccert" data-id="{{$student->id}}"><i class="fa fa-file-pdf text-secondary"></i></button>@endif @endif @endif</li>
                                                @endif
                                            @endforeach
                                        </ol>
                                    </div>
                                </div>
                                <hr/>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        @endif
        <!-- /.card-body -->
        </div>
    @else
    {{-- @if($esc == 1)
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-6">
                    <em class="text-danger">Note: Please separate per paragraph</em>
                </div>
                <div class="col-6 text-right">
                    <button type="button" class="btn btn-sm btn-outline-primary" id="btn-addnew-note">Add New Note</button>
                </div>
            </div>
            <div id="container-notes"></div>
        </div>
    </div>
    @endif --}}
    <div class="card card-success card-eachsection">
        {{-- @if($esc == 1)
        <div class="card-header">
            <div class="row">
                <div class="col-6">
                    <em class="text-danger">Note: Please separate per paragraph</em>
                    <div class="icheck-primary d-inline">
                        <input type="checkbox" id="checkboxesc" name="escCheck" @if($esc > 0) value="1" checked @else value="0" @endif>
                        <label for="checkboxesc">
                            ESC Grantee
                        </label>
                    </div>
                </div>
                <div class="col-6 text-right">
                    <button type="button" class="btn btn-sm btn-outline-primary" id="btn-addnew-note">Add New Note</button>
                </div>
            </div>
            <div id="container-notes"></div>
        </div>
        @endif --}}
    <div class="card-body">
        <div class="row">
            @if(count($students) == 0)
                <div class="col-md-12">
                    <div class="alert alert-primary" role="alert">
                        No students enrolled!
                        </div>
                </div>
            @else
                <div class="col-md-12 mb-2 text-right">
                    @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'gbbc' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sbc')
                        <form action="/reports_studentmasterlist/print/{{$syid}}/{{$sectionid}}" target="_blank" id="exportform" class="m-0 p-0">
                            <input type="hidden" name="exporttype" id="exporttype"/>
                            <input type="hidden" name="levelid" value="{{$levelid}}"/>
                            <input type="hidden" name="semid" value="{{$semid}}"/>
                            <input type="hidden" name="syid" value="{{$syid}}"/>
                            <input type="hidden" name="collegeid" value="{{$collegeid}}"/>
                            <input type="hidden" name="courseid" value="{{$courseid}}"/>
                            <input type="hidden" name="sectionid" value="{{$sectionid}}"/>
                            <input type="hidden" name="acadprogid" value="{{$acadprogid}}"/>
                            <div class="row">
                                <div class="col-md-4">
                                    <select class="form-control form-control-sm" name="format">
                                        <option value="lastname_first">Template - Last Name First</option>
                                        <option value="firstname_first">Template - First Name First</option>
                                    </select>
                                </div>
                                <div class="col-md-8">
                                    <button type="button" class="btn btn-default btn-sm btn-export" id="exportpdf">Export to PDF</button>
                                    <button type="button" class="btn btn-default btn-sm btn-export" id="exportexcelinfo">Export to EXCEL (INFO)</button>
                                    <button type="button" class="btn btn-default btn-sm btn-export" id="exportexcellist">Export to EXCEL (LIST)</button>
                                </div>
                            </div>
                            <input type="hidden" value="{{$esc}}" name="esc"/>
                        </form>
                    @else
                        <form action="/reports_studentmasterlist/print/{{$syid}}/{{$sectionid}}" target="_blank" id="exportform" class="m-0 p-0">
                            <input type="hidden" name="exporttype" id="exporttype"/>
                            <input type="hidden" name="levelid" value="{{$levelid}}"/>
                            <input type="hidden" name="semid" value="{{$semid}}"/>
                            <input type="hidden" name="syid" value="{{$syid}}"/>
                            <input type="hidden" name="collegeid" value="{{$collegeid}}"/>
                            <input type="hidden" name="courseid" value="{{$courseid}}"/>
                            <input type="hidden" name="sectionid" value="{{$sectionid}}"/>
                            <input type="hidden" name="acadprogid" value="{{$acadprogid}}"/>
                            <div class="row">
                                <div class="col-md-3 text-left">
                                    <select class="form-control form-control-sm" name="format">
                                        <option value="lastname_first">Template - Last Name First</option>
                                        <option value="firstname_first">Template - First Name First</option>
                                    </select>
                                </div>
                                <div class="col-md-4 text-left">
                                    <button type="button" class="btn btn-default btn-sm btn-export" id="exportpdf">Export to PDF</button>
                                    <button type="button" class="btn btn-default btn-sm btn-export" id="exportexcel">Export to EXCEL</button>
                                </div>
                                <div class="col-md-5">
                                    @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hccsi')
                                    <button type="button" class="btn btn-default btn-sm btn-export" id="exportexcelinfo">Export to EXCEL (LIST)</button>
                                    <button type="button" class="btn btn-default btn-sm btn-export" id="exportexcellist">Export to EXCEL (INFO)</button>
                                    @else
                                    <button type="button" class="btn btn-default btn-sm btn-export" id="exportexcelinfo">Export to EXCEL (INFO)</button>
                                    <button type="button" class="btn btn-default btn-sm btn-export" id="exportexcellist">Export to EXCEL (LIST)</button>
                                    @endif
                                </div>
                            </div>
                            <input type="hidden" value="{{$esc}}" name="esc"/>
                        </form>
                    @endif
                    {{-- <button type="button" class="btn btn-default btn-sm btn-exportpdf">Export to PDF</button> --}}
                </div>
                <div class="col-md-6">
                    <label>MALE</label>
                    <ol>
                        @foreach($students as $student)
                            @if(strtolower($student->gender) == 'male')
                                <li  style="display: list-item;list-style: decimal; list-style-position: inside; @if($student->studstatus == 3 || $student->studstatus == 5)text-decoration: line-through @endif">{{$student->lastname}}, {{$student->firstname}} {{$student->middlename}}  @if($student->studstatus == 3 || $student->studstatus == 5){{DB::table('studentstatus')->where('id', $student->studstatus)->first()->description}}@endif @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hc babak') @if($esc == 1) @if(strtolower($student->granteedesc) == 'esc') - <button type="button" class="btn btn-sm btn-default btn-each-esccert" data-id="{{$student->id}}"><i class="fa fa-file-pdf text-secondary"></i></button>@endif @endif @endif</li>
                            @endif
                        @endforeach
                    </ol>
                </div>
                <div class="col-md-6">
                    <label>FEMALE</label>
                    <ol>
                        @foreach($students as $student)
                            @if(strtolower($student->gender) == 'female')
                            <li  style="display: list-item;list-style: decimal; list-style-position: inside; @if($student->studstatus == 3 || $student->studstatus == 5)text-decoration: line-through @endif">{{$student->lastname}}, {{$student->firstname}} {{$student->middlename}} @if($student->studstatus == 3 || $student->studstatus == 5){{DB::table('studentstatus')->where('id', $student->studstatus)->first()->description}}@endif @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hc babak') @if($esc == 1) @if(strtolower($student->granteedesc) == 'esc') - <button type="button" class="btn btn-sm btn-default btn-each-esccert" data-id="{{$student->id}}"><i class="fa fa-file-pdf text-secondary"></i></button>@endif @endif @endif</li>
                            @endif
                        @endforeach
                    </ol>
                </div>
            @endif
        </div>
    </div>
    <!-- /.card-body -->
    </div>
    @endif
    <script>
        $('#btn-addnew-note').on('click', function(){
            $('#container-notes').append(
                '<div class="row mt-1"><div class="col-md-10"><textarea class="form-control text-area-note" style="height: 35px !important;"></textarea></div>'+
                '<div class="col-md-2 text-right"><button type="button" class="btn btn-sm btn-outline-success btn-save-note">Save <i class="fa fa-share"></i></button></div></div>'
            )
        })
    </script>