Pure PHP, Jquery and Bootstrap Phonebook
========================================

## Objetivo
Aplicação criada para realização de teste de aptidão técnica em decorrência de processo seletivo para preenchimento de vaga de trabalho em empresa brasileira não citada aqui.

## Descrição
A aplicação se resume a uma agenda telefônica desenvolvida em PHP puro, ou seja, sem a utilização de qualquer framework PHP. Para o frontend foram utilizados o Bootstrap Framework e Jquery.

*Algumas considerações sobre a aplicação*
- Para o backend foi desenvolvida uma API Restfull que disponibiliza os recursos para o gerenciamento(CRUD) de contatos da agenda. Para a estruturação da API, foi utilizado o conceito de MVC e o código da mesma encontra-se no diretório `api`. Dentro desse diretório, temos dos diretórios `Controller`, `Entity`, `Model` e `Test`. Dentro do diretório `Model` temos um arquivo denominado `DBConfig.php`, que contém os dados de comunicação ao banco de dados mysql.
- A aplicação apresenta validação dos campos nos CRUDs de cadastro e edição de usuário, sendo esta validação feita em duas frente, frontend(com html e jquery) e backend(com validação de obrigatoriedade e nulidade de campos na API) e máscara para o campo de telefone nos formulários de ambos os recursos(`create` e `update`).
- A aplicação conta também com um gráfico simples incial feito com jquery e atualizado por ajax que mostra a variação de quantidades de contatos adicionados em relação à data de cadastro do mesmo no ano de 2017.
- O arquivo `.htaccess` que encontra-se na pasta `api` garante que, mesmo que tenhamos outros `indexes` dentro de outros diretórios da raíz, os endpoints da API funcionem corretamente, desde que os virtual hosts sejam configurados corretamente.
- Alguns testes simples e iniciais para foram criados dentro da pasta `Test`. Diversos outros testes de unidade e de integração podem ser feitos, porém com o advento do tempo disponível para o desenvolvimento da aplicação ser restrito não houve a possibilidade, em um primeiro momento, da exploração aprofundada dos testes.
- O Frontend da aplicação está toda no diretório `frontend` e consome os recursos da API acessando seus endpoints por meio do script `manage-data.js`, que encontra-se localizado no diretório `fronted/js/`. O frontend foi contruído em forma de SPA com jquery e bootstrap e encontra-se totalmente desacoplado do backend, estando ambos ligados apenas pela API Rest criada, o que garante à aplicação uma melhor modularização, escalabilidade e independência de escopo e responsabilidades. 
- A aplicação não possui qualquer tipo de autenticação de usuário(signup, login, logout), pelo fato de as funcionalidades de autenticação não contarem no escopo da demanda descrita.

## Configuração de Ambientes e instalação
A primeira coisa que deve ser feita é a importação do arquivo `phonebook.sql`, que encontra-se no diretório `data`, em um banco de dados mysql.

Após a importação da tabela de contatos feita no passo acima, para que a aplicação funcione a pleno, deve-se criar dois virtual hosts, um para a API apontando para a para o diretório `api` e um para a home da aplicação aplicando para o diretório `frontend`. Ambos dentro do diretório raiz.

Abaixo segue em exemplo de como deveria ser aconfiguração dos virtal hosts utilizando um servidor Apache.

```php
# api.phonebook.dev [Phonebook - API]
<VirtualHost *:80>
    ServerName api.phonebook.dev
    ServerAlias api.phonebook.dev
    DocumentRoot "/Applications/MAMP/htdocs/phonebook-api/api"
    <Directory "/Applications/MAMP/htdocs/phonebook-api/api>
        Options Indexes FollowSymLinks
        AllowOverride All
        Order allow,deny
        Allow from all
    </Directory>
</VirtualHost>


# admin.phonebook.dev [Phonebook - Application]
<VirtualHost *:80>
    ServerName admin.phonebook.dev
    ServerAlias admin.phonebook.dev
    DocumentRoot "/Applications/MAMP/htdocs/phonebook-api/frontend"
    <Directory "/Applications/MAMP/htdocs/phonebook-api/frontend>
        Options Indexes FollowSymLinks
        AllowOverride All
        Order allow,deny
        Allow from all
    </Directory>
</VirtualHost>
```

Após realizar a configuração dos virtual hosts em seu servidor local ou web. Algumas configurações devem ser feitas no código da aplicação, sendo elas:

- Adicione os dados de acesso ao seu banco de dados no arquivo: `api/Model/DBConfig.php`. A partir da linha 8 Deixe algo como o exemplo abaixo:

```php
private $serverName = "localhost";
private $database = "phonebook";
private $userName = "db_username";
private $password = "bddv102";
```

- Adicione a URL do virtual host criado acima para a API no arquivo: `api/Test/Api/ApiResourcesTest.php`. Na linha 16 Deixe algo como o exemplo abaixo:

```php
$this->http = new \GuzzleHttp\Client(['base_uri' => 'http://api.phonebook.dev/']);
```

- Adicione a URL do virtual host criado acima para a API também no arquivo: `frontend/index.php`. A partir da linha 150 Deixe algo como o exemplo abaixo:

```html
<script type="text/javascript">
	var url = "http://api.phonebook.dev/";
</script>
```

- Rode um `composer install`, em um terminal, na raíz da aplicação para instalar todas as dependências.

- Para testes basta roda `phpunit`, em um terminal, também na raiz da aplicação.

Com as configurações acima realizadas, a aplicação já funcionará ao abrir a url `http://admin.phonebook.dev` em um browser.

## Considerações finais
Este projeto não é uma aplicação em produção e não deve ser tratada como tal, servindo para terceiros apenas para níveis de estudo.
