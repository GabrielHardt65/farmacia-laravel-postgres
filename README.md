# Sistema Interno de Farmácia

**Centro Universitário Campo Real — Engenharia de Software**

Projeto desenvolvido para a disciplina de Banco de Dados e Desenvolvimento Back End.

**Professores:** Bruno Dion e Dionatan Inhoato Markiu

**Integrantes:**
- Gabriel Hardt
- Luiz Gustavo Prado
- Kauana Cravelin
- Samara Almeida Ferreira

Guarapuava — 2026

## Sobre o projeto

O Sistema Interno de Farmácia é uma aplicação web desenvolvida para auxiliar no gerenciamento das operações de uma farmácia, permitindo organizar produtos, clientes, funcionários, compras, vendas, receitas médicas e estoque.

O projeto utiliza a arquitetura MVC do Laravel, integrando uma interface web com um banco de dados PostgreSQL.

## Tecnologias utilizadas

- PHP
- Laravel
- PostgreSQL
- Blade
- HTML, CSS e JavaScript
- Git e GitHub

## Funcionalidades

- Cadastro, consulta e edição de produtos.
- Gerenciamento de clientes e funcionários.
- Controle de compras e entradas de estoque.
- Registro e gerenciamento de vendas.
- Gerenciamento de receitas médicas.
- Relatórios de vendas, estoque e validade de produtos.
- API REST para operações de clientes e produtos.
- Integração com PostgreSQL utilizando functions, procedures e triggers.

## Requisitos

Para executar o projeto, é necessário possuir:

- PHP 8.3 ou superior.
- Composer.
- Node.js e npm.
- PostgreSQL.
- Extensões PHP: `pdo_pgsql`, `pgsql`, `intl`, `zip` e `fileinfo`.

## Como executar o projeto

### 1. Clonar o repositório

git clone https://github.com/GabrielHardt65/farmacia-laravel-postgres.git
cd farmacia-laravel-postgres

### 2. Instalar as dependências

composer install
npm install

### 3. Configurar o ambiente

Copie o arquivo de configuração de exemplo:
cp .env.example .env
php artisan key:generate

No Windows PowerShell, também é possível utilizar:
Copy-Item .env.example .env

### 4. Configurar a conexão com o PostgreSQL

Configure as variáveis de conexão no arquivo `.env`:

DB_CONNECTION=pgsql
DB_HOST=seu_host
DB_PORT=5432
DB_DATABASE=seu_banco
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha

Substitua os valores pelos dados do ambiente PostgreSQL utilizado.

Caso o servidor exija conexão SSL, configure-a conforme as exigências do provedor.

**Não compartilhe senhas ou credenciais de acesso no README ou no repositório.**

### 5. Configurar o banco de dados

Os scripts SQL estão disponíveis na pasta:

`database/scripts/`

Execute os scripts de estrutura, dados e objetos do banco de dados conforme a ordem indicada nos arquivos.

### 6. Compilar os arquivos do frontend

npm run build

### 7. Iniciar a aplicação

php artisan serve

A aplicação estará disponível em:

http://127.0.0.1:8000

## Rotas da aplicação

As rotas da aplicação web estão definidas em `routes/web.php`.

As rotas da API REST estão definidas em `routes/api.php`.

Para visualizar todas as rotas registradas:

php artisan route:list

### Exemplos de rotas da API

| Método | Rota | Objetivo |
|---|---|---|
| GET | `/api/vendas/{id}` | Consultar uma venda |
| POST | `/api/vendas/{id}/finalizar` | Finalizar uma venda |

### Exemplo de consulta

Com a aplicação em execução, uma venda pode ser consultada utilizando:
curl http://127.0.0.1:8000/api/vendas/1 -H "Accept: application/json"
O exemplo utiliza o identificador `1` como referência. A resposta depende dos dados cadastrados no banco.
**Atenção:** a rota de finalização de venda realiza alterações no banco de dados. Utilize um ambiente de testes para executar operações que modificam os registros.

## Objetos do banco de dados

O sistema utiliza objetos do PostgreSQL para implementar regras de negócio e operações relacionadas ao estoque e às vendas.

### Functions

| Function | Objetivo |
|---|---|
| `fn_estoque_produto` | Consultar informações relacionadas ao estoque de produtos. |
| `fn_venda_exige_receita` | Verificar a necessidade de receita médica para uma venda. |

### Procedures

| Procedure | Objetivo |
|---|---|
| `sp_finalizar_venda` | Realizar a finalização de uma venda. |
| `sp_registrar_compra_lote` | Registrar uma compra associada a lotes de produtos. |

### Triggers

| Trigger | Objetivo |
|---|---|
| `tg_baixa_estoque_lote` | Executar a baixa de estoque relacionada às operações de venda. |
| `tg_valida_receita` | Aplicar validações relacionadas às receitas médicas. |

Os objetos estão definidos nos scripts SQL disponíveis em `database/scripts/`.

### Índices

Os índices são utilizados para auxiliar na consulta e recuperação de dados.

- Índice em `lotes.data_validade`.
- Índice em `itens_venda.id_produto`.
- Índice em `receitas.id_venda`.
- Índice em `produtos.nome`.

## Testes automatizados

O projeto possui testes automatizados para verificar o funcionamento de funcionalidades da aplicação web e dos endpoints da API REST.

Para executar os testes:
php artisan test

Os testes devem ser executados em um ambiente de testes separado do banco de dados utilizado em produção.

## Backup e restauração

Os procedimentos de backup e restauração do banco de dados estão documentados no script:

`database/scripts/04_backup_restauracao.sql`

Os procedimentos devem ser executados em um ambiente apropriado, respeitando as permissões de acesso ao banco de dados.

## Estrutura do projeto

O projeto está organizado seguindo a estrutura MVC do Laravel, separando as regras de negócio, os controladores, os modelos, as interfaces e as rotas da aplicação.

```text
farmacia-laravel-postgres/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── Api/
│   │       └── Auth/
│   ├── Models/
│   └── Services/
├── database/
│   ├── factories/
│   ├── migrations/
│   ├── scripts/
│   └── seeders/
├── resources/
│   ├── css/
│   ├── js/
│   └── views/
├── routes/
│   ├── api.php
│   ├── console.php
│   └── web.php
├── tests/
│   ├── Feature/
│   └── Unit/
├── artisan
├── composer.json
├── package.json
└── README.md
```

### Descrição dos diretórios

| Diretório ou arquivo | Descrição |
|---|---|
| `app/Http/Controllers/` | Controladores responsáveis pelas requisições da aplicação web. |
| `app/Http/Controllers/Api/` | Controladores responsáveis pelos endpoints da API REST. |
| `app/Http/Controllers/Auth/` | Componentes relacionados à autenticação. |
| `app/Models/` | Modelos utilizados na comunicação com os dados. |
| `app/Services/` | Serviços que concentram regras e operações de negócio. |
| `database/migrations/` | Arquivos de estruturação do banco de dados. |
| `database/scripts/` | Scripts SQL de estrutura, dados e objetos do PostgreSQL. |
| `resources/views/` | Interfaces web desenvolvidas com Blade. |
| `resources/css/` | Arquivos de estilos da aplicação. |
| `resources/js/` | Arquivos JavaScript da interface. |
| `routes/web.php` | Rotas da aplicação web. |
| `routes/api.php` | Rotas da API REST. |
| `tests/Feature/` | Testes de funcionalidades e integração da aplicação. |
| `tests/Unit/` | Testes unitários. |

## Repositório

[GitHub — farmacia-laravel-postgres](https://github.com/GabrielHardt65/farmacia-laravel-postgres)
