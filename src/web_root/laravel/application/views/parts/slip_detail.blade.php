@layout('template_parts')

@section('title')
          <div class="title">
            <p>伝票発行/出荷処理</p>
            <p class="search_list"><a href="javascript:history.back();">&lt;&lt;検索結果一覧に戻る</a></p>
          </div>
@endsection
@section('main')
          <script type="text/javascript">
            // この画面には戻れない。
            history.forward();
          </script>
          <form method="post" action="slip_detail_finish.html" id="regF">
            <div class="order_detail">
              <table>
                <tr>
                  <th><p class="th_p">注文No</p></th>
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
                  <th><p class="th_p">ステータス</p></th>
                  <td>
                    <span id="order_status"></span>
                  </td>
                  <td>&nbsp;</td>
                </tr>

                <tr>
                  <th></th>
                  <td></td>
                  <td>&nbsp;</td>
                </tr>

                <tr>
                  <th><p class="th_p">施工会社（発注元）</p></th>
                  <td>
                    <span id="order_company"></span>
                  </td>
                  <td>&nbsp;</td>
                </tr>

                <tr>
                  <th><p class="th_p">注文日</p></th>
                  <td>
                    <span id="order_date_y"></span>年
                    <span id="order_date_m"></span>月
                    <span id="order_date_d"></span>日
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
                    <input type="text" name="shipping_company" size="50" id="shipping_company" />
                  </td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <th><p class="th_p">納入場所</p></th>
                  <td>
                    <input type="text" name="company" size="50" id="shipping_name" />
                  </td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <th><p class="th_p">納入先住所</p></th>
                  <td>
                    <input type="text" name="address" size="50" id="shipping_address" />
                  </td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <th><p class="th_p">納入先担当者</p></th>
                  <td>
                    <input type="text" name="person" size="50" id="shipping_person" />
                  </td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <th><p class="th_p">納入先メールアドレス</p></th>
                  <td>
                    <input type="text" name="email" size="50" id="email" />
                  </td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <th><p class="th_p">納入先電話番号</p></th>
                  <td>
                    <input type="text" name="tel" size="50" id="tel" />
                  </td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <th><p class="th_p">納入先FAX番号</p></th>
                  <td>
                    <input type="text" name="fax" size="50" id="fax" />
                  </td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <th><p class="th_p">納入希望日</p></th>
                  <td>
                    <span id="arrival_date_y"></span>年
                    <span id="arrival_date_m"></span>月
                    <span id="arrival_date_d"></span>日
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
                    <span style="vertical-align:middle;">先端翼径　</span><input type="text" name="tip"    size="10" id="item_size_{{ $i }}" />
                    <span style="vertical-align:middle;">　本数　  </span><input type="text" name="number" size="10" id="quantity_{{ $i }}" class="quantity" />
                  </td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td>
                    仕様
                    <select name="specification" id="item_type_{{ $i }}">
@foreach ( $weldings_list as $k => $v )
                      <option value="{{ $v }}">{{ $k }}</option>
@endforeach
                    </select>
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
                  <th><p class="th_p">運送会社</p></th>
                  <td>
                    <input type="text" name="trans_company" size="50" id="shipping_agent" />
                  </td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <th><p class="th_p">運賃</p></th>
                  <td>
                    <input type="text" name="representative" size="50" id="shipping_fee" style="text-align: right;" />
                  </td>
                  <td>（税抜）</td>
                </tr>
                <tr>
                  <th><p class="th_p">消費税</p></th>
                  <td style="text-align: right">
                    <span id="tax"></span>
                    <span id="rate" style="display: none"></span>
                  </td>
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
                  <th><p class="th_p">出荷日</p></th>
                  <td>
                    <input type="text" name="s_yyyy" size="4" id="shipping_date_y" />年
                    <input type="text" name="s_mm"   size="4" id="shipping_date_m" />月
                    <input type="text" name="s_dd"   size="4" id="shipping_date_d" />日
                  </td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <th><p class="th_p">到着予定日</p></th>
                  <td>
                    <input type="text" name="a_yyyy" size="4" id="delivery_date_y" />年
                    <input type="text" name="a_mm"   size="4" id="delivery_date_m" />月
                    <input type="text" name="a_dd"   size="4" id="delivery_date_d" />日
                  </td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <th><p class="th_p">問い合わせTEL</p></th>
                  <td>
                    <input type="text" name="agent_tel" size="50" id="agent_tel" />
                  </td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <th><p class="th_p">問い合わせNo</p></th>
                  <td>
                    <input type="text" name="enquiry_no" size="50" id="agent_inqno" />
                  </td>
                  <td>&nbsp;</td>
                </tr>

                <tr>
                  <th></th>
                  <td></td>
                  <td>&nbsp;</td>
                </tr>

              </table>
              <input class="order_detail_button" type="button" id="order_detail_button" />
@if ( $order->order_status == ORDER_SHIPPED && $order->shipping_date )
              <div style="margin-top: 10px;padding-left: 80px;">
                <input type="button" onclick="window.open(base + 'Inst_' + pad5({{ $order->order_no }}) + '.pdf')" id="inst_button" value="受注伝票兼出荷指示書ダウンロード" />
                <input type="button" onclick="window.open(base + 'Slip_' + pad5({{ $order->order_no }}) + '.pdf')" id="slip_button" />
              </div>
@endif
              </div>
          </form>
@endsection