<?php
session_start();

spl_autoload_register(function (string $classe): void {
    $diretorios = [
        __DIR__ . '/../app/core/',
        __DIR__ . '/../app/model/',
        __DIR__ . '/../app/enum/',
        __DIR__ . '/../app/DAO/',
        __DIR__ . '/../app/controller/',
        __DIR__ . '/../config/',
    ];
    foreach ($diretorios as $dir) {
        $arquivo = $dir . $classe . '.php';
        if (file_exists($arquivo)) {
            require_once $arquivo;
            return;
        }
    }
});

$controllers = [
    'login'        => AuthController::class,
    'clientes'     => ClienteController::class,
    'pets'         => PetController::class,
    'categorias'   => CategoriaController::class,
    'produtos'     => ProdutoController::class,
    'servicos'     => ServicoController::class,
    'agendamentos' => AgendamentoController::class,
    'vendas'       => VendaController::class,
];

$page = $_GET['page'] ?? 'login';

if (!isset($controllers[$page])) {
    http_response_code(404);
    die('Página não encontrada.');
}

$ctrl = new $controllers[$page]();
$ctrl->handle();
