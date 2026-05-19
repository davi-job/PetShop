<?php
$usuario = Auth::getUsuario();
$pagina  = $_GET['page'] ?? 'login';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PetShop</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; font-size: 14px; background: #f5f5f5; color: #333; }
        header { background: #2c7a3a; color: #fff; padding: 10px 20px; display: flex; align-items: center; justify-content: space-between; }
        header h1 { font-size: 18px; }
        nav { background: #3a9b4b; padding: 0 20px; }
        nav a { display: inline-block; color: #fff; text-decoration: none; padding: 8px 12px; font-size: 13px; }
        nav a:hover, nav a.ativo { background: #2c7a3a; }
        .container { max-width: 1100px; margin: 20px auto; padding: 0 20px; }
        .flash { padding: 10px 14px; margin-bottom: 16px; border-radius: 4px; background: #d4edda; border: 1px solid #c3e6cb; color: #155724; }
        table { width: 100%; border-collapse: collapse; background: #fff; }
        th, td { padding: 8px 12px; border: 1px solid #ddd; text-align: left; }
        th { background: #e9ecef; font-weight: bold; }
        tr:hover td { background: #f8f9fa; }
        a.btn, button { display: inline-block; padding: 6px 12px; background: #2c7a3a; color: #fff; border: none; border-radius: 3px; text-decoration: none; cursor: pointer; font-size: 13px; }
        a.btn:hover, button:hover { background: #225f2d; }
        a.btn-perigo { background: #c0392b; }
        a.btn-perigo:hover { background: #96281b; }
        a.btn-sec { background: #555; }
        a.btn-sec:hover { background: #333; }
        .acoes { white-space: nowrap; }
        .acoes a { margin-right: 4px; }
        form label { display: block; margin-top: 12px; font-weight: bold; font-size: 13px; }
        form input, form select, form textarea { display: block; width: 100%; padding: 7px 10px; margin-top: 4px; border: 1px solid #ccc; border-radius: 3px; font-size: 13px; }
        form textarea { height: 80px; resize: vertical; }
        form .acoes-form { margin-top: 16px; }
        .card { background: #fff; border: 1px solid #ddd; border-radius: 4px; padding: 16px; margin-bottom: 16px; }
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px 20px; }
        .info-grid dt { font-weight: bold; color: #555; font-size: 12px; }
        .info-grid dd { margin: 0; }
        h2 { margin-bottom: 14px; }
        .topo-lista { display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; }
    </style>
</head>
<body>
<header>
    <h1>PetShop</h1>
    <?php if ($usuario): ?>
        <span>
            <?= htmlspecialchars($usuario->getNome()) ?>
            &mdash;
            <a href="?page=login&acao=logout" style="color:#cfc; font-size:13px;">Sair</a>
        </span>
    <?php endif; ?>
</header>
<?php if ($usuario): ?>
<nav>
    <?php
    $modulos = [
        'clientes'     => 'Clientes',
        'pets'         => 'Pets',
        'categorias'   => 'Categorias',
        'produtos'     => 'Produtos',
        'servicos'     => 'Serviços',
        'agendamentos' => 'Agendamentos',
        'vendas'       => 'Vendas',
    ];
    foreach ($modulos as $key => $label):
    ?>
        <a href="?page=<?= $key ?>" <?= $pagina === $key ? 'class="ativo"' : '' ?>>
            <?= $label ?>
        </a>
    <?php endforeach; ?>
</nav>
<?php endif; ?>
<div class="container">
    <?php if (!empty($_SESSION['flash'])): ?>
        <div class="flash"><?= htmlspecialchars($_SESSION['flash']) ?></div>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>
    <?= $conteudo ?? '' ?>
</div>
</body>
</html>
