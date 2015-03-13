@layout('template_construction')

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
                    <input type="text" name="number1" size="10" maxlength="3" value="{{ $number1 }}" /> -
                    <input type="text" name="number2" size="10" maxlength="2" value="{{ $number2 }}" /> -
                    <input type="text" name="number3" size="10" maxlength="4" value="{{ $number3 }}" />
                  </td>
                </tr>
                <tr>
                  <th align="left">施工会社名</th>
                  <td>
                    <select name="company" id="construction_company">
                      <option value=""></option>
@foreach ( $companies as $c )
                      <option value="{{ $c->company_code }}"{{ ($c->company_code == $company) ? ' selected' : '' }}>{{ $c->company_name }}</option>
@endforeach
                    </select>
                  </td>
                </tr>
                <tr>
                <tr>
                  <th align="left">着工日</th>
                  <td>
                    <input type="text" name="s_s_yyyy" size="4" maxlength="4" value="{{ $s_s_yyyy }}" />年
                    <input type="text" name="s_s_mm" size="4" maxlength="2" value="{{ $s_s_mm }}" />月
                    <input type="text" name="s_s_dd" size="4" maxlength="2" value="{{ $s_s_dd }}" />日～

                    <input type="text" name="s_e_yyyy" size="4" maxlength="4" value="{{ $s_e_yyyy }}" />年
                    <input type="text" name="s_e_mm" size="4" maxlength="2" value="{{ $s_e_mm }}" />月
                    <input type="text" name="s_e_dd" size="4" maxlength="2" value="{{ $s_e_dd }}" />日
                  </td>
                </tr>
                <tr>
                  <th align="left">完工日</th>
                  <td>
                    <input type="text" name="e_s_yyyy" size="4" maxlength="4" value="{{ $e_s_yyyy }}" />年
                    <input type="text" name="e_s_mm" size="4" maxlength="2" value="{{ $e_s_mm }}" />月
                    <input type="text" name="e_s_dd" size="4" maxlength="2" value="{{ $e_s_dd }}" />日～

                    <input type="text" name="e_e_yyyy" size="4" maxlength="4" value="{{ $e_e_yyyy }}" />年
                    <input type="text" name="e_e_mm" size="4" maxlength="2" value="{{ $e_e_mm }}" />月
                    <input type="text" name="e_e_dd" size="4" maxlength="2" value="{{ $e_e_dd }}" />日
                  </td>
                </tr>
                <tr>
                  <th align="left">進捗状況</th>
                  <td>
                    <select name="status" id="stauts">
                      <option value=""></option>
@foreach ( $status_list as $k => $v )
                      <option value="{{ $v }}"{{ ($status != "" && $v == $status) ? ' selected' : '' }}>{{ $k }}</option>
@endforeach
                    </select>
                  </td>
                </tr>
              </table>
              <input class="search_button" id="search_button" type="button" />
            </div>
@if ( isset($result) && 0 < count($result) )
            <div class="thing_search_result">
              <table>
                <tr>
                  <th>認定番号</th>
                  <th>工事名</th>
                  <th>施工会社</th>
                  <th>設計会社</th>
                  <th>工期</th>
                  <th></th>
                </tr>

@foreach( $result as $r )
                <tr>
                  <td align="center" class="code">{{ parseConstructionNo($r->construction_no) }}</td>
                  <td align="left">{{ $r->construction_name }}</td>
                  <td align="left" nowrap>{{ array_search_with($r->construction_company, $companies, 'company_code', 'company_name') }}</td>
                  <td align="left" nowrap>{{ array_search_with($r->architect_company   , $companies, 'company_code', 'company_name') }}</td>
                  <td align="left">{{ preg_replace('#-#', '/', $r->construction_start_date) }}{{ ($r->construction_start_date || $r->complete_date) ? ' ～ ' : '' }}{{ preg_replace('#-#', '/', $r->complete_date) }}</td>
                  <td align="center" nowrap><a href="{{ URL::to("construction/{$r->construction_no}/{$r->construction_eda}/thing_detail.html") }}">詳細</a></td>
                </tr>
@endforeach
              </table>
            </div>
@else
            <p align="center" style="margin-top:4em;">検索条件に一致する物件がありません。</p>
@endif
          </form>
@endsection