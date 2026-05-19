<?php ob_start(); ?>
<h2><?= $servico ? 'Editar Serviço' : 'Novo Serviço' ?></h2>
<div class="card" style="max-width:500px;">
    <form method="post" action="?page=servicos">
        <input type="hidden" name="acao" value="<?= $servico ? 'atualizar' : 'salvar' ?>">
        <?php if ($servico): ?>
            <input type="hidden" name="id" value="<?= $servico->getId() ?>">
        <?php endif; ?>
        <label>Nome</label>
        <input type="text" name="nome" required value="<?= $servico ? htmlspecialchars($servico->getNome()) : '' ?>">
        <label>Descrição</label>
        <textarea name="descricao"><?= $servico ? htmlspecialchars($servico->getDescricao()) : '' ?></textarea>
        <div class="acoes-form">
            <button type="submit">Salvar</button>
            <a href="?page=servicos" class="btn btn-sec">Cancelar</a>
        </div>
    </form>
</div>
<?php
$conteudo = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
