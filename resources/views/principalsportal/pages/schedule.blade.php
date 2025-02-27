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
    <link rel="stylesheet" href="{{asset('css/pagination.css')}}">
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
  
    <style>
        * {
          box-sizing: border-box;
        }
        
        body {
          background-color: #f1f1f1;
        }
        
        #regForm {
          background-color: #ffffff;
          margin: 100px auto;
          font-family: Raleway;
          padding: 40px;
          width: 70%;
          min-width: 300px;
        }
        
        h1 {
          text-align: center;  
        }
        
        input {
          padding: 10px;
          width: 100%;
          font-size: 17px;
          font-family: Raleway;
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
          font-family: Raleway;
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

@if(App\Models\Principal\SPP_SchoolYear::getActiveSchoolYear()->id == Session::get('schoolYear')->id)

    @section('modalSection')
        <div class="modal fade" id="modal-primary" style="display: none;" aria-hidden="true">
            <div class="modal-dialog">
                <form action="/managestoreSections" method="GET">
                    <div class="modal-content">
                        <div class="modal-header bg-info">
                        <h4 class="modal-title">Section Form</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>   
                        </div>
                        <div class="modal-body">
                            <div class="message">
                            </div>
                            <div class="form-group">
                                <label>Section Name</label>
                                <input value="{{old('sn')}}" name="sn" class="form-control  @error('sn') is-invalid @enderror" id="sn" placeholder="Enter section name" oninput=" $(this).val($(this).val().replace(/[^a-z0-9 ]/gi, '')); this.value = this.value.toUpperCase();">
                                @if($errors->has('sn'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('sn') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>Grade Level</label>
                                <select data-placeholder="Select a Grade Level" class="form-control select2 @error('gl') is-invalid @enderror"  name="gl" id="gl" style="width: 100%;">
                                    <option selected value="">Select Grade Level</option>
                                    @foreach (\App\Models\Principal\LoadData::loadGradeLevelByDepartment() as $item)
                                        <option value="{{$item->id}}" {{ old('gl') == $item->id ? 'selected' : '' }}>{{$item->levelname}}</option>
                                    @endforeach
                                    
                                </select>
                                @if($errors->has('gl'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('gl') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>Teacher</label>
                                <select data-placeholder="Select a Teacher" class="form-control select2 @error('t') is-invalid @enderror"  name="t" id="t" style="width: 100%;">
                                    <option selected>Select Teacher</option>
                                    @if ($errors->any())
                                        @if(old('gl') >= 1 )
                                            @php
                                                $levelInfo = \App\Models\Principal\SPP_Gradelevel::getGradeLevel(null,null,old('gl'));
                                                $teachers = \App\Models\Principal\SPP_Teacher::filterTeacherFaculty(null,null,null,null,null,$levelInfo[0]->data[0]->acadprogid);
                                            @endphp
                                            @foreach ($teachers[0]->data  as $teacher)
                                                <option value="{{$teacher->id}}" {{ old('t') == $teacher->id ? 'selected' : '' }}>{{$teacher->firstname}} , {{$teacher->lastname}}</option>
                                            @endforeach
                                        @endif
                                    @endif
                                    
                                </select>
                                @if($errors->has('t'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('t') }}</strong>
                                    </span>
                                @endif
                                <p class="text-primary">*Teacher is optional</p>
                            </div>
                            <div class="form-group">
                                <label>Room</label>
                                <select data-placeholder="Select a Room" class="form-control select2 @error('r') is-invalid @enderror"  name="r" id="r" style="width: 100%;">
                                    @php
                                        $vacantRooms = App\Models\Principal\SPP_Rooms::getRooms(null,null,null,null);
                                    @endphp
                                    @if($vacantRooms[0]->count==0)
                                        <option value="" selected disabled>No more vacant room</option>
                                    @else
                                        <option value="" selected disabled>Select Room</option>
                                        @foreach ($vacantRooms[0]->data  as $room)
                                            <option value="{{$room->id}}" {{ old('r') == $room->id ? 'selected' : '' }}>{{$room->roomname}}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @if($errors->has('r'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('r') }}</strong>
                                    </span>
                                @endif
                                <p class="text-primary">*Room is required</p>
                            </div>
                            <div class="form-group">
                                <label >Session</label>
                                <select name="sectsession" id="" class="form-control" >
                                    <option value="">Whole Day</option>
                                    <option value="1">Morning Session</option>
                                    <option value="2">Afternoon Session</option>
                                    <option value="3">Night Session</option>
                                </select>
                            </div>
                            <hr>
                            <div class="form-group">
                                <div class="icheck-success d-inline">
                                    <input type="checkbox" id="nightClass"  name="nightClass" value="1">
                                    <label for="nightClass">Sunday Class
                                    </label>
                                </div>
                            </div>
                        </div>
                     
                        <div class="modal-footer justify-content-between">
                                <button type="submit" class="btn btn-primary ss" onClick="this.form.submit(); this.disabled=true; " style="padding: .5em;width: 160px;font-size: 14px;">CREATE SECTION</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endsection
@endif

@section('content')
    <section class="content-header  p-2">
        <div class="container-fluid">
        <div class="row">
            <div class="col-sm-8 pt-1">
                
                    <a class="text-danger "></a>
               
            </div>
            <div class="col-sm-4">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                <li class="breadcrumb-item active">Sections</li>
            </ol>
            </div>
        </div>
        </div>
    </section>
    <section class="content">
        @if($sectiondeatailcount > 0)
            <div class="card card-primary card-outline principalsection">
                <div class="card-header border-0 bg-info">
                   
                    <span class="col-md-6" style="font-size: 16px"><b><i class="nav-icon far fa-circle"></i> SECTIONS</b></span>
                   
                    <div class="input-group input-group-sm float-right w-25" >
                        <input type="text" id="search" name="table_search" class="form-control float-right" placeholder="Search">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                        </div>
                    </div>

                    @if(App\Models\Principal\SPP_SchoolYear::getActiveSchoolYear()->id == Session::get('schoolYear')->id)
                        <button type="button" class="btn btn-primary btn-sm createsect float-right mb-2 mr-2" data-toggle="modal" data-target="#modal-primary">
                            <b>Add Section</b>
                        </button>
                    @else
                        <h6 class="card-title pt-1 text-danger">The selected school year is not the current school year. Editing is not available.</h6>
                    @endif
                </div>
                <div class="card-body p-0" id="sectionholder">
                    @include('search.principal.section')
                </div>
                <div class="card-footer pt-1 pb-1 pl-2  bg-white d-flex justify-content-center">
                    <div id="data-container"></div>
                </div>
            </div>
        @else
            @if(Session::get('schoolYear')->id == DB::table('sy')->where('isactive','1')->first()->id)
                <div class="card card-primary card-outline principalsection">
                    <div class="card-header pt-1 pb-1">
                        Section Setup
                    </div>
                    <div class="card-body" id="sectionholder">
                        <form id="regForm" action="/dupsectdetwithdetail" method="GET">
                            <div class="tab"> 
                                <label>Do you want to copy existing sections to {{Session::get('schoolYear')->sydesc}} school year?</label>
                                <br>
                                <br>
                                <a class="btn btn-danger" href="/dupsectdetwithoutdetail">No</a>
                                <a class="btn btn-success" href="#" id="nextBtn" onclick="nextPrev(1)">Yes</a>
                            </div>
                            <div class="tab">
                                <div class="form-group">
                                    <label>Copy information from school year:</label>
                                    <select class="p-0 form-control" name="sy"> 
                                        @foreach(App\Models\Principal\SPP_SchoolYear::loadAllSchoolYear() as $item)
                                            @if($item->id  != Session::get('schoolYear')->id )
                                                <option  value="{{Crypt::encrypt($item->id)}}"><u>{{$item->sydesc}}</u></option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <button type="button" class="btn btn-danger"  onclick="nextPrev(-1)" >Previous</button>
                                <button type="button" class="btn btn-success"  onclick="nextPrev(1)">Next</button>
                            </div>

                            <div class="tab">
                                <label>Select informations to be copied</label>
                                <br>
                                    <div class="icheck-success d-inline col-md-3">
                                        <input type="checkbox" id="sectinfo" name="sectinfo">
                                        <label for="sectinfo">Section Information ex. <i>Advicer, Room</i>
                                        </label>
                                    </div>
                                <br>
                                    <div class="icheck-success d-inline col-md-3">
                                        <input type="checkbox" id="sectclasssched" name="sectclasssched">
                                        <label for="sectclasssched">Section class schedule ex. <i>Time, Subject</i>
                                        </label>
                                    </div>
                                <br>
                                <br>
                                <button type="button" class="btn btn-danger"  onclick="nextPrev(-1)" >Previous</button>
                                <button type="submit" class="btn btn-success"  >Done</button>
                            </div>
                            <!-- Circles which indicates the steps of the form: -->
                            <div style="text-align:center;margin-top:40px;">
                                <span class="step"></span>
                                <span class="step"></span>
                                <span class="step"></span>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer pt-1 pb-1 pl-2  bg-white d-flex justify-content-center">
                    </div>
                </div>
            @else

            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline principalstudents ">
                        <div class="card-header  border-0 bg-info">
                            <h3 class="card-title acadid pr-3">
                               SECTIONS
                                
                            </h3>
                        </div>
                        <div class="card-body p-0" id="studentholder">
                            <div class="container h-100" >
                                <div class="row align-items-center h-100" style="min-height:430px; !important">
                                    <div class="mx-auto">
                                        SCHOOL YEAR IS NOT YET ACTIVATED.
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
            </div>

            @endif

        @endif
    </section>
@endsection

        @section('footerjavascript')

            <script src="{{asset('js/pagination.js')}}"></script>
            <script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>

            <script>
                var currentTab = 0; // Current tab is set to be the first tab (0)
                showTab(currentTab); // Display the current tab
                
                function showTab(n) {
                  // This function will display the specified tab of the form...
                  var x = document.getElementsByClassName("tab");
                  x[n].style.display = "block";
                  //... and fix the Previous/Next buttons:
                  if (n == 0) {
                    document.getElementById("prevBtn").style.display = "none";
                  } else {
                    document.getElementById("prevBtn").style.display = "inline";
                  }
                  if (n == (x.length - 1)) {
                    document.getElementById("nextBtn").innerHTML = "Submit";
                  } else {
                    document.getElementById("nextBtn").innerHTML = "Next";
                  }
                  //... and run a function that will display the correct step indicator:
                  fixStepIndicator(n)
                }
                
                function nextPrev(n) {
                  // This function will figure out which tab to display
                  var x = document.getElementsByClassName("tab");
                  // Exit the function if any field in the current tab is invalid:
                  if (n == 1 && !validateForm()) return false;
                  // Hide the current tab:
                  x[currentTab].style.display = "none";
                  // Increase or decrease the current tab by 1:
                  currentTab = currentTab + n;
                  // if you have reached the end of the form...
                  if (currentTab >= x.length) {
                    // ... the form gets submitted:
                    document.getElementById("regForm").submit();
                    return false;
                  }
                  // Otherwise, display the correct tab:
                  showTab(currentTab);
                }
                
                function validateForm() {
                  // This function deals with validation of the form fields
                  var x, y, i, valid = true;
                  x = document.getElementsByClassName("tab");
                  y = x[currentTab].getElementsByTagName("input");
                  // A loop that checks every input field in the current tab:
                  for (i = 0; i < y.length; i++) {
                    // If a field is empty...
                    if (y[i].value == "") {
                      // add an "invalid" class to the field:
                      y[i].className += " invalid";
                      // and set the current valid status to false
                      valid = false;
                    }
                  }
                  // If the valid status is true, mark the step as finished and valid:
                  if (valid) {
                    document.getElementsByClassName("step")[currentTab].className += " finish";
                  }
                  return valid; // return the valid status
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




            @if($sectiondeatailcount > 0 || $sectiondeatailcount == null)
                <script>
                    
                    if($(window).width() <= 1024){
                    
                        $('.card-title').addClass('w-50');
                    }
                    else{
                        $('body').removeClass('sidebar-collapse');
                    }

                   
                    $(document).ready(function(){

                        @if ($errors->any())
                            $('#modal-primary').modal('show');
                        @endif

                        $(document).on('click','.createsect',function(){
                            

                            var vacantRoom = '{{ App\Models\Principal\SPP_Rooms::getRooms(null,null,null,null)[0]->count }}';

                            

                            if(vacantRoom == 0){

                                var errorstring = '';
                                var footmessage = 'Please contact your administrator to add additional';

                                if(vacantRoom == 0){

                                    errorstring += '<li>No available or vacant room.</li>';
                                    footmessage += ' room';

                                }

                                $('.modal-content').empty();
                                $('.modal-content').append(
                                    '<div class="modal-header"><h3 class="mb-0" style="font-size: 25px; font-weight: 300;"><i class="fas fa-exclamation-triangle text-warning"></i> No more available room.</h3> </div><div class="modal-body">'+
                                        '<div class="error-content">'+
                                            ''+
                                            '<p>'+footmessage+'.</p>'+
                                        '</div>'+
                                    '</div>'+
                                    '<div class="card-footer"> <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button></div>'
                                );
                            }

                        })


                    });
                </script>

                <script>
                    $(document).ready(function(){

                        $(function () {
                            $('.select2').select2()
                            // $('.select2bs4').select2({
                            //     theme: 'bootstrap4'
                            // })
                        })
                        $(document).ready(function(){
                            console.log('sdfsdf');
                        })

                        $(document).on('change','#gl',function(){
                            console.log('sdfsdf');
                            $.ajax({
                                type:'GET',
                                url:'/principalGetTeacher',
                                data:{
                                    data:$(this).val(),
                                },
                                success:function(data) {
                                    $('#t').empty();
                                    console.log(data);
                                    $('#t').append('<option value="">Select Teacher</option>')
                                    $.each(data[0].data,function(key,value){
                                        $('#t').append('<option value='+value.id+'>'+value.lastname+', '+value.firstname+'</option>')
                                    })

                                }
                            })
                        })
                    });
                </script>

                <script>
                    $(document).ready(function(){
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
                                url:'/searchsectionajax',
                                data:{
                                data:$("#search").val(),
                                pagenum:pagination.pageNumber},
                                success:function(data) {
                                $('#sectionholder').empty();
                                $('#sectionholder').append(data);
                                }
                            })
                            }
                            pagetype=true;
                        },
                            hideWhenLessThanOnePage: true,
                            pageSize: 6,
                        })
                    }
                    $("#search" ).keyup(function() {
                        $.ajax({
                        type:'GET',
                        url:'/searchsectionajax',
                        data:{data:$(this).val(),pagenum:'1'},
                        success:function(data) {
                            $('#sectionholder').empty();
                            $('#sectionholder').append(data);
                            pagination($('#searchCount').val())
                        }
                        })
                    });
                    })
                </script>
            @endif
        @endsection

