@php
  use Carbon\Carbon;
  use App\Models\Order;
  use App\Models\OrderStatus;
  use App\Models\PurchaseOrder;
  use App\Models\PurchaseOrderStatus;
  use App\Models\Payable;
  use App\Models\PayableStatus;
  use App\Models\DeliveryReceipt;
  use App\Models\DeliveryReceiptStatus;
@endphp

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Reports</title>
  <link type="text/css" href="{{ url('auth/pdf/assets/css/app.css') }}" rel="stylesheet">
  <style type="text/css">
    #compact-table {
      white-space:nowrap;
    }

    #margin-left {
        margin-left: 7px;
    }

    #margin-right {
        margin-right: 7px;
    }

    #space-table {
      margin-left: 7px;
      margin-right: 7px;
    }

    .text-pdf-small {
      font-size: 11px;
    }

    .font-change {
        font-family: "Lucida Console", "Courier New", monospace;
    }

    .heading-text {
      font-size: 30px;
    }

    .no-underline {
      text-decoration: none !important;
    }

    body {
      background: #fff;
    }
  </style>
</head>
<body>
  <br><br>
  <div class="container">
    <a href="{{ route('admin.reports.payables') }}" style="text-decoration: none">
      <button class="btn btn-default" id="margin-right">Go Back</button>
    </a>
    <button type="button" id="btnExport" class="btn btn-success" onclick="fnExcelReport();">Create Excel File</button>
    <button class="btn btn-success" onClick="printdiv('div_print');" id="margin-left">Print page</button>
    <br><br>

    <div id="div_print" class="font-change">
      <img src="{{ url(env('APP_LOGO_WITH_TEXT')) }}" width="180px">
      <hr>
      <h4><b>Payables Report</b></h4>
      <h6>
         <div class="row">
            <div class="col-md-2">
             <strong>From Date:</strong>
            </div>

            <div class="col-md-4">
              @if ($from_date != '*')
                {{ date('m-d-Y',strtotime($from_date)) }}
              @endif
            </div>

            <div class="col-md-2">
              <strong>To Date:</strong>
            </div>

            <div class="col-md-4">
              @if ($to_date != '*')
                {{ date('m-d-Y',strtotime($to_date)) }}
              @endif
            </div>
        </div>
    </h6>

    <br>

    <table class="table table-responsive-sm table-bordered table-striped table-sm" id="mainTable">
      <tbody>
        @foreach ($goods_receipts as $goods_receipt)
          @php
            $purchase_order = PurchaseOrder::find($goods_receipt->purchase_order->id);
            $orders_total = Order::where('purchase_order_id', $purchase_order->id)
                                    ->where('status', OrderStatus::ACTIVE)
                                    ->sum('total');
            $delivery_receipts = DeliveryReceipt::where('goods_receipt_id', $goods_receipt->id)
                                            ->where('status', DeliveryReceiptStatus::ACTIVE)
                                            ->get();
            $paid_total = Payable::where('goods_receipt_id', $goods_receipt->id)
                              ->sum('price');
            $remaining_balance = $orders_total - $paid_total;
          @endphp
          @if (Payable::where('goods_receipt_id', $goods_receipt->id)->exists())
            <thead class="thead-dark">
                <tr>
                  <th id="compact-table">PO#</th>
                  <th id="compact-table">GRPO#</th>
                  <th id="compact-table">DR #</th>
                  <th id="compact-table">Supplier</th>
                  <th id="compact-table">Grand Total</th>
                  <th id="compact-table"colspan="4">Remaining Balance</th>
                </tr>
            </thead>
            <tr>
                <td id="compact-table"><b><a href="{{ route('internals.purchase-orders.view', [$purchase_order->id]) }}">{{ $purchase_order->reference_number }}</a></b></td>
                <td id="compact-table"><b><a href="{{ route('internals.goods-receipts.view', [$goods_receipt->id]) }}">{{ $goods_receipt->reference_number }}</a></b></td>
                <td id="compact-table">
                  @foreach ($delivery_receipts as $delivery_receipt)
                    {{ $delivery_receipt->delivery_receipt_number }};
                  @endforeach
                </td>
                <td id="compact-table">{{ $goods_receipt->purchase_order->supplier->name }}</td>
                <td id="compact-table">{{ number_format($orders_total, 2) }}</td>
                <td id="compact-table" colspan="4">{{ number_format($remaining_balance, 2) }}</td>
            </tr>
            <thead>
                <tr>
                  <th id="compact-table">Check #</th>
                  <th id="compact-table">MOP</th>
                  <th id="compact-table">Paid Amount</th>
                  <th id="compact-table">Date Issued</th>
                  <th id="compact-table">Date Released</th>
                  <th id="compact-table">Due Date</th>
                  <th id="compact-table">Status</th>
                </tr>
            </thead>
            @foreach ($payables as $payable)
              @if ($payable->goods_receipt_id == $goods_receipt->id)
                <tr>
                    <td id="compact-table">{{ $payable->check_number }}</td>
                    <td id="compact-table">{{ $payable->mop }}</td>
                    <td id="compact-table">{{ number_format($payable->price, 2) }}</td>
                    <td id="compact-table">{{ date("M d Y", strtotime($payable->date_issued)) }}</td>
                    <td id="compact-table">{{ date("M d Y", strtotime($payable->date_released)) }}</td>
                    <td id="compact-table">{{ date("M d Y", strtotime($payable->due_date)) }}</td>
                    <td>
                        @if ($payable->status == PayableStatus::PENDING)
                            PENDING
                        @elseif ($payable->status == PayableStatus::PAID)
                            PAID
                        @elseif ($payable->status == PayableStatus::UNPAID)
                            UNPAID
                        @endif
                    </td>
                </tr>
              @endif
            @endforeach
          @endif
        @endforeach
        
        <tr>
          <td colspan="5"></td>
          <td id="compact-table"><b>Grand Total</b></td>
          <td style="width:1%;white-space:nowrap !important; font-weight: 500 !important;"><b>{{ number_format($grand_total, 2) }}</b></td>
        </tr>
      </tbody>
    </table>

    @if(auth()->user()->role == 'Cashier')
      <b>All Transactions By:</b><br>
        {{ auth()->user()->firstname }} {{ auth()->user()->lastname }}<br>
      <b>{{ auth()->user()->role }}</b>
    @endif

  </div>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/gh/linways/table-to-excel@v1.0.4/dist/tableToExcel.js"></script>

  <script type="text/javascript">
    function fnExcelReport()
    {
      TableToExcel.convert(document.getElementById("mainTable"), {
        name: "Payables Report {{ date('m-d-y') }}.xlsx",
        sheet: {
          name: "Main",
        }
      });

      TableToExcel.convert(document.getElementById("summaryTable"), {
        name: "Summary Payables Report {{ date('m-d-y') }}.xlsx",
        sheet: {
          name: "Main",
        }
      });
    }
  </script>

  <script language="javascript">
    function printdiv(printpage)
    {
      var headstr = "<html><head><title></title></head><body>";
      var footstr = "</body>";
      var newstr = document.all.item(printpage).innerHTML;
      var oldstr = document.body.innerHTML;
      document.body.innerHTML = headstr+newstr+footstr;
      window.print();
      document.body.innerHTML = oldstr;
      return false;
    }
  </script>

</body>
</html>