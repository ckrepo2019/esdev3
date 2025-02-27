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
@section('pagespecificscripts')
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <style>
        .done_print {
            color: black;
            text-decoration: none;
        }

        .done_print:hover {
            color: green !important;
            text-decoration: underline !important;
        }
    </style>
@endsection
@section('content')
    {{-- Modal View ID Details --}}
    <div class="modal fade" id="viewIDModal" tabindex="-1" role="dialog" aria-labelledby="viewIDModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="teacher-name"> TEACHER NAME </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card shadow">
                        <div class="container-fluid">
                            <div class="row">
                                @if (auth()->user()->type !== 17)
                                    <div class="{{ auth()->user()->type !== 17 ? 'col-md-8' : '' }} ">
                                        <div class="p-2">
                                            <p style="color:rgb(39, 37, 37); font-weight: bold;">Select fullname format:</p>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="checkbox2">
                                                <label class="form-check-label" for="checkbox2">
                                                    <h4 id="current-person2">Person Name 2</h4>
                                                </label>
                                            </div>

                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="checkbox1">
                                                <label class="form-check-label" for="checkbox1">
                                                    <h4 id="current-person">Person Name 1</h4>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div class="{{ auth()->user()->type === 17 ? 'col-12' : 'col-md-4' }}">
                                    <div class="d-flex justify-content-between">
                                        @if (auth()->user()->type === 17)
                                            <button type="button" class="btn btn-outline-primary m-2 download"><i
                                                    class="fas fa-download"></i>
                                                Download</button>
                                            <button type="button" class="btn btn-danger m-2 send-to-print"><i
                                                    class="fas fa-share-square"></i>
                                                Send to Print</button>
                                            {{-- @else
                                            <div class="ml-auto p-2">
                                                <button type="button" class="btn btn-success ready-to-print"><i
                                                        class="fas fa-user-check mr-1"></i>
                                                    READY FOR PRINT</button>
                                            </div>

                                            <div class="ml-auto p-2">
                                                <button type="button" class="btn btn-danger cancel-print"><i
                                                        class="fas fa-times mr-1"></i>
                                                    Cancel</button>
                                            </div> --}}
                                        @endif
                                        {{-- <button type="button" class="btn btn-info ml-auto mt-2 view-id-details"> <i
                                    class="fas fa-info-circle mr-1"></i> View ID Details</button> --}}

                                    </div>
                                </div>
                            </div>

                            <div id="preview"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Modal Edit Info --}}
    <div class="modal fade" id="editInfoModal" tabindex="-1" role="dialog" aria-labelledby="editInfoModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editInfoModalLabel">Edit Staff Info</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card shadow">
                        <form>
                            <div class="card-body">
                                <div class="form-group">
                                    <label class="mb-1">Firstname</label>
                                    <input type="text" class="form-control form-control-sm-form" id="firstname"
                                        placeholder="Enter firstname" readonly>
                                </div>
                                <div class="form-group">
                                    <label class="mb-1">Middlename</label>
                                    <input type="text" class="form-control form-control-sm-form" id="middlename"
                                        placeholder="Enter middlename" readonly>
                                </div>
                                <div class="form-group">
                                    <label class="mb-1">Lastname</label>
                                    <input type="text" class="form-control form-control-sm-form" id="lastname"
                                        placeholder="Enter lastname" readonly>
                                </div>

                                <div class="form-group">
                                    <label class="mb-1">Contact No.</label>
                                    <input type="number" class="form-control form-control-sm-form" id="contactno"
                                        placeholder="Enter Phone">
                                </div>

                                <div class="form-group">
                                    <label class="mb-1">Select Template</label>
                                    <select class="form-control select2 form-control-sm-form" id="select-template"
                                        style="width: 100%;">
                                    </select>
                                </div>
                            </div>
                            <div class="card-footer d-flex">
                                <div>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                    <button type="button" class="btn btn-success update_teacher">Save</button>
                                </div>
                                <a type="button" class="done_print ml-auto">Mark as done</a>

                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>



    <div class="row" style="width: 100%;">
        <div class="col-12 pt-3" id="col1" style="width: 100%;">
            <div class="container-fluid">
                <h2 class="p-2" id="orgname"> {{ DB::table('schoolinfo')->get()[0]->schoolname }} </h2>
                <div style="padding: 10px; height: 80vh">
                    <div class="d-flex justify-content-between">
                        <p>TEACHERS RECORDS</p>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="card shadow">
                                <div class="card-body p-3">
                                    <div class="row mt-2">
                                        <div class="col-md-12" style="font-size:.9rem !important; ">

                                            <table class="table-hover table table-striped table-sm table-bordered"
                                                id="teachers_datatable" width="100%" style="width: 100%;">
                                                <thead>
                                                    <tr>
                                                        <th width="10%">Title</th>
                                                        <th width="10%">Lastname</th>
                                                        <th width="10%">Firstname</th>
                                                        <th width="5%">Middlename</th>
                                                        <th width="10%">Usertype</th>
                                                        <th width="10%">Phone</th>
                                                        <th width="5%">Status</th>
                                                        <th width="10%">Template</th>
                                                        <th width="10%" class="align-middle">Print</th>
                                                        <th width="10%" class="align-middle"></th>
                                                        <th width="10%" class="align-middle"></th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footerjavascript')
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/4.5.0/fabric.min.js"></script>
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.5/jszip.min.js"></script>

    <script>
        var canvas2 = new fabric.Canvas('canvas2');
        var canvas = new fabric.Canvas('canvas');
        var templates;
        var personname;
        var currentid = 0;
		
		 var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000
        });
		
        $(document).ready(function() {
            get_templates();
            load_teachers();

            $(document).on('click', '.edit_item', function(event) {
                currentid = $(this).attr('data-id');
                console.log(currentid);
                edit_item(currentid);
            });

            $(document).on('click', '.update_teacher', function(event) {
                update_teacher(currentid);
            });

            $(document).on('click', '.btn_print', function(event) {
                // $('#checkbox1').prop("checked", false);
                // $('#checkbox2').prop("checked", false);
                currentid = $(this).attr('data-id');
                print(currentid);
            });

            $(document).on('click', '.send-to-print', function() {
                openPrintWindow()
            });

            $(document).on('click', '.download', function(event) {
                // $('.canva-wrapper').show()
                console.log(personname)
                downloadSvgAsPngWithFabric(svgStringFront, svgStringBack, personname,
                    'frontid.png', 'backid.png');
                // downloadSvgAsPngWithFabric(svgStringBack, $('#current-person').text(), 'backid.png');
            });

        });

        function downloadSvgAsPngWithFabric(svgString, svgString2, studname, fileName, fileName2) {
            var zip = new JSZip();

            function processSvg(svg, filename) {
                return new Promise(resolve => {


                    fabric.loadSVGFromString(svg, function(objects, options) {
                        var loadedObjects = fabric.util.groupSVGElements(objects, options);
                        console.log(loadedObjects)
                        canvas2.clear();
                        canvas2.add(loadedObjects).renderAll();

                        loadedObjects.forEachObject(function(obj) {


                            if (obj.type === 'text') {
                                obj.set({
                                    textAlign: 'center',
                                    originX: 'center', // Set originX to 'center' for proper centering
                                });
                            }
                        });

                        // var coverImage = getObjectById(canvas2, 'cover');

                        // textList.forEach(text => {

                        //     var myrect = rectsList.find(obj => obj.id == text.id);
                        //     if (myrect && text.originX === 'left') {
                        //         // console.log('left text')
                        //         // console.log(text.textAlign)

                        //         // text.set({
                        //         //     left: myrect.left,
                        //         //     top: myrect.top,
                        //         // });

                        //         // canvas2.renderAll();
                        //     }

                        //     // if (myrect && text.id === 'name') {
                        //     //     console.log('Center text')
                        //     //     console.log(`this is right: ${text}`)
                        //     //     var coverWidth = coverImage.width;
                        //     //     var textWidth = text.width * text
                        //     //         .scaleX; // Measure the width of the text

                        //     //     // Calculate the remaining space
                        //     //     var remainingSpace = coverWidth - textWidth;

                        //     //     // Center the text by adjusting the left position
                        //     //     text.set({
                        //     //         left: coverImage.left + remainingSpace / 2,
                        //     //         top: myrect.top,
                        //     //         originX: 'center',
                        //     //         originY: 'center',
                        //     //     });
                        //     // }
                        // });

                        canvas2.renderAll();

                        // var boundingBox = coverImage.getBoundingRect();

                        canvas2.setDimensions({
                            width: loadedObjects.width,
                            height: loadedObjects.height,
                            redraw: true,
                        });

                        // canvas2.setDimensions({
                        //     width: boundingBox.width,
                        //     height: boundingBox.height,
                        //     left: boundingBox.left,
                        //     top: boundingBox.top,
                        //     redraw: true,
                        // });

                        var dataUrl = canvas2.toDataURL({
                            format: 'png',
                            multiplier: 1,
                        });

                        zip.file(`${studname}/${filename || 'image.png'}`, dataUrl.split('base64,')[1], {
                            base64: true
                        });

                        canvas2.clear();
                        resolve();
                    });
                });
            }

            // Process the first SVG
            processSvg(svgString, fileName).then(() => {
                // Process the second SVG after the first one is done
                processSvg(svgString2, fileName2).then(() => {
                    // Generate the zip file after both SVGs are processed
                    zip.generateAsync({
                        type: 'blob'
                    }).then(function(content) {
                        var anchor = document.createElement('a');
                        anchor.href = URL.createObjectURL(content);
                        anchor.download = `${studname}_images.zip`;
                        document.body.appendChild(anchor);
                        anchor.click();
                        document.body.removeChild(anchor);
                    });
                });
            });
        }

        function loadImageData(svgString) {
            return new Promise((resolve) => {
                fabric.loadSVGFromString(svgString, function(objects, options) {
                    var loadedObjects = fabric.util.groupSVGElements(objects, options);
                    canvas.add(loadedObjects).renderAll();

                    var maxWidth = loadedObjects.width;

                    loadedObjects.forEachObject(function(obj) {
                        if (obj.type === 'text') {
                            var originalFontSize = obj.get('fontSize');

                            // Trim text content to remove leading and trailing spaces
                            var trimmedText = obj.text.trim();

                            // Calculate a scale factor based on the available width
                            var scaleToFit = maxWidth / obj.width;
                            var newFontSize = originalFontSize * scaleToFit;

                            // Change font size only if the text width is greater than loadedObjects.width
                            if (obj.width > loadedObjects.width) {
                                obj.set({
                                    fontSize: newFontSize,
                                });
                            }

                            // Calculate the left position to center the text within the loaded object
                            var leftPosition = loadedObjects.left - loadedObjects.width / 2 + obj
                                .width * obj.scaleX / 2;

                            if (obj.width > loadedObjects.width) {
                                obj.set({
                                    fontSize: newFontSize,
                                    left: leftPosition,
                                });
                            }

                            // Set the trimmed text and other properties while keeping other properties intact
                            obj.set({
                                text: trimmedText,
                                top: obj.top + 20.0,
                                textAlign: 'center',
                                originX: 'center',
                                width: loadedObjects.width,
                            });

                            canvas.renderAll();
                        }
                    });

                    // Set canvas dimensions to match the loaded SVG
                    canvas.setDimensions({
                        width: loadedObjects.width,
                        height: loadedObjects.height,
                        redraw: true,
                    });

                    // Disable retina scaling for clear image
                    var imageDataUrl = canvas.toDataURL({
                        format: 'png',
                        multiplier: 1,
                        enableRetinaScaling: false,
                    });

                    resolve(imageDataUrl);
                    canvas.clear();
                });
            });
        }


        async function openPrintWindow() {
            var frontimg = await loadImageData(svgStringFront);
            var backimg = await loadImageData(svgStringBack);

            var printWindow = window.open('', '_blank');
            printWindow.document.write('<html><head><title>Print</title></head>');

            printWindow.document.write('<body style="margin: 0; padding: 0; height: 100vh; width: 100vw;">');

            // Set the size of the paper
            printWindow.document.write(
                '<style>@media print { body { margin: 0; } @page { size: 100% 100%; margin: 0; } }</style>');

            // Add front and back images
            printWindow.document.write('<img style="width: 100%; height: 100%;" src="' + frontimg + '" />');
            printWindow.document.write('<img style="width: 100%; height: 100%;" src="' + backimg + '" />');

            printWindow.document.write('</body></html>');
            printWindow.document.close();

            // Wait for the content to load before printing
            printWindow.onload = function() {
                // Remove the title and other unnecessary elements
                printWindow.document.head.innerHTML = '';

                // Print the document
                printWindow.print();
            };
        }

        function get_templates() {
            $.ajax({
                type: 'GET',
                url: '{{ route('templates') }}',
                success: function(data) {
                    console.log(data)
                    results = data.templates;
                    if (results.length) {
                        // Clear existing options
                        $('#select-template').empty();
                        // $('#select-template2').empty();

                        // Add a default option
                        $('#select-template').append('<option value="">Select Template</option>');
                        // $('#select-template2').append('<option value="">Select Template</option>');

                        $.each(results, function(index, value) {
                            value.text = value.name;
                        });
                        console.log(results)

                        // Initialize Select2 after updating options
                        $('#select-template').select2({
                            data: results,
                            allowClear: true,
                            placeholder: "Select Template",
                            theme: 'bootstrap4'
                        });
                    }
                }
            });
        }

        function load_teachers() {
            $.ajax({
                type: 'GET',
                url: '{{ route('teachers') }}',
                success: function(data) {
                    console.log(data);
                    propagate_database(data);
                }
            })
        }

        function propagate_database(data) {
            $("#teachers_datatable").DataTable({
                autowidth: false,
                destroy: true,
                data: data,
                stateSave: true,
                lengthChange: false,
                columns: [{
                        "data": "title"
                    },
                    {
                        "data": "lastname"
                    },
                    {
                        "data": "firstname"
                    },
                    {
                        "data": "middlename"
                    },
                    {
                        "data": "utype"
                    },
                    {
                        "data": "phonenumber"
                    },
                    {
                        "data": 'status'
                    },
                    {
                        "data": 'templatename'
                    },
                    {
                        "data": null
                    },
                    {
                        "data": null
                    },
                    {
                        "data": null
                    },
                ],
                columnDefs: [{
                        'targets': 1,
                        'orderable': false,
                        'createdCell': function(td, cellData, rowData, row, col) {
                            var disabled = '';
                            var buttons =
                                '<a>' + rowData.firstname ? rowData.lastname.toUpperCase() : null + '</a>';
                            $(td)[0].innerHTML = buttons
                        }
                    },
                    {
                        'targets': 2,
                        'orderable': false,
                        'createdCell': function(td, cellData, rowData, row, col) {
                            var disabled = '';
                            var buttons =
                                '<a>' + rowData.firstname.toUpperCase() + '</a>';
                            $(td)[0].innerHTML = buttons
                        }
                    },

                    {
                        'targets': 8,
                        'orderable': false,
                        'createdCell': function(td, cellData, rowData, row, col) {
                            var buttons =
                                `<a class="btn_print" target="_blank" data-id="${rowData.userid}" ><i class="fas fa-print " style="color: magenta;"></i></a>`;
                            $(td)[0].innerHTML = buttons
                            $(td).addClass('text-center')
                            $(td).addClass('align-middle')
                        }
                    },

                    {
                        'targets': 9,
                        'orderable': false,
                        'createdCell': function(td, cellData, rowData, row, col) {
                            var buttons =
                                `<a type="button" href="javascript:void(0)" class="edit_item" data-id="${rowData.userid}" ><i class="far fa-edit text-primary"></i></a>`;
                            $(td)[0].innerHTML = buttons
                            $(td).addClass('text-center')
                            $(td).addClass('align-middle')
                        }
                    },
                    {
                        'targets': 10,
                        'orderable': false,
                        'createdCell': function(td, cellData, rowData, row, col) {
                            var buttons =
                                '<a type="button" href="javascript:void(0)" class="delete_item" data-id="' +
                                rowData.userid +
                                '"><i class="far fa-trash-alt text-danger"></i></a>';
                            $(td)[0].innerHTML = buttons
                            $(td).addClass('text-center')
                            $(td).addClass('align-middle')
                        }
                    },
                ]

            });
        }

        function edit_item(id) {
            $.ajax({
                type: 'GET',
                data: {
                    id: id,
                },
                url: '{{ route('get.teacher') }}',
                success: function(data) {
                    var teacher = data.teacher[0];
                    console.log(teacher)
                    $('#firstname').val(teacher.firstname)
                    $('#middlename').val(teacher.middlename)
                    $('#lastname').val(teacher.lastname);
                    $('#contactno').val(teacher.phonenumber);
                    $('#select-template').val(teacher.template).trigger('change');

                    $('#editInfoModal').modal()
                }
            })
        }

        function update_teacher(id) {
            $.ajax({
                type: 'GET',
                data: {
                    id: id,
                    templateid: $('#select-template').val(),
                },
                url: '{{ route('update.teacher') }}',
                success: function(data) {
                    notify(data[0].statusCode, data[0].message);
                    $("#editInfoModal").modal("hide");
                    load_teachers();
                }
            })
        }

        function print(id) {
            $.ajax({
                type: 'GET',
                data: {
                    id: id,
                },
                url: '{{ route('get.teacher') }}',
                success: function(data) {
                    console.log(data)
                    teacherinfo = data.teacher[0];
                    templates = data.templates;
                    personname = teacherinfo.fullname ?? "";
                    if (teacherinfo.name) {
                        $('#student-name').text(personname);
                    } else {
                        $('#student-name').text(
                            `${teacherinfo.firstname ?? ''} ${teacherinfo.middlename ?? ''} ${teacherinfo.lastname ?? ''}`
                            .toUpperCase()
                        );
                    }
                    // grantee = teacherinfo.grantee;

                    if (!teacherinfo.template) {
                        notify("error",
                            `No template assigned for ${teacherinfo.firstname} ${teacherinfo.lastname}!`);
                        return;
                    }

                    if (teacherinfo.status === 1) {
                        $('.ready-to-print').hide();
                        $('.cancel-print').show();
                    } else if (teacherinfo.status === 2 || teacherinfo.status === 0) {
                        $('.ready-to-print').show();
                        $('.cancel-print').hide();
                    } else {
                        $('.ready-to-print').show();
                        $('.cancel-print').hide();
                    }

                    modifyTemplate(personname)
                }
            });
        }

        function modifyTemplate(strlbl) {

            var fullname =
                `${teacherinfo.lastname ?? ''}, ${teacherinfo.firstname ?? ''} ${teacherinfo.middlename ?? ''} ${teacherinfo.suffix ?? ''}`
            var fullname2 =
                `${teacherinfo.firstname ?? ''} ${teacherinfo.middlename ?? ''} ${teacherinfo.lastname ?? ''} ${teacherinfo.suffix ?? ''}`
            // $('#current-person2').text(fullname);
            // $('#current-person').text(fullname2);
            $('#viewIDModal').modal();
            // $('#col1').addClass('col-md-8');
            // $('#col2').addClass('col-md-4');

            var front = templates.find(temp => temp.type === "front");
            var back = templates.find(temp => temp.type === "back");

            var parser = new DOMParser();
            var svgDocFront = parser.parseFromString(front.template, 'image/svg+xml');
            var svgDocBack = parser.parseFromString(back.template, 'image/svg+xml');

            console.log(svgDocFront)
            console.log(svgDocBack)

            // console.log(svgDocFront)
            // var esc = svgDocFront.querySelector(`g[id=esc]`);
            // if (esc && grantee != 2) {
            //     console.log('This is not ESC')
            //     console.log(esc)
            //     esc.style.display = 'none';
            // }

            // // Update properties based on the JSON data
            $.each(teacherinfo, function(key, value) {
                var gElement = svgDocFront.querySelector(`g[id=${key}]`);
                var gElement2 = svgDocBack.querySelector(`g[id=${key}]`);

                if (gElement) {
                    // console.log(gElement);
                    if (key == "picurl") {
                        var imageElement = gElement.querySelector('image');
                        if (imageElement) {
                            // Change the xlink:href attribute
                            var newImageUrl =
                                `${window.location.protocol}//${window.location.hostname}/${value}`;
                            imageElement.setAttributeNS('http://www.w3.org/1999/xlink',
                                'xlink:href',
                                newImageUrl);
                        }
                    } else if (key == "name") {

                        var textElement = gElement.querySelector('text');

                        // Find the < tspan > element within the < text > element
                        var tspanElement = textElement.querySelector('tspan');

                        if (tspanElement) {
                            // if (strlbl != '') {
                            //     tspanElement.textContent = strlbl;
                            // } else {
                            //     tspanElement.textContent = value;
                            // }
                            tspanElement.textContent = value;
                        }
                    } else {
                        var textElement = gElement.querySelector('text');

                        // Find the < tspan > element within the < text > element
                        var tspanElement = textElement.querySelector('tspan');

                        if (tspanElement) {
                            // Set the new text content
                            tspanElement.textContent = value;
                        }
                    }
                }

                if (gElement2) {
                    // console.log(gElement2);
                    if (key == "picurl") {
                        var imageElement = gElement.querySelector('image');
                        if (imageElement) {
                            // Change the xlink:href attribute
                            var newImageUrl =
                                `${window.location.protocol}//${window.location.hostname}/${value}`;
                            imageElement.setAttributeNS('http://www.w3.org/1999/xlink',
                                'xlink:href',
                                newImageUrl);
                        }
                    } else {
                        var textElement = gElement2.querySelector('text');

                        // Find the < tspan > element within the < text > element
                        var tspanElement = textElement.querySelector('tspan');

                        if (tspanElement) {
                            // Set the new text content
                            tspanElement.textContent = value;
                        }
                    }
                }
            });

            var tempoFront = svgDocFront;
            var tempoBack = svgDocBack;

            // // Set the width and height attributes to 100%
            tempoFront.documentElement.setAttribute('width', '100%');
            tempoFront.documentElement.setAttribute('height', '100%');
            tempoBack.documentElement.setAttribute('width', '100%');
            tempoBack.documentElement.setAttribute('height', '100%');

            // Convert the modified SVG document back to an SVG string
            svgtempoFront = new XMLSerializer().serializeToString(tempoFront);
            svgtempoBack = new XMLSerializer().serializeToString(tempoBack);
            svgStringFront = new XMLSerializer().serializeToString(svgDocFront);
            svgStringBack = new XMLSerializer().serializeToString(svgDocBack);


            // Create a container div for the row
            var rowContainer = $('<div class="row"></div>');

            // Create a div for the first SVG with col-6 class
            var svgContainer1 = $('<div class="col-md-6"></div>').html(svgtempoFront);

            // Create a div for the second SVG with col-6 class
            var svgContainer2 = $('<div class="col-md-6"></div>').html(svgtempoBack);

            // Append the SVG containers to the row container
            rowContainer.append(svgContainer1);
            rowContainer.append(svgContainer2);

            // Empty the preview and append the row container
            $('#preview').empty().append(rowContainer);

        }
		
		function notify(code, message) {
            Toast.fire({
                type: code,
                title: message,
            });

        }
    </script>
@endsection
