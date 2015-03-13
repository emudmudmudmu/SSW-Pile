@layout('template_construction')

@section('title')
          <div class="title"><p>指定施工会社名簿</p><p class="search_list"><a href="{{ URL::to('construction/roster.html') }}">&lt;&lt;検索結果一覧に戻る</a></p></div>
@endsection
@section('main')
          <div class="roster_detail">
            <table style="width: 600px;">
@foreach($m_company as $company)
              <tr>
                <th><p class="th_p">会社コード</p></th>
                <td>
                  {{ $company->company_code }}
                </td>
              </tr>
              <tr>
                <th><p class="th_p">会社名</p></th>
                <td>
                  {{ $company->company_name }}
                </td>
              </tr>
              <tr>
                <th><p class="th_p">住所</p></th>
                <td>
                  {{ $company->address }}
                </td>
              </tr>
              <tr>
                <th><p class="th_p">代表者名</p></th>
                <td>
                  {{ $company->ceo }}
                </td>
              </tr>
              <tr>
                <th><p class="th_p">電話番号</p></th>
                <td>
                  {{ $company->tel }}
                </td>
              </tr>
              <tr>
                <th><p class="th_p">FAX番号</p></th>
                <td>
                  {{ $company->fax }}
                </td>
              </tr>
              <tr>
                <th><p class="th_p">連絡担当者</p></th>
                <td>
                  {{ $company->tanto }}
                </td>
              </tr>
              <tr>
                <th><p class="th_p">E-Mail</p></th>
                <td>
                  <a href="mailto:{{ $company->email }}">{{ $company->email }}</a>
                </td>
              </tr>
              <tr>
                <th><p class="th_p">加入日</p></th>
                <td>
                  {{ $company->join_date }}
                </td>
              </tr>
              <tr>
                <th><p class="th_p">施工エリア</p></th>
                <td>
<?php
	$sep = '';
	foreach( $m_area as $area ){
		foreach( $prefs as $p ){
			if($p->pref_code == $area->pref_code) {
				echo $sep.$p->pref_name;
			}
		}
		$sep = ',';
	}
?>
                </td>
              </tr>
@endforeach
            </table>
          </div>
@endsection