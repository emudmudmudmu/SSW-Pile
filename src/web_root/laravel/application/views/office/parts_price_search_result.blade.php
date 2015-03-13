@layout('template_office')

@section('title')
          <div class="title"><p>パーツ代金精算</p></div>
@endsection
@section('main')
            <div class="parts_price_search_result">
            <table style="width: 400px; margin-left: 130px;" id="bill_header">
              <tr>
                <th style="border-color: #DAE0DB #E5F0FC #E5F0FC #DAE0DB;">会社名</th>
                <th style="border-color: #DAE0DB #DAE0DB #E5F0FC #E5F0FC; font-weight: bold;">
                  {{ $company_name }}
                </th>
              </tr>
              <tr>
                <th style="border-color: #E5F0FC #E5F0FC #E5F0FC #DAE0DB;">精算年月</th>
                <th style="border-color: #E5F0FC #DAE0DB #E5F0FC #E5F0FC; font-weight: bold;">
                  <span id="bill_nen">{{ $input['yyyy'] }}</span>年 <span id="bill_tuki">{{ $input['mm'] }}</span>月
                </th>
              </tr>
              <tr>
                <th style="border-color: #E5F0FC #E5F0FC #E5F0FC #DAE0DB;">発注金額合計(税込)</th>
                <th style="border-color: #E5F0FC #DAE0DB #E5F0FC #E5F0FC; font-weight: bold;">
                  \{{ number_format($order_total) }}
                </th>
              </tr>
              <tr>
                <th style="border-color: #E5F0FC #E5F0FC #E5F0FC #DAE0DB;">請求書合計(税込)</th>
                <th style="border-color: #E5F0FC #DAE0DB #E5F0FC #E5F0FC; font-weight: bold;">
                  \{{ number_format($bill_total) }}
                </th>
              </tr>
              <tr>
                <th style="border-color: #E5F0FC #E5F0FC #DAE0DB #DAE0DB;">精算金額</th>
                <th style="border-color: #E5F0FC #DAE0DB #E5F0FC #E5F0FC; font-weight: bold;">
                  \{{ number_format($bill_total - $order_total) }}
                </th>
              </tr>
              <tr>
                <td colspan="2">
                  <input class="csv" type="button" id="csv_button" />
                </td>
              </tr>

            </table>

            <p>■発注内容内訳（発注単価×数量＋運賃）</p>
            <table>
              <tr align="center">
                <th>月日</th><th>注文No</th><th>金額(税込)</th><th>指定施工会社</th><th>納入先</th><th></th>
              </tr>

@foreach ( $orders as $order )
              <tr>
                <td align="center">{{ $order['order_date_md'] }}</td>
                <td align="center" class="code">{{ $order['order_no'] }}</td>
                <td align="right">\{{ number_format($order['total']) }}</td>
                <td align="left">{{ $order['company_name'] }}</td>
                <td align="left">{{ $order['shipping_name'] }}</td>
                <td align="center"><a href="{{ URL::to("office/{$order['order_no']}/order_detail.html#maincolumn") }}">詳細</a></td>
              </tr>
@endforeach

            </table>

            <p>■請求書内容内訳</p>
            <table>
              <tr align="center">
                <th>月日</th>
                <th>品名</th>
                <th>数量</th>
                <th>単価</th>
                <th>金額(税抜)</th>
                <th>納入先</th>
                <th></th>
              </tr>

@foreach ( $bills as $bill )
<?php $class = 'meisai_first'; ?>
@foreach ( $bill['meisai'] as $meisai )
@if ( $meisai['bill_type'] == HINMOKU_LICENSE )
              <tr style="background-color:#eee;color:gray;" class="{{ $class }}">
@else
              <tr class="{{ $class }}">
@endif
                <td align="center">{{ $meisai['meisai_date_md'] }}</td>
                <td align="left" nowrap>{{ $meisai['bill_name'] }}</td>
                <td align="right">{{ $meisai['quantity'] }}</td>
                <td align="right">\{{ number_format($meisai['price']) }}</td>
                <td align="right">\{{ number_format($meisai['sub_total']) }}</td>
                <td align="left">{{ $meisai['shipping_name'] }}</td>
                <td align="center" nowrap>
@if ( $meisai['url'] )
                  <a href="{{ $meisai['url'] }}">詳細</a>
@endif
                </td>
              </tr>
<?php $class = 'meisai_not_first'; ?>
@endforeach
@endforeach

            </table>


            </div>
@endsection