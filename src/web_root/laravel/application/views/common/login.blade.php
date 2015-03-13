@layout('template_common')

@section('title')
          <div class="title"><p>ログイン</p></div>
@endsection
@section('main')
          <div class="login_input">
            <p align="center">ID、パスワードを入力してください。</p>
            
            <form method="POST" action="construction/top.html">
              <div class="login_bg">
                <table class="login_bg_table">
                  <tr><th class="login_th"><p>ID&gt;&gt;</p></th><td class="login_td"><input type="text" name="username" id="login_id" /></td></tr>
                  <tr><th class="login_th"><p>パスワード&gt;&gt;</p></th><td class="login_td"><input type="password" name="passwd" id="passwd" /></td></tr>
                </table>
              </div>
              <input class="login_button" id="submit_button" type="button" name="submit" />
            </form>

          </div>
@endsection