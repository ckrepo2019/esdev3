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
        <div class="col-md-12">
            <table class="table text-center">
                <thead class="bg-info text-center">
                    <tr>
                        <th colspan="3">
                            DAILY
                        </th>
                    </tr>
                    <tr>
                        <th>
                            Bracket
                        </th>
                        <th>
                            Compensation Range
                        </th>
                        <th>
                            Prescribed Withholding Tax
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($brackets as $bracket)
                        @if($bracket->salarytypeid == 1)
                            <tr >
                                <td>{{$bracket->bracket}}</td>
                                <td>{{$bracket->rangefrom}} - {{$bracket->rangeto}}</td>
                                <td>
                                    {{$bracket->prescribeamount}}
                                    <br>
                                    +{{$bracket->prescriberate}} % over {{$bracket->prescribeover}}
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <table class="table text-center">
                <thead class="bg-info text-center">
                    <tr>
                        <th colspan="3">
                            WEEKLY
                        </th>
                    </tr>
                    <tr>
                        <th>
                            Bracket
                        </th>
                        <th>
                            Compensation Range
                        </th>
                        <th>
                            Prescribed Withholding Tax
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($brackets as $bracket)
                        @if($bracket->salarytypeid == 2)
                            <tr >
                                <td>{{$bracket->bracket}}</td>
                                <td>{{$bracket->rangefrom}} - {{$bracket->rangeto}}</td>
                                <td>
                                    {{$bracket->prescribeamount}}
                                    <br>
                                    +{{$bracket->prescriberate}} % over {{$bracket->prescribeover}}
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <table class="table text-center">
                <thead class="bg-info text-center">
                    <tr>
                        <th colspan="3">
                            MONTHLY
                        </th>
                    </tr>
                    <tr>
                        <th>
                            Bracket
                        </th>
                        <th>
                            Compensation Range
                        </th>
                        <th>
                            Prescribed Withholding Tax
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($brackets as $bracket)
                        @if($bracket->salarytypeid == 4)
                            <tr >
                                <td>{{$bracket->bracket}}</td>
                                <td>{{$bracket->rangefrom}} - {{$bracket->rangeto}}</td>
                                <td>
                                    {{$bracket->prescribeamount}}
                                    <br>
                                    +{{$bracket->prescriberate}} % over {{$bracket->prescribeover}}
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
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
</script>
@endsection

