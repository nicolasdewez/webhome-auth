<?php

use Phinx\Migration\AbstractMigration;

class Data extends AbstractMigration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        // Groups
        $data = [
            ['\'USER\'', '\'User\''],
            ['\'ADM\'', '\'Administrator\''],
            ['\'ADM_SUPER\'', '\'Super Administrator\''],
        ];

        $values = [];
        foreach ($data as $authorization) {
            array_unshift($authorization, 'nextval(\'groups_id_seq\')', 'true');
            $values[] = '('.implode(',', $authorization).')';
        }

        $this->execute('INSERT INTO groups (id, active, code, title) VALUES '.implode(',', $values));

        // Users
        $idGroup = $this->getGroupId('ADM_SUPER', 'Super Administrator');

        $data = [
            'nextval(\'users_id_seq\')',
            $idGroup,
            '\'ndewez\'',
            '\'A39NGem5FnO/IvfUEooTdWMW/htaGC9VIZt6aBluDdSiikyglfmF0i7Cb79Y+eu1OqfaIiCRMz/loS6OVUXZZQ==\'',
            '\'10ce560d6b6a50b4f9fdfd4fe40a72b8\'',
            '\'Nicolas\'',
            '\'DEWEZ\'',
            'null',
            'null',
            '\'fr\'',
            'true',
        ];

        $this->execute('INSERT INTO users (id, group_id, username, password, salt, first_name, last_name, birth_date, email, locale, active) VALUES ('.implode(',', $data).')');
    }

    /**
     * @param string $code
     * @param string $title
     *
     * @return int
     */
    private function getGroupId($code, $title)
    {
        $sql = 'SELECT id FROM groups WHERE code = \''.$code.'\' AND title = \''.$title.'\'';
        $row = $this->fetchRow($sql);

        return $row['id'];
    }
}
