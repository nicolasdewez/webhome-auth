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
            ['\'AUTH_GRPS\'', '\'authorization.auth.groups.title\''],
            ['\'AUTH_GRPS_ADD\'', '\'authorization.auth.groups.title\''],
            ['\'AUTH_GRPS_EDIT\'', '\'authorization.auth.groups.edit\''],
            ['\'AUTH_GRPS_SHOW\'', '\'authorization.auth.groups.show\''],
            ['\'AUTH_GRPS_DEL\'', '\'authorization.auth.groups.del\''],
            ['\'AUTH_GRPS_ACTIV\'', '\'authorization.auth.groups.activ\''],
            ['\'AUTH_GRPS_AUTHZ\'', '\'authorization.auth.groups.authz.title\''],
            ['\'AUTH_GRPS_AUTHZ_EDIT\'', '\'authorization.auth.groups.authz.edit\''],
            ['\'AUTH_GRPS_AUTHZ_SHOW\'', '\'authorization.auth.groups.authz.show\''],
            ['\'AUTH_USERS\'', '\'authorization.auth.users.title\''],
            ['\'AUTH_USERS_ADD\'', '\'authorization.auth.users.title\''],
            ['\'AUTH_USERS_EDIT\'', '\'authorization.auth.users.edit\''],
            ['\'AUTH_USERS_SHOW\'', '\'authorization.auth.users.show\''],
            ['\'AUTH_USERS_DEL\'', '\'authorization.auth.users.del\''],
            ['\'AUTH_USERS_ACTIV\'', '\'authorization.auth.users.activ\''],
        ];

        $values = [];
        foreach ($data as $authorization) {
            array_unshift($authorization, 'nextval(\'authorizations_id_seq\')', $idApplication);
            $values[] = '('.implode(',', $authorization).')';
        }

        $this->execute('INSERT INTO authorizations (id, application_id, code, title) VALUES '.implode(',', $values));
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
