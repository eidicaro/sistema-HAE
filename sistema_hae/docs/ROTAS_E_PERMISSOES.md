# Rotas e permissões

Todas as rotas abaixo, exceto início e login, exigem autenticação. O middleware `auth.tipo` compara o papel gravado no usuário autenticado; ele não confia em sessão ou parâmetro de formulário.

| Área | Métodos e caminhos | Papel |
|---|---|---|
| Início/login | `GET /`, `GET/POST /login/{tipo}` | público; POST possui rate limiting |
| Logout | `POST /logout` | autenticado |
| Dashboards | `GET /professor`, `/coordenador`, `/direcao` | papel correspondente |
| Consulta de HAE | `GET /hae/{id}` | autor, relator, coordenador do curso ou direção |
| Nova HAE | `GET /formulario`, `POST /salvar-hae` | professor |
| Ajuste de HAE | `GET /hae/{id}/edit`, `PUT /hae/{id}` | professor autor; somente diligência |
| Parecer | `POST /parecer/{hae_id}` | relator ou coordenador autorizado |
| Relatório | `GET/POST /hae/{id}/relatorio` | professor autor; HAE em execução |
| Arquivo | `GET /arquivo/{id}/ver|download` | mesma visibilidade da HAE |
| Decisão | `POST /direcao/decisao/{id}` | direção |
| Relatores | `GET/POST /direcao/relatores...` | direção |
| Avaliação de relatório | `POST /relatorio/{id}/aprovar|reprovar` | direção |
| Resultados | `GET /resultados-dir` | direção |
| Semestres | `/semestres...` | direção |
| Tipos de HAE | resource `/direcao/tipos-hae...` e toggle | direção |
| Professores | resource `/direcao/professores...` | direção |
| Exportação | `GET /direcao/exportar-csv` | direção |

## Convenções

- Prefira nomes de rota (`route(...)`) em novas views.
- Rotas de direção devem permanecer dentro do grupo `auth.tipo:direcao`.
- O middleware protege a área; o controller ainda deve validar vínculo com o registro. Essa dupla verificação evita acesso por troca de ID na URL.
- Após alterar rotas, rode `php artisan route:list --except-vendor` e os testes.
