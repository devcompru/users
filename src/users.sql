CREATE TABLE {table_name}
    (
    id INT(255) AUTO_INCREMENT PRIMARY KEY,
    GUID VARCHAR(128) NOT NULL,
    access_key VARCHAR (128),
    username VARCHAR (128) NOT NULL,
    pass_hash VARCHAR (128) NOT NULL,
    reg_user_ip VARCHAR (20) NOT NULL,
    oauth_token VARCHAR (255) ,
    oauth_id VARCHAR (128),
    oauth_type VARCHAR (20),
    ban INT(1) DEFAULT 0,
    password_reset VARCHAR (128),
    created_at INT(32) NOT NULL,
    updated_at INT(32)
    )
