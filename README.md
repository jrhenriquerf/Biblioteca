# PHP-Advanced

Projeto de um sistema de biblioteca para a aula de PHP Avançado da Pós Graduação utilizando Laravel.

## Dependências
- [Composer](https://getcomposer.org/)
- [Docker](https://www.docker.com/get-started)
- [PHP](https://www.php.net/downloads)
- [Node](https://nodejs.org/en/)

## Instruções
Para rodar o projeto siga os passos a seguir:
- Clone o projeto
- Entre na pasta `src` e renomeie o arquivo `.env.example` para `.env`

### Baixar as dependências
- Entre na pasta `src` e rode os seguintes comandos
- Para baixar as dependências de front: `npm install`
- Para gerar o build do projeto: `npm run dev`
- Para baixar as dependências do back: `composer install`

### Configurações dos conteiners
- Rode o comando `docker-compose up -d` na pasta do projeto
- Entre na conteiner do php rodando o comando `docker exec -it biblioteca-php bash`
- Rode os seguintes comandos:
    - Para gerar a chave: `php artisan key:generate`
    - Para criar as tabelas no banco de dados: `php artisan migrate`

### Finalizando
- Após realizar os passos descritos o projeto será rodado em http://localhost:8091/ e será possível abrir o banco de dados atráves do phpmyadmin (http://localhost:8083/) com o usuário e senha configurados no docker-compose (**usuário:** developer, **senha:** biblioteca)

## Testando a API
As rotas desenvolvidas como API são:
- **POST** - `localhost:8091/api/v1/save_book`: Para cadastrar um novo livro utilizando o body abaixo
```
{
	"title": "Título do livro",
	"description": "Descrição do livro",
	"authors": ["1", "2", "3"],
	"image": "link da imagem"
}
```

- **GET** - `localhost:8091/api/v1/authors`: Para buscar todos os autores
- **GET** - `localhost:8091/api/v1/books`: Para buscar todos os livros


**Obs.:** Todas devem receber o `Authorization` com o token no header.
