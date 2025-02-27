<html>
    <head>
        <style>
            @page{
                margin: 70px 20px;
            }
            .font-one{
                font-family:  "Times New Roman", Georgia, serif;
                font-stretch: semi-expanded
;
            }
            .font-two{
                font-family: 'Bookman', 'URW Bookman L', serif;
                
            }
            td{
                padding: 0px;
            }
            /* header {
                position: fixed;
                top: -60px;
                left: 0px;
                right: 0px;
                height: 50px;
                color: white;
                text-align: center;
                line-height: 35px;
            }

            footer {
                position: fixed; 
                bottom: -60px; 
                left: 0px; 
                right: 0px;
                height: 50px; 

                color: white;
                text-align: center;
                line-height: 35px;
            } */
            header {
                position: fixed;
                top: -60px;
                left: 0px;
                right: 0px;
                height: 50px;

                /** Extra personal styles **/
                /* background-color: #03a9f4; */
                /* color: white; */
                /* text-align: center; */
                /* line-height: 35px; */
            }

            footer {
                position: fixed; 
                bottom: 300px; 
                left: 0px; 
                right: 0px;
                /* height: 500px;  */

                /** Extra personal styles **/
                /* background-color: #03a9f4; */
                /* color: white; */
                /* text-align: center; */
                /* line-height: 35px; */
            }
        </style>
    </head>
    <body>
        @php
            $url = asset($studentinfo->picurl);
            $parts = parse_url($url);
            $fullurl = $parts['scheme'] . '://' . $parts['host'].'/';
        $registrarname = '';

        $registrar = DB::table('teacher')
            ->where('userid', auth()->user()->id)
            ->first();
            
        if($registrar)
        {
            if($registrar->firstname != null)
            {
                $registrarname.=$registrar->firstname.' ';
            }
            if($registrar->middlename != null)
            {
                $registrarname.=$registrar->middlename[0].'. ';
            }
            if($registrar->lastname != null)
            {
                $registrarname.=$registrar->lastname;
            }
        }
        @endphp
        <!-- Define header and footer blocks before your content -->
        {{-- <header>
            <table style="width: 100%">
                <thead>
                    <tr>
                        <th style="width: 20%; text-align: right;">
                            <img src="{{base_path()}}/public/{{$schoolinfo->picurl}}" alt="school" width="80px"/>
                        </th>
                        <th style="width: 80%;" colspan="2">
                            <img src="{{base_path()}}/public/assets/images/gbbc/tor-header.png" alt="school" width="500px"/>
                        </th>
                    </tr>
                    <tr>
                        <th rowspan="2"></th>
                        <th style="width: 60%;text-align: center; font-weight: bolder; color: #2d7691; font-size: 15px;" class="font-one">Office of the Registrar</th>
                        <th style="width: 20%" rowspan="2">
                            <img src="{{$fullurl}}{{$studentinfo->picurl}}" alt="school" width="500px"/>
                        </th>
                    </tr>
                    <tr>
                        <th style="width: 60%;text-align: center; font-weight: bolder; color: #2d6c91; font-size: 23px;" class="font-one">Offical Transcript of Record</th>
                    </tr>
                </thead>
            </table>
        </header>

        <footer>
            <table style="width: 100%; font-size: 12px; margin-left: 20px; font-weight: bold;">
                            <thead>
                                <tr>
                                    <th style="width: 2%;"></th>
                                    <th style="width: 10%;">Remarks:</th>
                                    <th style="border-bottom: 1px solid black; width: 83%;"></th>
                                    <th style="width: 5%;"></th>
                                </tr>
                            </thead>
                            <tr>
                                <td style="width: 2%;">&nbsp;</td>
                                <td colspan="2" style="width: 93%; border-bottom: 1px solid black;">&nbsp;</td>
                                <td style="width: 5%;">&nbsp;</td>
                            </tr>
                        </table>
                        <table style="width: 100%; font-size: 13px;">
                            <thead>
                                <tr>
                                    <th style="width: 2%;">&nbsp;</th>
                                    <th style="width: 93%;">GRADING SYSTEM:</th>
                                    <td style="width: 5%;">&nbsp;</td>
                                </tr>
                            </thead>
                        </table>
                        <table style="width: 100%; font-size: 12px;">
                            <thead>
                                <tr>
                                    <th style="width: 2%;"></th>
                                    <th style="width: 29%;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;95-100-Denotes Excellent</th>
                                    <th style="width: 28%;">80-84&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- &nbsp;Denotes Satisfactory</th>
                                    <th style="width: 20%;">W&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;Withdrawn</th>
                                    <th style="width: 21%;">&nbsp;&nbsp;FD&nbsp;&nbsp;-&nbsp;Failure Debarred</th>
                                </tr>
                                <tr>
                                    <th style="width: 2%;"></th>
                                    <th style="width: 29%;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;90-94&nbsp;&nbsp;-Denotes Very Good</th>
                                    <th style="width: 28%;">75-79&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- &nbsp;Denotes Fair</th>
                                    <th style="width: 20%;">Inc.&nbsp;&nbsp;-&nbsp;&nbsp;Incomplete</th>
                                    <th style="width: 21%;">&nbsp;&nbsp;Drp&nbsp;&nbsp;-&nbsp;&nbsp;Dropped</th>
                                </tr>
                                <tr>
                                    <th style="width: 2%;"></th>
                                    <th style="width: 29%;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;85-89-Denotes Good</th>
                                    <th style="width: 28%;">74 & below&nbsp;&nbsp;- &nbsp;Signifies Failure</th>
                                    <th style="width: 20%;">WF&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;Withdrawn Failure</th>
                                    <th style="width: 21%;"></th>
                                </tr>
                            </thead>
                        </table>
                        <table style="width: 100%; font-size: 13px;">
                            <thead>
                                <tr>
                                    <th style="width: 2%;">&nbsp;</th>
                                    <th style="width: 7%; font-weight: bold;">Note:</th>
                                    <th style="width: 91%; font-weight: bold;"></th>
                                </tr>
                                <tr>
                                    <th style="width: 2%;">&nbsp;</th>
                                    <th style="width: 7%;">&nbsp;</th>
                                    <th style="width: 91%;">
                                        &nbsp;&nbsp;&nbsp;This transcript is valid only when it bears the seal of the College
                                    </th>
                                </tr>
                                <tr>
                                    <th style="width: 2%;">&nbsp;</th>
                                    <th style="width: 7%;">&nbsp;</th>
                                    <th style="width: 91%;">
                                        &nbsp;&nbsp;&nbsp;and the original signature in ink of the Registrar.
                                    </th>
                                </tr>
                                <tr>
                                    <th style="width: 2%;">&nbsp;</th>
                                    <th style="width: 7%;">&nbsp;</th>
                                    <th style="width: 91%;">
                                        &nbsp;&nbsp;&nbsp;Any erasures or alteration made on the entries of this form renders this transcript null and void.
                                    </th>
                                </tr>
                            </thead>
                        </table>
                        <table style="width: 100%;">
                            <thead style="font-size: 13px;">
                                <tr>
                                    <th style="width: 100%;">&nbsp;</th>
                                </tr>
                                <tr>
                                    <th style="width: 25%;">&nbsp;</th>
                                    <th style="width: 75%;">Prepared by:</th>
                                </tr>
                            </thead>
                        </table>
                        <table style="width: 100%; font-size: 13px;">
                            <tr>
                                <th style="width: 30%;">&nbsp;</th>
                                <th style="width: 40%; text-align: center;">asdada</th>
                                <th style="width: 30%;">&nbsp;</th>
                            </tr>
                        </table>
                        <table style="width: 100%; font-size: 13px;">
                            <tr>
                                <td style="width: 30%; font-size: 11px;"><em>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;School Seal</em></td>
                                <td style="width: 40%; font-size: 11px; text-align: center;">Assistant Registrar</td>
                                <td style="width: 30%; text-align: center;">'.$registrarname.'</td>
                            </tr>
                            <tr>
                                <td style="width: 30%; font-size: 11px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;O.R #:</td>
                                <td style="width: 40%; font-size: 11px; text-align: center;"></td>
                                <td style="width: 30%; text-align: center; font-size: 11px;">Registrar</td>
                            </tr>
                            <tr>
                                <td style="width: 30%; font-size: 11px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Date Issued:</td>
                                <td style="width: 40%; font-size: 11px; text-align: center;"></td>
                                <td style="width: 30%; text-align: center;"></td>
                            </tr>
                        </table>
        </footer>

        <!-- Wrap the content of your PDF inside a main tag -->
        <main>
            <p style="page-break-after: always;">
                Content Page 1
            </p>
            <p style="page-break-after: never;">
                Content Page 2
            </p>
        </main> --}}
        @php
            $url = asset($studentinfo->picurl);
            $parts = parse_url($url);
            $fullurl = $parts['scheme'] . '://' . $parts['host'].'/';
        @endphp
        
        <table style="width: 100%">
            <thead>
                <tr>
                    <th style="width: 20%; text-align: right;">
                        <img src="{{base_path()}}/public/{{$schoolinfo->picurl}}" alt="school" width="80px"/>
                    </th>
                    <th style="width: 80%;" colspan="2">
                        <img src="{{base_path()}}/public/assets/images/gbbc/tor-header.png" alt="school" width="500px"/>
                    </th>
                </tr>
                <tr>
                    <th rowspan="2"></th>
                    <th style="width: 60%;text-align: center; font-weight: bolder; color: #2d7691; font-size: 15px;" class="font-one">Office of the Registrar</th>
                    <th style="width: 20%" rowspan="2">
                        <img src="{{$fullurl}}{{$studentinfo->picurl}}" alt="school" width="500px"/>
                    </th>
                </tr>
                <tr>
                    <th style="width: 60%;text-align: center; font-weight: bolder; color: #2d6c91; font-size: 23px;" class="font-one">Offical Transcript of Record</th>
                </tr>
            </thead>
            <tr>
                <td style="vertical-align: top; padding: 0px; font-size: 11px; width: 100%;" colspan="3"><table style="width: 100%; margin: 0px;">
                        <tr>
                            <td colspan="4" style="width: 100%;">Revised Transcript</td>
                        </tr>
                        <tr>
                            <td style="width: 18%;">Name:</td>
                            <td style="width: 32%;border-bottom: 1px solid black;" colspan="3"></td>
                            <td style="width: 15%;">Address:</td>
                            <td style="width: 35%;border-bottom: 1px solid black;" colspan="3"></td>
                        </tr>
                        <tr>
                            <td style="width: 18%;">Date of Birth:</td>
                            <td style="width: 17%;border-bottom: 1px solid black;"></td>
                            <td style="width: 5%;">Sex</td>
                            <td style="width: 10%;border-bottom: 1px solid black;"></td>
                            <td style="width: 15%;">Place of Birth:</td>
                            <td style="width: 35%;border-bottom: 1px solid black;" colspan="3"></td>
                        </tr>
                        <tr>
                            <td style="width: 18%;">Parent or Guardian:</td>
                            <td style="width: 32%;border-bottom: 1px solid black;" colspan="3"></td>
                            <td style="width: 15%;">Address:</td>
                            <td style="width: 35%;border-bottom: 1px solid black;" colspan="3"></td>
                        </tr>
                        <tr>
                            <td style="width: 18%;">Elementary Course:</td>
                            <td style="width: 32%;border-bottom: 1px solid black;" colspan="3"></td>
                            <td style="width: 15%;">Date Complete:</td>
                            <td style="width: 35%;border-bottom: 1px solid black;" colspan="3"></td>
                        </tr>
                        <tr>
                            <td style="width: 18%;">Secondary Course:</td>
                            <td style="width: 32%;border-bottom: 1px solid black;" colspan="3"></td>
                            <td style="width: 15%;">Date Complete:</td>
                            <td style="width: 35%;border-bottom: 1px solid black;" colspan="3"></td>
                        </tr>
                        <tr>
                            <td style="width: 18%;">Admission Date:</td>
                            <td style="width: 32%;border-bottom: 1px solid black;" colspan="3"></td>
                            <td style="width: 15%;">Degree:</td>
                            <td style="width: 35%;border-bottom: 1px solid black;" colspan="3"></td>
                        </tr>
                        <tr>
                            <td style="width: 18%;">Basis of Admission:</td>
                            <td style="width: 32%;border-bottom: 1px solid black;" colspan="3">Form 138-A</td>
                            <td style="width: 15%;">Major:</td>
                            <td style="width: 14%;border-bottom: 1px solid black;"></td>
                            <td style="width: 7%;">Minor:</td>
                            <td style="width: 14%;border-bottom: 1px solid black;"></td>
                        </tr>
                        <tr>
                            <td style="width: 18%;border-bottom: 1px solid black;">Special Order:</td>
                            <td style="width: 32%;border-bottom: 1px solid black;" colspan="3"></td>
                            <td style="width: 15%;border-bottom: 1px solid black;">Graduation Date:</td>
                            <td style="width: 35%;border-bottom: 1px solid black;"></td>
                        </tr>
                    </table>
                    <div style="line-height: 5px;"></div>
                    <table style="width: 100%; margin: 0px; max-height: 500px;" class="font-two">
                        <thead>
                            <tr style="font-weight: bold; font-size: 14px; font-weight: bold;">
                                <th style="width: 30%; border-bottom: 1px solid black;border-top: 1px solid black;" colspan="2">COURSE NUMBER</th>
                                <th style="width: 45%; border-bottom: 1px solid black;border-top: 1px solid black;" colspan="2">DESCRIPTIVE TITLE OF THE COURSE</th>
                                <th style="width: 12%; text-align: center; border-bottom: 1px solid black;border-top: 1px solid black;">GRADE</th>
                                <th style="width: 13%; border-bottom: 1px solid black;border-top: 1px solid black;">CREDITS</th>
                            </tr>
                        </thead>
                        @if(count($records)>0)
                            @foreach($records as $record)
                                <tr>
                                    <td colspan="6" style="text-align: center; width: 100%;"><u style="text-transform: uppercase; font-weight: bold; font-size: 14px;">{{$record->schoolname}}</u></td>
                                </tr>
                                <tr style="font-size: 13px;">
                                    <td style="width: 14%; font-weight: bold;"><u>@if($record->semid == 1)First Semester @elseif($record->semid == 2)Second Semester @endif</u></td>
                                    <td style="width: 5%;"></td>
                                    <td style="width: 11%; text-align: right;"><u>{{$record->sydesc}}</u>&nbsp;&nbsp;</td>
                                    <td style="width: 45%;"></td>
                                    <td style="width: 12%;"></td>
                                    <td style="width: 13%;"></td>
                                </tr>
                                @if(count($record->subjdata)>0)
                                    @foreach($record->subjdata as $subj)
                                        <tr style="font-size: 12px;">
                                            <td style="width: 14%;">{{$subj->subjcode}}</td>
                                            <td style="width: 5%;">{{$subj->subjunit}}</td>
                                            <td style="width: 11%;">&nbsp;</td>
                                            <td style="width: 45%;">{{$subj->subjdesc}}</td>
                                            <td style="width: 12%; text-align: right; font-weight: bold;">{{$subj->subjgrade}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                            <td style="width: 13%;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$subj->subjcredit}}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            @endforeach
                        @endif
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>