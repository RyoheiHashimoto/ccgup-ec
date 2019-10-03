<?php
/**
 * @license CC BY-NC-SA 4.0
 * @license https://creativecommons.org/licenses/by-nc-sa/4.0/deed.ja
 * @copyright CodeCamp https://codecamp.jp
 */

define("DIR_APP", dirname(dirname(__FILE__)) . '/');    // システムのベースディレクトリ
define("DIR_IMG",  "./img/");                           // 画像ディレクトリのパス（webrootからの相対)
define("DIR_IMG_FULL", DIR_APP . "webroot/img/");       // 画像ディレクトリのフルパス
define("DIR_MODEL", DIR_APP . "model/");                // モデルのディレクトリ
define("DIR_VIEW", DIR_APP . "view/");                  // ビューのディレクトリ
define("DIR_VIEW_ELEMENT", DIR_VIEW . "element/");      // ビューエレメントのディレクトリ
