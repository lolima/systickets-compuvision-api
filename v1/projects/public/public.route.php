<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE, PATCH");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
header('Content-Type: application/json');

use Aws\Support\SupportClient;
use Psr\Http\Message\ResponseInterface as ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as ServerRequestInterface;

$app->group('/v1/public', function () use ($conn) {

    $this->get('/ticket/single/{id}', function (ServerRequestInterface $request, ResponseInterface $response, $arguments) use ($conn) {
        // $requestData = $request->getParsedBody();
        // $getToken = HeaderHelper::getToken($request->getHeader('HTTP_AUTHORIZATION'));
        $headers = $request->getHeaders();
        $idticket = $arguments['id'];

        try {
            AdminTicketsController::singleTicket($idticket);
        } catch (\Exception $e) {
            ResponseHelper::sendErrorResponse(ResponseStatus::HTTP_BAD_REQUEST, null, ResponseTextHelper::ERROR_GENERIC_MESSAGE, $e->getMessage());
        }
    });

    $this->get('/ticket/all/{id}', function (ServerRequestInterface $request, ResponseInterface $response, $arguments) use ($conn) {
        // $requestData = $request->getParsedBody();
        // $getToken = HeaderHelper::getToken($request->getHeader('HTTP_AUTHORIZATION'));
        $headers = $request->getHeaders();
        $user_email = $arguments['id'];

        try {
            AdminTicketsController::allUserTickets($user_email);
        } catch (\Exception $e) {
            ResponseHelper::sendErrorResponse(ResponseStatus::HTTP_BAD_REQUEST, null, ResponseTextHelper::ERROR_GENERIC_MESSAGE, $e->getMessage());
        }
    });

    $this->post('/ticket/new', function (ServerRequestInterface $request, ResponseInterface $response, $arguments) use ($conn) {
        $requestData = $request->getParsedBody();
        // $getToken = HeaderHelper::getToken($request->getHeader('HTTP_AUTHORIZATION'));
        $headers = $request->getHeaders();

        try {
            AdminTicketsController::newTicket($requestData);
        } catch (\Exception $e) {
            ResponseHelper::sendErrorResponse(ResponseStatus::HTTP_BAD_REQUEST, null, ResponseTextHelper::ERROR_GENERIC_MESSAGE, $e->getMessage());
        }
    });

});