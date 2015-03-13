@layout('template_construction')

@section('title')
          <div class="title"><p>パーツ発注</p></div>
@endsection
@section('main')
          <script type="text/javascript">
            // この画面への「戻る」ボタン押下は無効
            history.forward();
          </script>
          <form method="POST" action="parts_order_finish.html">
            <div class="parts_order_check">
              <table>
                <tr>
                  <th><p class="th_p">納入会社</p></th>
                  <td colspan="2">
                    {{ $input['shipping_company'] }}
                  </td>
                </tr>
              <tr>
                  <th><p class="th_p">納入場所</p></th>
                  <td colspan="2">
                    {{ $input['shipping_name'] }}
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">納入先住所</p></th>
                  <td colspan="2">
                    {{ $input['shipping_address'] }}
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">納入先担当者</p></th>
                  <td colspan="2">
                    {{ $input['shipping_person'] }}
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">納入先メールアドレス</p></th>
                  <td colspan="2">
                    {{ $input['email'] }}
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">納入先電話番号</p></th>
                  <td colspan="2">
                    {{ $input['tel'] }}
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">納入先FAX番号</p></th>
                  <td colspan="2">
                    {{ $input['fax'] }}
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">納入希望日</p></th>
                  <td colspan="2">
                    {{ getLabelYMD($input['arrival_date']) }}
                  </td>
                </tr>
<?php $line = 1;?>
@foreach ( $orders as $order )
                <tr>
                  <th><p class="th_p">発注内容<?= (1 < count($orders) ? '(' . $line++ . ')' : '' ) ?></p></th>
                  <td>先端翼径</td>
                  <td>
                    {{ $order['item_size'] }}
                  </td>
                </tr>
                <tr>
                  <th class="o_id"></th>
                  <td>本数</td>
                  <td>
                    {{ $order['quantity'] }}
                  </td>
                </tr>
                <tr>
                  <th class="o_id"></th>
                  <td>単価</td>
                  <td>
                    \{{ number_format($order['item_sprice']) }}
                  </td>
                </tr>
                <tr>
                  <th class="o_id"></th>
                  <td>仕様</td>
                  <td>
                    {{ getLabelBy('weldings', $order['item_type']) }}
                  </td>
                </tr>
                <tr>
                  <th class="o_id"></th>
                  <td class="o_id" colspan="2">
                  </td>
                </tr>
                <tr>
                  <th class="o_id"></th>
                  <td><p class="th_p">パーツ代金（税抜）</p></td>
                  <td>
                    \{{ number_format($order['subtotal']) }}
                  </td>
                </tr>
                <tr>
                  <th class="o_id"></th>
                  <td><p class="th_p">消費税</td>
                  <td>
                    \{{ number_format($order['tax']) }}
                  </td>
                </tr>
                <tr>
                  <th class="o_id"></th>
                  <td class="o_id" colspan="2">
                  </td>
                </tr>
@endforeach
                <tr>
                  <th class="o_id"></th>
                  <td class="o_id" colspan="2">
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">代金合計（税込）</p></th>
                  <td>&nbsp;</td>
                  <td>
                    \{{ number_format($total) }}
                  </td>
                </tr>

              </table>
              <div style="margin-left: 20px;">※送料につきましては出荷お知らせメールにてお知らせします。</div>
              <table id="o_table">
                <tr>
                  <td class="o_id">
                    <input class="order_back" type="button" name="button" onclick="history.back()" />
                  </td>
                  <td class="o_id">
                    <input class="order_button" id="order_button" type="button" />
                  </td>
                </tr>
              </table>
            </div>
          </form>
@endsection