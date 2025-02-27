<table class="table table-bordered" >
      <thead>
            <tr>
                
                  <th width="20%">Section</th>
                  <th width="20%">Subject</th>
                  <th class="text-center" width="10%">Quarter</th>
                  <th class="text-center"  width="10%">Status</th>
                  <th class="text-center"  width="20%">Date Submitted</th>
                  <th class="text-center"  width="20%">Date Posted</th>
            </tr>
      </thead>
      <tbody >
            @if($notgeneratedCount != 0)
                  <tr>
                        <td colspan="2"><button class="btn btn-primary btn-sm" id="generate_status"><i class="fas fa-sync-alt"></i> Generate Grade Status</button></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                  </tr>
            @endif
            @foreach ($allsubjects as $item)
                  @if($item->genstatus == 1)
                        <tr>
                              <td rowspan="4" class="align-middle" >{{$item->sectionname}}</td>
                              <td rowspan="4" class="align-middle">{{$item->subjcode}}</td>
                              <td class="text-center p-1 align-middle">Q1</td>
                              <td class="text-center p-1">
                                    @if($item->gstatus[0]->q1status == 1)
                                          <span class="badge badge-success btn-block">Submitted</span>
                                    @elseif($item->gstatus[0]->q1status == 2)
                                          <span class="badge badge-primary btn-block">Approved</span>
                                    @elseif($item->gstatus[0]->q1status == 3)
                                          <span class="badge badge-secondary btn-block">Posted</span>
                                    @elseif($item->gstatus[0]->q1status == 4)
                                          <span class="badge badge-warning btn-block">Pending</span>
                                          <button class="btn btn-sm btn-primary pg btn-block" d-q="1" d-id="{{$item->gstatus[0]->id}}"><i class="fas fa-share-square"></i> SUBMIT</button>
                                    @else
                                          <button class="btn btn-sm btn-primary pg btn-block" d-q="1" d-id="{{$item->gstatus[0]->id}}"><i class="fas fa-share-square"></i> SUBMIT</button>
                                    @endif
                              </td>

                              @if($item->gstatus[0]->q1datesubmitted != null)
                                    <td class="text-center p-1 align-middle">{{\Carbon\Carbon::create($item->gstatus[0]->q1datesubmitted)->isoFormat('MMM DD, YYYY hh:mm a')}}</td>
                              @else
                                    <td class="text-center p-1 align-middle"><span class="badge badge-danger">Not Submitted</span></td>
                              @endif

                              @if($item->gstatus[0]->q1dateposted != null)
                                    <td class="text-center p-1 align-middle">{{\Carbon\Carbon::create($item->gstatus[0]->q1dateposted)->isoFormat('MMM DD, YYYY hh:mm a')}}</td>
                              @else
                                    <td class="text-center p-1 align-middle"><span class="badge badge-danger">Not Posted</span></td>
                              @endif
                        </tr>
                        <tr>
                              <td class="text-center p-1 align-middle">Q2</td>
                              <td class="text-center p-1">
                                    @if($item->gstatus[0]->q2status == 1)

                                          <span class="badge badge-success btn-block">Submitted</span>

                                    @elseif($item->gstatus[0]->q2status == 2)

                                          <span class="badge badge-primary btn-block">Approved</span>

                                    @elseif($item->gstatus[0]->q2status == 3)

                                          <span class="badge badge-secondary btn-block">Posted</span>

                                    @elseif($item->gstatus[0]->q2status == 4)

                                          <span class="badge badge-warning btn-block">Pending</span>
                                          <button class="btn btn-sm btn-primary pg btn-block" d-q="2" d-id="{{$item->gstatus[0]->id}}"><i class="fas fa-share-square"></i> SUBMIT</button>

                                    @else

                                          @if($item->gstatus[0]->q1status == 3)
                                                <button class="btn btn-sm btn-primary pg btn-block" d-q="2" d-id="{{$item->gstatus[0]->id}}"><i class="fas fa-share-square"></i> SUBMIT</button>
                                          @endif

                                    @endif

                              </td>

                              @if($item->gstatus[0]->q2datesubmitted != null)
                                    <td class="text-center p-1 align-middle">{{\Carbon\Carbon::create($item->gstatus[0]->q2datesubmitted)->isoFormat('MMM DD, YYYY hh:mm a')}}</td>
                              @else
                                    <td class="text-center p-1 align-middle"><span class="badge badge-danger">Not Submitted</span></td>
                              @endif

                              @if($item->gstatus[0]->q2dateposted != null)
                                    <td class="text-center p-1 align-middle">{{\Carbon\Carbon::create($item->gstatus[0]->q2dateposted)->isoFormat('MMM DD, YYYY hh:mm a')}}</td>
                              @else
                                    <td class="text-center p-1 align-middle"><span class="badge badge-danger">Not Posted</span></td>
                              @endif
                        </tr>
                        <tr>
                              <td class="text-center p-1 align-middle">Q3</td>
                              <td class="text-center p-1">
                                    @if($item->gstatus[0]->q3status == 1)
                                          <span class="badge badge-success btn-block">Submitted</span>
                                    @elseif($item->gstatus[0]->q3status == 2)
                                          <span class="badge badge-primary btn-block">Approved</span>
                                    @elseif($item->gstatus[0]->q3status == 3)
                                          <span class="badge badge-secondary btn-block">Posted</span>
                                    @elseif($item->gstatus[0]->q3status == 4)
                                          <span class="badge badge-warning btn-block">Pending</span>
                                          <button class="btn btn-sm btn-primary pg btn-block" d-q="3" d-id="{{$item->gstatus[0]->id}}"><i class="fas fa-share-square"></i> SUBMIT</button>
                                    @else
                                          @if($item->gstatus[0]->q2status == 3)
                                                <button class="btn btn-sm btn-primary pg btn-block" d-q="3" d-id="{{$item->gstatus[0]->id}}"><i class="fas fa-share-square"></i> SUBMIT</button>
                                          @endif
                                    @endif
                              </td>
                              @if($item->gstatus[0]->q3datesubmitted != null)
                                    <td class="text-center p-1 align-middle">{{\Carbon\Carbon::create($item->gstatus[0]->q3datesubmitted)->isoFormat('MMM DD, YYYY hh:mm a')}}</td>
                              @else
                                    <td class="text-center p-1 align-middle"><span class="badge badge-danger">Not Submitted</span></td>
                              @endif

                              @if($item->gstatus[0]->q3dateposted != null)
                                    <td class="text-center p-1 align-middle">{{\Carbon\Carbon::create($item->gstatus[0]->q3dateposted)->isoFormat('MMM DD, YYYY hh:mm a')}}</td>
                              @else
                                    <td class="text-center p-1 align-middle"><span class="badge badge-danger">Not Posted</span></td>
                              @endif
                        </tr>
                        <tr>
                              <td class="text-center p-1 align-middle">Q4</td>
                              <td class="text-center p-1">
                                    @if($item->gstatus[0]->q4status == 1)
                                          <span class="badge badge-success btn-block">Submitted</span>
                                    @elseif($item->gstatus[0]->q4status == 2)
                                          <span class="badge badge-primary btn-block">Approved</span>
                                    @elseif($item->gstatus[0]->q4status == 3)
                                          <span class="badge badge-secondary btn-block">Posted</span>
                                    @elseif($item->gstatus[0]->q4status == 4)
                                          <span class="badge badge-warning btn-block">Pending</span>
                                          <button class="btn btn-sm btn-primary pg btn-block" d-q="3" d-id="{{$item->gstatus[0]->id}}"><i class="fas fa-share-square"></i> SUBMIT</button>
                                    @else
                                          @if($item->gstatus[0]->q3status == 3)
                                                <button class="btn btn-sm btn-primary pg btn-block" d-q="4" d-id="{{$item->gstatus[0]->id}}"><i class="fas fa-share-square"></i> SUBMIT</button>
                                          @endif
                                    @endif
                              </td>
                              @if($item->gstatus[0]->q4datesubmitted != null)
                                    <td class="text-center p-1 align-middle">{{\Carbon\Carbon::create($item->gstatus[0]->q4datesubmitted)->isoFormat('MMM DD, YYYY hh:mm a')}}</td>
                              @else
                                    <td class="text-center p-1 align-middle"><span class="badge badge-danger">Not Submitted</span></td>
                              @endif

                              @if($item->gstatus[0]->q4dateposted != null)
                                    <td class="text-center p-1 align-middle">{{\Carbon\Carbon::create($item->gstatus[0]->q4dateposted)->isoFormat('MMM DD, YYYY hh:mm a')}}</td>
                              @else
                                    <td class="text-center p-1 align-middle"><span class="badge badge-danger">Not Posted</span></td>
                              @endif
                        </tr>
                  @elseif($item->genstatus == 0)
                        <tr>
                              <td  class="align-middle dd" d-se="{{$item->id}}" d-su="{{$item->subjid}}" d-lvl="{{$item->levelid}}">{{$item->sectionname}}</td>
                              <td class="align-middle">{{$item->subjcode}}</td>
                              <td colspan="4" class="align-middle text-center">NOT GENERATED</td>
                        </tr>
                  @endif
            @endforeach
      </tbody>
</table>


<script>
      $(document).ready(function(){

      
            var firstIndex = 0;
            var lastIndex = 0;
            var checkedGrades =  01;
            var saveCount = 0;
            var unSavedCount = 0;
            var proccessCount =  0;
            var checkedCount = 0;

            $(document).on('click','#generate_status',function(){

                  firstIndex = 0;
                  lastIndex = 10;
                  checkedGrades =  parseInt( $('.dd').length / 10 )  + 1;
                  saveCount = 0;
                  unSavedCount = 0;
                  proccessCount =  0;
                  checkedCount = $('.dd').length;

                  if(checkedCount == 0){

                        Swal.fire({
                              type: 'info',
                              title: 'All !',
                        });

                  }else{
                        
                        $('#proccess_count_modal .modal-title').text('Processing ...')
                        $('#proccess_done').attr('hidden','hidden')
                        $('#proccess_count_modal').modal()
                        $('#save_count').text(saveCount)
                        $('#not_saved_count').text(unSavedCount)
                        $('#proccess_count').text(proccessCount)
                        $('#generate_status').attr('disabled','disabled')
                        generateGradeStatus()
                  
                  }

            })


            function loadGradeStatus(){

                  $.ajax({
                        type:'GET',
                        url: '/reportcard/grade/status',
                        success:function(data) {
                            
                              $('#grade_status_holder').empty()
                              $('#grade_status_holder').append(data)
                              
                        }
                  })
            }

            $('.pg').unbind().click(function(){

                  var dq = $(this).attr('d-q')
                  var did = $(this).attr('d-id')

                  $.ajax({
                        type:'GET',
                        url: '/reportcard/grade/status/submit',
                        data:{
                              'dq': dq,
                              'did': did,
                        },
                        success:function(data) {
                              
                              if(data == 0){

                                    Swal.fire({
                                          type: 'error',
                                          title: 'Something went wrong!',
                                    });

                              }else{

                                    Swal.fire({
                                          type: 'success',
                                          title: 'Grades Submitted Successfully!',
                                    });

                                    loadGradeStatus()

                              }
                              
                        }
                  })

            })


            function generateGradeStatus(){

                  var counter = 0;

                  $('.dd').slice(firstIndex,lastIndex).each(function(){

                        var dsu = $(this).attr('d-su')
                        var dse = $(this).attr('d-se')
                        var dlvl = $(this).attr('d-lvl')

                        $.ajax({
                              type:'GET',
                              url: '/reportcard/generate/status',
                              data:{
                                    'dsu': dsu,
                                    'dse': dse,
                                    'dlvl':dlvl
                              },
                              success:function(data) {
                                    
                                    if(data == 1){

                                    saveCount += 1
                                          $('#save_count').text(saveCount)

                                    }
                                    else if(data == 0){

                                          unSavedCount += 1
                                          $('#not_saved_count').text(unSavedCount)

                                    }

                                    counter += 1;
                                    proccessCount += 1;

                                    if(counter == 9 && checkedGrades != 0){

                                          firstIndex  += 10;
                                          lastIndex += 10;
                                          checkedGrades -= 1
                                          generateGradeStatus()
                                    }

                                    if(  checkedCount  == proccessCount){

                                          $('#proccess_count_modal .modal-title').text('Complete')
                                          $('#proccess_done').removeAttr('hidden')
                                          $('.checked_grade').removeClass('checked_grade')
                                          $('#generate_status').removeAttr('disabled')
                                          loadGradeStatus()

                                    }

                                    $('#proccess_count').text(proccessCount+' / '+checkedCount)
                              
                              }
                        })

                  })

            }

      })
</script>