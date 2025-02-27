<script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{asset('plugins/datatables/jquery.dataTables.js') }}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>

<script>

      var teachers = []

      $(document).ready(function(){
            
            var dataArray = @json($teachers)
			
			
			console.log(dataArray)
		

            teachers = dataArray

            $(document).on('click','#teqs_button',function(){
                  $('#qurter_setup').modal();
            })
            
            $("#teacher_table").DataTable({
                  destroy: true,
                  data:dataArray,
                  columns: [
                              { "data": "search" },
                              { "data": "id" }
                        ],
                  columnDefs: [
                                {
                                    'targets': 0,
                                    'orderable': true, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                        var text = '<a class="mb-0">'+rowData.teacher+'</a><p class="text-muted mb-0" style="font-size:.7rem">'+rowData.tid+'</p>';
                                        $(td)[0].innerHTML =  text

                                    }
                               },
                      
                               {
                                    'targets': 1,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                          $(td)[0].innerHTML = '<button class="btn btn-primary btn-block vieweval btn-sm" data-id="'+rowData.id+'">View Evaluation</button>'
                                           $(td).addClass('text-center')
                                          $(td).addClass('align-middle')
                                    }
                               }
                        ]
                  })

            var label_text = $($('#teacher_table_wrapper')[0].children[0])[0].children[0]
            $(label_text)[0].innerHTML = '<button class="btn btn-primary btn-sm" id="evaluation_monitoring">Monitoring</button><button class="btn btn-primary btn-sm ml-2" id="evaluation_setup">Setup</button>'


            var selectedTeacher

            $(document).on('click','.vieweval',function(){

                  $('#teacher_evaluation_modal').modal();

                  selectedTeacher = $(this).attr('data-id');

                  $.ajax({
                        type:'GET',
                        url: '/teacherevaluation/schedule',
                        data:{
                              'teacherid': selectedTeacher,
                        },
                        success:function(data) {

                              $('#subject_assignment_table').empty()
                              $('#subject_assignment_table').append(data)
                              checkEvaluation()
                        
                        }
                  })

            })
            
            function checkEvaluation(){
                  $('.exporteval').attr('data-id',selectedTeacher)

                  var processlength = $('.dd').length;
                  var processcount = 0;

                  $('.dd').each(function(){
                        $.ajax({
                              type:'GET',
                              url: '/teacherevaluation/checkEvaluation',
                              data:{
                                  'data-id':$(this).attr('data-id'),
                                  'teacherid': selectedTeacher,
                                  'yearfilter' : $('#year_filter').val(),
                                  'syid': $('#filter_sy').val()
                              },
                              success:function(data) {

                                    var total = 0;
                                    var respondents = data[0].respondents
                              
                                    $.each(data[0].responses,function(a,b){
                                        
                                        
                                          total = 0;

                                          $.each(b.responses,function(c,d){

                                                $('.dt[data-dd="'+b.detail.id+'"][data-rt="'+d.rtid+'"]').text(d.ratingCount)

                                                total += parseInt ( $('.dth[data-rt="'+d.rtid+'"]')[0].innerHTML * d.ratingCount)


                                          })

                                          total = total / respondents;
                                          
                                          if(respondents == 0 ){
                                              total = 0
                                          }


                                          $('.dttotal[data-dd="'+b.detail.id+'"]').text(total)

                                    })
                              
                              }
                        })
                  })
            }
                        

      })
</script>