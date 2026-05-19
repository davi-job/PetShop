<?php ob_start(); ?>
<h2><?= $pet ? 'Editar Pet' : 'Novo Pet' ?></h2>
<div class="card" style="max-width:520px;">
    <form method="post" action="?page=pets">
        <input type="hidden" name="acao" value="<?= $pet ? 'atualizar' : 'salvar' ?>">
        <?php if ($pet): ?>
            <input type="hidden" name="id" value="<?= $pet->getId() ?>">
        <?php endif; ?>
        <label>Cliente</label>
        <select name="cliente_id" required>
            <option value="">-- Selecione --</option>
            <?php foreach ($clientes as $cliente): ?>
                <option value="<?= $cliente->getId() ?>"
                    <?= ($pet && $pet->getClienteId() === $cliente->getId()) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cliente->getNome()) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <label>Nome</label>
        <input type="text" name="nome" required value="<?= $pet ? htmlspecialchars($pet->getNome()) : '' ?>">
        <label>Espécie</label>
        <input type="text" name="especie" required value="<?= $pet ? htmlspecialchars($pet->getEspecie()) : '' ?>">
        <label>Raça</label>
        <input type="text" name="raca" value="<?= $pet ? htmlspecialchars($pet->getRaca()) : '' ?>">
        <label>Porte</label>
        <select name="porte" required>
            <?php foreach (Porte::cases() as $p): ?>
                <option value="<?= $p->value ?>" <?= ($pet && $pet->getPorte() === $p) ? 'selected' : '' ?>>
                    <?= ucfirst($p->value) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <label>Peso (kg)</label>
        <input type="number" name="peso" step="0.01" min="0" required
               value="<?= $pet ? $pet->getPeso() : '' ?>">
        <label>Data de Nascimento</label>
        <input type="date" name="data_nascimento" required
               value="<?= $pet ? $pet->getDataNascimento()->format('Y-m-d') : '' ?>">
        <label>Observações</label>
        <textarea name="observacoes"><?= $pet ? htmlspecialchars($pet->getObservacoes()) : '' ?></textarea>
        <div class="acoes-form">
            <button type="submit">Salvar</button>
            <a href="?page=pets" class="btn btn-sec">Cancelar</a>
        </div>
    </form>
</div>
<?php
$conteudo = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
