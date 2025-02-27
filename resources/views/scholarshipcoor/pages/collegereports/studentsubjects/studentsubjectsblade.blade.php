
@extends('scholarshipcoor.layouts.app2')

@section('pagespecificscripts')

      <link rel="stylesheet" href="{{asset('css/pagination.css')}}">

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
                              <li class="breadcrumb-item active">Enrollment Report</li>
                        </ol>
                        </div>
                  </div>
            </div>
      </section>
      <section class="content-header">
            <div class="container-fluid">
                  <div class="row">
                        <div class="col-sm-12">
                              <div class="card">
                                    <div class="card-header">
                                          <div class="d-flex justify-content-between">
                                                <h3 class="card-title">Enrollment Report</h3>
                                          </div>
                                    </div>
                                    @php
                                          $signatories = DB::table('signatory')->where('form','college_enrollment_report')->get();
                                    @endphp
                                    <div class="card-body p-2" >
                                          <h4 class="col-md-12 row">SIGNATORY</h4>
                                          @if(count($signatories) >= 3)

                                                @foreach ($signatories as $item)
                                                      <div class="row">
                                                            <div class="col-md-4">
                                                                  <div class="form-group">
                                                                        <label for="">Name</label>
                                                                        <div class="input-group">
                                                                              <input id="name" data-id="{{$item->id}}" type="text" class="form-control" value="{{$item->name}}">
                                                                              <span class="input-group-append">
                                                                              <button type="button" class="btn btn-primary btn-flat edit_signatory" data-id="{{$item->id}}" data-field="name"><i class="fas fa-edit" ></i></button>
                                                                              </span>
                                                                        </div>
                                                                  </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                  <div class="form-group">
                                                                        <label for="">Title</label>
                                                                        <div class="input-group">
                                                                              <input id="title" data-id="{{$item->id}}" type="text" class="form-control" value="{{$item->title}}">
                                                                              <span class="input-group-append">
                                                                              <button type="button" class="btn btn-primary btn-flat edit_signatory" data-id="{{$item->id}}" data-field="title"><i class="fas fa-edit"></i></button>
                                                                              </span>
                                                                        </div>
                                                                  </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                  <div class="form-group">
                                                                        <label for="">Description</label>
                                                                        <div class="input-group">
                                                                              <input id="description" data-id="{{$item->id}}" type="text" class="form-control" value="{{$item->description}}">
                                                                              <span class="input-group-append">
                                                                              <button type="button" class="btn btn-primary btn-flat edit_signatory" data-id="{{$item->id}}" data-field="description"><i class="fas fa-edit"></i></button>
                                                                              </span>
                                                                        </div>
                                                                  </div>
                                                            </div>
                                                      </div>
                                                @endforeach
                                                {{-- <div class="row">
                                                      <div class="col-md-4">
                                                            <div class="form-group">
                                                                  <label for="">Name</label>
                                                                  <div class="input-group">
                                                                        <input type="text" class="form-control" value="{{$signatories[0]->name}}">
                                                                        <span class="input-group-append">
                                                                        <button type="button" class="btn btn-primary btn-flat"><i class="fas fa-edit"></i></button>
                                                                        </span>
                                                                  </div>
                                                            </div>
                                                      </div>
                                                      <div class="col-md-4">
                                                            <div class="form-group">
                                                                  <label for="">Title</label>
                                                                  <div class="input-group">
                                                                        <input type="text" class="form-control" value="{{$signatories[0]->title}}">
                                                                        <span class="input-group-append">
                                                                        <button type="button" class="btn btn-primary btn-flat"><i class="fas fa-edit"></i></button>
                                                                        </span>
                                                                  </div>
                                                            </div>
                                                      </div>
                                                      <div class="col-md-4">
                                                            <div class="form-group">
                                                                  <label for="">Description</label>
                                                                  <div class="input-group">
                                                                        <input type="text" class="form-control" value="{{$signatories[0]->description}}">
                                                                        <span class="input-group-append">
                                                                        <button type="button" class="btn btn-primary btn-flat"><i class="fas fa-edit"></i></button>
                                                                        </span>
                                                                  </div>
                                                            </div>
                                                      </div>
                                                </div>
                                                <div class="row">
                                                      <div class="col-md-4">
                                                            <div class="form-group">
                                                                  <label for="">Name</label>
                                                                  <div class="input-group">
                                                                        <input type="text" class="form-control" value="{{$signatories[0]->name}}">
                                                                        <span class="input-group-append">
                                                                        <button type="button" class="btn btn-primary btn-flat"><i class="fas fa-edit"></i></button>
                                                                        </span>
                                                                  </div>
                                                            </div>
                                                      </div>
                                                      <div class="col-md-4">
                                                            <div class="form-group">
                                                                  <label for="">Title</label>
                                                                  <div class="input-group">
                                                                        <input type="text" class="form-control" value="{{$signatories[0]->title}}">
                                                                        <span class="input-group-append">
                                                                        <button type="button" class="btn btn-primary btn-flat"><i class="fas fa-edit"></i></button>
                                                                        </span>
                                                                  </div>
                                                            </div>
                                                      </div>
                                                      <div class="col-md-4">
                                                            <div class="form-group">
                                                                  <label for="">Description</label>
                                                                  <div class="input-group">
                                                                        <input type="text" class="form-control" value="{{$signatories[0]->description}}">
                                                                        <span class="input-group-append">
                                                                        <button type="button" class="btn btn-primary btn-flat"><i class="fas fa-edit"></i></button>
                                                                        </span>
                                                                  </div>
                                                            </div>
                                                      </div>
                                                </div>
                                         
                                                <div class="row">
                                                      <div class="col-md-4">
                                                            <div class="form-group">
                                                                  <label for="">Name</label>
                                                                  <div class="input-group">
                                                                        <input type="text" class="form-control" value="{{$signatories[0]->name}}">
                                                                        <span class="input-group-append">
                                                                        <button type="button" class="btn btn-primary btn-flat"><i class="fas fa-edit"></i></button>
                                                                        </span>
                                                                  </div>
                                                            </div>
                                                      </div>
                                                      <div class="col-md-4">
                                                            <div class="form-group">
                                                                  <label for="">Title</label>
                                                                  <div class="input-group">
                                                                        <input type="text" class="form-control" value="{{$signatories[0]->title}}">
                                                                        <span class="input-group-append">
                                                                        <button type="button" class="btn btn-primary btn-flat"><i class="fas fa-edit"></i></button>
                                                                        </span>
                                                                  </div>
                                                            </div>
                                                      </div>
                                                      <div class="col-md-4">
                                                            <div class="form-group">
                                                                  <label for="">Description</label>
                                                                  <div class="input-group">
                                                                        <input type="text" class="form-control" value="{{$signatories[0]->description}}">
                                                                        <span class="input-group-append">
                                                                        <button type="button" class="btn btn-primary btn-flat"><i class="fas fa-edit"></i></button>
                                                                        </span>
                                                                  </div>
                                                            </div>
                                                      </div>
                                                </div> --}}
                                          @else
                                                <div class="row">
                                                      <div class="col-md-12">
                                                            <p>No signatory assigned</p>
                                                      </div>
                                                </div>
                                          @endif
                                          <hr>
                                          <h4 class="col-md-12 row">FILTER</h4>
                                          <div class="row">
                                                <div class="form-group col-md-3">
                                                      <label for="">School Year</label>
                                                      <select name="sy" id="sy" class="form-control">
                                                            <option value="">All</option>
                                                            @foreach (DB::table('sy')->get() as $item)
                                                                  @if($item->isactive == 1)
                                                                        <option value="{{$item->id}}" selected>{{$item->sydesc}}</option>
                                                                  @else
                                                                        <option value="{{$item->id}}">{{$item->sydesc}}</option>
                                                                  @endif
                                                            @endforeach
                                                      </select>
                                                </div>
                                                <div class="form-group col-md-3">
                                                      <label for="">Semester</label>
                                                      <select name="sem" id="sem" class="form-control">
                                                            <option value="">All</option>
                                                            @foreach (DB::table('semester')->get() as $item)
                                                                  @if($item->isactive == 1)
                                                                        <option value="{{$item->id}}" selected>{{$item->semester}}</option>
                                                                  @else
                                                                        <option value="{{$item->id}}">{{$item->semester}}</option>
                                                                  @endif
                                                            @endforeach
                                                      </select>
                                                </div>
                                          </div>
                                          <div class="row">
                                                <div class="form-group col-md-3">
                                                      
                                                      <label for="">Grade Level</label>
                                                      <select name="gradelevel" id="gradelevel" class="form-control">
                                                            <option value="">All</option>
                                                            @foreach (DB::table('gradelevel')->where('acadprogid',6)->where('deleted',0)->get() as $item)
                                                                  <option value="{{$item->id}}">{{$item->levelname}}</option>
                                                            @endforeach
                                                      </select>
                                                </div>
                                                <div class="form-group col-md-3">
                                                      <label for="">Course</label>
                                                      <select name="course" id="course" class="form-control">
                                                            <option value="">All</option>
                                                            @foreach (DB::table('college_courses')->where('deleted',0)->where('deleted',0)->get() as $item)
                                                                  <option value="{{$item->id}}">{{$item->courseabrv}}</option>
                                                            @endforeach
                                                      </select>
                                                </div>
                                                <div class="form-group col-md-3">
                                                      <label for="">Section</label>
                                                      <select name="section" id="section" class="form-control">
                                                            <option value="">All</option>
                                                            @foreach (DB::table('college_sections')->where('deleted',0)->where('deleted',0)->get() as $item)
                                                                  <option value="{{$item->id}}" data-course="{{$item->courseID}}">{{$item->sectionDesc}}</option>
                                                            @endforeach
                                                      </select>
                                                </div>
                                                <div class="form-group col-md-3">
                                                      <label for="">Gender</label>
                                                      <select name="gender" id="gender" class="form-control">
                                                            <option value="">All</option>
                                                            <option value="MALE">Male</option>
                                                            <option value="FEMALE">Female</option>
                                                      </select>
                                                </div>
                                          </div>
                                          <div class="row">
                                                <div class="col-md-4">
                                                      <button class="btn btn-primary" id="generate"><i class="fas fa-sync-alt"></i> GENERATE</button>
                                                      <button class="btn btn-primary" id="print"><i class="fas fa-print"></i> PDF</button>
                                                      <button class="btn btn-primary" id="excel"><i class="fas fa-print"></i> EXCEL</button>
                                                </div>
                                          </div>
                                          <hr>
                                          <div class="row table-responsive p-2 ml-0"   id="enrollment_report_holder">
                                              
                                          </div>
                                    </div>
                                    <div class="card-footer">
                                          <div class="" id="data-container" >
      
                                          </div>
                                    </div> 
                              </div>
                        </div>
                  </div>
            </div>
      </section>

@endsection


@section('footerjavascript')

      <script src="{{asset('js/pagination.js')}}"></script> 

      <script>
            $(document).ready(function(){

                  
                  var sectionid = null;
                  var courseid = null;
                  var gradelevelid = null;
                  var gender = null;
                  var sy = $('#sy').val();
                  var sem = $('#sem').val();

                  processpaginate(null,10,null,true)

                  function processpaginate(skip = null,take = null ,search = null, firstload = true){

                        $.ajax({
                              type:'GET',
                              url:'/collge/report/enrollment',
                              data:{
                                    take:take,
                                    skip:skip,
                                    table:'table',
                                    search:search,
                                    sectionid:sectionid,
                                    courseid:courseid,
                                    gradelevelid:gradelevelid,
                                    sy:sy,
                                    sem:sem,
                                    gender:gender
                              },
                              success:function(data) {
                                    $('#enrollment_report_holder').empty();
                                    $('#enrollment_report_holder').append(data);
                                    pagination($('#searchCount').val(),false)
                              
                              }
                        })

                  }

                  var pageNum = 1;

                  function pagination(itemCount,pagetype){

                        var result = [];

                        for (var i = 0; i < itemCount; i++) {
                              result.push(i);
                        }

                        $('#data-container').pagination({
                              dataSource: result,
                              hideWhenLessThanOnePage: true,
                              pageNumber: pageNum,
                              pageRange: 1,
                              callback: function(data, pagination) {

                                          if(pagetype){

                                                processpaginate(pagination.pageNumber,10,$('#search').val(),false)

                                          }

                                          pageNum = pagination.pageNumber
                                          pagetype=true
                                    }
                              })
                  }

                  $(document).on('keyup','#search',function() {

                        pageNum = 1
                        processpaginate(0,10,$('#search').val(),null)

                  });

                  $(document).on('click','#generate',function() {

                        sectionid = $('#section').val()
                        courseid = $('#course').val()
                        gradelevelid = $('#gradelevel').val()
                        sy = $('#sy').val()
                        sem = $('#sem').val()
                        gender = $('#gender').val()

                        pageNum = 1
                        processpaginate(null,10,$('#search').val(),null)

                  });

                  $(document).on('change','#course',function() {

                        selectCourse = $(this).val()

                        $('#section').val('');

                        $('#section option').each(function(a,b){

                              $(this).removeAttr('hidden')

                        })

                        if($(this).val() != ''){

                              $('#section option').each(function(a,b){
                              
                                    if(selectCourse != $(this).attr('data-course') && $(this).attr('value') != ''){

                                          $(this).attr('hidden','hidden')

                                    }
                              })

                        }
                    

                  });


                  $(document).on('click','.edit_signatory',function(){

                        var signatoryid = $(this).attr('data-id')
                        var value = $('input[data-id="'+$(this).attr('data-id')+'"][id="'+$(this).attr('data-field')+'"]').val()
                        var field = $(this).attr('data-field')

                        $.ajax({
                              type:'GET',
                              url:'/signatory',
                              data:{
                                    update:'update',
                                    signatoryid:signatoryid,
                                    value:value,
                                    field:field
                              
                              },
                              success:function(data) {
                                   
                                   if(data == 1){

                                          Swal.fire({
                                                type: 'success',
                                                title: 'Updated Successfully!',
                                                showConfirmButton: false,
                                                timer: 1500
                                          })

                                   }
                                   else{

                                          Swal.fire({
                                                type: 'error',
                                                title: 'Somethin went wrong!',
                                                showConfirmButton: false,
                                                timer: 1500
                                          })
                                   }
                              
                              }
                        })



                  })

                  $(document).on('click','#print',function() {

                        sectionid = $('#section').val()
                        courseid = $('#course').val()
                        gradelevelid = $('#gradelevel').val()
                        gender = $('#gender').val()
                        cantPrint = true;

                        sy = $('#sy').val()
                        sem = $('#sem').val()

                        // if(sectionid == ''){
                        //       cantPrint = false
                        // }
                        if(courseid == ''){
                              cantPrint = false
                        }
                        if(gradelevelid == ''){
                              cantPrint = false
                        }

                        pageNum = 1
                        processpaginate(null,10,$('#search').val(),null)

                        if(!cantPrint){

                              Swal.fire({
                                          type: 'error',
                                          title: 'Something went wrong!',
                                          html:'<h5 class="text-danger"><i>Please select grade level, course & section</i></h5>',
                                    })

                        }
                        else{

                              window.open('/collge/report/enrollment?sectionid='+sectionid+'&gradelevelid='+gradelevelid+'&courseid='+courseid+'&gender='+gender+'&sy='+sy+'&sem='+sem+'&pdf=pdf&table=table', '_blank');


                        }

                  })

                  $(document).on('click','#excel',function() {

                        sectionid = $('#section').val()
                        courseid = $('#course').val()
                        gradelevelid = $('#gradelevel').val()
                        gender = $('#gender').val()
                        cantPrint = true;

                        sy = $('#sy').val()
                        sem = $('#sem').val()

                        // if(sectionid == ''){
                        //       cantPrint = false
                        // }
                        if(courseid == ''){
                              cantPrint = false
                        }
                        if(gradelevelid == ''){
                              cantPrint = false
                        }

                        pageNum = 1
                        processpaginate(null,10,$('#search').val(),null)

                        if(!cantPrint){

                              Swal.fire({
                                          type: 'error',
                                          title: 'Something went wrong!',
                                          html:'<h5 class="text-danger"><i>Please select grade level, course & section</i></h5>',
                                    })

                        }
                        else{

                              window.open('/collge/report/enrollment?sectionid='+sectionid+'&gradelevelid='+gradelevelid+'&courseid='+courseid+'&gender='+gender+'&sy='+sy+'&sem='+sem+'&excel=excel&table=table', '_blank');


                        }

                  })
                       



            }) 
      </script>
      

@endsection

