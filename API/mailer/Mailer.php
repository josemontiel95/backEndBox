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
					<p><b>Consulta nuestro aviso de privacidad en la siguiente dirección <a href=""></a></b></p>
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
					<p><b>Consulta nuestro aviso de privacidad en la siguiente dirección <a href=""></a></b></p>
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
					<p><b>Consulta nuestro aviso de privacidad en la siguiente dirección <a href=""></a></b></p>
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