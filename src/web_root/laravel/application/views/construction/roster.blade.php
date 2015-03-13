@layout('template_construction')

@section('title')
          <div class="title"><p>指定施工会社名簿</p></div>
@endsection
@section('main')
          <div class="roster">
            <table>
              <tr align="center">
                <th>会社名</th><th>電話番号</th><th>連絡担当者</th><th>E-Mail</th><th style="width: 40px;"></th>
              </tr>

<!-- loop start -->
@foreach($m_company as $company)
              <tr>
                <td align="left">{{ $company->company_name }}</td>
                <td align="center">{{ $company->tel }}</td>
                <td align="left">{{ $company->tanto }}</td>
                <td align="left"><a href="mailto:{{ $company->email }}">{{ $company->email }}</a></td>
                <td align="center"><a href="{{ $company->company_code }}/roster_detail.html">詳細</a></td>
              </tr>
@endforeach
<!-- loop end -->

            </table>
          </div>
@endsection