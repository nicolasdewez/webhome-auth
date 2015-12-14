<?php

use Phinx\Migration\AbstractMigration;

class WebHomeAuth extends AbstractMigration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        // Application
        $data = ['nextval(\'applications_id_seq\')', '\'AUTH\'',  '\'WebHome Auth\'', '\'http://localhost:8001/app_dev.php/\''];
        $this->execute('INSERT INTO applications (id, code, title, href) VALUES ('.implode(',', $data).')');

        $idApplication = $this->getApplicationId('AUTH', 'WebHome Auth');
        $data = [
            '\'AUTH_GRPS\'',
            '\'AUTH_GRPS_ADD\'',
            '\'AUTH_GRPS_EDIT\'',
            '\'AUTH_GRPS_SHOW\'',
            '\'AUTH_GRPS_DEL\'',
            '\'AUTH_GRPS_ACTIV\'',
            '\'AUTH_GRPS_AUTHZ\'',
            '\'AUTH_GRPS_AUTHZ_EDIT\'',
            '\'AUTH_GRPS_AUTHZ_SHOW\'',
            '\'AUTH_USERS\'',
            '\'AUTH_USERS_ADD\'',
            '\'AUTH_USERS_EDIT\'',
            '\'AUTH_USERS_SHOW\'',
            '\'AUTH_USERS_DEL\'',
            '\'AUTH_USERS_ACTIV\'',
            '\'AUTH_FOPWD\'',
            '\'AUTH_FOPWD_SHOW\'',
            '\'AUTH_FOPWD_DEL\'',
        ];

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
                        JOIN groups g ON g.code = \'ADM_SUPER\'
                        WHERE a.code LIKE \'AUTH_%\'');

        $this->execute('INSERT INTO group_authorization (group_id, authorization_id)
                        SELECT g.id, a.id
                        FROM authorizations a
                        JOIN groups g ON g.code = \'ADM\'
                        WHERE a.code LIKE \'AUTH_%\'');
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
