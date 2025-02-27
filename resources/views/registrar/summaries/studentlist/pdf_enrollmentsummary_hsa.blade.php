<html>
    <head>
        <style>
            * { font-family: Arial, Helvetica, sans-serif; }
            @page{
                margin: 0.5in 1in;
            }
            table{
                border-collapse: collapse;
            }
        </style>
    </head>
    <body>      
        @php
        
        if(strpos(db::table('schoolinfo')->first()->picurl, 'random') !== false)
        {
                                                $picurl = explode("?", db::table('schoolinfo')->first()->picurl);
                                               $picurl = $picurl[0];
        }else{
                                                $picurl = db::table('schoolinfo')->first()->picurl;
        }
        @endphp  
        {{-- {{$picurl}} --}}
        <table style="width: 100%;">
            <tr>
                <td  style="text-align: left; vertical-align: top; width: 15%; text-align: right;">
                    <img src="{{$picurl}}" alt="school" width="80px">
                </td>
                <td style="wudth: 70%; text-align: center;">
                    {{-- @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'ndsc') --}}
                    <div style="width: 100%; font-weight: bold; font-size: 18px;">{{DB::table('schoolinfo')->first()->schoolname}}</div>
                    <div style="width: 100%; font-size: 15px !important; font-weight: bold;">2900 LAOAG CITY</div>
                    {{-- <div style="width: 100%; font-weight: bold; font-size: 18px !important;">&nbsp;</div> --}}
                    <img src="{{base_path()}}/public/assets/images/hsa/summary_of_enrollment.jpg" alt="school" width="200px" style="margin-top: 6px;">
                    <div style="width: 100%; font-size: 15px !important; font-weight: bold;">SY {{$sydesc}}</div>
                    {{-- @else
                    <div style="width: 100%;">Republic of the Philippines</div>
                    <div style="width: 100%; font-size: 20px !important;">Department of Education</div>
                    <div style="width: 100%; font-size: 15px !important;">{{DB::table('schoolinfo')->first()->regiontext}}</div>
                    <div style="width: 100%; font-size: 15px !important;">{{DB::table('schoolinfo')->first()->divisiontext}}</div>
                    <div style="width: 100%; font-size: 15px !important;">{{DB::table('schoolinfo')->first()->districttext}}</div>
                    @endif --}}
                </td>
                <td style="vertical-align: top; text-align: right; width: 15%;">
                    {{-- @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'ndsc')
                    <img src="{{base_path()}}/public/assets/images/ndsc/logo_archdiocese.jpg" alt="school" width="100px">
                    @else
                    <img src="{{base_path()}}/public/assets/images/department_of_Education.png" alt="school" width="100px">
                    @endif --}}
                </td>
            </tr>
        </table>
        <table style="width: 100%; font-size: 12px; margin-top: 3px;" border="1">
            <tr>
                <th>GRADE</th>
                <th style="width: 30%;">SECTION</th>
                <th>MALE</th>
                <th>FEMALE</th>
                <th>TOTAL</th>
            </tr>
                @foreach($gradelevels as $gradelevel)
                    @php
                        $firstsection = '';
                        $theremainingsections = array();
                        $keysection = array();

                        
                        $totalmale = 0;
                        $totalfemale = 0;
                        $total = 0;
                    @endphp
                    <tr>
                        <td rowspan="{{$gradelevel->sectioncount+1}}">{{$gradelevel->levelname}} </td>
                        @if($gradelevel->sectioncount == 0)
                                <th><span style="color: red;">TOTAL</th>
                                <th><span style="color: red;">&nbsp;</span></th>
                                <th><span style="color: red;">&nbsp;</span></th>
                                <th><span style="color: red;">&nbsp;</span></th>
                        @else
                            @php
                            $firstsection = collect($gradelevel->sections)->keys()[0];
                            $keysection = collect($gradelevel->sections)->keys()->toArray();
                            $theremainingsections = array_diff( $keysection, [$firstsection ] ) ;
                            $firstsectionvalues = collect($gradelevel->sections)[$firstsection];
                            // $allsections = collect($allsections)->map(function ($item, $key) use($firstsection) {
                            //     if($key != $firstsection)
                            //     {
                            //         return $item;
                            //     }
                            // });
                            $totalmale += collect($firstsectionvalues)->where('gender','male')->count();
                            $totalfemale += collect($firstsectionvalues)->where('gender','female')->count();
                            $total += collect($firstsectionvalues)->count();
                            @endphp
                            <th>{{$firstsection}}</th>
                            <th>{{collect($firstsectionvalues)->where('gender','male')->count()}}</th>
                            <th>{{collect($firstsectionvalues)->where('gender','female')->count()}}</th>
                            <th>{{collect($firstsectionvalues)->count()}}</th>
                        @endif
                    </tr>
                   
                    @if($gradelevel->sectioncount >0)
                        @foreach($gradelevel->sections as $eachsectkey => $eachsectval)
                            @if($eachsectkey != $firstsection)
                                @php
                                $totalmale += collect($eachsectval)->where('gender','male')->count();
                                $totalfemale += collect($eachsectval)->where('gender','female')->count();
                                $total += collect($eachsectval)->count();
                                @endphp
                                <tr>
                                    <th>{{$eachsectkey}}</th>
                                    <th>{{collect($eachsectval)->where('gender','male')->count()}}</th>
                                    <th>{{collect($eachsectval)->where('gender','female')->count()}}</th>
                                    <th>{{collect($eachsectval)->count()}}</th>
                                </tr>
                            @endif
                        @endforeach
                        @php
                        $gradelevel->totalmale = $totalmale;
                        $gradelevel->totalfemale = $totalfemale;
                        $gradelevel->total = $total;
                        @endphp
                        <tr>
                            <th><span style="color: red;">TOTAL</span></th>
                            <th><span style="color: red;">{{$totalmale}}</span></th>
                            <th><span style="color: red;">{{$totalfemale}}</span></th>
                            <th><span style="color: red;">{{$total}}</span></th>
                        </tr>
                    @endif 
                @endforeach
        </table>
    </body>
</html>