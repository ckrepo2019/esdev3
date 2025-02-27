
                                    <div class=" modal-header-full-width   modal-header text-center">
                                        <h5 class="modal-title w-100" id="timescheddetailsviewlabel">
                                            @if($selectedday == 'mon')
                                            MONDAY TIME SCHEDULE
                                            @elseif($selectedday == 'tue')
                                            TUESDAY TIME SCHEDULE
                                            @elseif($selectedday == 'wed')
                                            WEDNESDAY TIME SCHEDULE
                                            @elseif($selectedday == 'thu')
                                            THURSDAY TIME SCHEDULE
                                            @elseif($selectedday == 'fri')
                                            FRIDAY TIME SCHEDULE
                                            @elseif($selectedday == 'sat')
                                            SATURDAY TIME SCHEDULE
                                            @elseif($selectedday == 'sun')
                                            SUNDAY TIME SCHEDULE
                                            @endif
                                        </h5>
                                        <button type="button" class="close " data-dismiss="modal" aria-label="Close">
                                            <span style="font-size: 1.3em;" aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-4" style="border: 1px solid #ddd">
                                                <div class="row text-center">
                                                    <div class="col-md-12">
                                                        <label>Morning</label>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label>IN</label>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label>OUT</label>
                                                    </div>
                                                </div>
                                                <div id="morningcontainer">
                                                    @if(count(collect($timescheds)->where('timeshift','mor')) > 0)
                                                        @foreach($timescheds as $timeschedmor)
                                                            @if($timeschedmor->timeshift == 'mor')
                                                                <div class="row mt-2">
                                                                    <div class="col-md-4">
                                                                        <input type="text" id="timepickerin{{$timeschedmor->id}}d" class="form-control form-control-sm timein" value="{{$timeschedmor->timein}}" readonly/>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <input type="text" id="timepickerout{{$timeschedmor->id}}d" class="form-control form-control-sm timeout" value="{{$timeschedmor->timeout}}" readonly/>
                                                                    </div>
                                                                    <div class="col-md-2">
                                                                        <button type="button" class="btn btn-sm updatetimesched btn-warning btn-block" data-id="mor" timeschedid="{{$timeschedmor->id}}"><i class="fa fa-edit"></i></button>
                                                                    </div>
                                                                    <div class="col-md-2">
                                                                        <button type="button" class="btn btn-sm deletetimesched btn-danger  btn-block" data-id="mor" timeschedid="{{$timeschedmor->id}}"><i class="fa fa-trash"></i></button>
                                                                    </div>
                                                                </div>
                                                                <script>
                                                                    $('#timepickerin{{$timeschedmor->id}}d').timepicker({ modal: false, header: false, footer: false, format: 'hh:MM'});
                                                                    $('#timepickerout{{$timeschedmor->id}}d').timepicker({ modal: false, header: false, footer: false, format: 'hh:MM'});
                                                                </script>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-8"></div>
                                                    <div class="col-md-4">
                                                        <button type="button" class="btn btn-sm btn-default float-right addtimesched btn-block" data-id="morning"><i class="fa fa-plus"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4" style="border: 1px solid #ddd">
                                                <div class="row text-center">
                                                    <div class="col-md-12">
                                                        <label>Afternoon</label>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>IN</label>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>OUT</label>
                                                    </div>
                                                </div>
                                                <div id="afternooncontainer">
                                                    @if(count(collect($timescheds)->where('timeshift','aft')) > 0)
                                                        @foreach($timescheds as $timeschedaft)
                                                            @if($timeschedaft->timeshift == 'aft')
                                                                <div class="row mt-2">
                                                                    <div class="col-md-4">
                                                                        <input type="text" id="timepickerin{{$timeschedaft->id}}" class="form-control form-control-sm timein" value="{{$timeschedaft->timein}}" readonly/>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <input type="text" id="timepickerout{{$timeschedaft->id}}" class="form-control form-control-sm timeout" value="{{$timeschedaft->timeout}}" readonly/>
                                                                    </div>
                                                                    <div class="col-md-2">
                                                                        <button type="button" class="btn btn-sm updatetimesched btn-warning btn-block" data-id="aft" timeschedid="{{$timeschedaft->id}}"><i class="fa fa-edit"></i></button>
                                                                    </div>
                                                                    <div class="col-md-2">
                                                                        <button type="button" class="btn btn-sm deletetimesched btn-danger  btn-block" data-id="aft" timeschedid="{{$timeschedaft->id}}"><i class="fa fa-trash"></i></button>
                                                                    </div>
                                                                </div>
                                                                <script>
                                                                    $('#timepickerin{{$timeschedaft->id}}').timepicker({ modal: false, header: false, footer: false, format: 'hh:MM'});
                                                                    $('#timepickerout{{$timeschedaft->id}}').timepicker({ modal: false, header: false, footer: false, format: 'hh:MM'});
                                                                </script>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-8"></div>
                                                    <div class="col-md-4">
                                                        <button type="button" class="btn btn-sm btn-default float-right addtimesched btn-block" data-id="afternoon"><i class="fa fa-plus"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4" style="border: 1px solid #ddd">
                                                <div class="row text-center">
                                                    <div class="col-md-12">
                                                        <label>Evening</label>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>IN</label>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>OUT</label>
                                                    </div>
                                                </div>
                                                <div id="eveningcontainer">
                                                    @if(count(collect($timescheds)->where('timeshift','eve')) > 0)
                                                        @foreach($timescheds as $timeschedeve)
                                                            @if($timeschedeve->timeshift == 'eve')
                                                                <div class="row mt-2">
                                                                    <div class="col-md-4">
                                                                        <input type="text" id="timepickerin{{$timeschedeve->id}}" class="form-control form-control-sm timein" value="{{$timeschedeve->timein}}" readonly/>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <input type="text" id="timepickerout{{$timeschedeve->id}}" class="form-control form-control-sm timeout" value="{{$timeschedeve->timeout}}" readonly/>
                                                                    </div>
                                                                    <div class="col-md-2">
                                                                        <button type="button" class="btn btn-sm updatetimesched btn-warning btn-block" data-id="aft" timeschedid="{{$timeschedeve->id}}"><i class="fa fa-edit"></i></button>
                                                                    </div>
                                                                    <div class="col-md-2">
                                                                        <button type="button" class="btn btn-sm deletetimesched btn-danger  btn-block" data-id="aft" timeschedid="{{$timeschedeve->id}}"><i class="fa fa-trash"></i></button>
                                                                    </div>
                                                                </div>
                                                                <script>
                                                                    $('#timepickerin{{$timeschedeve->id}}').timepicker({ modal: false, header: false, footer: false, format: 'hh:MM'});
                                                                    $('#timepickerout{{$timeschedeve->id}}').timepicker({ modal: false, header: false, footer: false, format: 'hh:MM'});
                                                                </script>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-8"></div>
                                                    <div class="col-md-4">
                                                        <button type="button" class="btn btn-sm btn-default float-right addtimesched btn-block" data-id="evening"><i class="fa fa-plus"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <script>
                                        
                                        var clickid = 1;
                                        $('.addtimesched').on('click', function(){

                                            if($(this).attr('data-id') == 'morning')
                                            {
                                                $('#morningcontainer').append(
                                                    '<div class="row mt-2">'+
                                                        '<div class="col-md-4">'+
                                                            '<input type="text" id="timepickerin'+clickid+'" class="form-control form-control-sm timein" readonly/>'+
                                                        '</div>'+
                                                        '<div class="col-md-4">'+
                                                            '<input type="text" id="timepickerout'+clickid+'" class="form-control form-control-sm timeout" readonly/>'+
                                                        '</div>'+
                                                        '<div class="col-md-2">'+
                                                            '<button type="button" class="btn btn-sm savetimesched btn-success btn-block" data-id="mor"><i class="fa fa-check"></i></button>'+
                                                        '</div>'+
                                                        '<div class="col-md-2">'+
                                                            '<button type="button" class="btn btn-sm deleterow btn-default  btn-block" data-id="mor"><i class="fa fa-times"></i></button>'+
                                                        '</div>'+
                                                    '</div>'
                                                )
                                            }
                                            if($(this).attr('data-id') == 'afternoon')
                                            {
                                                $('#afternooncontainer').append(
                                                    '<div class="row mt-2">'+
                                                        '<div class="col-md-4">'+
                                                            '<input type="text" id="timepickerin'+clickid+'" class="form-control form-control-sm timein" readonly/>'+
                                                        '</div>'+
                                                        '<div class="col-md-4">'+
                                                            '<input type="text" id="timepickerout'+clickid+'" class="form-control form-control-sm timeout" readonly/>'+
                                                        '</div>'+
                                                        '<div class="col-md-2">'+
                                                            '<button type="button" class="btn btn-sm savetimesched btn-success btn-block" data-id="aft"><i class="fa fa-check"></i></button>'+
                                                        '</div>'+
                                                        '<div class="col-md-2">'+
                                                            '<button type="button" class="btn btn-sm deleterow btn-default  btn-block" data-id="aft"><i class="fa fa-times"></i></button>'+
                                                        '</div>'+
                                                    '</div>'
                                                )
                                            }
                                            if($(this).attr('data-id') == 'evening')
                                            {
                                                $('#eveningcontainer').append(
                                                    '<div class="row mt-2">'+
                                                        '<div class="col-md-4">'+
                                                            '<input type="text" id="timepickerin'+clickid+'" class="form-control form-control-sm timein" readonly/>'+
                                                        '</div>'+
                                                        '<div class="col-md-4">'+
                                                            '<input type="text" id="timepickerout'+clickid+'" class="form-control form-control-sm timeout" readonly/>'+
                                                        '</div>'+
                                                        '<div class="col-md-2">'+
                                                            '<button type="button" class="btn btn-sm savetimesched btn-success btn-block" data-id="eve"><i class="fa fa-check"></i></button>'+
                                                        '</div>'+
                                                        '<div class="col-md-2">'+
                                                            '<button type="button" class="btn btn-sm deleterow btn-default  btn-block" data-id="eve"><i class="fa fa-times"></i></button>'+
                                                        '</div>'+
                                                    '</div>'
                                                )
                                            }
                                            $('#timepickerin'+clickid).timepicker({ modal: false, header: false, footer: false, format: 'hh:MM'});
                                            $('#timepickerout'+clickid).timepicker({ modal: false, header: false, footer: false, format: 'hh:MM'});
                                            clickid+=1;
                                            $('.deleterow').on('click', function(){
                                                $(this).closest('.row').remove()
                                            })
                                            $('.savetimesched').on('click', function(){
                                                var timeshift = $(this).attr('data-id')
                                                var thisrow = $(this).closest('.row');
                                                var submit = 0;
                                                $($(this).closest('.row').find('input')).each(function(){
                                                    if($(this).val().replace(/^\s+|\s+$/g, "").length == 0)
                                                    {                                                        
                                                        $(this).css("border","2px solid red")
                                                    }else{
                                                        submit+=1;
                                                        $(this).removeAttr('style');
                                                    }
                                                })
                                                if(submit >= 2)
                                                {
                                                    var timein  = thisrow.find('.timein').val();
                                                    var timeout = thisrow.find('.timeout').val();
                                                    $.ajax({
                                                        url: '/hr/employeebasicsalaryinfotimesched',
                                                        type: 'GET',
                                                        data: {
                                                            selectedday     : '{{$selectedday}}',
                                                            selectedshift   : timeshift,
                                                            action          : 'add',
                                                            timein          : timein,
                                                            timeout         : timeout,
                                                            employeeid      : '{{$employeeid}}'
                                                        },
                                                        success:function(data)
                                                        {
                                                            if(data == 1)
                                                            {
                                                                thisrow.find('.savetimesched').removeClass('btn-success')
                                                                thisrow.find('.savetimesched').addClass('btn-warning')
                                                                thisrow.find('.savetimesched').empty()
                                                                thisrow.find('.savetimesched').append('<i class="fa fa-edit"></i>')
                                                                thisrow.find('.savetimesched').addClass('updatetimesched')
                                                                thisrow.find('.updatetimesched').removeClass('savetimesched')

                                                                thisrow.find('.deleterow').removeClass('btn-default')
                                                                thisrow.find('.deleterow').addClass('btn-danger')
                                                                thisrow.find('.deleterow').empty()
                                                                thisrow.find('.deleterow').append('<i class="fa fa-trash"></i>')
                                                                thisrow.find('.deleterow').addClass('deletetimesched')
                                                                thisrow.find('.deletetimesched').removeClass('deleterow')


                                                                toastr.success('Added successfully!', 'Time Schedule')
                                                            }else{
                                                                toastr.error('Something went wrong!', 'Time Schedule')
                                                            }
                                                        }
                                                    })
                                                }
                                            })
                                        })
                                        $('.updatetimesched').on('click',function(){
                                            var timeshift = $(this).attr('data-id')
                                            var timeschedid = $(this).attr('timeschedid')
                                            var thisrow = $(this).closest('.row');
                                            var submit = 0;
                                            $($(this).closest('.row').find('input')).each(function(){
                                                if($(this).val().replace(/^\s+|\s+$/g, "").length == 0)
                                                {                                                        
                                                    $(this).css("border","2px solid red")
                                                }else{
                                                    submit+=1;
                                                    $(this).removeAttr('style');
                                                }
                                            })
                                            if(submit >= 2)
                                            {
                                                var timein  = thisrow.find('.timein').val();
                                                var timeout = thisrow.find('.timeout').val();
                                                $.ajax({
                                                    url: '/hr/employeebasicsalaryinfotimesched',
                                                    type: 'GET',
                                                    data: {
                                                        selectedday     : '{{$selectedday}}',
                                                        selectedshift   : timeshift,
                                                        timeschedid     : timeschedid,
                                                        action          : 'update',
                                                        timein          : timein,
                                                        timeout         : timeout,
                                                        employeeid      : '{{$employeeid}}'
                                                    },
                                                    success:function(data)
                                                    {
                                                        if(data == 1)
                                                        {
                                                            toastr.success('Updated successfully!', 'Time Schedule')
                                                        }else{
                                                            toastr.error('Something went wrong!', 'Time Schedule')
                                                        }
                                                    }
                                                })
                                            }
                                        })
                                        $('.deletetimesched').on('click',function(){
                                            var timeschedid = $(this).attr('timeschedid')
                                            var thisrow = $(this).closest('.row');
                                            Swal.fire({
                                                title: 'Are you sure you want to delete this sched?',
                                                type: 'warning',
                                                confirmButtonColor: '#3085d6',
                                                confirmButtonText: 'Delete',
                                                showCancelButton: true,
                                                allowOutsideClick: false
                                            }).then((confirm) => {
                                                    if (confirm.value) {
                                                        $.ajax({
                                                            url: '/hr/employeebasicsalaryinfotimesched',
                                                            type: 'GET',
                                                            data: {
                                                                selectedday     : '{{$selectedday}}',
                                                                timeschedid     : timeschedid,
                                                                action          : 'delete',
                                                                employeeid      : '{{$employeeid}}'
                                                            },
                                                            success: function(data){
                                                                if(data == 1)
                                                                {
                                                                    thisrow.remove()
                                                                    toastr.success('Deleted successfully!', 'Time Schedule')
                                                                }else{
                                                                    toastr.error('Something went wrong!', 'Time Schedule')
                                                                }
                                                            }
                                                        })
                                                    }
                                                })
                                        })
                                    </script>
                                    {{-- <div class="modal-footer-full-width  modal-footer">
                                        <button type="button" class="btn btn-danger btn-md btn-rounded" data-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-primary btn-md btn-rounded">Save changes</button>
                                    </div> --}}