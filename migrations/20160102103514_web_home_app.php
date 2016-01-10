<?php

use Phinx\Migration\AbstractMigration;

class WebHomeApp extends AbstractMigration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        // Application
        $data = ['nextval(\'applications_id_seq\')', '\'APP\'',  '\'WebHome\'', '\'http://localhost:8000/app_dev.php/\''];
        $this->execute('INSERT INTO applications (id, code, title, href) VALUES ('.implode(',', $data).')');

        $idApplication = $this->getApplicationId('APP', 'WebHome');
        $data = ['\'APP\''];

        // Authorizations
        $values = [];
        foreach ($data as $code) {
            $authorization = [$code];
            array_unshift($authorization, 'nextval(\'authorizations_id_seq\')', $idApplication);
            $values[] = '('.implode(',', $authorization).')';
        }

        $this->execute('INSERT INTO authorizations (id, application_id, code) VALUES '.implode(',', $values));


        // Groups <-> Authorizations
        $this->execute('INSERT INTO group_authorization (group_id, authorization_id)
                        SELECT g.id, a.id
                        FROM authorizations a
                        JOIN groups g ON g.id > 0
                        WHERE a.code = \'APP\'');
    }

    /**
     * @param string $code
     * @param string $title
     *
     * @return int
     */
    private function getApplicationId($code, $title)
    {
        $sql = 'SELECT id FROM applications WHERE code = \''.$code.'\' AND title = \''.$title.'\'';
        $row = $this->fetchRow($sql);

        return $row['id'];
    }
}
