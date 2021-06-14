<?php
namespace SiteBundle\Service;

use Doctrine\ORM\EntityManager;
use SiteBundle\Entity\MailTemplate;
use SiteBundle\Entity\Settings;

class MailTemplateService
{
    /**
     *
     * @var EntityManager
     */
    protected $em;
    protected $user_email_adress;
    protected $mailer;

    public function __construct(EntityManager $em, $user_email_adress, \Swift_Mailer $mailer)
    {
        $this->em = $em;
        $this->user_email_adress = $user_email_adress;
        $this->mailer = $mailer;
    }

    /**
     * @param string $alias_template
     * @param array $emails_to
     * @param array $variables
     * @param array $bcc
     * @param array $attachments
     * @return bool|int
     * @throws \Doctrine\ORM\ORMException
     */
    public function sendTemplateMail(
        string $alias_template,
        array $emails_to = [],
        array $variables = [],
        array $bcc = [],
        array $attachments = []
    )
    {
        $template = $this->em->getRepository(MailTemplate::class)->findOneBy(['alias' => $alias_template]);
        $admin_email = $this->em->getRepository(Settings::class)->getSetting('adminemail');
        if ($template) {
            $body = $this->setVariables($template->getText(), $variables);
            $subject = $this->setVariables($template->getSubject(), $variables);
            if ($template->getEmail()) {
                $emails_to = array_merge($emails_to, array_map(function ($email) {
                    return trim($email);
                }, explode(',', $template->getEmail())));
            }
            if ($admin_email) {
                $bcc = explode(',', $admin_email);
            }
            $message = (new \Swift_Message($subject, $body, "text/html"))
                ->setFrom($this->user_email_adress)
                ->setTo($emails_to);
            if ($bcc) {
                $message->setBcc($bcc);
            }
            foreach ($attachments as $attach ) {
                $message->attach(\Swift_Attachment::fromPath($attach));
            }

            return $this->mailer->send($message);
        }
        return false;
    }

    /**
     * Замена переменных в тексте
     *
     * @param $text
     * @param array $datas
     * @return mixed
     */
    protected function setVariables($text, $datas)
    {
        $parents = [];
        $replaces = [];
        foreach ($datas as $key => $value) {
            $parents[] = '/[$][$]' . strtoupper($key) . '[$][$]/';
            $replaces[] = $value;
        }
        return preg_replace($parents, $replaces, $text);
    }
}