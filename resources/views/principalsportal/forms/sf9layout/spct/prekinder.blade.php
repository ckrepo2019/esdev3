<!DOCTYPE html>

<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        {{-- <title>{{$student[0]->firstname.' '.$student[0]->middlename[0].' '.$student[0]->lastname}}</title> --}}
        <style>
            * { font-family: Arial, Helvetica, , sans-serif;}
            
            table{
                border-collapse: collapse;
            }
            .grades {
               font-family: ZapfDingbats, sans-serif;
            }

            @page {  
                margin: 45px 40px;
                
            }

            td, th{
                padding: 4px;
            }

            .text-center{
                text-align: center;
            }
            
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
        
            .align-middle{
                vertical-align: middle !important;    
            }
        
            .table-sm td, .table-sm th {
                padding: .3rem;
            }
        
            .text-right{
                text-align: right !important;
            }
        
            .text-left{
                    text-align: left !important;
                }

        </style>
    </head>
    <body>  
        <div style="text-align: right; font-weight: bold; font-size: 14px;">ECCD FORM 1</div>
        <div style="text-align: right; font-size: 8px;">(Teacher's copy)</div>
        <table style="width: 100%; font-size: 9px;">
            <tr>
                <td style="width: 8%;">SCHOOL:</td>
                <td style="width: 40%; border-bottom: 1px solid black;">{{ucwords(strtolower(DB::table('schoolinfo')->first()->schoolname))}}</td>
                <td colspan="3"></td>
            </tr>
            <tr>
                <td>CHILD'S NAME:</td>
                <td style="width: 40%; border-bottom: 1px solid black;">{{$student->student}}</td>
                <td style="width: 5%;"></td>
                <td style="width: 5%;">SECTION:</td>
                <td style="border-bottom: 1px solid black;"></td>
                <td style="border-bottom: 1px solid black;">{{$section->sectionname}}</td>
            </tr>
        </table>
        <table style="width: 100%; font-size: 9.5px; border: 2px solid black; margin-top: 5px; page-break-inside: always;" border="1" >
            <tr>
                <th rowspan="2" style="width: 2%;"></th>
                <th rowspan="2">RECEPTIVE LANGUAGE</th>
                <th colspan="3" style="width: 6%;">SCORE</th>
                <th rowspan="2" style="width: 2%;"></th>
                <th rowspan="2">COGNITIVE DEVELOPMENT</th>
                <th colspan="3" style="width: 6%;">SCORE</th>
                <th rowspan="2" style="width: 2%;"></th>
                <th rowspan="2">COGNITIVE DEVELOPMENT</th>
                <th colspan="3" style="width: 6%;">SCORE</th>
                <th rowspan="2" style="width: 2%;"></th>
                <th rowspan="2">SOCIAL-EMOTIONAL</th>
                <th colspan="3" style="width: 6%;">SCORE</th>
            </tr>
            <tr>
                <th>1</th>
                <th>2</th>
                <th>3</th>
                <th>1</th>
                <th>2</th>
                <th>3</th>
                <th>1</th>
                <th>2</th>
                <th>3</th>
                <th>1</th>
                <th>2</th>
                <th>3</th>
            </tr>
            <tr>
                <td style="text-align: center;">1</td>
                <td>Points to family member when asked to do so</td>
                <td class="text-center"><div class="grades">{{collect($setup)->where('id',230)->first()->q1grade != null ? '4' : ''}}</div></td>
                <td class="text-center"><div class="grades">{{collect($setup)->where('id',230)->first()->q2grade != null ? '4' : ''}}</div></td>
                <td class="text-center"><div class="grades">{{collect($setup)->where('id',230)->first()->q3grade != null ? '4' : ''}}</div></td>
                <td style="text-align: center;">1</td>
                <td>Looks at direction of fallen object</td>
                <td class="text-center"><div class="grades">{{collect($setup)->where('id',43)->first()->q1grade != null ? '4' : ''}}</div></td>
                <td class="text-center"><div class="grades">{{collect($setup)->where('id',43)->first()->q2grade != null ? '4' : ''}}</div></td>
                <td class="text-center"><div class="grades">{{collect($setup)->where('id',43)->first()->q3grade != null ? '4' : ''}}</div></td>
                <td style="text-align: center;">17</td>
                <td>Can assemble simple puzzles</td>
                <td class="text-center"><div class="grades">{{collect($setup)->where('id',60)->first()->q1grade != null ? '4' : ''}}</div></td>
                <td class="text-center"><div class="grades">{{collect($setup)->where('id',60)->first()->q2grade != null ? '4' : ''}}</div></td>
                <td class="text-center"><div class="grades">{{collect($setup)->where('id',60)->first()->q3grade != null ? '4' : ''}}</div></td>
                <td style="text-align: center;">10</td>
                <td>Imitates adult activities (e.g., cooking, washing)</td>
                <td class="text-center"><div class="grades">{{collect($setup)->where('id',74)->first()->q1grade != null ? '4' : ''}}</div></td>
                <td class="text-center"><div class="grades">{{collect($setup)->where('id',74)->first()->q2grade != null ? '4' : ''}}</div></td>
                <td class="text-center"><div class="grades">{{collect($setup)->where('id',74)->first()->q3grade != null ? '4' : ''}}</div></td>
            </tr>
            <tr>
                <td style="text-align: center;">2</td>
                <td>Points to 5 body parts on himself when asked to do so</td>
                <td class="text-center"><div class="grades">{{collect($setup)->where('id',22)->first()->q1grade != null ? '4' : ''}}</div></td>
                <td class="text-center"><div class="grades">{{collect($setup)->where('id',22)->first()->q2grade != null ? '4' : ''}}</div></td>
                <td class="text-center"><div class="grades">{{collect($setup)->where('id',22)->first()->q3grade != null ? '4' : ''}}</div></td>
                <td style="text-align: center;">2</td>
                <td>Looks for partially hidden object</td>
                <td class="text-center"><div class="grades">{{collect($setup)->where('id',44)->first()->q1grade != null ? '4' : ''}}</div></td>
                <td class="text-center"><div class="grades">{{collect($setup)->where('id',44)->first()->q2grade != null ? '4' : ''}}</div></td>
                <td class="text-center"><div class="grades">{{collect($setup)->where('id',44)->first()->q3grade != null ? '4' : ''}}</div></td>
                <td style="text-align: center;">18</td>
                <td>Demonstrates an understanding of opposites by completing a statement (e.g., Ang aso ay malaki, ang daga ay _____”)</td>
                <td class="text-center"><div class="grades">{{collect($setup)->where('id',61)->first()->q1grade != null ? '4' : ''}}</div></td>
                <td class="text-center"><div class="grades">{{collect($setup)->where('id',61)->first()->q2grade != null ? '4' : ''}}</div></td>
                <td class="text-center"><div class="grades">{{collect($setup)->where('id',61)->first()->q3grade != null ? '4' : ''}}</div></td>
                <td style="text-align: center;">11</td>
                <td>Identifies feelings in others</td>
                <td class="text-center"><div class="grades">{{collect($setup)->where('id',75)->first()->q1grade != null ? '4' : ''}}</div></td>
                <td class="text-center"><div class="grades">{{collect($setup)->where('id',75)->first()->q2grade != null ? '4' : ''}}</div></td>
                <td class="text-center"><div class="grades">{{collect($setup)->where('id',75)->first()->q3grade != null ? '4' : ''}}</div></td>
            </tr>
            <tr>
                <td style="text-align: center;">3</td>
                <td>Points to 5 named pictured objects when asked to do so</td>
                <td class="text-center"><div class="grades">{{collect($setup)->where('id',23)->first()->q1grade != null ? '4' : ''}}</div></td>
                <td><div class="grades">{{collect($setup)->where('id',23)->first()->q2grade != null ? '4' : ''}}</div></td>
                <td><div class="grades">{{collect($setup)->where('id',23)->first()->q3grade != null ? '4' : ''}}</div></td>
                <td style="text-align: center;">3</td>
                <td>Imitates behavior just seen a few minutes earlier</td>
                <td class="text-center"><div class="grades">{{collect($setup)->where('id',45)->first()->q1grade != null ? '4' : ''}}</div></td>
                <td class="text-center"><div class="grades">{{collect($setup)->where('id',45)->first()->q2grade != null ? '4' : ''}}</div></td>
                <td class="text-center"><div class="grades">{{collect($setup)->where('id',45)->first()->q3grade != null ? '4' : ''}}</div></td>
                <td style="text-align: center;">19</td>
                <td>Points to left and right sides of body</td>
                <td class="text-center"><div class="grades">{{collect($setup)->where('id',62)->first()->q1grade != null ? '4' : ''}}</div></td>
                <td class="text-center"><div class="grades">{{collect($setup)->where('id',62)->first()->q2grade != null ? '4' : ''}}</div></td>
                <td class="text-center"><div class="grades">{{collect($setup)->where('id',62)->first()->q3grade != null ? '4' : ''}}</div></td>
                <td style="text-align: center;">12</td>
                <td>Appropriately uses cultural gestures of greeting without much prompting (e.g., mano, bless, kiss,etc.)
                    Comforts playmates/siblings in distress
                    </td>
                <td class="text-center"><div class="grades">{{collect($setup)->where('id',76)->first()->q1grade != null ? '4' : ''}}</div></td>
                <td class="text-center"><div class="grades">{{collect($setup)->where('id',76)->first()->q2grade != null ? '4' : ''}}</div></td>
                <td class="text-center"><div class="grades">{{collect($setup)->where('id',76)->first()->q3grade != null ? '4' : ''}}</div></td>
            </tr>
            <tr>
                <td style="text-align: center;">4</td>
                <td>Follows one-step instructions that include simple prepositions (e.g., in, on, under, etc.)</td>
                <td class="text-center"><div class="grades">{{collect($setup)->where('id',24)->first()->q1grade != null ? '4' : ''}}</div></td>
                <td class="text-center"><div class="grades">{{collect($setup)->where('id',24)->first()->q2grade != null ? '4' : ''}}</div></td>
                <td class="text-center"><div class="grades">{{collect($setup)->where('id',24)->first()->q3grade != null ? '4' : ''}}</div></td>
                <td style="text-align: center;">4</td>
                <td>Offers object but will not release it</td>
                <td class="text-center"><div class="grades">{{collect($setup)->where('id',46)->first()->q1grade != null ? '4' : ''}}</div></td>
                <td class="text-center"><div class="grades">{{collect($setup)->where('id',46)->first()->q2grade != null ? '4' : ''}}</div></td>
                <td class="text-center"><div class="grades">{{collect($setup)->where('id',46)->first()->q3grade != null ? '4' : ''}}</div></td>
                <td style="text-align: center;">20</td>
                <td>Can state what is silly or wrong with pictures (e.g. Ano ang mali sa larawang ito?)</td>
                <td class="text-center"><div class="grades">{{collect($setup)->where('id',63)->first()->q1grade != null ? '4' : ''}}</div></td>
                <td class="text-center"><div class="grades">{{collect($setup)->where('id',63)->first()->q2grade != null ? '4' : ''}}</div></td>
                <td class="text-center"><div class="grades">{{collect($setup)->where('id',63)->first()->q3grade != null ? '4' : ''}}</div></td>
                <td style="text-align: center;">13</td>
                <td>Comforts playmates/siblings in distress</td>
                <td class="text-center"><div class="grades">{{collect($setup)->where('id',77)->first()->q1grade != null ? '4' : ''}}</div></td>
                <td class="text-center"><div class="grades">{{collect($setup)->where('id',77)->first()->q2grade != null ? '4' : ''}}</div></td>
                <td class="text-center"><div class="grades">{{collect($setup)->where('id',77)->first()->q3grade != null ? '4' : ''}}</div></td>
            </tr>
            <tr>
                <td style="text-align: center;">5</td>
                <td>Follows 2-step instructions that include simple prepositions</td>
                <td class="text-center"><div class="grades">{{collect($setup)->where('id',25)->first()->q1grade != null ? '4' : ''}}</div></td>
                <td class="text-center"><div class="grades">{{collect($setup)->where('id',25)->first()->q2grade != null ? '4' : ''}}</div></td>
                <td class="text-center"><div class="grades">{{collect($setup)->where('id',25)->first()->q3grade != null ? '4' : ''}}</div></td>
                <td style="text-align: center;">5</td>
                <td>Looks for completely hidden object</td>
                <td class="text-center"><div class="grades">{{collect($setup)->where('id',47)->first()->q1grade != null ? '4' : ''}}</div></td>
                <td class="text-center"><div class="grades">{{collect($setup)->where('id',47)->first()->q2grade != null ? '4' : ''}}</div></td>
                <td class="text-center"><div class="grades">{{collect($setup)->where('id',47)->first()->q3grade != null ? '4' : ''}}</div></td>
                <td style="text-align: center;">21</td>
                <td>Matches upper and lower case letters</td>
                <td class="text-center"><div class="grades">{{collect($setup)->where('id',64)->first()->q1grade != null ? '4' : ''}}</div></td>
                <td class="text-center"><div class="grades">{{collect($setup)->where('id',64)->first()->q2grade != null ? '4' : ''}}</div></td>
                <td class="text-center"><div class="grades">{{collect($setup)->where('id',64)->first()->q3grade != null ? '4' : ''}}</div></td>
                <td style="text-align: center;">14</td>
                <td>Persists when faced with a problem or obstacle to his wants</td>
                <td class="text-center"><div class="grades">{{collect($setup)->where('id',78)->first()->q1grade != null ? '4' : ''}}</div></td>
                <td class="text-center"><div class="grades">{{collect($setup)->where('id',78)->first()->q2grade != null ? '4' : ''}}</div></td>
                <td class="text-center"><div class="grades">{{collect($setup)->where('id',78)->first()->q3grade != null ? '4' : ''}}</div></td>
            </tr>
            <tr>
                <td style="text-align: center;"></td>
                <td style="text-align: right;">TOTAL</td>
                <td class="text-center">{{collect($setup)->where('group','A')->where('q1grade','!=',null)->sum('q1grade') != 0 ? collect($setup)->where('group','A')->where('q1grade','!=',null)->sum('q1grade') : ''}}</td>
                <td class="text-center">{{collect($setup)->where('group','A')->where('q2grade','!=',null)->sum('q2grade') != 0 ? collect($setup)->where('group','A')->where('q2grade','!=',null)->sum('q2grade') : ''}}</td>
                <td class="text-center">{{collect($setup)->where('group','A')->where('q3grade','!=',null)->sum('q3grade') != 0 ? collect($setup)->where('group','A')->where('q3grade','!=',null)->sum('q3grade') : ''}}</td>
                <td style="text-align: center;">6</td>
                <td>Exhibits simple pretend play (feed, put doll to sleep)</td>
                <td class="text-center"><div class="grades">{{collect($setup)->where('id',48)->first()->q1grade != null ? '4' : ''}}</div></td>
                <td class="text-center"><div class="grades">{{collect($setup)->where('id',48)->first()->q2grade != null ? '4' : ''}}</div></td>
                <td class="text-center"><div class="grades">{{collect($setup)->where('id',48)->first()->q3grade != null ? '4' : ''}}</div></td>
                <td style="text-align: center;"></td>
                <td style="text-align: right;">TOTAL</td>
                <td class="text-center">{{collect($setup)->where('group','C')->where('q1grade','!=',null)->sum('q1grade') != 0 ? collect($setup)->where('group','C')->where('q1grade','!=',null)->sum('q1grade') : ''}}</td>
                <td class="text-center">{{collect($setup)->where('group','C')->where('q2grade','!=',null)->sum('q2grade') != 0 ? collect($setup)->where('group','C')->where('q1grade','!=',null)->sum('q2grade') : ''}}</td>
                <td class="text-center">{{collect($setup)->where('group','C')->where('q3grade','!=',null)->sum('q3grade') != 0 ? collect($setup)->where('group','C')->where('q1grade','!=',null)->sum('q3grade') : ''}}</td>
                <td style="text-align: center;">15</td>
                <td>Helps with family chores (e.g., wiping tables, watering plants, etc.)</td>
                <td class="text-center"><div class="grades">{{collect($setup)->where('id',79)->first()->q1grade != null ? '4' : ''}}</div></td>
                <td class="text-center"><div class="grades">{{collect($setup)->where('id',79)->first()->q2grade != null ? '4' : ''}}</div></td>
                <td class="text-center"><div class="grades">{{collect($setup)->where('id',79)->first()->q3grade != null ? '4' : ''}}</div></td>
            </tr>
            <tr>
                <th rowspan="2" style="height: 50px;"></th>
                <th rowspan="2">EXPRESSIVE LANGUAGE</th>
                <th colspan="3">SCORE</th>
                <td style="text-align: center;">7</td>
                <td>Matches objects</td>
                <td class="text-center"><div class="grades">{{collect($setup)->where('id',49)->first()->q1grade != null ? '4' : ''}}</div></td>
                <td class="text-center"><div class="grades">{{collect($setup)->where('id',49)->first()->q2grade != null ? '4' : ''}}</div></td>
                <td class="text-center"><div class="grades">{{collect($setup)->where('id',49)->first()->q3grade != null ? '4' : ''}}</div></td>
                <td rowspan="2"></td>
                <th rowspan="2">SOCIAL-EMOTIONAL</th>
                <th colspan="3">SCORE</th>
                <td style="text-align: center;">16</td>
                <td>Curious about environment but knows when to stop asking questions from adults</td>
                <td class="text-center"><div class="grades">{{collect($setup)->where('id',80)->first()->q1grade != null ? '4' : ''}}</div></td>
                <td class="text-center"><div class="grades">{{collect($setup)->where('id',80)->first()->q2grade != null ? '4' : ''}}</div></td>
                <td class="text-center"><div class="grades">{{collect($setup)->where('id',80)->first()->q3grade != null ? '4' : ''}}</div></td>
            </tr>
            <tr>
                <th>1</th>
                <th>2</th>
                <th>3</th>
                <td style="text-align: center;">8</td>
                <td>Match 2 – 3 objects</td>
                <td class="text-center"><div class="grades">{{collect($setup)->where('id',50)->first()->q1grade != null ? '4' : ''}}</div></td>
                <td class="text-center"><div class="grades">{{collect($setup)->where('id',50)->first()->q2grade != null ? '4' : ''}}</div></td>
                <td class="text-center"><div class="grades">{{collect($setup)->where('id',50)->first()->q3grade != null ? '4' : ''}}</div></td>
                <th>1</th>
                <th>2</th>
                <th>3</th>
                <td style="text-align: center;">17</td>
                <td>Waits for turn</td>
                <td class="text-center"><div class="grades">{{collect($setup)->where('id',81)->first()->q1grade != null ? '4' : ''}}</div></td>
                <td class="text-center"><div class="grades">{{collect($setup)->where('id',81)->first()->q2grade != null ? '4' : ''}}</div></td>
                <td class="text-center"><div class="grades">{{collect($setup)->where('id',81)->first()->q3grade != null ? '4' : ''}}</div></td>
            </tr>
            <tr>
                <td style="text-align: center;">1</td>
                <td>Uses 5-20 recognizable words</td>
                <td class="text-center"><div class="grades">{{collect($setup)->where('id',27)->first()->q1grade != null ? '4' : ''}}</div></td>
                <td class="text-center"><div class="grades">{{collect($setup)->where('id',27)->first()->q2grade != null ? '4' : ''}}</div></td>
                <td class="text-center"><div class="grades">{{collect($setup)->where('id',27)->first()->q3grade != null ? '4' : ''}}</div></td>
                <td style="text-align: center;">9</td>
                <td>Matches pictures</td>
                @for($x=1;$x<=3;$x++)
                    @php $field = 'q'.$x.'grade'; $id = 81; @endphp
                    <td class="text-center"><div class="grades">{{collect($setup)->where('id',$id)->first()->$field != null ? '4' : ''}}</div></td>
                @endfor
                <td style="text-align: center;">1</td>
                <td>Enjoys watching activities of nearby people or animals</td>
                @for($x=1;$x<=3;$x++)
                    @php $field = 'q'.$x.'grade'; $id = 65; @endphp
                    <td class="text-center"><div class="grades">{{collect($setup)->where('id',$id)->first()->$field != null ? '4' : ''}}</div></td>
                @endfor
                <td style="text-align: center;">18</td>
                <td>Asks permission to play with toy being used by another</td>
                @for($x=1;$x<=3;$x++)
                    @php $field = 'q'.$x.'grade'; $id = 82; @endphp
                    <td class="text-center"><div class="grades">{{collect($setup)->where('id',$id)->first()->$field != null ? '4' : ''}}</div></td>
                @endfor
            </tr>
            <tr>
                <td style="text-align: center;">2</td>
                <td>Uses pronouns (e.g. I, me, ako, akin)</td>
                @for($x=1;$x<=3;$x++)
                    @php $field = 'q'.$x.'grade'; $id = 28; @endphp
                    <td class="text-center"><div class="grades">{{collect($setup)->where('id',$id)->first()->$field != null ? '4' : ''}}</div></td>
                @endfor
                <td style="text-align: center;">10</td>
                <td>Sorts based on shapes</td>
                @for($x=1;$x<=3;$x++)
                    @php $field = 'q'.$x.'grade'; $id = 52; @endphp
                    <td class="text-center"><div class="grades">{{collect($setup)->where('id',$id)->first()->$field != null ? '4' : ''}}</div></td>
                @endfor
                <td style="text-align: center;">2</td>
                <td>Friendly with strangers but initially may show slight anxiety or shyness</td>
                @for($x=1;$x<=3;$x++)
                    @php $field = 'q'.$x.'grade'; $id = 66; @endphp
                    <td class="text-center"><div class="grades">{{collect($setup)->where('id',$id)->first()->$field != null ? '4' : ''}}</div></td>
                @endfor
                <td style="text-align: center;">19</td>
                <td>Defends possessions with determination</td>
                @for($x=1;$x<=3;$x++)
                    @php $field = 'q'.$x.'grade'; $id = 83; @endphp
                    <td class="text-center"><div class="grades">{{collect($setup)->where('id',$id)->first()->$field != null ? '4' : ''}}</div></td>
                @endfor
            </tr>
            <tr>
                <td style="text-align: center;">3</td>
                <td>Uses 2-3 words verb-noun combinations (e.g. hingi gatas)</td>
                @for($x=1;$x<=3;$x++)
                    @php $field = 'q'.$x.'grade'; $id = 29; @endphp
                    <td class="text-center"><div class="grades">{{collect($setup)->where('id',$id)->first()->$field != null ? '4' : ''}}</div></td>
                @endfor
                <td style="text-align: center;">11</td>
                <td>Sorts objects based on 2 attributes (e.g., size and color)</td>
                @for($x=1;$x<=3;$x++)
                    @php $field = 'q'.$x.'grade'; $id = 53; @endphp
                    <td class="text-center"><div class="grades">{{collect($setup)->where('id',$id)->first()->$field != null ? '4' : ''}}</div></td>
                @endfor
                <td style="text-align: center;">3</td>
                <td>Plays alone but likes to be near familiar adults or brothers and sisters</td>
                @for($x=1;$x<=3;$x++)
                    @php $field = 'q'.$x.'grade'; $id = 67; @endphp
                    <td class="text-center"><div class="grades">{{collect($setup)->where('id',$id)->first()->$field != null ? '4' : ''}}</div></td>
                @endfor
                <td style="text-align: center;">20</td>
                <td>Plays organized group games fairly (e.g., does not cheat in order to win)</td>
                @for($x=1;$x<=3;$x++)
                    @php $field = 'q'.$x.'grade'; $id = 84; @endphp
                    <td class="text-center"><div class="grades">{{collect($setup)->where('id',$id)->first()->$field != null ? '4' : ''}}</div></td>
                @endfor
            </tr>
            <tr>
                <td style="text-align: center;">4</td>
                <td>Names objects in pictures</td>
                @for($x=1;$x<=3;$x++)
                    @php $field = 'q'.$x.'grade'; $id = 30; @endphp
                    <td class="text-center"><div class="grades">{{collect($setup)->where('id',$id)->first()->$field != null ? '4' : ''}}</div></td>
                @endfor
                <td style="text-align: center;">12</td>
                <td>Arranges objects according to size from smallest to biggest</td>
                @for($x=1;$x<=3;$x++)
                    @php $field = 'q'.$x.'grade'; $id = 54; @endphp
                    <td class="text-center"><div class="grades">{{collect($setup)->where('id',$id)->first()->$field != null ? '4' : ''}}</div></td>
                @endfor
                <td style="text-align: center;">4</td>
                <td>Laughs or squeals aloud in play</td>
                @for($x=1;$x<=3;$x++)
                    @php $field = 'q'.$x.'grade'; $id = 68; @endphp
                    <td class="text-center"><div class="grades">{{collect($setup)->where('id',$id)->first()->$field != null ? '4' : ''}}</div></td>
                @endfor
                <td style="text-align: center;">21</td>
                <td>Can talk about difficult feelings (e.g., anger, sadness, worry) he experiences.</td>
                @for($x=1;$x<=3;$x++)
                    @php $field = 'q'.$x.'grade'; $id = 85; @endphp
                    <td class="text-center"><div class="grades">{{collect($setup)->where('id',$id)->first()->$field != null ? '4' : ''}}</div></td>
                @endfor
            </tr>
            <tr>
                <td style="text-align: center;">5</td>
                <td>Speaks in grammatically correct 2-3 word sentences</td>
                @for($x=1;$x<=3;$x++)
                    @php $field = 'q'.$x.'grade'; $id = 31; @endphp
                    <td class="text-center"><div class="grades">{{collect($setup)->where('id',$id)->first()->$field != null ? '4' : ''}}</div></td>
                @endfor
                <td style="text-align: center;">13</td>
                <td>Names 4 – 6 colors</td>
                @for($x=1;$x<=3;$x++)
                    @php $field = 'q'.$x.'grade'; $id = 56; @endphp
                    <td class="text-center"><div class="grades">{{collect($setup)->where('id',$id)->first()->$field != null ? '4' : ''}}</div></td>
                @endfor
                <td style="text-align: center;">5</td>
                <td>Plays peek-a-boo (bulaga)</td>
                @for($x=1;$x<=3;$x++)
                    @php $field = 'q'.$x.'grade'; $id = 69; @endphp
                    <td class="text-center"><div class="grades">{{collect($setup)->where('id',$id)->first()->$field != null ? '4' : ''}}</div></td>
                @endfor
                <td style="text-align: center;">22</td>
                <td>Honors a simple bargain with caregiver (e.g., can play outside only after cleaning/fixing his room)</td>
                @for($x=1;$x<=3;$x++)
                    @php $field = 'q'.$x.'grade'; $id = 86; @endphp
                    <td class="text-center"><div class="grades">{{collect($setup)->where('id',$id)->first()->$field != null ? '4' : ''}}</div></td>
                @endfor
            </tr>
            <tr>
                <td style="text-align: center;">6</td>
                <td>Asks “what” questions</td>
                @for($x=1;$x<=3;$x++)
                @php $field = 'q'.$x.'grade'; $id = 32; @endphp
                    <td class="text-center"><div class="grades">{{collect($setup)->where('id',$id)->first()->$field != null ? '4' : ''}}</div></td>
                @endfor
                <td style="text-align: center;">14</td>
                <td>Copies shapes</td>
                @for($x=1;$x<=3;$x++)
                    @php $field = 'q'.$x.'grade'; $id = 57; @endphp
                    <td class="text-center"><div class="grades">{{collect($setup)->where('id',$id)->first()->$field != null ? '4' : ''}}</div></td>
                @endfor
                <td style="text-align: center;">6</td>
                <td>Rolls ball interactively with caregiver/examiner</td>
                @for($x=1;$x<=3;$x++)
                    @php $field = 'q'.$x.'grade'; $id = 70; @endphp
                    <td class="text-center"><div class="grades">{{collect($setup)->where('id',$id)->first()->$field != null ? '4' : ''}}</div></td>
                @endfor
                <td style="text-align: center;">23</td>
                <td>Watches responsibly over younger siblings/family members</td>
                @for($x=1;$x<=3;$x++)
                    @php $field = 'q'.$x.'grade'; $id = 87; @endphp
                    <td class="text-center"><div class="grades">{{collect($setup)->where('id',$id)->first()->$field != null ? '4' : ''}}</div></td>
                @endfor
            </tr>
            <tr>
                <td style="text-align: center;">7</td>
                <td>Asks “who”' and “why” questions</td>
                @for($x=1;$x<=3;$x++)
                    @php $field = 'q'.$x.'grade'; $id = 33; @endphp
                    <td class="text-center"><div class="grades">{{collect($setup)->where('id',$id)->first()->$field != null ? '4' : ''}}</div></td>
                @endfor
                <td style="text-align: center;">15</td>
                <td>Names 3 animals or vegetables when  asked</td>
                @for($x=1;$x<=3;$x++)
                    @php $field = 'q'.$x.'grade'; $id = 58; @endphp
                    <td class="text-center"><div class="grades">{{collect($setup)->where('id',$id)->first()->$field != null ? '4' : ''}}</div></td>
                @endfor
                <td style="text-align: center;">7</td>
                <td>Hugs or cuddles toys</td>
                @for($x=1;$x<=3;$x++)
                    @php $field = 'q'.$x.'grade'; $id = 71; @endphp
                    <td class="text-center"><div class="grades">{{collect($setup)->where('id',$id)->first()->$field != null ? '4' : ''}}</div></td>
                @endfor
                <td style="text-align: center;">24</td>
                <td>Cooperates with adults and peers in group situations to minimize quarrels and conflicts</td>
                @for($x=1;$x<=3;$x++)
                    @php $field = 'q'.$x.'grade'; $id = 88; @endphp
                    <td class="text-center"><div class="grades">{{collect($setup)->where('id',$id)->first()->$field != null ? '4' : ''}}</div></td>
                @endfor
            </tr>
            <tr>
                <td style="text-align: center;">8</td>
                <td>Gives account of recent experiences (with prompting) in order of occurrence using past tense</td>
                @for($x=1;$x<=3;$x++)
                    @php $field = 'q'.$x.'grade'; $id = 34; @endphp
                    <td class="text-center"><div class="grades">{{collect($setup)->where('id',$id)->first()->$field != null ? '4' : ''}}</div></td>
                @endfor
                <td style="text-align: center;">16</td>
                <td>States what common household items are used for</td>
                @for($x=1;$x<=3;$x++)
                    @php $field = 'q'.$x.'grade'; $id = 59; @endphp
                    <td class="text-center"><div class="grades">{{collect($setup)->where('id',$id)->first()->$field != null ? '4' : ''}}</div></td>
                @endfor
                <td style="text-align: center;">8</td>
                <td>Demonstrates respect for elders using terms like “po” and “opo”</td>
                @for($x=1;$x<=3;$x++)
                    @php $field = 'q'.$x.'grade'; $id = 72; @endphp
                    <td class="text-center"><div class="grades">{{collect($setup)->where('id',$id)->first()->$field != null ? '4' : ''}}</div></td>
                @endfor
                <td style="text-align: center;"></td>
                <td style="text-align: right;">TOTAL</td>
                <td class="text-center">{{collect($setup)->where('group','D')->where('q1grade','!=',null)->sum('q1grade') != 0 ? collect($setup)->where('group','D')->where('q1grade','!=',null)->sum('q1grade') : ''}}</td>
                <td class="text-center">{{collect($setup)->where('group','D')->where('q2grade','!=',null)->sum('q2grade') != 0 ? collect($setup)->where('group','D')->where('q2grade','!=',null)->sum('q2grade') : ''}}</td>
                <td class="text-center">{{collect($setup)->where('group','D')->where('q3grade','!=',null)->sum('q3grade') != 0 ? collect($setup)->where('group','D')->where('q3grade','!=',null)->sum('q3grade') : ''}}</td>
            </tr>
            <tr>
                <td style="text-align: center;"></td>
                <td style="text-align: right;">TOTAL</td>
                <td class="text-center">{{collect($setup)->where('group','B')->where('q1grade','!=',null)->sum('q1grade') != 0 ? collect($setup)->where('group','B')->where('q1grade','!=',null)->sum('q1grade') : ''}}</td>
                <td class="text-center">{{collect($setup)->where('group','B')->where('q2grade','!=',null)->sum('q2grade') != 0 ? collect($setup)->where('group','B')->where('q1grade','!=',null)->sum('q2grade') : ''}}</td>
                <td class="text-center">{{collect($setup)->where('group','B')->where('q3grade','!=',null)->sum('q3grade') != 0 ? collect($setup)->where('group','B')->where('q1grade','!=',null)->sum('q3grade') : ''}}</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td style="text-align: center;">9</td>
                <td>Shares toys with others</td>
                @for($x=1;$x<=3;$x++)
                    @php $field = 'q'.$x.'grade'; $id = 73; @endphp
                    <td class="text-center"><div class="grades">{{collect($setup)->where('id',$id)->first()->$field != null ? '4' : ''}}</div></td>
                @endfor
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </table>
        <br/>
        <table style="width: 100%; font-size: 9.5px; border: 2px solid black; margin-top: 5px; page-break-inside: always;" border="1" >
            <tr>
                <th rowspan="2" style="width: 2%;"></th>
                <th rowspan="2">GROSS MOTOR</th>
                <th colspan="3" style="width: 6%;">SCORE</th>
                <th rowspan="2" style="width: 2%;"></th>
                <th rowspan="2">FINE MOTOR</th>
                <th colspan="3" style="width: 6%;">SCORE</th>
                <th rowspan="2" style="width: 2%;"></th>
                <th rowspan="2">SELF-HELP</th>
                <th colspan="3" style="width: 6%;">SCORE</th>
                <th rowspan="2" style="width: 2%;"></th>
                <th rowspan="2">SELF-HELP </th>
                <th colspan="3" style="width: 6%;">SCORE</th>
            </tr>
            <tr>
                <th>1</th>
                <th>2</th>
                <th>3</th>
                <th>1</th>
                <th>2</th>
                <th>3</th>
                <th>1</th>
                <th>2</th>
                <th>3</th>
                <th>1</th>
                <th>2</th>
                <th>3</th>
            </tr>
            <tr>
                <td style="text-align: center;">1</td>
                <td>Climbs on chair or other 
                    elevated piece of furniture 
                    like a bed without help
                    </td>
                @for($x=1;$x<=3;$x++)
                    @php $field = 'q'.$x.'grade'; $id = 89; @endphp
                    <td class="text-center"><div class="grades">{{collect($setup)->where('id',$id)->first()->$field != null ? '4' : ''}}</div></td>
                @endfor
                <td style="text-align: center;">1</td>
                <td>Uses all 5 fingers to get food/toys placed on flat surface</td>
                @for($x=1;$x<=3;$x++)
                    @php $field = 'q'.$x.'grade'; $id = 102; @endphp
                    <td class="text-center"><div class="grades">{{collect($setup)->where('id',$id)->first()->$field != null ? '4' : ''}}</div></td>
                @endfor
                <td style="text-align: center;">1</td>
                <td>Feeds self with finger food (e.g. biscuits, bread) using fingers</td>
                @for($x=1;$x<=3;$x++)
                    @php $field = 'q'.$x.'grade'; $id = 113; @endphp
                    <td class="text-center"><div class="grades">{{collect($setup)->where('id',$id)->first()->$field != null ? '4' : ''}}</div></td>
                @endfor
                <td style="text-align: center;">1</td>
                <td>Dresses without assistance except buttons and tying</td>
                @for($x=1;$x<=3;$x++)
                    @php $field = 'q'.$x.'grade'; $id = 131; @endphp
                    <td class="text-center"><div class="grades">{{collect($setup)->where('id',$id)->first()->$field != null ? '4' : ''}}</div></td>
                @endfor
            </tr>
            <tr>
                <td style="text-align: center;">2</td>
                <td>Walks backwards</td>
                @for($x=1;$x<=3;$x++)
                    @php $field = 'q'.$x.'grade'; $id = 90; @endphp
                    <td class="text-center"><div class="grades">{{collect($setup)->where('id',$id)->first()->$field != null ? '4' : ''}}</div></td>
                @endfor
                <td style="text-align: center;">2</td>
                <td>Picks up objects with thumb and index finger</td>
                @for($x=1;$x<=3;$x++)
                    @php $field = 'q'.$x.'grade'; $id = 103; @endphp
                    <td class="text-center"><div class="grades">{{collect($setup)->where('id',$id)->first()->$field != null ? '4' : ''}}</div></td>
                @endfor
                <td style="text-align: center;">2</td>
                <td>Feeds self using fingers with spillage</td>
                @for($x=1;$x<=3;$x++)
                    @php $field = 'q'.$x.'grade'; $id = 114; @endphp
                    <td class="text-center"><div class="grades">{{collect($setup)->where('id',$id)->first()->$field != null ? '4' : ''}}</div></td>
                @endfor
                <td style="text-align: center;">2</td>
                <td>Dresses without assistance including buttons and tying</td>
                @for($x=1;$x<=3;$x++)
                    @php $field = 'q'.$x.'grade'; $id = 132; @endphp
                    <td class="text-center"><div class="grades">{{collect($setup)->where('id',$id)->first()->$field != null ? '4' : ''}}</div></td>
                @endfor
            </tr>
            <tr>
                <td style="text-align: center;">3</td>
                <td>Runs without tripping or falling</td>
                @for($x=1;$x<=3;$x++)
                    @php $field = 'q'.$x.'grade'; $id = 91; @endphp
                    <td class="text-center"><div class="grades">{{collect($setup)->where('id',$id)->first()->$field != null ? '4' : ''}}</div></td>
                @endfor
                <td style="text-align: center;">3</td>
                <td>Displays a definite hand 
                    Preference</td>
                 @for($x=1;$x<=3;$x++)
                    @php $field = 'q'.$x.'grade'; $id = 231; @endphp
                    <td class="text-center"><div class="grades">{{collect($setup)->where('id',$id)->first()->$field != null ? '4' : ''}}</div></td>
                @endfor
                <td style="text-align: center;">3</td>
                <td>Feeds self using spoon with spillage</td>
                @for($x=1;$x<=3;$x++)
                    @php $field = 'q'.$x.'grade'; $id = 115; @endphp
                    <td class="text-center"><div class="grades">{{collect($setup)->where('id',$id)->first()->$field != null ? '4' : ''}}</div></td>
                @endfor
                <td style="text-align: center;">3</td>
                <td>Informs the adult only after he has already urinated (peed) or moved his bowels (pooped) in his underpants</td>
                @for($x=1;$x<=3;$x++)
                    @php $field = 'q'.$x.'grade'; $id = 133; @endphp
                    <td class="text-center"><div class="grades">{{collect($setup)->where('id',$id)->first()->$field != null ? '4' : ''}}</div></td>
                @endfor
            </tr>
            <tr>
                <td style="text-align: center;">4</td>
                <td>Walks down stairs, 2 feet on each  step, with one hand held</td>
                @for($x=1;$x<=3;$x++)
                    @php $field = 'q'.$x.'grade'; $id = 92; @endphp
                    <td class="text-center"><div class="grades">{{collect($setup)->where('id',$id)->first()->$field != null ? '4' : ''}}</div></td>
                @endfor
                <td style="text-align: center;">4</td>
                <td>Puts small objects in/out of 
                    Containers
                    </td>
                @for($x=1;$x<=3;$x++)
                    @php $field = 'q'.$x.'grade'; $id = 105; @endphp
                    <td class="text-center"><div class="grades">{{collect($setup)->where('id',$id)->first()->$field != null ? '4' : ''}}</div></td>
                @endfor
                <td style="text-align: center;">4</td>
                <td>Feeds self using fingers without spillage</td>
                @for($x=1;$x<=3;$x++)
                    @php $field = 'q'.$x.'grade'; $id = 116; @endphp
                    <td class="text-center"><div class="grades">{{collect($setup)->where('id',$id)->first()->$field != null ? '4' : ''}}</div></td>
                @endfor
                <td style="text-align: center;">4</td>
                <td>Informs adult of need to urinate (pee) or move bowels (poop) so he can be brought to a designated place (e.g. comfort room)</td>
                @for($x=1;$x<=3;$x++)
                    @php $field = 'q'.$x.'grade'; $id = 134; @endphp
                    <td class="text-center"><div class="grades">{{collect($setup)->where('id',$id)->first()->$field != null ? '4' : ''}}</div></td>
                @endfor
            </tr>
            <tr>
                <td style="text-align: center;">5</td>
                <td>Walks up stairs holding
                    handrail, 2 feet on each step
                    </td>
                @for($x=1;$x<=3;$x++)
                    @php $field = 'q'.$x.'grade'; $id = 93; @endphp
                    <td class="text-center"><div class="grades">{{collect($setup)->where('id',$id)->first()->$field != null ? '4' : ''}}</div></td>
                @endfor
                <td style="text-align: center;">5</td>
                <td>Holds crayon with all the fingers of his hand making a fist (I.e., palmar grasp)</td>
                @for($x=1;$x<=3;$x++)
                    @php $field = 'q'.$x.'grade'; $id = 106; @endphp
                    <td class="text-center"><div class="grades">{{collect($setup)->where('id',$id)->first()->$field != null ? '4' : ''}}</div></td>
                @endfor
                <td style="text-align: center;">5</td>
                <td>Feeds self using spoon without spillage</td>
                @for($x=1;$x<=3;$x++)
                    @php $field = 'q'.$x.'grade'; $id = 117; @endphp
                    <td class="text-center"><div class="grades">{{collect($setup)->where('id',$id)->first()->$field != null ? '4' : ''}}</div></td>
                @endfor
                <td style="text-align: center;">5</td>
                <td>Goes to the designated place to urinate (pee) or move bowels (poop) but sometimes still does this in his underpants</td>
                @for($x=1;$x<=3;$x++)
                    @php $field = 'q'.$x.'grade'; $id = 135; @endphp
                    <td class="text-center"><div class="grades">{{collect($setup)->where('id',$id)->first()->$field != null ? '4' : ''}}</div></td>
                @endfor
            </tr>
            <tr>
                <td style="text-align: center;">6</td>
                <td>Walks upstairs with alternate feet without holding handrail</td>
                @for($x=1;$x<=3;$x++)
                    @php $field = 'q'.$x.'grade'; $id = 94; @endphp
                    <td class="text-center"><div class="grades">{{collect($setup)->where('id',$id)->first()->$field != null ? '4' : ''}}</div></td>
                @endfor
                <td style="text-align: center;">6</td>
                <td>Unscrews lid of container or unwraps food</td>
                @for($x=1;$x<=3;$x++)
                    @php $field = 'q'.$x.'grade'; $id = 107; @endphp
                    <td class="text-center"><div class="grades">{{collect($setup)->where('id',$id)->first()->$field != null ? '4' : ''}}</div></td>
                @endfor
                <td style="text-align: center;">6</td>
                <td>Eats without need for spoonfeeding during any meal</td>
                @for($x=1;$x<=3;$x++)
                    @php $field = 'q'.$x.'grade'; $id = 118; @endphp
                    <td class="text-center"><div class="grades">{{collect($setup)->where('id',$id)->first()->$field != null ? '4' : ''}}</div></td>
                @endfor
                <td style="text-align: center;">6</td>
                <td>Goes to the designated place to urinate (pee) or move bowels (poop) and never does this in his underpants anymore</td>
                @for($x=1;$x<=3;$x++)
                    @php $field = 'q'.$x.'grade'; $id = 136; @endphp
                    <td class="text-center"><div class="grades">{{collect($setup)->where('id',$id)->first()->$field != null ? '4' : ''}}</div></td>
                @endfor
            </tr>
            <tr>
                <td style="text-align: center;">7</td>
                <td>Walks downstairs with alternate feet without holding handrail</td>
                @for($x=1;$x<=3;$x++)
                    @php $field = 'q'.$x.'grade'; $id = 95; @endphp
                    <td class="text-center"><div class="grades">{{collect($setup)->where('id',$id)->first()->$field != null ? '4' : ''}}</div></td>
                @endfor
                <td style="text-align: center;">7</td>
                <td>Scribbles spontaneously</td>
                @for($x=1;$x<=3;$x++)
                    @php $field = 'q'.$x.'grade'; $id = 108; @endphp
                    <td class="text-center"><div class="grades">{{collect($setup)->where('id',$id)->first()->$field != null ? '4' : ''}}</div></td>
                @endfor
                <td style="text-align: center;">7</td>
                <td>Helps hold cup for drinking</td>
                @for($x=1;$x<=3;$x++)
                    @php $field = 'q'.$x.'grade'; $id = 119; @endphp
                    <td class="text-center"><div class="grades">{{collect($setup)->where('id',$id)->first()->$field != null ? '4' : ''}}</div></td>
                @endfor
                <td style="text-align: center;">7</td>
                <td>Wipes/Cleans self after a bowel movement (poop)</td>
                @for($x=1;$x<=3;$x++)
                    @php $field = 'q'.$x.'grade'; $id = 137; @endphp
                    <td class="text-center"><div class="grades">{{collect($setup)->where('id',$id)->first()->$field != null ? '4' : ''}}</div></td>
                @endfor
            </tr>
            <tr>
                <td style="text-align: center;">8</td>
                <td>Moves body part as directed</td>
                @for($x=1;$x<=3;$x++)
                    @php $field = 'q'.$x.'grade'; $id = 96; @endphp
                    <td class="text-center"><div class="grades">{{collect($setup)->where('id',$id)->first()->$field != null ? '4' : ''}}</div></td>
                @endfor
                <td style="text-align: center;">8</td>
                <td>Scribbles vertical and 
                    horizontal lines
                    </td>
                @for($x=1;$x<=3;$x++)
                    @php $field = 'q'.$x.'grade'; $id = 109; @endphp
                    <td class="text-center"><div class="grades">{{collect($setup)->where('id',$id)->first()->$field != null ? '4' : ''}}</div></td>
                @endfor
                <td style="text-align: center;">8</td>
                <td>Drinks from cup with spillage</td>
                @for($x=1;$x<=3;$x++)
                    @php $field = 'q'.$x.'grade'; $id = 120; @endphp
                    <td class="text-center"><div class="grades">{{collect($setup)->where('id',$id)->first()->$field != null ? '4' : ''}}</div></td>
                @endfor
                <td style="text-align: center;">8</td>
                <td>Participates when bathing (e.g. rubbing arms with soap)</td>
                @for($x=1;$x<=3;$x++)
                    @php $field = 'q'.$x.'grade'; $id = 138; @endphp
                    <td class="text-center"><div class="grades">{{collect($setup)->where('id',$id)->first()->$field != null ? '4' : ''}}</div></td>
                @endfor
            </tr>
            <tr>
                <td style="text-align: center;">9</td>
                <td>Jumps up</td>
                @for($x=1;$x<=3;$x++)
                    @php $field = 'q'.$x.'grade'; $id = 97; @endphp
                    <td class="text-center"><div class="grades">{{collect($setup)->where('id',$id)->first()->$field != null ? '4' : ''}}</div></td>
                @endfor
                <td style="text-align: center;">9</td>
                <td>Draws circle purposefully</td>
                @for($x=1;$x<=3;$x++)
                    @php $field = 'q'.$x.'grade'; $id = 110; @endphp
                    <td class="text-center"><div class="grades">{{collect($setup)->where('id',$id)->first()->$field != null ? '4' : ''}}</div></td>
                @endfor
                <td style="text-align: center;">9</td>
                <td>Drinks from cup unassisted</td>
                @for($x=1;$x<=3;$x++)
                    @php $field = 'q'.$x.'grade'; $id = 121; @endphp
                    <td class="text-center"><div class="grades">{{collect($setup)->where('id',$id)->first()->$field != null ? '4' : ''}}</div></td>
                @endfor
                <td style="text-align: center;">9</td>
                <td>Washes and dries hands without any help</td>
                @for($x=1;$x<=3;$x++)
                    @php $field = 'q'.$x.'grade'; $id = 139; @endphp
                    <td class="text-center"><div class="grades">{{collect($setup)->where('id',$id)->first()->$field != null ? '4' : ''}}</div></td>
                @endfor
            </tr>
            <tr>
                <td style="text-align: center;">10</td>
                <td>Throws ball overhead with 
                    Direction
                    </td>
                @for($x=1;$x<=3;$x++)
                    @php $field = 'q'.$x.'grade'; $id = 98; @endphp
                    <td class="text-center"><div class="grades">{{collect($setup)->where('id',$id)->first()->$field != null ? '4' : ''}}</div></td>
                @endfor
                <td style="text-align: center;">10</td>
                <td>Draws a human figure (head, eyes, trunk, arms, hands/fingers)</td>
                @for($x=1;$x<=3;$x++)
                    @php $field = 'q'.$x.'grade'; $id = 111; @endphp
                    <td class="text-center"><div class="grades">{{collect($setup)->where('id',$id)->first()->$field != null ? '4' : ''}}</div></td>
                @endfor
                <td style="text-align: center;">10</td>
                <td>Gets drink for self unassisted</td>
                @for($x=1;$x<=3;$x++)
                    @php $field = 'q'.$x.'grade'; $id = 122; @endphp
                    <td class="text-center"><div class="grades">{{collect($setup)->where('id',$id)->first()->$field != null ? '4' : ''}}</div></td>
                @endfor
                <td style="text-align: center;">10</td>
                <td>Washes face without any help</td>
                @for($x=1;$x<=3;$x++)
                    @php $field = 'q'.$x.'grade'; $id = 140; @endphp
                    <td class="text-center"><div class="grades">{{collect($setup)->where('id',$id)->first()->$field != null ? '4' : ''}}</div></td>
                @endfor
            </tr>
            <tr>
                <td style="text-align: center;">11</td>
                <td>Hops 1 to 3 steps on preferred foot </td>
                @for($x=1;$x<=3;$x++)
                    @php $field = 'q'.$x.'grade'; $id = 99; @endphp
                    <td class="text-center"><div class="grades">{{collect($setup)->where('id',$id)->first()->$field != null ? '4' : ''}}</div></td>
                @endfor
                <td style="text-align: center;">11</td>
                <td>Draws a house using geometric forms</td>
                @for($x=1;$x<=3;$x++)
                    @php $field = 'q'.$x.'grade'; $id = 112; @endphp
                    <td class="text-center"><div class="grades">{{collect($setup)->where('id',$id)->first()->$field != null ? '4' : ''}}</div></td>
                @endfor
                <td style="text-align: center;">11</td>
                <td>Pours from pitcher without spillage</td>
                @for($x=1;$x<=3;$x++)
                    @php $field = 'q'.$x.'grade'; $id = 123; @endphp
                    <td class="text-center"><div class="grades">{{collect($setup)->where('id',$id)->first()->$field != null ? '4' : ''}}</div></td>
                @endfor
                <td style="text-align: center;">11</td>
                <td>Bathes without any help</td>
                @for($x=1;$x<=3;$x++)
                    @php $field = 'q'.$x.'grade'; $id = 141; @endphp
                    <td class="text-center"><div class="grades">{{collect($setup)->where('id',$id)->first()->$field != null ? '4' : ''}}</div></td>
                @endfor
            </tr>
            <tr>
                <td style="text-align: center;">12</td>
                <td>Jumps and turns</td>
                @for($x=1;$x<=3;$x++)
                    @php $field = 'q'.$x.'grade'; $id = 100; @endphp
                    <td class="text-center"><div class="grades">{{collect($setup)->where('id',$id)->first()->$field != null ? '4' : ''}}</div></td>
                @endfor
                <td style="text-align: center;"></td>
                <td style="text-align: right;">TOTAL</td>
                <td class="text-center">{{collect($setup)->where('group','F')->where('q1grade','!=',null)->sum('q1grade') != 0 ? collect($setup)->where('group','F')->where('q1grade','!=',null)->sum('q1grade') : ''}}</td>
                <td class="text-center">{{collect($setup)->where('group','F')->where('q2grade','!=',null)->sum('q2grade') != 0 ? collect($setup)->where('group','F')->where('q2grade','!=',null)->sum('q2grade') : ''}}</td>
                <td class="text-center">{{collect($setup)->where('group','F')->where('q3grade','!=',null)->sum('q3grade') != 0 ? collect($setup)->where('group','F')->where('q3grade','!=',null)->sum('q3grade') : ''}}</td>
                <td style="text-align: center;">12</td>
                <td>Prepares own food/snack</td>
                @for($x=1;$x<=3;$x++)
                    @php $field = 'q'.$x.'grade'; $id = 124; @endphp
                    <td class="text-center"><div class="grades">{{collect($setup)->where('id',$id)->first()->$field != null ? '4' : ''}}</div></td>
                @endfor
                <td style="text-align: center;"></td>
                <td style="text-align: right;">TOTAL</td>
                <td class="text-center">{{collect($setup)->where('group','G')->where('q1grade','!=',null)->sum('q1grade') != 0 ? collect($setup)->where('group','G')->where('q1grade','!=',null)->sum('q1grade') : ''}}</td>
                <td class="text-center">{{collect($setup)->where('group','G')->where('q2grade','!=',null)->sum('q2grade') != 0 ? collect($setup)->where('group','G')->where('q2grade','!=',null)->sum('q2grade') : ''}}</td>
                <td class="text-center">{{collect($setup)->where('group','G')->where('q3grade','!=',null)->sum('q3grade') != 0 ? collect($setup)->where('group','G')->where('q3grade','!=',null)->sum('q3grade') : ''}}</td>
            </tr>
            <tr>
                <td style="text-align: center;">13</td>
                <td>Dances patterns / joins group 
                    movement activities
                    </td>
                @for($x=1;$x<=3;$x++)
                    @php $field = 'q'.$x.'grade'; $id = 101; @endphp
                    <td class="text-center"><div class="grades">{{collect($setup)->where('id',$id)->first()->$field != null ? '4' : ''}}</div></td>
                @endfor
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td style="text-align: center;">13</td>
                <td>Prepares meals for younger siblings/family members when no adult is around</td>
                @for($x=1;$x<=3;$x++)
                    @php $field = 'q'.$x.'grade'; $id = 125; @endphp
                    <td class="text-center"><div class="grades">{{collect($setup)->where('id',$id)->first()->$field != null ? '4' : ''}}</div></td>
                @endfor
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td style="text-align: center;"></td>
                <td style="text-align: right;">TOTAL</td>
                <td class="text-center">{{collect($setup)->where('group','E')->where('q1grade','!=',null)->sum('q1grade') != 0 ? collect($setup)->where('group','E')->where('q1grade','!=',null)->sum('q1grade') : ''}}</td>
                <td class="text-center">{{collect($setup)->where('group','E')->where('q2grade','!=',null)->sum('q2grade') != 0 ? collect($setup)->where('group','E')->where('q2grade','!=',null)->sum('q2grade') : ''}}</td>
                <td class="text-center">{{collect($setup)->where('group','E')->where('q3grade','!=',null)->sum('q3grade') != 0 ? collect($setup)->where('group','E')->where('q3grade','!=',null)->sum('q3grade') : ''}}</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td style="text-align: center;">14</td>
                <td>Participates when being dressed (e.g. raises arms or lifts leg)</td>
                @for($x=1;$x<=3;$x++)
                    @php $field = 'q'.$x.'grade'; $id = 126; @endphp
                    <td class="text-center"><div class="grades">{{collect($setup)->where('id',$id)->first()->$field != null ? '4' : ''}}</div></td>
                @endfor
                <td style="text-align: center;"></td>
                <td style="text-align: right;">OVER ALL TOTAL</td>
                <td class="text-center">{{collect($setup)->where('q1grade','!=',null)->sum('q1grade') != 0 ? collect($setup)->where('q1grade','!=',null)->sum('q1grade') : ''}}</td>
                <td class="text-center">{{collect($setup)->where('q2grade','!=',null)->sum('q2grade') != 0 ? collect($setup)->where('q2grade','!=',null)->sum('q2grade') : ''}}</td>
                <td class="text-center">{{collect($setup)->where('q3grade','!=',null)->sum('q3grade') != 0 ? collect($setup)->where('q3grade','!=',null)->sum('q3grade') : ''}}</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td style="text-align: center;">15</td>
                <td>Pulls down gartered short pants</td>
                @for($x=1;$x<=3;$x++)
                    @php $field = 'q'.$x.'grade'; $id = 127; @endphp
                    <td class="text-center"><div class="grades">{{collect($setup)->where('id',$id)->first()->$field != null ? '4' : ''}}</div></td>
                @endfor
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td style="text-align: center;">16</td>
                <td>Removes sando</td>
                @for($x=1;$x<=3;$x++)
                    @php $field = 'q'.$x.'grade'; $id = 128; @endphp
                    <td class="text-center"><div class="grades">{{collect($setup)->where('id',$id)->first()->$field != null ? '4' : ''}}</div></td>
                @endfor
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </table>
        <div style="page-break-after: always"></div>
        @php
            $width = count($attendance_setup) != 0? 70 / count($attendance_setup) : 0;
        @endphp
      
        <table class="table table-bordered table-sm" style="width: 60%; margin: 20px 50px; font-size: 12px;">
            <tr>
                <th width="20%"  class="text-left">Attendance Record</th>
                @foreach ($attendance_setup as $item)
                    <th class="text-center align-middle" width="{{$width}}%">{{\Carbon\Carbon::create(null, $item->month)->isoFormat('MMM')}}</th>
                @endforeach
                <th class="text-center align-middle" width="10%">Total</th>
            </tr>
            <tr class="table-bordered">
                <td >Days of School</td>
                @foreach ($attendance_setup as $item)
                    <td class="text-center align-middle">{{$item->days != 0 ? $item->days : '' }}</td>
                @endforeach
                <th class="text-center align-middle">{{collect($attendance_setup)->sum('days')}}</td>
            </tr>
            <tr class="table-bordered">
                <td>Days Present</td>
                @foreach ($attendance_setup as $item)
                    <td class="text-center align-middle">{{$item->days != 0 ? $item->present : ''}}</td>
                @endforeach
                <th class="text-center align-middle" >{{collect($attendance_setup)->where('days','!=',0)->sum('present')}}</th>
            </tr>
            <tr class="table-bordered">
                <td>Times Tardy</td>
                @foreach ($attendance_setup as $item)
                    <td class="text-center align-middle" >{{$item->days != 0 ? $item->absent : ''}}</td>
                @endforeach
                <th class="text-center align-middle" >{{collect($attendance_setup)->sum('absent')}}</td>
            </tr>
        </table>
        </br>
        <table style="width: 60%; margin: 20px 50px; font-size: 12px;" border="1">
            <tr>
                <th style="width: 70%;">CHRISTIAN LIVING EDUCATION</th>
                <th>1</th>
                <th>2</th>
                <th>3</th>
            </tr>
            @foreach ($clsetup as $item)
            <tr>
                <td>{{$item->description}}</td>
                <td class="text-center align-middle">
                    @if($item->q1grade == 'AO')
                        <img src="{{base_path()}}/public/assets/images/spct/prekinder_star.jpg" alt="school" width="20px;">
                    @elseif($item->q1grade == 'SO')
                        <img src="{{base_path()}}/public/assets/images/spct/prekinder_heart.jpg" alt="school" width="20px;">
                    @elseif($item->q1grade == 'RO')
                        <img src="{{base_path()}}/public/assets/images/spct/prekinder_line.jpg" alt="school" width="20px;">
                    @endif
                </td>
                <td class="text-center align-middle">
                    @if($item->q2grade == 'AO')
                        <img src="{{base_path()}}/public/assets/images/spct/prekinder_star.jpg" alt="school" width="20px;">
                    @elseif($item->q2grade == 'SO')
                        <img src="{{base_path()}}/public/assets/images/spct/prekinder_heart.jpg" alt="school" width="20px;">
                    @elseif($item->q2grade == 'RO')
                        <img src="{{base_path()}}/public/assets/images/spct/prekinder_line.jpg" alt="school" width="20px;">
                    @endif
                </td>
                 <td class="text-center align-middle">
                    @if($item->q3grade == 'AO')
                        <img src="{{base_path()}}/public/assets/images/spct/prekinder_star.jpg" alt="school" width="20px;">
                    @elseif($item->q3grade == 'SO')
                        <img src="{{base_path()}}/public/assets/images/spct/prekinder_heart.jpg" alt="school" width="20px;">
                    @elseif($item->q3grade == 'RO')
                        <img src="{{base_path()}}/public/assets/images/spct/prekinder_line.jpg" alt="school" width="20px;">
                    @endif
                </td>
            </tr>
            @endforeach
        </table>
        <br/>
        <br/>
        <table style="width: 100%; font-size: 12px;">
            <tr>
                <td style="width: 17%; text-align: right;"><img src="{{base_path()}}/public/assets/images/spct/prekinder_star.jpg" alt="school" width="50px;"></td>
                <td style="width: 4%; text-align: center;">-</td>
                <td>Always observed</td>
            </tr>
            <tr>
                <td style="width: 17%; text-align: right;"><img src="{{base_path()}}/public/assets/images/spct/prekinder_heart.jpg" alt="school" width="50px;"></td>
                <td style="width: 4%; text-align: center;">-</td>
                <td>Sometimes observed</td>
            </tr>
            <tr>
                <td style="width: 17%; text-align: right;"><img src="{{base_path()}}/public/assets/images/spct/prekinder_line.jpg" alt="school" width="50px;"></td>
                <td style="width: 4%; text-align: center;">-</td>
                <td>Rarely observed</td>
            </tr>
        </table>
        <div style="page-break-after: always"></div>
        <table style="width: 100%; font-size: 22px; margin-top: 10px;">
            <tr>
                <td></td>
                <th style="width: 40%; border-bottom: 1px solid black;">{{ucwords(strtolower(DB::table('schoolinfo')->first()->schoolname))}}</th>
                <td></td>
            </tr>
            <tr>
                <th></th>
                <th>School</th>
                <th></th>
            </tr>
        </table>
        <table style="width: 100%; font-size: 11px;  margin-bottom: 0 !important">
            <td style="width: 15%; text-align: right;">CHILD'S NAME:</td>
            <td style="width: 38%; border-bottom: 1px solid black;">{{$student->student}}</td>
            <td style="width: 10%;"></td>
            <td style="width: 5%; text-align: right;">Section:</td>
            <td style=" width: 25%; border-bottom: 1px solid black;">{{$section->sectionname}}</td>
            <td></td>
        </table>
        <table style="width: 100%; font-size: 12px; margin: 25px;  margin-bottom: 5px !important ;  margin-top: 10px !important" border="1">
            <tr>
                <th colspan="3" style="font-style: italic;">1<sup>st</sup> ADMINISTRATION</th>
                <th colspan="3" style="font-style: italic;">2<sup>nd</sup> ADMINISTRATION</th>
                <th colspan="3" style="font-style: italic;">3<sup>rd</sup> ADMINISTRATION</th>
            </tr>
            <tr>
                <th>DOMAINS</th>
                <th>RAW SCORE</th>
                <th>SCALED SCORE</th>
                <th>DOMAINS</th>
                <th>RAW SCORE</th>
                <th>SCALED SCORE</th>
                <th>DOMAINS</th>
                <th>RAW SCORE</th>
                <th>SCALED SCORE</th>
            </tr>
            <tr>
                <td style="text-align: center;">Gross Motor</td>
                <td style="text-align: center;">{{collect($setup)->where('group','E')->where('q1grade','!=',null)->sum('q1grade') != 0 ? collect($setup)->where('group','E')->where('q1grade','!=',null)->sum('q1grade') : ''}}</td>
                <td style="text-align: center;">{{collect($sumsetup)->where('group','E')->first()->q1grade}}</td>
                <td style="text-align: center;">Gross Motor</td>
                <td style="text-align: center;">{{collect($setup)->where('group','E')->where('q2grade','!=',null)->sum('q2grade') != 0 ? collect($setup)->where('group','E')->where('q2grade','!=',null)->sum('q2grade') : ''}}</td>
                <td style="text-align: center;">{{collect($sumsetup)->where('group','E')->first()->q2grade}}</td>
                <td style="text-align: center;">Gross Motor</td>
                <td style="text-align: center;">{{collect($setup)->where('group','E')->where('q3grade','!=',null)->sum('q3grade') != 0 ? collect($setup)->where('group','E')->where('q3grade','!=',null)->sum('q3grade') : ''}}</td>
                <td style="text-align: center;">{{collect($sumsetup)->where('group','E')->first()->q3grade}}</td>
            </tr>
            <tr>
                <td style="text-align: center;">Fine Motor</td>
                <td style="text-align: center;">{{collect($setup)->where('group','F')->where('q1grade','!=',null)->sum('q1grade') != 0 ? collect($setup)->where('group','F')->where('q1grade','!=',null)->sum('q1grade') : ''}}</td>
                <td style="text-align: center;">{{collect($sumsetup)->where('group','F')->first()->q1grade}}</td>
                <td style="text-align: center;">Fine Motor</td>
                <td style="text-align: center;">{{collect($setup)->where('group','F')->where('q2grade','!=',null)->sum('q2grade') != 0 ? collect($setup)->where('group','F')->where('q2grade','!=',null)->sum('q2grade') : ''}}</td>
                <td style="text-align: center;">{{collect($sumsetup)->where('group','F')->first()->q2grade}}</td>
                <td style="text-align: center;">Fine Motor</td>
                <td style="text-align: center;">{{collect($setup)->where('group','F')->where('q3grade','!=',null)->sum('q3grade') != 0 ? collect($setup)->where('group','F')->where('q3grade','!=',null)->sum('q3grade') : ''}}</td>
                <td style="text-align: center;">{{collect($sumsetup)->where('group','F')->first()->q3grade}}</td>
            </tr>
            <tr>
                <td style="text-align: center;">Self-Help</td>
                <td style="text-align: center;">{{collect($setup)->where('group','G')->where('q1grade','!=',null)->sum('q1grade') != 0 ? collect($setup)->where('group','G')->where('q1grade','!=',null)->sum('q1grade') : ''}}</td>
                <td style="text-align: center;">{{collect($sumsetup)->where('group','G')->first()->q1grade}}</td>
                <td style="text-align: center;">Self-Help</td>
                <td style="text-align: center;">{{collect($setup)->where('group','G')->where('q2grade','!=',null)->sum('q2grade') != 0 ? collect($setup)->where('group','G')->where('q2grade','!=',null)->sum('q2grade') : ''}}</td>
                <td style="text-align: center;">{{collect($sumsetup)->where('group','G')->first()->q2grade}}</td>
                <td style="text-align: center;">Self-Help</td>
                <td style="text-align: center;">{{collect($setup)->where('group','G')->where('q3grade','!=',null)->sum('q3grade') != 0 ? collect($setup)->where('group','G')->where('q3grade','!=',null)->sum('q3grade') : ''}}</td>
                <td style="text-align: center;">{{collect($sumsetup)->where('group','G')->first()->q3grade}}</td>
            </tr>
            <tr>
                <td style="text-align: center;">Receptive</td>
                <td style="text-align: center;">{{collect($setup)->where('group','A')->where('q1grade','!=',null)->sum('q1grade') != 0 ? collect($setup)->where('group','A')->where('q1grade','!=',null)->sum('q1grade') : ''}}</td>
                <td style="text-align: center;">{{collect($sumsetup)->where('group','A')->first()->q1grade}}</td>
                <td style="text-align: center;">Receptive</td>
                <td style="text-align: center;">{{collect($setup)->where('group','A')->where('q2grade','!=',null)->sum('q2grade') != 0 ? collect($setup)->where('group','A')->where('q2grade','!=',null)->sum('q2grade') : ''}}</td>
                <td style="text-align: center;">{{collect($sumsetup)->where('group','A')->first()->q2grade}}</td>
                <td style="text-align: center;">Receptive</td>
                <td style="text-align: center;">{{collect($setup)->where('group','A')->where('q3grade','!=',null)->sum('q3grade') != 0 ? collect($setup)->where('group','A')->where('q3grade','!=',null)->sum('q3grade') : ''}}</td>
                <td style="text-align: center;">{{collect($sumsetup)->where('group','A')->first()->q3grade}}</td>
            </tr>
            <tr>
                <td style="text-align: center;">Expressive</td>
                <td style="text-align: center;">{{collect($setup)->where('group','B')->where('q1grade','!=',null)->sum('q1grade') != 0 ? collect($setup)->where('group','B')->where('q1grade','!=',null)->sum('q1grade') : ''}}</td>
                <td style="text-align: center;">{{collect($sumsetup)->where('group','B')->first()->q1grade}}</td>
                <td style="text-align: center;">Expressive</td>
                <td style="text-align: center;">{{collect($setup)->where('group','B')->where('q2grade','!=',null)->sum('q2grade') != 0 ? collect($setup)->where('group','B')->where('q2grade','!=',null)->sum('q2grade') : ''}}</td>
                <td style="text-align: center;">{{collect($sumsetup)->where('group','B')->first()->q2grade}}</td>
                <td style="text-align: center;">Expressive</td>
                <td style="text-align: center;">{{collect($setup)->where('group','B')->where('q3grade','!=',null)->sum('q3grade') != 0 ? collect($setup)->where('group','B')->where('q3grade','!=',null)->sum('q3grade') : ''}}</td>
                <td style="text-align: center;">{{collect($sumsetup)->where('group','B')->first()->q3grade}}</td>
            </tr>
            <tr>
                <td style="text-align: center;">Cognitive</td>
                <td style="text-align: center;">{{collect($setup)->where('group','C')->where('q1grade','!=',null)->sum('q1grade') != 0 ? collect($setup)->where('group','C')->where('q1grade','!=',null)->sum('q1grade') : ''}}</td>
                <td style="text-align: center;">{{collect($sumsetup)->where('group','C')->first()->q1grade}}</td>
                <td style="text-align: center;">Cognitive</td>
                <td style="text-align: center;">{{collect($setup)->where('group','C')->where('q2grade','!=',null)->sum('q2grade') != 0 ? collect($setup)->where('group','C')->where('q2grade','!=',null)->sum('q2grade') : ''}}</td>
                <td style="text-align: center;">{{collect($sumsetup)->where('group','C')->first()->q2grade}}</td>
                <td style="text-align: center;">Cognitive</td>
                <td style="text-align: center;">{{collect($setup)->where('group','C')->where('q3grade','!=',null)->sum('q3grade') != 0 ? collect($setup)->where('group','C')->where('q3grade','!=',null)->sum('q3grade') : ''}}</td>
                <td style="text-align: center;">{{collect($sumsetup)->where('group','C')->first()->q3grade}}</td>
            </tr>
            <tr>
                <td style="text-align: center;">Socio-Emotional</td>
                <td style="text-align: center;">{{collect($setup)->where('group','D')->where('q1grade','!=',null)->sum('q1grade') != 0 ? collect($setup)->where('group','D')->where('q1grade','!=',null)->sum('q1grade') : ''}}</td>
                <td style="text-align: center;">{{collect($sumsetup)->where('group','D')->first()->q1grade}}</td>
                <td style="text-align: center;">Socio-Emotional</td>
                <td style="text-align: center;">{{collect($setup)->where('group','D')->where('q2grade','!=',null)->sum('q2grade') != 0 ? collect($setup)->where('group','D')->where('q2grade','!=',null)->sum('q2grade') : ''}}</td>
                <td style="text-align: center;">{{collect($sumsetup)->where('group','D')->first()->q2grade}}</td>
                <td style="text-align: center;">Socio-Emotional</td>
                <td style="text-align: center;">{{collect($setup)->where('group','D')->where('q3grade','!=',null)->sum('q3grade') != 0 ? collect($setup)->where('group','D')->where('q3grade','!=',null)->sum('q3grade') : ''}}</td>
                <td style="text-align: center;">{{collect($sumsetup)->where('group','D')->first()->q3grade}}</td>
            </tr>
        </table>
        <table style="width: 100%; font-size: 12px; margin-bottom: 5px !important; margin-top: 5px !important;">
            <tr>
                <td style="width: 15%; text-align: right;">Age:</td>
                <td style="border-bottom: 1px solid black;">{{collect($ageevaldate)->where('group','A')->first()->q1grade}}</td>
                <td></td>
                <td style="width: 15%; text-align: right;">Age:</td>
                <td style="border-bottom: 1px solid black;">{{collect($ageevaldate)->where('group','A')->first()->q2grade}}</td>
                <td></td>
                <td style="width: 15%; text-align: right;">Age:</td>
                <td style="border-bottom: 1px solid black;">{{collect($ageevaldate)->where('group','A')->first()->q2grade}}</td>
                <td></td>
            </tr>
            <tr>
                <td style="width: 15%; text-align: right;">Date:</td>
                <td style="border-bottom: 1px solid black;">{{collect($ageevaldate)->where('group','B')->first()->q1grade}}</td>
                <td></td>
                <td style="width: 15%; text-align: right;">Date:</td>
                <td style="border-bottom: 1px solid black;">{{collect($ageevaldate)->where('group','B')->first()->q2grade}}</td>
                <td></td>
                <td style="width: 15%; text-align: right;">Date:</td>
                <td style="border-bottom: 1px solid black;">{{collect($ageevaldate)->where('group','B')->first()->q3grade}}</td>
                <td></td>
            </tr>
            <tr>
                <td style="width: 15%; text-align: right;">Sum of Scaled Score:</td>
                <td style="border-bottom: 1px solid black;">{{collect($ageevaldate)->where('group','C')->first()->q1grade}}</td>
                <td></td>
                <td style="width: 15%; text-align: right;">Sum of Scaled Score:</td>
                <td style="border-bottom: 1px solid black;">{{collect($ageevaldate)->where('group','C')->first()->q2grade}}</td>
                <td></td>
                <td style="width: 15%; text-align: right;">Sum of Scaled Score:</td>
                <td style="border-bottom: 1px solid black;">{{collect($ageevaldate)->where('group','C')->first()->q3grade}}</td>
                <td></td>
            </tr>
            <tr>
                <td style="text-align: right;">Standard Score: </td>
                <td style="border-bottom: 1px solid black;">{{collect($ageevaldate)->where('group','D')->first()->q1grade}}</td>
                <td></td>
                <td style="text-align: right;">Standard Score: </td>
                <td style="border-bottom: 1px solid black;">{{collect($ageevaldate)->where('group','D')->first()->q2grade}}</td>
                <td></td>
                <td style="text-align: right;">Standard Score: </td>
                <td style="border-bottom: 1px solid black;">{{collect($ageevaldate)->where('group','D')->first()->q3grade}}</td>
                <td></td>
            </tr>
        </table>
        <table style="font-size: 12px; margin: 25px; margin-bottom: 5px !important; margin-top: 5px !important" width="50%" border="1">
           <tr>
               <td width="40%" style="text-align: center;">Standard Score</td>
               <td width="60%" style="text-align: center;">Interpretation</td>
           </tr> 
            <tr>
                <td style="text-align: center;">69 and  below</td
                <td>Suggest significat delay in overall development</td>
           </tr> 
           <tr>
                <td style="text-align: center;">70 - 79</td
                <td>Suggest slight delay in overall development</td>
           </tr> 
           <tr>
                <td style="text-align: center;">80-119</td
                <td>Average overall development</td>
           </tr> 
           <tr>
                <td style="text-align: center;">120-129</td
                <td>Suggest slightly advance development</td>
           </tr> 
            <tr>
                <td style="text-align: center;">130 and above</td
                <td>Suggest highly advanced development</td>
           </tr> 
        </table>
         <table style="font-size: 12px; margin: 25px; margin-bottom: 5px !important; margin-top: 15px !important" width="50%">
           <tr>
               <td width="10%" style="padding:0 !important;"></td>
               <td width="20%" style="text-align: center; padding:0 !important;">Promoted to:</td>
               <td width="70%" style="text-align: center; border-bottom: 1px black solid !important; padding:0 !important;"></td>
           </tr> 
           
        </table>
        <table style="font-size: 12px; margin: 25px; margin-bottom: 5px !important; margin-top: 30px !important" width="100%" >
            <tr>
               <td width="50%" style="text-align: center;"><u>{{$adviser}}</u></td>
               <td width="50%" style="text-align: center;"><u>{{$principal}}</u></td>
            </tr> 
            <tr>
                <td style="text-align: center;">Adviser</td
                <td style="text-align: center;">Principal</td>
            </tr> 
        </table>
    </body>
</html>