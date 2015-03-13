@layout('template_office')

@section('title')
          <div class="title"><p>物件情報閲覧/変更</p></div>
@endsection
@section('main')
          <form method="post" action="" id="searchF">
            <div class="thing_search">
              <table>
                <tr>
                  <th align="left">認定番号</th>
                  <td class="code">
                    W
                    <input type="text" name="number1" size="10" maxlength="3" /> -
                    <input type="text" name="number2" size="10" maxlength="2" /> -
                    <input type="text" name="number3" size="10" maxlength="4" />
                  </td>
                </tr>
                <tr>
                  <th align="left">施工会社名</th>
                  <td>
                    <select name="company" id="construction_company">
                      <option value=""></option>
@foreach ( $companies as $company )
                      <option value="{{ $company->company_code }}">{{ $company->company_name }}</option>
@endforeach
                    </select>
                  </td>
                </tr>
                <tr>
                <tr>
                  <th align="left">着工日</th>
                  <td>
                    <input type="text" name="s_s_yyyy" size="4" maxlength="4" />年
                    <input type="text" name="s_s_mm" size="4" maxlength="2" />月
                    <input type="text" name="s_s_dd" size="4" maxlength="2" />日～

                    <input type="text" name="s_e_yyyy" size="4" maxlength="4" />年
                    <input type="text" name="s_e_mm" size="4" maxlength="2" />月
                    <input type="text" name="s_e_dd" size="4" maxlength="2" />日
                  </td>
                </tr>
                <tr>
                  <th align="left">完工日</th>
                  <td>
                    <input type="text" name="e_s_yyyy" size="4" maxlength="4" />年
                    <input type="text" name="e_s_mm" size="4" maxlength="2" />月
                    <input type="text" name="e_s_dd" size="4" maxlength="2" />日～

                    <input type="text" name="e_e_yyyy" size="4" maxlength="4" />年
                    <input type="text" name="e_e_mm" size="4" maxlength="2" />月
                    <input type="text" name="e_e_dd" size="4" maxlength="2" />日
                  </td>
                </tr>
                <tr>
                  <th align="left">進捗状況</th>
                  <td>
                    <select name="status" id="stauts">
                      <option value=""></option>
@foreach ( $status as $k => $v )
                      <option value="{{ $v }}">{{ $k }}</option>
@endforeach
                    </select>
                  </td>
                </tr>
              </table>
              <input class="search_button" id="search_button" type="button" />
            </div>
          </form>
@endsection