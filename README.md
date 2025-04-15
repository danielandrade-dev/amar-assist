### Descrição do Teste

Nesse teste, deve ser criado um sistema de cadastro de produtos. 

Será necessária uma página de Login, no qual pode ser cadastrado apenas um usuário padrão.

Ao efetuar o login, o usuário verá uma lista de produtos e terá três opções: Editar, Cadastrar e Inativar produtos.

O produto precisa ter os seguintes campos:

* Título
* Imagens (poderá ser carregado várias imagens)
* Preço de venda
* Custo
* Descrição do produto

Serão necessárias algumas validações, sendo elas:

* O preço do produto não pode ser inferior ao custo do produto + 10%
* A descrição será um campo aberto para HTML, porém só será aceito as tags: `<p>`, `<br>`, `<b>` e `<strong>`
* Só serão permitidas imagens jpg e png

### Arquitetura

O sistema deverá utilizar os seguintes recursos, respeitando as versões:

* PHP: versão 8
* MYSQL: versão 8
* Laravel: versão 9

O front-end deverá ser feito em Vue.

Ele deverá rodar em um docker.

### Entrega

O sistema deverá ser entregue em um repositório no git.

Importante: Não utilizar o Laravel Sail.

### Diferenciais

* Log de criação/modificação de produtos
* CRUD de usuários
* Boas práticas de Segurança
* Documentação (arquitetura, manual de instalação e manual de usuário)
* Testes unitários