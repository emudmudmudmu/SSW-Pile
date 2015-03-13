@layout('template_construction')

@section('title')
          <div class="title"><p>受取請求履歴</p></div>
@endsection
@section('main')
          <script type="text/javascript">
            var key = {{ $bill->bill_no }};
          </script>
          <div class="receipt_billing_history">
            <table style="width: 400px; margin-left: 130px;" id="bill_header">
              <tr>
                <th style="border-color: #DAE0DB #E5F0FC #E5F0FC #DAE0DB;">請求書No</th>
                <th style="border-color: #DAE0DB #DAE0DB #E5F0FC #E5F0FC; font-weight: bold;" class="code">
                  {{ $bill->bill_no }}
                </th>
              </tr>
              <tr>
                <th style="border-color: #E5F0FC #E5F0FC #E5F0FC #DAE0DB;">請求年月日</th>
                <th style="border-color: #E5F0FC #DAE0DB #E5F0FC #E5F0FC; font-weight: bold;">
                  {{ getLabelYMD($bill->bill_date) }}
                </th>
              </tr>
              <tr>
                <th style="border-color: #E5F0FC #E5F0FC #E5F0FC #DAE0DB;">請求金額</th>
                <th style="border-color: #E5F0FC #DAE0DB #E5F0FC #E5F0FC; font-weight: bold;">
                  \{{ number_format($bill->total + $bill->tax) }}
                </th>
              </tr>
              <tr>
                <th style="border-color: #E5F0FC #E5F0FC #DAE0DB #DAE0DB;">ステータス</th>
                <th style="border-color: #E5F0FC #DAE0DB #E5F0FC #E5F0FC; font-weight: bold;">
                  {{ $bill->status_txt }}
                </th>
              </tr>
              <tr>
                <td style="border-color: #FFFFFF #FFFFFF #DAE0DB #DAE0DB;">
                  <input class="invoice_print" type="button" id="invoice_print" />
                </td>
                <td>
                  <input class="receipt_printing" type="button" id="receipt_printing" />
                </td>
              </tr>

            </table>

            <p class="p_subject">■請求内容内訳</p>
            <table>
              <tr align="center">
                <th>月日</th><th>品名</th><th>数量</th><th>単価</th><th>金額</th><th>納入先</th><th></th>
              </tr>

@foreach ( $bill->meisai as $meisai )
              <tr>
                <td align="center">{{ date_format($meisai->meisai_date_obj, 'n/j') }}</td>
                <td align="left">{{ $meisai->bill_name }}</td>
                <td align="right">{{ $meisai->quantity }}</td>
                <td align="right">\{{ number_format($meisai->price) }}</td>
                <td align="right">\{{ number_format($meisai->sub_total) }}</td>
                <td align="left">
                </td>
                <td align="center">
@if ( $meisai->bill_type != HINMOKU_LICENSE )
                  <a href="{{ URL::to("construction/{$meisai->order_no}/parts_order_history_detail.html") }}">詳細</a>
@endif
                </td>
              </tr>
@endforeach

            </table>
          </div>
@endsection