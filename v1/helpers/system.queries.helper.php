<?php

class AdminSessionQueries
{
    public static function staffInfo($id)
    {
        $sql = "SELECT * FROM users WHERE id = '$id' AND active = 1";
        return FunctionsHelper::prepareQuery($sql);
    }

    public static function staffCheckFullInfo($requestData)
    {
        $email = $requestData['login'];

        $sql = "SELECT * FROM users WHERE email = '$email' AND active = 1";
        return FunctionsHelper::prepareQuery($sql);
    }
}

class AdminTicketsQueries {
    public static function listTickets()
    {
        $sql = "SELECT * FROM tickets ORDER BY created_at";
        return FunctionsHelper::prepareQuery($sql);
    }

    public static function singleTicket($id)
    {
        $sql = "SELECT * FROM tickets WHERE identifier = '$id'";
        return FunctionsHelper::prepareQuery($sql);
    }

    public static function loadTicketMessages($id)
    {
        $sql = "SELECT * FROM tickets_messages WHERE ticket_id = '$id' ORDER BY created_at";
        return FunctionsHelper::prepareQuery($sql);
    }

    public static function newTicket($payload) {

        $id = $payload['id'];
        $identifier = $payload['identifier'];
        $client_name = $payload['client_name'];
        $client_phone = $payload['client_phone'];
        $client_email = $payload['client_email'];
        $description = $payload['description'];

        
        $sql = "INSERT INTO `tickets` (`id`, `identifier`, `client_name`, `client_phone`, `client_email`, `description`, `status`)
        VALUES
            ('$id', '$identifier', '$client_name', '$client_phone', '$client_email', '$description', 'open');
        ";
        return FunctionsHelper::prepareQuery($sql);
    }
}
