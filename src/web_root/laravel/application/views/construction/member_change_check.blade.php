@layout('template_construction')

@section('title')
          <div class="title"><p>入力内容確認</p></div>
@endsection
@section('main')
          <form method="post" action="member_change_finish.html">
            <input type="hidden" name="member_id"/>
            <input type="hidden" name="member_pswd"/>
            <input type="hidden" name="company_type"/>
            <div class="member_change_check">
              <table style="width: 600px;">
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
                  <th><p class="th_p">パスワード</p></th>
                  <td>
                    {{ $input['password'] }}
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
              </table>
              <table>
                <tr>
                  <td id="o_id">
                    <input class="reg_back" type="button" name="button" onclick="history.back();">
                  </td>
                  <td id="o_id">
                    <input class="reg_button" type="subuttonbmit" id="reg_button">
                  </td>
                </tr>
              </table>
            </div>
          </form>

@endsection