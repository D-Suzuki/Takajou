<?php
namespace Takajou\Controller;

class Base extends \Phalcon\Mvc\Controller {

########
# 定数 #
########
    // レスポンスタイプ
    const RESPONSE_TYPE_JSON  = 1;
    const RESPONSE_TYPE_JSONP = 2;
    const RESPONSE_TYPE_DATA  = 3;

    // デフォルトコンテンツタイプ
    const DEFAULT_JSON_CONTENT_TYPE  = 'Content-type: application/json; charset=UTF-8';
    const DEFAULT_JSONP_CONTENT_TYPE = 'Content-type: application/javascript; charset=UTF-8';
    const DEFALUT_DATA_CONTENT_TYPE  = 'Content-type: image/*';

    // デフォルト文字コード
    const DEFAULT_CHARSET = 'UTF-8';

##############
# プロパティ #
##############
    /**
     * レスポンスタイプ
     * @var unknown
     */
    private $responseType = self::RESPONSE_TYPE_JSON;
    private $callback     = NULL;
    
    public function initialize() {

        // callbackパラメータが設定されていた場合
        $callback = $this->request->get('callback');
        if (strlen($callback) > 0) {
            $this->setResponseType(self::RESPONSE_TYPE_JSONP);
            $this->setCallback($callback);
        } else {
            ;
        }
    }

############
# アクセサ #
############
    /**
     * レスポンスタイプへのsetter
     * @param unknown $responseType
     */
    protected function setResponseType($responseType) {
        $this->responseType = $responseType;
    }

    /**
     * レスポンスタイプへのgetter
     */
    protected function getResponseType() {
        return $this->responseType;
    }
    
    /**
     * コールバック名へのsetter
     * @var string
     */
    protected function setCallback( $callback ) {
        $this->callback = $callback;
    }

##########################
# コントローラーメソッド #
##########################
    /**
     * レスポンス返却
     * @param \Phalcon\Mvc\Dispatcher $dispatcher
     */
    public function afterExecuteRoute(\Phalcon\Mvc\Dispatcher $dispatcher) {

        $data = $dispatcher->getReturnedValue();

        $responseType = $this->getResponseType();
        // JSON
        if ($responseType == self::RESPONSE_TYPE_JSON) {
            $this->response->setContentType(self::DEFAULT_JSON_CONTENT_TYPE, self::DEFAULT_CHARSET);
            $this->response->setJsonContent($data);

        // JSONP
        } elseif($responseType == self::RESPONSE_TYPE_JSONP) {
            $this->response->setContentType(self::DEFAULT_JSONP_CONTENT_TYPE, self::DEFAULT_CHARSET);
            $this->response->setJsonContent($data);
            $this->response->setContent($this->callback . '(' . $this->response->getContent() . ')');

        // DATA
        } elseif($responseType == self::RESPONSE_TYPE_DATA) {
            $this->response->setContentType(self::DEFALUT_DATA_CONTENT_TYPE, self::DEFAULT_CHARSET);
            $this->response->setContent($data);

        }

        $this->response->send();
    }
}
