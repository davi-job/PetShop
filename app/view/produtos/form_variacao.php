<?php ob_start(); ?>
<h2>Nova Variação — <?= htmlspecialchars($produto->getNome()) ?></h2>
<div class="card" style="max-width:480px;">
    <form method="post" action="?page=produtos">
        <input type="hidden" name="acao" value="salvarVariacao">
        <input type="hidden" name="produto_id" value="<?= $produto->getId() ?>">
        <label>Nome da Variação</label>
        <input type="text" name="nome" required placeholder="Ex: 500g, Tamanho M, Sabor Frango...">
        <label>Preço (R$)</label>
        <input type="number" name="preco" step="0.01" min="0" required>
        <label>Estoque inicial</label>
        <input type="number" name="estoque" min="0" value="0" required>
        <div class="acoes-form">
            <button type="submit">Salvar</button>
            <a href="?page=produtos&acao=editar&id=<?= $produto->getId() ?>" class="btn btn-sec">Cancelar</a>
        </div>
    </form>
</div>
<?php
$conteudo = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
