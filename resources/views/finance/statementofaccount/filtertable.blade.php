
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <button class="btn btn-sm btn-warning" ><strong>{{count($students)}}</strong> Students</button>
                        </div>
                        <div class="col-md-6 text-right">
                            {{--<button type="button" class="btn btn-default btn-export-all" exporttype="pdf">
                                <i class="fa fa-file-pdf"></i> Export to PDF
                            </button>--}}
                            {{-- <button type="button" class="btn btn-default btn-export-all" exporttype="excel">
                                <i class="fa fa-file-excel"></i> Export to Excel
                            </button>--}}
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
                                        <th style="width: 5%;">SID</th>
                                        <th>Student</th>
                                    </tr>
                                </thead>
                                <tbody id="soa_body">
                                    @if(count($students)>0)
                                        @foreach($students as $student)
                                            <tr>
                                                <td>{{$student->sid}}</td>
                                                <td class="p-0">
                                                    <div class="card collapsed-card p-0 mb-0" style="border: none !important; background-color: unset !important;box-shadow: none !important;">
                                                    <div class="card-header">
                                                      <h3 class="card-title">{{$student->lastname}}, {{$student->firstname}} {{$student->middlename}} {{$student->suffix}}</h3>
                                      
                                                      <div class="card-tools">
                                                        <button type="button" class="btn btn-tool viewdetails" data-card-widget="collapse" id="{{$student->id}}">View
                                                        </button>
                                                        @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'xai' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'ndsc')
                                                        @else
                                                        {{-- <button type="button" class="btn btn-tool printstatementofacct"  exporttype="excel" studid="{{$student->id}}">Excel
                                                        </button> --}}
                                                        @endif
                                                        <button type="button" class="btn btn-tool printstatementofacct"  exporttype="pdf" studid="{{$student->id}}">PDF
                                                        </button>
                                                      </div>
                                                      <!-- /.card-tools -->
                                                    </div>
                                                    <!-- /.card-header -->
                                                    <div class="card-body" style="display: none;" id="stud{{$student->id}}">
                                                    </div>
                                                    <!-- /.card-body -->
                                                  </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>