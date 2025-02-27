<?php

namespace App\Http\Controllers\DirectorControllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;
use DB;
use Session;
use \Carbon\Carbon;
use \Carbon\CarbonPeriod;
class DirectorFinanceReportsController extends Controller
{
    public function cashiertransactionsindex(Request $request)
    {
        $schoolInfo = DB::table('schoollist')->where('id',Session::get('schoolid'))->first();
        
        if($schoolInfo->islocal == 0)
        {
            Config::set("database.connections.mysql", [
                'driver' => 'mysql',
                "host" => env('DB_HOST', '141.164.36.7'),
                "database" => $schoolInfo->db,
                "username" => "ckgroup_dev",
                "password" => "Sels2019",
                "port" => '3306',
                'charset'   => 'utf8',
                'collation' => 'utf8_unicode_ci',
                'prefix'    => '',
                'strict'    => false,
            ]);
            DB::purge('mysql');
        }
        $guzzleClient = new \GuzzleHttp\Client(array( 'curl' => array( CURLOPT_SSL_VERIFYPEER => false, CURLOPT_HEADER => true,), ));
        try{
            $url = DB::table('schoollist')->where('id',Session::get('schoolid'))->first();
            $schoolyears = $guzzleClient->request('GET', $url->eslink.'/passData?action=getschoolyears');
            $schoolyears = $schoolyears->getBody()->getContents();
            $schoolyears =  json_decode($schoolyears, true);
            $schoolyears = json_decode(json_encode($schoolyears), FALSE);
            
            $semesters = $guzzleClient->request('GET', $url->eslink.'/passData?action=getsemesters');
            $semesters = $semesters->getBody()->getContents();
            $semesters =  json_decode($semesters, true);
            $semesters = json_decode(json_encode($semesters), FALSE);

            $terminals = $guzzleClient->request('GET', $url->eslink.'/passData?action=getterminals');
            $terminals = $terminals->getBody()->getContents();
            $terminals =  json_decode($terminals, true);
            $terminals = json_decode(json_encode($terminals), FALSE);

            $paymentoptions = $guzzleClient->request('GET', $url->eslink.'/passData?action=getpaymenttypes');
            $paymentoptions = $paymentoptions->getBody()->getContents();
            $paymentoptions =  json_decode($paymentoptions, true);
            $paymentoptions = json_decode(json_encode($paymentoptions), FALSE);
            
            if($request->has('syid'))
            {
                $syid = $request->get('syid');
            }else{
                $syid = collect($schoolyears)->where('isactive','1')->first()->id;
            }
            if($request->has('semid'))
            {
                $semid = $request->get('semid');
            }else{
                $semid = collect($semesters)->where('isactive','1')->first()->id;
            }
        }catch(\Exception $error)
        {
            $terminals = DB::table('chrngterminals')
                ->get();

            $paymentoptions = DB::table('paymenttype')
                ->where('deleted','0')
                ->get();

            $schoolyears = DB::table('sy')
                ->get();
            $semesters = DB::table('semester')
                ->get();
            if($request->has('syid'))
            {
                $syid = $request->get('syid');
            }else{
                $syid = DB::table('sy')
                    ->where('isactive','1')
                    ->first()
                    ->id;
            }
            if($request->has('semid'))
            {
                $semid = $request->get('semid');
            }else{
                $semid = DB::table('semester')
                    ->where('isactive','1')
                    ->first()
                    ->id;
            }
        }
        if(!$request->has('action'))
        {
            return view('adminITPortal.pages.finance.cashiertransactions')
                ->with('terminals', $terminals)
                ->with('paymentoptions', $paymentoptions)
                ->with('schoolyears', $schoolyears)
                ->with('semesters', $semesters);
                // ->with('months', $months);
        }else{
        }
    }
    public function accountreceivablesindex(Request $request)
    {
        $schoolInfo = DB::table('schoollist')->where('id',Session::get('schoolid'))->first();
        
        if($schoolInfo->islocal == 0)
        {
            Config::set("database.connections.mysql", [
                'driver' => 'mysql',
                "host" => env('DB_HOST', '141.164.36.7'),
                "database" => $schoolInfo->db,
                "username" => "ckgroup_dev",
                "password" => "Sels2019",
                "port" => '3306',
                'charset'   => 'utf8',
                'collation' => 'utf8_unicode_ci',
                'prefix'    => '',
                'strict'    => false,
            ]);
            DB::purge('mysql');
        }
        $guzzleClient = new \GuzzleHttp\Client(array( 'curl' => array( CURLOPT_SSL_VERIFYPEER => false, CURLOPT_HEADER => true,), ));
        try{
            $url = DB::table('schoollist')->where('id',Session::get('schoolid'))->first();
            $schoolyears = $guzzleClient->request('GET', $url->eslink.'/passData?action=getschoolyears');
            $schoolyears = $schoolyears->getBody()->getContents();
            $schoolyears =  json_decode($schoolyears, true);
            $schoolyears = json_decode(json_encode($schoolyears), FALSE);
            
            $semesters = $guzzleClient->request('GET', $url->eslink.'/passData?action=getsemesters');
            $semesters = $semesters->getBody()->getContents();
            $semesters =  json_decode($semesters, true);
            $semesters = json_decode(json_encode($semesters), FALSE);

            $terminals = $guzzleClient->request('GET', $url->eslink.'/passData?action=getterminals');
            $terminals = $terminals->getBody()->getContents();
            $terminals =  json_decode($terminals, true);
            $terminals = json_decode(json_encode($terminals), FALSE);

            $paymentoptions = $guzzleClient->request('GET', $url->eslink.'/passData?action=getpaymenttypes');
            $paymentoptions = $paymentoptions->getBody()->getContents();
            $paymentoptions =  json_decode($paymentoptions, true);
            $paymentoptions = json_decode(json_encode($paymentoptions), FALSE);
            
            if($request->has('syid'))
            {
                $syid = $request->get('syid');
            }else{
                $syid = collect($schoolyears)->where('isactive','1')->first()->id;
            }
            if($request->has('semid'))
            {
                $semid = $request->get('semid');
            }else{
                $semid = collect($semesters)->where('isactive','1')->first()->id;
            }
        }catch(\Exception $error)
        {
            $terminals = DB::table('chrngterminals')
                ->get();

            $paymentoptions = DB::table('paymenttype')
                ->where('deleted','0')
                ->get();

            $schoolyears = DB::table('sy')
                ->get();
            $semesters = DB::table('semester')
                ->get();
            if($request->has('syid'))
            {
                $syid = $request->get('syid');
            }else{
                $syid = DB::table('sy')
                    ->where('isactive','1')
                    ->first()
                    ->id;
            }
            if($request->has('semid'))
            {
                $semid = $request->get('semid');
            }else{
                $semid = DB::table('semester')
                    ->where('isactive','1')
                    ->first()
                    ->id;
            }
        }
        if(!$request->has('action'))
        {
            return view('adminITPortal.pages.finance.accountreceivablesindex')
                ->with('terminals', $terminals)
                ->with('paymentoptions', $paymentoptions)
                ->with('schoolyears', $schoolyears)
                ->with('semesters', $semesters);
                // ->with('months', $months);
        }else{
        }
    }
    public function collectionsindex(Request $request)
    {
        $schoolInfo = DB::table('schoollist')->where('id',Session::get('schoolid'))->first();
        
        if($schoolInfo->islocal == 0)
        {
            Config::set("database.connections.mysql", [
                'driver' => 'mysql',
                "host" => env('DB_HOST', '141.164.36.7'),
                "database" => $schoolInfo->db,
                "username" => "ckgroup_dev",
                "password" => "Sels2019",
                "port" => '3306',
                'charset'   => 'utf8',
                'collation' => 'utf8_unicode_ci',
                'prefix'    => '',
                'strict'    => false,
            ]);
            DB::purge('mysql');
        }
        $guzzleClient = new \GuzzleHttp\Client(array( 'curl' => array( CURLOPT_SSL_VERIFYPEER => false, CURLOPT_HEADER => true,), ));
        try{
            $url = DB::table('schoollist')->where('id',Session::get('schoolid'))->first();
            $schoolyears = $guzzleClient->request('GET', $url->eslink.'/passData?action=getschoolyears');
            $schoolyears = $schoolyears->getBody()->getContents();
            $schoolyears =  json_decode($schoolyears, true);
            $schoolyears = json_decode(json_encode($schoolyears), FALSE);
            
            $semesters = $guzzleClient->request('GET', $url->eslink.'/passData?action=getsemesters');
            $semesters = $semesters->getBody()->getContents();
            $semesters =  json_decode($semesters, true);
            $semesters = json_decode(json_encode($semesters), FALSE);

            $terminals = $guzzleClient->request('GET', $url->eslink.'/passData?action=getterminals');
            $terminals = $terminals->getBody()->getContents();
            $terminals =  json_decode($terminals, true);
            $terminals = json_decode(json_encode($terminals), FALSE);

            $paymentoptions = $guzzleClient->request('GET', $url->eslink.'/passData?action=getpaymenttypes');
            $paymentoptions = $paymentoptions->getBody()->getContents();
            $paymentoptions =  json_decode($paymentoptions, true);
            $paymentoptions = json_decode(json_encode($paymentoptions), FALSE);
            
            if($request->has('syid'))
            {
                $syid = $request->get('syid');
            }else{
                $syid = collect($schoolyears)->where('isactive','1')->first()->id;
            }
            if($request->has('semid'))
            {
                $semid = $request->get('semid');
            }else{
                $semid = collect($semesters)->where('isactive','1')->first()->id;
            }
        }catch(\Exception $error)
        {
            $terminals = DB::table('chrngterminals')
                ->get();

            $paymentoptions = DB::table('paymenttype')
                ->where('deleted','0')
                ->get();

            $schoolyears = DB::table('sy')
                ->get();
            $semesters = DB::table('semester')
                ->get();
            if($request->has('syid'))
            {
                $syid = $request->get('syid');
            }else{
                $syid = DB::table('sy')
                    ->where('isactive','1')
                    ->first()
                    ->id;
            }
            if($request->has('semid'))
            {
                $semid = $request->get('semid');
            }else{
                $semid = DB::table('semester')
                    ->where('isactive','1')
                    ->first()
                    ->id;
            }
        }
        if(!$request->has('action'))
        {
            return view('adminITPortal.pages.finance.collectionsindex')
                ->with('terminals', $terminals)
                ->with('paymentoptions', $paymentoptions)
                ->with('schoolyears', $schoolyears)
                ->with('semesters', $semesters);
                // ->with('months', $months);
        }else{
        }

    }
    public function expensesindex(Request $request)
    {
        $schoolInfo = DB::table('schoollist')->where('id',Session::get('schoolid'))->first();
        
        if($schoolInfo->islocal == 0)
        {
            Config::set("database.connections.mysql", [
                'driver' => 'mysql',
                "host" => env('DB_HOST', '141.164.36.7'),
                "database" => $schoolInfo->db,
                "username" => "ckgroup_dev",
                "password" => "Sels2019",
                "port" => '3306',
                'charset'   => 'utf8',
                'collation' => 'utf8_unicode_ci',
                'prefix'    => '',
                'strict'    => false,
            ]);
            DB::purge('mysql');
        }
        $guzzleClient = new \GuzzleHttp\Client(array( 'curl' => array( CURLOPT_SSL_VERIFYPEER => false, CURLOPT_HEADER => true,), ));
        try{
            $url = DB::table('schoollist')->where('id',Session::get('schoolid'))->first();
            $schoolyears = $guzzleClient->request('GET', $url->eslink.'/passData?action=getschoolyears');
            $schoolyears = $schoolyears->getBody()->getContents();
            $schoolyears =  json_decode($schoolyears, true);
            $schoolyears = json_decode(json_encode($schoolyears), FALSE);
            
            $semesters = $guzzleClient->request('GET', $url->eslink.'/passData?action=getsemesters');
            $semesters = $semesters->getBody()->getContents();
            $semesters =  json_decode($semesters, true);
            $semesters = json_decode(json_encode($semesters), FALSE);

            $terminals = $guzzleClient->request('GET', $url->eslink.'/passData?action=getterminals');
            $terminals = $terminals->getBody()->getContents();
            $terminals =  json_decode($terminals, true);
            $terminals = json_decode(json_encode($terminals), FALSE);

            $paymentoptions = $guzzleClient->request('GET', $url->eslink.'/passData?action=getpaymenttypes');
            $paymentoptions = $paymentoptions->getBody()->getContents();
            $paymentoptions =  json_decode($paymentoptions, true);
            $paymentoptions = json_decode(json_encode($paymentoptions), FALSE);
            
            if($request->has('syid'))
            {
                $syid = $request->get('syid');
            }else{
                $syid = collect($schoolyears)->where('isactive','1')->first()->id;
            }
            if($request->has('semid'))
            {
                $semid = $request->get('semid');
            }else{
                $semid = collect($semesters)->where('isactive','1')->first()->id;
            }
        }catch(\Exception $error)
        {
            $terminals = DB::table('chrngterminals')
                ->get();

            $paymentoptions = DB::table('paymenttype')
                ->where('deleted','0')
                ->get();

            $schoolyears = DB::table('sy')
                ->get();
            $semesters = DB::table('semester')
                ->get();
            if($request->has('syid'))
            {
                $syid = $request->get('syid');
            }else{
                $syid = DB::table('sy')
                    ->where('isactive','1')
                    ->first()
                    ->id;
            }
            if($request->has('semid'))
            {
                $semid = $request->get('semid');
            }else{
                $semid = DB::table('semester')
                    ->where('isactive','1')
                    ->first()
                    ->id;
            }
        }
        if(!$request->has('action'))
        {
            return view('adminITPortal.pages.finance.expensesindex')
                ->with('terminals', $terminals)
                ->with('paymentoptions', $paymentoptions)
                ->with('schoolyears', $schoolyears)
                ->with('semesters', $semesters);
                // ->with('months', $months);
        }else{
        }
    }
}
