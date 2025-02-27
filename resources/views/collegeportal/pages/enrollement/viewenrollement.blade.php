
@extends('collegeportal.layouts.app2')

@section('pagespecificscripts')
      <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
      <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
      
      <style>
            #enrollinfo .card-body table tr td{
                  cursor: pointer;
            }
      </style>
@endsection

@section('content')

<section class="content-header">
      <div class="container-fluid">
      <div class="row">
          <div class="col-sm-6">
          </div>
          <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/home">HOME</a></li>
              <li class="breadcrumb-item active"><a href="/enrollement/college">ENROLLMENT</a></li>
          </ol>
          </div>
      </div>
      </div>
</section>
<section class="content">

      <div class="row">
            <div class="col-md-9">
                  <div class="card" id="schedCard">
                        <div class="card-header card-title bg-success">
                              CLASS SCHEDULE
                        </div>
                        <div class="card-body" id="classschedule">
                              @include('collegeportal.pages.tables.enrollmentsched')
                        </div>
                        <div class="card-footer">
                        </div>
                  </div>
            </div>
            <div class="col-md-3">
                  <div class="card" id="enrollinfo">
                              @if($enrolled)
                                    @include('collegeportal.pages.cards.enrollmentinfo')
                              @else
                              <div class="card-header card-title bg-primary">
                                    SECTIONS   
                              </div>
                              <div class="card-body">
                                    <table class="table table-sm table-hover">
                                    
                                                @foreach ($sections as $section)
                                                      <tr class="sectionselection">
                                                            <td data-value="{{$section->id}}">{{$section->sectionName}}</td>
                                                      </tr>
                                                @endforeach
                                          
                                    </table>
                              </div>
                        @endif
                        
                       
                  </div>
            </div>  
      </div>
</section>
@endsection

@section('footerscript')
      <script>
            $(document).ready(function(){

                  var a
                  var b = '{{$student->id}}'
              
                  $(document).on('click','.sectionselection td',function(){

                        $('.sectionselection td').removeClass('bg-primary')
                       
                        $(this).addClass('bg-primary')

                        a = $(this).attr('data-value');
                        
                       var table = schedTable(a)

                       table.done(function(){
                              $('#schedCard .card-footer').empty()
                              $('#schedCard .card-footer').append('<button class="btn btn-primary" id="enrollstudent">ENROLL</button>');
                       })
                       
                  })

                  function schedTable(a){
                        
                        return $.ajax({
                              type:'GET',
                              url:'/enrollement/sectscshed/'+a,
                              success:function(data) {
                                    $('#classschedule').empty();
                                    $('#classschedule').append(data);
                                    table = true
                                    
                              }
                        })

                        
                  }
             
                  $(document).on('click','.dropsubject',function(){
                        Swal.fire({
                              title: 'Are you sure?',
                              text: "You won't be able to revert this!",
                              type: 'info',
                              showCancelButton: true,
                              confirmButtonColor: '#3085d6',
                              cancelButtonColor: '#d33',
                              confirmButtonText: 'Yes, drop it!'
                              }).then((result) => {
                              if (result.value) {
                                   
                                    fetch('{{Request::root()}}'+'/college/enrollment/dropsubject/'+$(this).attr('data-value'))
                                    
                              }
                        })
                  })


                  $(document).on('click','#enrollstudent',function(){
                        Swal.fire({
                              title: 'Enroll Student?',
                              type: 'info',
                              showCancelButton: true,
                              confirmButtonColor: '#3085d6',
                              cancelButtonColor: '#d33',
                              confirmButtonText: 'Yes!'
                              }).then((result) => {
                                    if (result.value) {
                                          var c = $("input[name='c[]']").map(function () {
                                                            if($(this).prop("checked")  == true){
                                                                  return this.value; 
                                                            }
                                                      }).get();
                                          $.ajax({
                                                type:'GET',
                                                data: {
                                                      c : c
                                                },
                                                url:'/enroll/student/'+b+'/'+a,
                                                success:function(data) {
                                                      $("input[name='c[]']").map(function () {
                                                            if($(this).prop("checked")  == true){
                                                                  $(this).attr('onclick', 'return false')
                                                            }
                                                            else{
                                                                  $(this).attr('onclick', 'return false')
                                                            }
                                                      }).get();

                                                      $('#schedCard .card-footer').empty()
                                                      $('#enrollinfo').empty();
                                                      $('#enrollinfo').append(data);

                                                      Swal.fire({
                                                            title: 'Enrollment successful!',
                                                            type: 'success',
                                                            showConfirmButton: false,
                                                            timer: 1500
                                                      })
                                                      
                                                }
                                                
                                          })
                                    }
                              })

                            
                  })
                 
            })

      </script>
@endsection

