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
                    <input type="text" name="yyyy" size="4" maxlength="4" /> 年
                    <input type="text" name="mm"   size="4" maxlength="2" /> 月
                  </td>
                </tr>
                <tr>
                  <th align="left">請求先</th>
                  <td>
                    <select name="company_code" id="company_code">
                      <option value=""></option>
@foreach ( $companies as $company )
                      <option value="{{ pad3($company->company_code) }}">{{ $company->company_name }}</option>
@endforeach
                    </select>
                  </td>
                </tr>
                <tr>
                  <th align="left">請求書No</th>
                  <td>
                    <input type="text" name="no" size="50" />
                  </td>
                </tr>
              </table>
              <input class="search_button" id="search_button" type="button" />

            </div>

          </form>
@endsection