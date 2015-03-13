@layout('template_office')
@section('title')
          <div class="title"><p>会員登録</p></div>
@endsection
@section('main')
          <script type="text/javascript">
            function afterRendering() {
                entryChanged({{ $input['company_type'] }});
            };
            // この画面への「戻る」ボタン押下は無効
            history.forward();
          </script>
          <form method="post" action="member_change_finish.html">
            <div class="member_register_check">
              <table style="width: 600px">
                <tr>
                  <th><p class="th_p">会社コード</p></th>
                  <td>
                    {{ pad3($input['company_code']) }}
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">加入日</p></th>
                  <td>
                    {{ $input['join_dateY'] }}年 {{ $input['join_dateM'] }}月 {{ $input['join_dateD'] }}日
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">登録区分</p></th>
                  <td>
                    {{ getLabelByCompanyType($input['company_type']) }}
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">会社名</p></th>
                  <td>
                    {{ $input['company_name'] }}
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">代表者</p></th>
                  <td>
                    {{ $input['ceo'] }}
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">〒</p></th>
                  <td>
                    {{ $input['zip1'] ? $input['zip1'] . '-' : '&nbsp;' }}{{ $input['zip2'] }}
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">住所</p></th>
                  <td>
                    {{ $input['address'] }}
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">電話番号</p></th>
                  <td>
                    {{ $input['tel'] }}
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">FAX番号</p></th>
                  <td>
                    {{ $input['fax'] }}
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">連絡担当者</p></th>
                  <td>
                    {{ $input['tanto'] }}
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">E-Mail</p></th>
                  <td>
                    {{ $input['email'] }}
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">施工エリア</p></th>
                  <td>
<?php $sep = ''; ?>
@foreach ( $prefs as $p )
@if ( in_array( $p->pref_code, $input['areas'] ) )
{{ $sep }} {{ $p->pref_name }}
<?php $sep = ',';?>
@endif
@endforeach
                  </td>
                </tr>

                <tr>
                  <th colspan="2"><p class="th_p">【指定施工会社用ログインアカウント】</p></th>
                </tr>
                <tr>
                  <th><p class="th_p">ID</p></th>
                  <td>
                    {{ $input['member_id'] }}
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">パスワード</p></th>
                  <td>
                    {{ $input['member_pswd'] }}
                  </td>
                </tr>

                <tr id="dr01">
                  <th colspan="2"><p class="th_p">【理事会社用ログインアカウント】</p></th>
                </tr>
                <tr id="dr02">
                  <th><p class="th_p">ID</p></th>
                  <td>
                    {{ $input['manager_id'] }}
                  </td>
                </tr>
                <tr id="dr03">
                  <th><p class="th_p">パスワード</p></th>
                  <td>
                    {{ $input['manager_pswd'] }}
                  </td>
                </tr>

                <tr id="dr04">
                  <th colspan="2"><p class="th_p">【パーツ出荷担当用ログインアカウント】</p></th>
                </tr>
                <tr id="dr05">
                  <th><p class="th_p">ID</p></th>
                  <td>
                    {{ $input['shipping_id'] }}
                  </td>
                </tr>
                <tr id="dr06">
                  <th><p class="th_p">パスワード</p></th>
                  <td>
                    {{ $input['shipping_pswd'] }}
                  </td>
                </tr>
              </table>

              <table>
                <tr>
                  <td class="o_id">
                    <input class="reg_back" type="button" name="button" onclick="history.back();" />
                  </td>
                  <td class="o_id">
                    <input class="reg_button" id="reg_button" type="button" name="submit" />
                  </td>
                </tr>
              </table>

            </div>
          </form>
@endsection