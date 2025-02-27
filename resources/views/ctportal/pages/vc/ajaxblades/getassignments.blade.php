<div class="tab-pane fade show active" id="custom-tabs-one-assignments" role="tabpanel" aria-labelledby="custom-tabs-one-assignments-tab">
    <!-- daterange picker -->
    <link rel="stylesheet" href="{{asset('plugins/daterangepicker/daterangepicker.css')}}">
    <div class="row">
        <div class="col-md-12">
            <button type="button" class="btn btn-default" data-toggle="modal" data-target="#create"><i class="fa fa-plus"></i> Create</button>
            <div class="modal fade" id="create" aria-hidden="true" style="display: none;">
                <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title">Create Assignment</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                      </button>
                    </div>
                    <form action="/college/teacher/vc/publishass" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-3 text-center">
                                    <label>Title</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="assignmenttitle" required>
                                </div>
                            </div>
                            <hr/>
                            <div class="row">
                                <div class="col-md-3 text-center">
                                    <label>Description (Optional)</label>
                                </div>
                                <div class="col-md-9">
                                    <textarea class="form-control" rows="3" name="assignmentinstruction"></textarea>
                                </div>
                            </div>
                            <hr/>
                            <div class="row">
                                <div class="col-md-3 text-center">
                                    <label>Submission date</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" class="form-control duedate" name="duedatetime"/>
                                </div>
                            </div>
                            <hr/>
                            <div class="row">
                                <div class="col-md-3 text-center">
                                    <label>Upload file</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="file" class="form-control" name="assignmentfile" accept="application/msword, application/vnd.ms-excel, application/vnd.ms-powerpoint,application/pdf, image/*"/>
                                    <small style="font-size: 11px;color: red"><strong>(MSWord, Excel, PPT, PDF, Image)</strong></small>
                                </div>
                            </div>
                            <hr/>
                            <div class="row">
                                <div class="col-md-3 text-center">
                                    <label>Points</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="number" step="any" class="form-control" name="perfectscore"/>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            
                            <input type="hidden" class="form-control" name="classroomid" value="{{$classroomid}}"/>
                          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                          <button type="submit" class="btn btn-primary">Publish</button>
                        </div>
                    </form>
                  </div>
                  <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
              </div>
        </div>
    </div>
    
    @if(count($assignments)>0)
    <br/>
        <div class="row">
            @foreach($assignments as $classassignment)
                <div class="col-md-12">
                    <div class="card collapsed-card">
                        <div class="card-header bg-warning">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>
                                        {{strtoupper($classassignment->title)}}
                                    </h5>
                                    <small>Date created : {{$classassignment->createddatetime}}</small>
                                    <br/>
                                    <small>Points : {{$classassignment->perfectscore}}</small>
                                </div>
                                <div class="col-md-2">
                                    <small>Turned In : {{count($classassignment->turnedin)}}</small>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-sm btn-info btn-block viewassignment" id="{{$classassignment->id}}">View</button>
                                </div>
                                <div class="col-md-2">
                                    @if(count($classassignment->turnedin) == 0)
                                        <button type="button" class="btn btn-sm btn-success btn-block" disabled>Turned In</button>
                                    @else
                                        <button type="button" class="btn btn-sm btn-success btn-block" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">Turned In</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        @if(count($classassignment->turnedin) > 0)
                        <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="turnedintable table">
                                            <thead>
                                                <tr>
                                                    <th>&nbsp;</th>
                                                    <th>Date submitted</th>
                                                    <th>Score</th>
                                                    <th>&nbsp;</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($classassignment->turnedin as $turnedin)
                                                    <tr>
                                                        <td>
                                                            <strong>{{$turnedin->lastname}}, {{$turnedin->firstname}} {{$turnedin->middlename}} {{$turnedin->suffix}}</strong>
                                                        </td>
                                                        <td>{{$turnedin->createddatetime}}</td>
                                                        <td>{{$turnedin->score}}/{{$classassignment->perfectscore}}</td>
                                                        <td>
                                                            <button type="button" class="btn btn-sm btn-warning btn-block" data-toggle="modal" data-target="#view{{$turnedin->turnedinid}}">View</button>
                                                            <script>
                                                                
                                                                $('.setscore').on('mouseup keyup', function () {
                                                                $(this).val(Math.min('{{$classassignment->perfectscore}}', Math.max(0, $(this).val())));
                                                                });
                                                            </script>
                                                            {{-- <p>File could not open.</p> --}}
                                                        </td>
                                                    </tr>
                                                    {{-- <strong>{{$turnedin->lastname}}, {{$turnedin->firstname}} {{$turnedin->middlename}} {{$turnedin->suffix}}</strong> --}}
                                                {{-- @if($classassignment->extension == 'pdf')
                                                    <object style="width: 100%;height: 500px;"  type="application/pdf" data="{{asset($classassignment->filepath)}}?#view=FitH&scrollbar=0&toolbar=0&navpanes=0">
                                                    <p>Insert your error message here, if the PDF cannot be displayed.</p>
                                                </object>
                                                @else
                                                @endif --}}
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
    
    <div class="modal fade" id="viewassignment" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Assignment</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
              </button>
            </div>
            <form action="/college/teacher/vc/editass" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-3 text-center">
                            <label>Title</label>
                        </div>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="assignmenttitle" id="edittitle" required>
                        </div>
                    </div>
                    <hr/>
                    <div class="row">
                        <div class="col-md-3 text-center">
                            <label>Description (Optional)</label>
                        </div>
                        <div class="col-md-9">
                            <textarea class="form-control" rows="3" name="assignmentinstruction" id="editdescription"></textarea>
                        </div>
                    </div>
                    <hr/>
                    <div class="row">
                        <div class="col-md-3 text-center">
                            <label>Submission date</label>
                        </div>
                        <div class="col-md-9">
                            <input type="text" class="form-control duedate" name="duedatetime" id="editduedatetime"/>
                        </div>
                    </div>
                    <hr/>
                    <div class="row">
                        <div class="col-md-3 text-center">
                            <label>Upload file</label>
                        </div>
                        <div class="col-md-9">
                            <input type="file" class="form-control" name="assignmentfile" accept="application/msword, application/vnd.ms-excel, application/vnd.ms-powerpoint,application/pdf, image/*"/>
                            <small style="font-size: 11px;color: red"><strong>(MSWord, Excel, PPT, PDF, Image)</strong></small>
                        </div>
                    </div>
                    <hr/>
                    <div class="row">
                        <div class="col-md-3 text-center">
                            <label>Points</label>
                        </div>
                        <div class="col-md-9">
                            <input type="number" step="any" class="form-control" name="perfectscore" id="editpoints"/>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    
                    <input type="hidden" class="form-control" name="assignmentid" id="editassignmentid"/>
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
    <!-- InputMask -->
    <script src="{{asset('plugins/moment/moment.min.js')}}"></script>
    <!-- date-range-picker -->
    <script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
    <script>
        
        $('.duedate').daterangepicker({
        timePicker: true,
        timePickerIncrement: 30,
        locale: {
            format: 'MM/DD/YYYY hh:mm A'
        }
        })
        $(document).on('click','.viewassignment', function(){
            $('#viewassignment').modal('show'); 
            $.ajax({
                url: '/college/teacher/vc/getassignmentinfo',
                type: 'GET',
                datatype: 'json',
                data: {
                    assignmentid: $(this).attr('id')
                },
                success: function(data){
                    console.log(data)
                    $('#editassignmentid').val(data.id)
                    $('#edittitle').val(data.title)
                    $('#editdescription').val(data.instruction)
                    $('#editduedatetime').val(data.duefrom+' - '+data.dueto)
                    $('#editpoints').val(data.perfectscore)

                }
            })
        })
    </script>
</div>