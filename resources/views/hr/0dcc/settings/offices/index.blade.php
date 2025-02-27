

<!-- DataTables -->
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
<link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.css')}}">
@extends('hr.layouts.app')
@section('content')
<style>
    .card{
        box-shadow: 0 1rem 3rem rgba(0,0,0,.175)!important;;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #007bff;
        border-color: #006fe6;
        color: #fff;
        padding: 0 10px;
        margin-top: .31rem;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: #fff;
    }
    .select2-selection__rendered{
        padding-bottom: 5px !important;
    }
</style>
<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
        <h4 class="text-warning" style="text-shadow: 1px 1px 1px #000000"><i class="fa fa-chart-line nav-icon"></i> OFFICES</h4>
          <!-- <h1>Attendance</h1> -->
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/home">Home</a></li>
            <li class="breadcrumb-item active">OFFICES</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
  <section class="content">
    <div class="container-fluid">
        <div class="row">    
            <div class="col-md-4">
                <label>Select S.Y.</label>
                <select class="form-control mb-2" id="select-syid">
                    @foreach(DB::table('sy')->get() as $eachsy)
                        <option value="{{$eachsy->id}}" @if($eachsy->id == $syid) selected @endif>{{$eachsy->sydesc}}</option>
                    @endforeach
                </select>
                <div class="row">
                    <div class="col-md-6">
                        <button type="button" class="btn btn-primary btn-block btn-sm" data-toggle="modal" data-target="#modal-addoffice"><i class="fa fa-plus"></i> Add Office</button>                
                        <div class="modal fade" id="modal-addoffice">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Add New Office</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <input type="text" class="form-control" id="input-newoffice"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer justify-content-between">
                                        <button type="button" class="btn btn-default btn-close" data-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-primary" id="btn-submit-newoffice">Submit</button>
                                    </div>
                                </div>                    
                            </div>                    
                        </div>
                    </div>
                    <div class="col-md-6">
                        <button type="button" class="btn btn-default btn-block btn-sm" id="btn-export-offices"><i class="fa fa-download"></i> Export to PDF</button>  
                    </div>
                </div>
                <input type="text" class="form-control mt-2 mb-2" id="input-search-office" placeholder="Search..."/>
                <div class="row" id="container-offices">
                    @foreach($offices as $office)
                    <div class="col-md-12 div-each-office" data-string="{{$office->officename}}<">
                        <div class="info-box shadow-lg each-office" data-id="{{$office->id}}" style="cursor: pointer;">
                            <span class="info-box-icon"><i class="fa fa-building"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">{{$office->officename}}</span>
                            </div>
                        </div>
                     </div>
                    @endforeach
                </div>
            </div>
            <div class="col-md-8" id="container-employees">       
            </div>    
        </div>    
    </div>
</section>
@endsection

@section('footerscripts')
    <script>
        $(document).ready(function(){
            var selectedsyid = $('#select-syid').val();
            $('#select-syid').on('change', function(){
                window.open("/hr/settings/offices/index?syid="+$(this).val(),"_self");
            })
            $("#input-search-office").on("keyup", function() {
                var input = $(this).val().toUpperCase();
                var visibleCards = 0;
                var hiddenCards = 0;

                $(".container").append($("<div class='card-group card-group-filter'></div>"));


                $(".div-each-office").each(function() {
                    if ($(this).data("string").toUpperCase().indexOf(input) < 0) {

                    $(".card-group.card-group-filter:first-of-type").append($(this));
                    $(this).hide();
                    hiddenCards++;

                    } else {

                    $(".card-group.card-group-filter:last-of-type").prepend($(this));
                    $(this).show();
                    visibleCards++;

                    if (((visibleCards % 4) == 0)) {
                        $(".container").append($("<div class='card-group card-group-filter'></div>"));
                    }
                    }
                });

            });
            $(document).on('click','.each-office', function(){
                var id=$(this).attr('data-id')
                var thisbox = $(this).find('.info-box-icon');
                $('.info-box-icon').removeClass('bg-success')
                // $('.info-box-icon').addClass('bg-warning')
                thisbox.removeClass('bg-warning')
                thisbox.addClass('bg-success')
                $.ajax({
                    url: '/hr/settings/offices/getemployees',
                    type:"GET",
                    data:{
                        id: id
                    },
                    // dataType: 'json',
                    // headers: { 'X-CSRF-TOKEN': token },,
                    success: function(data){    
                        $('#container-employees').empty()
                        $('#container-employees').append(data)
                        $('.select2').select2()
                    }
                })
            })
            $('#btn-submit-newoffice').on('click', function(){
                var btnclose = $(this).closest('div').find('.btn-close');
                var newoffice = $('#input-newoffice').val();
                if(newoffice.replace(/^\s+|\s+$/g, "").length == 0)
                {
                    $('#input-newoffice').css('border','1px solid red');
                }else{
                    $.ajax({
                        url: '/hr/settings/offices/addoffice',
                        type:"GET",
                        data:{
                            newoffice: newoffice,
                            selectedsyid: selectedsyid
                        },
                        // dataType: 'json',
                        // headers: { 'X-CSRF-TOKEN': token },,
                        success: function(data){    
                            if(data == '0')
                            {                            
                                toastr.error('Something went wrong!', 'New Office')
                            }else{
                                btnclose.click()
                                toastr.success('Added successfully!', 'New Office')
                                $('.info-box-icon').removeClass('bg-success');
                                // $('.info-box-icon').addClass('bg-warning');
                                $('#container-offices').prepend(                                    
                                    '<div class="col-md-12 div-each-office">'+
                                        '<div class="info-box shadow-lg each-office" data-id="'+data.id+'">'+
                                            '<span class="info-box-icon bg-success"><i class="fa fa-building"></i></span>'+
                                            '<div class="info-box-content">'+
                                                '<span class="info-box-text">'+data.officename+'</span>'+
                                                // '<span class="info-box-number">Large</span>'+
                                            '</div>'+
                                        '</div>'+
                                    '</div>'
                                )
                                $('.each-office[data-id="'+data.id+'"]').click()
                            }     
                        }
                    })
                }
            })
            $(document).on('click','#btn-update-office', function(){
                var newofficename = $('#input-officename-update').val();
                var officeid = $(this).attr('data-officeid')
                if(newofficename.replace(/^\s+|\s+$/g, "").length == 0)
                {
                    $('#input-officename').css('border','1px solid red');
                            toastr.error('Something went wrong!', 'New Office')
                }else{
                    $.ajax({
                        url: '/hr/settings/offices/updateoffice',
                        type:"GET",
                        data:{
                            id              : officeid,
                            newofficename   : newofficename
                        },
                        success: function(data){    
                            if(data == 0)
                            {
                                toastr.error('Something went wrong!', 'Office Info')
                            }else{
                                $('.each-office[data-id="'+officeid+'"]').find('.info-box-text').text(data)
                                toastr.success('Updated successfully!', 'Office Info')
                            }
                        }
                    })

                }

            })
            $(document).on('click','#btn-delete-office', function(){
                var officeid = $(this).attr('data-officeid')
                Swal.fire({
                    title: 'Are you sure you want to delete this office?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                        url: '/hr/settings/offices/deleteoffice',
                            type:"GET",
                            dataType:"json",
                            data:{
                                    id          :   officeid
                            },
                            // headers: { 'X-CSRF-TOKEN': token },,
                            complete: function(){
                                toastr.success('Deleted successfully!')
                                window.location.reload()
                            }
                        })
                    }
                })

            })
            $(document).on('click', '#btn-submit-personnel', function(){
                var personnels = $('#select-personnels').val();
                var officeid = $(this).attr('data-officeid')
                if(personnels.length == 0)
                {
                    toastr.error('No employee selected!', 'Add personnel')
                    $('#select-personnels').css('border','1px solid red')
                }else{
                    $.ajax({
                        url: '/hr/settings/offices/addpersonnel',
                        type:"GET",
                        data:{
                            officeid: officeid,
                            personnels: JSON.stringify(personnels)
                        },
                        // dataType: 'json',
                        // headers: { 'X-CSRF-TOKEN': token },,
                        success: function(data){    
                            if(data == 1)
                            {                            
                                toastr.success('Added successfully!', 'Personnel')
                            }
                            // else{
                            //     btnclose.click()
                            //     toastr.success('Added successfully!', 'New Office')
                            //     $('.info-box-icon').removeClass('bg-success');
                            //     // $('.info-box-icon').addClass('bg-warning');
                            //     $('#container-offices').prepend(                                    
                            //         '<div class="col-md-12 div-each-office">'+
                            //             '<div class="info-box shadow-lg each-office" data-id="'+data.id+'">'+
                            //                 '<span class="info-box-icon bg-success"><i class="fa fa-building"></i></span>'+
                            //                 '<div class="info-box-content">'+
                            //                     '<span class="info-box-text">'+data.officename+'</span>'+
                            //                     // '<span class="info-box-number">Large</span>'+
                            //                 '</div>'+
                            //             '</div>'+
                            //         '</div>'
                            //     )
                            //     $('.each-office[data-id="'+data.id+'"]').click()
                            // }     
                        }
                    })

                }
                
            })
            $(document).on('click','.btn-update-data', function(){
                var officeempid=$(this).attr('dataid');
                var dataofficeid = $(this).attr('data-officeid');
                var title           = $(this).closest('table').find('.input-title').val();
                var major           = $(this).closest('table').find('.input-major').val();
                var where           = $(this).closest('table').find('.input-where').val();
                var mamba           = $(this).closest('table').find('.input-mamba').val();
                var mambawhere          = $(this).closest('table').find('.input-mambawhere').val();
                var doctorate           = $(this).closest('table').find('.input-doctorate').val();
                var doctoratewhere          = $(this).closest('table').find('.input-doctoratewhere').val();
                var prevposition            = $(this).closest('table').find('.input-prevposition').val();
                var prevpositionexp             = $(this).closest('table').find('.input-prevpositionexp').val();
                var presposition            = $(this).closest('table').find('.input-presposition').val();
                var prespositionexp             = $(this).closest('table').find('.input-prespositionexp').val();
                $.ajax({
                    url: '/hr/settings/offices/updatepersonnel',
                    type:"GET",
                    data:{
                        officeempid: officeempid,
                        title: title,
                        major: major,
                        where: where,
                        mamba: mamba,
                        mambawhere: mambawhere,
                        doctorate: doctorate,
                        doctoratewhere: doctoratewhere,
                        prevposition: prevposition,
                        prevpositionexp: prevpositionexp,
                        presposition: presposition,
                        prespositionexp: prespositionexp
                    },
                    // dataType: 'json',
                    // headers: { 'X-CSRF-TOKEN': token },,
                    success: function(data){    
                        if(data == '0')
                        {                            
                            toastr.error('Something went wrong!', 'New Office')
                        }else{
                                $('.each-office[data-id="'+dataofficeid+'"]').click()
                            toastr.success('Updated successfully!', 'Office Personnel')
                        }     
                    }
                })
            })
            $('#btn-export-offices').on('click', function(){           
                window.open("/hr/settings/offices/exportoffices?syid="+selectedsyid,'_blank');
            })
        })
    </script>
@endsection
