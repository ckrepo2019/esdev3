<html>
    <head>
                
        <style>
            * {
                text-transform: uppercase;
                        font-family: Arial, Helvetica, sans-serif;
            }
            .payslip-title {
                margin-bottom: 20px;
                text-align: center;
                text-decoration: underline;
                text-transform: uppercase;
            }
        /* body { margin: 0px; } */
        </style>
    </head>
    <body>
        <div style="width: 100%; text-align: center; font-weight: bold; font-size: 13px;">
            STAFF PROFILE
        </div>
        <div style="width: 100%; text-align: center; font-weight: bold; font-size: 13px;">
            SCHOOL YEAR : {{DB::table('sy')->where('id', $syid)->first()->sydesc}}
        </div>
        <br/>
        @if(count($offices)>0)
            @foreach($offices as $eachoffice)
                <div style="width: 100%; text-align: left; font-weight: bold; font-size: 11px;">
                    {{$eachoffice->officename}}
                </div>
                <ol style="font-size: 11px;">
                    @foreach($eachoffice->employees as $eachemployee)
                            <li  style="display: list-item;list-style: decimal; list-style-position: inside;">
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$eachemployee->prefix}} {{$eachemployee->firstname}} @if($eachemployee->middlename != null) {{$eachemployee->middlename}} @endif {{$eachemployee->lastname}} {{$eachemployee->suffix}}
                                <table style="width: 100%;">
                                    <tr>
                                        <td style="width: 30%;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Title/Degree</td>
                                        <td>- &nbsp;&nbsp;&nbsp;{{$eachemployee->title}}</td>
                                    </tr>    
                                    <tr>
                                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Major-Minor</td>
                                        <td>- &nbsp;&nbsp;&nbsp;{{$eachemployee->majorin}}</td>
                                    </tr>            
                                    <tr>
                                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Where Obtained</td>
                                        <td>- &nbsp;&nbsp;&nbsp;{{$eachemployee->degreewhere}}</td>
                                    </tr>            
                                    <tr>
                                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;MA/MBA</td>
                                        <td>- &nbsp;&nbsp;&nbsp;{{$eachemployee->ma_mba}}</td>
                                    </tr>            
                                    <tr>
                                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Where Obtained</td>
                                        <td>- &nbsp;&nbsp;&nbsp;{{$eachemployee->ma_mbawhere}}</td>
                                    </tr>            
                                    <tr>
                                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Doctorate Degree</td>
                                        <td>- &nbsp;&nbsp;&nbsp;{{$eachemployee->doctoratedegree}}</td>
                                    </tr>            
                                    <tr>
                                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Where Obtained</td>
                                        <td>- &nbsp;&nbsp;&nbsp;{{$eachemployee->doctoratedegreewhere}}</td>
                                    </tr>            
                                    <tr>
                                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Previous Position</td>
                                        <td>- &nbsp;&nbsp;&nbsp;{{$eachemployee->prevposition}}</td>
                                    </tr>            
                                    <tr>
                                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Experience</td>
                                        <td>- &nbsp;&nbsp;&nbsp;{{$eachemployee->prevpositionexp}}</td>
                                    </tr>            
                                    <tr>
                                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Present Position</td>
                                        <td>- &nbsp;&nbsp;&nbsp;{{$eachemployee->presposition}}</td>
                                    </tr>            
                                    <tr>
                                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Experience</td>
                                        <td>- &nbsp;&nbsp;&nbsp;{{$eachemployee->prespositionexp}}</td>
                                    </tr>   
                                    <tr>
                                        <td colspan="2">&nbsp;</td>    
                                    </tr>                                     
                                </table>
                                    
                                </div>
                            </li>
                    @endforeach
                </ol>
            @endforeach
        @endif
    </body>
</html>