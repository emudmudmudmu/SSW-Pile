@layout('template_common')

@section('title')
          <div class="title"><p>システム治具画面</p></div>
@endsection
@section('main')
          <div>
            <table>
              <tr>
                <th rowspan="2">ハッシュ生成</th>
                <th>入力</th>
                <td>
                  <form action="">
                    <input type="text" id="input" />
                    <button type="button" id="hash_button">生成</button><br />
                  </form>
                  <script type="text/javascript">
                  function afterRendering () {
                    $(document).on("click", "#hash_button", function () {
                        q(base + "hash.json", {
                            input: $("#input").val()
                        }).done(function (res) {
                            $("#output").val(res.output);
                        });
                    });
                  };
                  </script>
                </td>
              </tr>
              <tr>
                <th>出力</th>
                <td>
                  <form action="">
                    <input type="text" size="100" id="output" />
                  </form>
                </td>
            </table>
          </div>
          
          
          <div>
            <table>

              <tr>
                <th>PHP_INT_SIZE</th>
                <td>
                  {{ PHP_INT_SIZE }}
                </td>
              </tr>
              <tr>
                <th>PHP_INT_MAX</th>
                <td>
                  {{ PHP_INT_MAX }}
                </td>
              </tr>
            </table>
          </div>
          
          <div>
            <form action="{{ URL::to('pdftest.pdf') }}" target="_blank" method="post">
            ■PDF出力テスト
            <table>

              <tr>
                <th>埋め込み文言</th>
                <td>
                  <input type="text" name="mongon" />
                </td>
              </tr>
              <tr>
                <th>フォント</th>
                <td>
                  <select name="font">
                    <option value="1">IPA 明朝</option>
                    <option value="2">IPA P明朝</option>
                    <option value="3">IPA ゴシック</option>
                    <option value="4">IPA Pゴシック</option>
                  </select>
                  サイズ<input type="text" name="size" value="16" />
                </td>
              </tr>
              <tr>
                <th></th>
                <td>
                  <input type="submit" />
                </td>
              </tr>
            </table>
            </form>
          </div>
          
                    
          
          
          
          <div style="margin-top: 6em;">
            <h2>PHP Info</h2>
            <iframe src="{{ URL::to('info.php') }}" style="width: 670px;height: 800px; border:0;"></iframe>
          </div>
@endsection