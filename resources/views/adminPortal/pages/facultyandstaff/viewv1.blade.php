
@extends('adminPortal.layouts.app2')

@section('pagespecificscripts')
    <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <style>
        .btn-outline-dafault {
            color: white;
            border-color: white;
        }
    </style>
@endsection

@section('modalSection')

    <div class="modal fade" id="add-faculty" style="display: none; padding-right: 17px;" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header bg-info">
            <h4 class="modal-title">Faculty & Staff Form</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <form id="facultyform" action="/admininsertfaculty" method="GET">
              <div class="modal-body">
                  <input class="form-control" id="ui" name="ui" type="hidden" value="{{@old('ui')}}">
                  <div class="form-group">
                    <label for="exampleInputEmail1">First Name</label>
                    <input  value="{{@old('fn')}}" placeholder="Insert first name" class="form-control @error('fn') is-invalid @enderror" id="fn"  name="fn">
                    @if($errors->has('fn'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('fn') }}</strong>
                        </span>
                    @endif
                  </div>
                  
                  <div class="form-group">
                    <label for="exampleInputEmail1">Middle Name</label>
                    <input value="{{@old('mn')}}"  placeholder="Insert middle name" class="form-control @error('mn') is-invalid @enderror" id="mn"  name="mn">
                    @if($errors->has('mn'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('mn') }}</strong>
                        </span>
                    @endif
                  </div>
                 
                  <div class="form-group">
                    <label for="exampleInputEmail1">Last Name</label>
                    <input value="{{@old('ln')}}" placeholder="Insert last name" class="form-control  @error('ln') is-invalid @enderror" id="ln"  name="ln">
                    @if($errors->has('ln'))
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $errors->first('ln') }}</strong>
                      </span>
                    @endif
                  </div>
                  
                  <div class="form-group">
                    <label for="exampleInputEmail1">License Number</label>
                    <input value="{{@old('lcn')}}" placeholder="Insert license number" class="form-control @error('lcn') is-invalid @enderror" id="lcn"  name="lcn">
                    @if($errors->has('lcn'))
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $errors->first('lcn') }}</strong>
                      </span>
                    @endif
                  </div>
                  <div class="form-group">
                      <label for="exampleInputEmail1">User Type</label>
                      <select  class="form-control teacher  @error('ut') is-invalid @enderror" id="ut" name="ut">
                          <option value="" selected>Select User Type</option>
                          @foreach($usertype as $item)
                              <option value="{{$item->id}}"  data-ref="{{$item->refid}}" {{ old('ut') == $item->id ? 'selected' : '' }}>{{$item->utype}}</option>
                          @endforeach
                      </select>
                      @if($errors->has('ut'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('ut') }}</strong>
                        </span>
                      @endif
                  </div>
                  <div class="form-group" id="ap">
                    @if($errors->has('q'))
                      <label for="exampleInputEmail1">Academic Prog</label><br>
                        @if(old('ut') == 2 && old('ut') == 1)
                            @foreach(App\Models\Principal\SPP_AcademicProg::getAllAcadProg() as $key=>$item)
                                @if($item->id != 6)
                                    <div class="icheck-success d-inline">
                                    <input type="checkbox" id="q{{$key}}" name="q[]" value="{{$item->id}}">
                                    <label for="q{{$key}}"> {{$item->progname}}</label>
                                    </div><br>
                                @endif
                            @endforeach
                        @elseif(old('ut') == 16)
                            @foreach(DB::table('college_courses')->where('deleted',0)->get() as $key=>$item)
                                <div class="icheck-success d-inline">
                                <input type="checkbox" id="q{{$key}}" name="q[]" value="{{$item->id}}">
                                <label for="q{{$key}}"> {{$item->courseDesc}}</label>
                                </div><br>
                            @endforeach
                        @endif
                        <input type="hidden" class="form-control  @error('q') is-invalid @enderror">
                        @if($errors->has('q'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('q') }}</strong>
                            </span>
                        @endif
                    
                    @endif

                    @if(old('q')!=null )
                      <label for="exampleInputEmail1">Academic Prog</label><br>
                      @foreach(App\Models\Principal\SPP_AcademicProg::getAllAcadProg() as $key=>$item)
                        @if($item->id != 6)
                            <div class="icheck-success d-inline">
                            <input type="checkbox" id="q{{$key}}" name="q[]" value="{{$item->id}}" {{ (is_array(old('q')) and in_array($item->id, old('q'))) ? ' checked' : '' }}>
                            <label for="q{{$key}}"> {{$item->progname}}</label>
                            </div><br>
                        @endif
                      @endforeach
                    @endif
                  </div>
              </div>
              <div class="modal-footer justify-content-between mf" >
                  <button onClick="this.form.submit(); this.disabled=true; " type="submit" class="btn btn-primary us">Save</button>
                    @if($facultyInfo[0]->isactive == 1)
                        <button type="button" class="btn btn-danger" onclick="setActive(0)" id="setActiveButton">Set as Inactive</button>
                    @else
                        <button type="button" class="btn btn-primary" onclick="setActive(1)" id="setActiveButton">Set as Active</button>
                    @endif
              </div>
          </form>
        </div>
      </div>
  </div>
  <div class="modal fade " id="confirmation" style="display: none; padding-right: 17px;" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Confirmation</h4>
        </div>
        <form action="/adminRemoveFAS" method="GET">
            <div class="modal-body">
              <input value="{{@old('rid')}}" type="hidden" name="rid" id="rid">
              <input type="password" value="{{@old('ps')}}" placeholder="Enter Password" class="form-control   @if($errors->has('ps') || $errors->has('wrongpasss')  ) is-invalid @endif" id="ps"  name="ps">
              @if($errors->has('ps'))
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $errors->first('ps') }}</strong>
                  </span>
              @endif
              
              @if($errors->has('ps') ==null && $errors->has('wrongpasss') )
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('wrongpasss') }}</strong>
                </span>
              @endif

            </div>
            <div class="modal-footer justify-content-between">
                <button type="submit" class="btn btn-primary">Proceed</button>
                <button type="submit" class=" btn btn-default" data-dismiss="modal" aria-label="Close">Cancel</button>
            </div>
        </form>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modal-priv" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header bg-info">
            <h5 class="modal-title">Privilege Form</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
            </button>
        </div>
        <form id="roomform" method="GET" action="/adminaddprivelege">
            <div class="modal-body">
                <div class="form-group">
                    <input hidden value="{{Crypt::encrypt($facultyInfo[0]->userid)}}" name="sid">
                    <label for="exampleInputEmail1">User Type</label>
                    <select  class="form-control teacher  @error('privut') is-invalid @enderror"" id="privut" name="privut">
                        <option value="" selected>Select User Type</option>
                            @if ($errors->any() && old('privut')!=null)
                                @foreach($usertype as $item)
                                   
                                    @if($facultyInfo[0]->usertypeid == 1 && $item->id == 2)
                                        <option value="{{$item->id}}" data-ref="{{$item->refid}}" {{ old('privut') == $item->id ? 'selected' : '' }} disabled class="text-danger">{{$item->utype}}  ( NOT APPLICABLE )</option>
                                    @elseif($item->id == 2)
                                        <option value="{{Crypt::encrypt($item->id)}}"  data-ref="{{$item->refid}}" {{ old('privut') == $item->id ? 'selected' : '' }} disabled class="text-danger">{{$item->utype}}  ( NOT APPLICABLE )</option>
                                    @else
                                        <option value="{{$item->id}}"  data-ref="{{$item->refid}}" {{ old('privut') == $item->id ? 'selected' : '' }}>{{$item->utype}}</option>
                                    @endif
                                @endforeach
                            @else
                                @foreach($usertype as $item)
                                    @if($facultyInfo[0]->usertypeid == 1 && $item->id == 2)
                                        <option value="{{$item->id}}"  data-ref="{{$item->refid}}" disabled class="text-danger">{{$item->utype}}  ( NOT APPLICABLE )</option>
                                    @elseif($item->id == 2)
                                        <option value="{{Crypt::encrypt($item->id)}}"  data-ref="{{$item->refid}}" disabled class="text-danger">{{$item->utype}}  ( NOT APPLICABLE )</option>
                                    @else
                                        <option value="{{$item->id}}"  data-ref="{{$item->refid}}">{{$item->utype}}</option>
                                    @endif
                                @endforeach
                            @endif
                    </select>
                    @if($errors->has('privut'))
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $errors->first('privut') }}</strong>
                      </span>
                    @endif
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Privelege Type</label>
                    <select  class="form-control teacher  @error('priv') is-invalid @enderror"" id="priv" name="priv">
                        <option value="" selected>SELECT PRIVILEGE</option>
                        @if ($errors->any() && old('priv')!=null)
                            <option @if(Crypt::decrypt(old('priv')) == 1) selected @endif value="{{Crypt::encrypt(1)}}" >VIEW</option>
                            <option @if(Crypt::decrypt(old('priv')) == 2) selected @endif value="{{Crypt::encrypt(2)}}" >VIEW / EDIT</option>
                        @else
                            <option value="{{Crypt::encrypt(1)}}" >VIEW</option>
                            <option value="{{Crypt::encrypt(2)}}" >VIEW / EDIT</option>
                        @endif
                    </select>
                    @if($errors->has('priv'))
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $errors->first('priv') }}</strong>
                      </span>
                    @endif
                </div>
                <div class="form-group" id="privap">
                 
                </div>
                

                
            </div>
            <div class="modal-footer justify-content-between">
                <button onClick="this.form.submit(); this.disabled=true; " type="submit" class="btn btn-info savebutton"><i class="far fa-edit mr-1"></i>SAVE</button>
            </div>
        <form>
        </div>
    </div>
  </div>

@endsection


@section('content')
<section class="content-header">
    <div class="container-fluid">
    <div class="row">
        <div class="col-sm-6">
        <h4 class="text-success" style="text-shadow: 1px 2px 2px #6c757d"><u>{{$facultyInfo[0]->lastname}}, {{$facultyInfo[0]->firstname}}</u></h4>
        </div>
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/home">Home</a></li>
            <li class="breadcrumb-item"><a href="/manageaccounts">Faculty & Staff</a></li>
            <li class="breadcrumb-item active">Room</li>
        </ol>
        </div>
    </div>
    </div>
</section>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-9">
                @if(count(App\Models\Principal\SPP_AcademicProg::getAllAcadProg()) > 0 
                    && $facultyInfo[0]->usertypeid != 14
                    && $facultyInfo[0]->usertypeid != 16 )
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header bg-info">
                                    <h3 class="card-title">
                                        <i class="fas fa-edit"></i>
                                        ACADEMIC PROGRAM
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @foreach(App\Models\Principal\SPP_AcademicProg::getAllAcadProg() as $key=>$item)
                                            <div class="col-md-6">
                                                <div class="icheck-success d-inline">
                                                    @php
                                                        $checked = '';
                                                    @endphp

                                                    @if($facultyInfo[0]->usertypeid == 2 && count($principalAcadProg) > 0)
                                                        
                                                            @foreach ($principalAcadProg  as $fasitem)
                                                                @if($fasitem->id == $item->id)
                                                                    @php
                                                                        $checked = 'checked';
                                                                    @endphp
                                                                @endif  
                                                            @endforeach
                                                  
                                                    @else
                                                        @foreach ($acadProg  as $fasitem)
                                                            @if($fasitem->id == $item->id)
                                                                @php
                                                                    $checked = 'checked';
                                                                @endphp
                                                            @endif 
                                                        @endforeach
                                                    @endif
                                                    <input onclick="return false" {{$checked}} type="checkbox" id="t{{$key}}" name="t[]"> 
                                                    <label for="t{{$key}}"> {{$item->progname}}</label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif($facultyInfo[0]->usertypeid == 14)
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header bg-info">
                                    <h3 class="card-title">
                                        <i class="fas fa-edit"></i>
                                        COLLEGES
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @foreach(DB::table('college_colleges')->where('deleted',0)->get() as $key=>$item)

                                        @php
                                                $checked = '';
                                            @endphp

                                            @if($item->dean == $facultyInfo[0]->id)
                                                @php
                                                    $checked = 'checked';
                                                @endphp
                                            @endif

                                            <div class="col-md-6">
                                                <div class="icheck-success d-inline">
                                                    <input {{$checked}} 
                                                    {{-- onclick="updatecollege({{$item->id}})"  --}}
                                                    type="checkbox" id="t{{$key}}" name="t[]" class="dean_colleges" data-id="{{$item->id}}"> 
                                                    <label for="t{{$key}}"> {{$item->collegeDesc}}</label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if($facultyInfo[0]->usertypeid == 16)
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header bg-info">
                                    <h3 class="card-title">
                                        <i class="fas fa-edit"></i>
                                        COURSES
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @foreach(DB::table('college_courses')->where('deleted',0)->get() as $key=>$item)

                                            @php
                                                $checked = '';
                                            @endphp

                                            @if($item->courseChairman == $facultyInfo[0]->id)
                                                @php
                                                    $checked = 'checked';
                                                @endphp
                                            @endif

                                            <div class="col-md-6">
                                                <div class="icheck-success d-inline">
                                                    <input {{$checked}} type="checkbox" id="t{{$key}}" name="t[]" class="chairperson_course" data-id="{{$item->id}}"> 
                                                    <label for="t{{$key}}"> {{$item->courseDesc}}</label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                @endif
                
                @if($facultyInfo[0]->usertypeid == 2)
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header bg-info">
                                    <h3 class="card-title">
                                        <i class="fas fa-edit"></i>
                                        ACADEMIC PROGRAM [PRINCIPAL]
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @foreach(App\Models\Principal\SPP_AcademicProg::getAllAcadProg() as $key=>$item)
                                            <div class="col-md-6">
                                                <div class="icheck-success d-inline">
                                                    @php
                                                        $checked = '';
                                                    @endphp
                                                    @foreach ($principalAcadProg  as $fasitem)
                                                        @if($fasitem->id == $item->id)
                                                            @php
                                                                $checked = 'checked';
                                                            @endphp
                                                        @endif  
                                                    @endforeach
                                                    <input onclick="return false" {{$checked}} type="checkbox" id="p{{$key}}" name="p[]"> 
                                                    <label for="p{{$key}}"> {{$item->progname}}</label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <form action="/adminudpatepriv" method="GET">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                {{-- <form action="/adminudpatepriv" method="GET"> --}}
                                    <div class="card-header bg-primary">
                                        <h3 class="card-title">
                                            <i class="fas fa-edit"></i>
                                            PRIVILEGE
                                        </h3>
                                        <div class="card-tools">
                                            <span><button class="btn btn-sm btn-outline-dafault" data-toggle="modal"  data-target="#modal-priv" title="Privilege" data-widget="chat-pane-toggle"   ><i class="far fa-edit mr-1 "></i>Add Privilege</button></span>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            @foreach ($privelege  as $item)
                                                <div class="col-md-4">
                                                    <label for="exampleInputEmail1">{{$item->utype}}</label>
                                                    {{-- <input hidden name="privid[]" value="{{Crypt::encrypt($item->id)}}"> --}}
                                                    <select  class="form-control selectpriv"  data-id="{{$item->usertype}} {{$facultyInfo[0]->userid}}">
                                                        <option value="{{Crypt::encrypt(0)}}" @if($item->privelege == 0) selected @else  @endif>NONE</option>
                                                        <option @if($item->privelege == 1) selected @else  @endif value="{{Crypt::encrypt(1)}}" >VIEW</option>
                                                        <option @if($item->privelege == 2) selected @else  @endif value="{{Crypt::encrypt(2)}}" >VIEW / EDIT</option>
                                                    </select>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                {{-- </form> --}}
                                <div class="card-footer">

                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-3">

                @if($facultyInfo[0]->isactive == 1)
                    <div class="ribbon-wrapper ribbon-lg mr-2" hidden id="isRibbon">
                @else
                    <div class="ribbon-wrapper ribbon-lg mr-2" id="isRibbon">
                @endif
                    <div class="ribbon bg-danger">                           
                        INACTIVE            
                    </div>
                </div>
               
                <div class="card card-primary">
                    <div class="card-header bg-success">
                      <h3 class="card-title">About Me</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="row">
                        <div class="col-7 text-left">
                        <strong><i class="fas fa-book mr-1"></i>Name</strong>
                        <p class="text-muted">
                            {{$facultyInfo[0]->lastname}}, {{$facultyInfo[0]->firstname}}
                        </p>
                        </div>
                        <div class="col-5 text-right">
                          {{-- <img src="http://192.168.0.127:8000/dist/img/download.png" onerror="this.src='http://192.168.0.127:8000/dist/img/download.png'" alt="" class="img-circle img-fluid"> --}}
                      </div>
                        </div>
                        <hr>
                        <strong><i class="fas fa-map-marker-alt mr-1"></i>Position</strong>
                            @if(isset($facultyInfo[0]->utype))
                                <p class="text-muted">{{$facultyInfo[0]->utype}} </p>
                            @else
                                <p class="text-muted">No Assigned</p>
                            @endif
                        <hr>
                        <strong><i class="fas fa-map-marker-alt mr-1"></i>Teacher ID</strong>
                        <p class="text-muted">{{$facultyInfo[0]->tid}} </p>
                   
                        <span><button type="button" class="btn btn-sm btn-outline-primary ee btn-block" id="{{$facultyInfo[0]->id}}" ><i class="far fa-edit mr-1"></i>Edit Information</button></span>
                    </div>
                    <!-- /.card-body -->
                  </div>
            </div>

        </div>
    </div>
</section>


@endsection


@section('footerjavascript')
    <script>


        $(document).on('change','#privut',function(){
            if($(this).val()==2 || $(this).val()==1  || $(this).val()==3 ||  $(this).val()==8){

                $('#privap').empty();
                    var dataString = '<label for="exampleInputEmail1">Academic Prog</label><br>';
                    @foreach(App\Models\Principal\SPP_AcademicProg::getAllAcadProg() as $key=>$item)
                        @if($item->id != 6)

                            dataString+='<div class="icheck-success d-inline">'+
                                        '<input type="checkbox" id="qp'+'{{$key}}'+'" name="q[]" value="'+'{{$item->id}}'+'">'+
                                        '<label for="qp'+'{{$key}}'+'">'+'{{$item->progname}}'+
                                        '</label>'+
                                    '</div><br>'

                        @else

                            if($(this).val()==3){

                                dataString+='<div class="icheck-success d-inline">'+
                                        '<input type="checkbox" id="qp'+'{{$key}}'+'" name="q[]" value="'+'{{$item->id}}'+'">'+
                                        '<label for="qp'+'{{$key}}'+'">'+'{{$item->progname}}'+
                                        '</label>'+
                                    '</div><br>'

                            }

                        @endif
                    @endforeach
                $('#privap').append(dataString)

            }
            // else if($(this).val() == 16){

            //     @foreach(DB::table('college_courses')->where('deleted',0)->get() as $key=>$item)
            //         dataString+='<div class="icheck-success d-inline">'+
            //                         '<input type="checkbox" id="qp'+'{{$key}}'+'" name="q[]" value="'+'{{$item->id}}'+'">'+
            //                         '<label for="qp'+'{{$key}}'+'">'+'{{$item->courseDesc}}'+
            //                         '</label>'+
            //                     '</div><br>'
            //     @endforeach

            //     $('#privap').empty();

            // }
            else{

                $('#privap').empty();

            }
        })


        
        $(document).on('click','.dean_colleges',function(){

            var n = $(this).attr('data-id')

            if($(this).prop('checked') == true){
                status = 1;
            }
            else if($(this).prop('checked') == false){
                status = 2;
            }

            $.ajax({
                type:'GET',
                url:'/admin/update/faculty/college/'+'{{$facultyInfo[0]->id}}'+'/'+n+'?status='+status,
                success:function(){

                    Swal.fire({
                        type: 'success',
                        title: 'Updated Successfully!',
                        showConfirmButton: false,
                        timer: 1500,
                    })
                   
                }
            })

        })

        $(document).on('click','.chairperson_course',function(){

            var n = $(this).attr('data-id')

            if($(this).prop('checked') == true){
                status = 1;
            }
            else if($(this).prop('checked') == false){
                status = 2;
            }

            $.ajax({
                type:'GET',
                url:'/admin/update/faculty/course/'+'{{$facultyInfo[0]->id}}'+'/'+n+'?status='+status,
                success:function(){

                    Swal.fire({
                        type: 'success',
                        title: 'Updated Successfully!',
                        showConfirmButton: false,
                        timer: 1500,
                    })
                
                }
            })

        })

        // function updatecollege(n){

        //     console.log()

        //     $.ajax({
        //         type:'GET',
        //         url:'/admin/update/faculty/college/'+'{{$facultyInfo[0]->id}}'+'/'+n,
        //         success:function(){

                   
        //         }
        //     })


        // }


         function setActive(n){

            $.ajax({
                type:'GET',
                url:'/admin/set/facultyactive/'+'{{$facultyInfo[0]->id}}'+'/'+n,
                success:function(){

                    if(n == 0){
                        
                        $('#isRibbon').removeAttr('hidden')
                        $('#setActiveButton').attr('onclick','setActive(1)')
                        $('#setActiveButton').text('Set as Active')
                        $('#setActiveButton').addClass('btn-primary')
                        $('#setActiveButton').removeClass('btn-danger')
                     
                    }
                    else{
                   
                        $('#isRibbon').attr('hidden','hidden')
                        $('#setActiveButton').attr('onclick','setActive(0)')
                        $('#setActiveButton').text('Set as Inactive')

                        $('#setActiveButton').addClass('btn-danger')
                        $('#setActiveButton').removeClass('btn-primary')
                    }

                    $('#add-faculty').modal('hide')

                    Swal.fire({
                        type: 'success',
                        title: 'Updated Successfully!',
                        showConfirmButton: false,
                        timer: 1500,
                    })
                }
            })
        }
        $(document).ready(function(){

            var privid = [];

            $(document).on('change','.selectpriv',function(){

                $.ajax({
                    type:'GET',
                    url:'/admin/update/faspriv/'+$(this).find(":selected").val()+'/'+$(this).attr('data-id'),
                    success:function(){
                        Swal.fire({
                            type: 'success',
                            title: 'Updated Successfully!',
                            showConfirmButton: false,
                            timer: 1500,
                        })
                    }
                })
            })

            @if ($errors->any() && Session::get('invalidpriv'))

                $('#modal-priv').modal('show');
                @if(old('privut') == 1 || old('privut') == 3 || old('privut') == 8)
                    $('#privap').empty();
                        var dataString = '<label for="exampleInputEmail1">Academic Prog</label><br>';
                        @foreach(App\Models\Principal\SPP_AcademicProg::getAllAcadProg() as $key=>$item)
                            @if($item->id != 6)

                                dataString+='<div class="icheck-success d-inline">'+
                                                '<input type="checkbox" id="qp'+'{{$key}}'+'" name="q[]" value="'+'{{$item->id}}'+'">'+
                                                '<label for="qp'+'{{$key}}'+'">'+'{{$item->progname}}'+
                                                '</label>'+
                                            '</div><br>'
                                            
                            @else
                                if($(this).val()==3){

                                    dataString+='<div class="icheck-success d-inline">'+
                                                '<input type="checkbox" id="qp'+'{{$key}}'+'" name="q[]" value="'+'{{$item->id}}'+'">'+
                                                '<label for="qp'+'{{$key}}'+'">'+'{{$item->progname}}'+
                                                '</label>'+
                                            '</div><br>'

                                }
                            @endif
                        @endforeach
                      
                    $('#privap').append(dataString)
                @else

                    $('#privap').empty();                    

                @endif

            @endif

            @if ($errors->any() && (Session::has('update') || Session::has('deleteerror')))

                @if(Session::has('update')  || Session::has('deleteerror'))
                    $('#add-faculty').modal('show');
                    $("#facultyform").attr('action', '/updateAccountInfo');
                    $('.us').text('Update')
                    $('.us').addClass('btn-success')
                    $('.us').removeClass('btn-primary')
                    $('.rm').remove();

                @endif
                
                @if(Session::has('deleteerror'))
                    $('#confirmation').modal('show');
                    loadTeacherInfo({{old('rid')}})
                @endif
            @endif

            $(document).on('click','.ee',function(){
            
                loadTeacherInfo($(this).attr('id'))

            })


            $(document).on('click','.cc',function(){

                $('.selectpriv').each(function(key,value){
              
                    $(this).val(privid[key])

                })

                $('.card-tools').empty();
                $('.card-tools').append(' <span><button class="btn btn-sm btn-outline-primary" data-toggle="modal"  data-target="#modal-priv" title="Contacts" data-widget="chat-pane-toggle"   ><i class="far fa-edit mr-1" ></i>Add Privilege</button></span>')
            })


            function loadTeacherInfo($id){

                $('.rm').remove();
                    $('#rid').val($id)
                    $('#ui').val($id)
                    $('#add-faculty').modal('show');
                    $('#ap').empty();
                    $.ajax({
                    type:'GET',
                    url:'/adminggetfacultyinfo',
                    data:{
                        d:$id
                    },
                    success:function(data) {
            
                    $('#fn').val(data[0].data[0].firstname)
                    $('#ln').val(data[0].data[0].lastname)
                    $('#mn').val(data[0].data[0].middlename)
                    $('#lcn').val(data[0].data[0].licno)
                    $('#ut').val(data[0].data[0].usertypeid).change()

                    console.log(data[0].data[0].usertypeid)
            
                        if(data[0].data[0].usertypeid==2){
                            $.ajax({
                                type:'GET',
                                url:'/getprincipalacadprog',
                                data:{
                                    d:data[0].data[0].id},
                                    success:function(data) {
                                    $('input[type=checkbox]').each(function(){
                                    var checkboxValue = $(this).val();
                                    var matchedAcadProg = false;
                                    $.each(data,function(index,value){
                                        if(checkboxValue==value.id){
                                        matchedAcadProg = true;
                                        }
                                    })
                                    if(matchedAcadProg){
                                        $(this).prop('checked',true)
                                    }
                                    })
                                }
                            })
                        }
                        else if(data[0].data[0].usertypeid==1 || data[0].data[0].usertypeid==3 ||  data[0].data[0].usertypeid== 8 ){

                            
                            $.ajax({
                                type:'GET',
                                url:'/adminGetTeacherAcadProg',
                                data:{
                                d:data[0].data[0].id},
                                success:function(data) {
                                    $('input[type=checkbox]').each(function(){
                                    var checkboxValue = $(this).val();
                                    var matchedAcadProg = false;
                                    $.each(data,function(index,value){
                                        if(checkboxValue==value.id){
                                        matchedAcadProg = true;
                                        }
                                    })
                                    if(matchedAcadProg){
                                        $(this).prop('checked',true)
                                    }
                                    })
                                }
                            })
                        }

                        // else if(data[0].data[0].usertypeid==16){
                        //     $.ajax({
                        //         type:'GET',
                        //         url:'/chairpersoninfo?courses=courses&teacherid='+$id,
                        //         data:{
                        //         d:data[0].data[0].id},
                        //         success:function(data) {
                                 
                        //             $('input[data-input-type=course]').each(function(){
                                       
                        //                 var checkboxValue = $(this).val();
                        //                 var matchedAcadProg = false;
                        //                 $.each(data,function(index,value){
                        //                     if(checkboxValue==value.id){
                        //                     matchedAcadProg = true;
                        //                     }
                        //                 })
                        //                 if(matchedAcadProg){
                        //                     $(this).prop('checked',true)
                        //                 }
                        //             })
                        //         }
                        //     })
                        // }
                    }
                })
                $("#facultyform").attr('action', '/updateAccountInfo');
                $('.us').text('Update')
                $('.us').addClass('btn-success')
                $('.us').removeClass('btn-primary')
            }
            $(document).on('change','#ut',function(){


                if($(this).val()==2 || $(this).val()==1  || $(this).val()==3 || $( "#ut option:selected" ).attr('data-ref') == 20 || $(this).val() == 8){

                    $('#ap').empty();
                        var dataString = '<label for="exampleInputEmail1">Academic Prog</label><br>';
                        @foreach(App\Models\Principal\SPP_AcademicProg::getAllAcadProg() as $key=>$item)
                            @if($item->id != 6)
                                dataString+='<div class="icheck-success d-inline">'+
                                            '<input type="checkbox" id="q'+'{{$key}}'+'" name="q[]" value="'+'{{$item->id}}'+'">'+
                                            '<label for="q'+'{{$key}}'+'">'+'{{$item->progname}}'+
                                            '</label>'+
                                        '</div><br>'
                            @else

                                if($(this).val()==3){

                                    dataString+='<div class="icheck-success d-inline">'+
                                            '<input type="checkbox" id="q'+'{{$key}}'+'" name="q[]" value="'+'{{$item->id}}'+'">'+
                                            '<label for="q'+'{{$key}}'+'">'+'{{$item->progname}}'+
                                            '1</label>'+
                                        '</div><br>'

                                }
                            @endif
                        @endforeach



                    $('#ap').append(dataString)

                }
                // else if($(this).val()==16){

                //     var dataString = '<label for="exampleInputEmail1">Courses</label><br>';
                //     @foreach(DB::table('college_courses')->where('deleted',0)->get() as $key=>$item)
                //         dataString+='<div class="icheck-success d-inline">'+
                //                         '<input data-input-type="course" type="checkbox" id="qp'+'{{$key}}'+'" name="q[]" value="'+'{{$item->id}}'+'">'+
                //                         '<label for="qp'+'{{$key}}'+'">'+'{{$item->courseDesc}}'+
                //                         '</label>'+
                //                     '</div><br>'
                //     @endforeach
                //     $('#ap').append(dataString)

                // }
                else{
                    $('#ap').empty();
                }

            })
            $('#add-faculty').on('hidden.bs.modal', function () {
               $('.invalid-feedback').remove();
               $('.is-invalid').removeClass('is-invalid')
          })

          

        })
    </script>

@endsection


