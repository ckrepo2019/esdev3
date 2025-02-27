<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>Test Portal</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
<link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
{{-- <link rel="stylesheet" href="{{asset('assets\css\sideheaderfooter.css')}}"> --}}
{{-- <link rel="stylesheet" href="{{asset('plugins/pace-progress/themes/black/pace-theme-flat-top.css')}}"> --}}
<link rel="stylesheet" href="{{asset('plugins/toastr/toastr.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/datatables/DataTables/css/jquery.dataTables.css')}}">
<link rel="stylesheet" href="{{asset('plugins/particles/css/style.css')}}">


<style>
        body {
            margin: 0;
            padding: 0;
            overflow: hidden; /* Prevents scrollbars due to video size */
            display: flex;
            justify-content: center; /* Center horizontally */
            align-items: center; /* Center vertically */
            height: 100vh; /* Set the body height to the viewport height */
        }

        #particles-js {
            background-color: #673AB7; /* Place the video behind other content */
        }

        .container.quizcontent {
            /* Your container styles here */
            padding: 20px;
            left:0;
            right:0;
            margin-left: auto;
            margin-right: auto;
            position: absolute;
            padding: 20px;
            position: fixed;
            z-index: 2;
            
        }
</style>

</head>
<body >

        <div id="particles-js">


            <div class="container quizcontent mt-5">
                <div class="row justify-content-center mt-5">
                    <div class="col-8 ">
                        <div class="card mt-5" style= "border-top: 10px solid #55bedb  !important;
                                border-radius: 10px  !important;" id="quiz-info">
                                <div class="card-header">
                                    <h1 class="card-title">
                                        Test Submitted
                                    </h1>
                                </div>
                                    
                                    
                                <div class="card-body">
                                    <h1>The Answer has been submitted </h1>
                                </div>
                        </div> 
                    </div>
                </div>
            </div>

        </div>

</body>


<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('dist/js/adminlte.min.js')}}"></script>
{{-- <script src="{{asset('plugins/pace-progress/pace.min.js') }}"></script> --}}
<script src="{{asset('plugins/sweetalert2/sweetalert2.all.min.js')}}"></script>
<script src="{{asset('plugins/toastr/toastr.min.js')}}"></script>
<script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('plugins/particles/particles.js')}}"></script>
<script src="{{asset('plugins/particles/js/app.js')}}"></script>



<script type="text/javascript">
    particlesJS("particles-js", {
    "particles": {
        "number": {
        "value": 60,
        "density": {
            "enable": true,
            "value_area": 800
        }
        },
        "color": {
        "value": "#ffffff"
        },
        "shape": {
        "type": "circle",
        "stroke": {
            "width": 0,
            "color": "#000000"
        },
        "polygon": {
            "nb_sides": 5
        },
        "image": {
            "src": "img/github.svg",
            "width": 100,
            "height": 100
        }
        },
        "opacity": {
        "value": 0.1,
        "random": false,
        "anim": {
            "enable": false,
            "speed": 1,
            "opacity_min": 0.1,
            "sync": false
        }
        },
        "size": {
        "value": 6,
        "random": false,
        "anim": {
            "enable": false,
            "speed": 40,
            "size_min": 0.1,
            "sync": false
        }
        },
        "line_linked": {
        "enable": true,
        "distance": 150,
        "color": "#ffffff",
        "opacity": 0.1,
        "width": 2
        },
        "move": {
        "enable": true,
        "speed": 1.5,
        "direction": "top",
        "random": false,
        "straight": false,
        "out_mode": "out",
        "bounce": false,
        "attract": {
            "enable": false,
            "rotateX": 600,
            "rotateY": 1200
        }
        }
    },
    "interactivity": {
        "detect_on": "canvas",
        "events": {
        "onhover": {
            "enable": false,
            "mode": "repulse"
        },
        "onclick": {
            "enable": true,
            "mode": "push"
        },
        "resize": true
        },
        "modes": {
        "grab": {
            "distance": 400,
            "line_linked": {
            "opacity": 1
            }
        },
        "bubble": {
            "distance": 400,
            "size": 40,
            "duration": 2,
            "opacity": 8,
            "speed": 3
        },
        "repulse": {
            "distance": 200,
            "duration": 0.4
        },
        "push": {
            "particles_nb": 4
        },
        "remove": {
            "particles_nb": 2
        }
        }
    },
    "retina_detect": true
    });
</script>



