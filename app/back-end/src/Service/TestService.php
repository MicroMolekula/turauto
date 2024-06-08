<?php

use Symfony\Component\HttpFoundation\RequestStack;

class SomeService
{
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;

        // Получение доступа к сессии в конструкторе *НЕ* рекомендуется, так как
        // к ней еще может не быть доступа или это может привести к нежелательным побочным эффектам
        // $this->session = $requestStack->getSession();
    }

    public function someMethod()
    {
        $session = $this->requestStack->getSession();

        // сохраняет атрибут в сессии для дальнейшего повторного использования
        $session->set('attribute-name', 'attribute-value');

        // получает атрибут по имени
        $foo = $session->get('foo');

        // второй аргумент - это значение, возвращенное, если атрибут не существует
        $filters = $session->get('filters', []);

        // ...
    }
}