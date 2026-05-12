# Arquitetura do Sistema PetShop

## Visão Geral

O sistema é dividido em três grandes áreas: **usuários e clientes**, **produtos**, e **serviços**. Todas elas se encontram no momento da venda.

---

## Entidades

### `Usuario`

Representa qualquer pessoa com acesso ao sistema. O cargo define o que cada um pode fazer: o **admin** tem acesso total, o **atendente** pode criar agendamentos e abrir vendas, o **caixa** pode finalizar vendas, e o **funcionário** é a base dos demais — qualquer um pode ser responsável por executar um serviço.

| Atributo  | Tipo                                               |
| --------- | -------------------------------------------------- |
| id        | SERIAL PK                                          |
| nome      | VARCHAR                                            |
| email     | VARCHAR UNIQUE                                     |
| senhaHash | VARCHAR                                            |
| cargo     | ENUM(`admin`, `caixa`, `atendente`, `funcionario`) |
| ativo     | BOOLEAN                                            |

---

### `Cliente`

O tutor do pet que chega na petshop. Pode ter um ou mais pets cadastrados.

| Atributo | Tipo           |
| -------- | -------------- |
| id       | SERIAL PK      |
| nome     | VARCHAR        |
| cpf      | VARCHAR UNIQUE |
| telefone | VARCHAR        |
| email    | VARCHAR        |
| endereco | TEXT           |

---

### `Pet`

O animal, sempre vinculado a um cliente. Carrega espécie, raça e porte, que são usados no cálculo do preço dos serviços.

| Atributo       | Tipo                               |
| -------------- | ---------------------------------- |
| id             | SERIAL PK                          |
| clienteId      | FK → Cliente                       |
| nome           | VARCHAR                            |
| especie        | VARCHAR                            |
| raca           | VARCHAR                            |
| porte          | ENUM(`pequeno`, `medio`, `grande`) |
| peso           | DECIMAL                            |
| dataNascimento | DATE                               |
| observacoes    | TEXT                               |

---

### `Categoria`

Classifica os produtos (Rações, Medicamentos, Acessórios, Roupas, Petiscos...).

| Atributo  | Tipo      |
| --------- | --------- |
| id        | SERIAL PK |
| nome      | VARCHAR   |
| descricao | TEXT      |

---

### `Produto`

O produto em si. Preço e estoque ficam nas variações.

| Atributo    | Tipo           |
| ----------- | -------------- |
| id          | SERIAL PK      |
| categoriaId | FK → Categoria |
| nome        | VARCHAR        |
| descricao   | TEXT           |

---

### `ProdutoVariacao`

Toda variação de um produto. Produtos sem variação têm exatamente uma variação com `nome = null`. É aqui que ficam preço e estoque.

| Atributo  | Tipo             |
| --------- | ---------------- |
| id        | SERIAL PK        |
| produtoId | FK → Produto     |
| nome      | VARCHAR nullable |
| preco     | DECIMAL          |
| estoque   | INTEGER          |

---

### `Servico`

O tipo de serviço oferecido (Banho, Tosa, Banho Medicamentoso...). Sem preço fixo — o preço varia por espécie e porte do pet.

| Atributo  | Tipo      |
| --------- | --------- |
| id        | SERIAL PK |
| nome      | VARCHAR   |
| descricao | TEXT      |

---

### `ServicoPreco`

Tabela de preços por espécie e porte. Chave única composta por `(servicoId, especie, porte)`.

| Atributo  | Tipo                               |
| --------- | ---------------------------------- |
| id        | SERIAL PK                          |
| servicoId | FK → Servico                       |
| especie   | VARCHAR                            |
| porte     | ENUM(`pequeno`, `medio`, `grande`) |
| preco     | DECIMAL                            |

---

### `Agendamento`

Um agendamento de um ou mais serviços para um pet específico em uma data e hora. Funciona como cabeçalho — os serviços ficam nos itens.

| Atributo  | Tipo                                                       |
| --------- | ---------------------------------------------------------- |
| id        | SERIAL PK                                                  |
| petId     | FK → Pet                                                   |
| criadoPor | FK → Usuario                                               |
| dataHora  | TIMESTAMP                                                  |
| status    | ENUM(`agendado`, `em_andamento`, `concluido`, `cancelado`) |
| criadoEm  | TIMESTAMP                                                  |

---

### `AgendamentoServico`

Cada serviço dentro de um agendamento. É aqui que acontece o agendamento (associar serviço, funcionário e horário) e depois a execução (confirmar funcionário, adicionar observações). O `precoCobrado` é um snapshot do preço no momento do agendamento — se o preço mudar depois, o registro histórico permanece correto.

| Atributo      | Tipo             |
| ------------- | ---------------- |
| id            | SERIAL PK        |
| agendamentoId | FK → Agendamento |
| servicoId     | FK → Servico     |
| funcionarioId | FK → Usuario     |
| precoCobrado  | DECIMAL          |
| observacoes   | TEXT nullable    |

---

### `Venda`

Uma transação de caixa. Pode conter produtos e/ou serviços executados.

| Atributo       | Tipo                                                       |
| -------------- | ---------------------------------------------------------- |
| id             | SERIAL PK                                                  |
| clienteId      | FK → Cliente nullable                                      |
| caixaId        | FK → Usuario                                               |
| data           | TIMESTAMP                                                  |
| formaPagamento | ENUM(`dinheiro`, `cartao_debito`, `cartao_credito`, `pix`) |
| status         | ENUM(`aberta`, `finalizada`, `cancelada`)                  |
| total          | DECIMAL                                                    |

---

### `ItemVenda`

Cada linha de uma venda. Um item é **ou** um produto, **ou** um serviço executado — nunca os dois.

| Atributo             | Tipo                             |
| -------------------- | -------------------------------- |
| id                   | SERIAL PK                        |
| vendaId              | FK → Venda                       |
| produtoVariacaoId    | FK → ProdutoVariacao nullable    |
| agendamentoServicoId | FK → AgendamentoServico nullable |
| quantidade           | INTEGER                          |
| precoUnitario        | DECIMAL                          |
| subtotal             | DECIMAL                          |

---

## Regras de Negócio

| #   | Regra                                                                                                                           |
| --- | ------------------------------------------------------------------------------------------------------------------------------- |
| R1  | Um funcionário não pode ter dois agendamentos no mesmo horário para pets diferentes                                             |
| R2  | Um horário só pode ser agendado se houver ao menos um funcionário disponível                                                    |
| R3  | Um pet não pode ter dois serviços simultâneos com funcionários diferentes                                                       |
| R4  | Apenas `atendente` e `admin` podem criar agendamentos e abrir vendas                                                            |
| R5  | Apenas `caixa` e `admin` podem finalizar uma venda                                                                              |
| R6  | O `precoCobrado` de um `AgendamentoServico` é preenchido automaticamente a partir de `ServicoPreco` pelo porte e espécie do pet |
| R7  | Apenas produtos decrementam estoque ao serem vendidos                                                                           |
| R8  | Um `ItemVenda` deve ter exatamente um de: `produtoVariacaoId` ou `agendamentoServicoId`                                         |

---

## Arquitetura MVC

O projeto segue o padrão MVC em PHP puro, com um único ponto de entrada (`public/index.php`) que repassa todas as requisições ao `Router`.

```
petshop/
├── public/
│   ├── index.php           ← Front controller
│   ├── css/
│   └── js/
│
├── app/
│   ├── Core/
│   │   ├── Database.php    ← Conexão PDO com PostgreSQL (Singleton)
│   │   ├── Router.php      ← Mapeia rotas para controllers
│   │   ├── Controller.php  ← Base: render, redirect, verificação de cargo
│   │   └── Auth.php        ← Sessão, login, verificação de permissão
│   │
│   ├── Controllers/
│   │   ├── AuthController.php
│   │   ├── ClienteController.php
│   │   ├── PetController.php
│   │   ├── ProdutoController.php
│   │   ├── CategoriaController.php
│   │   ├── ServicoController.php
│   │   ├── AgendamentoController.php
│   │   └── VendaController.php
│   │
│   ├── Models/
│   │   ├── Usuario.php
│   │   ├── Cliente.php
│   │   ├── Pet.php
│   │   ├── Produto.php
│   │   ├── ProdutoVariacao.php
│   │   ├── Categoria.php
│   │   ├── Servico.php
│   │   ├── ServicoPreco.php
│   │   ├── Agendamento.php
│   │   ├── AgendamentoServico.php
│   │   ├── Venda.php
│   │   └── ItemVenda.php
│   │
│   └── Views/
│       ├── layouts/
│       │   └── main.php
│       ├── auth/
│       ├── clientes/
│       ├── pets/
│       ├── produtos/
│       ├── servicos/
│       ├── agendamentos/
│       └── vendas/
│
└── config/
    ├── database.php
    └── routes.php
```

---

## Fluxos Principais

**Agendamento:**
Atendente seleciona cliente → seleciona pet → escolhe data e hora → escolhe serviço(s) e funcionário(s) → sistema valida disponibilidade → cria `Agendamento` e os `AgendamentoServico` com status pendente.

**Execução:**
Funcionário busca o agendamento → confirma a execução → adiciona observações → status do agendamento avança para concluído.

**Venda:**
Atendente abre a venda → adiciona produtos e/ou serviços já executados → caixa finaliza, registra forma de pagamento → estoque dos produtos é decrementado.
