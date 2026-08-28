# Sistema HAE

Aplicação web interna da Fatec para gerenciar o ciclo de vida de Horas de Atividades Específicas: submissão pelo professor, pareceres, decisão da direção, execução, entrega de relatório e encerramento.

## Requisitos

- PHP 8.2 ou superior, com extensões usuais do Laravel e o driver do banco;
- Composer 2;
- MySQL/MariaDB;
- Node.js e npm apenas se os assets Vite forem usados/recompilados.

## Instalação local

```bash
composer install
copy .env.example .env
php artisan key:generate
```

Configure o banco e as variáveis `SEED_DIRECAO_*` no `.env`, depois execute:

```bash
php artisan migrate
php artisan db:seed
php artisan serve
```

No PowerShell, `Copy-Item .env.example .env` pode ser usado no lugar de `copy`.

O sistema visual está em `public/css/app.css`; portanto, o fluxo atual não depende do Vite. Caso altere `resources/js` ou passe a usar o pipeline Vite:

```bash
npm install
npm run build
```

## Validação

```bash
composer test
vendor/bin/pint --test
php artisan route:list --except-vendor
```

`composer test` usa `.env.testing` e SQLite em memória; nenhum banco local ou de produção é alterado.

## Documentação

- [Índice técnico](docs/INDEX.md)
- [Arquitetura e fluxos](docs/ARQUITETURA.md)
- [Regras de negócio](docs/REGRAS_DE_NEGOCIO.md)
- [Banco de dados](docs/BANCO_DE_DADOS.md)
- [Rotas e permissões](docs/ROTAS_E_PERMISSOES.md)
- [Desenvolvimento e testes](docs/DESENVOLVIMENTO.md)
- [Sistema visual](docs/SISTEMA_VISUAL.md)
- [Operação e deploy](docs/OPERACAO_E_DEPLOY.md)
- [Pendências e próximos passos](docs/PENDENCIAS.md)

## Convenções essenciais

- Não grave status ou papéis novos como strings dispersas; use as constantes de `Haes` e `User`.
- Toda consulta de carga horária deve incluir `semestre_id`.
- Rotas administrativas pertencem ao middleware `auth.tipo:direcao`.
- Uma HAE só pode ser alterada pelo autor quando estiver `com_diligencia`.
- Nunca execute `migrate:fresh` em produção.
