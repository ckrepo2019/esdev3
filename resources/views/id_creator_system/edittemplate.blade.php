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
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <style>
        #scrollable-container {
            width: 100%;
            overflow-x: scroll;
            white-space: nowrap;
            margin-top: 5px;
        }

        .scrollable-image {
            display: inline-block;
            margin-right: 10px;
            cursor: pointer;
            max-height: 200px;
            height: 200px;
            width: auto;
            border-radius: 10px;

        }

        .tab-content {
            display: none;
            /* Hide the content by default */
        }

        .active-tab {
            display: block;
            /* Show the content when active */
        }

        .active-btn {
            background-color: rgb(220, 217, 217);

        }

        .tab-button {
            border-radius: 10px;
            color: black;
            cursor: pointer;
        }

        .notes-text {
            color: red;
            font-style: italic;
            margin-top: 10px;
        }

        #show-toggle {
            color: rgb(60, 57, 57);
            font-weight: normal;
            background-color: rgb(222, 216, 216);
            border-radius: 4px;
            padding: 2px;
            cursor: pointer;
        }

        .hidden {
            display: none;
        }
    </style>
@endsection

@section('modalSection')
    <div class="modal fade" id="addImageModal" tabindex="-1" role="dialog" aria-labelledby="addImageModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addImageModalLabel">Upload New Image</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card card-primary">
                        <form id="uploadForm" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="image">Image File </label>
                                    <input type="file" name="image" class="form-control" id="image">
                                    <br>
                                    <button type="button" class="btn btn-success upload">Upload Image</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="row" style="width: 100%;">
        <div class="col-md-8 col-12" style="width: 100%;">
            <div class="d-flex justify-content-between align-items-center p-2">
                <div id="title-editor" contenteditable="false">
                    <h1 id="template-name"
                        style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin-left: 10px; font-size: 40px;">
                        {{ $data[0]->name }}</h1>
                </div>
                <div class="d-flex">
                    {{-- <button type="button" class="btn btn-outline-secondary btn-sm btn-reset"><i class="fas fa-redo"></i>
                        Reset</button> --}}
                    <button type="button" class="btn btn-info btn-sm btn-merge ml-2">Merge</button>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-center">

                <div class="d-flex mb-2">

                    <a id="front" data-tab="tab1" class="px-2 py-1 tab-button active-btn"
                        style="font-weight: bold;">Front</a>
                    <a id="back" data-tab="tab2" class="ml-2 px-2 py-1 tab-button" style="font-weight: bold;">Back</a>
                </div>
            </div>
            <div class="d-flex justify-content-center align-items-center">
                <div id="tab1" class="tab-content active-tab" style="height: auto; width: auto; overflow: hidden;">
                    <canvas id="canvas" height="500" width="500"></canvas>
                </div>
            </div>

            <div class="d-flex justify-content-center align-items-center">
                <div id="tab2" class="tab-content" style="height: auto; width: auto;  overflow: hidden;">
                    <canvas id="canvas2" height="500" width="500"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4" style="width: 100%;">
            <div class="py-2">
                <h1 class="text-lg">Controls</h1>
            </div>
            <button class="btn btn-outline-success btn-sm new_label"> <i class="fas fa-plus-circle"></i> Add New
                Label</button>

            <p class="notes-text">
                <b> Notes: Use only label IDs or names that are exclusively available within the table.</b>
                <label id="show-toggle" class="px-2" style="font-size: 14px;">Show</label>
            </p>

            <table class="table-hover table table-striped table-sm table-bordered hidden" id="dataTable" width="100%"
                style="width: 100%;">
                <thead>
                    <tr>
                        <th class="align-middle">ID/NAME</th>
                        <th class="align-middle">DEFINITION</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>name</td>
                        <td>fullname of the person</td>
                    </tr>
                    <tr>
                        <td>contactno</td>
                        <td>phone number of the person</td>
                    </tr>
                    <tr>
                        <td>address</td>
                        <td>complete address</td>
                    </tr>
                    <tr>
                        <td>sid</td>
                        <td>sid of the student</td>
                    </tr>
                    <tr>
                        <td>lrn</td>
                        <td>lrn of the student</td>
                    </tr>
                    <tr>
                        <td>sydesc</td>
                        <td>school year</td>
                    </tr>
                    <tr>
                        <td>dob</td>
                        <td>birthdate of the student</td>
                    </tr>
                    <tr>
                        <td>gender</td>
                        <td>gender of the person</td>
                    </tr>
                    <tr>
                        <td>levelname</td>
                        <td>gradelevel of the student</td>
                    </tr>
                    <tr>
                        <td>emergencyperson</td>
                        <td>in case of emergency person name to notify</td>
                    </tr>
                    <tr>
                        <td>emergencyno</td>
                        <td>in case of emergency person contact no</td>
                    </tr>
                    <tr>
                        <td>emergencyaddress</td>
                        <td>in case of emergency person address</td>
                    </tr>
                </tbody>
            </table>

            <div class="row">
                <div class="col">
                    <button class="btn btn-outline-warning add_esc_logo">
                        New ESC Logo
                    </button>
                    <button class="btn btn-outline-warning add_school_logo">
                        New School Logo
                    </button>
                    <button class="btn btn-outline-warning add_new_profile">
                        New Profile
                    </button>
                </div>
            </div>

            <div class="row mt-4">
                <div id="text-controls" class="col-12" style="width: 100%;">
                    <label for="fontSize">Label ID:</label>
                    <select id="labelid">
                        <option value="name">name</option>
                        <option value="sid">sid</option>
                        <option value="lrn">lrn</option>
                        <option value="dob">dob</option>
                        <option value="sydesc">sydesc</option>
                        <option value="gender">gender</option>
                        <option value="levelname">levelname</option>
                        <option value="contactno">contactno</option>
                        <option value="emergencyno">emergencyno</option>
                        <option value="emergencyperson">emergencyperson</option>
                        <option value="emergencyaddress">emergencyaddress</option>
                        <!-- Add more options as needed -->
                    </select>
                    <br>
                    <label for="fontFamily">Font Family:</label>
                    <select id="fontFamily">
                        <option value="Arial">Arial</option>
                        <option value="Verdana">Verdana</option>
                        <option value="Helvetica">Helvetica</option>
                        <option value="Times New Roman">Times New Roman</option>
                        <option value="Courier New">Courier New</option>
                        <option value="Georgia">Georgia</option>
                        <option value="Impact">Impact</option>
                        <!-- Add more font options as needed -->
                    </select>
                    <br>
                    <label for="alignmentSelect">Text Alignment:</label>
                    <select id="alignmentSelect">
                        {{-- <option value="left">Left</option> --}}
                        <option value="center">Center</option>
                        {{-- <option value="right">Right</option> --}}
                    </select>
                    <br>
                    <label for="fontSize">Font Size:</label>
                    <input type="number" id="fontSize" value="20">
                    <br>
                    <label for="fontColor">Font Color:</label>
                    <input type="color" id="fontColor" value="#000000">
                    <br>
                    <label for="bold">Bold:</label>
                    <input type="checkbox" id="bold">
                    <br>
                    <label for="italic">Italic:</label>
                    <input type="checkbox" id="italic">
                    <br>
                    <label for="underline">Underline:</label>
                    <input type="checkbox" id="underline">
                    <br>
                    <button id="delete-text" class="btn btn-md btn-danger">Delete Text</button>
                </div>

                <div id="ctl-img" class="col-12" style="width: 100%;">
                    <h2 class="text-md">Image Controls</h2>
                    <label for="image-width2">Width:</label>
                    <input type="number" id="image-width2" />
                    <br>
                    <label for="image-height2">Height:</label>
                    <input type="number" id="image-height2" />
                    <br>
                    <label for="image-width">Size:</label>
                    <input type="range" id="image-width" min="1" max="1000" />
                    <br>
                    <label for="image-opacity">Opacity:</label>
                    <input type="range" id="image-opacity" min="0" max="1" step="0.1"
                        value="1" />
                    <br>
                    <label for="borderRadius">Border Radius:</label>
                    <input type="range" id="borderRadius" min="0" max="50" value="0">
                    <br>
                    <button id="delete-image" class="btn btn-sm btn-danger">Delete Image</button>
                </div>
            </div>

            <div class="row mt-4" style="width: 100%;">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="text-md">Choose Background</h2>
                        <button class="btn btn-outline-primary upload_img">Upload</button>
                    </div>
                    <div id="scrollable-container">

                        <!-- Add more images as needed -->
                    </div>
                </div>
            </div>

            <div id="preview-wrapper" style="width: 100%;">
                <h2 class="mt-2 text-md"> Preview </h2>
                <div class="my-2">
                    <button type="button" class="btn btn-success btn-save" style="width: 100%;">Update Template</button>
                </div>
                <div style="width: 100%; height: 600px;overflow-y: scroll; overflow-x: scroll;max-width: 100%;">
                    <div id="content" class="mr-2" style=" width: 100%; height: 700px;">

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('footerjavascript')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/4.5.0/fabric.min.js"></script>
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    </head>
    <script>
        var currentTemplate = '';
        var frontid;
        var backid;
        var canvaWidth;
        var canvaHeight;
        var unit;
        var rect;
        var currentCover = '';
        var currentCover2 = '';
        var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000
        });
        var textContainer = $('#text-container');
        var textIdCounter = 1;
        var svgString;
        var svgString2;
        $('#preview-wrapper').hide();
        $('#ctl-img').hide();
        $('#text-controls').hide();
        var canvas = new fabric.Canvas('canvas');
        var canvas2 = new fabric.Canvas('canvas2');
        var currentCanva = 1;
        var images = [];
        var storedLeft = canvas.width - 20; // Default value is 100
        var storedTop = 20; // Default value is 100
        var enabled = false;
        var activeObject = canvas.getActiveObject();
        $(document).ready(function() {
            currentTemplate = $('#template-name').text().trim();
            load_images();
            loadImagePositions();
            getLocalStorage();
            $.ajax({
                type: 'GET',
                url: '{{ route('get.byname.template') }}',
                data: {
                    name: currentTemplate,
                },
                success: function(data) {
                    console.log(data)
                    frontid = data[0].id;
                    backid = data[1].id;
                    currentCover = data[0].image;
                    currentCover2 = data[1].image;
                    loadSVGIntoCanvas(data[0].template, data[1].template);
                }
            })

            $('.add_esc_logo').on('click', function(event) {
                // load ESC logo in the canvas
                fabric.Image.fromURL('{{ asset('assets/ESC-logo.png') }}', function(img1) {
                    const desiredWidth = 100; // Adjust this value to your desired width
                    const scale = desiredWidth / img1.width;
                    var updatedWidth;
                    var updatedHeight
                    // Scale the image
                    img1.scale(scale);

                    // Set the position of the image
                    img1.set({
                        top: 0,
                        left: 300,
                        id: 'esc',
                    });
                    images.push(img1);
                    // Add the image to the canvas
                    if (currentCanva === 1) {
                        canvas.add(img1);
                        canvas.bringToFront(img1)
                    } else {
                        canvas2.add(img1);
                        canvas2.bringToFront(img1)
                    }

                    img1.on('scaling', function(options) {
                        if (activeObject && activeObject.type === 'image') {
                            var scaleX = activeObject.scaleX;
                            var scaleY = activeObject.scaleY;

                            // Calculate the updated width and height
                            updatedWidth = activeObject.width * scaleX;
                            updatedHeight = activeObject.height * scaleY;

                            console.log(activeObject);
                            // Update the input fields
                            $('#image-width2').val(updatedWidth);
                            $('#image-height2').val(updatedHeight);
                        }
                    });

                    // Assume img1 is your Fabric.js image object
                    img1.on('selected', function() {
                        // Get the active object (selected object)
                        if (currentCanva === 1) {
                            activeObject = canvas.getActiveObject();
                        } else {
                            activeObject = canvas2.getActiveObject();
                        }

                        if (activeObject && activeObject.type === 'image') {
                            var scaleX = activeObject.scaleX;
                            var scaleY = activeObject.scaleY;

                            // Calculate the updated width and height
                            updatedWidth = activeObject.width * scaleX;
                            updatedHeight = activeObject.height * scaleY;

                            console.log(activeObject);
                            // Update the input fields
                            $('#image-width2').val(updatedWidth);
                            $('#image-height2').val(updatedHeight);
                        }
                    });
                });
            })

            $('.add_school_logo').on('click', function(event) {
                // load maryknoll logo in the canvas
                fabric.Image.fromURL('{{ asset('assets/maryknoll-logo.png') }}', function(img1) {
                    const desiredWidth = 100; // Adjust this value to your desired width
                    const scale = desiredWidth / img1.width;
                    var updatedWidth;
                    var updatedHeight
                    // Scale the image
                    img1.scale(scale);

                    // Set the position of the image
                    img1.set({
                        top: 100,
                        left: 200,
                        id: 'logo',
                    });
                    images.push(img1);
                    // Add the image to the canvas
                    if (currentCanva === 1) {
                        canvas.add(img1);
                        canvas.bringToFront(img1)
                    } else {
                        canvas2.add(img1);
                        canvas2.bringToFront(img1)
                    }

                    img1.on('scaling', function(options) {
                        if (activeObject && activeObject.type === 'image') {
                            var scaleX = activeObject.scaleX;
                            var scaleY = activeObject.scaleY;

                            // Calculate the updated width and height
                            updatedWidth = activeObject.width * scaleX;
                            updatedHeight = activeObject.height * scaleY;

                            console.log(activeObject);
                            // Update the input fields
                            $('#image-width2').val(updatedWidth);
                            $('#image-height2').val(updatedHeight);
                        }
                    });

                    // Assume img1 is your Fabric.js image object
                    img1.on('selected', function() {
                        // Get the active object (selected object)
                        if (currentCanva === 1) {
                            activeObject = canvas.getActiveObject();
                        } else {
                            activeObject = canvas2.getActiveObject();
                        }

                        if (activeObject && activeObject.type === 'image') {
                            var scaleX = activeObject.scaleX;
                            var scaleY = activeObject.scaleY;

                            // Calculate the updated width and height
                            updatedWidth = activeObject.width * scaleX;
                            updatedHeight = activeObject.height * scaleY;

                            console.log(activeObject);
                            // Update the input fields
                            $('#image-width2').val(updatedWidth);
                            $('#image-height2').val(updatedHeight);
                        }
                    });
                });
            });

            $('.add_new_profile').on('click', function(event) {
                // Load an image onto the canvas
                fabric.Image.fromURL('{{ asset('storage/STUDENT/113230000102242023091211.png') }}',
                    function(img1) {
                        const desiredWidth = 100; // Adjust this value to your desired width
                        const scale = desiredWidth / img1.width;
                        var updatedWidth;
                        var updatedHeight
                        // Scale the image
                        img1.scale(scale);

                        // Set the position of the image
                        img1.set({
                            left: 0,
                            top: 0,
                            id: 'picurl',
                        });
                        images.push(img1);
                        // Add the image to the canvas
                        if (currentCanva === 1) {
                            canvas.add(img1);
                            canvas.bringToFront(img1)
                        } else {
                            canvas2.add(img1);
                            canvas2.bringToFront(img1)
                        }

                        img1.on('scaling', function(options) {
                            if (activeObject && activeObject.type === 'image') {
                                var scaleX = activeObject.scaleX;
                                var scaleY = activeObject.scaleY;

                                // Calculate the updated width and height
                                updatedWidth = activeObject.width * scaleX;
                                updatedHeight = activeObject.height * scaleY;

                                console.log(activeObject);
                                // Update the input fields
                                $('#image-width2').val(updatedWidth);
                                $('#image-height2').val(updatedHeight);
                            }
                        });

                        // Assume img1 is your Fabric.js image object
                        img1.on('selected', function() {
                            // Get the active object (selected object)
                            if (currentCanva === 1) {
                                activeObject = canvas.getActiveObject();
                            } else {
                                activeObject = canvas2.getActiveObject();
                            }

                            if (activeObject && activeObject.type === 'image') {
                                var scaleX = activeObject.scaleX;
                                var scaleY = activeObject.scaleY;

                                // Calculate the updated width and height
                                updatedWidth = activeObject.width * scaleX;
                                updatedHeight = activeObject.height * scaleY;

                                console.log(activeObject);
                                // Update the input fields
                                $('#image-width2').val(updatedWidth);
                                $('#image-height2').val(updatedHeight);
                            }
                        });
                    });
            })

            $('#show-toggle').on('click', function(event) {
                var showbtn = $('#show-toggle')
                var dataTable = $('#dataTable');
                dataTable.toggleClass('hidden');
                $('#show-toggle').text(dataTable.hasClass('hidden') ? 'Show' : 'Hide');
            });

            // $('.btn-reset').on('click', function(event) {
            //     loadLogoAndProfile();
            // })

            $('#front').on('click', function(even) {
                currentCanva = 1;
                $('#back').removeClass('active-btn');
                $(this).addClass('active-btn')
            });

            $('#back').on('click', function() {
                currentCanva = 2;
                console.log(currentCanva);
                $('#front').removeClass('active-btn');
                $(this).addClass('active-btn')
            });

            // Click event for tab buttons
            $('.tab-button').on('click', function() {
                // Hide all tabs
                $('.tab-content').removeClass('active-tab');

                // Show the selected tab
                $('#' + $(this).data('tab')).addClass('active-tab');
            });

            $('.new_label').on('click', function() {
                addTextToCanvas('Sampletext');
            });

            $(document).on('click', '.upload', function(event) {
                upload();
            });

            $('.upload_img').on('click', function() {
                $('#addImageModal').modal();
            })
            $("#title-editor").data("original-content", $("#title-editor").html());
            $('.btn-merge').on('click', function() {
                enabled = true;
                convertToSVG();
                $('#preview-wrapper').show();
            });

            $('.btn-save').on('click', function() {
                saveTemplate();
            });

            // Handle input changes
            $("#title-editor").on("input", function() {
                var editor = $("#title-editor");
                var newContent = editor.html().trim();

                // Check if the content is cleared
                if (newContent === "") {
                    // If cleared, restore the original content
                    editor.html(editor.data("original-content"));
                }
            });

            canvas.on('mouse:up', function(options) {
                activeObject = options.target;
                if (activeObject && activeObject instanceof fabric.IText) {
                    console.log('IText clicked:', activeObject.id);
                    console.log(activeObject)
                    editTextStyle();
                    $('#text-controls').show();
                }
                if (options.target && options.target.type === 'image') {
                    $('#ctl-img').show();
                }
            });

            canvas2.on('mouse:up', function(options) {
                var rect;
                activeObject = options.target;
                if (activeObject && activeObject instanceof fabric.IText) {
                    console.log('IText clicked:', activeObject.id);
                    // console.log(activeObject)
                    editTextStyle();

                    console.log(rect)
                    $('#text-controls').show();
                }
                if (options.target && options.target.type === 'image') {
                    $('#ctl-img').show();
                }
            });

            // Event listener for hiding text controls when canvas is clicked
            canvas.on('mouse:down', function() {
                $('#text-controls').hide();
                $('#ctl-img').hide();
            });
            canvas2.on('mouse:down', function() {
                $('#text-controls').hide();
                $('#ctl-img').hide();
            });

            // Event listener for opacity control
            $('#image-opacity').on('input', function() {
                if (currentCanva == 1) {
                    activeObject = canvas.getActiveObject();
                    if (activeObject && activeObject.type === 'image') {
                        activeObject.set('opacity', parseFloat($(this).val()));
                        canvas.renderAll();
                    }
                }

                if (currentCanva == 2) {
                    activeObject = canvas2.getActiveObject();
                    if (activeObject && activeObject.type === 'image') {
                        activeObject.set('opacity', parseFloat($(this).val()));
                        canvas2.renderAll();
                    }
                }
            });

            // Event listener for delete button
            $('#delete-image').on('click', function() {
                if (currentCanva == 1) {
                    activeObject = canvas.getActiveObject();
                    if (activeObject && activeObject.type === 'image') {
                        canvas.remove(activeObject);
                    }
                }

                if (currentCanva == 2) {
                    activeObject = canvas2.getActiveObject();
                    if (activeObject && activeObject.type === 'image') {
                        canvas2.remove(activeObject);
                    }
                }

            });

            $('#delete-text').on('click', function() {
                if (currentCanva == 1) {
                    activeObject = canvas.getActiveObject();
                    if (activeObject && activeObject.type === 'i-text') {
                        canvas.remove(activeObject);
                    }
                }
                if (currentCanva == 2) {
                    activeObject = canvas2.getActiveObject();
                    if (activeObject && activeObject.type === 'i-text') {
                        canvas2.remove(activeObject);
                    }
                }
            });

            // Event listener for object moving
            canvas.on('object:moving', function(e) {
                const movedObject = e.target;
                if (images.includes(movedObject)) {
                    saveImagePositions();
                }
            });
            canvas2.on('object:moving', function(e) {
                const movedObject = e.target;
                if (images.includes(movedObject)) {
                    saveImagePositions();
                }
            });

            // Attach the updateTextStyle function to the change event of controls
            $('#fontFamily, #fontSize, #fontColor, #bold, #italic, #underline, #labelid, #alignmentSelect').on(
                'input',
                function() {
                    updateTextStyle();
                });

        });

        function loadSVGIntoCanvas(frontsvg, backsvg) {
            fabric.loadSVGFromString(frontsvg, function(objects, options) {
                // var canvas = new fabric.Canvas('myCanvas', {
                //     selection: false
                // });

                // Add each object to the canvas
                objects.forEach(function(object) {
                    if (object.type === 'text') {
                        var originalFontSize = object.get('fontSize');

                        // Trim text content to remove leading and trailing spaces
                        var trimmedText = object.text.trim();

                        // Calculate a scale factor based on the available width
                        var scaleToFit = options.width / object.width;
                        var newFontSize = originalFontSize * scaleToFit;

                        // Change font size only if the text width is greater than loadedObjects.width
                        if (object.width > options.width) {
                            object.set({
                                fontSize: newFontSize,
                            });
                        }

                        // Calculate the left position to center the text within the loaded object
                        var leftPosition = options.left - options.width / 2 + object
                            .width * object.scaleX / 2;

                        if (object.width > options.width) {
                            object.set({
                                fontSize: newFontSize,
                                left: leftPosition,
                            });
                        }

                        // Set the trimmed text and other properties while keeping other properties intact
                        object.set({
                            text: trimmedText,
                            top: object.top + 20.0,
                            textAlign: 'center',
                            originX: 'center',
                            width: options.width,
                        });

                        canvas.renderAll();
                    }

                    if (typeof object.id === 'undefined') {
                        console.log(object);
                        canvas.setBackgroundImage(object, canvas.renderAll.bind(canvas), {});
                    } else {
                        canvas.add(object);
                    }
                });

                // Set canvas dimensions to match the loaded SVG
                canvas.setDimensions({
                    width: options.width,
                    height: options.height,
                    redraw: true,
                });

                // Make canvas elements selectable and editable
                canvas.selection = true;
                canvas.renderAll();
            });

            fabric.loadSVGFromString(backsvg, function(objects, options) {
                // var canvas = new fabric.Canvas('myCanvas', {
                //     selection: false
                // });

                // Add each object to the canvas
                objects.forEach(function(object) {
                    if (object.type === 'text') {
                        object.set({
                            textAlign: 'center',
                            originX: 'center',
                        });
                    }

                    if (typeof object.id === 'undefined') {
                        console.log(object);
                        canvas2.setBackgroundImage(object, canvas2.renderAll.bind(canvas2), {});
                    } else {
                        canvas2.add(object);
                    }
                });

                // Set canvas dimensions to match the loaded SVG
                canvas2.setDimensions({
                    width: options.width,
                    height: options.height,
                    redraw: true,
                });

                // Make canvas elements selectable and editable
                canvas2.selection = true;
                canvas2.renderAll();
            });
        }


        function loadLogoAndProfile() {
            canvas.clear();
            // Load an image onto the canvas
            fabric.Image.fromURL('{{ asset('storage/STUDENT/113230000102242023091211.png') }}', function(img1) {
                const desiredWidth = 100; // Adjust this value to your desired width
                const scale = desiredWidth / img1.width;
                var updatedWidth;
                var updatedHeight
                // Scale the image
                img1.scale(scale);

                // Set the position of the image
                img1.set({
                    left: 0,
                    top: 0,
                    id: 'picurl',
                });
                images.push(img1);
                // Add the image to the canvas
                canvas.add(img1);
                canvas.bringToFront(img1)

                img1.on('scaling', function(options) {
                    if (activeObject && activeObject.type === 'image') {
                        var scaleX = activeObject.scaleX;
                        var scaleY = activeObject.scaleY;

                        // Calculate the updated width and height
                        updatedWidth = activeObject.width * scaleX;
                        updatedHeight = activeObject.height * scaleY;

                        console.log(activeObject);
                        // Update the input fields
                        $('#image-width2').val(updatedWidth);
                        $('#image-height2').val(updatedHeight);
                    }
                });

                // Assume img1 is your Fabric.js image object
                img1.on('selected', function() {
                    // Get the active object (selected object)
                    activeObject = canvas.getActiveObject();

                    if (activeObject && activeObject.type === 'image') {
                        var scaleX = activeObject.scaleX;
                        var scaleY = activeObject.scaleY;

                        // Calculate the updated width and height
                        updatedWidth = activeObject.width * scaleX;
                        updatedHeight = activeObject.height * scaleY;

                        console.log(activeObject);
                        // Update the input fields
                        $('#image-width2').val(updatedWidth);
                        $('#image-height2').val(updatedHeight);
                    }
                });
            });
            // load maryknoll logo in the canvas
            fabric.Image.fromURL('{{ asset('assets/maryknoll-logo.png') }}', function(img1) {
                const desiredWidth = 100; // Adjust this value to your desired width
                const scale = desiredWidth / img1.width;
                var updatedWidth;
                var updatedHeight
                // Scale the image
                img1.scale(scale);

                // Set the position of the image
                img1.set({
                    top: 100,
                    left: 200,
                    id: 'logo',
                });
                images.push(img1);
                // Add the image to the canvas
                canvas.add(img1);
                canvas.bringToFront(img1)

                img1.on('scaling', function(options) {
                    if (activeObject && activeObject.type === 'image') {
                        var scaleX = activeObject.scaleX;
                        var scaleY = activeObject.scaleY;

                        // Calculate the updated width and height
                        updatedWidth = activeObject.width * scaleX;
                        updatedHeight = activeObject.height * scaleY;

                        console.log(activeObject);
                        // Update the input fields
                        $('#image-width2').val(updatedWidth);
                        $('#image-height2').val(updatedHeight);
                    }
                });

                // Assume img1 is your Fabric.js image object
                img1.on('selected', function() {
                    // Get the active object (selected object)
                    activeObject = canvas.getActiveObject();

                    if (activeObject && activeObject.type === 'image') {
                        var scaleX = activeObject.scaleX;
                        var scaleY = activeObject.scaleY;

                        // Calculate the updated width and height
                        updatedWidth = activeObject.width * scaleX;
                        updatedHeight = activeObject.height * scaleY;

                        console.log(activeObject);
                        // Update the input fields
                        $('#image-width2').val(updatedWidth);
                        $('#image-height2').val(updatedHeight);
                    }
                });
            });
            // load ESC logo in the canvas
            fabric.Image.fromURL('{{ asset('assets/ESC-logo.png') }}', function(img1) {
                const desiredWidth = 100; // Adjust this value to your desired width
                const scale = desiredWidth / img1.width;
                var updatedWidth;
                var updatedHeight
                // Scale the image
                img1.scale(scale);

                // Set the position of the image
                img1.set({
                    top: 0,
                    left: 300,
                    id: 'esc',
                });
                images.push(img1);
                // Add the image to the canvas
                canvas.add(img1);
                canvas.bringToFront(img1)

                img1.on('scaling', function(options) {
                    if (activeObject && activeObject.type === 'image') {
                        var scaleX = activeObject.scaleX;
                        var scaleY = activeObject.scaleY;

                        // Calculate the updated width and height
                        updatedWidth = activeObject.width * scaleX;
                        updatedHeight = activeObject.height * scaleY;

                        console.log(activeObject);
                        // Update the input fields
                        $('#image-width2').val(updatedWidth);
                        $('#image-height2').val(updatedHeight);
                    }
                });

                // Assume img1 is your Fabric.js image object
                img1.on('selected', function() {
                    // Get the active object (selected object)
                    activeObject = canvas.getActiveObject();

                    if (activeObject && activeObject.type === 'image') {
                        var scaleX = activeObject.scaleX;
                        var scaleY = activeObject.scaleY;

                        // Calculate the updated width and height
                        updatedWidth = activeObject.width * scaleX;
                        updatedHeight = activeObject.height * scaleY;

                        console.log(activeObject);
                        // Update the input fields
                        $('#image-width2').val(updatedWidth);
                        $('#image-height2').val(updatedHeight);
                    }
                });
            });
        }

        // Function to update canvas dimensions
        function updateCanvasDimensions(newImg) {
            canvaWidth = $('#canvasWidth').val();
            canvaHeight = $('#canvasHeight').val();
            unit = $('#unit').val();

            // Convert dimensions based on the selected unit
            if (unit === 'inches') {
                canvaWidth *= 96; // 1 inch = 96 pixels
                canvaHeight *= 96;
            } else if (unit === 'cm') {
                canvaWidth *= 37.8; // 1 cm = 37.8 pixels (approximately)
                canvaHeight *= 37.8;
            }

            if (currentCanva == 1) {

                canvas.setDimensions({
                    width: canvaWidth,
                    height: canvaHeight,
                });

                canvas.setBackgroundImage(newImg, canvas.renderAll.bind(canvas), {
                    scaleX: canvaWidth / newImg.width,
                    scaleY: canvaHeight / newImg.height
                });
            }

            if (currentCanva == 2) {

                canvas2.setDimensions({
                    width: canvaWidth,
                    height: canvaHeight,
                });

                canvas2.setBackgroundImage(newImg, canvas2.renderAll.bind(canvas2), {
                    scaleX: canvaWidth / newImg.width,
                    scaleY: canvaHeight / newImg.height
                });
            }
        }

        function upload() {
            // Get the file input element
            var fileInput = $('#image')[0];
            console.log(fileInput);

            // Check if the file input has a file selected
            if (!fileInput.files.length) {
                notify("warning", "Please select a file for upload")

                return;
            }
            var formData = new FormData($('#uploadForm')[0]);
            console.log(formData);
            $.ajax({
                type: 'POST', // Make sure this is set to POST
                data: formData,
                processData: false,
                contentType: false,
                url: '{{ route('file.upload') }}',
                success: function(data) {
                    console.log(data);
                    load_images();
                    notify(data[0].statusCode, data[0].message)

                }
            });
        }

        // function editRectPosition() {
        //     if (currentCanva == 1) {
        //         console.log(' edit rect')
        //         var rectobj = canvas.getObjects().find(obj => obj.type === 'rect' && activeObject.rectid ==
        //             obj.rectid);

        //         if (rectobj) {
        //             rectobj.set({
        //                 // height: activeObject.height,
        //                 // width: activeObject.width,
        //                 left: activeObject.left,
        //                 top: activeObject.top,
        //                 // bottom: activeObject.bottom,
        //             })
        //             canvas.renderAll()
        //         }

        //         // activeObject.set({
        //         //     height: rectobj.height,
        //         // })
        //     }

        //     if (currentCanva == 2) {
        //         console.log(' edit rect')
        //         var rectobj = canvas2.getObjects().find(obj => obj.type === 'rect' && activeObject.rectid ==
        //             obj.rectid);

        //         if (rectobj) {
        //             rectobj.set({
        //                 height: activeObject.height,
        //                 width: activeObject.width,
        //                 left: activeObject.left,
        //                 top: activeObject.top,
        //             })
        //             canvas2.renderAll()
        //         }
        //     }
        // }

        function editTextStyle() {

            if (currentCanva == 1) {

                const activeObject = canvas.getActiveObject();
            }
            if (currentCanva == 2) {

                const activeObject = canvas2.getActiveObject();
            }

            if (activeObject && activeObject.type === 'i-text') {
                const labelID = $('#labelid');
                const fontFamily = $('#fontFamily');
                const alignment = $('#alignmentSelect');
                const fontSize = $('#fontSize');
                const fontColor = $('#fontColor');
                const boldCheckbox = $('#bold');
                const italicCheckbox = $('#italic');
                const underlineCheckbox = $('#underline');

                // Set values of controls based on active object's properties
                labelID.val(activeObject.id);
                fontFamily.val(activeObject.fontFamily);
                alignment.val(activeObject.originX);
                fontSize.val(activeObject.fontSize);
                fontColor.val(activeObject.fill);
                boldCheckbox.prop('checked', activeObject.fontWeight === 'bold');
                italicCheckbox.prop('checked', activeObject.fontStyle === 'italic');
                underlineCheckbox.prop('checked', activeObject.textDecoration === 'underline');

                // editRectPosition()
            }
        }

        function updateTextStyle() {
            if (currentCanva == 1) {
                activeObject = canvas.getActiveObject();
            }

            if (currentCanva == 2) {
                activeObject = canvas2.getActiveObject();
            }

            if (activeObject && activeObject.type === 'i-text') {
                const labelID = $('#labelid').val();
                const fontFamily = $('#fontFamily').val();
                const alignment = $('#alignmentSelect').val();
                const fontSize = parseInt($('#fontSize').val()) || 20;
                const fontColor = $('#fontColor').val();
                const isBold = $('#bold').prop('checked');
                const isItalic = $('#italic').prop('checked');
                const isUnderline = $('#underline').prop('checked');

                activeObject.set({
                    id: labelID,
                    fontFamily: fontFamily,
                    originX: alignment,
                    fontSize: fontSize,
                    fill: fontColor,
                    fontWeight: isBold ? 'bold' : 'normal',
                    fontStyle: isItalic ? 'italic' : 'normal',
                    textDecoration: isUnderline ? 'underline' : 'none',
                });

                console.log(activeObject.styles)

                if (currentCanva == 1) {
                    // changeRectID(canvas, labelID)
                    canvas.renderAll();
                }

                if (currentCanva == 2) {
                    // changeRectID(canvas2, labelID)
                    canvas2.renderAll();
                }

            }
        }

        // function addRectangleToCanvas(id) {
        //     console.log(id)
        //     var newRect = new fabric.Rect({
        //         left: 50,
        //         top: 50,
        //         width: 100,
        //         height: 50,
        //         fill: '',
        //         strokeWidth: 1,
        //         stroke: 'black',
        //         opacity: 0, // Set the opacity value (0 to 1) for transparency
        //         text: 'Your Text Here', // Embed text directly in the rectangle
        //         fontFamily: 'Arial',
        //         fontSize: 16,
        //         fill: 'black',
        //         textAlign: 'center',
        //         originX: 'center',
        //         originY: 'center',
        //         id: id + '_rect',
        //         rectid: id
        //     });

        //     if (currentCanva == 1) {
        //         // Add the rectangle to the canvas
        //         canvas.add(newRect);

        //         // Render the canvas after making changes
        //         canvas.renderAll();
        //     }

        //     if (currentCanva == 2) {
        //         // Add the rectangle to the canvas
        //         canvas2.add(newRect);

        //         // Render the canvas after making changes
        //         canvas2.renderAll();
        //     }
        //     console.log(newRect)

        // }

        function addTextToCanvas(text) {
            $('#alignmentSelect').val('center')

            var id = 'text_' + textIdCounter++
            var newText = new fabric.IText(text, {
                left: 100,
                top: 100,
                fontFamily: 'Arial',
                fontSize: 16,
                fontWeight: 'normal',
                fontStyle: 'normal',
                textAlign: 'center',
                originX: 'center',
                originY: 'center',
                id: id,
                rectid: id
            });

            if (currentCanva == 1) {
                console.log(newText);
                console.log(currentCanva);
                // Add the text to the canvas
                canvas.add(newText);
                canvas.setActiveObject(newText);
                canvas.bringToFront(newText);

                // Render the canvas after making changes
                canvas.renderAll();
            }

            if (currentCanva == 2) {
                console.log(newText);

                // Add the text to the canvas
                canvas2.add(newText);
                canvas2.setActiveObject(newText);
                canvas2.bringToFront(newText);

                // Render the canvas after making changes
                canvas2.renderAll();
            }

            $('#text-controls').show();

            newText.on('moved', function() {
                localStorage.setItem('textLeft', newText.left);
                localStorage.setItem('textTop', newText.top);
                // editRectPosition()
            });

            // Update the stored position when the text is resized
            newText.on('scaled', function() {
                localStorage.setItem('textLeft', newText.left);
                localStorage.setItem('textTop', newText.top);
            });

            // addRectangleToCanvas(id);

        }

        function load_images() {
            $.ajax({
                type: 'GET',
                url: '{{ route('file.load') }}',
                success: function(data) {
                    var result = data[0].data;
                    console.log('this is images')
                    console.log(result)
                    if (result.length > 0) {
                        $('#scrollable-container').empty();
                        $.each(result, function(index, item) {
                            var img =
                                `<img src="${item}" class="scrollable-image" alt="Image ${index}">`

                            $('#scrollable-container').append(img);
                        });

                        // Event listener for image click
                        $('.scrollable-image').on('click', function() {
                            var imageSrc = $(this).attr('src');
                            if (currentCanva == 1) {
                                currentCover = imageSrc;
                            }
                            if (currentCanva == 2) {
                                currentCover2 = imageSrc;
                            }
                            addImageToCanvas(imageSrc);
                        });
                    }

                },
                error: function(xhr, status, error) {
                    console.error(xhr);
                    // Handle error if needed
                }
            });
        }

        // Event listener for width control
        $('#image-width2').on('input', function() {
            adjustImageSize2('w');
        });

        // Event listener for height control
        $('#image-height2').on('input', function() {
            adjustImageSize2('h');
        });

        function adjustImageSize2(value) {
            if (currentCanva == 1) {
                activeObject = canvas.getActiveObject();
            }
            if (currentCanva == 2) {
                activeObject = canvas2.getActiveObject();
            }

            if (activeObject && activeObject.type === 'image') {
                var widthInput = $('#image-width2');
                var heightInput = $('#image-height2');
                var width = parseInt(widthInput.val());
                var height = parseInt(heightInput.val());

                // Check if both width and height are valid
                if (!isNaN(width) && !isNaN(height)) {
                    // Scale the image to the specified width while maintaining aspect ratio
                    if (value == 'w') {
                        activeObject.scaleToWidth(width);
                    }
                    if (value == 'h') {
                        activeObject.scaleToHeight(height);
                    }

                    if (currentCanva == 1) {

                        canvas.renderAll();
                    }
                    if (currentCanva == 2) {

                        canvas2.renderAll();
                    }
                }
            }
        }

        $('#image-width').on('input', function() {
            adjustImageSize();
        });

        // Event listener for height control
        $('#image-height').on('input', function() {
            adjustImageSize();
        });

        // Event listener for opacity control
        $('#image-opacity').on('input', function() {
            if (currentCanva == 1) {
                activeObject = canvas.getActiveObject();
                if (activeObject && activeObject.type === 'image') {
                    activeObject.set('opacity', parseFloat($(this).val()));
                    canvas.renderAll();
                }
            }

            if (currentCanva == 2) {
                activeObject = canvas2.getActiveObject();
                if (activeObject && activeObject.type === 'image') {
                    activeObject.set('opacity', parseFloat($(this).val()));
                    canvas2.renderAll();
                }
            }
        });

        function adjustImageSize() {
            if (currentCanva == 1) {
                activeObject = canvas.getActiveObject();
                if (activeObject && activeObject.type === 'image') {
                    var widthInput = $('#image-width');
                    var heightInput = $('#image-height');
                    var width = parseInt(widthInput.val());
                    var height = parseInt(heightInput.val());

                    // Maintain the proper aspect ratio
                    var aspectRatio = activeObject.width / activeObject.height;
                    if (width && !isNaN(width)) {
                        height = width / aspectRatio;
                    } else if (height && !isNaN(height)) {
                        width = height * aspectRatio;
                    }
                    console.log(`${height} ${width}`)
                    $('#image-width2').val(width);
                    $('#image-height2').val(height);


                    // Update the image size
                    activeObject.set({
                        scaleX: width / activeObject.width,
                        scaleY: height / activeObject.height
                    });

                    canvas.renderAll();
                }
            }

            if (currentCanva == 2) {
                activeObject = canvas2.getActiveObject();
                if (activeObject && activeObject.type === 'image') {
                    var widthInput = $('#image-width');
                    var heightInput = $('#image-height');
                    var width = parseInt(widthInput.val());
                    var height = parseInt(heightInput.val());

                    // Maintain the proper aspect ratio
                    var aspectRatio = activeObject.width / activeObject.height;
                    if (width && !isNaN(width)) {
                        height = width / aspectRatio;
                    } else if (height && !isNaN(height)) {
                        width = height * aspectRatio;
                    }
                    console.log(`${height} ${width}`)
                    $('#image-width2').val(width);
                    $('#image-height2').val(height);


                    // Update the image size
                    activeObject.set({
                        scaleX: width / activeObject.width,
                        scaleY: height / activeObject.height
                    });

                    canvas2.renderAll();
                }
            }
        }

        function addImageToCanvas(imageSrc) {
            fabric.Image.fromURL(imageSrc, function(newImg) {

                console.log(imageSrc)
                // Set the position of the new image
                // newImg.set({
                //     left: 0, // Set your desired initial left position
                //     top: 0, // Set your desired initial top position
                //     id: 'cover',
                // });

                // // Make the new image draggable
                // newImg.set({
                //     hasControls: true,
                //     hasBorders: true,
                //     lockMovementX: false,
                //     lockMovementY: false,
                // });

                // // Event listener for object moving
                // newImg.on('moving', function(e) {
                //     saveImagePositions();
                // });


                // Add the new image to the canvas
                if (currentCanva == 1) {
                    canvas.setBackgroundImage(newImg, canvas.renderAll.bind(canvas), {});

                    // canvas.sendToBack(newImg);
                    canvas.setDimensions({
                        width: newImg.width,
                        height: newImg.height,
                    });



                }

                if (currentCanva == 2) {
                    canvas2.setBackgroundImage(newImg, canvas2.renderAll.bind(canvas2), {});

                    // console.log('two')
                    // canvas2.add(newImg);
                    // canvas2.sendToBack(newImg);
                    canvas2.setDimensions({
                        width: newImg.width,
                        height: newImg.height,
                    });
                    // $('#tab2').css({
                    //     'height': newImg.height,
                    //     'width': newImg.width
                    // });
                }

                // images.push(newImg);

                // newImg.on('scaling', function(options) {
                //     if (currentCanva == 1) {
                //         activeObject = canvas.getActiveObject();
                //     }

                //     if (currentCanva == 2) {
                //         activeObject = canvas2.getActiveObject();
                //     }

                //     if (activeObject && activeObject.type === 'image') {
                //         var scaleX = activeObject.scaleX;
                //         var scaleY = activeObject.scaleY;

                //         // Calculate the updated width and height
                //         var updatedWidth = activeObject.width * scaleX;
                //         var updatedHeight = activeObject.height * scaleY;

                //         // Update the input fields
                //         $('#image-width2').val(updatedWidth);
                //         $('#image-height2').val(updatedHeight);
                //     }
                // });

                // newImg.on('selected', function() {
                //     if (currentCanva == 1) {
                //         activeObject = canvas.getActiveObject();
                //     }

                //     if (currentCanva == 2) {
                //         activeObject = canvas2.getActiveObject();
                //     }

                //     if (activeObject && activeObject.type === 'image') {
                //         var scaleX = activeObject.scaleX;
                //         var scaleY = activeObject.scaleY;

                //         // Calculate the updated width and height
                //         updatedWidth = activeObject.width * scaleX;
                //         updatedHeight = activeObject.height * scaleY;

                //         console.log(activeObject);
                //         // Update the input fields
                //         $('#image-width2').val(updatedWidth);
                //         $('#image-height2').val(updatedHeight);
                //     }

                //     console.log('hello')


                // });
                // updateCanvasDimensions(newImg)

                // // Event listeners for input changes
                // $('#canvasWidth, #canvasHeight, #unit').on('input', function() {
                //     updateCanvasDimensions(newImg);
                // });

            });
        }

        function saveImagePositions() {
            const positions = {};
            images.forEach((image, index) => {
                positions[`image${index}`] = {
                    left: image.left,
                    top: image.top
                };
            });
            localStorage.setItem('imagePositions', JSON.stringify(positions));
        }

        function loadImagePositions() {
            const positions = JSON.parse(localStorage.getItem('imagePositions')) || {};

            images.forEach((image, index) => {
                const position = positions[`image${index}`];
                if (image instanceof fabric.Object && position) {
                    image.set({
                        left: position.left,
                        top: position.top
                    });
                    image.setCoords(); // Update internal coordinates
                    if (currentCanva == 1) {
                        canvas.renderAll();
                    }
                    if (currentCanva == 2) {
                        canvas2.renderAll();
                    }
                }
            });
        }

        // Function to save image positions in local storage
        function saveImagePositions() {
            const positions = {};
            images.forEach((image, index) => {
                positions[`image${index}`] = {
                    left: image.left,
                    top: image.top
                };
            });
            localStorage.setItem('imagePositions', JSON.stringify(positions));
        }

        function getLocalStorage() {
            storedLeft = localStorage.getItem('textLeft') || canvas.width - 20;
            storedTop = localStorage.getItem('textTop') || 20; //  
        }

        function convertToSVG() {

            // if (!currentCover && currentCanva == 1) {
            //     notify("warning", "Select Cover for Front ID");
            //     return;
            // }

            // if (!currentCover2 && currentCanva == 2) {
            //     notify("warning", "Select Cover for Back ID");
            //     return;
            // }

            if (currentCanva == 1) {
                // removeRect(canvas)
                getBoundingBoxFront()
                console.log(svgString);
                $('#content').html(svgString);

            }

            if (currentCanva == 2) {
                // removeRect(canvas2)
                getBoundingBoxBack()
                console.log(svgString2);
                $('#content').html(svgString2);
            }

        }

        function getObjectById(canvas, id) {
            var objects = canvas.getObjects();
            for (var i = 0; i < objects.length; i++) {
                if (objects[i].id === id) {
                    return objects[i];
                }
            }
            return null;
        }

        function getObjectByIdRect(canvas, id) {
            var objects = canvas.getObjects();
            for (var i = 0; i < objects.length; i++) {
                if (objects[i].id === id) {
                    console.log(`this is rect = ${objects[i]}`)
                    console.log(objects[i])
                    return objects[i];
                }
            }
            return null;
        }

        function getBoundingBoxFront() {
            // removeRect(canvas)
            // var coverImage = getObjectById(canvas, 'cover');
            // var bounds = coverImage.getBoundingRect();
            svgString = canvas.toSVG({
                // width: bounds.width,
                // height: bounds.height,
                // viewBox: {
                //     x: bounds.left,
                //     y: bounds.top,
                //     width: bounds.width,
                //     height: bounds.height,
                // },
            });

        }

        function getBoundingBoxBack() {
            // removeRect(canvas2)
            // var coverImage = getObjectById(canvas2, 'cover');
            // var bounds = coverImage.getBoundingRect();
            svgString2 = canvas2.toSVG({
                // width: bounds.width,
                // height: bounds.height,
                // viewBox: {
                //     x: bounds.left,
                //     y: bounds.top,
                //     width: bounds.width,
                //     height: bounds.height,
                // },
            });
        }

        // function changeRectID(canv, newid) {
        //     var newrect = canv.getObjects().find(obj => obj.type === 'rect' && obj.rectid == canv.getActiveObject().rectid);
        //     newrect.set({
        //         id: newid
        //     });
        //     // Render the canvas after making changes
        //     canvas.renderAll();
        // }

        // function removeRect(canv) {
        //     var rects = canv.getObjects().filter(obj => obj.type === 'rect');
        //     rects.forEach(rec => {
        //         rec.set({
        //             opacity: 0
        //         });
        //     });
        //     // Render the canvas after making changes
        //     canvas.renderAll();
        // }

        function saveTemplate() {
            getBoundingBoxFront()
            getBoundingBoxBack()
            $.ajax({
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    name: currentTemplate,
                    templates: [{
                            id: frontid,
                            template: svgString,
                            image: currentCover,
                            type: 'front',
                        },
                        {
                            id: backid,
                            template: svgString2,
                            image: currentCover2,
                            type: 'back',
                        },
                    ],
                },
                url: '{{ route('update.template') }}',
                success: function(data) {
                    notify(data.statusCode, data.message);
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
