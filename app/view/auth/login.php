<?php ob_start(); ?>
<div style="max-width:360px; margin:60px auto; background:#fff; border:1px solid #ddd; border-radius:4px; padding:28px;">
    <h2 style="margin-bottom:20px; text-align:center;">PetShop — Acesso</h2>
    <?php if (!empty($erro)): ?>
        <div style="background:#f8d7da; border:1px solid #f5c6cb; color:#721c24; padding:8px 12px; border-radius:3px; margin-bottom:14px;">
            <?= htmlspecialchars($erro) ?>
        </div>
    <?php endif; ?>
    <form method="post" action="?page=login">
        <input type="hidden" name="acao" value="login">
        <label>E-mail</label>
        <input type="email" name="email" required autofocus>
        <label>Senha</label>
        <input type="password" name="senha" required>
        <div class="acoes-form">
            <button type="submit" style="width:100%; padding:10px;">Entrar</button>
        </div>
    </form>
</div>
<?php
$conteudo = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
