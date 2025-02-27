@extends('studentPortal.layouts.app2')

@section('pagespecificscripts')
      <style>
            .enrollment_history{
                  cursor: pointer;
            }
            .font-sm{
                  font-size: 13px;
            }
      </style>
@endsection

@section('content')
      <section class="content-header pt-0">
            <div class="container-fluid ">
                  <div class="row mb-2">
                    <div class="col-sm-6">
                      <h1>Enrollment History</h1>
                    </div>
                    <div class="col-sm-6">
                      <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Enrollment History</li>
                      </ol>
                    </div>
                  </div>
            </div>
      </section>
      <section class="">
            <div class="row">
                  <div class="col-md-12">
                        <div class="card">
                              <div class="card-header p-1 bg-primary">

                              </div>
                              <div class="card-body">
                                    <div class="row">
                                          <div class="col-md-3 border-right pl-0">
                                                <table class="table table-sm">
                                                      <thead>
                                                            <tr><th>SCHOOL YEAR - SEMESTER</th></tr>
                                                      </thead>
                                                      <tbody id="enrollment_history_holder">
                                                           
                                                      </tbody>
                                                </table>
                                          </div>
                                          <div class="col-md-9 pr-0">
                                                <div class="row">
                                                      <div class="col-md-12">
                                                           <div class="row">
                                                                  <div class="col-md-3">
                                                                        <strong>Grade Level</strong>
                                                                        <p class="text-muted" id="enrollment_level">--</p>
                                                                  </div>
                                                                  <div class="col-md-3">
                                                                        <strong>Section</strong>
                                                                        <p class="text-muted" id="enrollment_section">--</p>
                                                                  </div>
                                                                  <div class="col-md-3">
                                                                        <strong>Enrollment Status</strong>
                                                                        <p class="text-muted" id="enrollment_status">--</p>
                                                                  </div>
                                                                  <div class="col-md-2">

                                                                  </div>
                                                           </div>
                                                      </div>
                                                      <div class="col-md-12">
                                                            <div class="row">
                                                                  <div class="col-md-6">
                                                                        <strong>Adviser</strong>
                                                                        <p class="text-muted" id="enrollment_adviser">--</p>
                                                                  </div>
                                                            </div>
                                                      </div>
                                                      <hr>
                                                      <table class="table table-sm font-sm mb-0 mt-3">
                                                            <thead>
                                                                  <tr>
                                                                        <th class="bg-secondary">CLASS SCHEDULE</th>
                                                                  </tr>
                                                            </thead>
                                                     </table>
                                                      <div class="col-md-12 table-responsive p-0"  style="height: 300px;">
                                                            <table class="table table-sm font-sm table-bordered font-sm" style="width: 100%">
                                                                  <thead>
                                                                        <tr>
                                                                              <th width="20%">SUBJECT</th>
                                                                              <th width="20%" class="text-center">DAY</th>
                                                                              <th width="20%" class="text-center">TIME</th>
                                                                              <th width="20%" class="text-center">ROOM</th>
                                                                              <th width="20%" class="text-center">TEACHER</th>
                                                                        </tr>
                                                                  </thead>
                                                                  <tbody id="enrollment_history_schedule">
                                                                       
                                                                  </tbody>
                                                            </table>
                                                      </div>
                                                      <table class="table table-sm font-sm mb-0 mt-3">
                                                            <thead>
                                                                  <tr>
                                                                        <th class="bg-secondary" >GRADES</th>
                                                                  </tr>
                                                            </thead>
                                                     </table>
                                                      <div class="col-md-12 table-responsive p-0">
                                                            <table class="mb-0 table table-bordered table-sm font-sm">
                                                                  <thead>
                                                                        <tr>
                                                                              <td class="p-1 align-middle text-center" rowspan="2" width="55%"><small>SUBJECTS</small></td>
                                                                              <td class="p-1 align-middle pr" align="center" colspan="4" width="20%" id="pr"><small>PERIODIC RATINGS</small></td>
                                                                              <td class="p-1 align-middle" align="center" rowspan="2" width="10%"><small>Final<br>Rating</small></td>
                                                                              <td class="p-1 align-middle" align="center" rowspan="2" width="15%"><small>Action<br>Taken</small></td>
                                          
                                                                        </tr>
                                                                        <tr align="center">
                                                                              <td class="p-1" id="q1_fg"><small>1</small></td>
                                                                              <td class="p-1" id="q2_fg"><small>2</small></td>
                                                                              <td class="p-1" id="q3_fg"><small>3</small></td>
                                                                              <td class="p-1" id="q4_fg"><small>4</small></td>
                                                                        </tr>
                                                                  </thead>
                                                                  <tbody id="enrollment_history_grade">
                                                                  </tbody>
                                                            </table>
                                                      </div>
                                                      {{-- <table class="table table-sm font-sm mb-0 mt-3 gs" hidden>
                                                            <thead>
                                                                  <tr>
                                                                        <th class="bg-secondary">BILLING ASSESSMENT</th>
                                                                  </tr>
                                                            </thead>
                                                      </table>
                                                      <div class="col-md-12 table-responsive p-0" hidden>
                                                            <table class="table table-sm font-sm table-head-fixed">
                                                                  <thead>
                                                                        
                                                                        <tr>
                                                                              <th width="55%">PARTICULARS</th>
                                                                              <th width="15%" class="text-right">AMOUNT</th>
                                                                              <th width="15%" class="text-right">PAYMENT</th>
                                                                              <th width="15%" class="text-right">BALANCE</th>
                                                                        </tr>
                                                                  </thead>
                                                                  <tbody id="student_billing">
                                                                    
                                                                  </tbody>
                                                            </table>
                                                      </div>
                                                      <table class="table table-sm font-sm mb-0 mt-3">
                                                            <thead>
                                                                  <tr>
                                                                        <th class="bg-secondary">LEDGER</th>
                                                                  </tr>
                                                            </thead>
                                                      </table
                                                      <div class="col-md-12 table-responsive p-0 gs">
                                                            <table class="table table-sm font-sm table-head-fixed">
                                                                  <thead>
                                                                        
                                                                        <tr>
                                                                              <th width="55%">PARTICULARS</th>
                                                                              <th width="15%" class="text-right">AMOUNT</th>
                                                                              <th width="15%" class="text-right">PAYMENT</th>
                                                                              <th width="15%" class="text-right">BALANCE</th>
                                                                        </tr>
                                                                  </thead>
                                                                  <tbody id="student_ledger">
                                                                    
                                                                  </tbody>
                                                            </table>
                                                     
                                                     
                                                      <table class="table table-sm font-sm mb-0 mt-3" hidden>
                                                            <thead>
                                                                  <tr>
                                                                        <th class="bg-secondary" >ATTENDANCE <span class="sydisplay"></span></th>
                                                                  </tr>
                                                            </thead>
                                                     </table>
                                                      <div class="col-md-12 table-responsive p-0" hidden> 
                                                            <table class="mb-0 table table-bordered table-sm font-sm">
                                                                  <thead>
                                                                        <tr>
                                                                            <td width="55%" class="align-middle text-center">MONTH</td>
                                                                            <td width="15%" class="align-middle text-center">DAYS IN SCHOOL</td>
                                                                            <td width="15%" class="align-middle text-center">DAYS <br>PRESENT</td>
                                                                            <td width="15%" class="align-middle text-center">DAYS <br>ABSENT</td>
                                                                           
                                                                        </tr>
                                                                    </thead>
                                                                  <tbody id="enrollment_history_attendance">
                                                                        
                                                                  </tbody>
                                                            </table>
                                                      </div> --}}
                                                </div>
                                          </div>
                                    </div>

                                   
                              </div>
                        </div>
                  </div>
            </div>
            
      </section>
      <section>
            <div class="row">
                  
            </div>
      </section>

      <script>
            $(document).ready(function(){

                  var active_sy = @json(DB::table('sy')->where('isactive',1)->select('id','sydesc')->first());
                  var active_sem =  @json(DB::table('semester')->where('isactive',1)->select('id','semester')->first());
                  var payment_setup = @json(DB::table('schoolinfo')->first());

                  var currently_enrolled = false
                  var syid 
                  var semid
                  var levelid

                  loadstudentenrollment()

                  function loadstudentenrollment(){

                        $.ajax({
                              type:'GET',
                              url: '/student/enrollment/record',
                              success:function(data) {
                                    enrollment_record = data

                                    $.each(data,function(a,b){

                                          if(b.acadprogid == 5){
                                                if(active_sy.id == b.syid && active_sem.id == b.semid){
                                                      $('#enrollment_history_holder').append('<tr tr-acad="'+b.acadprogid+'" class="enrollment_history bg-primary" tr-sy="'+b.syid+'" tr-sem="'+b.semid+'" tr-sydesc="'+b.sydesc+'" tr-semdesc="'+b.semester+'"><td>'+b.sydesc+' - '+ b.semester+'</td></tr>')
                                                      currently_enrolled = true;
                                                      var temp_enrollment_info = enrollment_record.filter(x=>x.syid == b.syid && x.semid == b.semid)
                                                      syid = b.syid
                                                      semid = b.semid
                                                      $('#enrollment_section').text(temp_enrollment_info[0].sectionname)
                                                      $('#enrollment_level').text(temp_enrollment_info[0].levelname)
                                                      $('#enrollment_status').text(temp_enrollment_info[0].description)
                                                      $('#enrollment_adviser').text(temp_enrollment_info[0].adviser)
                                                      var strandid = temp_enrollment_info[0].strandid;
                                                      var sectionid = temp_enrollment_info[0].sectionid;
                                                      var blockid = temp_enrollment_info[0].blockid;
                                                      levelid = temp_enrollment_info[0].levelid;
                                                      acadprogid = b.acadprogid
                                                      loadall(sectionid,blockid,levelid,strandid)
                                                      if(payment_setup.shssetup == 0){
                                                            // load_billing(syid,semid)
                                                            // prev_balance(syid,semid)
                                                            //load_ledger(syid,semid)
                                                      }else{
                                                            // load_billing(syid,1)
                                                            // prev_balance(syid,1)
                                                            //load_ledger(syid,semid)
                                                      }

                                                }else{
                                                      $('#enrollment_history_holder').append('<tr tr-acad="'+b.acadprogid+'"  class="enrollment_history" tr-sy="'+b.syid+'" tr-sem="'+b.semid+'" tr-sydesc="'+b.sydesc+'" tr-semdesc="'+b.semester+'"><td>'+b.sydesc+' - '+ b.semester+'</td></tr>')
                                                }
                                          }else{
                                                if(active_sy.id == b.syid){

                                                      $('#enrollment_history_holder').append('<tr tr-acad="'+b.acadprogid+'"  class="enrollment_history bg-primary" tr-sy="'+b.syid+'" tr-sydesc="'+b.sydesc+'"><td>'+b.sydesc+'</td></tr>')
                                                      currently_enrolled = true;
                                                      var temp_enrollment_info = enrollment_record.filter(x=>x.syid == b.syid)
                                                      syid = b.syid
                                                      $('#enrollment_section').text(temp_enrollment_info[0].sectionname)
                                                      $('#enrollment_level').text(temp_enrollment_info[0].levelname)
                                                      $('#enrollment_status').text(temp_enrollment_info[0].description)
                                                      $('#enrollment_adviser').text(temp_enrollment_info[0].adviser)
                                                      var sectionid = temp_enrollment_info[0].sectionid;
                                                      var blockid = temp_enrollment_info[0].blockid;
                                                      var strandid = temp_enrollment_info[0].strandid;
                                                      levelid = temp_enrollment_info[0].levelid;
                                                      acadprogid = b.acadprogid
                                                      loadall(sectionid,blockid,levelid,strandid)

                                                      if(payment_setup.shssetup == 0){
                                                            // load_billing(syid,semid)
                                                            // prev_balance(syid,semid)
                                                            //load_ledger(syid,semid)
                                                      }else{
                                                            // load_billing(syid,1)
                                                            // prev_balance(syid,1)
                                                            //load_ledger(syid,semid)
                                                      }
                                                      

                                                }else{
                                                      $('#enrollment_history_holder').append('<tr class="enrollment_history" tr-sy="'+b.syid+'" tr-acad="'+b.acadprogid+'" tr-sydesc="'+b.sydesc+'" tr-semdesc="'+b.semester+'"><td>'+b.sydesc+'</td></tr>')
                                                }
                                          }
                                        
                                    })

                              }
                        })

                  }

                  function load_enrollment_history(syid = null, semid = null){

                        var total_units = 0;

                        if(acadprogid != 5 && acadprogid != 6){
                              var temp_enrollment_info = enrollment_record.filter(x=>x.syid == syid)
                        }else{
                              var temp_enrollment_info = enrollment_record.filter(x=>x.syid == syid && x.semid == semid)
                        }
                        
                        

                        if(temp_enrollment_info.length > 0){

                              $('#enrollment_section').text(temp_enrollment_info[0].sectionname)
                              $('#enrollment_status').text(temp_enrollment_info[0].description)
                              $('#enrollment_adviser').text(temp_enrollment_info[0].adviser)
                              $('#enrollment_level').text(temp_enrollment_info[0].levelname)

                              var sectionid = temp_enrollment_info[0].sectionid;
                              var blockid = temp_enrollment_info[0].blockid;
                              var strandid = temp_enrollment_info[0].strandid;

                              levelid = temp_enrollment_info[0].levelid;
                              loadall(sectionid,blockid,levelid,strandid)

                              if(payment_setup.shssetup == 0){
                                    // load_billing(syid, semid)
                                    // prev_balance(syid,semid)
                                    //load_ledger(syid,semid)
                              }else{
                                    // load_billing(syid,1)
                                    // prev_balance(syid,1)
                                    //load_ledger(syid,semid)
                              }

                             
                        }

                  }

                  function loadall(sectionid,blockid,levelid,strandid) {
                        load_schedule(sectionid,blockid,levelid,strandid)
                        student_grades(sectionid,blockid,levelid)
                        // student_attendance()
                  }

                  function load_schedule(sectionid,blockid,levelid,strandid) {
                        $.ajax({
                              type:'GET',
                              url: '/student/enrollment/record/subjects',
                              data:{
                                    syid:syid,
                                    semid:semid,
                                    sectionid:sectionid,
                                    blockid:blockid,
                                    levelid:levelid,
                                    strandid:strandid
                              },
                              success:function(data) {
                                    $('#enrollment_history_schedule').append(data)
                              }
                        })
                  }

                  function student_grades(sectionid,blockid,levelid) {

                        if(acadprogid != 5 && acadprogid != 6){
                              var temp_enrollment_info = enrollment_record.filter(x=>x.syid == syid)
                        }else{
                              var temp_enrollment_info = enrollment_record.filter(x=>x.syid == syid && x.semid == semid)
                        }
                        
                        $.ajax({
                              type:'GET',
                              url: '/student/enrollment/record/grades',
                              data:{
                                    syid:temp_enrollment_info[0].syid,
                                    semid:temp_enrollment_info[0].semid,
                                    sectionid:temp_enrollment_info[0].sectionid,
                                    levelid:temp_enrollment_info[0].levelid,
                                    strandid:temp_enrollment_info[0].strandid
                              },
                              success:function(data) {
                                    $('#q1_fg').removeAttr('hidden')
                                    $('#q2_fg').removeAttr('hidden')
                                    $('#q3_fg').removeAttr('hidden')
                                    $('#q4_fg').removeAttr('hidden')
                                    $('#enrollment_history_grade').empty()
                                    $('#pr').attr('colspan',4)
                                    var sem1 = '';
                                    var sem2 = '';
                                    if(temp_enrollment_info[0].levelid == 14 || temp_enrollment_info[0].levelid == 15){
                                          $('#pr').attr('colspan',2)
                                          if(semid == 1){
                                                sem2 = 'hidden="hidden"'
                                                $('#q3_fg').attr('hidden','hidden')
                                                $('#q4_fg').attr('hidden','hidden')
                                          }else if(semid == 2){
                                                sem1 = 'hidden="hidden"'
                                                $('#q1_fg').attr('hidden','hidden')
                                                $('#q2_fg').attr('hidden','hidden')
                                          }
                                    }
                                     if(data.length > 0){
                                          var subjgrades = data.filter(x=>x.id != 'G1')
                                          $.each(subjgrades,function (a,b){
                                                var padding = b.subjCom != null ? 'pl-4':''
                                                var quarter1_grade = b.q1 != null ? b.q1 : ''
                                                var quarter2_grade = b.q2 != null ? b.q2 : ''
                                                var quarter3_grade = b.q3 != null ? b.q3 : ''
                                                var quarter4_grade = b.q4 != null ? b.q4 : ''
                                                var finalrating = b.finalrating != null ? b.finalrating : ''
                                                var actiontaken = b.actiontaken != null ? b.actiontaken : ''
                                                $('#enrollment_history_grade').append('<tr><td class="'+padding+'" >'+b.subjdesc+'</td><td class="text-center align-middle" '+sem1+'>'+quarter1_grade+'</td><td class="text-center align-middle" '+sem1+'>'+quarter2_grade+'</td></td><td class="text-center align-middle" '+sem2+'>'+quarter3_grade+'</td><td class="text-center align-middle" '+sem2+'>'+quarter4_grade+'</td><td class="text-center align-middle">'+finalrating+'</td><td class="text-center align-middle">'+actiontaken+'</td></tr>') 
                                          })
                                          
                                          var finalgrade = data.filter(x=>x.id == 'G1')
                                          var colspan = 5;
                                          $.each(finalgrade,function (a,b){
                                                var finalrating = b.finalrating != null ? b.finalrating : ''
                                                var actiontaken = b.actiontaken != null ? b.actiontaken : ''
                                                if(temp_enrollment_info[0].levelid == 14 || temp_enrollment_info[0].levelid == 15){
                                                     colspan = 3;
                                                }
                                                 $('#enrollment_history_grade').append('<tr><td  colspan="'+colspan+'" class="text-right">'+b.subjdesc+'</td><td class="text-center align-middle">'+finalrating+'</td><td class="text-center align-middle">'+actiontaken+'</td></tr>') 
                                          })
                                    }
                              }
                        })
                  }

                  function student_attendance(sectionid,blockid,levelid) {
                        $.ajax({
                              type:'GET',
                              url: '/student/enrollment/record/attendance',
                              data:{
                                    syid:syid,
                              },
                              success:function(data) {
                                    
                                    if(data.length > 0){
                                          var total_absent = 0;
                                          var total_present = 0;
                                          var total_days = 0;
                                          $.each(data,function (a,b){
                                                total_absent += parseInt(b.absent);
                                                total_present += parseInt(b.present);
                                                total_days += parseInt(b.days)
                                                $('#enrollment_history_attendance').append('<tr><td class="text-center">'+b.monthdesc+'</td><td  class="text-center">'+b.days+'</td><td  class="text-center">'+b.present+'</td><td  class="text-center">'+b.absent+'</td></tr>')
                                          })
                                          $('#enrollment_history_attendance').append('<tr><td  class="text-center">TOTAL</td><td  class="text-center">'+total_days+'</td><td  class="text-center">'+total_present+'</td><td  class="text-center">'+total_absent+'</td></tr>')
                                    }
                              }
                        })
                  }
                  
                  var acadprogid = null

                  $(document).on('click','.enrollment_history',function(){
                        syid = $(this).attr('tr-sy')
                        semid = $(this).attr('tr-sem')

                        acadprogid = $(this).attr('tr-acad')
                        $('.enrollment_history').removeClass('bg-primary')
                        $(this).addClass('bg-primary')
                        $('.sydisplay').text($(this).attr('tr-sydesc') + ' - ' +$(this).attr('tr-semdesc'))

                        load_enrollment_history(syid,semid)
                        $('#student_ledger').empty();
                        $('#enrollment_history_schedule').empty()
                        $('#enrollment_history_grade').empty()
                        $('#enrollment_history_attendance').empty()
                  })


                  // function prev_balance(syid,semid,balance){

                  //       if(semid == 2){
                  //             my_sem = semid - 1
                  //       }
                  //       else if(semid == 1){
                  //             my_sem = 2
                  //       }else{
                  //             my_sem = 1
                  //       }

                  //       my_sy = syid;

                  //       if(semid == 1){
                  //             my_sy -= 1  
                  //       }

                  //       $.ajax({
                  //             type:'GET',
                  //             url: '/parent/enrollment/previousbalance',
                  //             data:{
                  //                   syid:my_sy,
                  //                   semid:my_sem,
                  //             },
                  //             success:function(data) {
                  //                   if(acadprogid != 5 && acadprogid != 6){
                  //                         var temp_er = enrollment_record.filter(x=>x.syid == syid)
                  //                   }else{
                  //                         var temp_er = enrollment_record.filter(x=>x.syid == syid && x.semid == semid)
                  //                   }
                                    
                  //                   var prevbalance = 0

                  //                   if(data[0].prev_balance != null && enrollment_record.length != 0 && data[0].prev_balance != 0){

                  //                         pre_balance = parseFloat(data[0].prev_balance) + parseFloat( balance)

                  //                         pre_balance = parseFloat(data[0].prev_balance).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,")

                  //                         $('#student_ledger').append('<tr><td>'+temp_er[0].sydesc+'- '+temp_er[0].semester+' Balance </td><td></td><td></td><td class="text-right">&#8369; '+pre_balance+'</td></tr>')
                  //                   }

                  //                   if(data.length != 0){

                  //                         prevbalance = data[0].prev_balance

                  //                   }
                               
                  //                   load_ledger(syid, semid, prevbalance)
                  //             }
                  //       })
                  // }

                  // function load_ledger(syid,semid,prevbalance){
                  //       $('#student_ledger').empty();
                  //       $.ajax({
                  //             type:'GET',
                  //             url: '/parent/enrollment/ledger',
                  //             data:{
                  //                   syid:syid,
                  //                   semid:semid,
                  //             },
                  //             success:function(data) {

                  //                   var total_amount = 0;
                  //                   var total_payment = 0;
                  //                   var total_balance = 0
                  //                   var abalance
                  //                   var apayment
                  //                   var aamount
                  //                   if(prevbalance == null){
                  //                         var runbal = 0;
                  //                   }else{
                  //                         var runbal = parseFloat(prevbalance);
                  //                   }

                  //                   $.each(data,function(a,b){
                  //                         var ornum = ''
                  //                         if(b.ornum != ''){
                  //                               ornum = b.ornum
                  //                         }
                  //                         runbal += parseFloat( b.amount )
                  //                         runbal -=  parseFloat(b.payment)
                  //                         abalance = parseFloat(runbal).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,")
                  //                         apayment = parseFloat(b.payment).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,")
                  //                         aamount = parseFloat(b.amount).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,")
                  //                         $('#student_ledger').append('<tr><td >'+b.particulars+'</td><td class="text-right">&#8369; '+aamount+'</td><td class="text-right">&#8369; '+apayment+'</td><td class="text-right">'+abalance+'</td></tr>')

                  //                   })
                  //                   if(data.length != 0 || runbal != 0){
                  //                         runbal = parseFloat(runbal).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,")
                  //                         $('#student_ledger').append('<tr class="bg-info"><td class="text-right" colspan="3">REMAINING BALANCE</td><td class="text-right">&#8369; '+runbal+'</td></tr>')
                  //                   }
                  //             }
                  //       })
                  // }

                 

            })
      </script>

@endsection
