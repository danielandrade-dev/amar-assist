# AMAR Assist - Sistema de Gerenciamento de Produtos

## 📋 Sobre o Projeto

AMAR Assist é um sistema de gerenciamento de produtos desenvolvido com Laravel e Vue.js. O sistema oferece funcionalidades de cadastro, edição e gerenciamento de produtos com uma interface moderna e intuitiva.

## 🚀 Funcionalidades

- Sistema de autenticação de usuários
- Gerenciamento completo de produtos:
  - Cadastro de produtos com múltiplas imagens
  - Edição de informações
  - Inativação de produtos
  - Validação automática de preços e custos
  - Editor de descrição com suporte a HTML básico
- Upload de imagens (suporte para JPG e PNG)
- Interface responsiva e moderna

## 🛠️ Tecnologias Utilizadas

- **Backend:**
  - PHP 8
  - Laravel 9
  - MySQL 8
  - MinIO (Armazenamento de objetos S3-compatible)

- **Frontend:**
  - Vue.js
  - HTML5/CSS3
  - JavaScript

- **Infraestrutura:**
  - Docker
  - Docker Compose

## ⚙️ Requisitos do Sistema

- Docker e Docker Compose instalados
- Git
- MinIO Server (incluído no Docker Compose)

## 🔧 Instalação

1. Clone o repositório:
```bash
git clone [https://github.com/danielandrade-dev/amar-assist]
cd amar-assist
```

2. Configure o ambiente:
```bash
cp .env.example .env
```

3. Configure as variáveis do MinIO no arquivo .env:
```env
FILESYSTEM_DRIVER=s3
AWS_ACCESS_KEY_ID=0CYmbypeoj0iTtBkalpV
AWS_SECRET_ACCESS_KEY=ajEBWxSSL1nF6Vo3C9mWL2gM27lv7oVzs79ajnsW
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=local
AWS_ENDPOINT=http://minio:9000
AWS_URL=http://localhost:9000/local
AWS_USE_PATH_STYLE_ENDPOINT=true
```

4. Inicie os containers Docker:
```bash
docker-compose up -d
```

5. Instale as dependências e configure o projeto:
```bash
docker-compose exec app composer install
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate
docker-compose exec app php artisan storage:link
```

6. Configure o bucket do MinIO:
```bash
# Acesse o console do MinIO em http://localhost:9001
# Credenciais padrão de acesso ao console: minioadmin / minioadmin

# Criando Access Key e Secret Key:
# 1. No console do MinIO, clique em "Access Keys" no menu lateral
# 2. Clique em "Create access key"
# 3. Guarde as credenciais geradas e atualize no seu .env:
#    AWS_ACCESS_KEY_ID=sua_access_key_gerada
#    AWS_SECRET_ACCESS_KEY=sua_secret_key_gerada

# Criando o Bucket:
# 1. No console, vá para "Buckets"
# 2. Clique em "Create Bucket"
# 3. Nome do bucket: local (ou o nome definido em AWS_BUCKET)
# 4. Em "Access Policy", selecione "public"
# 5. Clique em "Create Bucket"

# Configurando a Política de Acesso do Bucket:
# 1. Vá para o bucket criado
# 2. Clique em "Access Policy"
# 3. Selecione "Add Policy"
# 4. Escolha "Read Only"
# 5. Em "Principal" coloque "*" (sem aspas)
# 6. Em "Prefix" deixe vazio para acesso a todo bucket
# 7. Clique em "Add"
```

7. Instale as dependências do frontend:
```bash
docker-compose exec app npm install
docker-compose exec app npm run dev
```

## 🔒 Validações do Sistema

### Produtos
- Preço de venda deve ser superior ao custo + 10%
- Descrição aceita apenas as tags HTML: `<p>`, `<br>`, `<b>` e `<strong>`
- Suporte apenas para imagens nos formatos JPG e PNG

## 👥 Autores

- Daniel Andrade (danielthooth@gmail.com)

## 📄 Licença

Este projeto está sob a licença MIT.

Copyright (c) 2024 Daniel Tooth

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.

## 📞 Suporte

Para suporte, envie um email para danielthooth@gmail.com

## 📘 Guia de Uso

### Interface Web

1. **Login no Sistema**
   - Acesse `http://localhost:8000`
   - Use suas credenciais para entrar no sistema

2. **Gerenciamento de Produtos**
   - Na página principal, você verá a lista de produtos
   - Use o botão "Novo Produto" para adicionar um produto
   - Para cada produto na lista, você pode:
     - Clicar em "Editar" para modificar as informações
     - Usar "Inativar" para desativar temporariamente o produto

3. **Cadastro de Produtos**
   - Preencha todos os campos obrigatórios (Título, Preço, Custo)
   - Faça upload de uma ou mais imagens (JPG ou PNG)
   - Use o editor HTML para formatar a descrição (tags permitidas: `<p>`, `<br>`, `<b>`, `<strong>`)
   - O sistema validará automaticamente se o preço está pelo menos 10% acima do custo

## 🔌 API Documentation

### Autenticação com Sanctum

1. **Obter Token de Acesso**
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email": "seu-email@exemplo.com", "password": "sua-senha"}'
```

2. **Usar o Token nas Requisições**
```bash
curl -X GET http://localhost:8000/api/v1/products \
  -H "Authorization: Bearer {seu-token}"
```

### Endpoints Disponíveis

#### Produtos

- **Listar Produtos**
  ```
  GET /api/products
  ```

- **Obter Produto Específico**
  ```
  GET /api/products/{id}
  ```

- **Criar Produto**
  ```
  POST /api/products
  Content-Type: multipart/form-data
  
  {
    "title": "Nome do Produto",
    "price": 100.00,
    "cost": 80.00,
    "description": "<p>Descrição do produto</p>",
    "images[]": [arquivo1, arquivo2]
  }
  ```

- **Atualizar Produto**
  ```
  PUT /api/products/{id}
  ```

- **Inativar Produto**
  ```
  DELETE /api/products/{id}
  ```

### Documentação Completa da API

A documentação completa da API está disponível em:
```
http://localhost:8000/api/documentation
```

Para acessar a documentação:
1. Certifique-se de que o servidor está rodando
2. Acesse a URL da documentação
3. Use o botão "Authorize" para autenticar com seu token
4. Explore todos os endpoints disponíveis com exemplos de requisição e resposta

### Códigos de Status

- `200`: Sucesso
- `201`: Criado com sucesso
- `400`: Erro de validação
- `401`: Não autorizado
- `403`: Acesso proibido
- `404`: Não encontrado
- `422`: Erro de validação de dados
- `500`: Erro interno do servidor