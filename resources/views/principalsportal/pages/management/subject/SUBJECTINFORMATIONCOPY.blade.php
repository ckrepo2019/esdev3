@extends('principalsportal.layouts.app2')

@if($deleted)

    @section('content')

    <section class="content-header">
    
    </section>
    <section class="content">
        <div class="error-page">
            <div class="error-content pt-4">
                <h3><i class="fas fa-exclamation-triangle text-warning"></i> This subject has been deleted</h3>
                <p>
                @if(Session::get('isPreSchoolPrinicpal'))
                    <a href="/principalviewPSSubjects/{{Crypt::encrypt('2')}}">      click here </a>
                @elseif(Session::get('isGradeSchoolPrinicpal'))
                   <a href="/principalviewGSSubjects/{{Crypt::encrypt('3')}}">      click here </a>
                @elseif(Session::get('isJuniorHighPrinicpal'))
                    <a href="/principalviewJHSubjects/{{Crypt::encrypt('4')}}">      click here </a>
                @elseif(Session::get('isSeniorHighPrincipal'))
                    <a href="/principalviewSHSubjects/{{Crypt::encrypt('5')}}">      click here </a>
                @endif
                to view all subjects</a>.
                </p>
            </div>
        </div>
    </section>
    @endsection

@else

    @section('pagespecificscripts')

        <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
        <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{asset('css/pagination.css')}}">

        <style>
            * {
              box-sizing: border-box;
            }
            
            body {
              background-color: #f1f1f1;
            }
            
            #regForm {
              background-color: #ffffff;
              margin: 10px auto;
              padding: 10px;
              width: 90%;
              min-width: 300px;
            }
            
            h1 {
              text-align: center;  
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
        </style>

    @endsection



    @section('modalSection')

    @if(Crypt::decrypt($acadid) != 5)
        <div class="modal fade" id="updatemodal" style="display: none;" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <form id="regForm" action="/principalupdatesubject" method="GET">
                            <input id="acad" name="acad" type="hidden" value="{{$acadid}}">
                            <input id="si" name="si" type="hidden" value="{{$subject_info[0]->id}}">
                            <div class="tab"> 
                            <div class="modal-header si bg-info">
                                <span class="text-right" style="font-size: 14px;"><b>ADD SUBJECT</b></span>
                                <span class="text-right" style="font-size: 14px; position: absolute; right: 15%"><b> 
                                @if($acadprogs[0]->id == 3)
                                    <span class="text-warning" style="text-shadow: 1px 1px 2px #000">GRADE SCHOOL</span>             
                                @else    
                                    <span class="text-warning" style="text-shadow: 1px 1px 2px #000">JUNIOR HIGH SCHOOL</span>
                                @endif
                                
                                </b></span>
                                <!-- <h5 class="modal-title">SUBJECT INFORMATION</h5> -->
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                </button>
                            </div>
                                <br>
                                <h6 class="modal-title text-success pb-2">SUBJECT INFORMATION</h6>
                                <div class="form-group">
                                    <label>Subject</label>
                                    <input value="{{$subject_info[0]->subjdesc}}" class="form-control @error('sn') is-invalid @enderror" id="sn"  name="sn" placeholder="Subject Name">
                                    @if($errors->has('sn'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('sn') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label>Subject Code</label>
                                    <input value="{{$subject_info[0]->subjcode}}" class="form-control @error('sc') is-invalid @enderror" id="sc"  name="sc" placeholder="Subject Code">
                                    @if($errors->has('sc'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('sc') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <br>
                                <br>
                                <button class="btn btn-danger" class="close" data-dismiss="modal" aria-label="Close">Cancel</button>
                                <a class="btn btn-success" href="#" id="nextBtn" onclick="nextPrev(1)">Next</a>
                            </div>
                            <!-- <div class="tab sgs"> -->
                            <div class="tab sgs">
                            <div class="modal-header bg-info sgs">
                                <span class="text-right" style="font-size: 14px;"><b>STUDENT GRADE SETUP</b></span>
                                <span class="text-right" style="font-size: 14px; position: absolute; right: 15%"><b> 
                                @if($acadprogs[0]->id == 3)
                                    <span class="text-warning" style="text-shadow: 1px 1px 2px #000">GRADE SCHOOL</span>             
                                @else    
                                    <span class="text-warning" style="text-shadow: 1px 1px 2px #000">JUNIOR HIGH SCHOOL</span>
                                @endif
                                </b></span>
                                <!-- <h5 class="modal-title">STUDENT GRADE SETUP</h5> -->
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                </button>
                            </div>
                                <br>
                            
                                <p class="text-primary" style="text-align: justify">Written works, Performance task and Quarterly Assessment is in percentage form and should equal to 100</p>
                                <div class="row">
                                <div class="col-lg-9">
                                <div class="col-md-12">
                                        <div class="form-group mb-0">
                                            <label><i class="fas fa-file-alt text-warning"></i> Written Works</label>
                                            <input id="sww" value="{{old('ww')}}" type="text" class="form-control" id="ww" name="ww" placeholder="Written Works" min="1" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                                        </div>
                                    </div>
                                    <br>
                                    <div class="col-md-12">
                                        <div class="form-group mb-0">
                                            <label><i class="fas fa-tasks text-warning"></i> Performance Task</label>
                                            <input id="spt" value="{{old('pt')}}" type="text" class="form-control" id="pt"  name="pt"  placeholder="Performance Task" min="1" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                                        </div>
                                    </div>
                                    <br>
                                    <div class="col-md-12">
                                        <div class="form-group mb-0">
                                            <label><i class="fas fa-chart-line text-warning"></i> Quarter Assessment</label>
                                            <input id="sqa" value="{{old('qa')}}" type="text" class="form-control" id="qa"  name="qa"  placeholder="Quarter Assessment" " min="1" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                                        </div>
                                    </div>
                                </div>
                                    <div class="col-lg-3">
                                    <label class="mt-3">Quarter</label>
                                <div class="form-group clearfix">
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
                                <br>
                                <br>
                                
                            </div>
                            </div>
                            <br>
                            <button type="button" class="btn btn-danger"  onclick="nextPrev(-1)" >Previous</button>
                            <button type="button" class="btn btn-success studgradesetup"  onclick="nextPrev(1)">Next</button>
                            </div>
                                
                            <!-- <div class="tab"> -->
                            <div class="tab">
                            <div class="modal-header bg-info sgs">
                                <span class="text-right" style="font-size: 14px;"><b>SUBJECT GRADE SETUP</b></span>
                                <span class="text-right" style="font-size: 14px; position: absolute; right: 15%"><b> 
                                @if($acadprogs[0]->id == 3)
                                    <span class="text-warning" style="text-shadow: 1px 1px 2px #000">GRADE SCHOOL</span>             
                                @else    
                                    <span class="text-warning" style="text-shadow: 1px 1px 2px #000">JUNIOR HIGH SCHOOL</span>
                                @endif
                                </b></span>
                                <!-- <h5 class="modal-title">SUBJECT GRADE SETUP</h5> -->
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                </button>
                            </div>
                                <br>
                                <p class="text-primary">Select grade level to apply subject grade setup. <u><mark>You can select multiple grade level(s).</mark></u></p>
                                <div class="form-group">
                                    <label>Grade Level</label>
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
                                </div>
                                <br>
                                <br>
                                <button type="button" class="btn btn-danger"  onclick="nextPrev(-1)" >Previous</button>
                                <button type="button" class="btn btn-success"  onclick="nextPrev(1)">Next</button>
                                
                            </div>
                            <div class="tab">
                            <!-- <div> -->
                            <div class="modal-header bg-info sgs">
                                <span class="text-right" style="font-size: 14px;"><b>FORMS COMPLETE</b></span>
                                <span class="text-right" style="font-size: 14px; position: absolute; right: 15%"><b> 
                                @if($acadprogs[0]->id == 3)
                                    <span class="text-warning" style="text-shadow: 1px 1px 2px #000">GRADE SCHOOL</span>             
                                @else    
                                    <span class="text-warning" style="text-shadow: 1px 1px 2px #000">JUNIOR HIGH SCHOOL</span>
                                @endif
                                </b></span>
                                <!-- <h5 class="modal-title">FORMS COMPLETE</h5> -->
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                </button>
                            </div>
                                <br>
                                <p>Click <span class="text-success">Proceed</span> to create subject.</p>
                                <br>
                                <button type="button" class="btn btn-danger"  onclick="nextPrev(-1)" >Previous</button>
                                <button type="submit" class="btn btn-success" >Proceed</button>
                            </div>
                            <div style="text-align:center;margin-top:40px;">
                                <span class="step"></span>
                                <span class="step"></span>
                                <span class="step"></span>
                                <span class="step"></span>
                            </div>
                        </form>
                    </div>
                </div>
        
            </div>
        </div>
    @else
        <div class="modal fade" id="updatemodal" style="display: none;" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                            <form id="regForm" action="/principalupdatesubject" method="GET">
                                <input id="acad" name="acad" type="hidden" value="{{$acadid}}">
                                <input id="shsi" name="shsi" type="hidden" value="{{$acadid}}">
                                <div class="tab"> 
                                    <div class="form-group">
                                        <label>Subject</label>
                                        <input value="{{old('sn')}}" class="form-control @error('sn') is-invalid @enderror" id="shsn"  name="sn" placeholder="Subject Name">
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
                                                <input value="{{old('sc')}}" class="form-control @error('sc') is-invalid @enderror" id="shsc"  name="sc" placeholder="Subject Code">
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
                                
                                    <label>Strand</label>
                                    <select id="strand" name="strand" class="form-control @error('strand') is-invalid @enderror" >
                                        <option value="" selected >APPLY TO STRAND</option>
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
                                    @endif
                                    <div class="form-group prereq">
                                       
                                    </div>
                                    <br>
                                    <br>
                                    <button class="btn btn-danger" class="close" data-dismiss="modal" aria-label="Close">Cancel</button>
                                    <button type="button" class="btn btn-success"  onclick="nextPrev(1)">Next</button>
                                </div>
                                <div class="tab">
                                    <label>Subject Grade Setup</label>
                                    <br>
                                    <br>
                                
                                    <p class="text-primary">Written works, Performance task and Quarterly Assessment is in percentage form and should equal to 100</p>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group mb-0">
                                                <label>WW</label>
                                                <input value="{{old('ww')}}" type="text" class="form-control" id="ww" name="ww" placeholder="Written Works" min="1" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-0">
                                                <label>PT</label>
                                                <input value="{{old('pt')}}" type="text" class="form-control" id="pt"  name="pt"  placeholder="Perf. Task" min="1" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-0">
                                                <label>QA</label>
                                                <input value="{{old('qa')}}" type="text" class="form-control" id="qa"  name="qa"  placeholder="Quar. Assess." " min="1" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
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
                                    <br>
                                    <br>
                                    <button type="button" class="btn btn-danger"  onclick="nextPrev(-1)" >Previous</button>
                                    <button type="button" class="btn btn-success"  onclick="nextPrev(1)">Next</button>
                                </div>
        
                                <div class="tab">
                                    <label>Subject Grade Setup</label>
                                    <br>
                                    <br>
                                    <p class="text-primary">Select grade level to apply subject grade setup. You can select multiple grade level.</p>
                                    <div class="form-group">
                                        <label>Grade Level</label>
                                        <select class="form-control select2 " id="gradelevel" name=gradelevel[] multiple="multiple" data-placeholder="Select grade level" style="width: 100%;">

                                            @foreach (App\Models\Principal\SPP_Gradelevel::getGradeLevel(null,null,null,null,$acadid)[0]->data as $item)
                                                <option value="{{$item->id}}">{{$item->levelname}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <br>
                                    <br>
                                    <button type="button" class="btn btn-danger"  onclick="nextPrev(-1)" >Previous</button>
                                    <button type="button" class="btn btn-success"  onclick="nextPrev(1)">Next</button>
                                    
                                </div>
                                <div class="tab">
                                    <label>Forms complete!</label>
                                    <br>
                                    <br>
                                    <p>Click Proceed to create subject.</p>
                                    <br>
                                    <br>
                                    <button type="button" class="btn btn-danger"  onclick="nextPrev(-1)" >Previous</button>
                                    <button type="submit" class="btn btn-success" >Proceed</button>
                                </div>
                                <div style="text-align:center;margin-top:40px;">
                                    <span class="step"></span>
                                    <span class="step"></span>
                                    <span class="step"></span>
                                    <span class="step"></span>
                                </div>
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
               
                <h3 class="text-warning" style="text-shadow: 1px 1px 1px #000000">
                    <span class="text-dark">SUBJECT : </span>
                    <b>
                        <u>
                        @if($subject_info[0]->acadprogid == 5)
                            {{$subject_info[0]->subjtitle}} 
                        @else
                            {{$subject_info[0]->subjdesc}} 
                        @endif
                        </u>
                    </b>
                </h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>

                        @if(Session::get('isPreSchoolPrinicpal'))
                            <li class="breadcrumb-item active"><a href="/principalviewPSSubjects/{{Crypt::encrypt('2')}}">Subjects</a></li>
                        @elseif(Session::get('isGradeSchoolPrinicpal'))
                            <li class="breadcrumb-item active"><a href="/principalviewGSSubjects/{{Crypt::encrypt('3')}}">Subjects</a></li>
                        @elseif(Session::get('isJuniorHighPrinicpal'))
                            <li class="breadcrumb-item active"><a href="/principalviewJHSubjects/{{Crypt::encrypt('4')}}">Subjects</a></li>
                        @elseif(Session::get('isSeniorHighPrincipal'))
                            <li class="breadcrumb-item active"><a href="/principalviewSHSubjects/{{Crypt::encrypt('5')}}">Subjects</a></li>
                        @endif
                    
                        <li class="breadcrumb-item active">Subject Information</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section>
    <div class="row">
        <div class="col-md-9">
                <div class="card">
                    <div class="card-header bg-info">
                                <span class="text-right" style="font-size: 16px; position: absolute; right:5%"><b> GRADE SETUP</b></span>
                                <span class="text-right" style="font-size: 16px;"><b> 
                                @if($acadprogs[0]->id == 3)
                                    GRADE SCHOOL             
                                @else    
                                    JUNIOR HIGH SCHOOL
                                @endif</b>
                                </span>
                        <!-- Grade Setup -->
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-sm ">
                            <tr class="bg-warning">
                                <th width="2%" class="p-0"></th>
                                <th width="49%" class="border-right">Grade Level</th>
                                <th width="7%" class="text-center">WW</th>
                                <th width="7%" class="text-center">PT</th>
                                <th width="7%" class="border-right text-cente">QA</th>
                                <th width="7%" class="text-center">Q1</th>
                                <th width="7%" class="text-center">Q2</th>
                                <th width="7%" class="text-center">Q3</th>
                                <th width="7%" class="border-right text-center">Q4</th>
                                {{-- <th colspan="1" class="text-center pr-0" width="10%">Action</th> --}}
                            </tr>
                            <tr>
                                @foreach ($grade_level[0]->data as $item)
                                    @php
                                        $count = 0;
                                        $gradeSetup = null;
                                    @endphp
                                    <tr>
                                    
                                            @foreach ($grades_setup[0]->data as $key=>$grade_setup)
                                                @if($item->sortid == $grade_setup->sortid)
                                                
                                                    @php
                                                        $gradeSetup = $grade_setup;
                                                        $count += 1;
                                                        unset($grades_setup[0]->data[$key])
                                                    @endphp
                                                    <td class="bg-success p-0"></td>
                                                    @break
                                                @endif
                                            @endforeach

                                            @if($count == 0)
                                                <td  class="bg-danger p-0"></td>
                                            @endif
                                
                                        <td class="border-right">{{$item->levelname}}</td>

                                        @if( $gradeSetup != null)
                                            <td class="text-center">{{$gradeSetup->writtenworks}}</td>
                                            <td class="text-center">{{$gradeSetup->performancetask}}</td>
                                            <td class="border-right text-center">{{$gradeSetup->qassesment}}</td>

                                            @if($gradeSetup->first == 1)
                                                <td class="text-center"><i class="fas fa-check-square text-success"></i></td>
                                            @else
                                                <td class="text-center"><i class="fas fa-times-circle text-danger"></i></td>
                                            @endif
                                            @if($gradeSetup->second == 1)
                                                <td class="text-center"><i class="fas fa-check-square text-success"></i></td>
                                            @else
                                                <td class="text-center"><i class="fas fa-times-circle text-danger"></i></td>
                                            @endif
                                            @if($gradeSetup->third == 1)
                                                <td class="text-center"><i class="fas fa-check-square text-success"></i></td>
                                            @else
                                                <td class="text-center"><i class="fas fa-times-circle text-danger"></i></td>
                                            @endif
                                            @if($gradeSetup->fourth == 1)
                                                <td class="border-right text-center"><i class="fas fa-check-square text-success"></i></td>
                                            @else
                                                <td class="border-right text-center"><i class="fas fa-times-circle text-danger"></i></td>
                                            @endif
                                            {{-- <td class="text-center pr-0"><button class="btn  btn-sm p-0" data-toggle="modal"  data-target="#grade_setup_modal" title="EDIT" data-widget="chat-pane-toggle" ><i class="far fa-edit text-primary"></i></button></td> --}}
                                            {{-- <td class="pr-0"><i class="far fa-trash-alt text-danger"></i></td> --}}
                                        @else
                                            <td class="text-center">0</td>
                                            <td class="text-center">0</td>
                                            <td class="border-right text-center">0</td>

                                            <td class="text-center"><i class="fas fa-times-circle text-danger"></i></td>

                                            <td class="text-center"><i class="fas fa-times-circle text-danger"></i></td>

                                            <td class="text-center"><i class="fas fa-times-circle text-danger"></i></td>

                                            <td class="text-center border-right"><i class="fas fa-times-circle text-danger"></i></td>
                                            
                                            {{-- <td class="text-center pr-0"><i class="far fa-edit text-primary "></i></td> --}}
                                           
                                        @endif
                                       
                                    </tr>
                                @endforeach
                                
                            </tr>

                        </table>
                    </div>
                </div>
        </div>
        <div class="col-md-3">
                <div class="card">
                    <div class="card-header bg-success">
                        Subject Information
                    </div>
                    <div class="card-body ">
                        <strong><i class="fas fa-signature mr-1"></i>Subject Name</strong>
                        <p class="small text-warning" style="text-shadow: 1px 1px 1px #000000">
                            <b>
                            @if($subject_info[0]->acadprogid == 5)
                                {{$subject_info[0]->subjtitle}} 
                            @else
                                {{$subject_info[0]->subjdesc}} 
                            @endif
                            </b>
                        </p>
                        <hr>
                        <strong><i class="fas fa-layer-group mr-1"></i>Subject Code</strong>
                        <p class="text-warning small" style="text-shadow: 1px 1px 1px #000000">
                            {{$subject_info[0]->subjcode}} 
                        </p>
                        @if(Crypt::decrypt($acadid) == 5)
                            <hr>
                            <strong><i class="fas fa-layer-group mr-1"></i>Type</strong>
                            <p class="text-muted small">
                                <b>
                                @if($subject_info[0]->type == 1)
                                    CORE SUBJECT
                                @elseif($subject_info[0]->type == 2)
                                    SPECIALIZED SUBJECT
                                @elseif($subject_info[0]->type == 3)
                                    APPLIED SUBJECT
                                @endif
                                </b>
                            </p>
                            <hr>
                            <strong><i class="fas fa-layer-group mr-1"></i>Strand</strong>
                            <p class="text-muted small">
                                {{$subject_info[0]->strandname}} 
                            </p>
                            <hr>
                            <strong><i class="fas fa-layer-group mr-1"></i>REQUISITE</strong>
                            <p class="text-muted small">
                                @if($subject_info[0]->type==2 || $subject_info[0]->type==3)
                               
                                    @foreach (App\Models\Principal\SPP_Prerequisite::loadSHSubjectPrerequisiteBySubject($subject_info[0]->id) as $prereq)
                                        {{$prereq->subjcode}}
                                    @endforeach
                                @endif
                            </p>
                        @endif
                        
                        <hr>
                     
                        @if(auth()->user()->type == 2)

                            <button type="button" class="btn btn-sm edit btn-outline-primary btn-block" data-toggle="modal"  data-target="#updatemodal" title="EDIT" data-widget="chat-pane-toggle" id="{{Crypt::encrypt($subject_info[0]->id)}}"  ><i class="fas fa-edit"></i> <span>EDIT SUBJECT</span></button>
                        
                    
                       
                        @if($subject_info[0]->usage == false)

                            <a class="btn btn-sm btn-outline-danger btn-block mt-3 deletesubj" href="#"><i class="fas fa-trash-alt"></i> <span>DELETE SUBJECT</span></a>

                        @endif

                        @endif
                        
                </div>
        </div>
    </div>
    </section>
    @endsection
    
    @section('footerjavascript')

    <script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{asset('js/pagination.js')}}"></script>

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
            
         
          
        })
    </script>

    <script>
        $(document).ready(function(){
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

            function clearForm(){

                $('.is-invalid').removeClass('is-invalid');
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

            $(document).on('click','.deletesubj',function(){
                        Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete subject!'
                    }).then((result) => {
                        if (result.value) {
                            if('{{$subject_info[0]->usage}}' == false){
                                $.ajax({
                                    type:'GET',
                                    url:'/principalremovesubject/'+'{{Crypt::encrypt($subject_info[0]->id)}}'+'/'+'{{$acadid}}',
                                    success:function(data) { 
                                        Swal.fire({
                                            type: 'success',
                                            title: 'Success!',
                                            text: 'Subject has been deleted!',
                                            showConfirmButton: false,
                                            timer: 1500
                                        }).then(()=>{
                                            location.reload(); 
                                        })
                                    }
                                });
                            }

                        }
                    })
                });

            @if(Crypt::decrypt($acadid)==5)
                @if ($errors->any())
                    $('#updatemodal').modal('show');
                        
                    @if( old('type')==2)
                        $('#strand').removeAttr('disabled')
                    @endif

                    @if(old('strand')!=null)
                        displayPrereq()
                    @endif

                @endif
            @else
                @if ($errors->any())
                    $('#updatemodal').modal('show');
                @endif
            @endif
        });

        $(document).on('change','#type',function(){

            $('#strand').val(null);
            $(this).css('background-color','#fffffff')

            if($(this).val()=='2'){

                $('#sem').removeAttr('disabled')

            }
            else if($(this).val()=='3'){

            displayPrereq(null,['3','1'])

            }

            else{
                    $('#gradelevel').val('')
                    $('#strand').val('')
                    $('#sem').val('')
                    $('#sem').prop('disabled',true)
                    $('.prereq').empty();
            }

            

        })


        $(document).on('change','#strand',function(){

            displayPrereq($(this).val(),['1','2','3']);

        })

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
        
        // function validateForm() {
        //     var x, y, i, valid = true;
        //     x = document.getElementsByClassName("tab");
        //     y = x[currentTab].getElementsByTagName("input");

        //     for (i = 0; i < y.length; i++) {
            
        //         if (
        //             y[i].value == ""  && y[i].className  != 
        //             "select2-search__field") {
        //             y[i].className += " invalid";
        //             valid = false;
        //         }

        //         if(y[i].className  == "select2-search__field"){
        //             if($('#gradelevel').val().length == 0){
        //                 valid = false;
        //                 y[0].closest( "span").style.backgroundColor = '#ffdddd'
        //             }
        //             else{
        //                 valid = true;
        //                 y[0].closest( "span").style.backgroundColor = '#ffffff'
        //             }
        //         }
                
        //         if(y.length>2){
        //             var sum = parseInt(y[0].value) + parseInt(y[1].value) + parseInt(y[2].value)
                
        //             if(sum != 100){
        //                 valid = false;
        //             }
        //         }

                
                
        //     }

            

        //     if (valid) {

        //         document.getElementsByClassName("step")[currentTab].className += " finish";

        //     }

        
        //     return valid;
        // }

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

                
            if (valid) {

                document.getElementsByClassName("step")[currentTab].className += " finish";

            }


            return valid;
                
            }
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

@endif

