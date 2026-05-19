<?php ob_start(); ?>
<div class="topo-lista">
    <h2>Clientes</h2>
    <a href="?page=clientes&acao=novo" class="btn">+ Novo Cliente</a>
</div>
<table>
    <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>CPF</th>
        <th>Telefone</th>
        <th>E-mail</th>
        <th>Ações</th>
    </tr>
    <?php foreach ($clientes as $cliente): ?>
    <tr>
        <td><?= $cliente->getId() ?></td>
        <td><?= htmlspecialchars($cliente->getNome()) ?></td>
        <td><?= htmlspecialchars($cliente->getCpf()) ?></td>
        <td><?= htmlspecialchars($cliente->getTelefone()) ?></td>
        <td><?= htmlspecialchars($cliente->getEmail()) ?></td>
        <td class="acoes">
            <a href="?page=clientes&acao=editar&id=<?= $cliente->getId() ?>" class="btn btn-sec">Editar</a>
            <a href="?page=clientes&acao=deletar&id=<?= $cliente->getId() ?>" class="btn btn-perigo"
               onclick="return confirm('Excluir cliente?')">Excluir</a>
        </td>
    </tr>
    <?php endforeach; ?>
    <?php if (empty($clientes)): ?>
    <tr><td colspan="6" style="text-align:center; color:#888;">Nenhum cliente cadastrado.</td></tr>
    <?php endif; ?>
</table>
<?php
$conteudo = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
