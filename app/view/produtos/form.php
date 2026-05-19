<?php ob_start(); ?>
<h2><?= $produto ? 'Editar Produto' : 'Novo Produto' ?></h2>
<div class="card" style="max-width:520px;">
    <form method="post" action="?page=produtos">
        <input type="hidden" name="acao" value="<?= $produto ? 'atualizar' : 'salvar' ?>">
        <?php if ($produto): ?>
            <input type="hidden" name="id" value="<?= $produto->getId() ?>">
        <?php endif; ?>
        <label>Categoria</label>
        <select name="categoria_id" required>
            <option value="">-- Selecione --</option>
            <?php foreach ($categorias as $cat): ?>
                <option value="<?= $cat->getId() ?>"
                    <?= ($produto && $produto->getCategoriaId() === $cat->getId()) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat->getNome()) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <label>Nome</label>
        <input type="text" name="nome" required value="<?= $produto ? htmlspecialchars($produto->getNome()) : '' ?>">
        <label>Descrição</label>
        <textarea name="descricao"><?= $produto ? htmlspecialchars($produto->getDescricao()) : '' ?></textarea>
        <div class="acoes-form">
            <button type="submit">Salvar</button>
            <a href="?page=produtos" class="btn btn-sec">Cancelar</a>
        </div>
    </form>
</div>

<?php if ($produto && !empty($variacoes)): ?>
<h3 style="margin-top:24px; margin-bottom:10px;">Variações</h3>
<table>
    <tr>
        <th>Nome</th>
        <th>Preço</th>
        <th>Estoque</th>
        <th>Ações</th>
    </tr>
    <?php foreach ($variacoes as $v): ?>
    <tr>
        <td><?= htmlspecialchars($v->getNome()) ?></td>
        <td>R$ <?= number_format($v->getPreco(), 2, ',', '.') ?></td>
        <td><?= $v->getEstoque() ?></td>
        <td class="acoes">
            <a href="?page=produtos&acao=deletarVariacao&id=<?= $v->getId() ?>" class="btn btn-perigo"
               onclick="return confirm('Excluir variação?')">Excluir</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<?php endif; ?>

<?php if ($produto): ?>
<div style="margin-top:14px;">
    <a href="?page=produtos&acao=novaVariacao&id=<?= $produto->getId() ?>" class="btn">+ Nova Variação</a>
</div>
<?php endif; ?>
<?php
$conteudo = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
