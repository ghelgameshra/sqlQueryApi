<?php

namespace App\Http\Controllers\Database;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class ConnectionController extends Controller
{
    private $mysqlHost;
    private $username;
    private $password;

    public function __construct($mysqlHost)
    {

        $auth = DB::table('auth_toko')->whereBetween('periode', [now()->startOfMonth(), now()])->first();

        $this->mysqlHost = $mysqlHost;
        $this->username = $auth->username;
        $this->password = $auth->password;
    }

    public function getConnection(): Object
    {
        Config::set('database.connections.mysql_toko.host', $this->mysqlHost);
        Config::set('database.connections.mysql_toko.username', $this->username);
        Config::set('database.connections.mysql_toko.password', $this->password);

        $connection = DB::connection('mysql_toko');
        return $connection;
    }
}
