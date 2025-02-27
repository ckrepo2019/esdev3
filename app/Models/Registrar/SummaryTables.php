<?php

namespace App\Models\Registrar;

use Illuminate\Database\Eloquent\Model;

class SummaryTables extends Model
{
    public static function table1($strands,$filteredstud,$selectedgender)
    {
        $numofstudents = 1;   
        $numofstudentsall = 1;  
        $html = '';
        // return $filteredstud;
        if(count($strands) > 0)
        {
            foreach($strands as $strand)
            {
                $malecount = 1;
                $femalecount = 1;
                if(count(collect($filteredstud)->where('strandid', $strand->id)) > 0)
                {
                    $html.='<table border="1" cellpadding="2">
                        <thead>
                            <tr>
                                <th colspan="3"  style="font-size: 10px !important; font-weight: bold; text-align: center;" >'.$strand->strandname.'<br/>'.$selectedgender.'</th>
                            </tr>
                            <tr>
                                <th style="font-size: 10px !important; font-weight: bold; text-align: center;" width="400">Students</th>
                                <th style="font-size: 10px !important; font-weight: bold; text-align: center;" width="119">Grade Level</th>
                                <th style="font-size: 10px !important; font-weight: bold; text-align: center;" width="119">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="3"  style="background-color: #b3ecff;">MALE</td>
                            </tr>
                        ';

                            foreach($filteredstud as $student)
                            {
                                if(strtolower($student->gender) == 'male')
                                {
                                    if($student->dateenrolled == null)
                                    {
                                        $date = '';
                                    }else{
                                        $date=date_create($student->dateenrolled);
                                        $date = date_format($date,"m/d/Y");
                                    }
                                    if($student->strandid == $strand->id)
                                    {
                                        $html.='<tr nobr="true">
                                            <td style="font-size: 10px !important; text-align: left;" width="400">'.$malecount.'. '.$student->lastname.', '.$student->firstname.' '.$student->middlename.'</td>
                                            <td style="font-size: 10px !important; text-align: center;" width="119">'.$student->levelname.'</td>
                                            <td style="font-size: 10px !important; text-align: center;" width="119">'.$student->studentstatus.' <span style="float: right; font-size: 9px;">'.$date.'</span></td>
                                        </tr>';
                                        $malecount+=1;   
                                        $numofstudents+=1;   
                                    }
                                }
                            }
                            $html.='<tr>
                                        <td colspan="3"  style="background-color: #ffccff;">FEMALE</td>
                                    </tr>';
                                    foreach($filteredstud as $student)
                                    {
                                        if(strtolower($student->gender) == 'female')
                                        {
                                            if($student->dateenrolled == null)
                                            {
                                                $date = '';
                                            }else{
                                                $date=date_create($student->dateenrolled);
                                                $date = date_format($date,"m/d/Y");
                                            }
                                            if($student->strandid == $strand->id)
                                            {
                                                $html.='<tr nobr="true">
                                                    <td style="font-size: 10px !important; text-align: left;" width="400">'.$femalecount.'. '.$student->lastname.', '.$student->firstname.' '.$student->middlename.'</td>
                                                    <td style="font-size: 10px !important; text-align: center;" width="119">'.$student->levelname.'</td>
                                                    <td style="font-size: 10px !important; text-align: center;" width="119">'.$student->studentstatus.' <span style="float: right; font-size: 9px;">'.$date.'</span></td>
                                                </tr>';
                                                $femalecount+=1;   
                                                $numofstudents+=1;   
                                            }
                                        }
                                    }
                            $html.='</tbody>
                    </table><table border="0">
                    <tr style="line-height: 30px;" > 
                    <td></td>
                    </tr>
                    </table>';
                }
            }
        }
        return $html;
    }
    public static function table2($selectedstudenttype,$filteredstud,$selectedgender)
    {
        // return 'asdasd';
        $numofstudents = 1;   
        $malecount = 1;   
        $femalecount = 1;   
        $numofstudentsall = 1;  
        $html = '';
        $html.='<table border="1" cellpadding="2">
            <thead >
                <tr>
                    <th colspan="3" style="font-size: 10px !important; font-weight: bold; text-align: center;">'.strtoupper($selectedstudenttype).' STUDENTS<br/>'.$selectedgender.'</th>
                </tr>
                <tr>
                    <th style="font-size: 10px !important; font-weight: bold; text-align: center;" width="400">Students</th>
                    <th style="font-size: 10px !important; font-weight: bold; text-align: center;" width="119">Grade Level</th>
                    <th style="font-size: 10px !important; font-weight: bold; text-align: center;" width="119">Status</th>
                </tr>
            </thead>
            <tbody>
            <tr>
                <td colspan="3"  style="background-color: #b3ecff;">MALE</td>
            </tr>';
                foreach($filteredstud as $student)
                {
                    if(strtolower($student->gender) == 'male')
                    {
                        if($student->dateenrolled == null)
                        {
                            $date = '';
                        }else{
                            $date=date_create($student->dateenrolled);
                            $date = date_format($date,"m/d/Y");
                        }
                            $html.='<tr nobr="true">
                                <td style="font-size: 10px !important;text-align: left;" width="400">'.$malecount.'. '.$student->lastname.', '.$student->firstname.' '.$student->middlename.'</td>
                                <td style="font-size: 10px !important;text-align: center;" width="119">'.$student->levelname.'</td>
                                <td style="font-size: 10px !important;text-align: center;" width="119">'.$student->studentstatus.' <span style="float: right; font-size: 9px;">'.$date.'</span></td>
                            </tr>';
                            $malecount+=1;   
                            $numofstudents+=1;   
                    }
                    // }else{
                    //     $html.='<tr nobr="true">
                    //         <td style="font-size: 10px !important;text-align: left;" width="400">'.$numofstudents.'. '.$student->lastname.', '.$student->firstname.' '.$student->middlename.'</td>
                    //         <td style="font-size: 10px !important;text-align: center;" width="119">'.$student->levelname.'</td>
                    //         <td style="font-size: 10px !important;text-align: center;" width="119">'.$student->studentstatus.' <span style="float: right; font-size: 9px;">'.$date.'</span></td>
                    //     </tr>';
                    // }
                }
                $html.='
                <tr>
                    <td colspan="3"  style="background-color: #ffccff;">FEMALE</td>
                </tr>';
                foreach($filteredstud as $student)
                {
                    if(strtolower($student->gender) == 'female')
                    {
                        if($student->dateenrolled == null)
                        {
                            $date = '';
                        }else{
                            $date=date_create($student->dateenrolled);
                            $date = date_format($date,"m/d/Y");
                        }
                            $html.='<tr nobr="true">
                                <td style="font-size: 10px !important;text-align: left;" width="400">'.$femalecount.'. '.$student->lastname.', '.$student->firstname.' '.$student->middlename.'</td>
                                <td style="font-size: 10px !important;text-align: center;" width="119">'.$student->levelname.'</td>
                                <td style="font-size: 10px !important;text-align: center;" width="119">'.$student->studentstatus.' <span style="float: right; font-size: 9px;">'.$date.'</span></td>
                            </tr>';
                            $femalecount+=1;   
                            $numofstudents+=1;   
                    }
                    // }else{
                    //     $html.='<tr nobr="true">
                    //         <td style="font-size: 10px !important;text-align: left;" width="400">'.$numofstudents.'. '.$student->lastname.', '.$student->firstname.' '.$student->middlename.'</td>
                    //         <td style="font-size: 10px !important;text-align: center;" width="119">'.$student->levelname.'</td>
                    //         <td style="font-size: 10px !important;text-align: center;" width="119">'.$student->studentstatus.' <span style="float: right; font-size: 9px;">'.$date.'</span></td>
                    //     </tr>';
                    // }
                }
                $html.='</tbody>
        </table>';
        return $html;
    }
    public static function table3($strands,$filteredstud,$selectedgender)
    {
        $numofstudentsall = 1;  
        $html = '';
        
        if(count($strands)>0)
        {
            foreach($strands as $strand)
            {
                $malecount = 1;
                $femalecount = 1;
                $html.='<table border="1" cellpadding="2" style="margin-top: 5px;">
                    <thead style="border: 1px solid black;">
                        <tr>
                            <th colspan="3" style="font-size: 10px !important; font-weight: bold; text-align: center;">'.$strand->strandname.'<br/>'.$selectedgender.'</th>
                        </tr>
                        <tr>
                            <th style="font-size: 10px !important; font-weight: bold; text-align: center;" width="400">Students</th>
                            <th style="font-size: 10px !important; font-weight: bold; text-align: center;" width="119">Grade Level</th>
                            <th style="font-size: 10px !important; font-weight: bold; text-align: center;" width="119">Status</th>
                        </tr>
                    </thead>
                    <tbody>';
                        if(count($filteredstud) == 0)
                        {
                            $html.='<tr nobr="true">
                                <td colspan="3" style="text-align:center">No students</td>
                            </tr>';
                        }else{
                            $html.='
                            <tr>
                                <td colspan="3"  style="background-color: #b3ecff;">MALE</td>
                            </tr>';
                            $numofstudents = 1;   
                            foreach($filteredstud as $student)
                            {
                                if(strtolower($student->gender) == 'male')
                                {
                                    if($student->dateenrolled == null)
                                    {
                                        $date = '';
                                    }else{
                                        $date=date_create($student->dateenrolled);
                                        $date = date_format($date,"m/d/Y");
                                    }
                                    if($student->strandid == $strand->id)
                                    {
                                        // if(strtolower($student->gender) == 'male')
                                        // {
                                            $html.='<tr nobr="true">
                                                <td style="font-size: 10px !important; text-align: left;" width="400">'.$malecount.'. '.$student->lastname.', '.$student->firstname.' '.$student->middlename.'</td>
                                                <td style="font-size: 10px !important; text-align: center;" width="119">'.$student->levelname.'</td>
                                                <td style="font-size: 10px !important; text-align: center;" width="119">'.$student->studentstatus.' <span style="float: right; font-size: 9px;">'.$date.'</span></td>
                                            </tr>';
                                        // }else{
                                        //     $html.='<tr nobr="true">
                                        //         <td style="font-size: 10px !important; text-align: left;" width="400">'.$numofstudents.'. '.$student->lastname.', '.$student->firstname.' '.$student->middlename.'</td>
                                        //         <td style="font-size: 10px !important; text-align: center;" width="119">'.$student->levelname.'</td>
                                        //         <td style="font-size: 10px !important; text-align: center;" width="119">'.$student->studentstatus.' <span style="float: right; font-size: 9px;">'.$date.'</span></td>
                                        //     </tr>';
                                        // }
                                        $malecount+=1;   
                                        $numofstudents+=1;   
                                    }
                                }
                            }
                            $html.='
                            <tr>
                                <td colspan="3"  style="background-color: #ffccff;">FEMALE</td>
                            </tr>';
                            foreach($filteredstud as $student)
                            {
                                if(strtolower($student->gender) == 'female')
                                {
                                    if($student->dateenrolled == null)
                                    {
                                        $date = '';
                                    }else{
                                        $date=date_create($student->dateenrolled);
                                        $date = date_format($date,"m/d/Y");
                                    }
                                    if($student->strandid == $strand->id)
                                    {
                                        // if(strtolower($student->gender) == 'male')
                                        // {
                                            // $html.='<tr nobr="true">
                                            //     <td style="font-size: 10px !important; text-align: left;" width="400">'.$malecount.'. '.$student->lastname.', '.$student->firstname.' '.$student->middlename.'</td>
                                            //     <td style="font-size: 10px !important; text-align: center;" width="119">'.$student->levelname.'</td>
                                            //     <td style="font-size: 10px !important; text-align: center;" width="119">'.$student->studentstatus.' <span style="float: right; font-size: 9px;">'.$date.'</span></td>
                                            // </tr>';
                                        // }else{
                                            $html.='<tr nobr="true">
                                                <td style="font-size: 10px !important; text-align: left;" width="400">'.$femalecount.'. '.$student->lastname.', '.$student->firstname.' '.$student->middlename.'</td>
                                                <td style="font-size: 10px !important; text-align: center;" width="119">'.$student->levelname.'</td>
                                                <td style="font-size: 10px !important; text-align: center;" width="119">'.$student->studentstatus.' <span style="float: right; font-size: 9px;">'.$date.'</span></td>
                                            </tr>';
                                        // }
                                        $femalecount+=1;   
                                        $numofstudents+=1;   
                                    }
                                }
                            }
                        }
                        
                        $html.='</tbody>
                </table><table border="0">
                <tr style="line-height: 30px;" > 
                <td></td>
                </tr>
                </table>';
            }
        }
        return $html;
    }
    
    public static function table4($trackname,$strandname,$filteredstud,$selectedgender)
    {
        // return 'asdasd';
        $numofstudents = 1;   
        $numofstudentsall = 1;  
        $malecount = 1;
        $femalecount = 1;
        $html = '';
        $html .= '<table border="1" cellpadding="2" >
                    <thead >
                        <tr>
                            <th colspan="3" style="font-size: 10px !important; font-weight: bold; text-align: center;">'.$trackname.'<br>'.$strandname.'<br/>'.$selectedgender.'</th>
                        </tr>
                        <tr>
                            <th style="font-size: 10px !important; font-weight: bold; text-align: center;" width="400">Students</th>
                            <th style="font-size: 10px !important; font-weight: bold; text-align: center;" width="119">Grade Level</th>
                            <th style="font-size: 10px !important; font-weight: bold; text-align: center;" width="119">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td colspan="3"  style="background-color: #b3ecff;">MALE</td>
                    </tr>';
                foreach($filteredstud as $student)
                {
                    if(strtolower($student->gender) == 'male')
                    {
                        if($student->dateenrolled == null)
                        {
                            $date = '';
                        }else{
                            $date=date_create($student->dateenrolled);
                            $date = date_format($date,"m/d/Y");
                        }
                        // if(strtolower($student->gender) == 'male')
                        // {
                            $html .= '<tr nobr="true">
                                <td style="font-size: 10px !important; text-align: left;" width="400">'.$malecount.'. '.$student->lastname.', '.$student->firstname.' '.$student->middlename.'</td>
                                <td style="font-size: 10px !important; text-align: center;" width="119">'.$student->levelname.'</td>
                                <td style="font-size: 10px !important; text-align: center;" width="119">'.$student->studentstatus.' <span style="float: right; font-size: 9px;">'.$date.'</span></td>
                            </tr>';
                        // }else{
                        //     $html .= '<tr nobr="true">
                        //         <td style="font-size: 10px !important; text-align: left;" width="400">'.$numofstudents.'. '.$student->lastname.', '.$student->firstname.' '.$student->middlename.'</td>
                        //         <td style="font-size: 10px !important; text-align: center;" width="119">'.$student->levelname.'</td>
                        //         <td style="font-size: 10px !important; text-align: center;" width="119">'.$student->studentstatus.' <span style="float: right; font-size: 9px;">'.$date.'</span></td>
                        //     </tr>';
                        // }
                        $malecount+=1;  
                        $numofstudents+=1;  
                    } 
                }
                $html .= '
                <tr>
                    <td colspan="3"  style="background-color: #ffccff;">FEMALE</td>
                </tr>';
                foreach($filteredstud as $student)
                {
                    if(strtolower($student->gender) == 'female')
                    {
                        if($student->dateenrolled == null)
                        {
                            $date = '';
                        }else{
                            $date=date_create($student->dateenrolled);
                            $date = date_format($date,"m/d/Y");
                        }
                        // if(strtolower($student->gender) == 'male')
                        // {
                            // $html .= '<tr nobr="true">
                            //     <td style="font-size: 10px !important; text-align: left;" width="400">'.$malecount.'. '.$student->lastname.', '.$student->firstname.' '.$student->middlename.'</td>
                            //     <td style="font-size: 10px !important; text-align: center;" width="119">'.$student->levelname.'</td>
                            //     <td style="font-size: 10px !important; text-align: center;" width="119">'.$student->studentstatus.' <span style="float: right; font-size: 9px;">'.$date.'</span></td>
                            // </tr>';
                        // }else{
                            $html .= '<tr nobr="true">
                                <td style="font-size: 10px !important; text-align: left;" width="400">'.$femalecount.'. '.$student->lastname.', '.$student->firstname.' '.$student->middlename.'</td>
                                <td style="font-size: 10px !important; text-align: center;" width="119">'.$student->levelname.'</td>
                                <td style="font-size: 10px !important; text-align: center;" width="119">'.$student->studentstatus.' <span style="float: right; font-size: 9px;">'.$date.'</span></td>
                            </tr>';
                        // }
                        $femalecount+=1;  
                        $numofstudents+=1;  
                    } 
                }
                
                $html.='</tbody>
        </table>';
        return $html;
    }

    ///////////////////////////////////////////////////////////////////                     //////////////////////////////////////////////////////////////
    
    public static function table_0($filteredstud,$selectedgender)
    {
        $numofstudents = 1;   
        $numofstudentsall = 1;  
        $html = '';
        $malecount = 1;
        $femalecount = 1;
        $html.='<table border="1" cellpadding="2" style="font-size: 10px;">
            <thead>
                <tr>
                    <th style=" font-weight: bold; text-align: center;" width="5%">#</th>
                    <th style=" font-weight: bold; text-align: center;" width="10%" >SID</th>
                    <th style=" font-weight: bold; text-align: center;" width="10%" >LRN</th>
                    <th style=" font-weight: bold; text-align: center;" width="15%">Students</th>
                    
                    <th style=" font-weight: bold; text-align: center;" width="10%" >Grade Level</th>
                    <th style=" font-weight: bold; text-align: center;" width="10%" >Section</th>
                    <th style=" font-weight: bold; text-align: center;" width="10%" >College/<br/>Track</th>
                    <th style=" font-weight: bold; text-align: center;" width="10%" >Course/<br/>Strand</th>
                    <th style=" font-weight: bold; text-align: center;" width="10%" >MOL</th>
                    <th style=" font-weight: bold; text-align: center;" width="10%" >Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="10"  style="background-color: #b3ecff;">MALE</td>
                </tr>
            ';
            foreach($filteredstud as $student)
            {
                if(strtolower($student->gender) == 'male')
                {
                    if($student->dateenrolled == null)
                    {
                        $date = '';
                    }else{
                        $date=date_create($student->dateenrolled);
                        $date = date_format($date,"m/d/Y");
                    }
                    $html.='<tr nobr="true">
                                <td style="font-size: 10px !important; text-align: left;" width="5%">'.$malecount.'</td>
                                <td style="font-size: 10px !important; text-align: left;" width="10%">'.$student->sid.'</td>
                                <td style="font-size: 10px !important; text-align: left;" width="10%">'.$student->lrn.'</td>
                                <td style="font-size: 10px !important; text-align: left;" width="15%">'.$student->lastname.', '.$student->firstname.' '.$student->middlename.'</td>
                                
                                <td style="font-size: 10px !important; text-align: left;" width="10%">'.$student->levelname.'</td>
                                <td style="font-size: 10px !important; text-align: left;" width="10%">'.$student->sectionname.'</td>
                                <td style="font-size: 10px !important; text-align: left;" width="10%">'.$student->trackname.'</td>
                                <td style="font-size: 10px !important; text-align: left;" width="10%">'.$student->strandcode.'</td>
                                <td style="font-size: 10px !important; text-align: left;" width="10%">'.$student->mol.'</td>
                                <td style="font-size: 10px !important; text-align: left;" width="10%">'.$student->studentstatus.'</td>
                            </tr>';
                    $malecount+=1;   
                    $numofstudents+=1;   
                }
            }
            $html.='<tr>
                        <td colspan="10"  style="background-color: #ffccff;">FEMALE</td>
                    </tr>';
            foreach($filteredstud as $student)
            {
                if(strtolower($student->gender) == 'female')
                {
                    if($student->dateenrolled == null)
                    {
                        $date = '';
                    }else{
                        $date=date_create($student->dateenrolled);
                        $date = date_format($date,"m/d/Y");
                    }
                        $html.='<tr nobr="true">
                                    <td style="font-size: 10px !important; text-align: left;" width="5%">'.$femalecount.'</td>
                                    <td style="font-size: 10px !important; text-align: left;" width="10%">'.$student->sid.'</td>
                                    <td style="font-size: 10px !important; text-align: left;" width="10%">'.$student->lrn.'</td>
                                    <td style="font-size: 10px !important; text-align: left;" width="15%">'.$student->lastname.', '.$student->firstname.' '.$student->middlename.'</td>
                                    
                                    <td style="font-size: 10px !important; text-align: left;" width="10%">'.$student->levelname.'</td>
                                    <td style="font-size: 10px !important; text-align: left;" width="10%">'.$student->sectionname.'</td>
                                    <td style="font-size: 10px !important; text-align: left;" width="10%">'.$student->trackname.'</td>
                                    <td style="font-size: 10px !important; text-align: left;" width="10%">'.$student->strandcode.'</td>
                                    <td style="font-size: 10px !important; text-align: left;" width="10%">'.$student->mol.'</td>
                                    <td style="font-size: 10px !important; text-align: left;" width="10%">'.$student->studentstatus.'</td>
                                </tr>';
                        $femalecount+=1;   
                        $numofstudents+=1;   
                }
            }

                $html.='</tbody>
        </table><table border="0">
        <tr style="line-height: 30px;" > 
        <td></td>
        </tr>
        </table>';
        return $html;
    }
    public static function table_1($gradelevels,$filteredstud,$selectedgender)
    {
        $numofstudents = 1;   
        $numofstudentsall = 1;  
        $html = '';
        // return $filteredstud;
        if(count($gradelevels) > 0)
        {
            foreach($gradelevels as $gradelevel)
            {
                $malecount = 1;
                $femalecount = 1;
                if(count(collect($filteredstud)->where('levelid', $gradelevel->id)) > 0)
                {
                    $html.='<table border="1" cellpadding="2">
                        <thead>
                            <tr>
                                <th colspan="10"  style="font-size: 10px !important; font-weight: bold; text-align: center;" >'.$gradelevel->levelname.'</th>
                            </tr>
                            <tr>
                            <th style=" font-weight: bold; text-align: center;" width="5%">#</th>
                            <th style=" font-weight: bold; text-align: center;" width="10%" >SID</th>
                            <th style=" font-weight: bold; text-align: center;" width="10%" >LRN</th>
                            <th style=" font-weight: bold; text-align: center;" width="15%">Students</th>
                            
                            <th style=" font-weight: bold; text-align: center;" width="10%" >Grade Level</th>
                            <th style=" font-weight: bold; text-align: center;" width="10%" >Section</th>
                            <th style=" font-weight: bold; text-align: center;" width="10%" >College/<br/>Track</th>
                            <th style=" font-weight: bold; text-align: center;" width="10%" >Course/<br/>Strand</th>
                            <th style=" font-weight: bold; text-align: center;" width="10%" >MOL</th>
                            <th style=" font-weight: bold; text-align: center;" width="10%" >Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="10"  style="background-color: #b3ecff;">MALE</td>
                            </tr>
                        ';
                        foreach($filteredstud as $student)
                        {
                            if($student->levelid == $gradelevel->id)
                            {
                                if(strtolower($student->gender) == 'male')
                                {
                                    if($student->dateenrolled == null)
                                    {
                                        $date = '';
                                    }else{
                                        $date=date_create($student->dateenrolled);
                                        $date = date_format($date,"m/d/Y");
                                    }
                                    $html.='<tr nobr="true">
                                                <td style="font-size: 10px !important; text-align: left;" width="5%">'.$malecount.'</td>
                                                <td style="font-size: 10px !important; text-align: left;" width="10%">'.$student->sid.'</td>
                                                <td style="font-size: 10px !important; text-align: left;" width="10%">'.$student->lrn.'</td>
                                                <td style="font-size: 10px !important; text-align: left;" width="15%">'.$student->lastname.', '.$student->firstname.' '.$student->middlename.'</td>
                                                
                                                <td style="font-size: 10px !important; text-align: left;" width="10%">'.$student->levelname.'</td>
                                                <td style="font-size: 10px !important; text-align: left;" width="10%">'.$student->sectionname.'</td>
                                                <td style="font-size: 10px !important; text-align: left;" width="10%">'.$student->trackname.'</td>
                                                <td style="font-size: 10px !important; text-align: left;" width="10%">'.$student->strandcode.'</td>
                                                <td style="font-size: 10px !important; text-align: left;" width="10%">'.$student->mol.'</td>
                                                <td style="font-size: 10px !important; text-align: left;" width="10%">'.$student->studentstatus.'</td>
                                            </tr>';
                                    $malecount+=1;   
                                    $numofstudents+=1;   
                                }
                            }
                        }
                        $html.='<tr>
                                    <td colspan="10"  style="background-color: #ffccff;">FEMALE</td>
                                </tr>';
                        foreach($filteredstud as $student)
                        {
                            if($student->levelid == $gradelevel->id)
                            {
                                if(strtolower($student->gender) == 'female')
                                {
                                    if($student->dateenrolled == null)
                                    {
                                        $date = '';
                                    }else{
                                        $date=date_create($student->dateenrolled);
                                        $date = date_format($date,"m/d/Y");
                                    }
                                        $html.='<tr nobr="true">
                                                    <td style="font-size: 10px !important; text-align: left;" width="5%">'.$femalecount.'</td>
                                                    <td style="font-size: 10px !important; text-align: left;" width="10%">'.$student->sid.'</td>
                                                    <td style="font-size: 10px !important; text-align: left;" width="10%">'.$student->lrn.'</td>
                                                    <td style="font-size: 10px !important; text-align: left;" width="15%">'.$student->lastname.', '.$student->firstname.' '.$student->middlename.'</td>
                                                    
                                                    <td style="font-size: 10px !important; text-align: left;" width="10%">'.$student->levelname.'</td>
                                                    <td style="font-size: 10px !important; text-align: left;" width="10%">'.$student->sectionname.'</td>
                                                    <td style="font-size: 10px !important; text-align: left;" width="10%">'.$student->trackname.'</td>
                                                    <td style="font-size: 10px !important; text-align: left;" width="10%">'.$student->strandcode.'</td>
                                                    <td style="font-size: 10px !important; text-align: left;" width="10%">'.$student->mol.'</td>
                                                    <td style="font-size: 10px !important; text-align: left;" width="10%">'.$student->studentstatus.'</td>
                                                </tr>';
                                        $femalecount+=1;   
                                        $numofstudents+=1;   
                                }
                            }
                        }

                            $html.='</tbody>
                    </table><table border="0">
                    <tr style="line-height: 30px;" > 
                    <td></td>
                    </tr>
                    </table>';
                }
            }
        }
        return $html;
    }
    public static function table_2($sections,$filteredstud,$selectedgender)
    {
        $numofstudents = 1;   
        $numofstudentsall = 1;  
        $html = '';
        // return $filteredstud;
        if(count($sections) > 0)
        {
            foreach($sections as $section)
            {
                $malecount = 1;
                $femalecount = 1;
                if(count(collect($filteredstud)->where('sectionid', $section->id)) > 0)
                {
                    $html.='<table border="1" cellpadding="2">
                        <thead>
                            <tr>
                                <th colspan="10"  style="font-size: 10px !important; font-weight: bold; text-align: center;" >SECTION : '.$section->sectionname.'</th>
                            </tr>
                            <tr>
                                <th style=" font-weight: bold; text-align: center;" width="5%">#</th>
                                <th style=" font-weight: bold; text-align: center;" width="10%" >SID</th>
                                <th style=" font-weight: bold; text-align: center;" width="10%" >LRN</th>
                                <th style=" font-weight: bold; text-align: center;" width="15%">Students</th>
                                
                                <th style=" font-weight: bold; text-align: center;" width="10%" >Grade Level</th>
                                <th style=" font-weight: bold; text-align: center;" width="10%" >Section</th>
                                <th style=" font-weight: bold; text-align: center;" width="10%" >College/<br/>Track</th>
                                <th style=" font-weight: bold; text-align: center;" width="10%" >Course/<br/>Strand</th>
                                <th style=" font-weight: bold; text-align: center;" width="10%" >MOL</th>
                                <th style=" font-weight: bold; text-align: center;" width="10%" >Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="10"  style="background-color: #b3ecff;">MALE</td>
                            </tr>
                        ';
                        foreach($filteredstud as $student)
                        {
                            if($student->sectionid == $section->id)
                            {
                                if(strtolower($student->gender) == 'male')
                                {
                                    if($student->dateenrolled == null)
                                    {
                                        $date = '';
                                    }else{
                                        $date=date_create($student->dateenrolled);
                                        $date = date_format($date,"m/d/Y");
                                    }
                                    $html.='<tr nobr="true">
                                                <td style="width:3%; font-size: 10px !important; text-align: left;" width="5%">'.$malecount.'</td>
                                                <td style="width:8%; font-size: 10px !important; text-align: left;" width="10%">'.$student->sid.'</td>
                                                <td style="width:10%; font-size: 10px !important; text-align: left;" width="10%">'.$student->lrn.'</td>
                                                <td style="width:15%; width:15%; font-size: 10px !important; text-align: left;" width="15%">'.$student->lastname.', '.$student->firstname.' '.$student->middlename.'</td>
                                                
                                                <td style="width:10%; font-size: 10px !important; text-align: left;" width="10%">'.$student->levelname.'</td>
                                                <td style="width:10%; font-size: 10px !important; text-align: left;" width="10%">'.$student->sectionname.'</td>
                                                <td style="width:10%; font-size: 10px !important; text-align: left;" width="10%">'.$student->trackname.'</td>
                                                <td style="width:10%; font-size: 10px !important; text-align: left;" width="10%">'.$student->strandcode.'</td>
                                                <td style="width:10%; font-size: 10px !important; text-align: left;" width="10%">'.$student->mol.'</td>
                                                <td style="width:8%; font-size: 10px !important; text-align: left;" width="10%">'.$student->studentstatus.'</td>
                                            </tr>';
                                    $malecount+=1;   
                                    $numofstudents+=1;   
                                }
                            }
                        }
                        $html.='<tr>
                                    <td colspan="10"  style="background-color: #ffccff;">FEMALE</td>
                                </tr>';
                        foreach($filteredstud as $student)
                        {
                            if($student->sectionid == $section->id)
                            {
                                if(strtolower($student->gender) == 'female')
                                {
                                    if($student->dateenrolled == null)
                                    {
                                        $date = '';
                                    }else{
                                        $date=date_create($student->dateenrolled);
                                        $date = date_format($date,"m/d/Y");
                                    }
                                        $html.='<tr nobr="true">
                                                    <td style="width:3%; font-size: 10px !important; text-align: left;" width="5%">'.$femalecount.'</td>
                                                    <td style="width:8%; font-size: 10px !important; text-align: left;" width="10%">'.$student->sid.'</td>
                                                    <td style="width:10%; font-size: 10px !important; text-align: left;" width="10%">'.$student->lrn.'</td>
                                                    <td style="width:15%; font-size: 10px !important; text-align: left;" width="15%">'.$student->lastname.', '.$student->firstname.' '.$student->middlename.'</td>
                                                    
                                                    <td style="width:10%; font-size: 10px !important; text-align: left;" width="10%">'.$student->levelname.'</td>
                                                    <td style="width:10%; font-size: 10px !important; text-align: left;" width="10%">'.$student->sectionname.'</td>
                                                    <td style="width:10%; font-size: 10px !important; text-align: left;" width="10%">'.$student->trackname.'</td>
                                                    <td style="width:10%; font-size: 10px !important; text-align: left;" width="10%">'.$student->strandcode.'</td>
                                                    <td style="width:10%; font-size: 10px !important; text-align: left;" width="10%">'.$student->mol.'</td>
                                                    <td style="width:8%; font-size: 10px !important; text-align: left;" width="10%">'.$student->studentstatus.'</td>
                                                </tr>';
                                        $femalecount+=1;   
                                        $numofstudents+=1;   
                                }
                            }
                        }

                            $html.='</tbody>
                    </table><table border="0">
                    <tr style="line-height: 30px;" > 
                    <td></td>
                    </tr>
                    </table>';
                }
            }
        }
        return $html;
    }
    public static function table_3($tracks,$filteredstud,$selectedgender)
    {
        $numofstudents = 1;   
        $numofstudentsall = 1;  
        $html = '';
        // return $filteredstud;
        if(count($tracks) > 0)
        {
            foreach($tracks as $track)
            {
                $malecount = 1;
                $femalecount = 1;
                if(count(collect($filteredstud)->where('trackid', $track->id)) > 0)
                {
                    $html.='<table border="1" cellpadding="2">
                        <thead>
                            <tr>
                                <th colspan="10"  style="font-size: 10px !important; font-weight: bold; text-align: center;" >TRACK : '.$track->trackname.'</th>
                            </tr>
                            <tr>
                                <th style=" font-weight: bold; text-align: center;" width="5%">#</th>
                                <th style=" font-weight: bold; text-align: center;" width="10%" >SID</th>
                                <th style=" font-weight: bold; text-align: center;" width="10%" >LRN</th>
                                <th style=" font-weight: bold; text-align: center;" width="15%">Students</th>
                                
                                <th style=" font-weight: bold; text-align: center;" width="10%" >Grade Level</th>
                                <th style=" font-weight: bold; text-align: center;" width="10%" >Section</th>
                                <th style=" font-weight: bold; text-align: center;" width="10%" >College/<br/>Track</th>
                                <th style=" font-weight: bold; text-align: center;" width="10%" >Course/<br/>Strand</th>
                                <th style=" font-weight: bold; text-align: center;" width="10%" >MOL</th>
                                <th style=" font-weight: bold; text-align: center;" width="10%" >Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="10"  style="background-color: #b3ecff;">MALE</td>
                            </tr>
                        ';
                        foreach($filteredstud as $student)
                        {
                            if($student->trackid == $track->id)
                            {
                                if(strtolower($student->gender) == 'male')
                                {
                                    if($student->dateenrolled == null)
                                    {
                                        $date = '';
                                    }else{
                                        $date=date_create($student->dateenrolled);
                                        $date = date_format($date,"m/d/Y");
                                    }
                                    $html.='<tr nobr="true">
                                                <td style="width:3%; font-size: 10px !important; text-align: left;" width="5%">'.$malecount.'</td>
                                                <td style="width:8%; font-size: 10px !important; text-align: left;" width="10%">'.$student->sid.'</td>
                                                <td style="width:10%; font-size: 10px !important; text-align: left;" width="10%">'.$student->lrn.'</td>
                                                <td style="width:15%; width:15%; font-size: 10px !important; text-align: left;" width="15%">'.$student->lastname.', '.$student->firstname.' '.$student->middlename.'</td>
                                                
                                                <td style="width:10%; font-size: 10px !important; text-align: left;" width="10%">'.$student->levelname.'</td>
                                                <td style="width:10%; font-size: 10px !important; text-align: left;" width="10%">'.$student->sectionname.'</td>
                                                <td style="width:10%; font-size: 10px !important; text-align: left;" width="10%">'.$student->trackname.'</td>
                                                <td style="width:10%; font-size: 10px !important; text-align: left;" width="10%">'.$student->strandcode.'</td>
                                                <td style="width:10%; font-size: 10px !important; text-align: left;" width="10%">'.$student->mol.'</td>
                                                <td style="width:8%; font-size: 10px !important; text-align: left;" width="10%">'.$student->studentstatus.'</td>
                                            </tr>';
                                    $malecount+=1;   
                                    $numofstudents+=1;   
                                }
                            }
                        }
                        $html.='<tr>
                                    <td colspan="10"  style="background-color: #ffccff;">FEMALE</td>
                                </tr>';
                        foreach($filteredstud as $student)
                        {
                            if($student->trackid == $track->id)
                            {
                                if(strtolower($student->gender) == 'female')
                                {
                                    if($student->dateenrolled == null)
                                    {
                                        $date = '';
                                    }else{
                                        $date=date_create($student->dateenrolled);
                                        $date = date_format($date,"m/d/Y");
                                    }
                                        $html.='<tr nobr="true">
                                                    <td style="width:3%; font-size: 10px !important; text-align: left;" width="5%">'.$femalecount.'</td>
                                                    <td style="width:8%; font-size: 10px !important; text-align: left;" width="10%">'.$student->sid.'</td>
                                                    <td style="width:10%; font-size: 10px !important; text-align: left;" width="10%">'.$student->lrn.'</td>
                                                    <td style="width:15%; font-size: 10px !important; text-align: left;" width="15%">'.$student->lastname.', '.$student->firstname.' '.$student->middlename.'</td>
                                                    
                                                    <td style="width:10%; font-size: 10px !important; text-align: left;" width="10%">'.$student->levelname.'</td>
                                                    <td style="width:10%; font-size: 10px !important; text-align: left;" width="10%">'.$student->sectionname.'</td>
                                                    <td style="width:10%; font-size: 10px !important; text-align: left;" width="10%">'.$student->trackname.'</td>
                                                    <td style="width:10%; font-size: 10px !important; text-align: left;" width="10%">'.$student->strandcode.'</td>
                                                    <td style="width:10%; font-size: 10px !important; text-align: left;" width="10%">'.$student->mol.'</td>
                                                    <td style="width:8%; font-size: 10px !important; text-align: left;" width="10%">'.$student->studentstatus.'</td>
                                                </tr>';
                                        $femalecount+=1;   
                                        $numofstudents+=1;   
                                }
                            }
                        }

                            $html.='</tbody>
                    </table><table border="0">
                    <tr style="line-height: 30px;" > 
                    <td></td>
                    </tr>
                    </table>';
                }
            }
        }
        return $html;
    }
    public static function table_4($strands,$filteredstud,$selectedgender)
    {
        $numofstudents = 1;   
        $numofstudentsall = 1;  
        $html = '';
        // return $filteredstud;
        if(count($strands) > 0)
        {
            foreach($strands as $strand)
            {
                $malecount = 1;
                $femalecount = 1;
                if(count(collect($filteredstud)->where('strandid', $strand->id)) > 0)
                {
                    $html.='<table border="1" cellpadding="2">
                        <thead>
                            <tr>
                                <th colspan="10"  style="font-size: 10px !important; font-weight: bold; text-align: center;" >STRAND : '.$strand->strandname.'</th>
                            </tr>
                            <tr>
                                <th style=" font-weight: bold; text-align: center;" width="5%">#</th>
                                <th style=" font-weight: bold; text-align: center;" width="10%" >SID</th>
                                <th style=" font-weight: bold; text-align: center;" width="10%" >LRN</th>
                                <th style=" font-weight: bold; text-align: center;" width="15%">Students</th>
                                
                                <th style=" font-weight: bold; text-align: center;" width="10%" >Grade Level</th>
                                <th style=" font-weight: bold; text-align: center;" width="10%" >Section</th>
                                <th style=" font-weight: bold; text-align: center;" width="10%" >College/<br/>Track</th>
                                <th style=" font-weight: bold; text-align: center;" width="10%" >Course/<br/>Strand</th>
                                <th style=" font-weight: bold; text-align: center;" width="10%" >MOL</th>
                                <th style=" font-weight: bold; text-align: center;" width="10%" >Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="10"  style="background-color: #b3ecff;">MALE</td>
                            </tr>
                        ';
                        foreach($filteredstud as $student)
                        {
                            if($student->strandid == $strand->id)
                            {
                                if(strtolower($student->gender) == 'male')
                                {
                                    if($student->dateenrolled == null)
                                    {
                                        $date = '';
                                    }else{
                                        $date=date_create($student->dateenrolled);
                                        $date = date_format($date,"m/d/Y");
                                    }
                                    $html.='<tr nobr="true">
                                                <td style="width:3%; font-size: 10px !important; text-align: left;" width="5%">'.$malecount.'</td>
                                                <td style="width:8%; font-size: 10px !important; text-align: left;" width="10%">'.$student->sid.'</td>
                                                <td style="width:10%; font-size: 10px !important; text-align: left;" width="10%">'.$student->lrn.'</td>
                                                <td style="width:15%; width:15%; font-size: 10px !important; text-align: left;" width="15%">'.$student->lastname.', '.$student->firstname.' '.$student->middlename.'</td>
                                                
                                                <td style="width:10%; font-size: 10px !important; text-align: left;" width="10%">'.$student->levelname.'</td>
                                                <td style="width:10%; font-size: 10px !important; text-align: left;" width="10%">'.$student->sectionname.'</td>
                                                <td style="width:10%; font-size: 10px !important; text-align: left;" width="10%">'.$student->trackname.'</td>
                                                <td style="width:10%; font-size: 10px !important; text-align: left;" width="10%">'.$student->strandcode.'</td>
                                                <td style="width:10%; font-size: 10px !important; text-align: left;" width="10%">'.$student->mol.'</td>
                                                <td style="width:8%; font-size: 10px !important; text-align: left;" width="10%">'.$student->studentstatus.'</td>
                                            </tr>';
                                    $malecount+=1;   
                                    $numofstudents+=1;   
                                }
                            }
                        }
                        $html.='<tr>
                                    <td colspan="10"  style="background-color: #ffccff;">FEMALE</td>
                                </tr>';
                        foreach($filteredstud as $student)
                        {
                            if($student->strandid == $strand->id)
                            {
                                if(strtolower($student->gender) == 'female')
                                {
                                    if($student->dateenrolled == null)
                                    {
                                        $date = '';
                                    }else{
                                        $date=date_create($student->dateenrolled);
                                        $date = date_format($date,"m/d/Y");
                                    }
                                        $html.='<tr nobr="true">
                                                    <td style="width:3%; font-size: 10px !important; text-align: left;" width="5%">'.$femalecount.'</td>
                                                    <td style="width:8%; font-size: 10px !important; text-align: left;" width="10%">'.$student->sid.'</td>
                                                    <td style="width:10%; font-size: 10px !important; text-align: left;" width="10%">'.$student->lrn.'</td>
                                                    <td style="width:15%; font-size: 10px !important; text-align: left;" width="15%">'.$student->lastname.', '.$student->firstname.' '.$student->middlename.'</td>
                                                    
                                                    <td style="width:10%; font-size: 10px !important; text-align: left;" width="10%">'.$student->levelname.'</td>
                                                    <td style="width:10%; font-size: 10px !important; text-align: left;" width="10%">'.$student->sectionname.'</td>
                                                    <td style="width:10%; font-size: 10px !important; text-align: left;" width="10%">'.$student->trackname.'</td>
                                                    <td style="width:10%; font-size: 10px !important; text-align: left;" width="10%">'.$student->strandcode.'</td>
                                                    <td style="width:10%; font-size: 10px !important; text-align: left;" width="10%">'.$student->mol.'</td>
                                                    <td style="width:8%; font-size: 10px !important; text-align: left;" width="10%">'.$student->studentstatus.'</td>
                                                </tr>';
                                        $femalecount+=1;   
                                        $numofstudents+=1;   
                                }
                            }
                        }

                            $html.='</tbody>
                    </table><table border="0">
                    <tr style="line-height: 30px;" > 
                    <td></td>
                    </tr>
                    </table>';
                }
            }
        }
        return $html;
    }
    public static function table_5($colleges,$filteredstud,$selectedgender)
    {
        $numofstudents = 1;   
        $numofstudentsall = 1;  
        $html = '';
        // return $filteredstud;
        if(count($colleges) > 0)
        {
            foreach($colleges as $college)
            {
                $malecount = 1;
                $femalecount = 1;
                if(count(collect($filteredstud)->where('collegeid', $college->id)) > 0)
                {
                    $html.='<table border="1" cellpadding="2">
                        <thead>
                            <tr>
                                <th colspan="10"  style="font-size: 10px !important; font-weight: bold; text-align: center;" >COLLEGE : '.$college->collegeDesc.'</th>
                            </tr>
                            <tr>
                                <th style=" font-weight: bold; text-align: center;" width="5%">#</th>
                                <th style=" font-weight: bold; text-align: center;" width="10%" >SID</th>
                                <th style=" font-weight: bold; text-align: center;" width="10%" >LRN</th>
                                <th style=" font-weight: bold; text-align: center;" width="15%">Students</th>
                                
                                <th style=" font-weight: bold; text-align: center;" width="10%" >Grade Level</th>
                                <th style=" font-weight: bold; text-align: center;" width="10%" >Section</th>
                                <th style=" font-weight: bold; text-align: center;" width="10%" >College/<br/>Track</th>
                                <th style=" font-weight: bold; text-align: center;" width="10%" >Course/<br/>Strand</th>
                                <th style=" font-weight: bold; text-align: center;" width="10%" >MOL</th>
                                <th style=" font-weight: bold; text-align: center;" width="10%" >Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="10"  style="background-color: #b3ecff;">MALE</td>
                            </tr>
                        ';
                        foreach($filteredstud as $student)
                        {
                            if($student->collegeid == $college->id)
                            {
                                if(strtolower($student->gender) == 'male')
                                {
                                    if($student->dateenrolled == null)
                                    {
                                        $date = '';
                                    }else{
                                        $date=date_create($student->dateenrolled);
                                        $date = date_format($date,"m/d/Y");
                                    }
                                    $html.='<tr nobr="true">
                                                <td style="width:3%; font-size: 10px !important; text-align: left;" width="5%">'.$malecount.'</td>
                                                <td style="width:8%; font-size: 10px !important; text-align: left;" width="10%">'.$student->sid.'</td>
                                                <td style="width:10%; font-size: 10px !important; text-align: left;" width="10%">'.$student->lrn.'</td>
                                                <td style="width:15%; width:15%; font-size: 10px !important; text-align: left;" width="15%">'.$student->lastname.', '.$student->firstname.' '.$student->middlename.'</td>
                                                
                                                <td style="width:10%; font-size: 10px !important; text-align: left;" width="10%">'.$student->levelname.'</td>
                                                <td style="width:10%; font-size: 10px !important; text-align: left;" width="10%">'.$student->sectionname.'</td>
                                                <td style="width:10%; font-size: 10px !important; text-align: left;" width="10%">'.$student->collegename.'</td>
                                                <td style="width:10%; font-size: 10px !important; text-align: left;" width="10%">'.$student->coursename.'</td>
                                                <td style="width:10%; font-size: 10px !important; text-align: left;" width="10%">'.$student->mol.'</td>
                                                <td style="width:8%; font-size: 10px !important; text-align: left;" width="10%">'.$student->studentstatus.'</td>
                                            </tr>';
                                    $malecount+=1;   
                                    $numofstudents+=1;   
                                }
                            }
                        }
                        $html.='<tr>
                                    <td colspan="10"  style="background-color: #ffccff;">FEMALE</td>
                                </tr>';
                        foreach($filteredstud as $student)
                        {
                            if($student->collegeid == $college->id)
                            {
                                if(strtolower($student->gender) == 'female')
                                {
                                    if($student->dateenrolled == null)
                                    {
                                        $date = '';
                                    }else{
                                        $date=date_create($student->dateenrolled);
                                        $date = date_format($date,"m/d/Y");
                                    }
                                        $html.='<tr nobr="true">
                                                    <td style="width:3%; font-size: 10px !important; text-align: left;" width="5%">'.$femalecount.'</td>
                                                    <td style="width:8%; font-size: 10px !important; text-align: left;" width="10%">'.$student->sid.'</td>
                                                    <td style="width:10%; font-size: 10px !important; text-align: left;" width="10%">'.$student->lrn.'</td>
                                                    <td style="width:15%; font-size: 10px !important; text-align: left;" width="15%">'.$student->lastname.', '.$student->firstname.' '.$student->middlename.'</td>
                                                    
                                                    <td style="width:10%; font-size: 10px !important; text-align: left;" width="10%">'.$student->levelname.'</td>
                                                    <td style="width:10%; font-size: 10px !important; text-align: left;" width="10%">'.$student->sectionname.'</td>
                                                    <td style="width:10%; font-size: 10px !important; text-align: left;" width="10%">'.$student->collegename.'</td>
                                                    <td style="width:10%; font-size: 10px !important; text-align: left;" width="10%">'.$student->coursename.'</td>
                                                    <td style="width:10%; font-size: 10px !important; text-align: left;" width="10%">'.$student->mol.'</td>
                                                    <td style="width:8%; font-size: 10px !important; text-align: left;" width="10%">'.$student->studentstatus.'</td>
                                                </tr>';
                                        $femalecount+=1;   
                                        $numofstudents+=1;   
                                }
                            }
                        }

                            $html.='</tbody>
                    </table><table border="0">
                    <tr style="line-height: 30px;" > 
                    <td></td>
                    </tr>
                    </table>';
                }
            }
        }
        return $html;
    }
    public static function table_6($courses,$filteredstud,$selectedgender)
    {
        $numofstudents = 1;   
        $numofstudentsall = 1;  
        $html = '';
        // return $filteredstud;
        if(count($courses) > 0)
        {
            foreach($courses as $course)
            {
                $malecount = 1;
                $femalecount = 1;
                if(count(collect($filteredstud)->where('courseid', $course->id)) > 0)
                {
                    $html.='<table border="1" cellpadding="2">
                        <thead>
                            <tr>
                                <th colspan="10"  style="font-size: 10px !important; font-weight: bold; text-align: center;" >COURSE : '.$course->courseDesc.'</th>
                            </tr>
                            <tr>
                                <th style=" font-weight: bold; text-align: center;" width="5%">#</th>
                                <th style=" font-weight: bold; text-align: center;" width="10%" >SID</th>
                                <th style=" font-weight: bold; text-align: center;" width="10%" >LRN</th>
                                <th style=" font-weight: bold; text-align: center;" width="15%">Students</th>
                                
                                <th style=" font-weight: bold; text-align: center;" width="10%" >Grade Level</th>
                                <th style=" font-weight: bold; text-align: center;" width="10%" >Section</th>
                                <th style=" font-weight: bold; text-align: center;" width="10%" >College/<br/>Track</th>
                                <th style=" font-weight: bold; text-align: center;" width="10%" >Course/<br/>Strand</th>
                                <th style=" font-weight: bold; text-align: center;" width="10%" >MOL</th>
                                <th style=" font-weight: bold; text-align: center;" width="10%" >Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="10"  style="background-color: #b3ecff;">MALE</td>
                            </tr>
                        ';
                        foreach($filteredstud as $student)
                        {
                            if($student->courseid == $course->id)
                            {
                                if(strtolower($student->gender) == 'male')
                                {
                                    if($student->dateenrolled == null)
                                    {
                                        $date = '';
                                    }else{
                                        $date=date_create($student->dateenrolled);
                                        $date = date_format($date,"m/d/Y");
                                    }
                                    $html.='<tr nobr="true">
                                                <td style="width:3%; font-size: 10px !important; text-align: left;" width="5%">'.$malecount.'</td>
                                                <td style="width:8%; font-size: 10px !important; text-align: left;" width="10%">'.$student->sid.'</td>
                                                <td style="width:10%; font-size: 10px !important; text-align: left;" width="10%">'.$student->lrn.'</td>
                                                <td style="width:15%; width:15%; font-size: 10px !important; text-align: left;" width="15%">'.$student->lastname.', '.$student->firstname.' '.$student->middlename.'</td>
                                                
                                                <td style="width:10%; font-size: 10px !important; text-align: left;" width="10%">'.$student->levelname.'</td>
                                                <td style="width:10%; font-size: 10px !important; text-align: left;" width="10%">'.$student->sectionname.'</td>
                                                <td style="width:10%; font-size: 10px !important; text-align: left;" width="10%">'.$student->collegename.'</td>
                                                <td style="width:10%; font-size: 10px !important; text-align: left;" width="10%">'.$student->coursename.'</td>
                                                <td style="width:10%; font-size: 10px !important; text-align: left;" width="10%">'.$student->mol.'</td>
                                                <td style="width:8%; font-size: 10px !important; text-align: left;" width="10%">'.$student->studentstatus.'</td>
                                            </tr>';
                                    $malecount+=1;   
                                    $numofstudents+=1;   
                                }
                            }
                        }
                        $html.='<tr>
                                    <td colspan="10"  style="background-color: #ffccff;">FEMALE</td>
                                </tr>';
                        foreach($filteredstud as $student)
                        {
                            if($student->courseid == $course->id)
                            {
                                if(strtolower($student->gender) == 'female')
                                {
                                    if($student->dateenrolled == null)
                                    {
                                        $date = '';
                                    }else{
                                        $date=date_create($student->dateenrolled);
                                        $date = date_format($date,"m/d/Y");
                                    }
                                        $html.='<tr nobr="true">
                                                    <td style="width:3%; font-size: 10px !important; text-align: left;" width="5%">'.$femalecount.'</td>
                                                    <td style="width:8%; font-size: 10px !important; text-align: left;" width="10%">'.$student->sid.'</td>
                                                    <td style="width:10%; font-size: 10px !important; text-align: left;" width="10%">'.$student->lrn.'</td>
                                                    <td style="width:15%; font-size: 10px !important; text-align: left;" width="15%">'.$student->lastname.', '.$student->firstname.' '.$student->middlename.'</td>
                                                    
                                                    <td style="width:10%; font-size: 10px !important; text-align: left;" width="10%">'.$student->levelname.'</td>
                                                    <td style="width:10%; font-size: 10px !important; text-align: left;" width="10%">'.$student->sectionname.'</td>
                                                    <td style="width:10%; font-size: 10px !important; text-align: left;" width="10%">'.$student->collegename.'</td>
                                                    <td style="width:10%; font-size: 10px !important; text-align: left;" width="10%">'.$student->coursename.'</td>
                                                    <td style="width:10%; font-size: 10px !important; text-align: left;" width="10%">'.$student->mol.'</td>
                                                    <td style="width:8%; font-size: 10px !important; text-align: left;" width="10%">'.$student->studentstatus.'</td>
                                                </tr>';
                                        $femalecount+=1;   
                                        $numofstudents+=1;   
                                }
                            }
                        }

                            $html.='</tbody>
                    </table><table border="0">
                    <tr style="line-height: 30px;" > 
                    <td></td>
                    </tr>
                    </table>';
                }
            }
        }
        return $html;
    }
}
