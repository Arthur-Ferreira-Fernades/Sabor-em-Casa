<?php
    require('scripts/conectaBanco.php');
    require('scripts/estaLogado.php');

    $usuario_id = $_SESSION['UsuarioId'];

    if (isset($_SESSION['usuario_validado']) && $_SESSION['usuario_validado'] == true) {
        $usuario_id = $_SESSION['UsuarioId'];
        $query = "SELECT UsuImagem FROM usuarios WHERE UsuId = :usuario_id";
        $stmt = $conexao->prepare($query);
        $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        $imagemPerfil = $usuario['UsuImagem'];
    }

    $query = "
        SELECT r.*, u.UsuNome AS Criador, GROUP_CONCAT(i.ingrediente SEPARATOR ', ') AS ingredientes 
        FROM favoritos f
        JOIN receitas r ON f.receita_id = r.Id_receita
        LEFT JOIN ingredientes i ON r.Id_receita = i.Id_receita
        LEFT JOIN usuarios u ON r.criador = u.UsuId  -- Certifique-se de que este campo está correto
        WHERE f.usuario_id = :usuario_id
        GROUP BY r.Id_receita
    ";
    $stmt = $conexao->prepare($query);
    $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
    $stmt->execute();
    $receitasFavoritas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="img/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/cabecalho.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="styles/index.css">
    <title>Receitas Favoritas</title>
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
    <div class="conteudo">
        <div class="container-com-borda text-center">
            <h2>Minhas Receitas Favoritas</h2>
            <div id="receitas" class="receitas text-center row">
                <?php if (empty($receitasFavoritas)) { ?>
                    <p>Nenhuma receita favorita encontrada.</p>
                    <?php } else {
                    foreach ($receitasFavoritas as $receita) { ?>
                        <div class="col-md-4">
                            <div class="card cartao">
                                <img src="<?php echo $receita['imagem']; ?>" class="card-img-top" alt="<?php echo $receita['nome']; ?>">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $receita['nome']; ?></h5>
                                    <p class="card-text">Criador: <?php echo $receita['Criador'] ? $receita['Criador'] : 'Desconhecido'; ?></p>
                                    <button type="button" class="btn verReceita" data-bs-toggle="modal" data-bs-target="#<?php echo $receita['Id_receita']; ?>">
                                        Ver receita
                                    </button>
                                    <button type="button" class="btn favoritar" onclick="confirmarRemoverFavorito(<?php echo $receita['Id_receita']; ?>)">
                                        <i class="fa fa-heart favoritado" aria-hidden="true"></i>
                                    </button>
                                    <!-- Modal -->
                                    <div class="modal fade" id="<?php echo $receita['Id_receita']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="exampleModalLabel"><?php echo $receita['nome']; ?></h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <strong>Ingredientes:</strong>
                                                    <ul>
                                                        <?php echo implode('', array_map(fn($ing) => "<li>$ing</li>", explode(', ', $receita['ingredientes']))); ?>
                                                    </ul>
                                                    <p><?php echo $receita['preparo']; ?></p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                <?php }
                } ?>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmarRemoverFavorito(receitaId) {
            if (confirm("Tem certeza de que deseja remover esta receita dos favoritos?")) {
                removerFavorito(receitaId);
            }
        }

        async function removerFavorito(receitaId) {
            const response = await fetch(`scripts/removerFavorito.php?receitaId=${receitaId}`, {
                method: 'POST'
            });

            if (response.ok) {
                const resultado = await response.json();
                if (resultado.removido) {
                    alert("Receita removida dos favoritos!");
                    location.reload();
                } else {
                    alert("Erro ao remover a receita dos favoritos.");
                }
            } else {
                alert("Erro ao comunicar com o servidor.");
            }
        }
    </script>
</body>
</html>