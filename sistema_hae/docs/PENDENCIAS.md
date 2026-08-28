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

## Parte 3 — segurança concluída

- rate limiting, mensagem genérica e registro de eventos no login;
- ciclo de sessão corrigido e configuração segura de cookies documentada;
- senhas novas com mínimo de 6 caracteres por compatibilidade com as contas atuais;
- autorização por perfil e por vínculo coberta por testes de regressão;
- anexos privados restritos por MIME, extensão, quantidade e tamanho;
- visualização inline limitada a PDF e imagens permitidas;
- cabeçalhos HTTP defensivos e política de conteúdo sem scripts inline;
- exportação protegida contra injeção de fórmulas;
- transações e locks nas operações concorrentes de carga horária e relatórios;
- logs de autenticação, relatores, decisões, relatórios e anexos;
- dependências PHP atualizadas e `package-lock.json` criado;
- auditorias Composer e npm sem vulnerabilidades conhecidas em 28/08/2026;
- testes de segurança para login, cabeçalhos, anexos, autorização, senha e exportação.

Controles futuros, caso o risco ou o número de usuários aumente: MFA, bloqueio/desativação de conta, recuperação de senha, troca inicial obrigatória, antivírus de anexos, auditoria imutável e teste de invasão da instalação real.

## Dívida técnica e integridade

- criar migration futura para remover `limites_hae` depois de auditar produção;
- auditar relatórios duplicados e então adicionar unique em `relatorios.hae_id`;
- auditar duplicidades existentes em `relatores` antes de adicionar constraint retroativa na produção;
- transformar curso em entidade/enum configurável; hoje igualdade textual controla visibilidade do coordenador;
- revisar `HaesExport`: `indicadores`, `horarios_hae` e `mes_1` a `mes_5` não existem na migration atual;
- normalizar nomes singulares/plurais (`Haes`, `Semestres`, tabela `parecer`);
- ampliar a cobertura para parecer, diligência e todos os casos de erro dos CRUDs administrativos;
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
