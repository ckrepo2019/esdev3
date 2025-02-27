
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
        z-index: 9999 !important;
}

.dataTables_filter, .dataTables_info { display: none; }
</style>
<div class="card shadow" style="box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important; border: none;">
    <div class="card-header text-right">
        <button type="button" class="btn btn-outline-primary" id="btn-exporttoexcel"><i class="fa fa-file-excel"></i> Export to Excel</button>
        {{-- <button type="button" class="btn btn-outline-primary" id="btn-exporttopdf"><i class="fa fa-file-pdf"></i> Export to PDF</button> --}}
    </div>
    <div class="card-body">
        <table class="table table-head-fixed text-nowrap table-bordered">
          <thead>
              <tr>
                <th rowspan="2">Employee
                    No. (or Tax
                    Identification
                    Number -
                    T.I.N.)</th>
                <th rowspan="2">Name of School Personnel
                    (Arrange by Position, Descending) </th>
                <th rowspan="2">Sex</th>
                <th rowspan="2">Fund
                    Source</th>
                <th rowspan="2">Position/
                    Designation</th>
                <th rowspan="2">Nature of
                    Appointment/
                    Employment
                    Status</th>
                <th colspan="3">EDUCATIONAL QUALIFICATION</th>
                <th rowspan="2">Subject Taught
                    (include Grade &
                    Section), Advisory Class
                    & Other Ancillary
                    Assignments</th>
                <th colspan="4">Daily Program (time duration)</th>
                <th rowspan="2">Remarks (For
                    Detailed Items,
                    Indicate name of
                    school/office, For
                    IP's -Ethnicity)</th>
              </tr>
              <tr>
                <th>Degree / Post
                    Graduate</th>
                <th>Major/
                    Specialization</th>
                <th>Minor</th>
                <th>DAY
                    (M/T/W/
                    TH/F)</th>
                <th>From
                    (00:00)</th>
                <th>To
                    (00:00)</th>
                <th>Total Actual
                    Teaching
                    Minutes per
                    Week</th>
              </tr>
          </thead>
          <tbody>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
          </tbody>
        </table>
    </div>
</div>
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
    $('th').unbind('click.DT');
    function getemployees(){
        
        var syid = $('#select-syid').val()
        var month   = $('#select-month').val()
        $('#thetable').DataTable({
            // "paging": false,
            // "lengthChange": false,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "destroy": true,
            serverSide: true,
            processing: true,
            // ajax:'/student/preregistration/list',
            ajax:{
                url: '/schoolform/sf7',
                type: 'GET',
                data: {
                    action : 'table',
                    syid     :  syid,
                    month       :  month
                }
            },
            columns: [
                // { "data": null },
                // { "data": null },
                // { "data": null },
                // { "data": null },
                // { "data": null },
                // { "data": null },
                // { "data": null },
                // { "data": null },
                // { "data": null },
                // { "data": null },
                // { "data": null },
                // { "data": null },
                // { "data": null },
                // { "data": null },
                { "data": null }
            ],
            columnDefs: [
                {
                    'targets': 0,
                    'orderable': false, 
                    'createdCell':  function (td, cellData, rowData, row, col) {
                        $(td)[0].innerHTML = ' <div class="row">'+
                            '<div class="col-md-3">'+

                                '</div>'+
                                '<div class="col-md-9">'+
                                    '<div class="row">'+
                                        '<div class="col-md-12">'+rowData.lastname+', '+rowData.firstname+'</div>   ' +
                                        '<div class="col-md-12">'+ '<small class="text-primary">'+rowData.tid+'</small></div>   ' +
                                    '</div>'+
                                    
                                
                                '</div>'+
                            '</div>'
                            // $(td).addClass('align-middle')
                    }
                }
            ]
        });
    }
    getemployees();
</script>