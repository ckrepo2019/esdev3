@extends('finance.layouts.app')

@section('content')
  
  <section class="content">
  			<!-- Payment Items -->
        <div class="row mb-2 ml-2">
            <h1 class="m-0 text-dark">Student Portal</h1>
        </div>

		<div class="row">
            <div id="viewtui" class="col-12">
                
            </div>          
        </div>
  </section>
@endsection



@section('js')
  
  <script type="text/javascript">
    
    $(document).ready(function(){
        var searchVal = $('#txtsearchitem').val();
        

        $('.select2').select2({
            theme: 'bootstrap4'
        });

        screenadjust();

        function screenadjust()
        {
            var screen_height = $(window).height();

            $('#main_table').css('height', screen_height - 300);
            // $('.screen-adj').css('height', screen_height - 223);
        }

        appendtui();

        
        function appendtui()
        {
            var levelid = 13;
            
            $.ajax({
                url: '{{route('u_loadlevel')}}',
                type: 'GET',
                data: {
                    levelid:levelid,
                },
                success:function(data)
                {
                    $('#viewtui').append(data);
                }
            });
            
        }

        

    });

  </script>
  
@endsection