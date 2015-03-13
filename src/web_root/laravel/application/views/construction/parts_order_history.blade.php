@layout('template_construction')

@section('title')
          <div class="title"><p>パーツ発注履歴</p></div>
@endsection
@section('main')
          <form method="post" action="" id="searchF">

@if ( isset($result) && 0 < count($result) )
            <div class="parts_order_history">
              <table>
                <tr>
                <th>注文日</th><th>発注No</th><th>品名</th><th>本数</th><th>納入日</th><th>ステータス</th><th></th>
                </tr>

@foreach( $result as $order )
                <tr>
                  <td align="center">{{ parseDate($order->order_date) }}</td>
                  <td align="center">{{ $order->order_no }}</td>
                  <td>
<?php $style = '';?>
@foreach( $order->meisais as $meisai )
                    <div style="clear: both;{{ $style }}">
	                  <div style="float:left;">{{ $meisai->item_size }}</div>
                      <div style="float:right;">{{ getLabelBy('weldings', $meisai->item_type) }}</div>
                    </div>
<?php $style = 'border-top:dashed 1px silver;';?>
@endforeach

                  </td>
                  <td>
<?php $style = '';?>
@foreach( $order->meisais as $meisai )
                    <div style="text-align:right;{{ $style }}">
                      {{ $meisai->quantity }}本
                    </div>
<?php $style = 'border-top:dashed 1px silver;';?>
@endforeach
                  </td>
                  <td align="center">{{ parseDate($order->arrival_date) }}</td>
                  <td align="center">{{ getLabelBy('order', $order->order_status) }}</td>
                  <td align="center"><a href="{{ URL::to("construction/{$order->order_no}/parts_order_history_detail.html") }}">詳細</a></td>
                </tr>
@endforeach
              </table>
            </div>
@else
            <p align="center" style="margin-top:4em;">
              検索条件に一致する発注情報がありません。<br />
              {{ $shipping_company_name }}より出荷されたものだけが、ここで閲覧できます。
            </p>
@endif

          </form>
@endsection