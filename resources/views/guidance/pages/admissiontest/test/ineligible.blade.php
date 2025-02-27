<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ineligible</title>
    <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
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
<body>
    <div id="particles-js">
        <div class="container quizcontent mt-5">
            <div class="row justify-content-center mt-5">
                <div class="col-8">
                    <div class="card mt-5" style="border-top: 10px solid #55bedb; border-radius: 10px;">
                        <div class="card-body">
                            <p class="lead mb-4">Unable to Proceed</p>
                            <h4 class="">Please contact the person in charge.</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



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



</body>
</html>
