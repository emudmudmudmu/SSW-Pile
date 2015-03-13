@layout('template_office')

@section('title')
            <div class="title"><p>協会基本情報</p></div>
@endsection
@section('main')

          <form method="post">
                    <input type="hidden" id="association" value="{{ $input['association'] }}">
                    <input type="hidden" id="zip1" value="{{ $input['zip1'] }}">
                    <input type="hidden" id="zip2" value="{{ $input['zip2'] }}">
                    <input type="hidden" id="address" value="{{ $input['address'] }}">
                    <input type="hidden" id="comany" value="{{ $input['comany'] }}">
                    <input type="hidden" id="tel" value="{{ $input['tel'] }}">
                    <input type="hidden" id="fax" value="{{ $input['fax'] }}">
                    <input type="hidden" id="tanto" value="{{ $input['tanto'] }}">
                    <input type="hidden" id="email" value="{{ $input['email'] }}">
                    <input type="hidden" id="syukka" value="{{ $input['syukka'] }}">
                    <input type="hidden" id="bank_name1" value="{{ $input['bank_name1'] }}">
                    <input type="hidden" id="bank_name2" value="{{ $input['bank_name2'] }}">
                    <input type="hidden" id="bank_sybt" value="{{ $input['bank_sybt'] }}">
                    <input type="hidden" id="bank_no" value="{{ $input['bank_no'] }}">
                    <input type="hidden" id="bank_meigi" value="{{ $input['bank_meigi'] }}">

            <div class="society_register_check">
              <table>
                <tr>
                  <th><p class="th_p">名称</p></th>
                  <td>
                    {{ $input['association'] }}
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
                  <th colspan="2"><p class="th_p">【事務局住所】</p></th>
                </tr>
                <tr>
                  <th><p class="th_p">〒</p></th>
                  <td>
                    {{ $input['zip1'] }} - {{ $input['zip2'] }}
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">住所</p></th>
                  <td>
                    {{ $input['address'] }}
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">会社名</p></th>
                  <td>
                    {{ $input['comany'] }}
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
                  <th><p class="th_p">担当</p></th>
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
                  <th class="n_th"></th>
                  <td class="n_td"></td>
                </tr>
                <tr>
                  <th class="n_th"></th>
                  <td class="n_td"></td>
                </tr>
                <tr>
                </tr>
                <tr>
                  <th colspan="2"><p class="th_p">【パーツ出荷場所】</p></th>
                </tr>
                <tr>
                  <th><p class="th_p">会社名</p></th>
                  <td>
                    {{ $input['company_name'] }}
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
                  <th colspan="2"><p class="th_p">【口座情報】</p></th>
                </tr>
                <tr>
                  <th><p class="th_p">銀行名</p></th>
                  <td>
                    {{ $input['bank_name1'] }}
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">支店名</p></th>
                  <td>
                    {{ $input['bank_name2'] }}
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">預金種別</p></th>
                  <td>
                    {{ $input['bank_sybt'] }}
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">口座番号</p></th>
                  <td>
                    {{ $input['bank_no'] }}
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">名義人</p></th>
                  <td>
                    {{ $input['bank_meigi'] }}
                  </td>
                </tr>
              </table>

              <table>
                <tr>
                  <td class="o_id">
                    <input class="reg_back" type="button" id="button" onclick="history.back();">
                  </td>
                  <td class="o_id">
                    <input class="reg_button" type="button" id="update">
                  </td>
                </tr>
              </table>
            </div>
          </form>

@endsection