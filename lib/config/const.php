<?php
/**
 * @license CC BY-NC-SA 4.0
 * @license https://creativecommons.org/licenses/by-nc-sa/4.0/deed.ja
 * @copyright CodeCamp https://codecamp.jp
 */

// ディレクトリ
define("DIR_APP", dirname(dirname(__FILE__)) . '/');    // システムのベースディレクトリ
define("DIR_IMG",  "./img/");                           // 画像ディレクトリのパス（webrootからの相対)
define("DIR_IMG_FULL", DIR_APP . "../webroot/img/");    // 画像ディレクトリのフルパス
define("DIR_MODEL", DIR_APP . "model/");                // モデルのディレクトリ
define("DIR_VIEW", DIR_APP . "view/");                  // ビューのディレクトリ
define("DIR_VIEW_ELEMENT", DIR_VIEW . "element/");      // ビューエレメントのディレクトリ

// リダイレクト先
define("TOP_URL", "/top.php");                          // トップページのURL
define("ADMIN_URL", "/admin.php");                      // 管理ページのURL
define("LOGIN_URL", "/login.php");                      // ログインページのURL
define("LOGOUT_URL", "/logout.php");                    // ログアウトページのURL

// マジックナンバー
define("ACTIVE", 1);                                    // 商品の公開ステータス
define("INACTIVE", 0);                                  // 商品の非公開ステータス
define("ITEM_NAME_LENGTH_MIN", 3);                      // 商品名の最小値
define("ITEM_NAME_LENGTH_MAX", 100);                    // 商品名の最大値
define("SELECTABLE_CART_AMOUNT_MIN", 1);                // 選択可能なカート内商品の数量の最小値
define("SELECTABLE_CART_AMOUNT_MAX", 10);               // 選択可能なカート内商品の数量の最大値