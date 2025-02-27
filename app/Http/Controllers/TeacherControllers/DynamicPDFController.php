<?php

namespace App\Http\Controllers;
use PDF;
use DB;
use Illuminate\Http\Request;

class DynamicPDFController extends Controller
{
    function index()
    {
     $customer_data = $this->get_customer_data();
     return view('myPDF')->with('customer_data', $customer_data);
    }

    function get_customer_data()
    {
     $customer_data = DB::table('users')
         ->limit(10)
         ->get();
     return $customer_data;
    }

    function pdf()
    {
     $pdf = \App::make('dompdf.wrapper');
     $pdf->loadHTML($this->convert_customer_data_to_html());
     return $pdf->stream();
    }

    function convert_customer_data_to_html()
    {
     $customer_data = $this->get_customer_data();
     $output = '
     <h3 align="center">Customer Data</h3>
     <table width="100%" style="border-collapse: collapse; border: 0px;">
      <tr>
    <th style="border: 1px solid; padding:12px;" width="20%">Name</th>
    <th style="border: 1px solid; padding:12px;" width="30%">Email</th>
    <th style="border: 1px solid; padding:12px;" width="15%">Type</th>
   </tr>
     ';  
     foreach($customer_data as $customer)
     {
      $output .= '
      <tr>
       <td style="border: 1px solid; padding:12px;">'.$customer->name.'</td>
       <td style="border: 1px solid; padding:12px;">'.$customer->email.'</td>
       <td style="border: 1px solid; padding:12px;">'.$customer->type.'</td>
      </tr>
      ';
     }
     $output .= '</table>';
     return $output;
    }
}
