<style>
      .grade_view_table thead th:last-child  { 
         width:40px !important;
         position: sticky; 
         right: 0; 
         background-color: #fff; 
         outline: 2px solid #dee2e6;
         outline-offset: -1px;
     }

     .grade_view_table tbody th:last-child  { 
         width:40px !important;
         position: sticky; 
         right: 0; 
         background-color: #fff; 
         outline: 2px solid #dee2e6;
         outline-offset: -1px;
         }


      .grade_view_table thead th:nth-last-child(2)  { 
         position: sticky; 
         right: 62px; 
         width:50px !important;
         background-color: #fff; 
         outline: 2px solid #dee2e6;
         outline-offset: -1px;
     }

     .grade_view_table tbody th:nth-last-child(2)  { 
         position: sticky; 
         right:  62px; 
         width:50px !important;
         background-color: #fff; 
         outline: 2px solid #dee2e6;
         outline-offset: -1px;
         }
         
     .grade_view_table tbody th:first-child  {  
         position: sticky; 
         left: 0; 
         background-color: #fff; 
         width: 50px !important;
         background-color: #fff; 
         outline: 2px solid #dee2e6;
         outline-offset: -1px;
     }

     .grade_view_table thead th:first-child  { 
             position: sticky; left: 0; 
             width: 50px !important;
             background-color: #fff; 
             outline: 2px solid #dee2e6;
             outline-offset: -1px;
     }

     .grade_view_table tbody th:nth-child(2)   {  
         position: sticky; 
         left: 53px; 
         background-color: #fff; 
         width: 119px !important;
         background-color: #fff; 
         outline: 2px solid #dee2e6;
         outline-offset: -1px;
     }

     .grade_view_table thead th:nth-child(2)   { 
             position: sticky; left: 53px; 
             width: 119px !important;
             background-color: #fff; 
             outline: 2px solid #dee2e6;
             outline-offset: -1px;
     }

 

     .grade_view_table thead th:nth-child(3)   { 
             position: sticky; 
             left: 176px; 
             width: 110px !important;
             background-color: #fff; 
             outline: 2px solid #dee2e6;
             outline-offset: -1px;
     }

     .grade_view_table thead th:nth-child(4)   { 
             position: sticky; left: 291px; 
             width: 40px !important;
             background-color: #fff; 
             outline: 2px solid #dee2e6;
             outline-offset: -1px;
     }

     /* .grade_view_table tbody th:nth-child(5)   {  
         position: sticky; 
         left: 269px; 
         background-color: #fff; 
         width: 80px !important;
         background-color: #fff; 
         outline: 2px solid #dee2e6;
         outline-offset: -1px;
     }

     .grade_view_table thead th:nth-child(5)   { 
             position: sticky; left: 269px; 
             width: 80px !important;
             background-color: #fff; 
             outline: 2px solid #dee2e6;
             outline-offset: -1px;
     } */

     .comp   {  
            height: 29px;
            position: sticky; 
            top: 0px !important; 
            background-color: #fff; 
            background-color: #fff; 
            outline: 2px solid #dee2e6;
            outline-offset: -1px;
     }

     .comp_1   {  
            height: 29px;
            position: sticky; 
            top: 30px !important; 
            background-color: #fff; 
            background-color: #fff; 
            outline: 2px solid #dee2e6;
            outline-offset: -1px;
     }

     .comp_2   {  
            height: 29px;
            position: sticky; 
            top: 59px !important; 
            background-color: #fff; 
            background-color: #fff; 
            outline: 2px solid #dee2e6;
            outline-offset: -1px;
     }

     .comp_3   {  
            height: 59px;
            position: sticky; 
            top: 30px !important; 
            background-color: #fff; 
            background-color: #fff; 
            outline: 2px solid #dee2e6;
            outline-offset: -1px;
     }

     .toast-top-right {
         top: 20%;
         margin-right: 21px;
     }

     .tableFixHead {
         overflow: auto;
         height: 100px;
     }

     .tableFixHead thead th {
         position: sticky;
         top: 0;
         background-color: #fff;
         outline: 2px solid #dee2e6;
         outline-offset: -1px;
        
     }

     .isHPS {

         position: sticky;
         top: 59px !important;
         background-color: #fff;
         outline: 2px solid #dee2e6 ;
         outline-offset: -1px;
        
     }

     .ecr-date {
         width:80px;
         top: 80px;
         left: 12px;
         position: absolute;
         transform-origin: 0 0;
         transform: rotate(-90deg);
     }

</style>

@if(count($header) == 0)
      <table class="table table-sm table-bordered">
            <tr>
                  <td>No Grades Found</td>
            </tr>
      </table>

@else

      <div class=" table-responsive tableFixHead mt-1" style="height: 600px; font-size:12px !important" >
            <table class="table table-sm table-bordered grade_view_table">
                  <thead>
                        <tr>
                              <th colspan="4" rowspan="2" class="text-center  align-middle " style="min-width:332px  !important; z-index:100 !important">STUDENT'S PROFILE</th>
                              @if($term == 'CR MIDTERM SUMMARY')
                                    <td colspan="7" class="text-center comp">PRELIM</td> 
                                    <td colspan="7" class="text-center comp">PRE-MIDTERM</td> 
                                    <td colspan="7" class="text-center comp">MIDTERM</td> 
                                    <td colspan="2" class="text-center comp">TERM GRADE</td> 
                                    <th class="text-center comp" style="z-index: 100 !important"></th> 
                                    <th class="text-center comp" style="z-index: 100 !important"></th> 
                              @else
                                    <td colspan="7" class="text-center comp">SEMI-FINAL</td> 
                                    <td colspan="7" class="text-center comp">PRE-FINAL</td> 
                                    <td colspan="7" class="text-center comp">FINAL</td> 
                                    <td colspan="2" class="text-center comp">TERM GRADE</td> 
                                    <th class="text-center comp" style="z-index: 100 !important"></th> 
                                    <th class="text-center comp" style="z-index: 100 !important"></th> 
                              @endif
                        </tr>
                        <tr>
                              <td colspan="2" class="text-center comp_1">OR</td>
                              <td colspan="2" class="text-center comp_1">PT</td>
                              <td class="text-center comp_3" rowspan="2">E</td>
                              <td class="text-center comp_3"  rowspan="2">G</td>
                              <td class="text-center comp_3"  rowspan="2">30%</td>

                              <td colspan="2" class="text-center comp_1">OR</td>
                              <td colspan="2" class="text-center comp_1">PT</td>
                              <td class="text-center comp_3"  rowspan="2">E</td>
                              <td class="text-center comp_3"  rowspan="2">G</td>
                              <td class="text-center comp_3"  rowspan="2">30%</td>

                              <td colspan="2" class="text-center comp_1">OR</td>
                              <td colspan="2" class="text-center comp_1">PT</td>
                              <td class="text-center comp_3"  rowspan="2">E</td>
                              <td class="text-center comp_3"  rowspan="2">G</td>
                              <td class="text-center comp_3"  rowspan="2">40%</td>

                              <td class="text-center comp_3"  rowspan="2">SUM</td>
                              <td class="text-center comp_3"  rowspan="2">20%</td>
                              <th class="text-center comp_3"  rowspan="2" style="z-index: 100 !important">FG</th>
                              <th class="text-center comp_3"  rowspan="2" style="z-index: 100 !important">REMARKS</th>
                        </tr>
                        <tr>
                              <th class="comp_2" style="z-index: 100 !important">No.</th>
                              <th class="comp_2" style="z-index: 100 !important">LAST NAME</th>
                              <th class="comp_2" style="z-index: 100 !important">FIRST NAME</th>
                              <th class="comp_2" style="z-index: 100 !important">MI</td>
                              <td class="comp_2">F</td>
                              <td class="comp_2">S</td>
                              <td class="comp_2">UR</td>
                              <td class="comp_2">TR</td>
                              <td class="comp_2">F</td>
                              <td class="comp_2">S</td>
                              <td class="comp_2">UR</td>
                              <td class="comp_2">TR</td>
                              <td class="comp_2">F</td>
                              <td class="comp_2">S</td>
                              <td class="comp_2"UR</td>
                              <td class="comp_2">TR</td>
                        </tr>
                        @foreach($grade_sum as $key=>$item)
                              <tr>
                                    <th>{{$key+1}}</th>
                                    <th>{{$item->lastname}}</th>
                                    <th>{{$item->firstname}}</th>
                                    <th>{{$item->middlename}}</th>
                                    @if($term == 'CR MIDTERM SUMMARY')
                                          @php
                                                $termgrade = collect($gradedetail)
                                                                  ->where('term','PRELIM')
                                                                  ->where('studid',$item->studid)
                                                                  ->first();
                                          @endphp
                                          <td class="text-center align-middle">{{number_format($termgrade->fave)}}</td>
                                          <td  class="text-center align-middle">{{number_format($termgrade->save)}}</td>
                                          <td  class="text-center align-middle">{{number_format($termgrade->urave)}}</td>
                                          <td  class="text-center align-middle">{{number_format($termgrade->trave)}}</td>
                                          <td  class="text-center align-middle">{{number_format($termgrade->ptgenave)}}</td>
                                          <td  class="text-center align-middle"><b>{{number_format($termgrade->examgenave)}}</b></td>
                                          <td  class="text-center align-middle"><b>{{$item->prelim}}</b></td>

                                          @php
                                                $termgrade = collect($gradedetail)
                                                                  ->where('term','PRE-MIDTERM')
                                                                  ->where('studid',$item->studid)
                                                                  ->first();
                                          @endphp
                                          <td  class="text-center align-middle">{{number_format($termgrade->fave)}}</td>
                                          <td  class="text-center align-middle">{{number_format($termgrade->save)}}</td>
                                          <td  class="text-center align-middle">{{number_format($termgrade->urave)}}</td>
                                          <td  class="text-center align-middle">{{number_format($termgrade->trave)}}</td>
                                          <td  class="text-center align-middle">{{number_format($termgrade->ptgenave)}}</td>
                                          <td  class="text-center align-middle"><b>{{number_format($termgrade->examgenave)}}</b></td>
                                          <td  class="text-center align-middle"><b>{{$item->pre_midterm}}</b></td>

                                          @php
                                                $termgrade = collect($gradedetail)
                                                                  ->where('term','MIDTERM')
                                                                  ->where('studid',$item->studid)
                                                                  ->first();
                                          @endphp
                                          <td class="text-center align-middle">{{number_format($termgrade->fave)}}</td>
                                          <td class="text-center align-middle">{{number_format($termgrade->save)}}</td>
                                          <td class="text-center align-middle">{{number_format($termgrade->urave)}}</td>
                                          <td class="text-center align-middle">{{number_format($termgrade->trave)}}</td>
                                          <td class="text-center align-middle">{{number_format($termgrade->ptgenave)}}</td>
                                          <td class="text-center align-middle"><b>{{number_format($termgrade->examgenave)}}</b></td>
                                          <td class="text-center align-middle"><b>{{$item->midterm}}</b></td>

                                          <td class="text-center align-middle">{{$item->mid_term_sum}}</td>
                                          <td class="text-center align-middle">{{number_format($item->mid_term_per,2)}}</td>
                                          <th  class="text-center align-middle">{{number_format($item->mid_term_fg,1)}}</th>
                                          <th class="text-center align-middle">{{$item->mid_term_remarks}}</th>
                                 
                                    @else
                                          @php
                                                $termgrade = collect($gradedetail)
                                                                  ->where('term','SEMI-FINAL')
                                                                  ->where('studid',$item->studid)
                                                                  ->first();
                                          @endphp
                                          <td class="text-center align-middle">{{number_format($termgrade->fave)}}</td>
                                          <td class="text-center align-middle">{{number_format($termgrade->save)}}</td>
                                          <td class="text-center align-middle">{{number_format($termgrade->urave)}}</td>
                                          <td class="text-center align-middle">{{number_format($termgrade->trave)}}</td>
                                          <td class="text-center align-middle">{{number_format($termgrade->ptgenave)}}</td>
                                          <td class="text-center align-middle"><b>{{number_format($termgrade->examgenave)}}</b></td>
                                          <td class="text-center align-middle"><b>{{$item->semi_final}}</b></td>

                                          @php
                                                $termgrade = collect($gradedetail)
                                                                  ->where('term','PRE-FINAL')
                                                                  ->where('studid',$item->studid)
                                                                  ->first();
                                          @endphp
                                          <td class="text-center align-middle">{{number_format($termgrade->fave)}}</td>
                                          <td class="text-center align-middle">{{number_format($termgrade->save)}}</td>
                                          <td class="text-center align-middle">{{number_format($termgrade->urave)}}</td>
                                          <td class="text-center align-middle">{{number_format($termgrade->trave)}}</td>
                                          <td class="text-center align-middle">{{number_format($termgrade->ptgenave)}}</td>
                                          <td class="text-center align-middle"><b>{{number_format($termgrade->examgenave)}}</b></td>
                                          <td class="text-center align-middle"><b>{{$item->pre_final}}</b></td>

                                          @php
                                                $termgrade = collect($gradedetail)
                                                                  ->where('term','FINAL')
                                                                  ->where('studid',$item->studid)
                                                                  ->first();
                                          @endphp
                                          <td class="text-center align-middle">{{number_format($termgrade->fave)}}</td>
                                          <td class="text-center align-middle">{{number_format($termgrade->save)}}</td>
                                          <td class="text-center align-middle">{{number_format($termgrade->urave)}}</td>
                                          <td class="text-center align-middle">{{number_format($termgrade->trave)}}</td>
                                          <td class="text-center align-middle">{{number_format($termgrade->ptgenave)}}</td>
                                          <td class="text-center align-middle"><b>{{number_format($termgrade->examgenave)}}</b></td>
                                          <td class="text-center align-middle"><b>{{$item->final}}</b></td>

                                          <td class="text-center align-middle">{{$item->final_term_sum}}</td>
                                          <td class="text-center align-middle">{{number_format($item->final_term_per,2)}}</td>
                                          <th  class="text-center align-middle">{{number_format($item->final_term_fg,1)}}</th>
                                          <th class="text-center align-middle">{{$item->final_term_remarks}}</th>
                                    @endif
                              </tr>
                        @endforeach
                  </thead>
            </table>
      </div>
      <script>

            var utype = @json(Session::get('currentPortal'));

            var temp_headerid = @json($header);
            $('#ecr_submit').attr('data-id',temp_headerid[0].id)
            $('.status_button').attr('data-id',temp_headerid[0].id)

            $('#label_status').text(temp_headerid[0].statustext)
            $('#label_datesubmitted').text(temp_headerid[0].statusdate)
            $('#label_dateuploaded').text(temp_headerid[0].uploaddate)

            if(utype == 18){
                  if(temp_headerid[0].status == null || temp_headerid[0].status == 3 || temp_headerid[0].status == 0){
                        $('#ecr_submit').removeAttr('disabled','disabled')
                  }else{
                        $('#ecr_submit').attr('disabled','disabled')
                  }
            }
@endif