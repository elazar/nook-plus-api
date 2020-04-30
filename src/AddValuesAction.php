<?php

namespace NookPlus;

use Predis\Client;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Fig\Http\Message\StatusCodeInterface as Status;

class AddValuesAction
{
    /**
     * @var Data
     */
    private $data;

    public function __construct(Data $data)
    {
        $this->data = $data;
    }

    public function __invoke(Request $request, Response $response, $args)
    {
        $id = $args['id']; 
        if (!$this->data->userExists($id)) {
            return $response->withStatus(Status::STATUS_NOT_FOUND);
        }

        $key = $args['key'];
        if (!$this->data->keyExists($key)) {
            return $response->withStatus(Status::STATUS_NOT_FOUND);
        }

        $values = json_decode($request->getBody()->getContents());

        $this->data->addValues($id, $key, $values);

        return $response->withStatus(Status::STATUS_NO_CONTENT);
    }
}
