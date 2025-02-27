<?php

namespace App\Http\Controllers\FinanceControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
class ReportSetupController extends Controller
{
    public function index()
    {
        $configtype = DB::table('acc_rptsetup')
            ->where('deleted','0')
            ->get();

        return view('finance.accounting.reportsetup')
            ->with('configtypes', $configtype);
    }
    public function createreport(Request $request)
    {
        $checkifexists = DB::table('acc_rptsetup')
            ->where('description','like','%'.$request->get('newreport').'%')
            ->count();

        if($checkifexists == 0)
        {
            $reportid = DB::table('acc_rptsetup')
                ->insertGetid([
                    'description' => strtoupper($request->get('newreport')),
                    'createdby'   => auth()->user()->id,
                    'createddatetime'   => date('Y-m-d H:i:s')
                ]);

            return [0,$reportid,strtoupper($request->get('newreport'))];
        }else{
            return [1];
        }
    }
    public function getaccheaders(Request $request)
    {
        $accclass = DB::table('acc_coaclass')
            ->get();

        return collect($accclass);
    }
    public function saveheader(Request $request)
    {
        $checkifexists = DB::table('acc_rptsetupheader')
            ->where('setupid', $request->get('setupid'))
            ->where('classid', $request->get('classid'))
            ->where('deleted','0')
            ->count();
            
        if($checkifexists == 0)
        {
            $reportid = DB::table('acc_rptsetupheader')
                ->insertGetid([
                    'setupid'       => $request->get('setupid'),
                    'classid'       => $request->get('classid'),
                    'createdby'   => auth()->user()->id,
                    'createddatetime'   => date('Y-m-d H:i:s')
                ]);

            $classification = DB::table('acc_coaclass')
                    ->where('id', $request->get('classid'))
                    ->first();

            $classification->headerid = $reportid;
            return [0, collect($classification)];
        }else{
            return [1];
        }
    }
    public function getheaders(Request $request)
    {
        // return $request->all();
        $headers = DB::table('acc_rptsetupheader')
            ->select(
                'acc_rptsetupheader.*',
                'acc_coaclass.classification'
            )
            ->join('acc_coaclass', 'acc_rptsetupheader.classid', '=','acc_coaclass.id')
            ->where('acc_rptsetupheader.setupid', $request->get('setupid'))
            ->where('acc_rptsetupheader.deleted','0')
            ->get();

        return view('finance.accounting.reportssetupincludes.displayheaders')->with('headers',$headers);
    }
    public function getsubs(Request $request)
    {
        $subs = DB::table('acc_rptsetupsub')
            ->select(
                'acc_rptsetupsub.*',
                'acc_coagroup.group'
            )
            ->join('acc_coagroup', 'acc_rptsetupsub.groupid', '=','acc_coagroup.id')
            ->where('acc_rptsetupsub.headerid', $request->get('headerid'))
            ->where('acc_rptsetupsub.deleted','0')
            ->get();

        return view('finance.accounting.reportssetupincludes.displaysubs')->with('subs',$subs)->with('headerid',$request->get('headerid'));
    }
    public function getgroups(Request $request)
    {
        $groups = DB::table('acc_coagroup')
            ->select('id','group')
            ->where('deleted','0')
            ->get();

        return collect($groups);
    }
    public function savesub(Request $request)
    {
        $checkifexists = DB::table('acc_rptsetupsub')
            ->where('groupid', $request->get('groupid'))
            ->where('headerid', $request->get('headerid'))
            ->where('deleted','0')
            ->count();
            
        if($checkifexists == 0)
        {
            $subid = DB::table('acc_rptsetupsub')
                ->insertGetid([
                    'groupid'       => $request->get('groupid'),
                    'headerid'       => $request->get('headerid'),
                    'createdby'   => auth()->user()->id,
                    'createddatetime'   => date('Y-m-d H:i:s')
                ]);

            $groupinfo = DB::table('acc_coagroup')
                    ->where('id', $request->get('groupid'))
                    ->first();

            $groupinfo->subid = $subid;
            return [0, collect($groupinfo)];
        }else{
            return [1];
        }
    }
    public function getdetails(Request $request)
    {
        $details = DB::table('acc_rptsetupdetail')
            ->select(
                'acc_rptsetupdetail.*',
                'acc_map.mapname'
            )
            ->join('acc_map','acc_rptsetupdetail.mapid','=','acc_map.id')
            ->where('acc_rptsetupdetail.subid', $request->get('subid'))
            ->where('acc_rptsetupdetail.deleted','0')
            ->get();

        return view('finance.accounting.reportssetupincludes.displaydetails')->with('details',$details)->with('subid',$request->get('subid'));
    }
    public function getmaps(Request $request)
    {
        $maps = DB::table('acc_map')
            ->where('deleted','0')
            ->get();

            return collect($maps);

    }
    public function savedetail(Request $request)
    {
        $checkifexists = DB::table('acc_rptsetupdetail')
            ->where('subid',$request->get('subid'))
            ->where('mapid',$request->get('mapid'))
            ->where('description','like', '%'.$request->get('description').'%')
            ->where('deleted','0')
            ->count();
        if($checkifexists == 0)
        {
            $detailid = DB::table('acc_rptsetupdetail')
                ->insertGetid([
                    'subid'       => $request->get('subid'),
                    'mapid'       => $request->get('mapid'),
                    'description'       => $request->get('description'),
                    'createdby'   => auth()->user()->id,
                    'createddatetime'   => date('Y-m-d H:i:s')
                ]);

            $mapinfo = DB::table('acc_map')
                    ->where('id', $request->get('mapid'))
                    ->first();

            $mapinfo->detailid = $detailid;
            $mapinfo->description = $request->get('description');
            return [0, collect($mapinfo)];
        }else{
            return [1];
        }
        $maps = DB::table('acc_map')
            ->where('deleted','0')
            ->get();

            return collect($maps);

    }
    public function deleteheader(Request $request)
    {
        
        date_default_timezone_set('Asia/Manila');
        
        DB::table('acc_rptsetupheader')
            ->where('id', $request->get('headerid'))
            ->update([
                'deleted'           => 1,
                'deletedby'         => auth()->user()->id,
                'deleteddatetime'   => date('Y-m-d H:i:s')
            ]);
    }
    public function deletesub(Request $request)
    {
        
        date_default_timezone_set('Asia/Manila');

        DB::table('acc_rptsetupsub')
            ->where('id', $request->get('subid'))
            ->update([
                'deleted'           => 1,
                'deletedby'         => auth()->user()->id,
                'deleteddatetime'   => date('Y-m-d H:i:s')
            ]);
    }
    public function deletedetail(Request $request)
    {
        
        date_default_timezone_set('Asia/Manila');

        DB::table('acc_rptsetupdetail')
            ->where('id', $request->get('detailid'))
            ->update([
                'deleted'           => 1,
                'deletedby'         => auth()->user()->id,
                'deleteddatetime'   => date('Y-m-d H:i:s')
            ]);
    }
    public function getdetailinfo(Request $request)
    {
        
        $detailinfo = DB::table('acc_rptsetupdetail')
            ->where('id', $request->get('detailid'))
            ->first();

        
        $maps = DB::table('acc_map')
                ->where('deleted', 0)
                ->get();
                
        return view('finance.accounting.reportssetupincludes.displaydetailedit')
            ->with('detailinfo', $detailinfo)
            ->with('maps', $maps);
    }
    public function updatedetail(Request $request)
    {
        
        $checkifexists = DB::table('acc_rptsetupdetail')
            ->where('mapid',$request->get('mapid'))
            ->where('description','like', '%'.$request->get('description').'%')
            ->where('deleted','0')
            ->count();
        if($checkifexists == 0)
        {
            DB::table('acc_rptsetupdetail')
                ->where('id', $request->get('detailid'))
                ->update([
                    'description' => $request->get('description'),
                    'mapid' => $request->get('mapid'),
                    'updatedby' => auth()->user()->id,
                    'updateddatetime' => date('Y-m-d H:i:s')
                ]);

            $mapinfo = DB::table('acc_map')
                    ->where('id', $request->get('mapid'))
                    ->first();

            $mapinfo->detailid = $request->get('detailid');
            $mapinfo->description = $request->get('description');
            return [0, collect($mapinfo)];
        }elseif($checkifexists == 1)
        {
            $getinfo = DB::table('acc_rptsetupdetail')
                ->where('mapid',$request->get('mapid'))
                ->where('description','like', '%'.$request->get('description').'%')
                ->where('deleted','0')
                ->first();
            if($getinfo->id == $request->get('detailid'))
            {
                DB::table('acc_rptsetupdetail')
                    ->where('id', $request->get('detailid'))
                    ->update([
                        'description' => $request->get('description'),
                        'mapid' => $request->get('mapid'),
                        'updatedby' => auth()->user()->id,
                        'updateddatetime' => date('Y-m-d H:i:s')
                    ]);
    
                $mapinfo = DB::table('acc_map')
                        ->where('id', $request->get('mapid'))
                        ->first();
    
                $mapinfo->detailid = $request->get('detailid');
                $mapinfo->description = $request->get('description');
                return [2, collect($mapinfo)];
            }else{
                return [1];
            }
        }else{
            return [1];
        }
    }
    public function setupexport(Request $request)
    {
        $setupinfo = DB::table('acc_rptsetup')
            ->where('id', $request->get('setupid'))
            ->first();


        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();;
        $sheet = $spreadsheet->getActiveSheet();
        $borderstyle = [
            // 'alignment' => [
            //     'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
            // ],
            'borders' => [
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ]
        ];
        
        $schoolinfo = DB::table('schoolinfo')
            ->select(
                'schoolinfo.schoolid',
                'schoolinfo.schoolname',
                'schoolinfo.authorized',
                'refcitymun.citymunDesc as division',
                'schoolinfo.district',
                'schoolinfo.address',
                'refregion.regDesc as region'
            )
            ->join('refregion','schoolinfo.region','=','refregion.regCode')
            ->join('refcitymun','schoolinfo.division','=','refcitymun.citymunCode')
            ->first();
        
        $sheet->mergeCells('B3:E3');
        $sheet->setCellValue('B3',$schoolinfo->schoolname);
        $sheet->mergeCells('B4:E4');
        $sheet->setCellValue('B4',$setupinfo->description);

        $headers = DB::table('acc_rptsetupheader')
                ->select('acc_rptsetupheader.*','acc_coaclass.classification as description' )
                ->join('acc_coaclass','acc_rptsetupheader.classid','=','acc_coaclass.id')
                ->where('acc_rptsetupheader.setupid', $request->get('setupid'))
                ->where('acc_rptsetupheader.deleted','0')
                ->get();

        $sheet->setCellValue('H7','Balances');
        $sheet->getStyle('H7')->getFont()->setBold(true);
        $cellstart = 8;

        if(count($headers)>0)
        {
            foreach($headers as $header)
            {
                $subheaders = DB::table('acc_rptsetupsub')
                    ->select('acc_rptsetupsub.*','acc_coagroup.group as description' )
                    ->join('acc_coagroup','acc_rptsetupsub.groupid','=','acc_coagroup.id')
                    ->where('acc_rptsetupsub.headerid', $header->id)
                    ->where('acc_rptsetupsub.deleted','0')
                    ->where('acc_coagroup.deleted','0')
                    ->get();

                $sheet->setCellValue('C'.$cellstart,$header->description);
                $sheet->getStyle('C'.$cellstart)->getFont()->setBold(true);
                $cellstart+=1;

                $header->subheaders = $subheaders;

                if(count($subheaders)>0)
                {
                    foreach($subheaders as $subheader)
                    {
                        $sheet->setCellValue('C'.$cellstart,$subheader->description);
                        $cellstart+=1;
                        $details = DB::table('acc_rptsetupdetail')
                            ->where('subid', $subheader->id)
                            ->where('deleted','0')
                            ->get();

                        $subheader->details = $details;

                        if(count($details)>0)
                        {
                            foreach($details as $detail)
                            {
                                $sheet->mergeCells('D'.$cellstart.':f'.$cellstart);
                                $sheet->setCellValue('D'.$cellstart,$detail->description);
                                $cellstart+=1;

                            }
                        }

                    }
                }
                $sheet->getStyle('C'.$cellstart.':K'.$cellstart)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $cellstart+=1;
            }
        }
        

        
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="'.$setupinfo->description.'.xlsx"');
        $writer->save("php://output");
    }
    
}
