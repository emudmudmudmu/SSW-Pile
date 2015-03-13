@layout('template_construction')

@section('title')
          <div class="title"><p>受取請求履歴</p></div>
@endsection
@section('main')
          <div class="receipt_billing_history">
            <table>
              <tr align="center">
                <th>請求年月</th><th>請求書No</th><th>請求金額</th><th>ステータス</th><th></th>
              </tr>

@foreach ( $bills as $bill )
              <tr>
                <td align="center">{{ $bill->bill_nen }}/{{ $bill->bill_tuki }}</td>
                <td align="center">{{ $bill->bill_no }}</td>
                <td align="right">\{{ number_format($bill->total + $bill->tax) }}</td>
                <td align="center">{{ $bill->status_txt }}</td>
                <td align="center"><a href="{{ URL::to("construction/{$bill->bill_no}/receipt_billing_detail.html") }}">詳細</a></td>
              </tr>
@endforeach

            </table>
          </div>

@endsection