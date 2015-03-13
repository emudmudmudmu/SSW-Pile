@layout('template_construction')

@section('title')
          <div class="title"><p>物件照会</p><p class="search_list"><a href="javascript:history.back();">&lt;&lt;検索結果一覧に戻る</a></p></div>
@endsection
@section('main')
          <script type="text/javascript">
            // この画面への「戻る」ボタン押下は無効
            history.forward();
          </script>
            <div class="thing_reg_check">
              <table>
                <tr>
                  <th><p class="th_p">認定番号</p></th>
                  <td class="code">
                    {{ parseConstructionNo($construction->construction_no) }}
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
                    {{ getLabelBy('status', $construction->status) }}
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
                    {{ $construction->nendo }}
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">施工会社名</p></th>
                  <td>
                    {{ $construction->construction_company }}
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">施工管理技術者</p></th>
                  <td>
                    {{ $construction->engineer }}
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">会社通番</p></th>
                  <td class="code">
                    {{ substr($construction->construction_no, 6, 4) }}
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
                    {{ $construction->construction_company }}
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">設計担当者</p></th>
                  <td>
                    {{ $construction->architect }}
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">発注元</p></th>
                  <td>
                    {{ $construction->order_company }}
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
                    {{ $construction->construction_name }}
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">工事場所</p></th>
                  <td>
                    {{ $construction->construction_address }}
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
                    {{ getLabelYMD($construction->construction_start_date) }}
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">完工日</p></th>
                  <td>
                    {{ getLabelYMD($construction->complete_date) }}
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">報告書承認日</p></th>
                  <td>
                    {{ getLabelYMD($construction->report_date) }}
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
                    {{ $construction->amount }}
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">材種</p></th>
                  <td>
                    {{ $construction->material_id }}
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">種別</p></th>
                  <td>
                    {{ getLabelBy('sybt', $construction->sybt) }}
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">種別2</p></th>
                  <td>
                    {{ getLabelBy('sybt2', $construction->sybt2) }}
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">構造</p></th>
                  <td>
                    {{ getLabelBy('kozo', $construction->kouzou) }}
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">用途</p></th>
                  <td>
                    {{ $construction->yoto }}
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">基礎形式</p></th>
                  <td>
                    {{ getLabelBy('kiso', $construction->kiso) }}
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">階数</p></th>
                  <td>
                    {{ $construction->floor }}
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">高さ(m)</p></th>
                  <td>
                    {{ $construction->height }}
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">軒高(m)</p></th>
                  <td>
                    {{ $construction->nokidake }}
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">延べ面積(㎡)</p></th>
                  <td>
                    {{ $construction->totalarea }}
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">最大施工深さ(m)</p></th>
                  <td>
                    {{ $construction->depth }}
                  </td>
                </tr>
              </table>
            </div>
@endsection