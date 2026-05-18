<?php

class PetController extends Controller {

    public function handle(): void {
        $this->requireAuth();
        $acao = $_GET['acao'] ?? $_POST['acao'] ?? 'listar';

        match ($acao) {
            'novo'      => $this->novo(),
            'salvar'    => $this->salvar(),
            'editar'    => $this->editar(),
            'atualizar' => $this->atualizar(),
            'deletar'   => $this->deletar(),
            default     => $this->listar(),
        };
    }

    private function listar(): void {
        $pets = (new PetDAO())->listar();
        $this->render('pets/list', ['pets' => $pets]);
    }

    private function novo(): void {
        $clientes = (new ClienteDAO())->listar();
        $this->render('pets/form', ['pet' => null, 'clientes' => $clientes]);
    }

    private function salvar(): void {
        $pet = new Pet();
        $pet->setClienteId((int) $_POST['cliente_id']);
        $pet->setNome($_POST['nome']);
        $pet->setEspecie($_POST['especie']);
        $pet->setRaca($_POST['raca']);
        $pet->setPorte(Porte::from($_POST['porte']));
        $pet->setPeso((float) $_POST['peso']);
        $pet->setDataNascimento(new DateTime($_POST['data_nascimento']));
        $pet->setObservacoes($_POST['observacoes'] ?? '');

        (new PetDAO())->salvar($pet);

        $_SESSION['flash'] = 'Pet cadastrado com sucesso.';
        $this->redirect('?page=pets');
    }

    private function editar(): void {
        $pet      = (new PetDAO())->buscarPorId((int) $_GET['id']);
        $clientes = (new ClienteDAO())->listar();
        $this->render('pets/form', ['pet' => $pet, 'clientes' => $clientes]);
    }

    private function atualizar(): void {
        $pet = (new PetDAO())->buscarPorId((int) $_POST['id']);
        $pet->setClienteId((int) $_POST['cliente_id']);
        $pet->setNome($_POST['nome']);
        $pet->setEspecie($_POST['especie']);
        $pet->setRaca($_POST['raca']);
        $pet->setPorte(Porte::from($_POST['porte']));
        $pet->setPeso((float) $_POST['peso']);
        $pet->setDataNascimento(new DateTime($_POST['data_nascimento']));
        $pet->setObservacoes($_POST['observacoes'] ?? '');

        (new PetDAO())->atualizar($pet);

        $_SESSION['flash'] = 'Pet atualizado com sucesso.';
        $this->redirect('?page=pets');
    }

    private function deletar(): void {
        (new PetDAO())->deletar((int) $_GET['id']);

        $_SESSION['flash'] = 'Pet removido.';
        $this->redirect('?page=pets');
    }
}
