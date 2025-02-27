@extends('studentPortal.layouts.app2')


@section('pagespecificscripts')

    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css')}}">


@endsection


@section('content')


    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Scholarship Request</h1>
                </div>
                <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/home">Home</a></li>
                    <li class="breadcrumb-item active">Scholarship Request</li>
                </ol>
                </div>
            </div>
        </div>
    </section>

   

    <div class="container-fluid">
        <div class="row with_enrollment" >
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header bg-primary">
                        <h4>Scholarship Request
                            <span class="float-right">
                                <button type="button" class="btn btn-sm btn-secondary" id="btn-add-request"><i class="fa fa-plus" aria-hidden="true"></i> Add Request</button>
                            </span>
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 table-responsive">
                                <table class="table table-sm table-hover" id="scholarship_datatable">
                                    <thead>
                                        <tr>
                                            <th>Scholarship Applied</th>
                                            <th>Student Status</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="modal_large" tabindex="-1" role="dialog" aria-hidden="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header modal-header-bg">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Scholarship Request Form</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form role="form" id="scholarship-form">
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="control-label">Name of Scholarship Applied for</label>
                                                <div class="input-icon right">
                                                    <select class="form-control menu-select" name="name_scholarship" id="name_scholarship" required="">
                                                        <option value="" disabled="" selected="">- Scholarship Provider - </option>
                                                        @foreach(DB::table('scholarship_setup')->where('deleted','0')->get() as $item)
                                                            <option value="{{$item->id}}" >- {{$item->description}} - </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-bordered table-condensed table-stripe" id="tableDocuments">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center">Document Name</th>
                                                        <th class="text-center">Attachment</th>
                                                        <th class="autofit text-center">Upload</th>
                                                        <th class="">Remarks</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tableDocumentsBody">

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label class="control-label">School Year   <i class="fa fa-info-circle tooltips" data-original-title="Academic Year" data-container="body"></i></label>
                                                <div class="input-icon right">
                                                    <input type="text" class="form-control" value={{DB::table('sy')->where('isactive', 1)->first()->sydesc}} disabled>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label></label>
                                                <div class="checkbox-list">
                                                    <label class="checkbox-inline">
                                                        <input type="checkbox" id="inlineCheckbox1" value="1 " name="semester"> First Semester </label>
                                                    <label class="checkbox-inline">
                                                        <input type="checkbox" id="inlineCheckbox2" value="2" name="semester"> Second Semester </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-close" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="btn-save-scholarship">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    


@endsection



@section('footerjavascript')

    <script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>
    <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
    <script src="{{asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-fixedcolumns/js/fixedColumns.bootstrap4.js')}}"></script>
    <script src="{{asset('plugins/sweetalert2/sweetalert2.min.js')}}"></script>
    <!-- Toastr -->
    <script src="{{asset('plugins/toastr/toastr.min.js')}}"></script>
    <script>

        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });


        function renderData(id = null){

            getScholarship(id).done(function(data){
                $('#scholarship_datatable').DataTable({
                    data: data,
                    destroy: true,
                    columns: [
                        { data: 'description', name: 'description' },
                        { data: 'studstatus', name: 'studstatus' },
                        { data: 'scholar_status', name: 'scholar_status' },
                        { data: null, orderable: false, searchable: false, "render": function ( data, type, row ) {
                            return '<a href="#" class="edit" data-id="'+row.id+'"><i class="far fa-edit"></i></a> <a href="#" class="delete" data-id="'+row.id+'"><i class="far fa-trash-alt text-danger"></i></a>';
                        }}
                    ]
                });
            })


        }

        function getScholarship(id = null){

            return $.ajax({
                type: "get",
                data: {
                    id: id,
                },
                url: "/student/scholarship",
            });

        }



        var csrfToken = "{{ csrf_token() }}";

        $(document).ready(function() {
            renderData();
            $('#name_scholarship').select2({
                theme: 'bootstrap4',
                dropdownParent: $('#modal_large')
            });

        });

        $(document).on('click', '#btn-add-request', function() {
                $('#modal_large').modal('show');
                $('#name_scholarship').val('').trigger('change');

                $('input[name="semester"]').each(function() {
                    $(this).prop('checked', false);
                })
        });

        $(document).on('change', '#name_scholarship', function() {

            var id = $(this).val();

            $.ajax({
                type: "get",
                url: "/student/scholarship/getRequirement",
                data: {
                    id: id
                },
                success: function(data) {

                    
                    var renderHtml = data.length > 0 ? data.map(entry => {

                        return `<tr>
                            <td class="text-center">${entry.description}</td>
                            <td class="text-center">
                                <a target="_blank"  href="/${entry.fileurl}" > ${entry.description} Attachment   </a> 
                            </td>
                            <td class="autofit text-center"><input type="file" data-id="${entry.id}"  class="form-control requirement2"> <input type="hidden" id="file${entry.id}"  data-id="${entry.id}" class="form-control requirement"></td>
                            <td><textarea name="remark${entry.id}" id="remark${entry.id}" data-id="${entry.id}" class="form-control remarks" ></textarea></td>
                        </tr>`;

                        return `
                            `;
                        }).join('') : `<tr>
                                            <td colspan="4" class="text-center">No Requirement Added</td>
                                        </tr>`;


                    $('#tableDocumentsBody').html(renderHtml);

                    
                }
            })

                

                $('#modal_large').modal('show');
        });

        $(document).on('click','#btn-save-scholarship',function(e){

            e.preventDefault();
            $('#btn-save-scholarship').prop('disabled', true);
            $('#customerRequired').hide();
            var isvalid = true;


            if(isvalid){

                var formData = new FormData($('#soform')[0]);


                var requirementsArray = [];


                $('.requirement').each(function() {
                    var value = $(this).val();
                    console.log(value);
                    var dataId = $(this).data('id');
                    requirementsArray.push({ value: value, dataId: dataId });
                });

                var remarks = [];

                $('.remarks').each(function() {
                    var value = $(this).val();
                    var dataId = $(this).data('id');
                    remarks.push({ value: value, dataId: dataId });
                });

                console.log(requirementsArray);
                console.log(remarks);

                formData.append('requirementsArray', JSON.stringify(requirementsArray));
                formData.append('remarks',  JSON.stringify(remarks));
                formData.append('scholarship_id',  $('#name_scholarship').val());
                var semesterid = $('input[name="semester"]:checked').val();
                formData.append('semesterid', semesterid);

                

                $.ajax({
                    url:'/student/scholarship/saveScholarship',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function(response) {
                        $('#btn-save-scholarship').prop('disabled', false);
                        $('.btn-close').trigger('click');
                        Toast.fire({
                                type: 'success',
                                title: 'The request has been saved successfully.'
                        })
                        renderData();
                    },
                    error: function(xhr, status, error) {
                        // Handle error
                        console.error(xhr.responseText);
                        alert('An error occurred while saving the scholarship. Please try again later.');

                    }
                });

            }

        });


        $(document).on('change', '.requirement2', function() {

            var fileInput = $(this)[0];
            var file = fileInput.files[0];
            var id = $(this).data('id');
        

            // AJAX request to upload the file
            var formData = new FormData();
            formData.append('file', file);

            $.ajax({
                url: '/uploadrequirement',
                type: 'POST',
                data: formData,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                processData: false,

                success: function(response) {
                    // Handle successful file upload
                    console.log("File uploaded successfully:", response.url);
                    // Set the filename value in a hidden input field in the form
                    $('#file' + id).val(response.url);
                    // Hide the progress bar
        
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    alert('An error occurred while uploading the file. Please try again later.');
                    // Hide the progress bar
                    $('.progress' + id).hide();
                }
            });


        
        });


        $(document).on('click', '.edit', function() {

            var id = $(this).data('id');



            getScholarship(id).done(function(data){

                console.log('Edit', data);

                
                $('#name_scholarship').val(data[0].scholarship_setup_id).trigger('change');

                $('#modal_large').modal('show');


                $('input[name="semester"]').each(function() {

                    if($(this).val() == data[0].semid){
                        $(this).prop('checked', true);
                    }
                })


            });
        });


        $(document).on('click', '.delete', function() {

            var id = $(this).data('id');

            Swal.fire({
                title: 'Are you sure you want to delete this scholarship?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                    var formData = new FormData();
                    formData.append('id', id);
                    formData.append('_token', csrfToken);
                    $.ajax({
                        type: "POST",
                        url: "/student/delete/scholarship",
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(data){
                            console.log(data);
                            if(data == 1){
                                renderData();
                                Toast.fire({
                                    type: 'success',
                                    title: 'Deleted successfully!'
                                })
                            }else{
                                
                            }
                        }
                    });
                }
            });

        
        })
    </script>
@endsection