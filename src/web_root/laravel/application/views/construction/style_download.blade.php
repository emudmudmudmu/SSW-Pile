@layout('template_construction')

@section('title')
          <div class="title"><p>様式ダウンロード</p></div>
@endsection
@section('main')
          <div class="style_download">
            <div class="style_download_text"><p class="p_subject">ボタンを押してダウンロードを開始して下さい。</p></div>
            <form method="POST" action="">
              <input class="style_download_button" type="button" name="download" onclick="window.open(base + '/scheme.zip')" />
            </form>
          </div>
@endsection