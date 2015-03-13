@layout('template_construction')

@section('title')
          <div class="title"><p>協会からのお知らせ</p><p class="search_list"><a href="{{ URL::to('construction/top.html') }}">&lt;&lt;一覧に戻る</a></p></div>
@endsection
@section('main')
          <div class="notice">
@foreach ( $d_news as $record )
            <dl class="clearfix">
              <dt>・{{ parseDate($record->news_date) }}</dt>
              <dd>{{ $record->news_title }}</dd>
            </dl>
            <br>
            <textarea readonly cols="80" rows="26" name="detail">{{ $record->news_content }}</textarea>
@endforeach

          </div>
@endsection