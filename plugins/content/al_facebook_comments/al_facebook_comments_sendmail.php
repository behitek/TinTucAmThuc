<?php

// Load Joomla! configuration file
require_once('../../../configuration.php');
// Create a JConfig object
$config = new JConfig();

if( isset($_GET['to']) && isset($_GET['subject']) && isset($_GET['body']) && isset($_GET['url']) ) 
{
	$from = $config->mailfrom;
	$to = $_GET["to"];
	$subject = $_GET["subject"];
	$message = $_GET["body"];
	$fromname = $config->fromname;
	$url = $_GET["url"];
	$cid = $_GET["cid"];
	sendemail($from, $to, $subject, $message, $fromname, $url, $cid);
}

function sendemail($from, $to, $subject, $message, $fromname, $url, $cid)
{
	$cmurl = $url.'?fb_comment_id='.$cid;
	// message
	$body = '
	<html>
	<head>
	<title>New Comment</title>
	</head>
	<body>
	<table width="620" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td width="1" height="1" bgcolor="#CCCCCC"></td>
			<td width="618" height="1" bgcolor="#CCCCCC"></td>
			<td width="1" height="1" bgcolor="#CCCCCC"></td>
		</tr>
		<tr>
			<td width="1" bgcolor="#CCCCCC"></td>
			<td><table width="618" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td bgcolor="#3b5998"><p style="font-family:Verdana, Geneva, sans-serif; font-size:14px; font-weight:bold; color:#FFF; padding:5px 20px; margin:0">'.$fromname.' ( post )</p></td>
					</tr>
					<tr>
						<td><table width="618" border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td width="20" height="20"></td>
									<td></td>
									<td width="20" height="20"></td>
								</tr>
								<tr>
									<td width="20"></td>
									<td><table width="588" border="0" cellspacing="0" cellpadding="0">
											<tr>
												<td width="488">
													<p style="font-family:Verdana, Geneva, sans-serif; font-size:14px; line-height:18px">'.$message.'<br />'.$cmurl.'</p></td>
												<td width="90"><a href="'.$cmurl.'" style="font-family:Verdana, Geneva, sans-serif; font-size:14px; line-height:18px; font-weight:bold; color:#FFF; padding:5px 0px; margin:0; display:block; text-decoration:none; background:#69a74e; width:90px; text-align:center">See post</a></td>
											</tr>
										</table>
										<p style="font-family:Verdana, Geneva, sans-serif; font-size:14px; line-height:18px">Al Facebook comments plugin for Joomla 2.5/3.0, this is a free plugin, please help me keeping it that way just visiting my website <a href="http://www.AlexLopezIT.com">www.AlexLopezIT.com</a> often and clicking on the ads.</p>
										<p style="font-family:Verdana, Geneva, sans-serif; font-size:14px; line-height:18px">Gracias por la instalar AL Comentarios Facebook para Joomla 2.5/3.0, este es un plugin gratuito, por favor ayudame a mantenerlo de esa manera, simplemente visita mi sitio web <a href="http://www.AlexLopezIT.com">www.AlexLopezIT.com</a> a menudo y haz clic en los anuncios.</p></td>
									<td width="20"></td>
								</tr>
								<tr>
									<td width="20" height="20"></td>
									<td></td>
									<td width="20" height="20"></td>
								</tr>
							</table></td>
					</tr>
				</table></td>
			<td width="1" bgcolor="#CCCCCC"></td>
		</tr>
		<tr>
			<td width="1" height="1" bgcolor="#CCCCCC"></td>
			<td width="618" height="1" bgcolor="#CCCCCC"></td>
			<td width="1" height="1" bgcolor="#CCCCCC"></td>
		</tr>
	</table>
	</body>
	</html>
	';

	// To send HTML mail, the Content-type header must be set
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	// Additional headers
	$headers .= 'From: '.$fromname.' <'.$from.'>' . "\r\n";

	// Mail it
	if (mail($to, $subject, $body, $headers)) {
	  echo("<p style=\"color:#00F\">Message successfully sent!</p>");
	 } else {
	  echo("<p style=\"color:#F00\">Message delivery failed...</p>");
	 }
}
?>