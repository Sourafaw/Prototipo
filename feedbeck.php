<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Receber dados do formulário
    $nome = htmlspecialchars($_POST['nome']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $telefone = htmlspecialchars($_POST['telefone']);
    $feedback = htmlspecialchars($_POST['feedback']);

    // Validação básica
    if (empty($nome) || empty($email) || empty($telefone) || empty($feedback)) {
        echo json_encode(['success' => false, 'message' => 'Por favor, preencha todos os campos.']);
        exit;
    }

    // Montar o e-mail
    $to = 'sourafaw@gmail.com'; // Substitua pelo endereço de destino
    $subject = 'Novo feedback recebido';
    $body = "
        Nome: $nome\n
        E-mail: $email\n
        Telefone: $telefone\n
        Feedback:\n
        $feedback
    ";
    $headers = "From: $email\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    // Enviar o e-mail
    if (mail($to, $subject, $body, $headers)) {
        echo json_encode(['success' => true, 'message' => 'Feedback enviado com sucesso!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao enviar o feedback. Tente novamente mais tarde.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método de requisição inválido.']);
}
?>
