<?php
    require('scripts/conectaBanco.php');
    session_start();

    $imagemPerfil = null;
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
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="img/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/enviarReceita.css">
    <link rel="stylesheet" href="styles/cabecalho.css">
    <title>Enviar Receita</title>
</head>
<body>
    <header>
        <div class="cabecalho">
            <img src="img/logo.png" alt="Logo" width="80px">
            <a href="index.php" class="nav-link">Página Inicial</a>
            <a href="enviarReceita.php" class="nav-link">Enviar Receita</a>
            <div class="dropdown">
                <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="ClientesFoto/<?php if ($imagemPerfil) {echo ($imagemPerfil);} else {echo ("padrao.png");}; ?>" alt="" class="img-perfil" width="40px">
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
    <div class="container mt-5">
        <h2>Enviar Receita</h2>
        <form action="scripts/ValidaReceita.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome da Receita</label>
                <input type="text" class="form-control" id="nome" name="nome" required>
            </div>
            <div class="mb-3">
                <label for="foto" class="form-label">Foto da Receita</label>
                <input type="file" class="form-control" id="foto" name="foto" accept="image/*" required>
            </div>
            <div class="mb-3">
                <label for="metodo" class="form-label">Método de Preparo</label>
                <textarea class="form-control" id="metodo" name="metodo" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label for="categoria" class="form-label">Categoria</label>
                <select class="form-select" id="categoria" name="categoria" required>
                    <option value="">Selecione uma categoria</option>
                    <option value="entradas">Entradas</option>
                    <option value="principal">Prato Principal</option>
                    <option value="sobremesa">Sobremesa</option>
                    <option value="bebida">Bebida</option>
                </select>
            </div>
            <div id="ingredientes-container">
                <div class="mb-3">
                    <label for="ingrediente1" class="form-label">Ingrediente</label>
                    <input type="text" class="form-control" id="ingrediente1" name="ingredientes[]" required>
                </div>
            </div>
            <button type="button" class="btn btn-primary mt-2" onclick="AdicionaIngrediente()">+</button>
            <button type="button" class="btn btn-danger mt-2" onclick="RemoveIngrediente()" id="remove-button" style="display: none;">-</button>
            <div class="text-center mt-4">
                <button type="submit" class="btn" style="background-color: #7d0102; color: white;">Enviar Receita</button>
            </div>
        </form>
    </div>
    <script>
        let ContIngrediente = 1;
        function AdicionaIngrediente() {
            ContIngrediente++;
            const container = document.getElementById('ingredientes-container');
            const newInput = document.createElement('div');
            newInput.className = 'mb-3';
            newInput.innerHTML = `
                <label for="ingrediente${ContIngrediente}" class="form-label">Ingrediente</label>
                <input type="text" class="form-control" id="ingrediente${ContIngrediente}" name="ingredientes[]" required>
            `;
            container.appendChild(newInput);
            const BotaoRemover = document.getElementById('remove-button');
            BotaoRemover.style.display = (ContIngrediente > 1) ? 'inline-block' : 'none';
        }

        function RemoveIngrediente() {
            if (ContIngrediente > 1) {
                const container = document.getElementById('ingredientes-container');
                container.removeChild(container.lastChild);
                ContIngrediente--;
                const BotaoRemover = document.getElementById('remove-button');
                BotaoRemover.style.display = (ContIngrediente > 1) ? 'inline-block' : 'none';
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>