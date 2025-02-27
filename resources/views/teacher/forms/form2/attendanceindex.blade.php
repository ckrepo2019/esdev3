<style>
    thead{
        background-color: #eee !important;
    }
    
    .table                      {font-size:90%; text-transform: uppercase; }
.table thead th:first-child  { 
    position: sticky; 
    left: 0; 
    background-color: #fff; 
    outline: 2px solid #dee2e6;
    outline-offset: -1px;
    z-index: 999 !important
}

.table tbody td:first-child  {  
    position: sticky; 
    left: 0; 
    background-color: #fff; 
    width: 150px !important;
    background-color: #fff; 
    outline: 2px solid #dee2e6;
    outline-offset: -1px;
}

.table thead th:first-child  { 
        position: sticky; left: 0; 
        width: 150px !important;
        background-color: #fff; 
        outline: 2px solid #dee2e6;
        outline-offset: -1px;
}


</style>
            <div class="row">
                <div class="col-md-6">
                    <label><em>Note: Please click the empty boxes to select status.</em></label><br/>
                    <label><em><small>Click multiple times on the selected empty box to see changes.</small></em></label>
                </div>
                <div class="col-md-6 text-right">
                  {{-- <button type="button" class="btn btn-primary save-button" id="btn-save" ><i class="fa fa-share"></i> Save Changes</button> --}}
                  {{-- <div class="btn-group btn-group-sm">
                      <button type="button" class="btn btn-primary">Apple</button>
                      <button type="button" class="btn btn-primary">Samsung</button>
                      <button type="button" class="btn btn-primary">Sony</button>
                    </div> --}}
                </div>
            </div>
            <div class="card-body  table-responsive p-0" style="height: 700px;">
                <table class="table table-head-fixed text-nowrap table-bordered">
                  <thead>
                      <tr>
                          <th>Student Name</th>
                          @if(count($dates)>0)
                              @foreach($dates as $date)
                                  <th class="text-center eachdate" data-date="{{$date->date}}">
                                      {{$date->datestr}}<br/>{{$date->day}}<br/>
                                      {{-- <button type="button" class="btn btn-sm btn-default btn-hide">&nbsp;<i class="fa fa-eye"></i>&nbsp;</button> --}}
                                      <button type="button" class="btn btn-sm btn-default btn-column-null" data-date="{{$date->date}}">&nbsp;<i class="fa fa-trash"></i>&nbsp;</button>
                                      <button type="button" class="btn btn-sm btn-default btn-column-present" data-date="{{$date->date}}">P</button>
                                      <button type="button" class="btn btn-sm btn-default btn-column-late" data-date="{{$date->date}}">L</button>
                                      <button type="button" class="btn btn-sm btn-default btn-column-absent" data-date="{{$date->date}}">A</button>
                                  </th>
                              @endforeach
                          @endif
                      </tr>
                  </thead>
                  <tbody>
                      @if(count($students)>0)
                        <tr class="bg-info">
                            <td class="bg-info">MALE</td>
                            <td colspan="{{count($dates)}}"></td>
                        </tr>
                          @foreach($students as $student)
                            @if(strtolower($student->gender) == 'male')
                                <tr class="eachstud" data-id="{{$student->id}}">
                                      <td class="p-1">
                                          <div class="row mb-1">
                                              <div class="col-md-12">
                                                  <span class="badge badge-info">{{$student->sid}}</span>
                                                  {{-- <span class="badge badge-info">{{$student->description}}</span> --}}
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
                                          </div>
                                      </td>
                                    @if(count($student->attendance)>0)
                                        @foreach($student->attendance as $att)
                                        <td data-class="attstatus" class="@if(strtolower($att->status) == 'present') bg-success @elseif(strtolower($att->status) == 'absent') bg-danger @elseif(strtolower($att->status) == 'late') bg-warning @elseif(strtolower($att->status) == 'cc') bg-secondary @endif eachstuddate"
                                            data-studid="{{$student->id}}"
                                            data-tdate="{{$att->tdate}}"
                                             {{-- @if($att->status == null)
                                              data-status="PRESENT" 
                                              data-newstatus="PRESENT"
                                              style="background-color: #8fdba3" 
                                              clicked="1"
                                             @else  --}}
                                                  data-status="{{$att->status}}"
                                                  data-newstatus="{{$att->status}}"
                                                  clicked="0"
                                              {{-- @endif --}}
                                              >
                                             {{-- @if($att->status == null)
                                             Present<br/> <small>(Default)</small>
                                             @else --}}
                                             {{$att->status}} 
                                             {{-- @endif --}}
                                        </td>
                                        @endforeach
                                    @endif
                                </tr>
                            @endif
                          @endforeach
                          <tr class="bg-pink">
                              <td class="bg-pink">FEMALE</td>
                              <td colspan="{{count($dates)}}"></td>
                          </tr>
                            @foreach($students as $student)
                              @if(strtolower($student->gender) == 'female')
                                  <tr class="eachstud" data-id="{{$student->id}}">
                                    <td class="p-1">
                                          <div class="row mb-1">
                                              <div class="col-md-12">
                                                  <span class="badge badge-info">{{$student->sid}}</span>
                                                  {{-- <span class="badge badge-info">{{$student->description}}</span> --}}
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
                                          </div>
                                      </td>
                                      @if(count($student->attendance)>0)
                                          @foreach($student->attendance as $att)
                                          <td data-class="attstatus"
                                          class="@if(strtolower($att->status) == 'present') bg-success @elseif(strtolower($att->status) == 'absent') bg-danger @elseif(strtolower($att->status) == 'late') bg-warning @elseif(strtolower($att->status) == 'cc') bg-secondary @endif eachstuddate"
                                          data-studid="{{$student->id}}"
                                          data-tdate="{{$att->tdate}}"
                                          {{-- @if($att->status == null)
                                           data-status="PRESENT" 
                                           data-newstatus="PRESENT"
                                           style="background-color: #8fdba3" 
                                           clicked="1"
                                          @else  --}}
                                               data-status="{{$att->status}}"
                                               data-newstatus="{{$att->status}}"
                                               clicked="0"
                                           {{-- @endif --}}
                                           >
                                          {{-- @if($att->status == null)
                                          Present<br/> <small>(Default)</small>
                                          @else --}}
                                          {{$att->status}} 
                                          {{-- @endif --}}
                                          </td>
                                          @endforeach
                                      @endif
                                  </tr>
                              @endif
                            @endforeach
                      @endif
                  </tbody>
                </table>
            </div>
            
<!-- Toastr -->
<script src="{{asset('plugins/toastr/toastr.min.js')}}"></script>
<script>
    $(document).ready(function(){
        var columnid = 0;
        $('body').addClass('sidebar-collapse');
        $('#btn-reload').hide();
        var selecteddates = [];
        
        $(document).on('click','.active-date', function(){
            $('#selected-dates-container').empty()
            var idx = $.inArray($(this).attr('data-id'), selecteddates);
            if (idx == -1) {
                selecteddates.push($(this).attr('data-id'));
                $(this).addClass('btn-success')
            } else {
                selecteddates.splice(idx, 1);
                $(this).removeClass('btn-success')
            }
            selecteddates.sort(function(a, b) {
                return a - b;
            });
            if(selecteddates.length == 0)
            {
                $('#btn-generate').hide();
            }else{
                $('#btn-generate').show();
            }
        })
        var arr = ['present', 'absent', 'late', 'none'];
        // var arr = ['present', 'absent', 'late', 'cc','none'];
        i = 0;

        $(document).on('click', 'td[data-class="attstatus"]', function() {
            if($(this).attr('clicked') == 0)
            {
                i = 0;
            }
            $(this).attr('clicked','1');
            var controlclicks = $('td[clicked="1"]').length;
            // if(controlclicks == 16)
            // {
            //     toastr.warning('Limited. Please save changes first!', 'Class Attendance')
            // }else{
                if(i === arr.length){
                    i=0;   
                }
                if(arr[i] == 'present')
                {
                    $(this).removeAttr('class')
                    $(this).addClass('bg-success')
                    $(this).text('PRESENT')
                }
                else if(arr[i] == 'absent')
                {
                    $(this).removeAttr('class')
                    $(this).addClass('bg-danger')
                    $(this).text('ABSENT')
                }
                else if(arr[i] == 'late')
                {
                    $(this).removeAttr('class')
                    $(this).addClass('bg-warning')
                    $(this).text('LATE')
                }
                else if(arr[i] == 'cc')
                {
                    $(this).removeAttr('class')
                    $(this).addClass('bg-secondary')
                    $(this).text('CC')
                }else{
                    $(this).removeAttr('class')
                    $(this).text('')
                }
                $(this).attr('data-newstatus',arr[i])
                i++;
                return false;
            // }
        });

        $(document).on('click', '.btn-hide', function(){
            columnid = $(this).closest('th').index();
            $(this).closest('th').remove();
            $("tr.eachstud").each(function() {
                $(this).children("td:eq("+columnid+")").remove();
            });
        })
        $(document).on('click', '.btn-column-null', function(){
            columnid = $(this).closest('th').index();
            var selecteddate = $(this).attr('data-date');
            var studids = []
            $('.eachstud').each(function(){
                studids.push($(this).attr('data-id'));
            })
            Swal.fire({
                title: 'Are you sure you want to delete the attedance from this date?',
                // text: "You won't be able to revert this!",
                html: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                allowOutsideClick: false
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: '/classattendance/deleteattendancecol',
                        type:"GET",
                        dataType:"json",
                        data:{
                            tdate    :  selecteddate,
                            studids    : JSON.stringify(studids),
                            levelid  : '{{$levelid}}',
                            sectionid: '{{$sectionid}}'
                        },
                        // headers: { 'X-CSRF-TOKEN': token },,
                        complete: function(){
                            toastr.success('Reset successfully!')
                            $("tr.eachstud").each(function() {
                                $(this).children("td:eq("+columnid+")").removeAttr('class');
                                $(this).children("td:eq("+columnid+")").removeAttr('style');
                                $(this).children("td:eq("+columnid+")").text('');
                                $(this).children("td:eq("+columnid+")").attr('data-newstatus','none');
                                $(this).children("td:eq("+columnid+")").attr('clicked','0');
                            });
                        }
                    })
                }
            })
        })
        $(document).on('click', '.btn-column-present', function(){
            columnid = $(this).closest('th').index();
            var selecteddate = $(this).attr('data-date');
            var studids = []
            $('.eachstud').each(function(){
                studids.push($(this).attr('data-id'));
            })
            Swal.fire({
                title: 'Are you sure you want to mark this column PRESENT?',
                // text: "You won't be able to revert this!",
                html: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Mark',
                allowOutsideClick: false
            }).then((result) => {
                if (result.value) {
                    Swal.fire({
                        title: 'Saving changes...',
                        allowOutsideClick: false,
                        closeOnClickOutside: false,
                        onBeforeOpen: () => {
                            Swal.showLoading()
                        }
                    }) 
                    $.ajax({
                        url: '/classattendance/presentattendancecol',
                        type:"GET",
                        dataType:"json",
                        data:{
                            tdate    :  selecteddate,
                            studids    : JSON.stringify(studids),
                            levelid  : '{{$levelid}}',
                            sectionid: '{{$sectionid}}'
                        },
                        // headers: { 'X-CSRF-TOKEN': token },,
                        complete: function(){
                            toastr.success('Updated successfully!')
                            $("tr.eachstud").each(function() {
                                $(this).children("td:eq("+columnid+")").removeAttr('class');
                                $(this).children("td:eq("+columnid+")").addClass('bg-success');
                                $(this).children("td:eq("+columnid+")").text('PRESENT');
                                $(this).children("td:eq("+columnid+")").attr('data-newstatus','PRESENT');
                                $(this).children("td:eq("+columnid+")").attr('data-status','PRESENT');
                                $(this).children("td:eq("+columnid+")").attr('clicked','0');
                            });
                            $(".swal2-container").remove();
                            $('body').removeClass('swal2-shown')
                            $('body').removeClass('swal2-height-auto')
                        }
                    })
                }
            })
        })
        $(document).on('click', '.btn-column-late', function(){
            columnid = $(this).closest('th').index();
            var selecteddate = $(this).attr('data-date');
            var studids = []
            $('.eachstud').each(function(){
                studids.push($(this).attr('data-id'));
            })
            Swal.fire({
                title: 'Are you sure you want to mark this column LATE?',
                // text: "You won't be able to revert this!",
                html: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Mark',
                allowOutsideClick: false
            }).then((result) => {
                if (result.value) {
                    Swal.fire({
                        title: 'Saving changes...',
                        allowOutsideClick: false,
                        closeOnClickOutside: false,
                        onBeforeOpen: () => {
                            Swal.showLoading()
                        }
                    }) 
                    $.ajax({
                        url: '/classattendance/lateattendancecol',
                        type:"GET",
                        dataType:"json",
                        data:{
                            tdate    :  selecteddate,
                            studids    : JSON.stringify(studids),
                            levelid  : '{{$levelid}}',
                            sectionid: '{{$sectionid}}'
                        },
                        // headers: { 'X-CSRF-TOKEN': token },,
                        complete: function(){
                            toastr.success('Updated successfully!')
                            $("tr.eachstud").each(function() {
                                $(this).children("td:eq("+columnid+")").removeAttr('class');
                                $(this).children("td:eq("+columnid+")").addClass('bg-warning');
                                $(this).children("td:eq("+columnid+")").text('LATE');
                                $(this).children("td:eq("+columnid+")").attr('data-newstatus','LATE');
                                $(this).children("td:eq("+columnid+")").attr('data-status','LATE');
                                $(this).children("td:eq("+columnid+")").attr('clicked','0');
                            });
                            $(".swal2-container").remove();
                            $('body').removeClass('swal2-shown')
                            $('body').removeClass('swal2-height-auto')
                        }
                    })
                }
            })
        })
        $(document).on('click', '.btn-column-absent', function(){
            columnid = $(this).closest('th').index();
            var selecteddate = $(this).attr('data-date');
            var studids = []
            $('.eachstud').each(function(){
                studids.push($(this).attr('data-id'));
            })
            Swal.fire({
                title: 'Are you sure you want to mark this column ABSENT?',
                // text: "You won't be able to revert this!",
                html: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Mark',
                allowOutsideClick: false
            }).then((result) => {
                if (result.value) {
                    Swal.fire({
                        title: 'Saving changes...',
                        allowOutsideClick: false,
                        closeOnClickOutside: false,
                        onBeforeOpen: () => {
                            Swal.showLoading()
                        }
                    }) 
                    $.ajax({
                        url: '/classattendance/absentattendancecol',
                        type:"GET",
                        dataType:"json",
                        data:{
                            tdate    :  selecteddate,
                            studids    : JSON.stringify(studids),
                            levelid  : '{{$levelid}}',
                            sectionid: '{{$sectionid}}'
                        },
                        // headers: { 'X-CSRF-TOKEN': token },,
                        complete: function(){
                            toastr.success('Updated successfully!')
                            $("tr.eachstud").each(function() {
                                $(this).children("td:eq("+columnid+")").removeAttr('class');
                                $(this).children("td:eq("+columnid+")").addClass('bg-danger');
                                $(this).children("td:eq("+columnid+")").text('ABSENT');
                                $(this).children("td:eq("+columnid+")").attr('data-newstatus','ABSENT');
                                $(this).children("td:eq("+columnid+")").attr('data-status','ABSENT');
                                $(this).children("td:eq("+columnid+")").attr('clicked','0');
                            });
                            $(".swal2-container").remove();
                            $('body').removeClass('swal2-shown')
                            $('body').removeClass('swal2-height-auto')
                        }
                    })
                }
            })
        })
        $(document).on('click','.btn-row-null', function(){
            var studid = $(this).attr('data-id');
            var thistr = $(this).closest('tr');
            var dates = []
            $('.eachdate').each(function(){
                dates.push($(this).attr('data-date'));
            })
            Swal.fire({
                title: 'Are you sure you want to delete the attedance of the selected student?',
                // text: "You won't be able to revert this!",
                html: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                allowOutsideClick: false
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: '/classattendance/deleteattendancerow',
                        type:"GET",
                        dataType:"json",
                        data:{
                            studid   :  studid,
                            dates    : JSON.stringify(dates),
                            levelid  : '{{$levelid}}',
                            sectionid: '{{$sectionid}}'
                        },
                        // headers: { 'X-CSRF-TOKEN': token },,
                        complete: function(){
                            toastr.success('Deleted successfully!')
                            thistr.find('.eachstuddate').each(function(){
                                $(this).removeAttr('class');
                                $(this).addClass('eachstuddate');
                                $(this).removeAttr('style');
                                $(this).text('');
                                $(this).attr('data-newstatus','none');
                                $(this).attr('clicked','0');
                            })
                        }
                    })
                }
            })
        })
        $(document).on('click','.btn-row-present', function(){
            var studid = $(this).attr('data-id');
            var thistr = $(this).closest('tr');
            var dates = []
            $('.eachdate').each(function(){
                dates.push($(this).attr('data-date'));
            })
            Swal.fire({
                title: 'Are you sure you want to mark this row PRESENT?',
                // text: "You won't be able to revert this!",
                html: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Mark',
                allowOutsideClick: false
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: '/classattendance/presentattendancerow',
                        type:"GET",
                        dataType:"json",
                        data:{
                            studid   :  studid,
                            dates    : JSON.stringify(dates),
                            levelid  : '{{$levelid}}',
                            sectionid: '{{$sectionid}}'
                        },
                        // headers: { 'X-CSRF-TOKEN': token },,
                        complete: function(){
                            toastr.success('Marked successfully!')
                            thistr.find('.eachstuddate').each(function(){
                                $(this).removeAttr('class');
                                $(this).addClass('eachstuddate');
                                $(this).addClass('bg-success');
                                $(this).removeAttr('style');
                                $(this).text('PRESENT');
                                $(this).attr('data-newstatus','PRESENT');
                                $(this).attr('data-status','PRESENT');
                                $(this).attr('clicked','0');
                            })
                            $('#btn-reload').click()
                        }
                    })
                }
            })
        })
        $(document).on('click','.btn-row-late', function(){
            var studid = $(this).attr('data-id');
            var thistr = $(this).closest('tr');
            var dates = []
            $('.eachdate').each(function(){
                dates.push($(this).attr('data-date'));
            })
            Swal.fire({
                title: 'Are you sure you want to mark this row LATE?',
                // text: "You won't be able to revert this!",
                html: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Mark',
                allowOutsideClick: false
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: '/classattendance/lateattendancerow',
                        type:"GET",
                        dataType:"json",
                        data:{
                            studid   :  studid,
                            dates    : JSON.stringify(dates),
                            levelid  : '{{$levelid}}',
                            sectionid: '{{$sectionid}}'
                        },
                        // headers: { 'X-CSRF-TOKEN': token },,
                        complete: function(){
                            toastr.success('Marked successfully!')
                            thistr.find('.eachstuddate').each(function(){
                                $(this).removeAttr('class');
                                $(this).addClass('eachstuddate');
                                $(this).addClass('bg-warning');
                                $(this).removeAttr('style');
                                $(this).text('LATE');
                                $(this).attr('data-newstatus','LATE');
                                $(this).attr('data-status','LATE');
                                $(this).attr('clicked','0');
                            })
                        }
                    })
                }
            })
        })
        $(document).on('click','.btn-row-absent', function(){
            var studid = $(this).attr('data-id');
            var thistr = $(this).closest('tr');
            var dates = []
            $('.eachdate').each(function(){
                dates.push($(this).attr('data-date'));
            })
            Swal.fire({
                title: 'Are you sure you want to mark this row ABSENT?',
                // text: "You won't be able to revert this!",
                html: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Mark',
                allowOutsideClick: false
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: '/classattendance/absentattendancerow',
                        type:"GET",
                        dataType:"json",
                        data:{
                            studid   :  studid,
                            dates    : JSON.stringify(dates),
                            levelid  : '{{$levelid}}',
                            sectionid: '{{$sectionid}}'
                        },
                        // headers: { 'X-CSRF-TOKEN': token },,
                        complete: function(){
                            toastr.success('Marked successfully!')
                            thistr.find('.eachstuddate').each(function(){
                                $(this).removeAttr('class');
                                $(this).addClass('eachstuddate');
                                $(this).addClass('bg-danger');
                                $(this).removeAttr('style');
                                $(this).text('ABSENT');
                                $(this).attr('data-newstatus','ABSENT');
                                $(this).attr('data-status','ABSENT');
                                $(this).attr('clicked','0');
                            })
                        }
                    })
                }
            })
        })
    })
</script>