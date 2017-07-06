<?php
namespace Member\Model\Mail;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;

class MailService
{
    const MAIL = [
        'encoding' => 'UTF-8',
        'from'     => 'zf2-sample@example.com',
        'subject'  => '会員登録の確認',
    ];

    public function sendMail($userdata)
    {
        $message = new Message();
        $messageBody =<<<EOM
{$userdata['login_id']}様

会員登録ありがとうございます。
下のリンクにアクセスして会員登録を完了してください。
http://zf2kawano.local/member/checkPremember?login_id={$userdata["login_id"]}&link_pass={$userdata['link_pass']}

このメールに覚えがない場合はメールを削除してください。

--
会員システム

EOM;
        $message->setEncoding(self::MAIL['encoding'])
                ->addFrom(self::MAIL['from'])
                ->addTo($userdata['mail_address'])
                ->setSubject(self::MAIL['subject'])
                ->setBody($messageBody);

        $this->send($message);
    }

    private function send($message)
    {
        $transport = new SmtpTransport();
        $options   = new SmtpOptions(array(
            'host'              => 'zf2kawano_mailcatcher_1',
            'port'              => '1025',
        ));
        $transport->setOptions($options);
        $transport->send($message);
    }
}
