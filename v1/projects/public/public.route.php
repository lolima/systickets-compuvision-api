<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
header('Content-Type: application/json');

use Aws\Support\SupportClient;
use Psr\Http\Message\ResponseInterface as ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as ServerRequestInterface;

$app->group('/v1/public', function () use ($conn) {

    $this->post('/lead', function (ServerRequestInterface $request, ResponseInterface $response, $arguments) use ($conn) {
        $requestBody = $request->getParsedBody();

        if (PublicController::checkTicketFields($requestBody)) {
            try {

                $generatedId = FunctionsHelper::GUIDv4();
               

                ResponseHelper::sendSuccessResponse(ResponseStatus::HTTP_OK, $generatedId, 'Sucesso');
            } catch (\Exception $e) {
                ResponseHelper::sendErrorResponse(ResponseStatus::HTTP_BAD_REQUEST, $requestBody,'Erro', $e->getMessage());
            }
        } else {
            ResponseHelper::sendErrorResponse(ResponseStatus::HTTP_BAD_REQUEST, null, 'Verifique se os campos foram preenchidos corretamente');
        }
    });

});