<?php

namespace App\Models\Teacher;

use Illuminate\Database\Eloquent\Model;
use DB;
class FormExcel extends Model
{
    public static function form1($forms)
    {
$html = '<div style="page-break-inside: avoid;">'.
'<table id="header">'.
'<tr>'.
    '<th rowspan="2">'.
        ''.
    '</th>'.
    '<th colspan="11" style="padding-left:5%">'.
        '<h2><center>School Form 1 (SF 1) School Register</center></h2>'.
        '<small><em><center>This replaced Form 1 Master List STS Form 2-Family Background and Profile</center></em></small>'.
    '</th>'.
'</tr>'.
'</table>';

return $html;
// @php
// $countstudentmale = 0;
// $countstudentfemale = 0;
// @endphp
// '<table style="border: 1px solid black; width: 100%; font-size: 9px;" id="formtable">'.
// '<thead>
//     '<tr>'.
//         '<th rowspan="2" style="width: 15px !important;">'.

//         </th>'.
//         '<th rowspan="2"  style="width: 70px;">'.
//             LRN
//         </th>'.
//         '<th rowspan="2">'.
//             NAME
//             <br>'.
//             (Last Name, First Name, Middle Name)
//         </th>'.
//         '<th rowspan="2" style="width: 10px;">'.
//             Sex
//             <br>'.
//             (M/F)
//         </th>'.
//         '<th rowspan="2" style="width: 25px;">'.
//             BIRTH DATE  
//             <br>'.
//             (mm/dd/yyyy)
//         </th>'.
//         '<th rowspan="2" style="width: 10px;">'.
//             AGE as<br>'.
//             of 1st<br>'.
//             Friday
//             <br>'.
//             June
//         </th>'.
//         '<th rowspan="2">'.
//             MOTHER
//             <br>'.
//             TONGUE
//         </th>'.
//         '<th rowspan="2">'.
//             IP
//             <br>'.
//             (Ethnic Group)
//         </th>'.
//         '<th rowspan="2">'.
//             RELIGION
//         </th>'.
//         '<th colspan="4">'.
//             ADDRESS
//         </th>'.
//         '<th colspan="2">'.
//             PARENTS
//         </th>'.
//         '<th colspan="2">'.
//             GUARDIAN
//             <br>'.
//             (If not Parent)
//         </th>'.
//         '<th rowspan="2">'.
//             Contact Number of Parent or Guardian
//         </th>'.
//         '<th>'.
//             REMARKS
//         </th>'.
//     </tr>'.
//     '<tr>'.
//         '<th>'.
//             House #/Street/<br>'.Sitio/Purok
//         </th>'.
//         '<th>'.
//             Barangay
//         </th>'.
//         '<th>'.
//             Municipality/<br>'.City
//         </th>'.
//         '<th>'.
//             Province
//         </th>'.
//         '<th>'.
//             Father's Name (Last Name,<br>'.
//             First Name, Middle Name)
//         </th>'.
//         '<th>'.
//             Mother's Maiden Name (Last<br>'.
//             Name, First Name, Middle<br>'.
//             Name)
//         </th>'.
//         '<th>'.
//             Name
//         </th>'.
//         '<th>'.
//             Relation-ship
//         </th>'.
//         '<th>'.
//             (Please refer to the<br>'.
//             legend on last page)
//         </th>'.
//     </tr>'.
// </thead>
// '<tbody>
//     @foreach($forms[0]->students as $studentinfo)
//         @if(strtolower($studentinfo->gender) == 'male')
//             @php
//                 $countstudentmale+=1;
//             @endphp
//             '<tr>'.
//                 '<td style="text-align: center;">'.$countstudentmale.'</td>
//                 '<td>
//                     '.$studentinfo->lrn.'
//                 </td>
//                 '<td>'.$studentinfo->lastname.', '.$studentinfo->firstname.' '.$studentinfo->middlename.' '.$studentinfo->suffix.'</td>
//                 '<td style="text-align: center;">'.$studentinfo->gender[0].'</td>
//                 '<td style="text-align: center;">'.$studentinfo->dob.'</td>
//                 '<td style="text-align: center;">'.$studentinfo->age.'</td>
//                 '<td style="text-align: center;">'.$studentinfo->mtname.'</td>
//                 '<td style="text-align: center;">'.$studentinfo->egname.'</td>
//                 '<td style="text-align: center;">'.$studentinfo->religionname.'</td>
//                 '<td style="text-align: center;">'.$studentinfo->street.'</td>
//                 '<td style="text-align: center;">'.$studentinfo->barangay.'</td>
//                 '<td style="text-align: center;">'.$studentinfo->city.'</td>
//                 '<td>'.$studentinfo->province.'</td>
//                 '<td>
//                     @if($studentinfo->fathername != ',')
//                         '.$studentinfo->fathername.'
//                     @endif
//                 </td>
//                 '<td>
//                     @if($studentinfo->mothername != ',')
//                         '.$studentinfo->mothername.'
//                     @endif
//                 </td>
//                 '<td>
//                     @if($studentinfo->guardianname != ',')
//                         '.$studentinfo->guardianname.'
//                     @endif
//                 </td>
//                 '<td>'.$studentinfo->guardianrelation.'</td>
//                 '<td style="text-align: center;">'.
//                     @if($studentinfo->fcontactno != null)
//                         '.$studentinfo->fcontactno.'<br>'.
//                     @endif
//                     @if($studentinfo->mcontactno != null)
//                         '.$studentinfo->mcontactno.'<br>'.
//                     @endif
//                     @if($studentinfo->gcontactno != null)
//                         '.$studentinfo->gcontactno.'
//                     @endif
//                 </td>
//                 '<td style="text-align: center;">'.
//                     @if($studentinfo->studstatus == 2) '.-- Late enrolled --.'
//                         LE Date: '.$studentinfo->dateenrolled.'
//                     @elseif($studentinfo->studstatus == 3) '.-- Dropped --.'
//                         DRP
//                     @elseif($studentinfo->studstatus == 4) '.-- Transferred In --.'
//                         T/I Date: '.$studentinfo->dateenrolled.'
//                     @elseif($studentinfo->studstatus == 5) '.-- Transferred Out --.'
//                         T/O Date: '.$studentinfo->dateenrolled.'
//                     @endif
//                 </td>
//             </tr>'.
//         @endif
//     @endforeach
//     '<tr style="text-align: center;">'.
//         '<td></td>
//         '<td>'.$countstudentmale.'</td>
//         '<td>==TOTAL MALE</td>
//         '<td></td>
//         '<td></td>
//         '<td></td>
//         '<td></td>
//         '<td></td>
//         '<td></td>
//         '<td></td>
//         '<td></td>
//         '<td></td>
//         '<td></td>
//         '<td></td>
//         '<td></td>
//         '<td></td>
//         '<td></td>
//         '<td></td>
//         '<td></td>
//     </tr>'.
//     @foreach($forms[0]->students as $studentinfo)
//         @if(strtolower($studentinfo->gender) == 'female')
//             @php
//                 $countstudentfemale+=1;
//             @endphp
//             '<tr>'.
//                 '<td style="text-align: center;">'.$countstudentfemale.'</td>
//                 '<td>
//                     '.$studentinfo->lrn.'
//                 </td style="text-align: center;">'.
//                 '<td>'.$studentinfo->lastname.', '.$studentinfo->firstname.' '.$studentinfo->middlename.' '.$studentinfo->suffix.'</td>
//                 '<td style="text-align: center;">'.$studentinfo->gender[0].'</td>
//                 '<td style="text-align: center;">'.$studentinfo->dob.'</td>
//                 '<td style="text-align: center;">'.$studentinfo->age.'</td>
//                 '<td style="text-align: center;">'.$studentinfo->mtname.'</td>
//                 '<td style="text-align: center;">'.$studentinfo->egname.'</td>
//                 '<td style="text-align: center;">'.$studentinfo->religionname.'</td>
//                 '<td style="text-align: center;">'.$studentinfo->street.'</td>
//                 '<td style="text-align: center;">'.$studentinfo->barangay.'</td>
//                 '<td style="text-align: center;">'.$studentinfo->city.'</td>
//                 '<td>'.$studentinfo->province.'</td>
//                 '<td>
//                     @if($studentinfo->fathername != ',')
//                         '.$studentinfo->fathername.'
//                     @endif
//                 </td>
//                 '<td>
//                     @if($studentinfo->mothername != ',')
//                         '.$studentinfo->mothername.'
//                     @endif
//                 </td>
//                 '<td>
//                     @if($studentinfo->guardianname != ',')
//                         '.$studentinfo->guardianname.'
//                     @endif
//                 </td>
//                 '<td>'.$studentinfo->guardianrelation.'</td>
//                 '<td style="text-align: center;">'.
//                     @if($studentinfo->fcontactno != null)
//                         '.$studentinfo->fcontactno.'<br>'.
//                     @endif
//                     @if($studentinfo->mcontactno != null)
//                         '.$studentinfo->mcontactno.'<br>'.
//                     @endif
//                     @if($studentinfo->gcontactno != null)
//                         '.$studentinfo->gcontactno.'
//                     @endif
//                 </td>
//                 '<td style="text-align: center;">'.
//                     @if($studentinfo->studstatus == 2) '.-- Late enrolled --.'
//                         LE Date: '.$studentinfo->dateenrolled.'
//                     @elseif($studentinfo->studstatus == 3) '.-- Dropped --.'
//                         DRP
//                     @elseif($studentinfo->studstatus == 4) '.-- Transferred In --.'
//                         T/I Date: '.$studentinfo->dateenrolled.'
//                     @elseif($studentinfo->studstatus == 5) '.-- Transferred Out --.'
//                         T/O Date: '.$studentinfo->dateenrolled.'
//                     @endif
//                 </td>
//             </tr>'.
//         @endif
//     @endforeach
//     '<tr style="text-align: center;">'.
//         '<td></td>
//         '<td>'.$countstudentfemale.'</td>
//         '<td>==TOTAL FEMALE</td>
//         '<td></td>
//         '<td></td>
//         '<td></td>
//         '<td></td>
//         '<td></td>
//         '<td></td>
//         '<td></td>
//         '<td></td>
//         '<td></td>
//         '<td></td>
//         '<td></td>
//         '<td></td>
//         '<td></td>
//         '<td></td>
//         '<td></td>
//         '<td></td>
//     </tr>'.
//     '<tr style="text-align: center;">'.
//         '<td></td>
//         '<td>'.$countstudentfemale + $countstudentmale.'</td>
//         '<td>==COMBINED</td>
//         '<td></td>
//         '<td></td>
//         '<td></td>
//         '<td></td>
//         '<td></td>
//         '<td></td>
//         '<td></td>
//         '<td></td>
//         '<td></td>
//         '<td></td>
//         '<td></td>
//         '<td></td>
//         '<td></td>
//         '<td></td>
//         '<td></td>
//         '<td></td>
//     </tr>'.
// </tbody>
// </table>
// <br>'.
// '<table style="width: 100%; border-collapse: collapse;" >
// '<tr>'.
//     '<td >
//         '<table style="width: 100%; border-collapse: collapse; font-size: 10px; " class="indicatortable">'.
//             '<tr>'.
//                 '<th colspan="5" style="text-align: center;">List and Code of Indicators under REMARKS column</th>'.
//                 '<th colspan="4"></th>'.
//                 '<th>'.&amp;nbsp;&amp;nbsp;&amp;nbsp;</th>'.
//                 '<th rowspan="2">Prepared by:</th>'.
//                 '<th rowspan="6">nbsp;&amp;nbsp;&amp;nbsp;</th>'.
//                 '<th rowspan="2">Certified Correct:</th>'.
//             </tr>'.
//             '<tr>'.
//                 '<td>Indicator</td>
//                 '<td style="text-align: center;">Code</td>
//                 '<td>Required Information</td>
//                 '<td style="text-align: center;">Code</td>
//                 '<td>Required Information</td>
//                 '<td rowspan="5" style="border-top: none; border-bottom: none;"></td>
//                 '<td style="text-align: center;">REGISTERED</td>
//                 '<td style="text-align: center;">BoSY</td>
//                 '<td style="text-align: center;">EoSy</td>
//                 '<td rowspan="5" style="border: none;"></td>
//             </tr>'.
//             '<tr>'.
//                 '<td style="border-bottom: none;">Transferred Out</td>
//                 '<td style="border-bottom: none;text-align: center;">T/O</td>
//                 '<td style="border-bottom: none;">Name of Public (P) Private (PR) School Effectivity Date</td>
//                 '<td style="border-bottom: none;text-align: center;">CCT</td>
//                 '<td style="border-bottom: none;">CCT Control/reference number Effectivity Date</td>
//                 '<td style="text-align: center;">MALE</td>
//                 '<td style="text-align: center;">'.$countstudentmale.'</td>
//                 '<td></td>
//                 '<td style="border: none; border-bottom: 1px solid black; text-transform: uppercase; text-align: center;">'.
//                     '.$forms[0]->preparedby->firstname.' '.$forms[0]->preparedby->middlename[0].'.'.' '.$forms[0]->preparedby->lastname.' '.$forms[0]->preparedby->suffix.'
//                 </td>
//                 '<td style="border: none; border-bottom: 1px solid black; text-align: center;">'.
//                     '.$forms[0]->schoolinfo->authorized.'
//                 </td>
//             </tr>'.
//             '<tr>'.
//                 '<td style="border-top: none; border-bottom: none;">Transferred IN</td>
//                 '<td style="border-top: none; border-bottom: none;text-align: center;">T/I</td>
//                 '<td style="border-top: none; border-bottom: none;">Name of Public (P) Private (PR) School Effectivity Date</td>
//                 '<td style="border-top: none; border-bottom: none;text-align: center;">B/A</td>
//                 '<td style="border-top: none; border-bottom: none;">Name of school last attended Year</td>
//                 '<td style="text-align: center;">FEMALE</td>
//                 '<td style="text-align: center;">'.$countstudentfemale.'</td>
//                 '<td></td>
//                 '<td style="border: none; font-size: 8px; text-align: center; padding: 0px;">'.
//                     <sup>
//                         <em>
//                             (Signature of Adviser over Printed Name)
//                         </em>
//                     </sup>
//                 </td>
//                 '<td style="border: none; font-size: 8px; text-align: center;">'.
//                     <sup>
//                         <em>
//                             (Signature of School Head over Printed Name)
//                         </em>
//                     </sup>
//                 </td>
//             </tr>'.
//             '<tr>'.
//                 '<td style="border-top: none; border-bottom: none;">DROPPED</td>
//                 '<td style="border-top: none; border-bottom: none;text-align: center;">DRP</td>
//                 '<td style="border-top: none; border-bottom: none;">Reason and Effectivity Date</td>
//                 '<td style="border-top: none; border-bottom: none;text-align: center;">LWD</td>
//                 '<td style="border-top: none; border-bottom: none;">Specify</td>
//                 '<td rowspan="2" style="text-align: center;">TOTAL</td>
//                 '<td rowspan="2" style="text-align: center;">'.$countstudentmale+$countstudentfemale.'</td>
//                 '<td rowspan="2"></td>
//                 '<td rowspan="2" style="border: none; border-bottom: 1px solid;">'.
//                     BoSy Date:nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; EoSY Date:
//                 </td>
//                 '<td rowspan="2" style="border: none; border-bottom: 1px solid;">'.
//                     BoSy Date:nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; EoSY Date:
//                 </td>
//             </tr>'.
//             '<tr>'.
//                 '<td style="border-top: none;">Late Enrollment</td>
//                 '<td style="border-top: none;text-align: center;">LE</td>
//                 '<td style="border-top: none;">Reason (Enrollment beyond 1st Friday of June)</td>
//                 '<td style="border-top: none;text-align: center;">ACL</td>
//                 '<td style="border-top: none;">Specify </td>
//             </tr>'.
//         </table>
//     </td>
// </tr>'.
// </table>



    }
}
