
<html>
<head>
  <style>
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
      *{
          
        font-family:  "Times New Roman", Georgia, serif;
      }
      /* table{
          border-collapse: collapse;
      } */
    @page { margin: 80px 25px; size: 8.5in 14in}
    header { position: relative; top: -60px; left: 0px; right: 0px; height: 250px; }
    footer { position: fixed; bottom: -30px; left: 0px; right: 0px; height: 250px; }
    /* p { page-break-after: always;} */
    p:last-child { page-break-after: never; }
    
    #watermark {
                position: fixed;

                /** 
                    Set a position in the page for your image
                    This should center it vertically
                **/
                /* ==========  1st page  ========== */
                /* top: 9.5cm;
                bottom:   22cm;
                left:     1cm;
                opacity: 0.1; */
                
                /* ========== 2nd page  ========== */
                top: 5.3cm;
                bottom:   22cm;
                left:     1cm;
                opacity: 0.1;

                /** Change image dimensions**/
                /* width:    8cm;
                height:   8cm; */

                /** Your watermark should be behind every content**/
                z-index:  -1000;
            }
  </style>
</head>
<body>
<!-- <div id="watermark">
    <img src="{{base_path()}}/public/{{$schoolinfo->picurl}}" width="700px" />
</div> -->
<div class="headersection">
    @php
    // $registrarname = '';

    // $registrar = DB::table('teacher')
    //     ->where('userid', auth()->user()->id)
    //     ->first();
        
    // if($registrar)
    // {
    //     if($registrar->firstname != null)
    //     {
    //         $registrarname.=$registrar->firstname.' ';
    //     }
    //     if($registrar->middlename != null)
    //     {
    //         $registrarname.=$registrar->middlename[0].'. ';
    //     }
    //     if($registrar->lastname != null)
    //     {
    //         $registrarname.=$registrar->lastname;
    //     }
    // }
            $address = '';
            if($studentinfo->street != null)
            {
                $address.=$studentinfo->street.', ';
            }
            if($studentinfo->barangay != null)
            {
                $address.=$studentinfo->barangay.', ';
            }
            if($studentinfo->city != null)
            {
                $address.=$studentinfo->city.', ';
            }
            if($studentinfo->province != null)
            {
                $address.=$studentinfo->province;
            }
    @endphp
  <header>
    <table width="100%" height="200px">
        <tr background-color="green">
            <td style="width: 25%; text-align: center"><img src="{{base_path()}}/public/assets/images/sbc/logo.png" alt="school" style="height:120px; width:120px;"/></td>
            <td style="width: 50%; text-align:center;">
                <span style="font-size:20px; font-weight:bold">SOUTHERN BAPTIST COLLGE</span><br>
                <span style="font-size:15px; font-weight:bold">M'lang, Cotabato</span><br>
                <span style="font-size:13px">Tel.No.(064) 572-4020</span><br>
                <br>
                <span>OFFICE OF THE REGISTRAR</span><br>
                <span style="font-size:13px">COLLEGE CODE: 12066</span><br>
                <span style="font-size:12px; color: #207ACC;">Accredited Level II: Association of Christian School, Colleges & Universities-<br>
                      Accrediting Council, Inc (ACSCU-ACI)
                </span>
            </td>
            <td style="width: 25%;">&nbsp;</td>
        </tr>
    </table>
    <hr style="border: .2px solid #000;border-radius:1px">
    <hr style="border: 2px solid #000; margin-top: -7px">
</header>
</div>
<!-- ==================================================================================================== -->
<!-- <div class="bodysection" style="margin-top: -170px">
<p style="font-size:20px; text-align:center;">OFFICIAL TRANSCRIPT OF RECORDS</p>
    <table border-spacing="5px" style="width: 100%; margin: 0px; font-size: 12px; table-layout: fixed !important;">
            <tr>
                <td colspan="5"></td>
                <td style="width: 20%; border-bottom: 1px solid #000000;text-align:center!important;" colspan="2"></td>
            </tr>
            <tr>
                <td colspan="5"></td>
                <td style="width: 25%; text-align:center;font-size: 10px;" colspan="2"><span style="">Date</span></td>
            </tr>
            <tr>
                <td style="width: 15%;">Name:</td>
                <td style="width: 50%; border-bottom: 1px solid #000000;" colspan="3"></td>
            </tr>
            <tr>
                <td style="width: 15%;"></td>
                <td style="width: 50%;position: absolute;text-align:center;font-size: 10px;" colspan="3">
                    <span style="position: absolute;left:0px">Last Name</span>
                    <span style="">First Name</span>
                    <span style="position: absolute;right:0px" colspan="2">Middle Name</span>
                </td>
            </tr>
            <tr>
                <td style="width: 15%;">Parent/Guardian:</td>
                <td style="width: 50%; border-bottom: 1px solid #000000;" colspan="3"></td>
                <td style="width: 10%; padding-left: 10px;">Gender:</td>
                <td style="width: 25%; border-bottom: 1px solid #000000;" colspan="2"></td>
            </tr>
            <tr>
                <td style="width: 15%;">Address:</td>
                <td style="width: 50%; border-bottom: 1px solid #000000;" colspan="3"></td>
                <td style="width: 10%; padding-left: 10px;">Birth Date:</td>
                <td style="width: 25%; border-bottom: 1px solid #000000;" colspan="2"></td>
            </tr>
            <tr>
                <td style="width: 15%;">Mailing Address:</td>
                <td style="width: 50%; border-bottom: 1px solid #000000;" colspan="3"></td>
                <td style="width: 15%; padding-left: 10px;">Place of Birth:</td>
                <td style="width: 20%; border-bottom: 1px solid #000000;" colspan="2"></td>
            </tr>
           <tr style="position: absolute;">
                <td style="width: 20%;" colspan="2">Elementary Course Completed:</td>
                <td style="width: 40%; border-bottom: .5px solid #000000;" colspan="3"></td>
                <td style="width: 15%; padding-left: 10px;">School Year:</td>
                <td style="width: 24%; border-bottom: .5px solid #000000;"></td>
            </tr>
            <tr style="position: absolute;">
                <td style="width: 20%;" colspan="2">Secondary Course Completed:</td>
                <td style="width: 40%; border-bottom: 1px solid #000000;" colspan="3"></td>
                <td style="width: 15%; padding-left: 10px;">School Year:</td>
                <td style="width: 25%; border-bottom: 1px solid #000000;"></td>
            </tr>
            <tr style="position: absolute;">
                <td style="width: 20%;" colspan="2">Collegiate Course Completed:</td>
                <td style="width: 40%; border-bottom: 1px solid #000000;" colspan="3"></td>
                <td style="width: 15%; padding-left: 10px;">School Year:</td>
                <td style="width: 25%; border-bottom: 1px solid #000000;"></td>
            </tr>
            <tr style="position: absolute;">
                <td style="width: 20%;" colspan="2">Degree(s):</td>
                <td style="width: 40%; border-bottom: 1px solid #000000;" colspan="3"></td>
                <td style="width: 15%; padding-left: 10px;">Major:</td>
                <td style="width: 25%; border-bottom: 1px solid #000000;"></td>
            </tr>
    </table>

    <div class="bodysection2" style="height: 600px; width: 100%; border: 1.5px solid #000000;margin-top: 2em;">
    
    <div id="watermark">
        <img src="{{base_path()}}/public/assets/images/sbc/logo2.png" alt="school" style="height:560px; width:700px;"/>
    </div>
    <table class="" border-spacing="5px" style="width: 100%; margin: 0px;font-size: 12px; table-layout: fixed !important; border-bottom: 1px solid #000000; text-align: center;border-collapse: collapse;">
        <tr>
            <th style="width: 10%; border-right: 1px solid #000;">COURSE</th>
            <th style="width: 5%; border-right: 1px solid #000;">NO.</th>
            <th style="width: 65%; border-right: 1px solid #000;">DESCRIPTIVE TITLE</th>
            <th style="width: 10%; border-right: 1px solid #000;">GRADE</th>
            <th style="width: 10%;">CREDIT</th>
        </tr>
    </table>
    <table class="" border-spacing="5px" style="width: 100%; margin: 0px;font-size: 12px; table-layout: fixed !important; text-align: center;border-collapse: collapse;">
        <tr>
            <th style="width: 10%;"></th>
            <th style="width: 5%;"></th>
            <th style="width: 65%;">Southern Baptist College - Mlang, Cotabato</th>
            <th style="width: 10%;"></th>
            <th style="width: 10%;"></th>
        </tr>
    </table>
    </div>
    
    <table class="" border-spacing="5px" style="width: 100%; margin: 0px;font-size: 12px; table-layout: fixed !important; text-align: center;border-collapse: collapse;">
        <tr>
            <td style="width: 10%;">Page 1 of 3</td>
            <td style="width: 10%;"></td>
            <td style="width: 40%;">(Not Valid Without College Seal)</td>
            <td style="width: 20%;"></td>
            <td style="width: 20%;"></td>
        </tr>
        <tr>
            <td style="width: 10%;"></td>
            <td style="width: 10%;"></td>
            <td style="width: 70%;"></td>
            <td style="width: 5%; font-size: 15px;" colspan="2"><b>MERIAM S. FRASCO, MBE</b></td>
        </tr>
        <tr>
            <td style="width: 10%;"></td>
            <td style="width: 10%;"></td>
            <td style="width: 70%;"></td>
            <td style="width: 5%;" colspan="2"><b>Registrar</b></td>
        </tr>
        
    </table>
        
    <div style="width: 100%;">
        <div style="width: 40%; height: 150px;float: left;">
            <span style="transform: rotate(-90); position: absolute;margin-left: 1em; margin-bottom: 3em; font-size: 12px;">Space for Authentication</span>
        </div>
        <div style="width: 50%; height: 150px;float: right;">

        </div>
    </div>
</div> -->

<!-- =============================================================================================================== -->
<!-- 2nd Page ni -->

<!-- <div class="bodysection" style="margin-top: -170px">
<p style="font-size:20px; text-align:center;">OFFICIAL TRANSCRIPT OF RECORDS</p>
<table border-spacing="5px" style="width: 100%; margin: 0px; font-size: 12px; table-layout: fixed !important;">
    <tr>
        <td colspan="5"></td>
        <td style="width: 20%; border-bottom: 1px solid #000000;text-align:center!important;" colspan="2"></td>
    </tr>
    <tr>
        <td colspan="5"></td>
        <td style="width: 25%; text-align:center;font-size: 10px;" colspan="2"><span style="">Date</span></td>
    </tr>
</table>

<div class="bodysection2" style="height: 750px; width: 100%; border: 1.5px solid #000000;margin-top: .5em;">
    <div id="watermark">
        <img src="{{base_path()}}/public/assets/images/sbc/logo2.png" alt="school" style="height:700px; width:700px;"/>
    </div>
    <table class="" border-spacing="5px" style="width: 100%; margin: 0px;font-size: 12px; table-layout: fixed !important; border-bottom: 1px solid #000000; text-align: center;border-collapse: collapse;">
        <tr>
            <th style="width: 10%; border-right: 1px solid #000;">COURSE</th>
            <th style="width: 5%; border-right: 1px solid #000;">NO.</th>
            <th style="width: 65%; border-right: 1px solid #000;">DESCRIPTIVE TITLE</th>
            <th style="width: 10%; border-right: 1px solid #000;">GRADE</th>
            <th style="width: 10%;">CREDIT</th>
        </tr>
    </table>
    <table class="" border-spacing="5px" style="width: 100%; margin: 0px;font-size: 12px; table-layout: fixed !important; text-align: center;border-collapse: collapse;">
        <tr>
            <th style="width: 10%;"></th>
            <th style="width: 5%;"></th>
            <th style="width: 65%;"></th>
            <th style="width: 10%;"></th>
            <th style="width: 10%;"></th>
        </tr>
    </table>
    </div>
    
    <table class="" border-spacing="5px" style="width: 100%; margin: 0px;font-size: 12px; table-layout: fixed !important; text-align: center;border-collapse: collapse;">
        <tr>
            <td style="width: 10%;">Page 1 of 3</td>
            <td style="width: 10%;"></td>
            <td style="width: 40%;">(Not Valid Without College Seal)</td>
            <td style="width: 20%;"></td>
            <td style="width: 20%;"></td>
        </tr>
        <tr>
            <td style="width: 10%;"></td>
            <td style="width: 10%;"></td>
            <td style="width: 70%;"></td>
            <td style="width: 5%; font-size: 15px;" colspan="2"><b>MERIAM S. FRASCO, MBE</b></td>
        </tr>
        <tr>
            <td style="width: 10%;"></td>
            <td style="width: 10%;"></td>
            <td style="width: 70%;"></td>
            <td style="width: 5%;" colspan="2"><b>Registrar</b></td>
        </tr>
        
    </table>
        
    <div style="width: 100%;">
        <div style="width: 40%; height: 150px;float: left;">
            <span style="transform: rotate(-90); position: absolute;margin-left: 1em; margin-bottom: 5em; font-size: 12px;">Space for Authentication</span>
        </div>
        <div style="width: 50%; height: 150px;float: right;">

        </div>
    </div>
</div> -->
<!-- Close sa 2nd Page -->
<!-- =====================================================================================-->

<!-- =====================================================================================--> 
<!-- 3rd Page -->
<div class="bodysection" style="margin-top: -170px">
<p style="font-size:20px; text-align:center;">OFFICIAL TRANSCRIPT OF RECORDS</p>
<table border-spacing="5px" style="width: 100%; margin: 0px; font-size: 12px; table-layout: fixed !important;">
    <tr>
        <td colspan="5"></td>
        <td style="width: 20%; border-bottom: 1px solid #000000;text-align:center!important;" colspan="2"></td>
    </tr>
    <tr>
        <td colspan="5"></td>
        <td style="width: 25%; text-align:center;font-size: 10px;" colspan="2"><span style="">Date</span></td>
    </tr>
</table>

<div class="bodysection2" style="height: 750px; width: 100%; border: 1.5px solid #000000;margin-top: .5em;">
    <div id="watermark">
        <img src="{{base_path()}}/public/assets/images/sbc/logo2.png" alt="school" style="height:700px; width:700px;"/>
    </div>
    <table class="" border-spacing="5px" style="width: 100%; margin: 0px;font-size: 12px; table-layout: fixed !important; border-bottom: 1px solid #000000; text-align: center;border-collapse: collapse;">
        <tr>
            <th style="width: 10%; border-right: 1px solid #000;">COURSE</th>
            <th style="width: 5%; border-right: 1px solid #000;">NO.</th>
            <th style="width: 65%; border-right: 1px solid #000;">DESCRIPTIVE TITLE</th>
            <th style="width: 10%; border-right: 1px solid #000;">GRADE</th>
            <th style="width: 10%;">CREDIT</th>
        </tr>
    </table>
    <table class="" border-spacing="5px" style="width: 100%; margin: 0px;font-size: 12px; table-layout: fixed !important; text-align: center;border-collapse: collapse;">
        <tr>
            <th style="width: 10%;"></th>
            <th style="width: 5%;"></th>
            <th style="width: 65%;"></th>
            <th style="width: 10%;"></th>
            <th style="width: 10%;"></th>
        </tr>
    </table>
    </div>
    
    <table class="" border-spacing="5px" style="width: 100%; margin: 0px;font-size: 12px; table-layout: fixed !important;border-collapse: collapse;">
        <tr>
            <td style="width: 10%;">Page 1 of 3</td>
            <td style="width: 10%;"></td>
            <td style="width: 40%;">(Not Valid Without College Seal)</td>
            <td style="width: 20%;"></td>
            <td style="width: 20%;"></td>
        </tr>
        <tr>
            <td style="width: 10%;">School Seal</td>
            <td style="width: 10%;"></td>
            <td style="width: 70%;"></td>
            <td style="width: 5%; font-size: 15px;" colspan="2"><b>MERIAM S. FRASCO, MBE</b></td>
        </tr>
        <tr>
            <td style="width: 15%;"><b>Remarks:</b></td>
            <td style="width: 60%; border-bottom: 1px solid #000000;" colspan="2"></td>
            <td style="width: 5%; text-align:center;" colspan="2"><b>Registrar</b></td>
        </tr>
        
    </table>
        
    <div style="width: 100%;">
        <div style="width: 40%; height: 150px;float: left;">
            <span style="transform: rotate(-90); position: absolute;margin-left: 1em; margin-bottom: 5em; font-size: 12px;">Space for Authentication</span>
        </div>
        <div style="width: 50%; height: 90px;float: right; border: 1px solid #000; margin-top: 10px;">
            <table class="" border-spacing="5px" style="width: 100%; margin: 0px;padding-left: 3px; font-size: 12px; table-layout: fixed !important;border-collapse: collapse; font-weight: bold;">
                <tr>
                    <td style="">Grading System: </td>
                </tr>
                <tr>
                    <td style="">1.00 = 98-100</td>
                    <td style="">1.75 = 89-91</td>
                    <td style="">2.50 = 80-82</td>
                    <td style="">5.0 = Failure</td>
                </tr>
                <tr>
                    <td style="">1.25 = 95-97</td>
                    <td style="">2.00 = 86-88</td>
                    <td style="">2.75 = 77-79</td>
                    <td style="">NG = No Grade</td>
                </tr>
                <tr>
                    <td style="">1.50 = 92-94</td>
                    <td style="">2.25 = 83-85</td>
                    <td style="">3.00 = 75-76</td>
                    <td style="">DRP = Dropped</td>
                </tr>
                <tr>
                    <td style=""></td>
                    <td style=""></td>
                    <td style=""></td>
                    <td style="">INC = Incomplete</td>
                </tr>
            </table>
        </div>
    </div>
</div>
<!-- ===================================================================================== -->
</body>
</html>