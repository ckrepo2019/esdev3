@if(count($setup) == 0)
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
    <div class="col-md-4">
        <label style="font-size: 12px;">Total Equivalence In Class Attendace</label>
        <input type="number" class="form-control" id="input-equivalence"  readonly="true" ondblclick="this.readOnly='';" @if(count($equivalence) > 0) value="{{$equivalence[0]->equivalence}}" data-id="{{$equivalence[0]->id}}" @endif />
    </div>
    <div class="col-md-8 text-right">
        <label>&nbsp;</label><br/>
        <span class="badge badge-info">D</span> Distribution &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="badge badge-warning">R</span> Retreival
    </div>
    @if(count($equivalence)>0)
        <div class="col-md-12">
            <table class="table text-center table-bordered" style="font-size: 12px; table-layout: fixed;">
                <thead>
                    <tr>
                        <th rowspan="2" style="width: 27%;">Students</th>
                        <th colspan="2">1st Week</th>
                        <th colspan="2">2nd Week</th>
                        <th colspan="2">3rd Week</th>
                        <th colspan="2">4th Week</th>
                        <th rowspan="2">Days Present</th>
                        <th rowspan="2">Days Absent</th>
                        <th rowspan="2">Remarks</th>
                        <th rowspan="2">&nbsp;</th>
                    </tr>
                    <tr style="font-size: 14px;">
                        <th><span class="badge badge-info">D</span></th>
                        <th><span class="badge badge-warning">R</span></th>
                        <th><span class="badge badge-info">D</span></th>
                        <th><span class="badge badge-warning">R</span></th>
                        <th><span class="badge badge-info">D</span></th>
                        <th><span class="badge badge-warning">R</span></th>
                        <th><span class="badge badge-info">D</span></th>
                        <th><span class="badge badge-warning">R</span></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-bold">MALE</td>
                        <td colspan="12"></td>
                    </tr>
                    @foreach($students as $student)
                        @if(strtolower($student->gender) == 'male')
                            <tr>
                                <td class="text-left"><strong>{{$student->lastname}}</strong>, {{ucwords(strtolower($student->firstname))}} @if($student->middlename != null){{$student->middlename[0]}}.@endif {{$student->suffix}}</td>
                                <td class="p-0"><input type="number" class="form-control data-input input-first-d p-1" value="{{$student->firstd}}"/></td>
                                <td class="p-0"><input type="number" class="form-control data-input input-first-r p-1" value="{{$student->firstr}}"/></td>
                                <td class="p-0"><input type="number" class="form-control data-input input-second-d p-1" value="{{$student->secondd}}"/></td>
                                <td class="p-0"><input type="number" class="form-control data-input input-second-r p-1" value="{{$student->secondr}}"/></td>
                                <td class="p-0"><input type="number" class="form-control data-input input-third-d p-1" value="{{$student->thirdd}}"/></td>
                                <td class="p-0"><input type="number" class="form-control data-input input-third-r p-1" value="{{$student->thirdr}}"/></td>
                                <td class="p-0"><input type="number" class="form-control data-input input-fourth-d p-1" value="{{$student->fourthd}}"/></td>
                                <td class="p-0"><input type="number" class="form-control data-input input-fourth-r p-1" value="{{$student->fourthr}}"/></td>
                                <td class="days-present" data-id="days-present{{$student->id}}">{{$student->dayspresent}}</td>
                                <td class="days-absent" data-id="days-absent{{$student->id}}">{{$student->daysabsent}}</td>
                                <td class="p-0"><button type="button" class="btn btn-default btn-block text-secondary"><i class="fa fa-sticky-note"></i></button></td>
                                <td class="p-0"><button type="button" class="btn btn-default btn-block btn-lact2-save text-success" data-studid="{{$student->id}}"><i class="fa fa-share"></i></button></td>
                            </tr>
                        @endif
                    @endforeach
                    <tr>
                        <td class="text-bold">FEMALE</td>
                        <td colspan="12"></td>
                    </tr>
                    @foreach($students as $student)
                        @if(strtolower($student->gender) == 'female')
                            <tr>
                                <td class="text-left"><strong>{{$student->lastname}}</strong>, {{ucwords(strtolower($student->firstname))}} @if($student->middlename != null){{$student->middlename[0]}}.@endif {{$student->suffix}}</td>
                                <td class="p-0"><input type="number" class="form-control data-input input-first-d p-1" value="{{$student->firstd}}"/></td>
                                <td class="p-0"><input type="number" class="form-control data-input input-first-r p-1" value="{{$student->firstr}}"/></td>
                                <td class="p-0"><input type="number" class="form-control data-input input-second-d p-1" value="{{$student->secondd}}"/></td>
                                <td class="p-0"><input type="number" class="form-control data-input input-second-r p-1" value="{{$student->secondr}}"/></td>
                                <td class="p-0"><input type="number" class="form-control data-input input-third-d p-1" value="{{$student->thirdd}}"/></td>
                                <td class="p-0"><input type="number" class="form-control data-input input-third-r p-1" value="{{$student->thirdr}}"/></td>
                                <td class="p-0"><input type="number" class="form-control data-input input-fourth-d p-1" value="{{$student->fourthd}}"/></td>
                                <td class="p-0"><input type="number" class="form-control data-input input-fourth-r p-1" value="{{$student->fourthr}}"/></td>
                                <td class="days-present" data-id="days-present{{$student->id}}">{{$student->dayspresent}}</td>
                                <td class="days-absent" data-id="days-absent{{$student->id}}">{{$student->daysabsent}}</td>
                                <td class="p-0"><button type="button" class="btn btn-default btn-block text-secondary"><i class="fa fa-sticky-note"></i></button></td>
                                <td class="p-0"><button type="button" class="btn btn-default btn-block btn-lact2-save text-success" data-studid="{{$student->id}}"><i class="fa fa-share"></i></button></td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endif