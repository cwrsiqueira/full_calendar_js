<?php session_start();
/** Inicia uma sessão para receber as mensagens gravadas */ ?>

<!-- Inicia o HTML -->
<!DOCTYPE html>
<html lang='pt-br'>

<head>
    <meta charset='utf-8' />
    <title>Full Calendar</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <!-- Formulário modal de cadastro, edição e exclusão -->
    <div class="modal-opened hidden modal-cadastro">
        <div class="modal">
            <div class="modal-header">
                <div class="modal-title">
                    <h3>Cadastrar Evento</h3>
                </div>
                <div class="modal-close">x</div>
            </div>
            <form action="action-event.php" method="post" id="form-add-event">
                <div class="modal-body">
                    <input type="hidden" name="id" id="id">
                    <input type="hidden" name="action" id="action" value="">
                    <label for="title">Nome do Evento</label>
                    <input type="text" name="title" id="title">
                    <label for="color">Escolha uma cor</label>
                    <input type="color" name="color" id="color">
                    <label for="ev_start">Início</label>
                    <input type="datetime-local" name="ev_start" id="ev_start">
                    <label for="ev_end">Término</label>
                    <input type="datetime-local" name="ev_end" id="ev_end">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn-save">Salvar</button>
                    <button type="button" class="btn-delete hidden">Excluir</button>
                </div>
            </form>
        </div>
    </div>

    <div class="calendar-area">
        <!-- Mostra mensagens de sucesso ou erro, gravadas na sessão msg -->
        <div class="msg">
            <?php if (!empty($_SESSION['msg'])) {
                echo $_SESSION['msg'];
                unset($_SESSION['msg']);
            } ?>
        </div>

        <!-- Renderiza o calendário Full Calendar -->
        <div id='calendar'></div>
    </div>

    <!-- Arquivos js do Full Calendar -->
    <script src='dist/index.global.min.js'></script>
    <!-- Arquivos js da tradução do Full Calendar -->
    <script src="core/locales/pt-br.global.min.js"></script>
    <!-- Arquivo js customizado -->
    <script src="script.js"></script>
</body>

</html>