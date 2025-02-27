@extends('teacher.layouts.app')

@section('content')
<style>
    span{
        text-transform: uppercase;
    }
</style>
    <div class="row">
        <div class="col-md-12">
            <div class="main-card mb-3 card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-users"></i>
                        Students
                    </h3>
                </div>
                @if(isset($message))
                    <div class="card-body">
                        <div class="alert alert-warning alert-dismissible">
                            <h5><i class="icon fas fa-exclamation-triangle"></i>  {{$message}}</h5>
                            Possible reasons:
                           <ul>
                               <li>No assigned schedule.</li>
                           </ul>
                        </div>
                    </div>
                @endif
                @if(isset($gradeLevel))
                <div class="card-body">
                    @php
                        $countQuery= count($gradeLevel);
                    @endphp
                    <div class="alert alert-info alert-dismissible" id="noAssignedSched">
                        <h5><i class="icon fas fa-info"></i> Alert!</h5>
                        You are not yet assigned to any subjects.
                    </div>
                    
                    @if($countQuery==0)
                        <div class="alert alert-info alert-dismissible" id="noAssignedSched">
                            <h5><i class="icon fas fa-info"></i> Alert!</h5>
                            You are not yet assigned to a schedule.
                        </div>
                    @endif
                    <div id="filterPanel">
                        <input type="hidden" id="countQuery" value="{{$countQuery}}" hidden >
                        <select id="gradeLevel" class="form-control-sm col-md-3 " style="position:relative; display:inline-block" >
                            <option>Select Grade Level</option>
                            
                                @if($countQuery!=0)
                                    @foreach($gradeLevel as $level)
                                        <option value="{{$level->id}}" >{{$level->levelname}}</option>
                                    @endforeach
                                @endif
                        </select>&nbsp;&nbsp;
                        <select id="section" class="form-control-sm col-md-3 " style="position:relative; display:inline-block" >
                            <option>Select Section</option>
                        </select>&nbsp;&nbsp;
                        <select id="subject" class="form-control-sm col-md-3 " style="position:relative; display:inline-block" >
                            <option>Select Subject</option>
                        </select>
                    </div>
                    
                </div>
                @endif
            </div>
        </div>
    </div>
    <div class="row">
        <div class="card-body">
            <div class="alert alert-warning alert-dismissible" id="noAssignedStudents">
                <h5><i class="icon fas fa-info"></i> Alert!</h5>
                There are no available students under this subject.
            </div>
            <div class="row" id="studentView"></div>
        </div>
    </div>
{{-- </div> --}}
<script src="{{asset('assets/scripts/js.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/scripts/jquery.min.js')}}"></script>

<script>
var $ = jQuery;
$('#noAssignedSched').hide();
$('#noAssignedStudents').hide();
    function imgError(image) {
        image.onerror = "";
        image.src = "{{asset('assets/images/avatars/male.png')}}";
        return true;
    }
$(document).ready(function(){
    if($('#countQuery').val() == 0) {
        $('#noAssignedSched').show();
        $('#filterPanel').hide();
        $('#noAssignedStudents').hide();
    }
    else{
        $('#gradeLevel').on('change', function(){
            $('#studentView').empty();
            var gradeLevelId = $(this).val();
            $.ajax({
                url: '/students/'+gradeLevelId,
                type:"GET",
                dataType:"json",
                data:{
                    getStudents:'getSections'
                },
                success:function(data) {
                    $('#subject').empty();
                    $('#subject').append('<option value="0">Select Subject</option>');
                    $('#section').empty();
                    $('#section').append('<option value="0">Select Section</option>');
                    $.each(data, function(key, value){
                        $('#section').append('<option value="'+ value.id +'">' + value.sectionname + '</option>');
                    });
                },
            });
        });
        $('#section').on('change', function(){
            $('#studentView').empty();
            var sectionID = $(this).val();
            $.ajax({
                url: '/students/'+sectionID,
                type:"GET",
                dataType:"json",
                data:{
                    getStudents:'getSubjects'
                },
                success:function(data) {
                    $('#subject').empty();
                    $('#subject').append('<option value="0">Select Subject</option>');
                    $.each(data, function(key, value){
                        $('#subject').append('<option value="'+ value.id +'">' + value.subjdesc + '</option>');
                    });
                },
            });
        });
        $('#subject').on('change', function(){
            var subjectID = $(this).val();
            var sectionID = $('#section').val();
            $.ajax({
                url: '/students/'+subjectID,
                type:"GET",
                dataType:"json",
                data:{
                    getStudents:'getStudents',
                    sectionId:sectionID
                },
                success:function(data) {
                    console.log(data);
                    $('#studentView').empty();
                    if(data.length == 0){
                        $('#noAssignedStudents').show();
                    }
                    else{
                        $('#noAssignedStudents').hide();
                        $.each(data, function(key, value){
                            if(value.lrn != null){
                                var lrn = value.lrn;
                            }
                            else if(value.lrn == null){
                                var lrn = "";
                            }
                            if(value.firstname != null){
                                var firstname = value.firstname;
                            }
                            else if(value.firstname == null){
                                var firstname = "";
                            }
                            if(value.lastname != null){
                                var lastname = value.lastname;
                            }
                            else if(value.lastname == null){
                                var lastname = "";
                            }
                            if(value.middlename != null){
                                var middlename = value.middlename;
                            }
                            else if(value.middlename == null){
                                var middlename = "";
                            }
                            if(value.suffix != null){
                                var suffix = value.suffix;
                            }
                            else if(value.suffix == null){
                                var suffix = "";
                            }
                            if(value.gender != null){
                                var gender = value.gender;
                            }
                            else if(value.gender == null){
                                var gender = "";
                            }
                            if(value.dob != null){
                                var dob = value.dob;
                            }
                            else if(value.dob == null){
                                var dob = "";

                            }
                            if(value.contactno != null){
                                var contactno = value.contactno;
                            }
                            else if(value.contactno == null){
                                var contactno = "";
                            }
                            if(value.street != null){
                                var street = value.street;
                            }
                            else if(value.street == null){
                                var street = "";
                            }
                            if(value.barangay != null){
                                var barangay = value.barangay;
                            }
                            else if(value.barangay == null){
                                var barangay = "";
                            }
                            if(value.city != null){
                                var city = value.city;
                            }
                            else if(value.city == null){
                                var city = "";
                            }
                            if(value.province != null){
                                var province = value.province;
                            }
                            else if(value.province == null){
                                var province = "";
                            }
                            if(value.mothername != ','){
                                var mothername = value.mothername;
                            }
                            else if(value.mothername == ','){
                                var mothername = "";
                            }
                            if(value.mcontactno != null){
                                var mcontactno = value.mcontactno;
                            }
                            else if(value.mcontactno == null){
                                var mcontactno = "";
                            }
                            if(value.moccupation != null){
                                var moccupation = value.moccupation;
                            }
                            else if(value.moccupation == null){
                                var moccupation = "";
                            }
                            if(value.fathername != ','){
                                var fathername = value.fathername;
                            }
                            else if(value.fathername == ','){
                                var fathername = "";
                            }
                            if(value.fcontactno != null){
                                var fcontactno = value.fcontactno;
                            }
                            else if(value.fcontactno == null){
                                var fcontactno = "";
                            }
                            if(value.foccupation != null){
                                var foccupation = value.foccupation;
                            }
                            else if(value.foccupation == null){
                                var foccupation = "";
                            }
                            if(value.gcontactno != null){
                                var gcontactno = value.gcontactno;
                            }
                            else if(value.gcontactno == null){
                                var gcontactno = "";
                            }
                            if(value.bloodtype != null){
                                var bloodtype = value.bloodtype;
                            }
                            else if(value.bloodtype == null){
                                var bloodtype = "";
                            }
                            if(value.allergy != null){
                                var allergy = value.allergy;
                            }
                            else if(value.allergy == null){
                                var allergy = "";
                            }
                            if(value.guardianname != null){
                                var guardianname = value.guardianname;
                            }
                            else if(value.guardianname == null){
                                var guardianname = "";
                            }
                            if(value.guardianrelation != null){
                                var guardianrelation = value.guardianrelation;
                            }
                            else if(value.guardianrelation == null){
                                var guardianrelation = "";
                            }
                            if(value.picurl != null){
                                var picurl = value.picurl;
                            }
                            // else if(value.picurl == null){
                            //     if(value.gender == 'MALE' || value.gender == 'Male'){
                            //         var picurl = "{{asset('images/avatars/male.png')}}";
                            //     }
                            //     else if(value.gender == 'FEMALE' || value.gender == 'Female'){
                            //         var picurl = "{{asset('images/avatars/female.png')}}";
                            //     }
                            // }
                            // if(value.gender == "Female" || value.gender == "FEMALE"){
                                $('#studentView')
                                .append('<div class="col-md-4 col-sm-6 col-12"><a class="toModal" id="'+value.id+'" style="color:black" href="#" data-toggle="modal" data-target="#studentmodal" style="text-decoration:none;"><div class="info-box"><span class="info-box-icon"><img src="'+picurl+'" onerror="imgError(this);" width="70px"></span><div class="info-box-content"><p><span class="lrnNum" hidden>'+lrn+'</span><span class="suffixName" hidden>'+suffix+'</span><span class="lname">'+lastname+'</span>, <span class="fname">'+ firstname + '</span> <span class="mname">'+middlename +'</span><span class="gender" hidden>'+gender +'</span><span class="dob" hidden>'+dob +'</span><span class="contactnum" hidden>'+contactno +'</span><span class="street" hidden>'+street +'</span><span class="barangay" hidden>'+barangay +'</span><span class="city" hidden>'+city +'</span><span class="province" hidden>'+province +'</span><span class="bloodtype" hidden>'+bloodtype +'</span><span class="allergy" hidden>'+allergy +'</span><span class="mothername" hidden>'+mothername +'</span><span class="mothercontactnum" hidden>'+mcontactno +'</span><span class="motheroccupation" hidden>'+moccupation +'</span><span class="fathername" hidden>'+fathername +'</span><span class="fathercontactnum" hidden>'+fcontactno +'</span><span class="fatheroccupation" hidden>'+foccupation +'</span><span class="guardianname" hidden>'+guardianname +'</span><span class="guardiancontactnum" hidden>'+gcontactno +'</span><span class="guardianrelation" hidden>'+guardianrelation +'</span></p></div></div></a></div>');

                            // }
                            // else if(value.gender == "Male" || value.gender == "MALE"){
                                // $('#studentView')
                                // .append('<div class="col-md-4 col-sm-6 col-12"><a class="toModal" id="'+value.id+'" style="color:black" href="#" data-toggle="modal" data-target="#studentmodal" style="text-decoration:none;"><div class="info-box"><span class="info-box-icon"><img src="{{asset("assets/images/avatars/male.png")}}" width="70px"></span><div class="info-box-content"><p><span class="lrnNum" hidden>'+lrn+'</span><span class="suffixName" hidden>'+suffix+'</span><span class="lname">'+lastname+'</span>, <span class="fname">'+ firstname + '</span> <span class="mname">'+middlename +'</span><span class="gender" hidden>'+gender +'</span><span class="dob" hidden>'+dob +'</span><span class="contactnum" hidden>'+contactno +'</span><span class="street" hidden>'+street +'</span><span class="barangay" hidden>'+barangay +'</span><span class="city" hidden>'+city +'</span><span class="province" hidden>'+province +'</span><span class="bloodtype" hidden>'+bloodtype +'</span><span class="allergy" hidden>'+allergy +'</span><span class="mothername" hidden>'+mothername +'</span><span class="mothercontactnum" hidden>'+mcontactno +'</span><span class="motheroccupation" hidden>'+moccupation +'</span><span class="fathername" hidden>'+fathername +'</span><span class="fathercontactnum" hidden>'+fcontactno +'</span><span class="fatheroccupation" hidden>'+foccupation +'</span><span class="guardianname" hidden>'+guardianname +'</span><span class="guardiancontactnum" hidden>'+gcontactno +'</span><span class="guardianrelation" hidden>'+guardianrelation +'</span></p></div></div></a></div>');
                            // }
                        });
                    }
                }
            });
        }); 
    }
});

$(document).on('click','.toModal',function(){
    var modalID = '#'+$(this).attr('id');
    $('.modalLrn').text($(modalID + ' .lrnNum').text());
    $('.modalLastName').text($(modalID + ' .lname').text());
    $('.modalFirstName').text($(modalID + ' .fname').text());
    $('.modalMidName').text($(modalID + ' .mname').text());
    var guardianrelation = $(modalID + ' .guardianrelation').text();
    var getSuffix = $(modalID + ' .suffixName').text();
    if(getSuffix == 'null'){
        $('.modalSufName').text('');
    }else{
        $('.modalSufName').text($(modalID + ' .suffixName').text());
    }
    $('.modalDob').text($(modalID + ' .dob').text());
    $('.modalGender').text($(modalID + ' .gender').text());

    $('.modalContactNo').text($(modalID + ' .contactnum').text());
    $('.modalStreet').text($(modalID + ' .street').text()+', ');
    $('.modalBarangay').text($(modalID + ' .barangay').text()+', ');
    $('.modalCity').text($(modalID + ' .city').text()+', ');
    $('.modalProvince').text($(modalID + ' .province').text());
    $('.modalBloodType').text($(modalID + ' .bloodtype').text());
    $('.modalAllergies').text($(modalID + ' .allergy').text());
    $('.modalMotherName').text($(modalID + ' .mothername').text());
    $('.modalMotherContactNum').text($(modalID + ' .mothercontactnum').text());
    $('.modalMotherOccupation').text($(modalID + ' .motheroccupation').text());
    $('.modalFatherName').text($(modalID + ' .fathername').text());
    $('.modalFatherContactNum').text($(modalID + ' .fathercontactnum').text());
    $('.modalFatherOccupation').text($(modalID + ' .fatheroccupation').text());
    $('.modalGuardianName').text($(modalID + ' .guardianname').text());
    $('.modalGuardianContactNum').text($(modalID + ' .guardiancontactnum').text());
    if(guardianrelation == 'null'){
        $('.modalGuardianRelation').text('');
    }else{
        $('.modalGuardianRelation').text($(modalID + ' .guardianrelation').text());
    }
    $('#studentProfile-tab').attr('class','nav-link active');
    $('#studentProfile').attr('class','tab-pane fade show active');
    $('#parents-tab').attr('class','nav-link');
    $('#parents').attr('class','tab-pane fade');
})
</script>
<div class="modal fade studentmodal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="studentmodal">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Profile</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card-header p-0 border-bottom-0">
                    <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link" id="studentProfile-tab" data-toggle="pill" href="#studentProfile" role="tab" aria-controls="studentProfile" aria-selected="false">Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="parents-tab" data-toggle="pill" href="#parents" role="tab" aria-controls="parents" aria-selected="false">Parents/Guardian</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="custom-tabs-three-tabContent">
                        <div class="tab-pane fade" id="studentProfile" role="tabpanel" aria-labelledby="studentProfile-tab">
                            <p><span style="width: 30%">LRN:</span> <strong><span class="modalLrn"></span></strong>
                            <p>Full name: <strong><span class="modalLastName"></span>, <span class="modalFirstName"></span> <span class="modalMidName"></span> <span class="modalSufName"></span></strong></p> 
                            <p>Date of Birth: <strong><span class="modalDob"></span></strong></p>
                            <p>Gender: <strong><span class="modalGender"></span></strong></p>
                            <p>Contact No.: <strong><span class="modalContactNo"></span></strong></p>
                            <p>Home Address: <strong><span class="modalStreet"></span><span class="modalBarangay"></span><span class="modalCity"></span><span class="modalProvince"></span></strong></p>
                            <hr style="border:1px solid #ddd">
                            <p>Blood Type: <strong><span class="modalBloodType"></span></strong></p>
                            <p>Allergies: <strong><span class="modalAllergies"></span></strong></p>
                        </div>
                        <div class="tab-pane fade" id="parents" role="tabpanel" aria-labelledby="parents-tab">
                            <em>In case of emergency, contact:</em>
                            <hr style="border:1px solid #ddd">
                            <p>Mother's Name: <strong><span class="modalMotherName"></span></strong></p>
                            <p>Contact No.: <strong><span class="modalMotherContactNum"></span></strong></p>
                            <p>Occupation: <strong><span class="modalMotherOccupation"></span></strong></p>
                            <hr style="border:1px solid #ddd">
                            <p>Father's Name: <strong><span class="modalFatherName"></span></strong></p>
                            <p>Contact No.: <strong><span class="modalFatherContactNum"></span></strong></p>
                            <p>Occupation: <strong><span class="modalFatherOccupation"></span></strong></p>
                            <hr style="border:1px solid #ddd">
                            <p>Guardian's Name: <strong><span class="modalGuardianName"></span></strong></p>
                            <p>Contact No.: <strong><span class="modalGuardianContactNum"></span></strong></p>
                            <p>Relation: <strong><span class="modalGuardianRelation"></span></strong></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection
    