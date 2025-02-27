
@extends('adminPortal.layouts.app2')

@section('pagespecificscripts')

    <link rel="stylesheet" href="{{asset('plugins/daterangepicker/daterangepicker.css')}}">
    <link rel="stylesheet" href="{{asset('css/pagination.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">

@endsection

@section('modalSection')

    <div class="modal fade" id="modal-primary" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title">Event Form</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="holidayform" method="GET" action="/admininsertholiday">
                <div class="modal-body">
                    <div class="message"></div>
                    <input name="si" type="hidden" id="si">
                    <div class="form-group">
                      <label>Event Name</label>
                      <input value="{{@old('des')}}"  id="des"  name="des" class="form-control @error('des') is-invalid @enderror" placeholder="Enter Name" onkeyup="this.value = this.value.toUpperCase();">
                      @if($errors->has('des'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('des') }}</strong>
                        </span>
                      @endif
                    </div>
                    <div class="form-group">
                        <label>Date</label>
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <span class="input-group-text">
                              <i class="far fa-calendar-alt"></i>
                            </span>
                          </div>
                          <input value="{{@old('day')}}" type="text" class="form-control float-right @error('day') is-invalid @enderror" id="day" name="day" >
                        </div>
                    </div>
                    <div class="form-group">
                      <label>Classification</label>
                      <select class="form-control @error('clas') is-invalid @enderror"  id="clas" name="clas" style="width: 100%;">
                          <option value="">SELECT EVENT CLASSIFICATION</option>
                          <option value="1">HOLIDAY</option>
                          <option value="2">ACTIVITY</option>
                      </select>
                      @if($errors->has('type'))
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $errors->first('type') }}</strong>
                          </span>
                      @endif
                    </div>
                    <div class="form-group">
                      <label>Event Type</label>
                      <select class="form-control @error('type') is-invalid @enderror"  id="type" name="type" style="width: 100%;">
                        <option value="">SELECT EVENT TYPE</option>
                          @if ($errors->any())
                            @if (@old('clas')!=null)
                              @foreach (App\Models\Principal\SPP_Calendar::getEventType(@old('clas')) as $item)
                                <option {{ old('type') == $item->id ? 'selected' : '' }} value="{{$item->id}}">{{$item->typename}}</option>
                              @endforeach 
                            @endif
                          @endif
                      </select>
                      @if($errors->has('type'))
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $errors->first('type') }}</strong>
                          </span>
                      @endif
                    </div>
                  <hr>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <div class="icheck-success d-inline">
                            <input @if(@old('noclass')!=null) checked @endif type="checkbox" id="noclass"  name="noclass" value="1">
                            <label  for="noclass" class="text-muted">No Class</label>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <div class="icheck-success d-inline">
                            <input @if(@old('noclass')!=null) checked @endif type="checkbox" id="annual"  name="annual" value="1">
                            <label  for="annual" class="text-muted">Annual Event</label>
                        </div>
                      </div>
                    </div>
                  </div>
                   
                </div>
                <div class="modal-footer justify-content-between">
                    
                    <button onClick="this.form.submit(); this.disabled=true; " type="submit" class="btn btn-primary savebutton">SAVE</button>
                </div>
            <form>
            </div>
        </div>
    </div>
@endsection


@section('content')
<section class="content-header ">
  <div class="container-fluid">
  <div class="row">
      <div class="col-sm-6">
     
      </div>
      <div class="col-sm-6">
      <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="/home">Home</a></li>
          <li class="breadcrumb-item active">School Calendar</li>
      </ol>
      </div>
  </div>
  </div>
</section>
<section class="content p-0">
  <div class="container-fluid">
      <div class="row">
        <div class="col-md-10">
          <div class="card">
            <div class="card-header bg-info">
            <span style="font-size: 16px"><b><i class="nav-icon fa fa-thumbtack"></i> SCHOOL CALENDAR</b></span>
              <div class="input-group input-group-sm w-25 float-right search">
                <input type="text" id="search" name="search" class="form-control float-right" placeholder="Search">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                </div>
              </div>
              <button class="btn btn-sm btn-primary float-right mr-2 mb-2" data-toggle="modal"  data-target="#modal-primary" title="Contacts" data-widget="chat-pane-toggle" ><b>ADD EVENT</b></button>
            </div>
            <div class="card-body table-responsive p-0 " id="dataholder">
                  @include('search.admin.holiday')
            </div>
            <div class="card-footer">
              <div class="mt-3" id="data-container">
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-2">
        <div class="card">
          <div class="card-header p-2 bg-info text-center d-flex justify-content-center"">
              <h3 class="card-title">Legends</h3>
          </div>
          <div class="card-body p-0">
              <ul class="nav nav-pills flex-column">
                  <li class="nav-item">
                      <a href="#" class="nav-link">
                          <strong>HOLIDAY</strong>
                          <span class="float-right badge badge-pill badge-danger mt-1">&nbsp;</span>
                      </a>
                  </li>
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                        <strong>ACTIVITY</strong>
                        <span class="float-right badge badge-pill badge-success mt-1">&nbsp;</span>
                    </a>
                </li>
              </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>



@endsection

@section('footerjavascript')

    <script src="{{asset('plugins/moment/moment.min.js')}}"></script>
    <script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
    <script src="{{asset('js/pagination.js')}}"></script>
    <script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>

<script>
    $(document).ready(function(){
        @if ($errors->any())
          $('#modal-primary').modal('show')
          for(x = 0 ; x < $('#clas')[0].options.length ; x++){
        
            if($('#clas')[0].options[x].value == '{{@old('clas')}}'){
              $('#clas')[0].options[x].selected = true
            }
            
          }
          @if(Session::has('update'))
            $("#holidayform").attr('action', '/adminupdateholiday');
            $('#si').val($(this).attr('id'))
            $('.savebutton').text('UPDATE')
            $('.savebutton').removeClass('btn-primary')
            $('.savebutton').addClass('btn-success')

          @endif
        @endif

        if($(window).width()<500){
            $('.search').addClass('w-50')
            $('.search').removeClass('w-25')
        }
    })


    $(function () {
      
        $('#day').daterangepicker({
            locale: {
                format: 'YYYY/MM/DD'
            }
        })
    })


</script>

<script>
    $(document).ready(function(){


      $(document).on('change','#clas',function(){
        if($(this).val() == 1){
          $('#noclass').prop('checked','checked')
          $('#annual').prop('checked','checked')
        }
        else{
          $('#noclass').prop('checked',false)
          $('#annual').prop('checked',false)
        }

        $.ajax({
          type:'GET',
          url:'/admingeteventtype',
          data:{
            id:$(this).val()
          },
          success:function(data) {
            $('#type').empty();
            $('#type').append('<option>SELECT EVENT TYPE</option>')
            $.each(data,function(key,value){
              $('#type').append('<option value="'+value.id+'">'+value.typename+'</option>')
            })
              
          }
        })
      })


        $('#modal-primary').on('hidden.bs.modal', function () {
            $('#holidayform').attr('action', '/admininsertholiday');
            $('.savebutton').text('SAVE')
            $('.savebutton').removeClass('btn-success')
            $('.savebutton').addClass('btn-primary')
            $('#holidayform')[0].reset()
            $('#day').daterangepicker({
                locale: {
                    format: 'YYYY/MM/DD'
                }
            })
        })

        $(document).on('click','.ed',function(){

            var typeid = 0;

            $("#holidayform").attr('action', '/adminupdateholiday');
            $('#si').val($(this).attr('id'))
            $('.savebutton').text('UPDATE')
            $('.savebutton').removeClass('btn-primary')
            $('.savebutton').addClass('btn-success')
            $.ajax({
                type:'GET',
                url:'/admingetholiday',
                data:{
                  id:$(this).attr('id')
                },
                success:function(data) {
                    if(data[0].data[0].annual == 1){
                    
                      $('#annual').prop('checked','checked')
                    }

                    if(data[0].data[0].noclass == 1){
                      $('#noclass').prop('checked','checked')
                    }
           
                    $('#clas').val(data[0].data[0].eventtype)
            
                    typeid = data[0].data[0].eventtype

                    var scholcaltypeid = data[0].data[0].typeid

                    $('#des').val(data[0].data[0].description)
                 
                    $.ajax({
                      type:'GET',
                      url:'/admingeteventtype',
                      data:{
                        id:typeid
                      },
                      success:function(data) {
              
                        $('#type').empty();
                        $('#type').append('<option>SELECT EVENT TYPE</option>')

                        $.each(data,function(key,value){

                          if(scholcaltypeid == value.id){
                            $('#type').append('<option selected value="'+value.id+'">'+value.typename+'</option>')
                          }
                          else{
                            $('#type').append('<option value="'+value.id+'">'+value.typename+'</option>')
                          }
                         
                         
                        })
                          
                      }
                    })

                    $('#day').data('daterangepicker').setStartDate(data[0].data[0].datefrom.replace(/-/g, "/"));
                    $('#day').data('daterangepicker').setEndDate(data[0].data[0].dateto.replace(/-/g, "/"));
                   
                }
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
          pageSize: 10,
          hideWhenLessThanOnePage: true,
          callback: function(data, pagination) {
            if(pagetype){
              $.ajax({
                type:'GET',
                url:'/adminsearchholiday',
                data:{
                  data:$("#search").val(),
                  pagenum:pagination.pageNumber},
                success:function(data) {
                  $('#dataholder').empty();
                  $('#dataholder').append(data);
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
          url:'/adminsearchholiday',
          data:{data:$(this).val(),pagenum:'1'},
          success:function(data) {
            $('#dataholder').empty();
            $('#dataholder').append(data);
            pagination($('#searchCount').val())
          }
        })
      });
    })
</script>


    
@endsection

