<?php

class AgendamentoController extends Controller {

    public function handle(): void {
        $this->requireAuth();
        $acao = $_GET['acao'] ?? $_POST['acao'] ?? 'listar';

        match ($acao) {
            'novo'      => $this->novo(),
            'salvar'    => $this->salvar(),
            'ver'       => $this->ver(),
            'iniciar'   => $this->iniciar(),
            'concluir'  => $this->concluir(),
            'cancelar'  => $this->cancelar(),
            default     => $this->listar(),
        };
    }

    private function listar(): void {
        $agendamentos = (new AgendamentoDAO())->listar();
        $this->render('agendamentos/list', ['agendamentos' => $agendamentos]);
    }

    private function novo(): void {
        // R4: só atendente e gerente podem criar agendamentos
        $this->requireRole('atendente', 'gerente');
        $clientes    = (new ClienteDAO())->listar();
        $servicos    = (new ServicoDAO())->listar();
        $funcionarios = (new UsuarioDAO())->listar();
        $this->render('agendamentos/form', [
            'clientes'     => $clientes,
            'servicos'     => $servicos,
            'funcionarios' => $funcionarios,
        ]);
    }

    private function salvar(): void {
        $this->requireRole('atendente', 'gerente');

        $dataHora      = $_POST['data_hora'];
        $funcionarioId = (int) $_POST['funcionario_id'];
        $petId         = (int) $_POST['pet_id'];

        $agendamentoDAO = new AgendamentoDAO();

        // R1/R2: verifica se o funcionário está disponível no horário
        if (!$agendamentoDAO->verificarDisponibilidade($dataHora, $funcionarioId)) {
            $_SESSION['flash'] = 'Funcionário já possui agendamento nesse horário.';
            $this->redirect('?page=agendamentos&acao=novo');
        }

        $agendamento = new Agendamento();
        $agendamento->setPetId($petId);
        $agendamento->setCriadoPor(Auth::getUsuario()->getId());
        $agendamento->setDataHora(new DateTime($dataHora));

        $agendamentoId = $agendamentoDAO->salvar($agendamento);

        // R6: busca o preço pelo porte do pet
        $pet            = (new PetDAO())->buscarPorId($petId);
        $servicoPrecoDAO = new ServicoPrecoDAO();
        $asDAO          = new AgendamentoServicoDAO();

        foreach ((array) $_POST['servico_id'] as $servicoId) {
            $preco = $servicoPrecoDAO->buscarPorPorte((int) $servicoId, $pet->getPorte()->value);
            $precoCobrado = $preco ? $preco->getPreco() : 0.0;

            $as = new AgendamentoServico();
            $as->setAgendamentoId($agendamentoId);
            $as->setServicoId((int) $servicoId);
            $as->setPrecoCobrado($precoCobrado);
            $asDAO->salvar($as);
        }

        $_SESSION['flash'] = 'Agendamento criado com sucesso.';
        $this->redirect('?page=agendamentos');
    }

    private function ver(): void {
        $agendamento = (new AgendamentoDAO())->buscarPorId((int) $_GET['id']);
        $itens       = (new AgendamentoServicoDAO())->listarPorAgendamento($agendamento->getId());
        $this->render('agendamentos/ver', [
            'agendamento' => $agendamento,
            'itens'       => $itens,
        ]);
    }

    private function iniciar(): void {
        $dao         = new AgendamentoDAO();
        $agendamento = $dao->buscarPorId((int) $_GET['id']);
        $agendamento->iniciar();
        $dao->atualizar($agendamento);

        $_SESSION['flash'] = 'Agendamento iniciado.';
        $this->redirect('?page=agendamentos&acao=ver&id=' . $agendamento->getId());
    }

    private function concluir(): void {
        $dao         = new AgendamentoDAO();
        $agendamento = $dao->buscarPorId((int) $_GET['id']);
        $agendamento->concluir();
        $dao->atualizar($agendamento);

        $_SESSION['flash'] = 'Agendamento concluído.';
        $this->redirect('?page=agendamentos&acao=ver&id=' . $agendamento->getId());
    }

    private function cancelar(): void {
        $dao         = new AgendamentoDAO();
        $agendamento = $dao->buscarPorId((int) $_GET['id']);
        $agendamento->cancelar();
        $dao->atualizar($agendamento);

        $_SESSION['flash'] = 'Agendamento cancelado.';
        $this->redirect('?page=agendamentos');
    }
}
