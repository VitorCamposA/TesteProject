# API de Dicionário

## 📋 Sobre o Projeto

Este é um sistema de API de dicionário desenvolvido em Laravel que permite aos usuários pesquisar palavras em inglês, gerenciar favoritos e manter um histórico de buscas. O sistema utiliza autenticação JWT para proteger as rotas e oferece uma interface RESTful completa.

## ✨ Funcionalidades

### 🔐 Autenticação
- Cadastro de usuários (signup)
- Login de usuários (signin)
- Autenticação via JWT

### 📚 Dicionário
- Busca de palavras em inglês
- Visualização de detalhes de palavras
- Listagem paginada de palavras
- Histórico de palavras pesquisadas

### ⭐ Favoritos
- Adicionar palavras aos favoritos
- Remover palavras dos favoritos
- Listar palavras favoritas do usuário
- Verificar se uma palavra está favoritada

### 👤 Perfil do Usuário
- Visualização dos dados do usuário
- Histórico de palavras pesquisadas
- Lista de palavras favoritas

## 🛠️ Tecnologias Utilizadas

- **Laravel** - Framework PHP
- **MySQL** - Banco de dados
- **JWT** - Autenticação
- **Redis** - Cache
- **Postman** - Testes de API

## 📦 Estrutura do Banco de Dados

O sistema utiliza as seguintes tabelas:
- `users` - Armazena informações dos usuários
- `words` - Armazena o dicionário de palavras
- `favorites` - Relaciona usuários com suas palavras favoritas
- `histories` - Registra o histórico de palavras pesquisadas pelos usuários

## 🔌 Endpoints da API

### Autenticação
- `POST /auth/signup` - Cadastro de usuário
- `GET /auth/signin` - Login de usuário

### Palavras
- `GET /entries/en/{word}` - Detalhes de uma palavra
- `GET /entries/en` - Lista de palavras (com busca opcional)

### Favoritos
- `POST /entries/en/{word}/favorite` - Adicionar aos favoritos
- `DELETE /entries/en/{word}/unfavorite` - Remover dos favoritos
- `GET /user/me/favorites` - Listar favoritos do usuário

### Usuário
- `GET /user/me/history` - Histórico de palavras pesquisadas
- `GET /user/me` - Dados do usuário

## 🚀 Como Executar

1. Clone o repositório
2. Instale as dependências:
   ```bash
   composer install
   ```
3. Configure o arquivo `.env` com suas credenciais
4. Execute as migrações:
   ```bash
   php artisan migrate
   ```
5. Inicie o servidor:
   ```bash
   php artisan serve
   ```

## 📝 Testes

O projeto inclui uma coleção do Postman (`api-test-collection.json`) para testar todos os endpoints da API.

## 📄 Licença

Este projeto está sob a licença MIT.
