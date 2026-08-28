# Desenvolvimento e testes

## Ambiente local

Siga o README da aplicação. Em XAMPP, confirme que o PHP do terminal é a versão desejada com `php -v` e que o MySQL está ativo. O diretório que deve ser servido pelo Apache é `sistema_hae/public`, não a raiz do repositório.

## Padrões adotados

- PHP formatado com Laravel Pint.
- Regras reutilizadas por constantes nos modelos (`User::ROLE_*`, `Haes::STATUS_*`, `Haes::CURSOS`).
- Validação obrigatória antes de usar dados de request.
- Comparações de IDs e estados estritas quando possível.
- Alterações de múltiplos registros relacionadas a um evento usam transação.
- Eloquent para acesso ao banco; não há SQL manual no domínio atual.
- Mensagens e documentação em português.

## Testes

O arquivo `tests/Feature/HaeWorkflowTest.php` cobre atualmente:

- disponibilidade da página pública;
- cabeçalhos HTTP e cache privado das páginas autenticadas;
- rate limiting e não enumeração de perfil no login;
- isolamento de rotas por papel;
- acesso do relator e bloqueio de professor sem vínculo;
- bloqueio da edição de HAE alheia;
- submissão válida no semestre ativo;
- autorização, envio e aprovação do relatório;
- rejeição de anexo inseguro e autorização de download;
- política de senha e neutralização de fórmula na exportação;
- isolamento do limite de carga por semestre.

Execute:

```bash
composer test
```

O script limpa cache de configuração e usa `.env.testing` com SQLite em memória. Para conferir estilo e rotas:

```bash
vendor/bin/pint --test
php artisan route:list --except-vendor
composer audit --locked
npm audit --package-lock-only
```

## Checklist para mudar uma regra

1. Identifique papéis, estados e registros afetados.
2. Atualize validação e autorização no backend; esconder botão na view não é autorização.
3. Garanta que toda soma de carga tenha tipo e semestre.
4. Use transação se dois ou mais registros mudarem juntos.
5. Adicione teste de sucesso e de tentativa não autorizada.
6. Atualize `REGRAS_DE_NEGOCIO.md`, banco ou rotas se o contrato mudou.
7. Rode testes, Pint, route list e auditorias de dependências.

## Assets

Todas as telas carregam `public/css/app.css` através dos layouts compartilhados. A área autenticada carrega `public/js/app.js`, sem scripts inline, para respeitar a Content Security Policy. `resources/js`, Vite e Tailwind permanecem instalados, mas não são o pipeline da interface atual. Ao migrar o pipeline, mantenha fontes únicas para os assets e preserve os tokens descritos em `SISTEMA_VISUAL.md`.

## Dados de desenvolvimento

O seeder não contém mais senhas fixas. Defina `SEED_DIRECAO_NAME`, `SEED_DIRECAO_EMAIL` e `SEED_DIRECAO_PASSWORD` no `.env` e rode `php artisan db:seed`. Sem essas variáveis, ele apenas mostra um aviso. A senha precisa ter no mínimo 6 caracteres.

Para testes manuais, existe um seeder separado que só executa com `APP_ENV=local` ou `testing`:

```bash
php artisan db:seed --class=UsuariosTesteSeeder
```

Ele cria ou atualiza as contas abaixo:

| Perfil | E-mail | Curso |
|---|---|---|
| Professor | `professor.teste1@fatec.sp.gov.br` | — |
| Professor | `professor.teste2@fatec.sp.gov.br` | — |
| Coordenador | `coordenador.teste1@fatec.sp.gov.br` | primeiro curso de `Haes::CURSOS` |
| Coordenador | `coordenador.teste2@fatec.sp.gov.br` | segundo curso de `Haes::CURSOS` |

A senha compartilhada é `teste123` por padrão. Para usar outra, defina `SEED_TEST_PASSWORD` no `.env` antes de executar o comando. O seeder é idempotente e não é chamado pelo `DatabaseSeeder`.
