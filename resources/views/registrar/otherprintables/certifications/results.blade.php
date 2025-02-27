
<div class="card card-primary card-outline">
    <div class="card-body p-1">
        <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
            <li class="nav-item">
            <a class="nav-link" id="custom-content-below-home-tab" data-toggle="pill" href="#custom-content-below-home" role="tab" aria-controls="custom-content-below-home" aria-selected="false">Legend</a>
            </li>
            <li class="nav-item">
            <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#custom-content-below-profile" role="tab" aria-controls="custom-content-below-profile" aria-selected="true">Select a student</a>
            </li>
            <li class="nav-item">
            <a class="nav-link" id="custom-content-below-messages-tab" data-toggle="pill" href="#custom-content-below-messages" role="tab" aria-controls="custom-content-below-messages" aria-selected="false">Templates</a>
            </li>
        </ul>
        <div class="tab-content" id="custom-content-below-tabContent">
            <div class="tab-pane fade" id="custom-content-below-home" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
                <div class="row mb-2">
                    <div class="col-md-12">
                        <table class="table table-bordered table-sm">
                            <tr>
                                <td width="25%" >Student ID</td>
                                <td width="25%" style="font-weight: bold;">${sid}</td>
                                <td width="25%" >LRN</td>
                                <td width="25%"style="font-weight: bold;">${lrn}</td>
                            </tr>
                            <tr>
                                <td>Full Name (First Name first)</td>
                                <td style="font-weight: bold;">${fullname_first}</td>
                                <td>Objective Pronoun (Her/Him)</td>
                                <td style="font-weight: bold;">${objectpronoun}</td>
                            </tr>
                            <tr>
                                <td>Full Name (Last Name first)</td>
                                <td style="font-weight: bold;">${fullname_last}</td>
                                <td>Subjective Pronoun (Her/Him)</td>
                                <td style="font-weight: bold;">${subjectpronoun }</td>
                            </tr>
                            <tr>
                                <td>First Name</td>
                                <td style="font-weight: bold;">${firstname}</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Middle Name</td>
                                <td style="font-weight: bold;">${middlename}</td>
                                <td></td>
                                <td></td>
                            </tr>
							<tr>
                                <td>Middle Initial</td>
                                <td style="font-weight: bold;">${middleinitial}</td>
                                <td>Strand Code</td>
                                <td style="font-weight: bold;">${strandcode}</td>
                            </tr>
                            <tr>
                                <td>Last Name</td>
                                <td style="font-weight: bold;">${lastname}</td>
                                <td>Suffix</td>
                                <td style="font-weight: bold;">${suffix}</td>
                            </tr>
                            <tr>
                                <td>School Year</td>
                                <td style="font-weight: bold;">${schoolyear}</td>
                                <td>Semester</td>
                                <td style="font-weight: bold;">${semester}</td>
                            </tr>
                            <tr>
                                <td>Grade Level</td>
                                <td style="font-weight: bold;">${gradelevel}</td>
                                <td>Section</td>
                                <td style="font-weight: bold;">${section}</td>
                            </tr>
                            <tr>
                                <td>Track</td>
                                <td style="font-weight: bold;">${track}</td>
                                <td>Strand</td>
                                <td style="font-weight: bold;">${strand}</td>
                            </tr>
                            <tr>
                                <td>College</td>
                                <td style="font-weight: bold;">${college}</td>
                                <td>Course</td>
                                <td style="font-weight: bold;">${course}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td style="font-weight: bold;"></td>
                                <td>Purpose</td>
                                <td style="font-weight: bold;">${purpose}</td>
                            </tr>
                            <tr>
                                <td>Registrar</td>
                                <td style="font-weight: bold;">${registar}</td>
                                <td>Date of Graduation</td>
                                <td style="font-weight: bold;">${dateofgraduation}</td>
                            </tr>
                            <tr>
                                <td>Date Issued</td>
                                <td colspan="3"><b>${date1}<sup>${date2}</sup> day of ${datemonthyear}<b></td>
                            </tr>
                        </table>
                    </div>
                    <hr>
                    <div class="col-md-12">
                        <table class="table table-sm table-bordered">
                            <tr>
                                <td colspan="6">Secial Order</td>
                            <tr>
                            <tr>
                                <td width="20%">Number</td>
                                <td width="20%"><b>${so_num}</b></td>
                                <td width="15%">Series</td>
                                <td width="15%"><b>${so_series}</b></td>
                                <td width="15%">Date</td>
                                <td width="15%"><b>${so_date}</b></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-12">
                        <small><em>Does your report contains student's enrolled subjects?</em></small>
                        <table class="table table-bordered table-sm">
                            <thead>
                                <tr>
                                    <th>Subject Code</th>
                                    <th>Subject Title</th>
                                    <th>Units</th>
                                    <th>Grade</th>
                                    <th>Credits</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="6"><em>Note: Copy this variables and paste it on the first row of your data table!</em></td>
                                </tr>
                                <tr>
                                    <td>${subjectcode}</td>
                                    <td>${subjectdesc}</td>
                                    <td>${subjectunits}</td>
                                    <td>${subjectgrade}</td>
                                    <td>${subjectcredits}</td>
                                    <td>${subjectremarks}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="custom-content-below-profile" role="tabpanel" aria-labelledby="custom-content-below-profile-tab">
                <div class="row m-2">
                    <div class="col-md-8">
                        <label>Select a student:</label>
                        <select class="form-control select2" id="select-student" >
                            @if(count($students)>0)
                                @foreach($students as $student)
                                    <option value="{{$student->id}}" levelid="{{$student->levelid}}">{{$student->lastname}}, {{$student->firstname}} {{$student->middlename}} {{$student->middlename}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>Date Issued</label>
                        <input type="date" class="form-control" value="{{date('Y-m-d')}}" id="input-dateissued"/>
                    </div>
                </div>
                <div class="row m-2">
                    <div class="col-md-8">
                        <label>Purpose</label>
                        <input type="text" class="form-control" id="input-purpose"/>
                    </div>
                    <div class="col-md-4 align-self-end">
                        <label>Template</label>
                        <select class="form-control select2"  id="select-template">
                            @if(count($templates)>0)
                                @foreach($templates as $template)
                                    <option value="{{$template->id}}">{{$template->name}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
               
                <div class="row m-2 grade_holder" hidden>
                    <div class="col-md-5">
                        <label>Subject</label>
                        <select class="form-control select2"  id="select-subject">
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Quarter</label>
                        <select class="form-control select2"  id="select-quarter">
                            <option value="1">Quarter 1</option>
                            <option value="2">Quarter 2</option>
                            <option value="3">Quarter 3</option>
                            <option value="4">Quarter 4</option>
                        </select>
                    </div>
                </div>
                <hr class="">
                <div class="row m-2 " > 
                    <div class="col-md-4 dog_holder" hidden>
                        <label>Date Of Graduation</label>
                        <input type="date" class="form-control" value="{{date('Y-m-d')}}" id="input-dateofgraduation"/>
                    </div>
                    <div class="col-md-5">
                        <label>Registrar</label>
                        <select class="form-control select2"  id="select-registrar">
                           
                        </select>
                    </div>
                </div>
                <hr class="so_holder">
                <div class="row m-2 so_holder" hidden>
                    <div class="col-md-4">
                        <label for="">Special Order</label>
                    </div>
                </div>
                <div class="row m-2 so_holder">
                    <div class="col-md-3"Graduation>
                        <label>No.</label>
                        <input type="text" class="form-control" id="input-so_num"/>
                    </div>
                    <div class="col-md-3"Graduation>
                        <label>Series</label>
                        <input type="tex" class="form-control" id="input-so_series"/>
                    </div>
                    <div class="col-md-3"Graduation>
                        <label>Date</label>
                        <input type="date" class="form-control" value="{{date('Y-m-d')}}" id="input-so_date"/>
                    </div>
                </div>
                <div class="row m-2">
                    <div class="col-md-12 align-self-end text-right" id="container-export">
                        <button type="button" class="btn btn-default" id="btn-printpdf" hidden><i class="fas fa-file-pdf text-danger"></i> COE PDF</button>
                        <button type="button" class="btn btn-default"@if(count($templates)>0) id="btn-export"@else disabled @endif><i class="fa fa-download fa-sm"></i> Export Certificate</button>
                        @if(count($templates)==0)
                        <br/>
                        <small>Note: Please upload a template first!</small>
                        @endif
                    </div>
                </div>
              
            </div>
            <div class="tab-pane fade p-2" id="custom-content-below-messages" role="tabpanel" aria-labelledby="custom-content-below-messages-tab">

                <fieldset class="form-group border p-2">
                    <legend class="w-auto m-0">Add template</legend>
                    <form action="/printable/certificationsupload" method="post" enctype="multipart/form-data" id="form-submit">
                        @csrf
                        <div class="row m-2">
                            <div class="col-md-9 mb-2">
                                <label>Upload File</label>
                                <input type="file" id="input-file" name="file" accept="application/msword,.doc,.docx" class="form-control" required/>
                            </div>
                            <div class="col-md-3 text-right align-self-end mb-2">
                                <button type="submit" class="btn btn-default" id="btn-upload">Upload</button>
                            </div>
                        </div>
                        <div class="row m-2">
                            <div class="col-md-12">
                                <table class="table table-striped" id="table-templates">
                                    @if(count($templates)>0)
                                        @foreach($templates as $template)
                                            <tr>
                                                <td style="vertical-align: middle;" width="60%">{{$template->name}}.{{$template->extension}}</td>
                                                <td class="text-right" width="15%">
                                                    <button type="button" class="btn btn-default btn-sm btn-delete-template" data-tempid="{{$template->id}}"><i class="fa fa-trash"></i> Delete</button>
                                                </td>
                                                <td class="text-right" width="25%">
                                                    <button type="button" class="btn btn-success btn-sm btn-download-template" data-tempid="{{$template->id}}"><i class="fa fa-download"></i> Download Template</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </table>
                            </div>
                        </div>
                    </form>
                </fieldset>
            </div>
        </div>
    </div>
    
</div>

<script>


    if($('#select-certtype').val() == 'hd_so' || $('#select-certtype').val() == 'cert_g_so'){
        $('.so_holder').removeAttr('hidden')
    }else{
        $('.so_holder').attr('hidden','hidden')
    }

    if($('#select-certtype').val() == 'hd_g' 
        || $('#select-certtype').val() == 'hd_so'
        || $('#select-certtype').val() == 'hd_so' 
        ||  $('#select-certtype').val() == 'cert_g'
        ||  $('#select-certtype').val() == 'cert_g_so'
    ){
        $('.dog_holder').removeAttr('hidden')
    }else{
        $('.dog_holder').attr('hidden','hidden')
    }

    get_registrar()
    var students = @json($students);

    function get_registrar(){
       
        var search = {'value':'REGISTRAR'};
        var data = {'search':search}

        $.ajax({
            url: '/administrator/setup/accounts/list',
            type:'GET',
            data:data,
            success:function(data) {
                var registar =JSON.parse( data).data

                $.each(registar,function(a,b){
                    b.text = b.fullname
                })
             
                $('#select-registrar').select2({
                    data:registar,
                    theme: 'bootstrap4'
                })
            }
        })
    }

    get_subjects()

    function get_subjects(){

        var tempinfo = students.filter(x=>x.id == $('#select-student').val())

        if(tempinfo[0].acadprogid == 5){
            var semid = $('#select-semid').val()
        }else{
            var semid = null
        }

        $.ajax({
            url: '/superadmin/setup/subject/plot/list',
            type:'GET',
            data:{
                syid:$('#select-syid').val(),
                semid:semid,
                levelid:tempinfo[0].levelid,
                issp:true
            },
            success:function(data) {
              

                $.each(data,function(a,b){
                    b.text = b.subjdesc
                    b.id = b.subjid
                })
                $('#select-subject').empty()
                $('#select-subject').select2({
                    data:data,
                    theme: 'bootstrap4'
                })
               
            }
        })
    }

    $(document).on('change','#select-student',function(){
        get_subjects()
    })
       
</script>

<script>    
 

    
    $('.select2').select2({
      theme: 'bootstrap4'
    })
    $(sessionStorage.getItem('activetab')).addClass('active')
    $(sessionStorage.getItem('activetabpane')).addClass('show active')
    $('.nav-link').on('click', function(){
        sessionStorage.setItem('activetab', '#'+$(this).attr('id'));
        sessionStorage.setItem('activetabpane',$(this).attr('href'));
        console.log(sessionStorage.getItem('activetab'))
        console.log(sessionStorage.getItem('activetabpane'))
    })
    var deleted_printables = parseInt('{{count($templates)}}');
    $(document).ready(function(){

        
        $("#input-file").change(function(){
            var selectedFile = this.files[0];
            var idxDot = selectedFile.name.lastIndexOf(".") + 1;
            var extFile = selectedFile.name.substr(idxDot, selectedFile.name.length).toLowerCase();
            if (extFile == "docx" ) {
                var reader = new FileReader();
                reader.onload = function (e) {
                $uploadCrop.croppie('bind', {
                    url: e.target.result
                }).then(function(){
                    console.log('jQuery bind complete');
                });
                }
                reader.readAsDataURL(this.files[0]);
            } else {
                Swal.fire({
                    title: 'File type should be .docx',
                    type: 'error',
                    // showConfirmButton: false,
                    // timer: 1500
                })
                $(this).val('')
            }
        });

        $('#form-submit').submit( function( e ) {

            var inputs = new FormData(this)
            var syid = $('#select-syid').val();
            var semid = $('#select-semid').val();
            var certtype = $('#select-certtype').val();
            inputs.append('syid', syid);
            inputs.append('semid', semid);
            inputs.append('certtype', certtype);
            inputs.append('action', 'upload');
            // if($('#input-file').files.length == 0 ){
            //     toastr.warning('No Word document selected!')
            // }else{
                Swal.fire({
                    title: 'Uploading...',
                    onBeforeOpen: () => {
                        Swal.showLoading()
                    },
                    allowOutsideClick: false
                })
                // console.log(inputs)
                $.ajax({
                    url: '/printable/certificationsupload',
                    type:'POST',
                    data: inputs,
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success:function(data) {
                        // toastr.success('Uploaded successfully!')
                        deleted_printables+=1;
                        if(deleted_printables > 0)
                        {
                            $('#container-export').find('button').attr('id','btn-export')
                            $('#container-export').find('button').removeAttr('disabled')
                            $('#container-export').find('small').remove()
                            $('#container-export').find('br').remove()
                            $('#select-template').append('<option value="'+data.id+'">'+data.name+'</option>')
                            $('#table-templates').append(
                                `<tr>
                                    <td style="vertical-align: middle;" width="60%">`+data.name+`.`+data.extension+`</td>
                                    <td class="text-right" width="15%">
                                        <button type="button" class="btn btn-default btn-sm btn-delete-template" data-tempid="`+data.id+`"><i class="fa fa-trash"></i> Delete</button>
                                    </td>
                                    <td class="text-right" width="25%">
                                        <button type="button" class="btn btn-success btn-sm btn-download-template" data-tempid="`+data.id+`"><i class="fa fa-download"></i> Download Template</button>
                                    </td>
                                </tr> `
                            )
                            $('.btn-delete-template').on('click', function(){
                                $(this).prop('disabled',true)
                                var templateid = $(this).attr('data-tempid')
                                var thisrow = $(this).closest('tr');
                                // return false
                                
                                $.ajax({
                                    url: '/printable/certifications',
                                    type:'GET',
                                    data: {
                                        action: 'template_delete',
                                        tempid : templateid
                                    },
                                    dataType: 'json',
                                    success:function(data) {
                                        deleted_printables-=1;
                                        if(deleted_printables == 0)
                                        {
                                            $('#container-export').append(
                                                '<br/><small>Note: Please upload a template first!</small>'
                                            );
                                            $('#btn-export').prop('disabled',true)
                                            $('#btn-export').removeAttr('id')
                                        }
                                        $('#btn-generate').click();
                                        toastr.success('Deleted successfully!')
                                        thisrow.remove();
                                        $('#select-template option[value="'+templateid+'"]').remove()
                                    }
                                })
                            })
                            
                        }
                        $(".swal2-container").remove();
                        $('body').removeClass('swal2-shown')
                        $('body').removeClass('swal2-height-auto')
                    }
                })
                e.preventDefault();
            // }


        })
        $('.btn-delete-template').on('click', function(){
            $(this).prop('disabled',true)
            var templateid = $(this).attr('data-tempid')
            var thisrow = $(this).closest('tr');
            // return false
            
            $.ajax({
                url: '/printable/certifications',
                type:'GET',
                data: {
                    action: 'template_delete',
                    tempid : templateid
                },
                dataType: 'json',
                success:function(data) {
                    deleted_printables-=1;
                    if(deleted_printables == 0)
                    {
                        $('#container-export').append(
                            '<br/><small>Note: Please upload a template first!</small>'
                        );
                        $('#btn-export').prop('disabled',true)
                        $('#btn-export').removeAttr('id')
                    }
                    $('#btn-generate').click();
                    toastr.success('Deleted successfully!')
                    thisrow.remove();
                    $('#select-template option[value="'+templateid+'"]').remove()
                }
            })
        })

        $(document).on('click','.btn-download-template', function(){
            var templateid = $(this).attr('data-tempid')
            window.open("/printable/certifications/downloadtemplate?templateid="+templateid, "_blank");
        })

        $(document).on('click','#btn-export', function(){
            var syid = $('#select-syid').val();
            var semid = $('#select-semid').val();
            var certtype = $('#select-certtype').val();
            var studid = $('#select-student').val();
            var templateid = $('#select-template').val();
            var dateissued = $('#input-dateissued').val();
            var purpose = $('#input-purpose').val();
            var quarter = $('#select-quarter').val();
            var subject = $('#select-subject').val();
            var subject = $('#select-subject').val();
            var dateofgraduation = $('#input-dateofgraduation').val();
            var registrar = $('#select-registrar option:selected').text();

            var so_num = $('#input-so_num').val();
            var so_series = $('#input-so_series').val();
            var so_date = $('#input-so_date').val();

            window.open("/printable/certifications?action=export&syid="+syid+"&semid="+semid+"&certtype="+certtype+"&studid="+studid+"&templateid="+templateid+"&dateissued="+dateissued+"&purpose="+purpose+"&registrar="+registrar+"&subjid="+subject+"&quarter="+quarter+"&dateofgraduation="+dateofgraduation, "_blank");
        })

        $(document).on('click', '#btn-printpdf', function(){
            var syid = $('#select-syid').val();
            var semid = $('#select-semid').val();
            var certtype = $('#select-certtype').val();
            var levelid = $('#select-student option:selected').attr('levelid'); // Corrected to get attribute from selected option
            var studid = $('#select-student').val();
            var dateissued = $('#input-dateissued').val();
            var template = $('#select-template').val();

            if (levelid == 16 || levelid < 13) {
                template = 'jhs';
            } else if(levelid >= 14 && levelid <= 15){
                template = 'shs';
            } else {
                template = 'college';
            }

            if (template == 'jhs') {
                window.open(`/printable/certification/generate/?export=pdf&template=${template}&syid=${syid}&studid=${studid}&givendate=${dateissued}`, "_blank");
            } else {
                window.open(`/printable/certification/generate/?export=pdf&template=${template}&syid=${syid}&semid=${semid}&studid=${studid}&givendate=${dateissued}`, "_blank");
            }
        })

                
    })


</script>