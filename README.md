# Laravel Sales Manager

Sistema de gestão de vendas em Laravel. Permite cadastrar clientes e produtos, registrar vendas e emitir recibos.

## Subir o projeto

Com Docker e Docker Compose **v2.24 ou superior**, abra a pasta do projeto e execute:

```sh
docker compose up --build -d
```

Acesse [o painel de vendas](http://localhost:8989/dashboard). A primeira compilação baixa as dependências do Composer. O Compose prepara as credenciais locais, aguarda o MySQL e aplica as migrações antes de disponibilizar o PHP e o Nginx. Os serviços `credenciais` e `migracoes` terminam com código 0 após concluir suas tarefas.

Em uma instalação nova, as chaves e os dados ficam em volumes do Docker. O banco começa vazio. Para personalizar portas, conexão ou e-mail, copie `.env.example` para `.env` e ajuste os valores antes de iniciar. Um `APP_KEY` ou `DB_PASSWORD` já configurado tem prioridade para a aplicação. Mudanças em PHP da pasta app, nas rotas, nos templates e nos arquivos públicos aparecem ao recarregar a página. Após alterar dependências, configurações PHP ou migrações, repita o comando com `--build`.

| Tarefa | Comando |
| --- | --- |
| Parar e manter os dados | `docker compose down` |
| Voltar a subir | `docker compose up -d` |
| Acompanhar a inicialização | `docker compose logs migracoes sistema_gestao nginx` |
| Criar uma venda de demonstração | `docker compose exec sistema_gestao php artisan db:seed` |
| Executar os testes em SQLite em memória | `docker compose exec sistema_gestao php artisan test` |
| Ler os recibos locais | `docker compose exec sistema_gestao tail -n 60 storage/logs/laravel.log` |
| Subir o phpMyAdmin opcional em localhost:8899 | `docker compose --profile tools up -d phpmyadmin` |

O phpMyAdmin conecta ao serviço `mysql`. Para acessá-lo com uma senha de sua escolha, defina `DB_USERNAME` e `DB_PASSWORD` antes da primeira inicialização do volume. Alterar a senha de um usuário já existente exige atualizá-la também no MySQL.

## Quem já utilizava o projeto

Preserve o nome do projeto Compose, o arquivo `.env` existente e os volumes. O banco da instalação anterior em MySQL 8.4 continua usando o volume `sales_db`. Se você ainda usa os arquivos de MySQL 5.7 do projeto original, confirme estas opções no `.env` **antes de subir** e mantenha as credenciais originais:

```dotenv
MYSQL_IMAGE=mysql:5.7.22
MYSQL_DATA_LOCATION=./.docker/mysql/dbdata
```

Para atualizar dados do MySQL 5.7 ao 8.4, exporte e importe em um volume novo. A nova configuração usa um volume para os arquivos Laravel em `storage`. Se houver uploads ou logs importantes no diretório local antigo, eles continuam no host e devem ser copiados para o novo volume se necessários.

## Dashboard

O painel reúne vendas recentes, clientes compradores e produtos mais vendidos no mês. Cada venda corresponde a um produto.
