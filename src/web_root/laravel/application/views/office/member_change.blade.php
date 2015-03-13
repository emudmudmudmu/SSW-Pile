@layout('template_office')

@section('title')
          <div class="title">
            <p>会員情報閲覧/変更</p>
            <p class="search_list"><a href="javascript:history.back();">&lt;&lt;検索結果一覧に戻る</a></p>
          </div>
@endsection
@section('main')
          <form method="post" action="member_register_check.html" id="regF">
            <div class="member_change">
              <p class="member_change_text">登録内容を変更する場合、変更箇所を訂正し〔入力確認〕を押してください。</p>
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
                    <input type="text" name="yyyy" size="4" />年
                    <input type="text" name="mm" size="4" />月
                    <input type="text" name="dd" size="4" />日
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">登録区分</p></th>
                  <td>
                    <input type="radio" name="division" id="div01" value="{{ COMPANY_MEMBER  }}" onclick="entryChanged(this.value);" checked /><label for="div01">一般指定施工会社</label><br />
                    <input type="radio" name="division" id="div02" value="{{ COMPANY_MANAGER }}" onclick="entryChanged(this.value);" /><label for="div02">理事会社</label><br />
                    <input type="radio" name="division" id="div03" value="{{ COMPANY_JOINT   }}" onclick="entryChanged(this.value);" /><label for="div03">共同開発会社</label><br />
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">会社名</p></th>
                  <td>
                    <input type="text" name="company" size="50" />
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">代表者</p></th>
                  <td>
                    <input type="text" name="representative" size="50" />
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">〒</p></th>
                  <td>
                    <input type="text" name="zip01" size="4" />－
                    <input type="text" name="zip02" size="4" />
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">住所</p></th>
                  <td>
                    <input type="text" name="address" size="50" />
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">電話番号</p></th>
                  <td>
                    <input type="text" name="tel" size="50" />
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">FAX番号</p></th>
                  <td>
                    <input type="text" name="fax" size="50" />
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">連絡担当者</p></th>
                  <td>
                    <input type="text" name="person" size="50" />
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">E-Mail</p></th>
                  <td>
                    <input type="text" name="email" size="50" />
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

              
              <table>
                <tr>
                  <th colspan="2"><p class="th_p">【指定施工会社用ログインアカウント】</p></th>
                </tr>
                <tr>
                  <th><p class="th_p">ID</p></th>
                  <td>
                    <input type="text" name="common_id" size="50" />
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">パスワード</p></th>
                  <td>
                    <input type="text" name="common_pass" size="50" />
                  </td>
                </tr>

                <tr id="dr01">
                  <th colspan="2"><p class="th_p">【理事会社用ログインアカウント】</p></th>
                </tr>
                <tr id="dr02">
                  <th><p class="th_p">ID</p></th>
                  <td>
                    <input type="text" name="director_id" size="50" />
                  </td>
                </tr>
                <tr id="dr03">
                  <th><p class="th_p">パスワード</p></th>
                  <td>
                    <input type="text" name="director_pass" size="50" />
                  </td>
                </tr>

                <tr id="dr04">
                  <th colspan="2"><p class="th_p">【パーツ出荷担当用ログインアカウント】</p></th>
                </tr>
                <tr id="dr05">
                  <th><p class="th_p">ID</p></th>
                  <td>
                    <input type="text" name="parts_id" size="50" />
                  </td>
                </tr>
                <tr id="dr06">
                  <th><p class="th_p">パスワード</p></th>
                  <td>
                    <input type="text" name="parts_pass" size="50" />
                  </td>
                </tr>

              </table>
              <input class="member_register_button" id="member_change_button" type="button" name="submit" />
            </div>
          </form>
@endsection