<?php
/**
 *
 */


class extMessageModelMessage
{

    /**
     * @var data []
     */
    private $data = [];

    private $from = false;

    /**
     * Constructor
     * @param $data
     */
    public function __construct($data = false)
    {
        if (!$data) {
            $data = $this->data;
        }
        $this->setData($data);
    }

    /**
     * @return data
     */
    public function setData($data = [])
    {
        $this->data = $data;
        return $this->getData();
    }

    /**
     * @return data
     */
    public function getData() {
        return $this->data;
    }

    /**
     * Getter
     */

    public function getID() {
        return $this->data['messageID'];
    }
    public function getSubject() {
        return $this->data['messageSubject'];
    }
    public function getFrom() {
        if (!$this->from && $this->data['messageSender']) {
            $this->from = user::getUserByID($this->data['messageSender']);
        }
        return $this->from;
    }
    public function getPriority() {
        return $this->data['messagePriority'];
    }
    public function getIsRead() {
        return $this->data['messageIsRead'];
    }
    public function getTime() {
        return date('d.m.Y H:i',$this->data['messageTime']);
    }
    public function getAttachments() {
        return $this->data['messageAttachments'];
    }
    public function hasAttachments() {
        if ($this->getAttachments()) {
            return true;
        }
        return false;
    }



    public function getCollection() {
        $data =  [
            "id" => $this->getID(),
            "subject" => $this->getSubject(),
            "from" => false,
            "priority" => $this->getPriority(),
            "isRead" => $this->getIsRead(),
            "time" => $this->getTime(),
            "attachments" => $this->hasAttachments()
        ];
        if ($this->getFrom()) {
            $data["from"] = $this->getFrom()->getCollection();
        }
        return $data;
    }




}