
@extends('adminPortal.layouts.app2')

@section('pagespecificscripts')

<style>

      .dropdown-toggle::after {
                  display: none;
                  margin-left: .255em;
                  vertical-align: .255em;
                  content: "";
                  border-top: .3em solid;
                  border-right: .3em solid transparent;
                  border-bottom: 0;
                  border-left: .3em solid transparent;
            }
</style>

@endsection



@section('modalSection')

      <div class="modal fade" id="course_modal_table" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                  <div class="modal-header bg-primary">
                        <h5 class="modal-title">Courses</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                        </button>
                  </div>
                        <div class="modal-body course_table_holder table-responsive p-0" style="height: 400px">
                        
                        </div>
                        <div class="modal-footer">
                              <button class="btn btn-primary float-left create_course_modal" data-toggle="modal"  data-target="#courseModal" title="Contacts" data-widget="chat-pane-toggle" ><b>CREATE COURSE</b></button>
                        </div>
                  </div>
            </div>
      </div>

      @foreach($inputArray  as $item)
            @php
                  $inputs = $item[0];
                  $modalInfo = $item[1];
            @endphp
            @include('collegeportal.pages.forms.generalform')

      @endforeach

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
                    <li class="breadcrumb-item active">Room</li>
                </ol>
                </div>
            </div>
            </div>
      </section>

      <section class="content p-0">
            <div class="container-fluid">
                  <div class="card">
                              <div class="card-header">
                                    <button class="btn btn-primary create_college_modal" data-toggle="modal" data-target="#collegeModal" title="Contacts" data-widget="chat-pane-toggle" ><b>CREATE COLLEGE</b></button>
                              </div>
                              <div class="card-body college_table_holder" >
                                    <table class="table table-striped mb-0" >
                                          <thead>
                                                <th width="5%"></th>
                                                <th width="75%">College Description</th>
                                                <th width="20%">Abrv.</th>
                                               
                                          </thead>
                                          <tbody>
                                                @foreach ($colleges as $college)
                                                      <tr>
                                                            <td>
                                                                  <div class="dropdown">
                                                                        <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                              <i class="fas fa-ellipsis-v"></i>
                                                                        </button>
                                                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                                              <a class="dropdown-item" href="#" id="editcollege" data-id="{{$college->collegeDesc}}" data-value="{{$college->collegeDesc}}"  ><i class="fas fa-edit"></i> Edit College</a>
                                                                              <a class="dropdown-item" href="#" id="removecollege" data-id="{{$college->collegeDesc}}" ><i class="fas fa-trash-alt pr-2"></i>Remove College</a>
                                                                              <a class="dropdown-item" href="#" id="viewcourses" data-id="{{$college->collegeDesc}}" data-abrv="{{$college->collegeabrv}}"><i class="fas fa-eye pr-2"></i>View Course</a>
                                                                        </div>
                                                                  </div>
                                                                  
                                                            </td>
                                                            <td class="align-middle">{{$college->collegeDesc}}</td>
                                                            <td class="align-middle">{{$college->collegeabrv}}</td>
                                                           
                                                      </tr>
                                                @endforeach
                                          </tbody>
                                    </table>
                        </div>
                  </div>
            </div>
      </section>
    
@endsection

@section('footerjavascript')
      <script>
            $(document).ready(function(){

                  var collegeModalStatus = null;
                  var college = null;

                  const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                  });
                  
                  $(document).on('click','#editcollege',function(){

                        $('#collegeModal').modal();

                        var editcollegedataid = $(this).attr('data-id').toLowerCase().replace(/\s+/g, '-')

                        $.ajax({
                              type:'GET',
                              url:'/colleges/?college='+editcollegedataid+'&info=info',
                              data: {'_token': '{{ csrf_token() }}'},
                              success:function(data) {
                                    $('.collegeDesc').val(data[0].collegeDesc)
                                    $('.collegeabrv').val(data[0].collegeabrv)
                              }
                        })

                        $('#collegeModalForm button').text('UPDATE')
                        $('#collegeModalForm button').removeAttr('class')
                        $('#collegeModalForm button').addClass('btn btn-success savebutton')
      
                        $('#collegeModal .modal-header').removeClass('bg-primary')
                        $('#collegeModal .modal-header').addClass('bg-success')

                        college = editcollegedataid
                        collegeModalStatus = 2

                  })

                  
                  $(document).on('click','.create_college_modal',function(){

                        $('#collegeModalForm button').text('CREATE')
                        $('#collegeModalForm button').removeAttr('class')

                        $('#collegeModalForm button').addClass('btn btn-primary savebutton')

                        $('#collegeModal .modal-header').removeClass('bg-success')
                        $('#collegeModal .modal-header').addClass('bg-primary')

                        $('#collegeModalForm')[0].reset()

                        collegeModalStatus = 1

                  })


                  function loadcolleges(){

                        $.ajax({
                              type:'GET',
                              url:'/colleges?table=table',
                              success:function(data) {

                                    $('.college_table_holder').empty()
                                    $('.college_table_holder').append(data)

                              }
                        })

                  }

                  $(document).on('click','#removecollege',function(){

                        var removecollegedataid = $(this).attr('data-id').toLowerCase().replace(/\s+/g, '-')

                        $.ajax({
                              type:'GET',
                              url:'/colleges?college='+removecollegedataid+'&delete=delete',
                              data: {'_token': '{{ csrf_token() }}'},
                              success:function(data) {
                                    if(data == 1){

                                          loadcolleges()

                                          Toast.fire({
                                                type: 'success',
                                                title: 'Deleted Successfully!'
                                          })
                                    }
                              }
                        })

                  })



                  $( '#collegeModalForm' ).submit( function( e ) {

                        if(collegeModalStatus == 2){

                              var url = '/colleges?college='+college+'&update=update&collegeDesc='+$('input[name="collegeDesc"]').val()+'&collegeabrv='+$('input[name="collegeabrv"]').val()

                        }
                        else if(collegeModalStatus == 1){

                              var url = '/colleges?create=create&collegeDesc='+$('input[name="collegeDesc"]').val()+'&collegeabrv='+$('input[name="collegeabrv"]').val()

                        }

                        $.ajax( {
                              url: url,
                              type: 'GET',
                              data: {'_token': '{{ csrf_token() }}'},
                              success:function(data) {

                                    if(data[0].status == 1){

                                          loadcolleges()
                                          Toast.fire({
                                                type: 'success',
                                                title: 'Updated Successfully!'
                                          })

                                          $.each(data[0].inputs,function(a,b){
                                                $('input[name="'+a+'"]').removeClass('is-invalid')
                                          })

                                    }
                                    else if(data[0].status == 2){

                                          loadcolleges()

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

      <script>
            $(document).ready(function(){

                  $('.savebutton').removeAttr('type')
                  $('.savebutton').removeAttr('onclick')

                  $('#courseModalForm').removeAttr('method')
                  $('#collegeModalForm').removeAttr('method')
               
                  var selectedCourse;
                  var courseID = null;
                  var courseModalStatus = null;
                 
                  
                  var college = null;

                  const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                  });


                  $(document).on('click','#viewcourses',function(){

                        $('#course_modal_table').modal();
                        $('#course_modal_table .modal-title').text($(this).attr('data-abrv')+' COURSES')

                        var viewcollegedataid = $(this).attr('data-id').toLowerCase().replace(/\s+/g, '-')

                        college = viewcollegedataid;

                        $.ajax({
                              type:'GET',
                              url:'/course/?college='+viewcollegedataid+'&table=table',
                              data: {'_token': '{{ csrf_token() }}'},
                              success:function(data) {

                                    $('.course_table_holder').empty()
                                    $('.course_table_holder').append(data)

                              }
                        })

                      

                  })

                  function loadcourses(){

                        console.log(college)

                        $.ajax({
                              type:'GET',
                              url:'/course/?college='+college+'&table=table',
                              data: {'_token': '{{ csrf_token() }}'},
                              success:function(data) {

                                    $('.course_table_holder').empty()
                                    $('.course_table_holder').append(data)

                              }
                        })

                  }

                

                  $(document).on('click','#editcourse',function(){

                        $('#courseModal').modal()
                        selectedCourse = $(this).attr('data-value').toLowerCase().replace(/\s+/g, '-');

                        console.log(selectedCourse)

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

