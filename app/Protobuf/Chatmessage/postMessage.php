<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: chatmessage.proto

namespace Chatmessage;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>chatmessage.postMessage</code>
 */
class postMessage extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>uint32 user_id = 1;</code>
     */
    protected $user_id = 0;
    /**
     * Generated from protobuf field <code>string message = 2;</code>
     */
    protected $message = '';
    /**
     * Generated from protobuf field <code>uint32 target_user_id = 3;</code>
     */
    protected $target_user_id = 0;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type int $user_id
     *     @type string $message
     *     @type int $target_user_id
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Chatmessage::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>uint32 user_id = 1;</code>
     * @return int
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Generated from protobuf field <code>uint32 user_id = 1;</code>
     * @param int $var
     * @return $this
     */
    public function setUserId($var)
    {
        GPBUtil::checkUint32($var);
        $this->user_id = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string message = 2;</code>
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Generated from protobuf field <code>string message = 2;</code>
     * @param string $var
     * @return $this
     */
    public function setMessage($var)
    {
        GPBUtil::checkString($var, True);
        $this->message = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>uint32 target_user_id = 3;</code>
     * @return int
     */
    public function getTargetUserId()
    {
        return $this->target_user_id;
    }

    /**
     * Generated from protobuf field <code>uint32 target_user_id = 3;</code>
     * @param int $var
     * @return $this
     */
    public function setTargetUserId($var)
    {
        GPBUtil::checkUint32($var);
        $this->target_user_id = $var;

        return $this;
    }

}

