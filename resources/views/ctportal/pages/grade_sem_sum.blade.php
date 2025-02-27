<style>
    
     .comp  {  
         position: sticky; 
         top: 0; 
         background-color: #fff; 
         width: 20px !important;
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

@if(count($grade_sum) == 0)
      <table class="table table-sm table-bordered">
            <tr>
                  <td>No Grades Found</td>
            </tr>
      </table>

@else

      <div class=" table-responsive tableFixHead mt-1" style="height: 600px; font-size:12px !important" >
            <table class="table table-sm table-bordered grade_view_table">
             
                        <tr>
                              <th class="comp">No.</th>
                              <th class="align-middle comp">LAST NAME</th>
                              <th class="align-middle comp">FIRST NAME</th>
                              <th class="align-middle comp">MI</th>
                              <th class=" text-center comp">MIDTERM</th>
                              <th class=" text-center comp">20%</th>
                              <th class=" text-center comp">FINAL</th>
                              <th class=" text-center comp">80%</th>
                              <th class=" text-center comp">FINAL GRADE</th>
                              <th class=" text-center comp">GRADE</th>
                              <th class=" text-center comp">REMARKS</th>
                        </tr>
                  @foreach($grade_sum as $key=>$item)
                        <tr>
                              <th>{{$key+1}}</th>
                              <th class="align-middle">{{$item->lastname}}</th>
                              <th class="align-middle">{{$item->firstname}}</th>
                              <th class="align-middle">{{$item->middlename}}</th>
                              <th class="align-middle text-center">{{$item->mid_term_sum}}</th>
                              <th class="align-middle text-center">{{$item->mid_term_per}}</th>
                              <th class="align-middle text-center">{{$item->final_term_sum}}</th>
                              <th class="align-middle text-center">{{$item->final_term_per}}</th>

                              <th class="align-middle text-center">{{$item->grade}}</th>
                              <th class="align-middle text-center">{{$item->grade_dec}}</th>
                              <th class="align-middle text-center">{{$item->graderemarks}}</th>
                        </tr>
                  @endforeach
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