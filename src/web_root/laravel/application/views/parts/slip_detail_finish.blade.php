@layout('template_parts')

@section('title')
          <div class="title">
            <p>伝票発行/出荷処理</p>
          </div>
@endsection
@section('main')
          <div class="slip_detail_finish">
            <div class="slip_detail_finish_text"><p class="p_subject">伝票発行/出荷処理が完了しました。</p></div>
          </div>
          <div style="margin-top: 10px;padding-left: 80px;">
          <form action="">
          <input type="button" onclick="window.open(base + 'Inst_' + pad5({{ $order_no }}) + '.pdf')" id="inst_button" value="受注伝票兼出荷指示書ダウンロード" />
          <input type="button" onclick="window.open(base + 'Slip_' + pad5({{ $order_no }}) + '.pdf')" id="slip_button" />
          </form>
          </div>
@endsection