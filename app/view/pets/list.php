<?php ob_start(); ?>
<div class="topo-lista">
    <h2>Pets</h2>
    <a href="?page=pets&acao=novo" class="btn">+ Novo Pet</a>
</div>
<table>
    <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>Espécie</th>
        <th>Raça</th>
        <th>Porte</th>
        <th>Peso (kg)</th>
        <th>Ações</th>
    </tr>
    <?php foreach ($pets as $pet): ?>
    <tr>
        <td><?= $pet->getId() ?></td>
        <td><?= htmlspecialchars($pet->getNome()) ?></td>
        <td><?= htmlspecialchars($pet->getEspecie()) ?></td>
        <td><?= htmlspecialchars($pet->getRaca()) ?></td>
        <td><?= $pet->getPorte()->value ?></td>
        <td><?= number_format($pet->getPeso(), 2, ',', '.') ?></td>
        <td class="acoes">
            <a href="?page=pets&acao=editar&id=<?= $pet->getId() ?>" class="btn btn-sec">Editar</a>
            <a href="?page=pets&acao=deletar&id=<?= $pet->getId() ?>" class="btn btn-perigo"
               onclick="return confirm('Excluir pet?')">Excluir</a>
        </td>
    </tr>
    <?php endforeach; ?>
    <?php if (empty($pets)): ?>
    <tr><td colspan="7" style="text-align:center; color:#888;">Nenhum pet cadastrado.</td></tr>
    <?php endif; ?>
</table>
<?php
$conteudo = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
