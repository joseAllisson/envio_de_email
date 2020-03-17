<?php

require "./Bibliotecas/PHPMailer/Exception.php";
require "./Bibliotecas/PHPMailer/OAuth.php";
require "./Bibliotecas/PHPMailer/PHPMailer.php";
require "./Bibliotecas/PHPMailer/POP3.php";
require "./Bibliotecas/PHPMailer/SMTP.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mensagem {
    private $para = null;
    private $assunto = null;
    private $mensagem = null;

    public $status = array('codigo_status' => null, 'descricao_status' => "" );

    public function __get($atributo){
        return $this->$atributo;
    }

    public  function __set($atributo, $valor)
    {
        $this->$atributo = $valor;
    }

    public function mensagemValida() {
        if(empty($this->para) || empty($this->assunto) || empty($this->mensagem)) {
            return false;
        }

        return true;
    }


}

$mensagem = new Mensagem();

$mensagem->__set('para', $_POST['para']);
$mensagem->__set('assunto', $_POST['assunto']);
$mensagem->__set('mensagem', $_POST['mensagem']);
$mensagem->__set('arquivo', $_FILES['arquivo']);

// print_r($_POST);

if (!$mensagem->mensagemValida()) {
    echo 'mensagem não é valida';
    header("location: index.php"); //manda para a pagina desejada depois de 'location:'
    //die(); //descarta as instruções seguintes
}

$mail = new PHPMailer(true);
try {
    //Server settings
    $mail->SMTPDebug = false;          //todos os passos de envio                       // Enable verbose debug output
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'smtp.gmail.com';                       // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'enviodaapp@gmail.com';       //!!!!coloque seu email aqui nao divulgue sua senha!!!
    $mail->Password = 'MinhaSenha3';                        //!!!!coloque sua senha aqui nao divulgue sua senha!!!
    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587;                                    // TCP port to connect to

    //Recipients
    $mail->setFrom('enviodaapp@gmail.com', 'Teste remetente');
    $mail->addAddress( $mensagem->__get('para') );     // Add a recipient
    // $mail->addReplyTo('info@example.com', 'Information');//resposta do destinatario
    // $mail->addCC('cc@example.com');
    // $mail->addBCC('bcc@example.com');

    //Attachments
    $mail->addAttachment($_FILES['arquivo']['tmp_name'],  $_FILES['arquivo']['name']);       // Add attachments
   // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

    //Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = $mensagem->__get('assunto');
    $mail->Body    = $mensagem->__get('mensagem');
    $mail->AltBody = "Para ter acesso ao conteúdo da mensagem é preciso utilizar o client que suporte HTML!";

    $mail->send();

    $mensagem->status['codigo_status'] = 1;
    $mensagem->status['descricao_status'] = 'Email enviado com sucesso. ';

} catch (Exception $e) {
    $mensagem->status['codigo_status'] = 2;
    $mensagem->status['descricao_status'] = 'Não foi possivel enviar esse e-mail! Por favor tente mais tarde. ' . $mail->ErrorInfo;


    
}

?>


<html>
	<head>
		<meta charset="utf-8" />
    	<title>App Mail Send</title>

    	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

	</head>

	<body>

		<div class="container">  

			<div class="py-3 text-center">
				<img class="d-block mx-auto mb-2" src="logo.png" alt="" width="72" height="72">
				<h2>Send Mail</h2>
				<p class="lead">Seu app de envio de e-mails particular!</p>
			</div>

      		<div class="row">
      			<div class="col-md-12">
                    <? if ( $mensagem->status['codigo_status'] == 1 ) { ?>
                    
                        <div class="container">
                            <h2 class="text-success display-4">Sucesso</h2>
                            <p class=""> <?= $mensagem->status['descricao_status'] ?> </p>  
                            <a href="index.php" class="btn btn-success">Voltar</a>
                        </div>
                      
 
                    <?  } ?>

                        <? if ( $mensagem->status['codigo_status'] == 2 ) { ?>
                        
                        <div class="container">
                            <h2 class="text-danger display-4">ops!</h2>
                            <p class=""> <?= $mensagem->status['descricao_status'] ?> </p>  
                            <a href="index.php" class="btn btn-danger">Voltar</a>
                        </div>
                            

                        <?  } ?>

				</div>
      		</div>

      	</div>

	</body>
</html>




