<?php ob_start(); ?>
<div class="topo-lista">
    <h2>Agendamentos</h2>
    <a href="?page=agendamentos&acao=novo" class="btn">+ Novo Agendamento</a>
</div>
<table>
    <tr>
        <th>ID</th>
        <th>Pet (ID)</th>
        <th>Data / Hora</th>
        <th>Status</th>
        <th>Criado por (ID)</th>
        <th>Ações</th>
    </tr>
    <?php foreach ($agendamentos as $ag): ?>
    <tr>
        <td><?= $ag->getId() ?></td>
        <td><?= $ag->getPetId() ?></td>
        <td><?= $ag->getDataHora()->format('d/m/Y H:i') ?></td>
        <td><?= $ag->getStatus()->value ?></td>
        <td><?= $ag->getCriadoPor() ?></td>
        <td class="acoes">
            <a href="?page=agendamentos&acao=ver&id=<?= $ag->getId() ?>" class="btn btn-sec">Ver</a>
            <?php if ($ag->getStatus() !== StatusAgendamento::cancelado && $ag->getStatus() !== StatusAgendamento::finalizado): ?>
                <a href="?page=agendamentos&acao=cancelar&id=<?= $ag->getId() ?>" class="btn btn-perigo"
                   onclick="return confirm('Cancelar agendamento?')">Cancelar</a>
            <?php endif; ?>
        </td>
    </tr>
    <?php endforeach; ?>
    <?php if (empty($agendamentos)): ?>
    <tr><td colspan="6" style="text-align:center; color:#888;">Nenhum agendamento cadastrado.</td></tr>
    <?php endif; ?>
</table>
<?php
$conteudo = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
