<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Receber dados do formulário
    $name = htmlspecialchars($_POST['name']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $phone = htmlspecialchars($_POST['phone']);
    $interest = htmlspecialchars($_POST['interest']);
    $message = htmlspecialchars($_POST['message']);
    $captcha = intval($_POST['captcha']);
    $resume = $_FILES['resume'];

    // Validação do CAPTCHA
    if ($captcha !== 7) {
        echo json_encode(['success' => false, 'message' => 'Resposta do CAPTCHA incorreta.']);
        exit;
    }

    // Validação básica dos dados
    if (empty($name) || empty($email) || empty($phone) || empty($interest) || empty($resume)) {
        echo json_encode(['success' => false, 'message' => 'Por favor, preencha todos os campos obrigatórios.']);
        exit;
    }

    // Processar o arquivo enviado
    $allowedExtensions = ['pdf', 'doc', 'docx'];
    $fileExtension = strtolower(pathinfo($resume['name'], PATHINFO_EXTENSION));

    if (!in_array($fileExtension, $allowedExtensions)) {
        echo json_encode(['success' => false, 'message' => 'Formato de arquivo inválido. Apenas PDF, DOC ou DOCX são permitidos.']);
        exit;
    }

    // Caminho temporário do arquivo
    $uploadedFilePath = $resume['tmp_name'];
    $fileContent = file_get_contents($uploadedFilePath);

    // Montar o e-mail
    $to = 'sourafaw@gmail.com'; // Substitua pelo endereço de destino
    $subject = 'Nova candidatura - Trabalhe Conosco';
    $body = "
        Nome: $name\n
        E-mail: $email\n
        Telefone: $phone\n
        Área de Interesse: $interest\n
        Mensagem: $message
    ";

    $boundary = md5(time());
    $headers = "From: $email\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n";

    $emailBody = "--$boundary\r\n";
    $emailBody .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $emailBody .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $emailBody .= $body . "\r\n";

    // Anexar o arquivo
    $emailBody .= "--$boundary\r\n";
    $emailBody .= "Content-Type: application/octet-stream; name=\"{$resume['name']}\"\r\n";
    $emailBody .= "Content-Transfer-Encoding: base64\r\n";
    $emailBody .= "Content-Disposition: attachment; filename=\"{$resume['name']}\"\r\n\r\n";
    $emailBody .= chunk_split(base64_encode($fileContent)) . "\r\n";
    $emailBody .= "--$boundary--";

    // Enviar o e-mail
    if (mail($to, $subject, $emailBody, $headers)) {
        echo json_encode(['success' => true, 'message' => 'Candidatura enviada com sucesso!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao enviar a candidatura. Tente novamente mais tarde.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método de requisição inválido.']);
}
?>
