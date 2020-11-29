<?php


    include_once "./swiftmailer/vendor/autoload.php";
    function sendEmail($to, $subject, $body){

        try{
            $transport = (new Swift_SmtpTransport('smtp.gmail.com', 587, 'tls'))
                // ->setUsername('sgujarathi17699@gmail.com')
                // ->setPassword('zuwyxwhfqtcuyibb')
                // for boompanda
                ->setUsername('admboompanda@gmail.com')
                ->setPassword('ezdgwpuplsugrvtg')
            ;
            $mailer = new Swift_Mailer($transport);
            $message = (new Swift_Message($subject))
                ->setFrom(['sgujarathi17699@gmail.com' => 'Boompanda'])
                ->setTo([$to])
                ->setBody($body, 'text/html')
            ;
            $result = $mailer->send($message); 
            if($result)
                return true;
            else
                return false;
        }catch(Exception $e){
            // $response['serverErr'] = $e->getMessage();
            return false;
        }

    }

?>