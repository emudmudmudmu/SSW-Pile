@layout('template_office')

@section('title')
          <div class="title"><p>物件情報閲覧/変更</p></div>
@endsection
@section('main')
          <script type="text/javascript">
            // この画面への「戻る」ボタン押下は無効
            history.forward();
          </script>
          <form method="post" action="thing_register_finish.html" id="regF">
            <div class="thing_change_check">
              <table>
                <tr>
                  <th><p class="th_p">認定番号</p></th>
                  <td class="code">
                    {{ parseConstructionNo($input['construction_no']) }}
                  </td>
                </tr>
                <tr>
                  <th class="n_th"></th>
                  <td class="n_td"></td>
                </tr>
                <tr>
                  <th class="n_th"></th>
                  <td class="n_td"></td>
                </tr>
                <tr>
                  <th><p class="th_p">進捗状況</p></th>
                  <td>
                    {{ getLabelBy('status', $input['status']) }}
                  </td>
                </tr>
                <tr>
                  <th class="n_th"></th>
                  <td class="n_td"></td>
                </tr>
                <tr>
                  <th class="n_th"></th>
                  <td class="n_td"></td>
                </tr>
                <tr>
                  <th><p class="th_p">識別年度</p></th>
                  <td>
                    {{ $input['nendo'] }}
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">施工会社名</p></th>
                  <td>
                    {{ $construction_company->company_name }}
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">施工管理技術者</p></th>
                  <td>
                    {{ $engineer }}
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">会社通番</p></th>
                  <td class="code">
                    {{ substr($input['construction_no'], 6, 4) }}
                  </td>
                </tr>
                <tr>
                  <th class="n_th"></th>
                  <td class="n_td"></td>
                </tr>
                <tr>
                  <th class="n_th"></th>
                  <td class="n_td"></td>
                </tr>
                <tr>
                  <th><p class="th_p">設計会社</p></th>
                  <td>
                    {{ $architect_company }}
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">設計担当者</p></th>
                  <td>
                    {{ $architect }}
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">発注元</p></th>
                  <td>
                    {{ $order_company }}
                  </td>
                </tr>
                <tr>
                  <th class="n_th"></th>
                  <td class="n_td"></td>
                </tr>
                <tr>
                  <th class="n_th"></th>
                  <td class="n_td"></td>
                </tr>
                <tr>
                  <th><p class="th_p">工事名称</p></th>
                  <td>
                    {{ $input['construction_name'] }}
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">工事場所</p></th>
                  <td>
                    {{ $input['construction_address'] }}
                  </td>
                </tr>
                <tr>
                  <th class="n_th"></th>
                  <td class="n_td"></td>
                </tr>
                <tr>
                  <th class="n_th"></th>
                  <td class="n_td"></td>
                </tr>
                <tr>
                  <th><p class="th_p">着工日</p></th>
                  <td>
                    {{ getLabelYMD($input['construction_start_date']) }}
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">完工日</p></th>
                  <td>
                    {{ getLabelYMD($input['complete_date']) }}
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">報告書承認日</p></th>
                  <td>
                    {{ getLabelYMD($input['report_date']) }}
                  </td>
                </tr>
                <tr>
                  <th class="n_th"></th>
                  <td class="n_td"></td>
                </tr>
                <tr>
                  <th class="n_th"></th>
                  <td class="n_td"></td>
                </tr>
                <tr>
                  <th><p class="th_p">打設本数</p></th>
                  <td>
                    {{ $input['amount'] }}
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">材種</p></th>
                  <td>
@foreach ( $materials as $material )
@if ( $material->material_id == $input['material_id'] )
{{ $material->material_name }}
@endif
@endforeach
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">種別</p></th>
                  <td>
                    {{ getLabelBy('sybt', $input['sybt']) }}
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">種別2</p></th>
                  <td>
                    {{ getLabelBy('sybt2', $input['sybt2']) }}
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">構造</p></th>
                  <td>
                    {{ getLabelBy('kozo', $input['kouzou']) }}
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">用途</p></th>
                  <td>
                    {{ $input['yoto'] }}
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">基礎形式</p></th>
                  <td>
                    {{ getLabelBy('kiso', $input['kiso']) }}
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">階数</p></th>
                  <td>
                    {{ $input['floor'] }}
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">高さ(m)</p></th>
                  <td>
                    {{ $input['height'] }}
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">軒高(m)</p></th>
                  <td>
                    {{ $input['nokidake'] }}
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">延べ面積(㎡)</p></th>
                  <td>
                    {{ $input['totalarea'] }}
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">最大施工深さ(m)</p></th>
                  <td>
                    {{ $input['depth'] }}
                  </td>
                </tr>
              </table>

              <table>
                <tr>
                  <td class="o_id">
                    <input class="reg_back" type="button" name="button" onclick="history.back();" />
                  </td>
                  <td class="o_id">
                    <input class="reg_button" id="update_button" type="button" />
                  </td>
                </tr>
              </table>
            </div>
          </form>
@endsection