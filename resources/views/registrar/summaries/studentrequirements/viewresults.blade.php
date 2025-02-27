
                            <style>
                                .modal-viewdetails .modal{
                                    position: fixed;
                                    top: 0;
                                    right: 0;
                                    bottom: 0;
                                    left: 0;
                                    overflow: hidden;
                                }
                        
                                .modal-viewdetails .modal-dialog {
                                    position: fixed;
                                    margin: 0;
                                    width: 100%;
                                    height: 100%;
                                    padding: 0;
                                }
                                @media (min-width: 576px)
                                {
                                    .modal-viewdetails .modal-dialog {
                                        max-width:  unset !important;
                                        margin: unset !important;
                                    }
                                }
                                .modal-viewdetails .modal-content {
                                    position: absolute;
                                    top: 0;
                                    right: 0;
                                    bottom: 0;
                                    left: 0;
                                    border: 2px solid #3c7dcf;
                                    border-radius: 0;
                                    box-shadow: none;
                                }
                        
                                .modal-viewdetails .modal-header {
                                    position: absolute;
                                    top: 0;
                                    right: 0;
                                    left: 0;
                                    height: 50px;
                                    padding: 10px;
                                    background: #6598d9;
                                    border: 0;
                                }
                        
                                .modal-viewdetails .modal-title {
                                    font-weight: 300;
                                    font-size: 2em;
                                    color: #fff;
                                    line-height: 30px;
                                }
                        
                                .modal-viewdetails .modal-body {
                                    position: absolute;
                                    top: 50px;
                                    bottom: 60px;
                                    width: 100%;
                                    font-weight: 300;
                                    overflow: auto;
                                    background-color: rgba(0,0,0,.0001) !important;
                                }
                                .modal-viewdetails .modal-footer {
                                    position: absolute;
                                    right: 0;
                                    bottom: 0;
                                    left: 0;
                                    height: 60px;
                                    padding: 10px;
                                    background: #f1f3f5;
                                }
                            </style>
                            <table class="table table-bordered table-hover" id="studentstable">
                                <thead>
                                    <tr>
                                        <th style="width: 5%;"></th>
                                        <th style="width: 85%;">Name of Student</th>
                                        {{-- <th style="width: 25%;">Grade Level & Section</th> --}}
                                        {{-- <th style="width: 50%;">Requirements</th> --}}
                                        <th style="width: 10%;">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(count($students)>0)
                                        @foreach($students as $studentkey=>$student)
                                            <tr>
                                                <td>{{$studentkey+1}}</td>
                                                <td>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <strong>{{$student->lastname}}, {{$student->firstname}} {{$student->middlename}} {{$student->suffix}}</strong>
                                                        </div>
                                                        <div class="col-md-6 text-right">
                                                            SID: {{$student->sid}} &nbsp;&nbsp;&nbsp; LRN: {{$student->lrn}}
                                                        </div>
                                                        <div class="col-md-12">
                                                            {{$student->levelname.' - '.$student->sectionname}}<br/>
                                                            @if(count($student->requirements)>0)
                                                                @foreach($student->requirements as $req)
                                                                    <button type="button" class="p-1 btn btn-sm @if($req->submitted == 1) btn-success  @else btn-default @endif btn-uploadphoto" style="font-size: 11px;" data-toggle="modal"  data-target="#modal-viewphoto"  data-reqsid="{{$req->submittedreqid}}" data-studid="{{$student->id}}" data-reqid="{{$req->id}}" data-queuecoderef="{{$student->queuecoderef}}">{{$req->description}}</button>
                                                                @endforeach
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if(collect($student->requirements)->where('status','1')->count() == count($student->requirements))
                                                        <button type="button" class="btn btn-sm btn-block btn-success btn-status mb-2" data-id="{{$student->id}}" data-sid="{{$student->sid}}" data-lrn="{{$student->lrn}}" data-name="{{$student->lastname}}, {{$student->firstname}} {{$student->middlename}} {{$student->suffix}}">COMPLETE</button>
                                                    @else
                                                        <button type="button" class="btn btn-sm btn-block btn-default btn-status mb-2" data-id="{{$student->id}}" data-sid="{{$student->sid}}" data-lrn="{{$student->lrn}}" data-name="{{$student->lastname}}, {{$student->firstname}} {{$student->middlename}} {{$student->suffix}}">INCOMPLETE</button>
                                                    @endif
                                                    <button type="button" class="btn btn-default btn-sm btn-block btn-viewdetails" data-toggle="modal"  data-target="#modal-viewdetails{{$student->id}}" data-studid="{{$student->id}}" data-queuecoderef="{{$student->queuecoderef}}">View</button>
                                                    <div class="modal-viewdetails modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="modal-viewdetails{{$student->id}}">
                                                        <div class="modal-dialog modal-md">
                                                            <div class="modal-content">
                                                                <div class="modal-header bg-info">
                                                                    <h5 class="modal-title" id="exampleModalLongTitle">Submitted Photos</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body" id="photos-container{{$student->id}}">
                                                                </div>
                                                                <div class="modal-footer justify-content-between">
                                                                    <button type="button" class="btn btn-secondary btn-view-close" data-dismiss="modal">Close</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
{{--                             
                            <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
                            <!-- fullCalendar 2.2.5 -->
                            <script src="{{asset('plugins/moment/moment.min.js')}}"></script>
                            <script src="{{asset('plugins/fullcalendar/main.min.js')}}"></script>
                            <script src="{{asset('plugins/fullcalendar-daygrid/main.min.js')}}"></script>
                            <script src="{{asset('plugins/fullcalendar-timegrid/main.min.js')}}"></script>
                            <script src="{{asset('plugins/fullcalendar-interaction/main.min.js')}}"></script>
                            <script src="{{asset('plugins/fullcalendar-bootstrap/main.min.js')}}"></script>
                            <!-- Bootstrap 4 -->
                            <script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script> --}}
                            <!-- DataTables -->
                            <script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>
                            <!-- ChartJS -->
                            {{-- <script src="{{asset('plugins/chart.js/Chart.min.js')}}"></script> --}}
                            <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
                            {{-- <script src="{{asset('plugins/sweetalert2/sweetalert2.all.min.js')}}"></script> --}}
                            <script>
                                                        
                                var table = $("#studentstable").DataTable({
                                    pageLength : 10
                                    // lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Show All']]
                                    // "bPaginate": false,
                                    // "bInfo" : false,
                                    // "bFilter" : false,
                                    // "order": [[ 1, 'asc' ]]
                                });
                                table.on( 'order.dt search.dt', function () {
                                    table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                                        cell.innerHTML = i+1;
                                    } );
                                } ).draw();
                            </script>