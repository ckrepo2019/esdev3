
@extends('collegeportal.layouts.app2')

@section('pagespecificscripts')

@endsection

@section('content')



      @foreach($inputArray  as $item)
            @php
                  $inputs = $item[0];
                  $modalInfo = $item[1];
            @endphp
            @include('collegeportal.pages.forms.generalform')
      @endforeach


<section class="content">
      <div class="row">
            <div class="col-md-9">
                  <div class="card">
                        <div class="card-header bg-primary">
                              <div class="card-title">
                                    COURSES
                              </div>
                              <button class="btn btn-default btn-sm float-right create_course_modal" data-toggle="modal"  data-target="#courseModal" title="Contacts" data-widget="chat-pane-toggle" ><b>CREATE COURSE</b></button>
                        </div>
                        <div class="card-body course_table_holder">

                              @include('collegeportal.pages.colleges.coursetable')

                        </div>
                  </div>
            </div>
            <div class="col-md-3">
                  <div class="card">
                        <div class="card-header bg-primary">
                              <div class="card-title">
                                    ABOUT
                              </div> 
                        </div>
                        <div class="card-body">

                              <label><i class="fa fa-door-open mr-2"></i>COLLEGE</label>
                              <p class="text-success">{{$collegeInfo[0]->collegeDesc}}</p>
                              <hr>
                              <button class="btn btn-sm btn-success btn-block mb-2" data-toggle="modal"  data-target="#collegeModal" data-widget="chat-pane-toggle"><b>UPDATE</b></button>
                             
                              <a href="/colleges/delete/{{Str::slug($collegeInfo[0]->collegeDesc)}}" class="btn btn-sm btn-danger btn-block mb-2"><b>DELETE</b></a>

                              
                        </div>
                       
                  </div>
            </div>  
      </div>
</section>
@endsection

@section('footerscript')

      //courseAPI
      <script>
            $(document).ready(function(){

                  $('.savebutton').removeAttr('type')
                  $('.savebutton').removeAttr('onclick')
                  $('#courseModalForm').removeAttr('method')


                  var college = '{{Str::slug($collegeInfo[0]->collegeDesc)}}'
                  var selectedCourse;
                  var courseID = null;
                  var courseModalStatus = null;

                  const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                  });

                  $(document).on('click','#editcourse',function(){

                        $('#courseModal').modal()
                        selectedCourse = $(this).attr('data-value').toLowerCase().replace(/\s+/g, '-');


                        $.ajax({
                              type:'GET',
                              url:'/course?course='+selectedCourse+'&info=info',
                              data: {'_token': '{{ csrf_token() }}'},
                              success:function(data) {
                                   $('input[name="courseDesc"]').val(data[0].courseDesc)
                                   $('input[name="courseabrv"]').val(data[0].courseabrv)
                                   courseID = data[0].id
                              }
                        })

                        $('#courseModalForm button').text('UPDATE')
                        $('#courseModalForm button').removeAttr('class')
                        $('#courseModalForm button').addClass('btn btn-success savebutton')
           
                        $('#courseModal .modal-header').removeClass('bg-primary')
                        $('#courseModal .modal-header').addClass('bg-success')

                        courseModalStatus = 2

                  })

                  $(document).on('click','.create_course_modal',function(){

                        $('#courseModalForm button').text('CREATE')
                        $('#courseModalForm button').removeAttr('class')
                        
                        $('#courseModalForm button').addClass('btn btn-primary savebutton')

                        $('#courseModal .modal-header').removeClass('bg-success')
                        $('#courseModal .modal-header').addClass('bg-primary')

                        $('#courseModalForm')[0].reset()

                        courseModalStatus = 1
           

                  })

                  $(document).on('click','#removecourse',function(){

                        $.ajax({
                              type:'GET',
                              url:'/course?course='+$(this).attr('data-id')+'&delete=delete',
                              data: {'_token': '{{ csrf_token() }}'},
                              success:function(data) {
                                    if(data == 1){
                                          loadcourses()

                                          Toast.fire({
                                                type: 'success',
                                                title: 'Deleted Successfully!'
                                          })
                                    }
                              }
                        })

                  })

                  function loadcourses(){

                        $.ajax({
                              type:'GET',
                              url:'/course/?college='+college+'&table=table',
                              data: {'_token': '{{ csrf_token() }}'},
                              success:function(courseModalFormdata) {

                                    $('.course_table_holder').empty()
                                    $('.course_table_holder').append(data)

                              }
                        })

                  }

                  $( '#courseModalForm' ).submit( function( e ) {

                        if(courseModalStatus == 2){

                              var url = '/course?course='+selectedCourse+'&update=update&courseDesc='+$('input[name="courseDesc"]').val()+'&courseabrv='+$('input[name="courseabrv"]').val()

                        }
                        else if(courseModalStatus == 1){

                              var url = '/course?course='+selectedCourse+'&create=create&courseDesc='+$('input[name="courseDesc"]').val()+'&courseabrv='+$('input[name="courseabrv"]').val()+'&college='+college

                        }

                        $.ajax( {
                              url: url,
                              type: 'GET',
                              data: {'_token': '{{ csrf_token() }}'},
                              success:function(data) {

                                    if(data[0].status == 1){

                                          loadcourses()
                                          Toast.fire({
                                                type: 'success',
                                                title: 'Updated Successfully!'
                                          })

                                          $.each(data[0].inputs,function(a,b){
                                                $('input[name="'+a+'"]').removeClass('is-invalid')
                                          })

                                    }
                                    else if(data[0].status == 2){

                                          loadcourses()

                                          Toast.fire({
                                                type: 'success',
                                                title: 'Created Successfully!'
                                          })

                                          $.each(data[0].inputs,function(a,b){
                                                $('input[name="'+a+'"]').removeClass('is-invalid')
                                          })

                                    }
                                    else if(data[0].status == 0){

                                          $.each(data[0].errors,function(a,b){
                                         
                                                $('input[name="'+a+'"]').addClass('is-invalid')
                                                $('#'+a+'Error')[0].innerHTML = '<strong>'+b+'</strong>'
                                               
                                          })

                                    }

                              },
                        } );

                        e.preventDefault();

                  })
            })      
      </script>
@endsection

