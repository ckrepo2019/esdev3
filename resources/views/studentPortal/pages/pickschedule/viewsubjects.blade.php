
                            
                            @if(count($subjects) >0)
                            
                            <input class="filter form-control" placeholder="Search Subject" />
                            <br/>
                            {{-- <div id="accordion" > --}}
                                @foreach ($subjects as $subject)
                                    <div class="card eachsubject m-0" style="border: none; box-shadow: unset !important;"  data-string="{{$subject->subjectcode}} {{$subject->subjectname}} {{$subject->levelname}} 
                                        {{-- @if($subject->semid == 1) 1ST SEMESTER @elseif($subject->semid == 2) 2ND SEMESTER @endif    --}}
                                         <">
                                        <div class="card-body p-0">
                                            <button type="button" class="btn btn-block text-left m-0" style="border: 1px solid #ddd" data-id="{{$subject->subjectid}}">
                                            {{$subject->subjectcode}} - {{$subject->subjectname}}<br/>
                                            <span class="badge badge-info">{{$subject->levelname}}</span> 
                                            {{-- <span class="badge badge-info">
                                                @if($subject->semid == 1)
                                                    1ST SEMESTER
                                                @elseif($subject->semid == 2)
                                                    2ND SEMESTER
                                                @endif    
                                            </span> --}}
                                        </button>
                                        </div>
                                        {{-- <div class="card-header">
                                            <a class="a-view" data-toggle="collapse" data-parent="#accordion" href="#collasesubjectid{{$subject->subjectid}}" data-id="{{$subject->subjectid}}">
                                            {{$subject->subjectcode}} - {{$subject->subjectname}}
                                            </a>
                                        </div>
                                        <div id="collasesubjectid{{$subject->subjectid}}" class="panel-collapse collapse in">
                                            <div class="card-body" id="eachsubject-schedcontainer{{$subject->subjectid}}">
                                                
                                            </div>
                                        </div> --}}
                                    </div>
                                @endforeach
                            {{-- </div> --}}
                            @endif