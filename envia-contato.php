<?php
ini_set("mail.log", "/tmp/mail.log");
ini_set("mail.add_x_header", TRUE);

// busca a biblioteca recaptcha
require_once "recaptchalib.php";
include 'PHPMailer-5.2.14/PHPMailerAutoload.php';

// sua chave secreta
$secret = "";
// resposta vazia
$response = null;
// verifique a chave secreta
$reCaptcha = new ReCaptcha($secret);
// se submetido, verifique a resposta
if ($_GET["g-recaptcha-response"]) {
  $response = $reCaptcha->verifyResponse(
    $_SERVER["REMOTE_ADDR"],
    $_GET["g-recaptcha-response"]
  );
}
//Verifica se a resposta retornada no Google foi positiva, sendo positiva retorna a mensagem abaixo, caso contrário retorna para a página do formulário:
if ($response != null && $response->success) {

  //Variaveis de POST, Alterar somente se necessário 
  //====================================================
  $nome = $_GET['nome'];
  $email = $_GET['email'];
  $telefone = $_GET['telefone'];


  //====================================================

  //REMETENTE --> ESTE EMAIL TEM QUE SER VALIDO DO DOMINIO
  //====================================================
  $email_remetente = "contact@contact.com.br"; // deve ser um email do dominio
  $email_key = "";
  //====================================================


  //Configurações do email, ajustar conforme necessidade
  //====================================================
  $email_destinatario = "marketing@poaclin.com.br"; // qualquer email pode receber os dados
  $email_reply = $email;
  $email_assunto = "[POACLIN ODONTOLOGIA] CONTATO";
  //====================================================

  //Monta o Corpo da Mensagem
  //====================================================

  $email_conteudo = "<p>Nome: $nome</p>" . "<p>E-mail: $email </p>" . "<p>Telefone: $telefone </p>";

  $M = new PHPMailer();

  //$M->SMTPDebug = 2; # Somente para debug
  $M->isSMTP(); # Informamos que é SMTP
  $M->Host = 'mail.poaclinodontologia.com.br'; # Colocamos o host de envio de e-mail.
  $M->SMTPAuth = true; # Informamos que terá autenticação de SMTP.
  $M->Username = $email_remetente; # Usuário
  $M->Password = $email_key; # Senha
  $M->Port = 465; # Porta de disparo.
  $M->Mailer = "smtp";
  $M->SMTPSecure = 'ssl'; # Caso tenha segurança.

  $M->From =  $email_remetente; # Remetente do disparo.
  $M->FromName = 'Contato'; # Nome do remetente.
  $M->addAddress($email_destinatario, 'contato'); # Destinatário.
  $M->isHTML(); # Informamos que o corpo tem o formato HTML.
  $M->Subject =  $email_assunto; # Assunto da mensagem.
  # Corpo da mensagem:
  $M->Body = $email_conteudo;

  //====================================================
  //====================================================
  if ($M->send()) {
    echo '<script type="text/javascript">
            alert(\'Sua mensagem foi enviada com sucesso!\nAssim que poss\u00edvel responderemos voc\u00ea.\');
            window.location.href =  "https://poaclinodontologia.com.br/#contato";
            </script>';
  } else {
    echo '<script type="text/javascript">
             alert(\'Falha ao enviar, por favor tente novamente.\');
             window.location.href =  "https://poaclinodontologia.com.br/#contato";   </script>';
  }
} else {
  echo '<script type="text/javascript">
             alert(\'Falha ao enviar, por favor preencha o captcha para verificarmos.\');
             window.location.href = "https://poaclinodontologia.com.br/#contato";   </script>';
}


  //Enviando o email
  //====================================================
