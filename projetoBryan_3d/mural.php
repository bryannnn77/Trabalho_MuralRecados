<?php
include "conexao.php";

// Inserir novo pedido/recado
if(isset($_POST['cadastra'])){
    $nome  = mysqli_real_escape_string($conexao, $_POST['nome']);
    $email = mysqli_real_escape_string($conexao, $_POST['email']);
    $msg   = mysqli_real_escape_string($conexao, $_POST['msg']);

    $sql = "INSERT INTO recados (nome, email, mensagem) VALUES ('$nome', '$email', '$msg')";
    mysqli_query($conexao, $sql) or die("Erro ao inserir dados: " . mysqli_error($conexao));
    header("Location: mural.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8"/>
    <title>Mural de Recados</title>
    <link rel="stylesheet" href="styyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <script>
    $(document).ready(function() {
        $("#mural").validate({
            rules: {
                nome: { required: true, minlength: 4 },
                email: { required: true, email: true },
                msg: { required: true, minlength: 10 }
            },
            messages: {
                nome: { 
                    required: "Digite o seu nome", 
                    minlength: "O nome deve ter no mínimo 4 caracteres" 
                },
                email: { 
                    required: "Digite o seu e-mail", 
                    email: "Digite um e-mail válido" 
                },
                msg: { 
                    required: "Digite sua mensagem", 
                    minlength: "A mensagem deve ter no mínimo 10 caracteres" 
                }
            },
            errorElement: "span",
            errorPlacement: function(error, element) {
                error.addClass("error-message");
                error.insertAfter(element);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass("error-field");
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass("error-field");
            },
            submitHandler: function(form) {
                $(".btn").prop("disabled", true).html('<i class="fas fa-spinner fa-spin"></i> Publicando...');
                form.submit();
            }
        });
    });
    </script>
</head>
<body>
    <div id="main">
        <div id="geral">
            <div id="header">
                <h1><i class="fas fa-comments"></i> Mural de Recados</h1>
                <p>Compartilhe sua mensagem com todos. <a href="#">Saiba mais</a></p>
            </div>

            <div id="formulario_mural">
                <h2>Deixe seu recado</h2>
                <form id="mural" method="post">
                    <label for="nome">Nome:</label>
                    <input type="text" id="nome" name="nome" placeholder="Seu nome completo">
                    
                    <label for="email">E-mail:</label>
                    <input type="email" id="email" name="email" placeholder="seu@email.com">
                    
                    <label for="msg">Mensagem:</label>
                    <textarea id="msg" name="msg" placeholder="Digite sua mensagem aqui..."></textarea>
                    
                    <div class="submit">
                        <button type="submit" name="cadastra" class="btn">
                            <i class="fas fa-paper-plane"></i> Publicar no Mural
                        </button>
                    </div>
                </form>
            </div>

            <div id="recados-container">
                <?php
                $seleciona = mysqli_query($conexao, "SELECT * FROM recados ORDER BY id DESC");
                if(mysqli_num_rows($seleciona) > 0) {
                    while($res = mysqli_fetch_assoc($seleciona)) {
                        $iniciais = "";
                        $nomes = explode(" ", $res['nome']);
                        if(count($nomes) > 0) {
                            $iniciais = strtoupper(substr($nomes[0], 0, 1) . (count($nomes) > 1 ? substr(end($nomes), 0, 1) : ""));
                        }
                        
                        echo '<div class="recado">';
                        echo '<div class="avatar">' . $iniciais . '</div>';
                        echo '<div class="message-content">';
                        echo '<p class="message-author">' . htmlspecialchars($res['nome']) . '</p>';
                        echo '<p class="message-text">' . nl2br(htmlspecialchars($res['mensagem'])) . '</p>';
                        echo '<div class="message-meta">';
                        echo '<span class="message-time"><i class="far fa-clock"></i> ' . date('d/m/Y H:i', strtotime($res['data'])) . '</span>';
                        echo '<span class="message-email"><i class="far fa-envelope"></i> ' . htmlspecialchars($res['email']) . '</span>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo '<div class="no-messages">';
                    echo '<i class="far fa-comment-dots"></i>';
                    echo '<p>Nenhum recado ainda. Seja o primeiro a compartilhar!</p>';
                    echo '</div>';
                }
                ?>
            </div>

            <div id="footer">
                <p>&copy; <?php echo date('Y'); ?> Mural de Recados. <a href="#">Política de Privacidade</a> | <a href="#">Termos de Uso</a></p>
            </div>
        </div>
    </div>
</body>
</html>