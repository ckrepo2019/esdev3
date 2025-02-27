@if(auth()->user()->type == 2)

    @php
        $xtend = 'principalsportal.layouts.app2';
    @endphp

@else
    @php
        $refid = DB::table('usertype')->where('id',auth()->user()->type)->where('deleted',0)->select('refid')->first();
    @endphp
    
    @if( $refid->refid == 20)
        @php
            $xtend = 'principalassistant.layouts.app2';
        @endphp
     @elseif( $refid->refid == 22)
        @php
            $xtend = 'principalcoor.layouts.app2';
        @endphp
    @endif
@endif

@extends($xtend)



@section('pagespecificscripts')


    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{asset('css/pagination.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">

    <style>
        * {
          box-sizing: border-box;
        }
        
        body {
          background-color: #f1f1f1;
        }
        
        #regForm {
          background-color: transparent;
          margin: 10px auto;
          padding: 10px;
          width: 90%;
          min-width: 300px;
        }
        
        h1 {
          text-align: center;  
        }
        h6 {
            letter-spacing: 1px;
            font-weight: 900;
        }
        
        input {
          padding: 10px;
          width: 100%;
          font-size: 17px;
          border: 1px solid #aaaaaa;
        }
        
        /* Mark input boxes that gets an error on validation: */
        input.invalid {
          background-color: #ffdddd;
        }
        
        /* Hide all steps by default: */
        .tab {
          display: none;
        }
        
        button {
          background-color: #4CAF50;
          color: #ffffff;
          border: none;
          padding: 10px 20px;
          font-size: 17px;
          cursor: pointer;
        }
        
        button:hover {
          opacity: 0.8;
        }
        
        #prevBtn {
          background-color: #bbbbbb;
        }
        
        /* Make circles that indicate the steps of the form: */
        .step {
          height: 15px;
          width: 15px;
          margin: 0 2px;
          background-color: #bbbbbb;
          border: none;  
          border-radius: 50%;
          display: inline-block;
          opacity: 0.5;
        }
        
        .step.active {
          opacity: 1;
        }
        
        /* Mark the steps that are finished and valid: */
        .step.finish {
          background-color: #4CAF50;
        }

        .si,.sgs{
            border-bottom: none!important;
            box-shadow: 1px 1px 5px #000;
        }
        
    </style>

    

@endsection


@section('modalSection')
<!-- start gian -->
    @if(Crypt::decrypt($acadid) != 5)
        <div class="modal fade" id="modal-default" style="display: none;" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <form id="regForm" action="/insertSubject" method="GET">
                            <input id="acad" name="acad" type="hidden" value="{{$acadid}}">

                            <!--  -->
                            
                            <div class="modal-header si bg-info">
                                <span class="text-right" style="font-size: 14px;"><b>ADD SUBJECT</b></span>
                                <span class="text-right" style="font-size: 14px; position: absolute; right: 15%"><b> 
                                @if($acadprogs[0]->id == 3)
                                    <span class="text-warning" style="text-shadow: 1px 1px 2px #000">GRADE SCHOOL</span>             
                                @elseif($acadprogs[0]->id == 2)  
                                    <span class="text-warning" style="text-shadow: 1px 1px 2px #000">PRE SCHOOL</span>   
                                @elseif($acadprogs[0]->id == 4)     
                                    <span class="text-warning" style="text-shadow: 1px 1px 2px #000">JUNIOR HIGH SCHOOL</span>
                                @elseif($acadprogs[0]->id == 5)     
                                    <span class="text-warning" style="text-shadow: 1px 1px 2px #000">SENIOR HIGH SCHOOL</span>
                                @endif

                                </b></span>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                </button>
                            </div>
                                <br>
                                <h6 class="modal-title text-success pb-2"><span class="text-danger"></span> SUBJECT INFORMATION</h6>
                                <div class="form-group">
                                    <label>Subject</label>
                                    <input value="{{old('sn')}}" class="form-control @error('sn') is-invalid @enderror" id="sn"  name="sn" placeholder="Subject Name" onkeyup="this.value = this.value.toUpperCase();" oninput="myFunction1()">
                                    @if($errors->has('sn'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('sn') }}</strong>
                                        </span>
                                    @endif

                                    
                                </div>
                                <div class="form-group">
                                    <label>Subject Code</label>
                                    <input value="{{old('sc')}}" class="form-control @error('sc') is-invalid @enderror" id="sc"  name="sc" placeholder="Subject Code" onkeyup="this.value = this.value.toUpperCase();">
                                    @if($errors->has('sc'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('sc') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <br>
                            
                                <div class="form-group">
                                <hr>
                                <h6 class="modal-title pb-2"><b><span class="text-success" id="subjectt1" style="text-transform: uppercase"></span> GRADE SETUP</b></h6>
                                <p class="text-primary" style="text-align: justify">Written works, Performance task and Quarterly Assessment is in percentage form and should equal to 100%</p>
                                <div class="row ">
                                <div class="col-lg-9">
                                <div class="col-md-12">
                                        <div class="form-group mb-0">
                                            <label><i class="fas fa-file-alt text-warning"></i> Written Works</label>
                                            <input id="sww" value="{{old('ww')}}" type="text" class="form-control" id="ww" name="ww" placeholder="Written Works %" min="1" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                                        </div>
                                    </div>
                                    <br>
                                    <div class="col-md-12">
                                        <div class="form-group mb-0">
                                            <label><i class="fas fa-tasks text-warning"></i> Performance Task</label>
                                            <input id="spt" value="{{old('pt')}}" type="text" class="form-control" id="pt"  name="pt"  placeholder="Performance Task %" min="1" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                                        </div>
                                    </div>
                                    <br>
                                    <div class="col-md-12">
                                        <div class="form-group mb-0">
                                            <label><i class="fas fa-chart-line text-warning"></i> Quarter Assessment</label>
                                            <input id="sqa" value="{{old('qa')}}" type="text" class="form-control" id="qa"  name="qa"  placeholder="Quarter Assessment %" " min="1" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group mb-0">
                                            <input type="hidden" class="form-control @error('total') is-invalid @enderror">
                                            @if($errors->has('total'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('total') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                               
                                <div class="col-lg-3">
                                
                                <div class="form-group clearfix">
                                <label>Quarter</label>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" id="q1" name="q[]" value="1" checked>
                                                <label for="q1">Q1
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" id="q2"  name="q[]" value="2" checked>
                                                <label for="q2">Q2
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" id="q3"  name="q[]" value="3" checked>
                                                <label for="q3">Q3
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" id="q4"  name="q[]" value="4" checked>
                                                <label for="q4">Q4
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    @if (\Session::has('q'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{\Session::get('q')->message}}</strong>
                                        </span>
                                    @endif
                                </div>
                                </div>
                                </div>
                               
                                <br>
                                </div>
                             
                                <hr>
                                <h6 class="modal-title pb-2"><b>APPLY <span class="text-success" id="subjectt2" style="text-transform: uppercase"></span> TO GRADE LEVELS</b></h6>
                                <p class="text-primary">Select grade level to apply subject grade setup. <i class="text-danger">You can select multiple grade level.</i></p>
                                <div class="form-group">
                                    <label>Grade Level(s)</label>
                                    <select class="form-control select2  @error('gradelevel') is-invalid @enderror" id="gradelevel" name=gradelevel[] multiple="multiple" data-placeholder="Select grade level" style="width: 100%;">
                                        @foreach (App\Models\Principal\SPP_Gradelevel::getGradeLevel(null,null,null,null,$acadid)[0]->data as $item)
                                            @php
                                                $withData = false;
                                            @endphp
                                            @if($errors->any())
                                                @if(old('gradelevel')!=null)
                                                    @foreach(old('gradelevel') as $glitem)
                                                        @if($glitem==$item->id)
                                                            <option selected value="{{$item->id}}">{{$item->levelname}}</option>
                                                            @php
                                                                $withData = true;
                                                            @endphp
                                                        @endif
                                                    @endforeach
                                                @endif
                                            @endif
                                            @if(!$withData)
                                                <option value="{{$item->id}}">{{$item->levelname}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @if($errors->has('gradelevel'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('gradelevel') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <hr>
                                <div class="form-group">
                                    <div class="icheck-success d-inline">
                                        <input type="checkbox" id="insf9"  name="insf9" value="1" checked>
                                        <label for="insf9">School Form 9</label>
                                    </div>
                                    <p class="text-danger"><i>Uncheck School Form 9 for subjects that are not included in School Form 9</i></p>
                                </div>
                                <hr>
                                <div class="form-group">
                                    <div class="icheck-success d-inline">
                                        <input type="checkbox" id="inMAPEH"  name="inMAPEH" value="1">
                                        <label for="inMAPEH">MAPEH</label>
                                    </div>
                                    <p class="text-danger"><i>Check MAPEH for subjects that are included in MAPEH subject</i></p>
                                </div>
                                <hr>
                                <h6 class="modal-title text-success pb-2">FORM COMPLETE</h6>
                                <p>Click <span class="text-success">Save</span> to create subject : <b><span class="text-success" id="subjectt3" style="text-transform: uppercase"></span></b></p>
                                <br>
                                <button type="button" class="btn closemodal btn-danger" data-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-success studgradesetup" >Save</button>
                         
                        </form>
                    </div>
                </div>
        
            </div>
        </div>
    @else
        <div class="modal fade" id="modal-sh" style="display: none;" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="regForm" action="/insertSubject" method="GET">
                        <input id="acad" name="acad" type="hidden" 
                            value="{{$acadid}}"
                        >
                        <!-- <div class="tab">  -->
                        <div class="modal-header bg-info sgs">
                                    <span class="text-right" style="font-size: 14px;"><b>ADD SUBJECT</b></span>
                                    <span class="text-right" style="font-size: 14px; position: absolute; right: 15%"><b> 
                                    @if($acadprogs[0]->id == 3)
                                    <span class="text-warning" style="text-shadow: 1px 1px 2px #000">GRADE SCHOOL</span>             
                                    @elseif($acadprogs[0]->id == 2)  
                                        <span class="text-warning" style="text-shadow: 1px 1px 2px #000">PRE SCHOOL</span>   
                                    @elseif($acadprogs[0]->id == 4)     
                                        <span class="text-warning" style="text-shadow: 1px 1px 2px #000">JUNIOR HIGH SCHOOL</span>
                                    @elseif($acadprogs[0]->id == 5)     
                                        <span class="text-warning" style="text-shadow: 1px 1px 2px #000">SENIOR HIGH SCHOOL</span>
                                    @endif
                                    </b></span>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                </button>
                            </div>
                            <br>
                            <h6 class="modal-title text-success pb-2">SUBJECT INFORMATION</h6>
                            
                            <div class="form-group">
                                <label>Subject</label>
                                <input value="{{old('sn')}}" class="form-control @error('sn') is-invalid @enderror" id="shsn" oninput="myFunction2()" name="sn" placeholder="Subject Name" onkeyup="this.value = this.value.toUpperCase();">
                                @if($errors->has('sn'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('sn') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Subject Code</label>
                                        <input value="{{old('sc')}}" class="form-control @error('sc') is-invalid @enderror"  id="shsc"  name="sc" placeholder="Subject Code" onkeyup="this.value = this.value.toUpperCase();">
                                        @if($errors->has('sc'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('sc') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Type</label>
                                        <select id="type" name="type" class="form-control  @error('type') is-invalid @enderror" >
                                            <option value="" selected >Select Type</option>
                                            @foreach(App\Models\Principal\SPP_SubjectType::loadSubjectType() as $item)
                                                <option {{ old('type') == $item->id ? 'selected' : '' }} value="{{$item->id}}">{{$item->description}}</option>
                                            @endforeach
                                        </select>
                                        @if($errors->has('type'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('type') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        
                            {{-- <label>Strand</label>
                            <select id="strand" name="strand" class="form-control @error('strand') is-invalid @enderror" >
                                <option value="" selected >SELECT STRAND</option>
                                @foreach(App\Models\Principal\SPP_Strand::loadSHStrands() as $item)
                                    @php
                                        $strand = null;
                                        if(old('strand')) {
                                            $strand = Crypt::decrypt(old('strand'));
                                        }
                                    @endphp
                                    <option  {{ $strand == $item->id ? 'selected' : '' }} value="{{Crypt::encrypt($item->id)}}">{{$item->strandcode}}</option>
                                @endforeach
                            </select>
                            @if($errors->has('strand'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('strand') }}</strong>
                                </span>
                            @endif --}}
                            <div class="form-group mt-3">
                                <label>Prerequisite</label>
                                <select id="prereq" name="prereq[]" class="select2 form-control"  multiple="multiple" data-placeholder="Select prerequisite subject" style="width: 100%;">
                                    @foreach(App\Models\Principal\SPP_Subject::getAllSubject(null,null,null,null,Crypt::encrypt(5))[0]->data as $item)
                                        <option  value="{{$item->id}}">{{$item->subjtitle}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mt-3">
                                <label>Track</label>
                                <select id="track" name="track" class="select2 form-control" data-placeholder="Select track" style="width: 100%;">
                                   <option value="1">Academic</option>
                                   <option value="2">TVL</option>
                                </select>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label for="">Semester</label>
                                <select id="semester" name="semester" class="form-control @error('semester') is-invalid @enderror" >
                                    <option value="" selected >Semester</option>
                                    @foreach(DB::table('semester')->where('deleted',0)->get() as $item)
                                        <option value="{{$item->id}}">{{$item->semester}}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('semester'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('semester') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <hr>
                            <h6 class="modal-title pb-2"><span class="text-success" id="subjectts1" style="text-transform: uppercase"></span> GRADE SETUP</h6>
                            <p class="text-primary">Written works, Performance task and Quarterly Assessment is in percentage form and should equal to 100%</p>
                            <!-- <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group mb-0">
                                        <label>WW</label>
                                        <input value="{{old('ww')}}" type="text" class="form-control" id="ww" name="ww" placeholder="Written Works %" min="1" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-0">
                                        <label>PT</label>
                                        <input value="{{old('pt')}}" type="text" class="form-control" id="pt"  name="pt"  placeholder="Perf. Task %" min="1" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-0">
                                        <label>QA</label>
                                        <input value="{{old('qa')}}" type="text" class="form-control" id="qa"  name="qa"  placeholder="Quar. Assess. %" " min="1" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                                    </div>
                                </div>
                            </div>
                            <label class="mt-3">Quarter</label>
                            <div class="form-group clearfix">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="icheck-success d-inline">
                                            <input type="checkbox" id="q1" name="q[]" value="1" checked>
                                            <label for="q1">Q1
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="icheck-success d-inline">
                                            <input type="checkbox" id="q2"  name="q[]" value="2" checked>
                                            <label for="q2">Q2
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="icheck-success d-inline">
                                            <input type="checkbox" id="q3"  name="q[]" value="3" checked>
                                            <label for="q3">Q3
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <div class="icheck-success d-inline">
                                            <input type="checkbox" id="q4"  name="q[]" value="4" checked>
                                            <label for="q4">Q4
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                @if (\Session::has('q'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{\Session::get('q')->message}}</strong>
                                    </span>
                                @endif
                            </div>
                            <br> -->
                            <div class="row ">
                                <div class="col-lg-9">
                                <div class="col-md-12">
                                        <div class="form-group mb-0">
                                            <label><i class="fas fa-file-alt text-warning"></i> Written Works</label>
                                            <input value="{{old('ww')}}" type="text" class="form-control" id="ww" name="ww" placeholder="Written Works %" min="1" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                                        </div>
                                    </div>
                                    <br>
                                    <div class="col-md-12">
                                        <div class="form-group mb-0">
                                            <label><i class="fas fa-tasks text-warning"></i> Performance Task</label>
                                            <input value="{{old('pt')}}" type="text" class="form-control" id="pt"  name="pt"  placeholder="Performance Task %" min="1" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                                        </div>
                                    </div>
                                    <br>
                                    <div class="col-md-12">
                                        <div class="form-group mb-0">
                                            <label><i class="fas fa-chart-line text-warning"></i> Quarter Assessment</label>
                                            <input value="{{old('qa')}}" type="text" class="form-control" id="qa"  name="qa"  placeholder="Quarter Assessment %" " min="1" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group mb-0">
                                            <input type="hidden" class="form-control @error('total') is-invalid @enderror">
                                            @if($errors->has('total'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('total') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                
                                <div class="form-group clearfix">
                                <label>Quarter</label>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" id="q1" name="q[]" value="1" checked>
                                                <label for="q1">Q1
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" id="q2"  name="q[]" value="2" checked>
                                                <label for="q2">Q2
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" id="q3"  name="q[]" value="3" checked>
                                                <label for="q3">Q3
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" id="q4"  name="q[]" value="4" checked>
                                                <label for="q4">Q4
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    @if (\Session::has('q'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{\Session::get('q')->message}}</strong>
                                        </span>
                                    @endif
                                </div>
                                </div>

                              
                                    
                            </div>
                            <!-- <button type="button" class="btn btn-danger"  onclick="nextPrev(-1)" >Previous</button>
                            <button type="button" class="btn btn-success"  onclick="nextPrev(1)">Next</button> -->
                        <!-- </div> -->

                        <!-- <div class="tab"> -->
                            
                            <!-- <label>Subject Grade Setup</label> -->
                            <hr>
                            <h6 class="modal-title pb-2">APPLY <span class="text-success" id="subjectts2" style="text-transform: uppercase"></span> TO GRADE LEVELS</h6>
                            <p class="text-primary">Select grade level to apply subject grade setup. You can select multiple grade level.</p>
                            <div class="form-group">
                                <label>Grade Level</label>
                                <select class="form-control select2 @error('gradelevel') is-invalid @enderror" id="gradelevel" name=gradelevel[] multiple="multiple" data-placeholder="Select grade level" style="width: 100%;">
                                    @foreach (App\Models\Principal\SPP_Gradelevel::getGradeLevel(null,null,null,null,$acadid)[0]->data as $item)
                                    @php
                                        $withData = false;
                                    @endphp
                                    @if($errors->any())
                                        @if(old('gradelevel')!=null)
                                            @foreach(old('gradelevel') as $glitem)
                                                @if($glitem==$item->id)
                                                    <option selected value="{{$item->id}}">{{$item->levelname}}</option>
                                                    @php
                                                        $withData = true;
                                                    @endphp
                                                @endif
                                            @endforeach
                                        @endif
                                    @endif
                                    @if(!$withData)
                                        <option value="{{$item->id}}">{{$item->levelname}}</option>
                                    @endif
                                @endforeach
                                </select>
                                @if($errors->has('gradelevel'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('gradelevel') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <hr>
                            <div class="form-group">
                                <div class="icheck-success d-inline">
                                    <input type="checkbox" id="insf9"  name="insf9" value="1" checked>
                                    <label for="insf9">School Form 9</label>
                                </div>
                                <p class="text-danger"><i>Uncheck School Form 9 for subjects that are not included in School Form 9</i></p>
                            </div>
                            <hr>
                            <h6 class="modal-title text-success pb-2">FORM COMPLETE</h6>
                            <p>Click <span class="text-success">Save</span> to create subject : <b><span class="text-success" id="subjectts3" style="text-transform: uppercase"></span></b></p>
                            <br>
                            <button type="button" class="btn closemodal btn-danger" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success studgradesetup" >Save</button>
                        <!-- </div> -->
                        <!-- <div style="text-align:center;margin-top:40px;">
                            <span class="step"></span>
                            <span class="step"></span>
                            <span class="step"></span>
                            <span class="step"></span>
                        </div> -->
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection

@section('content')
    <section class="content-header">
        <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
            </div>
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                <li class="breadcrumb-item active">Subject</li>
            </ol>
            </div>
        </div>
        </div>
    </section>
    <section>
        <div class="card main-card principalsubject">
            <div class="card-header bg-info">
            
            <span class="" style="font-size: 16px"><b><i class="nav-icon far fa-circle"></i> 
            @if($acadprogs[0]->id == 3)
                GRADE SCHOOL
            @elseif($acadprogs[0]->id == 2)   
                PRE SCHOOL
            @elseif($acadprogs[0]->id == 4)     
                JUNIOR HIGH SCHOOL
            @elseif($acadprogs[0]->id == 5)  
                SENIOR HIGH SCHOOL
            @endif
            </b></span>
            
                
            
                {{-- <div class="row"> --}}
                    {{-- <div class="col-md-3">
                        <select class="form-control form-control-sm" id="acadid">
                            <option selected value="Crypt::encrypt(0)" disabled>Select Academic Program</option>
                            @foreach (App\Models\Principal\SPP_AcademicProg::getPrincipalAcadProg(Session::get('prinInfo')->id) as $item)
                                @if($item->id == 2)
                                    {{$subjectcount = Session::get('pssubjectcount')}}
                                @elseif($item->id == 3)
                                    {{$subjectcount = Session::get('gssubjectcount')}}
                                @elseif($item->id == 4)
                                    {{$subjectcount = Session::get('jhsubjectcount')}}
                                @elseif($item->id == 5)
                                    {{$subjectcount = Session::get('shsubjectcount')}}
                                @endif
                                <option class=" {{ $subjectcount > 0 ? 'text-success':'text-danger'}}" value="{{Crypt::encrypt($item->id)}}">{{$item->progname}} <p style="background-color:#FF0055;">( {{$subjectcount}} )</p></option>
                            @endforeach
                        </select>
                    </div> --}}
                    
                    {{-- <div class="col-md-5"> --}}
                       
                    {{-- </div> --}}

                    {{-- <div class="col-md-4"> --}}
                        <div class="input-group input-group-sm float-right w-25 search">
                            <input type="text" id="search" name="search" class="form-control float-right" placeholder="Search" >
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-sm btn-default"><i class="fas fa-search"></i></button>
                            </div>
                        </div>

                        @if( Crypt::decrypt($acadid)==5 && ( auth()->user()->type == 2 || Session::get('prinInfo')->refid == 20 || Session::get('prinInfo')->refid == 22 ) )

                            <button class="btn btn-sm btn-primary principaladdsubject float-right mb-2 mr-2 " id="shsubjectmodal" data-toggle="modal"  data-target="#modal-sh" title="Contacts" data-widget="chat-pane-toggle"  ><i class="fas fa-plus"></i> Add Subject</button>

                        @elseif(Crypt::decrypt($acadid)!= 5 && ( auth()->user()->type == 2 || Session::get('prinInfo')->refid == 20 || Session::get('prinInfo')->refid == 22)  )

                            <button class="btn btn-sm btn-primary principaladdsubject float-right mb-2 mr-2 " id="subjectmodal"  data-toggle="modal"  data-target="#modal-default" title="Contacts" data-widget="chat-pane-toggle" ><i class="fas fa-plus"></i> Add Subject</button>

                        @endif
                    {{-- </div> --}}
                {{-- </div> --}}
               

               
                
            </div>
            <div class="card-body p-1 table-responsive" id="subjectholder">
                @include('search.principal.subject')
            </div>
            <div class="card-footer">
                <div id="data-container"></div>
            </div>
        </div>
    </section>
@endsection


@section('footerjavascript')

    <script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{asset('js/pagination.js')}}"></script>
    
    <script>
        function myFunction1() {
        var x = document.getElementById("sn").value;
        document.getElementById("subjectt1").innerHTML = x;
        document.getElementById("subjectt2").innerHTML = x;
        document.getElementById("subjectt3").innerHTML = x;
        }  

        function myFunction2() {
        var x = document.getElementById("shsn").value;
        document.getElementById("subjectts1").innerHTML = x;
        document.getElementById("subjectts2").innerHTML = x;
        document.getElementById("subjectts3").innerHTML = x;
        }   
    </script>
    <script>
        $(document).ready(function(){
            var inputOne = $("#sn");
            var inputTwo = $("#ssss");
            inputTwo.val(inputOne.val());

            $(".studgradesetup").on("click",function() {
                var sww = $("#sww").val();
                var spt = $("#spt").val();
                var sqa  = $("#sqa").val();
                
                var total = parseInt(sww,10) + parseInt(spt,10) +parseInt(sqa,10) ;
                
                if(total < 100){
                    Swal.fire(
                    'Please check your entries',
                    'Grades should equal to 100%',
                    'error'
                    )
                } if (total > 100) {
                    Swal.fire(
                    'Please check your entries',
                    'Grades should equal to 100%',
                    'error'
                    )
                }
            });

            $(".proceed").on("click",function() {
                Swal.fire(
                'Subject Created?',
                'success'
                )
            });

        })
    </script>
    <script>

        $(document).ready(function(){

            $(function () {
                $('#gradelevel').select2()
                $('.select2bs4').select2({
                theme: 'bootstrap4'
                })
            })

            $(function () {
                $('#subject').select2()
                $('.select2bs4').select2({
                theme: 'bootstrap4'
                })
            })

            $(function () {
                $('.select2').select2()
                $('.select2').select2({
                theme: 'bootstrap4'
                })
            })
            
            pagination('{{$data[0]->count}}',false);
            
            function pagination(itemCount,pagetype){

                var result = [];
                    for (var i = 0; i < itemCount; i++) {
                    result.push(i);
                }
                $('#data-container').pagination({
                dataSource: result,
                callback: function(data, pagination) {
                    if(pagetype){
                    $.ajax({
                        type:'GET',
                        url:'/principalsearchsubjects',
                        data:{
                        data:$("#search").val(),
                        pagenum:pagination.pageNumber,
                        acad:'{{$acadid}}'
                        },
                        success:function(data) {
                          
                        $('#subjectholder').empty();
                        $('#subjectholder').append(data);
                        }
                    })
                    }
                    pagetype=true;
                },
                    hideWhenLessThanOnePage: true,
                    pageSize: 10,
                })
            }
            $("#search").keyup(function() {
                $.ajax({
                type:'GET',
                url:'/principalsearchsubjects',
                data:{
                    data:$(this).val(),
                    pagenum:'1',
                    acad:'{{$acadid}}'
                    },
                success:function(data) {
                    console.log(data);
                    $('#subjectholder').empty();
                    $('#subjectholder').append(data);
                    pagination($('#searchCount').val())
                }
                })
            });
        })
    </script>


    <script>

     

        $(document).ready(function(){

            function clearForm(){

                // $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').remove();
                $('#type').val(null)
                $('#strand').val(null);
                $('.prereq').empty();

                // $('#subjectform')[0].reset()
                // $('#shsubjectform')[0].reset()
                $("#subjectform").attr('action', '/insertSubject');
                $("#shsubjectform").attr('action', '/insertSubject');
                
                $('.sb').empty();
                $('.sb').append('<button onClick="this.form.submit(); this.disabled=true; " type="submit" class="btn btn-info savebutton">Save Subject</button>');
            }

            $(document).on('click','.edit',function(){

                clearForm()

                var subjid = $(this).attr('id');
                $('#si').val($(this).attr('id'));
                $('#shsi').val($(this).attr('id'));

                $.ajax({
                    type:'GET',
                    url:'/prinicipalGetSubject',
                    data:{
                        i:$(this).attr('id'),
                        acad:'{{$acadid}}'},
                    success:function(data) {
                      
                        $('#strand').empty()
                        
                        @if(Crypt::decrypt($acadid)==5)
                            $('#shsn').val(data[0].subjtitle)
                            $('#shsc').val(data[0].subjcode)
                            $('#type').val(data[0].type)
                       
                        var string =  '<option value="" >Select Strands</option>'

                        @foreach(App\Models\Principal\SPP_Strand::loadSHStrands() as $item)

                            if(data[0].strandname == '{{$item->strandname}}'){
                                string += '<option value='+'{{Crypt::encrypt($item->id)}}'+' selected>'+'{{$item->strandcode}}'+'</option>'
                            }
                            else{
                                string += '<option value='+'{{Crypt::encrypt($item->id)}}'+'>'+'{{$item->strandcode}}'+'</option>'
                            }
                            
                        @endforeach

                        if(data[0].type == '3'){
                            var type = ['3','1'];
                        }
                        else if(data[0].type == '2'){
                            var type = ['3','2','1'];
                        }
                        else{
                            var type = ['1'];
                        }

                        displayPrereq(data[0].strandid, type,true,subjid)

                        $('#prereq').val(["14"]).trigger('change');

                        $('#strand').append(string)
                            
                        @else
                            $('#sn').val(data[0].subjdesc)
                            $('#sc').val(data[0].subjcode)
                        @endif

                    
                    }
                })

                $('.sb').empty();
                $('.sb').append('<button type="submit" class="btn btn-success us" onClick="this.form.submit(); this.disabled=true; ">Update Subject</button>');

                $("#shsubjectform").attr('action', '/principalupdatesubject');
                $("#subjectform").attr('action', '/principalupdatesubject');
            })  


            $(document).on('click','#subjectmodal',function(){
              clearForm()
            })
            $(document).on('click','#shsubjectmodal',function(){
              clearForm()
            })

            $('#modal-primary').on('hidden.bs.modal', function () {
                clearForm()
            })
          
        });

    </script>

    <script>

        $(document).ready(function(){

            @if(Crypt::decrypt($acadid)==5)
                @if ($errors->any())
                    $('#modal-sh').modal('show');
                        
                    @if( old('type')==2)
                        $('#strand').removeAttr('disabled')
                    @endif

                    @if(old('strand')!=null)
                        displayPrereq()
                    @endif

                @endif
            @else
                @if ($errors->any())
                    $('#modal-default').modal('show');
                @endif
            @endif
        });

        // $(document).on('change','#type',function(){

        //     $('#strand').val(null);
        //     $(this).css('background-color','#fffffff')

        //     if($(this).val()=='2'){

        //         $('#sem').removeAttr('disabled')

        //     }
        //     else if($(this).val()=='3'){

        //        displayPrereq(null,['3','1'])

        //     }

        //     else{
        //             $('#gradelevel').val('')
        //             $('#strand').val('')
        //             $('#sem').val('')
        //             $('#sem').prop('disabled',true)
        //             $('.prereq').empty();
        //     }
        // })
    

        // $(document).on('change','#strand',function(){

        //     displayPrereq($(this).val(),['1','2','3']);

        // })

        function displayPrereq(strand = null, type=null, update = false, subjid = null){
     

            if(type == null){
                type = $('#type').val()
            }

            $('.prereq').empty()

            var datastring = '<label>Prerequisite</label> <select id="prereq" name="prereq[]" class="select2" multiple="multiple" data-placeholder="Select prerequisite subject" style="width: 100%;">';

            $.ajax({
                type:'GET',
                url:'/viewSHSubjectsbyStrand',
                data:{
                    st:strand,
                    tp:type,
                    acad:'{{$acadid}}'
                    },
                success:function(data) {

                    var prereq;

                    $.ajax({
                        type:'GET',
                        url:'/principalGetPrereq',
                        async: false,
                        data:{
                            si: subjid,
                            },
                        success:function(data) {
                            prereq = data[0].data
                        }
                    })

                   
           
                    $.each(data,function(index, value){

                        var selected = "";

                        @if(Crypt::decrypt($acadid)==5)
                            @if ($errors->any())
                                @if(old('prereq'))
                                    @for($x = 0; $x < count(old('prereq')); $x++)

                                        if(value.id == '{{old('prereq')[$x]}}'){
                                            selected = 'selected'
                                        }
                                    @endfor
                                @endif
                            @endif
                        @endif

                        var it_works = false;

                        if(update){

                            $.each(prereq,function(index,valueprereq){
                                if(value.id == valueprereq.id){
                                    selected = 'selected';
                                }
                            })

                        }

                      
                        datastring+='<option '+selected+' value="'+value.id+'">'+value.subjtitle+'</option>'

                    });

                    datastring+='</select>'

                    $('.prereq').append(datastring)
                    $(function () {
                        $('.select2').select2()
                        $('.select2bs4').select2({
                        theme: 'bootstrap4'
                        })
                    })
                }
            })
        }
    
    
    </script>

    <script>
        var currentTab = 0; // Current tab is set to be the first tab (0)
        showTab(currentTab); // Display the current tab
        
        function showTab(n) {
        // This function will display the specified tab of the form...
        var x = document.getElementsByClassName("tab");
        x[n].style.display = "block";
        //... and fix the Previous/Next buttons:

        if (n == 0) {
            try {
                document.getElementById("prevBtn").style.display = "none";
            }
            catch(err) {
              
            }
           
        } else {

            try {
                document.getElementById("prevBtn").style.display = "inline";
            }
            catch(err) {
               
            }
            
        }

        if (n == (x.length - 1)) {
            try {
                document.getElementById("nextBtn").innerHTML = "Submit";
            }
            catch(err) {
              
            }
           
        } else {
            try {
                document.getElementById("nextBtn").innerHTML = "Next";
            }
            catch(err) {
              
            }
        }
        //... and run a function that will display the correct step indicator:
        fixStepIndicator(n)
        }
        
        function nextPrev(n) {
            var x = document.getElementsByClassName("tab");
            if (n == 1 && !validateForm()) return false;
            x[currentTab].style.display = "none";
            currentTab = currentTab + n;
            if (currentTab >= x.length) {
                document.getElementById("regForm").submit();
                return false;
            }
            showTab(currentTab);
        }
        
        function validateForm() {
            var x, y, i, valid = true;
            x = document.getElementsByClassName("tab");
            y = x[currentTab].getElementsByTagName("input");
            z = x[currentTab].getElementsByTagName("select");

            for (i = 0; i < y.length; i++) {
            
                if (
                    y[i].value == ""  && y[i].className  != 
                    "select2-search__field") {
                    y[i].className += " invalid";
                    valid = false;
                }

                if(y[i].className  == "select2-search__field"){
                    if($('#gradelevel').val().length == 0){
                      
                        try{
                        
                            y[0].closest( "span").style.backgroundColor = '#ffdddd'
                            valid = false;
                        }
                        catch{
                          
                            // valid = true;
                        }
                    }
                    else{
                   
                        try{
                            valid = true;
                            y[0].closest( "span").style.backgroundColor = '#ffffff'
                        }
                        catch{
                            // valid = true;
                        }
                    }
                }

             
                
                if(y.length>2){
                    var sum = parseInt(y[0].value) + parseInt(y[1].value) + parseInt(y[2].value)
                
                    if(sum != 100){
                        valid = false;
                    }
                }

               

                @if(Crypt::decrypt($acadid) == 5)

                    try{
                        if(z[0].value == ""){
                            console.log('b')
                            valid = false;
                            z[0].style.backgroundColor = '#ffdddd'
                        }
                        else{
                            console.log('d')
                            valid = true;
                            z[0].style.backgroundColor = '#ffffff'
                        }
                    }
                    catch{
                       
                    }
                @endif
                
            }

            

            if (valid) {

                document.getElementsByClassName("step")[currentTab].className += " finish";

            }

          
            return valid;
        }
        
        function fixStepIndicator(n) {
        // This function removes the "active" class of all steps...
        var i, x = document.getElementsByClassName("step");
        for (i = 0; i < x.length; i++) {
            x[i].className = x[i].className.replace(" active", "");
        }
        //... and adds the "active" class on the current step:
        x[n].className += " active";
        }
    </script>

@endsection

