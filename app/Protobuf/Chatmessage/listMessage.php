<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: storage/chatmessage.proto

namespace Chatmessage;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>chatmessage.listMessage</code>
 */
class listMessage extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>repeated .chatmessage.getMessage message = 1;</code>
     */
    private $message;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type \Chatmessage\getMessage[]|\Google\Protobuf\Internal\RepeatedField $message
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Storage\Chatmessage::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>repeated .chatmessage.getMessage message = 1;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Generated from protobuf field <code>repeated .chatmessage.getMessage message = 1;</code>
     * @param \Chatmessage\getMessage[]|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setMessage($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::MESSAGE, \Chatmessage\getMessage::class);
        $this->message = $arr;

        return $this;
    }

}

