<?php
require_once('Database.php');
use MailerSend\MailerSend;
use MailerSend\Helpers\Builder\Personalization;
use MailerSend\Helpers\Builder\Recipient;
use MailerSend\Helpers\Builder\EmailParams;
require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

class Login {
    private $conn;
    private $table = 'logins';

    public function __construct()
    {
        $this->conn = (new Database())->getConnection();
    }

    public function register($email, $password) {
        
        $existing = $this->conn->select('logins', ['email' => 'eq.' . $email]);
        if (!empty($existing)) {
            return "User already exists, please use another email.";
        }
 
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $this->conn->insert('users', ['email' => $email]);
        $user_id = $this->conn->select('users', ['email' => 'eq.' . $email], ['id']);

        $data = ['user_id' => $user_id[0]['id'], 'email' => $email, 'password' => $hashed_password];
        $result = $this->conn->insert($this->table, [$data]);
    
        if (!empty($result)) {
            session_start();
            $_SESSION['email'] = $result[0]['email'];
            $_SESSION['user_id'] = $result[0]['id'];
            return true;
        }

        return "Something went wrong. Please try again.";
    }

    public function login($email, $password) {
        $user = $this->conn->select('logins', ['email' => 'eq.' . $email]);
        if (!empty($user)) {
            if (password_verify($password, $user[0]['password'])) {
                session_start();
                $_SESSION['email'] = $user[0]['email'];
                $_SESSION['user_id'] = $user[0]['id'];
                return true;
            } else {
                return 'password';
            }
            
        } else {
            return 'email';
        }
        return false;                     
    }

    public function forgot_password($email){
        $mailersend = new MailerSend(['api_key' => 'mlsn.2fd3cc92368373b94ebf22b338cc8d9d1fdb2490fae156a2f9958173f24596b8']);
        $user_data  =  $this->conn->select('logins', ['email' => 'eq.' . $email]);

        if(count($user_data) === 0) {
            return "Not existing email";
        } 

        $personalization = [
            new Personalization('maria.spam2002@gmail.com', [
                    'name' => 'Book-Store',
                    'account_name' => 'Book-Store',
                    'support_email' => 'maria.spam2002@gmail.com'
            ])
        ];
        $recipients = [
            new Recipient($user_data[0]['email'], 'Recipient'),
        ];

        $emailParams = (new EmailParams())
            ->setFrom('test-q3enl6k6pjr42vwr.mlsender.net')
            ->setFromName('Book Store')
            ->setRecipients($recipients)
            ->setSubject('Password Reset')
            ->setHtml('This is the HTML content')
            ->setText('This is the text content')
            ->setPersonalization($personalization);

        $mailersend->email->send($emailParams);

        return true;
        
    }
}