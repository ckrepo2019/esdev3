
<style>
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        margin-top: -2px!important;
    }
    .alert-danger {
        color: #721c24;
        background-color: #f8d7da;
        border-color: #f5c6cb;
    }
    td, th{
        padding: 1px !important;
    }
    .info-box{
        min-height: unset;
    }
        
    .select2-container .select2-selection--single {
        height: 40px !important;
    }
</style>
<div class="card" style="border: none !important;">
    <div class="card-body">
        <div class="row">
            {{-- <div class="col-md-2 col-12">
                <h4>Payroll Period</h4>
            </div> --}}
            <div class="col-md-2 mb-3">
                <div class="input-group">
                    <input type="date" id="datefrom" class="form-control searchcontrol" data-toggle="tooltip" title="Date From" value="{{date('Y-m-01')}}">
                </div>
            </div>
            <div class="col-md-2 mb-3">
                <div class="input-group">
                    <input type="date" id="dateto" class="form-control searchcontrol" data-toggle="tooltip" title="Date To" value="{{\Carbon\Carbon::now('Asia/Manila')->toDateString()}}">
                </div>
            </div>
            <div class="col-md-2 col-12 mb-3">
                <div class="input-group">
                    <div class="input-group-append">
                        <button class="btn btn-primary" id="btn_create_payable">Set as Active</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-12 mb-3">
                @if(DB::table('hr_payrollv2')->where('deleted','0')->where('status','1')->count() > 0)
                    <select class="form-control select2" id="select-employee">
                        <option value="0">Select employee</option>
                        @foreach($employees as $employee)
                            <option value="{{$employee->id}}">{{$employee->lastname}}, {{$employee->firstname}} {{$employee->middlename}}</option>
                        @endforeach
                    </select>
                @endif
            </div>
            <div class="col-md-2 col-12 text-right">
                @if(DB::table('hr_payrollv2')->where('deleted','0')->where('status','1')->count() > 0)
                    <button type="button" class="btn btn-default" id="btn-print-summary"><i class="fa fa-file-pdf"></i> Export Summary</button>
                @endif
            </div>
        </div>

        {{-- <div class="row">
            <div class="col-md-12">
                @if(DB::table('hr_payrollv2')->where('deleted','0')->where('status','1')->count() > 0)
                <div class="row mt-2">
                    <div class="col-md-5">                    
                        <select class="form-control select2" id="select-employee">
                            <option value="0">Select employee</option>
                            @foreach($employees as $employee)
                                <option value="{{$employee->id}}">{{$employee->lastname}}, {{$employee->firstname}} {{$employee->middlename}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif
            </div>
        </div> --}}


        <div class="mt-4" id="container-results-salaryinfo">

        </div>
    </div>
</div>


<script>
    $(document).ready(function(){

    // Gian Additional

    $('#select-employee').select2();


    // Events
    $('#container-results-salaryinfo').empty()
    $(document).on('change', '#select-employee', function(){
        $('#container-results-salaryinfo').empty()
        var employeeid = $('#select-employee').val()
        var payrollid = '{{DB::table('hr_payrollv2')->where('deleted','0')->where('status','1')->first()->id}}';
        
        if (employeeid == 0) {
            $('#container-results-salaryinfo').empty()
        } else {
            $.ajax({
                type: "GET",
                url: "/hr/payrollv3/daily_salaryinfo",
                data: {
                    employeeid : employeeid,
                    payrollid : payrollid
                },
                success: function (data) {
                    $('#container-results-salaryinfo').append(data)
                }
            });
        }
        
        
    })

    
   
})
</script>
