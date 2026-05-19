<?php ob_start(); ?>
<h2>Novo Agendamento</h2>
<div class="card" style="max-width:560px;">
    <form method="post" action="?page=agendamentos">
        <input type="hidden" name="acao" value="salvar">

        <label>Pet</label>
        <select name="pet_id" required>
            <option value="">-- Selecione --</option>
            <?php foreach ($pets as $pet): ?>
                <option value="<?= $pet->getId() ?>">
                    <?= htmlspecialchars($pet->getNome()) ?>
                    (<?= htmlspecialchars($pet->getEspecie()) ?> — <?= $pet->getPorte()->value ?>)
                </option>
            <?php endforeach; ?>
        </select>

        <label>Data e Hora</label>
        <input type="datetime-local" name="data_hora" required>

        <label>Funcionário responsável</label>
        <select name="funcionario_id" required>
            <option value="">-- Selecione --</option>
            <?php foreach ($funcionarios as $func): ?>
                <option value="<?= $func->getId() ?>"><?= htmlspecialchars($func->getNome()) ?></option>
            <?php endforeach; ?>
        </select>

        <label>Serviços (marque ao menos um)</label>
        <div style="margin-top:6px;">
        <?php foreach ($servicos as $servico): ?>
            <label style="font-weight:normal; display:flex; align-items:center; gap:8px; margin-bottom:4px;">
                <input type="checkbox" name="servico_id[]" value="<?= $servico->getId() ?>">
                <?= htmlspecialchars($servico->getNome()) ?>
            </label>
        <?php endforeach; ?>
        </div>

        <div class="acoes-form">
            <button type="submit">Agendar</button>
            <a href="?page=agendamentos" class="btn btn-sec">Cancelar</a>
        </div>
    </form>
</div>
<?php
$conteudo = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
