
<link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
<style>
    
    th, td { white-space: nowrap; vertical-align: middle;}
    div.dataTables_wrapper {
        width: 800px;
        margin: 0 auto;
    }
#studentstable th{
    border: 1px solid #ddd !important;
}
#studentstable td{
    border: 1px solid #ddd !important;
}
.dataTables_filter, .dataTables_info { display: none; }
.dataTables_wrapper{
    margin: 0px !important;
    width: 100% !important;
}
</style>
        <div class="row">
            <div class="col-md-12 text-right">
                {{-- <button type="button" class="btn btn-primary" id="btn-export-excel"><i class="fa fa-file-pdf"></i> Export to Excel</button> --}}
                <button type="button" class="btn btn-primary" id="btn-export-pdf"><i class="fa fa-file-pdf"></i> Export to PDF</button>
            </div>
            <div class="col-md-12">
        <table class="table" style="width:100%; font-size: 11px; " id="studentstable">
                    <thead class="text-center">
                        <tr>
                            <th rowspan="3">Grade/Year Level</th>
                            <th rowspan="3">Section</th>
                            <th rowspan="3">Name of Adviser</th>
                            <th colspan="3" rowspan="2">REGISTERED<br/>LEARNERS<br/>(As of End of<br/>the Month)</th>
                            <th colspan="6">ATTENDANCE</th>
                            <th colspan="9">NLPA</th>
                            <th colspan="9">TRANSFERRED OUT</th>
                            <th colspan="9">TRANSFERRED IN</th>
                        </tr>
                        <tr>
                            <th colspan="3">Daily Average</th>
                            <th colspan="3">Percentage for<br/>the Month</th>
                            <th colspan="3">(A) Cumulative<br/>as of Previous<br/>Month</th>
                            <th colspan="3">(B) For the<br/>Month</th>
                            <th colspan="3">(A+B)<br/>Cumulative as of<br/>End of the Month</th>
                            <th colspan="3">(A) Cumulative<br/>as of Previous<br/>Month</th>
                            <th colspan="3">(B) For the<br/>Month</th>
                            <th colspan="3">(A+B)<br/>Cumulative as of<br/>End of the Month</th>
                            <th colspan="3">(A) Cumulative<br/>as of Previous<br/>Month</th>
                            <th colspan="3">(B) For the<br/>Month</th>
                            <th colspan="3">(A+B)<br/>Cumulative as of<br/>End of the Month</th>
                        </tr>
                        <tr>
                            <th>M</th>
                            <th>F</th>
                            <th>T</th>
                            <th>M</th>
                            <th>F</th>
                            <th>T</th>
                            <th>M</th>
                            <th>F</th>
                            <th>T</th>
                            <th>M</th>
                            <th>F</th>
                            <th>T</th>
                            <th>M</th>
                            <th>F</th>
                            <th>T</th>
                            <th>M</th>
                            <th>F</th>
                            <th>T</th>
                            <th>M</th>
                            <th>F</th>
                            <th>T</th>
                            <th>M</th>
                            <th>F</th>
                            <th>T</th>
                            <th>M</th>
                            <th>F</th>
                            <th>T</th>
                            <th>M</th>
                            <th>F</th>
                            <th>T</th>
                            <th>M</th>
                            <th>F</th>
                            <th>T</th>
                            <th>M</th>
                            <th>F</th>
                            <th>T</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($gradelevels)>0)
                            @foreach($gradelevels as $gradelevel)
                                @if(count($gradelevel->sections)>0)
                                    @foreach($gradelevel->sections as $eachsection)
                                        <tr>
                                            <td>{{$gradelevel->levelname}}</td>
                                            <td>{{$eachsection->sectionname}}</td>
                                            <td>{{$eachsection->lastname}}, {{$eachsection->firstname}} @if($eachsection->middlename != null) {{$eachsection->middlename[0]}}.@endif {{$eachsection->suffix}}</td>
                                            <td>{{$eachsection->registeredmale}}</td>
                                            <td>{{$eachsection->registeredfemale}}</td>
                                            <td>{{$eachsection->registeredmale + $eachsection->registeredfemale}}</td>                                            
                                            @if($eachsection->countdates == 0)
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            @else
                                            @php
                                                $eachsectavemale = $eachsection->registeredmale > 0 ? ((($eachsection->presentmale/$eachsection->countdates)/$eachsection->registeredmale)*100) : 0;
                                                $eachsectavefemale = $eachsection->registeredfemale > 0 ? ((($eachsection->presentfemale/$eachsection->countdates)/$eachsection->registeredfemale)*100) : 0;
                                            @endphp

                                            <td style="text-align: center;">
                                                @php
                                                    $da_m = number_format($eachsection->presentmale/$eachsection->countdates,2);
                                                @endphp
                                                {{$da_m}}
                                            </td>
                                            <td style="text-align: center;">
                                                @php
                                                    $da_f = number_format($eachsection->presentfemale/$eachsection->countdates,2);
                                                @endphp
                                                {{$da_f}}
                                            </td>
                                            <td style="text-align: center;">
                                                {{ $da_m + $da_f }} 
                                            </td>

                                            {{--                                             
                                            <td>{{number_format($eachsection->presentmale/$eachsection->countdates,2)}}</td>
                                            <td>{{number_format($eachsection->presentfemale/$eachsection->countdates,2)}}</td>
                                            <td>{{number_format(((($eachsection->presentmale/$eachsection->countdates)+($eachsection->presentfemale/$eachsection->countdates))/2),2)}}</td> --}}

                                            {{-- <td>{{number_format($eachsectavemale,2)}}</td>
                                            <td>{{number_format($eachsectavefemale,2)}}</td>
                                            <td>{{number_format((($eachsectavemale+$eachsectavefemale)/2),2)}}</td> --}}
                                            
                                            <td style="text-align: center;">
                                                @php
                                                    $pftm_m = number_format(($da_m/$eachsection->registeredmale)*100,2);

                                                    $eachsection->pfm_m = $pftm_m;
                                                @endphp
                                                {{$pftm_m}}
                                            </td>
                                            <td style="text-align: center;">
                                                @php
                                                    $pftm_f = number_format(($da_f/$eachsection->registeredfemale)*100,2);
                                                @endphp
                                                {{$pftm_f}}
                                            </td>

                                            <td style="text-align: center;">
                                                @php
                                                    $pftm_t = number_format( ( $pftm_m + $pftm_f ) / 2,2);
                                                @endphp
                                                {{$pftm_t}}

                                            
                                            </td>

                                            @php
                                                $eachsection->da_m = $da_m;
                                                $eachsection->da_f = $da_f;
                                                $eachsection->da_t =  $da_m + $da_f ;
        
                                                $eachsection->pfm_m = $pftm_m;
                                                $eachsection->pfm_f = $pftm_f;
                                                $eachsection->pfm_t = $pftm_t;                                            
                                            @endphp
                                            @endif
                                            <td>{{$eachsection->nlpa_a_m}}</td>
                                            <td>{{$eachsection->nlpa_a_f}}</td>
                                            <td>{{$eachsection->nlpa_a_m + $eachsection->nlpa_a_f}}</td>
                                            <td>{{$eachsection->nlpa_b_m}}</td>
                                            <td>{{$eachsection->nlpa_b_f}}</td>
                                            <td>{{$eachsection->nlpa_b_m + $eachsection->nlpa_b_f}}</td>
                                            <td>{{$eachsection->nlpa_a_m + $eachsection->nlpa_b_m}}</td>
                                            <td>{{$eachsection->nlpa_a_f + $eachsection->nlpa_b_f}}</td>
                                            <td>{{$eachsection->nlpa_a_f + $eachsection->nlpa_b_f + $eachsection->nlpa_a_m + $eachsection->nlpa_b_m}}</td>
                                            
                                            <td>{{$eachsection->to_a_m}}</td>
                                            <td>{{$eachsection->to_a_f}}</td>
                                            <td>{{$eachsection->to_a_m + $eachsection->to_a_f}}</td>
                                            <td>{{$eachsection->to_b_m}}</td>
                                            <td>{{$eachsection->to_b_f}}</td>
                                            <td>{{$eachsection->to_b_m + $eachsection->to_b_f}}</td>                                            
                                            <td>{{$eachsection->to_a_m + $eachsection->to_b_m}}</td>
                                            <td>{{$eachsection->to_a_f + $eachsection->to_b_f}}</td>
                                            <td>{{$eachsection->to_a_m + $eachsection->to_b_m + $eachsection->to_a_f + $eachsection->to_b_f}}</td>
                                            
                                            <td>{{$eachsection->ti_a_m}}</td>
                                            <td>{{$eachsection->ti_a_f}}</td>
                                            <td>{{$eachsection->ti_a_m + $eachsection->ti_a_f}}</td>
                                            <td>{{$eachsection->ti_b_m}}</td>
                                            <td>{{$eachsection->ti_b_f}}</td>
                                            <td>{{$eachsection->ti_b_m + $eachsection->ti_b_f}}</td>
                                            <td>{{$eachsection->ti_a_m + $eachsection->ti_b_m}}</td>
                                            <td>{{$eachsection->ti_a_f + $eachsection->ti_b_f}}</td>
                                            <td>{{$eachsection->ti_a_m + $eachsection->ti_b_m + $eachsection->ti_a_f + $eachsection->ti_b_f}}</td>

                                        </tr>
                                    @endforeach
                                @endif
                            @endforeach
                            @foreach($gradelevels as $gradelevel)
                            <tr>
                                <th></th>
                                <th>{{$gradelevel->levelname}}</th>
                                <th></th>
    
                                <th>{{collect($gradelevel->sections)->sum('registeredmale')}}</th>
                                <th>{{collect($gradelevel->sections)->sum('registeredfemale')}}</th>
                                <th>{{collect($gradelevel->sections)->sum('registeredmale')+collect($gradelevel->sections)->sum('registeredfemale')}}</th>
                                
                                <th>{{collect($gradelevel->sections)->sum('da_m')}}</th>
                                <th>{{collect($gradelevel->sections)->sum('da_f')}}</th>
                                <th>{{collect($gradelevel->sections)->sum('da_t')}}</th>
                                <th>{{collect($gradelevel->sections)->sum('pfm_m')}}</th>
                                <th>{{collect($gradelevel->sections)->sum('pfm_f')}}</th>
                                <th>{{collect($gradelevel->sections)->sum('pfm_t')}}</th>
                                
                                <th>{{collect($gradelevel->sections)->sum('nlpa_a_m')}}</th>
                                <th>{{collect($gradelevel->sections)->sum('nlpa_a_f')}}</th>
                                <th>{{collect($gradelevel->sections)->sum('nlpa_a_m')+collect($gradelevel->sections)->sum('nlpa_a_f')}}</th>
                                <th>{{collect($gradelevel->sections)->sum('nlpa_b_m')}}</th>
                                <th>{{collect($gradelevel->sections)->sum('nlpa_b_f')}}</th>
                                <th>{{collect($gradelevel->sections)->sum('nlpa_b_m')+collect($gradelevel->sections)->sum('nlpa_b_f')}}</th>
                                <th>{{collect($gradelevel->sections)->sum('nlpa_a_m')+collect($gradelevel->sections)->sum('nlpa_b_m')}}</th>
                                <th>{{collect($gradelevel->sections)->sum('nlpa_a_f')+collect($gradelevel->sections)->sum('nlpa_b_f')}}</th>
                                <th>{{collect($gradelevel->sections)->sum('nlpa_a_m')+collect($gradelevel->sections)->sum('nlpa_b_m')+collect($gradelevel->sections)->sum('nlpa_a_f')+collect($gradelevel->sections)->sum('nlpa_b_f')}}</th>
                                
                                <th>{{collect($gradelevel->sections)->sum('to_a_m')}}</th>
                                <th>{{collect($gradelevel->sections)->sum('to_a_f')}}</th>
                                <th>{{collect($gradelevel->sections)->sum('to_a_m')+collect($gradelevel->sections)->sum('to_a_f')}}</th>
                                <th>{{collect($gradelevel->sections)->sum('to_b_m')}}</th>
                                <th>{{collect($gradelevel->sections)->sum('to_b_f')}}</th>
                                <th>{{collect($gradelevel->sections)->sum('to_b_m')+collect($gradelevel->sections)->sum('to_b_f')}}</th>
                                <th>{{collect($gradelevel->sections)->sum('to_a_m')+collect($gradelevel->sections)->sum('to_b_m')}}</th>
                                <th>{{collect($gradelevel->sections)->sum('to_a_f')+collect($gradelevel->sections)->sum('to_b_f')}}</th>
                                <th>{{collect($gradelevel->sections)->sum('to_a_m')+collect($gradelevel->sections)->sum('to_b_m')+collect($gradelevel->sections)->sum('to_a_f')+collect($gradelevel->sections)->sum('to_b_f')}}</th>
                                
                                <th>{{collect($gradelevel->sections)->sum('ti_a_m')}}</th>
                                <th>{{collect($gradelevel->sections)->sum('ti_a_f')}}</th>
                                <th>{{collect($gradelevel->sections)->sum('ti_a_m')+collect($gradelevel->sections)->sum('ti_a_f')}}</th>
                                <th>{{collect($gradelevel->sections)->sum('ti_b_m')}}</th>
                                <th>{{collect($gradelevel->sections)->sum('ti_b_f')}}</th>
                                <th>{{collect($gradelevel->sections)->sum('ti_b_m')+collect($gradelevel->sections)->sum('ti_b_f')}}</th>
                                <th>{{collect($gradelevel->sections)->sum('ti_a_m')+collect($gradelevel->sections)->sum('ti_b_m')}}</th>
                                <th>{{collect($gradelevel->sections)->sum('ti_a_f')+collect($gradelevel->sections)->sum('ti_b_f')}}</th>
                                <th>{{collect($gradelevel->sections)->sum('ti_a_m')+collect($gradelevel->sections)->sum('ti_b_m')+collect($gradelevel->sections)->sum('ti_a_f')+collect($gradelevel->sections)->sum('ti_b_f')}}</th>
                            </tr>
                            @endforeach
                        @endif                            
                    </tbody>
                </table>
            </div>
        </div>
                <script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>
                <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
                <script src="{{asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>
                <script>
                   $('#studentstable').DataTable({
                "columnDefs": [
                  { "width": "10%", "targets": 0 },
                  { "width": "10%", "targets": 1 }
                ],
                      scrollY:        "500px",
                      scrollX:        true,
                      scrollCollapse: true,
                      paging:         false,
                      "ordering": false,
                      fixedColumns:   {
                          leftColumns: 2
                      },
                  "aaSorting": []
                    })   //using Capital D, which is mandatory to retrieve "api" datatables' object, latest jquery Datatable
                    // $('#myInputTextField').keyup(function(){
                    //     oTable.search($(this).val()).draw() ;
                    // })
                    // $('th').unbind('click.DT');
                    // $('#studentstable').find('.dataTables_sizing').css('display','none')
                    $(document).on('click','#btn-export-pdf', function(){
                        var syid = $('#select-syid').val();
                        var acadprogid = $('#select-acadprogid').val();
                        var selectyear = $('#select-year').val();
                        var selectmonth = $('#select-month').val();
                        
                        window.open("/registar/schoolforms/index?action=getsf4results&syid="+syid+"&acadprogid="+acadprogid+"&selectyear="+selectyear+"&selectmonth="+selectmonth+"&export=pdf");
                    })
                </script>