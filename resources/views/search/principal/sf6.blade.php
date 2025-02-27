


<table class="mb-0 table-bordered table" style="min-width:300px;" id="sf6">
    <thead class=" border-top">
        <tr>
            <td class="text-center" rowspan="2"  style="width:6%">
            SUMMARY TABLE</td>
            @for($x = 1; $x <= 6; $x++)
                <td class="text-center" colspan="3">GRADE {{$x}} / GRADE {{$x+6}}</td>
            @endfor
            <td  class="text-center"  colspan="3">TOTAL</td>
        </tr>
        <tr class="text-center">
                @for($x = 1; $x <= 7; $x++)
                    <td>MALE</td>
                    <td>FEMALE</td>
                    <td>TOTAL</td>
                @endfor
        </tr>
      
    </thead>
    <tbody>
        <tr class="text-center border-top">
            <th >PROMOTED</th>
            @for($x = 1; $x <= 6; $x++)
                <td>
                    {{collect($data)->where('sortid',$x+3)->first()->malepromtstud}} / 
                    {{collect($data)->where('sortid',$x+6+3)->first()->malepromtstud}}
                </td>
                <td>
                    {{collect($data)->where('sortid',$x+3)->first()->fempromtstud}} / 
                    {{collect($data)->where('sortid',$x+6+3)->first()->fempromtstud}}
                </td>
                <td>
                    {{collect($data)->where('sortid',$x+3)->first()->fempromtstud + collect($data)->where('sortid',$x+3)->first()->malepromtstud}} / 
                    {{collect($data)->where('sortid',$x+6+3)->first()->fempromtstud + collect($data)->where('sortid',$x+6+3)->first()->malepromtstud}}
                </td>
            @endfor
                <td>{{ collect($data)->where('sortid','!=','1')->where('sortid','!=','2')->sum('malepromtstud')}}</td>
                <td>{{ collect($data)->where('sortid','!=','1')->where('sortid','!=','2')->sum('fempromtstud')}}</td>
                <td>{{ collect($data)->where('sortid','!=','1')->where('sortid','!=','2')->sum('malepromtstud') + collect($data)->sum('fempromtstud')}} </td>

       </tr>
       <tr class="text-center">
            <th>IRREGULAR (Grade 7 onwards only)</th>
            @for($x = 1; $x <= 6; $x++)
                <td>
                    {{collect($data)->where('sortid',$x+3)->first()->maleconstud}} / 
                    {{collect($data)->where('sortid',$x+6+3)->first()->maleconstud}}
                </td>
                <td>
                    {{collect($data)->where('sortid',$x+3)->first()->femconstud}} / 
                    {{collect($data)->where('sortid',$x+6+3)->first()->femconstud}}
                </td>
                <td>
                    {{collect($data)->where('sortid',$x+3)->first()->femconstud + collect($data)->where('sortid',$x+3)->first()->maleconstud}} / 
                    {{collect($data)->where('sortid',$x+6+3)->first()->femconstud + collect($data)->where('sortid',$x+6+3)->first()->maleconstud}}
                </td>
            @endfor
                <td>{{ collect($data)->sum('maleconstud')}}</td>
                <td>{{ collect($data)->sum('femconstud')}}</td>
                <td>{{ collect($data)->sum('maleconstud') + collect($data)->sum('femconstud')}} </td>
        </tr>
        <tr class="text-center">
            <th>RETAINED</th>
            @for($x = 1; $x <= 6; $x++)
                <td>
                    {{collect($data)->where('sortid',$x+3)->first()->maleretstud}} / 
                    {{collect($data)->where('sortid',$x+6+3)->first()->maleretstud}}
                </td>
                <td>
                    {{collect($data)->where('sortid',$x+3)->first()->femretstud}} / 
                    {{collect($data)->where('sortid',$x+6+3)->first()->femretstud}}
                </td>
                <td>
                    {{collect($data)->where('sortid',$x+3)->first()->femretstud + collect($data)->where('sortid',$x+3)->first()->maleretstud}} / 
                    {{collect($data)->where('sortid',$x+6+3)->first()->femretstud + collect($data)->where('sortid',$x+6+3)->first()->maleretstud}}
                </td>
            @endfor
                <td>{{ collect($data)->sum('maleretstud')}}</td>
                <td>{{ collect($data)->sum('femretstud')}}</td>
                <td>{{ collect($data)->sum('maleretstud') + collect($data)->sum('femretstud')}} </td>
        </tr>
        <tr class="text-center">
            <th>LEVEL OF PROFICIENCY (K to 12 Only)</th>
            @for($x = 1; $x <= 7; $x++)
                <th class="align-middle">MALE</td>
                <th class="align-middle">FEMALE</td>
                <th class="align-middle">TOTAL</td>
            @endfor
        </tr>

        <tr class="text-center">
            <th>BEGINNING (B: 74% and below)</th>
            @for($x = 1; $x <= 6; $x++)
                <td>
                    {{collect($data)->where('sortid',$x+3)->first()->begmale}} / 
                    {{collect($data)->where('sortid',$x+6+3)->first()->begmale}}
                </td>
                <td>
                    {{collect($data)->where('sortid',$x+3)->first()->begfemale}} / 
                    {{collect($data)->where('sortid',$x+6+3)->first()->begfemale}}
                </td>
                <td>
                    {{  collect($data)->where('sortid',$x+3)->first()->begmale + collect($data)->where('sortid',$x+3)->first()->begfemale }} / 
                    {{  collect($data)->where('sortid',$x+6+3)->first()->begmale + collect($data)->where('sortid',$x+6+3)->first()->begfemale }}
                </td>
            @endfor
                <td>{{ collect($data)->sum('begmale')}}</td>
                <td>{{ collect($data)->sum('begfemale')}}</td>
                <td>{{ collect($data)->sum('begmale') + collect($data)->sum('begfemale')}} </td>
       </tr>
       <tr class="text-center">
            <th>DEVELOPING (D: 75%-79%)</th>
            @for($x = 1; $x <= 6; $x++)
                <td>
                    {{collect($data)->where('sortid',$x+3)->first()->devmale}} / 
                    {{collect($data)->where('sortid',$x+6+3)->first()->devmale}}
                </td>
                <td>
                    {{collect($data)->where('sortid',$x+3)->first()->devfemale}} / 
                    {{collect($data)->where('sortid',$x+6+3)->first()->devfemale}}
                </td>
                <td>
                    {{  collect($data)->where('sortid',$x+3)->first()->devfemale + collect($data)->where('sortid',$x+3)->first()->devmale }} / 
                    {{  collect($data)->where('sortid',$x+6+3)->first()->devfemale + collect($data)->where('sortid',$x+6+3)->first()->devmale }}
                </td>
            @endfor
                <td>{{ collect($data)->sum('devmale')}}</td>
                <td>{{ collect($data)->sum('devfemale')}}</td>
                <td>{{ collect($data)->sum('devmale') + collect($data)->sum('devfemale')}} </td>
        </tr>
        <tr class="text-center">
            <th>APPROACHING PROFICIENCY (AP: 80%-84%)</th>
            @for($x = 1; $x <= 6; $x++)
                <td>
                    {{collect($data)->where('sortid',$x+3)->first()->approfmale}} / 
                    {{collect($data)->where('sortid',$x+6+3)->first()->approfmale}}
                </td>
                <td>
                    {{collect($data)->where('sortid',$x+3)->first()->approffemale}} / 
                    {{collect($data)->where('sortid',$x+6+3)->first()->approffemale}}
                </td>
                <td>
                    {{  collect($data)->where('sortid',$x+3)->first()->approffemale + collect($data)->where('sortid',$x+3)->first()->approfmale }} / 
                    {{  collect($data)->where('sortid',$x+6+3)->first()->approffemale + collect($data)->where('sortid',$x+6+3)->first()->approfmale }}
                </td>
            @endfor
                <td>{{ collect($data)->sum('approfmale')}}</td>
                <td>{{ collect($data)->sum('approffemale')}}</td>
                <td>{{ collect($data)->sum('approffemale') + collect($data)->sum('approfmale')}} </td>
        </tr>
        <tr class="text-center">
            <th>PROFICIENT (P: 85%-89%)</th>
            @for($x = 1; $x <= 6; $x++)
                <td>
                    {{collect($data)->where('sortid',$x+3)->first()->profmale}} / 
                    {{collect($data)->where('sortid',$x+6+3)->first()->profmale}}
                </td>
                <td>
                    {{collect($data)->where('sortid',$x+3)->first()->proffemale}} / 
                    {{collect($data)->where('sortid',$x+6+3)->first()->proffemale}}
                </td>
                <td>
                    {{  collect($data)->where('sortid',$x+3)->first()->proffemale + collect($data)->where('sortid',$x+3)->first()->profmale }} / 
                    {{  collect($data)->where('sortid',$x+6+3)->first()->proffemale + collect($data)->where('sortid',$x+6+3)->first()->profmale }}
                </td>
            @endfor
                <td>{{ collect($data)->sum('profmale')}}</td>
                <td>{{ collect($data)->sum('proffemale')}}</td>
                <td>{{ collect($data)->sum('proffemale') + collect($data)->sum('profmale')}} </td>
            </tr>
        <tr class="text-center">
            <th>ADVANCED (A: 90% and above)</th>
            @for($x = 1; $x <= 6; $x++)
                <td>
                    {{collect($data)->where('sortid',$x+3)->first()->addmale}} / 
                    {{collect($data)->where('sortid',$x+6+3)->first()->addmale}}
                </td>
                <td>
                    {{collect($data)->where('sortid',$x+3)->first()->addfemale}} / 
                    {{collect($data)->where('sortid',$x+6+3)->first()->addfemale}}
                </td>
                <td>
                    {{  collect($data)->where('sortid',$x+3)->first()->addfemale + collect($data)->where('sortid',$x+3)->first()->addmale }} / 
                    {{  collect($data)->where('sortid',$x+6+3)->first()->addfemale + collect($data)->where('sortid',$x+6+3)->first()->addmale }}
                </td>
            @endfor
                <td>{{ collect($data)->where('sortid','!=','1')->where('sortid','!=','2')->sum('addmale')}}</td>
                <td>{{ collect($data)->where('sortid','!=','1')->where('sortid','!=','2')->sum('addfemale')}}</td>
                <td>{{ collect($data)->where('sortid','!=','1')->where('sortid','!=','2')->sum('addfemale') + collect($data)->where('sortid','!=','1')->where('sortid','!=','2')->sum('addmale')}} </td>
        </tr>
        <tr class="text-center">
            <th>TOTAL</th>
            @for($x = 1; $x <= 6; $x++)
                <td>
                    {{
                        collect($data)->where('sortid',$x+3)->first()->begmale +
                        collect($data)->where('sortid',$x+3)->first()->devmale +
                        collect($data)->where('sortid',$x+3)->first()->approfmale +
                        collect($data)->where('sortid',$x+3)->first()->profmale +
                        collect($data)->where('sortid',$x+3)->first()->addmale 
                        
                    }} / 
                    {{
                        collect($data)->where('sortid',$x+6+3)->first()->begmale +
                        collect($data)->where('sortid',$x+6+3)->first()->devmale +
                        collect($data)->where('sortid',$x+6+3)->first()->approfmale +
                        collect($data)->where('sortid',$x+6+3)->first()->profmale +
                        collect($data)->where('sortid',$x+6+3)->first()->addmale 
                    }}
                </td>
                <td>
                    {{
                        collect($data)->where('sortid',$x+3)->first()->begfemale +
                        collect($data)->where('sortid',$x+3)->first()->devfemale +
                        collect($data)->where('sortid',$x+3)->first()->approffemale +
                        collect($data)->where('sortid',$x+3)->first()->proffemale +
                        collect($data)->where('sortid',$x+3)->first()->addfemale 
                        
                    }} / 
                    {{
                        collect($data)->where('sortid',$x+6+3)->first()->begfemale +
                        collect($data)->where('sortid',$x+6+3)->first()->devfemale +
                        collect($data)->where('sortid',$x+6+3)->first()->approffemale +
                        collect($data)->where('sortid',$x+6+3)->first()->proffemale +
                        collect($data)->where('sortid',$x+6+3)->first()->addfemale 
                    }}
                </td>
                <td>
                    {{
                        collect($data)->where('sortid',$x+3)->first()->begfemale +
                        collect($data)->where('sortid',$x+3)->first()->devfemale +
                        collect($data)->where('sortid',$x+3)->first()->approffemale +
                        collect($data)->where('sortid',$x+3)->first()->proffemale +
                        collect($data)->where('sortid',$x+3)->first()->addfemale +
                        collect($data)->where('sortid',$x+3)->first()->begmale +
                        collect($data)->where('sortid',$x+3)->first()->devmale +
                        collect($data)->where('sortid',$x+3)->first()->approfmale +
                        collect($data)->where('sortid',$x+3)->first()->profmale +
                        collect($data)->where('sortid',$x+3)->first()->addmale 
                        
                    }} / 
                    {{
                        collect($data)->where('sortid',$x+6+3)->first()->begfemale +
                        collect($data)->where('sortid',$x+6+3)->first()->devfemale +
                        collect($data)->where('sortid',$x+6+3)->first()->approffemale +
                        collect($data)->where('sortid',$x+6+3)->first()->proffemale +
                        collect($data)->where('sortid',$x+6+3)->first()->addfemale +
                        collect($data)->where('sortid',$x+6+3)->first()->begmale +
                        collect($data)->where('sortid',$x+6+3)->first()->devmale +
                        collect($data)->where('sortid',$x+6+3)->first()->approfmale +
                        collect($data)->where('sortid',$x+6+3)->first()->profmale +
                        collect($data)->where('sortid',$x+6+3)->first()->addmale 
                    }}
                </td>
            @endfor
                <td>
                    {{ 
                        collect($data)->where('sortid','!=','1')->where('sortid','!=','2')->sum('begmale') +
                        collect($data)->where('sortid','!=','1')->where('sortid','!=','2')->sum('devmale') +
                        collect($data)->where('sortid','!=','1')->where('sortid','!=','2')->sum('approfmale') +
                        collect($data)->where('sortid','!=','1')->where('sortid','!=','2')->sum('profmale') +
                        collect($data)->where('sortid','!=','1')->where('sortid','!=','2')->sum('addmale')
                    }}
                
                </td>
                <td>
                   {{
                        collect($data)->where('sortid','!=','1')->where('sortid','!=','2')->sum('begfemale') +
                        collect($data)->where('sortid','!=','1')->where('sortid','!=','2')->sum('devfemale') +
                        collect($data)->where('sortid','!=','1')->where('sortid','!=','2')->sum('approffemale') +
                        collect($data)->where('sortid','!=','1')->where('sortid','!=','2')->sum('proffemale') +
                        collect($data)->where('sortid','!=','1')->where('sortid','!=','2')->sum('addfemale')
                    }}
                </td>
                <td>
                    {{ 
                        collect($data)->where('sortid','!=','1')->where('sortid','!=','2')->sum('begmale') +
                        collect($data)->where('sortid','!=','1')->where('sortid','!=','2')->sum('devmale') +
                        collect($data)->where('sortid','!=','1')->where('sortid','!=','2')->sum('approfmale') +
                        collect($data)->where('sortid','!=','1')->where('sortid','!=','2')->sum('profmale') +
                        collect($data)->where('sortid','!=','1')->where('sortid','!=','2')->sum('addmale') +
                        collect($data)->where('sortid','!=','1')->where('sortid','!=','2')->sum('begfemale') +
                        collect($data)->where('sortid','!=','1')->where('sortid','!=','2')->sum('devfemale') +
                        collect($data)->where('sortid','!=','1')->where('sortid','!=','2')->sum('approffemale') +
                        collect($data)->where('sortid','!=','1')->where('sortid','!=','2')->sum('proffemale') +
                        collect($data)->where('sortid','!=','1')->where('sortid','!=','2')->sum('addfemale')
                    }} 
                </td>
        </tr>
       

    </tbody>
   
</table>