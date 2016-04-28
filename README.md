# zRFQuery

Pacote para buscar informações na Receita Federal referente a um CNPJ.

É feito uma requisição no serviço, retornando `cookie` e `captcha` do serviço. Após usuário informar
o captacha é feito outra requisição, retornando informações do CNPJ.

Este pacote deverá ser usado com responsabilidade, o autor e contribuidores não devem responder pelas implementações/ações feita com este pacote.

### Atenção

Este pacote foi desenvolvido com o intuito de facilidade consultas através de ERP ou serviços que necessitam de consistência de dados. Não foi criado com o intuito de ser utilizado como `bot`

Toda implementação será de sua responsabilidade.

### Version
1.0.0

### Instalação

```sh
$ composer require zrf/query 1.*
```
### Exemplo

```php
<?php

use zRF\Query\Search as zRF;

$cookie = zRF::cookie(); // retorna cookie para uso
$base64Image = zRF::image(); // base64 do captcha

// ...

// Informe para o método search o CNPJ, o captcha digitado e o cookie
zRF::search($cnpj, $digits, $cookie);
```

### Retorno
![Retorno](http://s32.postimg.org/r60gurdg5/Screenshot_from_2016_04_28_18_43_13.png)

### Desenvolvimento
Deseja contribuir com desenvolvimento? pull request :)

### To-do
- Implementar serviço `DeathByCaptcha`
- Criar API de consulta
- Repositório de imagem
- Testes unitários

License
----
MIT

**Free Software, Hell Yeah!**

[//]: # (These are reference links used in the body of this note and get stripped out when the markdown processor does its job. There is no need to format nicely because it shouldn't be seen. Thanks SO - http://stackoverflow.com/questions/4823468/store-comments-in-markdown-syntax)

