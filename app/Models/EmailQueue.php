<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailQueue extends Model {

    const STATUS_PENDING    = 1;
    const STATUS_SENT       = 10;
    const STATUS_FAILED     = 15;
    const STATUS_DELETED    = 20;

    const TYPE_BOOKING_USER_COMPLETE = 'BOOKING_USER_COMPLETE';
    const TYPE_BOOKING_USER_CANCELLED = 'BOOKING_USER_CANCELLED';
    const TYPE_BOOKING_PROPERTY_COMPLETE = 'BOOKING_PROPERTY_COMPLETE';
    const TYPE_BOOKING_PROPERTY_CANCELLED = 'BOOKING_PROPERTY_CANCELLED';
    const TYPE_BOOKING_PAYMENT_COMPLETE = 'BOOKING_PAYMENT_COMPLETE';
    const TYPE_BOOKING_PAYMENT_FAILED = 'BOOKING_PAYMENT_FAILED';    
    const TYPE_PROPERTY_CARD_INVALIDATED = 'PROPERTY_CARD_INVALIDATED';
    const TYPE_PROPERTY_CARD_EXPIRATION = 'PROPERTY_CARD_EXPIRATION';
    const TYPE_PROPERTY_CARD_MISSING = 'PROPERTY_CARD_MISSING';
    const TYPE_USER_QUERY = 'USER_QUERY';
    const TYPE_USER_QUERY_CONFIRM = 'USER_QUERY_CONFIRM';
    const TYPE_USER_VERIFY_ACCOUNT = 'USER_VERIFY_ACCOUNT';    
    const TYPE_BOOKING_INVOICE         = 'BOOKING_INVOICE';
    const TYPE_CREDIT_NOTE             = 'CREDIT_NOTE';
    const TYPE_MONTHLY_INVOICE         = 'MONTHLY_INVOICE';
    const TYPE_YEARLY_INVOICE          = 'YEARLY_INVOICE';
    const TYPE_OVERDUE_INVOICE         = 'OVERDUE_INVOICE';
    const TYPE_PASSWORD_USER_RESET = 'PASSWORD_USER_RESET';
    const TYPE_PASSWORD_USER_SET = 'PASSWORD_USER_SET';
    const TYPE_WELCOME_USER         = 'WELCOME_USER';
    
    const EMAIL_TEMPLATE_STAYBOOKED = 'STAYBOOKED';
    const EMAIL_TEMPLATE_CHICRETREATS = 'CHICRETREATS';
    
    /**
     * @var integer
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=32)
     */
    protected $type;

    /**
     * @var integer
     * @ORM\Column(type="integer")
     */
    protected $status = 1;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $emailTemplate;
    
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $email;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    protected $name;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    protected $errorMessage;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $origin;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    protected $stampCreated;

    /**
     * @var \DateTime|null
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $stampSent;

    /**
     * @var integer
     * @ORM\Column(type="integer", options={"unsigned"=true})
     */
    protected $subjectId;
    
    public $send = false;

    protected $table = 'email_queue';

    public $timestamps = false;

    /**
     * @param mixed $subject
     * @param string $type
     * @param string $email
     * @param string $origin
     */
    public function __construct($subject = null, string $type = null, string $email = null, string $origin = null)
    {
        $this->type         = $type;
        $this->email        = $email;
        $this->origin       = $origin;

        if($subject) {
            $this->subjectId    = $subject->getId();
        }
        
        $this->stampCreated = new \DateTime();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string|NULL
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return EmailQueue
     */
    public function setType(string $type): EmailQueue
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     * @return EmailQueue
     */
    public function setStatus(int $status): EmailQueue
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return string|NULL
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return EmailQueue
     */
    public function setEmail(string $email): EmailQueue
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return EmailQueue
     */
    public function setName(?string $name): EmailQueue
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }

    /**
     * @param string|null $errorMessage
     * @return EmailQueue
     */
    public function setErrorMessage(?string $errorMessage): EmailQueue
    {
        $this->errorMessage = $errorMessage;
        return $this;
    }

    /**
     * @return string
     */
    public function getOrigin(): string
    {
        return $this->origin;
    }

    /**
     * @param string $origin
     * @return EmailQueue
     */
    public function setOrigin(string $origin): EmailQueue
    {
        $this->origin = $origin;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getStampCreated(): \DateTime
    {
        return $this->stampCreated;
    }

    /**
     * @param \DateTime $stampCreated
     * @return EmailQueue
     */
    public function setStampCreated(\DateTime $stampCreated): EmailQueue
    {
        $this->stampCreated = $stampCreated;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getStampSent(): ?\DateTime
    {
        return $this->stampSent;
    }

    /**
     * @param \DateTime|null $stampSent
     * @return EmailQueue
     */
    public function setStampSent(?\DateTime $stampSent): EmailQueue
    {
        $this->stampSent = $stampSent;
        return $this;
    }

    /**
     * @return int|NULL
     */
    public function getSubjectId(): ?int
    {
        return $this->subjectId;
    }

    /**
     * @param int $subjectId
     * @return EmailQueue
     */
    public function setSubjectId(int $subjectId): EmailQueue
    {
        $this->subjectId = $subjectId;
        return $this;
    }

    /**
     * @return EmailQueue
     */
    public function setProcessed(): EmailQueue
    {
        $this->setStampSent(new \DateTime());
        $this->setStatus(EmailQueue::STATUS_SENT);

        return $this;
    }

    /**
     * @param string|null $error
     * @return EmailQueue
     */
    public function setFailed(?string $error = null): EmailQueue
    {
        $this->setErrorMessage($error);
        $this->setStatus(EmailQueue::STATUS_FAILED);
        return $this;
    }
    
    /**
     * @return string
     */
    public function getEmailTemplate(): ?string
    {
        return $this->emailTemplate;
    }

    /**
     * @param string $emailTemplate EmailQueue::EMAIL_TEMPLATE_
     */
    public function setEmailTemplate(string $emailTemplate)
    {
        $this->emailTemplate = $emailTemplate;
    }
    
}
