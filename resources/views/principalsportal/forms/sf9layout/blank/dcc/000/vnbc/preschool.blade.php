<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <style>
                html                        { font-family: Arial, Helvetica, sans-serif;  }

                table                       { border-collapse: collapse; }

                .text-center                { text-align: center !important; }

                .table-bordered             { border: 1px solid black; }

                .table-bordered th,
                .table-bordered td          { border: 1px solid black; }

                .pl-4, .px-4                { padding-left: 1.5rem!important; }
                @page                       { margin: 50px !important; }
        </style>    
    </head>
    <body>
        <table style="width: 100%; font-size: 12px;">
            <tr>
                <td rowspan="5" style="width: 12%;">
                    <img src="{{base_path()}}/public/{{$schoolinfo[0]->picurl}}" alt="school" width="80px">
                </td>
                <td style="font-weight: bold;">
                    Republic of the Philippines
                </td>
                <td style="text-align: center;font-weight: bold;">
                    KINDERGATEN PROGRESS REPORT
                </td>
            </tr>
            <tr>
                <td style="font-weight: bold;">
                    Department of Education
                </td>
                <td style="text-align: center;font-weight: bold;">
                    SY {{$schoolyear->sydesc}}
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    Region: {{$schoolinfo[0]->regDesc}}
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    Division: {{$schoolinfo[0]->citymunDesc}}
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    School: {{$schoolinfo[0]->schoolname}}
                </td>
            </tr>

        </table>
        <br/>
        <table style="width: 100%; font-size: 12px;" >
            <tr>
                <td>
                    <span>Name: <u>&nbsp;{{$student[0]->lastname}}, {{$student[0]->firstname}} {{$student[0]->firstname[0].'.'}}&nbsp;</u></span>
                </td>
            </tr>
            <tr>
                <td>
                    <span>Section: {{$student[0]->levelname}} - {{$student[0]->ensectname}}</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span>Teacher: </span>
                </td>
            </tr>
            <tr>
                <td>
                    <span>Age of Child at the Beginning of the SY:</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span>Age of the Child at the End of the SY:</span>
                </td>
            </tr>
        </table>
        <br/>
        <div style="width: 100%; border: 1px solid black; font-size: 12px;padding: 5px;text-align: justify;">
            The purpose of this progress report is to inform parents about their child’s learning achievement based on the Kindergarten Curriculum Guide. This reflects a summary of your child’s learning performance. It identifies you child’s levels of progress in different domains of development (not necessarily academic) every ten (10) weeks or quarter so that we know if additional time and follow-up are needed to make your child achieve the competencies expected of a five (5) year old. 
        </div>
        <br/>
        <div style="width: 100%; text-align: center; font-weight: bold; font-size: 12px;">
            Each competency will be marked with: Beginning (B); Developing (D) or; Consistent (C)
        </div>

        @foreach (collect($checkGrades)->groupBy('group') as $groupitem)
            <table style="width: 100%; font-size: 12px;" border="2">
                @foreach ($groupitem as $item)
                    @if($item->value == 0)
                        <tr>
                            <th style="text-align: left;width:70%;border: 3px solid black;">{{$item->description}}</th>
                            <th style="border: 3px solid black;">Q1</th>
                            <th style="border: 3px solid black;">Q2</th>
                            <th style="border: 3px solid black;">Q3</th>
                            <th style="border: 3px solid black;">Q4</th>
                        </tr>
                    @else
                        <tr>
                            <td class="align-middle"  style="width:70%;">{{$item->description}}</td>
                            <td class="text-center">
                                
                            </td>
                            <td class="text-center">
                                
                            </td>
                            <td class="text-center">
                                
                            </td>
                            <td class="text-center">
                                
                            </td>
                        </tr>
                    @endif
                @endforeach
            </table>
            <br/>
        @endforeach
        <div style="width: 100%; text-align: center; font-size: 12px; font-weight: bold">
            RATING SCALE
        </div>
        <table border="1" style="width: 100%;font-size: 12px;">
            <tr>
                <th style="width: 30%;border: 3px solid black;">Rating</th>
                <th style="border: 3px solid black;">Indicators</th>
            </tr>
            <tr>
                <td rowspan="3" style="text-align: center;font-weight: bold;">Beginning (B)</td>
                <td>
                    Rarely demonstrates the expected competency
                </td>
            </tr>
            <tr>
                <td>Rarely participates in class activities and/or initiates independent works</td>
            </tr>
            <tr>
                <td>Shows interest in doing tasks but needs close supervision</td>
            </tr>
            <tr>
                <td rowspan="3" style="text-align: center;font-weight: bold;">Developing (D)</td>
                <td>
                    Sometimes demonstrates the competency
                </td>
            </tr>
            <tr>
                <td>Sometimes participates, minimal supervision</td>
            </tr>
            <tr>
                <td>Progresses continuously in doing assigned tasks</td>
            </tr>
            <tr>
                <td rowspan="3" style="text-align: center;font-weight: bold;">Consistent (C) </td>
                <td>
                    Always demonstrates the expected competency
                </td>
            </tr>
            <tr>
                <td>Always participates in the different activities, works independently</td>
            </tr>
            <tr>
                <td>Always perform tasks, advanced in some aspects</td>
            </tr>
        </table>
        <br/>
        <div style="width: 100%; text-align: center; font-size: 12px; font-weight: bold">
            TEACHER’S COMMENTS/REMARKS
        </div>
        <table style="width: 100%;font-size: 12px;table-layout: fixed;">
            <tr>
                <td style="padding-left: 20%;">
                    <div style="width: 98%;border: 1px solid black;padding: 3px;">
                        <div style="text-align: center; width: 100%;">First Quarter (Weeks 1-10)</div>
                        <br/>
                        <div style="text-align: justify; width: 100%;padding: 3px;overflow:hidden;">
                            <u style="font-weight: bold;">She shows enthusiasm for classroom activities. She enjoys conversation with friends during free time. </u>
                        </div>
                        <br/>
                        <br/>
                        <div style="border-bottom: 1px solid black;margin-left:50px;;margin-right:50px;"></div>
                        <br/>
                        <div style="margin-left:50px;;margin-right:50px;text-align: center;">Parent or Guardian’s Signature</div>
                        
                    </div>
                </td>
                <td style="padding-left: 2%%;">
                    <div style="width: 78%;border: 1px solid black;padding: 3px;">
                        <div style="text-align: center; width: 100%;">Second Quarter (Weeks 11-20)</div>
                        <br/>
                        <div style="text-align: justify; width: 100%;padding: 3px;overflow:hidden;">
                            <u style="font-weight: bold;">She is one of the most participative students. She is always has confidence in when being ask like, classroom recitations. </u>
                        </div>
                        <br/>
                        <br/>
                        <div style="border-bottom: 1px solid black;margin-left:50px;;margin-right:50px;"></div>
                        <br/>
                        <div style="margin-left:50px;;margin-right:50px;text-align: center;">Parent or Guardian’s Signature</div>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="padding-left: 20%;">
                    <div style="width: 98%;border: 1px solid black;padding: 3px;">
                        <div style="text-align: center; width: 100%;">Third Quarter (Weeks 21-30)</div>
                        <br/>
                        <div style="text-align: justify; width: 100%;padding: 3px;overflow:hidden;">
                            <u style="font-weight: bold;">She is competitive and friendly. She speaks with confidence to the group. She is always willing to offer help. </u>
                        </div>
                        <br/>
                        <br/>
                        <div style="border-bottom: 1px solid black;margin-left:50px;;margin-right:50px;"></div>
                        <br/>
                        <div style="margin-left:50px;;margin-right:50px;text-align: center;">Parent or Guardian’s Signature</div>
                        <br/>
                        
                    </div>
                </td>
                <td style="padding-left: 2%%;">
                    <div style="width: 78%;border: 1px solid black;padding: 3px;">
                        <div style="text-align: center; width: 100%;">Fourth Quarter (Weeks 31-40)</div>
                        <br/>
                        <div style="text-align: justify; width: 100%;padding: 3px;overflow:hidden;">
                            <u style="font-weight: bold;">She has a vibrant imagination and not afraid to share it during our circle time. I’m happy to have her in our class. </u>
                        </div>
                        <br/>
                        <br/>
                        <br/>
                        <br/>
                        {{-- <div style="border-bottom: 1px solid black;margin-left:50px;;margin-right:50px;"></div> --}}
                        <br/>
                        {{-- <div style="margin-left:50px;;margin-right:50px;text-align: center;">Parent or Guardian’s Signature</div> --}}
                    </div>
                </td>
            </tr>
        </table>
        <br/>
        <div style="width: 100%; padding-left: 200px; padding-right: 200px; font-size: 12px;font-weight: bold;">
            <div style="width: 100%; text-align: center;">
                ATTENDANCE RECORD
            </div>
            <table style="width: 100%;" border="1">
                <tr>
                    <th style="width: 50%;"></th>
                    <th>Q1</th>
                    <th>Q2</th>
                    <th>Q3</th>
                    <th>Q4</th>
                </tr>
                <tr>
                    <th style="text-align: left;">&nbsp;&nbsp;Days Present</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
                <tr>
                    <th style="text-align: left;">&nbsp;&nbsp;Days Absent</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
                <tr>
                    <th style="text-align: left;">&nbsp;&nbsp;Days Tardy</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
                <tr>
                    <th style="text-align: left;">&nbsp;&nbsp;Days Incomplete</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </table>
        </div>
        <br/>
        <div style="width: 100%;text-align: justify;font-size: 12px;">
            This is to certify that <u style="font-weight: bold;">{{$student[0]->firstname}} {{$student[0]->middlename[0].'.'}} {{$student[0]->lastname}}</u> of <u style="font-weight: bold;">{{$schoolinfo[0]->schoolname}}</u> has developed the general based on the Kindergarten Curriculum Guide.
        </div>
        <br/>
        <div style="width: 100%;text-align: center;font-size: 12px;font-weight: bold;">
            <u>(Title. First Name Middle Initial. Last Name)</u>
            <br/>
            Teacher's Signature
            <br/>
            <br/>
            <br/>
            <u>(Title. First Name Middle Initial. Last Name)</u>
            <br/>
            Pre-School Coordinator’s Signature
            <br/>
            <br/>
            <br/>
            <u>(Title. First Name Middle Initial. Last Name)</u>
            <br/>
            School Head’s Signature
        </div>
    </body>
</html>