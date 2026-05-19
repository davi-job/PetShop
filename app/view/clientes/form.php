<?php ob_start(); ?>
<h2><?= $cliente ? 'Editar Cliente' : 'Novo Cliente' ?></h2>
<div class="card" style="max-width:520px;">
    <form method="post" action="?page=clientes">
        <input type="hidden" name="acao" value="<?= $cliente ? 'atualizar' : 'salvar' ?>">
        <?php if ($cliente): ?>
            <input type="hidden" name="id" value="<?= $cliente->getId() ?>">
        <?php endif; ?>
        <label>Nome</label>
        <input type="text" name="nome" required value="<?= $cliente ? htmlspecialchars($cliente->getNome()) : '' ?>">
        <label>CPF</label>
        <input type="text" name="cpf" required value="<?= $cliente ? htmlspecialchars($cliente->getCpf()) : '' ?>">
        <label>Telefone</label>
        <input type="text" name="telefone" value="<?= $cliente ? htmlspecialchars($cliente->getTelefone()) : '' ?>">
        <label>E-mail</label>
        <input type="email" name="email" value="<?= $cliente ? htmlspecialchars($cliente->getEmail()) : '' ?>">
        <label>Endereço</label>
        <textarea name="endereco"><?= $cliente ? htmlspecialchars($cliente->getEndereco()) : '' ?></textarea>
        <div class="acoes-form">
            <button type="submit">Salvar</button>
            <a href="?page=clientes" class="btn btn-sec">Cancelar</a>
        </div>
    </form>
</div>
<?php
$conteudo = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
