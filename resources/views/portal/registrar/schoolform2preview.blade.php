
<style>
    table,th,td{
        font-size: 12px;
        border:1px solid black !important;
        text-align: center;
        padding: 4px !important;
    }
    th{
        vertical-align: middle !important;
        text-align: center;
    }
    .rotate {
      text-align: center;
      white-space: nowrap;
      vertical-align: middle;
      width: 1.5em;
      padding: 5px !important;
      font-size: 11px;
    }
    .rotate div {
         -moz-transform: rotate(-90.0deg);  /* FF3.5+ */
           -o-transform: rotate(-90.0deg);  /* Opera 10.5 */
      -webkit-transform: rotate(-90.0deg);  /* Saf3.1+, Chrome */
                 filter:  progid:DXImageTransform.Microsoft.BasicImage(rotation=0.083);  /* IE6,IE7 */
             -ms-filter: "progid:DXImageTransform.Microsoft.BasicImage(rotation=0.083)"; /* IE8 */
             margin-left: -10em;
             margin-right: -10em;
    }
    .table td{
            padding: 0px !important;
        }
    .late {
      background: rgba(240, 105, 93, 1);
      background: linear-gradient(to top left, grey 50%, white 51%);
      color: #fff;
      height: 20px;
    }
    .cc {
      background: rgba(240, 105, 93, 1);
      background: linear-gradient(to top left, white 50%, grey 51%);
      color: #fff;
      height: 20px;
    }
    .padtd td{
        padding: 5px !important;
    }
    @media only screen and (max-width: 600px) {
        .container {
            max-width: 100%;
            overflow-x: scroll;
        }
    }
</style>
@extends('registrar.layouts.app')
@section('content')
<style>
.table tr{
    border-bottom: 1px solid #ddd;
}
.card-header{
    border-bottom: hidden;
}
</style>
@php
    
@endphp
<div class="col-12">
    <div class="card ">
        <div class="card-header">
                <h3 class="card-title">
                    <strong>{{$gradeAndSection[0]->levelname}} - {{$gradeAndSection[0]->sectionname}}</strong>
                </h3>
                <a href="/preview_school_form_2/print/{{$gradeAndSection[0]->gradelevelid}}/{{$gradeAndSection[0]->sectionid}}" class="btn btn-primary btn-sm float-right" style="color: white;"><i class="fa fa-print"></i> Print</a>
        </div>
        <div class="card-header">
            <span style="display: inline-block;position: relative;">Report for the Month of:&nbsp;</span>
            <select class="col-md-3 form-control form-control-sm" style="display:inline;position:relative;">
                <option></option>
            </select>
        </div>
    </div>
</div>
<div class="card-body">
        {{-- style="table-layout: fixed;" --}}
        <div class="row">
            <div class="col-md-12 container">
                <table class="table table-bordered" >
                    <thead>
                        <tr>
                            <th colspan="2" rowspan="3" width="20%">LEARNER'S NAME<br>(Last Name, First Name, Middle Name)</th>
                            <th colspan="25">(1st row fo date, 2nd row for Day: M, T, W, TH, F)</th>
                            <th colspan="2" rowspan="2" width="10%">Total for the Month</th>
                            <th rowspan="3" width="20%">REMARKS/S (If DROPPED OUT, state reason, please refer to legend number 2. If TRANSFERRED IN/OUT, write the name of School.) </th>
                        </tr>
                        <tr>
                            <th>1</th>
                            <th>2</th>
                            <th>3</th>
                            <th>4</th>
                            <th>5</th>
                            <th>6</th>
                            <th>7</th>
                            <th>8</th>
                            <th>9</th>
                            <th>10</th>
                            <th>11</th>
                            <th>12</th>
                            <th>13</th>
                            <th>14</th>
                            <th>15</th>
                            <th>16</th>
                            <th>17</th>
                            <th>18</th>
                            <th>19</th>
                            <th>20</th>
                            <th>21</th>
                            <th>22</th>
                            <th>23</th>
                            <th>24</th>
                            <th>25</th>
                        </tr>
                        <tr>
                            <th class='rotate'><div>MON</div></th>
                            <th class='rotate'><div>TUE</div></th>
                            <th class='rotate'><div>WED</div></th>
                            <th class='rotate'><div>THU</div></th>
                            <th class='rotate'><div>FRI</div></th>
                            <th class='rotate'><div>MON</div></th>
                            <th class='rotate'><div>TUE</div></th>
                            <th class='rotate'><div>WED</div></th>
                            <th class='rotate'><div>THU</div></th>
                            <th class='rotate'><div>FRI</div></th>
                            <th class='rotate'><div>MON</div></th>
                            <th class='rotate'><div>TUE</div></th>
                            <th class='rotate'><div>WED</div></th>
                            <th class='rotate'><div>THU</div></th>
                            <th class='rotate'><div>FRI</div></th>
                            <th class='rotate'><div>MON</div></th>
                            <th class='rotate'><div>TUE</div></th>
                            <th class='rotate'><div>WED</div></th>
                            <th class='rotate'><div>THU</div></th>
                            <th class='rotate'><div>FRI</div></th>
                            <th class='rotate'><div>MON</div></th>
                            <th class='rotate'><div>TUE</div></th>
                            <th class='rotate'><div>WED</div></th>
                            <th class='rotate'><div>THU</div></th>
                            <th class='rotate'><div>FRI</div></th>
                            <th>ABSENT</th>
                            <th>TARDY</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td >1.</td>
                            <td width="20%"></td>
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
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td >2.</td>
                            <td width="20%"></td>
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
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <th colspan="2">
                                <i class="fa fa-arrow-left pr-3"></i> MALE | TOTAL Per Day <i class="fa fa-arrow-right pl-3"></i>
                            </th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                        <tr>
                            <td >1.</td>
                            <td width="20%"></td>
                            <td><div class="late">
                                </div></td>
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
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td >2.</td>
                            <td width="20%"></td>
                            <td><div class="cc"></div></td>
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
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <th colspan="2">
                                <i class="fa fa-arrow-left pr-3"></i> FEMALE | TOTAL Per Day <i class="fa fa-arrow-right pl-3"></i>
                            </th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                        <tr>
                            <th colspan="2">
                                <i class="fa fa-arrow-left pr-3"></i> COMBINED TOTAL PER DAY <i class="fa fa-arrow-right pl-3"></i>
                            </th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-md-5">
                <small><strong>GUIDELINES:</strong></small>
                <small>
                    <ol style="font-size: 11px; padding-left: 10px;">
                        <li> The attendance shall be accomplished daily. Refer to the codes for checking learners' attendance.</li>
                        <li> Dates shall be witten in the preceding columns beside Leaner's Name.</li>
                        <li>
                            To compute the following:
                            <br>
                            <div class="row" style="">
                                <div class="col-md-6"  style="">
                                    a. Percentage of Enrolment = 
                                </div>
                                <div class="col-md-5">
                                    <div class="row text-center" style="border-bottom: 1px solid black;">Registered Learner as of End of the Month</div> 
                                    <div class="row" style="">Enrolment as of 1st Fiday of June</div> 
                                </div>
                                <div class="col-md-1" style=" vertical-align:middle !important;text-align:center; padding: 5px;">
                                     <span>x100</span>
                                </div>
                            </div>
                            <div class="row" style="">
                                <div class="col-md-6"  style="">
                                    b. Average Daily Attendance = 
                                </div>
                                <div class="col-md-5" style="">
                                    <div class="row" style="border-bottom: 1px solid black;">Total Daily Attendance</div> 
                                    <div class="row" style="">Number of School Days in reporting month</div> 
                                </div>
                                <div class="col-md-1" style=" vertical-align:middle !important;text-align:center;">
                                    &nbsp;
                                </div>
                            </div>
                            <div class="row" style="">
                                <div class="col-md-6"  style="">
                                    c. Pecentage of Attendance for the month = 
                                </div>
                                <div class="col-md-5" style="">
                                    <div class="row" style="border-bottom: 1px solid black;">Average daily attendance</div> 
                                    <div class="row" style="">Registered Learner as of End of the month</div> 
                                </div>
                                <div class="col-md-1" style=" vertical-align:middle !important;text-align:center; padding: 5px;">
                                        <span>x100</span>
                                </div>
                            </div>
                            <br>
                        </li>
                        <li> Every End of the month, the class adviser will submit this form to the office of the pincipal fo recording of summary table into the School Form 4. Once signed by the principal, this form should be returned to the adviser.</li>
                        <li> The adviser will extend neccessary intervention including but not limited to home visitation to learner/s that committed 5 consecutive days of absences or those with potentials of dropping out.</li>
                        <li> Attendance peformance of leaner is expected to reflect in Form 137 and Form 138 every grading period<br> * Beginning of School Year cut-off report is every 1st Fiday of School Calenda Days</li>
                    </ol>
                </small>
            </div>
            <div class="col-md-3" style="border: 1px solid black;padding:0px;">
                <div class="col-md-12" style="border-bottom: 1px solid black;">
                    <small>
                        <strong>1. CODES FOR CHECKING ATTENDANCE</strong>
                    </small>
                </div>
                <div class="col-md-12" style="font-size: 11px; padding-top:5px; padding-bottom:5px;">
                        <strong>blank</strong> - Pesent; (x)- Absent; Tardy (half shaded = Upper for Late Commer, Lowe for Cutting Classes)
                </div>
                <div class="col-md-12" >
                    <span>
                        <small>
                            <strong>2. REASONS/CAUSES OF DROP-OUTS</strong>
                        </small>
                    </span>
                    <br>
                    <div style="padding-bottom:3px;">
                        <span style="font-size: 11px;">
                            <strong>a. Domestic-Related Factors</strong>
                        </span>
                        <br>
                        <span style="font-size: 11px;">
                            a.1. Had to take care of siblings
                        </span>
                        <br>
                        <span style="font-size: 11px;">
                            a.2. Early marriage/pregnancy
                        </span>
                        <br>
                        <span style="font-size: 11px;">
                            a.3. Parents' attitude towad schooling
                        </span>
                        <br>
                        <span style="font-size: 11px;">
                            a.4. Family problems
                        </span>
                    </div>
                    <div style="padding-bottom:3px;">
                        <span style="font-size: 11px;">
                            <strong>b. Individual-Related Factors</strong>
                        </span>
                        <br>
                        <span style="font-size: 11px;">
                            b.1. Illness
                        </span>
                        <br>
                        <span style="font-size: 11px;">
                            b.2. Overage
                        </span>
                        <br>
                        <span style="font-size: 11px;">
                            b.3. Death
                        </span>
                        <br>
                        <span style="font-size: 11px;">
                            b.4. Drug Abuse
                        </span>
                        <br>
                        <span style="font-size: 11px;">
                            b.5. Poor academic performance
                        </span>
                        <br>
                        <span style="font-size: 11px;">
                            b.6. Lack of interest/Dsitractions
                        </span>
                        <br>
                        <span style="font-size: 11px;">
                            b.7. Hunger/Malnutrition
                        </span>
                    </div>
                    <div style="padding-bottom:3px;">
                        <span style="font-size: 11px;">
                            <strong>c. School-Related Factors</strong>
                        </span>
                        <br>
                        <span style="font-size: 11px;">
                            c.1. Teacher Factor
                        </span>
                        <br>
                        <span style="font-size: 11px;">
                            c.2. Physical condition of classroom
                        </span>
                        <br>
                        <span style="font-size: 11px;">
                            c.3. Peer influence
                        </span>
                    </div>
                    <div style="padding-bottom:3px;">
                        <span style="font-size: 11px;">
                            <strong>d. Geographic/Environmental</strong>
                        </span>
                        <br>
                        <span style="font-size: 11px;">
                            d.1. Distance between home and school
                        </span>
                        <br>
                        <span style="font-size: 11px;">
                            d.2. Armed conflict (incl. Tribal wars & clanfeuds)
                        </span>
                        <br>
                        <span style="font-size: 11px;">
                            d.3. Calamities/Disasters
                        </span>
                    </div>
                    <div style="padding-bottom:3px;">
                        <span style="font-size: 11px;">
                            <strong>e. Financial-Related</strong>
                        </span>
                        <br>
                        <span style="font-size: 11px;">
                            e.1. Child labor, work
                        </span>
                    </div>
                    <div style="padding-bottom:3px;">
                        <span style="font-size: 11px;">
                            <strong>f. Others</strong>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <table class="table table-bordered padtd">
                    <tr>
                        <th rowspan="2" width="30%">Month: JULY</th>
                        <th rowspan="2" width="40%">No. of Days of Classes: 1</th>
                        <th colspan="3" width="30%">Summary for the Month</th>
                    </tr>
                    <tr>
                        <th>M</th>
                        <th>F</th>
                        <th>TOTAL</th>
                    </tr>
                    <tr>
                        <td colspan="2"><em>* Enrolment as of (1st Friday of June)</em></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="2"><em>Late Enrollment <strong>during the month</strong><br>(beyond cut-off)</em></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="2"><em>Registered Learner as of <strong>end of the month</strong></em></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="2"><em>Percentage of Enollment as of <strong>end of the month</strong></em></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="2"><em>Average Daily Attendance</em></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="2"><em>Percentage of Attendance for the month</em></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="2"><em>Number of students with 5 consecutive days of absences:</em></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="2"><em><strong>Drop out</strong></em></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="2"><em><strong>Tansferred out</strong></em></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="2"><em><strong>Tansferred in</strong></em></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </table>
                <div style="font-size: 11px;">
                    <span>
                        <em>I certify that this is a true and correct report.</em>
                    </span>
                </div>
                <div style="font-size: 11px; text-align:center;">
                    <center>
                        
                        <span>
                            &nbsp;
                        </span>
                        
                        <hr class="col-md-8 p-0 m-0" style="border-color: black;"/>
                        <em class="p-0">(Signature of Teacher over Printed Name)</em>
                    </center>
                </div>
                <div style="font-size: 11px;">
                    <span>
                        <em>Attested by:</em>
                    </span>
                </div>
                <div style="font-size: 11px; text-align:center;">
                    <center>
                        
                        <span>
                            &nbsp;
                        </span>
                        
                        <hr class="col-md-8 p-0 m-0" style="border-color: black;"/>
                        <em class="p-0">(Signature of School Head over Printed Name)</em>
                    </center>
                </div>
            </div>
        </div>
        </div>
{{-- </div> --}}
<!-- jQuery -->
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
@endsection
