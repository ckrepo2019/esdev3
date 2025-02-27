@extends('registrar.layouts.app')
@section('content')
    <style>
        .select2-container .select2-selection--single {
            height: 40px !important;
        }

        #modal-adddata .modal-dialog {
            max-width: 800px
        }

        td,
        th {
            padding: 1px !important;
        }
    </style>
    <div class="row mb-2">
        <div class="col-12">
            <div class="card card-default color-palette-box">
                <div class="card-header">
                    @if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'mci')
                        <h3><strong>Application for Graduation from Collegiate Course</strong></h3>
                    @else
                        <h3><strong>Record of Candidate For Graduation</strong></h3>
                    @endif
                    <strong>Students ({{ count($students) }})</strong>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <select class="form-control select2" id="select-student">
                                <option value="0">Select student</option>
                                @foreach ($students as $student)
                                    <option value="{{ $student->id }}">{{ $student->lastname }}, {{ $student->firstname }}
                                        {{ $student->middlename }}</option>
                                @endforeach
                            </select>
                        </div>
                        {{-- <div class="col-md-3 text-right"> --}}
                        {{-- <button type="button" class="btn btn-outline-info btn-block" data-toggle="modal" data-target="#modal-addsubjectgroup"><i class="fa fa-plus"></i> Subject Groupings</button> --}}
                        {{-- </div> --}}
                        <div class="col-md-4 text-right">
                            <button type="button" class="btn btn-primary" disabled id="btn-generate"><i
                                    class="fa fa-sync"></i> Generate</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="div-results"></div>
    {{-- <div class="modal fade" id="modal-addsubjectgroup" aria-hidden="true" style="display: none;">
      <div class="modal-dialog modal-lg">
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
                        <tr>
                            <td colspan="2"></td>
                            <td colspan="2"><button type="button" class="btn btn-info btn-block btn-sm" id="btn-adddata-row"><i class="fa fa-plus"></i> Add more</button></td>
                        </tr>
                          <thead class="text-center">
                              <tr>
                                  <th style="width: 10%;">#</th>
                                  <th>Subject Group</th>
                                  <th style="width: 15%;">Units Required</th>
                                  <th style="width: 12%;"></th>
                              </tr>
                          </thead>
                          <tbody id="tbody-adddata">
                              <tr class="tr-adddata">
                                  <td class="p-0"><input type="text" class="form-control input-subjnum"/></td>
                                  <td class="p-0"><input type="text" class="form-control input-subjgroup" placeholder="Group"/></td>
                                  <td class="p-0"><input type="number" class="form-control input-subjunit" placeholder="Units"/></td>
                                  <td class="p-0"><button type="button" class="btn btn-default btn-adddata-save"><i class="fa fa-check text-success"></i></button></td>
                              </tr>
                          </tbody>
                      </table>
                  </div>
              </div>
          </div>
          
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div> --}}
@endsection
@section('footerscripts')
    <script>
        $(function() {
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
        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap4'
            })

            $('#select-student').on('change', function() {
                if ($(this).val() == 0) {
                    $('#btn-generate').prop('disabled', true)
                } else {
                    $('#btn-generate').prop('disabled', false)
                }
                $('#div-results').empty()
            })
            $('#btn-generate').on('click', function() {

                Swal.fire({
                    title: 'Generating...',
                    allowOutsideClick: false,
                    closeOnClickOutside: false,
                    onBeforeOpen: () => {
                        Swal.showLoading()
                    }
                })
                $.ajax({
                    url: '{{ route('rcfggetrecords') }}',
                    type: 'GET',
                    data: {
                        studid: $('#select-student').val()
                    },
                    success: function(data) {
                        $('#div-results').empty()
                        $('#div-results').append(data)
                        $(".swal2-container").remove();
                        $('body').removeClass('swal2-shown')
                        $('body').removeClass('swal2-height-auto')
                        $('#small-selectsy').hide();
                        $('#small-inputsy').hide();
                        $('#small-selectcourse').hide();
                        $('#small-inputcoursename').hide();
                        $('.auto-disabled').find('input').attr('disabled')
                        $('.auto-disabled').find('button').hide()
                    }
                });

            })
            $(document).on('click', '.btn-updateunitplot', function() {
                var syid = $(this).attr('data-syid');
                var semid = $(this).attr('data-semid');
                var subjectid = $(this).attr('data-subjectid');
                var studid = $('#select-student').val();

                var subjgroupid = 0;
                var radioinputs = $(this).closest('tr').find('input');
                radioinputs.each(function() {
                    if ($(this).is(':checked')) {
                        subjgroupid = $(this).val()
                    }
                })

                if (subjgroupid == 0) {
                    toastr.warning('Please select a subject group!', 'Unit Plotting')
                } else {
                    $.ajax({
                        url: '{{ route('rcfgsubjgroupunitplot') }}',
                        type: 'GET',
                        dataType: '',
                        data: {
                            studid: studid,
                            syid: syid,
                            semid: semid,
                            subjectid: subjectid,
                            subjgroupid: subjgroupid
                        },
                        success: function(data) {
                            if (data == 1) {
                                toastr.success('Updated successfully', 'Unit Plotting')
                            }
                        }
                    });
                }
            })
            $(document).on('click', '#btn-exportpdf', function() {
                var studid = $('#select-student').val();
                var degree = $('#input-degree').val();
                var entrancedata = $('#input-entrancedata').val();
                var checkedby = $('#input-checkedby').val()
                var collegereg = $('#input-collegereg').val()
                var intermediatecourse = $('#input-intermediatecourse').val()
                var intermediateyear = $('#input-intermediateyear').val()
                var secondarycourse = $('#input-secondarycourse').val()
                var secondaryyear = $('#input-secondaryyear').val()

                window.open('/schoolform/rcfggetrecords/getrecords?action=exportpdf&studid=' + studid +
                    '&degree=' + degree + '&entrancedata=' + entrancedata + '&checkedby=' + checkedby +
                    '&collegereg=' + collegereg + '&intermediatecourse=' + intermediatecourse +
                    '&intermediateyear=' + intermediateyear + '&secondarycourse=' + secondarycourse +
                    '&secondaryyear=' + secondaryyear, '_blank')
            })
            // $(document).on('click','#btn-details-save', function(){
            //     var parentguardian      = $('#input-parentguardian').val();
            //     var address             = $('#input-address').val();
            //     var elemcourse          = $('#input-elemcourse').val();
            //     var elemdatecomp        = $('#input-elemdatecomp').val();
            //     var secondcourse        = $('#input-secondcourse').val();
            //     var seconddatecomp      = $('#input-seconddatecomp').val();
            //     var admissiondate       = $('#input-admissiondate').val();
            //     var degree              = $('#input-degree').val();
            //     var basisofadmission    = $('#input-basisofadmission').val();
            //     var major               = $('#input-major').val();
            //     var specialorder        = $('#input-specialorder').val();
            //     var graduationdate      = $('#input-graduationdate').val();

            //     $.ajax({
            //         url: '{{ route('torsavedetail') }}',
            //         type: 'GET',
            //         data: {
            //             studid              :   $('#select-student').val(),
            //             parentguardian      :   parentguardian,
            //             address             :   address,
            //             elemcourse          :   elemcourse,
            //             elemdatecomp        :   elemdatecomp,
            //             secondcourse        :   secondcourse,
            //             seconddatecomp      :   seconddatecomp,
            //             admissiondate       :   admissiondate,
            //             degree              :   degree,
            //             basisofadmission    :   basisofadmission,
            //             major               :   major,
            //             specialorder        :   specialorder,
            //             graduationdate      :   graduationdate
            //         },
            //         success:function(data){
            //             if(data == 1)
            //             {
            //                 toastr.success('Updated successfully!', 'Other details')
            //             }else{
            //                 toastr.warning('Something went wrong!', 'Other details')
            //             }
            //         }
            //     })
            // })
            // $(document).on('click','.btn-deleterecord', function(){
            //     var torid = $(this).attr('data-torid');
            //     var thisbutton  = $(this);
            //     Swal.fire({
            //         title: 'Are you sure you want to delete this record?',
            //         // text: "You won't be able to revert this!",
            //         html:
            //             "You won't be able to revert this!",
            //         type: 'warning',
            //         showCancelButton: true,
            //         confirmButtonColor: '#3085d6',
            //         cancelButtonColor: '#d33',
            //         confirmButtonText: 'Yes, delete it!',
            //         allowOutsideClick: false
            //     }).then((result) => {
            //         if (result.value) {
            //             $.ajax({
            //             url: '{{ route('tordeleterecord') }}',
            //                 type:"GET",
            //                 data:{
            //                     torid :   torid
            //                 },
            //                 // headers: { 'X-CSRF-TOKEN': token },,
            //                 success: function(data){
            //                     if(data == 1)
            //                     {
            //                         toastr.success('Deleted successfully!', 'Delete Record')
            //                         thisbutton.closest('.card').remove()
            //                     }else{
            //                         toastr.warning('Something went wrong!', 'Delete Record')
            //                     }
            //                 }
            //             })
            //         }
            //     })

            // })
            $(document).on('change', '#select-sy', function() {
                if ($(this).val() == 0) {
                    $('#div-customsy').show()
                } else {
                    $('#div-customsy').hide()
                }
            })
            $(document).on('change', '#select-course', function() {
                if ($(this).val() == 0) {
                    $('#div-customcourse').show()
                } else {
                    $('#div-customcourse').hide()
                }
            })

            // $(document).on('click','#btn-submit-addnewrecord', function(){
            //     var checkvalidation     = 0;

            //     var schoolid            = $('#input-schoolid').val();
            //     var schoolname          = $('#input-schoolname').val();
            //     var schooladdress       = $('#input-schooladdress').val();

            //     var syid                = $('#select-sy').val();
            //     var customsy            = $('#input-sy').val();
            //     if(syid == 0 && customsy.replace(/^\s+|\s+$/g, "").length == 0)
            //     {
            //         console.log('syid')
            //         $('#input-sy').css('border','1px solid red');
            //         $('#small-selectsy').show();
            //         checkvalidation = 1;
            //     }else{
            //         $('#input-sy').removeAttr('style')
            //         $('#small-selectsy').hide();
            //     }

            //     var semid               = $('#select-sem').val();

            //     var courseid            = $('#select-course').val();
            //     var customcourse        = $('#input-coursename').val();
            //     if(courseid == 0 && customcourse.replace(/^\s+|\s+$/g, "").length == 0)
            //     {
            //         console.log('course')
            //         $('#input-coursename').css('border','1px solid red');
            //         $('#small-selectcourse').show();
            //         checkvalidation = 1;
            //     }else{
            //         $('#small-selectcourse').hide();
            //     }
            //     if(checkvalidation == 1)
            //     {
            //         toastr.warning('Please fill in required fields!', 'Add new record')
            //     }else{
            //         $(this).prop('disabled',true)
            //         $.ajax({
            //             url: '{{ route('toraddnewrecord') }}',
            //             type: 'GET',
            //             dataType: '',
            //             data: {
            //                 studid          :   $('#select-student').val(),
            //                 schoolid        :   schoolid,
            //                 schoolname      :   schoolname,
            //                 schooladdress   :   schooladdress,
            //                 syid            :   syid,
            //                 customsy        :   customsy,
            //                 semid           :   semid,
            //                 courseid        :   courseid,
            //                 customcourse    :   customcourse
            //             },
            //             success:function(data)
            //             {
            //                 if(data == 1)
            //                 {
            //                     toastr.success('Added successfully!', 'Add new record')
            //                     $('#btn-close-addnewrecord').click();
            //                     $('#modal-newrecord').find('input,select').val('')
            //                     $('#btn-generate').click()
            //                 }else{
            //                     toastr.warning('The same form already exists!', 'Add new record')
            //                 }
            //             }
            //         }); 
            //     }
            // })
            // var torid = 0;
            // $(document).on('click','.btn-adddata', function(){
            //     torid = $(this).attr('data-torid');
            //     $('#modal-adddata').modal('show')
            // })
            // $('#btn-adddata-row').on('click', function(){
            //     $('#tbody-adddata').append(

            //         '<tr class="tr-adddata">'+
            //             '<td class="p-0"><input type="text" class="form-control input-subjnum"/></td>'+
            //             '<td class="p-0"><input type="text" class="form-control input-subjgroup" placeholder="Group"/></td>'+
            //             '<td class="p-0"><input type="number" class="form-control input-subjunit" placeholder="Units"/></td>'+
            //             '<td class="p-0"><button type="button" class="btn btn-default btn-adddata-save"><i class="fa fa-check text-success"></i></button><button type="button" class="btn btn-default btn-remove-row"><i class="fa fa-times text-danger"></i></button></td>'+
            //         '</tr>'
            //     )
            // })
            // $(document).on('click','.btn-remove-row', function(){
            //     $(this).closest('tr').remove()
            // })
            // $(document).on('click','.btn-editdata', function(){
            //     $(this).closest('tr').find('input,button').prop('disabled',false)
            // })
            // $("#modal-adddata").on("hidden.bs.modal", function () {
            //     $('#btn-generate').click();
            // });
            // $(document).on('click','#btn-exporttopdf', function(){
            //     var degree = $('#input-degree').val(); 
            //     var entrancedata = $('#input-entrancedata').val(); 


            //     window.open('/schoolform/tor/exporttopdf?studid='+$('#select-student').val()+'&degree='+degree+'&entrancedata='+entrancedata,'_blank')
            // })
        })
    </script>
@endsection
