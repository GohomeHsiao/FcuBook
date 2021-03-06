<?php
// 取得系統組態
include ("connectDB/configure.php");
// 連結資料庫
include ("connectDB/connect_db.php");
//處理文字編碼
mysql_query("SET NAMES 'UTF8'");
header('Content-type:text/html; charset=utf-8');
?>
<html>

<head>
<meta http-equiv="Content-Language" content="zh-tw">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<link rel="stylesheet" href="poet.css" type="text/css">
<title>逢甲大學二手書交易平台</title>
</head>

<body>

<?php include("header.php"); ?><div id="area">
<div align="center">
<table border="0" width="85%" height="400" cellspacing="20">
	<tr>
		<td>
		<p class="line30T" align="center">逢甲大學二手書交易平台 - 平台規章</td>
	</tr>
	<tr>
		<td>
		一、關於此二手書交易平台 <br>
		<p style="line-height: 150%; text-indent:25px">逢甲大學二手書交易平台服務係提供使用者之間交易的平台，刊登之商品是由使用者所自行上傳銷售者，並非逢甲大學所販賣。逢甲大學不參與使用者間之交易，對於出現在交易上的商品品質、安全性或合法性，逢甲大學均不予保證。<br>
		當您使用逢甲大學二手書交易平台時，必須瞭解且遵守以下事項：<br><ul>
		<li>買方和賣方必須對交易之履行負完全的責任。<br>
		<br>
		<li>買方和賣方必須自行解決由交易引起的糾紛。<br>
		<br>
		<li>買方和賣方必須自行負擔因交易而產生的費用。<br>
		<br>
		<li>買方和賣方必須了解並遵守中華民國相關法律規定。<br></ul>
		<p style="line-height: 150%; text-indent:25px">為確保交易之順利履行，使用者須先行判斷是否有出售該商品之權利、交易標的是否合法，並詳細記載商品資訊及交易條件，買方於出價購買前，亦應詳細審視及評估商品說明、賣方評價、交貨方式、條件等訊息，一經成交，買賣合約即存在買賣雙方間，雙方各自負擔給付價款及交付商品之責任，除法令另有規定外，任一方均不得以任何理由反悔。</td>
	</tr>
	<tr>
		<td>二、會員註冊確認程序 <br>
		<p style="line-height: 150%; text-indent:25px">成為交易會員，您必須先完成以下註冊確認程序，步驟依序為：(1)使用逢甲大學NID帳號註冊；(2)確認交易會員資料；(3)同意使用規範，完成以上步驟您即成為交易服務會員並能夠使用出價功能。</td>
	</tr>
	<tr>
		<td>三、費用<br>
		<p style="line-height: 150%; text-indent:25px">
		當您刊登交易商品、設立交易商店及購買廣告時，須依規定（關於《交易收費標準》）支付刊登功能使用費、設立交易商店相關費用及廣告費用。上述費用逢甲大學得隨時變更或調整其收費項目或金額，經公告後生效。<br>
		<p style="line-height: 150%; text-indent:25px">請注意，除本使用規範另有規定者外，您於刊登商品、設立交易商店或購買廣告後，即不得以任何理由拒絕付款或要求退費，逢甲大學依本規範刪除您所刊登的交易資訊時，亦同。</td>
	</tr>
	<tr>
		<td>四、服務使用規則<br>
		<p style="line-height: 150%; text-indent:25px">逢甲大學對於您所刊登之交易商品及資訊(以下稱「交易資訊」)不會進行檢查、過濾或其他調查，但仍保留刪除該交易資訊之權利。如您違反本使用規範或任何服務使用說明或交易資訊有違法之虞，平台管理團隊有權不經通知立即刪除您所刊登的交易資訊且終止您使用交易服務的權利，情節嚴重時並得終止您使用此平台服務。如因您所刊登之交易資訊商品侵害第三人權利或違反法令，致逢甲大學受第三人追償或受主管機關處分時，您應賠償逢甲大學因此所生之一切損失及費用。<br>
		<br>
		使用逢甲大學交易服務應遵守下列規定：<br>
		<ul>
		<li>您不得刊登禁止及限制的商品《禁止刊登商品》。<br>
		<br>
		<li>您不得違反刊登行為之規範《違規刊登行為》。<br>
		<br>
		<li>對於您出售之商品如已有買方得標時，您即有責任按照得標金額與其他約定之交易條件出售該商品。<br>
		<br>
		<li>對於您已經得標的商品，您即有責任按照得標金額與其他約定之交易條件付款。<br>
		</ul>
		<p style="line-height: 150%; text-indent:25px">您不得操縱交易，或干擾正常之交易與競標程序。
		例如：在其他賣方進行交易時與對其出價的買方接洽，向買方兜售類似或相同物品。</td>
	</tr>
	<tr>
		<td>五、授權<br>
		<p style="line-height: 150%; text-indent:25px">任何交易商品資料（含廣告內容）一經您上載、傳送、輸入或提供至逢甲大學交易時，視為您已允許逢甲大學可以為宣傳逢甲大學交易服務或交易商品（包括您的或他人的）之目的，無條件重製、散布、修改、展示、公開播送、公開傳輸該等資料，您對此絕無異議。</td>
	</tr>
	<tr>
		<td>六、交易安全注意事項<br>
		<p style="line-height: 150%; text-indent:25px">網路上的身份認證是很困難的，因此我們提供會員註冊與確認程序、黑名單制度、檢舉商品、檢舉詐欺及棄標處理等機制，藉由確實的交易紀錄與網友守望相助的力量共同來維護線上交易環境。雖有這些機制，使用者於交易時仍應謹慎為之（關於《交易安全須知》）。</td>
	</tr>
	<tr>
		<td>七、服務中止<br>
		<p style="line-height: 150%; text-indent:25px">交易服務宗旨在於提供24小時不間斷的網路交易活動，保持系統不間斷以及各項服務功能的隨時正常運作是逢甲大學全體工作人員努力的目標。除因不可抗力事件或因其他不可歸責於逢甲大學之事由所致者外，若由於逢甲大學交易服務之系統維護、更新、故障或中斷，使您暫時無法使用全部或部分的交易服務功能時，逢甲大學交易服務將依下列情況分別提供您適當的補償（關於《此平台交易補償辦法》）。</td>
	</tr>
	<tr>
		<td>八、責任範圍<br>
		<p style="line-height: 150%; text-indent:25px">除本使用規範另有規定外，逢甲大學對於您因使用交易服務所生之損害不負任何補償或賠償責任，惟如依法令規定逢甲大學因此應負損害賠償責任時，您瞭解並同意逢甲大學所負之責任應不逾您當次就使用逢甲大學交易服務所支付之費用。</td>
	</tr>
	<tr>
		<td>九、免責約款 <br>
		<p style="line-height: 150%; text-indent:25px">逢甲大學僅以「現狀」提供服務，對下述事項不為保證：<br>
		<ul>
		<li>逢甲大學交易系統符合使用者的需求；<br><br>
		<li>逢甲大學交易內容及系統程式不發生錯誤或障礙等情事。</td>
		</ul>
	</tr>
	<tr>
		<td>十、稅務申報 <br>
		<p style="line-height: 150%; text-indent:25px">依中華民國所得稅法及營業稅法之規定，您使用逢甲大學交易銷售商品之所得應申報並繳納所得稅及營業稅：<br>
		<ul>
		<li>個人
		<p style="line-height: 150%; text-indent:25px">您如果是以個人名義銷售商品時，應於每年申報所得稅時，一併申報及繳納銷售商品的所得。 
		但您如果是常態性的銷售商品，而不僅僅是偶一為之者，應依商業登記法、所得稅法、營業稅法等相關規定，辦理營利事業登記，除依法申報並繳納營利事業所得稅外，並應依法領用發票並於銷售時開立發票，或依法開立收據，繳納營業稅。<br>
		<br>
		<li>營利事業
		<p style="line-height: 150%; text-indent:25px">您如果是以營利事業（例如公司或商號）名義銷售商品，您銷售商品的所得，應計入該公司或商號營利事業所得，並於每年申報所得稅時，依法申報並繳納營利事業所得稅。銷售商品時，並應依法開立統一發票或收據，繳納營業稅。</td>
		</ul>
	</tr>
	<tr>
		<td>十一、準據法與管轄法院<br>
		<p style="line-height: 150%; text-indent:25px">本使用規範之解釋與適用，以及與本約定書有關的爭議，均應依照中華民國法律予以處理。您並同意以台灣台中地方法院為第一審管轄法院。</td>
	</tr>
	<tr>
		<td>十二、本使用規範之適用與修正<br>
		<p style="line-height: 150%; text-indent:25px">逢甲大學二手書交易平台上之所有服務說明均為本使用規範之一部分，逢甲大學有權於任何時間修改或變更本使用規範之內容，建議您隨時注意該等修改或變更。本使用規範如有任何重大變更或修正時，逢甲大學會以公告或電子郵件通知您，敬請隨時注意逢甲大學交易公告事項及您所註冊信箱內之郵件。</td>
	</tr>
	<tr>
		<td>
		<p align="right"><br>逢甲大學二手書交易平台管理團隊 敬上</td>
	</tr>
</table></div>
&nbsp;</div>
<?php include("footer.php"); ?>
</body>

</html>