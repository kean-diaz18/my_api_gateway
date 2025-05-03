Create database apigateway_db;

use apigateway_db;

CREATE TABLE api_keys (
    api_key VARCHAR(64) PRIMARY KEY,
    user_name VARCHAR(100) NOT NULL
);

CREATE TABLE rate_limits (
    api_key VARCHAR(64) PRIMARY KEY,
    last_request_ts INT NOT NULL,
    request_count INT NOT NULL
);

INSERT INTO api_keys (api_key, user_name) VALUES ('key123', 'UserA'), ('key456', 'UserB');
