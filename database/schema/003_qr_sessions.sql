USE healthy_bite;

CREATE TABLE IF NOT EXISTS qr_sessions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    qr_token_id BIGINT UNSIGNED NOT NULL,
    session_identifier VARCHAR(255) NOT NULL,
    started_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    last_seen_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY qr_sessions_token_session_unique (qr_token_id, session_identifier),
    KEY qr_sessions_token_id_index (qr_token_id),
    KEY qr_sessions_identifier_index (session_identifier),
    CONSTRAINT qr_sessions_token_id_foreign FOREIGN KEY (qr_token_id) REFERENCES qr_tokens (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
