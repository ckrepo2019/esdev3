
@extends('adminPortal.layouts.app2')

@section('pagespecificscripts')
   
    <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/pagination.css')}}">

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
                      <select  class="form-control teacher  @error('ut') is-invalid @enderror"" id="ut" name="ut">
                          <option value="" selected>Select User Type</option>
                          @foreach($usertype as $item)
                              <option value="{{$item->id}}" data-ref="{{$item->refid}}" {{ old('ut') == $item->id ? 'selected' : '' }}>{{$item->utype}}</option>
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
                      @foreach(App\Models\Principal\SPP_AcademicProg::getAllAcadProg() as $key=>$item)
                        @if($item->id != 6)
                          <div class="icheck-success d-inline">
                            <input type="checkbox" id="q{{$key}}" name="q[]" value="{{$item->id}}">
                            <label for="q{{$key}}"> {{$item->progname}}</label>
                          </div><br>
                        @else
                          @if(old('ut') == 3)
                            <div class="icheck-success d-inline">
                              <input type="checkbox" id="q{{$key}}" name="q[]" value="{{$item->id}}">
                              <label for="q{{$key}}"> {{$item->progname}}</label>
                            </div><br>
                          @endif
                        @endif
                      @endforeach
                      <input type="hidden" class="form-control  @error('q') is-invalid @enderror">
                      @if($errors->has('q'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('q') }}</strong>
                        </span>
                      @endif
                    @endif

                    @if(old('q')!=null)
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
          <li class="breadcrumb-item active">Faculty & Staff</li>
      </ol>
      </div>
  </div>
  </div>
</section>
<section class="content p-0">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card adminfacultystaff">
            <div class="card-header bg-info">
              <span class="" style="font-size: 16px"><b><i class="nav-icon fa fa-users"></i> FACULTY AND STAFF</b></span>
              <div class="input-group input-group-sm float-right w-25 search"> 
                <input type="text" id="search" class="form-control float-right" placeholder="Search" >
                <div class="input-group-append">
                  <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                </div>
              </div>
              <button class="btn btn-primary btn-sm float-right mr-2 mb-2" data-toggle="modal"  data-target="#add-faculty" data-widget="chat-pane-toggle" ><b>ADD FACULTY AND STAFF</b></button>
            </div>
            <div class="card-body table-responsive" id="facultystaff">
              @include('search.admin.facultystaff')
            </div>
            <div class="card-footer">
              <div id="data-container">
              </div>
            </div>
          </div>
        </div>
       
      </div>
    </div><!-- /.container-fluid -->

  </section>
@endsection


@section('footerjavascript')

  <script src="{{asset('js/pagination.js')}}"></script>

  <script>
    $(document).ready(function(){
      $('#add-faculty').on('hidden.bs.modal', function () {
        $(this).find("input,select").val('').end()
        $('.invalid-feedback').remove();
        $('#ap').empty();
        $('.is-invalid').removeClass('is-invalid')
        $('.rm').remove();
      });
    });
  </script>

  <script>
    
  </script>

  <script>
    $(document).ready(function(){

      if($(window).width()<500){
            $('.search').addClass('w-50')
            $('.search').removeClass('w-25')
        }

      pagination('{{$data[0]->count}}',false);

      function pagination(itemCount,pagetype){
        var result = [];
        for (var i = 0; i < itemCount; i++) {
          result.push(i);
        }
        $('#data-container').pagination({
          dataSource: result,
          pageSize: 6,
          hideWhenLessThanOnePage: true,
          callback: function(data, pagination) {
            if(pagetype){
              $.ajax({
                type:'GET',
                url:'/searchfacultystaff',
                data:{
                  data:$("#search").val(),
                  pagenum:pagination.pageNumber},
                success:function(data) {
                  $('#facultystaff').empty();
                  $('#facultystaff').append(data);
                }
              })
            }
            pagetype=true
          }
        })
      }

      $("#search" ).keyup(function() {
        $.ajax({
          type:'GET',
          url:'/searchfacultystaff',
          data:{data:$(this).val(),pagenum:'1'},
          success:function(data) {
            $('#facultystaff').empty();
            $('#facultystaff').append(data);
            pagination($('#searchCount').val())
          }
        })
      });
    })
  </script>

  <script>

  $(document).ready(function(){
      @if ($errors->any())
          @if(Session::has('update') || Session::has('deleteerror'))
            $('#add-faculty').modal();
            $("#facultyform").attr('action', '/updateAccountInfo');
            $('.us').text('Update')
            $('.us').addClass('btn-success')
            $('.us').removeClass('btn-primary')
          @endif
          @if(Session::has('deleteerror'))
            $('#confirmation').modal();
            loadTeacherInfo({{old('rid')}})
          @endif
          $('#add-faculty').modal();
      @endif
     
  });



    // function loadTeacherInfo($id){

    //     $('.mf').append('<a href="#" type="button" class="btn btn-danger text-white rm">Remove</a>')
    //     $('#rid').val($id)
    //     $('#ui').val($id)
    //     $('#add-faculty').modal();
    //     $('#ap').empty();
    //     $.ajax({
    //       type:'GET',
    //       url:'/adminggetfacultyinfo',
    //       data:{
    //         d:$id
    //       },
    //       success:function(data) {

    //       $('#fn').val(data[0].data[0].firstname)
    //       $('#ln').val(data[0].data[0].lastname)
    //       $('#mn').val(data[0].data[0].middlename)
    //       $('#lcn').val(data[0].data[0].licno)
    //       $('#ut').val(data[0].data[0].usertypeid).change()

    //       if(data[0].data[0].usertypeid==2){
    //         $.ajax({
    //           type:'GET',
    //           url:'/getprincipalacadprog',
    //           data:{
    //             d:data[0].data[0].id},
    //             success:function(data) {
    //             $('input[type=checkbox]').each(function(){
    //               var checkboxValue = $(this).val();
    //               var matchedAcadProg = false;
    //               $.each(data,function(index,value){
    //                 if(checkboxValue==value.id){
    //                   matchedAcadProg = true;
    //                 }
    //               })
    //               if(matchedAcadProg){
    //                 $(this).prop('checked',true)
    //               }
    //             })
    //           }
    //         })
    //       }
    //       else if(data[0].data[0].usertypeid==1){
    //         $.ajax({
    //           type:'GET',
    //           url:'/adminGetTeacherAcadProg',
    //           data:{
    //             d:data[0].data[0].id},
    //             success:function(data) {
    //             $('input[type=checkbox]').each(function(){
    //               var checkboxValue = $(this).val();
    //               var matchedAcadProg = false;
    //               $.each(data,function(index,value){
    //                 if(checkboxValue==value.id){
    //                   matchedAcadProg = true;
    //                 }
    //               })
    //               if(matchedAcadProg){
    //                 $(this).prop('checked',true)
    //               }
    //             })
    //           }
    //         })
           
    //       }
    //     }
    //   })
    //   $("#facultyform").attr('action', '/updateAccountInfo');
    //   $('.us').text('Update')
    //   $('.us').addClass('btn-success')
    //   $('.us').removeClass('btn-primary')
    // }

        $(document).ready(function(){

          $(document).on('change','#ut',function(){

            if($(this).val()==2 || $(this).val()==1 || $(this).val()==3 || $(this).val()== 8 || $( "#ut option:selected" ).attr('data-ref') == 20){
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
                                  '</label>'+
                              '</div><br>'   
                    }
                                  
                  @endif
                @endforeach
              $('#ap').append(dataString)
            }
            // else if($(this).val()==16){

            //   var dataString = '<label for="exampleInputEmail1">Courses</label><br>';
            //   @foreach(DB::table('college_courses')->where('deleted',0)->get() as $key=>$item)
            //       dataString+='<div class="icheck-success d-inline">'+
            //                       '<input type="checkbox" id="qp'+'{{$key}}'+'" name="q[]" value="'+'{{$item->id}}'+'">'+
            //                       '<label for="qp'+'{{$key}}'+'">'+'{{$item->courseDesc}}'+
            //                       '</label>'+
            //                   '</div><br>'
            //   @endforeach
            //   console.log(dataString)
            //   $('#ap').append(dataString)

            // }
            else{
              $('#ap').empty();
            }

          })

          $(document).on('click','.rm',function(){
            $('#confirmation').modal('show');
          })

          // $(document).on('click','.ee',function(){
          //   loadTeacherInfo($(this).attr('id'))

          // })

          $('#add-faculty').on('hidden.bs.modal', function () {
            $("facultyform").attr('action', '/admininsertfaculty');
            $('.us').text('Save')
            $('.us').removeClass('btn-success')
            $('.us').addClass('btn-primary')
          })
        })
  </script>

@endsection


