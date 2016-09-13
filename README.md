# Nurun Data Insights - OLAP

Este package disponibiliza classes para conexão com APIs de terceiros e classes de extração de dados destas APIs.

#### Para criar uma nova classe de Connect leia a documentação [CONNECT](http://gitag2nurun.hostsag2.com.br/NDI/nurun-data-insights-olap/blob/master/CONNECT.md)

## Instalação

### Dependência

Este package deve ser instalado através do [composer](https://getcomposer.org/).

Se você não usa o composer em seus projetos PHP pare agora mesmo o que está fazendo e leia: [Easy package management with composer](http://code.tutsplus.com/tutorials/easy-package-management-with-composer--net-25530)

Para instalar basta abrir o arquivo composer.json do seu projeto e adicionar as informações abaixo

```json
{
    "repositories": [
        {
            "type": "gitlab",
            "url": "http://gitag2nurun.hostsag2.com.br/NDI/nurun-data-insights-olap.git",
            "no-api": true
        }
    ],
    "config": {
        "gitlab-domains": ["gitag2nurun.hostsag2.com.br"]
    },
    "require": {
        "ag2-nurun/olap": "dev-master"
    },
    "minimum-stability": "dev"
}
```

Depois não esqueça de incluir o autoload em seu projeto

```php
<?php

require_once '/path/to/your-project/vendor/autoload.php';
```

## Exemplo de uso

### Adobe

#### Autenticando

É necessário autenticar na api antes de começar a puxar relatórios

```php
<?php

require_once 'vendor/autoload.php';

$adobe  = new AdobeConnect();

$adobe->setCredentials(
    'usuario',
    'senha',
    'report suite id'
);

$adobe->auth();
```

#### Colocando um relatório na fila

O sistema da Adobe Ominiture trabalha com relatórios assíncronos, portanto é necessário primeiro realizar uma chamada colocando um relatório na fila para criação e após isso outra chamada a api para recuperar o relatório quando ele estiver pronto.

```php
<?php

// Exemplo de como colocar um relatório simples de visitas na fila
$reportId = $adobe->createReport(
    new Carbon('2016-01-01'),
    new Carbon('2016-01-02'),
    'day',
    ['visits']
);
```

O método 'createReport' retorna um reportId que será usado para baixar o relatório pronto

#### Baixar um relatório pronto

A Adobe costuma criar o relatório quase que instantaneamente, porém alguns relatórios, os com um intervalo de tempo muito grande levam mais tempo, as vezes 2, 4s até ficarem prontos.

Dessa forma um loop é necessário para tentar buscar o relatório até ele ficar pronto

```php
<?php

// Utilizando o $reportId do exemplo anterior
do {
    $report = $adobe->getQueuedReport($reportId);
    var_dump($report);
    sleep(2);
} while (isset($report->getRawResponse()['error']) && $report->getRawResponse()['error'] == 'report_not_ready');
```

A resposta deste relatório terá o formato abaixo

```php
<?php

Array
(
    [2016-01-01] => Array
        (
            [date] => Carbon\Carbon Object
                (
                    [date] => 2016-01-01 00:00:00.000000
                    [timezone_type] => 3
                    [timezone] => America/Sao_Paulo
                )

            [visits] => 15405
        )

    [2016-01-02] => Array
        (
            [date] => Carbon\Carbon Object
                (
                    [date] => 2016-01-02 00:00:00.000000
                    [timezone_type] => 3
                    [timezone] => America/Sao_Paulo
                )

            [visits] => 20330
        )
)
```
