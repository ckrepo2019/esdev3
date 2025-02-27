
@extends('superadmin.layouts.app2')

@section('pagespecificscripts')

    <link rel="stylesheet" href="{{asset('css/pagination.css')}}">

@endsection


@section('modalSection')
      <div class="modal fade" id="view_recievers" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                        <div class="modal-body"  >
                              <div id="receiver_holder" >
                              </div>
                              <hr>
                              <div class="row">
                                    <h4 class="col-md-12">FILTER</h4>
                                    <div class="col-md-4">
                                          <div class="form-group">
                                                <label for="">Date Enrolled</label>
                                                <input class="form-control" id="date_enrolled" type="date">
                                          </div>
                                    </div>
                              </div>
                              <hr>
                              <div class="row">
                                    <div class="col-md-12">
                                          {{-- <button id="send_proceed" class="btn btn-primary"><i class="fas fa-mail-bulk"></i> Send Text Message</button> --}}
                                    </div>
                              </div>
                              <hr>
                              <div class="row">
                                   
                                    <div class="col-md-3">
                                          <span>Proccess: <span id="proccess_count">0</span></span>
                                    </div>
                                    <div class="col-md-3">
                                          <span>Sent: <span id="sent_count">0</span></span>
                                    </div>
                              </div>
                        </div>
                       
                  </div>
            </div>
      </div>

      <div class="modal fade" id="evaluate_receivers_modal" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                        <div class="modal-body"  >
                              <div id="receiver_evaluate_holder" >
                              </div>
                              <hr>
                              <div class="row">
                                    <div class="col-md-3"></div>
                              </div>
                              <button id="send_proceed" class="btn btn-primary"><i class="fas fa-mail-bulk"></i> Resend All</button>
                              <button class="btn btn-primary" id="procceed_evaluation"><i class="fas fa-sync-alt"></i> EVALUATE</button>
                             
                              <table class="table  mt-3">
                                    <tbody>
                                          <tr style="font-size:13px">
                                                <th width="16%" >Proccess: <span id="total_proccess_count">0</span></th>
                                                <th width="13%" class="text-center">Valid</th>
                                                <th width="13%" class="text-center">Not Valid</th>
                                                <th width="13%" class="text-center">Sent</th>
                                                <th width="13%" class="text-center">Not Send</th>
                                                <th width="13%" class="text-center">Ready</th>
                                                <th width="19%"></th>
                                               
                                          </tr>
                                          <tr>
                                                <td>Parent</td>
                                                <td class="text-center" id="valid_parent_contact_count">0</td>
                                                <td class="text-center" id="invalid_parent_contact_count">0</td>
                                                <td class="text-center" id="valid_parent_sent_count">0</td>
                                                <td class="text-center" id="invalid_parent_sent_count">0</td>
                                                <td class="text-center" id="ready_parent_resent_count">0</td>
                                                <td><button class="btn btn-primary btn-sm btn-block text-left" id="resendParent" disabled="disabled"><i class="fas fa-envelope-open-text"></i> Send Parent</button></td>
                                          </tr>
                                          <tr>
                                                <td>Student</td>
                                                <td class="text-center" id="valid_student_contact_count">0</td>
                                                <td class="text-center" id="invalid_student_contact_count">0</td>
                                                <td class="text-center" id="valid_student_sent_count">0</td>
                                                <td class="text-center" id="invalid_student_sent_count">0</td>
                                                <td class="text-center" id="ready_student_resent_count">0</td>
                                                <td><button class="btn btn-primary btn-sm btn-block text-left" id="resendStudent" disabled="disabled"><i class="fas fa-envelope-open-text"></i> Send Student</button></td>
                                          </tr>
                                          <tr>
                                                <td>Total</td>
                                                <td class="text-center" id="valid_total_contact_count">0</td>
                                                <td class="text-center" id="invalid_total_contact_count">0</td>
                                                <td class="text-center" id="valid_total_sent_count">0</td>
                                                <td class="text-center" id="invalid_totalt_sent_count">0</td>
                                                <td class="text-center" id="ready_total_resent_count">0</td>
                                                <td></td>
                                          </tr>
                                    </tbody>
                                  
                              </table>
                              
                        </div>
                       
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
                                    <li class="breadcrumb-item active">Enrollment SMS Bunker</li>
                              </ol>
                        </div>
                  </div>
            </div>
      </section>
      <section class="content pt-0">
                  <div class="row">
                        <div class="col-12">
                              <div class="card">
                                    <div class="card-header bg-primary ">
                                          <h4>Enrollment SMS Bunker</h4>
                                    </div>
                                    <div class="card-body">
                                          <h4>FILTER</h4>
                                          <div class="row">
                                                <div class="col-md-3 form-group">
                                                      <label for="">Status</label>
                                                      <select name="" id="" class="form-control">
                                                            <option value="">All</option>
                                                            <option value="1">Sent</option>
                                                            <option value="2">Unsent</option>
                                                      </select>
                                                </div>
                                                {{-- <div class="col-md-3 form-group">
                                                      <label for="">Contact Number Status</label>
                                                      <select name="" id="" class="form-control">
                                                            <option value="">All</option>
                                                            <option value="1">New Number</option>
                                                            <option value="2">Old Number</option>
                                                      </select>
                                                </div> --}}
                                                <div class="col-md-3 form-group">
                                                      <label for="">Specified Contact</label>
                                                      <select name="specified" id="specified" class="form-control">
                                                            <option value="">All</option>
                                                            <option value="1">Mother</option>
                                                            <option value="2">Father</option>
                                                            <option value="3">Guardian</option>
                                                            <option value="4">Not Specified</option>
                                                      </select>
                                                </div>
                                                <div class="col-md-3 form-group">
                                                      <label for="">Contact Validity</label>
                                                      <select name="specified" id="specified" class="form-control">
                                                            <option value="">All</option>
                                                            <option value="1">Valid</option>
                                                            <option value="2">Not Valid</option>
                                                           
                                                      </select>
                                                </div>
                                          </div>
                                          <div class="row">
                                                <div class="col-md-12">
                                                      <button class="btn btn-primary" id="generate"><i class="fas fa-sync-alt"></i> GENERATE</button>
                                                      {{-- <button class="btn btn-primary" id="resend"><i class="fas fa-mail-bulk"></i> RESEND ALL</button> --}}
                                                      <button class="btn btn-primary" id="evaluate_receivers_button"><i class="fas fa-mail-bulk"></i> EVALUATE</button>
                                                </div>
                                          </div>
                                          <br>
                                          <div class="row">
                                                <div class="col-md-12" id="student_list">

                                                </div>
                                                <div class="col-md-12" id="data-container">

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

                  var resendliststudent;
                  var resendlistparent;
                  
                  $(document).on('click','#procceed_evaluation',function(){

                        var vpsc = 0
                        var ivpsc = 0;
                        var vssc = 0;
                        var ivssc = 0;
                        var rprc = 0 ;
                        var rsrc = 0 ;
                        var vscc = 0;
                        var ivscc = 0;
                        var vpcc = 0;
                        var ivpcc = 0;
                        var evelproccess = 0
                        var countStudent = 0;
                        var studinfoCount =  parseInt( $('.stud-info').length / 10 )  + 1;
                        var firstIndex = 0;
                        var lastIndex = 10;
                        resendliststudent = [];
                        resendlistparent = [];
                        
                        $('#ready_parent_resent_count').text(rprc)
                        $('#ready_student_resent_count').text(rsrc)
                        $('#valid_parent_sent_count').text(vpsc)
                        $('#invalid_parent_sent_count').text(ivpsc)
                        $('#valid_student_sent_count').text(vssc)
                        $('#invalid_student_sent_count').text(ivssc)
                        $('#total_proccess_count').text(evelproccess)
                        $('#valid_student_contact_count').text(vscc)
                        $('#invalid_student_contact_count').text(ivscc)
                        $('#valid_parent_contact_count').text(vpcc)
                        $('#invalid_parent_contact_count').text(ivpcc)

                        evalutateStudents()

                        function evalutateStudents(){

                              var counter = 0;
                              var studinfoLength = $('.stud-info').length

                              $('.stud-info').slice(firstIndex,lastIndex).each(function(){
                                    
                                    var studid = $(this).attr('data-studid')
                                    var pcontact = $(this).attr('data-pcontact')
                                    var scontact = $(this).attr('data-scontact')
                                    var sid = $(this).attr('data-sid')
                                    var firstname = $(this).attr('data-firstname')

                                    if($(this).attr('data-vpc') == 1){

                                          vpcc += 1;
                                          $('#valid_parent_contact_count').text(vpcc)

                                    }else if($(this).attr('data-vpc') == 0){

                                          ivpcc += 1
                                          $('#invalid_parent_contact_count').text(ivpcc)

                                    }

                                    if($(this).attr('data-vsc') == 1){

                                          vscc += 1;
                                          $('#valid_student_contact_count').text(vscc)

                                    }else if($(this).attr('data-vsc') == 0){

                                          ivscc += 1
                                          $('#invalid_student_contact_count').text(ivscc)

                                    }

                                    $.ajax({
                                          type:'GET',
                                          url:'/smsbunker/enrollment',
                                          data:{
                                                evaluate:'evaluate',
                                                sid:$(this).attr('data-sid'),
                                                firstname:$(this).attr('data-firstname'),
                                          },
                                          success:function(data) {

                                                evelproccess += 1;

                                                if(data[0].parentSent == 1){

                                                      vpsc += 1;

                                                }
                                                else if( data[0].parentSent == 0 ){

                                                      ivpsc += 1;

                                                      if($('.stud-info[data-studid="'+studid+'"]').attr('data-vpc') == 1){

                                                            rprc += 1

                                                            resendlistparent.push({
                                                                              sid: sid,
                                                                              name: firstname,
                                                                              contact: pcontact
                                                                        })

                                                      }

                                                }
                                             

                                                if(data[0].studentSent == 1){

                                                      vssc += 1;

                                                }
                                                else if(data[0].studentSent == 0){

                                                      ivssc += 1;
                                                
                                                      if($('.stud-info[data-studid="'+studid+'"]').attr('data-vsc') == 1){
                                                    
                                                            rsrc += 1

                                                            resendliststudent.push({
                                                                              sid: sid,
                                                                              name: firstname,
                                                                              contact: scontact
                                                                        })

                                                      }

                                                }

                                                $('#total_proccess_count').text(evelproccess)
                                                $('#ready_parent_resent_count').text(rprc)
                                                $('#ready_student_resent_count').text(rsrc)
                                                $('#valid_parent_sent_count').text(vpsc)
                                                $('#invalid_parent_sent_count').text(ivpsc)
                                                $('#valid_student_sent_count').text(vssc)
                                                $('#invalid_student_sent_count').text(ivssc)


                                                $('#valid_total_contact_count').text(vpsc + vssc)
                                                $('#invalid_total_contact_count').text(ivpsc + ivssc)
                                                $('#valid_total_sent_count').text(vpsc + vssc)
                                                $('#invalid_totalt_sent_count').text(ivpsc + ivssc)
                                                $('#ready_total_resent_count').text(rprc + rsrc)
                                              


                                                counter += 1;

                                                if(counter == 9 && studinfoCount != 0){

                                                      studinfoCount -= 1

                                                      firstIndex = firstIndex + 10 ;
                                                      lastIndex = lastIndex + 10 ;

                                                      evalutateStudents()

                                                }

                                                

                                                if(studinfoLength == evelproccess){

                                                      if(resendliststudent.length > 0){

                                                            $('#resendStudent').removeAttr('disabled')

                                                      }

                                                      if(resendlistparent.length > 0){

                                                            $('#resendParent').removeAttr('disabled')

                                                      }

                                                }

                                          }

                                    })
                                         
                              })

                        }

                  })


                  $(document).on('click','#resendParent',function(){

                        var buttonInfo = $(this)
                        buttonInfo.attr('disabled','disabled')
                        var parentListCount = resendlistparent.length

                        $.each(resendlistparent,function(a,b){

                              var arrayIndex = a;
                              var contact = b['contact']
                              var sid = b['sid']
                              var name = b['name']

                              $.ajax({
                                    type:'GET',
                                    url:'/smsbunker/enrollment',
                                    data:{
                                          send:'send',
                                          parent:'parent',
                                          contact:contact,
                                          sid:sid,
                                          name:name
                                    },
                                    success:function(data) {

                                          parentListCount -= 1
                                          $('#ready_parent_resent_count').text(parentListCount)

                                          if(parentListCount == 0){
                                                parentListCount = []
                                                buttonInfo.attr('disabled','disabled')
                                          }

                                    
                                    }
                              })

                        })

                  })

                  $(document).on('click','#resendStudent',function(){

                        var buttonInfo = $(this)
                        buttonInfo.attr('disabled','disabled')
                        var studentListCount = resendliststudent.length

                        $.each(resendliststudent,function(a,b){

                              var arrayIndex = a;
                              var contact = b['contact']
                              var sid = b['sid']
                              var name = b['name']


                              $.ajax({
                                    type:'GET',
                                    url:'/smsbunker/enrollment',
                                    data:{
                                          send:'send',
                                          student:'student',
                                          contact:contact,
                                          sid:sid,
                                          name:name
                                    },
                                    success:function(data) {

                                          studentListCount -=1;
                                          $('#ready_student_resent_count').text(studentListCount)

                                          if(studentListCount == 0){
                                                studentListCount = []
                                                buttonInfo.attr('disabled','disabled')

                                          }
                                    
                                    }
                              })

                        })

                  })    
                  

                  $(document).on('click','#evaluate_receivers_button',function(){

                        $('#evaluate_receivers_modal').modal()

                        $.ajax({
                              type:'GET',
                              url:'/smsbunker/enrollment',
                              data:{
                                    receivers:'receivers',
                              },
                              success:function(data) {
                                    $('#receiver_evaluate_holder').empty();
                                    $('#receiver_evaluate_holder').append(data);
                              
                              }
                        })

                  })

                  $(document).on('click','#send_proceed',function(){

                        var sendCount = 0;
                        var proccess_count = 0;
                        var send_count = 0;

                        $('.valid-contact').each(function(){

                              var scontactno = $(this).attr('data-scontact')
                              var pcontactno = $(this).attr('data-pcontact')

                              if($(this).attr('data-vsc') == 0){

                                    scontactno = null;

                              }
                              if($(this).attr('data-vpc') == 0){

                                    pcontactno = null;

                              }

                              $.ajax({
                                    type:'GET',
                                    url:'/smsbunker/enrollment',
                                    data:{
                                          send:'send',
                                          sid:$(this).attr('data-sid'),
                                          scontactno:scontactno,
                                          pcontactno:pcontactno,
                                          firstname:$(this).attr('data-firstname'),
                                          date_enrolled:$('#date_enrolled').val(),
                                          acadprogid:$(this).attr('data-acadprog'),
                                          studid:$(this).attr('data-studid')

                                    },
                                    success:function(data) {


                                          proccess_count += 1;
                                          $('#proccess_count').text(proccess_count)
                                         
                                          if(data == 1){

                                                sendCount += 1;
                                                $('#sent_count').text(sendCount)
                                          
                                          }
                                          
                                    
                                    }
                              })
                        
                        })

                     
                  })


                  $(document).on('click','#resend',function(){

                        $('#view_recievers').modal()

                        $.ajax({
                              type:'GET',
                              url:'/smsbunker/enrollment',
                              data:{
                                    receivers:'receivers',
                              },
                              success:function(data) {
                                    $('#receiver_holder').empty();
                                    $('#receiver_holder').append(data);
                              
                              }
                        })

                  })


                  processpaginate(0,10,null,true)

                  function processpaginate(skip = null,take = null ,search = null, firstload = true){

                        $.ajax({
                              type:'GET',
                              url:'/smsbunker/enrollment',
                              data:{
                                    take:take,
                                    skip:skip,
                                    table:'table',
                                    search:search,
                                    students:'students',
                                    specified:$('#specified').val()
                              },
                              success:function(data) {
                                    $('#student_list').empty();
                                    $('#student_list').append(data);
                                    pagination($('#searchCount').val(),false)
                              
                              }
                        })

                  }
                  

                  var pageNum = 1;

                  $(document).on('click','#generate',function(){

                        pageNum = 1;
                        processpaginate(0,10,null,true)

                  })

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


            })

      </script>
            
@endsection

