@layout('template_construction')

@section('title')
          <div class="title"><p>パーツ発注</p></div>
@endsection
@section('main')
<?php $sep = ' '; ?>
          <script type="text/javascript">
            // 単価表示に使用
            var items = [
@foreach( $items as $item )
              {{ $sep }}{{ '{'."id:{$item->item_id}, size:\"{$item->item_size}\", price:{$item->item_price}, sprice: {$item->item_sprice}".'}' }}

<?php $sep = ',';?>
@endforeach
            ];
          </script>
          <form method="POST" action="parts_order_check.html" id="regF">
            <div class="parts_order">
              <p class="p_subject">■納入情報</p>
              <table>
                <tr>
                  <th>納入会社</th>
                  <td class="order_td">
                    <input type="text" name="shipping_company" size="80" />
                  </td>
                </tr>
                <tr>
                  <th>納入場所</th>
                  <td class="order_td">
                    <input type="text" name="shipping_name" size="80" />
                  </td>
                </tr>
                <tr>
                  <th>納入先住所</th>
                  <td class="order_td">
                    <input type="text" name="shipping_address" size="80" />
                  </td>
                </tr>
                <tr>
                  <th>納入先担当者</th>
                  <td class="order_td">
                    <input type="text" name="shipping_person" size="80" />
                  </td>
                </tr>
                <tr>
                  <th>納入先メールアドレス</th>
                  <td class="order_td">
                    <input type="text" name="email" size="80" />
                  </td>
                </tr>
                <tr>
                  <th>納入先電話番号</th>
                  <td class="order_td">
                    <input type="text" name="tel" size="80" />
                  </td>
                </tr>
                <tr>
                  <th>納入先FAX番号</th>
                  <td class="order_td">
                    <input type="text" name="fax" size="80" />
                  </td>
                </tr>
                <tr>
                  <th>納入希望日</th>
                  <td class="order_td">
                    <input type="text" name="day_yyyy" size="4" />年
                    <input type="text" name="day_mm"   size="4" />月
                    <input type="text" name="day_dd"   size="4" />日
                  </td>
                </tr>
              </table>

              <br>
              <p class="p_subject">■発注内容</p>
              <p style="padding-left: 1em;">※「本数」に入力のある行のみが発注されます。</p>
              <table>
                <tr>
                  <th>先端翼径</th><th>単価</th><th>本数</th><th>仕様</th>
                </tr>
<?php /* パーツ種類数×仕様種類数だけ入力行を用意 */ ?>
@for ( $i = 0; $i < count($items) * count($weldings); $i++ )
                <tr>
                  <td align="center">
                    <select name="item{{ $i + 1 }}" id="item{{ $i + 1 }}" class="item">
@for ( $j = 0; $j < count($items); $j++ )
                      <option value="{{ $items[$j]->item_id }}"{{ ( floor($i / count($weldings)) == $j ? ' selected' : '') }}>{{ $items[$j]->item_size }}</option>
@endfor
                    </select>
                  </td>
                  <td align="right">
                    <span id="sprice{{ $i + 1 }}"></span>
                  </td>
                  <td align="center">
                    <input type="text" name="quantity{{ $i + 1 }}" class="quantity" size="10" style="text-align: right;" />
                  </td>
                  <td align="center">
                    <select name="item_type{{ $i + 1 }}" id="item_type{{ $i + 1 }}">
@foreach( $weldings as $k => $v )
                      <option value="{{ $v }}"{{ ( ($v - 1) % 3 == $i % 3 ? ' selected' : '') }}>{{ $k }}</option>
@endforeach
                    </select>
                  </td>
                </tr>
@endfor
              </table>
              <input class="order_button" id="order_check_button" type="button" />
            </div>
          </form>
@endsection