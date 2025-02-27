<style type="text/css">

    @page{size: 21.59cm 27.94cm;}

    .header{
        width: 100%;
        /* table-layout: fixed; */
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
        /* border: 1px solid black; */
    }
    .header td {
        font-size: 12px !important;
        font-weight: bold;
        /* border: 1px solid black; */
    }

    .content{
      font-size: 10px !important;
      font-family: Arial, Helvetica, sans-serif;
    }

    .summaryheader{
      font-size: 20px !important;
      font-family: Arial, Helvetica, sans-serif;
      text-align: center !important;
      width: 100%;
    }

    .table-header{
      border: none;
      text-align: center;
      padding: 0;
      height: 30px;
    }

    .summarysubheader{
      font-size: 15px !important;
      font-family: Arial, Helvetica, sans-serif;
      text-align: center !important;
    }

    .text-center {
      text-align: center;
    }
    .text-right {
      text-align: right;
    }

    .text-bold{
      font-weight: bold;
    }

    tbody td {
        font-size: 11px !important;
    }
    thead th{
      height: 30px !important;

    }

    tfoot td{
      font-size: 12px !important;
    }

    header {
        position: fixed;
        top: -60px;
        left: 0px;
        right: 0px;
        height: 50px;

        /** Extra personal styles **/
        background-color: #03a9f4;
        color: white;
        text-align: center;
        line-height: 35px;
    }

    footer {
        border-top: 2px solid #007bffa8;
        position: fixed; 
        bottom: -60px; 
        left: 0px; 
        right: 0px;
        height: 100px; 


        /** Extra personal styles **/
        /* background-color: #03a9f4; */
        color: black;
        /* text-align: center; */
        line-height: 20px;
    }

    .list{
      border-collapse: collapse;
    }

    .list td, th{
      border: 1px solid #999;
      padding-right: .2rem;
      padding-left: .2rem;
      padding-top: 0px;
      padding-bottom: 0px;
    }
    .page_break { 
      page-break-before: always; 
    }

    .totalrow{
      border-left: hidden !important;
      height: 20px;
      /*padding: 2em;*/
    }


</style>
<div style="margin-left: -23px">
  <table class="header">
    <tr>
      <td>
        {{DB::table('schoolinfo')->first()->schoolname}}
      </td>
    </tr>
    <tr>
      <td>
        DAILY CASH COLLECTION REPORT
      </td>
    </tr>
    <tr>
      <td>
        {{$datenow}}
      </td>
    </tr>
  </table>
</div>
<br>
<div class="content" style="margin-left: -23px">
  <table class="list">
    <thead>
      <tr>
        <th style="width: 70px">
          OR NO.  
        </th>
        <th class="text-center" style="width: 300px">
          Name
        </th>
        <th class="text-center">
          Registration
        </th>
        <th class="text-center">
          Medical
        </th>
        <th class="text-center">
          Insurance
        </th>
        <th class="text-center">
          ID
        </th>
        <th class="text-center">
          Developmental Fee
        </th>
        <th class="text-center page_break">
          Annual Dues
        </th>
        <th class="text-center">
          Security Services
        </th>
        
      </tr>
    </thead>
    <tbody>
      @foreach($list as $row)
        <tr>
          <td class="text-center">{{$row->ornum}}</td>
          <td>{{$row->studname}}</td>

          @if($row->registration == 0)
            <td class="text-right"></td>
          @else
            <td class="text-right">{{$row->registration}}</td>
          @endif

          @if($row->medical == 0)
            <td class="text-right"></td>
          @else
            <td class="text-right">{{$row->medical}}</td>
          @endif

          @if($row->insurance == 0)
            <td class="text-right"></td>
          @else
            <td class="text-right">{{$row->insurance}}</td>
          @endif

          @if($row->id == 0)
            <td class="text-right"></td>
          @else
            <td class="text-right">{{$row->id}}</td>
          @endif

          @if($row->developmentfee == 0)
            <td class="text-right"></td>
          @else
            <td class="text-right">{{$row->developmentfee}}</td>
          @endif

          @if($row->annualdues == 0)
            <td class="text-right"></td>
          @else
            <td class="text-right">{{$row->annualdues}}</td>
          @endif

          @if($row->securityservices == 0)
            <td class="text-right"></td>
          @else
            <td class="text-right">{{$row->securityservices}}</td>
          @endif
        </tr>
      @endforeach
    </tbody>
    <tfoot>
      <tr>
        <td colspan="2" class="text-right text-bold">TOTAL: </td>
        <td class="text-right text-bold">{{$totalregistration}}</td>
        <td class="text-right text-bold">{{$totalmedical}}</td>
        <td class="text-right text-bold">{{$totalinsurance}}</td>
        <td class="text-right text-bold">{{$totalid}}</td>
        <td class="text-right text-bold">{{$totaldevelopmentfee}}</td>
        <td class="text-right text-bold">{{$totalannualdues}}</td>
        <td class="text-right text-bold">{{$totalsecurityservices}}</td>
        
      </tr>
    </tfoot>
  </table>

  <br>

  <div class="page_break">

    <div style="margin-left: -23px; color: white;">
      <table class="header">
        <tr>
          <td>
            {{DB::table('schoolinfo')->first()->schoolname}}
          </td>
        </tr>
        <tr>
          <td>
            DAILY CASH COLLECTION REPORT
          </td>
        </tr>
        <tr>
          <td>
            {{$datenow}}
          </td>
        </tr>
      </table>
    </div>
    <br>

    <table class="list">
      <thead>
        <tr>
          <th class="text-center">
            PTA Maintenance
          </th>
          <th class="text-center">
            ID System
          </th>
          <th class="text-center">
            Internet Fee
          </th>
          <th class="text-center">
            Graduation Fee
          </th>
          <th class="text-center">
            Tuition
          </th>
          <th class="text-center">
            Text Book
          </th>
          <th class="text-center">
            Balance Forwarding
          </th>
          <th class="text-center">
            Certificate
          </th>
          <th class="text-center">
            Others
          </th>
          <th class="text-center">
            Total
          </th>
          <th class="text-center">
            OR NO.
          </th>
        </tr>
      </thead>
      <tbody>
        @foreach($list as $row)
          <tr>
            @if($row->pta == 0)
              <td class="text-right"></td>
            @else
              <td class="text-right">{{$row->pta}}</td>
            @endif
            @if($row->idsystem == 0)
              <td class="text-right"></td>
            @else
              <td class="text-right">{{$row->idsystem}}</td>
            @endif

            @if($row->internetfee == 0)
              <td class="text-right"></td>
            @else
              <td class="text-right">{{$row->internetfee}}</td>
            @endif

            @if($row->graduationfee == 0)
              <td class="text-right"></td>
            @else
              <td class="text-right">{{$row->graduationfee}}</td>
            @endif

            @if($row->tuition == 0)
              <td class="text-right"></td>
            @else
              <td class="text-right">{{$row->tuition}}</td>
            @endif
            
            @if($row->textbook == 0)
              <td class="text-right"></td>
            @else
              <td class="text-right">{{$row->textbook}}</td>
            @endif

            @if($row->balforward == 0)
              <td class="text-right"></td>
            @else
              <td class="text-right">{{$row->balforward}}</td>
            @endif            
            
            @if($row->cert == 0)
              <td class="text-right"></td>
            @else
              <td class="text-right">{{$row->cert}}</td>
            @endif

            @if($row->others == 0)
              <td class="text-right"></td>
            @else
              <td class="text-right">{{$row->others}}</td>
            @endif
            
            @if($row->totals == 0)
              <td class="text-right"></td>
            @else
              <td class="text-right text-bold">{{$row->totals}}</td>
            @endif
            <td class="text-center">{{$row->ornum}}</td>
          </tr>
        @endforeach
      </tbody>
      <tfoot>
        <tr>
          <td class="text-right text-bold">{{$totalpta}}</td>
          <td class="text-right text-bold">{{$totalidsystem}}</td>
          <td class="text-right text-bold">{{$totalinternetfee}}</td>
          <td class="text-right text-bold">{{$totalgraduationfee}}</td>
          <td class="text-right text-bold">{{$totaltuition}}</td>
          <td class="text-right text-bold">{{$totaltextbook}}</td>
          <td class="text-right text-bold">{{$totalbalforward}}</td>
          <td class="text-right text-bold">{{$totalcert}}</td>
          <td class="text-right text-bold">{{$totalothers}}</td>
          <td class="text-right text-bold">{{$grandtotal}}</td>
          <td></td>
        </tr>
        <tr style="height: 150px !important; padding: 2rem!important">
          <td colspan="3" class="text-bold text-right">OR NO.: {{$rangeOR}}</td>
          <td colspan="7" class="text-right text-bold totalrow">TOTAL COLLECTION: {{$grandtotal}}</td>
          <td></td>

        </tr>
      </tfoot>
    </table>  
  </div>
  <div class="page_break">
    <table class="" style="padding: 0;width: 100% !important">
      <thead>
        <tr>
          <th  rowspan="2" style="width: 25%;border: none;"><img src="{{DB::table('schoolinfo')->first()->picurl}}" style="width: 65px; float: right;"></th>
          <th class="text-center table-header" style="width: 50%; border: none">
            <span class="summaryheader">{{DB::table('schoolinfo')->first()->schoolname}}</span>

          </th>
          <th style="width: 25%;" rowspan="2" class="table-header"></th>
        </tr>
        <tr>
            <th class="table-header">
            {{DB::table('schoolinfo')->first()->address}}</th>

        </tr>
      </thead>
    </table>
    <table style="width: 100%">
      <thead>
        <tr>
          <th class="text-center table-header" style="width: 50%; border: none">
            <span style="font-size: 16px !important">DAILY CASH COLLECTION REPORT</span>
          </th>
        </tr>
        <tr>
          <th class="text-center table-header" style="width: 50%; border: none">
            <span>{{$datenow}}</span>
          </th>
        </tr>
      </thead>
    </table>

    <table>
      <thead>
        <tr>
          <th width="100px" style="border: none;">&nbsp;</th>
          <th width="280px" style="border-bottom: solid; border-top: none; border-left: none; border-right: none;">PARTICULARS</th>
          <th width="80px" style="border-bottom: solid; border-top: none; border-left: none; border-right: none;">AMOUNT</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td></td>
          <td style="border-bottom: solid;">TUITION</td>
          <td style="border-bottom: solid;" class="text-right">{{$totaltuition}}</td>
        </tr>
        <tr>
          <td></td>
          <td style="border-bottom: solid;">REGISTRATION</td>
          <td style="border-bottom: solid;" class="text-right">{{$totalregistration}}</td>
        </tr>
        <tr>
          <td></td>
          <td style="border-bottom: solid;">MEDICAL</td>
          <td style="border-bottom: solid;" class="text-right">{{$totalmedical}}</td>
        </tr>
        <tr>
          <td></td>
          <td style="border-bottom: solid;">INSURANCE</td>
          <td style="border-bottom: solid;" class="text-right">{{$totalinsurance}}</td>
        </tr>
        <tr>
          <td></td>
          <td style="border-bottom: solid;">ID CARD</td>
          <td style="border-bottom: solid;" class="text-right">{{$totalidsystem}}</td>
        </tr>
        <tr>
          <td></td>
          <td style="border-bottom: solid;">DEVELOPMENT FEE</td>
          <td style="border-bottom: solid;" class="text-right">{{$totaldevelopmentfee}}</td>
        </tr>
        <tr>
          <td></td>
          <td style="border-bottom: solid;">ANNUAL DUES</td>
          <td style="border-bottom: solid;" class="text-right">{{$totalannualdues}}</td>
        </tr>
        <tr>
          <td></td>
          <td style="border-bottom: solid;">SECURITY SERVICES</td>
          <td style="border-bottom: solid;" class="text-right">{{$totalsecurityservices}}</td>
        </tr>
        <tr>
          <td></td>
          <td style="border-bottom: solid;">PTA MAINTENANCE</td>
          <td style="border-bottom: solid;" class="text-right">{{$totalpta}}</td>
        </tr>
        <tr>
          <td></td>
          <td style="border-bottom: solid;">ID SYSTEM</td>
          <td style="border-bottom: solid;" class="text-right">{{$totalidsystem}}</td>
        </tr>
        <tr>
          <td></td>
          <td style="border-bottom: solid;">INTERNET FEE</td>
          <td style="border-bottom: solid;" class="text-right">{{$totalinternetfee}}</td>
        </tr>
        <tr>
          <td></td>
          <td style="border-bottom: solid;">GRADUATION FEE</td>
          <td style="border-bottom: solid;" class="text-right">{{$totalgraduationfee}}</td>
        </tr>
        <tr>
          <td></td>
          <td style="border-bottom: solid;">TEXT BOOK</td>
          <td style="border-bottom: solid;" class="text-right">{{$totaltextbook}}</td>
        </tr>
        <tr>
          <td></td>
          <td style="border-bottom: solid;">BALANCE FORWARDING</td>
          <td style="border-bottom: solid;" class="text-right">{{$totalbalforward}}</td>
        </tr>
        <tr>
          <td></td>
          <td style="border-bottom: solid;">CERTIFICATE</td>
          <td style="border-bottom: solid;" class="text-right">{{$totalcert}}</td>
        </tr>
        <tr>
          <td></td>
          <td style="border-bottom: solid;">OTHERS</td>
          <td style="border-bottom: solid;" class="text-right">{{$totalothers}}</td>
        </tr>
        <tr>
          <td></td>
          <td class="text-right text-bold" style="height: 80px; border-bottom: solid;"><span>OR No. <u>{{$rangeOR}}</u>&nbsp; &nbsp; &nbsp; &nbsp; </span> <span>Total:</span></td>
          <td class="text-right text-bold" style="height: 80px; border-bottom: solid;"><u>{{$grandtotal}}</u></td>
        </tr>
      </tbody>
    </table>
    <div style="height: 100px">
      &nbsp;
    </div>
    <table>
      <thead>
        <tr>
          <th style="width: 220px; border: none; text-align: left !important;">Prepared by:</th>
          <th style="width: 220px; border: none; text-align: left !important;">Checked by:</th>
          <th style="width: 220px; border: none; text-align: left !important;">Approved by:</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>MS. HELEN M. REYES</td>
          <td>SR. JOAN R. MATULLANO, TDM</td>
          <td>SR. EDITHA D. DISMAS, TDM</td>
        </tr>
        <tr>
          <td style="padding-left: 40px">Cashier</td>
          <td style="padding-left: 60px">Treasurer</td>
          <td style="padding-left: 30px">Directress/Pricipal</td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

