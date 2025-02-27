@if(count($equivalence) == 0)
    <div class="col-md-12">
        <div class="alert alert-warning alert-dismissible">
            <h5><i class="icon fas fa-exclamation-triangle"></i> Alert!</h5>
            Warning alert preview. No setup found! <br/>
            <button id="btn-create-setup" class="btn btn-default"><i class="fa fa-plus"></i> Create Setup</button>
        </div>
    </div>
    <script>
        $('#btn-view-setup').prop('hidden',true);
        $('#btn-printpdf').prop('hidden', true);
        $('#btn-printexcel').prop('hidden', true);
        
        $('#btn-create-setup').on('click', function(){
                $('#show-calendar').modal('show')

                $.ajax({
                    url: '/forms/form2',
                    type: 'GET',
                    data: {
                        action                  : 'getcalendar',
                        sectionid               : $('#sectionid').attr('data-id'),
                        selectedyear            : $('#selectedyear').val(),
                        selectedmonth           : $('#selectedmonth').val()
                    },
                    success:function(data){
                        $('#calendar-container').empty();
                        $('#calendar-container').append(data)
                        $('.active-date').on('click', function(){
                            $('#selected-dates-container').empty()
                            var idx = $.inArray($(this).attr('data-id'), selecteddates);
                            if (idx == -1) {
                                selecteddates.push($(this).attr('data-id'));
                                $(this).addClass('btn-success')
                            } else {
                                selecteddates.splice(idx, 1);
                                $(this).removeClass('btn-success')
                            }
                        })
                    }
                })
            })
    </script>
@else
    <script>
        $('#btn-view-setup').show();
@if(count($equivalence)==0)
        $('#btn-view-setup').removeAttr('hidden');
        $('#btn-printpdf').removeAttr('hidden');
        $('#btn-printexcel').removeAttr('hidden');
@endif
    </script>
    <div class="col-md-4">
        <button type="button" id="btn-view-setup" class="btn btn-sm btn-danger mr-2 warning"><i class="fa fa-undo"></i> Delete Setup</button> 
        <label style="font-size: 12px;">Complete days of Attendance / No. of schooldays</label>
        <input type="number" class="form-control" id="input-equivalence"  readonly="true" value="{{count($setup[0]->dates)}}" data-id="{{$equivalence[0]->id}}"/>
    </div>
    @if(count($equivalence)>0)
        <div class="col-md-8 text-right">
            <label>&nbsp;</label>
            <button type="button" id="btn-printpdf" class="btn btn-sm btn-default"><i class="fa fa-file-pdf"></i> PDF</button>
                                <button type="button" id="btn-printexcel" class="btn btn-sm btn-default"><i class="fa fa-file-excel"></i> Excel</button>

        </div>
        <div class="col-md-12">
            <table class="table text-center table-bordered" style="font-size: 12px; table-layout: fixed;">
                <thead>
                    <tr>
                        <th style="width: 27%;">Students</th>
                        <th>Submitted Modules</th>
                        <th>Required Modules</th>
                        <th>Days Present</th>
                        <th>Days Absent</th>
                        <th>Remarks</th>
                        <th>&nbsp;</th>
                    </tr>
                    <tr>
                        <td colspan="7"><em>Note: Please input first the <u class="text-bold">required modules</u> for each student before filling in the  <u class="text-bold">submitted modules</u> column.</em></td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-bold">MALE</td>
                        <td colspan="6"></td>
                    </tr>
                    @foreach($students as $student)
                        @if(strtolower($student->gender) == 'male')
                            <tr>
                                <td class="text-left"><strong>{{$student->lastname}}</strong>, {{ucwords(strtolower($student->firstname))}} @if($student->middlename != null){{$student->middlename[0]}}.@endif {{$student->suffix}}</td>
                                <td class="p-0"><input type="number" class="form-control data-input input-submitted p-1" value="{{$student->submitted}}"/></td>
                                <td class="p-0"><input type="number" class="form-control data-input input-required p-1" value="{{$student->required}}"/></td>
                                <td class="days-present" data-id="days-present{{$student->id}}">{{$student->dayspresent}}</td>
                                <td class="days-absent" data-id="days-absent{{$student->id}}">{{$student->daysabsent}}</td>
                                <td class="p-0">
                                    <button type="button" class="btn btn-default student-remarks" data-id="{{$student->id}}" data-toggle="tooltip" data-placement="left" title="@if($student->remarks!=""){{$student->remarks}}@else Add remarks @endif"><i class="fa fa-comment-alt fa-lg	text-info"></i></button>
                                </td>
                                <td class="p-0"><button type="button" class="btn btn-default btn-block btn-lact3-save text-success" data-studid="{{$student->id}}"><i class="fa fa-share"></i></button></td>
                            </tr>
                        @endif
                    @endforeach
                    <tr>
                        <td class="text-bold">FEMALE</td>
                        <td colspan="6"></td>
                    </tr>
                    @foreach($students as $student)
                        @if(strtolower($student->gender) == 'female')
                            <tr>
                                <td class="text-left"><strong>{{$student->lastname}}</strong>, {{ucwords(strtolower($student->firstname))}} @if($student->middlename != null){{$student->middlename[0]}}.@endif {{$student->suffix}}</td>
                                <td class="p-0"><input type="number" class="form-control data-input input-submitted p-1" value="{{$student->submitted}}"/></td>
                                <td class="p-0"><input type="number" class="form-control data-input input-required p-1" value="{{$student->required}}"/></td>
                                <td class="days-present" data-id="days-present{{$student->id}}">{{$student->dayspresent}}</td>
                                <td class="days-absent" data-id="days-absent{{$student->id}}">{{$student->daysabsent}}</td>
                                <td class="p-0">                                    
                                    <button type="button" class="btn btn-default student-remarks" data-id="{{$student->id}}" data-toggle="tooltip" data-placement="left" title="@if($student->remarks!=""){{$student->remarks}}@else Add remarks @endif"><i class="fa fa-comment-alt fa-lg	text-info"></i></button>
                                </td>
                                <td class="p-0"><button type="button" class="btn btn-default btn-block btn-lact3-save text-success" data-studid="{{$student->id}}"><i class="fa fa-share"></i></button></td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endif