

<!-- DataTables -->
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
<link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.css')}}">
@extends('hr.layouts.app')
@section('content')
<style>
    .mobile{
        display: none;
    }
    @media only screen and (max-width: 600px) {
        .mobile {
            display: block;
        }
        .web {
            display: none;
        }
    }

</style>
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
            <!-- <h1>Standard Deductions Setup</h1> -->
                <h4 class="text-warning" style="text-shadow: 1px 1px 1px #000000">
            <!-- <i class="fa fa-chart-line nav-icon"></i>  -->
            EMPLOYMENT REQUIREMENTS</h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/home">Home</a></li>
                    <li class="breadcrumb-item active">Credential Types</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<section class="content">
    <div class="card">
        <div class="card-header">
            <button type="button" class="btn btn-sm btn-primary" id="addrequirementsbutton" clicked="0"><i class="fa fa-plus"></i>&nbsp; Add Requirement/s</button>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4" id="addcredentialscontainer">
                    <form action="/requirements/addnew" method="get" id="addrequirementcontainer">
                    
                    </form>
                </div>
                <div class="col-md-8">
                    @if(count($types) > 0)
                        @foreach($types as $type)
                            <form action="/requirements/{{Crypt::encrypt('edit')}}" method="get" class="p-0 m-0">
                                <div class="row eachtype">
                                    <div class="col-md-8">
                                        <input type="text" name="description" class="form-control form-control text-uppercase" value="{{$type->description}}" disabled/>
                                        <input type="hidden" name="typeid" class="form-control form-control text-uppercase" value="{{$type->id}}" />
                                    </div>
                                    <div class="col-md-4">
                                        <span class=" web buttonscontainer pt-2 col-3">
                                            <span class="btn btn-warning btn-sm editdeductiontypebutton">Edit</span>&nbsp;<span class="btn btn-sm btn-danger deletedeductiontypebutton" >Delete</span>
                                        </span>
                                        <span class="mobile buttonscontainer pt-2">
                                            <div class="row mobilecontainer">
                                            <div class="col-6">
                                            <span class="btn btn-warning btn-sm btn-block editdeductiontypebutton">Edit</span>
                                            </div>
                                            <div class="col-6">
                                                <span class="btn btn-sm btn-block btn-danger deletedeductiontypebutton" >Delete</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <br>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="row text-uppercase"> 
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <button type="button" class="btn btn-sm btn-primary" id="addrequirementsbutton" clicked="0"><i class="fa fa-plus"></i>&nbsp; Add Requirement/s</button>
                </div>
                <div class="card-body" id="addcredentialscontainer">
                    <form action="/requirements/addnew" method="get" id="addrequirementcontainer">
                    
                    </form>
                </div>
            </div>
        </div> --}}
        {{-- <div class="col-md-12">
            @if(count($types) > 0)
                <div class="card">
                    <div class="card-body">
                        @foreach($types as $type)
                            <form action="/requirements/{{Crypt::encrypt('edit')}}" method="get" class="p-0 m-0">
                                <div class="row eachtype">
                                    <div class="col-md-9">
                                        <input type="text" name="description" class="form-control form-control text-uppercase" value="{{$type->description}}" disabled/>
                                        <input type="hidden" name="typeid" class="form-control form-control text-uppercase" value="{{$type->id}}" />
                                    </div>
                                    <div class="col-md-3">
                                        <span class=" web buttonscontainer pt-2 col-3">
                                            <span class="btn btn-warning btn-sm editdeductiontypebutton">Edit</span>&nbsp;<span class="btn btn-sm btn-danger deletedeductiontypebutton" >Delete</span>
                                        </span>
                                        <span class="mobile buttonscontainer pt-2">
                                            <div class="row mobilecontainer">
                                            <div class="col-6">
                                            <span class="btn btn-warning btn-sm btn-block editdeductiontypebutton">Edit</span>
                                            </div>
                                            <div class="col-6">
                                                <span class="btn btn-sm btn-block btn-danger deletedeductiontypebutton" >Delete</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <br>
                        @endforeach
                    </div>
                </div>
            @endif
        </div> 
    </div> --}}
</section>
<input type="hidden" name="deleteid" value="{{Crypt::encrypt('deletedeductiontype')}}"/>
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- ChartJS -->
<script src="{{asset('plugins/chart.js/Chart.min.js')}}"></script>
<!-- DataTables -->
<script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
<script src="{{asset('plugins/summernote/summernote-bs4.min.js')}}"></script>
<script>
    var clickedaddrequirement = 0;

    $(document).on('click','#addrequirementsbutton', function(){

        clickedaddrequirement+=1;

        if(clickedaddrequirement == 1){

            $('#addrequirementcontainer').append(
                '<label>Description</label>'+
                '<input type="text" class="form-control  text-uppercase form-control-sm" name="description[]" required/>'+
                '<br/>'+
                '<button type="submit" class="btn btn-block btn-primary btn-sm">Submit</button>'
            );

        }else{

            $('#addrequirementcontainer').prepend(
                '<label>Description</label>'+
                '<input type="text" class="form-control  text-uppercase form-control-sm" name="description[]" required/>'
            );

        }
        

    });
    
    $(document).on('click','.editdeductiontypebutton', function(){
            // console.log($(this).closest('.nav-link')[0].children[0])
            $(this).closest('.eachtype').find('input[name=description]').attr('disabled',false);
            $(this).closest('.col-md-4').append(
                '<button type="submit" class="btn btn-sm btn-success savebutton">Update</button>'
            );
            $(this).closest('.mobilecontainer').remove();
            $(this).find('.deletedeductiontypebutton').remove();
            $(this).next('.deletedeductiontypebutton').remove();
            $(this).remove();

        })
        $('.deletedeductiontypebutton').click(function() {
            var typeid = $(this).closest('.eachtype').find('input[name=typeid]').val();
            // console.log()
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: '/requirements/{{Crypt::encrypt("delete")}}',
                        type:"GET",
                        dataType:"json",
                        data:{
                            typeid: typeid
                        },
                        // headers: { 'X-CSRF-TOKEN': token },,
                        complete: function(){
                            Swal.fire({
                                title: 'Deleted!',
                                text: "Your file has been deleted.",
                                type: 'success',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'OK!'
                            }).then((confirm) => {
                                if (confirm.value) {
                                    window.location.reload();
                                }
                            })
                        }
                    })
                }
            })
        });
    

</script>
@endsection

