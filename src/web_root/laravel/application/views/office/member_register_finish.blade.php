@layout('template_office')

@section('title')
          <div class="title"><p>会員登録</p></div>
@endsection
@section('main')
          <div class="member_register_finish">
            <div class="member_register_finish_text">
              <p class="p_subject">会員情報の登録が完了しました。</p>
            </div>

            <table>
              <tr>
                <th><p class="th_p">会社コード</p></th>
                <td>
                  {{ pad3($input['company_code']) }}
                </td>
              </tr>
              <tr>
                <th><p class="th_p">会社名</p></th>
                <td>
                  {{ $input['company_name'] }}
                </td>
              </tr>
            </table>

            <p style="margin-left:150px; margin-top: 30px; color: #000000;">【指定施工会社用ログインアカウント】</p>
            <table>
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
            </table>
@if ( $input['company_type'] == COMPANY_MANAGER || $input['company_type'] == COMPANY_JOINT )
            <p style="margin-left:150px; margin-top: 30px; color: #000000;">【理事会社用ログインアカウント】</p>
            <table>
              <tr>
                <th><p class="th_p">ID</p></th>
                <td>
                  {{ $input['manager_id'] }}
                </td>
              </tr>
              <tr>
                <th><p class="th_p">パスワード</p></th>
                <td>
                  {{ $input['manager_pswd'] }}
                </td>
              </tr>
            </table>
@endif
@if ( $input['company_type'] == COMPANY_MANAGER )
            <p style="margin-left:150px; margin-top: 30px; color: #000000;">【パーツ出荷担当用ログインアカウント】</p>
            <table>
              <tr>
                <th><p class="th_p">ID</p></th>
                <td>
                  {{ $input['shipping_id'] }}
                </td>
              </tr>
              <tr>
                <th><p class="th_p">パスワード</p></th>
                <td>
                  {{ $input['shipping_pswd'] }}
                </td>
              </tr>
            </table>
@endif

            <div align="center" style="margin-top: 4em;">
              <p>
                会員情報は「<a href="{{  URL::to('office/member_change_search.html') }}">会員情報閲覧/変更</a>」から確認できます。
              </p>
            </div>
@endsection