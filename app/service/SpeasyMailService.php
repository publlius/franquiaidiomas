<?php

class SpeasyMailService
{
    /**
     * Send email
     * @param $tos array of target emails
     * @param $subject message subject
     * @param $body message body
     * @param $bodytype body type (text, html)
     */
    public static function send($tos, $subject, $body, $bodytype = 'text', $attachs = null)
    {
        TTransaction::open('permission');
        $preferences = SystemPreference::getAllPreferences();
        TTransaction::close();
        
        $mail = new TMail;
        $mail->setFrom( trim($preferences['mail_from']), APPLICATION_NAME );
        
        if (is_string($tos))
        {
            $tos = str_replace(',', ';', $tos);
            $tos = explode(';', $tos);
        }
        
        if (is_array($tos))
        {
            foreach ($tos as $to)
            {
                $mail->addAddress( $to );
            }
        }
        else
        {
            $mail->addAddress( $tos );
        }
        $mail->setSubject( $subject );
        
        if ($preferences['smtp_auth'])
        {
            $mail->SetUseSmtp();
            $mail->SetSmtpHost($preferences['smtp_host'], $preferences['smtp_port']);
            $mail->SetSmtpUser($preferences['smtp_user'], $preferences['smtp_pass']);
        }
        
        if (!empty($attachs))
        {
            foreach ($attachs as $attach)
            {
                $mail->addAttach($attach[0], (isset($attach[1]) ? $attach[1] : null));
            }
        }
        
        if ($bodytype == 'text')
        {
            $mail->setTextBody($body);
        }
        else
        {
            $mail->setHtmlBody($body);
        }
        
        $mail->send();
    }
}
