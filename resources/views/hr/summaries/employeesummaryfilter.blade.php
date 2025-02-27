
             
    <div class="card" style="border: none;">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <button type="button" class="btn btn-info">Employees ({{count($employees)}})</button>
                </div>
                <div class="col-md-6 text-right">
                    <button type="button" class="btn btn-default exportsummary" exporttype="pdf"><i class="fa fa-download"></i> PDF</button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                <table id="example1" class="table table-bordered table-hover" style="font-size: 12px;">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th class="no-sort">ID</th>
                            <th>Name</th>
                            <th>Gender</th>
                            <th>Department</th>
                            <th>Designation</th>
                            <th>Employment Status</th>
                            
                        </tr>
                    </thead>
                    <tbody id="resultscontainer">
                        @if(count($employees)>0)
                            @foreach($employees as $employee)
                                <tr>
                                    <td></td>
                                    <td class="text-center"><strong>{{$employee->teacherid}}</strong></td>
                                    <td><strong>{{strtoupper($employee->lastname)}}</strong>, {{ucwords(strtolower($employee->firstname))}} {{ucwords(strtolower($employee->middlename))}} {{ucwords(strtolower($employee->suffix))}}</td>
                                    <td class="text-center">{{ucfirst($employee->gender)}}</td>
                                    <td class="text-center">{{ucwords(strtolower($employee->department))}}</td>
                                    <td class="text-center">{{ucwords(strtolower($employee->designation))}}</td>
                                    <td class="text-center">
                                        
                                        {{-- // 1 = casual; 2 = prov; 3 = regu;4 = parttime; 5 = substitute --}}
                                        @if($employee->employmentstatus == 1)
                                        CASUAL
                                        @elseif($employee->employmentstatus == 2)
                                        PROVISIONARY
                                        @elseif($employee->employmentstatus == 3)
                                        REGULAR
                                        @elseif($employee->employmentstatus == 4)
                                        PART-TIME
                                        @elseif($employee->employmentstatus == 5)
                                        SUBSTITUTE
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('.exportsummary').on('click', function(){
            var exporttype          = $(this).attr('exporttype');
            var selecteddepartment  = $('#selecteddepartment').val();
            var selecteddesignation = $('#selecteddesignation').val();
            var selectedstatus      = $('#selectedstatus').val();
            var selectedgender      = $('#selectedgender').val();
            var paramet = {
                    exporttype          :   exporttype,
                    selecteddepartment  :   selecteddepartment,
                    selecteddesignation :   selecteddesignation,
                    selectedstatus      :   selectedstatus,
                    selectedgender      :   selectedgender
            }
            window.open("/hrreports/summaryofemployees/export?"+$.param(paramet));
        })
    </script>