@extends('principalsportal.layouts.app2')

@section('pagespecificscripts')
    <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <link rel="stylesheet" href="../../plugins/summernote/summernote-bs4.css">
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

@section('content')
<style>
.note-editor.note-frame .note-editing-area .note-editable {
    height: 285px;
}
</style>
<section class="content-header">
    <div class="container-fluid">
    <div class="row">
        <div class="col-sm-6">
        </div>
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/home">Home</a></li>
            <li class="breadcrumb-item active">Compose</li>
        </ol>
        </div>
    </div>
    </div>
</section>

<div class="row">
    <div class="col-md-12">
        <div class="main-card mb-3 card principalannouncement">
            <div class="card-header bg-info">
            <span class="" style="font-size: 16px"><b><i class="nav-icon far fa-circle"></i> COMPOSE ANNOUNCEMENT</b></span>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="">Title</label>
                    <input name="title"  id="title"  value="{{old('title')}}" class="form-control @error('title') is-invalid @enderror" placeholder="Title:">
                    <span class="invalid-feedback" role="alert">
                        <strong>Title is Required</strong>
                    </span>
                </div>
                <div class="form-group">
                    {{-- <textarea placeholder="Message Here.." rows="3" name="content" id="content" class="form-control @error('content') is-invalid @enderror">
                    </textarea> --}}
                    <label for="">Content</label>
                    <textarea rows="3" placeholder="Content:" name="content" id="content" class="form-control @error('content') is-invalid @enderror"></textarea>
                    <span class="invalid-feedback" role="alert">
                        <strong>Content is required</strong>
                    </span>
                </div>
            </div>
            <div class="card-footer">
                <div class="float-right">
                    <button class="btn btn-primary" id="composeSubmit"><i class="far fa-envelope"></i> Save</button>
                    <button class="btn btn-danger" id="cancelCompose"><i class="fas fa-ban"></i> Cancel</button>
                    
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="main-card mb-3 card principalannouncement">
            <div class="card-header bg-success">
                <h5 class="card-title">Message Type</h5>
            </div>
            <div class="card-body">
               
                <div class="form-group clearfix">
                    <div class="icheck-primary d-inline mr-3">
                        <input type="radio" id="radioPrimary1" value="1" name="announcetype" checked>
                        <label for="radioPrimary1">System Message</label>
                    </div>
                    <div class="icheck-primary d-inline">
                        <input type="radio" id="radioPrimary2" value="2" name="announcetype" >
                        <label for="radioPrimary2">Text Blast</label>
                    </div>
                </div>
                <div class="form-group clearfix text-danger" id="text_blast_info" hidden>
                    <p>You are only allowed to send 1 text message per day. 
                        Text messages is only limited to 320 characters.
                    </p>
                </div>
                <div class="form-group">
                    <label for="">Announcement Title</label>
                    <select name="announcementselect" id="announcementselect" class="form-control select2">
                        <option value="">Select Message</option>
                    </select>
                    <span class="invalid-feedback" role="alert">
                        <strong>Announce is required</strong>
                    </span>
                </div>
            </div>
        </div>
        <div class="main-card mb-3 card principalannouncement">
            <div class="card-header bg-primary">
                <h5 class="card-title">Receivers</h5>
            </div>
            <div class="card-body p-0">
              
                @if(Session::has('reciever'))
                    <span class="text-danger ml-3 mt-1" style="font-size: 80%;">
                        <strong>{{ Session::get('reciever')->reciever }}</strong>
                    </span>
                @endif

                <div class="position-relative form-group clearfix mb-0 p-3  ">
                        {{-- <div class="icheck-primary d-inline mr-4">
                            <input type="checkbox" name="all" id="all" {{ old('all')=="on" ? 'checked':'' }}>
                            <label  for="all">All</label>
                        </div>
                        <br> --}}
                        <div class="icheck-primary d-inline mr-4">
                            <input type="checkbox" name="teachers" id="teachers" 
                             {{ old('teachers')=="on" ? 'checked':'' }}
                             >
                            <label class="" for="teachers">Teachers</label>
                        </div>
                        <br>
                        <div class="icheck-primary d-inline">
                            <input type="checkbox" name="students" id="students"
                             {{ old('students')=="on" ? 'checked':'' }} 
                            >
                            <label class="" for="students">Students</label>
                        </div>
                        <br>
                        <div class="icheck-primary d-inline">
                            <input type="checkbox" name="parents" id="parents"
                             {{ old('parents')=="on" ? 'checked':'' }} 
                            >
                            <label class="" for="parents">Parents</label>
                        </div>
                    <input type="hidden" class="form-control @error('G') is-invalid @enderror" id="none">
                    @error('G')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="dropdown-divider mt-0"></div>
                <h5 class="card-title pl-3">Students Grade Levels</h5>
                <br>
       

                <div class="position-relative form-group clearfix mb-0 p-3 gradelevelholder">
                        {{-- @foreach(App\Models\Principal\LoadSections::gradelevel() as $item)
                            <div class="icheck-primary d-inline  mr-4">
                                <input type="checkbox" name="G[]" id="G{{$item->id}}"  value= "{{$item->id}}" class=" gradelevel"
                                @if(old('G'))
                                    {{ in_array($item->id,old('G')) ? 'checked': ''}}
                                @endif
                                >
                                <label style="width:40%" for="G{{$item->id}}">{{$item->levelname}}</label>
                            </div>
                        @endforeach --}}
                         
                        @foreach(Session::get('principalInfo') as $item)
                        
                            @foreach(App\Models\Principal\SPP_AcademicProg::getAllGradeLevelByAcadprog($item->acadid) as $item)
                                <div class="icheck-primary d-inline  mr-4">
                                    <input type="checkbox" name="G[]" id="G{{$item->id}}"  value= "{{$item->id}}" class=" gradelevel"
                                    @if(old('G'))
                                        {{ in_array($item->id,old('G')) ? 'checked': ''}}
                                    @endif
                                    >
                                    <label style="width:40%" for="G{{$item->id}}">{{$item->levelname}}</label>
                                </div>
                            @endforeach
                            
                        @endforeach
                        {{-- @if(Session::get('isPreSchoolPrinicpal'))
                            @foreach(App\Models\Principal\SPP_AcademicProg::getAllGradeLevelByAcadprog() as $item)
                                <div class="icheck-primary d-inline  mr-4">
                                    <input type="checkbox" name="G[]" id="G{{$item->id}}"  value= "{{$item->id}}" class=" gradelevel"
                                    @if(old('G'))
                                        {{ in_array($item->id,old('G')) ? 'checked': ''}}
                                    @endif
                                    >
                                    <label style="width:40%" for="G{{$item->id}}">{{$item->levelname}}</label>
                                </div>
                            @endforeach
                        @endif --}}
                </div>
                {{-- <div class="dropdown-divider"></div>
                <h5 class="card-title pl-3">Sections</h5>
                <br>
                <div class="position-relative form-group clearfix mb-0 p-3">
                    @foreach(App\Models\Principal\LoadSections::sections() as $item)
                        <div class="icheck-primary d-inline col-md-2">
                            <input type="checkbox" name="S[]" id="S{{$item->id}}"  value="{{$item->id}}" 
                            @if(old('S'))
                                {{ in_array($item->id,old('S')) ? 'checked': ''}}
                            @endif
                            class="section">
                            <label style="width:45%" for="S{{$item->id}}">{{$item->sectionname}}</label></div>
                    @endforeach
                </div> --}}
            </div>
            <div class="card-footer">
                <button class="btn btn-primary" id="filters">Filter</button>
            </div>
        </div>
        
    </div>
    <div class="col-md-8">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-info">
                        Students
                    </div>
                    <div class="card-body table-responsive p-0" style="height: 200px">
                        <table class="table table-sm table-head-fixed">
                            <thead>
                                <tr>
                                    <th>Student Name</th>
                                    <th>Phone #</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="studentlistholder">
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer row">
                        <button class="btn btn-primary col-md-3 send" id="sendStudent">Send Message</button>
                        <div class="col-md-4 text-center">Sent: <span id="studentSentCount"></span></div>
                        <div class="col-md-4 text-center">Unsent: <span id="studentNotSentCount"></span></div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header bg-info">
                        <div class="row">
                            <div class="col-md-6 h5">  Teachers</div>
                            <div class="col-md-6" id="select_all_teacher_holder"></div>
                        </div>
                      
                       
                    </div>
                    <div class="card-body table-responsive p-0" style="height: 200px">
                        <table class="table table-sm table-head-fixed">
                            <thead>
                                <tr>
                                    <th>Teacher Name</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="teacherlistholder">
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer row">
                        <button class="btn btn-primary col-md-3 send" id="sendTeacher">Send Message</button>
                        <div class="col-md-4 text-center">Sent: <span id="teacherSentCount"></span></div>
                        <div class="col-md-4 text-center">Unsent: <span id="teacherNotSentCount"></span></div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header bg-info">
                        Parents
                    </div>
                    <div class="card-body table-responsive p-0" style="height: 200px">
                        <table class="table table-sm table-head-fixed">
                            <thead>
                                <tr>
                                    <th>Parents Name</th>
                                    <th>Contact</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="parentslistholder">
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer row">
                        <button class="btn btn-primary col-md-3 send" id="sendParent">Send Message</button>
                        <div class="col-md-4 text-center">Sent: <span id="parentSentCount"></span></div>
                        <div class="col-md-4 text-center">Unsent: <span id="parentNotSentCount"></span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

       <script>
            $( document ).ready(function() {

                $('#all').change(function(){
                    if($(this).is(':checked')){
                        $('input:checkbox').prop("checked", true);
                    }
                    else{
                        $('input:checkbox').prop("checked", false);
                    }
                 
                    
                })
                $('#students').change(function(){
                    if($(this).is(':checked')){
                        $('.gradelevel').prop("checked", true);
                        $('.section').prop("checked", true);
                    }
                    else{
                        $('.gradelevel').prop("checked", true);
                        $('input[class=" gradelevel"]:checkbox').prop("checked", false);
                    }

                   
                })

                $('.gradelevel').change(function(){
                    $('.gradelevel, .section').each(function(){
                        if($(this).not(':checked')){
                            $('#students').prop("checked", false);
                            $('#all').prop("checked", false);
                        }
                    })
                })

                

              
            });
       </script>


<script src="../../plugins/summernote/summernote-bs4.min.js"></script>
<script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{asset('plugins/moment/moment.min.js')}}"></script>

<script>
     
    $(document).ready(function(){

        const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                  });

        var canSendStudent = true;
        var canSendParents = true;
        var canSendTeacher = true;
        var selectedLevel = [];
        var selectMessage = null;

        var todate = moment().format("YYYY/MM/DD");

        $('.select2').select2()

        $(document).on('click','input[name="announcetype"]',function(){

            $('#studentlistholder').empty();
            $('#teacherlistholder').empty();
            $('#parentslistholder').empty();
            $('input[type="checkbox"]').prop('checked',false)

            $('#studentSentCount')[0].innerText = 0
            $('#studentNotSentCount')[0].innerText = 0
            $('#teacherSentCount')[0].innerText = 0
            $('#teacherNotSentCount')[0].innerText = 0
            $('#parentSentCount')[0].innerText = 0
            $('#parentNotSentCount')[0].innerText = 0


            selectedLevel = [];
            selectMessage = null;

            canSendStudent = true;
            canSendParents = true;
            canSendTeacher = true;

            if($(this).val() == 1){
                
                $('#text_blast_info').attr('hidden','hidden')
            }
            else{
                $('#text_blast_info').removeAttr('hidden')
            }

        })

        

        $(document).on('click','.gradelevel',function(){

            if($(this).prop('checked') == true){

                selectedLevel.push($(this).val())

            }else{

                selectedLevel

                var index = selectedLevel.indexOf($(this).val());
                if (index !== -1) selectedLevel.splice(index, 1);

            }
    
        })

        $(document).on('click','#students',function(){

            selectedLevel = []

            if($(this).prop('checked') == true){

                $('.gradelevel').each(function(){

                    selectedLevel.push($(this).val())

                })

            }
            else{

                selectedLevel = []

            }

        })

        $(document).on('click','#filters',function(){

            var validFilter = true;

            if( $('#announcementselect').val() == ''){

                Toast.fire({
                        type: 'error',
                        title: 'No announcement selected!'
                    })

                validFilter= false;

                $('#announcementselect').addClass('is-invalid')

            }
            else{
                $('#announcementselect').removeClass('is-invalid')
            }

            if($('input[type="checkbox"]:checked').length == 0){

                validFilter= false;

                Toast.fire({
                        type: 'error',
                        title: 'No receiver selected!'
                    })
            }

          

            if(validFilter){

                if(selectedLevel.length > 0){

                    getStudents()

                }
                else{

                    $('#studentlistholder').empty()

                }

                if($('#teachers').prop('checked') == true){

                    var countUnchecked = 0;

                    $.ajax({
                        url: '/teacherdetails',
                        type: 'POST',
                        data: {
                            '_token': '{{ csrf_token() }}',
                            'announcement':'announcement',
                            'annID':$('#announcementselect').val()
                        },
                        success:function(data) {
                        
                            var teacherSentCount = 0
                            var teacherNotSentCount = 0

                            $('#teacherlistholder').empty()

                            $.each(data,function(a,b){
                                
                                var htmlstring = '<tr><td>'+b.lastname.toUpperCase()+', '+b.firstname.toUpperCase()+'</td>'
                                
                                if(b.status != null){

                                    var icheckString = '<div class="icheck-primary d-inline"><input class="teachercheckbox" type="checkbox" onclick="return false" checked><label></label></div>'
                                    teacherSentCount += 1;
                                    htmlstring += '<td class="sendTeacher" data-id="'+b.userid+'">'+icheckString+'</td>';

                                }
                                else{

                                    var icheckString = '<div class="icheck-primary d-inline"><input class="teachercheckbox" id="user'+b.userid+'" type="checkbox" data-id="'+b.userid+'"><label for="user'+b.userid+'"></label></div>'
                                    teacherNotSentCount += 1;
                                    htmlstring += '<td class="sendTeacher" data-id="'+b.userid+'" td-id="'+b.userid+'">'+icheckString+'</td>';

                                    countUnchecked += 1;

                                }

                                htmlstring += '</tr>'

                                $('#teacherlistholder').append(htmlstring)
                            })

                            $('#teacherSentCount')[0].innerText = teacherSentCount
                            $('#teacherNotSentCount')[0].innerText = teacherNotSentCount

                            if(countUnchecked > 0){

                                $('#select_all_teacher_holder')[0].innerHTML = '<button class="btn btn-default btn-sm float-right" id="select_all_teacher" data-value="1">Select All</button>'

                            }
                        }
                    })

                }
                else{
                    
                    $('#teacherlistholder').empty()

                }

                if($('#parents').prop('checked') == true){

                    getParents()

                   

                }
                else{
                    
                    $('#parentslistholder').empty()

                }

            }
            
        })

        $(document).on('click','input[type="checkbox"]',function(){

          

            if($(this).prop('checked') == true){

                $('td[td-id="'+$(this).attr('data-id')+'"]').attr('status',0)



            }
            else if($(this).prop('checked') == false){

                $('td[td-id="'+$(this).attr('data-id')+'"]').removeAttr('status')

            }
            

        })

        function getParents(){

            if($('input[name="announcetype"]:checked').val() == 1){

                var url = '/studentmasterlist?announcement=announcement';

            }else if($('input[name="announcetype"]:checked').val() == 2){


                var url = '/studentmasterlist?textblast=textblast';

            }

            $.ajax({
                url: url,
                type: 'GET',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'enrolled':'enrolled',
                    'parents':'parents',
                    'annID':$('#announcementselect').val(),
                    'gradelevel':selectedLevel
                },
                success:function(data) {

                    var parentSentCount = 0
                    var parentNotSentCount = 0

                    $('#parentslistholder').empty()

                    $.each(data,function(a,b){

                        var htmlstring = ''
                        var contact_number = 0

                        if( b.ismothernum == 1 && b.mcontactno != null ){

                            contact_number = b.mcontactno 

                        }
                        else if( b.isfathernum == 1 && b.fcontactno != null){

                            contact_number = b.fcontactno 

                        }
                        else if(b.isguardannum == 1 && b.gcontactno != null){

                            contact_number = b.gcontactno 

                        }
                        else if(b.mcontactno != null){

                            contact_number = b.mcontactno 

                        }
                        else if(b.fcontactno != null){

                            contact_number = b.fcontactno 

                        }
                        else if(b.gcontactno != null){

                            contact_number = b.gcontactno 

                        }else{

                            contact_number = b.contactno 
                        }
                        
                        
                        if(contact_number == null){

                            contact_number = 0

                        }

                      
                        if(b.parentName == undefined || b.parentName == '' ){

                            b.parentName = '<span class="text-danger">STUDENT</span> - '+b.lastname+', '+b.firstname
                        }

                        htmlstring = '<tr><td>'+b.parentName+'</td><td>'+contact_number+'</td>'
            
                        // b.status = null

                        if(b.status != null){

                            parentSentCount += 1;
                            htmlstring += '<td class="sendParent" status="1" data-id="'+b.parentUserid+'" data-number="'+contact_number+'">sent</td>';
                        
                        }
                        else{
                          
                          
                            parentNotSentCount += 1;
                            htmlstring += '<td class="sendParent" status="0" data-id="'+b.parentUserid+'" data-number="'+contact_number+'"></td>';
                        }

                        htmlstring += '</tr>'

                        $('#parentslistholder').append(htmlstring)
                    })

                    $('#parentSentCount')[0].innerText = parentSentCount
                    $('#parentNotSentCount')[0].innerText = parentNotSentCount
                  
                }
            })

        }


        function getStudents(){

            if($('input[name="announcetype"]:checked').val() == 1){

                var url = '/studentmasterlist?announcement=announcement';

            }else if($('input[name="announcetype"]:checked').val() == 2){

            
                var url = '/studentmasterlist?textblast=textblast';

            }

            $.ajax({
                url: url,
                type: 'GET',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'enrolled':'enrolled',
                    'annID':$('#announcementselect').val(),
                    'gradelevel':selectedLevel
                
                },
                success:function(data) {
                  
                    var studentSentCount = 0
                    var studentNotSentCount = 0

                    $('#studentlistholder').empty()

                    $.each(data,function(a,b){

                        var htmlstring = '<tr><td>'+b.lastname.toUpperCase()+', '+b.firstname.toUpperCase()+'</td><td>'+b.contactno+'</td>'

                        if(b.status != null || b.smsstatus != null){

                            studentSentCount += 1;
                            htmlstring += '<td class="sendStudent" status="1" data-id="'+b.userid+'" data-number="'+b.contactno+'">sent</td>';

                        }
                        else{
                            
                            studentNotSentCount += 1;
                            htmlstring += '<td class="sendStudent" status="0" data-id="'+b.userid+'" data-number="'+b.contactno+'"></td>';

                        }

                        htmlstring += '</tr>'

                        $('#studentlistholder').append(htmlstring)
                    })

                    $('#studentSentCount')[0].innerText = studentSentCount
                    $('#studentNotSentCount')[0].innerText = studentNotSentCount


                }
            })

        }

       


        $(document).on('click','.send',function(){

            var cansent = true

            if($('#content').val().length > parseInt(320) && $('input[name="announcetype"]:checked').val() == 2){

                Toast.fire({
                        type: 'error',
                        title: 'Content is to large!'
                    })

                cansent = false

            }

            // if($(this).attr('id') == 'sendStudent' && !canSendStudent && $('input[name="announcetype"]:checked').val() == 2){

            //     cansent = false;

            //     Toast.fire({
            //             type: 'error',
            //             title: 'Text Blast for students are already sent for this day!'
            //         })

            // }
            // else if($(this).attr('id') == 'sendParent' && !canSendParents && $('input[name="announcetype"]:checked').val() == 2){

            //     cansent = false;

            //     Toast.fire({
            //             type: 'error',
            //             title: 'Text Blast for parents are already sent for this day!'
            //         })

            // }
            // else if($('input[name="announcetype"]:checked').val() == 2 && $(this).attr('id') == 'sendTeacher'){
                
            //     cansent = false;

            //     Toast.fire({
            //             type: 'error',
            //             title: 'Text Blast for teachers is not yet available!'
            //         })


            // }

            if($('input[name="announcetype"]:checked').val() == 2 && $(this).attr('id') == 'sendTeacher'){
                
                cansent = false;

                Toast.fire({
                        type: 'error',
                        title: 'Text Blast for teachers is not yet available!'
                    })


            }

            if(cansent){

                $('td[class="'+$(this).attr('id')+'"][status="0"]').each(function(){


                    var thisValue = $(this)
                    var temp_send_status = true

                    if(thisValue.attr('class') == 'sendStudent' && ( $(this).attr('data-number') != 'null' && $(this).attr('data-number').length !=  11 )){

                        temp_send_status = false

                    }else if(thisValue.attr('class') == 'sendParent' && ( $(this).attr('data-number') != 'null' && $(this).attr('data-number').length !=  11 )){

                        temp_send_status = false

                    }
                   
                    if(temp_send_status){

                        $.ajax({
                            url: '/announcementdetail',
                            type: 'POST',
                            data: {
                                '_token': '{{ csrf_token() }}',
                                'annID':$('#announcementselect').val(),
                                'receiverid':$(this).attr('data-id'),
                                'type':$('input[name="announcetype"]:checked').val(),
                                'phonenumber':$(this).attr('data-number'),
                                'send':'send',
                                'recievertype':thisValue.attr('class')
                            },
                            success:function(data) {

                                if(thisValue.attr('class') == 'sendStudent'){

                                    thisValue.attr('status',1)

                                    $('#studentSentCount')[0].innerText = $('td[class="'+thisValue.attr('class')+'"][status="1"]').length
                                    $('#studentNotSentCount')[0].innerText = $('td[class="'+thisValue.attr('class')+'"][status="0"]').length

                                }
                                else if(thisValue.attr('class') == 'sendTeacher'){
                                
                                    thisValue.removeAttr('status')

                                    $('input[class="teachercheckbox"]:checked').attr('onclick','return false')


                                    $('#teacherSentCount')[0].innerText = $('input[class="teachercheckbox"]:checked').length

                                    $('#teacherNotSentCount')[0].innerText = $('input[class="teachercheckbox"]:not(:checked)').length

                                    if($('#select_all_teacher').attr('data-value') == 2){

                                        $('#select_all_teacher').remove()

                                    }

                                }
                                else if(thisValue.attr('class') == 'sendParent'){

                                    
                                    thisValue.attr('status',1)
                                    
                                    $('#parentSentCount')[0].innerText = $('td[class="'+thisValue.attr('class')+'"][status="1"]').length
                                    $('#parentNotSentCount')[0].innerText = $('td[class="'+thisValue.attr('class')+'"][status="0"]').length

                                }
                                
                            }
                        })

                    }

                })


                if($(this).attr('id') == 'sendStudent' && $('input[name="announcetype"]:checked').val() == 2){

                    canSendStudent = false;

                }
                else if($(this).attr('id') == 'sendParent' && $('input[name="announcetype"]:checked').val() == 2){

                    canSendParents = false;

                }
                else if($('input[name="announcetype"]:checked').val() == 2 && $(this).attr('id') == 'sendTeacher'){

                    cansent = false;

                }

            }

            
          


        })

        $(document).on('change','#announcementselect',function(){

           

            if($(this).val() == ''){

                $('#composeSubmit')[0].innerHTML= '<i class="far fa-envelope"></i> Save'
                selectMessage = null
                $('#title').val('')
                $('#content').val('')


            }
            else{

                $('#composeSubmit')[0].innerHTML= '<i class="far fa-envelope"></i> Update'

                selectMessage = $(this).val()

                $.ajax({
                    url: '/announcementdetail',
                    type: 'POST',
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'annID':$(this).val(),
                    },
                    success:function(data) {

                        $('#title').val(data[0].title)
                        $('#content').val(data[0].content)

                        console.log($('#content').val().length);

                    }
                })

            }
         

          

        })


        
        getcreateAnnouncement()

        $(document).on('click','#composeSubmit',function(){

            var validInput = true;

            if($('#title').val() == ''){

                $('#title').addClass('is-invalid')
                validInput = false

            }
            else{

                $('#title').removeClass('is-invalid')

            }

            if($('#content').val() == ''){

                $('#content').addClass('is-invalid')
                validInput = false

            }
            else{

                $('#content').removeClass('is-invalid')

            }


            console.log(selectMessage)
           

            if(validInput){

                $.ajax({
                    url: '/announcementdetail',
                    type: 'POST',
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'compose':'compose',
                        'title':$('#title').val(),
                        'content':$('#content').val(),
                        'messageID':selectMessage
                    },
                    success:function(data) {


                        if(selectMessage != null){

                            Toast.fire({
                                type: 'success',
                                title: 'Updated successfully!'
                            })


                            getcreateAnnouncement()

                        }
                        else if(selectMessage == null){

                            Toast.fire({
                                type: 'success',
                                title: 'Created successfully!'
                            })

                            selectMessage = null;
                        
                            $('#composeSubmit')[0].innerHTML = '<i class="far fa-envelope"></i> Save'
                            $('#title').val('')
                            $('#content').val('')
                            getcreateAnnouncement()



                        }

                       

                    }
                })
            }


        })


        $(document).on('click','#cancelCompose',function(){

            $('#composeSubmit')[0].innerHTML= '<i class="far fa-envelope"></i> Save'
            selectMessage = null
            $('#title').val('')
            $('#content').val('')
            $('#announcementselect').val('').change()
            

        })

        function getcreateAnnouncement(){

            $.ajax({
                url: '/announcementdetail',
                type: 'POST',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'announcements':'announcements',
                },
                success:function(data) {

                    $('#announcementselect').empty()
                    $('#announcementselect').append('<option value="">Select Message</option>')
                    
                    $.each(data,function(a,b){
                        $('#announcementselect').append('<option value="'+b.id+'">'+b.title+'</option>')
                    })


                    

                }
            })

        }

        $(document).on('click','#select_all_teacher',function(){

            console.log($(this).attr('helloworld'))

            var button_data_value = $(this).attr('data-value')

            if(button_data_value == 1){

                $(this).attr('data-value',2);

                $(this)[0].innerText = 'Unselect All'

                $('.teachercheckbox').each(function(){

                    if($(this).prop('checked') == false){

                        $(this).prop('checked',true)

                        $('td[td-id="'+$(this).attr('data-id')+'"]').attr('status',0)


                    }
                })

            }
            else if(button_data_value == 2){

                $(this).attr('data-value',1);
                $(this)[0].innerText = 'Select All'

                $('.teachercheckbox').each(function(){

                    if( $('td[td-id="'+$(this).attr('data-id')+'"]').attr('status') == 0){

                        $(this).prop('checked',false)

                        $('td[td-id="'+$(this).attr('data-id')+'"]').removeAttr('status')


                    }
                })

            }

        })



    })

</script>
        
@endsection




