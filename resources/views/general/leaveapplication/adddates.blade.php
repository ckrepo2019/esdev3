@if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'gbbc')
    @if(count($dates) == 0)
    <div id="container-alloweddates">
    <div class="row mb-2">
        <div class="col-md-12">
            No allowed dates to apply!
        </div>
    </div>
    </div>
    @else
    <div id="container-alloweddates">
    <div class="row mb-2">
        <div class="col-md-12"><label>Allowed Dates: </label> 
            @foreach($dates as $date)
            <span class="badge badge-default" style="border: 1px solid black;">{{$date->datestr}}</span>
            @endforeach
        </div>
    </div>

    </div>

    <div class="row mb-2">
        <div class="col-md-12">
            <button type="button" class="btn btn-sm btn-info" id="btn-adddates"><i class="fa fa-plus"></i> Add dates</button>
        </div>
    </div>
    <div id="div-adddates">
        
    </div>
    <script>
        var availableDates = ["2-1-2019","3-1-2019","4-1-2019"];

    $(function()
    {
        $('.input-adddates').datepicker({ beforeShowDay:
        function(dt)
        { 
            return [available(dt), "" ];
        }
    , changeMonth: true, changeYear: false});
    });

    function available(date) {
    dmy = date.getDate() + "-" + (date.getMonth()+1) + "-" + date.getFullYear();
    if ($.inArray(dmy, availableDates) != -1) {
        return true;
    } else {
        return false;
    }
    }

    var input = '{{$selecttext}}';
    toParseapp = input.substring(input.indexOf('(') + 1),
    countapp = parseFloat(toParseapp);
    toParsenum = input.substring(input.indexOf('/') + 1),
    countnum = parseFloat(toParsenum);
    var remaining = countnum-countapp;
    console.log(remaining);
    if(remaining % 1 == 0.5)
    {
    remaining+=0.5;
    }

    var clickedadddates = 0;
    var datesnum  = [];
    //Application
    $('#btn-adddates').on('click', function(){
    if('{{count($specificdates)}}'<remaining)
    {
        clickedadddates+=1;
        datesnum.push(clickedadddates)
        $('#div-adddates').append(
            '<div class="row">'+
                '<div class="col-md-5">'+
                    '<input type="date" class="form-control input-adddates"  name="selecteddates[]" required/>'+
                '</div>'+
                '<div class="col-md-5 pb-2 pt-2 pr-0 pl-0">'+
                    '<div class="form-group clearfix">'+
                        '<div class="icheck-primary d-inline">'+
                            '<input type="radio" id="radioPrimary1'+clickedadddates+'" name="r'+clickedadddates+'" checked=""  value="0">'+
                            '<label for="radioPrimary1'+clickedadddates+'">&nbsp;Whole Day&nbsp;</label>'+
                        '</div>'+
                        '<div class="icheck-primary d-inline">'+
                            '<input type="radio" id="radioPrimary2'+clickedadddates+'" name="r'+clickedadddates+'" value="1">'+
                            '<label for="radioPrimary2'+clickedadddates+'">&nbsp;AM&nbsp;</label>'+
                        '</div>'+
                        '<div class="icheck-primary d-inline">'+
                            '<input type="radio" id="radioPrimary3'+clickedadddates+'" name="r'+clickedadddates+'" value="2">'+
                            '<label for="radioPrimary3'+clickedadddates+'">&nbsp;PM&nbsp;</label>'+
                        '</div>'+
                    '</div>'+
                '</div>'+
                '<div class="col-md-2 p-0 text-center">'+
                    '<button type="button" class="btn btn-default btn-removedate" data-numid="'+clickedadddates+'"><i class="fa fa-times"></i></button>'+
                '</div>'+
            '</div>'
        )
    }
    })
    $(document).on('change','.input-adddates', function(){
        if($.inArray($(this).val(), @php echo json_encode($specificdates); @endphp) == -1)
        {

        }
        else{        
            toastr.warning("The selected date is not within the allowed dates!")
            $(this).val('');
        }
    })
    $(document).on('click','.btn-removedate', function(){
    var numid = $(this).attr('data-numid');
    datesnum.splice($.inArray(numid, datesnum),1);
    $(this).closest('.row').remove();
    })

    </script>
    @endif
@else

    {{-- <div class="row mb-2">
        <div class="col-md-12">
            <button type="button" class="btn btn-sm btn-info" id="btn-adddates"><i class="fa fa-plus"></i> Add dates</button>
        </div>
    </div> --}}
    <div id="div-adddates">
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <i class="far fa-calendar-alt"></i>
                </span>
            </div>
            <input type="text" class="form-control float-right input-payrolldates" id="reservation">
            {{-- <div class="input-group-append">
                <button type="button" class="btn btn-sm btn-success btn-days-submit" id="btn-days-submit" data-action="new">
                    <i class="fa fa-share"></i> Add Days
                </button>
            </div> --}}
        </div>
        <div class="row mt-2">
            <div class="col-md-6">
                <div class="form-group form-group-sm form-check">
                    <input type="checkbox" class="form-check-input halfdayleave" id="amleave" style="width: 18px; height: 18px;" halfdaystatus="amleave">
                    <label class="form-check-label" for="amleave">&nbsp;AM Leave</label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group form-group-sm form-check">
                    <input type="checkbox" class="form-check-input halfdayleave" id="pmleave" style="width: 18px; height: 18px;" halfdaystatus="pmleave">
                    <label class="form-check-label" for="pmleave">&nbsp;PM Leave</label>
                </div>
            </div>
        </div>
    </div>
    <script>
    var availableDates = ["2-1-2019","3-1-2019","4-1-2019"];
    $('#reservation').daterangepicker({
        locale: {
        format: 'M/DD/YYYY'
        }
    })
    $(function()
    {
        $('.input-adddates').datepicker({ beforeShowDay:
        function(dt)
        { 
            return [available(dt), "" ];
        }
    , changeMonth: true, changeYear: false});
    });


    function available(date) {
    dmy = date.getDate() + "-" + (date.getMonth()+1) + "-" + date.getFullYear();
    if ($.inArray(dmy, availableDates) != -1) {
        return true;
    } else {
        return false;
    }
    }

    var input = '{{$selecttext}}';
    toParseapp = input.substring(input.indexOf('(') + 1),
    countapp = parseFloat(toParseapp);
    toParsenum = input.substring(input.indexOf('/') + 1),
    countnum = parseFloat(toParsenum);
    var remaining = countnum-countapp;
    if(remaining % 1 == 0.5)
    {
    remaining+=0.5;
    }

    var clickedadddates = 0;
    var datesnum  = [];
    console.log('{{count($specificdates)}}')
    console.log(remaining)
    //Application
    $(document).on('click', '#btn-adddates', function(){
    // $('#btn-adddates').on('click', function(){
    console.log('this is ');
    if('{{count($specificdates)}}'<remaining)
    {
        if(clickedadddates < remaining)
        {
        clickedadddates+=1;
        datesnum.push(clickedadddates)
        $('#div-adddates').append(
            '<div class="row">'+
                '<div class="col-md-10">'+
                    '<input type="date" class="form-control input-adddates"  name="selecteddates[]" required/>'+
                '</div>'+
                '<div class="col-md-5 pb-2 pt-2 pr-0 pl-0" hidden>'+
                    '<div class="form-group clearfix" >'+
                        '<div class="icheck-primary d-inline">'+
                            '<input type="radio" id="radioPrimary1'+clickedadddates+'" name="r'+clickedadddates+'" checked=""  value="0">'+
                            '<label for="radioPrimary1'+clickedadddates+'">&nbsp;Whole Day&nbsp;</label>'+
                        '</div>'+
                        '<div class="icheck-primary d-inline">'+
                            '<input type="radio" id="radioPrimary2'+clickedadddates+'" name="r'+clickedadddates+'" value="1">'+
                            '<label for="radioPrimary2'+clickedadddates+'">&nbsp;AM&nbsp;</label>'+
                        '</div>'+
                        '<div class="icheck-primary d-inline">'+
                            '<input type="radio" id="radioPrimary3'+clickedadddates+'" name="r'+clickedadddates+'" value="2">'+
                            '<label for="radioPrimary3'+clickedadddates+'">&nbsp;PM&nbsp;</label>'+
                        '</div>'+
                    '</div>'+
                '</div>'+
                '<div class="col-md-2 p-0 text-center">'+
                    '<button type="button" class="btn btn-default btn-removedate" data-numid="'+clickedadddates+'"><i class="fa fa-times"></i></button>'+
                '</div>'+
            '</div>'
        )
        }else{
            toastr.warning('Limit reached!')
            return false;
        }
    }
    })
    
    $(document).on('click','.btn-removedate', function(){
    var numid = $(this).attr('data-numid');
    datesnum.splice($.inArray(numid, datesnum),1);
    $(this).closest('.row').remove();
    clickedadddates-=1;
    })

    </script>
@endif