# 野菜掲示板
このサイトは地域の農家の方や買い手の方が野菜の販売状況を共有できるサイトです<br>

<h3>BE
  <a href="https://skillicons.dev">
    <img src="https://skillicons.dev/icons?i=php"/>
  </a>
<h3>DB
<a href="https://skillicons.dev">
<img style="height:48px; border-radius:10px;" src="https://blog.share-wis.com/wp-content/uploads/sites/10/2015/12/NXdGpkmF.png" />
</a>

  
実家が農家で地元のスーパーや道の駅で野菜を出品するさいに情報が共有しずらいという問題を解決するため<br>
作成しました。ログインさえすれば誰でも扱え簡単で使いやすいサイトを目指しました<br>
セキュリティ面を意識して安心して使えるように力を入れました<br>


### 1.新規登録機能
掲示板を使用するためのアカウント登録機能.<br>
名前・メールアドレス・パスワードの項目があり入力が適切でなければエラーがでるように.<br>
メールアドレスは既に登録済みであればエラーが表示されパスワードも適切な文字数でなければエラーがでるように.<br>
<img src="./readmeimg/regist_screen.png"><img src="./readmeimg/regist_error.png">


### 2.ログイン機能
掲示板を使用するためのログイン機能.<br>
正しいメールアドレスとパスワードを使用しないとエラーが表示される.<br>
ログインしていなければ投稿機能・削除機能・いいね機能などの主要な機能が使えない.<br>
<img src="./readmeimg/login_screen.png">

### 3.検索機能
投稿されている内容を検索できる機能.<br>
タイトルと投稿内容を検索して一致しているものがあれば表示できる.<br>
<img src="./readmeimg/search_screen.png">

### 4.削除機能
投稿を削除できる機能.<br>
ログインしているかつその投稿をしたユーザーのみが投稿を削除できる機能<br>
誰でも削除できるわけではなく投稿した本人のみ削除可能.<br>

### 5.詳細機能
画像と投稿日時から詳細をみることができる.<br>

### 6.いいね機能
投稿にいいねする機能.<br>
ログインしているユーザーのみ使用可能で同じフォームには1ユーザー1度のみいいねが使用可能.<br>
<img src="./readmeimg/view_screen.png">

### 7.ページネーション機能
投稿内容が多くなればページを分割する機能.<br>
5投稿以上になれば自動的に次のページがつくられ移動することが可能.<br>
<img src="./readmeimg/page_screen.png">



