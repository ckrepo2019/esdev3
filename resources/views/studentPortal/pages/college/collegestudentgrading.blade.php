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
                                                                        <strong>SECTION</strong>
                                                                        <p class="text-muted" id="enrollment_section">--</p>
                                                                  </div>
                                                                  <div class="col-md-3">
                                                                        <strong>Enrollment Status</strong>
                                                                        <p class="text-muted" id="enrollment_status">--</p>
                                                                  </div>
                                                                  <div class="col-md-2">

                                                                  </div>
                                                                  <div class="col-md-2">
                                                                        <strong>Subjects</strong>
                                                                        <p class="text-muted" id="enrollment_subjects">--</p>
                                                                  </div>
                                                                  <div class="col-md-2">
                                                                        <strong>Units</strong>
                                                                        <p class="text-muted" id="enrollment_units">--</p>
                                                                  </div>
                                                           </div>
                                                      </div>
                                                      <hr>
                                                      <table class="table table-sm font-sm mb-0 mt-3">
                                                            <thead>
                                                                  <tr>
                                                                        <th class="bg-secondary">CLASS SCHEDULE <span class="sydisplay"></span></th>
                                                                  </tr>
                                                            </thead>
                                                     </table>
                                                      <div class="col-md-12 table-responsive p-0"  style="height: 300px;">
                                                            <table class="table table-sm font-sm" style="width: 1024px">
                                                                  <thead>
                                                                        {{-- <tr>
                                                                              <th colspan="6" class="bg-secondary"></th>
                                                                        </tr> --}}
                                                                        <tr>
                                                                              <th width="10%">SECTION</th>
                                                                              <th width="10%">CODE</th>
                                                                              <th width="35%">DESCRIPTION</th>
                                                                              <th width="5%" class="text-center">UNITS</th>
                                                                              <th width="25%">SCHEDULE</th>
                                                                              <th width="15%">INSTRUCTOR</th>
                                                                        </tr>
                                                                  </thead>
                                                                  <tbody id="enrollment_history_schedule">
                                                                       
                                                                  </tbody>
                                                            </table>
                                                      </div>
                                                     
                                                     <table class="table table-sm font-sm mb-0 mt-3">
                                                            <thead>
                                                                  <tr>
                                                                        <th class="bg-secondary" >GRADES <span class="sydisplay"></span></th>
                                                                  </tr>
                                                            </thead>
                                                     </table>
                                                      <div class="col-md-12 table-responsive p-0"  style="height: 300px;">
                                                            <table class="table table-sm font-sm table-head-fixed" style="width: 1024px">
                                                                  <thead>
                                                                        <tr>
                                                                              <th width="10%">CODE</th>
                                                                              <th width="40%">SUBJECT</th>
                                                                              <th width="10%" class="text-center">Prelim</th>
                                                                              <th width="10%" class="text-center">MidTerm</th>
                                                                              <th width="10%" class="text-center">SemiFinal</th>
                                                                              <th width="10%" class="text-center">Final</th>
                                                                              <th width="10%" class="text-center">Status</th>
                                                                        </tr>
                                                                  </thead>
                                                                  <tbody id="enrollment_history_grade">
                                                                    
                                                                  </tbody>
                                                            </table>
                                                      </div>
                                                      <table class="table table-sm font-sm mb-0 mt-3">
                                                            <thead>
                                                                  <tr>
                                                                        <th class="bg-secondary">BILLING ASSESSMENT <span class="sydisplay"></span></th>
                                                                  </tr>
                                                            </thead>
                                                      </table>
                                                      <div class="col-md-12 table-responsive p-0"  style="height: 300px;">
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
                                                                        <th class="bg-secondary">LEDGER <span class="sydisplay"></span></th>
                                                                  </tr>
                                                            </thead>
                                                      </table>
                                                      <div class="col-md-12 table-responsive p-0"  style="height: 300px;">
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
                                                      </div>
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

                  loadstudentenrollment()
                  var enrollment_record = []
                  
                  var active_sy = @json(DB::table('sy')->where('isactive',1)->select('id','sydesc')->first())

                  var active_sem =  @json(DB::table('semester')->where('isactive',1)->select('id','semester')->first())

                  var currently_enrolled = false
                  function loadstudentenrollment(){

                        $.ajax({
                              type:'GET',
                              url: '/student/college/enrollment/record',
                              success:function(data) {
                                    enrollment_record = data

                                    $.each(data,function(a,b){

                                          $('#enrollment_history_holder').append('<tr class="enrollment_history" tr-sy="'+b.syid+'" tr-sem="'+b.semid+'" tr-sydesc="'+b.sydesc+'" tr-semdesc="'+b.semester+'"><td>'+b.sydesc+' - '+ b.semester+'</td></tr>')

                                          if(active_sy.id == b.syid && active_sem.id == b.semid){
                                                currently_enrolled = true;
                                          }

                                    })

                                    if(!currently_enrolled){
                                      
                                          $('#enrollment_history_holder').append('<tr class="enrollment_history bg-primary" tr-sy="'+active_sy.id+'" tr-sem="'+active_sem.id+'" tr-sydesc="'+active_sy.sydesc+'" tr-semdesc="'+active_sem.semester+'"><td>'+active_sy.sydesc+' - '+ active_sem.semester+'</td></tr>')
                                          load_enrollment_history(active_sy.id,active_sem.id)
                                          load_billing(active_sy.id, active_sem.id)
                                          // load_ledger(active_sy.id,active_sem.id)
                                          prev_balance(active_sy.id,active_sem.id)
                                          $('.sydisplay').text(active_sy.sydesc + ' - ' +active_sem.semester)
                                    }

                                          
                              }
                        })

                  }

                  $(document).on('click','.enrollment_history',function(){

                        var syid = $(this).attr('tr-sy')
                        var semid = $(this).attr('tr-sem')
                        $('.enrollment_history').removeClass('bg-primary')
                        $(this).addClass('bg-primary')
                        $('.sydisplay').text($(this).attr('tr-sydesc') + ' - ' +$(this).attr('tr-semdesc'))
                        load_enrollment_history(syid,semid)
                        load_billing(syid, semid)
                        // load_ledger(syid,semid)
                        prev_balance(syid,semid)
                        $('#enrollment_history_schedule').empty()
                        $('#enrollment_history_grade').empty()
                        $('#student_billing').empty()
                        $('#student_ledger').empty()
                        
                  
                  })

                  function load_enrollment_history(syid = null, semid = null){
                        var total_units = 0;
                        $.ajax({
                              type:'GET',
                              url: '/student/college/enrollment/record/subject',
                              data:{
                                    syid:syid,
                                    semid:semid,
                              },
                              success:function(data) {

                                    if(data.length == 0){
                                          $('#enrollment_history_schedule').empty()
                                          $('#enrollment_history_grade').empty()
                                          $('#enrollment_section').text('NOT ASSIGNED')
                                          $('#enrollment_status').text('NOT ENROLLED')
                                          $('#enrollment_subjects').text(0)
                                          $('#enrollment_subjects').text(0)
                                          $('#enrollment_units').text(0)
                                    }
                                    else{
                                          
                                          var temp_enrollment_info = enrollment_record.filter(x=>x.syid == syid && x.semid == semid)
                                          $('#enrollment_section').text(temp_enrollment_info[0].sectionname)
                                          $('#enrollment_status').text(temp_enrollment_info[0].description)
                                          $('#enrollment_subjects').text(data.length)
                                          $.each(data,function(a,b){
                                                      var lastname = ''
                                                      var firstname = ''
                                                      var teacher = ''
                                                      var temp_stime = (new Date('2020-01-01T'+b.stime)).toLocaleString('en-US', { hour: 'numeric', minute: 'numeric', hour12: true })
                                                      var temp_etime = (new Date('2020-01-01T'+b.etime)).toLocaleString('en-US', { hour: 'numeric', minute: 'numeric', hour12: true })
                                                      if(b.lastname != null){
                                                            lastname = b.lastname
                                                      }
                                                      if(b.firstname != null){
                                                            firstname = b.firstname
                                                      }
                                                      if(b.lastname != null || b.lastname != null){
                                                            teacher = lastname+', '+firstname
                                                            teacher = teacher.substring(0, 15) + "..." 
                                                      }

                                                totalUnits = b.lecunits + b.labunits
                                                total_units += totalUnits
                                                $('#enrollment_history_schedule').append('<tr><td>'+b.sectionDesc+'</td><td>'+b.subjCode+'</td><td>'+b.subjDesc+'</td><td class="text-center">'+totalUnits+'</td><td>'+b.description +' / '+ temp_stime + ' - ' + temp_etime+'</td><td>'+teacher+'</td></tr>')


                                                $('#enrollment_history_grade').append('<tr class="tr-row" tr-id="'+b.id+'"><td>'+b.subjCode+'</td><td tr-id="'+b.id+'" >'+b.subjDesc+'</td><td tr-id="'+b.id+'" class="tr-prelim text-center"></td><td tr-id="'+b.id+'" class="tr-midterm text-center"></td><td tr-id="'+b.id+'" class="tr-prefigrade text-center"></td><td tr-id="'+b.id+'" class="tr-finalgrade text-center"></td><td tr-id="'+b.id+'" class="tr-remark text-center"></td></tr>')

                                          })

                                          load_grades(syid,semid)

                                          $('#enrollment_units').text(total_units)
                                    }
                              }
                        })

                  }

                  function load_grades(syid,semid){


                        $.ajax({
                              type:'GET',
                              url: '/student/college/grades',
                              data:{
                                    syid:syid,
                                    semid:semid,
                              },
                              success:function(data) {

                                    $.each(data,function(a,b){

                                          $('.tr-prelim[tr-id="'+b.id+'"]').text(b.prelimgrade)
                                          $('.tr-midterm[tr-id="'+b.id+'"]').text(b.midtermgrade)
                                          $('.tr-prefigrade[tr-id="'+b.id+'"]').text(b.prefigrade)
                                          $('.tr-finalgrade[tr-id="'+b.id+'"]').text(b.finalgrade)
                                          $('.tr-remark[tr-id="'+b.id+'"]').text(b.remarks)

                                          if(b.remarks == 'DROPPED' || b.remarks == 'INC' || b.remarks == 'FAILED'){
                                                $('.tr-row[tr-id="'+b.id+'"]').addClass('bg-danger')
                                          }
                                          if(b.remarks == 'PASSED'){
                                                $('.tr-row[tr-id="'+b.id+'"]').addClass('bg-success')
                                          }

                                    })
                              
                              }
                        })

                  }


                  function load_billing(syid,semid){
                        $.ajax({
                              type:'GET',
                              url: '/student/college/billing',
                              data:{
                                    syid:syid,
                                    semid:semid,
                              },
                              success:function(data) {

                                    var total_amount = 0;
                                    var total_payment = 0;
                                    var total_balance = 0
                                    var count = 0
                                    $.each(data,function(a,b){
                                          pariticular = ''
                                          if(b.duedate != null){
                                                if(count == 0){
                                                    pariticular = 'PRELIM'
                                                }
                                                else if(count == 1){
                                                     pariticular = 'MIDTERM'
                                                }
                                                else if(count == 2){
                                                     pariticular = 'FINAL'
                                                }else{
                                                      pariticular = b.particulars
                                                }
                                                count += 1
                                            }else{
                                                pariticular = b.particulars
                                            }


                                          $('#student_billing').append('<tr><td >'+pariticular+'</td><td class="text-right">&#8369;'+b.amount+'</td><td class="text-right">&#8369;'+b.amountpay+'</td><td class="text-right">&#8369;'+b.balance+'</td></tr>')

                                          total_amount += parseFloat(b.amount)
                                          total_payment += parseFloat(b.amountpay)
                                          total_balance += parseFloat(b.balance)
                                    })

                                    if(data.length != 0){
                                          total_amount = total_amount.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,")
                                          total_payment = total_payment.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,")
                                          total_balance = total_balance.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,")

                                          $('#student_billing').append('<tr class="bg-info"><td class="text-right">TOTAL</td><td class="text-right">&#8369;'+total_amount+'</td><td class="text-right">&#8369;'+total_payment+'</td><td class="text-right">&#8369;'+total_balance+'</td></tr>')
                                    }
                              }
                        })
                  }

                  function load_ledger(syid,semid,prevbalance){

                        $.ajax({
                              type:'GET',
                              url: '/student/college/ledger',
                              data:{
                                    syid:syid,
                                    semid:semid,
                              },
                              success:function(data) {

                                    var total_amount = 0;
                                    var total_payment = 0;
                                    var total_balance = 0
                                    var runbal = prevbalance != null ? parseFloat(prevbalance) : 0;
                                    var abalance
                                    var apayment
                                    var amount
                                    
                                     console.log(prevbalance)

                                    $.each(data,function(a,b){

                                          var ornum = ''

                                          if(b.ornum != ''){
                                                ornum = b.ornum
                                          }

                                          runbal += parseFloat(b.amount)
                                          runbal -=  parseFloat(b.payment)
                                          
                                          console.log(b.amount)

                                          abalance = parseFloat(runbal).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,")
                                          apayment = parseFloat(b.payment).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,")
                                          amount = parseFloat(b.amount).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,")

                                          $('#student_ledger').append('<tr><td >'+b.particulars+'</td><td class="text-right">&#8369; '+amount+'</td><td class="text-right">&#8369; '+apayment+'</td><td class="text-right">'+abalance+'</td></tr>')

                                    })

                                    if(data.length != 0 || runbal != 0){

                                          runbal = parseFloat(runbal).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,")

                                          $('#student_ledger').append('<tr class="bg-info"><td class="text-right" colspan="3">REMAINING BALANCE</td><td class="text-right">&#8369; '+runbal+'</td></tr>')

                                    }


                                   

                                    
                              }
                        })

                  }

                  function prev_balance(syid,semid,balance){

                        if(semid == 2){
                              my_sem = semid - 1
                        }

                        else if(semid == 1){
                              my_sem = 2
                        }

                        my_sy = syid;

                        if(semid == 1){
                              my_sy -= 1  
                        }
                 
                        console.log( my_sy + ' - '+my_sem)
                      
                        $.ajax({
                              type:'GET',
                              url: '/student/college/previousbalance',
                              data:{
                                    syid:my_sy,
                                    semid:my_sem,
                              },
                              success:function(data) {
                                    var temp_er = enrollment_record.filter(x=>x.syid == my_sy && x.semid == my_sem)
                                    var prevbalance = 0

                                    if(data[0].prev_balance != null && enrollment_record.length != 0 && data[0].prev_balance != 0){

                                          pre_balance = parseFloat(data[0].prev_balance) + parseFloat( balance)

                                          pre_balance = parseFloat(data[0].prev_balance).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,")

                                          $('#student_ledger').append('<tr><td>'+temp_er[0].sydesc+'- '+temp_er[0].semester+' Balance </td><td></td><td></td><td class="text-right">&#8369; '+pre_balance+'</td></tr>')
                                    }

                                    if(data.length != 0){

                                          prevbalance = data[0].prev_balance

                                    }

                                    load_ledger(syid, semid, prevbalance)

                              }
                        })
                  }
            })
      </script>

@endsection
