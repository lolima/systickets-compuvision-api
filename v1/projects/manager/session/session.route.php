<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
header('Content-Type: application/json');

use Aws\Support\SupportClient;
use Psr\Http\Message\ResponseInterface as ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as ServerRequestInterface;

$app->group('/v1/manager/session', function () use ($conn) {

    $this->post('/signin', function (ServerRequestInterface $request, ResponseInterface $response, $arguments) use ($conn) {
        $requestData = $request->getParsedBody();

        if (StaffSessionController::checkSignInFields($requestData)) {
            try {

                $requestData['login'] = trim($requestData['login']);

                $sql = AdminSessionQueries::staffCheckFullInfo($requestData);
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $user = $stmt->fetch();

                if (!$user) {
                    ResponseHelper::sendErrorResponse(ResponseStatus::HTTP_CONFLICT, null, ResponseTextHelper::ERROR_USER_NOT_FOUND);
                } else {
                    if (StaffSessionController::comparePasswords($requestData, $user)) {                        
                        $id = $user['id'];
                        $sql = AdminSessionQueries::staffInfo($id);
                        $stmt = $conn->prepare($sql);
                        $stmt->execute();
                        $user = $stmt->fetch();


                        $payload = StaffSessionController::prepareForAPIReturn($user);
                        $payload['token'] = StaffSessionController::generateToken($payload);

                        ResponseHelper::sendSuccessResponse(ResponseStatus::HTTP_OK, $payload, ResponseTextHelper::USER_SIGNIN);
                    } else {
                        ResponseHelper::sendErrorResponse(ResponseStatus::HTTP_CONFLICT, null, ResponseTextHelper::ERROR_USER_SIGNIN);
                    }
                }
            } catch (\Exception $e) {
                ResponseHelper::sendErrorResponse(ResponseStatus::HTTP_BAD_REQUEST, $requestData, ResponseTextHelper::ERROR_USER_SIGNIN, $e->getMessage());
            }
        } else {
            ResponseHelper::sendErrorResponse(ResponseStatus::HTTP_BAD_REQUEST, null, ResponseTextHelper::ERROR_CHECK_FIELDS);
        }
    });
});
