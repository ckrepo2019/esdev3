
                <div class="modal-header">
                    <h4 class="modal-title" id="timelogs-employeename">{{$employeeinfo->lastname}}, {{$employeeinfo->firstname}}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">×</span>
                    </button>
                  </div>
                  <div class="modal-body" id="timelogs-employeename">
                    <div class="row">
                      <div class="col-md-12 text-center">
                        <label>Custom Time Sched</label>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-3 text-center">
                        <p><strong>{{$customtimesched[0]->amin}}</strong></p>
                        <sup>AM IN</sup>

                      </div>
                      <div class="col-md-3 text-center">
                        <p><strong>{{$customtimesched[0]->amout}}</strong></p>
                        <sup>AM OUT</sup>

                      </div>
                      <div class="col-md-3 text-center">
                        <p><strong>{{$customtimesched[0]->pmin}}</strong></p>
                        <sup>PM IN</sup>

                      </div>
                      <div class="col-md-3 text-center">
                        <p><strong>{{$customtimesched[0]->pmout}}</strong></p>
                        <sup>PM OUT</sup>

                      </div>
                    </div>
                    {{-- <br/> --}}
                    @if(count($logs) > 0)
                        @foreach($logs as $log)
                          <div class="row">
                            @if(strtolower($log->tapstate) == 'in')
                                @php
                                    $badge = 'badge-success';
                                @endphp
                            @else
                                @php
                                    $badge = 'badge-danger';
                                @endphp
                            @endif
                            @if($log->mode == 0)
                            <div class="col-1 mb-2 text-left">
                              <button type="button" class="btn btn-sm btn-danger deletelogtap" id="{{$log->id}}"><i class="fa fa-trash"></i></button>
                            </div>
                              <div class="col-6">
                                {{-- <button type="button" class="btn btn-sm btn-default">&nbsp;<i class="fa fa-plus"></i>&nbsp;</button>  --}}
                                <button type="button" class="btn btn-sm btn-default btn-block">{{$log->ttime}}</button> 
                                {{-- <input type="time" class="form-control form-control-sm" name="newtimelog" value="{{ \Carbon\Carbon::parse($log->ttime)->format('H:i') }}" readonly/> --}}
                              </div>
                              <div class="col-2 mb-2 text-center pt-2" style="display: contents">
                                {{-- <span class="right badge {{$badge}}">{{$log->tapstate}}</span> --}}
                                &nbsp;
                                <div class="form-group form-check">
                                  <input type="checkbox" class="form-check-input tappingedit" id="tapin" timestatus="IN" logid="{{$log->id}}" style="width: 18px;height: 18px;" @if ($log->tapstate == 'IN') checked @endif>
                                  <label class="form-check-label" for="in"><strong>IN</strong></label>
                                </div> &nbsp;&nbsp;
                                <div class="form-group form-check">
                                  <input type="checkbox" class="form-check-input tappingedit" id="tapout" timestatus="OUT" logid="{{$log->id}}" style="width: 18px;height: 18px;" @if ($log->tapstate == 'OUT') checked @endif>
                                  <label class="form-check-label" for="out"><strong>OUT</strong></label>
                                </div>
                              </div>
                              <div class="col-3text-center pt-2">&nbsp;
                              </div>
                            @else
                            <div class="col-1 text-center pb-2">
                              <button type="button" class="btn btn-sm btn-danger deletelog" id="{{$log->id}}"><i class="fa fa-trash"></i></button>
                            </div>
                            <div class="col-6">
                                {{-- <button type="button" class="btn btn-sm btn-default">&nbsp;<i class="fa fa-plus"></i>&nbsp;</button>  --}}
                                <button type="button" class="btn btn-sm btn-default btn-block">{{$log->ttime}}</button> 
                            </div>
                              <div class="col-2 text-center pt-2"><span class="right badge {{$badge}}">{{$log->tapstate}}</span>
                              </div>
                              <div class="col-3 text-center pt-2">&nbsp;
                              </div>
                            @endif
                        </div>
                        @endforeach
                    @endif
                    <div id="newlogscontainer" employeeid="{{$employeeinfo->id}}" usertypeid="{{$employeeinfo->usertypeid}}"></div>
                    <div class="row">
                        <div class="col-12">
                            <button type="button" class="btn btn-default btn-sm" id="buttonaddnewlog"><i class="fa fa-plus"></i> Time Log</button>
                        </div>
                    </div>
                    <div class="row mt-2">
                      <div class="col-md-12">
                        <label>Remarks</label>
                        <input type="text" class="form-control employee-remarks" @if($remarks)value="{{$remarks->remarks}}"@endif data-id="{{$employeeinfo->id}}"/>
                        <sup>Note: Press <strong>ENTER</strong> to submit remarks.</sup>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
                  </div>

                  <script>
                    $(document).ready(function () {
                      
                      const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                      });
                        $(document).on('click', '#tapin', function(){
                            $('#tapout').prop('checked', false)
                        })
                        $(document).on('click', '#tapout', function(){
                            $('#tapin').prop('checked', false)
                        })
                      
                        // Handle checkbox click event
                        $('.tappingedit').on('change', function () {
                            if ($(this).is(':checked')) {
                                var timestatus = $(this).attr('timestatus');
                                var logid = $(this).attr('logid');
                                var empid = $('#newlogscontainer').attr('employeeid');
                                
                                $.ajax({
                                  url: "/hr/attendance/updatetapstate",
                                  type: "get",
                                  data: {
                                    logid : logid,
                                    timestatus : timestatus,
                                    empid : empid
                                  },
                                  success: function (data) {
                                    if(data[0].status == 0){
                                      Toast.fire({
                                        type: 'error',
                                        title: data[0].message
                                      })
                                    }else{
                                      Toast.fire({
                                        type: 'success',
                                        title: data[0].message
                                      })
                                    }
                                  }
                                }); 

                            } else {
                                // Checkbox is unchecked
                                console.log('Checkbox unchecked.');
                                // Perform additional actions if needed
                            }
                        });

                    });
                </script>