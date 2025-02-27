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
    @if($acadprogid == 5)
    @php
        $strands = collect($students)->groupBy('strandcode');
    @endphp
            <div class="card card-success card-eachsection">
                <div class="card-body" style="overflow: scroll;">
                    @if(count($students) == 0)
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-primary" role="alert">
                                    No students enrolled!
                                    </div>
                            </div>
                        </div>
                    @else
                        <div class="row">
                            <div class="col-md-12 mb-2 text-right">
                                <form action="/registar/schoolforms/ssf" target="_blank" id="exportform" class="m-0 p-0">
                                    <input type="hidden" name="action" id="print"/>
                                    <input type="hidden" name="exporttype" id="exporttype"/>
                                    <input type="hidden" name="levelid" value="{{$levelid}}"/>
                                    <input type="hidden" name="syid" value="{{$syid}}"/>
                                    <input type="hidden" name="semid" value="{{$semid}}"/>
                                    <input type="hidden" name="sectionid" value="{{$sectionid}}"/>
                                    <button type="button" class="btn btn-default btn-sm btn-export" id="exportexcel">Export to EXCEL</button>
                                    <input type="hidden" value="{{$esc}}" name="esc"/>
                                </form>
                            </div>
                            <div class="col-md-12">
                                <table class="table table-bordered" style="font-size: 12px; table-layout: fixed;">
                                    <thead>
                                        <tr>
                                            {{-- <th rowspan="2" style=""></th> --}}
                                            <th rowspan="2" style="width: 10%;">LRN</th>
                                            <th rowspan="2" style="width: 25%;">NAME</th>
                                            {{-- <th rowspan="2">Gender</th> --}}
                                            <th rowspan="2">ESC/<br/>PAYING</th>
                                            <th colspan="6" class="text-center">GRADES</th>
                                            <th rowspan="2" style="width: 10%;">STATUS</th>
                                        </tr>
                                        <tr>
                                            <th>1ST</th>
                                            <th>2ND</th>
                                            <th>AVE</th>
                                            <th>3RD</th>
                                            <th>4TH</th>
                                            <th>AVE</th>
                                        </tr>
                                    </thead>
                                    <tbody>                            
                                        @foreach($students as $student)
                                            @if(strtolower($student->gender) == 'male')
                                                <tr>
                                                    <td>{{$student->lrn}}</td>
                                                    <td>{{$student->lastname}}, {{$student->firstname}} {{$student->middlename}}</td>
                                                    <td>{{$student->granteedesc}}</td>
                                                    <td>{{$student->q1}}</td>
                                                    <td>{{$student->q2}}</td>
                                                    <td>{{$student->fcomp1}}</td>
                                                    <td>{{$student->q3}}</td>
                                                    <td>{{$student->q4}}</td>
                                                    <td>{{$student->fcomp2}}</td>
                                                    <td>{{$student->status}}</td>
                                                </tr>
                                            @endif
                                        @endforeach     
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                        </tr>    
                                        @foreach($students as $student)
                                            @if(strtolower($student->gender) == 'female')
                                                <tr>
                                                    <td>{{$student->lrn}}</td>
                                                    <td>{{$student->lastname}}, {{$student->firstname}} {{$student->middlename}}</td>
                                                    <td>{{$student->granteedesc}}</td>
                                                    <td>{{$student->q1}}</td>
                                                    <td>{{$student->q2}}</td>
                                                    <td>{{$student->fcomp1}}</td>
                                                    <td>{{$student->q3}}</td>
                                                    <td>{{$student->q4}}</td>
                                                    <td>{{$student->fcomp2}}</td>
                                                    <td>{{$student->status}}</td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>
        </div>
    @else
    <div class="card card-success card-eachsection">
    <div class="card-body" style="overflow: scroll;">
        @if(count($students) == 0)
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-primary" role="alert">
                        No students enrolled!
                        </div>
                </div>
            </div>
        @else
            <div class="row">
                <div class="col-md-12 mb-2 text-right">
                    <form action="/registar/schoolforms/ssf" target="_blank" id="exportform" class="m-0 p-0">
                        <input type="hidden" name="action" id="print"/>
                        <input type="hidden" name="exporttype" id="exporttype"/>
                        <input type="hidden" name="levelid" value="{{$levelid}}"/>
                        <input type="hidden" name="syid" value="{{$syid}}"/>
                        <input type="hidden" name="sectionid" value="{{$sectionid}}"/>
                        <button type="button" class="btn btn-default btn-sm btn-export" id="exportexcel">Export to EXCEL</button>
                        <input type="hidden" value="{{$esc}}" name="esc"/>
                    </form>
                    {{-- <button type="button" class="btn btn-default btn-sm btn-exportpdf">Export to PDF</button> --}}
                </div>
                <div class="col-md-12">
                    <table class="table table-bordered" style="font-size: 12px; table-layout: fixed;">
                        <thead>
                            <tr>
                                {{-- <th rowspan="2" style=""></th> --}}
                                <th rowspan="2" style="width: 10%;">LRN</th>
                                <th rowspan="2" style="width: 25%;">NAME</th>
                                {{-- <th rowspan="2">Gender</th> --}}
                                <th rowspan="2">ESC/<br/>PAYING</th>
                                <th colspan="5" class="text-center">GRADES</th>
                                <th rowspan="2" style="width: 10%;">STATUS</th>
                            </tr>
                            <tr>
                                <th>1ST</th>
                                <th>2ND</th>
                                <th>3RD</th>
                                <th>4TH</th>
                                <th>GEN.<br/>AVE</th>
                            </tr>
                        </thead>
                        <tbody>                            
                            @foreach($students as $student)
                                @if(strtolower($student->gender) == 'male')
                                    <tr>
                                        <td>{{$student->lrn}}</td>
                                        <td>{{$student->lastname}}, {{$student->firstname}} {{$student->middlename}}</td>
                                        <td>{{$student->granteedesc}}</td>
                                        <td>{{$student->q1}}</td>
                                        <td>{{$student->q2}}</td>
                                        <td>{{$student->q3}}</td>
                                        <td>{{$student->q4}}</td>
                                        <td>{{$student->finalrating}}</td>
                                        <td>{{$student->status}}</td>
                                    </tr>
                                @endif
                            @endforeach     
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>    
                            @foreach($students as $student)
                                @if(strtolower($student->gender) == 'female')
                                    <tr>
                                        <td>{{$student->lrn}}</td>
                                        <td>{{$student->lastname}}, {{$student->firstname}} {{$student->middlename}}</td>
                                        <td>{{$student->granteedesc}}</td>
                                        <td>{{$student->q1}}</td>
                                        <td>{{$student->q2}}</td>
                                        <td>{{$student->q3}}</td>
                                        <td>{{$student->q4}}</td>
                                        <td>{{$student->finalrating}}</td>
                                        <td>{{$student->status}}</td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
        {{-- <div class="row">
            @if(count($students) == 0)
                <div class="col-md-12">
                    <div class="alert alert-primary" role="alert">
                        No students enrolled!
                        </div>
                </div>
            @else
                <div class="col-md-12 mb-2 text-right">
                    <form action="/reports_studentmasterlist/print/{{$syid}}/{{$sectionid}}" target="_blank" id="exportform" class="m-0 p-0">
                        <input type="hidden" name="exporttype" id="exporttype"/>
                        <input type="hidden" name="levelid" value="{{$levelid}}"/>
                    <button type="button" class="btn btn-default btn-sm btn-export" id="exportpdf">Export to PDF</button>
                    <button type="button" class="btn btn-default btn-sm btn-export" id="exportexcelinfo">Export to EXCEL (INFO)</button>
                    <button type="button" class="btn btn-default btn-sm btn-export" id="exportexcellist">Export to EXCEL (LIST)</button>
                    <input type="hidden" value="{{$esc}}" name="esc"/>
                </form>
                </div>
                <div class="col-md-12">
                    <table class="table">
                        <thead>
                            <tr>
                                <th rowspan="2"></th>
                                <th rowspan="2">LRN</th>
                                <th rowspan="2">NAME</th>
                                <th rowspan="2">Gender</th>
                                <th rowspan="2">ESC/PAYING</th>
                                <th colspan="5">GRADES</th>
                                <th rowspan="2">STATUS</th>
                                <th rowspan="2">TRANSFER TO</th>
                                <th rowspan="2">REASON-DATE</th>
                            </tr>
                            <tr>
                                <th>1ST</th>
                                <th>2ND</th>
                                <th>3RD</th>
                                <th>4TH</th>
                                <th>GEN.AVE</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div class="col-md-6">
                    <label>MALE</label>
                    <ol>
                        @foreach($students as $student)
                            @if(strtolower($student->gender) == 'male')
                                <li  style="display: list-item;list-style: decimal; list-style-position: inside; @if($student->studstatus == 3 || $student->studstatus == 5)text-decoration: line-through @endif">{{$student->lastname}}, {{$student->firstname}} {{$student->middlename}} @if($student->studstatus == 3 || $student->studstatus == 5){{DB::table('studentstatus')->where('id', $student->studstatus)->first()->description}}@endif</li>
                            @endif
                        @endforeach
                    </ol>
                </div>
                <div class="col-md-6">
                    <label>FEMALE</label>
                    <ol>
                        @foreach($students as $student)
                            @if(strtolower($student->gender) == 'female')
                            <li  style="display: list-item;list-style: decimal; list-style-position: inside; @if($student->studstatus == 3 || $student->studstatus == 5)text-decoration: line-through @endif">{{$student->lastname}}, {{$student->firstname}} {{$student->middlename}} @if($student->studstatus == 3 || $student->studstatus == 5){{DB::table('studentstatus')->where('id', $student->studstatus)->first()->description}}@endif</li>
                            @endif
                        @endforeach
                    </ol>
                </div>
            @endif
        </div> --}}
    </div>
    <!-- /.card-body -->
    </div>
    @endif