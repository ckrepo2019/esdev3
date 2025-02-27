<style>
         .table thead th:last-child  { 
            position: sticky; 
            right: 0; 
            background-color: #fff; 
            outline: 2px solid #dee2e6;
            outline-offset: -1px;
        }

        .table tbody th:last-child  { 
            position: sticky; 
            right: 0; 
            background-color: #fff; 
            outline: 2px solid #dee2e6;
            outline-offset: -1px;
            }

        .table tbody th:first-child  {  
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

</style>

<div class=" table-responsive tableFixHead mt-1" style="height: 400px; font-size:11px !important" >
      <table  class="table table-bordered mb-0 table-sm" disabled>
            <thead>
                  <tr style="font-size:12px">
                        <th style="min-width:210px !important; z-index:100;background-color:#fff">&nbsp;</th>
                        <th colspan="9" class="text-center">
                              Written Works <span class="text-danger">({{$percentage[0]->ww}}%)</span>
                        </th>
                        <th colspan="5" class="text-center align-middle">
                              Performance Task <span class="text-danger">({{$percentage[0]->pt}}%)</span>
                        </th>
                        <th colspan="4" class="text-center  align-middle" >
                              Quarterly ASSMNT<span class="text-danger">({{$percentage[0]->qa}}%)</span>
                        </th>
                        <th style="z-index:100;background-color:#fff"> </th>
                  </tr>
                  <tr>
                        <th style="top: 31px; z-index: 100 !important " class="text-center align-middle">STUDENT</th>
                        @for($x = 0 ; $x < 7; $x++)
                              <th style="top: 31px; min-width:40px;" class="text-center align-middle">{{$x+1}}</th>
                        @endfor
                        <th style="top: 31px;" class="text-center align-middle">TOTAL</th>
                        <th style="top: 31px;" class="text-center align-middle">WS</th>
                        @for($x = 0 ; $x < 3; $x++)
                              <th style="top: 31px; min-width:40px;" class="text-center align-middle">{{$x+1}}</th>
                        @endfor
                        <th style="top: 31px;" class="text-center align-middle">TOTAL</th>
                        <th style="top: 31px;" class="text-center align-middle">WS</th>
                        @if($percentage[0]->qa != null || $percentage[0]->qa != 0)
                              <th style="top: 31px; min-width:40px;" class="text-center align-middle">1</th>
                              <th style="top: 31px; min-width:40px;" class="text-center align-middle">2</th>
                              <th style="top: 31px;" class="text-center align-middle">TOTAL</th>
                              <th style="top: 31px;" class="text-center align-middle">WS</th>
                        @endif
                        <th style="min-width:60px; top: 31px;z-index:100" class="text-center align-middle">FG</th>
                  </tr>
            </thead>
            <tbody>
                  @if(count($grades) > 0)
                        @php
                              $male = 0;
                              $female = 0;
                        @endphp
                        @foreach($grades as $grade)
                              @if($male == 0 && $grade->gender == 'MALE')
                                    <tr class="bg-secondary">
                                          <th class="text-dark bg-secondary">MALE</th>
                                          @for($x = 0 ; $x < 9; $x++)
                                                <th class="text-dark bg-secondary"></th>
                                          @endfor
                                          @for($x = 0 ; $x < 5; $x++)
                                                <th class="text-dark bg-secondary"></th>
                                          @endfor
                                          @if($percentage[0]->qa != null || $percentage[0]->qa != 0)
                                                <th class="text-dark bg-secondary" colspan="4"></th>
                                          @endif
                                          <th class="text-center align-middle bg-secondary" id="fg" style="z-index: 1;"></th>
                                    </tr>
                                    @php
                                          $male = 1;
                                    @endphp
                              @elseif($female == 0  && $grade->gender == 'FEMALE')
                                    <tr class="bg-secondary">
                                          <th class="text-dark bg-secondary">FEMALE</th>
                                          @for($x = 0 ; $x < 9; $x++)
                                                <th class="text-dark bg-secondary"></th>
                                          @endfor
                                          @for($x = 0 ; $x < 5; $x++)
                                                <th class="text-dark bg-secondary"></th>
                                          @endfor
                                          @if($percentage[0]->qa != null || $percentage[0]->qa != 0)
                                                <th class="text-dark bg-secondary" colspan="4"></th>
                                          @endif
                                          <th class="text-center align-middle bg-secondary" id="fg" style="z-index: 1;"></th>
                                    </tr>
                                    @php
                                          $female = 1;
                                    @endphp
                              @endif
                              

                              <tr data-value="{{$grade->id}}" class="gradedetail color-palette-set" >
                                    <th>
                                          {{$grade->student}}
                                    </th>
                                    @for($x = 1 ; $x < 8; $x++)
                                          @php
                                                $wsstring = 'ww'.$x;  
                                          @endphp
                                          <td class="text-center align-middle">{{$grade->$wsstring}}</td>
                                    @endfor
                                    <th class="text-center align-middle bg-lightblue color-palette"  style="background-color: #b4c6e7;">{{$grade->wwtotal}}</th>
                                    <th class="text-center align-middle" style="background-color: #f8cbad;">{{number_format($grade->wwws,2)}}</th>
                                    @for($x = 1 ; $x < 4; $x++)
                                          @php
                                                $ptstring = 'pt'.$x;  
                                          @endphp
                                          <td class="text-center align-middle">{{$grade->$ptstring}}</td>
                                    @endfor
                                    <th class="text-center align-middle" style="background-color: #b4c6e7;">{{$grade->pttotal}}</th>
                                    <th class="text-center align-middle" style="background-color: #f8cbad; ">{{number_format($grade->ptws,2)}}</th>
                                    @if($percentage[0]->qa != null || $percentage[0]->qa != 0)
                                          <td class="text-center align-middle">{{$grade->qa1}}</td>
                                          <td class="text-center align-middle">{{$grade->qa2}}</td>
                                          <td class="text-center align-middle" style="background-color: #b4c6e7;">{{$grade->qatotal}}</td>
                                          <th class="text-center align-middle" style="background-color: #f8cbad;">{{number_format($grade->qaws,2)}}</th>
                                    @endif

                                    <th  class="text-center align-middle" style="background-color: #aad08e;">{{$grade->qg}}</th>
                                   
                              </tr>
                        @endforeach
                  @else
                        <tr>
                              <th></th>
                              <td colspan="29"><i class="text-danger">No file upload. Please try uploading a file.</i></td>
                        </tr>

                  @endif
            </tbody>
      </table>
</div>

<script>
      $(document).ready(function(){

            var hps = @json($hps)[0];

            $('#label_dateuploaded').text(hps.uploadeddatetime)
            
           
            $('#approve_grade').attr('disabled','disabled')
            $('#post_grade').attr('disabled','disabled')
            $('#pending_grade').attr('disabled','disabled')
            $('#unpost_grade').attr('disabled','disabled')

            if(hps.submitted == 0){
                  $('#label_status').text('Not submitted')
                  $('#input_ecr').removeAttr('disabled')
                  $('#upload_ecr_button').removeAttr('disabled')
                  if(hps.status == 3){
                        $('#label_status, #ecr_status').text('Pending')
                  }
            }else{
                  $('#input_ecr').attr('disabled','disabled')
                  $('#upload_ecr_button').attr('disabled','disabled')
                  if(hps.status == 1 || hps.status == 0){
                        $('#label_status, #ecr_status').text('Submitted')
                        $('#label_status').text('Submitted')
                        $('#approve_grade').removeAttr('disabled')
                        $('#pending_grade').removeAttr('disabled')
                        $('#post_grade').removeAttr('disabled')
                  }else if(hps.status == 4){
                        $('#label_status, #ecr_status').text('Posted')
                        $('#unpost_grade').removeAttr('disabled')
                  }else if(hps.status == 2){
                        $('#label_status, #ecr_status').text('Approved')
                        $('#pending_grade').removeAttr('disabled')
                        $('#post_grade').removeAttr('disabled')
                  }
                  $('#label_datesubmitted').text(hps.date_submitted)
            }

            var grades = @json($grades)

            const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                  })

            if(grades.length == 0){
                  Toast.fire({
                        type: 'warning',
                        title: 'No records found.'
                  })
                  $('#ecr_submit').attr('disabled','disabled')
            }else{
                  Toast.fire({
                        type: 'warning',
                        title: 'Grades found.'
                  })
                  if(hps.submitted == 0){
                        try{
                              $('#ecr_submit').removeAttr('disabled')
                              $('#ecr_submit')[0].innerHTML = '<i class="far fa-share-square"></i> Submit'
                        }catch(err){

                        }
                  }else{
                        try{
                              $('#ecr_submit').attr('disabled','disabled')
                              $('#ecr_submit')[0].innerHTML = '<i class="fas fa-check"></i> Submitted'
                        }catch(err){

                        }
                  }
            }

      })

</script>