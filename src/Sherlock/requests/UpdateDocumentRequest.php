<?php
/**
 * User: Umutcan Onal
 * Date: 22.05.2014
 * Time: 17:22
 */

namespace Sherlock\requests;

use \Elasticsearch\Client;

class UpdateDocumentRequest extends Request
{

    public function __construct($esClient)
    {
        $this->params['index'] = null;
        $this->params['type']  = null;
        $this->params['id']  = null;
        $this->params['updateScript'] = null;
        $this->params['updateParams'] = null;
        $this->params['updateUpsert'] = null;
        $this->params['doc']          = null;

        parent::__construct($esClient);

    }

    /**
     * @param $name
     * @param $args
     *
     * @return UpdateDocumentRequest
     */
    public function __call($name, $args)
    {
        $this->params[$name] = $args[0];

        return $this;
    }

    /**
     * @param $id
     *
     * @return UpdateDocumentRequest
     */
    public function document($id)
    {
        $this->params['id'] = $id;
        return $this;
    }

    public function execute()
    {
        $body = array();
        if ($this->params['updateScript'] !== null) {
            $body['script'] = $this->params['updateScript'];
        }

        if ($this->params['doc'] !== null) {
            $body['body']['doc'] = $this->params['doc'];
        }

        if ($this->params['updateParams'] !== null) {
            $body['body']["params"] = $this->params['updateParams'];
        }

        if ($this->params['updateUpsert'] !== null) {
            $body['body']["upsert"] = $this->params['updateUpsert'];
        }

        $params['index']=$this->params['index'];
        $params['type']=$this->params['type'];
        $params['id']=$this->params['id'];
        $params['body']=$body;

        return $this->esClient->update($params);
    }
} 