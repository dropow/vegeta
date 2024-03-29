# 野菜掲示板
このサイトは地域の農家の方や買い手の方が野菜の販売状況を共有できるサイトです<br>

## 作った理由　意識したこと
phpを学習するうえでサイト実践的なサイト開発をする必要性を感じどうせなら実用的なものを<br>
実家が農家で地元のスーパーや道の駅で野菜を出品する際に情報が共有しずらいという問題を解決するため<br>
作成しました。ログインさえすれば誰でも扱えるように簡単で使いやすいサイトを目指しました<br>
セキュリティ面を意識して安心して使えるように力を入れました<br>

## 製作期間
個人開発で1か月<br>

## 使用技術
<h3>BE
  <a href="https://skillicons.dev">
    <img src="https://skillicons.dev/icons?i=php"/>
  </a>
<h3>DB
mariadb
</a><br>
<br>

## 苦労したこと、乗り越えた背景
1,データベース操作の複雑さ<br>
データベースとのやり取りは多くのデータを扱う際大変でした<br>
知識をつけてからはコードを実際に動かしてみて感覚をつかんだ<br>

2,セキュリティの問題<br>
SQLインジェクション対策　PDOとプリペアドステートメントを使用<br>
XSS（クロスサイトスクリプティング）攻撃対策: htmlspecialchars() を使用して特殊文字をエスケープし、XSS攻撃を防止<br>
セッション固定攻撃対策:セッションIDを定期的に再生成してセッション固定攻撃を防止など<br>

今までセキュリティの面でプログラムを書くことはなかったので苦労した<br>
動画教材を使う、webサイトを参考にするなどのインプットで知識基礎を付けたうえで実装した<br>
実装するなかでもわからないことは多くエラーがでた際はコードを読み返したり同じ事例をネットで<br>
調べたりしもう一度書いてみるなど試行錯誤した<br>

## 機能

### 1.新規登録機能
掲示板を使用するためのアカウント登録機能.<br>
名前・メールアドレス・パスワードの項目があり入力が確認される.<br>
メールアドレスは既に登録済みであればエラーが表示されパスワードも適切な文字数でなければエラーが表示.<br>
<img src="./readmeimg/regist_screen.png"><img src="./readmeimg/regist_error.png">


### 2.ログイン機能
掲示板を使用するためのログイン機能.<br>
正しいメールアドレスとパスワードを使用しないとエラーが表示.<br>
ログインしていなければ投稿機能・削除機能・いいね機能などの主要な機能が使えない.<br>
<img src="./readmeimg/login_screen.png">

### 3.検索機能
投稿されている内容を検索できる機能.<br>
タイトルと投稿内容を検索して一致しているものがあれば表示できる.<br>
<img src="./readmeimg/search_screen.png">

### 4.削除機能
投稿を削除できる機能.<br>
ログインしているかつその投稿をしたユーザーのみが投稿を削除できる機能.<br>
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

### 8.セキュリティ対策
1.セッションベースの認証:ユーザーのログイン状態はセッション変数を通じて追跡されログイン状態が確認される<br>
2.セッション固定攻撃対策:セッションIDを定期的に再生成してセッション固定攻撃を防止<br>
3.クロスサイトスクリプティング（XSS）対策:htmlspecialchars() を使用して特殊文字をエスケープし、XSS攻撃を防止<br>
4.データベースインジェクション対策:PDOとプリペアドステートメントを使用し、SQLインジェクション攻撃を防止<br>
　ユーザー入力はプレースホルダーにバインドされ、直接クエリに含まれない<br>
5.ファイルアップロードのバリデーション:アップロードされたファイルのサイズと形式をチェックし不正なファイルのアップロードを防止<br>
6.エラーメッセージの管理:エラーメッセージは配列に格納されユーザーに表示されこれによりエラー発生時の詳細情報が不適切に露出するのを防止<br>
7.HTTPSの使用:データの安全な伝送のためにHTTPSプロトコルを使用して通信を暗号化<br>
これらのセキュリティ対策を実施して安全性を高めました<br>
