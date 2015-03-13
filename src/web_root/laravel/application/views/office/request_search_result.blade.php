@layout('template_office')

@section('title')
          <div class="title"><p>請求処理</p></div>
@endsection
@section('main')
          <form method="post" action="" id="searchF">
            <div class="request_search">
              <table>
                <tr>
                  <th align="left">請求年月</th>
                  <td>
                    <input type="text" name="yyyy" size="4" maxlength="4" value="{{ $yyyy }}" /> 年
                    <input type="text" name="mm"   size="4" maxlength="2" value="{{ $mm }}"   /> 月
                  </td>
                </tr>
                <tr>
                  <th align="left">請求先</th>
                  <td>
                    <select name="company_code" id="company_code">
                      <option value=""></option>
@foreach ( $companies as $company )
                      <option value="{{ pad3($company->company_code) }}"{{ $company->company_code == $company_code ? ' selected' : ''}}>{{ $company->company_name }}</option>
@endforeach
                    </select>
                  </td>
                </tr>
                <tr>
                  <th align="left">請求書No</th>
                  <td>
                    <input type="text" name="no" size="50" value="{{ $no }}" />
                  </td>
                </tr>
              </table>
              <input class="search_button" id="search_button" type="button" />

            </div>

@if ( isset($result) && 0 < count($result) )
            <div class="request_search_result">
              <p style="margin: 2em 0 -2em;font-size:12px;">
              ※請求先がすべて同一の場合「一括請求処理」ボタンが出現します。<br />
              　「一括請求処理」を行なうと「請求書No.」がまだ決まっていないものを１枚の請求書にまとめます。<br />
              ※個別に請求書を発行する場合は「詳細」リンクから請求詳細画面に移動し「請求処理」ボタンをクリックしてください。
              </p>
              <table>
                <tr>
                <th>請求先</th><th>請求金額</th><th>請求書No</th><th>請求日</th><th></th>
                </tr>

@foreach ( $result as $bill )
                <tr>
                  <td align="left">{{ $bill->company_name }}</td>
                  <td align="right">\{{ number_format($bill->total) }}</td>
                  <td align="center" class="code">{{ $bill->bill_no }}</td>
                  <td align="center">{{ parseDate($bill->bill_date) }}</td>
                  <td align="center"><a href="{{ URL::to("office/{$bill->result_type}/{$bill->key}/request_result.html") }}">詳細</a></td>
                </tr>
@endforeach
              </table>
@if ( $combined )
              <input class="batch_button" type="button" id="batch_button" data-target="{{ $target }}" />
@endif
            </div>
@else
            <p align="center" style="margin-top:4em;">検索条件に一致する請求情報がありません。</p>
@endif
          </form>
@endsection