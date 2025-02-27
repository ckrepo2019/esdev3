@extends('principalsportal.layouts.app2')

@section('pagespecificscripts')

      <link rel="stylesheet" href="{{asset('css/pagination.css')}}">

@endsection

@section('modalSection')

   

@endsection

@section('content')
      <section class="content-header">
            <div class="container-fluid">
            <div class="row mb-2">
            <div class="col-sm-7">
            
            </div>
            <div class="col-sm-5">
            <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="/home">Home</a></li>
                  <li class="breadcrumb-item"><a href="/principalPortalSchedule">Sections</a></li>
                  <li class="breadcrumb-item active"></li>
            </ol>
            </div>
            </div>
            </div>
      </section>
      <section class="content ">
            <div class="container-fluid ">
            <div class="row">
                  <div class="col-md-3">
                        <div class="card h-100">
                              <div class="card-header bg-primary pb-3">
                                    FILTER
                                   
                              </div>
                              <div class="card-body">
                                    <div class="form-group">
                                          <label for="">Academic Program</label>
                                          <select class="form-control form-control-sm" id="acadid">
                                                <option selected value="Crypt::encrypt(0)" disabled>Select Academic Program</option>
                                                @foreach (App\Models\Principal\SPP_AcademicProg::getPrincipalAcadProg(Session::get('prinInfo')->id) as $item)
                                                    @if($item->id == 2)
                                                        {{$studcount = Session::get('psstudentcount')}}
                                                    @elseif($item->id == 3)
                                                        {{$studcount = Session::get('gsstudentcount')}}
                                                    @elseif($item->id == 4)
                                                        {{$studcount = Session::get('jhstudentcount')}}
                                                    @elseif($item->id == 5)
                                                        {{$studcount = Session::get('shstudentcount')}}
                                                    @endif
                                                    <option id="acadids" class="{{ $studcount > 0 ? 'text-success':'text-danger'}}" value="{{Crypt::encrypt($item->id)}}">{{$item->progname}} <p style="background-color:#FF0055;">( {{$studcount}} )</p></option>
                                                @endforeach
                                          </select>
                                    </div>
                                    <div class="form-group">
                                          <label>Grade Level</label>
                                          <select class="form-control form-control-sm" id="aglevels">
                                                <option selected value="">All</option>
                                          </select>
                                    </div>
                                    <div class="form-group">
                                          <label for="">Gender</label>
                                          <select name="gender" id="gender" class="form-control">
                                                <option value="">All</option>
                                                <option value="MALE">MALE</option>
                                                <option value="FEMALE">FEMALE</option>
                                          </select>
                                    </div>
                                    <div class="form-group">
                                          <label for="">Grantee</label>
                                          <select name="grantee" id="grantee" class="form-control">
                                                <option value="">Select Grantee</option>
                                                @foreach (DB::table('grantee')->get() as $item)
                                                      <option value="{{$item->id}}">{{$item->description}}</option>
                                                @endforeach
                                          </select>
                                    </div>
                                    <div class="form-group">
                                          <label for="">Student Status</label>
                                          <select name="" id="" class="form-control">
                                                <option value="">Select Status</option>
                                                <option value="1">ENROLLED</option>
                                                <option value="2">LATE ENROLLED</option>
                                                <option value="3">DROPPED OUT</option>
                                                <option value="4">TRANSFERRED IN</option>
                                                <option value="5">TRANSFERRED OUT</option>
                                          </select>
                                    </div>
                              </div>
                        </div>
                  </div>
                  <div class="col-md-9">
                        <div class="card card-primary  h-100">
                              <div class="card-header  border-0 bg-primary">
                                  <div class="input-group input-group-sm w-25 float-right search">
                                      <input type="text" id="search" name="table_search" class="form-control float-right" placeholder="Search">
                                      <div class="input-group-append">
                                          <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                                      </div>
                                  </div>
                                  
                              </div>
                              <div class="card-body p-0" id="studentholder">
                                 
                              </div>
                              <div class="card-footer  pt-1 pb-1 pl-2  bg-white d-flex justify-content-center">
                                  <div id="data-container"></div>
                              </div>
                          </div>
                  </div>
            </div>
            </div>
      </section>

@endsection

@section('footerjavascript')

    <script src="{{asset('js/pagination.js')}}"></script>

    {{-- <script>
        $(document).ready(function(){

            $(document).on('change','.acadid',function() {
                $.ajax({
                type:'GET',
                url:'/searchbygradelevel',
                data:{
                    data:$(this).val(),
                    apid:$('#acadid').val()
                    },
                    success:function(data) {
                        console.log('sdfsfdf');
                        $('#aglevels').empty();
                        $('#aglevels').append('<option >Select Grade Level</option>')
                        $.each(data,function(key,value){
                            $('#aglevels').append('<option value='+value.id+'>'+value.levelname+'</option>')
                            
                        })
                    }
                })

            });

            $(document).on('change','.aglevels',function() {
                $.ajax({
                type:'GET',
                url:'/searchbygradelevelid',
                data:{
                    data:$(this).val(),
                    aglevel:$('#aglevels').val()
                    }
                })

            });
            
        })
    </script> --}}
    <script>
        $(document).ready(function(){

            if($(window).width()<500){
                $('.search').addClass('w-100 mt-2')
                $('.acadid').addClass('w-100')
                $('.col-md-2, .col-md-8').addClass('mb-3')
                $('.search').removeClass('w-25')
            }

        pagination(0,false);

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
                    url:'/searchstudentajax',
                    data:{
                        data:$("#search").val(),
                        pagenum:pagination.pageNumber,
                        apid:$('#acadid').val(),
                        gl:$('#aglevels').val(),
                        gender:$('#gender').val(),
                        gender:$('#grantee').val(),
                        tableform:true
                    },
                    success:function(data) {
                    $('#studentholder').empty();
                    $('#studentholder').append(data);
                    }
                })
                }
                pagetype=true;
            }
            })
        }

        $("#search" ).keyup(function() {
            $.ajax({
            type:'GET',
            url:'/searchstudentajax',
            data:{
                  data:$("#search").val(),
                  pagenum:'1',
                  apid:$('#acadid').val(),
                  gl:$('#aglevels').val(),
                  gender:$('#gender').val(),
                  grantee:$('#grantee').val(),
                  tableform:true
                },
            success:function(data) {
                $('#studentholder').empty();
                $('#studentholder').append(data);
                pagination($('#searchCount').val())
            }
            })
        });

      $(document).on('change','#acadid',function() {
            $.ajax({
            type:'GET',
            url:'/searchstudentajax',
            data:{
                  data:$("#search").val(),
                  pagenum:'1',
                  apid:$('#acadid').val(),
                  gl:$('#aglevels').val(),
                  gender:$('#gender').val(),
                  grantee:$('#grantee').val(),
                  tableform:true
                  },
            success:function(data) {

                  $('#studentholder').empty();
                  $('#studentholder').append(data);

                  $.ajax({
                  type:'GET',
                  url:'/searchbygradelevel',
                  data:{
                        data:'',
                        apid:$('#acadid').val()
                        },
                        success:function(data) {
                              
                              $('#aglevels').empty();
                              $('#aglevels').append('<option value="">All</option>')
                              $.each(data,function(key,value){
                              $('#aglevels').append('<option value='+value.id+'>'+value.levelname+'</option>')
                              
                              })
                        }
                  })

                  pagination($('#searchCount').val())
            }
            })
      });

            $(document).on('change','#aglevels',function() {
                  $.ajax({
                        type:'GET',
                        url:'/searchstudentajax',
                        data:{
                              data:$("#search").val(),
                              pagenum:'1',
                              apid:$('#acadid').val(),
                              gl:$('#aglevels').val(),
                              gender:$('#gender').val(),
                              grantee:$('#grantee').val(),
                              tableform:true
                              },
                        success:function(data) {

                              $('#studentholder').empty();
                              $('#studentholder').append(data);
                              pagination($('#searchCount').val())

                        }
                  })
            });

            $(document).on('change','#gender',function() {
                  $.ajax({
                        type:'GET',
                        url:'/searchstudentajax',
                        data:{
                              data:$("#search").val(),
                              pagenum:'1',
                              apid:$('#acadid').val(),
                              gl:$('#aglevels').val(),
                              gender:$('#gender').val(),
                              grantee:$('#grantee').val(),
                              tableform:true
                              },
                        success:function(data) {

                              $('#studentholder').empty();
                              $('#studentholder').append(data);
                              pagination($('#searchCount').val())
                              
                        }
                  })
            });

            $(document).on('change','#grantee',function() {
                  $.ajax({
                        type:'GET',
                        url:'/searchstudentajax',
                        data:{
                              data:$("#search").val(),
                              pagenum:'1',
                              apid:$('#acadid').val(),
                              gl:$('#aglevels').val(),
                              gender:$('#gender').val(),
                              grantee:$('#grantee').val(),
                              tableform:true
                        },
                        success:function(data) {

                              $('#studentholder').empty();
                              $('#studentholder').append(data);
                              pagination($('#searchCount').val())
                              
                        }
                  })
            });
      })
</script>



@endsection


