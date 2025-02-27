<style>
      html                        { font-family: Arial, Helvetica, sans-serif;  }
      /* html                        { font-family: Arial, Helvetica, sans-serif; } */
      .left                       { float: left; width : 45%; /* height : 100px; */ /* border: solid 1px black; */ /* display : inline-block; */ }
      .right                      { float: right; width : 45%; /* border: solid 1px black; */ /* background-color: red; */ }
      #logoTable                  { border-collapse: collapse; width:100%; font-size: 11px; }
      
      #studentInfo                { border-spacing: 0; width:100%; font-size: 11px; /* margin: 0px 20px 0px 0px; */ /* font-family: Arial, Helvetica, sans-serif; */ }
      #studentInfo td             { /* border:1px solid black; */ padding:3px; border-spacing: 0; /* margin: 0px 20px 0px 0px; */ /* font-family: Arial, Helvetica, sans-serif; */ }
      #report                     { /* border: 1px solid #ddd; */ border-collapse: collapse; width:100%; }
      .report2                     { /* border: 1px solid #ddd; */ border-collapse: collapse; width:100%; }
      #values                     { /* border: 1px solid #ddd; */ /* border-collapse: collapse; */ border-spacing: 0; width:100%; font-family: Arial, Helvetica, sans-serif; /* text-align: justify; */ }
      #behavior                   { width:45%; }
      #remarks                    { /* border: 1px solid #ddd; */ font-size: 11px; border-collapse: collapse; width:100%; font-family: Arial, Helvetica, sans-serif; }
      #remarksCells               { border-bottom: 1px solid black; }
      #report td, #report th      { border: 1px solid #111; border-collapse: collapse; padding: 4px; font-size: 10px; }
      .report2 td, .report2 th      { border: 1px solid #111; border-collapse: collapse; padding: 4px; font-size: 10px; }
      
      .cellBottom                 { border-bottom: 1px solid #111 !important; }
      .cellRight                  { border-right: 1px solid #111 !important;  }
      #values td, #values th      { border: 1px solid #111; border-bottom: hidden; border-right: hidden; border-collapse: collapse; padding: 4px; font-size: 11px; }
      #reportHeader               { margin:0px 5px 20px 5px; font-family: Arial, Helvetica, sans-serif; }
      #thquarter                  { width:20%; padding:10px; }
      #thsignature                { width:30%; padding:10px; }
      p#signature                 { font-family: Arial, Helvetica, sans-serif; }
      div#letter                  { font-size: 11px; }
      #firstParagraph             { text-indent: 10%; }
      #transfer                   { /* border: 1px solid #ddd; */ border-collapse: collapse; width:100%; margin: 0px 20px 0px 0px; /* font-family: Arial, Helvetica, sans-serif; */ }
      .page_break                 { page-break-before: always; }
  
      #logoTable2                  { border-collapse: collapse; width:100%; font-size: 11px; table-layout: fixed;/* border: 1px solid #ddd; */}
      #logoTable2 td                 {/* border: 1px solid #ddd;*/}
  
  </style>
  <table id="logoTable2">
      <tr>
          <td style="width: 25%;">
              <div >
                  <br>
                  
                  {{-- <img src="{{base_path()}}/public/assets/images/united_church_of_christ.png" alt="school" style="margin: 2px;" width="70px"> --}}
              </div>
          </td>
          <td style="width: 50%; line-height: 15px;">
              <center>
                  REPUBLIC OF THE PHILIPPINES
                  <br>
                  DEPARTMENT OF EDUCATION - 
                  
                  <br>
                  
                  <br>
                 
                  <br>
                  
                  <br>
                  SCHOOL ID - &nbsp;
              </center>
          </td>
          <td style="width: 25%;"><img src="{{base_path()}}/public/assets/images/deped_logo.png" alt="school" width="150px"></td>
      </tr>
  </table>
  <div style="width: 100%; background-color:#87b900; height: 15px;">
      <center>
          <small style="font-size: 11px;">S.Y. &nbsp;</small>
      </center>
  </div>
  <div style="display: block; position: absolute; ">
  <div style="float: left; width: 40%;">
      <table style="width: 100%; table-layout: fixed; font-size: 11px;">
          <tr>
              <td style="width: 20%;">NAME:</td>
              <td style="border-bottom: 1px solid black;text-transform: uppercase;">&nbsp;</td>
          </tr>
      </table>
      <table style="width: 100%; table-layout: fixed; font-size: 11px;">
          <tr>
              <td style="width: 20%;">AGE: </td>
              <td style="border-bottom: 1px solid black;">&nbsp;</td>
              <td style="text-align: right;">SEX: </td>
              <td style="border-bottom: 1px solid black;">&nbsp;</td>
          </tr>
      </table>
      <table style="width: 100%; table-layout: fixed; font-size: 11px;">
          <tr>
              <td style="width: 20%;">TRACK:</td>
              <td style="border-bottom: 1px solid black;text-transform:uppercase;">&nbsp;</td>
          </tr>
      </table>
  </div>
  <div style="float: right; width: 50%;">
      <table style="width: 100%; table-layout: fixed; font-size: 11px;">
          <tr>
              <td style="width: 55%; text-transform:none;">Learner's Reference Number (LRN):</td>
              <td style="border-bottom: 1px solid black;">&nbsp;</td>
          </tr>
          <tr>
              <td>GRADE/SECTION:</td>
              <td style="border-bottom: 1px solid black;">&nbsp;</td>
          </tr>
          <tr>
              <td>STRAND:</td>
              <td style="border-bottom: 1px solid black;">&nbsp;</td>
          </tr>
      </table>
  </div>
  </div>
  <br>
  <br>
  <br>
  &nbsp;
  <table class="report2"  >
      <thead>
          <tr>
              <td colspan="5" style="background-color:#87b900;font-size: 12px;">
                  <center><strong>REPORT ON LEARNING PROGRESS AND ACHIEVEMENT</strong></center>
              </td>
          </tr>
          <tr>
              <th colspan="2" style="width:70%"><center>FIRST SEMESTER</center></th>
              <th rowspan="2"  style="width: 10%;"><center>1</center></th>
              <th rowspan="2"  style="width: 10%;"><center>2</center></th>
              <th rowspan="2" style="width: 10%;" ><center>FINAL</center></th>
          </tr>
          <tr>
              <th colspan="2"><center>Subjects</center></th>
          </tr>
      </thead>
  </table>
  <table class="report2"  >
      <tbody>
          <tr>
              <td colspan="5" style="background-color:#b8ff33;font-size: 11px;">
                  CORE SUBJECTS
              </td>
          </tr>
          <tr>
              <td colspan="5" style="background-color:#ffe699;font-size: 11px;">
                  APPLIED SUBJECTS
              </td>
          </tr>
          <tr>
              <td colspan="5" style="background-color:#b3f0ff;font-size: 11px;">
                  SPECIALIZED SUBJECT/S
              </td>
          </tr>
          <tr>
              <td colspan="2"><center>General Average for the Semester</center></td>
              <td colspan="3"></td>
          </tr>
      </tbody>
  </table>
  <br>
  <table class="report2"  >
      <thead>
          <tr>
              <th colspan="2" style="width:70%"><center>SECOND SEMESTER</center></th>
              <th rowspan="2"  style="width: 10%;"><center>3</center></th>
              <th rowspan="2"  style="width: 10%;"><center>4</center></th>
              <th rowspan="2" style="width: 10%;" ><center>FINAL</center></th>
          </tr>
          <tr>
              <th colspan="2"><center>Subjects</center></th>
          </tr>
      </thead>
  </table>
  <table class="report2"  >
      <tbody>
          <tr>
              <td colspan="5" style="background-color:#b8ff33;font-size: 11px;">
                  CORE SUBJECTS
              </td>
          </tr>
          <tr>
              <td colspan="5" style="background-color:#ffe699;font-size: 11px;">
                  APPLIED SUBJECTS
              </td>
          </tr>
          <tr>
              <td colspan="5" style="background-color:#b3f0ff;font-size: 11px;">
                  SPECIALIZED SUBJECT/S
              </td>
          </tr>
          <tr>
              <td colspan="2"><center>General Average for the Semester</center></td>
              <td colspan="3"></td>
          </tr>
      </tbody>
  </table>
      <table class="report2" style="width: 100%; font-size:12px; text-transform:none;margin-top:5px;table-layout: fixed">
          <tr>
              <th>Descriptors</th>
              <th>Grading Scale</th>
              <th>Remarks</th>
          </tr>
          <tr>
              <td>Outstanding</td>
              <td>90-100</td>
              <td>PASSED</td>
          </tr>
          <tr>
              <td>Very Satisfactory</td>
              <td>85-89</td>
              <td>PASSED</td>
          </tr>
          <tr>
              <td>Satisfactory</td>
              <td>80-84</td>
              <td>PASSED</td>
          </tr>
          <tr>
              <td>Fairly Satisfactory</td>
              <td>75-79</td>
              <td>PASSED</td>
          </tr>
          <tr>
              <td>Did Not Meet Expectations</td>
              <td>Below 75</td>
              <td>FAILED</td>
          </tr>
      </table>
      &nbsp;
      {{-- <p><strong><center</center></strong></p> --}}
      <table id="values" style="border-bottom: hidden !important;">
          <thead>
              <tr>
                  <th colspan="6" class="cellRight"><center>REPORT ON LEARNER'S OBSERVED VALUES</center></th>
              </tr>
              <tr>
                  <th rowspan="2" style="width:15%"><center>Core Values</center></th>
                  <th rowspan="2"><center>Behavior Statements</center></th>
                  <th colspan="4" class="cellRight"><center>Quarter</center></th>
              </tr>
              <tr>
                  <th><center>1</center></th>
                  <th><center>2</center></th>
                  <th><center>3</center></th>
                  <th class="cellRight"><center>4</center></th>
              </tr>
          </thead>
          <tbody>
              <tr>
                  <th rowspan="2">1. Maka-Diyos</th>
                  <td id="behavior">Expresses one's spiritual beliefs while respecting the beliefs of others</td>
                  <td>&nbsp;
                  </td>
                  <td>&nbsp;
                  </td>
                  <td>&nbsp;
                  </td>
                  <td class="cellRight">&nbsp;
                  </td>
              </tr>
              <tr>
                  <td id="behavior">Shows adherence to ethical principles by upholding the truth in all undertakings</td>
                  <td style="width: 5%;">&nbsp;
                  </td>
                  <td style="width: 5%;">&nbsp;
                  </td>
                  <td style="width: 5%;">&nbsp;
                  </td style="width: 5%;">
                  <td class="cellRight" style="width: 5%;">&nbsp;
                  </td>
              </tr>
              <tr>
                  <th>2. Makatao</th>
                  <td id="behavior">Is sensitive to individual, social and cultural differences; resists stereotyping people</td>
                  <td>&nbsp;
                  </td>
                  <td>&nbsp;
                  </td>
                  <td>&nbsp;
                  </td>
                  <td class="cellRight">&nbsp;
                  </td>
              </tr>
              <tr>
                  <th rowspan="2">3. Makakalikasan</th>
                  <td id="behavior">Demonstrates contributions towards solidarity</td>
                  <td>&nbsp;
                  </td>
                  <td>&nbsp;
                  </td>
                  <td>&nbsp;
                  </td>
                  <td class="cellRight">&nbsp;
                  </td>
              </tr>
              <tr>
                  <td>Cares for the environment and utilizes resources wisely, judiciously and economically</td>
                  <td>&nbsp;
                  </td>
                  <td>&nbsp;
                  </td>
                  <td>&nbsp;
                  </td>
                  <td class="cellRight">&nbsp;
                  </td>
              </tr>
              <tr>
                  <th class="cellBottom" rowspan="2">4. Makabansa</th>
                  <td id="behavior">Demonstrates pride in being a Filipino, exercises the rights and responsibilities of a Filipino Citizen</td>
                  <td>&nbsp;
                  </td>
                  <td>&nbsp;
                  </td>
                  <td>&nbsp;
                  </td>
                  <td class="cellRight">&nbsp;
                  </td>
              </tr>
              <tr>
                  <td id="behavior" class="cellBottom">Demonstrates appropriate behavior in carrying out activities in the school, community and country</td>
                  <td class="cellBottom">&nbsp;
                  </td>
                  <td class="cellBottom">&nbsp;
                  </td>
                  <td class="cellBottom">&nbsp;
                  </td>
                  <td class="cellBottom cellRight">&nbsp;
                  </td>
              </tr>
          </tbody>
      </table> 
      <table id="values" style="border-top: hidden;">
          <tr>
              <td colspan="4" style="background-color:#a3d762 " style="border-top: hidden;" class="cellRight"><center>Observed Values</center></td>
          </tr>
          <tr>
              <td class="cellBottom"><center>AO - Always Observed</center></td>
              <td class="cellBottom"><center>SO - Sometimes Observed</center></td>
              <td class="cellBottom"><center>RO - Rarely Observed</center></td>
              <td class="cellBottom cellRight"><center>NO - Not Observed</center></td>
          </tr>
      </table>
      <br>
      <center><span style="font-size: 11px;"><strong>Certificate of Transfer</strong></span></center>
      <br>
      <table style="width: 100%; table-layout: fixed; font-size: 11px;">
          <tr>
              <td style="width: 15%;">Admitted to Grade:</td>
              <td style="border-bottom: 1px solid black;"></td>
              <td style="width: 25%;">Eligibility for Admission to Grade:</td>
              <td style="border-bottom: 1px solid black;"></td>
          </tr>
      </table>
      <br>
      <table style="width: 100%; table-layout: fixed; font-size: 11px;">
          <tr>
              <td style="padding-left: 10%; padding-right: 10%;text-align:center; width:">
                  <div style="width: 100%; border-bottom: 1px solid;text-transform: uppercase;">
                      <strong>&nbsp;</strong>
                  </div>
                  <br>
                  <sup>Adviser</sup>
              </td>
              <td style="padding-left: 10%; padding-right: 10%;text-align:center;">
                  <div style="width: 100%; border-bottom: 1px solid;text-transform: uppercase;">
                      <strong>&nbsp;</strong>
                  </div>
                  <br>
                  <sup>Senior High School Principal</sup>
              </td>
          </tr>