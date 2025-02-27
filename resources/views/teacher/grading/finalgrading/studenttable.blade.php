<style>

      .gradestable td, .ps, .ws{
            min-width:50px !important;
            text-align: center;
            cursor: pointer;
            vertical-align: middle !important;
      }
</style>

<table class="table table-sm table-head-fixed gradestable">
      <thead>
            <tr>
                  <th>Student Name</th>
                  <th>Final Grade</th>
            </tr>
      </thead>
      <tbody>
            @php
                  $male = 0;
                  $female = 0;
            @endphp
            @foreach ($students as $item)
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
                        @if(count($item->gsdget) > 0)
                              <td 
                                    data-id= "{{isset($item->gsdget) ? isset($item->gsdget[0]->id) ? $item->gsdget[0]->id : '' : ''}}">
                                          
                                    {{isset($item->gsdget) ? isset($item->gsdget[0]->qgq1) ? number_format($item->gsdget[0]->qgq1) : '' : ''}}
                                    {{isset($item->gsdget) ? isset($item->gsdget[0]->qgq2) ? number_format($item->gsdget[0]->qgq2) : '' : ''}}
                                    {{isset($item->gsdget) ? isset($item->gsdget[0]->qgq3) ? number_format($item->gsdget[0]->qgq3) : '' : ''}}
                                    {{isset($item->gsdget) ? isset($item->gsdget[0]->qgq4) ? number_format($item->gsdget[0]->qgq4) : '' : ''}}
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


            var gradestatus = @json($checkStatus)

            
            if(gradestatus.length > 0){
              
                  var status = null
                  if($('#input_quarter').val() == 1){
                        status = gradestatus[0].q1status
                  }
                  else if($('#input_quarter').val() == 2){
                        status = gradestatus[0].q2status
                  }
                  else if($('#input_quarter').val() == 3){
                        status = gradestatus[0].q3status
                  }
                  else if($('#input_quarter').val() == 4){
                        status = gradestatus[0].q4status
                  }
                  if(status == 4){
                        var canedit = true;
                        status = 'PENDING'
                        $('#buton_submit_grades').removeAttr('hidden')
                  }else if(status == 1){
                        var canedit = false;
                        status = 'SUBMITTED'
                  }
                  else if(status == 2){
                        status = 'APPROVED'
                        var canedit = false;
                  }
                  else if(status == 3){
                        status = 'POSTED'
                        var canedit = false;
                  }else{
                        var canedit = true;
                        status = 'NOT SUBMITTED'
                        $('#buton_submit_grades').removeAttr('hidden')
                  }

                  console.log(canedit)
                  if($('#input_quarter').val() == 1){
                        $('#label_status').text(status)
                        $('#label_datesubmitted').text(gradestatus[0].q1datesubmitted)
                        $('#label_dateposted').text(gradestatus[0].q1dateposted)
                  }else if($('#input_quarter').val() == 2){
                        $('#label_status').text(status)
                        $('#label_datesubmitted').text(gradestatus[0].q2datesubmitted)
                        $('#label_dateposted').text(gradestatus[0].q2dateposted)
                  }
                  else if($('#input_quarter').val() == 3){
                        $('#label_status').text(status)
                        $('#label_datesubmitted').text(gradestatus[0].q3datesubmitted)
                        $('#label_dateposted').text(gradestatus[0].q3dateposted)
                  }
                  else if($('#input_quarter').val() == 4){
                        $('#label_status').text(status)
                        $('#label_datesubmitted').text(gradestatus[0].q4datesubmitted)
                        $('#label_dateposted').text(gradestatus[0].q4dateposted)
                  }
                  $('#button_generate_gradestatus').attr('hidden','hidden')
                  

            }else{
                  $('#button_generate_gradestatus').removeAttr('hidden')
                  $('#buton_submit_grades').attr('hidden','hidden')
            }

          
            var currentIndex 
            var start;
            var string;
            var bgcolor = null;
            var lastIndex = null;
            var studentgrades = @json($students)

            if(studentgrades.filter(x=>x.gsdget.length == 0).length > 0){
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
                        string = currentIndex.innerText
                        string = string.slice(0 , -1);
                        if(string.length == 0){
                              string = '0';
                              currentIndex.innerText = 0
                              store_grades_array.push({
                                    id:id,
                                    string:string
                              })
                              store_final_grade()
                        }else{
                              currentIndex.innerText = parseInt(string)
                              inputIndex = currentIndex
                              store_grades_array.push({
                                    id:id,
                                    string:string
                              })
                              store_final_grade()
                        }
                  }
                  else if ( e.key >= 0 && e.key <= 9 && currentIndex != undefined) {
                        var id = $(currentIndex).attr('data-id')
                        if(string == 0){
                              string = ''
                        }
                        string += e.key;
                        if(string <= 100){
                              currentIndex.innerText = string
                              store_grades_array.push({
                                    id:id,
                                    string:string
                              })
                              store_final_grade()
                        }
                        
                  }
            }

          


            var acadprogid = @json($acadprogid)

            var grading_system = @json($grading_system)


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
                                    id:temp_store_grades.id,
                                    grade:temp_store_grades.string,
                                    acadprogid:acadprogid,
                                    subjid:$('#input_subject').val(),
                                    quarter:$('#input_quarter').val(),
                              },
                              success:function(data) {
                                   
                                    store_grades_array = store_grades_array.splice(1);
                                   
                                    isprocessing= false
                                    store_final_grade()
                              }
                        })
                       
                  }
                  
            }

            var no_gs_students = []

            $(document).on('click','#button_generate_gradedetail',function(){
                  no_gs_students = studentgrades.filter(x=>x.gsdget.length == 0)
                  generate_student_gradesdetail()
            })


            $(document).on('click','#button_generate_gradestatus',function(){
                  $.ajax({
                        type:'GET',
                        url:'/reportcard/generate/status',
                        data:{
                              dse:$('#input_section').val(),
                              dsu:$('#input_subject').val(),
                              dlvl:$('#input_gradelevel').val(),
                        },
                        success:function(data) {
                              reload_final()
                        }
                  })
            })


            $(document).on('click','#buton_submit_grades',function(){
                  $.ajax({
                        type:'GET',
                        url: '/reportcard/grade/status/submit',
                        data:{
                              'dq': $('#input_quarter').val(),
                              'did': gradestatus[0].id,
                        },
                        success:function(data) {
                              reload_final()
                        }
                  })
            })

            function generate_student_gradesdetail(){

                  if(no_gs_students.length == 0){
                        Toast.fire({
                              type: 'success',
                              title: 'Generate Successfully!'
                        })
                        reload_final()
                        return false;
                  }

                  var gradeurl = null

                  if(acadprogid == 2){
                        gradeurl = '/gradestudent/preschool';
                  }
                  else if(acadprogid == 3){
                        gradeurl = '/reportcard/grades/gradeschool';
                  }
                  else if(acadprogid == 4){
                        gradeurl = '/reportcard/grades/highschool';
                  }
                  else if(acadprogid == 5){
                        gradeurl = '/reportcard/grades/seniorhigh';
                  }

                  var temp_student = no_gs_students[0]

                  $.ajax({
                        type:'GET',
                        url:gradeurl,
                        data:{
                              generate:'generate',
                              studid: temp_student.id,
                              section:$('#input_section').val(),
                              subject:$('#input_subject').val(),
                              quarter:$('#input_quarter').val(),
                              gradelevel:$('#input_gradelevel').val(),
                              gsid:grading_system[0].id
                        },
                        success:function(data) {
                              no_gs_students = no_gs_students.filter(x=>x.id != temp_student.id)
                              generate_student_gradesdetail()
                        }
                  })
            }

            function reload_final(){
                  var syid = $('#input_syid').val();
                  var semid = $('#input_semester').val();
                  if(acadprogid != 5){
                        semid = 1
                  }
                  $.ajax({
                        type:'GET',
                        url:'/teacher/grading/final/get/grades',
                        data:{
                              syid:syid,
                              semid:semid,
                              section:$('#input_section').val(),
                              subject:$('#input_subject').val(),
                              quarter:$('#input_quarter').val(),
                              gradelevel:$('#input_gradelevel').val(),
                              acadprogid:acadprogid
                        },
                        success:function(data) {
                              $('#grade_holder').empty()
                              $('#grade_holder').append(data)
                              $('#button_generate_gradedetail').attr('hidden','hidden')
                              $('#buton_submit_grades').attr('hidden','hidden')
                              $('#button_generate_gradestatus').attr('hidden','hidden')
                        }
                  })
            }

      })
</script>