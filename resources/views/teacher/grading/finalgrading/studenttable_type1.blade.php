<style>

      .gradestable td, .ps, .ws{
            min-width:50px !important;
            text-align: center;
            cursor: pointer;
            vertical-align: middle !important;
      }
</style>

<table class="table table-sm table-head-fixed gradestable table-bordered" data-id="{{$gradestatus[0]->id}}" data-levelid="{{$gradestatus[0]->levelid}}">
      <thead>
            <tr>
                  <th>Student Name</th>
                  <th class="text-center">Final Grade</th>
            </tr>
      </thead>
      <tbody>
            @php
                  $male = 0;
                  $female = 0;
            @endphp
            @foreach ($gradesdetail as $item)
                  @if($male == 0 && $item->gender == 'MALE')
                        <th colspan="2" class="bg-secondary">MALE</th>
                        @php
                              $male = 1;
                        @endphp
                  @elseif($female == 0  && $item->gender == 'FEMALE')
                        <th colspan="2" class="bg-secondary">FEMALE</th>
                        @php
                              $female = 1;
                        @endphp
                  @endif
                  <tr>
                        <th>{{$item->lastname}} , {{$item->firstname}} </th>
                        @if($item->id != null)
                              <td  data-studid= "{{$item->studid}}"
                                    data-id= "{{$item->id}}">
                                    {{$item->qg}}
                              </td>

                        @else
                              <th class="text-danger text-center">
                                    NO GS
                              </th>

                        @endif
                  </tr>
            @endforeach
      </tbody>
</table>   


<script>
   
      $(document).ready(function (){
            var type = 1
            
            var gradestatus = @json($gradestatus)
			
			console.log(gradestatus)

            if(gradestatus.length > 0){
                  var status = 'NOT SUBMITTED'
                  $('#buton_submit_grades').attr('hidden','hidden')
                  if(gradestatus[0].submitted == 1  && gradestatus[0].status == 0){
                        status = 'SUBMITTED'
                        var canedit = false;
                  }
                  else if(gradestatus[0].submitted == 1 && gradestatus[0].status == 1){
                        status = 'APPROVED'
                        var canedit = false;
                  }
                  else if(gradestatus[0].submitted == 1 && gradestatus[0].status == 4){
                        status = 'POSTED'
                        var canedit = false;
                  }
                  else if(gradestatus[0].submitted == 0 && gradestatus[0].status == 3){
                        status = 'PENDING'
                        var canedit = true;
                        $('#buton_submit_grades').removeAttr('hidden')
                     
                  }
                  else if(gradestatus[0].submitted == 1 && gradestatus[0].status == 5){
                        status = 'SUBMITTED'
                        var canedit = false;
                  }else{
                        var canedit = true;
                        $('#buton_submit_grades').removeAttr('hidden')
                  }
                  $('#label_status').text(status)
            }else{
                  $('#label_status').text('NO GRADE STATUS')
                  $('#button_generate_gradedetail').removeAttr('hidden')
            }    


            var currentIndex 
            var start;
            var string;
            var bgcolor = null;
            var lastIndex = null;
            var studentgrades = @json($gradesdetail)

            if(studentgrades.filter(x=>x.id == null).length > 0){
                  $('#button_generate_gradedetail').removeAttr('hidden')
            }

            const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                  })

            $('.gradestable td').unbind().click(function(){
                 
                  if(canedit){
                        $('td[data-toggle="start"]').removeAttr('style')
                        $('td[data-toggle="start"]').removeAttr('data-toggle')
                        $(currentIndex).removeAttr('style')
                        $(this).attr('data-toggle','start')
                        start = $('td[data-toggle="start"]')[0];
                              start.focus();
                              start.style.backgroundColor = 'green';
                              start.style.color = 'white';
                        dotheneedful(this);
                        updatebg()
                  }else{
                       
                        Toast.fire({
                              type: 'error',
                              title: 'Grades are submitted'
                        })
                  }
            })


            function dotheneedful(sibling) {
                  if (sibling != null) {
                        currentIndex = sibling
                        start.focus();
                        start.style.backgroundColor = '';
                        start.style.color = '';
                        sibling.focus();
                        sibling.style = 'background-color: #6c757d !important';
                        sibling.style.color = 'white';
                        start = sibling;
                        string = $(currentIndex)[0].innerText
                  }
            }

            function updatebg(){
                  lastIndex = currentIndex;
                  bgcolor = null
            }

            document.onkeydown = checkKey;

            function checkKey(e) {

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
                        updatebg()
                  } else if (e.keyCode == '40' && currentIndex != undefined) {
                        var idx = start.cellIndex;
                        var nextrow = start.parentElement.nextElementSibling;
                        if (nextrow != null) {
                              var sibling = nextrow.cells[idx];
                              string = sibling.innerText;
                              dotheneedful(sibling);
                        }
                        updatebg()
                  } else if (e.keyCode == '37' && currentIndex != undefined) {
                        var sibling = start.previousElementSibling;
                        if($(sibling)[0].cellIndex != 0){
                              string = sibling.innerText;
                              dotheneedful(sibling);
                        }
                        updatebg()


                  } else if (e.keyCode == '39' && currentIndex != undefined) {
                        var sibling = start.nextElementSibling;
                        if($(sibling)[0].cellIndex != 0){
                              string = sibling.innerText;
                              dotheneedful(sibling);
                        }
                        updatebg()
                  }
                  else if( e.key == "Backspace" && currentIndex != undefined){
                        var id = $(currentIndex).attr('data-id')
                        var studid = $(currentIndex).attr('data-studid')
                        string = currentIndex.innerText
                        string = string.slice(0 , -1);
                        if(string.length == 0){
                              string = '0';
                              currentIndex.innerText = 0
                              store_grades_array.push({
                                    id:id,
                                    studid:studid,
                                    string:string
                              })
                              store_final_grade()
                        }else{
                              currentIndex.innerText = parseInt(string)
                              inputIndex = currentIndex
                              store_grades_array.push({
                                    id:id,
                                    studid:studid,
                                    string:string
                              })
                              store_final_grade()
                        }
                  }
                  else if ( e.key >= 0 && e.key <= 9 && currentIndex != undefined) {
                        var id = $(currentIndex).attr('data-id')
                        var studid = $(currentIndex).attr('data-studid')
                        if(string == 0){
                              string = ''
                        }
                        string += e.key;
                        if(string <= 100){
                              currentIndex.innerText = string
                              store_grades_array.push({
                                    id:id,
                                    studid:studid,
                                    string:string
                              })
                              store_final_grade()
                        }
                        
                  }
            }

            var acadprogid = @json($acadprogid)

            var store_grades_array = []
            var isprocessing = false

            function store_final_grade(){
                 
                  if(!isprocessing){
                        $('#p_count').text(store_grades_array.length )
                        if(store_grades_array.length == 0){
                              isprocessing = false
                              return false
                        }

                        var syid = $('#input_syid').val()
                        var semid = $('#input_semester').val()
                        var temp_store_grades = store_grades_array[0]
                 
                 
                        isprocessing = true
                        $.ajax({
                              type:'GET',
                              url:'/teacher/grading/final/store/grades',
                              data:{
                                    gdid:temp_store_grades.id,
                                    fg:temp_store_grades.string,
                                    studid:temp_store_grades.studid,
                              },
                              success:function(data) {
                                   
                                    store_grades_array = store_grades_array.filter(x=>x.id != temp_store_grades.id || x.string != temp_store_grades.string)
                                   
                                    isprocessing= false
                                    store_final_grade()
                              }
                        })
                       
                  }
            }

            
      })
</script>