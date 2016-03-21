<?php

use Phinx\Migration\AbstractMigration;

class WebHomeApp extends AbstractMigration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        // Client
        $this->execute('SELECT nextval(\'client_id_seq\')');
        $data = [
            '2',
            '\'5f4iikcxri804co4cgwksccgo0sww08kc888w4wossog0k0ss4\'',
            '\'a:1:{i:0;s:33:"http://localhost:8000/app_dev.php";}\'',
            '\'4mjc8g8q8884c00owg48s00kc8ooowokcswgkk0c4wcggwossg\'',
            '\'a:2:{i:0;s:18:"authorization_code";i:1;s:5:"token";}\'',
            '\'WebHome App\''
        ];
        $this->execute('INSERT INTO client (id, random_id, redirect_uris, secret, allowed_grant_types, name) VALUES ('.implode(',', $data).')');

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
