<?php defined('SYSPATH') OR die('No direct script access.');

class Database_MySQL extends Kohana_Database_MySQL
{
    public function create_backup($name = 'autoschool')
    {
        $config = Kohana::$config->load('database.default.connection');

        $path = APPPATH.'backups/'.$name.'-'.date('d.m.Y_H:i:s').'.sql.gz';

        shell_exec(
            "mysqldump --user={$config['username']} --password={$config['password']} --host={$config['hostname']} {$config['database']} | gzip -cq9 > {$path}"
        );

        return $path;
    }
}
