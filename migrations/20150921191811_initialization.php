<?php

use Phinx\Migration\AbstractMigration;

/**
 * Class Initialization.
 */
class Initialization extends AbstractMigration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->execute('CREATE SEQUENCE users_id_seq INCREMENT BY 1 MINVALUE 1 START 1;');
        $this->execute('CREATE SEQUENCE groups_id_seq INCREMENT BY 1 MINVALUE 1 START 1;');
        $this->execute('CREATE SEQUENCE authorizations_id_seq INCREMENT BY 1 MINVALUE 1 START 1;');
        $this->execute('CREATE SEQUENCE applications_id_seq INCREMENT BY 1 MINVALUE 1 START 1;');
        $this->execute('CREATE SEQUENCE refresh_token_id_seq INCREMENT BY 1 MINVALUE 1 START 1;');
        $this->execute('CREATE SEQUENCE access_token_id_seq INCREMENT BY 1 MINVALUE 1 START 1;');
        $this->execute('CREATE SEQUENCE auth_code_id_seq INCREMENT BY 1 MINVALUE 1 START 1;');
        $this->execute('CREATE SEQUENCE client_id_seq INCREMENT BY 1 MINVALUE 1 START 1;');
        $this->execute('CREATE TABLE users (id INT NOT NULL, group_id INT DEFAULT NULL, username VARCHAR(30) NOT NULL, password VARCHAR(255) NOT NULL, salt VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, birth_date DATE DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, locale VARCHAR(3) NOT NULL, active BOOLEAN NOT NULL, PRIMARY KEY(id));');
        $this->execute('CREATE INDEX IDX_1483A5E9FE54D947 ON users (group_id);');
        $this->execute('CREATE TABLE groups (id INT NOT NULL, code VARCHAR(20) NOT NULL, title VARCHAR(255) NOT NULL, active BOOLEAN NOT NULL, PRIMARY KEY(id));');
        $this->execute('CREATE TABLE group_authorization (group_id INT NOT NULL, authorization_id INT NOT NULL, PRIMARY KEY(group_id, authorization_id));');
        $this->execute('CREATE INDEX IDX_258445ADFE54D947 ON group_authorization (group_id);');
        $this->execute('CREATE INDEX IDX_258445AD2F8B0EB2 ON group_authorization (authorization_id);');
        $this->execute('CREATE TABLE authorizations (id INT NOT NULL, application_id INT DEFAULT NULL, code VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id));');
        $this->execute('CREATE INDEX IDX_2BC15D693E030ACD ON authorizations (application_id);');
        $this->execute('CREATE TABLE applications (id INT NOT NULL, code VARCHAR(5) NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id));');
        $this->execute('CREATE TABLE refresh_token (id INT NOT NULL, client_id INT NOT NULL, user_id INT DEFAULT NULL, token VARCHAR(255) NOT NULL, expires_at INT DEFAULT NULL, scope VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id));');
        $this->execute('CREATE UNIQUE INDEX UNIQ_C74F21955F37A13B ON refresh_token (token);');
        $this->execute('CREATE INDEX IDX_C74F219519EB6921 ON refresh_token (client_id);');
        $this->execute('CREATE INDEX IDX_C74F2195A76ED395 ON refresh_token (user_id);');
        $this->execute('CREATE TABLE access_token (id INT NOT NULL, client_id INT NOT NULL, user_id INT DEFAULT NULL, token VARCHAR(255) NOT NULL, expires_at INT DEFAULT NULL, scope VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id));');
        $this->execute('CREATE UNIQUE INDEX UNIQ_B6A2DD685F37A13B ON access_token (token);');
        $this->execute('CREATE INDEX IDX_B6A2DD6819EB6921 ON access_token (client_id);');
        $this->execute('CREATE INDEX IDX_B6A2DD68A76ED395 ON access_token (user_id);');
        $this->execute('CREATE TABLE auth_code (id INT NOT NULL, client_id INT NOT NULL, user_id INT DEFAULT NULL, token VARCHAR(255) NOT NULL, redirect_uri TEXT NOT NULL, expires_at INT DEFAULT NULL, scope VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id));');
        $this->execute('CREATE UNIQUE INDEX UNIQ_5933D02C5F37A13B ON auth_code (token);');
        $this->execute('CREATE INDEX IDX_5933D02C19EB6921 ON auth_code (client_id);');
        $this->execute('CREATE INDEX IDX_5933D02CA76ED395 ON auth_code (user_id);');
        $this->execute('CREATE TABLE client (id INT NOT NULL, random_id VARCHAR(255) NOT NULL, redirect_uris TEXT NOT NULL, secret VARCHAR(255) NOT NULL, allowed_grant_types TEXT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id));');
        $this->execute('COMMENT ON COLUMN client.redirect_uris IS \'(DC2Type:array)\';');
        $this->execute('COMMENT ON COLUMN client.allowed_grant_types IS \'(DC2Type:array)\';');
        $this->execute('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9FE54D947 FOREIGN KEY (group_id) REFERENCES groups (id) NOT DEFERRABLE INITIALLY IMMEDIATE;');
        $this->execute('ALTER TABLE group_authorization ADD CONSTRAINT FK_258445ADFE54D947 FOREIGN KEY (group_id) REFERENCES groups (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE;');
        $this->execute('ALTER TABLE group_authorization ADD CONSTRAINT FK_258445AD2F8B0EB2 FOREIGN KEY (authorization_id) REFERENCES authorizations (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE;');
        $this->execute('ALTER TABLE authorizations ADD CONSTRAINT FK_2BC15D693E030ACD FOREIGN KEY (application_id) REFERENCES applications (id) NOT DEFERRABLE INITIALLY IMMEDIATE;');
        $this->execute('ALTER TABLE refresh_token ADD CONSTRAINT FK_C74F219519EB6921 FOREIGN KEY (client_id) REFERENCES client (id) NOT DEFERRABLE INITIALLY IMMEDIATE;');
        $this->execute('ALTER TABLE refresh_token ADD CONSTRAINT FK_C74F2195A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE;');
        $this->execute('ALTER TABLE access_token ADD CONSTRAINT FK_B6A2DD6819EB6921 FOREIGN KEY (client_id) REFERENCES client (id) NOT DEFERRABLE INITIALLY IMMEDIATE;');
        $this->execute('ALTER TABLE access_token ADD CONSTRAINT FK_B6A2DD68A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE;');
        $this->execute('ALTER TABLE auth_code ADD CONSTRAINT FK_5933D02C19EB6921 FOREIGN KEY (client_id) REFERENCES client (id) NOT DEFERRABLE INITIALLY IMMEDIATE;');
        $this->execute('ALTER TABLE auth_code ADD CONSTRAINT FK_5933D02CA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE;');
    }
}
