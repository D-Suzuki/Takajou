<?php
namespace Takajou\Controller;

class Base extends \Phalcon\Mvc\Controller {

    private $_isJsonResponse = true;

    public function getService($diName)
    {
        return $this->di->get($diName);
    }

    public function afterExecuteRoute(\Phalcon\Mvc\Dispatcher $dispatcher)
    {
        if ($this->_isJsonResponse) {
            $data = $dispatcher->getReturnedValue();
            if (is_array($data)) {
                $this->response->setContentType('application/json', 'UTF-8');
                $this->response->setJsonContent($data);
            }
            $this->response->send();
        }
    }
}