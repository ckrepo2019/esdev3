<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deportment Record PDF</title>
    <style>

        table {
            border-collapse: collapse;
            margin-bottom: 1rem;
            background-color: transparent;
            font-size:11px ;
        }
        
        .table thead th {
            vertical-align: bottom;
        }
        
        .table td, .table th {
            padding-left: 10px;
            padding-right: 10px;
            padding-top: 2px;
            padding-bottom: 2px;
            font-size: 10px;
        }

        h5{
            font-size: 13px;
            padding: 0px;
            margin: 0px;

        }

        h4{
            font-size: 15px;
            padding: 0px;
            margin: 0px;

        }

        h3{
            font-size: 17px;
            padding: 0px;
            margin: 0px;

        }

        p{
            
            font-size: 12px;
            padding: 0px;
            margin: 0px;
        }

  

        h5, h4, h3 {

            line-height: 1.3;

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


        .mb-0{
            margin-bottom: 0;
        }

        .border-bottom{
            border-bottom:1px solid black;
        }

        .mb-1, .my-1 {
            margin-bottom: .25rem!important;
        }
        
        .ml-1 {

            margin-left: 20px!important;
        }

        .mr-1 {

            margin-right: 20px!important;
        }

        body{
            font-family: Calibri, sans-serif;
        }
        
        .align-middle{
            vertical-align: middle !important;    
        }

        .text-red{
            color: red;
            border: solid 1px black;
        }

        .float-child-left{
            width: 90%;
            float: left;
        } 
        
        .float-child-right{
            width: 10%;
            float: right;

        }  

        .rating_ul p{

            font-size: 11px;
        }

        .section{

            float: right; 
           

        }

        .quarter{

            float: left; 
            padding: 0px;
        }

        .quarter p, .section p{

            padding: 0px;
            margin: 0px;
            font-weight: bold;
            font-size: 12px;
        }

        .base_rating{
         
            list-style: none;
        }

        .li_p{

            margin-top: 3px;
        }

        .values{

            text-align: center; 
            line-height: 0.5; 
            float: left;
        }

        .signatory td p{

            margin: 2px;
        }


        .page_break { page-break-before: always; }

        @page { size: 11in 8.5in; margin: .25in;  }
        
    </style>
</head>
<body>

    <div style="height: 180px">
        <table style="width: 100%">
            <tr>
                <td width="30%" style="padding-left: 200px">
                    <div>
                        <img src="{{base_path()}}/public/{{$schoolinfo->picurl}}" alt="school" width="120px">
                    </div>
                </td>

                <td width="70%" style="padding-right: 300px">
                    <div style="text-align: center">
                        <h3>{{$schoolinfo->schoolname}}</h3>
                        <p style="font-size: 12px">{{$schoolinfo->address}} </p>

                        <br>
                        <h5>BEHAVIOR RATING SHEET </h5>
                        <h5>{{$sections->sectionname}} - {{$gradelvl->levelname}}</h5>
                        @if($quarter_ID == 1)
                            <h5>First Quarter</h5>
                        @elseif($quarter_ID == 2)
                            <h5>Second Quarter</h5>
                        @elseif($quarter_ID == 3)
                            <h5>Third Quarter</h5>
                        @elseif($quarter_ID == 4)
                            <h5>Fourth Quarter</h5>
                        @endif

                        <h5>Christian Living Education 
                        @php 

                            if($gradelvl->acadprogid == 2){

                                echo $gradelvl->levelname;

                            }else{

                

                                echo str_replace('GRADE ', '', $gradelvl->levelname);
                            }

                        @endphp

                        </h5>
                        <h5>SY {{$schoolyear->sydesc}}</h5>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <table>
        <tr>
            <td>
                <h5 style="margin: 0px; padding: 0px">Bases for Rating</h5>
            </td>

        </tr>
        <tr>
            @foreach($values as $value)
                <td>
                    <div style="float: left; padding-right: 50px; white-space: normal; ">

                        @if(count($items) != null)
                            @php
                                $count = 0;
                                foreach($items as $item){
                                    if($value->id == $item->values_setupID){
                                        $count++;
                                        
                                    }
                                }

                            @endphp

                            @if($count != 0)

                                <h5 style="margin-top: 5px;" class="title">{{$value->value_desc}}</h5>
        
                            @endif

                        @endif
                        

                            @foreach($items as $item)

                                @if($value->id == $item->values_setupID)
                                    @if($item->value_item_desc != null)
                                        
                                        <p class="li_p">{{$item->value_item_abbr}} - {{$item->value_item_desc}}</p>
                                    @endif
                                @endif

                            @endforeach

                            

                    </div>

                </td>
            @endforeach
            
        </tr>
    </table>



    <!-- Page Break 1-->
    <div class="page_break"></div>
    


    <div style="height: 120px">
        <table style="width: 100%">
            <tr>
                <td width="30%" style="padding-left: 200px">
                    <div>
                        <img src="{{base_path()}}/public/{{$schoolinfo->picurl}}" alt="school" width="120px">
                    </div>
                </td>

                <td width="70%" style="padding-right: 300px">
                    <div style="text-align: center">
                        <h3>{{$schoolinfo->schoolname}}</h3>
                        <p style="font-size: 12px">{{$schoolinfo->address}} </p>
                        <br>
                        <h4>DEPORTMENT</h4>
                        <h5>{{$schoolyear->sydesc}}</h5>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <br>
    <div style="height: 25px; margin-bottom: 10px;">
        
        <div class="quarter">
            <p>{{$sections->sectionname}} - {{$gradelvl->levelname}}</p>
            @if($quarter_ID == 1)
                <p>First Quarter</p>
            @elseif($quarter_ID == 2)
                <p>Second Quarter</p>
            @elseif($quarter_ID == 3)
                <p>Third Quarter</p>
            @elseif($quarter_ID == 4)
                <p>Fourth Quarter</p>
            @endif
        </div>

        
        
        <div class="section">
            <p>Christian Living Education 
            @php 

                if($gradelvl->acadprogid == 2){

                    echo $gradelvl->levelname;

                }else{

     

                    echo str_replace('GRADE ', '', $gradelvl->levelname);
                }

            @endphp
            </p>

        </div>

    </div>

    <div>    
    <table width="100%" class="table table-bordered">

        <tr>
            <th>#</th>
            <th>Learner's Name</th>
            
            <?php $allCol = 0; ?>
            @foreach($values as $value)
                @if(count($items) != null){
                    @php
                        $count = 0;
                        foreach($items as $item){
                            if($value->id == $item->values_setupID){
                                $count++;
                                $allCol++;
                                
                            }
                        }

                    @endphp

                    @if($count != 0)

                        <th class="text-center" colspan="<?php echo $count ?>">{{$value->value_desc}}</th>
   
                    @endif

                @endif
            @endforeach

            <th></th>
            <th class="text-center">Initial</th>
            <th class="text-center">Quarterly</th>
        </tr>
        
        <tr>

            <th colspan="2"></th>
            @foreach($values as $value){
                
                @foreach($items as $item){
                    @if($value->id == $item->values_setupID){
                        <th style="text-align: center;" >{{$item->value_item_abbr}}</th>
                    @endif
                @endforeach
            @endforeach

            <th>Total</th>
            <th>Initial</th>
            <th>Final</th>


        </tr>

        <tr>
            <th colspan="2" style="text-align: center;">Highest Possible Score</th>
     
            @php 
                $sum = 0;  
                $col_count = 1;
                $col_val;
            @endphp
            @foreach($values as $value){
                
                @foreach($items as $item){
                    
                    @if($value->id == $item->values_setupID){
                        @php  
                            $col = $col_count++;
                            $col_val = 'col'.$col;
                        @endphp
                        
                        <th style="text-align: center; white-space: nowrap; overflow: hidden;" >{{$hps->$col_val}}</div></th>

                        @php $sum+= $hps->$col_val; @endphp

                    @endif

                @endforeach
                
            @endforeach

        
            <th><?php echo $sum; ?></th>
            <th>100</th>
            <th>100</th>


        </tr>
        <tr>

            <td colspan="<?php echo $allCol+5 ; ?>" style="text-align: left;">Male</td>

        </tr>

        <tbody>
            
            <?php $count = 1; ?>
            @foreach($data as $student){

                @if($student->gender == 'MALE'){
					<tr>
                        @php $row_count = $count++; @endphp
                        
                        <td width="2%"><?php echo $row_count; ?></td>
                        <td width="20%">{{$student->lastname}}, {{$student->firstname}} 
                            @if($student->middlename == '-' || $student->middlename == null) 
                                
                                <?php echo " "; ?>
                            @else

                                {{substr($student->middlename, 0, 1)}}. 


                            @endif
                        </td>

                        @for ($i=1; $i < count($items_search)+1; $i++) { 
                    
                            <?php $col_var = 'col'.$i; ?>

                            @if($student->$col_var == 0)

                                <td width="<?php 45/count($values) ?>%"  style="text-align: center; padding:0px" ></td>

                            @else

                                <td width="<?php 45/count($values) ?>%"  style="text-align: center; padding:0px" >{{$student->$col_var}}</td>

                            @endif

                        @endfor

                        <td width="10%" class="text-center">{{$student->total}}</td>
                        <td width="10%" class="text-center">{{$student->initial}}</td>
                        <td width="13%" class="text-center">{{$student->final}}</td>

					</tr>
                @endif


            @endforeach

            
        </tbody>
        
    </table>


    <br>


    <table class="signatory" width="100%" style="font-size: 12px;">
        @php 
        $signatoryCount = collect($signatory)->count();
        @endphp
        
            @if($signatoryCount <= 3)

                @foreach($signatory as $sign)
                    <td width="33%" class="text-left" style="padding-bottom: 30px">

                        <p style="font-weight: bold">{{$sign->type}}</p>
                        <div style="height: 10px"></div>
                        <p class="text-center">{{$sign->name}}</p>
                        <div style="height: 1px; background-color: black; margin-right: 20px; margin-left: 20px;"></div>
                        <p class="text-center">{{$sign->position}}</p>

                    </td>
                @endforeach
               
            @else

                @php 
                
                    $splitArray = array_chunk(collect($signatory)->toArray(), 3)

                @endphp

                @foreach($splitArray as $array)
                    <tr>
                        @foreach($array as $sign)
                        
                            <td width="33%" class="text-left" style="padding-bottom: 30px">

                                <p style="font-weight: bold">{{$sign->type}}:</p>
                                <div style="height: 10px"></div>
                                <p class="text-center">{{$sign->name}}</p>
                                <div style="height: 1px; background-color: black; margin-right: 20px; margin-left: 20px;"></div>
                                <p class="text-center">{{$sign->position}}</p>

                            </td>
                        
                        @endforeach
                    </tr>
           

                @endforeach

                
            @endif
            
    </table>

    <!-- Page Break 2-->
    <div class="page_break"></div>

    
    <div style="height: 120px">
        <table style="width: 100%">
            <tr>
                <td width="30%" style="padding-left: 200px">
                    <div>
                        <img src="{{base_path()}}/public/{{$schoolinfo->picurl}}" alt="school" width="120px">
                    </div>
                </td>

                <td width="70%" style="padding-right: 300px">
                    <div style="text-align: center">
                        <h3>{{$schoolinfo->schoolname}}</h3>
                        <p style="font-size: 12px">{{$schoolinfo->address}} </p>
                        <br>
                        <h4>DEPORTMENT</h4>
                        <h5>SY {{$schoolyear->sydesc}}</h5>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <br>
    <div style="height: 25px; margin-bottom: 10px;">
        
        <div class="quarter">
            <p>{{$sections->sectionname}} - {{$gradelvl->levelname}}</p>
            @if($quarter_ID == 1)
                <p>First Quarter</p>
            @elseif($quarter_ID == 2)
                <p>Second Quarter</p>
            @elseif($quarter_ID == 3)
                <p>Third Quarter</p>
            @elseif($quarter_ID == 4)
                <p>Fourth Quarter</p>
            @endif
        </div>

        
        
        <div class="section">
            <p>Christian Living Education 
            @php 

                if($gradelvl->acadprogid == 2){

                    echo $gradelvl->levelname;

                }else{

     

                    echo str_replace('GRADE ', '', $gradelvl->levelname);
                }

            @endphp
            </p>

        </div>

    </div>

    <table width="100%" class="table table-bordered">

        <tr>
            <th>#</th>
            <th>Learner's Name</th>
            
            <?php $allCol = 0; ?>
            @foreach($values as $value)
                @if(count($items) != null){
                    @php
                        $count = 0;
                        foreach($items as $item){
                            if($value->id == $item->values_setupID){
                                $count++;
                                $allCol++;
                                
                            }
                        }

                    @endphp

                    @if($count != 0)

                        <th class="text-center" colspan="<?php echo $count ?>">{{$value->value_desc}}</th>
   
                    @endif

                @endif
            @endforeach

            <th></th>
            <th class="text-center">Initial</th>
            <th class="text-center">Quarterly</th>
        </tr>
        
        <tr>

            <th style="z-index: 100; " colspan="2"></th>
            @foreach($values as $value){
                
                @foreach($items as $item){
                    @if($value->id == $item->values_setupID){
                        <th style="text-align: center;" >{{$item->value_item_abbr}}</th>
                    @endif
                @endforeach
            @endforeach

            <th>Total</th>
            <th>Initial</th>
            <th>Final</th>


        </tr>

        <tr>
            <th colspan="2" style="text-align: center;">Highest Possible Score</th>
     
				@php 
					$sum = 0;  
					$col_count = 1;
					$col_val;
				@endphp
				@foreach($values as $value)
					
					@foreach($items as $item)
						
						@if($value->id == $item->values_setupID)
							@php  
								$col = $col_count++;
								$col_val = 'col'.$col;
							@endphp
							
							<th style="text-align: center; white-space: nowrap; overflow: hidden;" >{{$hps->$col_val}}</div></th>

							@php $sum+= $hps->$col_val; @endphp

						@endif

					@endforeach
					
				@endforeach

        
            <th><?php echo $sum; ?></th>
            <th>100</th>
            <th>100</th>


        </tr>

        <tr>

            <td colspan="<?php echo $allCol+5 ; ?>" style="text-align: left;">Female</td>

        </tr>

        <tbody>
            
            <?php $count = 1; ?>
            @foreach($data as $student){

                @if($student->gender == 'FEMALE'){
					<tr>
                        @php $row_count = $count++; @endphp
                        
                        <td width="2%"><?php echo $row_count; ?></td>
                        <td width="20%">{{$student->lastname}}, {{$student->firstname}} 
                            @if($student->middlename == '-' || $student->middlename == null) 
                                
                                <?php echo " "; ?>
                            @else

                                {{substr($student->middlename, 0, 1)}}. 


                            @endif
                        </td>

                        @for ($i=1; $i < count($items_search)+1; $i++) { 
                    
                            <?php $col_var = 'col'.$i; ?>

                            @if($student->$col_var == 0)

                                <td width="<?php 45/count($values) ?>%"  style="text-align: center; padding:0px" ></td>

                            @else

                                <td width="<?php 45/count($values) ?>%"  style="text-align: center; padding:0px" >{{$student->$col_var}}</td>

                            @endif

                        @endfor

                        <td width="10%" class="text-center">{{$student->total}}</td>
                        <td width="10%" class="text-center">{{$student->initial}}</td>
                        <td width="13%" class="text-center">{{$student->final}}</td>

					</tr>
                @endif


            @endforeach

            
        </tbody>
        
    </table>

    <br>
 

    <table class="signatory" width="100%" style="font-size: 12px;">
        @php 
        $signatoryCount = collect($signatory)->count();
        @endphp
        
            @if($signatoryCount <= 3)

                @foreach($signatory as $sign)
                    <td width="33%" class="text-left" style="padding-bottom: 30px">

                        <p style="font-weight: bold">{{$sign->type}}</p>
                        <div style="height: 10px"></div>
                        <p class="text-center">{{$sign->name}}</p>
                        <div style="height: 1px; background-color: black; margin-right: 20px; margin-left: 20px;"></div>
                        <p class="text-center">{{$sign->position}}</p>

                    </td>
                @endforeach
               
            @else

                @php 
                
                    $splitArray = array_chunk(collect($signatory)->toArray(), 3)

                @endphp

                @foreach($splitArray as $array)
                    <tr>
                        @foreach($array as $sign)
                        
                            <td width="33%" class="text-left" style="padding-bottom: 30px">

                                <p style="font-weight: bold">{{$sign->type}}</p>
                                <div style="height: 10px"></div>
                                <p class="text-center">{{$sign->name}}</p>
                                <div style="height: 1px; background-color: black; margin-right: 20px; margin-left: 20px;"></div>
                                <p class="text-center">{{$sign->position}}</p>

                            </td>
                        
                        @endforeach
                    </tr>
           

                @endforeach

                
            @endif
            
    </table>


</body>
</html>