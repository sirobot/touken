<!DOCTYPE html>
<html>

	<head>
		<meta content="ja" http-equiv="Content-Language">
		<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
		<meta name="viewport" content="width=device-width, initial-scale=1">

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
				$('#flip_noncollect').click(function() {
					if ($('#flip_noncollect').is(':checked')) {
						// 未所持刀剣の表示
						$(".noncollect").show();
					} else {
						// 未所持刀剣の非表示
						$(".noncollect").hide();
					}
				});
				$('#flip_unimpl').click(function() {
					if ($('#flip_unimpl').is(':checked')) {
						// 未実装刀剣の表示
						$(".unimpl").show();
					} else {
						// 未実装刀剣の非表示
						$(".unimpl").hide();
					}
				});
			});
		</script>
	</head>

	<body>
		<div data-role="page">
			<div data-role="header" data-theme="a">
				<h1>刀剣男士所有一覧</h1>
			</div>
			<div data-role="content">
				<div data-role="collapsible" data-collapsed-icon="carat-d" data-expanded-icon="info" data-collapsed="false"  data-theme="a">
					<h3>メニュー</h3>
					<?php
					$path = "/home/vage/pear/PEAR/";
					set_include_path(get_include_path() . PATH_SEPARATOR . $path);
					require_once "../../siro_common/php/db_common.php";

					// GETで回収
					if ($_GET['toukenList'] == "") {
						$form_toukenList = array();
						$selectedToukenCount = 0;
						$presetList = '';
					} else {
						// ver1.1(32進数圧縮)対応
						if((float)$_GET['ver'] >= 1.1){
							// 圧縮された文字列を01列に戻す
							$temp_toukenList32 = str_split($_GET['toukenList']);
							$temp_toukenList = array();
							foreach($temp_toukenList32 as $base32){
								$temp_str = str_pad(base_convert($base32, 32, 2),5,'0',STR_PAD_LEFT);
								$temp_toukenList = array_merge($temp_toukenList,str_split($temp_str));
							}
						}else{
							$temp_toukenList = str_split($_GET['toukenList']);							
						}
						$form_toukenList = array();
						foreach ($temp_toukenList as $key => $val) {
							if ($val === '1') {
								$form_toukenList[] = $key + 1;  // 添字を1からにする
							}
						}
						$selectedToukenCount = count($form_toukenList);
						$presetList = implode(',', $form_toukenList);
						$form_toukenList[] = '0'; // ダミーデータを先頭に挿入し、添字を1からにする
					}
					// DBから刀剣男士のデータを取得
					$db_conn = db_conn();
					if ($db_conn !== false) {
						$query = mysql_query("select * from touken_charaList order by number");
						$impl_toukenList = array();
						while ($touken = mysql_fetch_object($query)) {
							// 実装済み刀剣Noの配列作成
							$impl_toukenList[] = $touken->number;
						}
						$toukenCount = count($impl_toukenList);
						mysql_close($db_conn);
					}

					// 所有率計算
					$collectionRate = floor(($selectedToukenCount / $toukenCount) * 100);

					$originalUrl = "http://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
					//$obj = Services_ShortURL::factory('TinyURL');
					//$shortUrl = $obj->shorten($originalUrl);
					//echo '<a href="http://dunkel.halfmoon.jp/touken/" data-role="button" data-theme="a" data-inline="true" data-ajax="false">この結果を編集する</a><br />';

					echo '<form action="index.php" method="post" data-ajax="false" name="form1" id="form1">';
					echo '<input type="hidden" id="preset" name="presetList" value="' . $presetList . '">';
					echo '<input data-theme="a" data-inline="true" type="submit" value="この結果を編集する"></form>';

					echo '<div>';
					echo '刀剣男士所有数 ' . $selectedToukenCount . '/' . $toukenCount . ' (所有率' . $collectionRate . '%)';
					echo '</div>';
					echo '<div class="menu">';
					echo '<a href="https://twitter.com/share" class="twitter-share-button" data-text="';
					echo '刀剣男士所有一覧を作成しました(所有率' . $collectionRate . '%)';
					echo '" data-count="none">Tweet</a>';
					echo "<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>";
					echo '</div>';
					?>
				</div>
				<div data-role="collapsible" data-collapsed-icon="carat-d" data-expanded-icon="grid" data-collapsed="false"  data-theme="a">

					<h3>刀剣男士所有一覧</h3>
					<div class="toukenleResultList">
						<fieldset data-role="controlgroup" data-type="horizontal">
							<legend>表示切替：</legend>
							<input id="flip_noncollect" name="flip_show" type="checkbox" value="noncollect" />
							<label for="flip_noncollect">未所持刀剣男士を表示</label>
							<input id="flip_unimpl" name="flip_show" type="checkbox" value="unimpl" />
							<label for="flip_unimpl">未実装刀剣男士を表示</label>
						</fieldset>
						<?php
						// 出力
						for ($j = 1; $j <= max($impl_toukenList); $j++) {
							// 「未実装」「実装済未所持」「所持」で表示を切り替える
							if (in_array($j, $form_toukenList)) {
								// 所持
								echo '<div class="toukenResult">';
								echo '<img src="image/' . $j . '.jpg" height="140" width="100"></div>';
							} elseif (in_array($j, $impl_toukenList)) {
								// 実装済未所持
								echo '<div class="toukenResult noncollect" style="display: none">';
								echo '<img src="image/' . $j . '.jpg" height="140" width="100"></div>';
							} else {
								// 未実装
								echo '<div class="toukenResult unimpl" style="display: none">';
								echo '<img src="image/0.jpg" height="140" width="100"></div>';
							}
						}
						?>
					</div>
				</div>
				<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
				<!-- touken_result -->
				<ins class="adsbygoogle"
					 style="display:inline-block;width:728px;height:15px"
					 data-ad-client="ca-pub-1725571372992163"
					 data-ad-slot="4052450739"></ins>
				<script>
					(adsbygoogle = window.adsbygoogle || []).push({});
				</script>
			</div>
		</div>
	</body>

</html>
