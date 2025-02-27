<style>
      .grade_view_table thead th:last-child  { 
         position: sticky; 
         right: 0; 
         background-color: #fff; 
         outline: 2px solid #dee2e6;
         outline-offset: -1px;
     }

     .grade_view_table tbody th:last-child  { 
         position: sticky; 
         right: 0; 
         background-color: #fff; 
         outline: 2px solid #dee2e6;
         outline-offset: -1px;
         }

     .grade_view_table tbody th:first-child  {  
         position: sticky; 
         left: 0; 
         background-color: #fff; 
         width: 20px !important;
         background-color: #fff; 
         outline: 2px solid #dee2e6;
         outline-offset: -1px;
     }

     .grade_view_table thead th:first-child  { 
             position: sticky; left: 0; 
             width: 20px !important;
             background-color: #fff; 
             outline: 2px solid #dee2e6;
             outline-offset: -1px;
     }

     .grade_view_table tbody th:nth-child(2)   {  
         position: sticky; 
         left: 29px; 
         background-color: #fff; 
         width: 119px !important;
         background-color: #fff; 
         outline: 2px solid #dee2e6;
         outline-offset: -1px;
     }

     .grade_view_table thead th:nth-child(2)   { 
             position: sticky; left: 29px; 
             width: 119px !important;
             background-color: #fff; 
             outline: 2px solid #dee2e6;
             outline-offset: -1px;
     }

     .grade_view_table tbody th:nth-child(3)   {  
         position: sticky; 
         left: 139px; 
         background-color: #fff; 
         width: 110px !important;
         background-color: #fff; 
         outline: 2px solid #dee2e6;
         outline-offset: -1px;
     }

     .grade_view_table thead th:nth-child(3)   { 
             position: sticky; left: 139px; 
             width: 110px !important;
             background-color: #fff; 
             outline: 2px solid #dee2e6;
             outline-offset: -1px;
     }

     .grade_view_table tbody th:nth-child(4)   {  
         position: sticky; 
         left: 239px; 
         background-color: #fff; 
         width: 40px !important;
         background-color: #fff; 
         outline: 2px solid #dee2e6;
         outline-offset: -1px;
     }

     .grade_view_table thead th:nth-child(4)   { 
             position: sticky; left: 239px; 
             width: 40px !important;
             background-color: #fff; 
             outline: 2px solid #dee2e6;
             outline-offset: -1px;
     }

     .grade_view_table tbody th:nth-child(5)   {  
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
     }

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
         height: 47px;
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
         top: 77px !important; 
         background-color: #fff; 
         background-color: #fff; 
         outline: 2px solid #dee2e6;
         outline-offset: -1px;
     }

     .comp_3   {  
         height: 76px;
         position: sticky; 
         top: 30px !important; 
         background-color: #fff; 
         background-color: #fff; 
         outline: 2px solid #dee2e6;
         outline-offset: -1px;
     }

     .comp_4   {  
         height: 105px;
         position: sticky; 
         top: 30px !important; 
         background-color: #fff; 
         background-color: #fff; 
         outline: 2px solid #dee2e6;
         outline-offset: -1px;
     }
     
     .comp_6   {  
         height: 105px;
         position: sticky; 
         top: 0 !important; 
         background-color: #fff; 
         background-color: #fff; 
         outline: 2px solid #dee2e6;
         outline-offset: -1px;
     }
     
     .comp_7   {  
         height: 29px;
         position: sticky; 
         top: 107px !important; 
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

      <div class=" table-responsive tableFixHead mt-1 " style="height: 600px; font-size:12px !important" >
            <table class="table table-sm table-bordered table-striped grade_view_table">
                  <thead>
                        <tr>
                              <th colspan="4" rowspan="3" class="text-center  align-middle " style="min-width:315px !important; z-index:100;background-color:#fff">STUDENT'S PROFILE</th>
                              <td rowspan="3"   class="text-center  align-middle comp_6" ></td>
                              <td colspan="18" class="text-center comp">OTHER REQUIREMENTS (20%)</td> 
                              <td colspan="12" class="text-center comp">PERMORMANCE TASKS (40%)</td> 
                              <td colspan="4" class="text-center  comp">EXAMINATIONS (40%)</td> 
                              <th class="text-center align-middle" rowspan="3"  style=" z-index:100;background-color:#fff">TOTAL<br>AVE</th> 
                        </tr>
                        <tr>
                              <td colspan="6" class="text-center comp_1">ACTIVITIES (FORMATIVE)</td>
                              <td rowspan="2" class="text-center comp_3">T</td>
                              <td rowspan="3" class="text-center comp_4">A</td>
                              <td colspan="6" class="text-center comp_1">ASSESSMENT (SUMMATIVE)</td>
                              <td rowspan="2" class="text-center comp_3">T</td>
                              <td rowspan="3" class="text-center comp_4">A</td>
                              <td rowspan="3" class="text-center comp_4">G.E.</td>
                              <td rowspan="3" class="text-center comp_4">%</td>

                              <td colspan="3" class="text-center comp_1">UNIT REQUIREMENTS</td>
                              <td rowspan="2" class="text-center comp_3">T</td>
                              <td rowspan="3" class="text-center comp_4">A</td>
                              <td colspan="3" class="text-center comp_1">TERMINAL REQUIREMENTS</td>
                              <td rowspan="2" class="text-center comp_3">T</td>
                              <td rowspan="3" class="text-center comp_4">A</td>
                              <td rowspan="3" class="text-center comp_4">G.E.</td>
                              <td rowspan="3" class="text-center comp_4">%</td>


                              <td rowspan="2" class="text-center comp_3">PRELIM</td>
                              <td rowspan="2" class="text-center comp_3">T</td>
                              <td rowspan="3" class="text-center comp_4">G.E.</td>
                              <td rowspan="3" class="text-center comp_4">%</td>
                             


                        </tr>
                        <tr>
                              <td class="text-center comp_2">F1</td>
                              <td class="text-center comp_2">F2</td>
                              <td class="text-center comp_2">F3</td>
                              <td class="text-center comp_2">F4</td>
                              <td class="text-center comp_2">F5</td>
                              <td class="text-center comp_2">F6</td>

                              <td class="text-center comp_2">S1</td>
                              <td class="text-center comp_2">S2</td>
                              <td class="text-center comp_2">S3</td>
                              <td class="text-center comp_2">S4</td>
                              <td class="text-center comp_2">S5</td>
                              <td class="text-center comp_2">S6</td>

                              <td class="text-center comp_2">UR1</td>
                              <td class="text-center comp_2">UR2</td>
                              <td class="text-center comp_2">UR3</td>
                        

                              <td class="text-center comp_2">TR1</td>
                              <td class="text-center comp_2">TR2</td>
                              <td class="text-center comp_2">TR3</td>
                              

                        </tr>
                        <tr>
                              <th style="z-index: 100 !important" class="comp_7">No.</th>
                              <th style="z-index: 100 !important" class="align-middle comp_7">LAST NAME</th>
                              <th style="z-index: 100 !important" class="align-middle comp_7">FIRST NAME</th>
                              <th style="z-index: 100 !important" class="align-middle comp_7">MI</th>
                              <td  class="comp_7"><b>MODALITY</b></td>
                              
                        
                              <td class="comp_7 text-center">{{$header[0]->f1}}</td>
                              <td class="comp_7 text-center">{{$header[0]->f2}}</td>
                              <td class="comp_7 text-center">{{$header[0]->f3}}</td>
                              <td class="comp_7 text-center">{{$header[0]->f4}}</td>
                              <td class="comp_7 text-center">{{$header[0]->f5}}</td>
                              <td class="comp_7 text-center">{{$header[0]->f6}}</td>
                              <td class="comp_7 text-center"><b>{{$header[0]->ftotal}}</b></td>


                              <td class="comp_7 text-center">{{$header[0]->s1}}</td>
                              <td class="comp_7 text-center">{{$header[0]->s2}}</td>
                              <td class="comp_7 text-center">{{$header[0]->s3}}</td>
                              <td class="comp_7 text-center">{{$header[0]->s4}}</td>
                              <td class="comp_7 text-center">{{$header[0]->s5}}</td>
                              <td class="comp_7 text-center">{{$header[0]->s6}}</td>
                              <td class="comp_7 text-center"><b>{{$header[0]->stotal}}</b></td>

                              <td class="comp_7 text-center">{{$header[0]->ur1}}</td>
                              <td class="comp_7 text-center">{{$header[0]->ur2}}</td>
                              <td class="comp_7 text-center">{{$header[0]->ur3}}</td>
                              <td class="comp_7 text-center"><b>{{$header[0]->urtotal}}</b></td>

                              <td class="comp_7 text-center">{{$header[0]->tr1}}</td>
                              <td class="comp_7 text-center">{{$header[0]->tr2}}</td>
                              <td class="comp_7 text-center">{{$header[0]->tr3}}</td>
                              <td class="comp_7 text-center"><b>{{$header[0]->trtotal}}</b></td>

                              <td class="comp_7 text-center">{{$header[0]->exam1}}</td>
                              <td class="comp_7 text-center"><b>{{$header[0]->examtotal}}</b></td>
                              <th class="comp_7 text-center" style="z-index: 100 !important"></th>
                        </tr>
                        @foreach ($gradedetail as $key=>$item)
                        <tr>
                              <th>{{$key+1}}</th>
                              <th class="align-middle">{{$item->lastname}}</th>
                              <th class="align-middle">{{$item->firstname}}</th>
                              <th class="align-middle">{{$item->middlename}}</th>
                              <td></td>
                              
                        
                              <td class="align-middle  text-center">{{$item->f1}}</td>
                              <td class="align-middle  text-center">{{$item->f2}}</td>
                              <td class="align-middle  text-center">{{$item->f3}}</td>
                              <td class="align-middle text-center">{{$item->f4}}</td>
                              <td class="align-middle text-center">{{$item->f5}}</td>
                              <td class="align-middle text-center">{{$item->f6}}</td>
                              <td class="align-middle text-center"><b>{{$item->ftotal}}</b></td>
                              <td class="align-middle text-center"><b>{{$item->fave}}</b></td>

                              <td class="align-middle text-center">{{$item->s1}}</td>
                              <td class="align-middle text-center">{{$item->s2}}</td>
                              <td class="align-middle text-center">{{$item->s3}}</td>
                              <td class="align-middle text-center">{{$item->s4}}</td>
                              <td class="align-middle text-center">{{$item->s5}}</td>
                              <td class="align-middle text-center">{{$item->s6}}</td>
                              <td class="align-middle text-center"><b>{{$item->stotal}}</b></td>
                              <td class="align-middle text-center"><b>{{$item->save}}</b></td>
                              <td class="align-middle text-center"><b>{{$item->orgenave}}</b></td>
                              <td class="align-middle text-center"><b>{{$item->orpercentage}}</b></td>

                              <td class="align-middle text-center">{{$item->ur1}}</td>
                              <td class="align-middle text-center">{{$item->ur2}}</td>
                              <td class="align-middle text-center">{{$item->ur3}}</td>
                              <td class="align-middle text-center"><b>{{$item->urtotal}}</b></td>
                              <td class="align-middle text-center"><b>{{$item->urave}}</b></td>


                              <td class="align-middle text-center">{{$item->tr1}}</td>
                              <td class="align-middle text-center">{{$item->tr2}}</td>
                              <td class="align-middle text-center">{{$item->tr3}}</td>
                              <td class="align-middle text-center"><b>{{$item->trtotal}}</b></td>
                              <td class="align-middle text-center"><b>{{$item->trave}}</b></td>
                              <td class="align-middle text-center"><b>{{$item->ptgenave}}</b></td>
                              <td class="align-middle text-center"><b>{{$item->ptpercentage}}</b></td>

                              <td class="align-middle text-center"><b>{{$item->prelim}}</b></td>
                              <td class="align-middle text-center"><b>{{$item->prelimtotal}}</b></td>
                              <td class="align-middle text-center"><b>{{$item->examgenave}}</b></td>
                              <td class="align-middle text-center"><b>{{$item->exampercentage}}</b></td>

                              <th class="align-middle">{{$item->totalave}}</th>
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
        </script>

@endif