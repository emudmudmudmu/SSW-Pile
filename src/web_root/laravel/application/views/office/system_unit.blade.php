@layout('template_office')

@section('title')
          <div class="title"><p>単価設定</p></div>
@endsection
@section('main')
          <script type="text/javascript">
            function insert(id){
                document.getElementById('licensefee_id').value = id;
            }
            function insert2(id){
                document.getElementById('item_id').value = id;
            }
          </script>
          <form method="post">
            <div class="system_unit">
              <p class="inf">■工法使用料単価設定</p>
              <table>
                <tr>
                <th>適用開始日</th><th>適用終了日</th><th>0～10本</th><th>11～50本</th><th>51本以上</th><th></th>
                </tr>
<!-- loop start -->
@foreach ( $m_licensefees as $record )
                <tr>
                  <td>
                    <input type="text" id="l_start_date_{{ $record->licensefee_id }}" value="{{ parseDate($record->start_date) }}" size="10">
                  </td>
                  <td>
                    <input type="text" id="l_end_date_{{ $record->licensefee_id }}" value="{{ parseDate($record->end_date) }}" size="10">
                  </td>
                  <td>
                    <input type="text" id="l_licensefees_price1_{{ $record->licensefee_id }}" value="{{ $record->licensefees_price1 }}" size="10">
                  </td>
                  <td>
                    <input type="text" id="l_licensefees_price2_{{ $record->licensefee_id }}" value="{{ $record->licensefees_price2 }}" size="10">
                  </td>
                  <td>
                    <input type="text" id="l_licensefees_price3_{{ $record->licensefee_id }}" value="{{ $record->licensefees_price3 }}" size="10">
                  </td>
                  <td>
                    <input type="button" class="reg_button" id="licensefees_update_button"  onclick="insert('{{ $record->licensefee_id }}');">
                    <input type="button" class="del_button" id="licensefees_delete_button" onclick="insert('{{ $record->licensefee_id }}');">
                  </td>
                </tr>


@endforeach
<!-- loop end -->
                <input type="hidden" id="licensefee_id"  value="" />

                <tr>
                  <td>
                    <input type="text" id="l_start_date" value="" size="10">
                  </td>
                  <td>
                    <input type="text" id="l_end_date" value="" size="10">
                  </td>
                  <td>
                    <input type="text" id="l_licensefees_price1" value="" size="10">
                  </td>
                  <td>
                    <input type="text" id="l_licensefees_price2" value="" size="10">
                  </td>
                  <td>
                    <input type="text" id="l_licensefees_price3" value="" size="10">
                  </td>
                  <td>
                    <input type="button" class="reg_button" name="licensefees_new_button" id="licensefees_new_button">
                  </td>
                </tr>

              </table>
              <p class="inf" style="font-size: 12px; margin-left: 20px;">工法使用料単価を変更する場合は、入力後〔登録〕を押してください。</p>

              <p class="inf">■パーツ単価設定</p>
              <table>
                <tr>
                <th>適用開始日</th><th>適用終了日</th><th>先端翼径</th><th>発注単価<br>（理事）</th><th>販売単価<br>（理事）</th><th>発注単価<br>（一般）</th><th>販売単価<br>（一般）</th><th></th>
                </tr>

<!-- loop start -->
@foreach ( $m_item as $record )
                <tr>
                  <td>
                    <input type="text" id="i_start_date_{{ $record->item_id }}" value="{{ parseDate($record->start_date) }}" size="8">
                  </td>
                  <td>
                    <input type="text" id="i_end_date_{{ $record->item_id }}" value="{{ parseDate($record->end_date) }}" size="8">
                  </td>
                  <td>
                    <input type="text" id="i_item_size_{{ $record->item_id }}" value="{{ $record->item_size }}" size="4">
                  </td>
                  <td>
                    <input type="text" id="i_item_price1_{{ $record->item_id }}" value="{{ $record->item_price1 }}" size="4">
                  </td>
                  <td>
                    <input type="text" id="i_item_sprice1_{{ $record->item_id }}" value="{{ $record->item_sprice1 }}" size="4">
                  </td>
                  <td>
                    <input type="text" id="i_item_price2_{{ $record->item_id }}" value="{{ $record->item_price2 }}" size="4">
                  </td>
                  <td>
                    <input type="text" id="i_item_sprice2_{{ $record->item_id }}" value="{{ $record->item_sprice2 }}" size="4">
                  </td>
                  <td>
                    <input type="button" class="reg_button" id="item_update_button"  onclick="insert2('{{ $record->item_id }}');">
                    <input type="button" class="del_button" id="item_delete_button" onclick="insert2('{{ $record->item_id }}');">
                  </td>
                </tr>
@endforeach
<!-- loop end -->
                <input type="hidden" id="item_id"  value="" />

                <tr>
                  <td>
                    <input type="text" id="i_start_date" value="" size="8">
                  </td>
                  <td>
                    <input type="text" id="i_end_date" value="" size="8">
                  </td>
                  <td>
                    <input type="text" id="i_item_size" value="" size="4">
                  </td>
                  <td>
                    <input type="text" id="i_item_price1" value="" size="4">
                  </td>
                  <td>
                    <input type="text" id="i_item_sprice1" value="" size="4">
                  </td>
                  <td>
                    <input type="text" id="i_item_price2" value="" size="4">
                  </td>
                  <td>
                    <input type="text" id="i_item_sprice2" value="" size="4">
                  </td>
                  <td>
                    <input type="button" class="reg_button" id="item_new_button">
                  </td>
                </tr>

              </table>
              <p class="inf" style="font-size: 12px; margin-left: 20px;">パーツ追加、パーツ単価を変更する場合は、入力後〔登録〕を押してください。</p>

            </div>
          </form>

@endsection