<div class="card">
    <div class="card-body">
        <div class="row mb-2">
               <div class="col-md-6">
                   <input type="text" id="input-search" placeholder="Search" class="form-control">
               </div>
               <div class="col-md-6 text-right">
                   {{-- @if(count($classscheds)>0)
                   <button type="button" class="btn btn-success" id="btn-export-pdf"> <i class="fa fa-file-pdf"></i> &nbsp;Export&nbsp; PDF</button>
                   <button type="button" class="btn btn-success" id="btn-export-excel"> <i class="fa fa-file-excel"></i> &nbsp;Export&nbsp; Excel</button>
                   @endif --}}
               </div>
           </div>
           <div class="row">
               <div class="col-md-12">
                   <span class="badge badge-warning">Schedules ({{count($classscheds)}})</span>
               </div>
           </div>
           <div class="row">
               <div class="col-md-12"  style="overflow: scroll; max-height: 600px;">
                   <table id="studentstable" class="table table-bordered tableFixHead table-hover">
                       <thead>
                           <tr>
                               <th style="width: 20px;">#</th>    
                               <th>Subject</th>     
                               <th>Description</th>      
                               <th>Section</th>    
                               <th>TimeBegin</th>    
                               <th>TimeEnd</th>    
                               <th>Days</th>    
                               <th>Room</th>      
                               <th>Enrolled</th>    
                               <th>Instructor</th>    
                               <th style="width: 80px;">Export</th>
                           </tr>
                       </thead>
                       <tbody class="studentscontainer"  style="font-size: 12px;">
                           @if(count($classscheds)>0)
                               @foreach($classscheds as $schedule)
                                   <tr>
                                       <td></td>
                                       <td>{{$schedule->subjcode}}</td>
                                       <td>{{$schedule->subjdesc}}</td>
                                       <td>{{$schedule->sectionname}}</td>
                                       <td>{{$schedule->stime}}</td>
                                       <td>{{$schedule->etime}}</td>
                                       <td>
                                           {{$schedule->days}}
                                       </td>
                                       <td>{{$schedule->roomname}}</td>
                                       <td>{{$schedule->numstudents}}</td>
                                       <td>{{$schedule->teachername ?? ''}}</td>
                                       <td>
                                           <a href="#" class="each-btn-export-jhs" data-schedid="{{$schedule->schedid}}">> <u>Class List</u></a>
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
                    var selectedschoolyear = $('#selectedschoolyear').val();
                    var selectedsemester   = $('#selectedsemester').val();
                    function splitArrayIntoChunksOfLen(arr, len) {
                    var chunks = [], i = 0, n = arr.length;
                    while (i < n) {
                        chunks.push(arr.slice(i, i += len));
                    }
                    return chunks;
                    }
                    var noofschedules='{{count($classscheds)}}';
                    var batches = [];
                    obj = {
                                    selectedschoolyear    : selectedschoolyear,
                                    selectedsemester    : selectedsemester,
                        from    : 1,
                        to      : 50
                    }
                    batches.push(obj)
                    var start = 1;
                    var end = 50;

                    var numcount = 1;
                    var numfrom = 0;
                    var numto = 0;
                    for (let i = 0; i < noofschedules; i++) {
                        
                        if(numfrom<50)
                        {
                            numfrom+=1;
                            start+=1;
                            end+=1;
                        }else{
                                obj = {
                                    selectedschoolyear    : selectedschoolyear,
                                    selectedsemester    : selectedsemester,
                                    from    : start,
                                    to      : end
                                }
                                batches.push(obj)
                                numfrom = 0;
                                numto = 0;
                        }
                    }
                                // console.log(batches.length)
                    // schedules=schedules.replace(/&quot;/gi,"");
                    // schedules=schedules.replace(/\[/gi,"");
                    // schedules=schedules.replace(/\]/gi,"");
                        $(document).on('click','.each-btn-export-jhs',  function(){
                        var selectedschoolyear = $('#selectedschoolyear').val();
                        var selectedsemester   = $('#selectedsemester').val();
                        var selectedgradelevel   = $('#selectedgradelevel').val();
                        var selectedsection   = $('#selectedsection').val();
                            var paramet = {
                                selectedterm        : $('#selectedterm').val(),
                                selectedschoolyear  : selectedschoolyear,
                                acadprogid  : '{{$acadprogid}}',
                                selectedsemester   : selectedsemester, 
                                selectedgradelevel  : selectedgradelevel, 
                                selectedcourse  : selectedcourse, 
                                selectedsection    : selectedsection
                            }
                            window.open("/registrar/summaries/alphaloading/filter?exporttype=pdfstudents&export=1&schedid="+$(this).attr('data-schedid')+"&"+$.param(paramet),'_blank');
                        })
                        $('#btn-export-pdf').on('click', function(){
                            for(var i = 0; i < batches.length; i++) {
                                console.log(batches.length)
                                    // window.open(playlist[i], "_blank");
                                var paramet = {
                                    selectedschoolyear  : selectedschoolyear,
                                    selectedsemester   : selectedsemester, 
                                    selectedgradelevel  : selectedgradelevel, 
                                    selectedcourse  : selectedcourse, 
                                    selectedsection    : selectedsection,
                                    from    : batches[i].from,
                                    to    : batches[i].to
                                }
                                window.open("/registrar/summaries/alphaloading/filter?exporttype=pdf&export=1&"+$.param(paramet),'_blank');
                            }
                        })
                </script>