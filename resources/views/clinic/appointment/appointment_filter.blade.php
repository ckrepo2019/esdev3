
                    @if(count($appointments)>0)
                        @foreach($appointments as $key=>$appointment)
                        @if($appointment->admitted == 1)
                        <tr >
                        @else
                        <tr style="background-color: #ffc92c6b;">
                        @endif
                       
                                <td>
                                    {{$key+1}}
                                </td>
                                <td class="text-left">
                                    <a>
                                        {{$appointment->name_showlast}}
                                        {{-- <span class="badge badge-info float-right mt-21">{{$appointment->utype}}</span> --}}
                                    </a>
                                    <br/>
                                    <small class="text-left">
                                        {{-- <strong>Time Slot: {{date('M d, Y', strtotime($appointment->adate))}} @if($appointment->atime == null) @else {{date('h:m A', strtotime($appointment->atime))}}  @endif</strong><br/> --}}
                                        <span  class="float-left">Submitted: {{date('M d, Y', strtotime($appointment->createddatetime))}}</span>
                                    </small>
                                </td>
                                <td>
                                    {{date('M d, Y', strtotime($appointment->adate))}} @if($appointment->atime == null) @else <br/> {{date('h:m A', strtotime($appointment->atime))}}  @endif
                                    {{-- <small>{{$appointment->description}}</small> --}}
                                    {{-- <ul class="list-inline">
                                        <li class="list-inline-item">
                                            <img alt="Avatar" class="table-avatar" src="../../dist/img/avatar.png">
                                        </li>
                                        <li class="list-inline-item">
                                            <img alt="Avatar" class="table-avatar" src="../../dist/img/avatar2.png">
                                        </li>
                                        <li class="list-inline-item">
                                            <img alt="Avatar" class="table-avatar" src="../../dist/img/avatar3.png">
                                        </li>
                                        <li class="list-inline-item">
                                            <img alt="Avatar" class="table-avatar" src="../../dist/img/avatar4.png">
                                        </li>
                                    </ul> --}}
                                </td>
                                <td>
                                    {{$appointment->description}}
                                </td>
                                <td class="project-state" style="font-size: 14px;">
                                    @if($appointment->admitted == 1)
                                        <span class="badge badge-success">Approved</span>
                                    @else
                                        <span class="badge badge-warning btn-appointmentadmit"  data-id="{{$appointment->id}}" style="cursor: pointer;">Pending</span>
                                    @endif
                                </td>
                                <td class="project-state">
                                    @if(count($appointment->doctors)>0)
                                        @if($appointment->admitted == 1)
                                            @foreach($appointment->doctors as $doctor)
                                                @if($appointment->docavailabilityid == $doctor->timeid) 
                                                    {{$doctor->lastname}}, {{$doctor->firstname}}
                                                    @if($doctor->available == 0) - Not Available @endif
                                                @endif
                                            @endforeach
                                        @else
                                        <select class="form-control form-control-sm select-doctor">
                                            @foreach($appointment->doctors as $doctor)
                                                <option value="{{$doctor->id}}-{{$doctor->timeid}}">{{$doctor->lastname}}, {{$doctor->firstname}}
                                                    @if($doctor->available == 1) - Available 
                                                    @else -Not Available
                                                </option>
                                            @endif
                                            @endforeach
                                        </select>
                                        @endif
									@else
										<select class="form-control form-control-sm select-doctor">
											<option value="0-0" selected="seleted">No Doctor Available</option>
										</select>
                                    @endif
                                    @if($appointment->admitted == 0 )
                                        <td>
                                        <button type="button" class="btn btn-sm btn-default btn-appointmentadmit" data-id="{{$appointment->id}}"><i class="fa fa-check"></i> Admit</button>
                                        </td>
                                    @else
                                        <td>
                                        </td>
                                     @endif


                                    {{-- @if($appointment->admitted == 1)
                                        @if($appointment->admittedby == auth()->user()->id)
                                            @if($appointment->adate == date('Y-m-d'))
                                                @if($appointment->label == 0)
                                                <span class="badge badge-info">Today</span>
                                                @else
                                                    <span class="badge badge-info">Done</span>
                                                @endif
                                            @elseif($appointment->adate > date('Y-m-d'))
                                                <button type="button" class="btn btn-sm btn-default btn-appointmentcancel" data-id="{{$appointment->id}}"><i class="fa fa-reset"></i> Drop</button>
                                            @endif
                                        @else
                                            <small>{{$appointment->appointedname}}</small>
                                        @endif
                                    @else
                                        <button type="button" class="btn btn-sm btn-default btn-appointmentadmit" data-id="{{$appointment->id}}"><i class="fa fa-check"></i> Admit</button>
                                    @endif --}}
                                </td>
                            </tr>
                        @endforeach
                    @endif

                <script>

                $(document).ready(function(){    
                    $('.btn-save').on('click', function(){
                        var id = $(this).attr('id');
                        console.log(id);

                        Swal.fire({
                            title: 'You are going to save this appointment.',
                            text: 'Would you like to continue?',
                            type: 'info',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Continue'
                                })

                        })
                    });


                </script>