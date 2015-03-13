@layout('template_construction')

@section('title')
          <div class="title"><p>パーツ発注</p></div>
@endsection
@section('main')
          <div class="parts_order_finish">
            <div class="parts_order_finish_text"><p class="p_subject">パーツ発注が完了しました。</p></div>
            <table style="margin-left: 210px;">
              <tr>
                <td  style="width: 100px; background-color: #E5F0FC;" align="center"><p id="p_td">注文番号</p></td>
                <td  style="width: 100px;">
                  <p class="p_td">{{ $order_no }}</p>
                </td>
              </tr>
            </table>
            <div align="center" style="margin-top: 2em;">
              <p>出荷が完了すると「<a href="{{ URL::to('construction/parts_order_history.html') }}">パーツ発注履歴</a>」から発注内容を確認できます。</p>
            </div>
          </div>
@endsection