


<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Administrator Portal</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
  <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
  <link rel="stylesheet" href="{{asset('plugins/pace-progress/themes/black/pace-theme-flat-top.css')}}">
  <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>



</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed  pace-primary">



    <script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('dist/js/adminlte.min.js')}}"></script>
    <script src="{{asset('plugins/pace-progress/pace.min.js') }}"></script>
    <script src="{{asset('plugins/sweetalert2/sweetalert2.all.min.js')}}"></script>
    
    <script>
            $(document).ready(function(){

                  var curentTime = false;

                  function backup(){
                        var today = new Date();
                        var time = today.getHours() + ":" + today.getMinutes();

                        console.log(time)

                        if(time == '16:59' && !curentTime){
                              $.ajax({
                                    type:'GET',
                                    url:'/performbackup',
                              })

                              curentTime = true
                        }
                        else if(time == '17:00') {

                              curentTime = false

                        }
                  }

                  window.setInterval(backup, 5000);
                
                  
          })
    </script>

    
  </body>
</html>
