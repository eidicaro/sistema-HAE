# Segurança

## Escopo da revisão

Esta revisão considera o Sistema HAE uma aplicação interna, mas publicada na internet. Isso reduz a quantidade de usuários legítimos, porém não elimina tentativas automatizadas contra o login, arquivos maliciosos, abuso de uma conta válida ou falhas de configuração da hospedagem.

O objetivo é uma proteção proporcional ao uso institucional. Não foram incluídos MFA, recuperação de senha, antivírus de anexos, auditoria imutável nem uma infraestrutura dedicada.

## Controles implementados

### Autenticação e sessão

- login limitado a 5 tentativas por minuto por combinação de e-mail e IP e a 20 por minuto por IP;
- mensagem genérica para credencial incorreta ou escolha do perfil errado, evitando confirmar a existência e o papel de uma conta;
- regeneração do identificador de sessão após login e invalidação completa no logout ou na tentativa pelo perfil errado;
- senha nova com no mínimo 6 caracteres, sem regra de composição, por compatibilidade com as contas atuais;
- e-mails de contas criadas pela direção normalizados em minúsculas;
- eventos de login, falha e logout registrados sem gravar senha nem e-mail em texto puro.

As senhas existentes continuam válidas. A regra é aplicada somente ao criar ou trocar uma senha. CPF atende tecnicamente ao mínimo por possuir 11 dígitos, mas é um dado pessoal previsível e não deve ser considerado uma senha forte ou permanente.

### Autorização

As rotas usam sessão autenticada e middleware por papel. Além disso, as operações sensíveis verificam o vínculo com a HAE:

- professor altera e envia relatório somente da própria HAE;
- relator consulta apenas HAE atribuída;
- coordenador consulta HAEs do próprio curso ou atribuídas a ele;
- direção executa decisões, cadastros administrativos, avaliações e exportação;
- download e visualização de anexos repetem a autorização da HAE correspondente.

Essas verificações no servidor são obrigatórias; ocultar um botão na interface não é considerado autorização.

### Entradas, anexos e exportação

- textos são renderizados pelo escape padrão do Blade;
- campos de texto e coleções possuem limites coerentes com o banco;
- anexos ficam no disco privado `local`, fora de `public`;
- são aceitos apenas PDF, JPG/JPEG, PNG, DOC/DOCX, ODT e XLS/XLSX;
- cada arquivo pode ter até 10 MB e cada relatório aceita até 10 comprovações;
- MIME detectado pelo conteúdo e extensão informada precisam ser permitidos;
- somente PDF e imagens permitidas abrem no navegador; documentos Office/OpenDocument são forçados para download;
- valores iniciados por `=`, `+`, `-` ou `@` são neutralizados antes da exportação para impedir injeção de fórmulas na planilha.

O atributo `accept` do formulário é apenas uma orientação ao navegador. A validação efetiva ocorre no servidor.

### Integridade e concorrência

Submissão, reenvio após diligência, aprovação de HAE e avaliação de relatório usam transações e bloqueios de linha. Isso impede que duas requisições simultâneas consumam o mesmo saldo de carga horária ou avaliem o mesmo relatório duas vezes.

### Respostas HTTP e navegador

O middleware global adiciona:

- Content Security Policy;
- bloqueio de enquadramento por outros sites;
- `nosniff` para tipos de conteúdo;
- política de referência restrita;
- bloqueio de câmera, microfone e geolocalização;
- `Cache-Control: no-store, private` nas páginas autenticadas;
- HSTS quando a requisição é reconhecida como HTTPS.

O JavaScript próprio foi movido para `public/js/app.js`, permitindo bloquear scripts inline. Estilos inline ainda são liberados pela CSP porque algumas barras de progresso usam largura dinâmica.

### Dependências

Em 28/08/2026, o lock PHP foi atualizado de Laravel 12.55.1 para 12.68.0, com as atualizações compatíveis de Guzzle, Symfony e demais pacotes. A auditoria passou de 33 avisos em 11 pacotes para nenhum aviso conhecido. O `package-lock.json` foi criado e a auditoria npm também terminou sem vulnerabilidades conhecidas.

Repita regularmente:

```bash
composer audit --locked
npm audit --package-lock-only
```

Uma auditoria sem avisos significa apenas que não havia vulnerabilidade conhecida nas bases consultadas naquele momento.

## Configuração obrigatória em produção

Use HTTPS e ajuste o `.env` da HostGator:

```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=https://seu-dominio

SESSION_DRIVER=database
SESSION_ENCRYPT=true
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=lax

LOG_CHANNEL=daily
LOG_LEVEL=info
LOG_DAYS=14
```

Depois da alteração, execute `php artisan optimize:clear` e `php artisan config:cache`.

Se a HostGator encerrar o HTTPS em um proxy, confirme que o Laravel reconhece a requisição como segura. Caso contrário, HSTS e o cookie `Secure` podem não funcionar corretamente. Não habilite proxies confiáveis de forma ampla sem conhecer os endereços usados pela hospedagem.

Além disso:

1. a raiz pública do domínio deve apontar somente para `public`;
2. `.env`, `vendor`, `storage` e o código PHP não podem ser servidos como arquivos estáticos;
3. mantenha `storage/app/private/relatorios` sem link público;
4. proteja o painel da hospedagem e o banco com senhas diferentes e fortes;
5. mantenha backup conjunto do banco e dos anexos;
6. revise usuários e logs periodicamente.

## Logs e resposta a incidente

São registrados login, falha de login, logout, atribuição de relatores, decisão, envio/avaliação de relatório e acesso a anexos. Os registros ficam nos logs do Laravel e não substituem uma trilha de auditoria imutável.

Ao suspeitar do comprometimento de uma conta:

1. troque a senha da conta;
2. remova as sessões dela na tabela `sessions`;
3. examine `storage/logs` e os registros alterados no período;
4. preserve uma cópia dos logs antes de fazer limpeza;
5. se arquivos ou banco foram afetados, retire o sistema do ar e restaure um backup validado.

Não exclua uma conta apenas para bloquear acesso: as chaves estrangeiras atuais podem apagar HAEs e histórico em cascata.

## Limitações aceitas e próximos controles

- não há MFA, expiração forçada, bloqueio/desativação de conta, recuperação ou troca obrigatória da senha inicial;
- a regra mínima de 6 caracteres e o eventual uso de CPF foram aceitos por compatibilidade operacional, com risco maior de adivinhação;
- anexos não passam por antivírus; tipos e tamanhos são validados, mas documentos permitidos ainda devem ser tratados como não confiáveis;
- a trilha de auditoria está em arquivos de log e pode ser alterada por quem controla a hospedagem;
- a exportação inclui dados pessoais necessários ao trabalho da direção;
- não foi realizado teste de invasão nem revisão da configuração real da HostGator;
- exclusões em cascata de usuários precisam ser redesenhadas antes de permitir exclusão de coordenadores ou direção;
- é recomendável auditar relatórios duplicados antes de criar uma restrição única retroativa em `relatorios.hae_id`.

Para o porte atual, esses itens são riscos residuais administráveis. Reavalie MFA, antivírus e auditoria dedicada se o sistema passar a armazenar documentos sensíveis, receber muitos usuários ou ser integrado a outros serviços.
