<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <style>
                html                        { font-family: Arial, Helvetica, sans-serif;  font-size: 12px;}

                table                       { border-collapse: collapse; }

                .text-center                { text-align: center !important; }

                .table-bordered             { border: 1px solid black; }

                .table-bordered th,
                .table-bordered td          { border: 1px solid black; }

                .pl-4, .px-4                { padding-left: 1.5rem!important; }
                @page                       { size: 13in 8.5in;margin: 20 20 0 20!important; }
                .page_break { page-break-before: always; }

        </style>    
    </head>
    <body >
        {{-- <br/> --}}
        {{-- <br/> --}}
        <br/>
        <table style="width: 100%; table-layout: fixed;">
            <tr>
                <td style="width:33.3333333333%">
                    <div style="text-align: center; font-weight: bold;">TEACHER'S COMMENTS / REMARKS</div>
                    {{-- <br/> --}}
                    <table style="width:100%;margin-right: 20px;margin-top: 8px;">
                        <tr>
                            <td style="border: 3px solid black;height: 155px;vertical-align: top;">
                                <br/>
                                <div style="text-align: center; font-weight: bold; font-size: 11px;">FIRST QUARTER (Weeks 1 - 10)</div>
                                {{-- <br/> --}}
                                <div style="border: 1px solid black;height: 60px;margin-left: 50px;margin-right: 50px;margin-top: 5px;">
                                </div>
                                <table style="width: 100%">
                                    <td style="width: 25%;text-align:left">
                                        <img src="{{base_path()}}/public/assets/images/msmi/msmi-preschool-rc1-removebg-preview.png" alt="school">
                                    </td>
                                    <td style="text-align: center;">
                                        <br/>
                                        <div style="border-bottom: 1px solid black;">&nbsp;</div>
                                        {{-- <br/> --}}
                                        <small>Parent or Guardian's Signature</small>
                                    </td>
                                    <td style="width: 25%;">
                                        
                                    </td>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td style="border: 3px solid black;height: 155px;vertical-align: top;">
                                <br/>
                                <div style="text-align: center; font-weight: bold;">SECOND QUARTER (Weeks 11 - 20)</div>
                                <div style="border: 1px solid black;height: 60px;margin-left: 50px;margin-right: 50px;margin-top: 5px;">
                                </div>
                                <table style="width: 100%">
                                    <td style="width: 25%;">
                                    </td>
                                    <td style="text-align: center;">
                                        <br/>
                                        <div style="border-bottom: 1px solid black;">&nbsp;</div>
                                        {{-- <br/> --}}
                                        <small>Parent or Guardian's Signature</small>
                                    </td>
                                    <td style="width: 25%;text-align:right">
                                        <img src="{{base_path()}}/public/assets/images/msmi/msmi-preschool-rc2-removebg-preview.png" alt="school">
                                    </td>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td style="border: 3px solid black;height: 155px;vertical-align: top;">
                                <br/>
                                <div style="text-align: center; font-weight: bold;">THIRD QUARTER (Weeks 21 - 30)</div>
                                <div style="border: 1px solid black;height: 60px;margin-left: 50px;margin-right: 50px;margin-top: 5px;">
                                </div>
                                <table style="width: 100%">
                                    <td style="width: 25%;text-align:left">
                                        <img src="{{base_path()}}/public/assets/images/msmi/msmi-preschool-rc3-removebg-preview.png" alt="school">
                                    </td>
                                    <td style="text-align: center;">
                                        <br/>
                                        <div style="border-bottom: 1px solid black;">&nbsp;</div>
                                        {{-- <br/> --}}
                                        <small>Parent or Guardian's Signature</small>
                                    </td>
                                    <td style="width: 25%;">
                                        
                                    </td>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td style="border: 3px solid black;height: 155px;vertical-align: top;">
                                <br/>
                                <div style="text-align: center; font-weight: bold;">FOURTH QUARTER (Weeks 31 - 40)</div>
                                <div style="border: 1px solid black;height: 60px;margin-left: 50px;margin-right: 50px;margin-top: 5px;">
                                </div>
                                <table style="width: 100%">
                                    <td style="width: 20%;">
                                    </td>
                                    <td style="text-align: center;">
                                        <br/>
                                        <div style="border-bottom: 1px solid black;">&nbsp;</div>
                                        {{-- <br/> --}}
                                        <small>Parent or Guardian's Signature</small>
                                    </td>
                                    <td style="width: 20%;text-align:right">
                                        <img src="{{base_path()}}/public/assets/images/msmi/msmi-preschool-rc4-removebg-preview.png" alt="school">
                                    </td>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
                <td style="vertical-align:top;width:33.3333333333%; padding-left: 20px; padding-right: 20px;">
                    <div style="text-align: center; font-weight: bold;">ATTENDANCE RECORD</div>
                    {{-- <br/> --}}
                    <table style="width:100%;margin-right: 30px;margin-left: 30px;" border="1">
                        <tr style="background-color: #b9ddeb">
                            <th style="width: 50%;"></th>
                            <th style="text-align:center">Q1</th>
                            <th style="text-align:center">Q2</th>
                            <th style="text-align:center">Q3</th>
                            <th style="text-align:center">Q4</th>
                        </tr>
                        <tr>
                            <td style="text-align: left;">Days Present</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="text-align: left;">Days Absent</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="text-align: left;">Days Tardy</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="text-align: left;">Days Incomplete</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </table>
                    <br/>
                    <br/>
                    <br/>
                    <div style="width: 100%;text-align: justify;line-height: 15px;">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This is to certify that 	__________ of Kindergarten  has developed the general competencies based on the Kindergarten Curriculum Guide.
                    </div>
                    <br/>
                    <br/>
                    <br/>
                    <table style="width: 100%;">
                        <tr>
                            <td style="border-bottom: 1px solid black;width: 50%"></td>
                            <td></td>
                            <td style="border-bottom: 1px solid black;width: 20%"></td>
                        </tr>
                        <tr style="text-align: center;">
                            <td>
                                <small>Teacher's Signature</small>
                            </td>
                            <td></td>
                            <td>
                                <small>Date</small>
                            </td>
                        </tr>
                    </table>
                    <br>
                    <br>
                    <br>
                    <table style="width: 100%;">
                        <tr>
                            <td style="border-bottom: 1px solid black;width: 50%"></td>
                            <td></td>
                            <td style="border-bottom: 1px solid black;width: 20%"></td>
                        </tr>
                        <tr style="text-align: center;">
                            <td>
                                <small>Principal's Signature</small>
                            </td>
                            <td></td>
                            <td>
                                <small>Date</small>
                            </td>
                        </tr>
                    </table>
                    <br>
                    <br>
                    <br>
                    <br>
                    <div style="width: 100%; border-top: 1px solid black;border-bottom: 1px solid black;height: 5px;"></div>
                    <br>
                    <div style="text-align: center; font-weight: bold;">CERTIFICATE OF TRANSFER</div>
                    <br/>
                    <p>Eligible for admission to Kindergarten</p>
                    <br>
                    <div style="text-align: center; font-weight: bold;">CANCELLATION OF ELIGIBILITY TO TRANSFER</div>
                    <br>
                    <br>
                    <table style="width: 100%;">
                        <tr>
                            <td style="width: 25%">Admitted in</td>
                            <td style="border-bottom: 1px solid black;width: 50%"></td>
                            <td style="width: 5%">on</td>
                            <td style="border-bottom: 1px solid black;width: 35%"></td>
                        </tr>
                        <tr style="text-align: center;">
                            <td></td>
                            <td><small>(School)</small></td>
                            <td></td>
                            <td><small>(Date)</small></td>
                        </tr>
                    </table>
                    <br/>
                    <br/>
                    <br/>
                    <br/>
                    <table style="width: 100%">
                        <tr>
                            <td style="width: 20%;">
                            </td>
                            <td style="text-align: center;">
                                <br/>
                                <div style="border-bottom: 1px solid black;">&nbsp;</div>
                                <small>Principal</small>
                            </td>
                            <td style="width: 20%;text-align:right">
                                
                            </td>
                        </tr>
                    </table>
                </td>
                <td style="vertical-align:top; padding-left: 20px;">
                    <table style="width: 100%; margin-left: 20px; margin-right: 30px;">
                        <tr>
                            <td style="width: 10%;text-align:right; padding-right: 10px;">
                                <img src="{{base_path()}}/public/{{$schoolinfo[0]->picurl}}" alt="school" width="50px">
                            </td>
                            <td style="text-align: center;width: 20%">
                                <div style="font-size: 10px;width: 100%;">Republic of the Philippines</div>
                                <div style="font-size: 11px;width: 100%;">Department of Education</div>
                                <div style="font-size: 11px;width: 100%;">{{$schoolinfo[0]->regDesc}}</div>
                                <div style="font-size: 11px;width: 100%;">{{$schoolinfo[0]->citymunDesc}}</div>
                            </td>
                            <td style="width: 10%;text-align:left; padding-left: 10px;">
                                <img src="{{base_path()}}/public/assets/images/department_of_Education.png" alt="school" width="50px">
                                {{-- <div style="font-size: 11px;width: 100%;">{{$schoolinfo[0]->district}}</div> --}}
                            </td>
                            {{-- <td style="width: 23%;text-align:left; padding-left: 10px;">
                                <img src="{{base_path()}}/public/assets/images/department_of_Education.png" alt="school" width="50px">
                            </td> --}}
                        </tr>
                        <tr>
                            <td colspan="3" style="text-align: center;">
                                <div style="font-size: 11px;width: 100%;">{{$schoolinfo[0]->district}}</div>
                                <div style="font-size: 11px;width: 100%;font-weight: bold;">{{$schoolinfo[0]->schoolname}}</div><br>
                                <img src="{{base_path()}}/public/assets/images/msmi/msmi-preschool-rc5.png" alt="school" width="260px;" style="margin-bottom: 0px;">
                                <img src="{{base_path()}}/public/assets/images/msmi/msmi-preschool-rc9.png" alt="school" width="260px;" style="margin-bottom: 0px;">
                                <h2 style="margin-top: 0px;margin-bottom: 0px;">S.Y.{{$schoolyear->sydesc}}</h2>
                                {{-- <h1>PROGRESS REPORT</h1> --}}
                                <img src="{{base_path()}}/public/assets/images/msmi/msmi-preschool-rc6.png" alt="school" width="250px;">
                            </td>
                        </tr>
                    </table>
                    {{-- <br/> --}}
                    <br/>
                    <div style="width: 100%;">LRN:</div>
                    <div style="width: 100%;">Name:</div>
                    <table style="width: 100%;">
                        <tr>
                            <td style="width: 5%;">Grade:</td>
                            <td style="border-bottom: 1px solid black;width: 25%;">&nbsp;</td>
                            <td style="width: 1%;">&nbsp;</td>
                            <td style="width: 10px;">Section</td>
                            <td style="border-bottom: 1px solid black;width: 30%;">&nbsp;</td>
                        </tr>
                    </table>
                    <br/>
                    <table style="width: 100%;">
                        <tr>
                            <td colspan="2">Age of Child at the Beginning of the SY:  2019-2020</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Years&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Months</td>
                        </tr>
                        <tr>
                            <td colspan="2">Age of Child at the End  of the SY:  2019-2020:</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Years&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Months</td>
                        </tr>
                    </table>
                    <br/>
                    <br/>
                    <div style="font-weight: bold;">Teacher: <u></u></div>
                    <div style="font-weight: bold;">Principal: <u></u></div>
                    <br/>
                    <br/>
                    {{-- <br/> --}}
                    <div style="width: 100%;text-align: right;">
                        <img src="{{base_path()}}/public/assets/images/msmi/msmi-preschool-rc8.png" alt="school" style="width: 330px;">
                        {{-- <span style="z-index: 999">fdsfsf</span> --}}
                    </div>
                    {{-- <span style="display: inline">fdsfsf</span> --}}
                    
                    {{-- {{base_path()}}/public/assets/images/msmi/msmi-preschool-rc7.png --}}
                </td>
            </tr>
        </table>
        {{-- <br/> --}}
        
        <div class="page_break"></div>
        <table style="width: 100%; font-size: 10px;table-layout: fixed;">
            <tr>
                <td style="width:33.3333333333%; padding-right:15px;vertical-align: top">
                    <table style="width: 100%; table-layout: fixed;" border="1">
                        <tr style="background-color: yellow;">
                            <td style="width: 65%; padding-left: 5px;padding-bottom: 0px;">Health, Well-Being, and Motor Development</td>
                            <td style="text-align: center;">Q1</td>
                            <td style="text-align: center;">Q2</td>
                            <td style="text-align: center;">Q3</td>
                            <td style="text-align: center;">Q4</td>
                        </tr>
                        <tr>
                            <td style="width: 65%; padding-left: 5px;padding-bottom: 0px;">Demonstrates health habits that keep one clean and sanitary</td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                        </tr>
                        <tr>
                            <td style="width: 65%; padding-left: 5px;padding-bottom: 0px;">Demonstrates behaviors that promote personal safety</td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                        </tr>
                        <tr>
                            <td style="width: 65%; padding-left: 5px;padding-bottom: 0px;">Demonstrates locomotor skills such as walking, running, skipping, jumping, climbing correctly during play, dance or exercise <br> activities</td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                        </tr>
                        <tr>
                            <td style="width: 65%; padding-left: 5px;padding-bottom: 0px;">Demonstrates non-locomotor skills such as pushing, pulling, turning, swaying, bending, throwing, catching, and kicking correctly during play, dance or exercise activities</td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                        </tr>
                        <tr>
                            <td style="width: 65%; padding-left: 5px;padding-bottom: 0px;">Demonstrates fine motor skills needed for self-care/self-help such as toothbrushing, buttoning, screwing and unscrewing lids, using spoon and fork correctly, etc.</td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                        </tr>
                        <tr>
                            <td style="width: 65%; padding-left: 5px;padding-bottom: 0px;">Demonstrates fine motor skills needed for creative self-expression /art activities, such as tearing, cutting, pasting, copying, drawing, coloring, molding, painting, lacing, etc.</td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                        </tr>
                        <tr>
                            <td style="width: 65%; padding-left: 5px;padding-bottom: 0px;">Traces, copies, or writes letters and numerals</td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                        </tr>
                        <tr style="background-color: yellow;">
                            <td style="width: 65%; padding-left: 5px;padding-bottom: 0px;">Socioemotional Development</td>
                            <td style="text-align: center;">Q1</td>
                            <td style="text-align: center;">Q2</td>
                            <td style="text-align: center;">Q3</td>
                            <td style="text-align: center;">Q4</td>
                        </tr>
                        <tr>
                            <td style="width: 65%; padding-left: 5px;padding-bottom: 0px;">States personal information (name, gender, age, birthday)</td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                        </tr>
                        <tr>
                            <td style="width: 65%; padding-left: 5px;padding-bottom: 0px;">Expresses personal interests and needs</td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                        </tr>
                        <tr>
                            <td style="width: 65%; padding-left: 5px;padding-bottom: 0px;">Demonstrates readiness in trying out new experiences, and self- confidence in doing tasks independently</td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                        </tr>
                        <tr>
                            <td style="width: 65%; padding-left: 5px;padding-bottom: 0px;">Expresses feelings in appropriate ways and in different situations</td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                        </tr>
                        <tr>
                            <td style="width: 65%; padding-left: 5px;padding-bottom: 0px;">Follows school rules willingly and executes school tasks and routines well</td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                        </tr>
                        <tr>
                            <td style="width: 65%; padding-left: 5px;padding-bottom: 0px;">Recognizes different emotions, acknowledges the feeling of others, and shows willingness to <br> help</td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                        </tr>
                        <tr>
                            <td style="width: 65%; padding-left: 5px;padding-bottom: 0px;">Shows respect in dealing with peers and <br> adults </td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                        </tr>
                        <tr>
                            <td style="width: 65%; padding-left: 5px;padding-bottom: 0px;">Identifies members of one’s family </td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                        </tr>
                        <tr>
                            <td style="width: 65%; padding-left: 5px;padding-bottom: 0px;">Identifies people and places in the school and community</td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                        </tr>
                        <tr style="background-color: yellow;">
                            <td style="width: 65%; padding-left: 5px;padding-bottom: 0px;">Language, Literacy, and Communication </td>
                            <td style="text-align: center;">Q1</td>
                            <td style="text-align: center;">Q2</td>
                            <td style="text-align: center;">Q3</td>
                            <td style="text-align: center;">Q4</td>
                        </tr>
                        <tr style="background-color: #8bba4c;">
                            <td style="width: 65%; padding-left: 5px;padding-bottom: 0px;">Listening and Viewing</td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                        </tr>
                        <tr>
                            <td style="width: 65%; padding-left: 5px;padding-bottom: 0px;">Distinguishes between elements of sounds e.g. pitch (low and high), volume (loud and soft)</td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                        </tr>
                        <tr>
                            <td style="width: 65%; padding-left: 5px;padding-bottom: 0px;">Listens attentively to stories/ poems/songs</td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                        </tr>
                        <tr>
                            <td style="width: 65%; padding-left: 5px;padding-bottom: 0px;">Recalls details from stories/poems/songs listened to</td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                        </tr>
                        <tr>
                            <td style="width: 65%; padding-left: 5px;padding-bottom: 0px;">Relate story events to personal experiences</td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                        </tr>
                        <tr>
                            <td style="width: 65%; padding-left: 5px;padding-bottom: 0px;">Sequence events from a story listened to</td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                        </tr>
                        <tr>
                            <td style="width: 65%; padding-left: 5px;padding-bottom: 0px;">Infer character traits and feelings</td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                        </tr>
                        <tr>
                            <td style="width: 65%; padding-left: 5px;padding-bottom: 0px;">Identify simple cause-and-effect and problem-solution relationship of events in a story listened to or in a familiar situation</td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                        </tr>
                        <tr>
                            <td style="width: 65%; padding-left: 5px;padding-bottom: 0px;">Predict story outcomes</td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                        </tr>
                    </table>
                </td>
                <td style="width:33.3333333333%; padding-right:15px;padding-left:15px;">
                    <table style="width: 100%; table-layout: fixed;" border="1">
                        <tr>
                            <td style="width: 65%; padding-left: 5px;padding-bottom: 0px;">Discriminates objects/pictures as same and different, identifies missing parts of objects/ pictures, and identifies which objects do not belong to the group</td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                        </tr>
                        <tr style="background-color: #8bba4c;">
                            <td style="width: 65%; padding-left: 5px;padding-bottom: 0px;">Speaking</td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                        </tr>
                        <tr>
                            <td style="width: 65%; padding-left: 5px;padding-bottom: 0px;">Uses proper expressions in  and polite greetings in appropriate situations</td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                        </tr>
                        <tr>
                            <td style="width: 65%; padding-left: 5px;padding-bottom: 0px;">Talks about details of object, people, etc. using appropriate speaking vocabulary</td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                        </tr>
                        <tr>
                            <td style="width: 65%; padding-left: 5px;padding-bottom: 0px;">Participates actively in class activities (e.g., reciting poems, rhymes, etc.) and discussions by responding to questions accordingly</td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                        </tr>
                        <tr>
                            <td style="width: 65%; padding-left: 5px;padding-bottom: 0px;">Asks simple questions ( who, what, where, when, why )</td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                        </tr>
                        <tr>
                            <td style="width: 65%; padding-left: 5px;padding-bottom: 0px;">Gives 1 to 2 step directions</td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                        </tr>
                        <tr>
                            <td style="width: 65%; padding-left: 5px;padding-bottom: 0px;">Retells simple stories or narrates personal experiences </td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                        </tr>
                        <tr style="background-color: #8bba4c;">
                            <td style="width: 65%; padding-left: 5px;padding-bottom: 0px;">Reading</td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                        </tr>
                        <tr>
                            <td style="width: 65%; padding-left: 5px;padding-bottom: 0px;">Identifies sounds of letters (using the alphabet of the Mother Tongue)</td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                        </tr>
                        <tr>
                            <td colspan="5" style="padding-left: 5px;text-align: center;">
                                The child can identify the following letter sounds:
/a/ /b/ /c/ /d/ /e/ /f/ /g/ /h/ /i/ /j/ /k/ /l/ /m/ /n/ /n/ /ng/ /o/ /p/ /q/ /r/ /s/ /t/ /u/ /v/ /w/ /x/ /y/ /z/

                            </td>
                        </tr>
                        <tr>
                            <td style="width: 65%; padding-left: 5px;padding-bottom: 0px;">Names uppercase and lower case letters (using the alphabet of the Mother Tongue)</td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                        </tr>
                        <tr>
                            <td colspan="5" style="padding-left: 5px;text-align: center;">
                                The child can name the following uppercase and lower case letters
                                A B C D E F G H I J K L M N N NG O P Q R S T U V W X Y Z
                                a b c d e f g h I j k l m n n ng o p q r s t u v w x y z 
                                

                            </td>
                        </tr>
                        <tr>
                            <td style="width: 65%; padding-left: 5px;padding-bottom: 0px;">Matches the uppercase and lower case letters (using the alphabet of the Mother Tongue)</td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                        </tr>
                        <tr>
                            <td style="width: 65%; padding-left: 5px;padding-bottom: 0px;">Identifies beginning sound of  a given word</td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                        </tr>
                        <tr>
                            <td style="width: 65%; padding-left: 5px;padding-bottom: 0px;">Distinguishes the words that rhyme</td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                        </tr>
                        <tr>
                            <td style="width: 65%; padding-left: 5px;padding-bottom: 0px;">Counts syllables in a given word</td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                        </tr>
                        <tr>
                            <td style="width: 65%; padding-left: 5px;padding-bottom: 0px;">Identifies parts of the book (front and back, title, author, illustrator, etc.)</td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                        </tr>
                        <tr>
                            <td style="width: 65%; padding-left: 5px;padding-bottom: 0px;">Shows interest in reading by browsing through books, predicting what the story is all about and demonstrating proper book handling behavior (e.g., flip pages sequentially, browses from left to right, etc.)</td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                        </tr>
                        <tr>
                            <td style="width: 65%; padding-left: 5px;padding-bottom: 0px;">Interprets information from simple pictographs, maps, and other environmental print</td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                        </tr>
                        <tr style="background-color: #8bba4c;">
                            <td style="width: 65%; padding-left: 5px;padding-bottom: 0px;">Writing</td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                        </tr>
                        <tr>
                            <td style="width: 65%; padding-left: 5px;padding-bottom: 0px;">Writes one’s given name</td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                        </tr>
                        <tr>
                            <td style="width: 65%; padding-left: 5px;padding-bottom: 0px;">Writes lowercase and uppercase letters</td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                        </tr>
                        <tr>
                            <td style="width: 65%; padding-left: 5px;padding-bottom: 0px;">Express simple ideas through symbols (e.g., drawings, invented spelling)</td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                        </tr>
                        <tr style="background-color: yellow;">
                            <td style="width: 65%; padding-left: 5px;padding-bottom: 0px;">Mathematics</td>
                            <td style="text-align: center;">Q1</td>
                            <td style="text-align: center;">Q2</td>
                            <td style="text-align: center;">Q3</td>
                            <td style="text-align: center;">Q4</td>
                        </tr>
                        <tr>
                            <td style="width: 65%; padding-left: 5px;padding-bottom: 0px;">Identifies colors</td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                        </tr>
                        <tr>
                            <td style="width: 65%; padding-left: 5px;padding-bottom: 0px;">Identifies shapes</td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                        </tr>
                        <tr>
                            <td style="width: 65%; padding-left: 5px;padding-bottom: 0px;">Sorts objects according to shape, size, and color</td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                        </tr>
                        <tr>
                            <td style="width: 65%; padding-left: 5px;padding-bottom: 0px;">Compares and arranges objects according to a specific attribute (e.g., size, length, quantity, or duration)</td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                        </tr>
                    </table>
                </td>
                <td style="width:33.3333333333%;padding-left:15px; vertical-align: top;">
                    <table style="width: 100%; table-layout: fixed;" border="1">
                        <tr>
                            <td style="width: 65%; padding-left: 5px;padding-bottom: 0px;">Recognizes and extends patterns</td>
                            <td style="text-align: center;">D</td>
                            <td style="text-align: center;">C</td>
                            <td style="text-align: center;">C</td>
                            <td style="text-align: center;">C</td>
                        </tr>
                        <tr>
                            <td style="width: 65%; padding-left: 5px;padding-bottom: 0px;">Tells the names of days in a week</td>
                            <td style="text-align: center;">C</td>
                            <td style="text-align: center;">C</td>
                            <td style="text-align: center;">C</td>
                            <td style="text-align: center;">C</td>
                        </tr>
                        <tr>
                            <td style="width: 65%; padding-left: 5px;padding-bottom: 0px;">Tells the months of the year</td>
                            <td style="text-align: center;">C</td>
                            <td style="text-align: center;">C</td>
                            <td style="text-align: center;">C</td>
                            <td style="text-align: center;">C</td>
                        </tr>
                        <tr>
                            <td style="width: 65%; padding-left: 5px;padding-bottom: 0px;">Distinguishes the time of the day and tells time by the hour (using analog clock)</td>
                            <td style="text-align: center;">B</td>
                            <td style="text-align: center;">D</td>
                            <td style="text-align: center;">D</td>
                            <td style="text-align: center;">C</td>
                        </tr>
                        <tr>
                            <td style="width: 65%; padding-left: 5px;padding-bottom: 0px;">Recognizes and extends patterns</td>
                            <td style="text-align: center;">D</td>
                            <td style="text-align: center;">C</td>
                            <td style="text-align: center;">C</td>
                            <td style="text-align: center;">C</td>
                        </tr>
                        <tr>
                            <td style="text-align: center;" colspan="5">
                                <span>The child can count up to: 1 2 3 4 5 6 7 8 9 10 11 12 13 14 15 16 17 </span><br>
                                <span>18 19 20 Others: _____________</span>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 65%; padding-left: 5px;padding-bottom: 0px;">Recognizes numerals up to 10</td>
                            <td style="text-align: center;">C</td>
                            <td style="text-align: center;">C</td>
                            <td style="text-align: center;">C</td>
                            <td style="text-align: center;">C</td>
                        </tr>
                        <tr>
                            <td style="text-align: center;" colspan="5">
                                <span>The child can recognize numerals up to: 1 2 3 4 5 6 7 8 9 10 Others:</span><br>
                                <span>_____________</span>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 65%; padding-left: 5px;padding-bottom: 0px;">Writes numerals up to 10</td>
                            <td style="text-align: center;">C</td>
                            <td style="text-align: center;">C</td>
                            <td style="text-align: center;">C</td>
                            <td style="text-align: center;">C</td>
                        </tr>
                        <tr>
                            <td style="text-align: center;" colspan="5">
                                <span>The child can recognize numerals up to: 1 2 3 4 5 6 7 8 9 10 Others:</span><br>
                                <span>&nbsp;</span>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 65%; padding-left: 5px;padding-bottom: 0px;">Sequences numbers</td>
                            <td style="text-align: center;">C</td>
                            <td style="text-align: center;">C</td>
                            <td style="text-align: center;">C</td>
                            <td style="text-align: center;">C</td>
                        </tr>
                        <tr>
                            <td style="width: 65%; padding-left: 5px;padding-bottom: 0px;">Identifies the placement of objects (e.g., 1st, 2nd, 3rd, etc) in a given set</td>
                            <td style="text-align: center;">C</td>
                            <td style="text-align: center;">C</td>
                            <td style="text-align: center;">C</td>
                            <td style="text-align: center;">C</td>
                        </tr>
                        <tr>
                            <td style="width: 65%; padding-left: 5px;padding-bottom: 0px;">Solves simple addition problems</td>
                            <td style="text-align: center;">C</td>
                            <td style="text-align: center;">C</td>
                            <td style="text-align: center;">C</td>
                            <td style="text-align: center;">C</td>
                        </tr>
                        <tr>
                            <td style="width: 65%; padding-left: 5px;padding-bottom: 0px;">Solves simple subtraction problems</td>
                            <td style="text-align: center;">C</td>
                            <td style="text-align: center;">C</td>
                            <td style="text-align: center;">C</td>
                            <td style="text-align: center;">C</td>
                        </tr>
                        <tr>
                            <td style="width: 65%; padding-left: 5px;padding-bottom: 0px;">Groups sets of concrete objects of equal quantities up to 10 (i.e., beginning multiplication)</td>
                            <td style="text-align: center;">C</td>
                            <td style="text-align: center;">C</td>
                            <td style="text-align: center;">C</td>
                            <td style="text-align: center;">C</td>
                        </tr>
                        <tr>
                            <td style="width: 65%; padding-left: 5px;padding-bottom: 0px;">Separates sets of concrete objects of equal quantities up to 10 (i.e., beginning division)</td>
                            <td style="text-align: center;">C</td>
                            <td style="text-align: center;">C</td>
                            <td style="text-align: center;">C</td>
                            <td style="text-align: center;">C</td>
                        </tr>
                        <tr>
                            <td style="width: 65%; padding-left: 5px;padding-bottom: 0px;">Measures length, capacity, and mass of objects using non-standards measuring tools</td>
                            <td style="text-align: center;">C</td>
                            <td style="text-align: center;">C</td>
                            <td style="text-align: center;">C</td>
                            <td style="text-align: center;">C</td>
                        </tr>
                        <tr>
                            <td style="width: 65%; padding-left: 5px;padding-bottom: 0px;">Recognizes coins and bills (up to Php20.00)</td>
                            <td style="text-align: center;">C</td>
                            <td style="text-align: center;">C</td>
                            <td style="text-align: center;">C</td>
                            <td style="text-align: center;">C</td>
                        </tr>
                        <tr>
                            <td colspan="5">
                                <span style="text-align: center;">The child can recognize the following coins and bills:  5 centavos, 10</span><br>
                                <span style="text-align: left;">centavos, 25 centavos, 1 peso, 5 pesos, 10 pesos     20 pesos</span>
                            </td>
                        </tr>
                        <tr style="background-color: yellow;">
                            <td style="width: 65%; padding-left: 5px;padding-bottom: 0px;">Health, Well-Being, and Motor Development</td>
                            <td style="text-align: center;">Q1</td>
                            <td style="text-align: center;">Q2</td>
                            <td style="text-align: center;">Q3</td>
                            <td style="text-align: center;">Q4</td>
                        </tr>
                        <tr>
                            <td style="width: 65%; padding-left: 5px;padding-bottom: 0px;">Identifies body parts and their functions</td>
                            <td style="text-align: center;">C</td>
                            <td style="text-align: center;">C</td>
                            <td style="text-align: center;">C</td>
                            <td style="text-align: center;">C</td>
                        </tr>
                        <tr>
                            <td style="width: 65%; padding-left: 5px;padding-bottom: 0px;">Records observations and data with pictures, numbers, and/or symbols</td>
                            <td style="text-align: center;">C</td>
                            <td style="text-align: center;">C</td>
                            <td style="text-align: center;">C</td>
                            <td style="text-align: center;">C</td>
                        </tr>
                        <tr>
                            <td style="width: 65%; padding-left: 5px;padding-bottom: 0px;">Identifies parts of plants and animals</td>
                            <td style="text-align: center;">C</td>
                            <td style="text-align: center;">C</td>
                            <td style="text-align: center;">C</td>
                            <td style="text-align: center;">C</td>
                        </tr>
                        <tr>
                            <td style="width: 65%; padding-left: 5px;padding-bottom: 0px;">Classifies animals according to shared characteristics</td>
                            <td style="text-align: center;">C</td>
                            <td style="text-align: center;">C</td>
                            <td style="text-align: center;">C</td>
                            <td style="text-align: center;">C</td>
                        </tr>
                        <tr>
                            <td style="width: 65%; padding-left: 5px;padding-bottom: 0px;">Describes the basic needs and ways to care for plants, animals and the environment</td>
                            <td style="text-align: center;">C</td>
                            <td style="text-align: center;">C</td>
                            <td style="text-align: center;">C</td>
                            <td style="text-align: center;">C</td>
                        </tr>
                        <tr>
                            <td style="width: 65%; padding-left: 5px;padding-bottom: 0px;">Identifies different kinds of weather</td>
                            <td style="text-align: center;">C</td>
                            <td style="text-align: center;">C</td>
                            <td style="text-align: center;">C</td>
                            <td style="text-align: center;">C</td>
                        </tr>
                    </table>
                    <table style="width: 100%; table-layout: fixed; margin-top: 10px;">
                        <tr>
                            <td style="text-align: center;"><b>Rating Scale</b></td>
                        </tr>
                    </table>
                    <table style="width: 100%; table-layout: fixed;" border="1">
                        <tr>
                            <td style="text-align: center;" width="15%"><b>Rating</b></td>
                            <td style="text-align: center;" width="85%"><b>Indicators</b></td>
                        </tr>
                        <tr>
                            <td rowspan="3" style="text-align: center;" width="15%"><span>Beginning</span><br><span>(B)</span></td>
                            <td style="text-align: left;" width="85%">Rarely demonstrates the expected competency</td>
                        </tr>
                        <tr>
                            <td style="text-align: left;" width="85%">Rarely participates in class activities and/or initiates independent works</td>
                        </tr>
                        <tr>
                            <td style="text-align: left;" width="85%">Shows interest in doing task but needs close supervision</td>
                        </tr>
                        <tr>
                            <td rowspan="3" style="text-align: center;" width="15%"><span>Developing</span><br><span>(D)</span></td>
                            <td style="text-align: left;" width="85%">Sometimes demonstrates the competency</td>
                        </tr>
                        <tr>
                            <td style="text-align: left;" width="85%">Sometimes participates, minimal supervision</td>
                        </tr>
                        <tr>
                            <td style="text-align: left;" width="85%">Progresses continuously in doing assigned task</td>
                        </tr>
                        <tr>
                            <td rowspan="3" style="text-align: center;" width="15%"><span>Consistent</span><br><span>(C)</span></td>
                            <td style="text-align: left;" width="85%">Always demonstrates the expected competency</td>
                        </tr>
                        <tr>
                            <td style="text-align: left;" width="85%">Always participates in the different activities, works independently</td>
                        </tr>
                        <tr>
                            <td style="text-align: left;" width="85%">Always perform tasks, advanced in some aspects</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        {{-- <table style="width: 100%; font-size: 12px;">
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
                        <br/>
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
        </div> --}}
    </body>
</html>