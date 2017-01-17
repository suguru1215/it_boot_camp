<?php

return [
    "driver" => env("SESSION_DRIVER", "file"),
    // 1dayに設定 (24 * 60)
    "lifetime" => 1440,
    "expire_on_close" => true,
    "encrypt" => false,
    "files" => storage_path("framework/sessions"),
    "connection" => null,
    "table" => "sessions",
    "lottery" => [2, 100,],
    "cookie" => "ibc_session",
    "path" => "/",
    "domain" => null,
    "secure" => env("SESSION_SECURE", false),];
