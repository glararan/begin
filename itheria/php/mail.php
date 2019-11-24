<?php
    require_once("PHPmailer/PHPMailerAutoload.php");

    class Mail
    {
        private $mailer;
        
        public function __construct()
        {
            $this->mailer = new PHPMailer;
        }

        public function createRegistrationMessage($to, $pass)
        {
            $message =  '<html><body>';
            $message .= '<div style="border-radius: 3px;height: 70px;text-align: center;padding-top: 40px;width: 600px;background-color:#67bd3c ;text-transform: uppercase;font-family: arial;font-weight: bold;font-size: 20px;color: #fff;">Vítejte v naší aplikaci</div>';
            $message .= '<div style="height: 500px;width: 560px;padding-left: 20px;padding-right: 20px;padding-left: 20px;">';
            $message .= '<h1 style="font-family: arial;font-weight: bold;font-size: 20px;color: #2f2e31;margin-top: 40px;margin-bottom: 40px;">Registrace dokončena</h1>';
            $message .= '<p style="width: 100%;margin-bottom: 40px;font-family: arial;font-weight: 500;">Děkujeme za registaci v naší aplikaci.</p>';
            $message .= '<div style="font-family: arial;width: calc(100% - 20px);height: 30px;background-color:#67bd3c ;color: #fff;border-top-left-radius: 5px;border-top-right-radius: 5px;padding-top: 10px;padding-left: 20px;font-size: 15px;font-weight: bold;">Přihlašovací údaje:</div>';
            $message .= '<table style="font-family: arial;width: 100%;background-color: #fff;border: 1px solid #67bd3c; border-bottom-left-radius: 5px;border-bottom-right-radius: 5px;">';
            $message .= '<tr style="height: 40px;font-size: 15px;"><td style="font-family:Arial;font-weight: 700; padding-left: 20px;">Email:</td><td>'.$to.'</td></tr>';
            $message .= '<tr style="height: 40px;font-size: 15px;"><td style="font-family:Arial;font-weight: 700; padding-left: 20px;">Heslo:</td><td>'.$pass.'</td></tr>';
            $message .= '</table>';
            $message .= '<p style="font-family: arial;margin:20px 0px;font-size: 11px;">Pokud máte k registraci jakékoliv další dotazy, kontaktujte prosím <a href="mailto:info@jakmluvim.cz">info@jakmluvim.cz</a></p>';
            $message .= '<div style="width: 100%;height: 40px;margin-top: 40px;">';
            //$message .= '<a style="padding: 10px 40px;margin-left: 40%;background-color: #dbdbdb;text-decoration: none;font-size: 15px;width: 80px;border-radius: 5px;border: none;box-shadow: none !important;text-align: center; color: #5f6f7e;" href="#">Potvrdit</a>';
            $message .= '</div>';
            $message .= '</div>';
            $message .= '</body></html>';

            $this->sendMail($to, "Informace o registraci", $message);
        }
        
        public function createResetPasswordMessage($to, $pass)
        {
            $message =  '<html><body>';
            $message .= '<div style="border-radius: 3px;height: 70px;text-align: center;padding-top: 40px;width: 600px;background-color:#67bd3c ;text-transform: uppercase;font-family: arial;font-weight: bold;font-size: 20px;color: #fff;">Bylo provedeno resetování hesla</div>';
            $message .= '<div style="height: 500px;width: 560px;padding-left: 20px;padding-right: 20px;padding-left: 20px;">';
            $message .= '<h1 style="font-family: arial;font-weight: bold;font-size: 20px;color: #2f2e31;margin-top: 40px;margin-bottom: 40px;">Nové heslo</h1>';
            $message .= '<p style="width: 100%;margin-bottom: 40px;font-family: arial;font-weight: 500;">V případě, že jste o reset nepožádali, kontaktujte administrátora.</p>';
            $message .= '<div style="font-family: arial;width: calc(100% - 20px);height: 30px;background-color:#67bd3c ;color: #fff;border-top-left-radius: 5px;border-top-right-radius: 5px;padding-top: 10px;padding-left: 20px;font-size: 15px;font-weight: bold;">Přihlašovací údaje:</div>';
            $message .= '<table style="font-family: arial;width: 100%;background-color: #fff;border: 1px solid #67bd3c; border-bottom-left-radius: 5px;border-bottom-right-radius: 5px;">';
            $message .= '<tr style="height: 40px;font-size: 15px;"><td style="font-family:Arial;font-weight: 700; padding-left: 20px;">Email:</td><td>'.$to.'</td></tr>';
            $message .= '<tr style="height: 40px;font-size: 15px;"><td style="font-family:Arial;font-weight: 700; padding-left: 20px;">Heslo:</td><td>'.$pass.'</td></tr>';
            $message .= '</table>';
            $message .= '<div style="width: 100%;height: 40px;margin-top: 40px;">';
            //$message .= '<a style="padding: 10px 40px;margin-left: 40%;background-color: #dbdbdb;text-decoration: none;font-size: 15px;width: 80px;border-radius: 5px;border: none;box-shadow: none !important;text-align: center; color: #5f6f7e;" href="#">Potvrdit</a>';
            $message .= '</div>';
            $message .= '</div>';
            $message .= '</body></html>';

            $this->sendMail($to, "Reset hesla", $message);
        }
        
        public function createExerciseMessage($to, $user)
        {
            $message =  '<html><body>';
            $message .= '<div style="border-radius: 3px;height: 70px;text-align: center;padding-top: 40px;width: 600px;background-color:#67bd3c ;text-transform: uppercase;font-family: arial;font-weight: bold;font-size: 20px;color: #fff;">Upozornění</div>';
            $message .= '<div style="height: 500px;width: 560px;padding-left: 20px;padding-right: 20px;padding-left: 20px;">';
            $message .= '<h1 style="font-family: arial;font-weight: bold;font-size: 20px;color: #2f2e31;margin-top: 40px;margin-bottom: 40px;">Uživatel vyplnil úvodní zadání</h1>';
            $message .= '<p style="width: 100%;margin-bottom: 40px;font-family: arial;font-weight: 500;">Je k dispozici cvičení ke zkontrolování.</p>';
            $message .= '<div style="font-family: arial;width: calc(100% - 20px);height: 30px;background-color:#67bd3c ;color: #fff;border-top-left-radius: 5px;border-top-right-radius: 5px;padding-top: 10px;padding-left: 20px;font-size: 15px;font-weight: bold;">Údaje uživatele:</div>';
            $message .= '<table style="font-family: arial;width: 100%;background-color: #fff;border: 1px solid #67bd3c; border-bottom-left-radius: 5px;border-bottom-right-radius: 5px;">';
            $message .= '<tr style="height: 40px;font-size: 15px;"><td style="font-family:Arial;font-weight: 700; padding-left: 20px;">Email:</td><td>'.$user.'</td></tr>';
            $message .= '</table>';
            $message .= '<p style="font-family: arial;margin:20px 0px;font-size: 11px;">Pokud máte jakékoliv další dotazy, kontaktujte <b> administrátora </b> aplikace.</p>';
            $message .= '<div style="width: 100%;height: 40px;margin-top: 40px;">';
            $message .= '</div>';
            $message .= '</div>';
            $message .= '</body></html>';
            
            $this->sendMail($to, "Uživatel odevzdal cvičení", $message);
        }
        
        public function createClientFreeMessage($to, $who)
        {
            $message =  '<html><body>';
            $message .= '<div style="border-radius: 3px;height: 70px;text-align: center;padding-top: 40px;width: 600px;background-color:#67bd3c ;text-transform: uppercase;font-family: arial;font-weight: bold;font-size: 20px;color: #fff;">Upozornění</div>';
            $message .= '<div style="height: 500px;width: 560px;padding-left: 20px;padding-right: 20px;padding-left: 20px;">';
            $message .= '<h1 style="font-family: arial;font-weight: bold;font-size: 20px;color: #2f2e31;margin-top: 40px;margin-bottom: 40px;">Uživatel je již delší dobu bez lektora</h1>';
            $message .= '<p style="width: 100%;margin-bottom: 40px;font-family: arial;font-weight: 500;">Uživatel aplikace ,,Jak mluvím ? ", vyplnil úvodní cvičení a je již delší dobu bez lektora.</p>';
            $message .= '<div style="font-family: arial;width: calc(100% - 20px);height: 30px;background-color:#67bd3c ;color: #fff;border-top-left-radius: 5px;border-top-right-radius: 5px;padding-top: 10px;padding-left: 20px;font-size: 15px;font-weight: bold;">Údaje uživatele:</div>';
            $message .= '<table style="font-family: arial;width: 100%;background-color: #fff;border: 1px solid #67bd3c; border-bottom-left-radius: 5px;border-bottom-right-radius: 5px;">';
            $message .= '<tr style="height: 40px;font-size: 15px;"><td style="font-family:Arial;font-weight: 700; padding-left: 20px;">Email:</td><td>'.$who.'</td></tr>';
            $message .= '</table>';
            $message .= '<p style="font-family: arial;margin:20px 0px;font-size: 11px;">Pokud máte jakékoliv další dotazy, kontaktujte <b> administrátora </b> aplikace.</p>';
            $message .= '<div style="width: 100%;height: 40px;margin-top: 40px;">';
            $message .= '</div>';
            $message .= '</div>';
            $message .= '</body></html>';

            $this->sendMail($to, "Upozornění - uživatel nemá lektora", $message);
        }

        public function sendMail($to, $subject, $messageCont)
        {
            $this->mailer->isSMTP();
            $this->mailer->Host     = 'smtp.manilotmedia.cz';  
            $this->mailer->SMTPAuth = true;                           
            $this->mailer->Username = 'info@manilotmedia.cz';               
            $this->mailer->Password = 'kofolaM1';                        

            $this->mailer->From     = MAIL_SENDER;
            $this->mailer->FromName = MAIL_NAME;
            
            $this->mailer->addAddress($to); // Name is optional
            $this->mailer->isHTML(true);                                 

            $this->mailer->Subject = $subject;
            $this->mailer->Body    = $messageCont;
            //$this->mailer->AltBody = 'This is the body in plain text for non-HTML mail clients';
            $this->mailer->CharSet = "UTF-8";
            
            $this->mailer->send();
        }
    }
?>