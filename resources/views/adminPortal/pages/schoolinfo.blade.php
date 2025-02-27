<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset</title>
    <link href="{{asset('assets/icons/fontawesome/css/all.css')}}" rel="stylesheet">
    <link href="{{asset('assets/css/main.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="../../plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

    <style>
        body {
            height: 100vh;
            padding: 0;
            margin: 0;
            background-image: linear-gradient( 405deg, #ff9797 13%, #fbfbfb 13%, #fbfbfb 87%, #ff9797 68% );
        }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed resethome">
    <div class="row col-lg-12 pt-4">
        <div class="col-md-4"></div>
        <div class="col-md-4 col-xs-12">
        <div class="card shadow style="box-shadow: 1px 1px 4px gray">
        
        <div class="card-header" style="background-color: #fff; letter-spacing: 5px;color: #3ac47d;justify-content:center">{{ __('SCHOOL INFORMATION') }} 
          
        </div>
        <form action="{{isset($schoolInfo) ? '/updateinfo' :'/insertinfo'}}" method="GET">
            <div class="card-body ">
                <div class="form-group">
                    <label><b>School Id</b></label>
                    <input placeholder="School" value="{{isset($schoolInfo->schoolname) ? $schoolInfo->schoolid :''}}"  name="schoolid" class="form-control"  required>
                </div>
                <div class="form-group">
                    <label><b>School Name</b></label>
                    <input placeholder="School Name" value="{{isset($schoolInfo->schoolname) ? $schoolInfo->schoolname :''}}" name="schoolname" class="form-control" required>
                </div>
                <div class="form-group">
                    <label><b>School Abbreviation</b></label>
                    <input placeholder="School Abbreviation" value="{{isset($schoolInfo->abbreviation) ? $schoolInfo->abbreviation :''}}" name="abbreviation" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Region</label>
                    <input placeholder="School Region" type="text" class="form-control"  name="region" id="region" value="{{isset($schoolInfo->regiontext) ? $schoolInfo->regiontext :''}}" >
                </div>
                <div class="form-group">
                    <label>Division</label>
                    <input placeholder="School Division" type="text" class="form-control"  name="division" id="division" value="{{isset($schoolInfo->divisiontext) ? $schoolInfo->divisiontext :''}}" >
                </div>
                <div class="form-group">
                    <label><b>District</b></label>
                    <input placeholder="School District"  value="{{isset($schoolInfo->districttext) ? $schoolInfo->districttext :''}}" type="text" name="district" class="form-control"  required>
                </div>
                <div class="form-group">
                    <label><b>Address</b></label>
                    <input placeholder="School Address" value="{{isset($schoolInfo->address) ? $schoolInfo->address :''}}" name="address" class="form-control"  required>
                </div>
                <div class="form-group">
                    <label for=""><b>School Tag Line</b></label>
                    <textarea placeholder="SCHOOL TAGLINE" class="form-control" name="schooltagline" rows="3"></textarea required>
                </div>
                <button  type="submit" class="btn {{isset($schoolInfo) ? 'btn-success' :'btn-info'}}" ><i class="fas fa-paper-plane"></i> 
                    {{isset($schoolInfo) ? 'Update' :'Submit'}}</button>
            </div>
        </form>
    </div>
        </div>
        <div class="col-md-4"></div>
    </div>

    <script src="../../plugins/jquery/jquery.min.js"></script>
    <script src="../../plugins/select2/js/select2.full.min.js"></script>

    <script>


        $(function () {
                $('.select2').select2({
                theme: 'bootstrap4'
            })
        })

        // $(document).ready(function(){

           
        //     @if(isset($schoolInfo->division))

        //         $.ajax({
        //                 type:'GET',
        //                 url:'/admingetcity',
        //                 data:{
        //                     data:'{{$schoolInfo->region}}',
        //                 },
        //                 success:function(data) {
        //                     $('#division').empty();
        //                     $('#division').append('<option>SELECT DIVISION</option>');
        //                     $.each(data,function(key,value){
        //                         if(value.citymunCode == '{{$schoolInfo->division}}'){
        //                             $('#division').append('<option selected value='+value.citymunCode+'>'+value.citymunDesc+'</option>');
        //                         }
        //                         else{
        //                             $('#division').append('<option value='+value.citymunCode+'>'+value.citymunDesc+'</option>');
        //                         }

                               
        //                     })  
        //                 $('.select2').select2()
        //             }
        //         })

        //     @endif

        //     $(document).on('change','#region',function(){
        //         $.ajax({
        //             type:'GET',
        //             url:'/admingetcity',
        //             data:{
        //                 data:$(this).val(),
        //             },
        //             success:function(data) {
        //                 $('#division').empty();
        //                 $('#division').append('<option>SELECT DIVISION</option>');
        //                 $.each(data,function(key,value){
                            
        //                     $('#division').append('<option value='+value.citymunCode+'>'+value.citymunDesc+'</option>');
        //                 })  

        //                 $('.select2').select2()
        //             }
        //         })
        //     })

        })


    </script>
</body>
</html>