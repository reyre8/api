<?php
namespace Reyre8\EnsembleChallenge\APIHandlers;

use Reyre8\EnsembleChallenge\Libs;

interface APIHandlerInterface
{
    /**
     * Signature for getAll
     */
    public function getAll(Libs\RequestHandler $requestHandler);

    /**
     * Signature for get
     */
    public function get(Libs\RequestHandler $requestHandler);

    /**
     * Signature for post
     */
    public function post(Libs\RequestHandler $requestHandler);

    /**
     * Signature for put
     */
    public function put(Libs\RequestHandler $requestHandler);

    /**
     * Signature for patch
     */
    public function patch(Libs\RequestHandler $requestHandler);

    /**
     * Signature for delete
     */
    public function delete(Libs\RequestHandler $requestHandler);
}