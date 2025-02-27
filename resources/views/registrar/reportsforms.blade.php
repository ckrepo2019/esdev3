
@extends('registrar.layouts.app')
@section('content')
    <section class="content-header">
        <div class="col-12">
            @if($academicprogram == 'preschool')
                <h4>Pre-school</h4>
            @elseif($academicprogram == 'elementary')
                <h4>Elementary</h4>
            @elseif($academicprogram == 'juniorhighschool')
                <h4>Junior High School</h4>
            @elseif($academicprogram == 'seniorhighschool')
                <h4>Senior High School</h4>
            @endif
        </div>
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active">School Forms</a></li>
                </ol>
                </div>
            </div>
        </div>
    </section>
    @php
         
         $rand = array('B54647', 'B68C4C', 'E0D29D', '54B0B9', '2F977A');
         $studentmasterlistcolor = $rand[rand(0,4)];
         $form1color = $rand[rand(0,4)];

    @endphp
    <div class="row d-flex align-items-stretch">

        <div class="col-12 col-sm-4 col-md-3 d-flex align-items-stretch">
          <div class="card col-md-12 " style="background-color:{{$studentmasterlistcolor}}">
              <div class="card-header text-muted border-bottom-0" >
                {{-- Digital Strategist --}}
              </div>
              
              <div class="card-body pt-0 bg-white">
                <div class="row">
                  <div class="col-12 ">
                    <h2 class="lead"><b>Student Masterlist</b></h2>
                    <p class="text-muted text-sm">&nbsp;</p>
                  </div>
                </div>
              </div>
              <div class="card-footer">
                <div class="text-right">
                  {{-- <a href="#" class="btn btn-sm bg-teal">
                  </a> --}}
                  
                <form action="/reports/selectSy"  method="GET">
                    <input type="hidden" value="Student Masterlist" name="selectedform"/>
                    <input type="hidden" value="{{$academicprogram}}" name="academicprogram"/>
                    <button type="submit" class="mt-auto btn btn-sm btn-success">Select</button>
                    
                </form>
                </div>
              </div>
            </div>
          </div>
          <div class="col-12 col-sm-4 col-md-3 d-flex align-items-stretch">
            <div class="card col-md-12 " style="background-color:{{$form1color}}">
                <div class="card-header text-muted border-bottom-0" >
                  {{-- Digital Strategist --}}
                </div>
                
                <div class="card-body pt-0 bg-white">
                  <div class="row">
                    <div class="col-12 ">
                      <h2 class="lead"><b>School Form 1</b></h2>
                      <p class="text-muted text-sm">School Register</p>
                    </div>
                  </div>
                </div>
                <div class="card-footer">
                  <div class="text-right">
                    {{-- <a href="#" class="btn btn-sm bg-teal">
                    </a> --}}
                    
                  <form action="/reports/selectSy"  method="GET">
                      <input type="hidden" value="School Form 1" name="selectedform"/>
                      <input type="hidden" value="{{$academicprogram}}" name="academicprogram"/>
                      <button type="submit" class="mt-auto btn btn-sm btn-success">Select</button>
                      
                  </form>
                  </div>
                </div>
              </div>
            </div>
        @if($academicprogram == 'elementary' || $academicprogram == 'juniorhighschool')
        @php
          
          // $form5color = '#'.$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)];
         $form5color = $rand[rand(0,4)];
        @endphp
        <div class="col-12 col-sm-4 col-md-3 d-flex align-items-stretch">
            <div class="card  col-md-12" style="background-color:{{$form5color}}">
              <div class="card-header text-muted border-bottom-0">
                {{-- Digital Strategist --}}
              </div>
              
              <div class="card-body pt-0 bg-white">
                <div class="row">
                  <div class="col-12">
                    <h2 class="lead"><b>School Form 5</b></h2>
                    <p class="text-muted text-sm"> Report on Promotion & Level of Proficiency </p>
                  </div>
                  <input type="hidden" value="School Form 5" name="selectedform"/>
                  <input type="hidden" value="{{$academicprogram}}" name="academicprogram"/>
                </div>
              </div>
              <div class="card-footer">
                <div class="text-right">
                    
            <form action="/reports/selectSy"  method="GET">
                  {{-- <a href="#" class="btn btn-sm bg-teal">
                  </a> --}}
                  <input type="hidden" value="School Form 5" name="selectedform"/>
                  <input type="hidden" value="{{$academicprogram}}" name="academicprogram"/>
                  <button type="submit" class="btn btn-sm btn-success m-0">Select</button>
            </form>
                </div>
              </div>
            </div>
          </div>
            {{-- <div class="col-md-3 col-sm-6 col-12 mb-2">
                <form action="/reports/selectSy"  method="GET">
                    <div class="info-box  m-0">
                        <span class="info-box-icon"><i class="fa fa-file"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-number">School Form 5</span>
                            <small>Report on Promotion & Level of Proficiency</small>
                        </div>
                        <input type="hidden" value="School Form 5" name="selectedform"/>
                        <input type="hidden" value="{{$academicprogram}}" name="academicprogram"/>
                    <!-- /.info-box-content -->
                    </div>
                    <button type="submit" class="btn btn-block btn-success m-0">Select</button>
                </form>
                <!-- /.info-box -->
            </div> --}}
        @endif
        
        @if($academicprogram == 'seniorhighschool')
      
        @php
          
         $form5acolor = $rand[rand(0,4)];
          // $form5acolor = '#'.$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)];
        @endphp
        <div class="col-12 col-sm-4 col-md-3 d-flex align-items-stretch">
            <div class="card  col-md-12"  style="background-color:{{$form5acolor}}">
              <div class="card-header text-muted border-bottom-0">
                {{-- Digital Strategist --}}
              </div>
              
              <div class="card-body pt-0 bg-white">
                <div class="row">
                  <div class="col-12">
                    <h2 class="lead"><b>School Form 5A</b></h2>
                    <p class="text-muted text-sm"> End of Semester and School Year Status of Learners for Senior High School </p>
                  </div>
                </div>
              </div>
              <div class="card-footer">
                <div class="text-right">
                    <form action="/reports/selectSy"  method="GET">
                  {{-- <a href="#" class="btn btn-sm bg-teal">
                  </a> --}}
                  <input type="hidden" value="School Form 5A" name="selectedform"/>
                  <input type="hidden" value="{{$academicprogram}}" name="academicprogram"/>
                  <button type="submit" class="btn btn-sm btn-success m-0">Select</button>
                    </form>
                </div>
              </div>
            </div>
          </div>
      
          @php
            
         $form5bcolor = $rand[rand(0,4)];
            // $form5bcolor = '#'.$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)];
          @endphp
          <div class="col-12 col-sm-4 col-md-3 d-flex align-items-stretch">
              <div class="card  col-md-12" style="background-color:{{$form5bcolor}}">
                <div class="card-header text-muted border-bottom-0">
                  {{-- Digital Strategist --}}
                </div>
                
                <div class="card-body pt-0 bg-white">
                  <div class="row">
                    <div class="col-12">
                      <h2 class="lead"><b>School Form 5B</b></h2>
                      <p class="text-muted text-sm"> List of Learners with Complete SHS Requirements </p>
                    </div>
                  </div>
                </div>
                <div class="card-footer">
                  <div class="text-right">
                    <form action="/reports/selectSy"  method="GET">
                    {{-- <a href="#" class="btn btn-sm bg-teal">
                    </a> --}}
                    <input type="hidden" value="School Form 5B" name="selectedform"/>
                    <input type="hidden" value="{{$academicprogram}}" name="academicprogram"/>
                    <button type="submit" class="btn btn-sm btn-success m-0">Select</button>
                    </form>
                  </div>
                </div>
              </div>
            </div>
            {{-- <div class="col-md-3 col-sm-6 col-12 mb-2">
                <form action="/reports/selectSy"  method="GET">
                    <div class="info-box  m-0">
                        <span class="info-box-icon"><i class="fa fa-file"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text"></span>
                            <span class="info-box-number">School Form 5A</span>
                            <small>End of Semester and School Year Status of Learners for Senior High School</small>
                        </div>
                        <input type="hidden" value="School Form 5A" name="selectedform"/>
                        <input type="hidden" value="{{$academicprogram}}" name="academicprogram"/>
                    </div>
                    <button type="submit" class="btn btn-block btn-success m-0">Select</button>
                </form>
                <!-- /.info-box -->
            </div>
            <div class="col-md-3 col-sm-6 col-12 mb-2">
                <form action="/reports/selectSy"  method="GET">
                    <div class="info-box  m-0">
                        <span class="info-box-icon"><i class="fa fa-file"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text"></span>
                            <span class="info-box-number">School Form 5B</span>
                            <small>List of Learners with Complete SHS Requirements </small>
                        </div>
                        <input type="hidden" value="School Form 5B" name="selectedform"/>
                        <input type="hidden" value="{{$academicprogram}}" name="academicprogram"/>
                    <!-- /.info-box-content -->
                    </div>
                    <button type="submit" class="btn btn-block btn-success m-0">Select</button>
                </form>
                <!-- /.info-box -->
            </div> --}}
        @endif
        {{-- @if($academicprogram == 'elementary' || $academicprogram == 'juniorhighschool' || $academicprogram == 'seniorhighschool') --}}
       
        {{-- @php
            
        $form6color = '#'.$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)];
      @endphp
        <div class="col-12 col-sm-4 col-md-3 d-flex align-items-stretch">
            <div class="card  col-md-12" style="background-color:{{$form6color}}">
              <div class="card-header text-muted border-bottom-0">
              </div>
              
              <div class="card-body pt-0 bg-white">
                <div class="row">
                  <div class="col-12">
                    <h2 class="lead"><b>School Form 6</b></h2>
                    <p class="text-muted text-sm"> School Form 6 (SF6) Summarized Report on Promotion and Level of Proficiency </p>
                  </div>
                </div>
              </div>
              <div class="card-footer">
                <div class="text-right">
                    <form action="/reports/selectSy"  method="GET">
                  <input type="hidden" value="School Form 6" name="selectedform"/>
                  <input type="hidden" value="{{$academicprogram}}" name="academicprogram"/>
                  <button type="submit" class="btn btn-sm btn-success m-0">Select</button>
                    </form>
                </div>
              </div>
            </div>
          </div> --}}
            @php
            
         $form9color = $rand[rand(0,4)];
            // $form9color = '#'.$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)];
          @endphp
          <div class="col-12 col-sm-4 col-md-3 d-flex align-items-stretch">
            <div class="card  col-md-12" style="background-color:{{$form9color}}">
              <div class="card-header text-muted border-bottom-0">
                {{-- Digital Strategist --}}
              </div>
              
              <div class="card-body pt-0 bg-white">
                <div class="row">
                  <div class="col-12">
                    <h2 class="lead"><b>School Form 9</b></h2>
                    <p class="text-muted text-sm"> Learner's Progress Report Card </p>
                  </div>
                  <input type="hidden" value="School Form 9" name="selectedform"/>
                  <input type="hidden" value="{{$academicprogram}}" name="academicprogram"/>
                </div>
              </div>
              <div class="card-footer">
                <div class="text-right">
                  <form action="/reports/selectSy"  method="GET">
                {{-- <a href="#" class="btn btn-sm bg-teal">
                </a> --}}
                <input type="hidden" value="School Form 9" name="selectedform"/>
                <input type="hidden" value="{{$academicprogram}}" name="academicprogram"/>
                <button type="submit" class="btn btn-sm btn-success m-0">Select</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        {{-- @endif --}}
        @if($academicprogram == 'elementary')
      
        @php
            
         $form10color = $rand[rand(0,4)];
        // $form10color = '#'.$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)];
      @endphp
        {{-- <div class="col-12 col-sm-4 col-md-3 d-flex align-items-stretch">
            <div class="card  col-md-12" style="background-color:{{$form10color}}">
              <div class="card-header text-muted border-bottom-0">
              </div>
              
              <div class="card-body pt-0 bg-white">
                <div class="row">
                  <div class="col-12">
                    <h2 class="lead"><b>School Form 10</b></h2>
                    <p class="text-muted text-sm">  Learner’s Permanent Academic Record</p>
                  </div>
                </div>
              </div>
              <div class="card-footer">
                <div class="text-right">
                    <form action="/reports/studentsform10"  method="GET">
                  <input type="hidden" name="academicprogram" value="elementary"/>
                  <button type="submit" class="btn btn-sm btn-success m-0">Select</button>
                  <button type="button" disabled class="btn btn-sm btn-success m-0 btn-block">Still working on this report</button>
                    </form>
                </div>
              </div>
            </div>
          </div> --}}
        @endif
        @if($academicprogram == 'juniorhighschool')
      
        @php
            
         $form10juncolor = $rand[rand(0,4)];
        // $form10juncolor = '#'.$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)];
      @endphp
        {{-- <div class="col-12 col-sm-4 col-md-3 d-flex align-items-stretch">
            <div class="card  col-md-12" style="background-color:{{$form10juncolor}}">
              <div class="card-header text-muted border-bottom-0">
              </div>
              
              <div class="card-body pt-0 bg-white">
                <div class="row">
                  <div class="col-12">
                    <h2 class="lead"><b>School Form 10</b></h2>
                    <p class="text-muted text-sm">  Learner’s Permanent Academic Record</p>
                  </div>
                </div>
              </div>
              <div class="card-footer">
                <div class="text-right">
                    <form action="/reports/studentsform10"  method="GET">
                  <input type="hidden" name="academicprogram" value="juniorhighschool"/>
                  <button type="submit" class="btn btn-sm btn-success m-0">Select</button>
                  <button type="button" disabled class="btn btn-sm btn-success m-0 btn-block">Still working on this report</button>
                    </form>
                </div>
              </div>
            </div>
          </div> --}}
        @endif
        @if($academicprogram == 'seniorhighschool')
       
        @php
            
         $form10sencolor = $rand[rand(0,4)];
        // $form10sencolor = '#'.$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)];
      @endphp
        {{-- <div class="col-12 col-sm-4 col-md-3 d-flex align-items-stretch">
            <div class="card  col-md-12" style="background-color:{{$form10sencolor}}">
              <div class="card-header text-muted border-bottom-0">
                Digital Strategist
              </div>
              
              <div class="card-body pt-0 bg-white">
                <div class="row">
                  <div class="col-12">
                    <h2 class="lead"><b>School Form 10</b></h2>
                    <p class="text-muted text-sm"> Learner’s Permanent Academic Record </p>
                  </div>
                  <input type="hidden" value="School Form 10" name="selectedform"/>
                  <input type="hidden" value="{{$academicprogram}}" name="academicprogram"/>
                </div>
              </div>
              <div class="card-footer">
                <div class="text-right">
                    <form action="/reports/studentsform10"  method="GET">
                  <input type="hidden" name="academicprogram" value="seniorhighschool"/>
                  <button type="submit" class="btn btn-sm btn-success m-0">Select</button>
                  <button type="button" disabled class="btn btn-sm btn-success m-0 btn-block">Still working on this report</button>
                    </form>
                </div>
              </div>
            </div>
          </div> 
            {{-- <div class="col-md-3 col-sm-6 col-12 mb-2">
                <form action="/reports/selectSy"  method="GET" class="m-0">
                    <div class="info-box  m-0">
                        <span class="info-box-icon"><i class="fa fa-file"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text"></span>
                            <span class="info-box-number">School Form 10</span>
                        </div>
                        <input type="hidden" value="School Form 10" name="selectedform"/>
                        <input type="hidden" value="{{$academicprogram}}" name="academicprogram"/>
                    <!-- /.info-box-content -->
                    </div>
                    <button type="submit" class="btn btn-block btn-success m-0">Select</button>
                </form>
                <!-- /.info-box -->
            </div> --}}
        @endif
    </div>
    <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<!-- fullCalendar 2.2.5 -->
@endsection
