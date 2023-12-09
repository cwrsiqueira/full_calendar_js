
/**
 * Aguarda o carregamento da página
 */
document.addEventListener('DOMContentLoaded', function () {

    /**
     * Armazena o elemento com o id calendar na variável calendarEl
     */
    var calendarEl = document.getElementById('calendar');

    /**
     * Inicia e configura o Full Calendar conf. documentação
     * https://fullcalendar.io/docs
     */
    var calendar = new FullCalendar.Calendar(calendarEl, {
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        initialView: 'dayGridMonth',
        locale: 'pt-br',
        navLinks: true,
        selectable: true,
        selectMirror: true,
        editable: true,
        dayMaxEvents: true,
        dateClick: function (info) {
            let date = info.dateStr;
            abrirModal(date);
        },
        eventClick: function (info) {
            abrirModalEditar(info.event);
        },
        eventDrop: function (info) {
            moverEvento(info);
        },
        events: 'lista-eventos.php',
    });
    /**
     * Renderiza o calendário
     */
    calendar.render();

    /** Armazena o elemento modal com as classes informadas, na variável */
    let modal = document.querySelector('.modal-opened.modal-cadastro');

    /**
     * Abre o modal de cadastro e preenche a data clicada, no campo correspondente
     * @param {object} date data recebida do elemento clicado no calendário
     */
    const abrirModal = (date) => {
        if (modal.classList.contains('hidden')) {
            modal.classList.remove('hidden');

            modal.style.transition = 'opacity 300ms';

            setTimeout(() => modal.style.opacity = '1', 100);
        }
        modal.querySelector('#ev_start').value = date + " 08:00";
        modal.querySelector('#ev_end').value = date + " 22:00";
    }

    /**
     * Recebe e trata os dados do elemento movido no calendário
     * Envia os dados tratados via Ajax para o arquivo PHP que faz as devidas alterações
     * e atualiza a movimentação do evento no Banco de dados
     * @param {object} info dados do elemento movido no calendário
     */
    const moverEvento = (info) => {
        let id = info.event.id;
        let title = info.event.title;
        let color = info.event.backgroundColor;
        let ev_start = info.event.startStr.substring(0, 19);
        let ev_end = info.event.endStr.substring(0, 19);

        let data = { id, title, color, ev_start, ev_end };

        let urlEncodedData = Object.keys(data).map(key =>
            encodeURIComponent(key) + '=' + encodeURIComponent(data[key])
        ).join('&');

        console.log(urlEncodedData);

        // Exemplo de requisição POST
        var ajax = new XMLHttpRequest();

        // Seta tipo de requisição: Post e a URL da API
        ajax.open("POST", "action-event.php", true);
        ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

        // Seta paramêtros da requisição e envia a requisição
        ajax.send(urlEncodedData);

        // Cria um evento para receber o retorno.
        ajax.onreadystatechange = function () {
            // Caso o state seja 4 e o http.status for 200, é porque a requisiçõe deu certo.
            if (ajax.readyState == 4 && ajax.status == 200) {
                var data = ajax.responseText;
                // Retorno do Ajax
                // console.log(data);
            }
        }
    }

    /**
     * Recebe os dados do elemento clicado no calendário
     * Abre o modal de edição do evento
     * e preenche os dados do evento no formulário de edição
     * @param {object} data dados do elemento clicado no calendário
     */
    const abrirModalEditar = (data) => {
        if (modal.classList.contains('hidden')) {
            modal.classList.remove('hidden');

            modal.style.transition = 'opacity 300ms';

            setTimeout(() => modal.style.opacity = '1', 100);
        }

        let date_start = data.startStr.split('T');
        let time_start = date_start[1].split('-')[0];
        date_start = date_start[0] + ' ' + time_start;

        let date_end = data.endStr.split('T');

        let time_end = date_end[1].split('-')[0];
        date_end = date_end[0] + ' ' + time_end;

        modal.querySelector('#id').value = data.id;
        modal.querySelector('#title').value = data.title;
        modal.querySelector('#color').value = data.backgroundColor;
        modal.querySelector('#ev_start').value = date_start;
        modal.querySelector('#ev_end').value = date_end;
        modal.querySelector('.btn-delete').classList.remove('hidden');
    }

    /**
     * Fecha o modal de cadastro e edição
     */
    const fecharModal = () => {
        if (!modal.classList.contains('hidden')) {

            modal.style.transition = 'opacity 300ms';

            setTimeout(() => {
                modal.style.opacity = '0';
            }, 100);

            setTimeout(() => {
                modal.classList.add('hidden');
                modal.querySelector('#id').value = '';
                modal.querySelector('#title').value = '';
                modal.querySelector('#color').value = '';
                modal.querySelector('#ev_start').value = '';
                modal.querySelector('#ev_end').value = '';
            }, 300);
        }
    }

    /**
     * Aguarda o evento de clique no elemento para fechar o modal
     */
    document.querySelector('.modal-close').addEventListener('click', function () {
        fecharModal();
    });

    /**
     * Fecha o modal ao clicar fora do formulário do modal de cadastro e edição
     */
    modal.addEventListener('click', function (event) {
        if (event.target === this) {
            fecharModal();
        }
    });

    /**
     * Fecha o modal ao pressionar a tecla ESC (escape) no teclado
     */
    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            fecharModal();
        }
    });

    /**
     * Verifica se os campos obrigatórios estão preenchidos
     * Emite um alerta se não estiverem
     * Submete o formulário caso estiverem preenchidos
     */
    document.querySelector('#form-add-event').addEventListener('submit', function (e) {
        e.preventDefault();
        let title = document.querySelector('#title');
        let ev_start = document.querySelector('#ev_start');

        if (title.value == '') {
            title.style.borderColor = 'red';
            title.focus();
            return false;
        }
        if (ev_start.value == '') {
            ev_start.style.borderColor = 'red';
            ev_start.focus();
            return false;
        }
        this.submit();
    });

    /**
     * Faz a confirmação via Javascript através do alert para excluir um evento
     */
    document.querySelector('.btn-delete').addEventListener('click', function () {
        if (confirm('Você quer mesmo excluir esse evento definitivamente? Esta ação não tem retorno.')) {
            document.querySelector('#action').value = 'delete';
            document.querySelector('#form-add-event').submit();
            return true;
        }
        return false;
    });
});