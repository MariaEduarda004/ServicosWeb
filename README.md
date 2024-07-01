# Sistema de Avaliação de Restaurantes

## Descrição
Este projeto é uma plataforma para avaliação e comentários de restaurantes. Permite que os usuários registrem suas experiências gastronômicas e ajudem outros consumidores a tomarem decisões informadas sobre onde comer.

## Autor
- Nome: Maria Eduarda Carvalho Lemos
- Projeto construído para disciplica de Serviços Web

## Requisitos
- PHP 7.4 ou superior
- Laravel 8 ou superior
- MySQL
- Composer
- Node.js
- Swagger para documentação da API

## Instalação
1. Clone o repositório:
   ```sh
   git clone https://github.com/MariaEduarda004/ServicosWeb.git
   cd ServicosWeb
2. Instale as dependências do PHP:
   ```sh
   composer install
3. Instale as dependências do Node.js:
   ```sh
   npm install
4. Configure o arquivo .env:
   ```sh
   cp .env.example .env
   php artisan key:generate
5. Configure as informações do banco de dados no arquivo .env.
6. Execute as migrações:
   ```sh
   php artisan migrate
7. Inicie o servidor:
   ```sh
   php artisan serve
## Uso
### Endpoints da API
A documentação completa da API pode ser acessada através do Swagger, disponível na URL /api/documentation após iniciar o servidor.

- GET /restaurantes: Lista todos os restaurantes.
- POST /restaurantes: Cria um novo restaurante.
- GET /restaurantes/{id}: Obtém um restaurante específico pelo ID.
- PUT /restaurantes/{id}: Atualiza um restaurante específico pelo ID.
- DELETE /restaurantes/{id}: Exclui um restaurante específico pelo ID.
- GET /restaurantes/{id}/avaliacoes: Lista todas as avaliações de um restaurante específico.
- POST /restaurantes/{id}/avaliacoes: Cria uma nova avaliação para um restaurante específico.
- POST /login: Login de usuário.
- POST /register: Cria um novo usuário.
- GET /user: Busca dados de usuário por token.



