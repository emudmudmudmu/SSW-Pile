@layout('template_construction')

@section('title')
          <div class="title">
            <p>パーツ発注履歴</p>
            <p class="search_list"><a href="javascript:history.back();">&lt;&lt;検索結果一覧に戻る</a></p>
          </div>
@endsection
@section('main')
          <script type="text/javascript">
            var key = {{ $order->order_no }};
          </script>
          <form method="post" action="slip_detail_finish.html" id="regF">
            <div class="parts_order_history_detail">
              <table>
                <tr>
                  <th><p class="th_p">発注No</p></th>
                  <td>
                    <span id="order_no" class="code"></span>
                  </td>
                  <td>&nbsp;</td>
                </tr>

                <tr>
                  <th></th>
                  <td></td>
                  <td>&nbsp;</td>
                </tr>


                <tr>
                  <th><p class="th_p">納入会社</p></th>
                  <td>
                    {{ $order->shipping_company }}
                  </td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <th><p class="th_p">納入場所</p></th>
                  <td>
                    {{ $order->shipping_name }}
                  </td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <th><p class="th_p">納入先住所</p></th>
                  <td>
                    {{ $order->shipping_address }}
                  </td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <th><p class="th_p">納入担当者</p></th>
                  <td>
                    {{ $order->shipping_person }}
                  </td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <th><p class="th_p">納入先メールアドレス</p></th>
                  <td>
                    {{ $order->email }}
                  </td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <th><p class="th_p">納入先電話番号</p></th>
                  <td>
                    {{ $order->tel }}
                  </td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <th><p class="th_p">納入先FAX番号</p></th>
                  <td>
                    {{ $order->fax }}
                  </td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <th><p class="th_p">納入希望日</p></th>
                  <td>
                    {{ getLabelYMD($order->arrival_date) }}
                  </td>
                  <td>&nbsp;</td>
                </tr>

                <tr>
                  <th></th>
                  <td></td>
                  <td>&nbsp;</td>
                </tr>

<?php
  $i = 1;
  $multi = (1 < count($order->meisai));
?>
@foreach ( $order->meisai as $meisai )
                <tr>
                  <th rowspan="3" valign="top" style="padding-top:7px;"><p class="th_p">受注内容{{ $multi ? "({$i})" : '' }}</p></th>
                  <td>
                    <span style="vertical-align:middle;">先端翼径　</span>{{ $meisai->item_size }}
                    <span style="vertical-align:middle;">　本数　　</span>{{ $meisai->quantity }}
                  </td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td>
                    仕様　
                    {{ getLabelBy('weldings', $meisai->item_type) }}
                  </td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td style="padding:0;">
                    <table class="inner_table">
                     <tr>
                       <th><p class="th_p">発注単価</p></th>
                       <td id="price_{{ $i }}"></td>
                     </tr>
                     <tr>
                       <th><p class="th_p">販売単価</p></th>
                       <td id="sprice_{{ $i }}"></td>
                     </tr>
                     <tr>
                       <th><p class="th_p">パーツ金額</p></th>
                       <td id="subtotal_{{ $i }}"></td>
                     </tr>
                   </table>
                  </td>
                  <td style="vertical-align: bottom;padding-bottom: 8px;">（税抜）</td>
                </tr>
<?php $i++; ?>
@endforeach
                <?php /* ↓複数の注文がある場合のみ表示(JSでの計算には使う) */?>
                <tr{{ $multi ? '' : ' style="display: none;"' }}>
                  <th></th>
                  <td></td>
                  <td>&nbsp;</td>
                </tr>
                <tr{{ $multi ? '' : ' style="display: none;"' }}>
                  <th>&nbsp;</th>
                  <td style="padding:0;">
                    <table class="inner_table">
                     <tr>
                       <th><p class="th_p">パーツ金額合計</p></th>
                       <td id="item_total"></td>
                     </tr>
                   </table>
                  </td>
                  <td style="vertical-align: bottom;padding-bottom: 13px;">（税抜）</td>
                </tr>
                <?php /* ↑複数の注文がある場合のみ表示(JSでの計算には使う) */?>

                <tr>
                  <th></th>
                  <td></td>
                  <td>&nbsp;</td>
                </tr>

                <tr>
                  <th><p class="th_p">請求金額</p></th>
                  <td style="text-align: right" id="total"></td>
                  <td>（税込）</td>
                </tr>

                <tr>
                  <th></th>
                  <td></td>
                  <td>&nbsp;</td>
                </tr>

                <tr>
                  <th><p class="th_p">ステータス</p></th>
                  <td>
                    {{ getLabelBy('order_long', $order->order_status) }}
                  </td>
                  <td>&nbsp;</td>
                </tr>

                <tr>
                  <th></th>
                  <td></td>
                  <td>&nbsp;</td>
                </tr>

                <tr>
                  <th><p class="th_p">出荷日</p></th>
                  <td>
                    {{ getLabelYMD($order->shipping_date) }}
                  </td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <th><p class="th_p">到着予定日</p></th>
                  <td>
                    {{ getLabelYMD($order->delivery_date) }}
                  </td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <th><p class="th_p">運送会社</p></th>
                  <td>
                    {{ $order->shipping_agent }}
                  </td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <th><p class="th_p">問い合わせTEL</p></th>
                  <td>
                    {{ $order->agent_tel }}
                  </td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <th><p class="th_p">問い合わせNo</p></th>
                  <td>
                    {{ $order->agent_inqno }}
                  </td>
                  <td>&nbsp;</td>
                </tr>

                <tr>
                  <th><p class="th_p">書類発行</p></th>
                  <td align="center">
                    <input class="delivery_slip" type="button" id="delivery_slip" >
                  </td>
                  <td>&nbsp;</td>
                </tr>

              </table>
            </div>
          </form>
@endsection