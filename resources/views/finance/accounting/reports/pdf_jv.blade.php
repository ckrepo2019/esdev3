<!DOCTYPE html>
<html>
  <head>
    <style type="text/css">
      body {
        font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
        font-size: 12px;
      }
      .text-bold{
        font-style: bold;
      }
      .header{
        font-size: medium;
      }
      .float-right{
        float: right;
        display: inline-block;
      }
      .mt-1{
        margin-top: 1em;
      }
      .mt-2{
        margin-top: 2em;
      }
      .mt-3{
        margin-top: 3em;
      }
      .text-right {
        text-align: right !important;
      }
      .text-center {
         text-align: center !important;
      }
      .b-1{
        border: solid !important;
      }

      .btop{
        border-top: solid;
      }

      .table {
  width: 100%;
  margin-bottom: 1rem;
  color: #212529;
  background-color: transparent;
}

.table th,
.table td {
  padding: 0.30rem;
  vertical-align: top;
  /* border-top: 0px solid #dee2e6; */
}

.table thead th {
  vertical-align: bottom;
  border-bottom: 2px solid #dee2e6;
}

.table tfoot th {
  vertical-align: bottom;
  /* border-top: 2px solid #dee2e6; */
}

.table tbody + tbody {
  /* border-top: 2px solid #dee2e6; */
}

.table-sm th,
.table-sm td {
  padding: 0.3rem;
}

.table-bordered {
  border: 1px solid #dee2e6;
}

.table-bordered th,
.table-bordered td {
  border: 1px solid #dee2e6;
}

.table-bordered thead th,
.table-bordered thead td {
  border-bottom-width: 2px;
}

.table-borderless th,
.table-borderless td,
.table-borderless thead th,
.table-borderless tbody + tbody {
  border: 0;
}

.table-striped tbody tr:nth-of-type(odd) {
  background-color: rgba(0, 0, 0, 0.05);
}

.table-hover tbody tr:hover {
  color: #212529;
  background-color: rgba(0, 0, 0, 0.075);
}

.table-primary,
.table-primary > th,
.table-primary > td {
  background-color: #b8daff;
}

.table-primary th,
.table-primary td,
.table-primary thead th,
.table-primary tbody + tbody {
  border-color: #7abaff;
}

.table-hover .table-primary:hover {
  background-color: #9fcdff;
}

.table-hover .table-primary:hover > td,
.table-hover .table-primary:hover > th {
  background-color: #9fcdff;
}

.table-secondary,
.table-secondary > th,
.table-secondary > td {
  background-color: #d6d8db;
}

.table-secondary th,
.table-secondary td,
.table-secondary thead th,
.table-secondary tbody + tbody {
  border-color: #b3b7bb;
}

.table-hover .table-secondary:hover {
  background-color: #c8cbcf;
}

.table-hover .table-secondary:hover > td,
.table-hover .table-secondary:hover > th {
  background-color: #c8cbcf;
}

.table-success,
.table-success > th,
.table-success > td {
  background-color: #c3e6cb;
}

.table-success th,
.table-success td,
.table-success thead th,
.table-success tbody + tbody {
  border-color: #8fd19e;
}

.table-hover .table-success:hover {
  background-color: #b1dfbb;
}

.table-hover .table-success:hover > td,
.table-hover .table-success:hover > th {
  background-color: #b1dfbb;
}

.table-info,
.table-info > th,
.table-info > td {
  background-color: #bee5eb;
}

.table-info th,
.table-info td,
.table-info thead th,
.table-info tbody + tbody {
  border-color: #86cfda;
}

.table-hover .table-info:hover {
  background-color: #abdde5;
}

.table-hover .table-info:hover > td,
.table-hover .table-info:hover > th {
  background-color: #abdde5;
}

.table-warning,
.table-warning > th,
.table-warning > td {
  background-color: #ffeeba;
}

.table-warning th,
.table-warning td,
.table-warning thead th,
.table-warning tbody + tbody {
  border-color: #ffdf7e;
}

.table-hover .table-warning:hover {
  background-color: #ffe8a1;
}

.table-hover .table-warning:hover > td,
.table-hover .table-warning:hover > th {
  background-color: #ffe8a1;
}

.table-danger,
.table-danger > th,
.table-danger > td {
  background-color: #f5c6cb;
}

.table-danger th,
.table-danger td,
.table-danger thead th,
.table-danger tbody + tbody {
  border-color: #ed969e;
}

.table-hover .table-danger:hover {
  background-color: #f1b0b7;
}

.table-hover .table-danger:hover > td,
.table-hover .table-danger:hover > th {
  background-color: #f1b0b7;
}

.table-light,
.table-light > th,
.table-light > td {
  background-color: #fdfdfe;
}

.table-light th,
.table-light td,
.table-light thead th,
.table-light tbody + tbody {
  border-color: #fbfcfc;
}

.table-hover .table-light:hover {
  background-color: #ececf6;
}

.table-hover .table-light:hover > td,
.table-hover .table-light:hover > th {
  background-color: #ececf6;
}

.table-dark,
.table-dark > th,
.table-dark > td {
  background-color: #c6c8ca;
}

.table-dark th,
.table-dark td,
.table-dark thead th,
.table-dark tbody + tbody {
  border-color: #95999c;
}

.table-hover .table-dark:hover {
  background-color: #b9bbbe;
}

.table-hover .table-dark:hover > td,
.table-hover .table-dark:hover > th {
  background-color: #b9bbbe;
}

.table-active,
.table-active > th,
.table-active > td {
  background-color: rgba(0, 0, 0, 0.075);
}

.table-hover .table-active:hover {
  background-color: rgba(0, 0, 0, 0.075);
}

.table-hover .table-active:hover > td,
.table-hover .table-active:hover > th {
  background-color: rgba(0, 0, 0, 0.075);
}

.table .thead-dark th {
  color: #ffffff;
  background-color: #212529;
  border-color: #383f45;
}

.table .thead-light th {
  color: #495057;
  background-color: #e9ecef;
  border-color: #dee2e6;
}

.table-dark {
  color: #ffffff;
  background-color: #212529;
}

.table-dark th,
.table-dark td,
.table-dark thead th {
  border-color: #383f45;
}

.table-dark.table-bordered {
  border: 0;
}

.table-dark.table-striped tbody tr:nth-of-type(odd) {
  background-color: rgba(255, 255, 255, 0.05);
}

.table-dark.table-hover tbody tr:hover {
  color: #ffffff;
  background-color: rgba(255, 255, 255, 0.075);
}

@media (max-width: 575.98px) {
  .table-responsive-sm {
    display: block;
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
  }
  .table-responsive-sm > .table-bordered {
    border: 0;
  }
}

@media (max-width: 767.98px) {
  .table-responsive-md {
    display: block;
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
  }
  .table-responsive-md > .table-bordered {
    border: 0;
  }
}

@media (max-width: 991.98px) {
  .table-responsive-lg {
    display: block;
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
  }
  .table-responsive-lg > .table-bordered {
    border: 0;
  }
}

@media (max-width: 1199.98px) {
  .table-responsive-xl {
    display: block;
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
  }
  .table-responsive-xl > .table-bordered {
    border: 0;
  }
}

.table-responsive {
  display: block;
  width: 100%;
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;
}

.table-responsive > .table-bordered {
  border: 0;
}

    </style>

    <title>Income Statement</title>
  </head>
  <body>
      @php
        $sinfo = db::table('schoolinfo')
          ->first();
      @endphp
      <div>
        
      </div>

      <table style="width: 100%;" cellpadding = 0 cellspacing = 0>
        <tr>
          <td style="text-align: center; font-weight: bold; font-size:11; width: 100%">
            {{$sinfo->schoolname}}
          </td>
        </tr>
        <tr>
          <td style="width: 100%; text-align: center;">
            {{$sinfo->address}}
          </td>
        </tr>
        <tr><td>&nbsp;</td></tr>
        <tr>
          <td style="width: 100%; text-align: center; font-weight: bold; font-size: 11">
            Journal Voucher
          </td>
        </tr>
        <tr>
          <td style="text-align: center">
            Date:  {{date_format(date_create($data['transdate']), 'm/d/Y')}}
          </td>
        </tr>
        <tr>
          <td style="text-align: right">Date Printed: {{date_format(date_create(App\FinanceModel::getServerDateTime()), 'm/d/Y')}}</td>
        </tr>
        <tr>
          <td style="font-size: 14; font-weight: lighter;">No.: {{$data['refid']}}</td>
        </tr>
      </table>
      <br><br>


      <div class="" style="margin-left: 0em">
        <table cellspacing="0" cellpadding="0" class="table" style="width: 100%">
          <thead>
            <tr>
              <th colspan="2" style="text-align: center;">Account</th>
              <th>Debit</th>
              <th>Credit</th>
            </tr>
          </thead>
          <tbody>
            @foreach($detail as $jv)
              @php
                $jvdebit = '';
                $jvcredit = '';

                if($jv->debit == 0)
                {
                  $jvdebit = '';
                }
                else{
                  $jvdebit = number_format($jv->debit, 2);
                }

                if($jv->credit == 0)
                {
                  $jvcredit = '';
                }
                else{
                  $jvcredit = number_format($jv->credit, 2);
                }
              @endphp
              <tr>
                <td colspan="" class="" style="border-top: solid 1px">{{$jv->code}}</td>
                <td colspan="" class="" style="border-top: solid 1px">{{$jv->account}}</td>
                <td colspan="" class="text-right" style="border-top: solid 1px">{{$jvdebit}}</td>
                <td colspan="" class="text-right" style="border-top: solid 1px">{{$jvcredit}}</td>
              </tr>
            @endforeach
          </tbody>
          <tfoot>
            <tr>
                <td colspan="2" class="text-right text-bold" style="border-top: solid 1px; border-bottom: solid 2px;">TOTAL: </td>
                <td colspan="" class="text-right text-bold" style="border-top: solid 1px; border-bottom: solid 2px;">{{$data['isdebit']}}</td>
                <td colspan="" class="text-right text-bold" style="border-top: solid 1px; border-bottom: solid 2px;">{{$data['iscredit']}}</td>
              </tr>
          </tfoot>
        </table>

        <table cellspacing="0" cellpadding="0" class="table" style="width: 100%">
          <tr>
            <td style="font-weight: bold;">Remarks</td>
          </tr>
          <tr>
            <td>{{$data['remarks']}}</td>
          </tr>
        </table>

        <br><br>

        @php
            $sig = db::table('finance_sigs')
                ->where('report_description', 'JOURNAL VOUCHER')
                ->first();
        @endphp
        <div style="line-height: 5px;"></div>
        <div style="line-height: 5px; font-weight: bold; font-size: 9; ">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$sig->title_1}}:
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            {{-- &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; --}}

            {{$sig->title_2}}:
        </div>
        <br><br><br>
        <div style="line-height: 5px;"></div>
        <table style="width: 100%; table-layout: fixed; font-size: 9; font-weight: bold;">
            <tr>
                <td style="width: 10%;"></td>
                <td style="width: 30%; border-bottom: 1px solid black; text-align: center">{{$sig->sig_1}}</td>
                <td style="width: 20%;"></td>
                <td style="width: 30%; border-bottom: 1px solid black; text-align: center;">{{$sig->sig_2}}</td>
                <td style="width: 10%;"></td>
            </tr>
            <tr>
                <td style="width: 10%;"></td>
                <td style="width: 30%; text-align: center;">{{$sig->designation_1}}</td>
                <td style="width: 20%;"></td>
                <td style="width: 30%; text-align: center;">{{$sig->designation_2}}</td>
                <td style="width: 10%;"></td>
            </tr>
            {{-- <tr>
                <td style="width: 10%;"></td>
                <td style="width: 30%; text-align: center;"></td>
                <td style="width: 20%; text-align: right;">CHECKED BY:</td>
                <td style="width: 30%; text-align: center;"></td>
                <td style="width: 10%;"></td>
            </tr>
            <tr>
                <td style="width: 10%;"></td>
                <td style="width: 30%;"></td>
                <td style="width: 20%;"></td>
                <td style="width: 30%; border-bottom: 1px solid black;"></td>
                <td style="width: 10%;"></td>
            </tr>
            <tr>
                <td style="width: 10%;"></td>
                <td style="width: 30%; text-align: center;"></td>
                <td style="width: 20%;"></td>
                <td style="width: 30%; text-align: center;">Director of Finance</td>
                <td style="width: 10%;"></td>
            </tr> --}}
        </table>

        
      </div>

    
    
        
  </body>
</html>