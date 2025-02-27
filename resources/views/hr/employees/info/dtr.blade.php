
    <div class="tab-pane fade show active" id="custom-content-above-dtr" role="tabpanel" aria-labelledby="custom-content-above-dtr-tab">
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-md-6">
                <strong>Daily Time Record</strong>
            </div>
        </div>
    </div>
    <div class="card-body">
        <label>DTR Period</label>
        <input type="text" name="dtrchangeperiod"  class="form-control form-control-sm col-md-3" id="dtrdaterange" value="{{$currentmonthfirstday}} - {{$currentmonthlastday}}">
        <br>
        <span class="div-only-mobile bg-info row">Swipe left to view more informations</span>
        <br>
        <div class="row" style="overflow: scroll;">

            <table class="table table-bordered" >
                <thead class="text-center">
                    <tr>
                        <th rowspan="2" style="width: 25%;">Date</th>
                        <th colspan="2">AM</th>
                        <th colspan="2">PM</th>
                        {{-- <th rowspan="2">Tardiness<br>(Minutes)</th>
                        <th rowspan="2">Hours<br>Rendered</th> --}}
                    </tr>
                    <tr>
                        <th>IN</th>
                        <th>OUT</th>
                        <th>IN</th>
                        <th>OUT</th>
                    </tr>
                </thead>
                <tbody id="timerecord">
                    @foreach($employeeattendance as $empattendance)
                        <tr>
                            <td>
                                {{$empattendance->date}}
                                @if(strtolower($empattendance->day) == 'saturday' || strtolower($empattendance->day) == 'sunday')
                                    <span class="right badge badge-secondary">{{$empattendance->day}}</span>
                                @else
                                    <span class="right badge badge-default">{{$empattendance->day}}</span>
                                @endif
                            </td>
                            <td class="text-center">{{$empattendance->timerecord->amin}}</td>
                            <td class="text-center">{{$empattendance->timerecord->amout}}</td>
                            <td class="text-center">{{$empattendance->timerecord->pmin}}</td>
                            <td class="text-center">{{$empattendance->timerecord->pmout}}</td>
                            {{-- <td class="text-center"></td>
                            <td class="text-center"></td> --}}
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>
<script>
    
        // ------------------------------------------------------------------------------------ DAILY TIME RECORD
   
   $(function () {
        $('#dtrdaterange').daterangepicker({
            locale: {
                format: 'MM-DD-YYYY'
            }
        });
   });

   $(document).on('change','input[name=dtrchangeperiod]', function(){

        $.ajax({
            url: '/hr/employees/profile/tabdtr/changeperiod',
            type:   "GET",
            dataType:"json",
            data:{
                employeeid  :'{{$profileinfoid}}',
                period      : $(this).val()
            },
            headers: {"Authorization": localStorage.getItem('token')},
            success:function(data) {
                var countrow = 1;
                $('#timerecord').empty();
                $.each(data, function(key, value){
                    $('#timerecord').append(
                        '<tr>'+
                            '<td id="dtr'+countrow+'">'+
                                value.date +
                            '</td>'+
                            '<td class="text-center">'+value.timerecord.amin+'</td>'+
                            '<td class="text-center">'+value.timerecord.amout+'</td>'+
                            '<td class="text-center">'+value.timerecord.pmin+'</td>'+
                            '<td class="text-center">'+value.timerecord.pmout+'</td>'+
                            // '<td class="text-center">'+value.undertime+'</td>'+
                            // '<td class="text-center">'+value.hoursrendered+'</td>'+
                        '</tr>'
                    )
                    if(value.day.toLowerCase() == 'sunday' || value.day.toLowerCase() == 'saturday'){
                        $('#dtr'+countrow).append(
                            ' <span class="right badge badge-secondary">'+value.day+'</span>'
                        )
                    }else{
                        $('#dtr'+countrow).append(
                            ' <span class="right badge badge-default">'+value.day+'</span>'
                        )
                    }
                    // $('#dtr'+countrow).append(
                    //     ' <span class="right badge badge-danger float-right repunchattendance" tdate="'+value.date+'"><i class="fa fa-sync"></i></span>'
                    // )

                    countrow+=1;
                });
            }
        })
   })

    $(document).on('click','.repunchattendance', function() {
        var tdate        = $(this).attr('tdate');
        var employeeid  = '{{$profileinfoid}}';
        
        Swal.fire({
            title: 'Are you sure you want to delete the record from this date?',
            // text: "You won't be able to revert this!",
            html:
                "Date: <strong>" + tdate + '</strong>'+
                '<br>'+
                "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            allowOutsideClick: false
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: '/hr/employees/profile/tabdtr/delete',
                    type:"GET",
                    dataType:"json",
                    data:{
                        tdate: tdate,
                        employeeid: employeeid
                    },
                    // headers: { 'X-CSRF-TOKEN': token },,
                    complete: function(){
                        toastr.success('Reset successfully!')
                        // $('#custom-content-above-dtr-tab').click()
                    }
                })
            }
        })
    });
</script>