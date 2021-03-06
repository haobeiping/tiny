<?php

namespace App\Widgets;


use App\Support\Widget\AbstractWidget;
use Illuminate\Contracts\Session\Session;

class Alert extends AbstractWidget
{
    const ALERT_KEY = 'ALERT_FLASH';

    /**
     * @var $session Session
     */
    private $session;

    private $isCurrentSession = false;

    public function __construct(Session $session, $config)
    {
        parent::__construct($config);
        $this->session = $session;
    }

    public function hasMessage()
    {
        return $this->session->has(static::ALERT_KEY);
    }

    public function keepMessage()
    {
        $this->session->keep(static::ALERT_KEY);
    }

    public function setMessage($type, $message, $hasCloseButton = null, $needContainer = null)
    {
        if (!in_array($type, config('tiny.alert.allow_type_list'))) {
            $type = $this->config['default_type'];
        }
        if (is_null($hasCloseButton)) {
            $hasCloseButton = $this->config['default_has_button'];
        }
        if (is_null($needContainer)) {
            $needContainer = $this->config['default_need_container'];
        }
        $this->isCurrentSession = true;
        $this->session->flash(
            static::ALERT_KEY, [
                'type' => $type,
                'message' => $message,
                'hasCloseButton' => (boolean) $hasCloseButton,
                'needContainer' => (boolean) $needContainer
            ]
        );
    }

    public function setInfo($message)
    {
        $this->setMessage('info', $message);
    }

    public function setSuccess($message)
    {
        $this->setMessage('success', $message);
    }

    public function setWarning($message)
    {
        $this->setMessage('warning', $message);
    }

    public function setDanger($message)
    {
        $this->setMessage('danger', $message);
    }

    public function getData(array $params)
    {
        if ($this->session->has(static::ALERT_KEY)) {
            if ($this->isCurrentSession)
                return $this->session->pull(static::ALERT_KEY);
            else
                return $this->session->get(static::ALERT_KEY);

        } else {
            return [];
        }
    }

    public function render(array $params)
    {
        if ($this->hasMessage()) {
            return parent::render($params);
        } else {
            return '';
        }
    }
}