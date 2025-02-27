

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
          <!-- <h1>Standard Allowances</h1> -->
          <h4 class="text-warning" style="text-shadow: 1px 1px 1px #000000">
            <!-- <i class="fa fa-chart-line nav-icon"></i>  -->
            STANDARD ALLOWANCES</h4>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Standard Allowances</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
  <section class="content">
      <div class="card">
          <div class="card-header">
            <div class="row">
                <div class="col-md-12">
                    <button type="button" class="btn btn-sm text-success float-right" id="addallowance" clicked="0"><i class="fa fa-plus"></i>&nbsp; Add standard allowance/s</button>
                </div>
            </div>
          </div>
          <div class="card-body">
              <div class="row">
                  <div class="col-md-4">
                    <form action="/updateallowances/{{Crypt::encrypt('addallowance')}}" method="get">
                        <ul class="nav flex-column addallowancescontainer">
                        </ul>
                    </form>
                </div>
                <div class="col-md-8 p-0">
                    <ul class="nav flex-column">
                        @foreach($standardallowances as $standardallowance)
                            <li class="nav-item">
                                <form action="/updateallowances/{{Crypt::encrypt('editallowance')}}" method="get">
                                    <a href="#" class="nav-link p-0">
                                        <div class="row">
                                            <div class="col-md-9">
                                                <input type="hidden" class="form-control form-control-sm" name="allowanceid" value="{{$standardallowance->id}}" style="display: inline-block; position:relative" readonly required/>
                                                <input type="text" class="form-control form-control-sm text-uppercase" name="editallowance" value="{{$standardallowance->description}}" style="display: inline-block; position:relative" readonly required/>
                                            </div>
                                            <div class="col-md-3 text-right">
                                                <span class=" web buttonscontainer pt-2 col-3">
                                                    <span class="btn btn-warning btn-sm editallowancebutton">Edit</span>&nbsp;<span class="btn btn-sm btn-danger deleteallowancebutton" >Delete</span>
                                                </span>
                                                <span class="mobile buttonscontainer pt-2">
                                                    <div class="row mobilecontainer">
                                                    <div class="col-6">
                                                    <span class="btn btn-warning btn-sm btn-block editallowancebutton">Edit</span>
                                                    </div>
                                                    <div class="col-6">
                                                        <span class="btn btn-sm btn-block btn-danger deleteallowancebutton" >Delete</span>
                                                    </div>
                                                </div>
                                                </span>
                                            </div>
                                        </div>
                                    </a>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                </div>
              </div>
          </div>
      </div>
  </section>
  <input type="hidden" name="deleteid" value="{{Crypt::encrypt('deleteallowance')}}"/>
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
    $(document).ready(function(){
        var addrows = 0;
        $('#addallowance').on('click', function(){
            // console.log($(this).attr('clicked'));
            if($(this).attr('clicked') == '0'){
                $('.addallowancescontainer').prepend(
                    '<li class="nav-item">'+
                        '<a href="#" class="nav-link p-0">'+
                        '<button type="submit" class="btn btn-sm btn-success savebutton col-10">Save</button>'+
                        '</a>'+
                    '</li>'
                )
                $('.addallowancescontainer').prepend(
                    '<li class="nav-item">'+
                        '<a href="#" class="nav-link p-0">'+
                        '<input type="text"class="form-control form-control-sm col-md-10 col-10 text-uppercase" name="descriptions[]" style="display: inline-block; position:relative" placeholder="Description" required/><button type="button" class="btn text-center col-md-2 col-2 deletebutton"><i class="fa fa-times text-danger"></i></button>'+
                        '</a>'+
                    '</li>'
                )
                $(this).attr('clicked','1')
            }else{
                $('.addallowancescontainer').prepend(
                    '<li class="nav-item">'+
                        '<a href="#" class="nav-link p-0">'+
                        '<input type="text"class="form-control form-control-sm col-md-10 col-10" name="descriptions[]" style="display: inline-block; position:relative" placeholder="Description" required/><button type="button" class="btn text-center col-md-2 col-2 deletebutton"><i class="fa fa-times text-danger"></i></button>'+
                    '</li>'
                )
            }
            addrows+=1;
        });
        $(document).on('click','.deletebutton', function(){
            addrows-=1;
            $(this).closest('li.nav-item').remove();
            if(addrows == 0){
                $('.addallowancescontainer').empty();
                $('#addallowance').attr('clicked','0')
            }
        });
        $(document).on('click','.editallowancebutton', function(){
            $(this).closest('.nav-link').find('input[name="editallowance"]').removeAttr('readonly')
            // console.log($(this).closest('.nav-link')[0].children[0])
            $(this).closest('.buttonscontainer').append(
                '<button type="submit" class="btn btn-sm btn-success savebutton">Update</button>'
            );
            $(this).closest('.mobilecontainer').remove();
            $(this).find('.deleteallowancebutton').remove();
            $(this).next('.deleteallowancebutton').remove();
            $(this).remove();

        })
        $('.deleteallowancebutton').click(function() {
            var allowanceid = $(this).closest('.nav-link').find('input[name="allowanceid"]').val();
            var description = $(this).closest('.nav-link').find('input[name="editallowance"]').val();
            var action = $('input[name=deleteid]').val();
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
                        url: '/updateallowances/'+action,
                        type:"GET",
                        dataType:"json",
                        data:{
                            allowanceid: allowanceid,
                            description: description
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
        // ===============================================================================================
        // ===============================================================================================
        // ===============================================================================================

    })
  </script>
@endsection

