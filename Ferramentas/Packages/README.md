# Packages no Laravel 8

Os pacotes/packages são a principal forma de adicionar funcionalidade ao Laravel. Os pacotes podem ser qualquer coisa, desde uma ótima maneira de trabalhar com datas como o Carbon ou uma estrutura de teste BDD inteira como o Behat, até praticamente um segundo aplicativo rodando dentro do aplicativo principal.

São uma forma de reaproveitamento de código e também de adionar funcionalidades aos aplicativos com Laravel.

Existem diferentes tipos de pacotes. Alguns pacotes são independentes, o que significa que funcionam com qualquer estrutura PHP. Carbon e Behat são exemplos de pacotes autônomos. Qualquer um desses pacotes pode ser usado com o Laravel solicitando-os em seu arquivo composer.json.

Por outro lado, outros pacotes são especificamente planejados para uso com o Laravel. Esses pacotes podem ter rotas, controladores, views e configurações especificamente destinadas a aprimorar um aplicativo Laravel. Este guia cobre principalmente o desenvolvimento dos pacotes que são específicos do Laravel.

## Uma nota sobre facades

Ao escrever um aplicativo Laravel, geralmente não importa se você usa contratos ou fachadas, já que ambos fornecem níveis essencialmente iguais de testabilidade. Porém, ao escrever pacotes, seu pacote normalmente não terá acesso a todos os ajudantes de teste do Laravel. Se você gostaria de poder escrever seus testes de pacote como se eles existissem dentro de uma aplicação típica do Laravel, você pode usar o pacote Orchestral Testbench.

## Descoberta de pacote

No arquivo de configuração config/app.php de uma aplicação Laravel, a opção providers define uma lista de provedores de serviço que devem ser carregados pelo Laravel. Quando alguém instala seu pacote, você normalmente deseja que seu provedor de serviços seja incluído nesta lista. Em vez de exigir que os usuários adicionem manualmente seu provedor de serviços à lista, você pode definir o provider na seção extra do arquivo composer.json do pacote. Além de service providers, você também pode listar quaisquer facades que gostaria de registrar:

    "extra": {
        "laravel": {
            "providers": [
                "Ribafs\\CrudGenerator\\CrudGeneratorServiceProvider"
            ]
        }
    }

Uma vez que seu pacote tenha sido configurado para descoberta, o Laravel irá registrar automaticamente seus provedores de serviço e facadas quando for instalado, criando uma experiência de instalação conveniente para os usuários do seu pacote.

## Optando pela não Descoberta do Pacote

Se você é o consumidor de um pacote e gostaria de desativar a descoberta de pacote para um pacote, você pode listar o nome do pacote na seção extra do arquivo composer.json do seu aplicativo:

"extra": {
    "laravel": {
        "dont-discover": [
            "barryvdh/laravel-debugbar"
        ]
    }
},


## Veja como crio meus pacotes para laravel

[Pacotes para Laravel 8](PackagesLaravel8.md)


## Como criar pacotes manualmente

[Criando Pacotes manualmente](CriacaoPacotes.md)


## Referências

https://laravel.com/docs/8.x/packages
