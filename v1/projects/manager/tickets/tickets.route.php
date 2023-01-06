<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE, PATCH");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
header('Content-Type: application/json');

use Aws\Support\SupportClient;
use Psr\Http\Message\ResponseInterface as ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as ServerRequestInterface;

$app->group('/v1/manager/tickets', function () use ($conn) {

    $this->get('/list', function (ServerRequestInterface $request, ResponseInterface $response, $arguments) use ($conn) {
        // $requestData = $request->getParsedBody();
        $getToken = HeaderHelper::getToken($request->getHeader('HTTP_AUTHORIZATION'));
        $headers = $request->getHeaders();

        try {
            AdminTicketsController::listTickets();
        } catch (\Exception $e) {
            ResponseHelper::sendErrorResponse(ResponseStatus::HTTP_BAD_REQUEST, null, ResponseTextHelper::ERROR_GENERIC_MESSAGE, $e->getMessage());
        }
    });

    $this->get('/single/{id}', function (ServerRequestInterface $request, ResponseInterface $response, $arguments) use ($conn) {
        // $requestData = $request->getParsedBody();
        $getToken = HeaderHelper::getToken($request->getHeader('HTTP_AUTHORIZATION'));
        $headers = $request->getHeaders();
        $idticket = $arguments['id'];

        try {
            AdminTicketsController::singleTicket($idticket);
        } catch (\Exception $e) {
            ResponseHelper::sendErrorResponse(ResponseStatus::HTTP_BAD_REQUEST, null, ResponseTextHelper::ERROR_GENERIC_MESSAGE, $e->getMessage());
        }
    });

    $this->post('/new', function (ServerRequestInterface $request, ResponseInterface $response, $arguments) use ($conn) {
        $requestData = $request->getParsedBody();
        $getToken = HeaderHelper::getToken($request->getHeader('HTTP_AUTHORIZATION'));
        $headers = $request->getHeaders();

        try {
            AdminTicketsController::newTicket($requestData);
        } catch (\Exception $e) {
            ResponseHelper::sendErrorResponse(ResponseStatus::HTTP_BAD_REQUEST, null, ResponseTextHelper::ERROR_GENERIC_MESSAGE, $e->getMessage());
        }
    });

    $this->patch('/edit/{id}', function (ServerRequestInterface $request, ResponseInterface $response, $arguments) use ($conn) {
        $requestData = $request->getParsedBody();
        $getToken = HeaderHelper::getToken($request->getHeader('HTTP_AUTHORIZATION'));
        $headers = $request->getHeaders();
        $idticket = $arguments['id'];

        try {
            AdminTicketsController::editTicket($requestData);
        } catch (\Exception $e) {
            ResponseHelper::sendErrorResponse(ResponseStatus::HTTP_BAD_REQUEST, null, ResponseTextHelper::ERROR_GENERIC_MESSAGE, $e->getMessage());
        }
    });

    $this->post('/message/add/{ticket_id}', function (ServerRequestInterface $request, ResponseInterface $response, $arguments) use ($conn) {
        $requestData = $request->getParsedBody();
        $getToken = HeaderHelper::getToken($request->getHeader('HTTP_AUTHORIZATION'));
        $headers = $request->getHeaders();
        $idticket = $arguments['ticket_id'];
        $user = $getToken->id;

        try {
            AdminTicketsController::addMessage($requestData, $idticket, $user);
        } catch (\Exception $e) {
            ResponseHelper::sendErrorResponse(ResponseStatus::HTTP_BAD_REQUEST, null, ResponseTextHelper::ERROR_GENERIC_MESSAGE, $e->getMessage());
        }
    });

    $this->delete('/message/remove/{message_id}', function (ServerRequestInterface $request, ResponseInterface $response, $arguments) use ($conn) {
        $requestData = $request->getParsedBody();
        $getToken = HeaderHelper::getToken($request->getHeader('HTTP_AUTHORIZATION'));
        $headers = $request->getHeaders();
        $message_id = $arguments['message_id'];
        $user = $getToken->id;

        try {
            AdminTicketsController::removeMessage($message_id);
        } catch (\Exception $e) {
            ResponseHelper::sendErrorResponse(ResponseStatus::HTTP_BAD_REQUEST, null, ResponseTextHelper::ERROR_GENERIC_MESSAGE, $e->getMessage());
        }
    });
});
