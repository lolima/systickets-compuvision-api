<?php

use Firebase\JWT\JWT;
use Tuupola\Base62;

class AdminTicketsController
{
    public static function checkTicketFields($payload)
    {
        $required = array("client_name","client_phone","client_email","description");

        return FunctionsHelper::checkFields($payload, $required);
    }

    public static function listTickets()
    {
        $conn = CustomDatabaseInteractor::getInstance();

        try {
            $sql = AdminTicketsQueries::listTickets();
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $tickets = $stmt->fetchAll();

            foreach ($tickets as &$ticket) {
                $ticket_id = $ticket['id'];

                $sql = AdminTicketsQueries::loadTicketMessages($ticket_id);
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $ticket['messages'] = $stmt->fetchAll();
            }

            ResponseHelper::sendSuccessResponse(ResponseStatus::HTTP_OK, $tickets, 'Tickets listados com sucesso');
        } catch (\Exception $e) {
            ResponseHelper::sendErrorResponse(ResponseStatus::HTTP_BAD_REQUEST, null, 'Erro ao listar tickets', $e->getMessage());
        }
    }

    public static function singleTicket($id)
    {

        try {
            $ticket = self::getTicketInfo($id);


            ResponseHelper::sendSuccessResponse(ResponseStatus::HTTP_OK, $ticket, 'Ticket listado com sucesso');
        } catch (\Exception $e) {
            ResponseHelper::sendErrorResponse(ResponseStatus::HTTP_BAD_REQUEST, null, 'Erro ao listar ticket', $e->getMessage());
        }
    }


    public static function getTicketInfo($id)
    {
        $conn = CustomDatabaseInteractor::getInstance();

        $sql = AdminTicketsQueries::singleTicket($id);
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $ticket = $stmt->fetch();

        $ticket_id = $ticket['id'];

        $sql = AdminTicketsQueries::loadTicketMessages($ticket_id);
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $ticket['messages'] = $stmt->fetchAll();

        return $ticket;
    }

    public static function newTicket($payload)
    {
        $conn = CustomDatabaseInteractor::getInstance();

        if (!self::checkTicketFields($payload)) {
            ResponseHelper::sendErrorResponse(ResponseStatus::HTTP_BAD_REQUEST, null, ResponseTextHelper::CHECK_FIELDS);
        }

        try {
            $identifier = FunctionsHelper::generateRandomId(8);
            $guid = FunctionsHelper::GUIDv4();

            $payload['id'] = $guid;
            $payload['identifier'] = $identifier;

            $sql = AdminTicketsQueries::newTicket($payload);
            $stmt = $conn->prepare($sql);
            $stmt->execute();

            $ticket = self::getTicketInfo($identifier);
            ResponseHelper::sendSuccessResponse(ResponseStatus::HTTP_OK, $ticket, 'Tickets criado com sucesso');
        } catch (\Exception $e) {
            ResponseHelper::sendErrorResponse(ResponseStatus::HTTP_BAD_REQUEST, null, 'Erro ao criar ticket', $e->getMessage());
        }
    }

    public static function editTicket($payload)
    {
        $conn = CustomDatabaseInteractor::getInstance();

        if (!self::checkTicketFields($payload)) {
            ResponseHelper::sendErrorResponse(ResponseStatus::HTTP_BAD_REQUEST, null, ResponseTextHelper::CHECK_FIELDS);
        }

        try {
            $sql = AdminTicketsQueries::editTicket($payload);
            $stmt = $conn->prepare($sql);
            $stmt->execute();

            $ticket = self::getTicketInfo($payload['identifier']);
            ResponseHelper::sendSuccessResponse(ResponseStatus::HTTP_OK, $ticket, 'Tickets alterado com sucesso');
        } catch (\Exception $e) {
            ResponseHelper::sendErrorResponse(ResponseStatus::HTTP_BAD_REQUEST, null, 'Erro ao alterar ticket', $e->getMessage());
        }
    }

    public static function addMessage($payload, $ticket_id, $user_id)
    {
        $conn = CustomDatabaseInteractor::getInstance();

        try {
            $payload['staff_id'] = $user_id;
            $payload['ticket_id'] = $ticket_id;
            $payload['origin'] = 'system';
    
            $guid = FunctionsHelper::GUIDv4();
            $payload['id'] = $guid;
       
            $sql = AdminTicketsQueries::addMessage($payload);
            $stmt = $conn->prepare($sql);
            $stmt->execute();

            $ticket = self::getTicketInfo($ticket_id);
            ResponseHelper::sendSuccessResponse(ResponseStatus::HTTP_OK, $ticket, 'Mensagem adicionada com sucesso');
        } catch (\Exception $e) {
            ResponseHelper::sendErrorResponse(ResponseStatus::HTTP_BAD_REQUEST, null, 'Erro ao adicionar mensagem', $e->getMessage());
        }
    }

    public static function removeMessage($message_id)
    {
        $conn = CustomDatabaseInteractor::getInstance();

        try {
            $sql = AdminTicketsQueries::removeMessage($message_id);
            $stmt = $conn->prepare($sql);
            $stmt->execute();

            // $ticket = self::getTicketInfo($ticket_id);
            ResponseHelper::sendSuccessResponse(ResponseStatus::HTTP_OK, null, 'Tickets removido com sucesso');
        } catch (\Exception $e) {
            ResponseHelper::sendErrorResponse(ResponseStatus::HTTP_BAD_REQUEST, null, 'Erro ao remover ticket', $e->getMessage());
        }
    }
}
