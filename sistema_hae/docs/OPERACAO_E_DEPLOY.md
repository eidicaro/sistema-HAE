# Operação e deploy

## Configuração de produção

Valores mínimos esperados no `.env`:

```dotenv
APP_NAME="Sistema HAE"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://seu-dominio
APP_LOCALE=pt_BR

DB_CONNECTION=mysql
DB_HOST=...
DB_PORT=3306
DB_DATABASE=...
DB_USERNAME=...
DB_PASSWORD=...

SESSION_DRIVER=database
SESSION_ENCRYPT=true
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=lax
FILESYSTEM_DISK=local

LOG_CHANNEL=daily
LOG_LEVEL=info
LOG_DAYS=14
```

Mantenha `APP_KEY` atual durante atualizações. Trocar a chave invalida sessões e pode tornar dados criptografados ilegíveis. Nunca envie `.env`, dumps ou anexos para o Git.

## Estrutura na hospedagem

- O document root do domínio/subdomínio deve apontar para `sistema_hae/public`.
- `storage` e `bootstrap/cache` precisam de permissão de escrita pelo PHP.
- O restante da aplicação não deve ficar publicamente navegável.
- Os anexos são privados; não crie link público para `storage/app/private`.

## Publicação segura

Antes:

1. faça backup do banco e de `storage/app/private/relatorios`;
2. registre o commit/versão atualmente publicado;
3. rode localmente `composer test`, Pint, `php artisan route:list`, `composer audit --locked` e `npm audit --package-lock-only`;
4. confira migrations pendentes com `php artisan migrate:status`.

Na publicação:

```bash
php artisan down
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan up
```

Se a parte alterada passar a depender de Vite, execute `npm ci && npm run build` no processo de build e publique `public/build`. Em hospedagem sem Node, gere os assets fora do servidor.

Depois, faça um smoke test com cada perfil: login, dashboard, abrir HAE e logout. Para uma versão que altera fluxo, teste também submissão, decisão e relatório com registros de homologação.

## Backup e restauração

Um backup completo precisa dos dois conjuntos:

- dump MySQL/MariaDB;
- diretório `storage/app/private/relatorios`.

Guardar apenas o banco perde os anexos; guardar apenas arquivos perde os vínculos. Teste restauração periodicamente em ambiente separado.

## Gestão de contas privilegiadas

A interface administra somente professores. O primeiro usuário de direção é criado pelo seeder a partir de `SEED_DIRECAO_*`. Coordenadores ainda precisam ser cadastrados por um administrador técnico, por exemplo no Tinker:

```php
App\Models\User::create([
    'name' => 'Nome do coordenador',
    'email' => 'coordenacao@fatec.sp.gov.br',
    'password' => 'senha-temporaria-forte',
    'role' => App\Models\User::ROLE_COORDENADOR,
    'curso' => App\Models\Haes::CURSOS[0],
]);
```

Use o nome de curso exatamente como definido em `Haes::CURSOS` e entregue a senha por canal adequado. A validação atual exige somente 6 caracteres para manter compatibilidade com as contas existentes. Evite usar CPF como senha permanente: ele é um dado pessoal previsível e não um segredo.

## Rollback

Se a versão falhar:

1. coloque a aplicação em manutenção;
2. restaure a versão de código anterior;
3. restaure banco e anexos se a migration ou a gravação de dados não for compatível;
4. limpe e reconstrua caches;
5. valide e retire a manutenção.

Não use `migrate:rollback` automaticamente: migrations podem remover dados. Prefira migration corretiva ou restauração validada.

## Diagnóstico

| Sintoma | Verificação |
|---|---|
| erro 500 | `storage/logs/laravel.log`, permissões, `APP_KEY`, versão PHP |
| CSS/imagens ausentes | document root em `public`, URL e paths dos assets |
| login não persiste | tabela `sessions`, cookie/domain, HTTPS e permissões do banco |
| erro 419 | sessão, domínio do cookie, HTTPS, cache de configuração e token CSRF |
| arquivo 404 | existência em `storage/app/private/relatorios` e vínculo no banco |
| rota antiga | `php artisan optimize:clear` e novo `route:cache` |
| tela sem HAEs | semestre ativo e vínculo de curso/usuário |
| limite inesperado | tipo, semestre, status e cargas das HAEs reservadas |

## Tarefas recorrentes

- monitorar tamanho de logs e anexos;
- verificar backups;
- revisar usuários e semestre ativo no início de cada período;
- desativar tipos antigos em vez de excluir os que possuem histórico;
- acompanhar versões suportadas de PHP, Laravel e dependências antes de atualizar.
- executar `composer audit --locked` e `npm audit --package-lock-only` ao menos mensalmente e antes de cada publicação.
