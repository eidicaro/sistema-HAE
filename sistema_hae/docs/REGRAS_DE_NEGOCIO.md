# Regras de negócio

## Papéis

### Professor

- cria HAE no semestre ativo;
- consulta HAEs próprias e aquelas em que foi definido como relator;
- edita e reenvia somente HAE própria em diligência;
- emite parecer somente quando é relator;
- envia relatório somente da própria HAE em execução;
- reenvia relatório somente após reprovação.

### Coordenador

- consulta HAEs do próprio curso ou HAEs para as quais foi definido como relator;
- emite um parecer por HAE do próprio curso ou atribuída a ele;
- não toma a decisão final e não administra cadastros.

### Direção

- consulta todas as HAEs;
- define relatores;
- registra decisão final;
- aprova ou reprova relatórios enviados;
- administra semestres, tipos de HAE e professores;
- exporta as HAEs do semestre ativo.

## Semestre

- Deve existir um semestre ativo para criar HAE e para exibir os dashboards correntes.
- A aplicação mantém somente um semestre ativo ao ativar outro, dentro de transação com lock dos períodos.
- `data_fim` não pode ser anterior a `data_inicio`.
- Ativar um semestre não move HAEs entre períodos.

## Submissão

- O tipo deve existir e estar ativo.
- O professor precisa aceitar o edital.
- Curso precisa pertencer à lista `Haes::CURSOS`.
- Título, carga horária, resumo e justificativa são obrigatórios.
- Carga horária deve ser inteira e maior que zero.
- Especificações e cronograma são opcionais; especificações vazias são persistidas como string vazia por compatibilidade com o esquema.
- Nova HAE começa como `pendente`.

O texto do edital ainda está fixo na view e deve ser parametrizado em uma evolução futura.

## Estados da HAE

| Estado | Significado | Próximas transições válidas |
|---|---|---|
| `pendente` | aguarda decisão | `com_diligencia`, `recusada`, `em_execucao` |
| `com_diligencia` | autor deve ajustar | `pendente` após reenvio |
| `em_execucao` | autorizada e em andamento | `finalizada` após relatório aprovado; permanece após reprovação |
| `finalizada` | relatório aprovado | terminal |
| `recusada` | submissão recusada | terminal |

Decisão só pode ser aplicada a `pendente` ou `com_diligencia`. Todo reenvio da HAE volta para `pendente`.

## Limite de carga horária

- O limite está em `tipo_haes.limite`.
- É calculado separadamente para cada combinação de tipo e semestre.
- Para impedir excesso já na submissão, reservam carga: `pendente`, `com_diligencia`, `em_execucao` e `finalizada`.
- `recusada` não reserva carga.
- Na decisão de aprovação, a direção confirma o total já comprometido em `em_execucao` e `finalizada` no mesmo semestre.
- Ao editar uma HAE, a própria carga anterior é excluída do somatório antes da validação.
- Submissão, edição e aprovação bloqueiam os registros envolvidos durante a transação para evitar consumo simultâneo do mesmo saldo.

Essa regra evita receber mais propostas do que o teto, mas pode bloquear novas submissões enquanto propostas pendentes ainda reservam horas. Se a instituição preferir concorrência entre propostas, a regra deve ser alterada explicitamente e acompanhada de testes.

## Pareceres e relatores

- Direção pode atribuir usuários com papel professor ou coordenador.
- A atribuição usa sincronização: a nova seleção substitui a anterior.
- Cada usuário pode ter no máximo um vínculo e um parecer por HAE em instalações novas.
- Parecer exige comentário e não muda o status.

## Relatórios

- Só o autor envia, e apenas quando a HAE está em execução.
- Título, sumário e principais resultados são obrigatórios.
- Cada arquivo aceita no máximo 10 MiB; são aceitos até dez comprovantes por envio.
- Os formatos permitidos são PDF, JPG/JPEG, PNG, DOC/DOCX, ODT e XLS/XLSX.
- Somente PDF e imagens são exibidos no navegador; os demais formatos são baixados.
- Um relatório `enviado` aguarda direção e não pode ser reenviado.
- Reprovação devolve a HAE para `em_execucao` e habilita o reenvio.
- Reenvio substitui textos, resultados e anexos antigos; os arquivos substituídos são apagados do disco.
- Aprovação muda relatório para `aprovado` e HAE para `finalizada` em uma única transação.

## Exclusões

- Professor com HAE vinculada não pode ser excluído pela interface.
- Tipo com HAE vinculada não pode ser excluído; deve ser desativado.
- Exclusões em cascata existentes no banco são documentadas em `BANCO_DE_DADOS.md`, mas não devem ser acionadas manualmente em produção sem backup.
