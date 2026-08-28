# Banco de dados

## Diagrama textual

```text
users 1 ---- N haes N ---- 1 semestres
                    N ---- 1 tipo_haes
users N ---- N haes          via relatores
users 1 ---- N parecer N --- 1 haes
users 1 ---- N decisoes N -- 1 haes
haes  1 ---- 1 relatorios
relatorios 1 ---- N relatorio_arquivos
relatorios 1 ---- N relatorio_resultados
```

## Tabelas de domínio

### `users`

Usuários autenticáveis. Campos relevantes: `name`, `email`, `curso`, `role`, `password`. Papéis suportados: `professor`, `coordenador` e `direcao`. `curso` é necessário para o escopo do coordenador, embora seja nullable no banco.

### `semestres`

Períodos letivos com nome, datas e flag `ativo`. A unicidade de semestre ativo é garantida hoje pela aplicação, não por constraint.

### `tipo_haes`

Catálogo configurável com `nome`, `descricao`, `limite` e `ativo`. O limite é um inteiro não negativo.

### `haes`

Entidade central. Liga professor, semestre e tipo, armazena curso, título, carga, textos, aceite do edital e status.

- exclusão do usuário ou semestre: cascade;
- exclusão do tipo: restrict;
- `especificacoes` é NOT NULL na migration atual;
- status é string e validado pela aplicação.

### `relatores`

Pivot entre usuários e HAEs. Instalações novas possuem chave única composta `(hae_id, user_id)`. Ambos os vínculos usam cascade.

### `parecer`

Um parecer por usuário/HAE, garantido por chave única composta. `tipo` aceita `relator` ou `coordenador`. A migration usa nome singular; preserve-o.

### `decisoes`

Histórico das decisões da direção. `avaliador_id` referencia `users`. A decisão atual também está refletida em `haes.status`.

### `relatorios`

Relatório de conclusão com textos e status. O código trabalha com o relatório mais recente e impede novos registros após o primeiro, salvo reaproveitamento do reprovado. O banco ainda não possui unique em `hae_id`; ver pendências.

### `relatorio_arquivos`

Metadados de anexos privados: caminho e tipo (`principal` ou `comprovacao`). O conteúdo físico fica no disk Laravel `local`, normalmente `storage/app/private/relatorios` no Laravel 12.

### `relatorio_resultados`

Comparações numéricas por campo, com previsto e realizado.

## Tabelas de infraestrutura

`sessions`, `cache`, `cache_locks`, `jobs`, `job_batches`, `failed_jobs` e `password_reset_tokens` vêm do esqueleto Laravel. O sistema usa sessões e pode usar cache; não possui jobs próprios nem recuperação de senha ativa.

## Legado

`limites_hae` foi substituída por `tipo_haes.limite`. A migration permanece para manter o histórico compatível com ambientes já implantados. Antes de remover a tabela em uma migration futura:

1. confirme que não há dados exclusivos nela em produção;
2. gere e teste backup;
3. crie migration nova, nunca edite apenas o banco manualmente.

## Cuidados com migrations

- Migrations existentes tiveram apenas correções de rollback e unicidade da pivot para instalações novas.
- Uma alteração em migration já executada não modifica produção. Para constraints retroativas, crie uma nova migration após auditar duplicidades.
- Use `php artisan migrate:status` antes de publicar.
- Nunca use `migrate:fresh`, `db:wipe` ou rollback destrutivo em produção.
