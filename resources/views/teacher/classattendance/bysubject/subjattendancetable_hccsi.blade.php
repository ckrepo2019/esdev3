
@if(count($students) == 0)

<style>
    table td, table th {
        padding: 0px !important;
    }
    thead{
        background-color: #eee !important;
    }
    
    .table                      {font-size:90%; text-transform: uppercase; }
    
#thetable thead th:first-child  { 
    position: sticky; 
    left: 0; 
    background-color: #fff; 
    outline: 2px solid #dee2e6;
    outline-offset: -1px;
}

#thetable tbody td:first-child  {  
    position: sticky; 
    left: 0; 
    background-color: #fff; 
    background-color: #fff; 
    outline: 2px solid #dee2e6;
    outline-offset: -1px;
}

#thetable thead th:first-child  { 
        position: sticky; left: 0; 
        background-color: #fff; 
        outline: 2px solid #dee2e6;
        outline-offset: -1px;
}

.dataTables_filter, .dataTables_info { display: none; }
</style>
<div class="alert alert-danger" role="alert">
 No students enrolled!
</div>
@else
<style>
    table td, table th {
        padding: 0px !important;
    }
    thead{
        background-color: #eee !important;
    }
    
    .table                      {font-size:90%; text-transform: uppercase; }
    
#thetable thead th:first-child  { 
    position: sticky; 
    left: 0; 
    background-color: #fff; 
    outline: 2px solid #dee2e6;
    outline-offset: -1px;
}

#thetable tbody td:first-child  {  
    position: sticky; 
    left: 0; 
    background-color: #fff; 
    background-color: #fff; 
    outline: 2px solid #dee2e6;
    outline-offset: -1px;
}

#thetable thead th:first-child  { 
        position: sticky; left: 0; 
        background-color: #fff; 
        outline: 2px solid #dee2e6;
        outline-offset: -1px;
}

.dataTables_filter, .dataTables_info { display: none; }
</style>
<div class="card">
   <div class="card-header">
       <div class="row">
           <div class="col-md-6 text-left">
               <button type="button" class="btn btn-default" id="btn-exportexcel"><i class="fa fa-file-excel"></i> EXCEL</button>
               {{-- <button type="button" class="btn btn-default" id="btn-exportpdf"><i class="fa fa-file-pdf"></i> PDF</button> --}}
           </div>
           <div class="col-md-6 text-right">
               <button type="button" class="btn btn-default" id="btn-reload"><i class="fa fa-sync"></i> Reload</button>
               <button type="button" class="btn btn-primary" id="btn-save"><i class="fa fa-share"></i> Save Changes</button>
           </div>
       </div>
       <div class="row">
           <div class="col-md-12">
                 <label><em>Note: Please click the empty boxes to select status.</em></label><br/>
                 <label><em><small>Click multiple times on the selected empty box to see changes.</small></em></label>
             </div>
             <div class="col-md-6">
                <input type="text" class="form-control" id="myInputTextField" placeholder="Search student...">
            </div>
       </div>
     {{-- <div class="btn-group btn-group-sm">
         <button type="button" class="btn btn-primary">Apple</button>
         <button type="button" class="btn btn-primary">Samsung</button>
         <button type="button" class="btn btn-primary">Sony</button>
       </div> --}}
   </div>
</div>
    <table class="table-head-fixed text-nowrap table-bordered" id="thetable" style="font-size: 12px;">
       <thead>
           <tr>
               <th>Student Name</th>
               @if(count($dates)>0)
                   @foreach($dates as $date)
                   <th class="text-center eachdate" data-date="{{$date->date}}">
                       {{$date->datestr}}<br/>{{$date->day}}<br/>
                       {{-- <button type="button" class="btn btn-sm btn-default btn-hide">&nbsp;<i class="fa fa-eye"></i>&nbsp;</button> --}}<br/>
                            <select class="form-control form-control-sm select-column-att" data-date="{{$date->date}}">
                                <option value="delete"></option>
                                <option value="delete">Reset</option>
                                <option value="present">Present</option>
                                <option value="absent">Absent</option>
                                <option value="late">Late</option>
                            </select>
                       {{-- <button type="button" class="btn btn-sm btn-default btn-column-null" data-date="{{$date->date}}">&nbsp;<i class="fa fa-trash"></i>&nbsp;</button>
                       <button type="button" class="btn btn-sm btn-default btn-column-present" data-date="{{$date->date}}">P</button>
                       <button type="button" class="btn btn-sm btn-default btn-column-late" data-date="{{$date->date}}">L</button>
                       <button type="button" class="btn btn-sm btn-default btn-column-absent" data-date="{{$date->date}}">A</button> --}}
                   </th>
                       {{-- <th class="text-center">{{$date->datestr}}<br/>{{$date->day}}</th> --}}
                   @endforeach
               @endif
           </tr>
       </thead>
       <tbody>
           @if(count($students)>0)
             <tr class="bg-info">
                 <td class="bg-info">MALE</td>
                 @if(count($dates)>0)
                     @foreach($dates as $date)
                        <td></td>
                     @endforeach
                @endif
             </tr>
               @foreach($students as $student)
                 @if(strtolower($student->gender) == 'male')
                 <tr class="eachstud" data-id="{{$student->id}}">
                       <td>
                        <div class="row">
                            <div class="col-md-4 align-self-start">
                                <select class="form-control form-control-sm select-row-att" data-id="{{$student->id}}">
                                    <option value="delete"></option>
                                    <option value="delete">Reset</option>
                                    <option value="present">Present</option>
                                    <option value="absent">Absent</option>
                                    <option value="late">Late</option>
                                </select>
                            </div>
                            <div class="col-md-8">
                                <span class="badge badge-info">{{$student->sid}}</span>
                                <span class="badge badge-info">{{$student->description}}</span><br/>
                                <strong>{{$student->lastname}}</strong>, {{ucwords(strtolower($student->firstname))}}
                            </div>
                        </div>
                        {{-- <div class="row mb-1">
                            <div class="col-md-12">
                                <span class="badge badge-info">{{$student->sid}}</span>
                                <span class="badge badge-info">{{$student->description}}</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                {{$student->lastname}}, {{$student->firstname}} {{$student->middlename}} {{$student->suffix}}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-sm btn-default btn-row-null m-0 pt-1 pb-1 pr-1 pl-1" data-id="{{$student->id}}"><i class="fa fa-trash"></i></button>
                                <button type="button" class="btn btn-sm btn-default btn-row-present m-0 pt-0 pb-0 pr-1 pl-1" data-id="{{$student->id}}">P</button>
                                <button type="button" class="btn btn-sm btn-default btn-row-late m-0 pt-0 pb-0 pr-1 pl-1" data-id="{{$student->id}}">L</button>
                                <button type="button" class="btn btn-sm btn-default btn-row-absent m-0 pt-0 pb-0 pr-1 pl-1" data-id="{{$student->id}}">A</button>
                            </div>
                        </div> --}}
                       </td>
                         @if(count($student->attendance)>0)
                             @foreach($student->attendance as $att)
                             <td data-class="attstatus" class="@if(strtolower($att->status) == 'present') bg-success @elseif(strtolower($att->status) == 'absent') bg-danger @elseif(strtolower($att->status) == 'late') bg-warning @elseif(strtolower($att->status) == 'cc') bg-secondary @endif eachstuddate"
                                 data-studid="{{$student->id}}"
                                 data-status="{{$att->status}}"
                                 data-tdate="{{$att->tdate}}"
                                 data-newstatus="{{$att->status}}"
                                 clicked="0" >
                             {{strtoupper($att->status)}} 
                             </td>
                             @endforeach
                         @endif
                     </tr>
                 @endif
               @endforeach
               <tr class="bg-pink">
                   <td class="bg-pink">FEMALE</td>
                   @if(count($dates)>0)
                       @foreach($dates as $date)
                          <td></td>
                       @endforeach
                  @endif
               </tr>
                 @foreach($students as $student)
                   @if(strtolower($student->gender) == 'female')
                   <tr class="eachstud" data-id="{{$student->id}}">
                         <td>
                            <div class="row">
                                <div class="col-md-4 align-self-start">
                                    <select class="form-control form-control-sm select-row-att" data-id="{{$student->id}}">
                                        <option value="reset"></option>
                                        <option value="reset">Reset</option>
                                        <option value="present">Present</option>
                                        <option value="absent">Absent</option>
                                        <option value="late">Late</option>
                                    </select>
                                </div>
                                <div class="col-md-8">
                                    <span class="badge badge-info">{{$student->sid}}</span>
                                    <span class="badge badge-info">{{$student->description}}</span><br/>
                                    <strong>{{$student->lastname}}</strong>, {{ucwords(strtolower($student->firstname))}}
                                </div>
                            </div>
                             {{-- <div class="row mb-1">
                                 <div class="col-md-12">
                                     <span class="badge  bg-pink">{{$student->sid}}</span>
                                     <span class="badge  bg-pink">{{$student->description}}</span>
                                 </div>
                             </div>
                             <div class="row">
                                 <div class="col-md-12">
                                     {{$student->lastname}}, {{$student->firstname}} {{$student->middlename}} {{$student->suffix}}
                                 </div>
                             </div>
                             <div class="row">
                                 <div class="col-md-12">
                                     <button type="button" class="btn btn-sm btn-default btn-row-null m-0 pt-1 pb-1 pr-1 pl-1" data-id="{{$student->id}}"><i class="fa fa-trash"></i></button>
                                     <button type="button" class="btn btn-sm btn-default btn-row-present m-0 pt-0 pb-0 pr-1 pl-1" data-id="{{$student->id}}">P</button>
                                     <button type="button" class="btn btn-sm btn-default btn-row-late m-0 pt-0 pb-0 pr-1 pl-1" data-id="{{$student->id}}">L</button>
                                     <button type="button" class="btn btn-sm btn-default btn-row-absent m-0 pt-0 pb-0 pr-1 pl-1" data-id="{{$student->id}}">A</button>
                                 </div>
                             </div> --}}
                         </td>
                           @if(count($student->attendance)>0)
                               @foreach($student->attendance as $att)
                               <td data-class="attstatus"
                               class="@if(strtolower($att->status) == 'present') bg-success @elseif(strtolower($att->status) == 'absent') bg-danger @elseif(strtolower($att->status) == 'late') bg-warning @elseif(strtolower($att->status) == 'cc') bg-secondary @endif eachstuddate"
                               data-studid="{{$student->id}}"
                               data-status="{{$att->status}}"
                               data-tdate="{{$att->tdate}}"
                               data-newstatus="{{$att->status}}"
                               clicked="0">
                               {{strtoupper($att->status)}} 
                               </td>
                               @endforeach
                           @endif
                       </tr>
                   @endif
                 @endforeach
           @endif
       </tbody>
     </table>
<script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
<script>
    var oTable = $('#thetable').DataTable({
  "columnDefs": [
    { "width": "300px", "targets": 0 }
  ],
fixedColumns: true,
    scrollY:        500,
    scrollX:        true,
    scrollCollapse: true,
    paging:         false,
    fixedColumns:   true,
    "aaSorting": []
    })   //using Capital D, which is mandatory to retrieve "api" datatables' object, latest jquery Datatable
    $('#myInputTextField').keyup(function(){
        oTable.search($(this).val()).draw() ;
    })
    $('th').on('click', function(){
        return false;
    })
    $('th').unbind('click.DT');
</script>
@endif