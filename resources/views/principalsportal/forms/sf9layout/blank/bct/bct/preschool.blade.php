<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <style>
                html                        { font-family: Arial, Helvetica, sans-serif; }

                table                       { border-collapse: collapse; }

                .text-center                { text-align: center !important; }

                .table-bordered             { border: 1px solid black; }

                .table-bordered th,
                .table-bordered td          { border: 1px solid black; }

                .pl-4, .px-4                { padding-left: 1.5rem!important; }
                @page                       { margin: 20 20 0 30!important; }
                .page_break { page-break-before: always; }
                .attendance td, .attendance th{
                    padding: 5px;
                }
                .p-0{
                    padding:0 !important;
                }
                .align-middle{
                    vertical-align:middle;
                }
        </style>    
    </head>
    <body >
        
        <br/>
        <table style="width: 100%; table-layout: fixed;">
            <tr>
                <td style="width:30.3333333333%; vertical-align: top; font-size: 14px;" >
                    <table style="width: 100%;" border="1">
                        <thead style="text-align: center;">
                            <tr>
                                <th style="width: 70%;"></th>
                                <th>1</th>
                                <th>2</th>
                                <th>3</th>
                                <th>4</th>
                            </tr>
                        </thead>
                        <tr>
                            <td style="padding-left:9px;">Following rules and routine</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FG8','FG'])->first()->q1eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FG8','FG'])->first()->q2eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FG8','FG'])->first()->q3eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FG8','FG'])->first()->q4eval}}</td>
                        </tr>
                        <tr>
                            <td style="padding-left:9px;">Showing independence in doing own tasks.</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FG9','FH'])->first()->q1eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FG9','FH'])->first()->q2eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FG9','FH'])->first()->q3eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FG9','FH'])->first()->q4eval}}</td>
                        </tr>
                        <tr>
                            <td style="padding-left:9px;">Praying with reverence</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FH1','FI'])->first()->q1eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FH1','FI'])->first()->q2eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FH1','FI'])->first()->q3eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FH1','FI'])->first()->q4eval}}</td>
                        </tr>
                        <tr>
                            <td style="padding-left:9px;">Showing cooperation in faith activities and situations</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FH2','FJ'])->first()->q1eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FH2','FJ'])->first()->q2eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FH2','FJ'])->first()->q3eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FH2','FJ'])->first()->q4eval}}</td>
                        </tr>
                        <tr style="background-color:#8dcf5f; font-weight: bold;">
                            <td style="padding-left:9px;">VIII. HEALTH AND SAFETY HABITS</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        
                        <tr>
                            <td style="padding-left:9px;">Keeping own self clean and tidy</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FI2','GA'])->first()->q1eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FI2','GA'])->first()->q2eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FI2','GA'])->first()->q3eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FI2','GA'])->first()->q4eval}}</td>
                        </tr>
                        <tr>
                            <td style="padding-left:9px;">Exhibiting hygiene practices</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FI3','GB'])->first()->q1eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FI3','GB'])->first()->q2eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FI3','GB'])->first()->q3eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FI3','GB'])->first()->q4eval}}</td>
                        </tr>
                    </table>
                    <br/>
                    <br/>
                    <table style="width: 100%;" border="1" class="attendance">
                        <thead>
                            <tr>
                                <th style="width: 20%;"></th>
                                @if(collect($attendance_setup)->where('month',8)->count() > 0) <th>A<br/>U<br/>G</th> @endif
                                @if(collect($attendance_setup)->where('month',9)->count() > 0) <th>S<br/>E<br/>P</th> @endif
                                @if(collect($attendance_setup)->where('month',10)->count() > 0) <th>O<br/>C<br/>T</th> @endif
                                @if(collect($attendance_setup)->where('month',11)->count() > 0) <th>N<br/>O<br/>V</th> @endif
                                @if(collect($attendance_setup)->where('month',12)->count() > 0) <th>D<br/>E<br/>C</th> @endif
                                @if(collect($attendance_setup)->where('month',1)->count() > 0) <th>J<br/>A<br/>N</th> @endif
                                @if(collect($attendance_setup)->where('month',2)->count() > 0) <th>F<br/>E<br/>B</th> @endif
                                @if(collect($attendance_setup)->where('month',3)->count() > 0) <th>M<br/>A<br/>R</th> @endif
                                @if(collect($attendance_setup)->where('month',4)->count() > 0) <th>A<br/>P<br/>R</th> @endif
                                @if(collect($attendance_setup)->where('month',5)->count() > 0) <th>M<br/>A<br/>Y</th> @endif
                                <th>T<br/>O<br/>T<br/>A<br/>L</th>
                                <!--<th>J<br/>A<br/>N</th>-->
                                <!--<th>F<br/>E<br/>B</th>-->
                                <!--<th>M<br/>A<br/>R</th>-->
                                <!--<th>A<br/>P<br/>R</th>-->
                                <!--<th>T<br/>O<br/>T<br/>A<br/>L</th>-->
                            </tr>
                        </thead>
                        <tr>
                            <td>No. of School days</td>
                            @if(collect($attendance_setup)->where('month',8)->count() > 0) <th>{{collect($attendance_setup)->where('month',8)->first()->days}}</th> @endif
                            @if(collect($attendance_setup)->where('month',9)->count() > 0) <th>{{collect($attendance_setup)->where('month',9)->first()->days}}</th> @endif
                            @if(collect($attendance_setup)->where('month',10)->count() > 0) <th>{{collect($attendance_setup)->where('month',10)->first()->days}}</th> @endif
                            @if(collect($attendance_setup)->where('month',11)->count() > 0) <th>{{collect($attendance_setup)->where('month',11)->first()->days}}</th> @endif
                            @if(collect($attendance_setup)->where('month',12)->count() > 0) <th>{{collect($attendance_setup)->where('month',12)->first()->days}}</th> @endif
                            @if(collect($attendance_setup)->where('month',1)->count() > 0) <th>{{collect($attendance_setup)->where('month',1)->first()->days}}</th> @endif
                            @if(collect($attendance_setup)->where('month',2)->count() > 0) <th>{{collect($attendance_setup)->where('month',2)->first()->days}}</th> @endif
                            @if(collect($attendance_setup)->where('month',3)->count() > 0) <th>{{collect($attendance_setup)->where('month',3)->first()->days}}</th> @endif
                            @if(collect($attendance_setup)->where('month',4)->count() > 0) <th>{{collect($attendance_setup)->where('month',4)->first()->days}}</th> @endif
                            @if(collect($attendance_setup)->where('month',5)->count() > 0) <th>{{collect($attendance_setup)->where('month',5)->first()->days}}</th> @endif
                            <th>{{collect($attendance_setup)->sum('days')}}</th>
                        </tr>
                        <tr>
                            <td>No. of Days Present</td>
                            @if(collect($attendance_setup)->where('month',8)->count() > 0) <th>{{collect($attendance_setup)->where('month',8)->first()->present}}</th> @endif
                            @if(collect($attendance_setup)->where('month',9)->count() > 0) <th>{{collect($attendance_setup)->where('month',9)->first()->present}}</th> @endif
                            @if(collect($attendance_setup)->where('month',10)->count() > 0) <th>{{collect($attendance_setup)->where('month',10)->first()->present}}</th> @endif
                            @if(collect($attendance_setup)->where('month',11)->count() > 0) <th>{{collect($attendance_setup)->where('month',11)->first()->present}}</th> @endif
                            @if(collect($attendance_setup)->where('month',12)->count() > 0) <th>{{collect($attendance_setup)->where('month',12)->first()->present}}</th> @endif
                            @if(collect($attendance_setup)->where('month',1)->count() > 0) <th>{{collect($attendance_setup)->where('month',1)->first()->present}}</th> @endif
                            @if(collect($attendance_setup)->where('month',2)->count() > 0) <th>{{collect($attendance_setup)->where('month',2)->first()->present}}</th> @endif
                            @if(collect($attendance_setup)->where('month',3)->count() > 0) <th>{{collect($attendance_setup)->where('month',3)->first()->present}}</th> @endif
                            @if(collect($attendance_setup)->where('month',4)->count() > 0) <th>{{collect($attendance_setup)->where('month',4)->first()->present}}</th> @endif
                            @if(collect($attendance_setup)->where('month',5)->count() > 0) <th>{{collect($attendance_setup)->where('month',5)->first()->present}}</th> @endif
                            <th>{{collect($attendance_setup)->sum('present')}}</th>
                        </tr>
                        <tr>
                            <td>No. of Times Tardy</td>
                             @if(collect($attendance_setup)->where('month',8)->count() > 0) <th>{{collect($attendance_setup)->where('month',8)->first()->absent}}</th> @endif
                            @if(collect($attendance_setup)->where('month',9)->count() > 0) <th>{{collect($attendance_setup)->where('month',9)->first()->absent}}</th> @endif
                            @if(collect($attendance_setup)->where('month',10)->count() > 0) <th>{{collect($attendance_setup)->where('month',10)->first()->absent}}</th> @endif
                            @if(collect($attendance_setup)->where('month',11)->count() > 0) <th>{{collect($attendance_setup)->where('month',11)->first()->absent}}</th> @endif
                            @if(collect($attendance_setup)->where('month',12)->count() > 0) <th>{{collect($attendance_setup)->where('month',12)->first()->absent}}</th> @endif
                            @if(collect($attendance_setup)->where('month',1)->count() > 0) <th>{{collect($attendance_setup)->where('month',1)->first()->absent}}</th> @endif
                            @if(collect($attendance_setup)->where('month',2)->count() > 0) <th>{{collect($attendance_setup)->where('month',2)->first()->absent}}</th> @endif
                            @if(collect($attendance_setup)->where('month',3)->count() > 0) <th>{{collect($attendance_setup)->where('month',3)->first()->absent}}</th> @endif
                            @if(collect($attendance_setup)->where('month',4)->count() > 0) <th>{{collect($attendance_setup)->where('month',4)->first()->absent}}</th> @endif
                            @if(collect($attendance_setup)->where('month',5)->count() > 0) <th>{{collect($attendance_setup)->where('month',5)->first()->absent}}</th> @endif
                            <th>{{collect($attendance_setup)->sum('absent')}}</th>
                        </tr>
                    </table>

                </td>
                <td style="vertical-align:top;width:36.3333333333%; padding-left: 20px; padding-right: 10px;">
                    <div style="text-align: center; font-weight: bold; font-size: 14px;">SIGNATURE OF PARENT/GUARDIAN</div>
                    <br/>
                    <table style="width: 100%;margin-left: 30px;margin-right: 20px; font-size: 14px;">
                        <tr>
                            <td style="width: 35%;">First Grading:</td>
                            <td style="border-bottom: qpx solid black;"></td>
                        </tr>
                    </table>
                    <br/>
                    <table style="width: 100%;margin-left: 30px;margin-right: 20px; font-size: 14px;">
                        <tr>
                            <td style="width: 35%;">Second Grading:</td>
                            <td style="border-bottom: qpx solid black;"></td>
                        </tr>
                    </table>
                    <br/>
                    <table style="width: 100%;margin-left: 30px;margin-right: 20px; font-size: 14px;">
                        <tr>
                            <td style="width: 35%;">Third Grading:</td>
                            <td style="border-bottom: qpx solid black;"></td>
                        </tr>
                    </table>
                    <br/>
                    <table style="width: 100%;margin-left: 30px;margin-right: 20px; font-size: 14px;">
                        <tr>
                            <td style="width: 35%;">Fourth Grading:</td>
                            <td style="border-bottom: qpx solid black;"></td>
                        </tr>
                    </table>
                    <br/>
                    <table style="width: 100%;margin-left: 30px;margin-right: 20px; font-size: 14px;">
                        <tr>
                            <td>Dear Mom and Dad,</td>
                        </tr>
                    </table>
                    <br/>
                    <table style="width: 100%;margin-left: 30px;margin-right: 20px; text-align: justify; font-size: 14px;">
                        <tr>
                            <td>I thank God that He blessed me with your love and send me in this school where I get the right preparation for my formal schooling. In this progress report card you will find my development during the school year. </td>
                        </tr>
                    </table>
                    <br/>
                    <table style="width: 100%;margin-left: 30px;margin-right: 20px; font-size: 14px;">
                        <tr>
                            <td>My gratitude also for acknowledging my sincere efforts.</td>
                        </tr>
                    </table>
                    <br/>
                    <table style="width: 100%;margin-left: 30px;margin-right: 20px; font-size: 14px;">
                        <tr>
                            <td>Your loving child.</td>
                        </tr>
                    </table>
                    <br/>
                    <br/>
                    <br/>
                    <table style="width: 100%;margin-left: 30px;margin-right: 20px; font-weight: bold; font-size: 17px;">
                        <tr>
                            <td>CERTIFICATE FOR PROMOTION/ADMISSION</td>
                        </tr>
                    </table>
                    <br/>
                    <table style="width: 100%;margin-left: 30px;margin-right: 20px; font-size: 14px;">
                        <tr>
                            <td style="width: 35%;">This certifies that</td>
                            <td style="border-bottom: 1px solid black; width: 45%;">&nbsp;</td>
                            <td style="width: 20%;">is eligible</td>
                        </tr>
                    </table>
                    <br/>
                    <table style="width: 100%;margin-left: 30px;margin-right: 20px; font-size: 14px;">
                        <tr>
                            <td style="width: 60%;">to be promoted/admitted to grade </td>
                            <td style="border-bottom: 1px solid black;">&nbsp;</td>
                        </tr>
                    </table>
                    <br/>
                    <br/>
                    <table style="width: 100%;margin-left: 30px;margin-right: 20px; font-size: 14px;">
                        <tr>
                            <td style="width: 70%;border-bottom: 1px solid black;text-align: center;">{{$student[0]->teacherfirstname}} {{$student[0]->teachermiddlename[0]}}. {{$student[0]->teacherlastname}}</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">Adviser</td>
                            <td>&nbsp;</td>
                        </tr>
                    </table>
                    <br/>
                    <br/>
                    <table style="width: 100%;margin-left: 30px;margin-right: 20px; font-size: 14px;">
                        <tr>
                            <td>&nbsp;</td>
                            <td style="width: 70%;border-bottom: 1px solid black;text-align: center;">Arthelo P. Palma, LPT, Ph.D.</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td style="text-align: center;">Academic Affairs Dep’t. Head</td>
                        </tr>
                    </table>
                </td>
                <td style="vertical-align:top; padding-left: 20px;">
                    <table style="width: 100%; margin-left: 20px; margin-right: 30px;">
                        <tr>
                            <td style="width: 40%;text-align:right; padding-right: 15px;">
                                <img src="{{base_path()}}/public/{{$schoolinfo[0]->picurl}}" alt="school" width="60px">
                            </td>
                            <td style="width: 60%;text-align:left; padding-left: 15px;">
                                <img src="{{base_path()}}/public/assets/images/bct/photo00.png" alt="school" width="110px">
                                {{-- <div style="font-size: 11px;width: 100%;">{{$schoolinfo[0]->district}}</div> --}}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align: center;">
                                <div style="font-size: 13px;width: 100%;font-weight: bold;">{{$schoolinfo[0]->schoolname}}</div>
                                <div style="font-size: 11px;width: 100%;">{{$schoolinfo[0]->address}}</div>
                                <div style="font-size: 11px;width: 100%;">Tel. No. 291-2556, 291-2512 mobile: 09283232892</div>
                                <div style="font-size: 11px;width: 100%;">email add: bc_toril@yahoo.com</div>
                                <br/>
                                <table width="100%">
                                    <tr>
                                        <td width="10%"></td>
                                        <td width="80%" class="text-center align-middle" style="border:solid 3px #91CCE6"><h2 style="margin:0 !important;">{{$student[0]->levelname}}</h2></td>
                                        <td width="10%"></td>
                                    </tr>
                                </table>
                                <br>
                                {{-- <img src="{{base_path()}}/public/assets/images/bct/photo0.png" alt="school" width="260px;" style="margin-bottom: 0px;"> --}}
                                {{-- <img src="{{base_path()}}/public/assets/images/msmi/msmi-preschool-rc9.png" alt="school" width="260px;" style="margin-bottom: 0px;">
                                <h2 style="margin-top: 0px;margin-bottom: 0px;">S.Y.{{$schoolyear->sydesc}}</h2> --}}
                                {{-- <h1>PROGRESS REPORT</h1> --}}
                                {{-- <img src="{{base_path()}}/public/assets/images/msmi/msmi-preschool-rc6.png" alt="school" width="250px;"> --}}
                                <div style="font-size: 13px;width: 100%;font-weight: bold;">PROGRESS REPORT CARD</div>
                                <div style="font-size: 13px;width: 100%;font-weight: bold;">Government Recognition No. 003 s. 2010</div>
                                <div style="font-size: 13px;width: 100%;font-weight: bold;">ACSCU-AAI LEVEL II ACCREDITED</div>
                                <div style="font-size: 13px;width: 100%;font-weight: bold;">School ID No. {{$schoolinfo[0]->schoolid}}</div>
                                <div style="font-size: 13px;width: 100%;font-weight: bold;">School Year: {{$schoolyear->sydesc}}</div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="2" style="border: 1px solid;padding: 10px;font-size: 15px;text-align: center; background-color: #91cce6;font-weight: bold;">PUPIL’S INFORMATION</td>
                        </tr>
                    </table>
                    <div style="margin-top: 5px;width: 100%; height: 350px;border: 8px solid #a3e9f0;border-radius: 10px;" >
                        <div style="width: 100%; height: 350px;background: url('{{url('/')}}/assets/images/bct/photo1.png') no-repeat center;background-size: 100% 100%; border: 1px solid #ddd;" >
                                <div style="bottom: 0; border: 3px solid #72aeb5; margin-top: 30%; margin-left: 20%; margin-right: 20%; background-color: white; height: 200px;">
                                    <table style="width: 100%; margin: 10px; text-align: center;">
                                        <tr>
                                            <td style="border-bottom: 1px solid black; font-weight: bold;">{{$student[0]->lastname}}, {{$student[0]->firstname}} {{$student[0]->middlename != null ? $student[0]->middlename[0].'.' : '' }}</td>
                                        </tr>
                                        <tr>
                                            <td >Pupil's Name</td>
                                        </tr>
                                        <tr>
                                            <td style="border-bottom: 1px solid black; font-weight: bold;">{{\Carbon\Carbon::parse($student[0]->dob)->age}}</td>
                                        </tr>
                                      
                                        <tr>
                                            <td>Age as of October 31</td>
                                        </tr>
                                        <tr>
                                            <td style="border-bottom: 1px solid black; font-weight: bold;">{{$student[0]->gender}}</td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight: bold;"><u></u></td>
                                        </tr>
                                        <tr>
                                            <td>Sex</td>
                                        </tr>
                                    </table>
                                </div>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
        {{-- <br/> --}}
        
        <div class="page_break"></div>
        <table style="width: 100%; font-size: 14px;table-layout: fixed;">
            <tr>
                <td style="width:33.3333333333%; padding-right:15px;vertical-align: top">
                    <table style="width: 100%;" border="1">
                        <thead style="text-align: center;">
                            <tr>
                                <th style="width: 70%;"></th>
                                <th>1</th>
                                <th>2</th>
                                <th>3</th>
                                <th>4</th>
                            </tr>
                        </thead>
                        <tr style="background-color:#8dcf5f; font-weight: bold;">
                            <td style="padding-left:9px;">I. GROSS MOTOR</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="padding-left:9px;">Coordination of leg movements</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['1B','AA'])->first()->q1eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['1B','AA'])->first()->q2eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['1B','AA'])->first()->q3eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['1B','AA'])->first()->q4eval}}</td>
                        </tr>
                        <tr>
                            <td style="padding-left:9px;">Coordination of arm movements</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['1C','AB'])->first()->q1eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['1C','AB'])->first()->q2eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['1C','AB'])->first()->q3eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['1C','AB'])->first()->q4eval}}</td>
                        </tr>
                        <tr>
                            <td style="padding-left:9px;">Movement of body parts as instructed.</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['1D','AC'])->first()->q1eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['1D','AC'])->first()->q2eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['1D','AC'])->first()->q3eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['1D','AC'])->first()->q4eval}}</td>
                        </tr>
                        <tr style="background-color:#8dcf5f; font-weight: bold;">
                            <td style="padding-left:9px;">II. FINE MOTOR</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="padding-left:9px;">Coordination in the use of fingers in picking objects</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['2B','BA'])->first()->q1eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['2B','BA'])->first()->q2eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['2B','BA'])->first()->q3eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['2B','BA'])->first()->q4eval}}</td>
                        </tr>
                        <tr>
                            <td style="padding-left:9px;">Coordination of fingers for scribbling and drawing.</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['2C','BB'])->first()->q1eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['2C','BB'])->first()->q2eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['2C','BB'])->first()->q3eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['2C','BB'])->first()->q4eval}}</td>
                        </tr>
                        <tr>
                            <td style="padding-left:9px;">Display of definite hand preference (either left or right)</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['2D','BC'])->first()->q1eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['2D','BC'])->first()->q2eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['2D','BC'])->first()->q3eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['2D','BC'])->first()->q4eval}}</td>
                        </tr>
                        {{-- <tr style="background-color:#8dcf5f; font-weight: bold;">
                            <td style="padding-left:9px;">III. SELF HELP</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="padding-left:9px;">Use of fingers properly in feeding own self</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="padding-left:9px;">Use of utensils for drinking.</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="padding-left:9px;">Dressing own self</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="padding-left:9px;">Cleaning own self properly after using the comfort room.</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>--}}
                        <tr style="background-color:#8dcf5f; font-weight: bold;">
                            <td style="padding-left:9px;">IV. RECEPTIVE LANGUAGE</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="padding-left:9px;">Following instructions correctly.</td>
                           <td class="text-center">{{collect($checkGrades)->whereIn('sort',['3B','CA'])->first()->q1eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['3B','CA'])->first()->q2eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['3B','CA'])->first()->q3eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['3B','CA'])->first()->q4eval}}</td>
                        </tr>
                        <tr>
                            <td style="padding-left:9px;">Pointing family members correctly when ask to do so</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['3C','CB'])->first()->q1eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['3C','CB'])->first()->q2eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['3C','CB'])->first()->q3eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['3C','CB'])->first()->q4eval}}</td>
                        </tr>
                        <tr>
                            <td style="padding-left:9px;">Pointing named objects correctly when ask to do so</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['3D','CC'])->first()->q1eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['3D','CC'])->first()->q2eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['3D','CC'])->first()->q3eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['3D','CC'])->first()->q4eval}}</td>
                        </tr>
                        <tr style="background-color:#8dcf5f; font-weight: bold;">
                            <td style="padding-left:9px;">V. EXPRESSIVE LANGUAGE</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="padding-left:9px;">Using recognizable words correctly.</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['4B','DA'])->first()->q1eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['4B','DA'])->first()->q2eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['4B','DA'])->first()->q3eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['4B','DA'])->first()->q4eval}}</td>
                        </tr>
                        <tr>
                            <td style="padding-left:9px;">Naming objects and pictures correctly</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['4C','DB'])->first()->q1eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['4C','DB'])->first()->q2eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['4C','DB'])->first()->q3eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['4C','DB'])->first()->q4eval}}</td>
                        </tr>
                        <tr>
                            <td style="padding-left:9px;">Asking questions appropriately (who, what, when, why, how?)</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['4D','DC'])->first()->q1eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['4D','DC'])->first()->q2eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['4D','DC'])->first()->q3eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['4D','DC'])->first()->q4eval}}</td>
                        </tr>
                        <tr>
                            <td style="padding-left:9px;">Telling account of recent experiences.</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['4E','DD'])->first()->q1eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['4E','DD'])->first()->q2eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['4E','DD'])->first()->q3eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['4E','DD'])->first()->q4eval}}</td>
                        </tr>
                    </table>
                    <br/>
                    <table style="width: 100%; text-align: left;">
                        <thead>
                            <tr>
                                <th>
                                    Marks for the Progress Report:
                                </th>
                            </tr>
                        </thead>
                        <tr>
                            <td>A- Highly Advanced Development	</td>
                        </tr>
                        <tr>
                            <td>B- Slightly Advanced Development </td>
                        </tr>
                        <tr>
                            <td>C - Average Development		</td>
                        </tr>
                        <tr>
                            <td>D- Slight Delay in Development</td>
                        </tr>
                        <tr>
                            <td>E – Significant Delay in Development</td>
                        </tr>
                    </table>
                </td>
                <td style="width:33.3333333333%; padding-right:15px;padding-left:15px; vertical-align: top;">
                    <table style="width: 100%;" border="1">
                        <thead style="text-align: center;">
                            <tr>
                                <th style="width: 70%;"></th>
                                <th>1</th>
                                <th>2</th>
                                <th>3</th>
                                <th>4</th>
                            </tr>
                        </thead>
                        <tr style="background-color:#8dcf5f; font-weight: bold;">
                            <td style="padding-left:9px;">VI. COGNITIVE DEVELOPMENT</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr style="background-color:#cbedaf; font-weight: bold;">
                            <td style="padding-left:9px;">A. WRITING READINESS</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="padding-left:9px;"> Exhibition of left to right progression.</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FA2','EAA'])->first()->q1eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FA2','EAA'])->first()->q2eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FA2','EAA'])->first()->q3eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FA2','EAA'])->first()->q4eval}}</td>
                        </tr>
                        <tr>
                            <td style="padding-left:9px;">Writing name correctly</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FA3','EAB'])->first()->q1eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FA3','EAB'])->first()->q2eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FA3','EAB'])->first()->q3eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FA3','EAB'])->first()->q4eval}}</td>
                        </tr>
                        <tr>
                            <td style="padding-left:9px;"> Writing upper case or lower case letters from memory</td>
                             <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FA4','EAC'])->first()->q1eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FA4','EAC'])->first()->q2eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FA4','EAC'])->first()->q3eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FA4','EAC'])->first()->q4eval}}</td>
                        </tr>
                        <tr>
                            <td style="padding-left:9px;"> Correctly copying shapes</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FA5','EAD'])->first()->q1eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FA5','EAD'])->first()->q2eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FA5','EAD'])->first()->q3eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FA5','EAD'])->first()->q4eval}}</td>
                        </tr>
                        <tr style="background-color:#cbedaf; font-weight: bold;">
                            <td style="padding-left:9px;">B. READING READINESS</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="padding-left:9px;">Correct Identification of objects and pictures</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FB2','EBA'])->first()->q1eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FB2','EBA'])->first()->q2eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FB2','EBA'])->first()->q3eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FB2','EBA'])->first()->q4eval}}</td>
                        </tr>
                        <tr>
                            <td style="padding-left:9px;">Correctly identifies similarities and differences of objects and pictures</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FB3','EBB'])->first()->q1eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FB3','EBB'])->first()->q2eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FB3','EBB'])->first()->q3eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FB3','EBB'])->first()->q4eval}}</td>
                        </tr>
                        <tr>
                            <td style="padding-left:9px;">Correct identification of upper or lower case letters from memory </td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FB4','EBC'])->first()->q1eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FB4','EBC'])->first()->q2eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FB4','EBC'])->first()->q3eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FB4','EBC'])->first()->q4eval}}</td>
                        </tr>
                        <tr>
                            <td style="padding-left:9px;">Correctly matching objects or pictures with the alphabet</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FB5','EBD'])->first()->q1eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FB5','EBD'])->first()->q2eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FB5','EBD'])->first()->q3eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FB5','EBD'])->first()->q4eval}}</td>
                        </tr>
                        <tr>
                            <td style="padding-left:9px;">Correctly sorting out pictures, alphabet, or shapes</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FB6','EBE'])->first()->q1eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FB6','EBE'])->first()->q2eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FB6','EBE'])->first()->q3eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FB6','EBE'])->first()->q4eval}}</td>
                        </tr>
                        <tr>
                            <td style="padding-left:9px;">Correctly following signs and symbols</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FB7','EBF'])->first()->q1eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FB7','EBF'])->first()->q2eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FB7','EBF'])->first()->q3eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FB7','EBF'])->first()->q4eval}}</td>
                        </tr>
                       
                        <tr style="background-color:#cbedaf; font-weight: bold;">
                            <td style="padding-left:9px;">C. LANGUAGE</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="padding-left:9px;">Listening attentively to someone who speaks.</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FC2','ECA'])->first()->q1eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FC2','ECA'])->first()->q2eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FC2','ECA'])->first()->q3eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FC2','ECA'])->first()->q4eval}}</td>
                        </tr>
                        <tr>
                            <td style="padding-left:9px;"> Correctly distinguish different type of sounds.</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FC3','ECB'])->first()->q1eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FC3','ECB'])->first()->q2eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FC3','ECB'])->first()->q3eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FC3','ECB'])->first()->q4eval}}</td>
                        </tr>
                        <tr>
                            <td style="padding-left:9px;">Responding correctly to different type of sounds.</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FC4','ECC'])->first()->q1eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FC4','ECC'])->first()->q2eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FC4','ECC'])->first()->q3eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FC4','ECC'])->first()->q4eval}}</td>
                        </tr>
                        <tr>
                            <td style="padding-left:9px;">Recalling significant facts in a story.</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FC5','ECD'])->first()->q1eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FC5','ECD'])->first()->q2eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FC5','ECD'])->first()->q3eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FC5','ECD'])->first()->q4eval}}</td>
                        </tr>
                        <tr>
                            <td style="padding-left:9px;">Expressing own thoughts, feelings and ideas.</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FC6','ECE'])->first()->q1eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FC6','ECE'])->first()->q2eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FC6','ECE'])->first()->q3eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FC6','ECE'])->first()->q4eval}}</td>
                        </tr>
                        <tr>
                            <td style="padding-left:9px;">Exhibiting comprehension of learned concepts.</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FC7','ECF'])->first()->q1eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FC7','ECF'])->first()->q2eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FC7','ECF'])->first()->q3eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FC7','ECF'])->first()->q4eval}}</td>
                        </tr>
                        <tr>
                            <td style="padding-left:9px;">Responding correctly to questions asked.</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FC8','ECG'])->first()->q1eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FC8','ECG'])->first()->q2eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FC8','ECG'])->first()->q3eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FC8','ECG'])->first()->q4eval}}</td>
                        </tr>
                        <tr>
                            <td style="padding-left:9px;">Reciting poems and verses.</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FC9','ECH'])->first()->q1eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FC9','ECH'])->first()->q2eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FC9','ECH'])->first()->q3eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FC9','ECH'])->first()->q4eval}}</td>
                        </tr>
                    </table>
                </td>
                <td style="width:33.3333333333%;padding-left:15px; vertical-align: top;">
                    <table style="width: 100%;" border="1">
                        <thead style="text-align: center;">
                            <tr>
                                <th style="width: 70%;"></th>
                                <th>1</th>
                                <th>2</th>
                                <th>3</th>
                                <th>4</th>
                            </tr>
                        </thead>
                        <tr style="background-color:#cbedaf; font-weight: bold;">
                            <td style="padding-left:9px;">D. MATH AND SCIENCE</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="padding-left:9px;">Reciting correctly numbers 1 to 10.</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FD2','EDA'])->first()->q1eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FD2','EDA'])->first()->q2eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FD2','EDA'])->first()->q3eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FD2','EDA'])->first()->q4eval}}</td>
                        </tr>
                        <tr>
                            <td style="padding-left:9px;"> Writing numerals 1 to 10.</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FD3','EDB'])->first()->q1eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FD3','EDB'])->first()->q2eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FD3','EDB'])->first()->q3eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FD3','EDB'])->first()->q4eval}}</td>
                        </tr>
                        <tr>
                            <td style="padding-left:9px;">Identifying correctly the number of animals, objects, or pictures</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FD4','EDC'])->first()->q1eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FD4','EDC'])->first()->q2eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FD4','EDC'])->first()->q3eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FD4','EDC'])->first()->q4eval}}</td>
                        </tr>
                        <tr>
                            <td style="padding-left:9px;">Correct identification of shapes </td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FD5','EDD'])->first()->q1eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FD5','EDD'])->first()->q2eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FD5','EDD'])->first()->q3eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FD5','EDD'])->first()->q4eval}}</td>
                        </tr>
                        <tr>
                            <td style="padding-left:9px;">Showing understanding on the concept of length, mass, volume/capacity</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FD6','EDE'])->first()->q1eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FD6','EDE'])->first()->q2eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FD6','EDE'])->first()->q3eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FD6','EDE'])->first()->q4eval}}</td>
                        </tr>
                        <tr>
                            <td style="padding-left:9px;">Exhibiting interests and curiosity about the environment</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FD7','EDF'])->first()->q1eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FD7','EDF'])->first()->q2eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FD7','EDF'])->first()->q3eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FD7','EDF'])->first()->q4eval}}</td>
                        </tr>
                        <tr>
                            <td style="padding-left:9px;">Showing interests and curiosity about living organism.</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FD8','EDG'])->first()->q1eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FD8','EDG'])->first()->q2eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FD8','EDG'])->first()->q3eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FD8','EDG'])->first()->q4eval}}</td>
                        </tr>
                        <tr style="background-color:#cbedaf; font-weight: bold;">
                            <td style="padding-left:9px;">E. MUSIC AND ARTS</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="padding-left:9px;">Participation in music and art related activities</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FE2','EEA'])->first()->q1eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FE2','EEA'])->first()->q2eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FE2','EEA'])->first()->q3eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FE2','EEA'])->first()->q4eval}}</td>
                        </tr>
                        <tr>
                            <td style="padding-left:9px;">Skill in drawing, singing, dancing, and/or acting.</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FE3','EEB'])->first()->q1eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FE3','EEB'])->first()->q2eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FE3','EEB'])->first()->q3eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FE3','EEB'])->first()->q4eval}}</td>
                        </tr>
                        <tr>
                            <td style="padding-left:9px;">Exhibiting interests in music and rhythm</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FE4','EEC'])->first()->q1eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FE4','EEC'])->first()->q2eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FE4','EEC'])->first()->q3eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FE4','EEC'])->first()->q4eval}}</td>
                        </tr>
                        <tr>
                            <td style="padding-left:9px;">Exhibiting ideas and feelings through print or art media</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FE5','EED'])->first()->q1eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FE5','EED'])->first()->q2eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FE5','EED'])->first()->q3eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FE5','EED'])->first()->q4eval}}</td>
                        </tr>
                        <tr style="background-color:#8dcf5f; font-weight: bold;">
                            <td style="padding-left:9px;">VII. SOCIAL, EMOTIONAL AND SPIRITUAL DEVELOPMENT</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="padding-left:9px;">1.Exhibiting concepts and feelings about self, family, school and community</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FG2','FA'])->first()->q1eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FG2','FA'])->first()->q2eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FG2','FA'])->first()->q3eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FG2','FA'])->first()->q4eval}}</td>
                        </tr>
                        <tr>
                            <td style="padding-left:9px;">2. Willingness to be with peers, adults and strangers.</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FG3','FB'])->first()->q1eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FG3','FB'])->first()->q2eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FG3','FB'])->first()->q3eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FG3','FB'])->first()->q4eval}}</td>
                        </tr>
                        <tr>
                            <td style="padding-left:9px;">3. Demonstration of courtesy and respect</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FG4','FC'])->first()->q1eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FG4','FC'])->first()->q2eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FG4','FC'])->first()->q3eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FG4','FC'])->first()->q4eval}}</td>
                        </tr>
                        <tr>
                            <td style="padding-left:9px;">4. Correct identification of feelings of others</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FG5','FD'])->first()->q1eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FG5','FD'])->first()->q2eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FG5','FD'])->first()->q3eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FG5','FD'])->first()->q4eval}}</td>
                        </tr>
                        <tr>
                            <td style="padding-left:9px;">5. Showing cooperation in group situations.</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FG6','FE'])->first()->q1eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FG6','FE'])->first()->q2eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FG6','FE'])->first()->q3eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FG6','FE'])->first()->q4eval}}</td>
                        </tr>
                        <tr>
                            <td style="padding-left:9px;">6. Expressing own feelings.</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FG7','FF'])->first()->q1eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FG7','FF'])->first()->q2eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FG7','FF'])->first()->q3eval}}</td>
                            <td class="text-center">{{collect($checkGrades)->whereIn('sort',['FG7','FF'])->first()->q4eval}}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>