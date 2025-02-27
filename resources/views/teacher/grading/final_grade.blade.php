
@php
      $male = 0;
      $female = 0;
@endphp

<table class="table table-bordered mb-0 table-sm pl-2 pr-2" style="width:100% !important">

      <thead>
            <tr>
                  <th width="50%">Student Name</th>
                  <th width="50%" class="text-center">Final Grade</th>
            </tr>
      </thead>
      <tbody>
            @foreach($final as $item)
                  @if($male == 0 &&  strtolower($item->gender) == "male")
                        <tr>
                              <td colspan="2" class="bg-secondary text-left">MALE</td>
                        </tr>
                        @php
                              $male = 1;
                        @endphp
                  @elseif($female == 0 && strtolower($item->gender) == 'female')
                        <tr>
                              <td colspan="2" class="bg-secondary text-left">FEMALE</td>
                        </tr>
                        @php
                        $female = 1;
                        @endphp
                  @endif
                  <tr>
                        @if($status->submitted == 1)
                              <th   class="text-left">{{$item->lastname}}, {{$item->firstname}}</th>
                              <th class="final_grade_item text-center">
                                    @if($item->qg == 0)
                                    
                                    @else
                                          {{$item->qg}}
                                    @endif
                              </th>
                        @else
                              <th   class="text-left">{{$item->lastname}}, {{$item->firstname}}</th>
                              <td data-studid="{{$item->studid}}" data-gdid="{{$item->gdid}}" class="final_grade_item">
                                    @if($item->qg == 0)
                                    
                                    @else
                                          {{$item->qg}}
                                    @endif
                              </td>

                        @endif
                  </tr>
            @endforeach
      </tbody>
</table>


<script>
      $(document).ready(function(){
            $.ajax({
                  type:'GET',
                  url:'/final/grades/type/update',
                  data:{
                        id:'{{$status->id}}',
                        type:2
                  },
            })    

            @if($status->submitted == 1)
                  $('#grade_type').attr('disabled','disabled')
            @else
            @endif

            @if($status->submitted == 1)

                  @if($status->status == 0)
                        $('#gradeRibbon').removeAttr('hidden');
                        $('#gradeRibbonMessage').text('Submitted');
                  @elseif($status->status == 2)
                        $('#gradeRibbon').removeAttr('hidden');
                        $('#gradeRibbonMessage').text('APPROVED');
                  @elseif($status->status == 3)
                        $('#gradeRibbon').removeAttr('hidden');
                        $('#gradeRibbonMessage').text('PENDING');
                  @elseif($status->status == 4)
                        $('#gradeRibbon').removeAttr('hidden');
                        $('#gradeRibbonMessage').text('POSTED');
                  @endif

            @else

                  @if($status->status == 3)
                        $('#gradeRibbon').removeAttr('hidden')
                        $('#gradeRibbonMessage').text('PENDING')
                  @else
                        $('#gradeRibbon').attr('hidden','hidden')
                        $('#gradeRibbonMessage').text('')
                  @endif

            @endif

      })

     
      
</script>


@if($status->submitted == 0 || $status->status == 3)
     
      <script>
            $(document).ready(function(){

                  var can_edit = true;
                  $('#divForm').empty();

                  @if($status->submitted == 0 || $status->status == 3)

                        $('#divForm').append(
                              '<button id="update_final" class="btn btn-success float-sm-left"><i class="fas fa-save mr-1"></i>UPDATE STUDENT FINAL</button><button name="btnSubmit" id="btnSubmit" class="btn btn-success float-sm-right btnSubmit" data-id='+'{{$status->id}}'+'><i class="fas fa-save mr-1" ></i>SUBMIT GRADES</button>'
                        );

                  @endif

                  console.log(can_edit)
                  
                  if(can_edit){

                        document.onkeydown = checkKey;

                        $(document).on('click','td',function(){
                              string = $(this).text();
                              currentIndex = this;

                              if($('#start').length > 0 ){

                                    dotheneedful(this);

                              }

                              $('td').removeAttr('style');
                              $('#start').removeAttr('id')
                              
                              $(this).attr('id','start')



                              var start = document.getElementById('start');
                                                start.focus();
                                                start.style.backgroundColor = 'green';
                                                start.style.color = 'white';
                        })

                  }
                

                  function dotheneedful(sibling) {
                        if (sibling != null) {
                              currentIndex = sibling
                              start.focus();
                              start.style.backgroundColor = '';
                              start.style.color = '';
                              sibling.focus();
                              sibling.style.backgroundColor = 'green';
                              sibling.style.color = 'white';
                              start = sibling;
                              $('#message').empty();
                              string = $(currentIndex)[0].innerText
                        }
                  }

                  $('#update_final').click(function(){
                        save_final_grade()
                  })

                 

                  // $('#submit_grade').click(function(){
                  //       $.ajax({
                  //             type:'GET',
                  //             url:'/final/grades/submit',
                  //             data:{
                  //                   id: '{{$status->id}}',
                  //             },
                  //             success:function(data){
                  //                   if(data[0].status == 1){
                  //                         Swal.fire({
                  //                               type: 'success',
                  //                               text: data[0].data
                  //                         });
                  //                         $('#submit_grade').remove()
                  //                         $('#update_final').remove()
                  //                         loadFinalGrades()
                  //                         can_edit = false;
                  //                   }
                  //                   else{
                  //                         Swal.fire({
                  //                               type: 'danger',
                  //                               text: "Somethin went wrong!"
                  //                         });
                  //                   }
                  //             }
                  //       })
                  // })

                  

                  function save_final_grade(){
                        $('.final_grade_item').each(function(a,b){
                              var gdid = $(this).attr('data-gdid')
                              var studid = $(this).attr('data-studid')
                              var fg = $(this)[0].innerText
                              if(fg != ""){
                                    $.ajax({
                                          type:'GET',
                                          url:'/final/grades/save',
                                          data:{
                                                gdid: gdid,
                                                studid:studid,
                                                fg:fg
                                          },
                                    })
                              }
                        })

                  }

                  function loadFinalGrades(){
                        var syid= $('#syid').val();
                        var gradelevelid= $('#gradelevelid').val();
                        var sectionid= $('#sectionid').val();
                        var subjectid= $('#subjectid').val();
                        $.ajax({
                              url: '/getfinalgrades',
                              type:"GET",
                              data:{
                                    syid: syid,
                                    gradelevelid:gradelevelid,
                                    sectionid: sectionid,
                                    subjectid :subjectid,
                                    quarter :'{{$status->quarter}}'
                              },
                              success:function(data) {
                                    $('#final_grade_container').removeAttr('hidden')
                                    $('#final_grade_container').empty()
                                    $('#final_grade_container').append(data)
                              }
                        })
                  }



                  

                  function checkKey(e) {

                        if(can_edit){

                              e = e || window.event;

                              if (e.keyCode == '38' && currentIndex != undefined)  {
                                    var idx = start.cellIndex;
                                    var nextrow = start.parentElement.previousElementSibling;
                                    $('#curText').text(string)

                                    if (nextrow != null) {
                                          var sibling = nextrow.cells[idx];
                                          string = sibling.innerText;
                                          dotheneedful(sibling);
                                    }

                              } else if (e.keyCode == '40' && currentIndex != undefined) {
                                    var idx = start.cellIndex;
                                    var nextrow = start.parentElement.nextElementSibling;
                                    $('#curText').text(string)

                                    if (nextrow != null) {
                                          var sibling = nextrow.cells[idx];
                                          string = sibling.innerText;
                                          dotheneedful(sibling);
                                    }

                              } 
                              else if( e.key == "Backspace" && currentIndex != undefined){
                                    
                                    string = currentIndex.innerText

                                    string = string.slice(0 , -1);

                                    if(string.length == 0){

                                          string = '0';
                                          currentIndex.innerText = 0

                                    }else{
                                          currentIndex.innerText = parseInt(string)
                                          inputIndex = currentIndex
                                    }

                                    $('#curText').text(string)

                              }

                              else if ( e.key >= 0 && e.key <= 9 && currentIndex != undefined) {
                              
                                    string += e.key;
                                    if(parseInt(string) > 100){

                                    }
                                    else{
                                          currentIndex.innerText = parseInt(string)
                                    }
                              

                              }
                                    
                        }

                  }
            })

      </script>
@else
      <script>
            $(document).ready(function(){
                  @if($status->submitted == 1)
                        @if($status->status == 0)
                              $('#gradeRibbon').removeAttr('hidden');
                              $('#gradeRibbonMessage').text('Submitted');
                        @elseif($status->status == 2)
                              $('#gradeRibbon').removeAttr('hidden');
                              $('#gradeRibbonMessage').text('APPROVED');
                        @elseif($status->status == 3)
                              $('#gradeRibbon').removeAttr('hidden');
                              $('#gradeRibbonMessage').text('PENDING');
                        @elseif($status->status == 4)
                              $('#gradeRibbon').removeAttr('hidden');
                              $('#gradeRibbonMessage').text('POSTED');
                        @endif
                  @else
                        @if($status->status == 3)
                              $('#gradeRibbon').removeAttr('hidden')
                              $('#gradeRibbonMessage').text('PENDING')
                        @else
                              $('#gradeRibbon').attr('hidden','hidden')
                              $('#gradeRibbonMessage').text('')
                        @endif
                  @endif
            })
      </script>
@endif