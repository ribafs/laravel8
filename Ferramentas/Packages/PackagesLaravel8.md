# Criação de pacotes no Laravel 8

Eu gostei do gerador de cruds para laravel - https://github.com/appzcoder/crud-generator

Sempre que eu o instalava, antes de usar, eu fazia algumas alterações. Então resolvi criar um fork e criar minha própria versão dele e publicar no Packagist para quando instalar usar a minha versão customizada e também poder distribuir para outros.

## Procurar e sobrescrever

Descompactei o pacote e o adaptei manualmente. Este foi meu primeiro pacote para laravel. Bem, para ser justo, é um pacote do appzcoder customizado por mim.

A minha primeira providência foi procurar informações sobre o appzcoder e sobrescrever pelas minhas:

- composer.json

Exemplo:

{
    "name": "ribafs/crud-generator",
    "description": "Laravel CRUD Generator by RibaFS",
    "authors": [
        {
            "name": "Sohel Amin",
            "email": "sohelamincse@gmail.com",
            "homepage": "http://www.Ribafs.com"
        },
        {
            "name": "Ribamar FS",
            "email": "ribafs@gmail.com",
            "homepage": "https://ribafs.github.io"
        }
    ],
...
    "autoload": {
        "psr-4": {
            "Ribafs\\CrudGenerator\\": "src/"
        },
    },
    "extra": {
        "laravel": {
            "providers": [
                "Ribafs\\CrudGenerator\\CrudGeneratorServiceProvider"
            ]
        }
    }
}

Então varri todos os scripts do pacote a procura de onde alterar.

- config/ (nada)
- docs/
    installation.md. Trocar para ribafs/crud-generator e Ribafs\CrudGenerator\CrudGeneratorServiceProvider
    O restante sem mudanças, mas como ficou não dá para instalar o do appzcoder e o do ribafs. Mas acho que não teria sentido fazer.

- src/CrudGeneratorServiceProvider.php. Trocar todas as ocorrências de Appzcoder por Ribafs.

## Finalizando com as minhas customizações

- src/stubs/views/html/index.blade.stub

Este arquivo tem uma linha

<td>{{ $loop->iteration }}</td>

Esta linha como está, tem um problema na listagem dos ids. Então um colega do grupo Laravel Brasil me deu a dica, mudar para:

<td>{{ $item->id }}</td>

E resolveu.

- Ainda no mesmo arquivo troquei

<table class="table">

Por 

<table class="table table-sm">

Achei melhor.

- Substitui o layout existente por um que eu gostava de usar em 

src/stubs/views/layouts/app.blade.php

- Adicionei o orderBy nos controllers gerador em

src/stubs/controller.stub

- Adicionei a pasta includes com o sidebar.blade.php chamado pelo layout

publish/views/includes/sidebar.blade.php

- Adicionei tratamento dos error 404, 402 e 500. Parece que isto ficou apenas nos planos, pois procurando agora não encontrei.


Empacotei tudo num zip e publiquei no Packagist (apgkagist.org).

Gosto de criar um release no GitHub para poder instalar assim:

composer require ribafs/crud-geenrator

Agora vem as versões. Faço os primeiros testes. Aparece um problema. Detecto, corrijo, gero um novo release e então instalo. Caso não apareça nenhum problema beleza, mas se aparecer outro problema, preciso identificar, corrigir, gerar novo release para testar novamente.

Assim fico até considerar estável para então poder divulgar, se for o caso.

Veja o caso do ribafs/laravel-acl (https://github.com/ribafs/laravel-acl). Ele está com 49 releases. Foram muitas correções e alguns ajustes para melhorar.

## Meu Sistema de versões

Existem regras para se noemar pacotes/projetos em geral. Geralmente são nomeados com 3 dígitos:

a.b.c

Exemplo

1.0.0

c - é a versão menor, quando corrigimos um bug, então mudamos o release para

1.0.1

Quando criamos uma nova feature para o projeto, mas não tão significativa, então o segundo dígito é incrementado

1.1.1

O primeiro dígito somente será incrementado quando nosso projeto sofrer uma grande mudança

2.1.1

Estou relatando aqui meio de cor, pode ser diferente, mas é por ai.

### Como eu nomeio meus releases

Com apenas 2 dígitos. A exemplo do laravel-acl, que está na versão 2.1

Incremento os egundo dígito indefinidamente, quando estou apenas corrigindo.

Somente mudo o primeiro quando adiciono algo que altera o projeto.

Mais simples. Me atende.


Até agora praticamente criei dois pacotes:

O crud-generator que deu origem ao crud-generator-acl

E o laravel-acl, que deu origem a vários, mudando apenas a versão do laravel e o último para ser instalado em aplicativo existente.

Algo legal destes pacotes, dos últimos, foi adicionar um diretório docs ao repositório e criar um site para sua documentação.

Gosto muito de fazer as coisas da forma mais prática possível. Para isso uso o mkdocs, um software que pega conteúdo em markdown e converte num bonito site em HTML, CSS e JavaScript.

Veja:

repositório - https://github.com/ribafs/laravel-acl

site - https://ribafs.github.io/laravel-acl/

Assim fiz com todos os derivados do laravel-acl

Aproveitei e mostrei como faço para usar o mkdocs - https://ribafs.github.io/laravel-acl/documentation/

Gosto também de procurar tempaltes free, especialmente com Bootstrap, de quem gosto muito, adaptar para usar na criação de sites dos repositórios, como fiz recentemente com o repositório laravel

ribafs.github.io/laravel


## Laravel-packager

Este pacote cria pacotes e me ajudou a criar os pacotes laravel*-acl.

## Instalar laravel-packager

composer require jeroen-g/laravel-packager --dev

## Publicar

php artisan vendor:publish --provider="JeroenG\Packager\PackagerServiceProvider"

## Criar pacote

php artisan packager:new Ribafs HelloPackage

Ele cria isso

vendor/ribafs/hellopackage -> /backup/www/laravel/pacote/packages/Ribafs/HelloPackage

Um link simbólico em

vendor/ribafs/hellopackage

Apontando para os arquivos que estão em

packages/Ribafs/HelloPackage

## Referências

https://github.com/jeroen-g/laravel-packager

