
@extends('studentportal.layouts.app2')

@section('pagespecificscripts')
 
    <link rel="stylesheet" href="{{asset('plugins/daterangepicker/daterangepicker.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">

    

    
@endsection

@section('content')


        <section class="content pt-2">
            <div class="container-fluid">
            
                  <form action="/student/submit/form" method="GET" id="survey_form" autocomplete="off">
          
                <div class="row">
                    <div class="card col-md-12">
                        <div class="card-body">
                              <div class="row" id="datail_info">
                                    <div class="col-md-12">
                                          <h5 class="text-primary"><i>Please complete Learner Enrollment And Survey Form to continue pre-enrollment!</i></h5>
                                    </div>
                              </div>
                              <hr>
                              <div class="row">
                                    <div class="col-md-12">
                                          <h3 class="card-title">LEARNER ENROLLMENT AND SURVEY FORM</h3>
                                    </div>
                              </div>
                              <hr>
                              <div class="row">
                                    <div class="col-md-12" style="font-size:13px !important">
                                          <label for="">Instructions;</label>
                                          <ol>
                                                <li>1. This enrollment survey shall be answered by the parent/guardian of the learner.</li>
                                                <li>2. Please read the questions carefully and fill in all applicable spaces and write your answers legibly in CAPITAL letters.For items not applicable, write N/A.</li>
                                                <li>3. For questions/ clarifications, please ask for the assistance of the teacher/ person-in-charge.</li>
                                          </ol>
                                    </div>
                              </div>
                              
                                   
                              <p class="bg-primary p-2">A. GRADE LEVEL AND SCHOOL INFORMATION</p>
                              <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-4 col-form-label">A1. School Year: </label>
                                    <div class="col-sm-8">
                                      <input class="form-control" id="a1" name="a4" value="{{$sy->sydesc}}" placeholder="School Year" readonly>
                                    </div>
                              </div>
                              <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-4 col-form-label ">A2. With LRN</label>
                                    <div class="col-sm-8 pt-2">
                                          <div class="icheck-primary d-inline">
                                                <input type="radio" id="a2a" name="a2" @if($student->lrn != null)checked @endif value="1" onclick="return false;">
                                                <label for="a2a">Yes</label>
                                          </div>
                                          <div class="icheck-primary d-inline ml-3">
                                                <input type="radio" id="a2b" name="a2" value="2" @if($student->lrn == null)checked @endif onclick="return false;">
                                                <label for="a2b">No</label>
                                          </div>
                                    </div>
                              </div>
                              <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-4 col-form-label ">A3. Returning (Balik-Aral): </label>
                                    <div class="col-sm-8 pt-2">
                                          <div class="icheck-primary d-inline">
                                                <input type="radio" id="a3a" name="a3" value="1" {{$surveyAns->a3 ? 'checked':''}}>
                                                <label for="a3a">Yes</label>
                                          </div>
                                          <div class="icheck-primary d-inline ml-3">
                                                <input type="radio" id="a3b" name="a3" value="2">
                                                <label for="a3b">No</label>
                                          </div>
                                    </div>
                              </div>
                              <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-4 col-form-label ">A4. Grade Level to enroll: </label>
                                    <div class="col-sm-8">
                                      <input name="a4" value="{{$student->levelname}}" class="form-control" id="a4" placeholder="Grade Level to enroll" readonly>
                                    </div>
                              </div>
                              <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-4 col-form-label ">A5. Last grade level completed: </label>
                                    <div class="col-sm-8">
                                          <select name="a5" id="a5" class="form-control input_field required">
                                                <option value="">SELECT LAST GRADE LEVEL COMPLETED</option>
                                                @foreach (DB::table('gradelevel')->where('deleted','0')->orderBy('sortid')->get() as $item)
                                                      <option value="{{$item->id}}" @if($surveyAns->a5 == $item->id) selected @endif>{{$item->levelname}}</option>
                                                @endforeach
                                          </select>
                                          <span class="invalid-feedback" role="alert">
                                                <strong>A5 is required.</strong>
                                          </span>
                                    </div>
                              </div>
                              <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-4 col-form-label ">A6. Last school year completed: </label>
                                    <div class="col-sm-8">
                                          <input class="form-control input_field required" value="{{$surveyAns->a6}}" id="a6" name="a6" placeholder="Last school year completed ex. {{intval($sy->sdate) - 1}} - {{intval($sy->edate) - 1}}">
                                          <span class="invalid-feedback" role="alert">
                                                <strong>A6 is required.</strong>
                                          </span>
                                    </div>
                                    
                              </div>
                              <div class="row">
                                    <div class="col-md-12">
                                          <p class="bg-danger p-2" >LAST SCHOOL ATTENDED</p>
                                    </div>
                              </div>
                              <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-4 col-form-label ">A7. Last School Atended: </label>
                                    <div class="col-sm-8">
                                      <input name="a7" class="form-control required input_field" value="{{$student->lastschoolatt}}" id="a7" placeholder="Last School Attended" onkeyup="this.value = this.value.toUpperCase();">
                                      <span class="invalid-feedback" role="alert">
                                          <strong>A7 is required.</strong>
                                       </span>
                                    </div>
                              </div>
                              <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-4 col-form-label ">A8. School ID: </label>
                                    <div class="col-sm-8">
                                      <input class="form-control" id="a8" value="{{$surveyAns->a8}}" name="a8" placeholder="School ID of last School Attended" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                                      
                                    </div>
                              </div>
                              <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-4 col-form-label ">A9. Address: </label>
                                    <div class="col-sm-8">
                                      <input class="form-control input_field required" id="a9" value="{{$surveyAns->a9}}" name="a9" placeholder="Address of last School Attended" onkeyup="this.value = this.value.toUpperCase();">
                                      <span class="invalid-feedback" role="alert">
                                          <strong>A9 is required.</strong>
                                       </span>
                                    </div>
                              </div>
                              <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-4 col-form-label ">A10. School Type:</label>
                                    <div class="col-sm-8 pt-3">
                                          <div class="icheck-primary d-inline">
                                                <input type="radio" id="a10a" name="a10" value="1" @if($surveyAns->a10 == 1) checked="checked" @endif>
                                                <label for="a10a" >PUBLIC</label>
                                          </div>
                                          <div class="icheck-primary d-inline ml-3">
                                                <input type="radio" id="a10b" name="a10" value="2" @if($surveyAns->a10 == 2) checked="checked" @endif>
                                                <label for="a10b">PRIVATE</label>
                                          </div>
                                         
                                         
                                    </div>
                              </div>
                              <div class="row">
                                    <div class="col-md-12">
                                          <p class="bg-danger p-2" >SCHOOL TO ENROLL</p>
                                    </div>
                              </div>
                              <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-4 col-form-label ">A11. School to enroll in: </label>
                                    <div class="col-sm-8">
                                      <input class="form-control" id="a11" name="a11" placeholder="School to enroll in" value="{{$schoolinfo->schoolname}}">
                                    </div>
                              </div>
                              <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-4 col-form-label ">A12. School ID of the school to enroll in: </label>
                                    <div class="col-sm-8">
                                      <input class="form-control" id="a12" name="a12" placeholder="School ID of the school to enroll in:" value="{{$schoolinfo->schoolid}}">
                                    </div>
                              </div>
                              <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-4 col-form-label ">A13. School Address of the school to enroll in: </label>
                                    <div class="col-sm-8">
                                      <input class="form-control" id="a13" name="a13" placeholder="School Address of the school to enroll in" value="{{$schoolinfo->address}}">
                                    </div>
                              </div>
                              <div class="row">
                                    <div class="col-md-12">
                                          <p class="bg-danger p-2" >FOR SENIOR HIGH SCHOOL ONLY:</p>
                                    </div>
                              </div>
                              <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-4 col-form-label ">A14. Semester (1st/2nd): </label>
                                    <div class="col-sm-8 pt-2">
                                          <div class="icheck-primary d-inline">
                                                <input type="radio" id="a14a" name="a14" value="1" onclick="return false;" @if($sem->id == 1)checked @endif>
                                                <label for="a14a">1st Sem</label>
                                          </div>
                                          <div class="icheck-primary d-inline ml-3">
                                                <input type="radio" id="a14b" name="a14" value="2" onclick="return false;" @if($sem->id == 2)checked @endif>
                                                <label for="a14b">2nd Sem</label>
                                          </div>
                                    </div>
                              </div>
                              <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-4 col-form-label ">A15. Track: </label>
                                    <div class="col-sm-8">
                                      <input class="form-control" id="a15" name="a15" placeholder="Track" readonly value="{{$student->trackname}}">
                                    </div>
                              </div>
                              <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-4 col-form-label ">A16. Strand (if any): </label>
                                    <div class="col-sm-8">
                                      <input class="form-control" id="a16" name="a16" placeholder="Strand (if any)" readonly value="{{$student->strandname}}">
                                    </div>
                              </div>
                              <p class="bg-primary p-2">B. STUDENT INFORMATION</p>
                              <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-4 col-form-label ">B1. PSA Birth Certificate No. (if available upon enrolment): </label>
                                    <div class="col-sm-8">
                                      <input class="form-control" id="b1" name="b1" placeholder="B1. PSA Birth Certificate No. (if available upon enrolment)" value="{{$surveyAns->b1}}" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                                    </div>
                              </div>
                              <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-4 col-form-label ">B2. Learner Reference Number (LRN): </label>
                                    <div class="col-sm-8">
                                      <input class="form-control" id="b2" name="b2" placeholder="Learner Reference Number (LRN)" readonly value="{{$student->lrn}}">
                                    </div>
                              </div>
                              <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-4 col-form-label ">B3. LAST NAME: </label>
                                    <div class="col-sm-8">
                                          <input class="form-control" id="b3" name="b3" placeholder="LAST NAME" readonly value="{{$student->lastname}}">
                                    </div>
                              </div>
                              <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-4 col-form-label ">B4. FIRST NAME: </label>
                                    <div class="col-sm-8">
                                          <input class="form-control" id="b4" name="b4" placeholder="FIRST NAME" readonly value="{{$student->firstname}}">
                                    </div>
                              </div>
                              <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-4 col-form-label ">B5. MIDDLE NAME: </label>
                                    <div class="col-sm-8">
                                          <input class="form-control" id="b5" name="b5" placeholder="MIDDLE NAME" readonly value="{{$student->middlename}}">
                                    </div>
                              </div>
                              <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-4 col-form-label ">B6. EXTENSION NAME e.g. Jr., III (if applicable): </label>
                                    <div class="col-sm-8">
                                      <input  class="form-control" id="b6" name="b6" placeholder="EXTENSION NAME e.g. Jr., III (if applicable)" readonly value="{{$student->suffix}}">
                                    </div>
                              </div>
                              <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-4 col-form-label ">B7. Date of Birth: (Month/Day/Year)</label>
                                    <div class="col-sm-8">
                                          <input class="form-control" id="b7" name="b7" placeholder="Date of Birth: (Month/Day/Year)" readonly value="{{\Carbon\Carbon::parse($student->dob)->isoFormat('MM/DD/YYYY')}}">
                                    </div>
                              </div>
                              <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-4 col-form-label ">B8. Age: </label>
                                    <div class="col-sm-8">
                                          <input class="form-control" id="b8" name="b8" placeholder="Age" readonly value="{{\Carbon\Carbon::parse($student->dob)->age}}">
                                    </div>
                              </div>
                              <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-4 col-form-label ">B9. Sex: </label>
                                    <div class="col-sm-8">
                                          <input class="form-control" id="b9" name="b9" placeholder="Sex" readonly value="{{$student->gender}}">
                                    </div>
                              </div>
                              <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-4 col-form-label ">B10. Belonging to Indgenous Community/Indigenous Cultural Community: </label>
                                    <div class="col-sm-8 pt-2">
                                          <div class="icheck-primary d-inline">
                                                <input type="radio" id="b10a" name="b10" value="1" @if($surveyAns->b10 == 1) checked="checked" @endif>
                                                <label for="b10a">Yes</label>
                                          </div>
                                          <div class="icheck-primary d-inline ml-3">
                                                <input type="radio" id="b10b" name="b10" value="2" @if($surveyAns->b10 == 2) checked="checked" @endif>
                                                <label for="b10b">No</label>
                                          </div>
                                    </div>
                              </div>
                              <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-4 col-form-label ">B11. If yes, please specify: </label>
                                    <div class="col-sm-8">
                                          <input class="form-control" id="b11" name="b11" placeholder="If yes, please specify" value="{{$surveyAns->b11}}" onkeyup="this.value = this.value.toUpperCase();">
                                    </div>
                              </div>
                              <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-4 col-form-label ">B12. Mother Tongue: </label>
                                    <div class="col-sm-8">
                                          <input onkeyup="this.value = this.value.toUpperCase();" class="form-control input_field required" id="b12" name="b12" placeholder="Mother Tongue" value="{{$surveyAns->b12}}">
                                          <span class="invalid-feedback" role="alert">
                                                <strong>B12 is required.</strong>
                                          </span>
                                    </div>
                              </div>
                              <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-4 col-form-label ">B13. Religion: </label>
                                    <div class="col-sm-8">
                                          <input onkeyup="this.value = this.value.toUpperCase();" class="form-control input_field required" id="b13" name="b13" placeholder="Religion" value="{{$surveyAns->b13}}">
                                          <span class="invalid-feedback" role="alert">
                                                <strong>B13 is required.</strong>
                                          </span>
                                    </div>
                              </div>
                              <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-4 col-form-label ">B14. Does the learner have special education needs? </label>
                                    <div class="col-sm-8 pt-2">
                                          <div class="icheck-primary d-inline">
                                                <input type="radio" id="b14a" name="b14" value="1" @if($surveyAns->b14 == 1) checked="checked" @endif>
                                                <label for="b14a">Yes</label>
                                          </div>
                                          <div class="icheck-primary d-inline ml-3">
                                                <input type="radio" id="b14b" name="b14" value="2" @if($surveyAns->b14 == 2) checked="checked" @endif>
                                                <label for="b14b">No</label>
                                          </div>
                                    </div>
                              </div>
                              <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-4 col-form-label ">B15. If yes, please specify:</label>
                                    <div class="col-sm-8">
                                          <input class="form-control" id="b15" name="b15" placeholder="If yes, please specify" value="{{$surveyAns->b15}}" onkeyup="this.value = this.value.toUpperCase();">
                                    </div>
                              </div>
                              <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-4 col-form-label ">B16. Do you have any assistive technology devices available at home? (i.e. screen reader, Braille, DAISY) </label>
                                    <div class="col-sm-8 pt-2">
                                          <div class="icheck-primary d-inline">
                                                <input type="radio" id="b16a" name="b16" value="1" @if($surveyAns->b16 == 1) checked="checked" @endif>
                                                <label for="b16a">Yes</label>
                                          </div>
                                          <div class="icheck-primary d-inline ml-3">
                                                <input type="radio" id="b16b" name="b16" value="2" @if($surveyAns->b16 == 2) checked="checked" @endif>
                                                <label for="b16b">No</label>
                                          </div>
                                    </div>
                              </div>
                              <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-4 col-form-label ">B17. If yes, please specify:</label>
                                    <div class="col-sm-8">
                                          <input class="form-control" id="b17" name="b17" placeholder="If yes, please specify:" value="{{$surveyAns->b17}}" onkeyup="this.value = this.value.toUpperCase();">
                                    </div>
                              </div>
                              <p class="bg-danger p-2" >ADDRESS</p>
                              <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-4 col-form-label ">B18. House Number and Street: </label>
                                    <div class="col-sm-8">
                                          <input class="form-control input_field required" id="b18" name="b18" placeholder="House Number and Street" value="{{$student->street}}" onkeyup="this.value = this.value.toUpperCase();">
                                          <span class="invalid-feedback" role="alert">
                                                <strong>B18 is required.</strong>
                                             </span>
                                    </div>
                              </div>
                              <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-4 col-form-label ">B19. Barangay: </label>
                                    <div class="col-sm-8">
                                          <input class="form-control input_field required" id="b19" name="b19" placeholder="Barangay" value="{{$student->barangay}}" onkeyup="this.value = this.value.toUpperCase();">
                                          <span class="invalid-feedback" role="alert">
                                                <strong>B19 is required.</strong>
                                          </span>
                                    </div>
                              </div>
                              <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-4 col-form-label ">B20. City/ Municipality: </label>
                                    <div class="col-sm-8">
                                          <input class="form-control input_field required" id="b20" name="b20" placeholder="City/ Municipality" value="{{$student->city}}" onkeyup="this.value = this.value.toUpperCase();">
                                          <span class="invalid-feedback" role="alert">
                                                <strong>B20 is required.</strong>
                                          </span>
                                    </div>
                              </div>
                              <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-4 col-form-label ">B21. Province: </label>
                                    <div class="col-sm-8">
                                          <input class="form-control input_field required" id="b21" name="b21" placeholder="Province" value="{{$student->province}}" onkeyup="this.value = this.value.toUpperCase();">
                                          <span class="invalid-feedback" role="alert">
                                                <strong>B21 is required.</strong>
                                          </span>
                                    </div>
                              </div>
                              <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-4 col-form-label ">B22. Region: </label>
                                    <div class="col-sm-8">
                                          <input class="form-control  input_field required" id="b22" name="b22" placeholder="Region" value="{{$surveyAns->b22}}">
                                          <span class="invalid-feedback" role="alert">
                                                <strong>B22 is required.</strong>
                                          </span>
                                    </div>
                              </div>
                              <p class="bg-primary p-2">C. PARENT/ GUARDIAN INFORMATION</p>
                              <p class="bg-danger p-2">Father</p>
                              <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-4 col-form-label ">C1. Father's Full Name (surname, full name, middle name): </label>
                                    <div class="col-sm-8">
                                      <input class="form-control" id="c1" name="c1" placeholder="Father's Full Name (surname, full name, middle name)" value="{{$student->fathername}}" onkeyup="this.value = this.value.toUpperCase();" required>
                                    </div>
                              </div>
                              <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-4 col-form-label ">C2. Father's Highest Educational Attainment: </label>
                                    <div class="col-sm-8">
                                          <select name="c2" id="c2" class="form-control">
                                                <option value="">Father's Highest Educational Attainment</option>
                                                <option value="1" @if($surveyAns->c2 == 1) selected @endif>Elementary graduate</option>
                                                <option value="2" @if($surveyAns->c2 == 2) selected @endif">High School graduate</option>
                                                <option value="3" @if($surveyAns->c2 == 3) selected @endif">Vocational</option>
                                                <option value="4" @if($surveyAns->c2 == 4) selected @endif">Master’s/Doctorate degree</option>
                                                <option value="5" @if($surveyAns->c2 == 5) selected @endif">Did not attend school</option>
                                          </select>
                                    </div>
                              </div>
                              <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-4 col-form-label ">C3. Father's Employment Status: </label>
                                    <div class="col-sm-8">
                                          <select name="c3" id="c3" class="form-control">
                                                <option value=""> Father's Employment Status</option>
                                                <option value="1" @if($surveyAns->c3 == 1) selected @endif>Full time</option>
                                                <option value="2" @if($surveyAns->c3 == 2) selected @endif>Part time</option>
                                                <option value="3" @if($surveyAns->c3 == 3) selected @endif>Self-employed (i.e. family business)</option>
                                                <option value="4" @if($surveyAns->c3 == 4) selected @endif>Unemployed due to ECQ</option>
                                                <option value="5" @if($surveyAns->c3 == 5) selected @endif>Not working</option>
                                          </select>
                                    </div>
                              </div>
                              <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-4 col-form-label ">C4. Working from home due to ECQ? </label>
                                    <div class="col-sm-8 pt-2">
                                          <div class="icheck-primary d-inline">
                                                <input type="radio" id="c4a" name="c4" value="1" @if($surveyAns->c4 == 1) checked="checked" @endif>
                                                <label for="c4a">Yes</label>
                                          </div>
                                          <div class="icheck-primary d-inline ml-3">
                                                <input type="radio" id="c4b" name="c4" value="2" @if($surveyAns->c4 == 2) checked="checked" @endif>
                                                <label for="c4b">No</label>
                                          </div>
                                    </div>
                              </div>
                              <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-4 col-form-label ">C5. Father's Contact number/s (cellphone/ telephone): </label>
                                    <div class="col-sm-8">
                                          <input class="form-control" id="c5" name="c5" placeholder="Father's Contact number/s (cellphone/ telephone)" value="{{$student->fcontactno}}">
                                    </div>
                              </div>
                              <p class="bg-danger p-2">Mother</p>
                              <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-4 col-form-label ">C7. Mother's Full Name (surname, full name, middle name): </label>
                                    <div class="col-sm-8">
                                          <input class="form-control" id="c7" name="c7" placeholder="Mother's Full Name (surname, full name, middle name)" value="{{$student->mothername}}" onkeyup="this.value = this.value.toUpperCase();">
                                    </div>
                              </div>
                              <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-4 col-form-label ">C8. Mother's Highest Educational Attainment: </label>
                             
                                    <div class="col-sm-8">
                                          <select name="c8" id="c8" class="form-control">
                                                <option value="">Mother's Highest Educational Attainment</option>
                                                <option value="1" @if($surveyAns->c8 == 1) selected @endif>Elementary graduate</option>
                                                <option value="2" @if($surveyAns->c8 == 2) selected @endif>High School graduate</option>
                                                <option value="3" @if($surveyAns->c8== 3) selected @endif>Vocational</option>
                                                <option value="4" @if($surveyAns->c8 == 4) selected @endif>Master’s/Doctorate degree</option>
                                                <option value="5" @if($surveyAns->c8 == 5) selected @endif>Did not attend school</option>
                                          </select>
                                    </div>
                                   
                              </div>
                              <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-4 col-form-label ">C9. Mother's Employment Status: </label>
                                    <div class="col-sm-8">
                                          <select name="c9" id="c9" class="form-control">
                                                <option value="">Mother's Employment Status</option>
                                                <option value="1" @if($surveyAns->c9 == 1) selected @endif>Full time</option>
                                                <option value="2" @if($surveyAns->c9 == 2) selected @endif>Part time</option>
                                                <option value="3" @if($surveyAns->c9 == 3) selected @endif>Self-employed (i.e. family business)</option>
                                                <option value="4" @if($surveyAns->c9 == 4) selected @endif>Unemployed due to ECQ</option>
                                                <option value="5" @if($surveyAns->c9 == 5) selected @endif>Not working</option>
                                          </select>
                                    </div>
                              </div>
                              <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-4 col-form-label ">C10. Working from home due to ECQ? </label>
                                    <div class="col-sm-8 pt-2">
                                          <div class="icheck-primary d-inline">
                                                <input type="radio" id="c10a" name="c10" value="1" @if($surveyAns->c10 == 1) checked="checked" @endif>
                                                <label for="c10a">Yes</label>
                                          </div>
                                          <div class="icheck-primary d-inline ml-3">
                                                <input type="radio" id="c10b" name="c10" value="2" @if($surveyAns->c10 == 2) checked="checked" @endif>
                                                <label for="c10b">No</label>
                                          </div>
                                    </div>
                              </div>
                              <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-4 col-form-label ">C11. Mother's Contact number/s (cellphone/ telephone): </label>
                                    <div class="col-sm-8">
                                          <input class="form-control" id="c11" name="c11" placeholder="Mother's Contact number/s (cellphone/ telephone)" value="{{$student->mcontactno}}">
                                    </div>
                              </div>
                              <p class="bg-danger p-2">Guardian</p>
                              <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-4 col-form-label ">C13. Guardian's Full Name (surname, full name, middle name): </label>
                                    <div class="col-sm-8">
                                      <input class="form-control" id="c13" name="c13" placeholder="Guardian's Full Name (surname, full name, middle name)" value="{{$student->guardianname}}" onkeyup="this.value = this.value.toUpperCase();">
                                    </div>
                              </div>
                              <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-4 col-form-label ">C14. Guardian's Highest Educational Attainment: </label>
                                    <div class="col-sm-8">
                                          <select name="c14" id="c14" class="form-control">
                                                <option value="">Guardian's Highest Educational Attainment</option>
                                                <option value="1" @if($surveyAns->c14 == 1) selected @endif>Elementary graduate</option>
                                                <option value="2" @if($surveyAns->c14 == 2) selected @endif>High School graduate</option>
                                                <option value="3" @if($surveyAns->c14 == 3) selected @endif>Vocational</option>
                                                <option value="4" @if($surveyAns->c14 == 4) selected @endif>Master’s/Doctorate degree</option>
                                                <option value="5" @if($surveyAns->c14 == 5) selected @endif>Did not attend school</option>
                                          </select>
                                    </div>
                              </div>
                              <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-4 col-form-label ">C15. Guardian's Employment Status: </label>
                                    <div class="col-sm-8">
                                          <select name="c15" id="c15" class="form-control">
                                                <option value="">Guardian's Employment Status</option>
                                                <option value="1" @if($surveyAns->c15 == 1) selected @endif>Full time</option>
                                                <option value="2" @if($surveyAns->c15 == 2) selected @endif>Part time</option>
                                                <option value="3" @if($surveyAns->c15 == 3) selected @endif>Self-employed (i.e. family business)</option>
                                                <option value="4" @if($surveyAns->c15 == 4) selected @endif>Unemployed due to ECQ</option>
                                                <option value="5" @if($surveyAns->c15 == 5) selected @endif>Not working</option>
                                          </select>
                                    </div>
                              </div>
                              <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-4 col-form-label ">C16. Working from home due to ECQ? </label>
                                    <div class="col-sm-8 pt-2">
                                          <div class="icheck-primary d-inline">
                                                <input type="radio" id="c16a" name="c16" value="1" @if($surveyAns->c16 == 1) checked="checked" @endif>
                                                <label for="c16a">Yes</label>
                                          </div>
                                          <div class="icheck-primary d-inline ml-3">
                                                <input type="radio" id="c16b" name="c16" value="2"  @if($surveyAns->c16 == 2) checked="checked" @endif>
                                                <label for="c16b">No</label>
                                          </div>
                                    </div>
                              </div>
                              <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-4 col-form-label ">C17. Guardian's Contact number/s (cellphone/ telephone): </label>
                                    <div class="col-sm-8">
                                          <input class="form-control" id="C17" name="c17" placeholder="Guardian's Contact number/s (cellphone/ telephone)" value="{{$student->gcontactno}}">
                                    </div>
                              </div>
                              <p class="bg-primary p-2">D. HOUSEHOLD CAPACITY AND  ACCESS TO DISTANCE  LEARNING</p>
                              <div class="form-group row">
                                    
                                    <label for="inputEmail3" class="col-sm-4 col-form-label ">D1. How does your child go to school? Choose all that applies. <br> <i class="text-danger d1_warning" hidden>* D1 is required</i></label>
                                    <div class="col-sm-8 pt-2">
                                          <div class="row">
                                                <div class="col-md-6">
                                                      <div class="icheck-primary d-inline">
                                                            <input type="checkbox" id="d1a" name="d1[]" value="1" @if(collect(explode(" ",$surveyAns->d1))->contains('1')) checked="checked" @endif class="d1_input">
                                                            <label for="d1a">Walking</label>
                                                      </div>
                                                </div>
                                                <div class="col-md-6">
                                                      <div class="icheck-primary d-inline">
                                                            <input type="checkbox" id="d1b" name="d1[]" value="2" @if(collect(explode(" ",$surveyAns->d1))->contains('2')) checked="checked" @endif class="d1_input">
                                                            <label for="d1b">Public Commute (land/water)</label>
                                                      </div>
                                                </div>
                                                <div class="col-md-6">
                                                      <div class="icheck-primary d-inline">
                                                            <input type="checkbox" id="d1c" name="d1[]" value="3" @if(collect(explode(" ",$surveyAns->d1))->contains('3')) checked="checked" @endif class="d1_input">
                                                            <label for="d1c">Family-owned vehicle</label>
                                                      </div>
                                                </div>
                                                <div class="col-md-6">
                                                      <div class="icheck-primary d-inline">
                                                            <input type="checkbox" id="d1d" name="d1[]" value="4" @if(collect(explode(" ",$surveyAns->d1))->contains('4')) checked="checked" @endif class="d1_input">
                                                            <label for="d1d">School Service</label>
                                                      </div>
                                                </div>
                                          </div>
                                    </div>
                              </div>
                              <div class="form-group row border-top">
                                    <label for="inputEmail3" class="col-sm-4 col-form-label ">D3. Who among the household members can provide instructional support to the child’s distance learning? Choose all that applies. <br> <i class="text-danger d3_warning" hidden>* D3 is required</i></label>
                                    <div class="col-sm-8 pt-2">
                                          <div class="row">
                                                <div class="col-md-6">
                                                      <div class="icheck-primary d-inline">
                                                            <input type="checkbox" id="d3a" name="d3[]" value="1" @if(collect(explode(" ",$surveyAns->d3))->contains('1')) checked="checked" @endif class="d3_input">
                                                            <label for="d3a">parents/ guardians</label>
                                                      </div>
                                                </div>
                                                <div class="col-md-6">
                                                      <div class="icheck-primary d-inline">
                                                            <input type="checkbox" id="d3b" name="d3[]" value="2" @if(collect(explode(" ",$surveyAns->d3))->contains('2')) checked="checked" @endif class="d3_input">
                                                            <label for="d3b">elder siblings </label>
                                                      </div>
                                                </div>
                                                <div class="col-md-6">
                                                      <div class="icheck-primary d-inline">
                                                            <input type="checkbox" id="d3c" name="d3[]" value="3" @if(collect(explode(" ",$surveyAns->d3))->contains('3')) checked="checked" @endif class="d3_input">
                                                            <label for="d3c">grandparents</label>
                                                      </div>
                                                </div>
                                                <div class="col-md-6">
                                                      <div class="icheck-primary d-inline">
                                                            <input type="checkbox" id="d3d" name="d3[]" value="4" @if(collect(explode(" ",$surveyAns->d3))->contains('4')) checked="checked" @endif class="d3_input">
                                                            <label for="d3d">extended members of the family</label>
                                                      </div>
                                                </div>
                                                <div class="col-md-6">
                                                      <div class="icheck-primary d-inline">
                                                            <input type="checkbox" id="d3e" name="d3[]" value="5" @if(collect(explode(" ",$surveyAns->d3))->contains('5')) checked="checked" @endif class="d3_input">
                                                            <label for="d3e">others (tutor, house helper)</label>
                                                      </div>
                                                </div>
                                                <div class="col-md-6">
                                                      <div class="icheck-primary d-inline">
                                                            <input type="checkbox" id="d3f" name="d3[]" value="6" @if(collect(explode(" ",$surveyAns->d3))->contains('6')) checked="checked" @endif class="d3_input">
                                                            <label for="d3f">none</label>
                                                      </div>
                                                </div>
                                                <div class="col-md-6">
                                                      <div class="icheck-primary d-inline">
                                                            <input type="checkbox" id="d3g" name="d3[]" value="7" @if(collect(explode(" ",$surveyAns->d3))->contains('7')) checked="checked" @endif class="d3_input">
                                                            <label for="d3g">able to do independent learning</label>
                                                      </div>
                                                </div>
                                          </div>
                                         
                                    </div>
                              </div>
                              <br>
                              <div class="form-group row border-top">
                                    <label for="inputEmail3" class="col-sm-4 col-form-label ">D4. What devices are available at home that the learner can use for learning? Check all that applies. <br> <i class="text-danger d4_warning" hidden>* D4 is required</i></label>
                                    <div class="col-sm-8 pt-2">
                                          <div class="row">
                                                <div class="col-md-6">
                                                      <div class="icheck-primary d-inline">
                                                            <input type="checkbox" id="d4a" name="d4[]" value="1" @if(collect(explode(" ",$surveyAns->d4))->contains('1')) checked="checked" @endif class="d4_input">
                                                            <label for="d4a">cable TV</label>
                                                      </div>
                                                </div>
                                                <div class="col-md-6">
                                                      <div class="icheck-primary d-inline">
                                                            <input type="checkbox" id="d4b" name="d4[]" value="2" @if(collect(explode(" ",$surveyAns->d4))->contains('2')) checked="checked" @endif class="d4_input">
                                                            <label for="d4b">non-cable TV</label>
                                                      </div>
                                                </div>
                                                <div class="col-md-6">
                                                      <div class="icheck-primary d-inline">
                                                            <input type="checkbox" id="d4c" name="d4[]" value="3" @if(collect(explode(" ",$surveyAns->d4))->contains('3')) checked="checked" @endif class="d4_input">
                                                            <label for="d4c">basic cellphone</label>
                                                      </div>
                                                </div>
                                                <div class="col-md-6">
                                                      <div class="icheck-primary d-inline">
                                                            <input type="checkbox" id="d4d" name="d4[]" value="4" @if(collect(explode(" ",$surveyAns->d4))->contains('4')) checked="checked" @endif class="d4_input">
                                                            <label for="d4d">smartphone</label>
                                                      </div>
                                                </div>
                                                <div class="col-md-6">
                                                      <div class="icheck-primary d-inline">
                                                            <input type="checkbox" id="d4e" name="d4[]" value="5" @if(collect(explode(" ",$surveyAns->d4))->contains('5')) checked="checked" @endif class="d4_input">
                                                            <label for="d4e">tablet</label>
                                                      </div>
                                                </div>
                                                <div class="col-md-6">
                                                      <div class="icheck-primary d-inline">
                                                            <input type="checkbox" id="d4f" name="d4[]" value="6" @if(collect(explode(" ",$surveyAns->d4))->contains('6')) checked="checked" @endif class="d4_input">
                                                            <label for="d4f">radio</label>
                                                      </div>
                                                </div>
                                                <div class="col-md-6">
                                                      <div class="icheck-primary d-inline">
                                                            <input type="checkbox" id="d4g" name="d4[]" value="7" @if(collect(explode(" ",$surveyAns->d4))->contains('7')) checked="checked" @endif class="d4_input">
                                                            <label for="d4g">desktop computer</label>
                                                      </div>
                                                </div>
                                              
                                                <div class="col-md-6">
                                                      <div class="icheck-primary d-inline">
                                                            <input type="checkbox" id="d4h" name="d4[]" value="8" @if(collect(explode(" ",$surveyAns->d4))->contains('8')) checked="checked" @endif class="d4_input">
                                                            <label for="d4h">laptop</label>
                                                      </div>
                                                </div>
                                                <div class="col-md-6">
                                                      <div class="icheck-primary d-inline">
                                                            <input type="checkbox" id="d4i" name="d4[]" value="9" @if(collect(explode(" ",$surveyAns->d4))->contains('9')) checked="checked" @endif class="d4_input">
                                                            <label for="d4i">none</label>
                                                      </div>
                                                </div>
                                          </div>
                                          <input class="form-control mt-2" id="d4others" name="d4others" placeholder="Others.." value="{{$surveyAns->d4others}}">
                                    </div>
                              </div>
                              <div class="form-group row border-top">
                                    <label for="inputEmail3" class="col-sm-4 col-form-label ">D5. Do you have a way to connect to the internet? <br> <i class="text-danger d5_warning" hidden>* D5 is required</i></label>
                                    <div class="col-sm-8 pt-3">
                                          <div class="icheck-primary d-inline">
                                                <input type="radio" id="d5a" name="d5" value="1" @if($surveyAns->d5 == 1) checked="checked" @endif class="d5_input" class="d5_input">
                                                <label for="d5a">Yes</label>
                                          </div>
                                          <div class="icheck-primary d-inline ml-3">
                                                <input type="radio" id="d5b" name="d5" value="2" @if($surveyAns->d5 == 2) checked="checked" @endif class="d5_input" class="d5_input">
                                                <label for="d5b">No (If NO, proceed to D7)</label>
                                          </div>
                                    </div>
                              </div>
                              <div class="form-group row border-top">
                                    <label for="inputEmail3" class="col-sm-4 col-form-label ">D6. How do you connect to the internet? Choose all that applies. <br> <i class="text-danger d6_warning" hidden>* D6 is required</i></label>
                                    <div class="col-sm-8 pt-2">
                                          <div class="row">
                                                <div class="col-md-6">
                                                      <div class="icheck-primary d-inline">
                                                            <input type="checkbox" id="d6a" name="d6[]" value="1" @if(collect(explode(" ",$surveyAns->d6))->contains('1')) checked="checked" @endif  class="d6_input" >
                                                            <label for="d6a">own mobile data</label>
                                                      </div>
                                                </div>
                                                <div class="col-md-6">
                                                      <div class="icheck-primary d-inline">
                                                            <input type="checkbox" id="d6b" name="d6[]" value="2" @if(collect(explode(" ",$surveyAns->d6))->contains('2')) checked="checked" @endif  class="d6_input">
                                                            <label for="d6b">computer shop</label>
                                                      </div>
                                                </div>
                                                <div class="col-md-6">
                                                      <div class="icheck-primary d-inline">
                                                            <input type="checkbox" id="d6c" name="d6[]" value="3" @if(collect(explode(" ",$surveyAns->d6))->contains('3')) checked="checked" @endif class="d6_input">
                                                            <label for="d6c">other places outside the home with internet connection (library, barangay/ municipal hall, neighbor, relatives)</label>
                                                      </div>
                                                </div>
                                                <div class="col-md-6">
                                                      <div class="icheck-primary d-inline">
                                                            <input type="checkbox" id="d6d" name="d6[]" value="4" @if(collect(explode(" ",$surveyAns->d6))->contains('4')) checked="checked" @endif class="d6_input">
                                                            <label for="d6d">own broadband internet (DSL, wireless fiber, satellite)</label>
                                                      </div>
                                                </div>
                                                <div class="col-md-6">
                                                      <div class="icheck-primary d-inline">
                                                            <input type="checkbox" id="d6e" name="d6[]" value="5" @if(collect(explode(" ",$surveyAns->d6))->contains('5')) checked="checked" @endif class="d6_input">
                                                            <label for="d6e">none</label>
                                                      </div>
                                                </div>
                                          </div>
                                    </div>
                              </div>
                              <div class="form-group row border-top">
                                    <label for="inputEmail3" class="col-sm-4 col-form-label ">D7. What distance learning modality/ies do you prefer for your child? Choose all that applies. <br> <i class="text-danger d7_warning" hidden>* D7 is required</i></label>
                                    <div class="col-sm-8 pt-2">
                                          <div class="row">
                                                <div class="col-md-6">
                                                      <div class="icheck-primary d-inline">
                                                            <input type="checkbox" id="d7a" name="d7[]" value="1" @if(collect(explode(" ",$surveyAns->d6))->contains('1')) checked="checked" @endif class="d7_input">
                                                            <label for="d7a">online learning</label>
                                                      </div>
                                                </div>
                                                <div class="col-md-6">
                                                      <div class="icheck-primary d-inline">
                                                            <input type="checkbox" id="d7b" name="d7[]" value="2" @if(collect(explode(" ",$surveyAns->d6))->contains('2')) checked="checked" @endif class="d7_input">
                                                            <label for="d7b">television</label>
                                                      </div>
                                                </div>
                                                <div class="col-md-6">
                                                      <div class="icheck-primary d-inline">
                                                            <input type="checkbox" id="d7c" name="d7[]" value="3" @if(collect(explode(" ",$surveyAns->d6))->contains('3')) checked="checked" @endif class="d7_input">
                                                            <label for="d7c">radio</label>
                                                      </div>
                                                </div>
                                                <div class="col-md-6">
                                                      <div class="icheck-primary d-inline">
                                                            <input type="checkbox" id="d7d" name="d7[]" value="4" @if(collect(explode(" ",$surveyAns->d6))->contains('4')) checked="checked" @endif class="d7_input">
                                                            <label for="d7d">modular learning</label>
                                                      </div>
                                                </div>
                                                <div class="col-md-6">
                                                      <div class="icheck-primary d-inline">
                                                            <input type="checkbox" id="d7e" name="d7[]" value="5" @if(collect(explode(" ",$surveyAns->d6))->contains('5')) checked="checked" @endif class="d7_input">
                                                            <label for="d7e">combination of face to face with other modalities</label>
                                                      </div>
                                                </div>
                                          </div>
                                          <input class="form-control mt-2" id="d7others" name="d7others" placeholder="Others.." value="{{$surveyAns->d7others}}">
                                    </div>
                              </div>
                              <div class="form-group row border-top">
                                    <label for="inputEmail3" class="col-sm-4 col-form-label ">D8. What are the challenges that may affect your child’s learning process through distance education? Choose all that applies. <br> <i class="text-danger d8_warning" hidden>* D8 is required</i></label>
                                    <div class="col-sm-8 pt-2">
                                          <div class="row">
                                                <div class="col-md-12">
                                                      <div class="icheck-primary d-inline">
                                                            <input type="checkbox" id="d8a" name="d8[]" value="1" @if(collect(explode(" ",$surveyAns->d8))->contains('1')) checked="checked" @endif class="d8_input">
                                                            <label for="d8a">lack of available gadgets/ equipment</label>
                                                      </div>
                                                </div>
                                                <div class="col-md-12">
                                                      <div class="icheck-primary d-inline">
                                                            <input type="checkbox" id="d8b" name="d8[]" value="2" @if(collect(explode(" ",$surveyAns->d8))->contains('2')) checked="checked" @endif class="d8_input">
                                                            <label for="d8b">insufficient load/ data allowance</label>
                                                      </div>
                                                </div>
                                                <div class="col-md-12">
                                                      <div class="icheck-primary d-inline">
                                                            <input type="checkbox" id="d8c" name="d8[]" value="3" @if(collect(explode(" ",$surveyAns->d8))->contains('3')) checked="checked" @endif class="d8_input">
                                                            <label for="d8c">unstable mobile/ internet connection</label>
                                                      </div>
                                                </div>
                                                <div class="col-md-12">
                                                      <div class="icheck-primary d-inline">
                                                            <input type="checkbox" id="d8d" name="d8[]" value="4" @if(collect(explode(" ",$surveyAns->d8))->contains('4')) checked="checked" @endif class="d8_input">
                                                            <label for="d8d">existing health condition/s</label>
                                                      </div>
                                                </div>
                                                <div class="col-md-12">
                                                      <div class="icheck-primary d-inline">
                                                            <input type="checkbox" id="d8e" name="d8[]" value="5" @if(collect(explode(" ",$surveyAns->d8))->contains('5')) checked="checked" @endif class="d8_input">
                                                            <label for="d8e">difficulty in independent learning</label>
                                                      </div>
                                                </div>
                                                <div class="col-md-12">
                                                      <div class="icheck-primary d-inline">
                                                            <input type="checkbox" id="d8f" name="d8[]" value="6" @if(collect(explode(" ",$surveyAns->d8))->contains('6')) checked="checked" @endif class="d8_input">
                                                            <label for="d8f">conflict with other activities (i.e., house chores)</label>
                                                      </div>
                                                </div>
                                                <div class="col-md-12">
                                                      <div class="icheck-primary d-inline">
                                                            <input type="checkbox" id="d8g" name="d8[]" value="7" @if(collect(explode(" ",$surveyAns->d8))->contains('7')) checked="checked" @endif class="d8_input">
                                                            <label for="d8g">high electrical consumption</label>
                                                      </div>
                                                </div>
                                                <div class="col-md-12">
                                                      <div class="icheck-primary d-inline">
                                                            <input type="checkbox" id="d8h" name="d8[]" value="8" @if(collect(explode(" ",$surveyAns->d8))->contains('8')) checked="checked" @endif class="d8_input">
                                                            <label for="d8h">distractions (i.e., social media, noise from community/neighbor)</label>
                                                      </div>
                                                </div>
                                          </div>
                                          <input class="form-control mt-2" id="d8others" name="d8others" placeholder="Others.." value="{{$surveyAns->d8others}}">
                                    </div>
                              </div>
                              <p class="border-top pt-2">
                                    I hereby certify that the above information given are true and correct to the best of my knowledge and I allow the Department of Education to use my child’s details to create and/or update his/her learner profile in the Learner Information System. The information herein shall be treated as confidential in compliance with the Data Privacy Act of 2012.
                              </p>
                              
                             
                                    <div class="row">
                                          <button type="submit" class="btn btn-primary">SUBMIT SURVEY</button>
                                    </div>
                             
                        </div>
                    </div>
                </div>
           
                  </form>
     
            </div>
        </section>

        <script src="{{asset('plugins/inputmask/min/jquery.inputmask.bundle.min.js')}}"></script>
        <script>
              $(document).ready(function(){

                  var student = @json($student);
                  var survey_exist = @json($checkIfExist);

                  const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                  })


                  
                  $("#a6").inputmask({mask: "9999-9999"});

                  $(document).on('click','#d3f',function(a,b){
                        if($(this).prop('checked')){
                              $('.d3_input').prop('checked',false)
                              $(this).prop('checked',true)
                        }
                  })

                  $(document).on('click','#d4i',function(a,b){
                        if($(this).prop('checked')){
                              $('.d4_input').prop('checked',false)
                              $(this).prop('checked',true)
                        }
                  })

                  $(document).on('click','#d6e',function(a,b){
                        if($(this).prop('checked')){
                              $('.d6_input').prop('checked',false)
                              $(this).prop('checked',true)
                        }
                  })

                  $( '#survey_form' )
                        .submit( function( e ) {



                              var valid_input =true
                              
                              if($(".d3_input:checked").length == 0){
                                    valid_input = false
                                    $('.d3_warning').removeAttr('hidden')
                              }else{
                                    $('.d3_warning').attr('hidden','hidden')
                              }

                              if($(".d1_input:checked").length == 0){
                                    valid_input = false
                                    $('.d1_warning').removeAttr('hidden')
                              }else{
                                    $('.d1_warning').attr('hidden','hidden')
                              }

                              if($(".d4_input:checked").length == 0){
                                    valid_input = false
                                    $('.d4_warning').removeAttr('hidden')
                              }else{
                                    $('.d4_warning').attr('hidden','hidden')
                              }

                              

                            
                              if($('.d5_input:checked').val() == undefined){
                                    $('.d5_warning').removeAttr('hidden')
                              }else{
                                    $('.d5_warning').attr('hidden','hidden')
                                    if($('.d5_input:checked').val() == 1){
                                          if($(".d6_input:checked").length == 0){
                                                valid_input = false
                                                $('.d6_warning').removeAttr('hidden')
                                          }else{
                                                $('.d6_warning').attr('hidden','hidden')
                                          }
                                    }else{
                                          $('.d6_input').prop('checked',false)
                                          $('.d6_warning').attr('hidden','hidden')
                                    }

                                    if($(".d7_input:checked").length == 0){
                                          valid_input = false
                                          $('.d7_warning').removeAttr('hidden')
                                    }else{
                                          $('.d7_warning').attr('hidden','hidden')
                                    }

                              }

                              if($(".d8_input:checked").length == 0){
                                    valid_input = false
                                    $('.d8_warning').removeAttr('hidden')
                              }else{
                                    $('.d8_warning').attr('hidden','hidden')
                              }
                             
                              
                              $('.input_field').each(function(a,b){
                                    if($(b).hasClass('required')){
                                          if($(b).val() == ""){
                                                window.location.hash = $(b).attr('id');
                                                valid_input = false
                                                $(b).addClass('is-invalid')
                                          }else{
                                                $(b).removeClass('is-invalid')
                                          }
                                    }
                              })

                              if(!valid_input){
                                    Toast.fire({
                                          type: 'warning',
                                          title: 'Please fill up the required fields!'
                                    })
                              }
                              else{
                                    Swal.fire({
                                          title: 'Do you want to submit survey form?',
                                          type: 'warning',
                                          showCancelButton: true,
                                          confirmButtonColor: '#3085d6',
                                          cancelButtonColor: '#d33',
                                          confirmButtonText: 'Submit'
                                    }).then((result) => {
                                          if (result.value) {
                                                $('#survey_form')[0].submit()
                                          }
                                    })

                              }
                             
                              e.preventDefault();
                        
                  })
                  
              })
        </script>




      

   

@endsection
