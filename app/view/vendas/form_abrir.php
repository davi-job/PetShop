<?php ob_start(); ?>
<h2>Abrir Nova Venda</h2>
<div class="card" style="max-width:480px;">
    <form method="post" action="?page=vendas">
        <input type="hidden" name="acao" value="salvar">
        <label>Cliente (opcional)</label>
        <select name="cliente_id">
            <option value="">-- Sem cliente vinculado --</option>
            <?php foreach ($clientes as $cliente): ?>
                <option value="<?= $cliente->getId() ?>"><?= htmlspecialchars($cliente->getNome()) ?></option>
            <?php endforeach; ?>
        </select>
        <div class="acoes-form">
            <button type="submit">Abrir Venda</button>
            <a href="?page=vendas" class="btn btn-sec">Cancelar</a>
        </div>
    </form>
</div>
<?php
$conteudo = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
