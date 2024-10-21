<?php
    require('scripts/conectaBanco.php');
    session_start();

    $usuario_validado = isset($_SESSION['usuario_validado']) && $_SESSION['usuario_validado'] == true;
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
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="img/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/cabecalho.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="styles/index.css">
    <title>Home</title>
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
    <div class="conteudo">
        <div class="container-com-borda text-center">
            <div class="categorias">
                <button class="categoria-btn btn" onclick="showReceitas('entradas')">Entradas</button>
                <button class="categoria-btn btn" onclick="showReceitas('principal')">Pratos Principais</button>
                <button class="categoria-btn btn" onclick="showReceitas('sobremesa')">Sobremesas</button>
                <button class="categoria-btn btn" onclick="showReceitas('bebida')">Bebidas</button>
            </div>
            <div id="receitas" class="receitas text-center row"></div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const usuario_validado = <?php echo json_encode($usuario_validado); ?>; 
        async function showReceitas(categoria) {
            const buttons = document.querySelectorAll('.categoria-btn');
            buttons.forEach(button => button.classList.remove('active-category'));

            const activeButton = Array.from(buttons).find(button => button.textContent.toLowerCase() === categoria);
            if (activeButton) {
                activeButton.classList.add('active-category');
            }

            const receitasDiv = document.getElementById('receitas');
            receitasDiv.innerHTML = '';
            receitasDiv.style.display = 'flex';

            const response = await fetch(`scripts/pega-receitas.php?categoria=${categoria}`);
            const receitas = await response.json();

            receitas.forEach(receita => {
                const receitaCard = document.createElement('div');
                receitaCard.classList.add('col-md-4');
                receitaCard.innerHTML = `
                    <div class="card cartao">
                        <img src="${receita.imagem}" class="card-img-top" alt="${receita.nome}">
                        <div class="favoritar-container">
                            ${usuario_validado ? `
                            <button type="button" class="btn favoritar" onclick="favoritarReceita(${receita.Id_receita})">
                                <i class="heart-icon fa fa-heart ${receita.favoritado ? 'favoritado' : ''}" aria-hidden="true"></i>
                            </button>
                            ` : `
                            <i class="heart-icon fa fa-heart" aria-hidden="true"></i>
                            `}
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">${receita.nome}</h5>
                            <p class="card-text">Criador: ${receita.criador}</p>
                            <button type="button" class="btn verReceita" data-bs-toggle="modal" data-bs-target="#${receita.Id_receita}">
                                Ver receita
                            </button>

                            <!-- Modal -->
                            <div class="modal fade" id="${receita.Id_receita}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">${receita.nome}</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <strong>Ingredientes:</strong>
                                            <ul>
                                                ${receita.ingredientes.split(', ').map(ingrediente => `<li>${ingrediente}</li>`).join('')}
                                            </ul>
                                            <p>${receita.preparo}</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                receitasDiv.appendChild(receitaCard);
            });
        }

        window.onload = function() {
            showReceitas('entradas');
        };

        async function favoritarReceita(receitaId) {
            const response = await fetch(`scripts/favoritarReceita.php?receitaId=${receitaId}`, {
                method: 'POST'
            });

            if (response.ok) {
                const resultado = await response.json();
                if (resultado.favoritado) {
                    alert("Receita adicionada aos favoritos!");
                } else {
                    alert("Receita removida dos favoritos!");
                }
                window.location.reload();
            } else {
                alert("Erro ao favoritar a receita.");
            }
        }
    </script>
</body>
</html>