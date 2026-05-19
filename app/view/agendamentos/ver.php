<?php ob_start();
$status = $agendamento->getStatus();
?>
<div class="topo-lista">
    <h2>Agendamento #<?= $agendamento->getId() ?></h2>
    <a href="?page=agendamentos" class="btn btn-sec">← Voltar</a>
</div>

<div class="card">
    <dl class="info-grid">
        <dt>Pet (ID)</dt>
        <dd><?= $agendamento->getPetId() ?></dd>
        <dt>Data / Hora</dt>
        <dd><?= $agendamento->getDataHora()->format('d/m/Y H:i') ?></dd>
        <dt>Status</dt>
        <dd><?= $status->value ?></dd>
        <dt>Criado por (ID)</dt>
        <dd><?= $agendamento->getCriadoPor() ?></dd>
    </dl>
</div>

<h3 style="margin-bottom:10px;">Serviços</h3>
<table>
    <tr>
        <th>Serviço (ID)</th>
        <th>Preço Cobrado</th>
    </tr>
    <?php foreach ($itens as $item): ?>
    <tr>
        <td><?= $item->getServicoId() ?></td>
        <td>R$ <?= number_format($item->getPrecoCobrado(), 2, ',', '.') ?></td>
    </tr>
    <?php endforeach; ?>
    <?php if (empty($itens)): ?>
    <tr><td colspan="2" style="text-align:center; color:#888;">Nenhum serviço vinculado.</td></tr>
    <?php endif; ?>
</table>

<div style="margin-top:16px;" class="acoes">
    <?php if ($status === StatusAgendamento::marcado): ?>
        <form method="post" action="?page=agendamentos" style="display:inline-flex; gap:10px; align-items:flex-end; flex-wrap:wrap;">
            <input type="hidden" name="acao" value="iniciar">
            <input type="hidden" name="id" value="<?= $agendamento->getId() ?>">
            <div>
                <label style="font-size:12px; font-weight:bold; display:block; margin-bottom:4px;">Funcionário responsável</label>
                <select name="funcionario_id" required style="padding:6px 10px; border:1px solid #ccc; border-radius:3px;">
                    <option value="">-- Selecione --</option>
                    <?php foreach ($funcionarios as $func): ?>
                        <option value="<?= $func->getId() ?>"><?= htmlspecialchars($func->getNome()) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit">Iniciar</button>
        </form>
        <a href="?page=agendamentos&acao=cancelar&id=<?= $agendamento->getId() ?>" class="btn btn-perigo"
           onclick="return confirm('Cancelar agendamento?')">Cancelar</a>
    <?php elseif ($status === StatusAgendamento::emAndamento): ?>
        <a href="?page=agendamentos&acao=concluir&id=<?= $agendamento->getId() ?>" class="btn">Concluir</a>
        <a href="?page=agendamentos&acao=cancelar&id=<?= $agendamento->getId() ?>" class="btn btn-perigo"
           onclick="return confirm('Cancelar agendamento?')">Cancelar</a>
    <?php elseif ($status === StatusAgendamento::finalizado): ?>
        <span style="color:#2c7a3a; font-weight:bold;">Agendamento concluído.</span>
    <?php else: ?>
        <span style="color:#c0392b; font-weight:bold;">Agendamento cancelado.</span>
    <?php endif; ?>
</div>
<?php
$conteudo = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
