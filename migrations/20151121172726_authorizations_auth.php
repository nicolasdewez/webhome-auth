<?php

use Phinx\Migration\AbstractMigration;

class AuthorizationsAuth extends AbstractMigration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $data = ['nextval(\'applications_id_seq\')', '\'AUTH\'',  '\'WebHome Auth\''];
        $this->execute('INSERT INTO applications (id, code, title) VALUES ('.implode(',', $data).')');

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

        $values = [];
        foreach ($data as $code) {
            $authorization = [$code];
            array_unshift($authorization, 'nextval(\'authorizations_id_seq\')', $idApplication);
            $values[] = '('.implode(',', $authorization).')';
        }

        $this->execute('INSERT INTO authorizations (id, application_id, code) VALUES '.implode(',', $values));
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
