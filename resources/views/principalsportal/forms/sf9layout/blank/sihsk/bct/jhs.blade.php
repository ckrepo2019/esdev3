<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
            * {
            
            font-family: Arial, Helvetica, sans-serif;
            }
            table {
                  border-collapse: collapse;
            }

            .text-center {
                  text-align: center !important;
            }

            .table-bordered {
                  border: 1px solid black;
            }

            .table-bordered th,
            .table-bordered td {
                  border: 1px solid black;
            }
            #gradestable td{
                border: 1px solid black;
            }
            #gradestable th{
                border: 2px solid black;
            }
            #values{
                border: 2px solid black;
            }
            #values th{
                border: 2px solid black;
            }
            #values td{
                border: 1px solid black;
            }
    </style>
</head>
<body>
    <table style="width: 100%;font-size: 13px;font-weight: bold;">
        <tr>
            <td style="width: 25%;">
                <div >
                    <br>
                    
                    <img src="{{base_path()}}/public/assets/images/united_church_of_christ.png" alt="school" style="margin: 2px;" width="70px">
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
      <div style="width: 100%; border: 2px solid black;text-align:center; background-color:#8fa178; font-size: 14px;">
            SCHOOL YEAR: SAMPLE
      </div>
      <table style="width: 100%; font-size: 12px;">
         
            <tr>
                  <td style="position: relative; width: 45%;">
                     <div style="width:15%;float:left">NAME:</div>
                     <div style="width:85%;float:right">
                        <div style="border-bottom: 1px solid black;">
                           &nbsp;
                        </div>
                     </div>
                  </td>
                  <td style="position: relative; width: 55%;">
                     Learner's Reference Number (LRN):
                     <div style="width:30%;float:right">
                        <div style="border-bottom: 1px solid black;">
                           &nbsp;
                        </div>
                     </div>
                  </td>
            </tr>
      </table>
      <table style="width: 100%; font-size: 12px;">
            <tr>
               <td style="position: relative; width: 45%;">
                  <div style="width:15%;float:left">AGE:</div>
                  <div style="width:85%;float:right">
                     <div style="width:50%; border-bottom: 1px solid black;">
                        &nbsp;
                     </div>
                  </div>
               </td>
               <td style="position: relative; width: 55%;">
                  SEX:
                  <div style="width:80%;float:right">
                     <div style="border-bottom: 1px solid black;">
                        &nbsp;
                     </div>
                  </div>
               </td>
            </tr>
      </table>
      <table style="width: 100%; font-size: 12px;">
            <tr>
               <td style="position: relative; width: 45%;">
                  <div style="width:15%;float:left">GRADE:</div>
                  <div style="width:85%;float:right">
                     <div style="width:50%; border-bottom: 1px solid black;">
                        &nbsp;
                     </div>
                  </div>
               </td>
               <td style="position: relative; width: 55%;">
                  SECTION:
                  <div style="width:70%;float:right">
                     <div style="border-bottom: 1px solid black;">
                        &nbsp;
                     </div>
                  </div>
               </td>
            </tr>
      </table>
      <br/>
      <table width="100%" style="border: 2px solid black;" id="gradestable">
            <thead style="font-size: 14px;">
                <tr>
                      <th class="text-center" style="background-color:#8fa178;" colspan="7">REPORT ON LEARNING PROGRESS AND ACHIEVEMENT</th>
                </tr>
                <tr>
                    <th rowspan="2">LEARNING AREAS</th>
                    <th colspan="4">QUARTER</th>
                    <th rowspan="2">FINAL GRADE</th>
                    <th rowspan="2">REMARKS</th>
                </tr>
                <tr>
                    <th>1</th>
                    <th>2</th>
                    <th>3</th>
                    <th>4</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th colspan="5">
                        GENERAL AVERAGE
                    </th>
                    <th></th>
                    <th></th>
                </tr>
            </tfoot>
      </table>
      <table width="100%" style="border: 2px solid black; font-size: 13px;" id="descriptorstable">
        <tr>
            <td style="border-right: 2px solid black;width: 60%">
                <table width="100%;" style="text-align: left !important;">
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
            </td>
            <td>
                <table width="100%" style="text-align: left !important;">
                    <tr>
                        <th>Grading System Used:</th>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>Standards and Competency-Based</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table style="font-size: 12px; width: 100%;" id="values">
        <thead>
            <tr>
                <th colspan="6"><center>LEARNER'S OBSERVED VALUES</center></th>
            </tr>
            <tr>
                <th rowspan="2" style="width:15%"><center>Core Values</center></th>
                <th rowspan="2"><center>Behavior Statements</center></th>
                <th colspan="4"><center>Quarter</center></th>
            </tr>
            <tr>
                <th><center>1</center></th>
                <th><center>2</center></th>
                <th><center>3</center></th>
                <th><center>4</center></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td rowspan="3" style="text-align:center;">FAITH</td>
                <td>Manifest belief of God's presence in all aspects of creation.</td>
                <td>&nbsp;
                </td>
                <td>&nbsp;
                </td>
                <td>&nbsp;
                </td>
                <td>&nbsp;
                </td>
            </tr>
            <tr>
                <td>Shows assurance of God's company in all circumstances.</td>
                <td style="width: 5%;">&nbsp;
                </td>
                <td style="width: 5%;">&nbsp;
                </td>
                <td style="width: 5%;">&nbsp;
                </td style="width: 5%;">
                <td style="width: 5%;">&nbsp;
                </td>
            </tr>
            <tr>
                <td>Dedicate undertakings to God's glory.</td>
                <td style="width: 5%;">&nbsp;
                </td>
                <td style="width: 5%;">&nbsp;
                </td>
                <td style="width: 5%;">&nbsp;
                </td style="width: 5%;">
                <td style="width: 5%;">&nbsp;
                </td>
            </tr>
            <tr>
                <td rowspan="2" style="text-align: center;">SERVICE</td>
                <td>Actively engage oneself in helping activities.</td>
                <td>&nbsp;
                </td>
                <td>&nbsp;
                </td>
                <td>&nbsp;
                </td>
                <td>&nbsp;
                </td>
            </tr>
            <tr>
                <td>Performs task/s for the benefit of others.</td>
                <td>&nbsp;
                </td>
                <td>&nbsp;
                </td>
                <td>&nbsp;
                </td>
                <td>&nbsp;
                </td>
            </tr>
            <tr>
                <td rowspan="2" style="text-align: center;">RESPONSIBLE<br/>STEWARDSHIP</th>
                <td>Shows eagerness to protect the wellness of God's creation.</td>
                <td>&nbsp;
                </td>
                <td>&nbsp;
                </td>
                <td>&nbsp;
                </td>
                <td>&nbsp;
                </td>
            </tr>
            <tr>
                <td>Takes proper care of what is entrusted.</td>
                <td>&nbsp;
                </td>
                <td>&nbsp;
                </td>
                <td>&nbsp;
                </td>
                <td>&nbsp;
                </td>
            </tr>
            <tr>
                <td rowspan="4" style="text-align:center;">SERVANT LEADERSHIP</td>
                <td>Manifest humility in leading other students/peers.</td>
                <td>&nbsp;
                </td>
                <td>&nbsp;
                </td>
                <td>&nbsp;
                </td>
                <td>&nbsp;
                </td>
            </tr>
            <tr>
                <td>Shows willingness to lead helpng anyone in need.</td>
                <td style="width: 5%;">&nbsp;
                </td>
                <td style="width: 5%;">&nbsp;
                </td>
                <td style="width: 5%;">&nbsp;
                </td style="width: 5%;">
                <td style="width: 5%;">&nbsp;
                </td>
            </tr>
            <tr>
                <td>Shows preference of other's welfare than self.</td>
                <td style="width: 5%;">&nbsp;
                </td>
                <td style="width: 5%;">&nbsp;
                </td>
                <td style="width: 5%;">&nbsp;
                </td style="width: 5%;">
                <td style="width: 5%;">&nbsp;
                </td>
            </tr>
            <tr>
                <td>Exhibits integrity in all service undertakings.</td>
                <td style="width: 5%;">&nbsp;
                </td>
                <td style="width: 5%;">&nbsp;
                </td>
                <td style="width: 5%;">&nbsp;
                </td style="width: 5%;">
                <td style="width: 5%;">&nbsp;
                </td>
            </tr>
        </tbody>
    </table> 
    <table style="font-size: 12px; width: 100%;" id="values">
        <thead>
            <tr>
                <th colspan="4"><center>Observed Values</center></th>
            </tr>
            </tr>
        </thead>
        <tbody style="text-align: center;">
            <tr>
                <td>AO - Always Observed</td>
                <td>SO - Sometimes Observed</td>
                <td>RO - Rarely Observed</td>
                <td>NO - Not Observed</td>
            </tr>
        </tbody>
    </table>
    <table style="font-size: 12px; width: 100%;" id="values">
        <thead>
            <tr>
                <th colspan="13"><center>REPORT ON ATTENDANCE</center></th>
            </tr>
            <tr>
                <th></th>
                <th>JUN</th>
                <th>JUL</th>
                <th>AUG</th>
                <th>SEP</th>
                <th>OCT</th>
                <th>NOV</th>
                <th>DEC</th>
                <th>JAN</th>
                <th>FEB</th>
                <th>MAR</th>
                <th>APR</th>
                <th>TOTAL</th>
            </tr>
        </thead>
        <tbody style="text-align: center;">
            <tr>
                <td>NO. OF SCHOOL DAYS</td>
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
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>NO. OF DAYS PRESENT</td>
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
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>NO. OF DAYS ABSENT</td>
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
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>
    <br/>
    <div style="width: 40%; float:left; font-size: 12px;">
        <strong>Eligible for Transfer and admission to:</strong>
    </div>
    <div style="width: 60%; float:right; font-size: 12px;border-bottom: 1px solid black;">
            &nbsp;
    </div>
    <br/>
    <br/>
    <br/>
    
    <div style="width: 45%; float:left; font-size: 12px;">
        <div style="width: 90%; border-bottom: 1px solid black">
            &nbsp;
        </div>
        <div style="width: 90%; text-align: center">
            Adviser
        </div>
    </div>
    <div style="width: 45%; float:right; font-size: 12px;">
        <div style="width: 90%; float:right; border-bottom: 1px solid black">
            &nbsp;
        </div>
        <div style="width: 90%; float:right; text-align: center">
            Academic Affairs Dep't Head
        </div>
    </div>
</body>
</html>