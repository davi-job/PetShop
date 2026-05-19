-- =============================================================
-- PetShop — Schema PostgreSQL
-- Ordem: ENUMs → tabelas base → tabelas dependentes → constraints
-- Convenção: snake_case no banco, camelCase no PHP
-- =============================================================
-- -------------------------------------------------------------
-- ENUMs
-- -------------------------------------------------------------
CREATE TYPE cargo_enum AS ENUM('funcionario', 'atendente', 'caixa', 'gerente');

CREATE TYPE porte_enum AS ENUM('pequeno', 'medio', 'grande');

-- Espelha enum Status de Agendamento.php
CREATE TYPE status_agendamento_enum AS ENUM(
    'cancelado',
    'marcado',
    'em_andamento',
    'finalizado'
);

-- Espelha enum Status de Venda.php
CREATE TYPE status_venda_enum AS ENUM('cancelada', 'pendente', 'concluida');

CREATE TYPE forma_pagamento_enum AS ENUM(
    'dinheiro',
    'cartao_debito',
    'cartao_credito',
    'pix'
);

-- =============================================================
-- TABELAS BASE
-- =============================================================
CREATE TABLE
    usuario (
        id SERIAL PRIMARY KEY,
        nome VARCHAR(150) NOT NULL,
        email VARCHAR(150) NOT NULL UNIQUE,
        senha_hash VARCHAR(255) NOT NULL,
        cargo cargo_enum NOT NULL DEFAULT 'funcionario',
        ativo BOOLEAN NOT NULL DEFAULT TRUE
    );

CREATE TABLE
    cliente (
        id SERIAL PRIMARY KEY,
        nome VARCHAR(150) NOT NULL,
        cpf VARCHAR(14) NOT NULL UNIQUE,
        telefone VARCHAR(20),
        email VARCHAR(150),
        endereco TEXT
    );

CREATE TABLE
    categoria (
        id SERIAL PRIMARY KEY,
        nome VARCHAR(100) NOT NULL,
        descricao TEXT
    );

CREATE TABLE
    servico (
        id SERIAL PRIMARY KEY,
        nome VARCHAR(100) NOT NULL,
        descricao TEXT
    );

-- =============================================================
-- TABELAS NÍVEL 2
-- =============================================================
CREATE TABLE
    pet (
        id SERIAL PRIMARY KEY,
        cliente_id INT NOT NULL REFERENCES cliente (id) ON DELETE CASCADE,
        nome VARCHAR(100) NOT NULL,
        especie VARCHAR(50) NOT NULL,
        raca VARCHAR(100),
        porte porte_enum NOT NULL,
        peso DECIMAL(5, 2),
        data_nascimento DATE,
        observacoes TEXT
    );

CREATE TABLE
    produto (
        id SERIAL PRIMARY KEY,
        categoria_id INT NOT NULL REFERENCES categoria (id),
        nome VARCHAR(150) NOT NULL,
        descricao TEXT
    );

-- Tabela de preços por porte. Chave única (servico_id, porte).
CREATE TABLE
    servico_preco (
        id SERIAL PRIMARY KEY,
        servico_id INT NOT NULL REFERENCES servico (id) ON DELETE CASCADE,
        porte porte_enum NOT NULL,
        preco DECIMAL(10, 2) NOT NULL CHECK (preco >= 0),
        UNIQUE (servico_id, porte)
    );

-- =============================================================
-- TABELAS NÍVEL 3
-- =============================================================
-- Produtos sem variação têm exatamente uma variação com nome NULL.
CREATE TABLE
    produto_variacao (
        id SERIAL PRIMARY KEY,
        produto_id INT NOT NULL REFERENCES produto (id) ON DELETE CASCADE,
        nome VARCHAR(100),
        preco DECIMAL(10, 2) NOT NULL CHECK (preco >= 0),
        estoque INT NOT NULL DEFAULT 0 CHECK (estoque >= 0)
    );

-- Cabeçalho do agendamento. Os serviços ficam em agendamento_servico.
CREATE TABLE
    agendamento (
        id SERIAL PRIMARY KEY,
        pet_id INT NOT NULL REFERENCES pet (id) ON DELETE CASCADE,
        criado_por INT NOT NULL REFERENCES usuario (id),
        data_hora TIMESTAMP NOT NULL,
        status status_agendamento_enum NOT NULL DEFAULT 'marcado',
        criado_em TIMESTAMP NOT NULL DEFAULT NOW()
    );

-- Uma venda pode ser anônima (cliente_id NULL = balcão sem cadastro).
-- atendente_id: quem abriu a venda (R4); caixa_id: quem finalizou (R5).
CREATE TABLE
    venda (
        id SERIAL PRIMARY KEY,
        cliente_id INT REFERENCES cliente (id),
        caixa_id INT REFERENCES usuario (id),
        atendente_id INT NOT NULL REFERENCES usuario (id),
        DATA TIMESTAMP NOT NULL DEFAULT NOW(),
        forma_pagamento forma_pagamento_enum,
        status status_venda_enum NOT NULL DEFAULT 'pendente',
        total DECIMAL(10, 2) NOT NULL DEFAULT 0 CHECK (total >= 0)
    );

-- =============================================================
-- TABELAS NÍVEL 4
-- =============================================================
-- Cada serviço dentro de um agendamento.
CREATE TABLE
    agendamento_servico (
        id SERIAL PRIMARY KEY,
        agendamento_id INT NOT NULL REFERENCES agendamento (id) ON DELETE CASCADE,
        servico_id INT NOT NULL REFERENCES servico (id),
        funcionario_id INT REFERENCES usuario (id),
        preco_cobrado DECIMAL(10, 2) NOT NULL CHECK (preco_cobrado >= 0),
        observacoes TEXT
    );

CREATE TABLE
    item_venda (
        id SERIAL PRIMARY KEY,
        venda_id INT NOT NULL REFERENCES venda (id) ON DELETE CASCADE,
        produto_variacao_id INT REFERENCES produto_variacao (id),
        agendamento_servico_id INT REFERENCES agendamento_servico (id),
        servico_id INT REFERENCES servico (id),
        porte porte_enum,
        quantidade INT NOT NULL DEFAULT 1 CHECK (quantidade > 0),
        preco_unitario DECIMAL(10, 2) NOT NULL CHECK (preco_unitario >= 0),
        subtotal DECIMAL(10, 2) NOT NULL CHECK (subtotal >= 0),
        CONSTRAINT chk_item_venda_exclusivo CHECK (
            (produto_variacao_id IS NOT NULL AND agendamento_servico_id IS NULL AND servico_id IS NULL)
            OR (produto_variacao_id IS NULL AND agendamento_servico_id IS NOT NULL AND servico_id IS NULL)
            OR (produto_variacao_id IS NULL AND agendamento_servico_id IS NULL AND servico_id IS NOT NULL)
        )
    );

-- =============================================================
-- SEED — usuário gerente inicial
-- =============================================================
INSERT INTO
    usuario (nome, email, senha_hash, cargo, ativo)
VALUES
    (
        'admin',
        'admin@petshop.local',
        'admin123',
        'gerente',
        TRUE
    );