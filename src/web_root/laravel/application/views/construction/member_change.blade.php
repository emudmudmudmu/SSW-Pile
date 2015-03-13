@layout('template_construction')

@section('title')
          <div class="title"><p>会員登録情報変更</p></div>
@endsection
@section('main')
          <form method="post"  id="regF">
            <input type="hidden" name="common_id"/>
            <input type="hidden" name="common_pass"/>
            <input type="hidden" name="company_type"/>

            <div class="member_change">
              <p style="text-align: center; margin-top: 20px;">変更箇所を訂正し〔入力確認〕を押してください。</p>
              <table>
                <tr>
                  <th><p class="th_p">会社コード</p></th>
                  <td>
                    {{ $company_code }}
                    <input type="hidden" name="company_code" id="company_code" />
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">加入日</p></th>
                  <td>
                    <input type="text" name="yyyy" size="4">年
                    <input type="text" name="mm" size="4">月
                    <input type="text" name="dd" size="4">日
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">会社名</p></th>
                  <td>
                    <input type="text" name="company" size="50">
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">代表者</p></th>
                  <td>
                    <input type="text" name="representative" size="50">
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">〒</p></th>
                  <td>
                    <input type="text" name="zip01" size="4">－
                    <input type="text" name="zip02" size="4">
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">住所</p></th>
                  <td>
                    <input type="text" name="address" size="50">
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">電話番号</p></th>
                  <td>
                    <input type="tel" name="tel" size="50">
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">FAX番号</p></th>
                  <td>
                    <input type="tel" name="fax" size="50">
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">連絡担当者</p></th>
                  <td>
                    <input type="text" name="person" size="50">
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">E-Mail</p></th>
                  <td>
                    <input type="email" name="email" size="50">
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">パスワード</p></th>
                  <td>
                    <input type="password" name="password" size="50">
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">パスワード（再入力）</p></th>
                  <td>
                    <input type="password" name="password2" size="50">
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">施工エリア</p></th>
                  <td>
                    <table>
@for ( $i = 0; $i < count($prefs); $i++ )
@if ( $i % 3 == 0 )
                      <tr>
@endif
                        <td><input type="checkbox" name="area" class="area_checkbox" value="{{ $prefs[$i]->pref_code }}" id="area{{ $i }}" /><label for="area{{ $i }}">{{ $prefs[$i]->pref_name }}</label></td>
@if ( $i % 3 == 2 )
                      </tr>
@elseif ( $i == count($prefs) - 1 )
                        <td>&nbsp;</td>
                      </tr>
@endif
@endfor
                    </table>
                  </td>
                </tr>
              </table>
              <input class="member_change_button" type="button" id="member_change_button">
            </div>
          </form>
@endsection