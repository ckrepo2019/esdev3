<style>
    * { font-family: Arial, Helvetica, sans-serif;}
    @page { margin: 48px 20px;}

    #table1 td{
        padding: 0px;
    }
    table {
        border-collapse: collapse;
    }
    #table2{
        margin-top: 2px;
        font-size: 11px;
    }

    input[type="checkbox"] {
    /* position: relative; */
    top: 2px;
    box-sizing: content-box;
    width: 14px;
    height: 14px;
    margin: 0 5px 0 0;
    cursor: pointer;
    -webkit-appearance: none;
    border-radius: 2px;
    background-color: #fff;
    border: 1px solid #b7b7b7;
    }

    input[type="checkbox"]:before {
    content: '';
    display: block;
    }

    input[type="checkbox"]:checked:before {
    width: 4px;
    height: 9px;
    margin: 0px 4px;
    border-bottom: 2px solid ;
    border-right: 2px solid ;
    transform: rotate(45deg);
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

@php

$guardianinfo = DB::table('studinfo')
    ->where('id',$student->id)
    ->first();

$guardianname = '';
if($guardianinfo->fathername == null)
{
    $guardianname.=$guardianinfo->guardianname;
}else{
    
    $explodename = explode(',',$guardianinfo->fathername);
    if(count($explodename)>1)
    {
        $guardianname.='MR. AND MRS. ';
        $explodelastname = $explodename[0];
        
        $firstname = explode(' ',$explodename[1]);
        if(count($firstname) < 3)
        {
            $guardianname.=$firstname[0];
        }
        else
        {
            $guardianname.=$firstname[0].' '.$firstname[1].' ';
        }
        $guardianname.=$explodelastname;
    }
    
}
$address = '';
if($guardianinfo->street != null)
{
    $address.=$guardianinfo->street.', ';
}
if($guardianinfo->barangay != null)
{
    $address.=$guardianinfo->barangay.', ';
}
if($guardianinfo->city != null)
{
    $address.=$guardianinfo->city.', ';
}
if($guardianinfo->province != null)
{
    $address.=$guardianinfo->province;
}

@endphp
<table style="width: 100%; font-size: 11px;">
    <tr>
        <td style="width: 32%; vertical-align: top; padding: 0px;">
            <table style="width: 100%; border-collapse: collapse;" border="1">
                <tr style="font-size: 12px !important;">
                    <th style="width: 65%;">HEALTH, WELL-BEING, AND MOTOR DEVELOPMENT</th>
                    <th>1</th>
                    <th>2</th>
                    <th>3</th>
                    <th>4</th>
                </tr>
                @foreach (collect($setup)->where('group','A') as $item)
                    <tr style="font-size: 9.8px !important;">
                        <td style="font-style: italic; padding: 2px 5px;">{{$item->description}}</td>
                        <td class="text-center align-middle">{{$item->grade[0]->q1grade}}</td>
                        <td class="text-center align-middle">{{$item->grade[0]->q2grade}}</td>
                        <td class="text-center align-middle">{{$item->grade[0]->q3grade}}</td>
                        <td class="text-center align-middle">{{$item->grade[0]->q4grade}}</td>
                    </tr>
                @endforeach
                
            </table>
            <br/>
            <br/>
            <table style="width: 100%; border-collapse: collapse;" border="1">
                <tr style="font-size: 12px !important;">
                    <th style="width: 65%;">MATHEMATICS</th>
                    <th>1</th>
                    <th>2</th>
                    <th>3</th>
                    <th>4</th>
                </tr>
                @foreach (collect($setup)->where('group','B') as $item)
                    <tr style="font-size: 9.8px !important;">
                        <td style="font-style: italic; padding: 2px 5px;">{{$item->description}}</td>
                        <td class="text-center align-middle">{{$item->grade[0]->q1grade}}</td>
                        <td class="text-center align-middle">{{$item->grade[0]->q2grade}}</td>
                        <td class="text-center align-middle">{{$item->grade[0]->q3grade}}</td>
                        <td class="text-center align-middle">{{$item->grade[0]->q4grade}}</td>
                    </tr>
                @endforeach
            </table>
        </td>
        <td style="width: 2%;"></td>
        <td style="width: 32%; vertical-align: top; padding: 0px;">
            <table style="width: 100%; border-collapse: collapse;" border="1">
                <tr style="font-size: 12px !important;">
                    <th style="width: 65%;">LANGUAGE, LITERACY, AND COMMUNICATION</th>
                    <th>1</th>
                    <th>2</th>
                    <th>3</th>
                    <th>4</th>
                </tr>
                <tr style="font-size: 12px !important; background-color: #b3ccff;">
                    <td style="font-style: italic; padding: 2px 5px;">Listening and Viewing</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                @foreach (collect($setup)->where('group','CA') as $item)
                    <tr style="font-size: 9.8px !important;">
                        <td style="font-style: italic; padding: 2px 5px;">{{$item->description}}</td>
                        <td class="text-center align-middle">{{$item->grade[0]->q1grade}}</td>
                        <td class="text-center align-middle">{{$item->grade[0]->q2grade}}</td>
                        <td class="text-center align-middle">{{$item->grade[0]->q3grade}}</td>
                        <td class="text-center align-middle">{{$item->grade[0]->q4grade}}</td>
                    </tr>
                @endforeach
                <tr style="font-size: 12px !important; background-color: #b3ccff;">
                    <td style="font-style: italic; padding: 2px 5px;">Speaking</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                @foreach (collect($setup)->where('group','CB') as $item)
                    <tr style="font-size: 9.8px !important;">
                        <td style="font-style: italic; padding: 2px 5px;">{{$item->description}}</td>
                        <td class="text-center align-middle">{{$item->grade[0]->q1grade}}</td>
                        <td class="text-center align-middle">{{$item->grade[0]->q2grade}}</td>
                        <td class="text-center align-middle">{{$item->grade[0]->q3grade}}</td>
                        <td class="text-center align-middle">{{$item->grade[0]->q4grade}}</td>
                    </tr>
                @endforeach
                <tr style="font-size: 12px !important; background-color: #b3ccff;">
                    <td style="font-style: italic; padding: 2px 5px;">Reading</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                @foreach (collect($setup)->where('group','CC') as $item)
                    <tr style="font-size: 9.8px !important;">
                        <td style="font-style: italic; padding: 2px 5px;">{{$item->description}}</td>
                        <td class="text-center align-middle">{{$item->grade[0]->q1grade}}</td>
                        <td class="text-center align-middle">{{$item->grade[0]->q2grade}}</td>
                        <td class="text-center align-middle">{{$item->grade[0]->q3grade}}</td>
                        <td class="text-center align-middle">{{$item->grade[0]->q4grade}}</td>
                    </tr>
                @endforeach
                <tr style="font-size: 12px !important; background-color: #b3ccff;">
                    <td style="font-style: italic; padding: 0px 5px;">Writing</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                @foreach (collect($setup)->where('group','CD') as $item)
                    <tr style="font-size: 9.8px !important;">
                        <td style="font-style: italic; padding: 2px 5px;">{{$item->description}}</td>
                        <td class="text-center align-middle">{{$item->grade[0]->q1grade}}</td>
                        <td class="text-center align-middle">{{$item->grade[0]->q2grade}}</td>
                        <td class="text-center align-middle">{{$item->grade[0]->q3grade}}</td>
                        <td class="text-center align-middle">{{$item->grade[0]->q4grade}}</td>
                    </tr>
                @endforeach
            </table>
        </td>
        <td style="width: 2%;"></td>
        <td style="width: 32%; vertical-align: top; padding: 0px;">
            <table style="width: 100%;" border="1">
                <tr style="font-size: 12px !important;">
                    <th style="width: 65%; font-style: italic; padding: 0px 5px; font-weight: bold;" >SOCIO-EMOTIONAL DEVELOPMENT</th>
                    <th>1</th>
                    <th>2</th>
                    <th>3</th>
                    <th>4</th>
                </tr>
                @foreach (collect($setup)->where('group','D') as $item)
                    <tr style="font-size: 9.8px !important;">
                        <td style="font-style: italic; padding: 2px 5px;">{{$item->description}}</td>
                        <td class="text-center align-middle">{{$item->grade[0]->q1grade}}</td>
                        <td class="text-center align-middle">{{$item->grade[0]->q2grade}}</td>
                        <td class="text-center align-middle">{{$item->grade[0]->q3grade}}</td>
                        <td class="text-center align-middle">{{$item->grade[0]->q4grade}}</td>
                    </tr>
                @endforeach
            </table>
            <br/>
            <table style="width: 100%;" border="1">
                <tr style="font-size: 12px !important;">
                    <th style="width: 65%; font-style: italic; padding: 0px 5px; font-weight: bold;" >UNDERSTANDING THE PHYSICAL AND NATURAL ENVIRONMENT</th>
                    <th>1</th>
                    <th>2</th>
                    <th>3</th>
                    <th>4</th>
                </tr>
                @foreach (collect($setup)->where('group','E') as $item)
                    <tr style="font-size: 9.8px !important;">
                        <td style="font-style: italic; padding: 2px 5px;">{{$item->description}}</td>
                        <td class="text-center align-middle">{{$item->grade[0]->q1grade}}</td>
                        <td class="text-center align-middle">{{$item->grade[0]->q2grade}}</td>
                        <td class="text-center align-middle">{{$item->grade[0]->q3grade}}</td>
                        <td class="text-center align-middle">{{$item->grade[0]->q4grade}}</td>
                    </tr>
                @endforeach
            </table>
            <br/>
            <table style="width: 100%;" border="1">
                <tr style="font-size: 12px !important;">
                    <th style="width: 65%; font-style: italic; padding: 0px 5px; font-weight: bold;" >CHRISTIAN LIVING EDUCATION</th>
                    <th>1</th>
                    <th>2</th>
                    <th>3</th>
                    <th>4</th>
                </tr>
                @foreach (collect($setup)->where('group','F') as $item)
                    <tr style="font-size: 9.8px !important;">
                        <td style="font-style: italic; padding: 2px 5px;">{{$item->description}}</td>
                        <td class="text-center align-middle">{{$item->grade[0]->q1grade}}</td>
                        <td class="text-center align-middle">{{$item->grade[0]->q2grade}}</td>
                        <td class="text-center align-middle">{{$item->grade[0]->q3grade}}</td>
                        <td class="text-center align-middle">{{$item->grade[0]->q4grade}}</td>
                    </tr>
                @endforeach
            </table>
            <br/>
            <br/>
            <table style="width: 100%; margin: 0px 20px;" border="1">
                <tr style="font-size: 12px;">
                    <th colspan="2">RATING SCALE</th>
                </tr>
                <tr style="font-size: 9.8px !important;">
                    <th style="width: 35%;">Rating</th>
                    <th>Indicators</th>
                </tr>
                <tr style="font-size: 9.8px !important;">
                    <th rowspan="3">Beginning (B)</th>
                    <td style=" padding: 0px 5px; font-style: italic;">Rarely demonstrates the expected competency</td>
                </tr>
                <tr style="font-size: 9.8px !important;">
                    <td style=" padding: 0px 5px; font-style: italic;">Rarely participates in class activities and /or initiates  independent works</td>
                </tr>
                <tr style="font-size: 9.8px !important;">
                    <td style=" padding: 0px 5px; font-style: italic;">Shows interest in doing tasks but needs close supervision</td>
                </tr>
                <tr style="font-size: 9.8px !important;">
                    <th rowspan="3">Developing (D)</th>
                    <td style=" padding: 0px 5px; font-style: italic;">Sometimes demonstrates the competency</td>
                </tr>
                <tr style="font-size: 9.8px !important;">
                    <td style=" padding: 0px 5px; font-style: italic;">Sometimes participates, minimal supervision</td>
                </tr>
                <tr style="font-size: 9.8px !important;">
                    <td style=" padding: 0px 5px; font-style: italic;">Progresses continuously in doing assigned tasks</td>
                </tr>
                <tr style="font-size: 9.8px !important;">
                    <th rowspan="3">Consistent (C)</th>
                    <td style=" padding: 0px 5px; font-style: italic;">Always demonstrates the expected competency</td>
                </tr>
                <tr style="font-size: 9.8px !important;">
                    <td style=" padding: 0px 5px; font-style: italic;">Always participates in the different activities, works independently</td>
                </tr>
                <tr style="font-size: 9.8px !important;">
                    <td style=" padding: 0px 5px; font-style: italic;">Always performs tasks, advanced in some aspects</td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<div style="page-break-inside: always"></div>
<table style="width: 100%; font-size: 11px;">
    <tr>
        <td style="width: 32%; vertical-align: top; padding: 0px;">
            @php
                $width = count($attendance_setup) != 0? 70 / count($attendance_setup) : 0;
            @endphp
          
            <table class="table table-bordered table-sm" width="100%" style="width: 100%; font-size: 9px !important;">
                <tr>
                    <th width="20%"  class="text-left">Attendance Record</th>
                    @foreach ($attendance_setup as $item)
                        <th class="text-center align-middle" width="{{$width}}%">{{$item->days != 0 ? \Carbon\Carbon::create(null, $item->month)->isoFormat('MMM') : ''}}</th>
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
          
            <br/>
            <br/>
            <div style="text-align: center; font-weight: bold; font-size: 12px;">TEACHER’S COMMENTS/REMARKS</div>
            <br/>
            <table style="width: 100%;" border="1" >
                <tr>
                    <td>
                        <table style="width: 100%; font-style: italic;">
                            <tr>
                                <th >First Quarter (Weeks 1-10)</th>
                            </tr>
                            <tr>
                                <th>&nbsp;</th>
                            </tr>
                            <tr>
                                <td class="text-center"><u>{{collect($remarks_setup)->where('group','A')->first()->q1grade}}</u></td>
                            </tr>
                        </table>
                        <br/>
                        <table style="width: 100%; font-style: italic;">
                            <tr>
                                <td></td>
                                <td style="width: 60%; border-bottom: 1px solid black;">&nbsp;</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="3" style="text-align: center;">Parent or Guardian’s Signature</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table style="width: 100%; font-style: italic;">
                            <tr>
                                <th >Second Quarter (Weeks 11-20)</th>
                            </tr>
                            <tr>
                                <th>&nbsp;</th>
                            </tr>
                            <tr>
                                <td class="text-center"><u>{{collect($remarks_setup)->where('group','A')->first()->q2grade}}</u></td>
                            </tr>
                        </table>
                        <br/>
                        <table style="width: 100%; font-style: italic;">
                            <tr>
                                <td></td>
                                <td style="width: 60%; border-bottom: 1px solid black;">&nbsp;</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="3" style="text-align: center;">Parent or Guardian’s Signature</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table style="width: 100%; font-style: italic;">
                            <tr>
                                <th >Third Quarter (Weeks 21-30)</th>
                            </tr>
                            <tr>
                                <th>&nbsp;</th>
                            </tr>
                            <tr>
                                <td class="text-center"><u>{{collect($remarks_setup)->where('group','A')->first()->q3grade}}</u></td>
                            </tr>
                        </table>
                        <br/>
                        <table style="width: 100%; font-style: italic;">
                            <tr>
                                <td></td>
                                <td style="width: 60%; border-bottom: 1px solid black;">&nbsp;</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="3" style="text-align: center;">Parent or Guardian’s Signature</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table style="width: 100%; font-style: italic;">
                            <tr>
                                <th >Fourth Quarter (Weeks 31-40)</th>
                            </tr>
                            <tr>
                                <th>&nbsp;</th>
                            </tr>
                            <tr>
                                <td class="text-center"><u>{{collect($remarks_setup)->where('group','A')->first()->q4grade}}</u></td>
                            </tr>
                        </table>
                        <br/>
                        <table style="width: 100%; font-style: italic;">
                            <tr>
                                <td></td>
                                <td style="width: 60%; border-bottom: 1px solid black;">&nbsp;</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="3" style="text-align: center;">Parent or Guardian’s Signature</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
        <td style="width: 2%;"></td>
        <td style="width: 32%; vertical-align: top; padding: 0px;">
            <table style="width: 100%; border: 1px dashed black; text-align: justify; font-style: italic; font-size: 12px;">
                <tr>
                    <td style="padding: 8px;">Dear Parents:</td>
                </tr>
                <tr>
                    <td style="padding: 8px; line-height: 20px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;The purpose of this progress report card is to inform you about your child’s learning achievement based on the Kindergarten Curriculum Guide. This reflects a summary of your child’s learning performance. It identifies your child’s level of progress in different domains of development (not necessarily academic) every ten (10) weeks or quarter so that we know if additional time and follow-up are needed to make your child achieve the competencies expected of a five (5) year old.</td>
                </tr>
            </table>
            <br/>
            <br/>
            <br/>            
            <div style="text-align: left; font-weight: bold; font-size: 12px;">VISION</div>
            <div style="width: 100%; text-align: justify; font-style: italic; font-size: 12px;">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;A Catholic educational community of evangelizers inspired by the Blessed Virgin Mary and Mother Rivier, that envisions to be Christ-centered, academically competent, proud of Filipino culture, and responsive to social and global issues and concerns.
            </div>
            <br/>
            <div style="text-align: left; font-weight: bold; font-size: 12px;">MISSION</div>
            <div style="width: 100%; text-align: justify; font-style: italic; font-size: 12px;">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Guided by our Vision, we commit ourselves to:
                <br/>
                <br/>
                a. strengthen moral and spiritual values in our personal and communal relationships;
                <br/>
                <br/>
                b. be academically competent in various disciplines and in the field of research;
                <br/>
                <br/>
                c. take pride in our cultural heritage and respect diversity of peoples and nations;
                <br/>
                <br/>
                d. be responsible in the utilization of organizational resources.
            </div>
        </td>
        <td style="width: 2%;"></td>
        <td style="width: 32%; vertical-align: top; padding: 0px;">
            <table style="width: 100%; text-align: center;">
                <tr>
                    <th>Saint Peter’s College of Toril</th>
                </tr>
                <tr>
                    <th>&nbsp;</th>
                </tr>
                <tr>
                    <td>Mc Arthur Highway, Toril, Davao City</td>
                </tr>
            </table>
            <br/>
            <br/>
            <br/>
            <table style="width: 100%; text-align: center;">
                <tr>
                    <th>PROGRESS REPORT CARD</th>
                </tr>
                <tr>
                    <th>&nbsp;</th>
                </tr>
                <tr>
                    <th>Kindergarten</th>
                </tr>
                <tr>
                    <th>&nbsp;</th>
                </tr>
                <tr>
                    <td>Government Recognition No. 021, s. 1980</td>
                </tr>
                <tr>
                    <th>&nbsp;</th>
                </tr>
                <tr>
                    <td>School Year</td>
                </tr>
            </table>
            <br/>
            <br/>
            <table style="width: 100%; margin: 0px 20px;">
                <tr>
                    <td style="width: 15%;">Name:</td>
                    <td colspan="3" style="border-bottom: 1px solid black;" class="text-center">{{$student->firstname}} {{$student->lastname}}</td>
                </tr>
                <tr>
                    <td colspan="4">&nbsp;</td>
                </tr>
                <tr>
                    <td>Section:</td>
                    <td style="border-bottom: 1px solid black;" class="text-center">{{$section->sectionname}}</td>
                    <td style="width: 15%;">Gender:</td>
                    <td style="border-bottom: 1px solid black;" class="text-center">{{$student->gender}}</td>
                </tr>
            </table>
            <br/>
            <br/>
            <br/>
            <table style="width: 100%; margin: 0px 20px;">
                <tr>
                    <td style="width: 60%;">Age of the Child at the Beginning of the SY:</td>
                    <td style="width: 5%;">Years</td>
                    <td style="border-bottom: 1px solid black;" class="text-center">{{collect($age_setup)->where('group','A')->first()->q1grade}}</td>
                    <td style="width: 5%;">Months</td>
                    <td style="border-bottom: 1px solid black;" class="text-center">{{collect($age_setup)->where('group','A')->first()->q2grade}}</td>
                </tr>
                <tr>
                    <td colspan="5">&nbsp;</td>
                </tr>
                <tr>
                    <td style="width: 60%;">Age of the Child at the End of the SY:     </td>
                    <td style="width: 5%;">Years</td>
                    <td style="border-bottom: 1px solid black;" class="text-center">{{collect($age_setup)->where('group','B')->first()->q1grade}}</td>
                    <td style="width: 5%;">Months</td>
                    <td style="border-bottom: 1px solid black;" class="text-center">{{collect($age_setup)->where('group','B')->first()->q2grade}}</td>
                </tr>
            </table>
            <br/>
            <br/>
            <br/>
            <br/>
            <br/>
            <br/>
            <br/>
            <br/>
            <table style="width: 100%; margin: 0px 20px;">
                <tr>
                    <td style="width: 20%;">Promoted to:</td>
                    <td style="width: 40%;; border-bottom: 1px solid black;"></td>
                    <td></td>
                </tr>
            </table>
            <br/>
            <br/>
            <br/>
            <br/>
            <br/>
            <br/>
            <table style="width: 100%; margin: 0px 20px;">
                <tr>
                    <td width="40%"></td>
                    <td width="20%"></td>
                    <td width="40%"></td>
                </tr>
                <tr>
                    <td style="border-bottom: 1px solid black;" class="text-center">{{$adviser}}</td>
                    <td></td>
                    <td style="border-bottom: 1px solid black;" class="text-center">{{$principal}}</td>
                </tr>
                <tr>
                    <td style="text-align: center;">Adviser</td>
                    <td></td>
                    <td style="text-align: center;">Principal</td>
                </tr>
            </table>
        </td>
    </tr>
</table>