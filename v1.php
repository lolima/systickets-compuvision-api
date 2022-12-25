<?php

// Helpers
require __DIR__ . "/v1/helpers/support.helper.php";
require __DIR__ . "/v1/helpers/response.helper.php";
require __DIR__ . "/v1/helpers/token.helper.php";

// Helpers - Queries
require __DIR__ . "/v1/helpers/system.queries.helper.php";

/**********************
 * Manager            *
 *********************/
require __DIR__ . "/v1/projects/manager/session/session.controller.php";
require __DIR__ . "/v1/projects/manager/session/session.route.php";

require __DIR__ . "/v1/projects/manager/tickets/tickets.controller.php";
require __DIR__ . "/v1/projects/manager/tickets/tickets.route.php";


/**********************
 * Public            *
 *********************/
require __DIR__ . "/v1/projects/public/public.route.php";
require __DIR__ . "/v1/projects/public/public.controller.php";