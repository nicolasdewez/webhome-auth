<?php

use Phinx\Migration\AbstractMigration;

class WebHomeCalendar extends AbstractMigration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        // Application
        $data = ['nextval(\'applications_id_seq\')', '\'CALD\'',  '\'WebHome Calendar\'', '\'http://localhost:8002/app_dev.php/\''];
        $this->execute('INSERT INTO applications (id, code, title, href) VALUES ('.implode(',', $data).')');

        $idApplication = $this->getApplicationId('CALD', 'WebHome Calendar');
        $data = [
            '\'CALD_JOBS\'',
            '\'CALD_JOBS_ADD\'',
            '\'CALD_JOBS_EDIT\'',
            '\'CALD_JOBS_SHOW\'',
            '\'CALD_JOBS_DEL\'',
            '\'CALD_JOBS_ACTIV\'',
            '\'CALD_NURS\'',
            '\'CALD_NURS_ADD\'',
            '\'CALD_NURS_EDIT\'',
            '\'CALD_NURS_SHOW\'',
            '\'CALD_NURS_DEL\'',
            '\'CALD_NURS_ACTIV\'',
            '\'CALD_GOOGL\'',
            '\'CALD_GOOGL_ADD\'',
            '\'CALD_GOOGL_EDIT\'',
            '\'CALD_GOOGL_SHOW\'',
            '\'CALD_GOOGL_DEL\'',
            '\'CALD_GOOGL_ACTIV\'',
            '\'CALD_CALD\'',
            '\'CALD_CALD_ADD\'',
            '\'CALD_CALD_EDIT\'',
            '\'CALD_CALD_SHOW\'',
            '\'CALD_CALD_DEL\'',
            '\'CALD_CALD_ACTIV\'',
            '\'CALD_CALD_JOBS\'',
            '\'CALD_CALD_JOBS_ADD\'',
            '\'CALD_CALD_JOBS_EDIT\'',
            '\'CALD_CALD_JOBS_SHOW\'',
            '\'CALD_CALD_JOBS_DEL\'',
            '\'CALD_CALD_NURS\'',
            '\'CALD_CALD_NURS_ADD\'',
            '\'CALD_CALD_NURS_EDIT\'',
            '\'CALD_CALD_NURS_SHOW\'',
            '\'CALD_CALD_NURS_DEL\'',
            '\'CALD_REPRT\'',
            '\'CALD_REPRT_JOB\'',
            '\'CALD_REPRT_NURS\'',
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
                        WHERE a.code LIKE \'CALD_%\'');

        $this->execute('INSERT INTO group_authorization (group_id, authorization_id)
                        SELECT g.id, a.id
                        FROM authorizations a
                        JOIN groups g ON g.code = \'ADM\'
                        WHERE a.code LIKE \'CALD_%\'');
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
