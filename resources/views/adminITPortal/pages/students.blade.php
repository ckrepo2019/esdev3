@extends('adminITPortal.layouts.app')


@section('pagespecificscripts')

@endsection


@section('content')
 
@endsection


@section('footerjavascript')
      <script>
            $(document).ready(function(){
                  let url = 'http://essentielv2.ck/studentmasterlist?enrolled=enrolled&count=count';
                  let options =  {
                        method: "GET",
                        mode: "no-cors",
                        headers: {
                        "access-control-allow-origin" : "*",
                        "Content-type": "application/json; charset=UTF-8",
                        "access-control-allow-headers": "content-type"
                        }
                  };

                  var resp = [];
                  $.ajax({
                  url: 'http://essentielv2.ck/studentmasterlist?enrolled=enrolled&count=count',
                        type: 'GET',
                        dataType: 'jsonp',
                  
                  success : function(data) {
                        console.log(data);
                        resp.push(data);
                  }, error : function(req, err) {
                        console.log(req)
                        console.log(err);
                  }
                  })
                  console.log(resp)

                
                        

            })  
      </script>
@endsection