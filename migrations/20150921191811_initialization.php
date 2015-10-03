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
        $this->execute('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(30) NOT NULL, password VARCHAR(255) NOT NULL, salt VARCHAR(255) NOT NULL, role VARCHAR(100) NOT NULL, firstName VARCHAR(255) NOT NULL, lastName VARCHAR(255) NOT NULL, birthDate DATE DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, active TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;');
        $this->execute('CREATE TABLE user_authorization (user_id INT NOT NULL, authorization_id INT NOT NULL, INDEX IDX_94E326BFA76ED395 (user_id), INDEX IDX_94E326BF2F8B0EB2 (authorization_id), PRIMARY KEY(user_id, authorization_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;');
        $this->execute('CREATE TABLE authorization (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) NOT NULL, category VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;');
        $this->execute('ALTER TABLE user_authorization ADD CONSTRAINT FK_94E326BFA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE;');
        $this->execute('ALTER TABLE user_authorization ADD CONSTRAINT FK_94E326BF2F8B0EB2 FOREIGN KEY (authorization_id) REFERENCES authorization (id) ON DELETE CASCADE;');

        $this->execute('INSERT INTO authorization VALUES (0, \'USER_ALL\', \'authorization.category.user\', \'authorization.user.user_all\');');
        $this->execute('INSERT INTO authorization VALUES (0, \'ENERGY_ADDRESS_ALL\', \'authorization.category.energy\', \'authorization.energy.address_all\');');
        $this->execute('INSERT INTO authorization VALUES (0, \'ENERGY_ADDRESS_USER\', \'authorization.category.energy\', \'authorization.energy.address_user\');');
        $this->execute('INSERT INTO authorization VALUES (0, \'ENERGY_ENERGY_ALL\', \'authorization.category.energy\', \'authorization.energy.energy_all\');');
        $this->execute('INSERT INTO authorization VALUES (0, \'ENERGY_ENERGY_USER\', \'authorization.category.energy\', \'authorization.energy.energy_user\');');
        $this->execute('INSERT INTO authorization VALUES (0, \'ENERGY_YEAR_ALL\', \'authorization.category.energy\', \'authorization.energy.year_all\');');
        $this->execute('INSERT INTO authorization VALUES (0, \'ENERGY_YEAR_USER\', \'authorization.category.energy\', \'authorization.energy.year_user\');');
        $this->execute('INSERT INTO authorization VALUES (0, \'ENERGY_VEHICLE_ALL\', \'authorization.category.energy\', \'authorization.energy.vehicle_all\');');
        $this->execute('INSERT INTO authorization VALUES (0, \'ENERGY_VEHICLE_USER\', \'authorization.category.energy\', \'authorization.energy.vehicle_user\');');
    }
}
