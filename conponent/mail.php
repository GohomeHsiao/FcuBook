<?php
// 解壓縮後的檔案位置
include("phpmailer/class.phpmailer.php");

// 產生 Mailer 實體
$mail = new PHPMailer();

// 設定為 SMTP 方式寄信
$mail->IsSMTP();

// SMTP 伺服器的設定，以及驗證資訊
$mail->SMTPAuth = true;      
$mail->SMTPSecure = "ssl";    
$mail->Host = "smtp.gmail.com";
$mail->Port = 465;

// 信件內容的編碼方式       
$mail->CharSet = "utf-8";

// 信件處理的編碼方式
$mail->Encoding = "base64";

// SMTP 驗證的使用者資訊
$mail->Username = "fcubooker@gmail.com";
$mail->Password = "kerker123";

// 簽名檔
$Context .= "
<br><br><br><br><br>如有問題請在網站中的客戶提問中發問，謝謝合作。
<br>Copyright © 2011 逢甲大學二手書交易平台管理團隊 敬上<br>
";

// 信件內容設定  
$mail->From = "fcubooker@gmail.com";
$mail->FromName = "逢甲大學二手書交易平台管理團隊";
$mail->Subject = $Title; 
$mail->Body = $Context;    
$mail->IsHTML(true);

// 收件人
$mail->AddAddress($RecipientMail, $RecipientName);

// 顯示訊息
if(!$mail->Send()) {     
echo "Mail error: " . $mail->ErrorInfo;     
}else {     
echo "Mail sent";     
} 
?>