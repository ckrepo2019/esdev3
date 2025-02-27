
@extends('deanportal.layouts.app2')

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
            
                  </ol>
            </div>
            </div>
            </div>
      </section>
      <section class="content">
            <div class="card">
                  <div class="card-header">
                        COURSES
                        <div class="input-group input-group-sm float-right w-25 search">
                              <input type="text" id="search" name="search" class="form-control float-right" placeholder="Search" >
                             
                        </div>
                  </div>
                  <div class="card-body p-0">
                        <table class="table table-striped">
                              <thead>
                                    <tr>
                                          <td>DESCRIPTION</>
                                    </tr>
                              </thead>
                              <tbody class="course_table_holder">
                                    {{-- @foreach ($courses as $item)
                                          <tr>
                                                <td><a href="#" class="courselink" data-id="{{$item->courseDesc}}">{{$item->courseDesc}}<a></td>
                                          </tr>
                                    @endforeach --}}
                              </tbody>
                        </table>
                  </div>
                  <div class="card-footer">
                        <div class="" id="data-container">
                        </div>
                  </div>
            </div>
      </section>
@endsection

@section('footerjavascript')

<script src="{{asset('js/pagination.js')}}"></script> 

<script>
      $(document).ready(function(){

           

            var colleges = []

            @foreach($college as $item )

                  colleges.push('{{$item}}')

            @endforeach

            $(document).on('input','#search',function(){

                  loadcourses()

            })

      
            loadcourses(0)

            function loadcourses(pagenum = 1){
          
                  $.ajax({
                        type:'GET',
                        url:'/course/?colleges='+colleges+'&table=table&search='+$('#search').val()+'&take=10'+'&pagenum='+pagenum,
                        data: {'_token': '{{ csrf_token() }}'},
                        success:function(data) {

                              $('.course_table_holder').empty()
                              $('.course_table_holder').append(data)


                              $.ajax( {
                                    url:'/course/?colleges='+colleges+'&count=count&search='+$('#search').val(),
                                    type: 'GET',
                                    success:function(data) {
                                      
                                          if(pagenum == 0){

                                                pagination(data,false,1)
                              
                                          }
                                          else if(pagenum > 0){

                                                pagination(data,false,pagenum)
                                                
                                          }

                                    }
                                    
                                    
                                    
                              });


                              $('.courselink').each(function(){
                                    var courseDesc = $(this).attr('data-id').toLowerCase().replace(/\s+/g, '-')
                                    $(this).attr('href','/dean/prospectus/'+courseDesc)
                              })



                        }
                  })

            }

            function pagination(itemCount,pagetype,pagenum){

                  var result = [];

                  for (var i = 0; i < itemCount; i++) {
                        result.push(i);
                  }

                  var pageNum = pagenum;

                  $('#data-container').pagination({
                        dataSource: result,
                        hideWhenLessThanOnePage: true,
                        pageNumber: pageNum,
                        pageRange: 1,
                        callback: function(data, pagination) {

                              if(pagetype){
                                    
                                    loadcourses(pagination.pageNumber)
                              
                              }

                              pagetype=true

                        }
                  })

            }



      })
</script>


@endsection

