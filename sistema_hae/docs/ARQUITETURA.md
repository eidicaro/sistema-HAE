# Arquitetura e fluxos

## Visão geral

Aplicação monolítica server-side em Laravel 12. As requisições entram por `routes/web.php`, passam por cabeçalhos defensivos, autenticação e controle de papel, chegam aos controllers, usam modelos Eloquent e renderizam Blade. Não há API pública, SPA, jobs próprios ou integração externa ativa.

## Tecnologias

- PHP 8.2+ e Laravel 12;
- Blade para HTML;
- MySQL/MariaDB em produção e SQLite em memória nos testes;
- Laravel session/auth para login;
- filesystem `local` para anexos privados;
- `maatwebsite/excel` para exportação XLSX;
- sistema visual unificado em `public/css/app.css`;
- Vite/Tailwind instalados, mas ainda não são o pipeline principal das telas.

## Módulos

| Área | Responsabilidade principal |
|---|---|
| `AuthController` | login por perfil e logout |
| `HaeController` | dashboards, submissão, consulta, diligência e exportação |
| `ParecerController` | parecer único de relator/coordenador |
| `DirecaoController` | relatores, decisão e resultados |
| `RelatorioController` | entrega, reenvio, arquivos e avaliação do relatório |
| `SemestresController` | cadastro e ativação do período letivo |
| `TipoHaeController` | catálogo e limite de tipos de HAE |
| `ProfessorController` | cadastro administrativo de professores |

## Estrutura relevante

```text
app/
  Exports/                 exportação XLSX
  Http/Controllers/        casos de uso HTTP
  Http/Middleware/         autorização por papel e cabeçalhos de segurança
  Models/                  entidades e relações Eloquent
database/migrations/       histórico do esquema
database/seeders/          criação opcional do acesso inicial
resources/views/           telas e componentes Blade
public/css/app.css        tokens e componentes visuais compartilhados
public/js/app.js          interações da interface compatíveis com a CSP
public/images/            marcas institucionais
routes/web.php             rotas web e fronteiras de permissão
tests/Feature/             regressões dos fluxos críticos
docs/                      documentação de continuidade
```

## Fluxo principal da HAE

```text
Professor submete
        |
     pendente
        |
        +-- Direção solicita ajuste --> com_diligencia
        |                                  |
        |                         professor reenvia
        |                                  |
        |                              pendente
        |
        +-- Direção recusa ----------> recusada
        |
        +-- Direção aprova ----------> em_execucao
                                           |
                                  professor envia relatório
                                           |
                              direção reprova | aprova
                                 em_execucao  | finalizada
```

Pareceres são informações de apoio e não alteram o status. A decisão registrada pela direção altera o status e cria uma linha em `decisoes`.

## Decisões arquiteturais atuais

- Papéis continuam em `users.role`, pois o sistema possui apenas três perfis fixos.
- Cursos continuam como texto, tanto no usuário coordenador quanto na HAE. Isso exige igualdade exata.
- Tipos de HAE são registros configuráveis e carregam o limite de horas.
- O limite é isolado por tipo e semestre.
- Alterações concorrentes de limite, status e relatório usam transações e locks de linha.
- Arquivos ficam privados e só saem pelos endpoints autorizados.
- Reenvio de relatório reaproveita o registro reprovado e substitui resultados/anexos.

## Código removido nesta revisão

- `DecisaoController`: não possuía rota e duplicava a decisão implementada na direção, além de referenciar modelos inexistentes.
- modelos `LimiteHae` e `Relatores`: não eram utilizados; limite pertence a `TipoHae` e relatores usam a relação many-to-many.
- componentes antigos por subtipo de HAE: dependiam de colunas e relações inexistentes. `especificacoes` é agora a fonte exibida.

A migration de `limites_hae` foi preservada por ser parte do histórico já potencialmente executado em produção. Não use essa tabela em código novo.
