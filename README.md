# ccgup-ec

## ライセンス

このプロジェクトのコンテンツは、個別にライセンスが定義されていない限り **Creative Commons BY-NC-SA 4.0** にて公開されています。  
[Creative Commons BY-NC-SA 4.0](https://creativecommons.org/licenses/by-nc-sa/4.0/deed.ja)

## ライセンスのための表記

```
このコンテンツはコードキャンプ株式会社により作成され、Creative Commons BY-NC-SA 4.0により公開されています。
https://codecamp.jp/
```

## 環境準備方法

### git clone

ホストOSで実行します。

```
cd $workspace/ccg/syncs/www/dev.lesson-codecamp.jp
rm -Rf ./webroot
git clone [リポジトリのurl] ./
```

### sqlの実行

ゲストOSにログインして実行します。

```
cd /vagrant/www/dev.lesson-codecamp.jp/lib/schema
mysql -u codecamp_user -p codecamp_db < schema.sql
```

### 設定ファイルの修正

ホストOS上で下記設定ファイルを環境に合わせて修正します。

```
$workspace/ccg/syncs/www/dev.lesson-codecamp.jp/config/const.php
```

## 接続確認

`環境準備方法` が終わりましたら、下記にアクセスして接続確認をしましょう。

### 【課題2-1】テーブル定義(SQL)

## 注文履歴
CREATE TABLE order_histories (
	order_history_id INT AUTO_INCREMENT NOT NULL,
	user_id INT NOT NULL,
	order_datetime DATETIME NOT NULL,
	PRIMARY KEY (order_history_id)
	);

## 注文詳細
CREATE TABLE order_details (
	order_detail_id INT AUTO_INCREMENT NOT NULL,
	order_hitsory_id INT NOT NULL,
	item_id INT NOT NULL,
	purchase_quantity INT NOT NULL,
	PRIMARY KEY (order_detail_id)
	);
[dev.lesson-codecamp.jp:8080](http://dev.lesson-codecamp.jp:8080)
