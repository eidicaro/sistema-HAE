# Sistema visual

## Direção de design

A interface adota um tom institucional, sóbrio e direto. Branco é a base predominante; o vermelho Fatec identifica ações principais, seleção e pontos de atenção. Cinzas azulados derivados da marca dão estrutura sem competir com o conteúdo.

## Tokens principais

Os tokens ficam no início de `public/css/app.css`.

| Token | Uso |
|---|---|
| `--red-600` | ação principal, item ativo e destaque Fatec |
| `--red-700` | hover e ações de maior peso |
| `--white` | superfícies e base predominante |
| `--ink-900` | navegação e texto de maior contraste |
| `--ink-50` a `--ink-300` | fundo, divisórias e estados neutros |
| verde, âmbar, laranja e azul | estados semânticos, nunca identidade principal |

A fonte usa a pilha nativa `Inter, Segoe UI, Roboto, Helvetica, Arial`, evitando dependência externa para um sistema interno.

## Layouts

- `layouts/public.blade.php`: entrada e login, com marcas Fatec/CPS.
- `layouts/app.blade.php`: área autenticada com sidebar por papel, identificação do usuário, cabeçalho contextual e mensagens.

Somente layouts devem declarar `html`, `head` e `body`. Views de página usam `@extends`; componentes retornam apenas seu fragmento.

## Componentes reutilizáveis

- `.button` e modificadores: ações primária, secundária, discreta, positiva e de atenção;
- `.panel`: superfícies de conteúdo;
- `.metrics-grid` e `.metric-card`: indicadores executivos;
- `.management-card`: atalhos administrativos;
- `components/hae-board.blade.php`: agrupamento das HAEs por estado;
- `.form-card`, `.form-section`, `.field`: formulários;
- `.data-table`: listagens administrativas;
- `.status-pill` e `.tag`: estados e metadados;
- `components/flash-messages.blade.php`: feedback de operação.

## Dashboard da direção

A hierarquia segue a frequência e urgência das tarefas:

1. contexto do semestre;
2. indicadores que exigem atenção;
3. acessos rápidos de gestão;
4. quadro operacional das HAEs;
5. utilização dos limites.

Novas funções administrativas devem entrar na sidebar e, se forem frequentes, no bloco de acesso rápido. Não crie botões isolados fora dessa hierarquia.

## Responsividade e acessibilidade

- abaixo de 900 px, a sidebar vira menu sobreposto;
- grids reduzem progressivamente até uma coluna;
- tabelas mantêm rolagem horizontal;
- botões e campos preservam área confortável para toque;
- foco por teclado é visível;
- ícones decorativos possuem `aria-hidden` e ações possuem texto;
- `prefers-reduced-motion` reduz transições.

Ao criar uma tela, valide pelo menos em 1440 px, 1024 px, 768 px e 375 px, além de navegação por teclado.
