
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>School Form 6</title>
        <style>
    html{
        font-family: Arial, Helvetica, sans-serif;
    }
    #header{
        font-size: 10px;
        border-spacing: 0;
        text-align: right;
        width: 100%;
    }
    #header th{
        padding: 3px;
    }
    h2{
        margin:5px;
    }
    #cellCenter{
        text-align: center !important;
    }
    div.box{
        border: 1px solid black;
        padding: 5px;
        text-align: center;
    }
    .labels{
        width: 60% !important;
    }
    .left{
        float:left;
        width:70%;
    }
    .right{
        float: right;
        width: 30%;
    }
    .studentsTable{
        table-layout: fixed;
        border: 1px solid black;
        width: 100%;
        text-align: center;
        border-collapse: collapse;
        font-size: 12px;
        margin-top: 10px;
    }
    .studentsTable th,.studentsTable td{
        padding-top: 5px;
        padding-bottom: 5px;
        border: 1px solid black;
        font-size: 9.5px;
    }
    .page_break { 
            page-break-before: always; 
    }
    h5{
        margin: 0px;
    }
    .leftAlign{
        text-align: left !important;
    }
    @page{
        margin:35px;
    }
</style>
    </head>
    <body>
@php

$signatoriesv2 = DB::table('signatory')
                    ->where('form','form6')
                    ->where('syid', $sy->id)
                    ->where('deleted','0')
                    ->get();

if(count($signatoriesv2)>0)
{
    $signatoriesv2 = collect($signatoriesv2)->toArray();
    $signatoriesv2 = array_chunk($signatoriesv2, 4);
}
            $signatories = DB::table('signatory')
                ->where('form','form6')
                ->where('deleted','0')
                ->get();

            if(count($signatories)>0)
            {
                $filtersignatories = collect($signatories)
                ->where('acadprogid', 0)
                ->values();
                if(count($filtersignatories)>0)
                {
                    $signatories = $filtersignatories;
                }
            }

@endphp

<table id="header">
    <tr>
        <th rowspan="3" style="width:10%; vertical-align: top;" class="leftImg">
            <center>
                <img style="padding:0px; margin:0px;" src="{{base_path()}}/public/assets/images/department_of_Education.png" alt="school" width="70px">
                {{-- <img style="padding:0px; margin:0px;" src="{{base_path()}}/public/{{$getSchoolInfo[0]->picurl}}" alt="school" width="70px"> --}}
            </center>
        </th>
        <td colspan="7" style="padding-top:0px;">
            <div style="padding-right:1%;margin-top: 0px;padding-top:0px;">
                <center>
                    <h5 style="font-size:20px !important;">School Form 6 (SF6)<br>Summarized Report on Promotion and Learning Progress & Achievement </h5>
                    <sup>
                        <em>(This replaces Form 20)</em>
                    </sup>
                </center>
                <br>
            </div>
        </td>
        <th rowspan="2" style="width:10%; text-align: right; vertical-align: top;" class="leftImg">
            &nbsp;
            {{-- <center>
                <img style="padding:0px; margin:0px;" src="{{base_path()}}/public/assets/images/department_of_Education.png" alt="school" width="70px">
            </center> --}}
        </th>
    </tr>
    <tr>
        <th width="8%">School ID</th>
        <th><div class="box">{{$getSchoolInfo[0]->schoolid}}</div></th>
        <th>Region</th>
        <th><div class="box">{{($getSchoolInfo[0]->region == null) ? DB::table('schoolinfo')->first()->regiontext : $getSchoolInfo[0]->region}}</div></th>
        <th>Division</th>
        <th colspan="2"><div class="box">{{($getSchoolInfo[0]->division == null) ? DB::table('schoolinfo')->first()->divisiontext : $getSchoolInfo[0]->division}}</div></th>
    </tr>
    <tr>
        <th>School Name</th>
        <th colspan="3"><div class="box">{{$getSchoolInfo[0]->schoolname}}</div></th>
        <th>District</th>
        <th><div class="box">{{($getSchoolInfo[0]->district == null) ? DB::table('schoolinfo')->first()->districttext : $getSchoolInfo[0]->district}}</div></th>
        <th>School Year</th>
        <th><div class="box">{{$sy->sydesc}}</div></th>
    </tr>
</table>
<table class="studentsTable">
    <tr>
        <th rowspan="2" width="10%" height="5px">SUMMARY TABLE</th>
        <th colspan="3">GRADE 1/GRADE 7</th>
        <th colspan="3">GRADE 2/GRADE 8</th>
        <th colspan="3">GRADE 3/GRADE 9</th>
        <th colspan="3">GRADE 4/GRADE 10</th>
        <th colspan="3">GRADE 5/GRADE 11</th>
        <th colspan="3">GRADE 6/GRADE 12</th>
        <th colspan="3">TOTAL</th>
    </tr>
    <tr>
        <th>MALE</th>
        <th>FEMALE</th>
        <th>TOTAL</th>
        <th>MALE</th>
        <th>FEMALE</th>
        <th>TOTAL</th>
        <th>MALE</th>
        <th>FEMALE</th>
        <th>TOTAL</th>
        <th>MALE</th>
        <th>FEMALE</th>
        <th>TOTAL</th>
        <th>MALE</th>
        <th>FEMALE</th>
        <th>TOTAL</th>
        <th>MALE</th>
        <th>FEMALE</th>
        <th>TOTAL</th>
        <th>MALE</th>
        <th>FEMALE</th>
        <th>TOTAL</th>
    </tr>
    <tr>
        <th class="leftAlign">PROMOTED</th>
        <td>
            @php
                $grade1male = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 1){
                        $grade1male = $status->promotedmale;
                    }
                }   
                $grade7male = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 10){
                        $grade7male = $status->promotedmale;
                    }
                }  
            @endphp 
            {{$grade1male}}  /  {{$grade7male}}               
        </td>
        <td>
            @php
                $grade1female = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 1){
                        $grade1female = $status->promotedfemale;
                    }
                }   
                $grade7female = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 10){
                        $grade7female = $status->promotedfemale;
                    }
                }  
            @endphp 
            {{$grade1female}}  /  {{$grade7female}}     
        </td>
        <td>
            @php
                $grade1 = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 1){
                        $grade1 = $status->promoted;
                    }
                }   
                $grade7 = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 10){
                        $grade7 = $status->promoted;
                    }
                }  
            @endphp 
            {{$grade1}}  /  {{$grade7}}    
        </td>
        <td>
            @php
                $grade2male = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 5){
                        $grade2male = $status->promotedmale;
                    }
                }   
                $grade8male = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 11){
                        $grade8male = $status->promotedmale;
                    }
                }  
            @endphp 
            {{$grade2male}}  /  {{$grade8male}}    
        </td>
        <td>
            @php
                $grade2female = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 5){
                        $grade2female = $status->promotedfemale;
                    }
                }   
                $grade8female = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 11){
                        $grade8female = $status->promotedfemale;
                    }
                }  
            @endphp 
            {{$grade2female}}  /  {{$grade8female}}  
        </td>
        <td>
            @php
                $grade2 = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 5){
                        $grade2 = $status->promoted;
                    }
                }   
                $grade8 = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 11){
                        $grade8 = $status->promoted;
                    }
                }  
            @endphp 
            {{$grade2}}  /  {{$grade8}}    
        </td>
        <td>
            @php
                $grade3male = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 6){
                        $grade3male = $status->promotedmale;
                    }
                }   
                $grade9male = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 12){
                        $grade9male = $status->promotedmale;
                    }
                }  
            @endphp 
            {{$grade3male}}  /  {{$grade9male}}    
        </td>
        <td>
            @php
                $grade3female = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 6){
                        $grade3female = $status->promotedfemale;
                    }
                }   
                $grade9female = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 12){
                        $grade9female = $status->promotedfemale;
                    }
                }  
            @endphp 
            {{$grade3female}}  /  {{$grade9female}}  
        </td>
        <td>
            @php
                $grade3 = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 6){
                        $grade3 = $status->promoted;
                    }
                }   
                $grade9 = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 12){
                        $grade9 = $status->promoted;
                    }
                }  
            @endphp 
            {{$grade3}}  /  {{$grade9}}  
        </td>
        <td>
            @php
                $grade4male = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 7){
                        $grade4male = $status->promotedmale;
                    }
                }   
                $grade10male = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 13){
                        $grade10male = $status->promotedmale;
                    }
                }  
            @endphp 
            {{$grade4male}}  /  {{$grade10male}}    
        </td>
        <td>
            @php
                $grade4female = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 7){
                        $grade4female = $status->promotedfemale;
                    }
                }   
                $grade10female = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 13){
                        $grade10female = $status->promotedfemale;
                    }
                }  
            @endphp 
            {{$grade4female}}  /  {{$grade10female}}  
        </td>
        <td>
            @php
                $grade4 = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 7){
                        $grade4 = $status->promoted;
                    }
                }   
                $grade10 = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 13){
                        $grade10 = $status->promoted;
                    }
                }  
            @endphp 
            {{$grade4}}  /  {{$grade10}}  
        </td>
        <td>
            @php
                $grade5male = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 16){
                        $grade5male = $status->promotedmale;
                    }
                }   
                $grade11male = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 14){
                        $grade11male = $status->promotedmale;
                    }
                }  
            @endphp 
            {{$grade5male}}  /  {{$grade11male}}    
        </td>
        <td>
            @php
                $grade5female = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 16){
                        $grade5female = $status->promotedfemale;
                    }
                }   
                $grade11female = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 14){
                        $grade11female = $status->promotedfemale;
                    }
                }  
            @endphp 
            {{$grade5female}}  /  {{$grade11female}}  
        </td>
        <td>
            @php
                $grade5 = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 16){
                        $grade5 = $status->promoted;
                    }
                }   
                $grade11 = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 14){
                        $grade11 = $status->promoted;
                    }
                }  
            @endphp 
            {{$grade5}}  /  {{$grade11}}  
        </td>
        <td>
            @php
                $grade6male = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 9){
                        $grade6male = $status->promotedmale;
                    }
                }   
                $grade12male = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 15){
                        $grade12male = $status->promotedmale;
                    }
                }  
            @endphp 
            {{$grade6male}}  /  {{$grade12male}}    
        </td>
        <td>
            @php
                $grade6female = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 9){
                        $grade6female = $status->promotedfemale;
                    }
                }   
                $grade12female = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 15){
                        $grade12female = $status->promotedfemale;
                    }
                }  
            @endphp 
            {{$grade6female}}  /  {{$grade12female}}  
        </td>
        <td>
            @php
                $grade6 = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 9){
                        $grade6 = $status->promoted;
                    }
                }   
                $grade12 = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 15){
                        $grade12 = $status->promoted;
                    }
                }  
            @endphp 
            {{$grade6}}  /  {{$grade12}}  
        </td>
        <td>
            @php
                $promotedmaletotal = $grade1male+$grade2male+$grade3male+$grade4male+$grade5male+$grade6male+$grade7male+$grade8male+$grade9male+$grade10male+$grade11male+$grade12male;   
            @endphp
            {{$promotedmaletotal}}
        </td>
        <td>
            @php
                $promotedfemaletotal = $grade1female+$grade2female+$grade3female+$grade4female+$grade5female+$grade6female+$grade7female+$grade8female+$grade9female+$grade10female+$grade11female+$grade12female;   
            @endphp
            {{$promotedfemaletotal}}
        </td>
        <td>
            @php
                $promotedtotal = $grade1+$grade2+$grade3+$grade4+$grade5+$grade6+$grade7+$grade8+$grade9+$grade10+$grade11+$grade12;   
            @endphp
            {{$promotedtotal}}
        </td>
    </tr>
    <tr>
        <th class="leftAlign">CONDITIONAL
            {{-- <br>
            (Grade 7 onwards only) --}}
        </th>
        <td>
            @php
                $grade1maleirregular = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 1){
                        $grade1maleirregular = $status->irregularmale;
                    }
                }   
                $grade7maleirregular = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 10){
                        $grade7maleirregular = $status->irregularmale;
                    }
                }  
            @endphp 
            {{$grade1maleirregular}}  /  {{$grade7maleirregular}}               
        </td>
        <td>
            @php
                $grade1femaleirregular = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 1){
                        $grade1femaleirregular = $status->irregularfemale;
                    }
                }   
                $grade7femaleirregular = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 10){
                        $grade7femaleirregular = $status->irregularfemale;
                    }
                }  
            @endphp 
            {{$grade1femaleirregular}}  /  {{$grade7femaleirregular}}     
        </td>
        <td>
            @php
                $grade1irregular = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 1){
                        $grade1irregular = $status->irregular;
                    }
                }   
                $grade7irregular = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 10){
                        $grade7irregular = $status->irregular ;
                    }
                }  
            @endphp 
            {{$grade1irregular}}  /  {{$grade7irregular}}    
        </td>
        <td>
            @php
                $grade2maleirregular = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 5){
                        $grade2maleirregular = $status->irregularmale;
                    }
                }   
                $grade8maleirregular = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 11){
                        $grade8maleirregular = $status->irregularmale;
                    }
                }  
            @endphp 
            {{$grade2maleirregular}}  /  {{$grade8maleirregular}}    
        </td>
        <td>
            @php
                $grade2femaleirregular = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 5){
                        $grade2femaleirregular = $status->irregularfemale;
                    }
                }   
                $grade8femaleirregular = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 11){
                        $grade8femaleirregular = $status->irregularfemale;
                    }
                }  
            @endphp 
            {{$grade2femaleirregular}}  /  {{$grade8femaleirregular}}  
        </td>
        <td>
            @php
                $grade2irregular = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 5){
                        $grade2irregular = $status->irregular;
                    }
                }   
                $grade8irregular = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 11){
                        $grade8irregular = $status->irregular;
                    }
                }  
            @endphp 
            {{$grade2irregular}}  /  {{$grade8irregular}}    
        </td>
        <td>
            @php
                $grade3maleirregular = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 6){
                        $grade3maleirregular = $status->irregularmale;
                    }
                }   
                $grade9maleirregular = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 12){
                        $grade9maleirregular = $status->irregularmale;
                    }
                }  
            @endphp 
            {{$grade3maleirregular}}  /  {{$grade9maleirregular}}    
        </td>
        <td>
            @php
                $grade3femaleirregular = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 6){
                        $grade3femaleirregular = $status->irregularfemale;
                    }
                }   
                $grade9femaleirregular = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 12){
                        $grade9femaleirregular = $status->irregularfemale;
                    }
                }  
            @endphp 
            {{$grade3femaleirregular}}  /  {{$grade9femaleirregular}}  
        </td>
        <td>
            @php
                $grade3irregular = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 6){
                        $grade3irregular = $status->irregular;
                    }
                }   
                $grade9irregular = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 12){
                        $grade9irregular = $status->irregular;
                    }
                }  
            @endphp 
            {{$grade3irregular}}  /  {{$grade9irregular}}  
        </td>
        <td>
            @php
                $grade4maleirregular = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 7){
                        $grade4maleirregular = $status->irregularmale;
                    }
                }   
                $grade10maleirregular = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 13){
                        $grade10maleirregular = $status->irregularmale;
                    }
                }  
            @endphp 
            {{$grade4maleirregular}}  /  {{$grade10maleirregular}}    
        </td>
        <td>
            @php
                $grade4femaleirregular = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 7){
                        $grade4femaleirregular = $status->irregularfemale;
                    }
                }   
                $grade10femaleirregular = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 13){
                        $grade10femaleirregular = $status->irregularfemale;
                    }
                }  
            @endphp 
            {{$grade4femaleirregular}}  /  {{$grade10femaleirregular}}  
        </td>
        <td>
            @php
                $grade4irregular = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 7){
                        $grade4irregular = $status->irregular;
                    }
                }   
                $grade10irregular = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 13){
                        $grade10irregular = $status->irregular;
                    }
                }  
            @endphp 
            {{$grade4irregular}}  /  {{$grade10irregular}}  
        </td>
        <td>
            @php
                $grade5maleirregular = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 16){
                        $grade5maleirregular = $status->irregularmale;
                    }
                }   
                $grade11maleirregular = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 14){
                        $grade11maleirregular = $status->irregularmale;
                    }
                }  
            @endphp 
            {{$grade5maleirregular}}  /  {{$grade11maleirregular}}    
        </td>
        <td>
            @php
                $grade5femaleirregular = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 16){
                        $grade5femaleirregular = $status->irregularfemale;
                    }
                }   
                $grade11femaleirregular = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 14){
                        $grade11femaleirregular = $status->irregularfemale;
                    }
                }  
            @endphp 
            {{$grade5femaleirregular}}  /  {{$grade11femaleirregular}}  
        </td>
        <td>
            @php
                $grade5irregular = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 16){
                        $grade5irregular = $status->irregular;
                    }
                }   
                $grade11irregular = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 14){
                        $grade11irregular = $status->irregular;
                    }
                }  
            @endphp 
            {{$grade5irregular}}  /  {{$grade11irregular}}  
        </td>
        <td>
            @php
                $grade6maleirregular = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 9){
                        $grade6maleirregular = $status->irregularmale;
                    }
                }   
                $grade12maleirregular = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 15){
                        $grade12maleirregular = $status->irregularmale;
                    }
                }  
            @endphp 
            {{$grade6maleirregular}}  /  {{$grade12maleirregular}}    
        </td>
        <td>
            @php
                $grade6femaleirregular = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 9){
                        $grade6femaleirregular = $status->irregularfemale;
                    }
                }   
                $grade12femaleirregular = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 15){
                        $grade12femaleirregular = $status->irregularfemale;
                    }
                }  
            @endphp 
            {{$grade6femaleirregular}}  /  {{$grade12femaleirregular}}  
        </td>
        <td>
            @php
                $grade6irregular = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 9){
                        $grade6irregular = $status->irregular;
                    }
                }   
                $grade12irregular = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 15){
                        $grade12irregular = $status->irregular;
                    }
                }  
            @endphp 
            {{$grade6irregular}}  /  {{$grade12irregular}}  
        </td>
        <td>
            @php
                $promotedmaletotalirregular = $grade1maleirregular+$grade2maleirregular+$grade3maleirregular+$grade4maleirregular+$grade5maleirregular+$grade6maleirregular+$grade7maleirregular+$grade8maleirregular+$grade9maleirregular+$grade10maleirregular+$grade11maleirregular+$grade12maleirregular;   
            @endphp
            {{$promotedmaletotalirregular}}
        </td>
        <td>
            @php
                $promotedfemaletotalirregular = $grade1femaleirregular+$grade2femaleirregular+$grade3femaleirregular+$grade4femaleirregular+$grade5femaleirregular+$grade6femaleirregular+$grade7femaleirregular+$grade8femaleirregular+$grade9femaleirregular+$grade10femaleirregular+$grade11femaleirregular+$grade12femaleirregular;   
            @endphp
            {{$promotedfemaletotalirregular}}
        </td>
        <td>
            @php
                $promotedtotalirregular = $grade1irregular+$grade2irregular+$grade3irregular+$grade4irregular+$grade5irregular+$grade6irregular+$grade7irregular+$grade8irregular+$grade9irregular+$grade10irregular+$grade11irregular+$grade12irregular;   
            @endphp
            {{$promotedtotalirregular}}
        </td>
    </tr>
    <tr>
        <th class="leftAlign">RETAINED</th>
        <td>
            @php
                $grade1maleretained = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 1){
                        $grade1maleretained = $status->retainedmale;
                    }
                }   
                $grade7maleretained = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 10){
                        $grade7maleretained = $status->retainedmale;
                    }
                }  
            @endphp 
            {{$grade1maleretained}}  /  {{$grade7maleretained}}               
        </td>
        <td>
            @php
                $grade1femaleretained = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 1){
                        $grade1femaleretained = $status->retainedfemale;
                    }
                }   
                $grade7femaleretained = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 10){
                        $grade7femaleretained = $status->retainedfemale;
                    }
                }  
            @endphp 
            {{$grade1femaleretained}}  /  {{$grade7femaleretained}}     
        </td>
        <td>
            @php
                $grade1retained = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 1){
                        $grade1retained = $status->retained;
                    }
                }   
                $grade7retained = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 10){
                        $grade7retained = $status->retained;
                    }
                }  
            @endphp 
            {{$grade1retained}}  /  {{$grade7retained}}    
        </td>
        <td>
            @php
                $grade2maleretained = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 5){
                        $grade2maleretained = $status->retainedmale;
                    }
                }   
                $grade8maleretained = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 11){
                        $grade8maleretained = $status->retainedmale;
                    }
                }  
            @endphp 
            {{$grade2maleretained}}  /  {{$grade8maleretained}}    
        </td>
        <td>
            @php
                $grade2femaleretained = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 5){
                        $grade2femaleretained = $status->retainedfemale;
                    }
                }   
                $grade8femaleretained = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 11){
                        $grade8femaleretained = $status->retainedfemale;
                    }
                }  
            @endphp 
            {{$grade2femaleretained}}  /  {{$grade8femaleretained}}  
        </td>
        <td>
            @php
                $grade2retained = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 5){
                        $grade2retained = $status->retained;
                    }
                }   
                $grade8retained = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 11){
                        $grade8retained = $status->retained;
                    }
                }  
            @endphp 
            {{$grade2retained}}  /  {{$grade8retained}}    
        </td>
        <td>
            @php
                $grade3maleretained = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 6){
                        $grade3maleretained = $status->retainedmale;
                    }
                }   
                $grade9maleretained = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 12){
                        $grade9maleretained = $status->retainedmale;
                    }
                }  
            @endphp 
            {{$grade3maleretained}}  /  {{$grade9maleretained}}    
        </td>
        <td>
            @php
                $grade3femaleretained = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 6){
                        $grade3femaleretained = $status->retainedfemale;
                    }
                }   
                $grade9femaleretained = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 12){
                        $grade9femaleretained = $status->retainedfemale;
                    }
                }  
            @endphp 
            {{$grade3femaleretained}}  /  {{$grade9femaleretained}}  
        </td>
        <td>
            @php
                $grade3retained = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 6){
                        $grade3retained = $status->retained;
                    }
                }   
                $grade9retained = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 12){
                        $grade9retained = $status->retained;
                    }
                }  
            @endphp 
            {{$grade3retained}}  /  {{$grade9retained}}  
        </td>
        <td>
            @php
                $grade4maleretained = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 7){
                        $grade4maleretained = $status->retainedmale;
                    }
                }   
                $grade10maleretained = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 13){
                        $grade10maleretained = $status->retainedmale;
                    }
                }  
            @endphp 
            {{$grade4maleretained}}  /  {{$grade10maleretained}}    
        </td>
        <td>
            @php
                $grade4femaleretained = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 7){
                        $grade4femaleretained = $status->retainedfemale;
                    }
                }   
                $grade10femaleretained = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 13){
                        $grade10femaleretained = $status->retainedfemale;
                    }
                }  
            @endphp 
            {{$grade4femaleretained}}  /  {{$grade10femaleretained}}  
        </td>
        <td>
            @php
                $grade4retained = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 7){
                        $grade4retained = $status->retained;
                    }
                }   
                $grade10retained = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 13){
                        $grade10retained = $status->retained;
                    }
                }  
            @endphp 
            {{$grade4retained}}  /  {{$grade10retained}}  
        </td>
        <td>
            @php
                $grade5maleretained = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 16){
                        $grade5maleretained = $status->retainedmale;
                    }
                }   
                $grade11maleretained = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 14){
                        $grade11maleretained = $status->retainedmale;
                    }
                }  
            @endphp 
            {{$grade5maleretained}}  /  {{$grade11maleretained}}    
        </td>
        <td>
            @php
                $grade5femaleretained = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 16){
                        $grade5femaleretained = $status->retainedfemale;
                    }
                }   
                $grade11femaleretained = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 14){
                        $grade11femaleretained = $status->retainedfemale;
                    }
                }  
            @endphp 
            {{$grade5femaleretained}}  /  {{$grade11femaleretained}}  
        </td>
        <td>
            @php
                $grade5retained = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 16){
                        $grade5retained = $status->retained;
                    }
                }   
                $grade11retained = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 14){
                        $grade11retained = $status->retained;
                    }
                }  
            @endphp 
            {{$grade5retained}}  /  {{$grade11retained}}  
        </td>
        <td>
            @php
                $grade6maleretained = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 9){
                        $grade6maleretained = $status->retainedmale;
                    }
                }   
                $grade12maleretained = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 15){
                        $grade12maleretained = $status->retainedmale;
                    }
                }  
            @endphp 
            {{$grade6maleretained}}  /  {{$grade12maleretained}}    
        </td>
        <td>
            @php
                $grade6femaleretained = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 9){
                        $grade6femaleretained = $status->retainedfemale;
                    }
                }   
                $grade12femaleretained = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 15){
                        $grade12femaleretained = $status->retainedfemale;
                    }
                }  
            @endphp 
            {{$grade6femaleretained}}  /  {{$grade12femaleretained}}  
        </td>
        <td>
            @php
                $grade6retained = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 9){
                        $grade6retained = $status->retained;
                    }
                }   
                $grade12retained = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 15){
                        $grade12retained = $status->retained;
                    }
                }  
            @endphp 
            {{$grade6retained}}  /  {{$grade12retained}}  
        </td>
        <td>
            @php
                $retainedmaletotal = $grade1maleretained+$grade2maleretained+$grade3maleretained+$grade4maleretained+$grade5maleretained+$grade6maleretained+$grade7maleretained+$grade8maleretained+$grade9maleretained+$grade10maleretained+$grade11maleretained+$grade12maleretained;   
            @endphp
            {{$retainedmaletotal}}
        </td>
        <td>
            @php
                $retainedfemaletotal = $grade1femaleretained+$grade2femaleretained+$grade3femaleretained+$grade4femaleretained+$grade5femaleretained+$grade6femaleretained+$grade7femaleretained+$grade8femaleretained+$grade9femaleretained+$grade10femaleretained+$grade11femaleretained+$grade12femaleretained;   
            @endphp
            {{$retainedfemaletotal}}
        </td>
        <td>
            @php
                $retainedtotal = $grade1retained+$grade2retained+$grade3retained+$grade4retained+$grade5retained+$grade6retained+$grade7retained+$grade8retained+$grade9retained+$grade10retained+$grade11retained+$grade12retained;   
            @endphp
            {{$retainedtotal}}
        </td>
    </tr>
    <tr>
        <th>LEARNING PROGRESS & ACHIEVEMENT (based on Learners' General Average)</th>
        <th>MALE</th>
        <th>FEMALE</th>
        <th>TOTAL</th>
        <th>MALE</th>
        <th>FEMALE</th>
        <th>TOTAL</th>
        <th>MALE</th>
        <th>FEMALE</th>
        <th>TOTAL</th>
        <th>MALE</th>
        <th>FEMALE</th>
        <th>TOTAL</th>
        <th>MALE</th>
        <th>FEMALE</th>
        <th>TOTAL</th>
        <th>MALE</th>
        <th>FEMALE</th>
        <th>TOTAL</th>
        <th>MALE</th>
        <th>FEMALE</th>
        <th>TOTAL</th>
    </tr>
    <tr>
        <th>
            Did Not Meet Expectations
            <br>
            (74% and below)
        </th>
        <td>
            @php
                $grade1maleproficiencyb = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 1){
                        $grade1maleproficiencyb = $status->proficiencybmale;
                    }
                }   
                $grade7maleproficiencyb = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 10){
                        $grade7maleproficiencyb = $status->proficiencybmale;
                    }
                }  
            @endphp 
            {{$grade1maleproficiencyb}}  /  {{$grade7maleproficiencyb}}               
        </td>
        <td>
            @php
                $grade1femaleproficiencyb = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 1){
                        $grade1femaleproficiencyb = $status->proficiencybfemale;
                    }
                }   
                $grade7femaleproficiencyb = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 10){
                        $grade7femaleproficiencyb = $status->proficiencybfemale;
                    }
                }  
            @endphp 
            {{$grade1femaleproficiencyb}}  /  {{$grade7femaleproficiencyb}}     
        </td>
        <td>
            @php
                $grade1proficiencyb = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 1){
                        $grade1proficiencyb = $status->proficiencyb;
                    }
                }   
                $grade7proficiencyb = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 10){
                        $grade7proficiencyb = $status->proficiencyb;
                    }
                }  
            @endphp 
            {{$grade1proficiencyb}}  /  {{$grade7proficiencyb}}    
        </td>
        <td>
            @php
                $grade2maleproficiencyb = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 5){
                        $grade2maleproficiencyb = $status->proficiencybmale;
                    }
                }   
                $grade8maleproficiencyb = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 11){
                        $grade8maleproficiencyb = $status->proficiencybmale;
                    }
                }  
            @endphp 
            {{$grade2maleproficiencyb}}  /  {{$grade8maleproficiencyb}}    
        </td>
        <td>
            @php
                $grade2femaleproficiencyb = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 5){
                        $grade2femaleproficiencyb = $status->proficiencybfemale;
                    }
                }   
                $grade8femaleproficiencyb = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 11){
                        $grade8femaleproficiencyb = $status->proficiencybfemale;
                    }
                }  
            @endphp 
            {{$grade2femaleproficiencyb}}  /  {{$grade8femaleproficiencyb}}  
        </td>
        <td>
            @php
                $grade2proficiencyb = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 5){
                        $grade2proficiencyb = $status->proficiencyb;
                    }
                }   
                $grade8proficiencyb = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 11){
                        $grade8proficiencyb = $status->proficiencyb;
                    }
                }  
            @endphp 
            {{$grade2proficiencyb}}  /  {{$grade8proficiencyb}}    
        </td>
        <td>
            @php
                $grade3maleproficiencyb = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 6){
                        $grade3maleproficiencyb = $status->proficiencybmale;
                    }
                }   
                $grade9maleproficiencyb = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 12){
                        $grade9maleproficiencyb = $status->proficiencybmale;
                    }
                }  
            @endphp 
            {{$grade3maleproficiencyb}}  /  {{$grade9maleproficiencyb}}    
        </td>
        <td>
            @php
                $grade3femaleproficiencyb = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 6){
                        $grade3femaleproficiencyb = $status->proficiencybfemale;
                    }
                }   
                $grade9femaleproficiencyb = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 12){
                        $grade9femaleproficiencyb = $status->proficiencybfemale;
                    }
                }  
            @endphp 
            {{$grade3femaleproficiencyb}}  /  {{$grade9femaleproficiencyb}}  
        </td>
        <td>
            @php
                $grade3proficiencyb = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 6){
                        $grade3proficiencyb = $status->proficiencyb;
                    }
                }   
                $grade9proficiencyb = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 12){
                        $grade9proficiencyb = $status->proficiencyb;
                    }
                }  
            @endphp 
            {{$grade3proficiencyb}}  /  {{$grade9proficiencyb}}  
        </td>
        <td>
            @php
                $grade4maleproficiencyb = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 7){
                        $grade4maleproficiencyb = $status->proficiencybmale;
                    }
                }   
                $grade10maleproficiencyb = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 13){
                        $grade10maleproficiencyb = $status->proficiencybmale;
                    }
                }  
            @endphp 
            {{$grade4maleproficiencyb}}  /  {{$grade10maleproficiencyb}}    
        </td>
        <td>
            @php
                $grade4femaleproficiencyb = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 7){
                        $grade4femaleproficiencyb = $status->proficiencybfemale;
                    }
                }   
                $grade10femaleproficiencyb = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 13){
                        $grade10femaleproficiencyb = $status->proficiencybfemale;
                    }
                }  
            @endphp 
            {{$grade4femaleproficiencyb}}  /  {{$grade10femaleproficiencyb}}  
        </td>
        <td>
            @php
                $grade4proficiencyb = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 7){
                        $grade4proficiencyb = $status->proficiencyb;
                    }
                }   
                $grade10proficiencyb = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 13){
                        $grade10proficiencyb = $status->proficiencyb;
                    }
                }  
            @endphp 
            {{$grade4proficiencyb}}  /  {{$grade10proficiencyb}}  
        </td>
        <td>
            @php
                $grade5maleproficiencyb = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 16){
                        $grade5maleproficiencyb = $status->proficiencybmale;
                    }
                }   
                $grade11maleproficiencyb = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 14){
                        $grade11maleproficiencyb = $status->proficiencybmale;
                    }
                }  
            @endphp 
            {{$grade5maleproficiencyb}}  /  {{$grade11maleproficiencyb}}    
        </td>
        <td>
            @php
                $grade5femaleproficiencyb = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 16){
                        $grade5femaleproficiencyb = $status->proficiencybfemale;
                    }
                }   
                $grade11femaleproficiencyb = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 14){
                        $grade11femaleproficiencyb = $status->proficiencybfemale;
                    }
                }  
            @endphp 
            {{$grade5femaleproficiencyb}}  /  {{$grade11femaleproficiencyb}}  
        </td>
        <td>
            @php
                $grade5proficiencyb = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 16){
                        $grade5proficiencyb = $status->proficiencyb;
                    }
                }   
                $grade11proficiencyb = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 14){
                        $grade11proficiencyb = $status->proficiencyb;
                    }
                }  
            @endphp 
            {{$grade5proficiencyb}}  /  {{$grade11proficiencyb}}  
        </td>
        <td>
            @php
                $grade6maleproficiencyb = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 9){
                        $grade6maleproficiencyb = $status->proficiencybmale;
                    }
                }   
                $grade12maleproficiencyb = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 15){
                        $grade12maleproficiencyb = $status->proficiencybmale;
                    }
                }  
            @endphp 
            {{$grade6maleproficiencyb}}  /  {{$grade12maleproficiencyb}}    
        </td>
        <td>
            @php
                $grade6femaleproficiencyb = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 9){
                        $grade6femaleproficiencyb = $status->proficiencybfemale;
                    }
                }   
                $grade12femaleproficiencyb = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 15){
                        $grade12femaleproficiencyb = $status->proficiencybfemale;
                    }
                }  
            @endphp 
            {{$grade6femaleproficiencyb}}  /  {{$grade12femaleproficiencyb}}  
        </td>
        <td>
            @php
                $grade6proficiencyb = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 9){
                        $grade6proficiencyb = $status->proficiencyb;
                    }
                }   
                $grade12proficiencyb = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 15){
                        $grade12proficiencyb = $status->proficiencyb;
                    }
                }  
            @endphp 
            {{$grade6proficiencyb}}  /  {{$grade12proficiencyb}}  
        </td>
        <td>
            @php
                $proficiencybmaletotal = $grade1maleproficiencyb+$grade2maleproficiencyb+$grade3maleproficiencyb+$grade4maleproficiencyb+$grade5maleproficiencyb+$grade6maleproficiencyb+$grade7maleproficiencyb+$grade8maleproficiencyb+$grade9maleproficiencyb+$grade10maleproficiencyb+$grade11maleproficiencyb+$grade12maleproficiencyb;   
            @endphp
            {{$proficiencybmaletotal}}
        </td>
        <td>
            @php
                $proficiencybfemaletotal = $grade1femaleproficiencyb+$grade2femaleproficiencyb+$grade3femaleproficiencyb+$grade4femaleproficiencyb+$grade5femaleproficiencyb+$grade6femaleproficiencyb+$grade7femaleproficiencyb+$grade8femaleproficiencyb+$grade9femaleproficiencyb+$grade10femaleproficiencyb+$grade11femaleproficiencyb+$grade12femaleproficiencyb;   
            @endphp
            {{$proficiencybfemaletotal}}
        </td>
        <td>
            @php
                $proficiencybtotal = $grade1proficiencyb+$grade2proficiencyb+$grade3proficiencyb+$grade4proficiencyb+$grade5proficiencyb+$grade6proficiencyb+$grade7proficiencyb+$grade8proficiencyb+$grade9proficiencyb+$grade10proficiencyb+$grade11proficiencyb+$grade12proficiencyb;   
            @endphp
            {{$proficiencybtotal}}
        </td>
    </tr>
    <tr>
        <th>
            Fairly Satisfactory  
            <br>
            (75%-79%)
        </th>
        <td>
            @php
                $grade1maleproficiencyd = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 1){
                        $grade1maleproficiencyd = $status->proficiencydmale;
                    }
                }   
                $grade7maleproficiencyd = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 10){
                        $grade7maleproficiencyd = $status->proficiencydmale;
                    }
                }  
            @endphp 
            {{$grade1maleproficiencyd}}  /  {{$grade7maleproficiencyd}}               
        </td>
        <td>
            @php
                $grade1femaleproficiencyd = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 1){
                        $grade1femaleproficiencyd = $status->proficiencydfemale;
                    }
                }   
                $grade7femaleproficiencyd = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 10){
                        $grade7femaleproficiencyd = $status->proficiencydfemale;
                    }
                }  
            @endphp 
            {{$grade1femaleproficiencyd}}  /  {{$grade7femaleproficiencyd}}     
        </td>
        <td>
            @php
                $grade1proficiencyd = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 1){
                        $grade1proficiencyd = $status->proficiencyd;
                    }
                }   
                $grade7proficiencyd = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 10){
                        $grade7proficiencyd = $status->proficiencyd;
                    }
                }  
            @endphp 
            {{$grade1proficiencyd}}  /  {{$grade7proficiencyd}}    
        </td>
        <td>
            @php
                $grade2maleproficiencyd = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 5){
                        $grade2maleproficiencyd = $status->proficiencydmale;
                    }
                }   
                $grade8maleproficiencyd = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 11){
                        $grade8maleproficiencyd = $status->proficiencydmale;
                    }
                }  
            @endphp 
            {{$grade2maleproficiencyd}}  /  {{$grade8maleproficiencyd}}    
        </td>
        <td>
            @php
                $grade2femaleproficiencyd = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 5){
                        $grade2femaleproficiencyd = $status->proficiencydfemale;
                    }
                }   
                $grade8femaleproficiencyd = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 11){
                        $grade8femaleproficiencyd = $status->proficiencydfemale;
                    }
                }  
            @endphp 
            {{$grade2femaleproficiencyd}}  /  {{$grade8femaleproficiencyd}}  
        </td>
        <td>
            @php
                $grade2proficiencyd = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 5){
                        $grade2proficiencyd = $status->proficiencyd;
                    }
                }   
                $grade8proficiencyd = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 11){
                        $grade8proficiencyd = $status->proficiencyd;
                    }
                }  
            @endphp 
            {{$grade2proficiencyd}}  /  {{$grade8proficiencyd}}    
        </td>
        <td>
            @php
                $grade3maleproficiencyd = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 6){
                        $grade3maleproficiencyd = $status->proficiencydmale;
                    }
                }   
                $grade9maleproficiencyd = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 12){
                        $grade9maleproficiencyd = $status->proficiencydmale;
                    }
                }  
            @endphp 
            {{$grade3maleproficiencyd}}  /  {{$grade9maleproficiencyd}}    
        </td>
        <td>
            @php
                $grade3femaleproficiencyd = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 6){
                        $grade3femaleproficiencyd = $status->proficiencydfemale;
                    }
                }   
                $grade9femaleproficiencyd = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 12){
                        $grade9femaleproficiencyd = $status->proficiencydfemale;
                    }
                }  
            @endphp 
            {{$grade3femaleproficiencyd}}  /  {{$grade9femaleproficiencyd}}  
        </td>
        <td>
            @php
                $grade3proficiencyd = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 6){
                        $grade3proficiencyd = $status->proficiencyd;
                    }
                }   
                $grade9proficiencyd = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 12){
                        $grade9proficiencyd = $status->proficiencyd;
                    }
                }  
            @endphp 
            {{$grade3proficiencyd}}  /  {{$grade9proficiencyd}}  
        </td>
        <td>
            @php
                $grade4maleproficiencyd = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 7){
                        $grade4maleproficiencyd = $status->proficiencydmale;
                    }
                }   
                $grade10maleproficiencyd = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 13){
                        $grade10maleproficiencyd = $status->proficiencydmale;
                    }
                }  
            @endphp 
            {{$grade4maleproficiencyd}}  /  {{$grade10maleproficiencyd}}    
        </td>
        <td>
            @php
                $grade4femaleproficiencyd = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 7){
                        $grade4femaleproficiencyd = $status->proficiencydfemale;
                    }
                }   
                $grade10femaleproficiencyd = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 13){
                        $grade10femaleproficiencyd = $status->proficiencydfemale;
                    }
                }  
            @endphp 
            {{$grade4femaleproficiencyd}}  /  {{$grade10femaleproficiencyd}}  
        </td>
        <td>
            @php
                $grade4proficiencyd = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 7){
                        $grade4proficiencyd = $status->proficiencyd;
                    }
                }   
                $grade10proficiencyd = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 13){
                        $grade10proficiencyd = $status->proficiencyd;
                    }
                }  
            @endphp 
            {{$grade4proficiencyd}}  /  {{$grade10proficiencyd}}  
        </td>
        <td>
            @php
                $grade5maleproficiencyd = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 16){
                        $grade5maleproficiencyd = $status->proficiencydmale;
                    }
                }   
                $grade11maleproficiencyd = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 14){
                        $grade11maleproficiencyd = $status->proficiencydmale;
                    }
                }  
            @endphp 
            {{$grade5maleproficiencyd}}  /  {{$grade11maleproficiencyd}}    
        </td>
        <td>
            @php
                $grade5femaleproficiencyd = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 16){
                        $grade5femaleproficiencyd = $status->proficiencydfemale;
                    }
                }   
                $grade11femaleproficiencyd = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 14){
                        $grade11femaleproficiencyd = $status->proficiencydfemale;
                    }
                }  
            @endphp 
            {{$grade5femaleproficiencyd}}  /  {{$grade11femaleproficiencyd}}  
        </td>
        <td>
            @php
                $grade5proficiencyd = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 16){
                        $grade5proficiencyd = $status->proficiencyd;
                    }
                }   
                $grade11proficiencyd = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 14){
                        $grade11proficiencyd = $status->proficiencyd;
                    }
                }  
            @endphp 
            {{$grade5proficiencyd}}  /  {{$grade11proficiencyd}}  
        </td>
        <td>
            @php
                $grade6maleproficiencyd = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 9){
                        $grade6maleproficiencyd = $status->proficiencydmale;
                    }
                }   
                $grade12maleproficiencyd = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 15){
                        $grade12maleproficiencyd = $status->proficiencydmale;
                    }
                }  
            @endphp 
            {{$grade6maleproficiencyd}}  /  {{$grade12maleproficiencyd}}    
        </td>
        <td>
            @php
                $grade6femaleproficiencyd = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 9){
                        $grade6femaleproficiencyd = $status->proficiencydfemale;
                    }
                }   
                $grade12femaleproficiencyd = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 15){
                        $grade12femaleproficiencyd = $status->proficiencydfemale;
                    }
                }  
            @endphp 
            {{$grade6femaleproficiencyd}}  /  {{$grade12femaleproficiencyd}}  
        </td>
        <td>
            @php
                $grade6proficiencyd = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 9){
                        $grade6proficiencyd = $status->proficiencyd;
                    }
                }   
                $grade12proficiencyd = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 15){
                        $grade12proficiencyd = $status->proficiencyd;
                    }
                }  
            @endphp 
            {{$grade6proficiencyd}}  /  {{$grade12proficiencyd}}  
        </td>
        <td>
            @php
                $proficiencydmaletotal = $grade1maleproficiencyd+$grade2maleproficiencyd+$grade3maleproficiencyd+$grade4maleproficiencyd+$grade5maleproficiencyd+$grade6maleproficiencyd+$grade7maleproficiencyd+$grade8maleproficiencyd+$grade9maleproficiencyd+$grade10maleproficiencyd+$grade11maleproficiencyd+$grade12maleproficiencyd;   
            @endphp
            {{$proficiencydmaletotal}}
        </td>
        <td>
            @php
                $proficiencydfemaletotal = $grade1femaleproficiencyd+$grade2femaleproficiencyd+$grade3femaleproficiencyd+$grade4femaleproficiencyd+$grade5femaleproficiencyd+$grade6femaleproficiencyd+$grade7femaleproficiencyd+$grade8femaleproficiencyd+$grade9femaleproficiencyd+$grade10femaleproficiencyd+$grade11femaleproficiencyd+$grade12femaleproficiencyd;   
            @endphp
            {{$proficiencydfemaletotal}}
        </td>
        <td>
            @php
                $proficiencydtotal = $grade1proficiencyd+$grade2proficiencyd+$grade3proficiencyd+$grade4proficiencyd+$grade5proficiencyd+$grade6proficiencyd+$grade7proficiencyd+$grade8proficiencyd+$grade9proficiencyd+$grade10proficiencyd+$grade11proficiencyd+$grade12proficiencyd;   
            @endphp
            {{$proficiencydtotal}}
        </td>
    </tr>
    <tr>
        <th>Satisfactory<br>(80%-84%)</th>
        <td>
            @php
                $grade1maleproficiencyap = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 1){
                        $grade1maleproficiencyap = $status->proficiencyapmale;
                    }
                }   
                $grade7maleproficiencyap = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 10){
                        $grade7maleproficiencyap = $status->proficiencyapmale;
                    }
                }  
            @endphp 
            {{$grade1maleproficiencyap}}  /  {{$grade7maleproficiencyap}}               
        </td>
        <td>
            @php
                $grade1femaleproficiencyap = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 1){
                        $grade1femaleproficiencyap = $status->proficiencyapfemale;
                    }
                }   
                $grade7femaleproficiencyap = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 10){
                        $grade7femaleproficiencyap = $status->proficiencyapfemale;
                    }
                }  
            @endphp 
            {{$grade1femaleproficiencyap}}  /  {{$grade7femaleproficiencyap}}     
        </td>
        <td>
            @php
                $grade1proficiencyap = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 1){
                        $grade1proficiencyap = $status->proficiencyap;
                    }
                }   
                $grade7proficiencyap = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 10){
                        $grade7proficiencyap = $status->proficiencyap;
                    }
                }  
            @endphp 
            {{$grade1proficiencyap}}  /  {{$grade7proficiencyap}}    
        </td>
        <td>
            @php
                $grade2maleproficiencyap = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 5){
                        $grade2maleproficiencyap = $status->proficiencyapmale;
                    }
                }   
                $grade8maleproficiencyap = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 11){
                        $grade8maleproficiencyap = $status->proficiencyapmale;
                    }
                }  
            @endphp 
            {{$grade2maleproficiencyap}}  /  {{$grade8maleproficiencyap}}    
        </td>
        <td>
            @php
                $grade2femaleproficiencyap = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 5){
                        $grade2femaleproficiencyap = $status->proficiencyapfemale;
                    }
                }   
                $grade8femaleproficiencyap = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 11){
                        $grade8femaleproficiencyap = $status->proficiencyapfemale;
                    }
                }  
            @endphp 
            {{$grade2femaleproficiencyap}}  /  {{$grade8femaleproficiencyap}}  
        </td>
        <td>
            @php
                $grade2proficiencyap = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 5){
                        $grade2proficiencyap = $status->proficiencyap;
                    }
                }   
                $grade8proficiencyap = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 11){
                        $grade8proficiencyap = $status->proficiencyap;
                    }
                }  
            @endphp 
            {{$grade2proficiencyap}}  /  {{$grade8proficiencyap}}    
        </td>
        <td>
            @php
                $grade3maleproficiencyap = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 6){
                        $grade3maleproficiencyap = $status->proficiencyapmale;
                    }
                }   
                $grade9maleproficiencyap = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 12){
                        $grade9maleproficiencyap = $status->proficiencyapmale;
                    }
                }  
            @endphp 
            {{$grade3maleproficiencyap}}  /  {{$grade9maleproficiencyap}}    
        </td>
        <td>
            @php
                $grade3femaleproficiencyap = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 6){
                        $grade3femaleproficiencyap = $status->proficiencyapfemale;
                    }
                }   
                $grade9femaleproficiencyap = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 12){
                        $grade9femaleproficiencyap = $status->proficiencyapfemale;
                    }
                }  
            @endphp 
            {{$grade3femaleproficiencyap}}  /  {{$grade9femaleproficiencyap}}  
        </td>
        <td>
            @php
                $grade3proficiencyap = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 6){
                        $grade3proficiencyap = $status->proficiencyap;
                    }
                }   
                $grade9proficiencyap = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 12){
                        $grade9proficiencyap = $status->proficiencyap;
                    }
                }  
            @endphp 
            {{$grade3proficiencyap}}  /  {{$grade9proficiencyap}}  
        </td>
        <td>
            @php
                $grade4maleproficiencyap = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 7){
                        $grade4maleproficiencyap = $status->proficiencyapmale;
                    }
                }   
                $grade10maleproficiencyap = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 13){
                        $grade10maleproficiencyap = $status->proficiencyapmale;
                    }
                }  
            @endphp 
            {{$grade4maleproficiencyap}}  /  {{$grade10maleproficiencyap}}    
        </td>
        <td>
            @php
                $grade4femaleproficiencyap = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 7){
                        $grade4femaleproficiencyap = $status->proficiencyapfemale;
                    }
                }   
                $grade10femaleproficiencyap = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 13){
                        $grade10femaleproficiencyap = $status->proficiencyapfemale;
                    }
                }  
            @endphp 
            {{$grade4femaleproficiencyap}}  /  {{$grade10femaleproficiencyap}}  
        </td>
        <td>
            @php
                $grade4proficiencyap = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 7){
                        $grade4proficiencyap = $status->proficiencyap;
                    }
                }   
                $grade10proficiencyap = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 13){
                        $grade10proficiencyap = $status->proficiencyap;
                    }
                }  
            @endphp 
            {{$grade4proficiencyap}}  /  {{$grade10proficiencyap}}  
        </td>
        <td>
            @php
                $grade5maleproficiencyap = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 16){
                        $grade5maleproficiencyap = $status->proficiencyapmale;
                    }
                }   
                $grade11maleproficiencyap = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 14){
                        $grade11maleproficiencyap = $status->proficiencyapmale;
                    }
                }  
            @endphp 
            {{$grade5maleproficiencyap}}  /  {{$grade11maleproficiencyap}}    
        </td>
        <td>
            @php
                $grade5femaleproficiencyap = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 16){
                        $grade5femaleproficiencyap = $status->proficiencyapfemale;
                    }
                }   
                $grade11femaleproficiencyap = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 14){
                        $grade11femaleproficiencyap = $status->proficiencyapfemale;
                    }
                }  
            @endphp 
            {{$grade5femaleproficiencyap}}  /  {{$grade11femaleproficiencyap}}  
        </td>
        <td>
            @php
                $grade5proficiencyap = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 16){
                        $grade5proficiencyap = $status->proficiencyap;
                    }
                }   
                $grade11proficiencyap = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 14){
                        $grade11proficiencyap = $status->proficiencyap;
                    }
                }  
            @endphp 
            {{$grade5proficiencyap}}  /  {{$grade11proficiencyap}}  
        </td>
        <td>
            @php
                $grade6maleproficiencyap = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 9){
                        $grade6maleproficiencyap = $status->proficiencyapmale;
                    }
                }   
                $grade12maleproficiencyap = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 15){
                        $grade12maleproficiencyap = $status->proficiencyapmale;
                    }
                }  
            @endphp 
            {{$grade6maleproficiencyap}}  /  {{$grade12maleproficiencyap}}    
        </td>
        <td>
            @php
                $grade6femaleproficiencyap = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 9){
                        $grade6femaleproficiencyap = $status->proficiencyapfemale;
                    }
                }   
                $grade12femaleproficiencyap = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 15){
                        $grade12femaleproficiencyap = $status->proficiencyapfemale;
                    }
                }  
            @endphp 
            {{$grade6femaleproficiencyap}}  /  {{$grade12femaleproficiencyap}}  
        </td>
        <td>
            @php
                $grade6proficiencyap = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 9){
                        $grade6proficiencyap = $status->proficiencyap;
                    }
                }   
                $grade12proficiencyap = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 15){
                        $grade12proficiencyap = $status->proficiencyap;
                    }
                }  
            @endphp 
            {{$grade6proficiencyap}}  /  {{$grade12proficiencyap}}  
        </td>
        <td>
            @php
                $proficiencyapmaletotal = $grade1maleproficiencyap+$grade2maleproficiencyap+$grade3maleproficiencyap+$grade4maleproficiencyap+$grade5maleproficiencyap+$grade6maleproficiencyap+$grade7maleproficiencyap+$grade8maleproficiencyap+$grade9maleproficiencyap+$grade10maleproficiencyap+$grade11maleproficiencyap+$grade12maleproficiencyap;   
            @endphp
            {{$proficiencyapmaletotal}}
        </td>
        <td>
            @php
                $proficiencyapfemaletotal = $grade1femaleproficiencyap+$grade2femaleproficiencyap+$grade3femaleproficiencyap+$grade4femaleproficiencyap+$grade5femaleproficiencyap+$grade6femaleproficiencyap+$grade7femaleproficiencyap+$grade8femaleproficiencyap+$grade9femaleproficiencyap+$grade10femaleproficiencyap+$grade11femaleproficiencyap+$grade12femaleproficiencyap;   
            @endphp
            {{$proficiencyapfemaletotal}}
        </td>
        <td>
            @php
                $proficiencyaptotal = $grade1proficiencyap+$grade2proficiencyap+$grade3proficiencyap+$grade4proficiencyap+$grade5proficiencyap+$grade6proficiencyap+$grade7proficiencyap+$grade8proficiencyap+$grade9proficiencyap+$grade10proficiencyap+$grade11proficiencyap+$grade12proficiencyap;   
            @endphp
            {{$proficiencyaptotal}}
        </td>
    </tr>
    <tr>
        <th>Very Satisfactory<br>(85%-89%)</th>
        <td>
            @php
                $grade1maleproficiencyp = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 1){
                        $grade1maleproficiencyp = $status->proficiencypmale;
                    }
                }   
                $grade7maleproficiencyp = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 10){
                        $grade7maleproficiencyp = $status->proficiencypmale;
                    }
                }  
            @endphp 
            {{$grade1maleproficiencyp}}  /  {{$grade7maleproficiencyp}}               
        </td>
        <td>
            @php
                $grade1femaleproficiencyp = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 1){
                        $grade1femaleproficiencyp = $status->proficiencypfemale;
                    }
                }   
                $grade7femaleproficiencyp = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 10){
                        $grade7femaleproficiencyp = $status->proficiencypfemale;
                    }
                }  
            @endphp 
            {{$grade1femaleproficiencyp}}  /  {{$grade7femaleproficiencyp}}     
        </td>
        <td>
            @php
                $grade1proficiencyp = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 1){
                        $grade1proficiencyp = $status->proficiencyp;
                    }
                }   
                $grade7proficiencyp = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 10){
                        $grade7proficiencyp = $status->proficiencyp;
                    }
                }  
            @endphp 
            {{$grade1proficiencyp}}  /  {{$grade7proficiencyp}}    
        </td>
        <td>
            @php
                $grade2maleproficiencyp = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 5){
                        $grade2maleproficiencyp = $status->proficiencypmale;
                    }
                }   
                $grade8maleproficiencyp = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 11){
                        $grade8maleproficiencyp = $status->proficiencypmale;
                    }
                }  
            @endphp 
            {{$grade2maleproficiencyp}}  /  {{$grade8maleproficiencyp}}    
        </td>
        <td>
            @php
                $grade2femaleproficiencyp = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 5){
                        $grade2femaleproficiencyp = $status->proficiencypfemale;
                    }
                }   
                $grade8femaleproficiencyp = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 11){
                        $grade8femaleproficiencyp = $status->proficiencypfemale;
                    }
                }  
            @endphp 
            {{$grade2femaleproficiencyp}}  /  {{$grade8femaleproficiencyp}}  
        </td>
        <td>
            @php
                $grade2proficiencyp = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 5){
                        $grade2proficiencyp = $status->proficiencyp;
                    }
                }   
                $grade8proficiencyp = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 11){
                        $grade8proficiencyp = $status->proficiencyp;
                    }
                }  
            @endphp 
            {{$grade2proficiencyp}}  /  {{$grade8proficiencyp}}    
        </td>
        <td>
            @php
                $grade3maleproficiencyp = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 6){
                        $grade3maleproficiencyp = $status->proficiencypmale;
                    }
                }   
                $grade9maleproficiencyp = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 12){
                        $grade9maleproficiencyp = $status->proficiencypmale;
                    }
                }  
            @endphp 
            {{$grade3maleproficiencyp}}  /  {{$grade9maleproficiencyp}}    
        </td>
        <td>
            @php
                $grade3femaleproficiencyp = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 6){
                        $grade3femaleproficiencyp = $status->proficiencypfemale;
                    }
                }   
                $grade9femaleproficiencyp = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 12){
                        $grade9femaleproficiencyp = $status->proficiencypfemale;
                    }
                }  
            @endphp 
            {{$grade3femaleproficiencyp}}  /  {{$grade9femaleproficiencyp}}  
        </td>
        <td>
            @php
                $grade3proficiencyp = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 6){
                        $grade3proficiencyp = $status->proficiencyp;
                    }
                }   
                $grade9proficiencyp = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 12){
                        $grade9proficiencyp = $status->proficiencyp;
                    }
                }  
            @endphp 
            {{$grade3proficiencyp}}  /  {{$grade9proficiencyp}}  
        </td>
        <td>
            @php
                $grade4maleproficiencyp = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 7){
                        $grade4maleproficiencyp = $status->proficiencypmale;
                    }
                }   
                $grade10maleproficiencyp = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 13){
                        $grade10maleproficiencyp = $status->proficiencypmale;
                    }
                }  
            @endphp 
            {{$grade4maleproficiencyp}}  /  {{$grade10maleproficiencyp}}    
        </td>
        <td>
            @php
                $grade4femaleproficiencyp = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 7){
                        $grade4femaleproficiencyp = $status->proficiencypfemale;
                    }
                }   
                $grade10femaleproficiencyp = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 13){
                        $grade10femaleproficiencyp = $status->proficiencypfemale;
                    }
                }  
            @endphp 
            {{$grade4femaleproficiencyp}}  /  {{$grade10femaleproficiencyp}}  
        </td>
        <td>
            @php
                $grade4proficiencyp = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 7){
                        $grade4proficiencyp = $status->proficiencyp;
                    }
                }   
                $grade10proficiencyp = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 13){
                        $grade10proficiencyp = $status->proficiencyp;
                    }
                }  
            @endphp 
            {{$grade4proficiencyp}}  /  {{$grade10proficiencyp}}  
        </td>
        <td>
            @php
                $grade5maleproficiencyp = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 16){
                        $grade5maleproficiencyp = $status->proficiencypmale;
                    }
                }   
                $grade11maleproficiencyp = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 14){
                        $grade11maleproficiencyp = $status->proficiencypmale;
                    }
                }  
            @endphp 
            {{$grade5maleproficiencyp}}  /  {{$grade11maleproficiencyp}}    
        </td>
        <td>
            @php
                $grade5femaleproficiencyp = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 16){
                        $grade5femaleproficiencyp = $status->proficiencypfemale;
                    }
                }   
                $grade11femaleproficiencyp = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 14){
                        $grade11femaleproficiencyp = $status->proficiencypfemale;
                    }
                }  
            @endphp 
            {{$grade5femaleproficiencyp}}  /  {{$grade11femaleproficiencyp}}  
        </td>
        <td>
            @php
                $grade5proficiencyp = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 16){
                        $grade5proficiencyp = $status->proficiencyp;
                    }
                }   
                $grade11proficiencyp = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 14){
                        $grade11proficiencyp = $status->proficiencyp;
                    }
                }  
            @endphp 
            {{$grade5proficiencyp}}  /  {{$grade11proficiencyp}}  
        </td>
        <td>
            @php
                $grade6maleproficiencyp = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 9){
                        $grade6maleproficiencyp = $status->proficiencypmale;
                    }
                }   
                $grade12maleproficiencyp = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 15){
                        $grade12maleproficiencyp = $status->proficiencypmale;
                    }
                }  
            @endphp 
            {{$grade6maleproficiencyp}}  /  {{$grade12maleproficiencyp}}    
        </td>
        <td>
            @php
                $grade6femaleproficiencyp = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 9){
                        $grade6femaleproficiencyp = $status->proficiencypfemale;
                    }
                }   
                $grade12femaleproficiencyp = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 15){
                        $grade12femaleproficiencyp = $status->proficiencypfemale;
                    }
                }  
            @endphp 
            {{$grade6femaleproficiencyp}}  /  {{$grade12femaleproficiencyp}}  
        </td>
        <td>
            @php
                $grade6proficiencyp = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 9){
                        $grade6proficiencyp = $status->proficiencyp;
                    }
                }   
                $grade12proficiencyp = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 15){
                        $grade12proficiencyp = $status->proficiencyp;
                    }
                }  
            @endphp 
            {{$grade6proficiencyp}}  /  {{$grade12proficiencyp}}  
        </td>
        <td>
            @php
                $proficiencypmaletotal = $grade1maleproficiencyp+$grade2maleproficiencyp+$grade3maleproficiencyp+$grade4maleproficiencyp+$grade5maleproficiencyp+$grade6maleproficiencyp+$grade7maleproficiencyp+$grade8maleproficiencyp+$grade9maleproficiencyp+$grade10maleproficiencyp+$grade11maleproficiencyp+$grade12maleproficiencyp;   
            @endphp
            {{$proficiencypmaletotal}}
        </td>
        <td>
            @php
                $proficiencypfemaletotal = $grade1femaleproficiencyp+$grade2femaleproficiencyp+$grade3femaleproficiencyp+$grade4femaleproficiencyp+$grade5femaleproficiencyp+$grade6femaleproficiencyp+$grade7femaleproficiencyp+$grade8femaleproficiencyp+$grade9femaleproficiencyp+$grade10femaleproficiencyp+$grade11femaleproficiencyp+$grade12femaleproficiencyp;   
            @endphp
            {{$proficiencypfemaletotal}}
        </td>
        <td>
            @php
                $proficiencyptotal = $grade1proficiencyp+$grade2proficiencyp+$grade3proficiencyp+$grade4proficiencyp+$grade5proficiencyp+$grade6proficiencyp+$grade7proficiencyp+$grade8proficiencyp+$grade9proficiencyp+$grade10proficiencyp+$grade11proficiencyp+$grade12proficiencyp;   
            @endphp
            {{$proficiencyptotal}}
        </td>
    </tr>
    <tr>
        <th>Outstanding<br>(90% and above)</th>
        <td>
            @php
                $grade1maleproficiencya = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 1){
                        $grade1maleproficiencya = $status->proficiencyamale;
                    }
                }   
                $grade7maleproficiencya = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 10){
                        $grade7maleproficiencya = $status->proficiencyamale;
                    }
                }  
            @endphp 
            {{$grade1maleproficiencya}}  /  {{$grade7maleproficiencya}}               
        </td>
        <td>
            @php
                $grade1femaleproficiencya = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 1){
                        $grade1femaleproficiencya = $status->proficiencyafemale;
                    }
                }   
                $grade7femaleproficiencya = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 10){
                        $grade7femaleproficiencya = $status->proficiencyafemale;
                    }
                }  
            @endphp
            {{$grade1femaleproficiencya}}  /  {{$grade7femaleproficiencya}}
        </td>
        <td>
            @php
                $grade1proficiencya = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 1){
                        $grade1proficiencya = $status->proficiencya;
                    }
                }   
                $grade7proficiencya = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 10){
                        $grade7proficiencya = $status->proficiencya;
                    }
                }  
            @endphp 
            {{$grade1proficiencya}}  /  {{$grade7proficiencya}}    a
        </td>
        <td>
            @php
                $grade2maleproficiencya = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 5){
                        $grade2maleproficiencya = $status->proficiencyamale;
                    }
                }   
                $grade8maleproficiencya = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 11){
                        $grade8maleproficiencya = $status->proficiencyamale;
                    }
                }  
            @endphp 
            {{$grade2maleproficiencya}}  /  {{$grade8maleproficiencya}}    
        </td>
        <td>
            @php
                $grade2femaleproficiencya = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 5){
                        $grade2femaleproficiencya = $status->proficiencyafemale;
                    }
                }   
                $grade8femaleproficiencya = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 11){
                        $grade8femaleproficiencya = $status->proficiencyafemale;
                    }
                }  
            @endphp 
            {{$grade2femaleproficiencya}}  /  {{$grade8femaleproficiencya}}  
        </td>
        <td>
            @php
                $grade2proficiencya = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 5){
                        $grade2proficiencya = $status->proficiencya;
                    }
                }   
                $grade8proficiencya = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 11){
                        $grade8proficiencya = $status->proficiencya;
                    }
                }  
            @endphp 
            {{$grade2proficiencya}}  /  {{$grade8proficiencya}}    
        </td>
        <td>
            @php
                $grade3maleproficiencya = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 6){
                        $grade3maleproficiencya = $status->proficiencyamale;
                    }
                }   
                $grade9maleproficiencya = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 12){
                        $grade9maleproficiencya = $status->proficiencyamale;
                    }
                }  
            @endphp 
            {{$grade3maleproficiencya}}  /  {{$grade9maleproficiencya}}    
        </td>
        <td>
            @php
                $grade3femaleproficiencya = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 6){
                        $grade3femaleproficiencya = $status->proficiencyafemale;
                    }
                }   
                $grade9femaleproficiencya = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 12){
                        $grade9femaleproficiencya = $status->proficiencyafemale;
                    }
                }  
            @endphp 
            {{$grade3femaleproficiencya}}  /  {{$grade9femaleproficiencya}}  
        </td>
        <td>
            @php
                $grade3proficiencya = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 6){
                        $grade3proficiencya = $status->proficiencya;
                    }
                }   
                $grade9proficiencya = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 12){
                        $grade9proficiencya = $status->proficiencya;
                    }
                }  
            @endphp 
            {{$grade3proficiencya}}  /  {{$grade9proficiencya}}  
        </td>
        <td>
            @php
                $grade4maleproficiencya = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 7){
                        $grade4maleproficiencya = $status->proficiencyamale;
                    }
                }   
                $grade10maleproficiencya = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 13){
                        $grade10maleproficiencya = $status->proficiencyamale;
                    }
                }  
            @endphp 
            {{$grade4maleproficiencya}}  /  {{$grade10maleproficiencya}}    
        </td>
        <td>
            @php
                $grade4femaleproficiencya = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 7){
                        $grade4femaleproficiencya = $status->proficiencyafemale;
                    }
                }   
                $grade10femaleproficiencya = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 13){
                        $grade10femaleproficiencya = $status->proficiencyafemale;
                    }
                }  
            @endphp 
            {{$grade4femaleproficiencya}}  /  {{$grade10femaleproficiencya}}  
        </td>
        <td>
            @php
                $grade4proficiencya = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 7){
                        $grade4proficiencya = $status->proficiencya;
                    }
                }   
                $grade10proficiencya = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 13){
                        $grade10proficiencya = $status->proficiencya;
                    }
                }  
            @endphp 
            {{$grade4proficiencya}}  /  {{$grade10proficiencya}}  
        </td>
        <td>
            @php
                $grade5maleproficiencya = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 16){
                        $grade5maleproficiencya = $status->proficiencyamale;
                    }
                }   
                $grade11maleproficiencya = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 14){
                        $grade11maleproficiencya = $status->proficiencyamale;
                    }
                }  
            @endphp 
            {{$grade5maleproficiencya}}  /  {{$grade11maleproficiencya}}    
        </td>
        <td>
            @php
                $grade5femaleproficiencya = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 16){
                        $grade5femaleproficiencya = $status->proficiencyafemale;
                    }
                }   
                $grade11femaleproficiencya = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 14){
                        $grade11femaleproficiencya = $status->proficiencyafemale;
                    }
                }  
            @endphp 
            {{$grade5femaleproficiencya}}  /  {{$grade11femaleproficiencya}}  
        </td>
        <td>
            @php
                $grade5proficiencya = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 16){
                        $grade5proficiencya = $status->proficiencya;
                    }
                }   
                $grade11proficiencya = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 14){
                        $grade11proficiencya = $status->proficiencya;
                    }
                }  
            @endphp 
            {{$grade5proficiencya}}  /  {{$grade11proficiencya}}  
        </td>
        <td>
            @php
                $grade6maleproficiencya = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 9){
                        $grade6maleproficiencya = $status->proficiencyamale;
                    }
                }   
                $grade12maleproficiencya = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 15){
                        $grade12maleproficiencya = $status->proficiencyamale;
                    }
                }  
            @endphp 
            {{$grade6maleproficiencya}}  /  {{$grade12maleproficiencya}}    
        </td>
        <td>
            @php
                $grade6femaleproficiencya = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 9){
                        $grade6femaleproficiencya = $status->proficiencyafemale;
                    }
                }   
                $grade12femaleproficiencya = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 15){
                        $grade12femaleproficiencya = $status->proficiencyafemale;
                    }
                }  
            @endphp 
            {{$grade6femaleproficiencya}}  /  {{$grade12femaleproficiencya}}  
        </td>
        <td>
            @php
                $grade6proficiencya = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 9){
                        $grade6proficiencya = $status->proficiencya;
                    }
                }   
                $grade12proficiencya = 0;
                foreach($studentseachlevel as $status){
                    if($status->gradelevelinfo->id == 15){
                        $grade12proficiencya = $status->proficiencya;
                    }
                }  
            @endphp 
            {{$grade6proficiencya}}  /  {{$grade12proficiencya}}  
        </td>
        <td>
            @php
                $proficiencyamaletotal = $grade1maleproficiencya+$grade2maleproficiencya+$grade3maleproficiencya+$grade4maleproficiencya+$grade5maleproficiencya+$grade6maleproficiencya+$grade7maleproficiencya+$grade8maleproficiencya+$grade9maleproficiencya+$grade10maleproficiencya+$grade11maleproficiencya+$grade12maleproficiencya;   
            @endphp
            {{$proficiencyamaletotal}}
        </td>
        <td>
            @php
                $proficiencyafemaletotal = $grade1femaleproficiencya+$grade2femaleproficiencya+$grade3femaleproficiencya+$grade4femaleproficiencya+$grade5femaleproficiencya+$grade6femaleproficiencya+$grade7femaleproficiencya+$grade8femaleproficiencya+$grade9femaleproficiencya+$grade10femaleproficiencya+$grade11femaleproficiencya+$grade12femaleproficiencya;   
            @endphp
            {{$proficiencyafemaletotal}}
        </td>
        <td>
            @php
                $proficiencyatotal = $grade1proficiencya+$grade2proficiencya+$grade3proficiencya+$grade4proficiencya+$grade5proficiencya+$grade6proficiencya+$grade7proficiencya+$grade8proficiencya+$grade9proficiencya+$grade10proficiencya+$grade11proficiencya+$grade12proficiencya;   
            @endphp
            {{$proficiencyatotal}}
        </td>
    </tr>
    <tr>
        <th>TOTAL</th>
        <td>{{$grade1male+
            $grade1maleirregular+
            $grade1maleretained+
            $grade1maleproficiencyb+
            $grade1maleproficiencyd+
            $grade1maleproficiencyap+
            $grade1maleproficiencyp+
            $grade1maleproficiencya}}
            /
            {{$grade7male+
            $grade7maleirregular+
            $grade7maleretained+
            $grade7maleproficiencyb+
            $grade7maleproficiencyd+
            $grade7maleproficiencyap+
            $grade7maleproficiencyp+
            $grade7maleproficiencya}}
        </td>
        <td>{{$grade1female+
            $grade1femaleirregular+
            $grade1femaleretained+
            $grade1femaleproficiencyb+
            $grade1femaleproficiencyd+
            $grade1femaleproficiencyap+
            $grade1femaleproficiencyp+
            $grade1femaleproficiencya}}
            /
            {{$grade7female+
            $grade7femaleirregular+
            $grade7femaleretained+
            $grade7femaleproficiencyb+
            $grade7femaleproficiencyd+
            $grade7femaleproficiencyap+
            $grade7femaleproficiencyp+
            $grade7femaleproficiencya}}
        </td>
        <td>
            {{$grade1+
            $grade1irregular+
            $grade1retained+
            $grade1proficiencyb+
            $grade1proficiencyd+
            $grade1proficiencyap+
            $grade1proficiencyp+
            $grade1proficiencya}}

            /
            {{$grade7+
            $grade7irregular+
            $grade7retained+
            $grade7proficiencyb+
            $grade7proficiencyd+
            $grade7proficiencyap+
            $grade7proficiencyp+
            $grade7proficiencya}}
        </td>
        <td>{{$grade2male+
            $grade2maleirregular+
            $grade2maleretained+
            $grade2maleproficiencyb+
            $grade2maleproficiencyd+
            $grade2maleproficiencyap+
            $grade2maleproficiencyp+
            $grade2maleproficiencya}}
            /
            {{$grade8male+
            $grade8maleirregular+
            $grade8maleretained+
            $grade8maleproficiencyb+
            $grade8maleproficiencyd+
            $grade8maleproficiencyap+
            $grade8maleproficiencyp+
            $grade8maleproficiencya}}
        </td>
        <td>{{$grade2female+
            $grade2femaleirregular+
            $grade2femaleretained+
            $grade2femaleproficiencyb+
            $grade2femaleproficiencyd+
            $grade2femaleproficiencyap+
            $grade2femaleproficiencyp+
            $grade2femaleproficiencya}}
            /
            {{$grade8female+
            $grade8femaleirregular+
            $grade8femaleretained+
            $grade8femaleproficiencyb+
            $grade8femaleproficiencyd+
            $grade8femaleproficiencyap+
            $grade8femaleproficiencyp+
            $grade8femaleproficiencya}}
        </td>
        <td>
            {{$grade2+
            $grade2irregular+
            $grade2retained+
            $grade2proficiencyb+
            $grade2proficiencyd+
            $grade2proficiencyap+
            $grade2proficiencyp+
            $grade2proficiencya}}

            /
            {{$grade8+
            $grade8irregular+
            $grade8retained+
            $grade8proficiencyb+
            $grade8proficiencyd+
            $grade8proficiencyap+
            $grade8proficiencyp+
            $grade8proficiencya}}
        </td>
        <td>{{$grade3male+
            $grade3maleirregular+
            $grade3maleretained+
            $grade3maleproficiencyb+
            $grade3maleproficiencyd+
            $grade3maleproficiencyap+
            $grade3maleproficiencyp+
            $grade3maleproficiencya}}
            /
            {{$grade9male+
            $grade9maleirregular+
            $grade9maleretained+
            $grade9maleproficiencyb+
            $grade9maleproficiencyd+
            $grade9maleproficiencyap+
            $grade9maleproficiencyp+
            $grade9maleproficiencya}}
        </td>
        <td>{{$grade3female+
            $grade3femaleirregular+
            $grade3femaleretained+
            $grade3femaleproficiencyb+
            $grade3femaleproficiencyd+
            $grade3femaleproficiencyap+
            $grade3femaleproficiencyp+
            $grade3femaleproficiencya}}
            /
            {{$grade9female+
            $grade9femaleirregular+
            $grade9femaleretained+
            $grade9femaleproficiencyb+
            $grade9femaleproficiencyd+
            $grade9femaleproficiencyap+
            $grade9femaleproficiencyp+
            $grade9femaleproficiencya}}
        </td>
        <td>
            {{$grade3+
            $grade3irregular+
            $grade3retained+
            $grade3proficiencyb+
            $grade3proficiencyd+
            $grade3proficiencyap+
            $grade3proficiencyp+
            $grade3proficiencya}}

            /
            {{$grade9+
            $grade9irregular+
            $grade9retained+
            $grade9proficiencyb+
            $grade9proficiencyd+
            $grade9proficiencyap+
            $grade9proficiencyp+
            $grade9proficiencya}}
        </td>
        <td>{{$grade4male+
            $grade4maleirregular+
            $grade4maleretained+
            $grade4maleproficiencyb+
            $grade4maleproficiencyd+
            $grade4maleproficiencyap+
            $grade4maleproficiencyp+
            $grade4maleproficiencya}}
            /
            {{$grade10male+
            $grade10maleirregular+
            $grade10maleretained+
            $grade10maleproficiencyb+
            $grade10maleproficiencyd+
            $grade10maleproficiencyap+
            $grade10maleproficiencyp+
            $grade10maleproficiencya}}
        </td>
        <td>{{$grade4female+
            $grade4femaleirregular+
            $grade4femaleretained+
            $grade4femaleproficiencyb+
            $grade4femaleproficiencyd+
            $grade4femaleproficiencyap+
            $grade4femaleproficiencyp+
            $grade4femaleproficiencya}}
            /
            {{$grade10female+
            $grade10femaleirregular+
            $grade10femaleretained+
            $grade10femaleproficiencyb+
            $grade10femaleproficiencyd+
            $grade10femaleproficiencyap+
            $grade10femaleproficiencyp+
            $grade10femaleproficiencya}}
        </td>
        <td>
            {{$grade4+
            $grade4irregular+
            $grade4retained+
            $grade4proficiencyb+
            $grade4proficiencyd+
            $grade4proficiencyap+
            $grade4proficiencyp+
            $grade4proficiencya}}

            /
            {{$grade10+
            $grade10irregular+
            $grade10retained+
            $grade10proficiencyb+
            $grade10proficiencyd+
            $grade10proficiencyap+
            $grade10proficiencyp+
            $grade10proficiencya}}
        </td>
        <td>{{$grade5male+
            $grade5maleirregular+
            $grade5maleretained+
            $grade5maleproficiencyb+
            $grade5maleproficiencyd+
            $grade5maleproficiencyap+
            $grade5maleproficiencyp+
            $grade5maleproficiencya}}
            /
            {{$grade11male+
            $grade11maleirregular+
            $grade11maleretained+
            $grade11maleproficiencyb+
            $grade11maleproficiencyd+
            $grade11maleproficiencyap+
            $grade11maleproficiencyp+
            $grade11maleproficiencya}}
        </td>
        <td>{{$grade5female+
            $grade5femaleirregular+
            $grade5femaleretained+
            $grade5femaleproficiencyb+
            $grade5femaleproficiencyd+
            $grade5femaleproficiencyap+
            $grade5femaleproficiencyp+
            $grade5femaleproficiencya}}
            /
            {{$grade11female+
            $grade11femaleirregular+
            $grade11femaleretained+
            $grade11femaleproficiencyb+
            $grade11femaleproficiencyd+
            $grade11femaleproficiencyap+
            $grade11femaleproficiencyp+
            $grade11femaleproficiencya}}
        </td>
        <td>
            {{$grade5+
            $grade5irregular+
            $grade5retained+
            $grade5proficiencyb+
            $grade5proficiencyd+
            $grade5proficiencyap+
            $grade5proficiencyp+
            $grade5proficiencya}}

            /
            {{$grade11+
            $grade11irregular+
            $grade11retained+
            $grade11proficiencyb+
            $grade11proficiencyd+
            $grade11proficiencyap+
            $grade11proficiencyp+
            $grade11proficiencya}}
        </td>
        <td>{{$grade6male+
            $grade6maleirregular+
            $grade6maleretained+
            $grade6maleproficiencyb+
            $grade6maleproficiencyd+
            $grade6maleproficiencyap+
            $grade6maleproficiencyp+
            $grade6maleproficiencya}}
            /
            {{$grade12male+
            $grade12maleirregular+
            $grade12maleretained+
            $grade12maleproficiencyb+
            $grade12maleproficiencyd+
            $grade12maleproficiencyap+
            $grade12maleproficiencyp+
            $grade12maleproficiencya}}
        </td>
        <td>{{$grade6female+
            $grade6femaleirregular+
            $grade6femaleretained+
            $grade6femaleproficiencyb+
            $grade6femaleproficiencyd+
            $grade6femaleproficiencyap+
            $grade6femaleproficiencyp+
            $grade6femaleproficiencya}}
            /
            {{$grade12female+
            $grade12femaleirregular+
            $grade12femaleretained+
            $grade12femaleproficiencyb+
            $grade12femaleproficiencyd+
            $grade12femaleproficiencyap+
            $grade12femaleproficiencyp+
            $grade12femaleproficiencya}}
        </td>
        <td>
            {{$grade6+
            $grade6irregular+
            $grade6retained+
            $grade6proficiencyb+
            $grade6proficiencyd+
            $grade6proficiencyap+
            $grade6proficiencyp+
            $grade6proficiencya}}

            /
            {{$grade12+
            $grade12irregular+
            $grade12retained+
            $grade12proficiencyb+
            $grade12proficiencyd+
            $grade12proficiencyap+
            $grade12proficiencyp+
            $grade12proficiencya}}
        </td>
        <td>{{$promotedmaletotal+
            $promotedmaletotalirregular+
            $retainedmaletotal+
            $proficiencybmaletotal+
            $proficiencydmaletotal+
            $proficiencyapmaletotal+
            $proficiencypmaletotal+
            $proficiencyamaletotal}}
        </td>
        <td>{{$promotedfemaletotal+
            $promotedfemaletotalirregular+
            $retainedfemaletotal+
            $proficiencybfemaletotal+
            $proficiencydfemaletotal+
            $proficiencyapfemaletotal+
            $proficiencypfemaletotal+
            $proficiencyafemaletotal}}
        </td>
        <td>
        {{$promotedtotal+
        $promotedtotalirregular+
        $retainedtotal+
        $proficiencybtotal+
        $proficiencydtotal+
        $proficiencyaptotal+
        $proficiencyptotal+
        $proficiencyatotal}}
        </td>
    </tr>
</table> 
<br>                           
@if(count($signatoriesv2)>0)
    @foreach($signatoriesv2 as $signatories)
        <table style="width: 100%; font-size: 8.5px; table-layout: fixed;">
            <tr>
                @for($x = 0; $x < 4; $x++)
                    @if(isset($signatories[$x]))
                    <td style="width: 10%;">{{$signatories[$x]->title}}</td>
                    <td style="text-align: center; border-bottom: 1px solid black;">{{$signatories[$x]->name}}</td>
                    <td style="width: 5%;"></td>
                    @else
                    <td></td>
                    <td></td>
                    <td></td>
                    @endif
                @endfor
            </tr>
            <tr>
                @for($x = 0; $x < 4; $x++)
                    @if(isset($signatories[$x]))
                    <td style="width: 10%;"></td>
                    <td style="text-align: center;">{{$signatories[$x]->description}}</td>
                    <td style="width: 5%;"></td>
                    @else
                    <td></td>
                    <td></td>
                    <td></td>
                    @endif
                @endfor
            </tr>
            <tr>
                @for($x = 0; $x < 4; $x++)
                    @if(isset($signatories[$x]))
                    <td style="width: 10%;">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td style="width: 5%;">&nbsp;</td>
                    @else
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    @endif
                @endfor
            </tr>
        </table>
    @endforeach
@endif
@if(count($signatories) == 0)
    <table style="width: 100%; font-size: 13px;">
        <tr>
            <td style="width: 16%;">Prepared and Submitted by: </td>
            <td style="width: 20%;border-bottom: 1px solid black; text-align: center;">{{DB::table('schoolinfo')->first()->authorized}}</td>
            <td style="width: 16%; text-align: right;">Reviewed & Validated by:</td>
            <td style="text-align: center; border-bottom: 1px solid black;"></td>
            <td style="width: 8%; text-align: right;">Noted by:</td>
            <td style="width: 20%; border-bottom: 1px solid black; text-align: center;"></td>
        </tr>
        <tr>
            <td></td>
            <td style="text-align: center; vertical-align: top;">SCHOOL HEAD</td>
            <td></td>
            <td style="text-align: center; vertical-align: top;">DIVISION REPRESENTATIVE</td>
            <td></td>
            <td style="text-align: center; vertical-align: top;">SCHOOLS DIVISION SUPERINTENDENT</td>
        </tr>
    </table>
@else
    <table style="width: 100%; font-size: 13px;">
        <tr>
            <td style="width: 16%;">@if(isset($signatories[0])){{$signatories[0]->title}}: @else Prepared and Submitted by: @endif</td>
            <td style="width: 20%;border-bottom: 1px solid black; text-align: center;">@if(isset($signatories[0])){{$signatories[0]->name}} @endif</td>
            <td style="width: 16%; text-align: right;">@if(isset($signatories[1])){{$signatories[1]->title}}: @else Reviewed & Validated by: @endif</td>
            <td style="text-align: center; border-bottom: 1px solid black;">@if(isset($signatories[1])){{$signatories[1]->name}} @endif</td>
            <td style="width: 8%; text-align: right;">@if(isset($signatories[2])){{$signatories[2]->title}}: @else Noted by: @endif</td>
            <td style="width: 20%; border-bottom: 1px solid black; text-align: center;">@if(isset($signatories[2])){{$signatories[2]->name}} @endif</td>
        </tr>
        <tr>
            <td></td>
            <td style="text-align: center; vertical-align: top;">@if(isset($signatories[0])){{$signatories[0]->description}} @else SCHOOL HEAD @endif</td>
            <td></td>
            <td style="text-align: center; vertical-align: top;">@if(isset($signatories[1])){{$signatories[1]->description}} @else DIVISION REPRESENTATIVE @endif</td>
            <td></td>
            <td style="text-align: center; vertical-align: top;">@if(isset($signatories[2])){{$signatories[2]->description}} @else SCHOOLS DIVISION SUPERINTENDENT @endif</td>
        </tr>
    </table>
@endif
{{-- <table style="table-layout:fixed;width:100%;font-size:11px;">
    <tbody>
        <tr>
            <th style="border: hidden !important;width:15%;padding:5px"><small>Pepared and Submitted:</small></th>
            <td style="border-top: hidden; border-bottom: 1px solid black;border-left:hidden; border-right: hidden; !important;padding:5px; text-transform: uppercase;">
                <center>
                    <small>
                        {{$schoolhead}}
                    </small>
                </center>
            </td>
            <th style="padding:5px;border:hidden;text-align:right"><small>Reviewed & Validated by:</small></th>
            <td style="padding:5px;border-bottom: 1px solid black;border-top:hidden;text-align:center; text-transform: uppercase;"><small>{{$divrep}}</small></td>
            <th style="padding:5px;border:hidden;text-align:right;width:10%"><small>Noted by:</small></th>
            <td style="padding:5px;border-right:hidden;border-bottom: 1px solid black;border-top:hidden;text-align:center; text-transform: uppercase;">{{$divsup}}</td>
        </tr>
        <tr>
            <th style="border:hidden;padding:5px;"></th>
            <td style="border-bottom:hidden;padding:5px;"><center><sup>SCHOOL HEAD</sup></center></td>
            <td style="border:hidden;padding:5px;"></td>
            <td style="border-bottom:hidden;padding:5px;"><center><sup>DIVISION REPRESENTATIVE</sup></center></td>
            <td style="border-bottom:hidden;padding:5px;"></td>
            <td style="border-bottom:hidden;border-right:hidden;padding:5px;"><center><sup>SCHOOLS DIVISION SUPERINTENDENT</sup></center></td>
        </tr>
    </tbody>
</table> --}}
<div style="width:100%;font-size:11px;margin:0px;">
    <p><strong>GUIDELINES:</strong></p>
    <ol>
        <li>After receiving and validating the report for Promotion submitted by the class adviser, the School Head shall compute the grade level total and school total.</li>
        <li>This report together with the copy of Report for Promotion submitted by the class adviser shall be fowarded to the Division Office by the end of the school year.</li>
        <li>The Report on Promotion per grade level is reflected in the End of School Year Report of GESP/GSSP.</li>
        <li>Protocols of validation & submission is unde the discretion of the Schools Division Superintendent.</li>
    </ol>
</div>
    </body>
</html>