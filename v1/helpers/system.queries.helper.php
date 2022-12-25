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
        $sql = "SELECT * FROM tickets WHERE identifier = '$id' OR id = '$id'";
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

    public static function editTicket($payload) {

        $id = $payload['id'];
        $identifier = $payload['identifier'];
        $client_name = $payload['client_name'];
        $client_phone = $payload['client_phone'];
        $client_email = $payload['client_email'];
        $description = $payload['description'];
        $status = $payload['status'];

        
        $sql = "UPDATE `tickets` SET 
        `client_name` = '$client_name',
        `client_phone` = '$client_phone',
        `client_email` = '$client_email',
        `description` = '$description',
        `status` = '$status'
        WHERE `id` = '$id';";
        return FunctionsHelper::prepareQuery($sql);
    }

    public static function addMessage($payload) {

        $id = $payload['id'];
        $ticket_id = $payload['ticket_id'];
        $origin = $payload['origin'];
        $message = $payload['message'];
        $staff_id = $payload['staff_id'] ? $payload['staff_id'] : null;

        if($staff_id) {
            $sql = "INSERT INTO `tickets_messages` (`id`, `ticket_id`, `origin`, `staff_id`, `message`)
            VALUES
                ('$id', '$ticket_id', '$origin', '$staff_id', '$message');
            ";
        } else {
            $sql = "INSERT INTO `tickets_messages` (`id`, `ticket_id`, `origin`, `staff_id`, `message`)
            VALUES
                ('$id', '$ticket_id', '$origin', NULL, '$message');
            ";
        }
    
        return FunctionsHelper::prepareQuery($sql);
    }

    public static function removeMessage($id)
    {
        $sql = "DELETE FROM tickets_messages WHERE id = '$id'";
        return FunctionsHelper::prepareQuery($sql);
    }
}
