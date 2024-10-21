<?php
    require('scripts/conectaBanco.php');
    require('scripts/estaLogado.php');

    $usuario_id = $_SESSION['UsuarioId'];
    $query = "
        SELECT r.*, GROUP_CONCAT(i.ingrediente SEPARATOR ', ') AS ingredientes 
        FROM receitas r
        LEFT JOIN ingredientes i ON r.Id_receita = i.Id_receita
        WHERE r.criador = :usuario_id
        GROUP BY r.Id_receita
    ";
    $stmt = $conexao->prepare($query);
    $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
    $stmt->execute();
    $receitas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (isset($_SESSION['usuario_validado']) && $_SESSION['usuario_validado'] == true) {
        $usuario_id = $_SESSION['UsuarioId'];
        $query = "SELECT UsuImagem FROM usuarios WHERE UsuId = :usuario_id";
        $stmt = $conexao->prepare($query);
        $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        $imagemPerfil = $usuario['UsuImagem'];
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="img/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/suasReceitas.css">
    <link rel="stylesheet" href="styles/cabecalho.css">
    <title>Minhas Receitas</title>
    <script>
        function confirmarCongelar(url) {
            if (confirm("Você tem certeza que deseja congelar esta receita?")) {
                window.location.href = url;
            }
        }
    </script>
</head>
<body>
    <header>
        <div class="cabecalho">
            <img src="img/logo.png" alt="Logo" width="80px">
            <a href="index.php" class="nav-link">Página Inicial</a>
            <a href="enviarReceita.php" class="nav-link">Enviar Receita</a>
            <div class="dropdown">
                <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="ClientesFoto/<?php echo $imagemPerfil; ?>" alt="" class="img-perfil" width="40px">
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="suaArea.php">Sua área</a></li>
                    <li><a class="dropdown-item" href="scripts/LogOff.php">Sair</a></li>
                </ul>
            </div>
        </div>
    </header>
    <main class="container mt-5">
        <?php  if (isset($_GET['mensagem']) && $_GET['mensagem'] == 'Erro2') {?>
            <div class="alert alert-danger" role="alert">
                Receita Não encontrada
            </div>
        <?php } ?>
        <div class="row">
            <?php if (empty($receitas)) { ?>
                <div class="col-12 text-center">
                    <h5>Você ainda não tem nenhuma receita cadastrada.</h5>
                    <a href="enviarReceita.php" class="btn btn-primary botao">Cadastrar Receita</a>
                </div>
            <?php } else { ?>
                <?php foreach ($receitas as $row) { ?>
                    <div class="col-md-4 mb-4">
                        <div class="card custom-card">
                            <img src="<?php echo $row['imagem']; ?>" class="card-img-top" alt="Imagem da Receita">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $row['nome']; ?></h5>
                                <div class="d-flex justify-content-between">
                                    <button class="btn botao" data-bs-toggle="modal" data-bs-target="#modalReceita<?php echo $row['Id_receita']; ?>">Ver Detalhes</button>
                                    <a href="editarReceita.php?id=<?php echo $row['Id_receita']; ?>" class="btn botao">Editar</a>
                                    <a href="#"
                                        onclick="confirmarCongelarDescongelar(<?php echo $row['Id_receita']; ?>, <?php echo $row['congelado']; ?>)"
                                        class="btn <?php echo ($row['congelado'] == 0) ? 'btn-danger' : 'btn-success'; ?>">
                                        <?php echo ($row['congelado'] == 1) ? 'Descongelar' : 'Congelar'; ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="modalReceita<?php echo $row['Id_receita']; ?>" tabindex="-1" aria-labelledby="modalLabel<?php echo $row['Id_receita']; ?>" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalLabel<?php echo $row['Id_receita']; ?>"><?php echo $row['nome']; ?></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <h6>Categoria:</h6>
                                    <p><?php echo $row['categoria']; ?></p>
                                    <h6>Ingredientes:</h6>
                                    <ul>
                                        <?php
                                        $ingredientes = explode(',', $row['ingredientes']);
                                        foreach ($ingredientes as $ingrediente) {
                                            echo "<li>" . trim($ingrediente) . "</li>";
                                        }
                                        ?>
                                    </ul>
                                    <h6>Modo de Preparo:</h6>
                                    <p><?php echo $row['preparo']; ?></p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            <?php } ?>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmarCongelarDescongelar(id_receita, congelado) {
            var acao = congelado == 1 ? "descongelar" : "congelar";
            var mensagem = "Tem certeza que deseja " + acao + " esta receita?";
            if (confirm(mensagem)) {
                var url = congelado == 1 ? 'scripts/descongelarReceita.php?id=' + id_receita : 'scripts/congelarReceita.php?id=' + id_receita;
                window.location.href = url;
            }
        }
    </script>
</body>
</html>