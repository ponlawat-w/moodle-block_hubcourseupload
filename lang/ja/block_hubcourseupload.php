<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Japanese language string
 *
 * @package block_hubcourseupload
 * @copyright 2018 Moodle Association of Japan
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = 'ハブコースアップロード';

$string['hubcourseupload:addinstance'] = 'ブロックを作成する';
$string['hubcourseupload:myaddinstance'] = 'マイページにブロックを作成する';
$string['hubcourseupload:upload'] = 'サイトにコースをアップロードする';

$string['settings:allowcapabilitychange'] = '元のパーミッションを上書きする';
$string['settings:allowcapabilitychange_description'] = 'チェックしたら、<i>moodle/restore:restorecourse</i>のパーミッションが一般のユーザーにも与えます。';
$string['settings:autoenableguestenrol'] = '自動ゲスト登録';
$string['settings:autoenableguestenrol_description'] = '新しいコースでゲスト登録モードを自動的にオンにする。';
$string['settings:maxfilesize'] = 'コースファイルの最大サイズ(ＭＢ)';
$string['settings:maxfilesize_description'] = 'コースファイルの最大サイズ（メガバイトで）<br><small>この設定と<i>php.ini</i>の設定の中に小さい方が適用されます。</small>';
$string['settings:defaultcategory'] = 'ディフォルトのカテゴリー';
$string['settings:defaultcategory_description'] = '新しいコースのカテゴリー';
$string['settings:autocreateinfoblock'] = 'コース情報ブロックを作成';
$string['settings:autocreateinfoblock_decription'] = 'コースのアップロードが完了した後、コース情報ブロックを自動的に作成する';

$string['error_filenotuploaded'] = 'アップロードファイルがありません。';
$string['error_cannotsaveuploadfile'] = 'ファイルを読み込めません。';
$string['error_backupisnotcourse'] = 'ファイルはコースのバックアップファイルではありません。';
$string['error_cannotextractfile'] = 'ファイルを読み込めません。';
$string['error_cannotgetroleinfo'] = 'Cannot get role <i>block_hubcourseupload</i>,　please manually create this role with given short name having permission <i>moodle:restore/restorecourse</i>.';
$string['error_cannotrestore'] = 'コースをサイトにインストールできません。';
$string['error_categorynotfound'] = 'カテゴリー不在';

$string['uploadcoursetohub'] = 'あなたのコースをハブにアップロード';

$string['coursefilechoose'] = 'ファイルブラウザーを開く';
$string['draganddrop'] = 'それともここに「.mbz」ファイルをドロップしてください。';
$string['nocapability'] = 'ハブにアップロードする権利がありません。';
$string['nosignin'] = 'ハブにアップロードするのにログインしてください。';
$string['uploaddescription'] = '対応しるファイルのエクステンション: .mbz';
$string['maxfilesize'] = 'ファイルの最大サイズ: {$a}MB';
$string['pleasewait'] = '少々お待ちください。';

$string['continueupload'] = '引き続き';

$string['proceedanyway'] = '続く';

$string['warning_moodleversion'] = '<p><strong>注意！</strong>アップロードされたコースが、このサイトより新しいムードルバージョンから作成されました。このサイトに正しく機能しない可能性があります。
<br>続けますか。</p>
<p><strong>コースのムードルバージョン:</strong> <span class="text-success">{$a->original}</span><br>
<strong>このサイトのムードルバージョン:</strong> <span class="text-danger">{$a->current}</span></p>';

$string['warning_pluginversion'] = '<strong>注意！</strong>アップロードされたコースが利用しているプラグインは、このサイトのプラグインと一部異なっています。このサイトでこのコースが正しく動かない可能性があります。
<br>下記の表を確認してください。';
$string['requiredplugin_name'] = 'プラグイン名';
$string['requiredplugin_courseversion'] = 'コースからのバージョン';
$string['requiredplugin_siteversion'] = 'このサイトのバージョン';
$string['requiredplugin_status'] = '状態';
$string['requiredplugin_notinstalled'] = 'このサイトに不在。';
$string['requiredplugin_identical'] = '同じ';
$string['requiredplugin_siteolder'] = 'このサイトの方が古い。';
$string['requiredplugin_sitenewer'] = 'このサイトの方が新しい。';

$string['initialversion'] = '最初バージョン';