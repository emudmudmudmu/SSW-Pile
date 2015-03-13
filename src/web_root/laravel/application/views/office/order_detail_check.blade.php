@layout('template_office')

@section('title')
          <div class="title">
            <p>受注情報閲覧/変更</p>
          </div>
@endsection
@section('main')
          <form method="post" action="order_detail_finish.html">
            <input type="hidden" name="o" id="o_no" value="{{ $input['order_no'] }}" />
            <div class="order_detail_check">
              <table>
                <tr>
                  <th><p class="th_p">注文No</p></th>
                  <td class="code">
                    {{ $input['order_no'] }}
                  </td>
                  <td>&nbsp;</td>
                </tr>

                <tr>
                  <th class="n_th"></th>
                  <td class="n_td"></td>
                  <td class="n_td"></td>
                </tr>

                <tr>
                  <th><p class="th_p">ステータス</p></th>
                  <td>
                    {{ getLabelBy('order_long', $input['order_status']) }}
                  </td>
                  <td>&nbsp;</td>
                </tr>

                <tr>
                  <th class="n_th"></th>
                  <td class="n_td"></td>
                  <td class="n_td"></td>
                </tr>

                <tr>
                  <th><p class="th_p">施工会社（発注元）</p></th>
                  <td>
                    {{ $company_name }}
                  </td>
                  <td>&nbsp;</td>
                </tr>

                <tr>
                  <th><p class="th_p">注文日</p></th>
                  <td>
                    {{ getLabelYMD($input['order_date']) }}
                  </td>
                  <td>&nbsp;</td>
                </tr>

                <tr>
                  <th class="n_th"></th>
                  <td class="n_td"></td>
                  <td class="n_td"></td>
                </tr>

                <tr>
                  <th><p class="th_p">納入会社</p></th>
                  <td>
                    {{ $input['shipping_company'] }}
                  </td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <th><p class="th_p">納入場所</p></th>
                  <td>
                    {{ $input['shipping_name'] }}
                  </td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <th><p class="th_p">納入先住所</p></th>
                  <td>
                    {{ $input['shipping_address'] }}
                  </td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <th><p class="th_p">納入先担当者</p></th>
                  <td>
                    {{ $input['shipping_person'] }}
                  </td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <th><p class="th_p">納入先メールアドレス</p></th>
                  <td>
                    {{ $input['email'] }}
                  </td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <th><p class="th_p">納入先電話番号</p></th>
                  <td>
                    {{ $input['tel'] }}
                  </td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <th><p class="th_p">納入先FAX番号</p></th>
                  <td>
                    {{ $input['fax'] }}
                  </td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <th><p class="th_p">納入希望日</p></th>
                  <td>
                    {{ getLabelYMD($input['arrival_date']) }}
                  </td>
                  <td>&nbsp;</td>
                </tr>

                <tr>
                  <th class="n_th"></th>
                  <td class="n_td"></td>
                  <td class="n_td"></td>
                </tr>

<?php
  $i = 1;
  $multi = (1 < count($input['meisai']));
?>
@foreach ( $input['meisai'] as $meisai )
                <tr>
                  <th rowspan="2" valign="top" style="padding-top:7px;"><p class="th_p">受注内容{{ $multi ? "({$i})" : '' }}</p></th>
                  <td>
                    先端翼径　{{ $meisai['item_size'] }}　　{{ $meisai['quantity'] }}本　　{{ getLabelBy('weldings', $meisai['item_type']) }}
                  </td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td style="padding:0;">
                    <table class="inner_table">
                     <tr>
                       <th><p class="th_p">発注単価</p></th>
                       <td>{{ $meisai['price'] }}</td>
                     </tr>
                     <tr>
                       <th><p class="th_p">販売単価</p></th>
                       <td>\{{ number_format($meisai['sprice']) }}</td>
                     </tr>
                     <tr>
                       <th><p class="th_p">パーツ金額</p></th>
                       <td>\{{ number_format($meisai['subtotal']) }}</td>
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
                       <td>\{{ number_format($item_total) }}</td>
                     </tr>
                   </table>
                  </td>
                  <td style="vertical-align: bottom;padding-bottom: 13px;">（税抜）</td>
                </tr>
                <?php /* ↑複数の注文がある場合のみ表示(JSでの計算には使う) */?>
                
                
                <tr>
                  <th><p class="th_p">運送会社</p></th>
                  <td>
                    {{ $input['shipping_agent'] }}
                  </td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <th><p class="th_p">運賃</p></th>
                  <td style="text-align: right;">
                    {{ $input['shipping_fee'] ? '\\' . number_format($input['shipping_fee']) : '' }}
                  </td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <th><p class="th_p">消費税</p></th>
                  <td style="text-align: right;">
                    \{{ number_format($tax) }}
                  </td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <th><p class="th_p">請求金額</p></th>
                  <td style="text-align: right;">
                    \{{ number_format($total) }}
                  </td>
                  <td>（税込）</td>
                </tr>

                <tr>
                  <th class="n_th"></th>
                  <td class="n_td"></td>
                  <td class="n_td"></td>
                </tr>

                <tr>
                  <th><p class="th_p">出荷日</p></th>
                  <td>
                    {{ getLabelYMD($input['shipping_date']) }}
                  </td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <th><p class="th_p">到着予定日</p></th>
                  <td>
                    {{ getLabelYMD($input['delivery_date']) }}
                  </td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <th><p class="th_p">問い合わせTEL</p></th>
                  <td>
                    {{ $input['agent_tel'] }}
                  </td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <th><p class="th_p">問い合わせNo</p></th>
                  <td>
                    {{ $input['agent_inqno'] }}
                  </td>
                  <td>&nbsp;</td>
                </tr>

                <tr>
                  <th class="n_th"></th>
                  <td class="n_td"></td>
                  <td class="n_td"></td>
                </tr>

                <tr>
                  <th><p class="th_p">請求No</p></th>
                  <td>
                    {{ $input['bill_no'] }}
                  </td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <th><p class="th_p">入金日</p></th>
                  <td>
                    {{ $input['payment_date'] }}
                  </td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <th><p class="th_p">キャンセル日</p></th>
                  <td>
                    {{ $input['cancel_date'] }}
                  </td>
                  <td>&nbsp;</td>
                </tr>

              </table>

              <table>
                <tr>
                  <td class="o_id">
                    <input class="reg_back" type="button" name="button" onclick="history.back()" />
                  </td>
                  <td class="o_id">
                    <input class="reg_button" id="reg_button" type="button" />
                  </td>
                </tr>
              </table>
            </div>
          </form>
@endsection