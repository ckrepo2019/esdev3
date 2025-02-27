
<!-- DataTables -->
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
@extends('registrar.layouts.app')

@section('content')

    <style>
    </style>
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h3 class="text-warning" style="text-shadow: 1px 1px 1px #000000">
                <span><i class="fas fa-graduation-cap"></i> <b>Entrance Exam Results</b></span>
            </h3>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/home">Home</a></li>
              <li class="breadcrumb-item active">Entrance Exam Results</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
            <div class="col-sm-6">
                <div id="example1_wrapper" >
                        {{-- <div class="col-sm-6"> --}}
                            <table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
                                <thead class="bg-warning">
                                    <tr>
                                        <th>Code</th>
                                        <th>Type</th>
                                        <th>Student</th>
                                        <th>Score</th>
                                        <th></th>
                                        {{-- <th>Deductions</th>
                                        <th>Salary type</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(count($results)>0)
                                        @foreach($results as $student)
                                            <tr>
                                                <td>{{$student->studentinfo->queing_code}}</td>
                                                <td>{{strtoupper($student->studentinfo->studtype)}}</td>
                                                <td>  {{$student->studentinfo->first_name}} {{$student->studentinfo->middle_name}} {{$student->studentinfo->last_name}} {{$student->studentinfo->suffix}}
                                                </td>
                                                <td>{{$student->score}}</td>
                                                <td><button class="btn btn-primary btn-sm btn-block viewdetailsbutton" id="{{$student->studentinfo->id}}">View</button></td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        {{-- </div> --}}
                </div>
            </div>
            <div class="col-md-6 p-3" id="resultscontainer" style="height:700px; overflow-y:scroll"></div>
        </div>
        </div><!-- /.container-fluid -->
      </section>

    <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
    <!-- fullCalendar 2.2.5 -->
    <script src="{{asset('plugins/moment/moment.min.js')}}"></script>
    <script src="{{asset('plugins/fullcalendar/main.min.js')}}"></script>
    <script src="{{asset('plugins/fullcalendar-daygrid/main.min.js')}}"></script>
    <script src="{{asset('plugins/fullcalendar-timegrid/main.min.js')}}"></script>
    <script src="{{asset('plugins/fullcalendar-interaction/main.min.js')}}"></script>
    <script src="{{asset('plugins/fullcalendar-bootstrap/main.min.js')}}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <!-- DataTables -->
    <script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>
    <!-- ChartJS -->
    <script src="{{asset('plugins/chart.js/Chart.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
    <script>
        $("#example1").DataTable({
            pageLength : 10,
            lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Show All']]
        });
        $(document).on('click','.viewdetailsbutton', function(){
            $.ajax({
                url: '/entranceexamresults/{{Crypt::encrypt('viewresults')}}',
                type:"GET",
                dataType:"json",
                data:{
                    preregstudid: $(this).attr('id')
                },
                // headers: { 'X-CSRF-TOKEN': token },
                success:function(data) {
                    $('#resultscontainer').empty();
                    if((data[0].answerresults).length == 0){
                        $('#resultscontainer').append(
                            '<br>'+
                            '<br>'+
                            '<br>'+
                            '<br>'+
                            '<h2 class="table-avatar text-secondary">'+
                                '<center>No results!</center>'+
                            '</h2>'
                        );
                    }else{
                        $('#resultscontainer').append(
                            '<h2 class="table-avatar text-success">'+
                                '<center>'+data[0].studentinfo.first_name+' '+data[0].studentinfo.middle_name+' '+data[0].studentinfo.last_name+' '+data[0].studentinfo.suffix+'</center>'+
                            '</h2>'+
                            '<br>'+
                                '<div id="eachquestioncontainer">'+
                                '</div>'
                        );
                        var uniqueid = 1;
                        $.each(data[0].questions, function(key, questionvalue){
                            $('#eachquestioncontainer').append(
                                    '<div class="row">'+
                                        '<div class="col-md-10"><label>Question #'+uniqueid+'</label></div>'+
                                    '</div>'+
                                    '<textarea type="text" class="form-control bg-info" name="question" required disabled>'+questionvalue.question.question+'</textarea>'+
                                    '<br>'+
                                    '<label>Choices</label>'+
                                    
                                    '<div class="row" id="choicescontainer'+uniqueid+'">'+
                                    '</div>'+
                                    '<br>'+
                                    '<label>Answer</label>'+
                                    '<div class="row" id="correctanswercontainer'+uniqueid+'">'+
                                    '</div>'+
                                    
                                '<hr>'+
                                '<br>'
                            )
                            var correctanswer = 0;
                            var correctanswervalue = 0;
                            $.each(questionvalue.answers, function(key, answervalue){
                                console.log(answervalue)
                                if(answervalue.correctanswer == 1){
                                    correctanswer = answervalue.id;
                                    correctanswervalue = answervalue.answer;
                                    $('#choicescontainer'+uniqueid+'').append(

                                        '<div class="icheck-success d-inline">'+
                                            '<label for="'+answervalue.id+'">'+
                                                '<input type="text" name="" class="form-control form-control-sm mt-1" style="border: 1px solid #28a745; background-color: #c5f0cf " value="'+answervalue.answer+'" disabled>'+
                                            '</label>'+
                                        '</div>'
                                    );
                                }else{
                                    $('#choicescontainer'+uniqueid+'').append(

                                        '<div class="icheck-success d-inline">'+
                                            '<label for="'+answervalue.id+'">'+
                                                '<input type="text" name="" class="form-control form-control-sm mt-1" value="'+answervalue.answer+'" disabled>'+
                                            '</label>'+
                                        '</div>'
                                    );
                                }
                            });
                            $.each(data[0].answerresults, function(key, answerresultsvalue){
                                if(answerresultsvalue.questionid == questionvalue.question.id){
                                    if(answerresultsvalue.answerid == correctanswer){
                                        $('#correctanswercontainer'+uniqueid+'').append(
                                            '<div class="icheck-success d-inline">'+
                                                '<label for="'+correctanswer+'">'+
                                                    '<input type="text" name="" class="form-control form-control-sm mt-1" style="border: 1px solid #28a745; background-color: #c5f0cf "  value="'+correctanswervalue+'" disabled>'+
                                                '</label>'+
                                            '</div>'
                                        );
                                    }else{
                                        $('#correctanswercontainer'+uniqueid+'').append(
                                            '<div class="icheck-success d-inline">'+
                                                '<label for="">'+
                                                    '<input type="text" name="" class="form-control form-control-sm mt-1" style="border: 1px solid red;"  value="'+answerresultsvalue.answer+'" disabled>'+
                                                '</label>'+
                                            '</div>'
                                        );
                                    }
                                }

                            });

                            uniqueid+=1;
                        })
                    }
                    console.log(data);
                }
            })
        })
    </script>
@endsection
// {{-- 
// @foreach($question->answers as $answer)
//     @if($answer->correctanswer == 0)
//         '<div class="icheck-success d-inline">'+
//             '<input type="radio" name="correctanswer" value="{{$answer->id}}" id="{{$answer->id}}" disabled>'+
//             '<label for="{{$answer->id}}">'+
//                 '<input type="text" name="answers[]" class="form-control form-control-sm mt-1" value="{{$answer->answer}}" disabled>'+
//                 '<input type="hidden" name="choiceids[]" class="form-control form-control-sm mt-1" value="{{$answer->id}}" required disabled>'+
//             '</label>'+
//         '</div>'+
//     @else
//         '<div class="icheck-success d-inline">'+
//             '<input type="radio" name="correctanswer" value="{{$answer->id}}" id="{{$answer->id}}"  checked disabled>'+
//             '<label for="{{$answer->id}}">'+
//                 '<input type="text" name="answers[]" class="form-control form-control-sm mt-1" style="border: 1px solid #28a745; background-color: #c5f0cf " value="{{$answer->answer}}"  disabled>'+
//                 '<input type="hidden" name="choiceids[]" class="form-control form-control-sm mt-1" style="border: 1px solid #28a745; background-color: #c5f0cf " value="{{$answer->id}}" disabled >'+
//             '</label>'+
//         '</div>'+
//     @endif
// @endforeach --}}