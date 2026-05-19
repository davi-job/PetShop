<?php ob_start(); ?>
<div class="topo-lista">
    <h2>Categorias</h2>
    <a href="?page=categorias&acao=novo" class="btn">+ Nova Categoria</a>
</div>
<table>
    <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>Descrição</th>
        <th>Ações</th>
    </tr>
    <?php foreach ($categorias as $categoria): ?>
    <tr>
        <td><?= $categoria->getId() ?></td>
        <td><?= htmlspecialchars($categoria->getNome()) ?></td>
        <td><?= htmlspecialchars($categoria->getDescricao()) ?></td>
        <td class="acoes">
            <a href="?page=categorias&acao=editar&id=<?= $categoria->getId() ?>" class="btn btn-sec">Editar</a>
            <a href="?page=categorias&acao=deletar&id=<?= $categoria->getId() ?>" class="btn btn-perigo"
               onclick="return confirm('Excluir categoria?')">Excluir</a>
        </td>
    </tr>
    <?php endforeach; ?>
    <?php if (empty($categorias)): ?>
    <tr><td colspan="4" style="text-align:center; color:#888;">Nenhuma categoria cadastrada.</td></tr>
    <?php endif; ?>
</table>
<?php
$conteudo = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
