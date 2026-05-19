<?php ob_start(); ?>
<h2><?= $categoria ? 'Editar Categoria' : 'Nova Categoria' ?></h2>
<div class="card" style="max-width:500px;">
    <form method="post" action="?page=categorias">
        <input type="hidden" name="acao" value="<?= $categoria ? 'atualizar' : 'salvar' ?>">
        <?php if ($categoria): ?>
            <input type="hidden" name="id" value="<?= $categoria->getId() ?>">
        <?php endif; ?>
        <label>Nome</label>
        <input type="text" name="nome" required value="<?= $categoria ? htmlspecialchars($categoria->getNome()) : '' ?>">
        <label>Descrição</label>
        <textarea name="descricao"><?= $categoria ? htmlspecialchars($categoria->getDescricao()) : '' ?></textarea>
        <div class="acoes-form">
            <button type="submit">Salvar</button>
            <a href="?page=categorias" class="btn btn-sec">Cancelar</a>
        </div>
    </form>
</div>
<?php
$conteudo = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
