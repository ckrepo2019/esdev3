
@extends('registrar.layouts.app')
@section('content')
<!-- DataTables -->
    <!-- DataTables -->
    <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
    <!-- jQuery -->
    <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
    <!-- DataTables  & Plugins -->
    <script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
    <script src="{{asset('plugins/jszip/jszip.min.js')}}"></script>
    <script src="{{asset('plugins/pdfmake/pdfmake.min.js')}}"></script>
    <script src="{{asset('plugins/pdfmake/vfs_fonts.js')}}"></script>
    <style>
        
        .select2-container .select2-selection--single {
            height: 40px !important;
        }
        
        #modal-adddata .modal-dialog{
            max-width: 800px
        }
        .alert {
    position: relative;
    padding: 0.75rem 1.25rem;
    margin-bottom: 1rem;
    border: 1px solid transparent;
    border-radius: 0.25rem;
}
        .alert-info {
    color: #0c5460;
    background-color: #d1ecf1;
    border-color: #bee5eb;
}
    </style>
    <div class="row mb-2">
        <div class="col-12">
            <div class="card card-default color-palette-box">
                <div class="card-header">
                    <h3><strong>OFFICIAL TRANSCRIPT OF RECORDS</strong></h3>
                    <strong>Students ({{count($students)}})</strong>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <select class="form-control select2" id="select-student">
                                <option value="0">Select student</option>
                                @foreach($students as $student)
                                    <option value="{{$student->id}}">{{$student->lastname}}, {{$student->firstname}} {{$student->middlename}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 text-right">
                            <button type="button" class="btn btn-primary" disabled id="btn-generate"><i class="fa fa-sync"></i> Generate</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-2" id="div-results"></div>
    <div class="modal fade" id="modal-adddata" aria-hidden="true" style="display: none;">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title"></h4>
            <button type="button" id="closeremarks" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
              <div class="row">
                  <div class="col-md-12 p-0">
                      <table class="table">
                          <thead class="text-center">
                              <tr>
                                  <th style="width: 15%;">Subject Code</th>
                                  <th style="width: 10%;">Units</th>
                                  <th>Description</th>
                                  <th style="width: 11%;">Grade</th>
                                  <th style="width: 11%;">Credits</th>
                                  <th style="width: 11%;"></th>
                              </tr>
                          </thead>
                          <tbody id="tbody-adddata">
                            @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'ndsc')
                            <tr class="tr-adddata">
                                <td class="p-0"><input type="text" class="form-control input-subjcode" placeholder="Code"/></td>
                                <td class="p-0"><input type="number" class="form-control input-subjunit" placeholder="Units"/></td>
                                <td class="p-0"><input type="text" class="form-control input-subjdesc" placeholder="Description"/></td>
                                <td class="p-0"><input type="number" class="form-control input-subjgrade" placeholder="Grade"/></td>
                                <td class="p-0"><input type="number" class="form-control input-subjreex" placeholder="Re-Ex"/></td>
                                <td class="p-0"><input type="number" class="form-control input-subjcredit" placeholder="Credit"/></td>
                                <td class="p-0"><button type="button" class="btn btn-default btn-adddata-save"><i class="fa fa-check text-success"></i></button></td>
                            </tr>
                              @else
                              <tr class="tr-adddata">
                                  <td class="p-0"><input type="text" class="form-control input-subjcode" placeholder="Code"/></td>
                                  <td class="p-0"><input type="number" class="form-control input-subjunit" placeholder="Units"/></td>
                                  <td class="p-0"><input type="text" class="form-control input-subjdesc" placeholder="Description"/></td>
                                  <td class="p-0"><input type="number" class="form-control input-subjgrade" placeholder="Grade"/></td>
                                  <td class="p-0"><input type="number" class="form-control input-subjcredit" placeholder="Credit"/></td>
                                  <td class="p-0"><button type="button" class="btn btn-default btn-adddata-save"><i class="fa fa-check text-success"></i></button></td>
                              </tr>
                              @endif
                          </tbody>
                          <tfoot>
                              <tr>
                                  <td colspan="2"><button type="button" class="btn btn-info btn-block btn-sm" id="btn-adddata-row"><i class="fa fa-plus"></i> Add more</button></td>
                                  <td colspan="4"></td>
                                  {{-- <td colspan="2"><button type="button" class="btn btn-primary btn-block btn-sm" id="btn-adddata-submit"><i class="fa fa-share"></i> Submit</button></td> --}}
                              </tr>
                              {{-- <tr>
                                  <td colspan="4"></td>
                                  <td colspan="2"><button type="button" class="btn btn-info btn-block btn-sm" id="btn-adddata-submit"><i class="fa fa-share"></i> Submit</button></td>
                              </tr> --}}
                          </tfoot>
                      </table>
                  </div>
              </div>
          </div>
          
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    
    <script>
        
        $(function () {
            $('.select2').select2()
            $('#example2').DataTable({
              "paging": false,
              "lengthChange": true,
              "searching": true,
              "ordering": false,
              "info": true,
              "autoWidth": false,
              "responsive": true,
            });
        });
        $(document).ready(function(){
            $('#select-student').on('change', function(){
                if($(this).val() == 0)
                {
                    $('#btn-generate').prop('disabled', true)
                }else{
                    $('#btn-generate').prop('disabled', false)
                }
                $('#div-results').empty()
            })
            $('#btn-generate').on('click', function(){
                
                Swal.fire({
                        title: 'Generating...',
                        allowOutsideClick: false,
                        closeOnClickOutside: false,
                        onBeforeOpen: () => {
                            Swal.showLoading()
                        }
                })
                $.ajax({
                        url: '{{route('torgetrecords')}}',
                        type: 'GET',
                        dataType: '',
                        data: {
                            studid:$('#select-student').val()
                        },
                        success:function(data)
                        {
                            $('#div-results').empty()
                            $('#div-results').append(data)
                            $(".swal2-container").remove();
                            $('body').removeClass('swal2-shown')
                            $('body').removeClass('swal2-height-auto')
                            $('.select2').select2()
                            $('#small-selectsy').hide();
                            $('#small-inputsy').hide();
                            $('#small-selectcourse').hide();
                            $('#small-inputcoursename').hide();
                            $('.auto-disabled').find('input').attr('disabled')
                            // $('.auto-disabled').find('button').hide()
                        }
                }); 

            })
            $(document).on('click','.btn-editrecord', function(){
                var torid = $(this).attr('data-torid');
                $('#modal-updaterecord').modal('show')
                $('#btn-submit-updaterecord').attr('data-torid', torid)
                $.ajax({
                        url: '{{route('torgetrecord')}}',
                        type: 'GET',
                        data: {
                            studid:$('#select-student').val(),
                            torid: torid
                        },
                        success:function(data)
                        {
                            $('#container-editrecord').empty()
                            $('#container-editrecord').append(data)
                            $('.auto-disabled').find('input').attr('disabled')
                            $('.auto-disabled').find('button').hide()
                        }
                }); 

            })
            $(document).on('click','#btn-submit-updaterecord', function(){
                
                var torid = $(this).attr('data-torid');
                var checkvalidation     = 0;

                var schoolid            = $('#editinput-schoolid').val();
                var syid                = $('#editselect-sy').val();
                var schoolname          = $('#editinput-schoolname').val();
                var schooladdress       = $('#editinput-schooladdress').val();

                var customsy            = $('#editinput-sy').val();

                if(syid == 0 && customsy.replace(/^\s+|\s+$/g, "").length == 0)
                {
                    
                    $('#editinput-sy').css('border','1px solid red');
                    $('#editsmall-selectsy').show();
                    checkvalidation = 1;
                }else{
                    $('#editinput-sy').removeAttr('style')
                    $('#editsmall-selectsy').hide();
                }

                var semid               = $('#editselect-sem').val();

                var courseid            = $('#editselect-course').val();
                var customcourse        = $('#editinput-coursename').val();
                if(courseid == 0 && customcourse.replace(/^\s+|\s+$/g, "").length == 0)
                {
                    $('#editinput-coursename').css('border','1px solid red');
                    $('#editsmall-selectcourse').show();
                    checkvalidation = 1;
                }else{
                    $('#editsmall-selectcourse').hide();
                }
                if(checkvalidation == 1)
                {
                    toastr.warning('Please fill in required fields!', 'Add new record')
                }else{
                    $(this).prop('disabled',true)
                    $.ajax({
                        url: '{{route('torupdaterecord')}}',
                        type: 'GET',
                        dataType: '',
                        data: {
                            studid          :   $('#select-student').val(),
                            torid        :   torid,
                            schoolid        :   schoolid,
                            schoolname      :   schoolname,
                            schooladdress   :   schooladdress,
                            syid            :   syid,
                            customsy        :   customsy,
                            semid           :   semid,
                            courseid        :   courseid,
                            customcourse    :   customcourse
                        },
                        success:function(data)
                        {
                            if(data == 1)
                            {
                                toastr.success('Updated successfully!', 'Update record')
                                $('#btn-close-updaterecord').click();
                                $('#modal-updaterecord').find('input,select').val('')
                                $('#btn-generate').click()
                            }else{
                                toastr.warning('The same form already exists!', 'Add new record')
                            }
                        }
                    }); 
                }
            })
            $(document).on('click','#btn-details-save', function(){
                var parentguardian      = $('#input-parentguardian').val();
                var address             = $('#input-address').val();
                var elemcourse          = $('#input-elemcourse').val();
                var elemdatecomp        = $('#input-elemdatecomp').val();
                var elemschoolyear      = $('#input-elemschoolyear').val();
                var secondcourse        = $('#input-secondcourse').val();
                var seconddatecomp      = $('#input-seconddatecomp').val();
                var secondschoolyear    = $('#input-secondschoolyear').val();
                var admissiondate       = $('#input-admissiondate').val();
                var degree              = $('#input-degree').val();
                var thirdschoolyear     = $('#input-thirdschoolyear').val();
                var basisofadmission    = $('#input-basisofadmission').val();
                var major               = $('#input-major').val();
                var specialorder        = $('#input-specialorder').val();
                var graduationdate      = $('#input-graduationdate').val();
                var remarks             = $('#input-remarks').val();

                var dateadmitted        = $('#input-dateadmitted').val();
                var collegeof           = $('#input-collegeof').val();
                var entrancedata        = $('#input-entrancedata').val();
                var intermediategrades  = $('#input-intermediategrades').val();
                var secondarygrades     = $('#input-secondarygrades').val();

                var placeofbirth        = $('#input-placeofbirth').val();
                var acrno               = $('#input-acrno').val();
                var citizenship         = $('#input-citizenship').val();
                var civilstatus         = $('#input-civilstatus').val();
                var parentaddress       = $('#input-parentaddress').val();
                var guardianaddress     = $('#input-guardianaddress').val();
                
                var entrancedate        = $('#input-entrancedate').val();
                var schoolnameprimary        = $('#input-schoolname-primary').val();
                var schooladdressprimary        = $('#input-schooladdress-primary').val();
                var schoolyearprimary        = $('#input-schoolyear-primary').val();
                var schoolnamejunior        = $('#input-schoolname-junior').val();
                var schooladdressjunior        = $('#input-schooladdress-junior').val();
                var schoolyearjunior        = $('#input-schoolyear-junior').val();
                var schoolnamesenior        = $('#input-schoolname-senior').val();
                var schooladdresssenior        = $('#input-schooladdress-senior').val();
                var schoolyearsenior        = $('#input-schoolyear-senior').val();

                var nstpserialno        = $('#input-nstpserialno').val();
                $.ajax({
                    url: '{{route('torsavedetail')}}',
                    type: 'GET',
                    data: {
                        studid              :   $('#select-student').val(),
                        parentguardian      :   parentguardian,
                        address             :   address,
                        elemcourse          :   elemcourse,
                        elemdatecomp        :   elemdatecomp,
                        secondcourse        :   secondcourse,
                        seconddatecomp      :   seconddatecomp,
                        admissiondate       :   admissiondate,
                        degree              :   degree,
                        basisofadmission    :   basisofadmission,
                        major               :   major,
                        specialorder        :   specialorder,
                        elemschoolyear      :   elemschoolyear,
                        secondschoolyear    :   secondschoolyear,
                        thirdschoolyear     :   thirdschoolyear,
                        remarks             :   remarks,
                        graduationdate      :   graduationdate,
                        dateadmitted        :   dateadmitted,
                        collegeof           :   collegeof,
                        entrancedata        :   entrancedata,
                        intermediategrades  :   intermediategrades,
                        secondarygrades     :   secondarygrades,
                        placeofbirth        :   placeofbirth,
                        acrno               :   acrno,
                        citizenship         :   citizenship,
                        civilstatus         :   civilstatus,
                        parentaddress       :   parentaddress,
                        guardianaddress     :   guardianaddress,
                        entrancedate     :   entrancedate,
                        schoolnameprimary         :   schoolnameprimary,
                        schooladdressprimary         :   schooladdressprimary,
                        schoolyearprimary       :   schoolyearprimary,
                        schoolnamejunior         :   schoolnamejunior,
                        schooladdressjunior         :   schooladdressjunior,
                        schoolyearjunior       :   schoolyearjunior,
                        schoolnamesenior         :   schoolnamesenior,
                        schooladdresssenior         :   schooladdresssenior,
                        schoolyearsenior       :   schoolyearsenior,
                        nstpserialno       :   nstpserialno
                    },
                    success:function(data){
                        if(data == 1)
                        {
                            toastr.success('Updated successfully!', 'Other details')
                        }else{
                            toastr.warning('Something went wrong!', 'Other details')
                        }
                    }
                })
            })
            $(document).on('click','.btn-deleterecord', function(){
                var torid = $(this).attr('data-torid');
                var thisbutton  = $(this);
                Swal.fire({
                    title: 'Are you sure you want to delete this record?',
                    // text: "You won't be able to revert this!",
                    html:
                        "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                        url: '{{route('tordeleterecord')}}',
                            type:"GET",
                            data:{
                                torid :   torid
                            },
                            // headers: { 'X-CSRF-TOKEN': token },,
                            success: function(data){
                                if(data == 1)
                                {
                                    toastr.success('Deleted successfully!', 'Delete Record')
                                    thisbutton.closest('.card').remove()
                                }else{
                                    toastr.warning('Something went wrong!', 'Delete Record')
                                }
                            }
                        })
                    }
                })

            })
            $(document).on('change','#editselect-sy', function(){
                if($(this).val() == 0)
                {
                    $('#editdiv-customsy').show()
                }else{

                    $('#editdiv-customsy').hide()
                }
                console.log($(this).val())
                syid = $(this).val()
            })
            $(document).on('change','#editselect-course', function(){
                if($(this).val() == 0)
                {
                    $('#editdiv-customcourse').show()
                }else{
                    $('#editdiv-customcourse').hide()
                }
            })
            $(document).on('change','#select-sy', function(){
                if($(this).val() == 0)
                {
                    $('#div-customsy').show()
                }else{

                    $('#div-customsy').hide()
                }
                console.log($(this).val())
                syid = $(this).val()
            })
            $(document).on('change','#select-course', function(){
                if($(this).val() == 0)
                {
                    $('#div-customcourse').show()
                }else{
                    $('#div-customcourse').hide()
                }
            })

            $(document).on('click','#btn-submit-addnewrecord', function(){
                var checkvalidation     = 0;

                var schoolid            = $('#input-schoolid').val();
                var schoolname          = $('#input-schoolname').val();
                var schooladdress       = $('#input-schooladdress').val();

                var customsy            = $('#input-sy').val();
                var syid            = $('#select-sy').val();

                console.log(syid)
                if(syid == 0 && customsy.replace(/^\s+|\s+$/g, "").length == 0)
                {
                    
                    $('#input-sy').css('border','1px solid red');
                    $('#small-selectsy').show();
                    checkvalidation = 1;
                }else{
                    $('#input-sy').removeAttr('style')
                    $('#small-selectsy').hide();
                }

                var semid               = $('#select-sem').val();

                var courseid            = $('#select-course').val();
                var customcourse        = $('#input-coursename').val();
                if(courseid == 0 && customcourse.replace(/^\s+|\s+$/g, "").length == 0)
                {
                    console.log('course')
                    $('#input-coursename').css('border','1px solid red');
                    $('#small-selectcourse').show();
                    checkvalidation = 1;
                }else{
                    $('#small-selectcourse').hide();
                }
                if(checkvalidation == 1)
                {
                    toastr.warning('Please fill in required fields!', 'Add new record')
                }else{
                    $(this).prop('disabled',true)
                    $.ajax({
                        url: '{{route('toraddnewrecord')}}',
                        type: 'GET',
                        dataType: '',
                        data: {
                            studid          :   $('#select-student').val(),
                            schoolid        :   schoolid,
                            schoolname      :   schoolname,
                            schooladdress   :   schooladdress,
                            syid            :   syid,
                            customsy        :   customsy,
                            semid           :   semid,
                            courseid        :   courseid,
                            customcourse    :   customcourse
                        },
                        success:function(data)
                        {
                            if(data == 1)
                            {
                                toastr.success('Added successfully!', 'Add new record')
                                $('#btn-close-addnewrecord').click();
                                $('#modal-newrecord').find('input,select').val('')
                                $('#btn-generate').click()
                            }else{
                                toastr.warning('The same form already exists!', 'Add new record')
                            }
                        }
                    }); 
                }
            })
            var torid = 0;
            function getsubjects(torid,semid,sydesc,courseid,studentid)
            {
                
                $.ajax({
                    url: '{{route('torgetsubjects')}}',
                    type: 'GET',
                    data: {
                        torid       :   torid,
                        semid    :   semid,
                        sydesc  :   sydesc,
                        courseid  :   courseid,
                        studentid  :   studentid
                    },
                    success:function(data){
                        if(data.length == 0)
                        {

                            @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'ndsc')
                                $('#tbody-adddata').append(
                                    
                                    '<tr class="tr-adddata">'+
                                        '<td class="p-0"><input type="text" class="form-control input-subjcode" placeholder="Code"/></td>'+
                                        '<td class="p-0"><input type="number" class="form-control input-subjunit" placeholder="Units"/></td>'+
                                        '<td class="p-0"><input type="text" class="form-control input-subjdesc" placeholder="Description"/></td>'+
                                        '<td class="p-0"><input type="number" class="form-control input-subjgrade" placeholder="Grade"/></td>'+
                                        '<td class="p-0"><input type="number" class="form-control input-subjreex" placeholder="Re-Ex"/></td>'+
                                        '<td class="p-0"><input type="number" class="form-control input-subjcredit" placeholder="Credit"/></td>'+
                                        '<td class="p-0"><button type="button" class="btn btn-default btn-adddata-save"><i class="fa fa-check text-success"></i></button><button type="button" class="btn btn-default btn-remove-row"><i class="fa fa-times text-danger"></i></button></td>'+
                                    '</tr>'
                                )
                            @else
                                $('#tbody-adddata').append(
                            '<tr class="tr-adddata">'+
                                '<td class="p-0"><input type="text" class="form-control input-subjcode" placeholder="Code"/></td>'+
                                '<td class="p-0"><input type="number" class="form-control input-subjunit" placeholder="Units"/></td>'+
                                '<td class="p-0"><input type="text" class="form-control input-subjdesc" placeholder="Description"/></td>'+
                                '<td class="p-0"><input type="number" class="form-control input-subjgrade" placeholder="Grade"/></td>'+
                                '<td class="p-0"><input type="number" class="form-control input-subjcredit" placeholder="Credit"/></td>'+
                                '<td class="p-0"><button type="button" class="btn btn-default btn-adddata-save"><i class="fa fa-check text-success"></i></button><button type="button" class="btn btn-default btn-remove-row"><i class="fa fa-times text-danger"></i></button></td>'+
                            '</tr>'
                                )
                            @endif

                        }else
                        {
                            var selectoptions = '';
                            $.each(data, function(key, value){
                                selectoptions+='<option value="'+value.id+'">'+value.subjdesc+'</option>'
                                if(key == 0)
                                {
                                    selectoptions+='<option value="0">Custom Subject</option>'
                                }
                            })
                            $('#tbody-adddata').append(                                
                                '<tr class="tr-adddata">'+
                                    '<td class="p-0"><input type="text" class="form-control input-subjcode" placeholder="Code" disabled value="'+data[0].subjcode+'"/></td>'+
                                    '<td class="p-0"><input type="number" class="form-control input-subjunit" placeholder="Units" disabled value="'+(data[0].lecunits)+(data[0].labunits)+'"/></td>'+
                                    '<td class="p-0"><select class="form-control select-subjdesc select2">'+selectoptions+'</select></td>'+
                                    '<td class="p-0"><input type="number" class="form-control input-subjgrade" placeholder="Grade"/></td>'+
                                    '<td class="p-0"><input type="number" class="form-control input-subjcredit" placeholder="Credit"/></td>'+
                                    '<td class="p-0"><button type="button" class="btn btn-default btn-adddata-save"><i class="fa fa-check text-success"></i></button><button type="button" class="btn btn-default btn-remove-row"><i class="fa fa-times text-danger"></i></button></td>'+
                                '</tr>'
                            )
                            $('.select2').select2()
                            
                            $('.select-subjdesc').on('change', function(){
                                var thisrow = $(this).closest('tr')
                                if($(this).val() == 0)
                                {
                                    thisrow.find('.input-subjcode').prop('disabled',false)
                                    thisrow.find('.input-subjunit').prop('disabled',false)
                                    thisrow.find('.input-subjcode').val('')
                                    thisrow.find('.input-subjunit').val('')
                                    var thistd = thisrow.find('.select-subjdesc').closest('td');
                                    thistd = thistd.empty()
                                    thistd.append('<input type="text" class="form-control input-subjdesc" placeholder="Description"/>')
                                }else{
                                    $.ajax({
                                        url: '{{route('torgetsubjects')}}',
                                        type: 'GET',
                                        data: {
                                            action          :   'getinfo',
                                            subjectid        :   $(this).val()
                                        },
                                        success:function(data)
                                        {
                                            thisrow.find('.input-subjcode').val(data.subjcode)
                                            thisrow.find('.input-subjunit').val((data.lecunits)+(data.labunits))
                                        }
                                    });
                                } 
                            })
                        }
                    }
                })
            }
            $(document).on('click','.btn-adddata', function(){

                torid = $(this).attr('data-torid');
                var semid = $(this).attr('data-semid');
                var sydesc = $(this).attr('data-sydesc');
                var courseid = $(this).attr('data-courseid');
                var studentid = $('#select-student').val()
                $('#btn-adddata-row').attr('data-torid', torid);
                $('#btn-adddata-row').attr('data-semid', semid);
                $('#btn-adddata-row').attr('data-sydesc', sydesc);
                $('#btn-adddata-row').attr('data-courseid', courseid);
                $('#modal-adddata').modal('show')
                
                getsubjects(torid,semid,sydesc,courseid,studentid)
            })
            $('#btn-adddata-row').on('click', function(){
                var semid = $(this).attr('data-semid');
                var sydesc = $(this).attr('data-sydesc');
                var courseid = $(this).attr('data-courseid');
                var studentid = $('#select-student').val()
                @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'ndsc')
                    $('#tbody-adddata').append(
                        
                        '<tr class="tr-adddata">'+
                            '<td class="p-0"><input type="text" class="form-control input-subjcode" placeholder="Code"/></td>'+
                            '<td class="p-0"><input type="number" class="form-control input-subjunit" placeholder="Units"/></td>'+
                            '<td class="p-0"><input type="text" class="form-control input-subjdesc" placeholder="Description"/></td>'+
                            '<td class="p-0"><input type="number" class="form-control input-subjgrade" placeholder="Grade"/></td>'+
                            '<td class="p-0"><input type="number" class="form-control input-subjreex" placeholder="Re-Ex"/></td>'+
                            '<td class="p-0"><input type="number" class="form-control input-subjcredit" placeholder="Credit"/></td>'+
                            '<td class="p-0"><button type="button" class="btn btn-default btn-adddata-save"><i class="fa fa-check text-success"></i></button><button type="button" class="btn btn-default btn-remove-row"><i class="fa fa-times text-danger"></i></button></td>'+
                        '</tr>'
                    )
                @else
                    getsubjects(torid,semid,sydesc,courseid,studentid)
                @endif
            })
            $(document).on('click','.btn-remove-row', function(){
                $(this).closest('tr').remove()
            })
            $(document).on('click','.btn-adddata-save', function(){
                var thisrow = $(this).closest('tr');
                if(thisrow.find('select-subjdesc') == 0)
                {
                    if($(this).closest('tr').find('.input-subjdesc').val().replace(/^\s+|\s+$/g, "").length > 0 && $(this).closest('tr').find('.input-subjgrade').val().replace(/^\s+|\s+$/g, "").length > 0)
                    {
                        var thisbutton  = $(this);
                        var subjcode    = $(this).closest('tr').find('.input-subjcode').val();
                        var subjunit    = $(this).closest('tr').find('.input-subjunit').val();
                        var subjdesc    = $(this).closest('tr').find('.input-subjdesc').val();
                        var subjgrade   = $(this).closest('tr').find('.input-subjgrade').val();
                        var subjreex   = $(this).closest('tr').find('.input-subjreex').val();
                        var subjcredit  = $(this).closest('tr').find('.input-subjcredit').val();

                        $.ajax({
                            url: '{{route('toraddnewdata')}}',
                            type: 'GET',
                            data: {
                                torid       :   torid,
                                studid      :   $('#select-student').val(),
                                subjcode    :   subjcode,
                                subjunit    :   subjunit,
                                subjdesc    :   subjdesc,
                                subjgrade   :   subjgrade,
                                subjreex   :   subjreex,
                                subjcredit  :   subjcredit
                            },
                            success:function(data){
                                if(data == 0)
                                {
                                    toastr.warning('The same form already exists!', 'Add new data')
                                }else{
                                    toastr.success('Added successfully!', 'Add new data')
                                    thisbutton.closest('td').empty()
                                    thisbutton.closest('td').append(
                                        '<button type="button" class="btn btn-default btn-editdata-save" data-subjgradeid="'+data+'"><i class="fa fa-share text-success"></i></button>'+
                                        '<button type="button" class="btn btn-default btn-delete-subjdata" data-subjgradeid="'+data+'"><i class="fa fa-trash text-danger"></i></button>'
                                    )
                                    thisbutton.remove()
                                }
                            }
                        })
                    }else{
                        $(this).closest('tr').find('.input-subjdesc').css('border','1px solid red');
                        $(this).closest('tr').find('.input-subjgrade').css('border','1px solid red');
                        toastr.warning('Please fill in required fields!', 'Add new data')
                    }
                }else{
                    if(thisrow.closest('tr').find('.input-subjgrade').val().replace(/^\s+|\s+$/g, "").length > 0)
                    {
                        var thisbutton  = $(this);
                        var subjcode    = $(this).closest('tr').find('.input-subjcode').val();
                        var subjunit    = $(this).closest('tr').find('.input-subjunit').val();
                        var subjid    = $(this).closest('tr').find('.select-subjdesc').val();
                        var getsubjdesc    = $(this).closest('tr').find('.select-subjdesc option:selected');
                        var subjdesc    = getsubjdesc.text();
                        var subjgrade   = $(this).closest('tr').find('.input-subjgrade').val();
                        var subjreex   = $(this).closest('tr').find('.input-subjreex').val();
                        var subjcredit  = $(this).closest('tr').find('.input-subjcredit').val();

                        $.ajax({
                            url: '{{route('toraddnewdata')}}',
                            type: 'GET',
                            data: {
                                torid       :   torid,
                                studid      :   $('#select-student').val(),
                                subjcode    :   subjcode,
                                subjid    :   subjid,
                                subjunit    :   subjunit,
                                subjdesc    :   subjdesc,
                                subjgrade   :   subjgrade,
                                subjreex   :   subjreex,
                                subjcredit  :   subjcredit
                            },
                            success:function(data){
                                if(data == 0)
                                {
                                    toastr.warning('The same form already exists!', 'Add new data')
                                }else{
                                    var thistd = thisbutton.closest('td');
                                    thistd.empty()
                                    toastr.success('Added successfully!', 'Add new data')
                                    thistd.append(
                                        '<button type="button" class="btn btn-default btn-editdata-save" data-subjgradeid="'+data+'"><i class="fa fa-share text-success"></i></button>'+
                                        '<button type="button" class="btn btn-default btn-delete-subjdata" data-subjgradeid="'+data+'"><i class="fa fa-trash text-danger"></i></button>'
                                    )
                                    // thisbutton.remove()
                                }
                            }
                        })
                    }else{
                        $(this).closest('tr').find('.input-subjdesc').css('border','1px solid red');
                        $(this).closest('tr').find('.input-subjgrade').css('border','1px solid red');
                        toastr.warning('Please fill in required fields!', 'Add new data')
                    }
                }
            })
            $(document).on('click','.btn-editdata-save', function(){
                var subjgradeid = $(this).attr('data-subjgradeid');
                if($(this).closest('tr').find('.input-subjdesc').val().replace(/^\s+|\s+$/g, "").length > 0 && $(this).closest('tr').find('.input-subjgrade').val().replace(/^\s+|\s+$/g, "").length > 0)
                {
                    var thisbutton  = $(this);
                    var subjcode    = $(this).closest('tr').find('.input-subjcode').val();
                    var subjunit    = $(this).closest('tr').find('.input-subjunit').val();
                    var subjdesc    = $(this).closest('tr').find('.input-subjdesc').val();
                    var subjgrade   = $(this).closest('tr').find('.input-subjgrade').val();
                    var subjreex   = $(this).closest('tr').find('.input-subjreex').val();
                    var subjcredit  = $(this).closest('tr').find('.input-subjcredit').val();

                    $.ajax({
                        url: '{{route('toreditsubjgrade')}}',
                        type: 'GET',
                        data: {
                            torid       :   torid,
                            subjgradeid :   subjgradeid,
                            studid      :   $('#select-student').val(),
                            subjcode    :   subjcode,
                            subjunit    :   subjunit,
                            subjdesc    :   subjdesc,
                            subjgrade   :   subjgrade,
                            subjreex    :   subjreex,
                            subjcredit  :   subjcredit
                        },
                        success:function(data){
                            if(data == 1)
                            {
                                toastr.success('Updated successfully!', 'Update data')
                            }else{
                                toastr.warning('The same form already exists!', 'Update data')
                            }
                        }
                    })
                }else{
                    $(this).closest('tr').find('.input-subjdesc').css('border','1px solid red');
                    $(this).closest('tr').find('.input-subjgrade').css('border','1px solid red');
                    toastr.warning('Please fill in required fields!', 'Update data')
                }
            })
            $(document).on('click', '.btn-delete-subjdata', function(){
                var subjgradeid = $(this).attr('data-subjgradeid');
                var thisbutton  = $(this);
                Swal.fire({
                    title: 'Are you sure you want to delete this row?',
                    // text: "You won't be able to revert this!",
                    html:
                        "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                        url: '{{route('tordeletesubjgrade')}}',
                            type:"GET",
                            data:{
                                subjgradeid :   subjgradeid
                            },
                            // headers: { 'X-CSRF-TOKEN': token },,
                            success: function(data){
                                if(data == 1)
                                {
                                    toastr.success('Deleted successfully!', 'Delete data')
                                    thisbutton.closest('tr').remove()
                                }else{
                                    toastr.warning('Something went wrong!', 'Delete data')
                                }
                            }
                        })
                    }
                })
            })
            $(document).on('click','.btn-editdata', function(){
                $(this).closest('tr').find('input,button').prop('disabled',false)
            })
            $("#modal-adddata").on("hidden.bs.modal", function () {
                $('#btn-generate').click();
            });
            $(document).on('click','.btn-save-text', function(){
                var thisbutton = $(this);
                var removebutton = $(this).closest('.row').find('.btn-remove');
                var id = $(this).attr('data-id');
                var semid = $(this).attr('data-semid');
                var sydesc = $(this).attr('data-sydesc');
                var thistext = $(this).closest('.row').find('input').val();
                if(thistext.replace(/^\s+|\s+$/g, "").length == 0)
                {
                    $(this).closest('.row').find('input').css('border','1px solid red')
                    toastr.warning('Fill in important field first!', 'Add text')
                }else{
                    $.ajax({
                        url: '{{route('torsavetext')}}',
                        type: 'GET',
                        data: {
                            id      :   id,
                            studid          :   $('#select-student').val(),
                            semid      :   semid,
                            sydesc      :   sydesc,
                            thistext      :   thistext
                        },
                        success:function(data){
                            if(data == 1)
                            {
                                toastr.success('Updated successfully!', 'Other details')
                                thisbutton.prop('disabled');
                                removebutton.remove()
                            }else{
                                toastr.warning('Something went wrong!', 'Other details')
                            }
                        }
                    })
                }
            })
            $(document).on('click','.btn-delete-text', function(){
                var id = $(this).attr('data-id');
                var thisrow = $(this).closest('.row');
                Swal.fire({
                    title: 'Are you sure you want to delete this text?',
                    html:
                        "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                        url: '{{route('tordeletetext')}}',
                            type:"GET",
                            data:{
                                id :   id
                            },
                            // headers: { 'X-CSRF-TOKEN': token },,
                            success: function(data){
                                if(data == 1)
                                {
                                    toastr.success('Deleted successfully!', 'Delete Text')
                                    thisrow.remove()
                                }else{
                                    toastr.warning('Something went wrong!', 'Delete Text')
                                }
                            }
                        })
                    }
                })
            })
            $(document).on('click','#btn-exporttopdf', function(){
                var registrar    = $('#input-registrar').val();
                var assistantreg = $('#input-assistantreg').val();
                var or           = $('#input-or').val();
                var dateissued   = $('#input-date-issued').val();
                window.open('/schoolform/tor/exporttopdf?studid='+$('#select-student').val()+'&registrar='+registrar+'&assistantreg='+assistantreg+'&or='+or+'&dateissued='+dateissued,'_blank')
            })
        })
   
    </script>
@endsection

                                        

                                        
                                        