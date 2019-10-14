# 課題開発用リポジトリについて

このリポジトリは課題開発用のリポジトリです。
このリポジトリは直接クローンせず、
一旦フォークしてからクローンして課題を開始してください。

## リポジトリのフォーク・クローン

このリポジトリからフォークしてクローンを行います。

MyDockerディレクトリ直下に
lamp_practice ディレクトリを作成し
移動します。

```bash
mkdir ~/MyDocker/lamp_practice
cd ~/MyDocker/lamp_practice
```

開発課題のリポジトリをフォークして、自分のアカウントのリポジトリにします。  

右上にあるフォークボタン(Forkと書かれたボタン）をクリックすると、
皆さんご自身の管理リポジトリの中に課題リポジトリのコピーが追加されます。


フォークしたリポジトリを開き、現在のディレクトリ(テキストではlamp_practice)にクローンします。

```
git clone [リポジトリurl] .
```

各種ファイルのダウンロードが終わるまでしばらく待ちましょう。

## dockerの立ち上げ

ダウンロードが終わったら、lamp_dock ディレクトリに移動し、
dockerを立ち上げます。

```bash
cd lamp_dock
docker-compose up
```

しばらくの間、コンテナ構築の処理が行われます。（特にmysqlコンテナの構築が終わるまでしばらく待ちます。）

なお、docker-compose up (-dオプションなし) で起動した場合には
Ctrl + C でコンテナを終了できます。

## Docker Toolboxをご利用の方へ

1. volumesの指定について、現在のディレクトリ（.）が指定されている箇所をクローンしたlamp_dockディレクトリに書き換えてください(lamp_dock内でpwd)
2. localhostの指定については、仮想マシンのipアドレスに読み替えてください。

## 確認

* ドキュメントルート: http://localhost:8080
* phpmyadmin: http://localhost:8888

にそれぞれアクセスし、アプリケーションのトップページ(ログイン画面)および
phpmyadminのログイン画面が表示されることを確認しておきましょう。

(Docker ToolBoxをお使いの方は、仮想マシンのipアドレスにアクセスしてください。)


phpmyadminでログインしようとして失敗する場合には、mysqlコンテナの構築が途中の段階である可能性が高いです。
うまくいかない場合、一度コンテナをdownしてから、再度

```
docker-compose up -d 
```

で立ち上げましょう。

## SQLによるインポート

クローンしたリポジトリの lamp_dock ディレクトリには sample.sql というインポート用のsqlファイルが含まれています。
phpmyadmin で sampleデータベースを選択して、「インポート」から sample.sql を選択してインポートしましょう。

## 課題開発環境のまとめ

* php7.2
* mysql5.7
* phpmyadmin

### ログイン情報

管理者としてログイン

* id: admin
* pass: admin

一般ユーザーとしてログイン

* id: sampleuser
* pass: password

### dockerの起動・停止

~/MyDocker/lamp_practice/lamp_dock ディレクトリに移動し、

``` 
docker-compose up -d
```
でコンテナを起動します。

```
docker-compose down
```
で停止、コンテナ削除が可能です。


```
docker exec -it lamp_dock_php_1 /bin/bash
```
でコンテナ内をbashで操作できます。
issueの練習です
もう一行追加