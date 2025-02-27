<!-- Font Awesome -->
<link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
<!-- DataTables -->
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
<!-- Theme style -->
<link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
<!-- Google Font: Source Sans Pro -->
<link rel="stylesheet" href="{{asset('plugins/fullcalendar/main.min.css')}}">
{{-- <link rel="stylesheet" href="{{asset('plugins/fullcalendar-interaction/main.min.css')}}"> --}}
<link rel="stylesheet" href="{{asset('plugins/fullcalendar-daygrid/main.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/fullcalendar-timegrid/main.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/fullcalendar-bootstrap/main.min.css')}}">
@extends('registrar.layouts.app')

@section('content')

    <style>
    </style>
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h3 class="text-warning" style="text-shadow: 1px 1px 1px #000000">
                <span><i class="fas fa-graduation-cap"></i> Entrance Exam Questions</span>
            </h3>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/home">Home</a></li>
              <li class="breadcrumb-item active">Entrance Exam Questions</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
          <!-- COLOR PALETTE -->
          
          <!-- START ALERTS AND CALLOUTS -->
  
          <div class="row">
            <div class="col-md-5" >
                
                <h6 class="text-success addquestions">
                    <i class="fa fa-plus "></i>
                    Add new question/s
                </h6>
                <form action="/addquestions" method="get">
                <div id="newquestionscontainer">
                </div>
                </form>
              <!-- /.card -->
            </div>
            <!-- /.col -->
  
            <div class="col-md-7">
              <div class="card card-default">
                <div class="card-header bg-info">
                  <h3 class="card-title">
                    <i class="fa fa-question-circle"></i>
                    Questions

                  </h3>
                  <br>
                  <small>If you wish to delete some choices, please leave it blank.</small>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <form action="/deletequestion" method="get" name="deletequestionform">
                        <input type="hidden" name="deletequestionid" />
                    </form>
                    @if(count($questions) > 0)
                    @php
                        $uniqueid = 0;   
                    @endphp
                        {{-- <div class="card"> --}}
                            @foreach ($questions as $question)
                                <form action="/editquestion" method="get" class="updateform">
                                    
                                    @if($question->withcorrectanswer == 0)
                                    <div class="eachquestioncontainer p-1" style="background-color: #e69199 ">
                                    @else
                                    <div class="eachquestioncontainer">
                                    @endif
                                        <div class="row">
                                            <div class="col-md-10"><label>Question</label></div>
                                            <div class="col-md-2 buttonscontainer"><button class="btn btn-sm btn-block btn-warning editquestion"><i class="fa fa-edit"></i></button></div>
                                        </div>
                                        <input type="hidden" name="questionid" value="{{$question->question->id}}" >
                                        <textarea type="text" class="form-control" name="question" required disabled>{{$question->question->question}}</textarea>
                                        <br>
                                        <label>Choices</label>
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                @foreach($question->answers as $answer)
                                                    @if($answer->correctanswer == 0)
                                                        <div class="icheck-success d-inline">
                                                            <input type="radio" name="correctanswer" value="{{$answer->id}}" id="{{$answer->id.''.$uniqueid}}" disabled>
                                                            <label for="{{$answer->id.''.$uniqueid}}">
                                                                <input type="text" name="answers[]" class="form-control form-control-sm mt-1" value="{{$answer->answer}}" disabled>
                                                                <input type="hidden" name="choiceids[]" class="form-control form-control-sm mt-1" value="{{$answer->id}}" required disabled>
                                                            </label>
                                                        </div>
                                                    @else
                                                        <div class="icheck-success d-inline">
                                                            <input type="radio" name="correctanswer" value="{{$answer->id}}" id="{{$answer->id.''.$uniqueid}}"  checked disabled>
                                                            <label for="{{$answer->id.''.$uniqueid}}">
                                                                <input type="text" name="answers[]" class="form-control form-control-sm mt-1" style="border: 1px solid #28a745; background-color: #c5f0cf " value="{{$answer->answer}}"  >
                                                                <input type="hidden" name="choiceids[]" class="form-control form-control-sm mt-1" style="border: 1px solid #28a745; background-color: #c5f0cf " value="{{$answer->id}}" required >
                                                            </label>
                                                        </div>
                                                    @endif
                                                    @php
                                                        $uniqueid+=1;   
                                                    @endphp
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <hr>
                                <br>
                            @endforeach
                        {{-- </div> --}}
                    @endif
                </div>
                <!-- /.card-body -->
              </div>
              <!-- /.card -->
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->
          <!-- END ALERTS AND CALLOUTS -->
          <!-- END TYPOGRAPHY -->
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
        var addnewcards = 0;
        $(document).on('click','.addquestions', function(){
            if(addnewcards == 0){
                $('#newquestionscontainer').append(
                    '<div class="card ">'+
                        '<button type="submit" class="btn btn-block btn-success savedeductionbutton">Save</button>'+
                    '</div>'
                );
                $('#newquestionscontainer').prepend(
                    '<div class="card">'+
                        '<div class="card-header bg-success">'+
                            '<div class="card-tools">'+
                                '<button type="button" class="btn btn-tool removecard" data-card-widget="remove">'+
                                    '<i class="fas fa-times"></i>'+
                                '</button>'+
                            '</div>'+
                        '</div>'+
                        '<div class="card-body">'+
                        
                            '<small><strong>Question</strong></small>'+
                            '<textarea type="text" name="question" class="form-control form-control-sm mb-2" placeholder="Question" required/></textarea>'+
                        
                            '<small><strong>Number of choices</strong></small>'+
                            '<input type="number" name="noofchoices" class="form-control form-control-sm mb-2" placeholder="" required/>'+

                            '<div class="choicescontainer"></div>'+
                        '</div>'+
                    '</div>'
                );
            }
            addnewcards+=1;
        });
        var appendedinputs = 0;
        $(document).on('input','input[name=noofchoices]', function(){
            
            $('.choicescontainer').append(
                    '<small><strong>Correct Answer</strong></small>'+
                    '<input type="text" name="correctanswer" class="form-control form-control-sm mb-2" placeholder="Correct answer" required/>'
                );
            for( i=appendedinputs; i<$(this).val(); i++){
                $('.choicescontainer').prepend(
                    '<input type="text" name="answers[]" class="form-control form-control-sm mb-2" required/>'
                );
                appendedinputs+=1;
            }
            if($(this).val() == 0){
                $('.choicescontainer').empty();
                appendedinputs = 0;
            }
            // if(appendedinputs < $(this).val()){
            // }
        })
        $(document).on('click','.removecard', function(){
            addnewcards-=1;
            if(addnewcards == 0){
                $('#newquestionscontainer').empty();
            }
        })
        var uniqueid = 0;
        $(document).on('click','.editquestion', function(){
            // console.log($(this).closest('.eachquestioncontainer').find('.buttonscontainer').append())
            $('.editquestion').attr('disabled', true);
            $(this).closest('.eachquestioncontainer').find('.buttonscontainer').append(
                '<button type="button" class="btn btn-sm btn-danger deletebutton"><i class="fa fa-times"></i></button> &nbsp; <button type="submit" class="btn btn-sm btn-primary id="'+uniqueid+'" updatebutton"><i class="fa fa-save"></i></button>'
            )
            $(this).closest('.eachquestioncontainer').find('input').attr('disabled',false)
            $(this).closest('.eachquestioncontainer').find('textarea').attr('disabled',false)
            $(this).remove();
            uniqueid+=1;
        });
        $(document).on('click','.deletebutton', function(){
//             $(deletequestionform
// deletequestionid)
            $('input[name=deletequestionid]').val($(this).closest('.eachquestioncontainer').find('input[name=questionid]').val())
            $('form[name=deletequestionform]').submit();
        })
    </script>
@endsection
