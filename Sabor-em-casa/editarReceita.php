<?php
    require('scripts/conectaBanco.php');
    require('scripts/estaLogado.php');

    if (!isset($_GET['id'])) {
        header("Location: suaArea.php?mensagem=ID da receita não especificado.");
        exit();
    }
    $id_receita = $_GET['id'];

    $query = "
        SELECT r.*, GROUP_CONCAT(i.ingrediente SEPARATOR ', ') AS ingredientes 
        FROM receitas r
        LEFT JOIN ingredientes i ON r.Id_receita = i.Id_receita
        WHERE r.Id_receita = :id_receita AND r.criador = :usuario_id
        GROUP BY r.Id_receita
    ";
    $stmt = $conexao->prepare($query);
    $stmt->bindParam(':id_receita', $id_receita, PDO::PARAM_INT);
    $stmt->bindParam(':usuario_id', $_SESSION['UsuarioId'], PDO::PARAM_INT);
    $stmt->execute();
    $receita = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$receita) {
        header("Location: suasReceitas.php?mensagem=Erro2");
        exit();
    }

    $ingredientes = explode(', ', $receita['ingredientes']);

    $imagemPerfil = null;
    if (isset($_SESSION['usuario_validado']) && $_SESSION['usuario_validado'] == true) {
        $usuario_id = $_SESSION['UsuarioId'];
        $query = "SELECT UsuImagem FROM usuarios WHERE UsuId = :usuario_id";
        $stmt = $conexao->prepare($query);
        $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        $imagemPerfil = $usuario['UsuImagem']; // Obtém a imagem de perfil
    }
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="img/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/editarReceita.css">
    <link rel="stylesheet" href="styles/cabecalho.css">
    <title>Editar Receita</title>
</head>

<body>
    <header>
        <div class="cabecalho">
            <img src="img/logo.png" alt="Logo" width="80px">
            <a href="index.php" class="nav-link">Página Inicial</a>
            <a href="enviarReceita.php" class="nav-link">Enviar Receita</a>
            <div class="dropdown">
                <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="ClientesFoto/<?php echo $imagemPerfil ? $imagemPerfil : 'padrao.png'; ?>" alt="" class="img-perfil" width="40px">
                </button>
                <ul class="dropdown-menu">
                    <?php if (!isset($_SESSION['usuario_validado']) || $_SESSION['usuario_validado'] == false) { ?>
                        <li><a class="dropdown-item" href="login.php">Fazer Login</a></li>
                    <?php } else { ?>
                        <li><a class="dropdown-item" href="suaArea.php">Sua área</a></li>
                        <li><a class="dropdown-item" href="scripts/LogOff.php">Sair</a></li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </header>

    <main class="container mt-5">
        <h2>Editar Receita: <?php echo htmlspecialchars($receita['nome']); ?></h2>
        <form action="scripts/ValidaEdicaoReceita.php?id=<?php echo $id_receita; ?>" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome da Receita</label>
                <input type="text" class="form-control" id="nome" name="nome" value="<?php echo htmlspecialchars($receita['nome']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="imagem" class="form-label">Imagem da Receita</label>
                <input type="file" class="form-control" id="imagem" name="imagem">
                <img src="<?php echo htmlspecialchars($receita['imagem']); ?>" alt="Imagem da Receita" class="img-thumbnail mt-2" width="200">
            </div>

            <div class="mb-3">
                <label for="ingredientes" class="form-label">Ingredientes</label>
                <div id="ingredientes-container">
                    <?php foreach ($ingredientes as $index => $ingrediente) : ?>
                        <div class="input-group mb-2">
                            <input type="text" class="form-control" name="ingredientes[]" value="<?php echo htmlspecialchars($ingrediente); ?>" required>
                            <button type="button" class="btn btn-danger" onclick="removerIngrediente(this)">Remover</button>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="btn btn-primary mt-2" onclick="adicionarIngrediente()">Adicionar Ingrediente</button>
            </div>

            <div class="mb-3">
                <label for="categoria" class="form-label">Categoria</label>
                <select class="form-select" id="categoria" name="categoria" required>
                    <option value="" disabled>Selecione uma categoria</option>
                    <option value="entradas" <?php echo ($receita['categoria'] == 'entradas') ? 'selected' : ''; ?>>Entrada</option>
                    <option value="principal" <?php echo ($receita['categoria'] == 'principal') ? 'selected' : ''; ?>>Prato Principal</option>
                    <option value="sobremesas" <?php echo ($receita['categoria'] == 'sobremesas') ? 'selected' : ''; ?>>Sobremesa</option>
                    <option value="bebidas" <?php echo ($receita['categoria'] == 'bebidas') ? 'selected' : ''; ?>>Bebida</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="preparo" class="form-label">Modo de Preparo</label>
                <textarea class="form-control" id="preparo" name="preparo" rows="5" required><?php echo htmlspecialchars($receita['preparo']); ?></textarea>
            </div>

            <button type="submit" class="btn btn-success">Salvar Alterações</button>
            <a href="suaArea.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </main>

    <script>
        function adicionarIngrediente() {
            const container = document.getElementById('ingredientes-container');
            const inputGroup = document.createElement('div');
            inputGroup.className = 'input-group mb-2';
            inputGroup.innerHTML = `
                <input type="text" class="form-control" name="ingredientes[]" required>
                <button type="button" class="btn btn-danger" onclick="removerIngrediente(this)">Remover</button>
            `;
            container.appendChild(inputGroup);
        }

        function removerIngrediente(button) {
            const inputGroup = button.parentElement;
            inputGroup.remove();
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>