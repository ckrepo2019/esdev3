
<div class="table-responsive" style="height: 500px;">

      <table class="table table-striped table-head-fixed" >
            <thead>
                  <tr style="font-size: 11px">
                        <th width="15%">SID ( {{count($students)}} )</th>
                        <th width="40%">STUDENT NAME</th>
                        <th width="15%" style="font-size:12px" class="text-center">Student Contact</th>
                        <th width="15%"  style="font-size:12px" class="text-center">Parent Contact</th>
                        <th width="15%"  style="font-size:12px" class="text-center">Evaluation</th>
                  </tr>
            </thead>
            <tbody>
                  @php
                        $studentCount  = 0;
                        $validStudentNumber = 0;
                        $validGuardianNumber = 0;
                  @endphp
                  @foreach ($students as $item)

                        

                        @php
                              $validContactParent = 0;
                              $validContactStudent = 0;
                              $validcontact = false;
                              $pcontact = null;

                              if($item->contactno != null && strlen ($item->contactno) == 11){

                                    $validContactStudent = 1;
                                    $validStudentNumber += 1;
                                    $validcontact = true;

                              }

                              $studentCount += 1;


                              if($item->isfathernum == 1 && ( $item->fcontactno != null && strlen ($item->fcontactno) == 11 ) ){

                                    $validGuardianNumber += 1;
                                    $validContactParent = 1;
                                    $pcontact = $item->fcontactno;
                                    $validcontact = true;
                              }

                              if($item->ismothernum == 1 && ( $item->mcontactno != null && strlen ($item->mcontactno) == 11 ) ){

                                    $validGuardianNumber += 1;
                                    $validContactParent = 1;
                                    $pcontact = $item->mcontactno;
                                    $validcontact = true;

                              }

                              if($item->isguardannum == 1 && ( $item->gcontactno != null && strlen ($item->gcontactno) == 11 ) ){

                                    $validGuardianNumber += 1;
                                    $validContactParent = 1;
                                    $pcontact = $item->gcontactno;
                                    $validcontact = true;

                              }

                        @endphp

                        @if($validcontact)
                              <tr  class="valid-contact stud-info"
                                    data-sid="{{$item->sid}}"
                                    data-scontact="{{$item->contactno}}"
                                    data-pcontact="{{$pcontact}}"
                                    data-firstname="{{$item->firstname}}"
                                    data-vsc = "{{$validContactStudent}}"
                                    data-vpc = "{{$validContactParent}}"
                                    data-acadprog = "{{$item->acadprogid}}"
                                    data-studid = "{{$item->id}}"
                              >
                        @else
                              <tr class="invalid-contact stud-info"
                                    data-firstname="{{$item->firstname}}"
                                    data-sid="{{$item->sid}}"
                                    data-studid = "{{$item->id}}"
                                    data-vsc = "{{$validContactStudent}}"
                                    data-vpc = "{{$validContactParent}}"
                              >
                        @endif
                              <td>{{$item->sid}}</td>
                              <td>{{$item->lastname.', '.$item->firstname}}</td>
                              <td class="text-center">{{$item->contactno}}</td>
                              <td class="text-center">
                                    @if($item->isfathernum == 1)
                                          {{$item->fcontactno}}
                                    @elseif($item->ismothernum == 1)
                                          {{$item->mcontactno}}
                                    @elseif($item->isguardannum == 1)
                                          {{$item->gcontactno}}
                                    @else
                                          NO CONTACT
                                    @endif
                              </td>
                              <td class="stud_eval" data-studid="{{$item->id}}"></td>
                        </tr>
                  @endforeach
            </tbody>
            <tfoot>
                  <tr>
                       
                  </tr>
            </tfoot>
      </table>
</div>




<script>
      $(document).ready(function(){

            $('#valid_parent_count').text($('.valid-contact').length)
            $('#invalid_parent_count').text($('.invalid-contact').length)


            $(document).on('click','#valid_parent_contact',function(){
                 
                  $('.valid-contact').removeClass('d-none')
                  $('.invalid-contact').addClass('d-none')
              
            })

            $(document).on('click','#invalid_parent_contact',function(){

                  $('.valid-contact').addClass('d-none')
                  $('.invalid-contact').removeClass('d-none')

            })

            $(document).on('click','#invalid_parent_contact',function(){

                  $('.valid-contact').addClass('d-none')
                  $('.invalid-contact').removeClass('d-none')

            })

      })
   
</script>
                                 
{{-- <table class="table border-0">
      <thead>
            <tr>
                  <th width="15%">{{$studentCount}}</th>
                  <th width="40%">&nbsp;</th>
                  <th width="20%">{{$validStudentNumber}}</th>
                  <th width="20%">{{$validGuardianNumber}}</th>
            </tr>
            <tr>

            </tr>
      </thead>
</table> --}}