@extends('hr.layouts.app')
@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6 text-uppercase">
                <!-- <h1>Standard Deductions Setup</h1> -->
                <h4 class="text-warning" style="text-shadow: 1px 1px 1px #000000">
                <!-- <i class="fa fa-chart-line nav-icon"></i>  -->
                {{$type}} Computation Setup</h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/home">Home</a></li>
                    <li class="breadcrumb-item active">Bracket</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
<section class="content-body">
    <div class="row">
        {{-- <div class="col-md-12">
            <button type="button" class="btn btn-primary float-right" id="btn-add-bracket" data-toggle="modal" data-target="#modal-default">
                <i class="fa fa-plus"></i> Add bracket
            </button>
        </div>
        <div class="col-md-12" id="div-container-new">

        </div> --}}
        <div class="col-md-12">
            <table class="table">
                <thead class="bg-info text-center">
                    <tr>
                        <th style="width: 40%;">Monthly Salary</th>
                        <th>Monthly Salary Credit</th>
                        <th>Employee's Contribution <br> (&#8369;)</th>
                        <th>Employer's Contribution <br> (&#8369;)</th>
                        <th style="width: 15%;"><i class="fa fa-cogs"></i></th>
                    </tr>
                </thead>
                <tr>
                    <td colspan="5">
                        <button type="button" class="btn btn-primary float-right" id="btn-add-bracket" data-toggle="modal" data-target="#modal-default">
                            <i class="fa fa-plus"></i> Add bracket
                        </button>
                    </td>
                </tr>
                <tbody id="container-new">
                </tbody>
                <tfoot>
                    @foreach(collect($brackets)->sortByDesc('id') as $bracket)
                        <form action="/bracketedit" method="get">
                            <tr>
                                <td>
                                    <div class="row">
                                        <input type="hidden" name="id" step="any" class="form-control form-control-sm" value="{{$bracket->id}}"/>
                                        <input type="hidden" name="type" step="any" class="form-control form-control-sm" value="sss"/>
                                        <div class="col-5">
                                            <input type="number" name="rangefrom" step="any" class="form-control form-control-sm" value="{{$bracket->rangefrom}}" disabled/>
                                        </div>
                                        <div class="col-2 text-center">-</div>
                                        <div class="col-5">
                                            <input type="number" name="rangeto" step="any" class="form-control form-control-sm" value="{{$bracket->rangeto}}" disabled/>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="row">
                                        <input type="number" name="monthlysalarycredit" step="any" class="form-control form-control-sm" value="{{$bracket->monthlysalarycredit}}" disabled/>
                                    </div>
                                </td>
                                <td>
                                    <div class="row">
                                        <input type="number" name="eesamount" step="any" class="form-control form-control-sm" value="{{$bracket->eesamount}}" disabled/>
                                    </div>
                                </td>
                                <td> 
                                    <div class="row">
                                        <input type="number" name="ersamount" step="any" class="form-control form-control-sm" value="{{$bracket->ersamount}}" disabled/>
                                    </div>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-warning btn-sm editrowfields">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-default btn-sm delete-bracket" data-id="{{$bracket->id}}">
                                        <i class="fa fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        </form>
                    @endforeach
                </tfoot>

            </table>
        </div>
    </div>
</section>
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script>
    $(document).on('click','.editrowfields[type=button]', function(event){
        // console.log($(this).find('i'))
        event.preventDefault();
        $('.editrowfields').removeClass('btn-primary');
        $('.editrowfields').addClass('btn-warning');
        $('.editrowfields').prop('type','button');
        $('.editrowfields').find('i').removeClass('fa-upload');
        $('.editrowfields').find('i').addClass('fa-edit');
        $('input[type=number]').prop('disabled', true);
        $(this).removeClass('btn-warning');
        $(this).addClass('btn-primary');
        $(this).find('i').removeClass('fa-edit');
        $(this).find('i').addClass('fa-upload');
        $(this).prop('type','submit')
        $(this).closest('tr').find('input').attr('disabled',false);
    })
    $(document).ready(function(){
        $('#btn-add-bracket').on('click', function(){
            var htmlview = '<tr>'+
                '<td>'+
                    '<div class="row">'+
                        '<div class="col-md-5"><input type="number" class="form-control form-control-sm new-bracket-from" placeholder="0.00"/></div>'+
                        '<div class="col-md-2 text-center">-</div>'+
                        '<div class="col-md-5"><input type="number" class="form-control form-control-sm new-bracket-to" placeholder="0.00"/></div>'+
                    '</div>'+
                '</td>'+
                '<td><input type="number" class="form-control form-control-sm new-bracket-credit" placeholder="0.00"/></td>'+
                '<td><input type="number" class="form-control form-control-sm new-bracket-contemployee" placeholder="0.00"/></td>'+
                '<td><input type="number" class="form-control form-control-sm new-bracket-contemployer" placeholder="0.00"/></td>'+
                '<td><button type="button" class="btn btn-sm btn-success btn-submit-new"><i class="fa fa-share"></i></button></td>'+
            '</tr>';
            $('#container-new').append(htmlview)
        })
        $(document).on('click', '.btn-submit-new', function(){
            var thisbutton = $(this);
            var bracketfrom         = $(this).closest('tr').find('.new-bracket-from').val();
            var bracketto           = $(this).closest('tr').find('.new-bracket-to').val();
            var bracketcredit       = $(this).closest('tr').find('.new-bracket-credit').val();
            var bracketcontemployee = $(this).closest('tr').find('.new-bracket-contemployee').val();
            var bracketcontemployer = $(this).closest('tr').find('.new-bracket-contemployer').val();

            var validation          = 0;
            if(bracketfrom.replace(/^\s+|\s+$/g, "").length == 0)
            { 
                validation=1;
                $(this).closest('tr').find('.new-bracket-from').css('border','2px solid red')
            }else{
                $(this).closest('tr').find('.new-bracket-from').removeAttr('style');
            }
            if(bracketto.replace(/^\s+|\s+$/g, "").length == 0)
            { 
                validation=1;
                $(this).closest('tr').find('.new-bracket-to').css('border','2px solid red')
            }else{
                $(this).closest('tr').find('.new-bracket-to').removeAttr('style');
            }
            if(bracketcredit.replace(/^\s+|\s+$/g, "").length == 0)
            { 
                validation=1;
                $(this).closest('tr').find('.new-bracket-credit').css('border','2px solid red')
            }else{
                $(this).closest('tr').find('.new-bracket-credit').removeAttr('style');
            }
            if(bracketcontemployee.replace(/^\s+|\s+$/g, "").length == 0)
            { 
                validation=1;
                $(this).closest('tr').find('.new-bracket-contemployee').css('border','2px solid red')
            }else{
                $(this).closest('tr').find('.new-bracket-contemployee').removeAttr('style');
            }
            if(bracketcontemployer.replace(/^\s+|\s+$/g, "").length == 0)
            { 
                validation=1;
                $(this).closest('tr').find('.new-bracket-contemployer').css('border','2px solid red')
            }else{
                $(this).closest('tr').find('.new-bracket-contemployer').removeAttr('style');
            }
            if(validation == 0)
            {

                $.ajax({
                    url: '/bracketadd',
                    type: 'get',
                    dataType: 'json',
                    data: {
                        type: 'sss',
                        bracketfrom: bracketfrom,
                        bracketto: bracketto,
                        bracketcredit: bracketcredit,
                        bracketcontemployee: bracketcontemployee,
                        bracketcontemployer: bracketcontemployer
                    },
                    success: function(data){
                        thisbutton.closest('tr').find('input').prop('disabled',true)
                        // console.log(thisbutton.closest('tr'))
                        // console.log(thisbutton.closest('tr').find('input'))
                        toastr.success('SAved successfully!','New Bracket')
                        thisbutton.empty()
                        thisbutton.append('<i class="fa fa-edit"></i>')
                        thisbutton.removeClass('btn-success')
                        thisbutton.removeClass('btn-submit-new')
                        thisbutton.addClass('btn-warning')
                        thisbutton.attr('data-id', data);
                        window.location.reload();
                    }
                })
            }else{
                
                toastr.warning('Please fill in important fields!','New Bracket')
            }
        })
        $('.delete-bracket').on('click', function(){
            var thisbutton = $(this);
            var bracketid = $(this).attr('data-id');
            
            Swal.fire({
                title: 'Are you sure you want to delete this bracket?',
                type: 'warning',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Delete',
                showCancelButton: true,
                allowOutsideClick: false,
            }).then((confirm) => {
                    if (confirm.value) {
                        
                        $.ajax({
                            url: '/bracketdelete',
                            type: 'get',
                            dataType: 'json',
                            data: {
                                type: 'sss',
                                id: bracketid
                            },
                            complete: function(data){
                                thisbutton.closest('tr').remove()
                                toastr.success('Deleted successfully!','Delete Bracket')
                            }
                        })
                    }
                })
        })
    })
</script>
@endsection

