<?php 

class Mailer{
	private $id_formatoCampo;
	private $informeNo;
	private $ordenDeServicio_id;
	private $observaciones;
	private $mr;
	private $tipo;

	public function sendMailBasic($correo, $cliente, $pdf){
		$from = new SendGrid\Email("(LACOCS)Centro de notificaciones inmediatas", "noReply@lacocsmex.com.mx");
		$subject = "Preliminar -- Centro de notificaciones inmediatas";
		$to = new SendGrid\Email("Cliente", $correo);
		$content = new SendGrid\Content("text/html", '
			<div style="text-align:center;width:100%;"><img style="width:150px;height:100px;" src="http://lacocs.montielpalacios.com/assets/img/lacocs.png"/>
					<p>Centro de notificaciones inmediatas</p>
					</div>
					<p></p>
					<p><b>Estimado '.$cliente.',</b></p>
					<p></p>
					<p>Este es un correo automatizado, A continuación te hacemos entrega de informacion preliminar del servicio que contrato con nosotros.</p>
					<p></p>
					<p><b>Servicio</b></p>
					<p></p>
					<p>El siguiente documento contiene..</p>
					<p></p>
					<p>Consulta el preliminar <a href="'.$pdf.'">aqui</a></p>
					<p></p>
					<p><b>IMPORTANTE</b></p>
					<p><b>Este es solo un informe preliminar. Este documento no tiene valides son las firmas autogramas de los signatarios autorizados por LACOCS</b></p>
					<p></p>
					<p><b>Aviso de Privacidad</b></p>
					<p><b>Consulta nuestro aviso de privacidad <a href="http://lacocsmex.com.mx/#/privacidad">aqu&iacute;</a></b></p>
					<p></p>
					<p><b>Aviso de Confidencialidad</b></p>
					<p><b>Esta tranmicion de correo electrónico, cuanquier documento, archivos o correos electronicos adjuntos al presente contienen información confidencial legalmente protegida. Si no eres el destinatario, o una persona encargada de entregarlo al destinatario, se te notifica que cualquier revelación, proceso de copiado, distribución o uso alguno de la información contenida o adjunta a esta transmisión esta ESTRICTAMENTE PROHIBIDA. Si has recibido esta transmisión por error, notifique al remitente de inmediato. Borre la transmisión original y sus adjuntos sin hacer lectura y sin almacenar copia alguna.</b></p>
					<p></p>
					<p><b>CONFIDENTIALITY NOTICE</b></p>
					<p><b>This e-mail transmission, and any documents, files or previous e-mail messages attached to it may contain confidential information that is legally privileged. If you are not the intended recipient, or a person responsible for delivering it to the intended recipient, you are hereby notified that any disclosure, copying, distribution or use of any of the information contained in or attached to this transmission is STRICTLY PROHIBITED. If you have received this transmission in error, please immediately notify the sender. Please destroy the original transmission and its attachments without reading or saving in any manner.</b></p>
					<p></p>
					<br><br><br><br>
					<div style="text-align:center;width:100%;">
						<p><b>Desarrollado por T4U Black <a href=""></a></b></p>
					</div>

			');
		$mail = new SendGrid\Mail($from, $subject, $to, $content);
		$apiKey = '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';
		$sg = new \SendGrid($apiKey);
		$response = $sg->client->mail()->send()->post($mail);


		return $response->statusCode();
	}
	public function sendMailFinal($correo, $cliente, $pdf){
		$from = new SendGrid\Email("(LACOCS)Centro de entregas automatizadas", "noReply@lacocsmex.com.mx");
		$subject = "Reporte Final -- Centro de entregas automatizadas";
		$to = new SendGrid\Email("Cliente", $correo);
		$content = new SendGrid\Content("text/html", '
			<div style="text-align:center;width:100%;"><img style="width:150px;height:100px;" src="http://lacocs.montielpalacios.com/assets/img/lacocs.png"/>
					<p>Centro de entregas automatizadas</p>
					</div>
					<p></p>
					<p><b>Estimado '.$cliente.',</b></p>
					<p></p>
					<p>Este es un correo automatizado, A continuación te hacemos entrega de informacion final del servicio que contrato con nosotros.</p>
					<p></p>
					<p><b>Servicio</b></p>
					<p></p>
					<p>El siguiente documento contiene el documento final digital de su servicio</p>
					<p></p>
					<p>Consulta el PDF <a href="'.$pdf.'">aqu&iacute;</a></p>
					<p></p>
					<p><b>Aviso de Privacidad</b></p>
					<p><b>Consulta nuestro aviso de privacidad <a href="http://lacocsmex.com.mx/#/privacidad">aqu&iacute;</a></b></p>
					<p></p>
					<p><b>Aviso de Confidencialidad</b></p>
					<p><b>Esta tranmicion de correo electrónico, cuanquier documento, archivos o correos electronicos adjuntos al presente contienen información confidencial legalmente protegida. Si no eres el destinatario, o una persona encargada de entregarlo al destinatario, se te notifica que cualquier revelación, proceso de copiado, distribución o uso alguno de la información contenida o adjunta a esta transmisión esta ESTRICTAMENTE PROHIBIDA. Si has recibido esta transmisión por error, notifique al remitente de inmediato. Borre la transmisión original y sus adjuntos sin hacer lectura y sin almacenar copia alguna.</b></p>
					<p></p>
					<p><b>CONFIDENTIALITY NOTICE</b></p>
					<p><b>This e-mail transmission, and any documents, files or previous e-mail messages attached to it may contain confidential information that is legally privileged. If you are not the intended recipient, or a person responsible for delivering it to the intended recipient, you are hereby notified that any disclosure, copying, distribution or use of any of the information contained in or attached to this transmission is STRICTLY PROHIBITED. If you have received this transmission in error, please immediately notify the sender. Please destroy the original transmission and its attachments without reading or saving in any manner.</b></p>
					<p></p>
					<br><br><br><br>
					<div style="text-align:center;width:100%;">
						<p><b>Desarrollado por T4U Black <a href=""></a></b></p>
					</div>

		');
		$mail = new SendGrid\Mail($from, $subject, $to, $content);
		$apiKey = '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';
		$sg = new \SendGrid($apiKey);
		$response = $sg->client->mail()->send()->post($mail);
		return $response->statusCode();
	}
	public function sendMailFinalAdministrativo($correo, $cliente, $pdf, $encargado, $factua, $customMail, $adjunto, $pdfPath, $xmlPath, $customText) {
		$from = new SendGrid\Email("LABORATORIO DE CONTROL DE CALIDAD Y SUPERVISIÓN S.A. DE C.V.", "noReply@lacocsmex.com.mx");
		$subject;
		if($adjunto == 1 && $pdfPath !== '0' && $xmlPath !== '0'){
			$subject = "CFDI & Reporte Final -- Facturación LACOCS";
		}else{
			$subject = "Reporte Final -- Centro de entregas automatizadas";
		}
		$to = new SendGrid\Email("Cliente", $correo);
		$contenido='<div style="text-align:center;width:100%;"><img style="width:150px;height:100px;" src="http://lacocs.montielpalacios.com/assets/img/lacocs.png"/>
			<p>LABORATORIO DE CONTROL DE CALIDAD Y SUPERVISI&Oacute;N S.A. DE C.V.</p>
			</div>
			<p></p>
			<p><b>Estimado '.$cliente.',</b></p>
			<p></p>
			<p>A continuaci&oacute;n le hacemos entrega de informaci&oacute;n final del servicio que contrato con nosotros.</p>
			<p></p>
			<p><b>Servicio</b></p>
			<p></p>
			<p>El siguiente documento contiene el documento final digital de su servicio</p>
			<p></p>
			<p>Consulta el PDF <a href="'.$pdf.'">aqu&iacute;</a></p>
			<p></p>
		';
		if($adjunto == 1 && $pdfPath !== '0' && $xmlPath !== '0'){
			$contenido=$contenido.'
				<p><b>Factura No: </b>'.$factua.'</p>
				<p></p>
				<p>Consulta el PDF <a href="'.$pdfPath.'">aqu&iacute;</a></p>
				<p></p>
				<p>Consulta el XML <a href="'.$xmlPath.'" download>aqu&iacute;</a></p>
				<p></p>
			';
		}
		if($customMail == 1 ){
			$contenido=$contenido.'
				<p><b>P.D.</b></p>
				<p></p>
				<p>'.$customText.'</p>
				<p></p>
			';
		}
		$contenido=$contenido.'
			<p><b>Atentamente</b></p>
			<p><b>'.$encargado.'</b></p>
			<p><b><a href="www.lacocsmex.com.mx">www.lacocsmex.com.mx</a></b></p>
			<p></p>
			<p></p>
			<p></p>
			<p><b>Aviso de Privacidad</b></p>
			<p><b>Consulta nuestro aviso de privacidad <a href="http://lacocsmex.com.mx/#/privacidad">aqu&iacute;</a></b></p>
			<p></p>
			<p><b>Aviso de Confidencialidad</b></p>
			<p><b>Esta tranmici&oacute;n de correo electr&oacute;nico, cuanquier documento, archivos o correos electronicos adjuntos al presente contienen informaci&oacute;n confidencial legalmente protegida. Si no eres el destinatario, o una persona encargada de entregarlo al destinatario, se te notifica que cualquier revelaci&oacute;n, proceso de copiado, distribuci&oacute;n o uso alguno de la informaci&oacute;n contenida o adjunta a esta transmisi&oacute;n esta ESTRICTAMENTE PROHIBIDA. Si has recibido esta transmisi&oacute;n por error, notifique al remitente de inmediato. Borre la transmisi&oacute;n original y sus adjuntos sin hacer lectura y sin almacenar copia alguna.</b></p>
			<p></p>
			<p><b>CONFIDENTIALITY NOTICE</b></p>
			<p><b>This e-mail transmission, and any documents, files or previous e-mail messages attached to it may contain confidential information that is legally privileged. If you are not the intended recipient, or a person responsible for delivering it to the intended recipient, you are hereby notified that any disclosure, copying, distribution or use of any of the information contained in or attached to this transmission is STRICTLY PROHIBITED. If you have received this transmission in error, please immediately notify the sender. Please destroy the original transmission and its attachments without reading or saving in any manner.</b></p>
			<p></p>
			<br><br><br><br>
			<div style="text-align:center;width:100%;">
				<p><b>Desarrollado por T4U Black <a href=""></a></b></p>
			</div>
		';
		$content = new SendGrid\Content("text/html", $contenido);
		$mail = new SendGrid\Mail($from, $subject, $to, $content);
		$apiKey = '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';
		$sg = new \SendGrid($apiKey);
		$response = $sg->client->mail()->send()->post($mail);
		return $response->statusCode();
	}

	public function sendGroupMailFinalAdministrativo($correo, $cliente, $arr, $encargado, $factua, $customMail, $adjunto, $pdfPath, $xmlPath, $customText) {
		$from = new SendGrid\Email("LABORATORIO DE CONTROL DE CALIDAD Y SUPERVISIÓN S.A. DE C.V.", "noReply@lacocsmex.com.mx");
		$subject;
		if($adjunto == 1 && $pdfPath !== '0' && $xmlPath !== '0'){
			$subject = "CFDI & Reporte Final -- Facturación LACOCS";
		}else{
			$subject = "Reporte Final -- Centro de entregas automatizadas";
		}
		$to = new SendGrid\Email("Cliente", $correo);
		$contenido='<div style="text-align:center;width:100%;"><img style="width:150px;height:100px;" src="http://lacocs.montielpalacios.com/assets/img/lacocs.png"/>
			<p>LABORATORIO DE CONTROL DE CALIDAD Y SUPERVISI&Oacute;N S.A. DE C.V.</p>
			</div>
			<p></p>
			<p><b>Estimado '.$cliente.',</b></p>
			<p></p>
			<p>A continuaci&oacute;n le hacemos entrega de informaci&oacute;n final del servicio que contrato con nosotros.</p>
			<p></p>
			<p><b>Servicios</b></p>
			<p></p>
			<p>A continuaci&oacute;n se enlistan los reportes finales de esta factura:</p>
			<p></p>';
		$tmp='
		<p></p>
		';
		foreach ($arr as $value) {
			if($value['tipo'] == "REVENIMIENTO"){
				$tmp=$tmp.'
					<p></p>
					<p>Informe final del revenimiento realizado el d&iacute;a: '.$value['fechaColado'].' con identificador: '.$value['informeNo'].'</p>
					<p>Consulta el PDF <a href="'.$value['pdfFinal'].'">aqu&iacute;</a></p>
					<p></p>
				';
			}else{
				$tmp=$tmp.'
					<p></p>
					<p>Informe final del especimen colado el d&iacute;a: '.$value['fechaColado'].' con identificador: '.$value['claveEspecimen'].'</p>
					<p>Consulta el PDF <a href="'.$value['pdfFinal'].'">aqu&iacute;</a></p>
					<p></p>
				';
			}
		}
		
		$contenido=$contenido.$tmp;
		
		if($adjunto == 1 && $pdfPath !== '0' && $xmlPath !== '0'){
			$contenido=$contenido.'
				<p><b>Factura No: </b>'.$factua.'</p>
				<p></p>
				<p>Consulta el PDF <a href="'.$pdfPath.'">aqu&iacute;</a></p>
				<p></p>
				<p>Consulta el XML <a href="'.$xmlPath.'" download>aqu&iacute;</a></p>
				<p></p>
			';
		}
		if($customMail == 1 ){
			$contenido=$contenido.'
				<p><b>P.D.</b></p>
				<p></p>
				<p>'.$customText.'</p>
				<p></p>
			';
		}
		$contenido=$contenido.'
			<p><b>Atentamente</b></p>
			<p><b>'.$encargado.'</b></p>
			<p><b><a href="www.lacocsmex.com.mx">www.lacocsmex.com.mx</a></b></p>
			<p></p>
			<p></p>
			<p></p>
			<p><b>Aviso de Privacidad</b></p>
			<p><b>Consulta nuestro aviso de privacidad <a href="http://lacocsmex.com.mx/#/privacidad">aqu&iacute;</a></b></p>
			<p></p>
			<p><b>Aviso de Confidencialidad</b></p>
			<p><b>Esta tranmici&oacute;n de correo electr&oacute;nico, cuanquier documento, archivos o correos electronicos adjuntos al presente contienen informaci&oacute;n confidencial legalmente protegida. Si no eres el destinatario, o una persona encargada de entregarlo al destinatario, se te notifica que cualquier revelaci&oacute;n, proceso de copiado, distribuci&oacute;n o uso alguno de la informaci&oacute;n contenida o adjunta a esta transmisi&oacute;n esta ESTRICTAMENTE PROHIBIDA. Si has recibido esta transmisi&oacute;n por error, notifique al remitente de inmediato. Borre la transmisi&oacute;n original y sus adjuntos sin hacer lectura y sin almacenar copia alguna.</b></p>
			<p></p>
			<p><b>CONFIDENTIALITY NOTICE</b></p>
			<p><b>This e-mail transmission, and any documents, files or previous e-mail messages attached to it may contain confidential information that is legally privileged. If you are not the intended recipient, or a person responsible for delivering it to the intended recipient, you are hereby notified that any disclosure, copying, distribution or use of any of the information contained in or attached to this transmission is STRICTLY PROHIBITED. If you have received this transmission in error, please immediately notify the sender. Please destroy the original transmission and its attachments without reading or saving in any manner.</b></p>
			<p></p>
			<br><br><br><br>
			<div style="text-align:center;width:100%;">
				<p><b>Desarrollado por T4U Black <a href=""></a></b></p>
			</div>
		';
		$content = new SendGrid\Content("text/html", $contenido);
		$mail = new SendGrid\Mail($from, $subject, $to, $content);
		$apiKey = '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';
		$sg = new \SendGrid($apiKey);
		$response = $sg->client->mail()->send()->post($mail);
		return $response->statusCode();
	}


	public function sendMailErrorDB($query,$type){
		$from = new SendGrid\Email("(LACOCS) Database", "noReply@lacocsmex.com.mx");
		$subject = "Error- DB Reporting error ";
		$to = new SendGrid\Email("Cliente", "josemontiel@me.com");
		$content = new SendGrid\Content("text/html", '
			<div style="text-align:center;width:100%;"><img style="width:150px;height:100px;" src="http://lacocs.montielpalacios.com/assets/img/lacocs.png"/>
					<p>Centro de notificaciones inmediatas</p>
					</div>
					<p></p>
					<p><b>Estimado administrador de la base de datos,</b></p>
					<p></p>
					<p>Este es un correo automatizado, A continuación te hacemos entrega de una query con error</p>
					<p></p>
					<p><b>Tipo, clase y funcion</b></p>
					<p></p>
					<p>'.$type.'</p>
					<p></p>
					<p><b>Query</b></p>
					<p></p>
					<p>'.$query.'</p>
					<p></p>
					<p></p>
					<p><b>Aviso de Privacidad</b></p>
					<p><b>Consulta nuestro aviso de privacidad <a href="http://lacocsmex.com.mx/#/privacidad">aqu&iacute;</a></b></p>
					<p></p>
					<p><b>Aviso de Confidencialidad</b></p>
					<p><b>Esta tranmicion de correo electrónico, cuanquier documento, archivos o correos electronicos adjuntos al presente contienen información confidencial legalmente protegida. Si no eres el destinatario, o una persona encargada de entregarlo al destinatario, se te notifica que cualquier revelación, proceso de copiado, distribución o uso alguno de la información contenida o adjunta a esta transmisión esta ESTRICTAMENTE PROHIBIDA. Si has recibido esta transmisión por error, notifique al remitente de inmediato. Borre la transmisión original y sus adjuntos sin hacer lectura y sin almacenar copia alguna.</b></p>
					<p></p>
					<p><b>CONFIDENTIALITY NOTICE</b></p>
					<p><b>This e-mail transmission, and any documents, files or previous e-mail messages attached to it may contain confidential information that is legally privileged. If you are not the intended recipient, or a person responsible for delivering it to the intended recipient, you are hereby notified that any disclosure, copying, distribution or use of any of the information contained in or attached to this transmission is STRICTLY PROHIBITED. If you have received this transmission in error, please immediately notify the sender. Please destroy the original transmission and its attachments without reading or saving in any manner.</b></p>
					<p></p>
					<br><br><br><br>
					<div style="text-align:center;width:100%;">
						<p><b>Desarrollado por T4U Black <a href=""></a></b></p>
					</div>

			');
		$mail = new SendGrid\Mail($from, $subject, $to, $content);
		$apiKey = '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';
		$sg = new \SendGrid($apiKey);
		$response = $sg->client->mail()->send()->post($mail);
		return $response->statusCode();
	}
}