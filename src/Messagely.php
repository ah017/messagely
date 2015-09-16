<?php namespace Wibleh\Messagely;

use \Illuminate\Session\Store;

/**
 * A Laravel bundle to help storage and retrieval of messages for output to users.
 */
class Messagely
{
    /**
     * The message container
     */
    public $messages = array();

    /**
     * Flash message container
     */
    protected $newFlashMessages = array();

    /**
     * The name of the flash session container
     */
    protected $flashContainerName = 'messagely';

    /**
     * The session store in which to store the message
     *
     * @var \Illuminate\Session\Store
     */
    protected $store;

    /**
     * Create a new messagely instance
     *
     * @param \Illuminate\Session\Store $store
     */
    public function __construct(Store $store)
    {
        $this->store = $store;

        $this->loadFlashedMessages();
    }

    /**
     * Sets the name of the flash container within the store.
     * Useful for having multiple instances of messagely and using flashing.
     *
     * @param $name
     */
    public function setFlashContainerName($name)
    {
        $this->flashContainerName = $name;

        $this->loadFlashedMessages();
    }

    protected function loadFlashedMessages()
    {
        $flashed = (array)$this->store->get($this->flashContainerName);

        $this->messages = array_merge($this->messages, $flashed);
    }

    /**
     * Adds one or more messages
     *
     * @param string $group The name of the group to add the message(s) to
     * @param string|string[] $messages The message(s) to add to the specified group
     * @param bool $flash Whether to flash the message(s) for the next request
     *
     * @return void
     */
    public function add($group, $messages = '', $flash = false)
    {
        $messages = (array)$messages;

        if ($flash) {
            if (!isset($this->newFlashMessages[$group]))
                $this->newFlashMessages[$group] = array();
            $this->newFlashMessages[$group] = array_merge($this->newFlashMessages[$group], $messages);
            $this->store->flash($this->flashContainerName, $this->newFlashMessages);
        } else {
            if (!isset($this->messages[$group]))
                $this->messages[$group] = array();
            $this->messages[$group] = array_merge($this->messages[$group], $messages);
        }
    }

    /**
     * Flashes to the session one or more messages
     *
     * @param    string $group
     * @param    string|string[] $messages The message(s) to flash to the session
     *
     * @return    void
     */
    public function flash($group, $messages = '')
    {
        $this->add($group, $messages, true);
    }

    /**
     * Fetches all messages for the specified group. If no group was found this
     * method will return FALSE instead of an array.
     *
     * @access    public
     * @param    string $group The name of the group you want to retrieve.
     * @return    array
     */
    public function get($group = null)
    {
        if (is_null($group)) return $this->messages;

        return isset($this->messages[$group]) ? $this->messages[$group] : array();
    }

    /**
     * Return a string of Twitter Bootstrap 3 compatible HTML to be displayed.
     *
     * @param string $group The name of the group to display
     * @return string
     */
    public function display($group = null)
    {
        $groups = array();

        if (is_null($group))
            $groups = $this->get();
        else
            $groups[$group] = $this->get($group);

        $groups = array_filter($groups);

        if (empty($groups)) return "";

        ob_start();

        ?>
        <div class="messagely"><?php

        foreach ($groups as $group => $messages) {
            ?>
            <div class="alert alert-<?php echo e($group); ?>"><?php

            foreach ($messages as $message) {
                ?><p><?php echo e($message); ?></p><?php
            }

            ?></div><?php
        }

        ?></div><?php

        return ob_get_clean();
    }
}