<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title></title>
        <style>

            .table {
                width: 100%;
                margin-bottom: 1rem;
                background-color: transparent;
                font-size:11px ;
            }

            table {
                border-collapse: collapse;
            }
            
            .table thead th {
                vertical-align: bottom;
            }
            
            .table td, .table th {
                padding: .75rem;
                vertical-align: top;
            }
            .table td, .table th {
                padding: .75rem;
                vertical-align: top;
            }
            
            .table-bordered {
                border: 1px solid #00000;
            }

            .table-bordered td, .table-bordered th {
                border: 1px solid #00000;
            }

            .table-sm td, .table-sm th {
                padding: .3rem;
            }

            .text-center{
                text-align: center !important;
            }
            
            .text-right{
                text-align: right !important;
            }
            
            .text-left{
                text-align: left !important;
            }
            
            .p-0{
                padding: 0 !important;
            }

            .p-1{
                padding: .25rem !important;
            }

            .pl-1{
                padding-left: .25rem !important;
            }



            .mb-0{
                margin-bottom: 0;
            }

            .border-bottom{
                border-bottom:1px solid black;
            }

            .mb-1, .my-1 {
                margin-bottom: .25rem!important;
            }

            body{
                font-family: Calibri, sans-serif;
            }
            
            .align-middle{
                vertical-align: middle !important;    
            }

            
            .small-text td{
                padding-top: .1rem;
                padding-bottom: .1rem;
                font-size: .55rem !important;
            }

            .studentinfo td{
                padding-top: .1rem;
                padding-bottom: .1rem;
            
            }

            .text-red{
                color: red;
                border: solid 1px black;
            }


            
            .page_break { page-break-before: always; }
            @page { size: 8.5in 11in; margin: .25in;  }
            
        </style>
    </head>
    <body>
      <table class="table mb-0 table-sm header" style="font-size:13px;">
            <tr>
                <td width="20%" rowspan="2" class="text-right align-middle p-0">
                    <img src="{{base_path()}}/public/{{$schoolinfo->picurl}}" alt="school" width="70px">
                </td>
                <td width="60%" class="p-0 text-center" >
                    <h3 class="mb-0" style="font-size:18px !important">{{$schoolinfo->schoolname}}</h3>
                </td>
                <td width="20%" rowspan="2" class="text-right align-middle p-0">
                
                </td>
            </tr>
            <tr>
                <td class="p-0 text-center">
                    {{$schoolinfo->address}}
                </td>
            </tr>
           
        </table>

        <table class="table mb-0 table-sm header" style="font-size:13px;">
            <tr>
                  <td class="p-0 text-center" style="font-size:.9rem !important;">
                      <b>{{$courseinfo->courseDesc}}</b>
                  </td>
            </tr>
            <tr>
                  <td class="p-0 text-center">
                      {{$curriculuminfo->curriculumname}}
                  </td>
            </tr>
        </table>
        <br>
        
            @foreach($yearlevel as $syitem)
                  @foreach($semester as $semitem)
                              @php
                                    $temp_subjects = collect($subjects[0]['subjects'])->where('semesterID',$semitem->id)->where('yearID',$syitem->id)->values();
                                    $totalunits = 0;

                                    $yearlevel = '';
                                    $semestername = '';

                                    if($semitem->id == 1){
                                          $semestername = '1st Semester';
                                    }else if($semitem->id == 2){
                                          $semestername = '2nd Semester';
                                    }else if($semitem->id == 3){
                                          $semestername = 'Summer';
                                    }
                                    
                                    if($syitem->id == 17){
                                          $yearlevel = '1st Year College';
                                    }else if($syitem->id == 18){
                                          $yearlevel = '2nd Year College';
                                    }else if($syitem->id == 19){
                                          $yearlevel = '3rd Year College';
                                    }else if($syitem->id == 20){
                                          $yearlevel = '4th Year College';
                                    }else if($syitem->id == 21){
                                          $yearlevel = '5th Year College';
                                    }

                              @endphp

                        @if(count($temp_subjects) > 0)
                        
                              <table class="table mb-0 table-sm table-bordered" style="font-size:13px;">
                                    <tr>
                                          <td colspan="6">{{$yearlevel}} - {{$semestername}}</td>
                                    </tr>
                                    <tr>
                                          <td width="15%" class="p-0 align-middle pl-1" rowspan="2"><b>Code</b></td>
                                          <td width="64%" class="p-0 align-middle pl-1" rowspan="2"><b>Subject Description</b></td>
                                          <td width="20%" class="p-0 align-middle pl-1" rowspan="2"><b>Prerequisite</b></td>
                                          <td width="21%" class="p-0 text-center" colspan="3"><b>Units</b></td>
                                    </tr>
                                    <tr>
                                          <td  class="p-0 text-center"><b>Lec.</b></td>
                                          <td  class="p-0 text-center"><b>Lab.</b></td>
                                          <td  class="p-0 text-center"><b>Total</b></td>
                                    </tr>
                                    
                                    @foreach($temp_subjects as $temp_subjects_item)
                                          <tr>
                                                <td class="p-0  pl-1">{{$temp_subjects_item->subjCode}}</td>
                                                <td class="p-0  pl-1">{{$temp_subjects_item->subjDesc}}</td>
                                                <td class="p-0  pl-1">

                                                    

                                                      @php
                                                            $temp_prereq = collect($subjects[0]['prereq'])->where('subjID',$temp_subjects_item->id)->values();
                                                            $perreq = '';

                                                            foreach ($temp_prereq as $key=>$temp_prereq_item) {
                                                                  $per_req_subjinfo = collect($subjects[0]['subjects'])->where('id',$temp_prereq_item->prereqsubjID)->first();
                                                                  if(isset($per_req_subjinfo)){
                                                                        $perreq .= $per_req_subjinfo->subjCode;
                                                                  }
                                                                  if(count($temp_prereq) - 1 != $key){
                                                                        $perreq .= ',';
                                                                  }
                                                            }
                                                            $totalunits += $temp_subjects_item->lecunits + $temp_subjects_item->labunits;
                                                      @endphp

                                                      {{ $perreq}}
                                                </td>
                                                <td class="p-0 text-center">{{$temp_subjects_item->lecunits}}</td>
                                                <td class="p-0 text-center">{{$temp_subjects_item->labunits}}</td>
                                                <td class="p-0 text-center">{{$temp_subjects_item->lecunits + $temp_subjects_item->labunits}}</td>
                                          </tr>
                                    @endforeach
                                    <tr>
                                          <td colspan="5" class="text-right"><b>Total Units</b></td>
                                          <td class="text-center">{{$totalunits}}</td>
                                    </tr>
                              </table>
                              <br>
                        @endif
                       
                  @endforeach
            @endforeach
        
        
    </body>
</html>