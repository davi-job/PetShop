<?php ob_start(); ?>
<div class="topo-lista">
    <h2>Serviços</h2>
    <a href="?page=servicos&acao=novo" class="btn">+ Novo Serviço</a>
</div>
<table>
    <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>Descrição</th>
        <th>Ações</th>
    </tr>
    <?php foreach ($servicos as $servico): ?>
    <tr>
        <td><?= $servico->getId() ?></td>
        <td><?= htmlspecialchars($servico->getNome()) ?></td>
        <td><?= htmlspecialchars($servico->getDescricao()) ?></td>
        <td class="acoes">
            <a href="?page=servicos&acao=editar&id=<?= $servico->getId() ?>" class="btn btn-sec">Editar</a>
            <a href="?page=servicos&acao=deletar&id=<?= $servico->getId() ?>" class="btn btn-perigo"
               onclick="return confirm('Excluir serviço?')">Excluir</a>
        </td>
    </tr>
    <?php endforeach; ?>
    <?php if (empty($servicos)): ?>
    <tr><td colspan="4" style="text-align:center; color:#888;">Nenhum serviço cadastrado.</td></tr>
    <?php endif; ?>
</table>
<?php
$conteudo = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
