<?php
namespace App\Models;
use System\Model;
class LoginModel extends Model
{
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * User Data
     *
     * @var mixed
     */
    private $user;

    /**
     * Logged In User
     *
     * @var \stdClass
     */

    /**
     * Determine if the given login data is valid (Determine if the login data matches data in the database)
     *
     * @param string $email
     * @param string $password
     * @return bool
     */
    public function isValidLogin($email, $password) {//Determine if the login data matches data in the database
        $user = $this->where('email=?' , $email)->fetch($this->table);//This function calls the __call() function in Model.php (which enable us to search the Database.php Class)
        //pre($user);
        if (! $user) {
            return false;
        }
        //pre($user);
        $this->user = $user;
        //pre($this->user);
        //pre($this->user->code);
        //pre($user);
        //echo password_verify($password, $user->password);
        return password_verify($password, $user->password);//The first argument is the entered password, the second one is the hashed password
    }

    /**
     * Get Logged In User data
     *
     * @return \stdClass
     */
    public function user()
    {
        //pre($this->user);
        return $this->user;
    }

    /**
     * Determine whether the user is logged in
     *
     * @return bool
     */
    public function isLogged() {
        /*if ($this->cookie->has('login')) {
            //return true;
            $user = $this->where('code=?' , $this->cookie->get('login'))->fetch($this->table);
        } elseif ($this->session->has('login')) {
            //return true;
            $user = $this->where('code=?' , $this->session->get('login'))->fetch($this->table);
        }*/
        if ($this->cookie->has('login')) {
            $code = $this->cookie->get('login');
            //$code = ''; // just for now
        } elseif ($this->session->has('login')) {
            $code = $this->session->get('login');
        } else {
            $code = '';
        }
        $user = $this->where('code=?' , $code)->fetch($this->table);
        if (!$user) {
            return false;
        }
        $this->user = $user;
        return true;
    }
}