# Laravel Sales Manager

Sistema de gestão de vendas desenvolvido durante um curso de Laravel. Permite cadastrar clientes e produtos, registrar vendas e emitir recibos.

## Iniciar localmente

Requisitos: Git, Docker com Compose v2 que aceite `up --wait` e um terminal POSIX (Linux, macOS ou WSL). Na pasta do projeto:

```sh
sh bin/setup
```

Abra [o dashboard](http://localhost:8989/dashboard). O comando cria o `.env` e uma senha local no primeiro uso, aguarda o MySQL, instala as dependências do `composer.lock`, gera a chave Laravel se necessário e aplica as migrações. A primeira compilação precisa de internet. O painel funciona com o banco vazio. Ao editar PHP, Blade ou CSS, recarregue a página.

### Uso diário

| Tarefa | Comando |
| --- | --- |
| Parar, preservando os dados | `docker compose down` |
| Iniciar novamente | `sh bin/setup` |
| Ver por que a inicialização falhou | `docker compose logs --tail=100 sistema_gestao mysql` |
| Criar uma venda fictícia de demonstração | `docker compose exec sistema_gestao php artisan db:seed` |
| Executar os testes em SQLite em memória | `docker compose exec sistema_gestao php artisan test` |
| Abrir phpMyAdmin opcional em localhost:8899 | `docker compose --profile tools up -d phpmyadmin` |

O serviço de e-mail padrão grava os recibos em `storage/logs/laravel.log`. Para mudar a porta do site, ajuste `APP_PORT` e `APP_URL` no `.env` e execute a inicialização novamente. O MySQL fica na rede interna do Compose. No phpMyAdmin, use as credenciais do `.env`.

### Instalações anteriores

Mantenha o `.env` original, incluindo `APP_KEY` e as credenciais do banco. Se o diretório `.docker/mysql/dbdata/mysql` estiver presente, o inicializador seleciona o diretório e a imagem MySQL 5.7.22 usados pelo projeto antigo. Para migrar ao MySQL 8.4, faça uma exportação lógica e importe em um volume novo. Apontar uma versão nova do servidor diretamente para os arquivos antigos pode danificá-los.

Instalações novas usam um volume Docker chamado `sales_db` (com prefixo do projeto Compose). Arquivos de banco, dumps e configurações locais ficam fora do Git. Se o `.env` tiver sido copiado à mão, defina `DB_PASSWORD` antes de iniciar. Para um volume novo, use um usuário de aplicação, como `sales_app`.

### Dashboard

A página inicial mostra vendas totais, vendas e clientes compradores no mês, as cinco vendas mais recentes e os três produtos com mais vendas no mês. Cada venda registrada representa um produto. O modelo atual guarda a referência ao produto e não registra o preço praticado no momento da venda.
