# Pendências e próximos passos

Este arquivo separa limitações conhecidas de defeitos já corrigidos. A ordem abaixo é uma sugestão de trabalho, não uma autorização para alterar produção sem backup e homologação.

## Parte 2 — design e experiência

- criar layout Blade único; hoje cada view repete documento HTML e includes podem conter marcação estrutural inválida;
- revisar todos os paths de CSS e consolidar o pipeline de assets;
- criar componentes para header, navegação, alertas, botões, cards, tabelas e formulários;
- padronizar nomes, acentuação, status e mensagens de sessão (`success`, `sucesso`, `error`, `erro`);
- tornar dashboards e formulários responsivos;
- exibir erros junto aos campos e preservar valores no relatório;
- melhorar acessibilidade: labels, foco, contraste, textos alternativos e navegação por teclado;
- remover estilos inline e JavaScript duplicado das views;
- parametrizar texto/número do edital e catálogo de cursos;
- corrigir semântica da exportação: o botão diz CSV, mas entrega XLSX, e há colunas legadas sem correspondência na tabela `haes`.

## Parte 3 — segurança

- substituir checagens manuais por Policies/Form Requests onde trouxer clareza;
- adicionar rate limiting e registro de tentativas de login;
- definir política de senha, troca inicial, bloqueio/desativação de usuário e recuperação, se desejada;
- restringir MIME/extensões de anexos, validar conteúdo, nome de download e considerar antivírus;
- revisar cabeçalhos HTTP, cookies de sessão, HTTPS e configuração HostGator;
- adicionar trilha de auditoria para login, relatores, decisões, alterações e downloads;
- revisar exposição de dados pessoais na exportação;
- criar testes de autorização para cada endpoint e testes de upload malicioso;
- avaliar exclusão em cascata de usuários: atualmente apagar usuário no banco pode apagar HAEs e histórico;
- revisar dependências com `composer audit` e `npm audit` no ambiente com rede.

## Dívida técnica e integridade

- criar migration futura para remover `limites_hae` depois de auditar produção;
- auditar relatórios duplicados e então adicionar unique em `relatorios.hae_id`;
- auditar duplicidades existentes em `relatores` antes de adicionar constraint retroativa na produção;
- garantir um único semestre ativo também no banco ou com mecanismo de lock adequado;
- proteger cálculo/reserva de carga contra duas submissões simultâneas com transação e lock;
- transformar curso em entidade/enum configurável; hoje igualdade textual controla visibilidade do coordenador;
- revisar `HaesExport`: `indicadores`, `horarios_hae` e `mes_1` a `mes_5` não existem na migration atual;
- normalizar nomes singulares/plurais (`Haes`, `Semestres`, tabela `parecer`);
- cobrir relatório, parecer, diligência, CRUD administrativo e exportação com testes;
- criar ambiente de homologação semelhante à HostGator;
- comparar `php artisan migrate:status` e esquema real da produção com as migrations do repositório.

## Pontos corrigidos na parte 1

- autorização administrativa apenas para direção;
- relator professor agora consegue consultar a HAE atribuída;
- edição direta de HAE alheia foi bloqueada;
- cálculo de aprovação deixou de misturar semestres;
- ação de decisão inválida não gera mais status indefinido;
- relatórios ganharam validação, autorização, transação e reenvio controlado;
- download/visualização de anexos seguem a visibilidade da HAE;
- formulário de edição de professor envia para a rota correta;
- relacionamentos Eloquent e rollbacks com nomes incorretos foram corrigidos;
- credenciais fracas fixas foram removidas do seeder;
- controllers, models e views sem uso foram removidos;
- testes padrão vazios foram substituídos por regressões do domínio.
