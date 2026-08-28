# Documentação técnica

Esta documentação descreve o comportamento suportado após a revisão de 28/08/2026. Em caso de divergência com comentários antigos no código, estas regras e os testes automatizados são a referência atual.

| Documento | Conteúdo |
|---|---|
| [ARQUITETURA.md](ARQUITETURA.md) | Tecnologias, módulos, estrutura e fluxos ponta a ponta |
| [REGRAS_DE_NEGOCIO.md](REGRAS_DE_NEGOCIO.md) | Papéis, estados, limites e invariantes |
| [BANCO_DE_DADOS.md](BANCO_DE_DADOS.md) | Tabelas, relacionamentos, exclusões e legado |
| [ROTAS_E_PERMISSOES.md](ROTAS_E_PERMISSOES.md) | Superfície HTTP e matriz de acesso |
| [DESENVOLVIMENTO.md](DESENVOLVIMENTO.md) | Setup, convenções, testes e checklist de alteração |
| [SISTEMA_VISUAL.md](SISTEMA_VISUAL.md) | Identidade, componentes de interface e responsividade |
| [OPERACAO_E_DEPLOY.md](OPERACAO_E_DEPLOY.md) | Configuração, publicação na HostGator, backup e diagnóstico |
| [PENDENCIAS.md](PENDENCIAS.md) | Limitações conhecidas e escopo sugerido das partes 2 e 3 |

## Leitura rápida para continuidade

1. Leia regras e arquitetura.
2. Suba o projeto conforme o README.
3. Rode `composer test` antes de alterar qualquer fluxo.
4. Confira as pendências antes de criar tabelas, status ou telas novas.
5. Ao mudar uma regra, atualize o documento correspondente e acrescente um teste.
