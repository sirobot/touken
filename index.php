<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<link rel="canonical" href="http://dunkel.halfmoon.jp/touken/" />
        <title>刀剣男士所有一覧</title>
		<link rel="stylesheet" href="themes/touken_jquery.min.css" />
		<link rel="stylesheet" href="themes/jquery.mobile.icons.min.css" />
		<link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.5/jquery.mobile.structure-1.4.5.min.css" />
		<script type="text/javascript" src="http://code.jquery.com/jquery-2.1.3.min.js"></script>
		<script type="text/javascript" src="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
		<link href="touken_style.css" rel="stylesheet" type="text/css">
		<link href="image-picker/image-picker.css" rel="stylesheet" type="text/css">
		<script src="image-picker/image-picker.min.js" type="text/javascript"></script>
		<script type="text/javascript">
			$(document).ready(function() {
				// 艦娘所有一覧をベースにバージョンアップを行う
				// ・クッキーによる過去入力の保存機能は廃止、代わりに結果URLからの再作成を可能に(postでパラメータを受け取る)
				// ・「未実装」「未所持」をそれぞれ非表示にする機能を追加
				// ・未実装用の画像を1枚に統一
				// ・URL長の問題、おそらく300文字程度なら影響ないと考えられるため0:未所持、1:所持でURLを構成するように変更

				// 入力(POST)読み込み
				var presetList = $('#presetList').val();
				if (presetList !== '') {
					var presetListArray = presetList.split(',');
					$('#toukenselect option').filter(function() {
						return jQuery.inArray($(this).val(), presetListArray) != -1;
					}).attr('selected', 'selected');
				}
				// imagePicker初期化
				$(".touken").imagepicker({hide_select: true, show_label: true});
				// 全選択
				$('#chkAll,#chkAll_b').click(function() {
					$('#toukenselect option').each(function() {
						$(this).attr('selected', 'selected');
					});
					$('ul.thumbnails li div.thumbnail').addClass('selected');
				});
				// 全選択解除
				$('#chkReset,#chkReset_b').click(function() {
					$('#toukenselect option').each(function() {
						$(this).removeAttr('selected');
					});
					$('ul.thumbnails li div.thumbnail').removeClass('selected');
				});
				// フォーム送信データ作成
				$("form").submit(function() {
					var formValArray = $('#form1 #toukenselect').val();
					// ['1','7',...]の配列になっている状態のものを01に変換
					// twitterのフォロワーの皆様実装アイディアありがとうございます
					var formVal = [];
					// formVal = {'0','0','0','0','0','0',...'0'}の配列を生成・初期化
					for (var i = 1; i < formValArray[formValArray.length - 1]; i++) {
						formVal[i] = '0';
					}
					// formValArrayの要素を添字に、必要な部分のみ1に上書き
					for (var i = 0; i < formValArray.length; i++) {
						formVal[formValArray[i]] = '1';
					}
					// (ver1.1)5文字ずつ32進数で圧縮
					// 末尾が5文字に満たない場合は0詰め
					console.log('formaVal.length:' + (formVal.length-1));
					for (var i = 0; i < (formVal.length - 1) % 5; i++){
						formVal.push('0');
					}
					var form32Val = '';
					var tempStr = '';
					var tempInt = 0;
					console.log('formaVal.length:' + (formVal.length-1));
					for (var i = 1;	i < formVal.length; i=i+5){
						tempStr = formVal[i] + formVal[i+1] +formVal[i+2] + formVal[i+3] + formVal[i+4];
						console.log('tempStr:' + tempStr);
						tempInt = parseInt(tempStr,2);
						console.log('tempInt:' + tempInt);
						form32Val = form32Val + tempInt.toString(32);
						console.log('form32Val:' + form32Val);
					}
					formVal = formVal.join('');
					$('#form1 #toukenselect').val(null);
					$('#form1 #toukenList').val(form32Val);
					console.log('test::');
					console.log(form32Val);
					console.log('::endTest');
				});
			});
		</script>

    </head>
    <body>
		<input type="hidden" id="presetList" name="presetList" value="<?php echo($_POST['presetList']); ?>">
		<div data-role="page">
			<div data-role="header" data-theme="a">
				<h1>刀剣男士所有一覧</h1>
			</div>
			<div data-role="content">
				<div data-role="collapsible" data-collapsed-icon="carat-d" data-expanded-icon="info" data-collapsed="false"   data-theme="a">
					<h3>これなに？</h3> 
					<p class="collapsible">刀剣乱舞-ONLINE-の刀剣男士所有一覧ジェネレーターです。</p>
					<p class="collapsible">持っている刀剣男士をクリックして選択し、「作成」ボタンを押すと作成できます。</p>
					<p class="collapsible">最近のブラウザならだいたい動くと思います。</p>
					<br />
					<a href="https://twitter.com/share" class="twitter-share-button" data-lang="ja">ツイート</a>
					<script>!function(d, s, id) {
							var js, fjs = d.getElementsByTagName(s)[0], p = /^http:/.test(d.location) ? 'http' : 'https';
							if (!d.getElementById(id)) {
								js = d.createElement(s);
								js.id = id;
								js.src = p + '://platform.twitter.com/widgets.js';
								fjs.parentNode.insertBefore(js, fjs);
							}
						}(document, 'script', 'twitter-wjs');
					</script>
				</div>
				<div data-role="collapsible" data-collapsed-icon="carat-d" data-expanded-icon="grid" data-collapsed="false"  data-theme="a">
					<h3>刀剣男士一覧</h3>
					<form action="result.php" method="get" data-ajax="false" name="form1" id="form1">
						<input type="hidden" id="toukenList" name="toukenList" value="">
						<input type="hidden" id="ver" name="ver" value="1.1">
						<button id="chkAll" data-icon="check" data-theme="a" data-inline="true" type="button">すべて選択</button>
						<button id="chkReset" data-icon="delete" data-theme="a" data-inline="true" type="reset">選択リセット</button>
						<input data-theme="b" data-inline="true" type="submit" value="作成">
						<?php
						$path = "/home/vage/pear/PEAR/";
						set_include_path(get_include_path() . PATH_SEPARATOR . $path);
						require_once "../../siro_common/php/db_common.php";

						// DBから刀剣男士のデータを取得
						$db_conn = db_conn();
						if ($db_conn !== false) {
							$query = mysql_query("select * from touken_charaList order by number");
							// selectタグの出力
							echo '<select id="toukenselect" multiple="multiple" class="touken" data-role="none" name="m[]">';
							while ($touken = mysql_fetch_object($query)) {
								echo "\t<option data-img-src='image/" . $touken->number . ".jpg' value='" . $touken->number . "'>No." . $touken->number . " " . $touken->name . "</option>\n";
							}
							echo '</select>';
							mysql_close($db_conn);
						}
						?>
						<button id="chkAll_b" data-icon="check" data-theme="a" data-inline="true" type="button">すべて選択</button>
						<button id="chkReset_b" data-icon="delete" data-theme="a" data-inline="true" type="reset">選択リセット</button>
						<input data-theme="b" data-inline="true" type="submit" value="作成">
					</form>
				</div>
				<div data-role="collapsible" data-collapsed-icon="carat-d" data-expanded-icon="mail" data-collapsed="true"  data-theme="a">
					<h3>他</h3>
					<p class="collapsible">何か不具合等ありましたらご連絡ください。</p>
					<p class="collapsible">作成：siro Twitter:@siro_xx</p>
				</div>
				<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
				<!-- touken -->
				<ins class="adsbygoogle"
					 style="display:inline-block;width:728px;height:90px"
					 data-ad-client="ca-pub-1725571372992163"
					 data-ad-slot="1098984337"></ins>
				<script>
					(adsbygoogle = window.adsbygoogle || []).push({});
				</script>
			</div>
		</div>

    </body>
</html>
