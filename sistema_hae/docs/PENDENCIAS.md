# Pendências e próximos passos

Este arquivo separa limitações conhecidas de defeitos já corrigidos. A ordem abaixo é uma sugestão de trabalho, não uma autorização para alterar produção sem backup e homologação.

## Parte 2 — design e experiência concluída

- layouts público e autenticado compartilhados;
- navegação lateral por perfil e painel responsivo;
- dashboard da direção reorganizado por prioridade operacional;
- componentes unificados para mensagens, botões, cards, tabelas, formulários e quadro de HAEs;
- identidade branca e vermelha alinhada à Fatec, com marcas existentes da Fatec Tatuí e CPS;
- foco visível, labels, navegação semântica e suporte a redução de movimento;
- um único arquivo de estilo, `public/css/app.css`;
- nomenclatura da exportação corrigida na interface para planilha.

Melhorias futuras de experiência: parametrizar o texto do edital, tornar cursos configuráveis, eliminar os poucos estilos inline de valores dinâmicos e realizar teste de usabilidade com usuários reais.

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
