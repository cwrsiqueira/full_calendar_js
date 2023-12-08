<?php session_start(); ?>
<!DOCTYPE html>
<html lang='pt-br'>

<head>
    <meta charset='utf-8' />
    <title>Full Calendar</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <div class="msg">
        <?php if (!empty($_SESSION['msg'])) {
            echo $_SESSION['msg'];
            unset($_SESSION['msg']);
        } ?>
    </div>

    <div class="modal-opened hidden">
        <div class="modal">
            <div class="modal-header">
                <div class="modal-title">
                    <h3>Cadastrar Evento</h3>
                </div>
                <div class="modal-close"><i class="fa-solid fa-xmark"></i></div>
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
        <div id='calendar'></div>
    </div>

    <script src='dist/index.global.min.js'></script>
    <script src="core/locales/pt-br.global.min.js"></script>
    <script src="script.js"></script>
</body>

</html>