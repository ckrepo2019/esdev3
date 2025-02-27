@php
    $check_refid = DB::table('usertype')
        ->where('id', Session::get('currentPortal'))
        ->select('refid', 'resourcepath')
        ->first();

    if (Session::get('currentPortal') == 14) {
        $extend = 'deanportal.layouts.app2';
    } elseif (auth()->user()->type == 17) {
        $extend = 'superadmin.layouts.app2';
    } elseif (Session::get('currentPortal') == 3) {
        $extend = 'registrar.layouts.app';
    } elseif (Session::get('currentPortal') == 8) {
        $extend = 'admission.layouts.app2';
    } elseif (Session::get('currentPortal') == 1) {
        $extend = 'teacher.layouts.app';
    } elseif (Session::get('currentPortal') == 2) {
        $extend = 'principalsportal.layouts.app2';
    } elseif (Session::get('currentPortal') == 4) {
        $extend = 'finance.layouts.app';
    } elseif (Session::get('currentPortal') == 15) {
        $extend = 'finance.layouts.app';
    } elseif (Session::get('currentPortal') == 18) {
        $extend = 'ctportal.layouts.app2';
    } elseif (Session::get('currentPortal') == 10) {
        $extend = 'hr.layouts.app';
    } elseif (Session::get('currentPortal') == 16) {
        $extend = 'chairpersonportal.layouts.app2';
    } elseif (auth()->user()->type == 16) {
        $extend = 'chairpersonportal.layouts.app2';
    } else {
        if (isset($check_refid->refid)) {
            if ($check_refid->resourcepath == null) {
                $extend = 'general.defaultportal.layouts.app';
            } elseif ($check_refid->refid == 27) {
                $extend = 'academiccoor.layouts.app2';
            } elseif ($check_refid->refid == 22) {
                $extend = 'principalcoor.layouts.app2';
            } elseif ($check_refid->refid == 29) {
                $extend = 'idmanagement.layouts.app2';
            } elseif ($check_refid->refid == 23) {
                $extend = 'clinic.index';
            } elseif ($check_refid->refid == 24) {
                $extend = 'clinic_nurse.index';
            } elseif ($check_refid->refid == 25) {
                $extend = 'clinic_doctor.index';
            } else {
                $extend = 'general.defaultportal.layouts.app';
            }
        } else {
            $extend = 'general.defaultportal.layouts.app';
        }
    }
@endphp
@extends($extend)
@section('content')
    <div class="row">
        {{-- <button type="button" class="btn btn-primary"><i class="fa fa-bell"></i> .btn-block</button> --}}
        <a href="/idprinting/canva" type="button" class="btn btn-success btn-sm m-4"> <i class="fas fa-plus"></i> Create
            Template
        </a>
    </div>

    <div class="row" id="template-wrapper">
    </div>
@endsection

@section('footerjavascript')
    <script>
        var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000
        });

        $(document).ready(function() {
            load_templates();


            $(document).on('click', '.delete-btn', function() {
                var templateId = $(this).data('id');
                delete_template(templateId)
            })

            $(document).on('click', '.set-default', function() {
                var tempname = $(this).data('id');
                if (tempname) {
                    set_default(tempname);
                }
            })
        });

        function set_default(name) {
            console.log(name)
            $.ajax({
                type: 'GET',
                data: {
                    name: name,
                },
                url: '{{ route('set.default') }}',
                success: function(data) {
                    notify(data[0].statusCode, data[0].message);
                    load_templates();
                }
            })
        }

        function load_templates() {
            $.ajax({
                type: 'GET',
                url: '{{ route('templates') }}',
                success: function(data) {
                    console.log(data)
                    if (data.templates.length == 0) {
                        notify("warning", "Empty Templates!");
                        return;
                    }
                    $('#template-wrapper').empty();
                    $.each(data.templates, function(index, each) {
                        var item = `<div class="col-md-3 col-sm-12 col-12">
                            <div class="card-body d-flex flex-column position-relative">
                                <center>
                                    <div>
                                        <img src="${each.image}" alt="template pic" class="img-fluid card shadow"
                                            style="max-height: 250px;">
                                        <h2 class="text-md">${each.name.toUpperCase()}</h2>
                                    </div>
                                </center>
                                <div class="position-absolute top-0 end-0 p-2">
                                    <div class="d-flex ">
                                        <div>
                                            <a type="button" href="javascript:void(0)" class="btn btn-danger btn-sm delete-btn" 
                                                data-id="${each.name}" >
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                        </div>
                                        <div class="ml-1">
                                            <a type="button" href="/idprinting/edit-template?id=${each.id}" class="btn btn-primary btn-sm edit-btn" 
                                                data-id="${each.id}" >
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <center>
                                    <div>  
                                        <button class="btn btn-sm set-default ${each.default ? "btn-primary" : "btn-outline-primary" } " data-id="${ each.default ? '' : each.name}" style="width: 70%;"> 
                                            ${each.default ? `<i class="far fa-check-circle"></i>` : '' } ${each.default ? 'DEFAULT' : 'SET AS DEFAULT'} 
                                        </button> 
                                    </div>
                                </center>
                            </div>
                        </div>`;
                        $('#template-wrapper').append(item);
                    });
                }
            });
        }


        function delete_template(id) {
            Swal.fire({
                type: 'info',
                title: 'You want to delete this template?',
                text: `You can't undo this process.`,
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: 'GET',
                        data: {
                            id: id,
                        },
                        url: '{{ route('delete.template') }}',
                        success: function(data) {
                            notify(data[0].statusCode, data[0].message);
                            load_templates();
                        }
                    });
                }
            });
        }

        function notify(code, message) {
            Toast.fire({
                type: code,
                title: message,
            });

        }
    </script>
@endsection
