# Criando classes Connect

Para criar uma nova classe de Connect você deve estender a classe BaseConnect e usar o sufixo 'Connect' conforme exemplo abaixo de uma classe para se conectar com o Twitter

```php
<?php

namespace Ag2Nurun\Olap\Connect;

use Illuminate\Support\Collection;
use Ag2Nurun\Olap\Connect\ConnectResponse;

class TwitterConnect extends BaseConnect
{

}
```

Toda classe de Connect precisa implementar um método 'formatResponse' que retorna um object do tipo ConnectResponse

```php
<?php

namespace Ag2Nurun\Olap\Connect;

use Illuminate\Support\Collection;
use Ag2Nurun\Olap\Connect\ConnectResponse;

class TwitterConnect extends BaseConnect
{

    protected function formatResponse($response)
    {
        // tratamento do $response para formatar os headers e body da resposta

        return new ConnectResponse(
            $header,
            $body,
            $response
        );
    }
}
```

